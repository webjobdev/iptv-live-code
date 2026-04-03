<?php

namespace Contus\GeoBlocking\Api\Controllers;

use Contus\Base\ApiController;
use Contus\GeoBlocking\Repositories\GeoRestrictionRepository;

class GeoRestrictionController extends ApiController {

    public function __construct(GeoRestrictionRepository $ipRestrictionRepository) {
        parent::__construct();
        $this->repository = $ipRestrictionRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postCreate() {
        $isCreated = false;
        if ($this->repository->addGeoRestriction()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => "Geo Restriction Added Succefully."])
                : $this->getErrorJsonResponse([], 'Error Occurred while adding Geo Restriction.');
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->editGeoRestriction($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => 'Geo Restriction Updated Succefully.'])
                : $this->getErrorJsonResponse([], 'Error Occurred while updating Geo Restriction.');
        }
    }
}
