<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\OrganizationPaymentRepository;

class OrganizationPaymentController extends ApiController {

    public function __construct(OrganizationPaymentRepository $organizationPaymentRepository) {
        parent::__construct();
        $this->repository = $organizationPaymentRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function storepayment() {
        $isCreated = false;
        if ($this->repository->paymentstore()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('customer::subscription.add.success')])
                : $this->getErrorJsonResponse([], trans('customer::subscription.add.error'));
        }
    }

    public function failurepayment() {
        $isCreated = false;
        if ($this->repository->paymentfailure()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('customer::subscription.add.success')])
                : $this->getErrorJsonResponse([], trans('customer::subscription.add.error'));
        }
    }
}
