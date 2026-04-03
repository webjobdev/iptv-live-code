<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\MonetizationPlanRepository;
use Illuminate\Support\Facades\Auth;

class MonetizationPlanController extends ApiController {

    public function __construct(MonetizationPlanRepository $monetizationPlanRepository) {
        parent::__construct();
        $this->repository = $monetizationPlanRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }
}
