<?php

namespace Contus\GeoBlocking\Api\Controllers;

use BadMethodCallException;
use Contus\GeoBlocking\Model\GeoBlocking;
use Contus\GeoBlocking\Repositories\GeoBlockingRepository;
use Contus\GeoBlocking\Repositories\DeviceRepository;
use Contus\Base\ApiController;
use Contus\Base\Contracts\GridableRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\GeoBlocking\Model\Device;
use Contus\GeoBlocking\Repositories\IpRestrictionRepository;

class IpRestrictionController extends ApiController {

    public function __construct(IpRestrictionRepository $ipBlockingRepository) {
        parent::__construct();
        $this->repository = $ipBlockingRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postCreate() {
        $isCreated = false;
        if ($this->repository->addIpRestrictions()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Ip Restriction Added Succefully.'])
                : $this->getErrorJsonResponse([], 'Error Occurred while adding Ip Restriction.');
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->editIpRestrictions($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => 'Ip Restriction Updated Succefully.'])
                : $this->getErrorJsonResponse([], 'Error Occurred while updating Ip Restriction.');
        }
    }
}
