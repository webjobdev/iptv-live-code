<?php

namespace Contus\Organizations\Repositories\AnnouncementReminders;

use App\Models\User;
use Contus\Base\Repository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Organizations\Model\OrgAncPushNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Packages\Contus\Organizations\Src\Services\FCMService;

class AnnouncementPushNotificationRepository extends Repository
{

    protected $_ancPushNotification;

    public function __construct(OrgAncPushNotification $orgAncPushNotification)
    {
        parent::__construct();
        $this->_ancPushNotification = $orgAncPushNotification;
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_ancPushNotification)->setEagerLoadingModels(['user', 'orgSubscription']);
        return $this;
    }


    public function addAncPushNotification()
    {
        $this->setRules([
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required',
            'subscription' => 'required',
            'platform' => 'required',
            'resource_type' => 'required'
        ]);

        $this->_validate();

        $user = Auth::user();

        $ancPushNotification = new OrgAncPushNotification();
        $ancPushNotification->organization_id = $this->request->input('organization_id');
        $ancPushNotification->name = $this->request->input('name');
        $ancPushNotification->title = $this->request->input('title');
        $ancPushNotification->description = $this->request->input('description');
        $ancPushNotification->org_subscription_id = $this->request->input('subscription');
        $ancPushNotification->subscriber_status = $this->request->input('status_group') == true ? 1 : 0;
        $ancPushNotification->platform = $this->request->input('platform');
        $ancPushNotification->resource_type = $this->request->input('resource_type');
        $ancPushNotification->publish = $this->request->input('publish');
        $ancPushNotification->created_by = $user->id;
        $ancPushNotification->status = $this->request->input('status');
        $ancPushNotification->save();

        if (isset($user->fcm_token) && ($this->request->input('publish') == 'Now' && $this->request->input('status') == "0")) {
            FCMService::send(
                $user->fcm_token,
                [
                    'title' => 'Announcement Push Notification',
                    'body' => 'your body',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'data' => $ancPushNotification,
            'message' => trans('organizations::index.notiftn-add.success'),
        ]);
    }

    protected function searchFilter($builderSubscription)
    {
        $searchRecords = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        /**
         * Loop the search fields of subscriptions grid and use them to filter search results.
         */
        foreach ($searchRecords as $key => $value) {
            if ($key == 'created_by') {
                $builderSubscription = $builderSubscription->whereHas('user', function ($query) use ($value) {
                    $query->where('email', 'like', "%$value%");
                });
                continue;
            }

            if ($key == 'subscription') {
                $builderSubscription = $builderSubscription->whereHas('orgSubscription', function ($query) use ($value) {
                    $query->where('name', 'like', "%$value%");
                });
                continue;
            }

            if ($key == 'name') {
                $builderSubscription = $builderSubscription->where('name', 'like', "%$value%");
                continue;
            }

            if ($key == 'title') {
                $builderSubscription = $builderSubscription->where('title', 'like', "%$value%");
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

            if ($key == 'updated_at_from') {
                $builderSubscription->whereDate('updated_at', '>=', $value);
                continue;
            }

            if ($key == 'updated_at_to') {
                $builderSubscription->whereDate('updated_at', '<', $value);
                continue;
            }

            if ($key == 'status') {
                $builderSubscription->where('status', $value);
                continue;
            }
        }

        return $builderSubscription;
    }


    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('organizations::index.notifctn_created_at'), 'value' => 'created_at', 'sort' => true, 'class' => false],
            ['name' => trans('organizations::index.notifctn_updated_at'), 'value' => 'updated_at', 'sort' => true],
            ['name' => trans('organizations::index.notifctn_created_by'), 'value' => 'created_by', 'sort' => false, 'class' => false],
            ['name' => trans('organizations::index.notifctn_name'), 'value' => 'name', 'sort' => false, 'class' => false],
            ['name' => trans('organizations::index.notifctn_subscription'), 'value' => 'org_subscription_id', 'sort' => false],
            ['name' => trans('organizations::index.notifctn_title'), 'value' => 'title', 'sort' => false],
            ['name' => trans('organizations::index.notifctn_status'), 'value' => 'status', 'sort' => false],
            ['name' => trans('organizations::index.action'), 'value' => '', 'sort' => false],
        ]];
    }
}
