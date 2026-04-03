<?php

namespace Contus\Organizations\Api\Controller\AnnouncementReminders;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncementReminders\AnnouncementPushNotificationRepository;

class AncPushNotificationController extends ApiController
{

    public function __construct(AnnouncementPushNotificationRepository $ancPushNotificationRepository)
    {
        parent::__construct();
        $this->repository = $ancPushNotificationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function addNotification()
    {
        $isCreated = false;
        if ($this->repository->addAncPushNotification()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse([
                'message' => trans('organizations::index.notiftn-add.success'),
                // 'data' => $this->repository->addAncPushNotification()->getData('data')['data']
            ])
                : $this->getErrorJsonResponse([], trans('organizations::index.notiftn-add.error'));
        }
    }
}
