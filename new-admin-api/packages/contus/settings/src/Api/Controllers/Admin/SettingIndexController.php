<?php

namespace Contus\Settings\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Settings\Repositories\SettingIndexRepository;

class SettingIndexController extends ApiController {

    protected $settingIndex;

    public function __construct(SettingIndexRepository $settingIndexRepository) {
        parent::__construct();
        $this->repository = $settingIndexRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addsettingdata()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('drm::index.add.success')])
                : $this->getErrorJsonResponse([], trans('drm::index.add.error'));
        }
    }

    public function postEdit($settingId) {
        $isCreated = false;
        if ($this->repository->addsettingdata($settingId)) {
            $isCreated = true;
        }
        return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('customer::subscription.update.success')])
            : $this->getErrorJsonResponse([], trans('cms::subscription.update.error'));
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
}
