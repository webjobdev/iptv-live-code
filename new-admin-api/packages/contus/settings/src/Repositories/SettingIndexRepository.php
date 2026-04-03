<?php

namespace Contus\Settings\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Settings\Model\SubscriberSetting;
use Illuminate\Support\Facades\Log;

class SettingIndexRepository extends Repository
{
    protected $_setting;

    public function __construct(SubscriberSetting $setting)
    {
        parent::__construct();
        $this->_setting = $setting;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_setting);
        return $this;
    }

    public function addsettingdata($id = null)
    {
        // Log::info('[setting] Starting addsettingdata.', ['id' => $id]);
        if (!empty($id)) {
            $settingsubscription = $this->_setting->find($id);
            if (!is_object($settingsubscription)) {
                // Log::warning('[Device] No device found for given ID.', ['id' => $id]);
                return false;
            }

            // Log::info('[Device] Updating existing device.', ['device_id' => $id]);

            $this->setRules([
                'product_type' => 'nullable|max:255',
                'days' => 'nullable|max:255',
                'accessories_name' => 'nullable|max:255',
                'device_type' => 'nullable|max:255',
                'month_type' => 'nullable|max:255',
                'price' => 'nullable|max:255',
                'is_active' => 'nullable|max:255',
            ]);
        } else {
            // Log::info('[Device] Creating new device record.');

            $this->setRules([
                'product_type' => 'nullable|max:255',
                'days' => 'nullable|max:255',
                'accessories_name' => 'nullable|max:255',
                'device_type' => 'nullable|max:255',
                'month_type' => 'nullable|max:255',
                'price' => 'nullable|max:255',
                'is_active' => 'nullable|max:255',
            ]);

            $settingsubscription = new SubscriberSetting();

            $settingsubscription->product_type = $this->request->get('product_type');
            $settingsubscription->accessories_name = $this->request->get('accessories_name');
            $settingsubscription->days = $this->request->get('days');
            $settingsubscription->device_type = $this->request->get('device_type');
            $settingsubscription->month_type = $this->request->get('month_type');
            $settingsubscription->price = $this->request->get('price');
            $settingsubscription->is_active = $this->request->get('is_active', 0);
        }
        $this->_validate();

        // Assign request values to model
        foreach ($this->request->all() as $key => $value) {
            if ($settingsubscription->isFillable($key)) {
                $settingsubscription->$key = $value;
            }
        }

        if ($settingsubscription->save()) {
            // Log::info('[Device] Device saved successfully.', ['device_id' => $settingsubscription->id]);
            return 1;
        } else {
            // Log::warning('[Device] Failed to save device.', ['device_id' => $id ?? 'new']);
            return 0;
        }
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('settings::index.product_type'), 'value' => '', 'sort' => true],
                ['name' => trans('settings::index.days'), 'value' => '', 'sort' => true],
                ['name' => trans('settings::index.accessories_name'), 'value' => '', 'sort' => true],
                ['name' => trans('settings::index.month_type'), 'value' => '', 'sort' => true],
                ['name' => trans('settings::index.device_type'), 'value' => '', 'sort' => false],
                ['name' => trans('settings::index.price'), 'value' => '', 'sort' => false],
                ['name' => trans('settings::index.active'), 'value' => '', 'sort' => false],
                ['name' => trans('settings::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }

    public function setting_data()
    {
        return SubscriberSetting::all();
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}
