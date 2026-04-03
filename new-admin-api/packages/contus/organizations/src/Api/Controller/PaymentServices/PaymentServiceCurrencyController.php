<?php

namespace Contus\Organizations\Api\Controller\PaymentServices;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\PaymentServices\PaymentServiceCurrencyRepository;

class PaymentServiceCurrencyController extends ApiController
{
    public function __construct(PaymentServiceCurrencyRepository $psCurrencyRepository)
    {
        parent::__construct();
        $this->repository = $psCurrencyRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $this->getSuccessJsonResponse(['success']);
    }

    public function postToggle()
    {
        $isUpdated = true;
        if ($this->repository->postToggle()) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

}