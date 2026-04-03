<?php

namespace Contus\AppApi\Api\Controllers\v3;

use Contus\AppApi\Model\OrganizationPlanPayment;
use Contus\Base\ApiController;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Contus\Settings\Model\PaymentService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Omnipay\Omnipay;
use Tymon\JWTAuth\Facades\JWTAuth;


class PaymentController extends ApiController
{
    public $gateway;
    
    // CreatePayment api 
    public function CreatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|int',
            // 'amount' => 'required|flaot',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => true,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $token = $request->bearerToken();
        $user = JWTAuth::setToken($token)->toUser();

        if (!$user) {
            return response()->json([
                'suucess' => false,
                'message' => 'User Not Found',
            ], 400);
        }

        $plan = OrgMonetizationPlanss::where('organization_id', $user->organization_id)
            ->where('id', $request->plan_id)
            ->first();

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found for this organization'
            ], 404);
        }

        $days = $plan->subscription_length;

        $start_date = now();
        $end_date = now()->addDays($days);

        // authorized net code start 
        $authorizedPaymentResponse = null;
        if ($authorizedPaymentResponse) {
            try {
                $fetchAuthData = PaymentService::where('payment_provider', 'Authorize.net')
                    ->first();

                if (!$fetchAuthData) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Authorize.net details not found'
                    ]);
                }

                $authData = json_decode($fetchAuthData->provider_data, true);

                $loginId = $authData['api_id'];
                $transactionKey = $authData['tansaction_key'];

                $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
                $this->gateway->setAuthName($loginId);
                $this->gateway->setTransactionKey($transactionKey);
                $this->gateway->setTestMode(true);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment Error: ' . $e->getMessage(),
                ], 500);
            }
        }

        $transactionId = rand(100000000, 999999999);

        if ($authorizedPaymentResponse) {
            $payment = OrganizationPlanPayment::create([
                'organization_id' => $user->organization_id,
                'user_id' => $user->id,
                'plan_id' => $request->id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'transaction_id' => $transactionId,
                'payment_gateway' => "Authorized.net",
                'currency' => $plan->currency,
                'method' => "Online",
                // 'payload' => ,
                'amount' => $plan->price / 100,
                'status' => "PAYMENT_SUCCESS",
            ]);
            dd($payment);
        }


        // return response()->json([
        //     'success' => true,
        //     'message' => 'Payment is successful.',
        //     'data' => $payment
        // ]);

        // authorized net code end



    }
}