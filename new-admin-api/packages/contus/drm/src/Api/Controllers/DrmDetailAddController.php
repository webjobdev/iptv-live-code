<?php

namespace Contus\Drm\Api\Controllers;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Drm\Repositories\DrmDetailAddRepository;

class DrmDetailAddController extends ApiController {
    public function __construct(DrmDetailAddRepository $drmRepository) {
        parent::__construct();
        $this->repository = $drmRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }


    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addaccdrm()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('drm::index.add.success')])
                : $this->getErrorJsonResponse([], trans('drm::index.add.error'));
        }
    }
}
