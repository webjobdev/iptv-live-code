<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\SubVideoOnDemand;
use Illuminate\Support\Facades\Log;

class VodRepository extends Repository
{
    protected $_vod;

    public function __construct(SubVideoOnDemand $subVideoOnDemand)
    {
        parent::__construct();
        $this->_vod = $subVideoOnDemand;
    }

    // public function addVodList() {
    //     $subscriberId = $this->request->input('subscriber_id', $this->request->input('id'));
    //     $vods = $this->request->input('video_on_demand_list', []);

    //     // Log::info('Received subscriber id.', ['subscriber_id' => $subscriberId]);
    //     // Log::info('Received vods list.', ['vods_list' => $vods]);

    //     if (empty($vods)) {
    //         Log::warning('No vods list were sent in request.');
    //         return response()->json([
    //             'message' => 'No vods Selected.',
    //         ], 400);
    //     }

    //     $inserted = [];
    //     foreach ($vods as $vod) {
    //         // If it's a string, treat as title
    //         $title = is_array($vod) ? ($vod['title'] ?? null) : $vod;

    //         if (!empty($title)) {
    //             $data = [
    //                 'subscriber_id' => $subscriberId,
    //                 'video_on_demand_list'  => $title
    //             ];
    //             // Log::info('Inserting assigned device.', $data);
    //             $inserted[] = OrgVideoOnDemand::create($data);
    //         }
    //         // else {
    //         //     Log::warning('⏭️ Skipping device with missing vods title.', ['vods' => $vod]);
    //         // }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => trans('subscribers::index.device_assigned.success'),
    //     ]);
    // }


    public function addVodList($id = null)
    {
        // Log::info('Vod method triggered.', ['id' => $id]);

        $subscriberId = $this->request->input('subscriber_id', $this->request->input('id'));
        $rawEndDate = $this->request->input('end_at');
        $startAt = now()->format('Y-m-d H:i:s');
        $endAt = null;

        if (!empty($rawEndDate)) {
            // Log::info("📅 Received raw end_at input: {$rawEndDate}");
            $timestamp = strtotime($rawEndDate);
            if ($timestamp) {
                $endAt = date('Y-m-d H:i:s', $timestamp);
                // Log::info("✅ Parsed end_at using strtotime: {$endAt}");
            } else {
                $dateObj = \DateTime::createFromFormat('d-m-y', $rawEndDate);
                if ($dateObj) {
                    $now = new \DateTime();
                    $dateObj->setTime($now->format('H'), $now->format('i'), $now->format('s'));
                    $endAt = $dateObj->format('Y-m-d H:i:s');
                    // Log::info("✅ Parsed end_at using d-m-y format: {$endAt}");
                } else {
                    Log::warning("❗ Failed to parse end_at: {$rawEndDate}");
                }
            }
        }

        if (!empty($id)) {
            $vod = $this->_vod->find($id);
            if (!$vod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vod Id Is Not Found.'
                ]);
            }

            $this->setRules([
                'end_at' => 'nullable|max:255',
                'is_active' => 'nullable|max:255',
            ]);
            $this->_validate();

            $vod->start_at = $startAt;
            $vod->is_active = 1;

            if (!empty($rawEndDate)) {
                $vod->end_at = $endAt;
            }

            if ($vod->save()) {
                // Log::info('✅ Custom stream updated.', ['stream_id' => $vod->id]);
                return response()->json([
                    'success' => true,
                    'message' => __('subscribers::index.cancel.success'),
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => __('subscribers::index.cancel.error'),
                ]);
            }
        }

        $this->setRules([
            'subscriber_id' => 'nullable|integer',
            'video_on_demand_list' => 'nullable|array',
            'end_at' => 'nullable|max:255',
            'is_active' => 'nullable|max:255',
        ]);

        $this->_validate();

        $Vodlist = $this->request->input('video_on_demand_list', []);
        foreach ($Vodlist as $vod) {
            $vodlist = new SubVideoOnDemand();
            $vodlist->subscriber_id = $subscriberId;
            $vodlist->video_on_demand_list = $vod;
            $vodlist->start_at = $startAt;
            $vodlist->end_at = $endAt;

            $vodlist->save();
        }

        return response()->json([
            'success' => true,
            'message' => trans('subscribers::index.cancel.success'),
        ]);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_vod);
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

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => __('subscribers::index.vod'), 'value' => '', 'sort' => true],
                ['name' => __('subscribers::index.set_order'), 'value' => '', 'sort' => true],
                ['name' => __('subscribers::index.action'), 'value' => '', 'sort' => true],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value = date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
