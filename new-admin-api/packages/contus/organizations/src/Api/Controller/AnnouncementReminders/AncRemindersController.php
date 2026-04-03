<?php

namespace Contus\Organizations\Api\Controller\AnnouncementReminders;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncementReminderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AncRemindersController extends ApiController
{

    public function __construct(AnnouncementReminderRepository $ancReminderepository)
    {
        parent::__construct();
        $this->repository = $ancReminderepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->addAnnouncementReminder()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.reminder-add.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.reminder-add.error'));
        }
    }

    public function postStatusUpdate()
    {
        $isUpdated = false;
        if ($this->repository->statusUpdate()) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.remndr-status.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.remndr-status.error'));
        }
    }

    public function postDestroy($id)
    {
        $isUpdated = false;
        if ($this->repository->deleteRecord($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.remndr-delete.success')])
                : $this->getErrorJsonResponse([], trans('organizations::index.remndr-delete.error'));
        }
    }
}
