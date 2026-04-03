<?php

namespace Contus\Devices\Repositories;

use Contus\Devices\Model\Device;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Customer\Models\Subscribers;
use Contus\Devices\Model\DeviceOraganization;
use Contus\Organizations\Model\Organization;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeviceRepository extends Repository
{

    protected $_device;

    public function __construct(OrgDevices $device)
    {
        parent::__construct();
        $this->_device = $device;
    }

    public function prepareGrid()
    {
        $data = $this->setGridModel($this->_device)->setEagerLoadingModels(['getAllOrganization']);
        return $this;
    }

    public function addDevices()
    {
        $this->setRules([
            'deviceData.mac' => 'required',
            'deviceData.serial' => 'required',
            'deviceData.device_type' => 'nullable',
            'deviceData.identifier' => 'nullable',
            'deviceData.timezone' => 'nullable',
            'deviceData.organization' => 'required',
            'deviceData.subscribers' => 'nullable',
            'deviceData.brand_model' => 'nullable',
            'deviceData.firmware_version' => 'nullable',
            'deviceData.ip_address' => 'nullable',
            'deviceData.isp' => 'required',
            'deviceData.location' => 'required',
            'deviceData.latitude' => 'nullable',
            'deviceData.longitude' => 'nullable',
        ]);

        $this->_validate();

        $macAddress = $this->request->input('deviceData')['mac'] ?? [];
        $serialNos = $this->request->input('deviceData')['serial'] ?? [];
        $identifiers = $this->request->input('deviceData')['identifier'] ?? [];
        $deviceModels = $this->request->input('deviceData')['brand_model'] ?? [];
        $firmwareVersions = $this->request->input('deviceData')['firmware_version'] ?? [];
        $ipAddress = $this->request->input('deviceData')['ip_address'] ?? [];
        $deviceTypes = $this->request->input('deviceData')['device_type'] ?? [];

        for ($i = 0; $i < count($macAddress); $i++) {
            $device = new OrgDevices();
            $device->mac_address = $macAddress[$i];
            $device->serial_number = $serialNos[$i];

            $detectedType = is_array($deviceTypes) ? ($deviceTypes[$i] ?? null) : $deviceTypes;
            if (empty($detectedType)) {
                $detectedType = $this->request->input('deviceData.device_type');
            }
            if (empty($detectedType)) {
                $detectedType = $this->detectDeviceType($macAddress[$i] ?? '', is_array($deviceModels) ? ($deviceModels[$i] ?? '') : $deviceModels, is_array($identifiers) ? ($identifiers[$i] ?? '') : $identifiers);
            }
            $device->device_type = $detectedType;

            $device->device_redirect = $this->request->input('deviceData.device_redirect') ?? '0';
            $device->identifier = is_array($identifiers) ? ($identifiers[$i] ?? null) : $identifiers;
            $device->timezone = $this->request->input('deviceData.timezone') ?? null;

            $device->security_code_required = $this->request->input('deviceData.security_code_required') ?? $this->request->input('deviceData.security_code_req') ?? null;
            $device->security_code = $this->request->input('deviceData.security_code') ?? null;

            $device->subscriber_id = $this->request->input('deviceData.subscribers');

            $device->brand_model = is_array($deviceModels) ? ($deviceModels[$i] ?? null) : $deviceModels;
            $device->firmware_version = is_array($firmwareVersions) ? ($firmwareVersions[$i] ?? null) : $firmwareVersions;
            $device->ip_address = is_array($ipAddress) ? ($ipAddress[$i] ?? null) : $ipAddress;
            $device->isp = $this->request->input('deviceData.isp') ?? null;
            $device->location = $this->request->input('deviceData.location') ?? null;
            $device->latitude = $this->request->input('deviceData.latitude') ?? null;
            $device->longitude = $this->request->input('deviceData.longitude') ?? null;
            $device->is_active = $this->request->input('deviceData.status') ?? '0';

            $device->create_subscriber = $this->request->input('deviceData.create_subscriber') ?? $this->request->input('deviceData.create_subscribers') ?? null;
            if ($this->request->hasFile('list')) {
                $file = $this->request->file('list');
                if ($file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('uploads/serial-mac-list');
                    $file->move($destinationPath, $filename);
                    $device->list = 'uploads/serial-mac-list/' . $filename;
                }
            }

            $device->first_value = $this->request->input('deviceData.first_value') ?? null;
            $device->serial_mac_seperator = $this->request->input('deviceData.serial_mac_seperator') ?? $this->request->input('deviceData.seperator') ?? null;

            if ($this->request->hasFile('parse_file')) {
                $file = $this->request->file('parse_file');
                if ($file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('uploads/parse-files');
                    $file->move($destinationPath, $filename);
                    $device->parse_file = 'uploads/parse-files/' . $filename;
                }
            }
            // dd($device);
            $device->save();

            $userId = Auth::id();

            foreach ($this->request->input('deviceData.organization') as $org) {
                $orgId = is_array($org) ? ($org['id'] ?? $org['organization_id'] ?? null) : $org;

                if ($orgId) {
                    DeviceOraganization::updateOrCreate([
                        'subscriber_device_id' => $device->id,
                        'organization_id' => $orgId
                    ], [
                        'create_by' => $userId
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => trans('devices::index.add.success'),
        ]);
    }

    // public function addMultipleDevice() {
    //     $this->setRules([
    //         'mac_add' => 'required',
    //         'serial_number' => 'required',
    //         'organization' => 'required',
    //     ]);

    //     $macAddress = json_decode($this->request->input('mac_add'), true);
    //     $serialNos = json_decode($this->request->input('serial_number'), true);
    //     $identifiers = json_decode($this->request->input('identifier'), true);
    //     $deviceModels = json_decode($this->request->input('brand_model'), true);
    //     $firmwareVersions = json_decode($this->request->input('firmware_version'), true);
    //     $ipAddress = json_decode($this->request->input('ip_address'), true);

    //     for ($i = 0; $i < count($macAddress); $i++) {
    //         $device = new Device();
    //         $device->device_redirect = $this->request->input('device_redirect') ? '1' : '0';
    //         $orgs = json_decode($this->request->input('organization'), true);
    //         $selectedOrgs = [];
    //         foreach ($orgs as $org) {
    //             $selectedOrgs[] = $org['organization_id'];
    //         }
    //         $device->organization_id = $selectedOrgs;
    //         $device->create_subscriber = $this->request->input('create_subscribers');

    //         if ($this->request->hasFile('list_file')) {
    //             $file = $this->request->file('list_file');
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . $file->getClientOriginalName();
    //                 $destinationPath = public_path('uploads/serial-mac-list');
    //                 $file->move($destinationPath, $filename);
    //                 $device->list = 'uploads/serial-mac-list/' . $filename;
    //             }
    //         }

    //         $device->mac_address = [$macAddress[$i]];
    //         $device->serial_number = [$serialNos[$i]];
    //         $device->first_value = $this->request->input('first_value');
    //         $device->serial_mac_seperator = $this->request->input('seperator');

    //         if ($this->request->hasFile('parse_file')) {
    //             $file = $this->request->file('parse_file');
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . $file->getClientOriginalName();
    //                 $destinationPath = public_path('uploads/parse-files');
    //                 $file->move($destinationPath, $filename);
    //                 $device->parse_file = 'uploads/parse-files/' . $filename;
    //             }
    //         }
    //         $device->identifier = [$identifiers[$i]];
    //         $device->brand_model = [$deviceModels[$i]];
    //         $device->firmware_version = [$firmwareVersions[$i]];
    //         $device->ip_address = [$ipAddress[$i]];
    //         $device->isp = $this->request->input('isp');
    //         $device->location = $this->request->input('location');

    //         $device->save();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => trans('devices::index.add.success'),
    //     ]);
    // }


    public function updateDevices($id)
    {
        $this->setRules([
            'mac_address' => 'required',
            'serial_number' => 'required',
            'device_type' => 'nullable',
            'identifier' => 'required',
            'timezone' => 'required',
            'organization_id' => 'required',
            'subscribers' => 'required',
            'brand_model' => 'required',
            'firmware_version' => 'required',
            'ip_address' => 'required',
            'isp' => 'required',
            'location' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'organization' => 'required',
        ]);

        $device = OrgDevices::find($id);
        $device->mac_address = $this->request->input('mac_address') ?? $this->request->input('mac_add');
        $device->serial_number = $this->request->input('serial_number');

        $deviceType = $this->request->input('device_type');
        if (empty($deviceType)) {
            $deviceType = $this->detectDeviceType($device->mac_address, $this->request->input('brand_model'), $this->request->input('identifier'));
        }
        $device->device_type = $deviceType;

        $device->device_redirect = $this->request->input('device_redirect') ? '1' : '0';
        $device->identifier = $this->request->input('identifier');
        $device->timezone = $this->request->input('timezone');

        $orgs = $this->request->input('organization');
        // $device->organization_id = $selectedOrgs;
        // $device->organization_id = $this->request->input('organization_id');
        $device->security_code_required = $this->request->input('security_code_required') ?? $this->request->input('security_code_req');
        $device->security_code = $this->request->input('security_code') ?? '119493';

        $device->subscriber_id = $this->request->input('subscribers');

        // $selectedSubs = [];
        // foreach ($subs as $subc) {
        //     $selectedSubs[] = $subc['id'];
        // }
        // $device->assigned_subscribers = $selectedSubs;
        // if (!empty($selectedSubs)) {
        //     $device->subscriber_id = $selectedSubs[0];
        // }

        $device->brand_model = $this->request->input('brand_model');
        $device->firmware_version = $this->request->input('firmware_version');
        $device->ip_address = $this->request->input('ip_address');
        $device->isp = $this->request->input('isp');
        $device->location = $this->request->input('location');
        $device->latitude = $this->request->input('latitude');
        $device->longitude = $this->request->input('longitude');
        $device->is_active = $this->request->input('is_active') == true ? '1' : '0';

        $device->first_value = $this->request->input('first_value') ?? null;
        $device->serial_mac_seperator = $this->request->input('serial_mac_seperator') ?? $this->request->input('seperator') ?? null;
        $device->create_subscriber = $this->request->input('create_subscriber') ?? $this->request->input('create_subscribers') ?? null;

        // if ($this->request->hasFile('parse_file')) {
        //     $file = $this->request->file('parse_file');
        //     if ($file->isValid()) {
        //         $filename = time() . '_' . $file->getClientOriginalName();
        //         $destinationPath = public_path('uploads/parse-files');
        //         $file->move($destinationPath, $filename);
        // }
        if ($this->request->hasFile('parse_file')) {
            $device->parse_file = 'true';
        }
        $device->save();

        $userId = Auth::id();
        foreach ($orgs as $org) {
            $orgId = is_array($org) ? ($org['id'] ?? $org['organization_id'] ?? null) : $org;
            if ($orgId) {
                DeviceOraganization::updateOrCreate([
                    'subscriber_device_id' => $device->id,
                    'organization_id' => $orgId
                ], [
                    'create_by' => $userId
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => trans('devices::index.update.success'),
        ]);
    }

    // public function searchByValue() {
    //     $searchVal = $this->request->input('searchVal');
    //     $macAddress = $this->request->input('mac_address');
    //     $serialNo = $this->request->input('serial_number');
    //     $identifier = $this->request->input('identifier');
    //     $device = Device::where('mac_address', 'like', "%$searchVal%")->OrWhere('serial_number', 'like', "%$searchVal%")->OrWhere('identifier', 'like', "%$searchVal%")->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $device,
    //         'message' => trans('devices::index.fetch-data.success'),
    //     ]);
    // }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {

            if ($key == 'mac_address') {
                Log::info("Value ", [ucfirst($value)]);
                $searchValue = ucfirst($value);
                $builderCoupon->where('mac_address', 'LIKE', "%{$searchValue}%");
                continue;
                Log::info("Data ", [$data]);
            }

            if ($key == 'serial_number') {
                $searchValue = ucfirst($value);
                $builderCoupon->where('serial_number', 'LIKE', "%{$searchValue}%");
                continue;
            }

            if ($key == 'identifier') {
                $searchValue = ucfirst($value);
                $builderCoupon->where('identifier', 'LIKE', "%{$searchValue}%");
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

    public function removeDevice($id)
    {
        $device = Device::find($id);
        if (!$device) {
            return false;
        }
        $device->delete();

        return true;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('devices::index.ip_address'), 'value' => 'ip_address', 'sort' => true, 'class' => false],
                ['name' => trans('devices::index.model'), 'value' => 'brand_model', 'sort' => true, 'class' => false],
                ['name' => trans('devices::index.mac_address'), 'value' => 'mac_address', 'sort' => true, 'class' => false],
                ['name' => trans('serial_number'), 'value' => 'serial_number', 'sort' => false],
                ['name' => trans('devices::index.identifier'), 'value' => 'identifier', 'sort' => false],
                ['name' => trans('devices::index.firmware'), 'value' => 'firmware_version', 'sort' => false],
                ['name' => trans('devices::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }

    /**
     * Auto detect device type based on MAC, Brand or Identifier
     *
     * @param string $mac
     * @param string $brand
     * @param string $identifier
     * @return string
     */
    protected function detectDeviceType($mac, $brand, $identifier)
    {
        $mac = strtoupper(str_replace([':', '-'], '', $mac));
        $brand = strtolower($brand);
        $identifier = strtolower($identifier);

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
}
