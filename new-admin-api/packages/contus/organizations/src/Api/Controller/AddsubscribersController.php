<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AddSubscriberRepository;
use Illuminate\Support\Facades\Auth;

class AddsubscribersController extends ApiController {

    public function __construct(AddSubscriberRepository $addSubscriberRepository) {
        parent::__construct();
        $this->repository = $addSubscriberRepository;
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
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.app_ctm_add.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.app_ctm_add.error'));
        }
    }
}
