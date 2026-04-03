<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\PaymentHistoryRepository;


class PaymentHistoryController extends ApiController {
    public function __construct(PaymentHistoryRepository $paymentHistoryRepository) {
        parent::__construct();
        $this->repository = $paymentHistoryRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postadd() {
        $isCredited = false;
        if ($this->repository->addcomment()) {
            $isCredited = true;
            return ($isCredited) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.payment_history.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.payment_history.error'));
        }
    }
}
