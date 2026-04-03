<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\SubCustomStream;
use Exception;
use Illuminate\Support\Facades\Log;

class CustomStreamRepository extends Repository
{

    protected $_customstream;

    public function __construct(SubCustomStream $subCustomStream)
    {
        parent::__construct();
        $this->_customstream = $subCustomStream;
    }

    // public function addChannelList($id = null) {
    //     // try {
    //     $subscriberId = $this->request->input('subscriber_id', $this->request->input('id'));
    //     $channel = $this->request->input('channel_list', []);

    //     // Log::info('Received subscriber id.', ['subscriber_id' => $subscriberId]);
    //     // Log::info('Received channel list.', ['channel_list' => $channel]);

    //     if (empty($channel)) {
    //         Log::warning('No channel list were sent in request.');
    //         return response()->json([
    //             'message' => 'No Channel Selected.',
    //         ], 400);
    //     }

    //     $inserted = [];
    //     foreach ($channel as $chl) {
    //         // If it's a string, treat as title
    //         $title = is_array($chl) ? ($chl['title'] ?? null) : $chl;

    //         if (!empty($title)) {
    //             $data = [
    //                 'subscriber_id' => $subscriberId,
    //                 'channel_list'  => $title
    //             ];
    //             // Log::info('Inserting assigned device.', $data);
    //             $inserted[] = SubCustomStream::create($data);
    //         }
    //         // else {
    //         //     Log::warning('⏭️ Skipping device with missing channel title.', ['Channel' => $chl]);
    //         // }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => trans('subscribers::index.device_assigned.success'),
    //     ]);
    //     // } catch (\Exception $e) {
    //     //     return 0;
    //     // }
    // }


    public function addChannelList($id = null)
    {
        $subscriberId = $this->request->input('subscriber_id', $this->request->input('id'));
        $rawEndDate = $this->request->input('end_at');
        $startAt = now()->format('Y-m-d H:i:s');
        $endAt = null;

        // Parse end_at if provided
        if (!empty($rawEndDate)) {
            $timestamp = strtotime($rawEndDate);
            if ($timestamp) {
                $endAt = date('Y-m-d H:i:s', $timestamp);
            } else {
                $dateObj = \DateTime::createFromFormat('d-m-y', $rawEndDate);
                if ($dateObj) {
                    $now = new \DateTime();
                    $dateObj->setTime($now->format('H'), $now->format('i'), $now->format('s'));
                    $endAt = $dateObj->format('Y-m-d H:i:s');
                }
            }
        }

        // Update existing record
        if (!empty($id)) {
            $channel = $this->_customstream->find($id);
            if (!$channel) {
                return response()->json(['success' => false, 'message' => 'Stream not found']);
            }

            $this->setRules([
                'end_at' => 'nullable|max:255',
                'is_active' => 'nullable|max:255',
            ]);
            $this->_validate();

            $channel->start_at = $startAt;
            $channel->is_active = 1;

            // Only update end_at if a new value is provided
            if (!empty($rawEndDate)) {
                $channel->end_at = $endAt;
            }

            if ($channel->save()) {
                return response()->json(['success' => true, 'message' => 'Data updated']);
            } else {
                return response()->json(['success' => false, 'message' => 'Update failed']);
            }
        }

        // Create new records
        $this->setRules([
            'subscriber_id' => 'required|integer',
            'channel_list' => 'required|array',
            'end_at' => 'nullable|max:255',
            'is_active' => 'nullable|max:255',
        ]);

        $this->_validate();

        $channelList = $this->request->input('channel_list', []);

        foreach ($channelList as $channelId) {
            $channel = new SubCustomStream();
            $channel->subscriber_id = $subscriberId;
            $channel->channel_list = $channelId;
            $channel->start_at = $startAt;
            $channel->end_at = $endAt;
            // $channel->is_active     = 1;

            $channel->save(); // You can still add logging here if needed
        }

        return response()->json([
            'success' => true,
            'message' => trans('subscribers::index.cancel.success'),
        ]);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_customstream);
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
                ['name' => __('subscribers::index.channel_list'), 'value' => '', 'sort' => true],
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
