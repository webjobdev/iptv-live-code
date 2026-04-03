<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\CustomStreamRepository;

class CustomStreamController extends ApiController {

    public function __construct(CustomStreamRepository $customStreamRepository) {
        parent::__construct();
        $this->repository = $customStreamRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'error' => 'false',
            'message' => 'Success'
        ], 200);
    }

    public function addChannel() {
        $isCreated = false;
        if ($this->repository->addChannelList()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.cancel.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.cancel.error'));
        }
    }

    public function postEdit($channelId){
        $isCreated = false;
        if ($this->repository->addChannelList($channelId)){
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.cancel.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.cancel.error'));
        }
    }
}
