<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\VodRepository;

class VodController extends ApiController {

    public function __construct(VodRepository $vodRepository) {
        parent::__construct();
        $this->repository = $vodRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'error' => 'false',
            'message' => 'Success'
        ], 200);
    }

    public function addVod() {
        $isCreated = false;
        if ($this->repository->addVodList()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.cancel.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.cancel.error'));
        }
    }

    public function postEdit($vodId) {
        $isCreated = false;
        if ($this->repository->addVodList($vodId)) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.cancel.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.cancel.error'));
        }
    }
}
