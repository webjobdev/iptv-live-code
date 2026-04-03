<?php

namespace Contus\Settings\Repositories;

use Contus\Base\Repository;
use Contus\Settings\Model\GeneralSetting;
use Illuminate\Support\Facades\Log;

class DashboardConfigurationRepository extends Repository
{

    protected $_dashCon;

    public function __construct(GeneralSetting $generalSetting)
    {
        parent::__construct();
        $this->_dashCon = $generalSetting;
    }

    // public function postUpdate()
    // {
    //     $requestData = $this->request->all();
    //     foreach ($requestData as $key => $value) {
    //         $config = GeneralSetting::where('key', $key)->first();
    //         $config->value = $value;

    //         Log::info($config);

    //         // $config->save();
    //         return true;
    //     }
    // }

    public function postUpdate()
    {
        $requestData = $this->request->all();

        foreach ($requestData as $key => $value) {
            if (in_array($key, ['_token', '_method'])) {
                continue;
            }

            $config = GeneralSetting::where('key', $key)->first();

            if ($config) {
                $config->value = is_array($value) ? json_encode($value) : $value;
                $config->save();

                // Log::info("Updated setting:", [
                //     'key' => $key,
                //     'new_value' => $config->value,
                // ]);
            } else {
                Log::warning("GeneralSetting key not found:", ['key' => $key]);
            }
        }

        return response()->json(['status' => true, 'message' => 'Settings updated successfully']);
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_dashCon);
        return $this;
    }
}