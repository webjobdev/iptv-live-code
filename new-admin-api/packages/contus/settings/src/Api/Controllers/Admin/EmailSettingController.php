<?php

namespace Contus\Settings\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Settings\Model\GeneralSetting;
use Contus\Settings\Repositories\EmailSettingRepository;

class EmailSettingController extends ApiController {

    protected $generalSetting;

    public function __construct(EmailSettingRepository $generalSettingRepository) {
        parent::__construct();
        $this->repository = $generalSettingRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function saveRecords() {
        $isSave = false;
        if ($this->repository->saveSettingdata()) {
            $isSave = true;
        }
        return ($isSave) ? $this->getSuccessJsonResponse(['message' => 'Setting Data Saved Successfully.'])
            : $this->getErrorJsonResponse([], 'Error Occured!');
    }

    public function saveTenantRecords() {
        $isSave = false;
        if ($this->repository->saveTenantSettingdata()) {
            $isSave = true;
        }
        return ($isSave) ? $this->getSuccessJsonResponse(['message' => 'Setting Data Saved Successfully.'])
            : $this->getErrorJsonResponse([], 'Error Occured!');
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse();
    }

    public function getRecords() {
        return $this->getSuccessJsonResponse([
            'info' => [
                'setting_data' => $this->repository->setting_data(),
            ]
        ]);
    }

    public function getSettingsRecords() {
        return GeneralSetting::all();
    }
}
