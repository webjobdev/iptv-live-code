<?php

namespace Contus\AppApi\Api\Controllers\TvApp;

use Carbon\Carbon;
use Contus\AppApi\Model\OtpVerification;
use Contus\AppApi\Model\SubscriberLike;
use Contus\AppApi\Model\SubscriberMyList;
use Contus\AppApi\Model\UserProfiles;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Tvshow\Model\TvShow;
use Contus\Video\Models\Video;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;

class UserApiController extends AppApiController
{
    // get profile data helper function
    public function getProfileData($userId)
    {
        // Fetch user profile data based on user ID
        $user = OrgSubscribers::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $data = [
            'id' => $user->id,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'user_name' => $user->user_name,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'phone' => $user->phone_number,
            'date_of_birth' => $user->date_of_birth,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'User Profile Fetched Successfully',
        ], 200);
    }

    public function getUserProfile(Request $request)
    {
        $user = Auth::user();
        $profileData = $this->getProfileData($user->id);

        return response()->json([
            'success' => true,
            'data' => $profileData->original['data'],
            'message' => 'User Profile Fetched Successfully',
        ], 200);
    }

    public function updateUserProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:org_subscribers,email,' . $id,
            'user_name' => 'sometimes|string|unique:org_subscribers,user_name,' . $id,
            'avatar' => 'sometimes|file|image|max:2048|mimes:jpeg,jpg,webp,png',
            'phone_number' => 'sometimes|string|max:20',
            'date_of_birth' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = OrgSubscribers::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update fields if provided
        $user->first_name = $request->input('first_name', $user->first_name);
        $user->last_name = $request->input('last_name', $user->last_name);
        $user->email = $request->input('email', $user->email);
        $user->user_name = $request->input('user_name', $user->user_name);

        $reqAvatar = $request->hasFile('avatar');
        if (isset($reqAvatar) && $reqAvatar != '') {
            // $thumbUrl = explode("/", $reqAvatar);
            // $fileName = $user->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.org_subscriber_avatar.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $reqAvatar;
            $user->avatar = $localIamgePath;
        }

        $user->phone_number = $request->input('phone_number', $user->phone_number);

        $dob = Carbon::parse($request->input('date_of_birth')); // formating date from request
        $user->date_of_birth = $dob ? $dob->format('Y-m-d') : $user->date_of_birth;
        $user->save();

        $updatedProfileData = $this->getProfileData($user->id);

        return response()->json([
            'success' => true,
            'data' => $updatedProfileData->original['data'],
            'message' => 'User Profile Updated Successfully',
        ], 200);
    }

    public function addToMyList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'rec_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        if ($request->type == 'movie') {
            $item = VideoOnDemad::find($request->rec_id);
        } elseif ($request->type == 'series') {
            $item = TvShow::find($request->rec_id);
        } elseif ($request->type == 'live-channel') {
            $item = Video::find($request->rec_id);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type provided'
            ], 400);
        }

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $existingList = SubscriberMyList::where('subscriber_id', $user->id);

        if ($request->type == 'movie') {
            $existingList->where('movie_id', $request->rec_id);
        } elseif ($request->type == 'series') {
            $existingList->where('series_id', $request->rec_id);
        } elseif ($request->type == 'live-channel') {
            $existingList->where('channel_id', $request->rec_id);
        }

        $existingList = $existingList->first();

        if ($existingList) {
            return response()->json([
                'success' => true,
                'data' => $existingList,
                'message' => 'Item already added to My List'
            ], 200);
        }

        $myList = new SubscriberMyList();
        $myList->subscriber_id = $user->id;

        if ($request->type == 'movie') {
            $myList->movie_id = $request->rec_id;
        } elseif ($request->type == 'series') {
            $myList->series_id = $request->rec_id;
        } elseif ($request->type == 'live-channel') {
            $myList->channel_id = $request->rec_id;
        }
        $myList->save();

        return response()->json([
            'success' => true,
            'data' => $myList,
            'message' => 'Item added to My List successfully'
        ], 200);
    }

    public function fetchMyList(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $myList = SubscriberMyList::with(['movie', 'series'])
            ->where('subscriber_id', $user->id)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'My List fetched successfully',
            'data' => $myList,
        ], 200);
    }

    // public function removeFromMyList(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 400);
    //     }

    //     $user = Auth::user();
    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     $myList = SubscriberMyList::where('subscriber_id', $user->id)
    //         ->where('id', $request->id)
    //         ->first();

    //     if (!$myList) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Item not found in My List'
    //         ], 404);
    //     }

    //     $myList->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Item removed from My List successfully'
    //     ], 200);
    // }


    public function addToMyLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'record_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        if ($request->type == 'movie') {
            $item = VideoOnDemad::find($request->record_id);
        } elseif ($request->type == 'series') {
            $item = TvShow::find($request->record_id);
        } elseif ($request->type == 'live-channel') {
            $item = Video::find($request->record_id);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type provided'
            ], 400);
        }

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $existingLike = SubscriberLike::where('subscriber_id', $user->id);

        if ($request->type == 'movie') {
            $existingLike->where('movie_id', $request->record_id);
        } elseif ($request->type == 'series') {
            $existingLike->where('series_id', $request->record_id);
        } elseif ($request->type == 'live-channel') {
            $existingLike->where('channel_id', $request->record_id);
        }

        $existingLike = $existingLike->first();

        if ($existingLike) {
            return response()->json([
                'success' => true,
                'message' => 'Item already added to like',
                'data' => $existingLike,
            ], 200);
        }

        $like = new SubscriberLike();
        $like->subscriber_id = $user->id;

        if ($request->type == 'movie') {
            $like->movie_id = $request->record_id;
        } elseif ($request->type == 'series') {
            $like->series_id = $request->record_id;
        } elseif ($request->type == 'live-channel') {
            $like->channel_id = $request->record_id;
        }
        $like->save();

        return response()->json([
            'success' => true,
            'message' => 'Item Added To Like Successfully',
            'data' => $like,
        ], 200);
    }

    public function fetchMyLike(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $myList = SubscriberLike::with(['movie', 'series'])
            ->where('subscriber_id', $user->id)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Like List Fetched Successfully.',
            'data' => $myList,
        ], 200);
    }

    // public function removeFromMyLike(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'id' => 'required'
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validate->errors()->first()
    //         ], 400);
    //     }

    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     $like = SubscriberLike::where('subscriber_id', $user->id)
    //         ->where('id', $request->id)
    //         ->first();

    //     if (!$like) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Like not found'
    //         ], 404);
    //     }

    //     $like->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Like removed successfully',
    //     ], 200);
    // }

    public function removeFromMyList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $myList = SubscriberMyList::where('subscriber_id', $user->id)
            ->where('id', $request->id)
            ->first();

        if (!$myList) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in My List'
            ], 404);
        }

        $myList->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from My List successfully'
        ], 200);
    }

    public function removeFromMyLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $like = SubscriberLike::where('subscriber_id', $user->id)
            ->where('id', $request->id)
            ->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found'
            ], 404);
        }

        $like->delete();

        return response()->json([
            'success' => true,
            'message' => 'Like removed successfully',
        ], 200);
    }

    public function subscriberProfiles(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found or Please login to your account.'
            ], 400);
        }

        $profileData = UserProfiles::with(['subscriber', 'organization'])
            ->where('organization_id', $user->organization_id)
            ->where('subscriber_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $profileData,
            'message' => 'User Profiles Fetched Successfully',
        ], 200);
    }

    public function createSubscriberProfiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'avatar' => 'sometimes|file|image|max:2048|mimes:jpeg,jpg,webp,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found or Please login to your account.'
            ], 400);
        }

        $exists = UserProfiles::where('organization_id', $user->organization_id)->where('subscriber_id', $user->id)->where('name', $request->name)->first();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Profile with this name already exists.'
            ]);
        }

        $userProfile = new UserProfiles();
        $userProfile->organization_id = $user->organization_id;
        $userProfile->subscriber_id = $user->id;
        $userProfile->name = $request->name;

        $reqAvatar = $request->hasFile('avatar');
        if (isset($reqAvatar) && $reqAvatar != '') {
            // $thumbUrl = explode("/", $reqAvatar);
            // $fileName = $user->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.org_subscriber_profile_avatar.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $reqAvatar;
            $userProfile->avatar = $localIamgePath;
        }

        $userProfile->save();

        return response()->json([
            'success' => true,
            'data' => $userProfile,
            'message' => 'Profile Created Successfully.'
        ], 200);

    }

    public function editSubscriberProfiles(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'avatar' => 'sometimes|file|image|max:2048|mimes:jpeg,jpg,webp,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found or Please login to your account.'
            ], 400);
        }

        $userProfile = UserProfiles::find($id);
        $userProfile->organization_id = $user->organization_id;
        $userProfile->subscriber_id = $user->id;
        $userProfile->name = $request->name;

        $reqAvatar = $request->hasFile('avatar');
        if (isset($reqAvatar) && $reqAvatar != '') {
            // $thumbUrl = explode("/", $reqAvatar);
            // $fileName = $user->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.org_subscriber_profile_avatar.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $reqAvatar;
            $userProfile->avatar = $localIamgePath;
        }

        $userProfile->save();

        return response()->json([
            'success' => true,
            'data' => $userProfile,
            'message' => 'Profile Updated Successfully.'
        ], 200);

    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|max:8',
            'new_password' => 'required|string|max:8|different:current_password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User Not Found or Please login to your account.'
            ], 400);
        }

        $current = $request->input('current_password');
        // dd($current);
        $hash = $user->password;

        $verify = password_verify($current, $hash);

        if ($verify) {
            $update = OrgSubscribers::find($user->id);
            $update->password = Hash::make($request->new_password);
            $update->save();

            return response()->json([
                'success' => true,
                'message' => 'Password Change Successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect. Please try again.'
            ], 401);
        }
    }

    public function changeUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_name" => "required|string|unique:org_subscribers,user_name"
        ], [
            "user_name.unique" => "This user name already exists."
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                "error" => true,
                "message" => "User Not Found or Please login to your account."
            ], 400);
        }

        $user->user_name = $request->user_name;
        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Username Changed Successfully."
        ], 200);
    }

    // email verification
    public function emailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->errors()->first()
            ], 400);
        }

        // $user = Auth::user();
        // if (!$user) {
        //     return response()->json([
        //         "error" => true,
        //         "message" => "User Not Found or Please login to your account."
        //     ], 400);
        // }

        $verifyUser = OrgSubscribers::where('email', $request->email)->first();
        if (!$verifyUser) {
            return response()->json([
                "error" => true,
                "message" => "User Not Found In Our System."
            ], 400);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        OtpVerification::create([
            // "organization_id" => $user->organization_id ?? null,
            // "user_id" => $user->id,
            "user_email" => $request->email,
            "otp" => $otp,
        ]);

        // Send OTP Email
        Mail::raw("Your ISG Password Reset OTP is: $otp", function ($msg) use ($request) {
            $msg->to($request->email)->subject("Password Reset OTP");
        });

        return response()->json([
            "success" => true,
            "message" => "OTP sent successfully to your email."
        ], 200);
    }

    // otp verification
    public function otpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "otp" => "required|numeric"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->errors()->first()
            ], 400);
        }

        $otpData = OtpVerification::where('user_email', $request->email)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$otpData) {
            return response()->json([
                "error" => true,
                "message" => "Invalid OTP."
            ], 400);
        }

        return response()->json([
            "success" => true,
            "message" => "OTP verified successfully."
        ], 200);
    }

    // forgot user password
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|string|max:8"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                "error" => true,
                "message" => "User Not Found or Please login to your account."
            ], 400);
        }

        $data = OrgSubscribers::where('email', $request->email)->first();
        if (!$data) {
            return response()->json([
                "error" => true,
                "message" => "User Not Found or Please login to your account."
            ], 400);
        }

        $data->password = Hash::make($request->password);
        $data->save();

        return response()->json([
            "success" => true,
            "message" => "Password Reset Successfully."
        ], 200);
    }
}
