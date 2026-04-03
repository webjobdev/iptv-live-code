<?php

namespace Contus\Settings\Repositories;

use Contus\Base\Repository;
use Contus\Settings\Model\GeneralSetting;
use Contus\User\Models\Setting;
use Illuminate\Support\Facades\Log;

class EmailSettingRepository extends Repository
{
    protected $_generalSetting;

    public function __construct(GeneralSetting $setting)
    {
        parent::__construct();
        $this->_generalSetting = $setting;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_generalSetting);
        return $this;
    }

    public function saveSettingdata()
    {
        $reqData = $this->request->all();

        foreach ($reqData as $rec) {
            if (is_array($rec) && isset($rec['id'])) {
                $settings = GeneralSetting::where('id', $rec['id'])->first();
                $settings->value = $rec['value'];
                $settings->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings Updated Successfully.',
        ]);
    }

    public function saveTenantSettingdata()
    {
        $reqData = $this->request->all();
        foreach ($reqData as $key => $value) {
            GeneralSetting::where('key', $key)->update(['value' => $value]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings Updated Successfully.',
        ]);
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => 'Name', 'value' => 'setting_name', 'sort' => true],
            ['name' => 'Value', 'value' => 'setting_value', 'sort' => true],
            ['name' => 'Hidden', 'value' => 'is_hidden', 'sort' => true],
            ['name' => trans('settings::index.action'), 'value' => '', 'sort' => false],
        ]];
    }

    public function setting_data()
    {
        return Setting::all();
    }
}
