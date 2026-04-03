<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Repositories\ActivationRepository;
use Contus\Subscribers\Repositories\AssignedDeviceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignedDeviceController extends ApiController
{
    public function __construct(AssignedDeviceRepository $assignedDeviceRepository)
    {
        parent::__construct();
        $this->repository = $assignedDeviceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function getassignedevice($subscriber_id)
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'assigned_device_info' => $this->repository->assigned_device_info($subscriber_id),
            ]
        ]);
    }

    /**
     * Set a device as primary for a subscriber.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPrimaryDevice()
    {
        $id = $this->request->input('id');
        $subscriberId = $this->request->input('subscriber_id');

        if (!$id || !$subscriberId) {
            return $this->getErrorJsonResponse([], 'Invalid parameters', 400);
        }

        try {
            return DB::transaction(function () use ($id, $subscriberId) {
                // Verify the device exists and belongs to this subscriber
                $record = SubscriberAssignedDevice::where('id', $id)
                    ->where('subscriber_id', $subscriberId)
                    ->first();

                if (!$record) {
                    return $this->getErrorJsonResponse([], 'Device record not found for this subscriber', 404);
                }

                // Reset all devices for this subscriber to not primary
                SubscriberAssignedDevice::where('subscriber_id', $subscriberId)->update(['is_primary' => 0]);

                // Set the selected device as primary
                $record->is_primary = 1;
                $record->save();

                return $this->getSuccessJsonResponse([], 'Device set as primary successfully.');
            });
        } catch (\Exception $e) {
            return $this->getErrorJsonResponse([], $e->getMessage(), 500);
        }
    }

    /**
     * Unlink a device from a slot.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteSlot()
    {
        $id = $this->request->input('id');

        if (!$id) {
            return $this->getErrorJsonResponse([], 'ID is required', 400);
        }

        $record = SubscriberAssignedDevice::find($id);
        if ($record) {

            $record->is_primary = 0;
            $record->is_active = 0;
            $record->deletable = 1;

            $record->save();

            if ($record->device_id) {
                OrgDevices::where('id', $record->device_id)->update(['is_active' => 0]);
            }

            return $this->getSuccessJsonResponse([], 'Device unlinked successfully.');
        }
        return $this->getErrorJsonResponse([], 'Record not found', 404);
    }

    public function postNotAssignDevice()
    {
        try {
            return $this->getSuccessJsonResponse([
                'data' => $this->repository->notAssignedDevices()
            ]);
        } catch (\Exception $e) {
            return $this->getErrorJsonResponse([], $e->getMessage(), 500);
        }
    }
}
