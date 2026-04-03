<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\UserDeviceRepository;
use Illuminate\Support\Facades\Auth;

class UserDeviceController extends ApiController {

    public function __construct(UserDeviceRepository $userDeviceRepository) {
        parent::__construct();
        $this->repository = $userDeviceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    
}
