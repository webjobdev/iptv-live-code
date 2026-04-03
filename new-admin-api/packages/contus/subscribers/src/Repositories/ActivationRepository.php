<?php

namespace Contus\Subscribers\Repositories;

use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrgDevices;
use Contus\Settings\Model\PaymentService;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use Razorpay\Api\Api;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\OrgSubscriberPayment;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivationRepository extends Repository
{
    protected $orgSubscriberAndPayment;
    protected $orgSubscriberPayment;
    protected $subscriberAssignedDevice;

    public function __construct(OrgSubscriberAndPayment $orgSubscriberAndPayment, OrgSubscriberPayment $orgSubscriberPayment, SubscriberAssignedDevice $subscriberAssignedDevice)
    {
        parent::__construct();
        $this->orgSubscriberAndPayment = $orgSubscriberAndPayment;
        $this->orgSubscriberPayment = $orgSubscriberPayment;
        $this->subscriberAssignedDevice = $subscriberAssignedDevice;
    }

    public function addDeviceSlot()
    {
        DB::beginTransaction();

        $validated = $this->request->validate([
            'id' => 'nullable|integer',
            'subscriber_id' => 'nullable|integer',
            'product_type' => 'required|string',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'razorpay_payment_id' => 'nullable|string',
        ]);

        $id = $validated['id'] ?? null;
        $subscriberId = $validated['subscriber_id'] ?? $id;
        $productType = $validated['product_type'];
        $activation = $this->request->input('activation');
        $amount = $validated['amount'] ?? null;
        $paymentId = $validated['razorpay_payment_id'] ?? null;

        // ✅ Find subscriber
        $subscriber = OrgSubscribers::find($subscriberId);
        if (!$subscriber) {
            return response()->json(['success' => false, 'message' => 'Subscriber not found.'], 404);
        }

        // Log::info("👉 Adding/Updating slot. Subscriber: {$subscriberId}, Product: {$productType}, Activation: {$activation}");

        // ✅ Capture payment if any
        $paymentResponse = null;
        $paymentPayload = [];
        $paymentGatewayName = 'razorpay';

        $paymentServiceStr = $this->request->input('payment_service');
        $paymentServiceObj = null;

        // Log::info("Processing payment via service: " . $paymentServiceStr);

        if ($paymentServiceStr) {
            if (is_numeric($paymentServiceStr)) {
                $paymentServiceObj = PaymentService::find($paymentServiceStr);
            } else {
                if (strtolower($paymentServiceStr) === 'authorizenet' || strtolower($paymentServiceStr) === 'authorize.net') {
                    $paymentServiceObj = PaymentService::where('payment_provider', 'Authorize.net')->first();
                } else {
                    $paymentServiceObj = PaymentService::where('payment_provider', $paymentServiceStr)->first();
                }
            }
        }

        $isAuthorizeNet = $paymentServiceObj && strtolower($paymentServiceObj->payment_provider) === 'authorize.net';

        if ($isAuthorizeNet) {
            // Log::info("Authorize.net detected.");
        }

        if (empty($amount) && $this->request->has('total')) {
            $amount = $this->request->input('total');
        }

        if ($isAuthorizeNet) {
            $paymentGatewayName = 'authorize.net';
            try {
                $authData = is_string($paymentServiceObj->provider_data) ? json_decode($paymentServiceObj->provider_data, true) : $paymentServiceObj->provider_data;

                $merchantAuthentication = new \net\authorize\api\contract\v1\MerchantAuthenticationType();
                $merchantAuthentication->setName($authData['api_id'] ?? '');
                $merchantAuthentication->setTransactionKey($authData['tansaction_key'] ?? '');

                // Card Info
                $creditCard = new \net\authorize\api\contract\v1\CreditCardType();
                $creditCard->setCardNumber($this->request->input('cc_number') ?? $this->request->input('card_number'));

                $expMonth = $this->request->input('cc_exp_month') ?? $this->request->input('expiry_month');
                $expYear = $this->request->input('cc_exp_year') ?? $this->request->input('expiry_year');
                $creditCard->setExpirationDate($expYear . '-' . $expMonth); // YYYY-MM

                $creditCard->setCardCode($this->request->input('cc_cvv') ?? $this->request->input('cvv'));

                $payment = new \net\authorize\api\contract\v1\PaymentType();
                $payment->setCreditCard($creditCard);

                $transactionRequest = new \net\authorize\api\contract\v1\TransactionRequestType();
                $transactionRequest->setTransactionType("authCaptureTransaction");
                $transactionRequest->setAmount($amount);
                $transactionRequest->setPayment($payment);

                // Attach Customer Billing Details
                if ($subscriber) {

                    $billTo = new \net\authorize\api\contract\v1\CustomerAddressType();

                    $cleanAddress = preg_replace('/\s+/', ' ', $subscriber->address ?? '');

                    $billTo->setFirstName(substr($subscriber->first_name ?? '', 0, 50));
                    $billTo->setLastName(substr($subscriber->last_name ?? '', 0, 50));
                    $billTo->setCompany(substr($subscriber->organization_name ?? '', 0, 50));
                    $billTo->setAddress(substr($cleanAddress, 0, 60));
                    $billTo->setCity(substr($subscriber->city ?? '', 0, 40));
                    $billTo->setState(substr($subscriber->state ?? '', 0, 40));
                    $billTo->setZip(substr($subscriber->zip_code ?? '', 0, 20));
                    $billTo->setCountry(substr($subscriber->country ?? '', 0, 60));
                    $billTo->setPhoneNumber(substr($subscriber->phone_number ?? '', 0, 25));

                    $customerData = new \net\authorize\api\contract\v1\CustomerDataType();
                    $customerData->setType("individual");
                    $customerData->setId((string) $subscriber->id);
                    $customerData->setEmail($subscriber->email ?? '');

                    $transactionRequest->setBillTo($billTo);
                    $transactionRequest->setCustomer($customerData);
                }

                $requestAnet = new \net\authorize\api\contract\v1\CreateTransactionRequest();
                $requestAnet->setMerchantAuthentication($merchantAuthentication);
                $requestAnet->setTransactionRequest($transactionRequest);

                $controller = new \net\authorize\api\controller\CreateTransactionController($requestAnet);

                $environment = (isset($authData['mode']) && $authData['mode'] === 'sandbox')
                    ? \net\authorize\api\constants\ANetEnvironment::SANDBOX
                    : \net\authorize\api\constants\ANetEnvironment::PRODUCTION;

                $response = $controller->executeWithApiResponse($environment);

                if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();

                    if ($tresponse && $tresponse->getMessages()) {
                        // Log::info("Authorize.net transaction successful: " . $tresponse->getTransId());
                        $paymentResponse = new \stdClass();
                        $paymentResponse->id = $tresponse->getTransId();

                        $currency = 'USD';
                        if ($this->request->has('payment_currency')) {
                            $currencyStr = $this->request->input('payment_currency');
                            $currencyParts = explode(' ', $currencyStr);
                            $currency = strtoupper(end($currencyParts));
                        } elseif (isset($authData['select_currency'])) {
                            $currency = substr(explode(' ', $authData['select_currency'])[0], 0, 3);
                        }

                        $paymentResponse->currency = $currency;
                        $paymentResponse->method = 'Authorize.net';
                        $paymentResponse->amount = $amount * 100;

                        $paymentPayload = [
                            'id' => $paymentResponse->id,
                            'currency' => $paymentResponse->currency,
                            'method' => $paymentResponse->method,
                            'amount' => $paymentResponse->amount,
                            'response' => json_encode($tresponse)
                        ];
                    } else {
                        DB::rollBack();
                        // Log::error("Authorize.net payment failed (tresponse error): " . json_encode($tresponse->getErrors()));
                        return response()->json(['success' => false, 'message' => 'Authorize.net Payment error details check logs.'], 500);
                    }
                } else {
                    DB::rollBack();
                    $errorMsg = "Unknown Error";
                    if ($response != null) {
                        $tresponse = $response->getTransactionResponse();
                        if ($tresponse != null && $tresponse->getErrors() != null) {
                            $errorMsg = $tresponse->getErrors()[0]->getErrorText();
                        } else {
                            $errorMsg = $response->getMessages()->getMessage()[0]->getText();
                        }
                    }
                    // Log::error("Authorize.net payment failed: " . $errorMsg);
                    return response()->json(['success' => false, 'message' => 'Authorize.net Payment error: ' . $errorMsg], 500);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                // Log::error("Authorize.net Exception: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Authorize.net Exception: ' . $e->getMessage()], 500);
            }
        } elseif ($paymentId) {
            try {
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $payment = $api->payment->fetch($paymentId);
                $paymentResponseInner = $payment->capture(['amount' => $amount]);

                $paymentResponse = new \stdClass();
                $paymentResponse->id = $paymentResponseInner->id;
                $paymentResponse->currency = $paymentResponseInner->currency;
                $paymentResponse->method = $paymentResponseInner->method;
                $paymentResponse->amount = $paymentResponseInner->amount;

                $paymentPayload = $paymentResponseInner->toArray();
                Log::info('✅ Payment captured.', (array) $paymentResponse);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('❌ Payment failed: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Payment error: ' . $e->getMessage()], 500);
            }
        }

        $formattedStartDate = null;

        if ($rawStartDate = $this->request->input('start_date')) {
            Log::info("📅 Received raw start_date input: {$rawStartDate}");

            $dateObj = \DateTime::createFromFormat('d-m-Y', $rawStartDate);

            if ($dateObj) {
                $now = new \DateTime();
                $dateObj->setTime($now->format('H'), $now->format('i'), $now->format('s'));

                $formattedStartDate = $dateObj->format('Y-m-d H:i:s');
                Log::info("✅ Parsed and formatted start_date: {$formattedStartDate}");
            } else {
                Log::warning("❗ Failed to parse start_date with format d-m-Y: {$rawStartDate}");
                try {
                    $formattedStartDate = Carbon::parse($rawStartDate)->format('Y-m-d H:i:s');
                    Log::info("✅ Fallback parsed start_date: {$formattedStartDate}");
                } catch (\Exception $e) {
                    Log::warning("❗ Fallback parsing failed for start_date.");
                }
            }
        }

        $existingMatch = OrgSubscriberAndPayment::where('subscriber_id', $subscriberId)
            ->where('product_type', $productType)
            ->where(function ($query) use ($activation) {
                if ($activation === 'top-up') {
                    $query->whereIn('activation', ['top-up', 'override'])
                        ->orWhereNull('activation');
                } else {
                    $query->where('activation', $activation);
                }
            })
            ->orderBy('id', 'desc')
            ->first();

        $isActive = 1;
        $extensionBaseDate = null;

        if ($existingMatch) {
            $id = $existingMatch->id;
            $formattedStartDate = $existingMatch->start_date;
            $extensionBaseDate = $existingMatch->end_date;
            $isActive = $existingMatch->is_active;
            // Log::info("🔄 Matching record found for update (Type: {$productType}, Activation: {$activation}). ID: {$id}");
        }

        // ✅ If activation = override → check for existing active override to update, or deactivate current active and make this new one active
        if ($activation === 'override') {
            $currentOverride = OrgSubscriberAndPayment::where('subscriber_id', $subscriberId)
                ->where('activation', 'override')
                ->where('is_active', 1)
                ->first();

            if ($currentOverride) {
                $id = $currentOverride->id;
                // Log::info("♻️ Found existing active override subscription. Targeting for update. ID: {$id}");
            } else {
                OrgSubscriberAndPayment::where('subscriber_id', $subscriberId)
                    ->where('is_active', 1)
                    ->update(['is_active' => 0]);
                // Log::info("🚫 No active override found. Deactivated other active subscriptions.");
            }
            $isActive = 1;

            // For override, start fresh from the input start_date, not appending to existing end_date
            $extensionBaseDate = null;
            if ($rawStartDate = $this->request->input('start_date')) {
                $dateObj = \DateTime::createFromFormat('d-m-Y', $rawStartDate);
                if ($dateObj) {
                    $now = new \DateTime();
                    $dateObj->setTime($now->format('H'), $now->format('i'), $now->format('s'));
                    $formattedStartDate = $dateObj->format('Y-m-d H:i:s');
                }
            }
        }
        // ✅ If activation = top-up → queue new subscription or extend existing
        elseif ($activation === 'top-up') {
            if (!$existingMatch) {
                // Find latest active or queued subscription to follow
                $latestActive = OrgSubscriberAndPayment::where('subscriber_id', $subscriberId)
                    ->whereIn('is_active', [1, 2])
                    ->orderBy('end_date', 'desc')
                    ->first();

                if ($latestActive && $latestActive->end_date) {
                    // Start immediately after previous
                    $formattedStartDate = $latestActive->end_date;
                    // Log::info("⏳ Top-up starts immediately after previous (ID: {$latestActive->id}): {$formattedStartDate}");
                }
                $isActive = 2;
            }
        } else {
            $isActive = 1;
        }

        $totalLength = $this->request->input('end_date'); // Default to new input diff

        if ($formattedStartDate && ($rawEndDiff = $this->request->input('end_date'))) {
            $calculationBase = $extensionBaseDate ?: $formattedStartDate;
            // Log::info("📅 Calculating end_date using base: {$calculationBase} and duration: {$rawEndDiff}");

            $startDateObj = Carbon::parse($calculationBase);

            $monthsToAdd = 0;
            $daysToAdd = 0;

            preg_match_all('/(\d+)\s*(month|months|day|days)/i', $rawEndDiff, $matches, PREG_SET_ORDER);
            // Log::info('🔍 Duration matches found:', $matches);

            foreach ($matches as $match) {
                $value = (int) $match[1];
                $unit = strtolower($match[2]);
                if (str_starts_with($unit, 'month')) {
                    $monthsToAdd += $value;
                } elseif (str_starts_with($unit, 'day')) {
                    $daysToAdd += $value;
                }
            }

            // Log::info("➕ Adding to start_date: {$monthsToAdd} months, {$daysToAdd} days");

            $endDateObj = $startDateObj->copy()->addMonths($monthsToAdd)->addDays($daysToAdd);

            // Subtract one day and move to end of day to treat the start date as Day 1
            // and ensure the subscription remains active until the end of the final day.
            // if (($monthsToAdd > 0 || $daysToAdd > 0) && !$extensionBaseDate) {
            //     $endDateObj->subDay()->endOfDay();
            // }

            $formattedEndDate = $endDateObj->format('Y-m-d H:i:s');

            // If we are updating an existing record, calculate the CUMULATIVE length
            if ($existingMatch) {
                $totalStart = Carbon::parse($formattedStartDate);
                $diff = $totalStart->diff($endDateObj);
                $parts = [];
                if ($diff->y > 0)
                    $parts[] = $diff->y . ($diff->y > 1 ? " years" : " year");
                if ($diff->m > 0)
                    $parts[] = $diff->m . ($diff->m > 1 ? " months" : " month");
                if ($diff->d > 0)
                    $parts[] = $diff->d . ($diff->d > 1 ? " days" : " day");
                $totalLength = implode(' ', $parts);
                // Log::info("📏 Re-calculated total length for updated record: {$totalLength}");
            }

            // Only update isActive if it's not already set to 2 (queued)
            if ($isActive !== 2) {
                $isActive = $endDateObj->isFuture() ? 1 : 0;
            }

            // Log::info("✅ Calculated end_date: {$formattedEndDate}, Active status: {$isActive}");
        }

        // ✅ Build subscription text if needed
        $subscriptionPlan = $this->request->input('subscription');

        // ✅ Find existing payment if editing (using $id from request if explicitly editing)
        $existingPayment = ($id) ? OrgSubscriberAndPayment::find($id) : null;

        if ($productType === 'add devices/slots') {
            if ($existingPayment) {
                $existingPayment->update([
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'is_active' => $isActive,
                ]);
                // Log::info("✏️ Updated existing add devices/slots ID: {$existingPayment->id}");
            } else {
                $existingPayment = OrgSubscriberAndPayment::create([
                    'subscriber_id' => $subscriberId,
                    'product_type' => $productType,
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'is_active' => $isActive,
                    'subscribable_type' => OrgSubscribers::class,
                    'subscription' => $subscriptionPlan,
                ]);
                // Log::info("✅ Created new add devices/slots ID: {$existingPayment->id}");
            }
        } else {
            if ($existingPayment) {
                $existingPayment->update([
                    'subscriber_id' => $subscriberId,
                    'activation' => $activation,
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'length_type' => $this->request->input('length_type'),
                    'length' => $totalLength,
                    'device' => json_encode($this->request->input('device')),
                    'is_active' => $isActive,
                    'auto_pay' => $this->request->input('autopay', 0),
                ]);
                // Log::info("✏️ Updated existing subscription ID: {$existingPayment->id}");
            } else {
                $existingPayment = OrgSubscriberAndPayment::create([
                    'subscriber_id' => $subscriberId,
                    'product_type' => $productType,
                    'activation' => $activation,
                    'subscription' => $subscriptionPlan,
                    'length_type' => $this->request->input('length_type'),
                    'length' => $totalLength,
                    'day_month_type' => $this->request->input('day_month_type'),
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'adjust_length' => $this->request->input('adjust_length'),
                    'subscribable_type' => OrgSubscribers::class,
                    'payment_service' => $this->request->input('payment_service'),
                    'cash_location' => $this->request->input('cash_location'),
                    'payment_currency' => $this->request->input('payment_currency'),
                    'total' => $this->request->input('total'),
                    'accessory' => json_encode($this->request->input('accessory')),
                    'custom_charge_comment' => $this->request->input('custom_charge_comment'),
                    'bundels' => $this->request->input('bundels'),
                    'device' => json_encode($this->request->input('device')),
                    'prorate_subsciption' => $this->request->input('prorate_subsciption'),
                    'terms_of_agreement' => $this->request->input('terms_of_agreement', 0),
                    'is_active' => $isActive ?? 0,
                    'auto_pay' => $this->request->input('autopay', 0),
                ]);
                // Log::info("✅ Created new subscription ID: {$existingPayment->id}");
            }
        }

        // ✅ Update subscriber
        $subscriber->update([
            'subscription_and_payments_id' => $existingPayment->id
        ]);

        // ✅ Handle Device Assignment if provided
        $deviceInput = $this->request->input('device');
        if ($deviceInput) {
            $deviceData = is_array($deviceInput) ? $deviceInput : json_decode($deviceInput, true);
            if (isset($deviceData['device_id']) && !empty($deviceData['device_id'])) {
                SubscriberAssignedDevice::updateOrCreate(
                    [
                        'subscriber_id' => $subscriberId,
                        'device_id' => $deviceData['device_id'],
                        'subscription_and_payments_id' => $existingPayment->id
                    ],
                    [
                        'device_name' => $deviceData['brand_model'] ?? $deviceData['name'] ?? 'Unnamed Device',
                        'price' => $deviceData['price'] ?? 0,
                    ]
                );

                // Ensure device is active
                OrgDevices::where('id', $deviceData['device_id'])->update(['is_active' => 1]);
                // Log::info("📱 Device assigned to subscription ID: {$existingPayment->id}");
            }
        }

        // ✅ Save payment if any
        if ($paymentResponse) {
            $paymentModel = OrgSubscriberPayment::create([
                'payment_id' => $paymentResponse->id,
                'currency' => $paymentResponse->currency,
                'method' => $paymentResponse->method,
                'amount' => $paymentResponse->amount / 100,
                'status' => 'PAYMENT_SUCCESS',
                'payment_gateway' => $paymentGatewayName ?? 'razorpay',
                'subscriber_id' => $subscriberId,
                'subscription_payments_id' => $existingPayment->id,
                'payload' => json_encode($paymentPayload),
            ]);
            $existingPayment->update([
                'subscriber_payment_id' => $paymentModel->id,
                'payment_status' => $paymentModel->status
            ]);
            // Log::info("💰 Payment saved: ID {$paymentModel->id}");
        }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Subscription added successfully.', 'data' => $existingPayment]);
    }

    public function paymentfailure()
    {
        DB::beginTransaction();

        $validated = $this->request->validate([
            'id' => 'nullable|integer',
            'subscriber_id' => 'nullable|integer',
            'amount' => 'nullable|numeric',
            'razorpay_payment_id' => 'nullable|string',
            'error' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $id = $validated['id'] ?? null;
        $subscriberId = $validated['subscriber_id'] ?? $id;
        $amount = $validated['amount'] ?? null;
        $paymentId = $validated['razorpay_payment_id'] ?? null;
        $error = $validated['error'] ?? 'Unknown error';
        $reason = $validated['reason'] ?? 'No reason provided';

        Log::info("🟡 Payment failure process started", compact('id', 'subscriberId', 'paymentId', 'amount'));

        $subscriber = OrgSubscribers::find($subscriberId);
        if (!$subscriber) {
            Log::warning("❌ Subscriber not found", ['subscriber_id' => $subscriberId]);
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Subscriber not found.'], 404);
        }

        if ($paymentId) {
            $duplicate = OrgSubscriberPayment::where('payment_id', $paymentId)
                ->where('status', 'PAYMENT_FAILED')
                ->first();

            if ($duplicate) {
                Log::info("⚠️ Duplicate payment failure entry skipped", ['payment_id' => $paymentId]);
                DB::rollBack();
                return response()->json([
                    'success' => true,
                    'message' => 'Payment failure already recorded.'
                ]);
            }
        }

        $paymentResponse = null;

        if ($paymentId) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment = $api->payment->fetch($paymentId);

            if ($payment->status === 'authorized') {
                $paymentResponse = $payment->capture(['amount' => $amount]);
                Log::info("✅ Razorpay payment captured", (array) $paymentResponse);
            } else {
                Log::info("ℹ️ Razorpay payment not authorized, skipping capture", ['status' => $payment->status]);
            }
        }

        $paymentModel = OrgSubscriberPayment::create([
            'payment_id' => $paymentResponse->id ?? $paymentId,
            'currency' => $paymentResponse->currency ?? 'INR',
            'method' => 'Payment method not store because payment cancel.',
            'amount' => ($paymentResponse->amount ?? $amount) / 100,
            'status' => 'PAYMENT_FAILED',
            'payment_gateway' => 'razorpay',
            'subscriber_id' => $subscriberId,
            'payload' => json_encode([
                'error' => $error,
                'reason' => $reason,
            ]),
        ]);

        DB::commit();
        Log::info("✅ Payment failure process completed successfully");

        return response()->json([
            'success' => true,
            'message' => 'Payment failure recorded.'
        ]);
    }

    public function postAssignedDevice()
    {
        DB::beginTransaction();

        $paymentResponse = $this->request->input('razorpay_payment_id');
        $subscriberId = $this->request->input('subscriber_id', $this->request->input('id'));
        $devices = $this->request->input('assigned_devices', []);
        $amount = $this->request->input('amount');

        // Log::info('📩 Received subscriber ID.', ['subscriber_id' => $subscriberId]);
        // Log::info('📦 Received assigned_devices list.', ['assigned_devices' => $devices]);

        if (empty($devices)) {
            Log::warning('⚠️ No assigned devices were sent in the this->request.');
            return response()->json(['message' => 'No devices selected.'], 400);
        }

        $response = null;
        if ($paymentResponse) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment = $api->payment->fetch($paymentResponse);
            $response = $payment->capture(['amount' => $amount]);

            // Log::info('✅ Razorpay payment captured', [
            //     'payment_id' => $response->id,
            //     'method' => $response->method,
            //     'currency' => $response->currency
            // ]);
        }

        $inserted = [];
        $isActive = 1;

        $subscriptionPayment = OrgSubscriberAndPayment::create([
            'subscriber_id' => $subscriberId,
            'product_type' => $this->request->input('product_type'),
            'subscribable_type' => OrgSubscribers::class,
            'payment_service' => $this->request->input('payment_service'),
            'cash_location' => $this->request->input('cash_location'),
            'payment_currency' => $this->request->input('payment_currency'),
            'total' => $this->request->input('total'),
            'device' => json_encode($this->request->input('device')),
            'terms_of_agreement' => $this->request->input('terms_of_agreement', 0),
            'is_active' => $isActive,
        ]);
        // Log::info('✅ OrgSubscriberAndPayment created successfully.', ['Subscriber Payment Data Insert' => $subscriptionPayment]);

        foreach ($devices as $device) {
            if (!empty($device['device_id'])) {
                $data = [
                    'subscriber_id' => $subscriberId,
                    'device_id' => $device['device_id'],
                    'subscription_and_payments_id' => $subscriptionPayment->id,
                    'device_name' => $device['brand_model'] ?? 'Unnamed Device',
                    'price' => $device['price'],
                ];

                // Log::info('✅ Inserting assigned device.', $data);

                $inserted[] = SubscriberAssignedDevice::create($data);
            }
            // else {
            //     Log::warning('⏭️ Skipping device with missing device_id.', ['device' => $device]);
            // }
        }
        // Log::info('🎉 Device assignment completed.', ['inserted_count' => count($inserted)]);

        if ($response) {
            $paymentModel = OrgSubscriberPayment::create([
                'payment_id' => $response->id,
                'currency' => $response->currency,
                'method' => $response->method,
                'amount' => $response->amount / 100,
                'status' => 'PAYMENT_SUCCESS',
                'payment_gateway' => 'razorpay',
                'subscriber_id' => $subscriberId,
                'subscription_payments_id' => $subscriptionPayment->id,
                'payload' => json_encode((array) $response),
            ]);
            // Log::info('💾 Payment saved in OrgSubscriberPayment and linked to subscription.');

            $subscriptionPayment->update([
                'subscriber_payment_id' => $paymentModel->id
            ]);
        }

        DB::commit();
        return response()->json([
            'success' => true,
            'message' => trans('subscribers::index.device_assigned.success'),
        ]);
    }

    public function assigned_device_info()
    {
        return SubscriberAssignedDevice::all();
    }

    public function fetch_alldata()
    {
        return OrgSubscriberAndPayment::with('subscriber_detail', 'transaction_detail')->get();
    }

    public function paymentRefund()
    {
        $paymentId = $this->request->input('payment_id');
        $amount = $this->request->input('amount');
        $subscriberId = $this->request->input('subscriber_id');

        Log::info('Refund this->request received', [
            'payment_id' => $paymentId,
            'amount' => $amount,
            'subscriber_id' => $subscriberId
        ]);

        try {
            if (!$paymentId || !$amount) {
                return response()->json(['message' => 'Missing required parameters'], 400);
            }

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment = $api->payment->fetch($paymentId);

            if ($payment->status !== 'captured') {
                $payment->capture(['amount' => $amount]);
                // Log::info('Payment captured before refund', ['payment_id' => $payment->id]);
            }

            $refund = $payment->refund([
                'amount' => $amount,
            ]);

            // Log::info('Refund successful', [
            //     'refund_id' => $refund->id,
            //     'amount' => $refund->amount,
            //     'subscriber_id' => $subscriberId
            // ]);

            // 🛠️ Update OrgSubscriberPayment table
            $paymentRefund = OrgSubscriberPayment::where('id', $subscriberId)
                ->update([
                    'refund_id' => $refund->id,
                    'status' => 'PAYMENT_REFUND',
                    'refund_payload' => json_encode('Amount will be credited to customer’s bank account within 5-7 working days after the refund has processed')
                ]);

            return response()->json([
                'success' => true,
                'message' => trans('subscribers::index.refund.success'),
            ]);
        } catch (\Exception $e) {
            // Log::error('Refund failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Refund failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->orgSubscriberAndPayment)
            ->setEagerLoadingModels(['transaction_detail', 'PlanDetail']);
        // $this->setGridModel($this->subscriberAssignedDevice)->setEagerLoadingModels(['device_detaile', 'subscriber_detaile', 'subscription_and_payments_detaile']);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $subscriberId = $this->request->input('subscriber-id') ?? $this->request->input('subscriber_id');
        if ($subscriberId) {
            return $builder->where('subscriber_id', $subscriberId);
        }
        return $builder;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Current Status', 'value' => '', 'sort' => true],
                ['name' => 'Active Until', 'value' => '', 'sort' => false],
                ['name' => 'Days Left', 'value' => '', 'sort' => false],
                ['name' => 'AutoPay', 'value' => '', 'sort' => false],
                ['name' => 'Subscription', 'value' => '', 'sort' => false],
                ['name' => 'Devices', 'value' => '', 'sort' => false],
                ['name' => 'Status', 'value' => '', 'sort' => false],
                // ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value = date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
