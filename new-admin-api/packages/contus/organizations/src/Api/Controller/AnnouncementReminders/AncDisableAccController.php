<?php

namespace Contus\Organizations\Api\Controller\AnnouncementReminders;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncementDisableAccRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AncDisableAccController extends ApiController {

    public function __construct(AnnouncementDisableAccRepository $ancDisableAccRepository) {
        parent::__construct();
        $this->repository = $ancDisableAccRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addDisableAcc()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.account-add.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.account-add.error'));
        }
    }
}
