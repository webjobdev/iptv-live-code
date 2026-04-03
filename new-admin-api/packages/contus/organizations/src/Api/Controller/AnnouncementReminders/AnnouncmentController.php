<?php

namespace Contus\Organizations\Api\Controller\AnnouncementReminders;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncmentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnnouncmentController extends ApiController {

    public function __construct(AnnouncmentRepository $announcment_repository) {
        parent::__construct();
        $this->repository = $announcment_repository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd(Request $request) {
        $isCreated = false;
        if ($this->repository->addAnnouncement()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.anc_send.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.anc_send.error'));
        }
    }


    public function postEdit($announcmentId) {
        $isCreated = false;

        if ($this->repository->addOrUpdateAnnouncment($announcmentId)) {
            $isCreated = true;
            $this->request->session()->flash('success', trans('organizations::index.update.success'));
        }

        return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.update.success')])
            : $this->getErrorJsonResponse([], trans('organizations::index.update.error'));
    }
}
