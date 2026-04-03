<?php

namespace Contus\AppApi\Api\Controllers\v3;

use Carbon\Carbon;
use Contus\AppApi\Model\SubDeviceLog;
use Contus\Base\ApiController;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends ApiController
{
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

    public function
        postLogin(
        Request $request
    ) {
        // Log::info('Login API started', ['request' => $request->all()]);

        // 1. Check for Provider ID in request headers
        $providerId = $request->header('provider-id');
        if (!$providerId) {
            return response()->json([
                'success' => false,
                'message' => 'Provider Id is required in header'
            ], 400);
        }

        // 2. Fetch Organization/Provider details using the Provider ID
        $provider = OrganizationDetail::where('provider_id', $providerId)->first();
        if (!$provider) {
            // Log::warning('Invalid Provider ID', ['provider_id' => $providerId]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid Provider Id'
            ], 404);
        }

        // 3. Validate user credentials from the request
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            // Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        // 4. Fetch the Subscriber/User by username
        $user = OrgSubscribers::where('user_name', $request->user_name)->first();

        if (!$user) {
            // Log::warning('User not found', ['user_name' => $request->user_name]);
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // 5. Verify the user's password
        if (!Hash::check($request->password, $user->password)) {
            // Log::warning('Invalid credentials', ['user_name' => $request->user_name]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
 
        // Update last login time
        $user->login_at = Carbon::now();
        $user->save();

        // 6. Retrieve all active subscriptions for the user
        $orgSubscriptions = OrgSubscriberAndPayment::where('subscriber_id', $user->id)
            ->with('PlanDetail')
            ->whereIn('product_type', ['custom subscription', 'subscription sets', 'free subscription'])
            ->where('is_active', '1')
            ->get();

        // 7. Generate a secure JWT token for the authenticated user
        $token = JWTAuth::fromUser($user);
        // Log::info('JWT token generated', ['user_id' => $user->id]);

        // ================= DEVICE & SUBSCRIPTION LOGIC =================
        $device = null;
        $requestedName = null;
        $hasSubscriptionAccess = true;
        $subscriptionMessage = 'Login successful';

        // A. Identify which subscription record should be targeted for this session
        $orgSubscription = $orgSubscriptions->first(); // Default fallback

        // B. Process Hardware Information (Register or Update the device)
        if ($request->hasAny(['mac_address', 'identifier', 'device_type'])) {
            $macAddress = $request->input('mac_address');
            $identifier = $request->input('identifier');

            // Find device by MAC address or Unique Identifier
            $deviceQuery = OrgDevices::where('subscriber_id', $user->id);
            if ($macAddress || $identifier) {
                $deviceQuery->where(function ($query) use ($macAddress, $identifier) {
                    if ($macAddress) {
                        $query->orWhere('mac_address', $macAddress);
                    }
                    if ($identifier) {
                        $query->orWhere('identifier', $identifier);
                    }
                });
            } else {
                $deviceQuery->whereNull('id');
            }

            $device = $deviceQuery->first();

            if ($device) {
                // Update existing hardware record
                $device->device_type = $request->input('device_type', $device->device_type);
                $device->brand_model = $request->input('brand_model', $device->brand_model);
                $device->mac_address = $macAddress ?: $device->mac_address;
                $device->serial_number = $request->input('serial_number', $device->serial_number);
                $device->identifier = $identifier ?: $device->identifier;
                $device->ip_address = $request->input('ip_address', $request->ip());
                $device->latitude = $request->input('latitude', $device->latitude);
                $device->longitude = $request->input('longitude', $device->longitude);
                $device->updated_at = Carbon::now();
                $device->save();
            } else {
                // Register a new hardware record
                $device = new OrgDevices();
                $device->subscriber_id = $user->id;
                $device->device_type = $request->input('device_type');
                $device->brand_model = $request->input('brand_model');
                $device->mac_address = $macAddress;
                $device->serial_number = $request->input('serial_number');
                $device->identifier = $identifier;
                $device->ip_address = $request->input('ip_address', $request->ip());
                $device->latitude = $request->input('latitude');
                $device->longitude = $request->input('longitude');
                $device->is_active = 1;
                $device->updated_at = Carbon::now();
                $device->save();
            }

            $requestedName = $request->input('brand_model', $device->device_type);

            

            // C. Find if this device is already assigned to a plan slot
            $assignedDevice = SubscriberAssignedDevice::where('subscriber_id', $user->id)
                ->where('device_id', $device->id)
                ->where('deletable', 0)
                ->first();

            // Link to the correct subscription if already assigned
            if ($assignedDevice && $assignedDevice->subscription_and_payments_id) {
                $foundSub = $orgSubscriptions->where('id', $assignedDevice->subscription_and_payments_id)->first();
                if ($foundSub) {
                    $orgSubscription = $foundSub;
                }
            } else {
                // If new device, find the first available subscription slot (plan with empty slots)
                foreach ($orgSubscriptions as $sub) {
                    $limit = (int) ($sub->PlanDetail->subscription_devices ?? 0);
                    $usedCount = SubscriberAssignedDevice::where('subscription_and_payments_id', $sub->id)->where('deletable', 0)->count();
                    if ($usedCount < $limit) {
                        $orgSubscription = $sub;
                        break;
                    }
                }
            }

            // D. Calculate total device numerical limits (Global across all plans)
            $orgLimit = $provider->device_activation_limit;
            $planLimitTotal = 0;
            foreach ($orgSubscriptions as $sub) {
                $planLimitTotal += (int) ($sub->PlanDetail->subscription_devices ?? 0);
            }
            $effectiveLimit = ($planLimitTotal > 0) ? $planLimitTotal : $orgLimit;
            if ($orgLimit > 0 && $planLimitTotal > 0) {
                $effectiveLimit = min($planLimitTotal, $orgLimit);
            }

            // Current count of active devices for this user
            $assignedCount = SubscriberAssignedDevice::where('subscriber_id', $user->id)->where('deletable', 0)->count();

            // Check if adding this device would exceed the numerical limit (e.g., 6th device when limit is 5)
            if ($effectiveLimit > 0 && $assignedCount >= $effectiveLimit && !$assignedDevice) {
                $device->is_active = 0;
                $device->save();
                return response()->json([
                    'success' => false,
                    'message' => 'Your device limit reached. You can only use up to ' . $effectiveLimit . ' devices. or contact admin to add more devices.',
                ], 403);
            }

            // E. Finalize device registration in the primary assignment table
            $device->is_active = 1;
            $device->save();

            if (!$assignedDevice) {
                $assignedDevice = SubscriberAssignedDevice::where('subscriber_id', $user->id)->where('device_id', $device->id)->first() ?: new SubscriberAssignedDevice();
                $assignedDevice->subscriber_id = $user->id;
                $assignedDevice->device_id = $device->id;
                $assignedDevice->subscription_and_payments_id = $orgSubscription->id ?? null;
                $assignedDevice->device_name = $requestedName;
                $assignedDevice->is_primary = ($assignedCount == 0) ? 1 : 0;
                $assignedDevice->deletable = 0;
                $assignedDevice->is_active = 1;
                $assignedDevice->save();
            }

            // ================= SUBSCRIPTION AUTHORIZATION CHECK =================
            // Check if this specific device model is authorized in the subscription whitelist
            if ($orgSubscription && !empty($orgSubscription->device)) {
                $allowed = $orgSubscription->device;
                // Handle JSON decoding for stored device lists
                if (is_string($allowed)) {
                    $allowed = json_decode($allowed, true);
                    if (is_string($allowed)) {
                        $allowed = json_decode($allowed, true);
                    }
                }

                if (is_array($allowed) && !empty($allowed)) {
                    if (!in_array($requestedName, $allowed)) {
                        // If not in whitelist, deny subscription details (even if login is allowed)
                        $hasSubscriptionAccess = false;
                        $subscriptionMessage = 'You do not have access to this subscription on this device. Please add more devices or contact admin.';
                    }
                }
            }

            // NOTE: Auto-syncing of devices to the subscription table is disabled 
            // to prevent unauthorized devices from being automatically whitelisted.
        }

        // 8. Prepare Subscription Details for the JSON response
        if ($orgSubscription && $hasSubscriptionAccess) {
            $user['subscription_detail'] = [
                'plan_name' => $orgSubscription->PlanDetail->subscription_name ?? null,
                'package_name' => $orgSubscription->product_type,
                'length' => (isset($orgSubscription->PlanDetail->subscription_length) && (int) $orgSubscription->PlanDetail->subscription_length > 0 ? $orgSubscription->PlanDetail->subscription_length . ' ' . $orgSubscription->PlanDetail->subs_length_time_type : true),
                'start_date' => $orgSubscription->start_date,
                'end_date' => $orgSubscription->end_date,
                'device_total' => (string) ($orgSubscription->PlanDetail->subscription_devices ?? ''),
                'platform' => $orgSubscription->PlanDetail->platforms ?? [],
                'assign_device' => $orgSubscription->device,
                'status' => $orgSubscription->is_active == '1' ? 'Active' : 'Inactive',
                'auto_renew' => $orgSubscription->PlanDetail->autopay == '1' ? 'true' : 'false',
            ];
        } else {
            // Return empty if no subscription or device not authorized
            $user['subscription_detail'] = [];
        }

        // 9. Record the login event in device logs
        if ($device) {
            SubDeviceLog::updateOrCreate(
                ['subscriber_id' => $user->id, 'device_id' => $device->id],
                ['plan_id' => ($orgSubscription && $hasSubscriptionAccess) ? ($orgSubscription->PlanDetail->id ?? null) : null, 'login_time' => Carbon::now(), 'ip_address' => $request->ip()]
            );
        }

        // 10. Return the final successful login response
        $finalCount = SubscriberAssignedDevice::where('subscriber_id', $user->id)->where('deletable', 0)->count();
        return response()->json([
            'success' => true,
            'message' => $subscriptionMessage,
            'token' => $token,
            'data' => $user,
            // 'device_slots' => [
            //     'total' => (string) $effectiveLimit,
            //     'used' => $finalCount,
            //     'remaining' => max(0, (int) $effectiveLimit - $finalCount)
            // ]
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

    /**
     * Unassign / Logout a specific device
     */
    public function deviceLogout(Request $request)
    {
        Log::info('Device logout request initiated', $request->all());

        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
            'device_id' => 'required_without:assigned_device_id|integer',
            'assigned_device_id' => 'required_without:device_id|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Verify credentials
        $user = OrgSubscribers::where('user_name', $request->user_name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Device logout failed: Invalid credentials', ['user_name' => $request->user_name]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $assignedDeviceQuery = SubscriberAssignedDevice::where('subscriber_id', $user->id)->where('deletable', 0);

        if ($request->has('assigned_device_id')) {
            $assignedDeviceQuery->where('id', $request->assigned_device_id);
        } else {
            $assignedDeviceQuery->where('device_id', $request->device_id);
        }

        $assignedDevice = $assignedDeviceQuery->first();

        if ($assignedDevice) {
            Log::info('Device found. Unassigning...', ['device_id' => $request->device_id, 'subscriber_id' => $user->id]);
            $assignedDevice->deletable = 1;
            $assignedDevice->is_active = 0;
            $assignedDevice->save();

            // Sync the device names array in the subscription record
            $orgSubscription = OrgSubscriberAndPayment::where('subscriber_id', $user->id)
                ->where('is_active', '1')
                ->first();

            if ($orgSubscription) {
                $this->syncSubscriptionDevices($user->id, $orgSubscription);
                Log::info('Subscription device list synced after logout.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Device logged out successfully'
            ], 200);
        }

        Log::warning('Device not found or not assigned', ['device_id' => $request->device_id]);
        return response()->json([
            'success' => false,
            'message' => 'Device not found or not assigned to this user'
        ], 404);
    }

    /**
     * Helper to sync active device names to the subscription record
     */
    private function syncSubscriptionDevices($subscriberId, $orgSubscription)
    {
        $activeDeviceNames = SubscriberAssignedDevice::where('subscriber_id', $subscriberId)
            ->where('deletable', 0)
            ->pluck('device_name')
            ->toArray();

        $orgSubscription->device = $activeDeviceNames;
        $orgSubscription->save();
    }

    /**
     * Auto detect device type based on MAC, Brand or Identifier
     *
     * @param string $mac
     * @param string $brand
     * @param string $identifier
     * @return string
     */
    private function detectDeviceType($mac, $brand, $identifier)
    {
        if (!$mac && !$brand && !$identifier) {
            return 'Web';
        }

        $mac = strtoupper(str_replace([':', '-'], '', (string) $mac));
        $brand = strtolower((string) $brand);
        $identifier = strtolower((string) $identifier);

        // MAG Device Detection (00:1A:79 prefix)
        if (strpos($mac, '001A79') === 0) {
            return 'MAG';
        }

        // iOS Detection
        if (strpos($brand, 'iphone') !== false || strpos($brand, 'ipad') !== false || strpos($brand, 'ios') !== false || strpos($brand, 'apple') !== false) {
            return 'iOS';
        }

        // Windows Detection
        if (strpos($brand, 'windows') !== false || strpos($brand, 'pc') !== false) {
            return 'Windows';
        }

        // Web/Browser Detection
        if (strpos($brand, 'web') !== false || strpos($brand, 'browser') !== false || strpos($brand, 'chrome') !== false || strpos($brand, 'safari') !== false) {
            return 'Web';
        }

        // Default to Android for most STBs/Mobile
        if (strpos($brand, 'android') !== false || strpos($brand, 'smart') !== false || strpos($brand, 'tv') !== false) {
            return 'Android';
        }

        return 'Android'; // Default fallback
    }

    /**
     * SSE Stream for real-time device updates
     * 
     * @param Request $request
     */
    public function getStream(Request $request)
    {
        // IMPORTANT: Release session lock to prevent blocking other requests!
        if (session_id()) {
            session_write_close();
        }

        // Disable any output buffering
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache, no-transform');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        header('Access-Control-Allow-Origin: *'); // Force CORS for stream

        set_time_limit(0);

        $subscriberId = $request->query('subscriber_id');
        // Log::info('🟢 SSE: Stream connection established for subscriber', ['id' => $subscriberId]);

        // Find current count
        $lastCount = SubscriberAssignedDevice::where('subscriber_id', $subscriberId)->count();

        // Initial response headers to the client
        echo "data: " . json_encode(['connected' => true, 'id' => $subscriberId]) . "\n\n";

        while (true) {
            if (connection_aborted()) {
                // Log::info('🔴 SSE: Connection closed by client', ['id' => $subscriberId]);
                break;
            }

            // Check database
            $currentCount = SubscriberAssignedDevice::where('subscriber_id', $subscriberId)->count();

            if ($currentCount > $lastCount) {
                // Log::info('✨ SSE: Change detected! Sending event', ['old' => $lastCount, 'new' => $currentCount]);
                echo "event: deviceUpdate\n";
                echo "data: " . json_encode(['update' => true, 'count' => $currentCount]) . "\n\n";
                $lastCount = $currentCount;
            } else {
                // Heartbeat to keep connection alive
                echo "event: ping\n";
                echo "data: " . time() . "\n\n";
            }

            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();

            // Wait 2 seconds
            sleep(2);
        }
    }
}


// if ($request->hasAny(['mac_address', 'identifier', 'device_type'])) {
//     $macAddress = $request->input('mac_address');
//     $identifier = $request->input('identifier');

//     $deviceQuery = OrgDevices::where('subscriber_id', $user->id);
//     if ($macAddress) {
//         $deviceQuery->where('mac_address', $macAddress);
//     } elseif ($identifier) {
//         $deviceQuery->where('identifier', $identifier);
//     } else {
//         // If neither is strictly present, force no match so it creates a new record
//         $deviceQuery->where('id', 0);
//     }

//     $device = $deviceQuery->first();

//     if (!$device) {
//         $device = new OrgDevices();
//         $device->subscriber_id = $user->id;
//         $device->device_type = $request->input('device_type');
//         $device->brand_model = $request->input('brand_model');
//         if ($macAddress) {
//             $device->mac_address = $macAddress;
//         }
//         $device->serial_number = $request->input('serial_number');
//         if ($identifier) {
//             $device->identifier = $identifier;
//         }
//         $device->ip_address = $request->input('ip_address', $request->ip());
//         $device->city = $request->input('city');
//         $device->country = $request->input('country');
//         $device->latitude = $request->input('latitude');
//         $device->longitude = $request->input('longitude');
//         $device->is_active = 1;
//     }

//     // Only update last_session for existing devices (and set for new ones)
//     $device->last_session = date('Y-m-d H:i:s');
//     $device->save();
// }