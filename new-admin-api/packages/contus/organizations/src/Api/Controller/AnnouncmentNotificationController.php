<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AnnouncmentNotificationRepository;
use Google\Service\Classroom\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncmentNotificationController extends ApiController {

    public function __construct(AnnouncmentNotificationRepository $notificationRepository) {
        parent::__construct();
        $this->repository = $notificationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        $authID = $user->id ?? '';
        
        return $this->getSuccessJsonResponse([
            'info' => [

                'announcment_info' => $this->repository->morenotification(),
                'announcements' => $this->repository->announcements(),
                'todayAnnouncementCount' => $this->repository->todayAnnouncementCount(),

                'current_user' => [
                    'user_id' => $authID
                ]
            ]
        ]);
    }
}
