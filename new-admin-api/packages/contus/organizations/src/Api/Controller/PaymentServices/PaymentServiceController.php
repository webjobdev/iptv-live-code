<?php

namespace Contus\Organizations\Api\Controller\PaymentServices;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\PaymentServices\PaymentServiceRepository;
// use Contus\Settings\Repositories\PaymentService\PaymentServiceRepository;

class PaymentServiceController extends ApiController
{
    public function __construct(PaymentServiceRepository $paymentServiceRepository)
    {
        parent::__construct();
        $this->repository = $paymentServiceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $this->getSuccessJsonResponse(['success']);
    }

    public function postEdit()
    {
        $isUpdated = true;
        if ($this->repository->postEdit()) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

    public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

    public function postDefault()
    {
        $isUpdated = true;
        if ($this->repository->postDefault()) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }
}