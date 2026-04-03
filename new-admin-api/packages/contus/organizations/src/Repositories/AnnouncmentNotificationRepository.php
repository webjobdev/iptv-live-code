<?php

namespace Contus\Organizations\Repositories;

use Carbon\Carbon;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationAnnouncement;
use Illuminate\Support\Facades\Auth;

class AnnouncmentNotificationRepository extends Repository {

    protected $_notifiction;

    public function __construct(OrganizationAnnouncement $organizationAnnouncement) {
        parent::__construct();
        $this->_notifiction = $organizationAnnouncement;
    }

    public function morenotification() {
        $user = auth()->user();

        return OrganizationAnnouncement::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function todayAnnouncementCount() {
        $user = auth()->user();

        return OrganizationAnnouncement::where('created_by', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function announcements(){
        $user = auth()->user();
        return OrganizationAnnouncement::where('created_by', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    }


    public function prepareGrid() {
        $this->setGridModel($this->_notifiction);
        return $this;
    }
}
