<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\CreditCardRepository;
use Illuminate\Support\Facades\Auth;

class CreditCardController extends ApiController {
    public function __construct(CreditCardRepository $creditCardRepository) {
        parent::__construct();
        $this->repository = $creditCardRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd() {
        $isCredited = false;
        if ($this->repository->addorupdatecreditcard()) {
            $isCredited = true;
            return ($isCredited) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.credit_card.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.credit_card.error'));
        }
    }

    public function postEdit($creditcardId) {
        $isCredited = false;
        if ($this->repository->addorupdatecreditcard($creditcardId)) {
            $isCredited = true;
            return ($isCredited) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.credit_card_update.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.credit_card_update.error'));
        }
    }
}
