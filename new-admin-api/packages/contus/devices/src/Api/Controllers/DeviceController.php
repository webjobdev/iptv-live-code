<?php

namespace Contus\Devices\Api\Controllers;

use BadMethodCallException;
use Contus\Devices\Model\Devices;
use Contus\Devices\Repositories\DeviceRepository;
use Contus\Base\ApiController;
use Contus\Base\Contracts\GridableRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Devices\Model\Device;

class DeviceController extends ApiController {

    public function __construct(DeviceRepository $deviceRepository) {
        parent::__construct();
        $this->repository = $deviceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addDevices()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('devices::index.add.success')])
                : $this->getErrorJsonResponse([], trans('devices::index.add.error'));
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->updateDevices($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('devices::index.update.success')])
                : $this->getErrorJsonResponse([], trans('devices::index.update.error'));
        }
    }

    public function deleteRule($id) {
        $isDeleted = false;
        if ($this->repository->deleteRuleData($id)) {
            $isDeleted = true;
            return ($isDeleted) ? $this->getSuccessJsonResponse([
                'message' => trans('devices::index.delete.success'),
            ])
                : $this->getErrorJsonResponse([], trans('devices::index.delete.error'));
        }
    }

    public function postSearch() {
        $isSearched = false;
        $data = $this->repository->searchByValue()->getData('data');
        if ($this->repository->searchByValue()) {
            $isSearched = true;
            return ($isSearched) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('devices::index.fetch-data.success'),
                    'data' => $data,
                ])
                : $this->getErrorJsonResponse([], trans('devices::index.fetch-data.error'));
        }
    }

    public function getAllowedOrgs() {
        $isFetched = false;
        $data = $this->repository->getOrgList()->getData('data');
        if ($this->repository->getOrgList()) {
            $isFetched = true;
            return ($isFetched) ? $this->getSuccessJsonResponse([
                'message' => trans('devices::index.fetch-data.success'),
                'data' => $data,
            ]) : $this->getErrorJsonResponse([], trans('devices::index.fetch-data.error'));
        }
    }


    public function postAddMultiple() {
        $isCreated = false;
        if ($this->repository->addMultipleDevice()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('devices::index.multiple-add.success')]) : $this->getErrorJsonResponse([], trans('devices::index.multiple-add.error'));
        }
    }

    public function deleteDevice($id) {
        $isDeleted = false;
        if ($this->repository->removeDevice($id)) {
            $isDeleted = true;
            return ($isDeleted) ? $this->getSuccessJsonResponse([
                'message' => trans('devices::index.delete.success'),
            ])
                : $this->getErrorJsonResponse([], trans('devices::index.delete.error'));
        }
    }
}
