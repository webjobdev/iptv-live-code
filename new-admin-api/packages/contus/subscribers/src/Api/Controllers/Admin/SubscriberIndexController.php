<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\SubscriberIndexRepository;
use Illuminate\Support\Facades\Auth;

class SubscriberIndexController extends ApiController {
    public function __construct(SubscriberIndexRepository $subscriberIndexRepository) {
        parent::__construct();
        $this->repository = $subscriberIndexRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addSub()) {
            $isCreated = true;
            // $this->request->session()->flash(StringLiterals::SUCCESS, trans('cms::index.add.success'));
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.subscriber_add.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.subscriber_add.error'));
        }
    }

    public function getAll() {
        return $this->getSuccessJsonResponse([
            'data' => $this->repository->fetchdata()
        ]);
    }
}
