<?php

namespace Contus\Drm\Api\Controllers;

use Contus\Base\ApiController;
use Contus\Drm\Repositories\DrmDetailProfileRepository;

class DrmProfileDetailsController extends ApiController {

    public function __construct(DrmDetailProfileRepository $drmDetailProfileRepository) {
        parent::__construct();
        $this->repository = $drmDetailProfileRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addprodrm()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('drm::index.add.success')])
                : $this->getErrorJsonResponse([], trans('drm::index.add.error'));
        }
    }
}
