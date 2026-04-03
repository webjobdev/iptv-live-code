<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationPayment;
use Contus\Organizations\Model\OrganizationSubscription;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class OrganizationPaymentRepository extends Repository {

    protected $payment;
    protected $subscribers;

    public function __construct(OrganizationPayment $organization_payment, OrganizationSubscription $organization_subscription) {
        parent::__construct();
        $this->payment = $organization_payment;
        $this->subscribers = $organization_subscription;
    }

    public function paymentstore() {
        DB::beginTransaction();

        // Log::info('Received Payment Request', ['request_data' => $this->request->all()]);

        $paymentResponse = $this->request->input('razorpay_payment_id');
        $planId = $this->request->input('plan_id');
        $orgId = $this->request->input('organization_id');
        $amount = $this->request->input('amount');

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        // Log::info('Fetched Razorpay API credentials');

        $payment = $api->payment->fetch($paymentResponse);
        $response = $payment->capture(['amount' => $amount]);

        // Log::info('Razorpay Payment Captured', ['razorpay_response' => $response]);

        $plan = OrganizationMonitizationPlan::find($planId);
        // if (!$plan) {
        //     Log::error('Invalid Plan ID', ['plan_id' => $planId]);
        //     return response()->json(['success' => false, 'error' => 'Invalid Plan'], 400);
        // }

        $organizationPayment = OrganizationPayment::create([
            'user_id' => auth()->id(),
            'payment_id' => $response->id,
            'currency' => $response->currency,
            'method' => $response->method,
            'amount' => $response->amount / 100,
            'status' => 'PAYMENT_SUCCESS',
            'plan_id' => $plan->id,
            'organization_id' => $orgId,
            'payload' => json_encode((array) $response),
        ]);

        // Log::info('Payment Stored Successfully', [
        //     'payment_id' => $response->id,
        //     'user_id' => auth()->id(),
        //     'amount' => $response->amount / 100,
        //     'plan_id' => $plan->id,
        //     'organization_id' => $orgId,
        // ]);

        $startAt = Carbon::now();

        if (!is_numeric($plan->duration) || $plan->duration <= 0) {
            // Log::error('Invalid plan duration', ['plan_id' => $plan->id, 'plan_duration' => $plan->duration]);
            return response()->json(['success' => false, 'error' => 'Invalid plan duration'], 400);
        }

        $duration = Carbon::now()->addDays((int)$plan->duration);

        // Log::info('Subscription Date Calculated', [
        //     'start_at' => $startAt,
        //     'end_at' => $duration,
        //     'plan_duration' => $plan->duration,
        // ]);

        $subscription = OrganizationSubscription::create([
            'user_id' => auth()->id(),
            'subscribable_type' => OrganizationMonitizationPlan::class,
            'subscribable_id' => $plan->id,
            'organization_id' => $orgId,
            'start_at' => $startAt,
            'end_at' => $duration,
        ]);

        // Log::info('Subscription Created Successfully', [
        //     'subscription_id' => $subscription->id,
        //     'user_id' => auth()->id(),
        //     'plan_id' => $plan->id,
        //     'organization_id' => $orgId,
        //     'start_at' => $startAt,
        //     'end_at' => $duration,
        // ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => trans('organizations::organization.add.success'),
        ]);
    }


    public function paymentfailure() {
        DB::beginTransaction();
        try {
            // Log::info('Received Payment Failure Request', ['request_data' => $this->request->all()]);

            $planId = $this->request->input('plan_id');
            $orgId = $this->request->input('organization_id');
            $amount = $this->request->input('amount');
            $paymentId = $this->request->input('razorpay_payment_id');
            $error = $this->request->input('error', 'Unknown error');
            $reason = $this->request->input('reason', 'No reason provided');

            $plan = OrganizationMonitizationPlan::find($planId);

            organizationpayment::create([
                'user_id' => auth()->id(),
                'payment_id' => $paymentId,
                'currency' => 'INR', // or set default
                'method' => 'razorpay',
                'amount' => $amount / 100,
                'status' => 'PAYMENT_FAILED',
                'plan_id' => $plan->id,
                'organization_id' => $orgId,
                'payload' => json_encode([
                    'error' => $error,
                    'reason' => $reason,
                    'plan_id' => $planId,
                    'organization_id' => $orgId
                ])
            ]);

            // Log::info('Payment Failure Recorded', [
            //     'payment_id' => $paymentId,
            //     'user_id' => auth()->id(),
            //     'amount' => $amount / 100,
            //     'plan_id' => $planId,
            //     'organization_id' => $orgId,
            // ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Payment failure recorded'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Log::error('PAYMENT_FAILURE_ERROR: ' . $th->getMessage());
            return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
        }
    }



    public function prepareGrid() {
        $this->setGridModel($this->payment);
        $this->setGridModel($this->subscribers)->setEagerLoadingModels(['user', 'plan', 'orgname']);
        return $this;
    }
}
