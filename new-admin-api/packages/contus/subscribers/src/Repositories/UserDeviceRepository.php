<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDeviceRepository extends Repository
{
    protected $_devices;

    public function __construct(OrgDevices $orgDevices)
    {
        parent::__construct();
        $this->_devices = $orgDevices;
    }

    public function addsubscriberdevice($id = null)
    {
        // Log::info('[Device] Starting addsubscriberdevice.', ['id' => $id]);

        try {
            if (!empty($id)) {
                $subscription = $this->_devices->find($id);
                // if (!is_object($subscription)) {
                //     Log::warning('[Device] No device found for given ID.', ['id' => $id]);
                //     return false;
                // }

                // Log::info('[Device] Updating existing device.', ['device_id' => $id]);

                $this->setRules([
                    'device_type' => 'required|max:255',
                    'model_name' => 'required|max:255',
                    'mac_address' => 'required|max:255',
                    'serial_number' => 'required|max:255',
                    'identifier' => 'required|max:255',
                    'ip_address' => 'required|max:255',
                    'city' => 'required|max:255',
                    'country' => 'required|max:255',
                    'latitude' => 'required|max:255',
                    'longitude' => 'required|max:255',
                    'last_session' => 'required|max:255',
                    'is_active' => 'required|max:255',
                ]);

                // $this->_validate();

            } else {
                // Log::info('[Device] Creating new device record.');

                $this->setRules([
                    'device_type' => 'required|max:255',
                    'model_name' => 'required|max:255',
                    'mac_address' => 'required|max:255',
                    'serial_number' => 'required|max:255',
                    'identifier' => 'required|max:255',
                    'ip_address' => 'required|max:255',
                    'city' => 'required|max:255',
                    'country' => 'required|max:255',
                    'latitude' => 'required|max:255',
                    'longitude' => 'required|max:255',
                    'last_session' => 'required|max:255',
                    'is_active' => 'required|max:255',
                ]);

                // $this->_validate();

                $subscription = new OrgDevices();
                $subscription->is_active = 1;
            }


            // Assign request values to model
            foreach ($this->request->all() as $key => $value) {
                if ($subscription->isFillable($key)) {
                    $subscription->$key = $value;
                }
            }

            if ($subscription->save()) {
                // Log::info('[Device] Device saved successfully.', ['device_id' => $subscription->id]);
                return response()->json([
                    'success' => true,
                    'message' => trans('subscribers::index.subscriber_device_update.success'),
                ]);
            } else {
                // Log::warning('[Device] Failed to save device.', ['device_id' => $id ?? 'new']);
                return response()->json([
                    'success' => true,
                    'message' => trans('subscribers::index.subscriber_device.success'),
                ]);
            }
        } catch (\Exception $e) {
            // Log::error('[Device] Exception during save operation.', [
            //     'id' => $id,
            //     'error' => $e->getMessage(),
            // ]);
            return 0;
        }
    }
    public function prepareGrid()
    {
        $this->setGridModel($this->_devices);
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

    // public function prepareGrid() {
    //     $subscriberId = $this->request->route('id') ?? $this->request->route('subscriber_id');

    //     Log::info('[DeviceGrid] Preparing grid data.', [
    //         'subscriber_id' => $subscriberId
    //     ]);

    //     $query = $this->_devices;

    //     if (!empty($subscriberId)) {
    //         Log::info('[DeviceGrid] Filtering devices by subscriber_id.', [
    //             'subscriber_id' => $subscriberId
    //         ]);
    //         $query = $query->where('subscriber_id', $subscriberId);
    //     } else {
    //         Log::info('[DeviceGrid] No subscriber_id provided. Returning all devices.');
    //     }

    //     $this->setGridModel($query);

    //     Log::info('[DeviceGrid] Grid model set successfully.');

    //     return $this;
    // }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $device = $this->_devices->findOrFail($id);

            $device->is_active = $this->request->is_active ? 1 : 0;
            $device->save();

            return response()->json([
                'success' => true,
                'message' => 'Channel Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('organizations::index.brand_model'), 'value' => '', 'sort' => true],
                ['name' => trans('organizations::index.mac_address'), 'value' => '', 'sort' => false],
                ['name' => trans('organizations::index.serial_number'), 'value' => '', 'sort' => false],
                ['name' => trans('organizations::index.identifier'), 'value' => '', 'sort' => false],
                ['name' => trans('organizations::index.ip_address'), 'value' => '', 'sort' => false],
                ['name' => trans('Location'), 'value' => '', 'sort' => false],
                // ['name' => trans('organizations::index.country'), 'value' => '', 'sort' => false],
                // ['name' => trans('organizations::index.latitude'), 'value' => '', 'sort' => false],
                // ['name' => trans('organizations::index.longitude'), 'value' => '', 'sort' => false],
                // ['name' => trans('organizations::index.last_session'), 'value' => '', 'sort' => false],
                ['name' => trans('organizations::index.status'), 'value' => '', 'sort' => false],
                ['name' => trans('organizations::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builder)
    {
        $searchRecord = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecord as $key => $value) {
            if ($value === null || $value === '' || $value === 'all') {
                continue;
            }

            if ($key === 'is_active' || $key === 'status') {
                $builder = $builder->where('is_active', (int) $value);
            } else {
                $builder = $builder->where($key, 'like', '%' . $value . '%');
            }
        }

        return $builder;
    }

    public function deviceinfo()
    {
        // return OrgDevices::where('subscriber_id', $subscriber_id)->get();
        return OrgDevices::all();
    }

}
