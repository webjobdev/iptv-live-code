<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\UserDeviceRepository;
use Illuminate\Support\Facades\Auth;

class UserDeviceController extends ApiController {

    public function __construct(UserDeviceRepository $userDeviceRepository) {
        parent::__construct();
        $this->repository = $userDeviceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse([
            'data' => [
                'device_info' => $this->repository->deviceinfo(),
            ]
        ]);
    }

    public function sub_device($subscriber_id){
        return $this->getSuccessJsonResponse([
            // 'data' => [
            //     'deivce_info' => $this->repository->deviceInfo($subscriber_id)
            // ]
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addsubscriberdevice()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.subscriber_device.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.subscriber_device.error'));
        }
    }

    public function postEdit($subscriptionId) {
        $isCreated = false;
        if ($this->repository->addsubscriberdevice($subscriptionId)) {
            $isCreated = true;
        }
        return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.subscriber_device_update.success')])
            : $this->getErrorJsonResponse([], trans('subscribers::index.subscriber_device_update.error'));
    }

        public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Channel Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Channel Data Not Update.');
        }
    }
}
