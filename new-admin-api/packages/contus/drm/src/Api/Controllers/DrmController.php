<?php

namespace Contus\Drm\Api\controllers;

use Contus\Base\ApiController;
use Contus\Drm\Repositories\DrmRepository;

class DrmController extends ApiController
{

    public function __construct(DrmRepository $drmRepository)
    {
        parent::__construct();
        $this->repository = $drmRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->adddrm()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('drm::index.add.success')])
                : $this->getErrorJsonResponse([], trans('drm::index.add.error'));
        }
    }
}
