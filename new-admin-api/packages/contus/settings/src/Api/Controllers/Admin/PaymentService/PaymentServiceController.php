<?php

namespace Contus\Settings\Api\Controllers\Admin\PaymentService;

use Contus\Base\ApiController;
use Contus\Settings\Repositories\PaymentService\PaymentServiceRepository;

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

    public function postCreate()
    {
        $insert = $this->repository->postCreate();
        if ($insert == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Payment service provider created successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $insert);
        }
    }

    public function postEdit($id)
    {
        $isUpdated = true;
        if ($this->repository->postEdit($id)) {
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

    public function postDefault($id)
    {
        $isUpdated = true;
        if ($this->repository->postDefault($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }
}