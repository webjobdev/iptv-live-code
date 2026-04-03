<?php

namespace Contus\Organizations\Api\Controller\PaymentServices;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\PaymentServices\PaymentServiceCurrencyConverterRepository;

class PaymentServiceCurrencyConverterController extends ApiController
{
    public function __construct(PaymentServiceCurrencyConverterRepository $psCurrencyConverterRepository)
    {
        parent::__construct();
        $this->repository = $psCurrencyConverterRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $create = $this->repository->postAdd();
        if ($create == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Payment converter created successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $create);
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
}