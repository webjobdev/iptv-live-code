<?php

namespace Contus\Organizations\Repositories\AnnouncementReminders;

use App\Models\User;
use Contus\Base\Repository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Organizations\Model\OrgAnnouncementReminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnnouncementReminderRepository extends Repository
{

    protected $_announcmentReminder;

    public function __construct(OrgAnnouncementReminder $orgAnnouncementReminder)
    {
        parent::__construct();
        $this->_announcmentReminder = $orgAnnouncementReminder;
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_announcmentReminder)->setEagerLoadingModels('user');
        return $this;
    }


    public function addAnnouncementReminder()
    {
        \Log::info('CREATE REMINDER :: REQUEST RECEIVED', [$this->request->all()]);

        $this->setRules([
            'subject' => 'required|string',
            'message' => 'required|string',
            'day_before' => 'required',
            'reminder_to' => 'nullable',
        ]);
        $this->_validate();

        $user = Auth::user();
        $id = $this->request->input('id');
        // dd($id);

        $announcementReminder = new OrgAnnouncementReminder();
        $res = trans('organizations::index.reminder-add.success');

        // dd(isset($id) && $id);
        if (isset($id) && $id) {
            $announcementReminder = OrgAnnouncementReminder::find($id);
            $res = trans('organizations::index.reminder-update.success');
        }

        $announcementReminder->organization_id = $this->request->input('organization_id');
        $announcementReminder->subject = $this->request->input('subject');
        $announcementReminder->message = $this->request->input('message');
        $announcementReminder->day_before = $this->request->input('day_before');
        $announcementReminder->reminder_to = $this->request->input('reminder_to');
        $announcementReminder->created_by = $user->id;
        $announcementReminder->save();


        return response()->json([
            'status' => true,
            'data' => $announcementReminder,
            'message' => $res,
        ]);
    }

    public function statusUpdate()
    {
        $ancReminder = OrgAnnouncementReminder::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'status' => true,
            'data' => $ancReminder,
            'message' => trans('organizations::index.reminder-update.success'),
        ]);
    }

    public function deleteRecord($id)
    {
        $orgAnnouncementReminder = OrgAnnouncementReminder::find($id);
        if ($orgAnnouncementReminder) {
            $orgAnnouncementReminder->delete();
        }
        return response()->json([
            'status' => true,
            'message' => trans('organizations::index.reminder-delete.success'),
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

            if ($key == 'reminder_to') {
                $map = [
                    'all' => 'All Subscribers',
                    'autopay' => 'Autopay Subscribers',
                    'non-autopay' => 'Non-Autopay Subscribers',
                ];

                $toReminder = $map[$value] ?? null;

                if ($toReminder) {
                    $builderSubscription->where('reminder_to', $toReminder);
                }
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
                ['name' => trans('organizations::index.remndr_created_at'), 'value' => 'created_at', 'sort' => true, 'class' => false],
                ['name' => trans('organizations::index.remndr_subject'), 'value' => 'subject', 'sort' => true],
                ['name' => trans('organizations::index.remndr_created_by'), 'value' => 'created_by', 'sort' => true, 'class' => false],
                ['name' => trans('organizations::index.remndr_reminder_to'), 'value' => 'reminder_to', 'sort' => true, 'class' => false],
                ['name' => trans('organizations::index.remndr_before_day'), 'value' => 'day_before', 'sort' => true],
                ['name' => trans('organizations::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
