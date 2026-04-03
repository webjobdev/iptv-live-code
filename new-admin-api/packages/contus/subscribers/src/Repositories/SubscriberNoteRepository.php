<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\Repository;
use Contus\Base\Repository as BaseRepository;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Subscribers\Model\SubscriberNote;
use Illuminate\Support\Facades\Log;

/**
 * SubscriberNoteRepository class
 *
 * This class is used to manage subscriber notes.
 */

class SubscriberNoteRepository extends BaseRepository {
    /**
     * SubscriberNoteRepository constructor.
     *
     * @param SubscriberNote $subscriberNote
     */

    protected $subscriberNote;

    public function __construct(SubscriberNote $subscriberNote) {
        parent::__construct();
        $this->subscriberNote = $subscriberNote;
    }

    public function postAdd($id = null) {
        if (!empty($id)) {
            // update existing
            $notes = $this->subscriberNote->find($id);
            if (!is_object($notes)) {
                Log::warning('[Subscriber Note] No subscriber note found for given Id.', ['id' => $id]);
                return false;
            }

            // Log::info('[Subscriber Note] Updating existing subscriber note.', ['subscriber_note_id' => $id]);

            // set "updates" field as updated
            $notes->updates = 'updated';

            // validation rules (nullable so user can send partial data)
            $this->setRules([
                'note_type'     => 'nullable|max:255',
                'sub_note_type' => 'nullable|max:255',
                'subject'       => 'nullable|max:255',
                'description'   => 'nullable|max:1000',
            ]);
        } else {
            // create new
            // Log::info('[Subscriber Note] Creating new subscriber note record.');

            $notes = new SubscriberNote();
            $notes->updates = 'not updated yet';

            $this->setRules([
                'note_type'     => 'nullable|max:255',
                'sub_note_type' => 'nullable|max:255',
                'subject'       => 'nullable|max:255',
                'description'   => 'nullable|max:1000',
            ]);
        }

        // validate input
        try {
            $this->_validate();
            // Log::info('[Subscriber Note] Validation passed.');
        } catch (\Exception $e) {
            // Log::error('[Subscriber Note] Validation failed.', ['error' => $e->getMessage()]);
            return false;
        }


        foreach ($this->request->all() as $key => $value) {
            if ($notes->isFillable($key)) {
                $notes->$key = $value;
            }
        }

        if ($notes->save()) {
            // Log::info('[Subscriber Note] Subscriber note saved successfully.', [
            //     'subscriber_note_id' => $notes->id,
            //     'updates' => $notes->updates,
            //     'data' => $notes->toArray()
            // ]);
            return response()->json([
                'success' => true,
                'message' => trans('subscribers::index.subscriber_note_edit.success'),
            ]);
        } else {
            // Log::error('[Subscriber Note] Failed to save subscriber note.', ['data' => $notes->toArray()]);
            return response()->json([
                'success' => true,
                'message' => trans('subscribers::index.subscriber_note.success'),
            ]);
        }
    }




    public function prepareGrid() {
        $this->setGridModel($this->subscriberNote)
            ->setEagerLoadingModels(['subscriber_details']);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $subscriberId = $this->request->input('subscriber-id') ?? $this->request->input('subscriber_id');
        sleep(2);
        if ($subscriberId) {
            return $builder->where('subscriber_id', $subscriberId);
        }
        return $builder;
    }

    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('subscribers::index.created'), 'value' => '', 'sort' => true],
            ['name' => trans('subscribers::index.created_by'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.note_type'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.sub_note_type'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.subject'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.description'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.updates'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.action'), 'value' => '', 'sort' => false],
        ]];
    }

    protected function searchFilter($builderCoupon) {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value =  date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
