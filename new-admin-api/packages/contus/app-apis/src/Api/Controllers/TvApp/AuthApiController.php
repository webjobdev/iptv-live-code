<?php

namespace Contus\AppApi\Api\Controllers\TvApp;

use Contus\Base\ApiController;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends ApiController
{

    // user register api
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     $userExists = User::where('email', $request->email)->first();

    //     if ($userExists) {
    //         return $this->getErrorJsonResponse([], 'Email is already taken!');
    //     }

    //     $newUser = new User();
    //     $newUser->email = $request->email;
    //     $newUser->password = Hash::make($request->password);
    //     $newUser->save();

    //     if ($newUser) {
    //         $res['token'] = JWTAuth::fromUser($newUser);
    //         $res['user'] = $newUser;

    //         return response()->json([
    //             'token' => $res['token'],
    //             'error' => 'false',
    //             'data' => $res['user'],
    //             'message' => 'User Registered Succefully.'
    //         ], 200);
    //     } else {
    //         return $this->getErrorJsonResponse([], 'Registration Failed.', 401);
    //     }
    // }


    // verify provider id api

    public function checkProviderId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $organization = OrganizationDetail::where('provider_id', $request->provider_id)->first();

        if (!$organization) {
            return response()->json([
                "error" => true,
                "message" => "Provider Id not found",
            ], 404);
        }

        return response()->json([
            "error" => false,
            "status" => "success",
            "data" => $organization,
            "message" => "Provider Id Verified Succeddfully.",
        ], 200);
    }

    public function postLogin(Request $request)
    {
        $providerId = $request->header('provider-id');
        if (!$providerId) {
            return response()->json([
                'success' => false,
                'message' => 'Provider Id is required in header'
            ], 400);
        }

        $provider = OrganizationDetail::where('provider_id', $providerId)->first();
        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Provider Id'
            ], 404);
        }


        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Fetch user record from User model
        $user = OrgSubscribers::where('email', $request->email)->first();

        // If user not found
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $orgSubscription = OrgSubscriberAndPayment::where('subscriber_id', $user->id)
            ->whereIn('product_type', ['custom subscription', 'subscription sets', 'free subscription'])
            ->where('is_active', '1')
            ->first();
        if ($orgSubscription) {
            $user['subscription_detail'] = [
                'subsriber_id' => $orgSubscription->id,
                'plan_name' => in_array($orgSubscription->product_type, ['custom subscription', 'subscription sets', 'free subscription']) ? $orgSubscription->product_type : null,
                'start_date' => $orgSubscription->start_date ?? null,
                'end_date' => $orgSubscription->end_date ?? null,
                'status' => $orgSubscription->is_active == '0' ? 'InActive' : ($orgSubscription->is_active == '1' ? 'Active' : ($orgSubscription->is_active == '2' ? 'Waiting/queued' : 'Expired')),
                'auto_renew' => 'false',
            ];
        } else {
            $user['subscription_detail'] = [];
        }

        // If user exists and password matches → generate token from model
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'data' => $user,
        ], 200);
    }

    // user logout api
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);
    }
}
