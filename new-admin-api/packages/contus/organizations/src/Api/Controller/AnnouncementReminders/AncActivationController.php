<?php

namespace Contus\Organizations\Api\Controller\AnnouncementReminders;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncementActivationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AncActivationController extends ApiController {

    public function __construct(AnnouncementActivationRepository $ancActivationRepository) {
        parent::__construct();
        $this->repository = $ancActivationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addAnnouncementReminder()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.activation-add.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.activation-add.error'));
        }
    }
}
