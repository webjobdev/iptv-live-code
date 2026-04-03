<?php

namespace Contus\Organizations\Repositories\AnnouncementReminders;

use App\Models\User;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationAnnouncement;
use Contus\Base\Helpers\StringLiterals;
use Contus\Organizations\Model\AnnouncementSubscribers;
use Illuminate\Support\Facades\Auth;

class AnnouncmentRepository extends Repository
{

    protected $_announcment;

    public function __construct(OrganizationAnnouncement $organizationAnnouncement)
    {
        parent::__construct();
        $this->_announcment = $organizationAnnouncement;
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_announcment)->setEagerLoadingModels(['announcementSubscribers', 'user', 'announcementSubscribers.announcements', 'announcementSubscribers.subscriber']);
        return $this;
    }


    public function addAnnouncement()
    {
        $this->setRules([
            // 'user_id' => 'required|exists:users,id',
            'announcement' => 'nuallble|string|max:255',
            'subject' => 'required',
            'message' => 'required',
        ]);
        $this->_validate();

        $user = Auth::user();

        $subs = $this->request->input('subscribers');

        foreach ($subs as $item) {
            $announcement = new OrganizationAnnouncement();
            $announcement->organization_id = $this->request->input('organization_id');
            $announcement->subject = $this->request->input('subject');
            $announcement->message = $this->request->input('message');
            $announcement->created_by = $user->id;
            $announcement->announcement_subscribers_id = null;
            $announcement->save();

            if ($announcement) {
                $announcementSubscribers = new AnnouncementSubscribers();
                $announcementSubscribers->organization_id = $this->request->input('organization_id');
                $announcementSubscribers->announcement_id = $announcement->id;

                $announcementSubscribers->subscriber_id = $item['id'];
                $announcementSubscribers->save();
            }

            if ($announcementSubscribers) {
                $announcement->update(['announcement_subscribers_id' => $announcementSubscribers->id]);
            }
        }

        return response()->json([
            'status' => true,
            'data' => $announcement,
            'message' => trans('organizations::index.anc_send.success'),
        ]);
    }

    protected function searchFilter($builderSubscription)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        /**
         * Loop the search fields of subscriptions grid and use them to filter search results.
         */
        foreach ($searchRecordUsers as $key => $value) {

            if ($key == 'created_by') {
                $builderSubscription = $builderSubscription->whereHas('user', function ($query) use ($value) {
                    $query->where('name', 'like', "%$value%");
                });
                continue;
            }

            if ($key == 'created_at_from') {
                $builderSubscription->whereDate('created_at', '>=', $value);
                continue;
            }

            if ($key == 'created_at_to') {
                $builderSubscription->whereDate('created_at', '<', $value);
                continue;
            }
        }

        return $builderSubscription;
    }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('organizations::index.anc_created_at'), 'value' => 'created_at', 'sort' => true, 'class' => false],
                ['name' => trans('organizations::index.anc_created_by'), 'value' => 'created_by', 'sort' => true, 'class' => false],
                ['name' => trans('organizations::index.anc_subscribers'), 'value' => 'subscribers', 'sort' => false, 'class' => false],
                ['name' => trans('organizations::index.anc_subject'), 'value' => 'subject', 'sort' => false],
                ['name' => trans('organizations::index.anc_message'), 'value' => 'message', 'sort' => false],
            ]
        ];
    }
}
