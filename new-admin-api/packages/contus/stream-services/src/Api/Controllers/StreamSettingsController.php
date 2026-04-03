<?php

namespace Contus\StreamServices\Api\Controllers;

use Contus\StreamServices\Model\StreamServices;
use Contus\StreamServices\Repositories\StreamSettingRepository;
use Contus\Base\ApiController;

class StreamSettingsController extends ApiController {

    public function __construct(StreamSettingRepository $streamSettingRespository) {
        parent::__construct();
        $this->repository = $streamSettingRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addStreamSettings()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.policy_add.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.policy_add.error'));
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->updateStreamSettings($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.policy_update.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.policy_update.error'));
        }
    }

    public function postStatusEdit() {
        $isEdited = false;
        if ($this->repository->statusUpdate()) {
            $isEdited = true;
            return ($isEdited) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.status-update.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.status-update.error'));
        }
    }

    public function searchRecord() {
        $isEdited = false;
        $data = $this->repository->searchByName()->getData('data');
        if ($this->repository->searchByName()) {
            $isEdited = true;
            return ($isEdited) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('stream-services::index.fetch-data.success'),
                    'data' => $data['data'][0] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('stream-services::index.fetch-data.error'));
        }
    }
}
