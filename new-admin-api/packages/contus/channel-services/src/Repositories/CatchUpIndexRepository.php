<?php

namespace Contus\ChannelServices\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\ChannelServices\Model\CatchUpIndex;
use Illuminate\Support\Facades\Log;

class CatchUpIndexRepository extends Repository
{
    protected $_catchUpIndexModel;

    public function __construct(CatchUpIndex $catchUpIndexModel)
    {
        parent::__construct();
        $this->_catchUpIndexModel = $catchUpIndexModel;
    }

    public function Create()
    {
        return $this->PostCreate($this->request->all());
    }

    public function PostCreate($requestData)
    {
        $this->setRules([
            'channel_id' => 'required',
            'drm_type' => 'required',
            'drm_profile' => 'required',
            'description' => 'nullable',
            'days' => 'required',
            'schedule_base' => 'required',
            'streaming_provider' => 'required',
            // 'custom_streaming_url' => 'required',
            'url' => 'required|url',
            'playback_token' => 'required',
            'token_generator' => 'required',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $datainert = new CatchUpIndex();

        $datainert->channel_id = $requestData['channel_id'];
        $datainert->drm_type = $requestData['drm_type'];
        $datainert->drm_profile = $requestData['drm_profile'];
        $datainert->description = $requestData['description'];
        $datainert->days = $requestData['days'];
        $datainert->schedule_base = $requestData['schedule_base'];
        $datainert->streaming_provider = $requestData['streaming_provider'];
        $datainert->url = $requestData['url'];
        $datainert->playback_token = $requestData['playback_token'];
        $datainert->token_generator = $requestData['token_generator'];
        $datainert->custom_streaming_url = isset($requestData['custom_streaming_url']) ? 1 : 0;
        $datainert->is_active = isset($requestData['is_active']) ? 1 : 0;

        $datainert->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {
            $data = $this->_catchUpIndexModel->findOrFail($id);
            // dd($data);

            // if ($data->is_active == 1) {
            $this->setRules([
                'channel_id' => 'required',
                // 'drm_type' => 'required',
                // 'drm_profile' => 'required',
                // 'description' => 'nullable',
                // 'days' => 'required',
                // 'schedule_base' => 'required',
                // 'streaming_provider' => 'required',
                // 'custom_streaming_url' => 'required',
                // 'url' => 'required',
                // 'playback_token' => 'required',
                // 'token_generator' => 'required',
                // 'is_active' => 'required',
            ]);
            // }

            $this->validate($this->request, $this->getRules());

            $data->channel_id = $this->request->channel_id;
            // $data->drm_type = $this->request->drm_type;
            // $data->drm_profile = $this->request->drm_profile;
            $data->description = $this->request->description;
            $data->days = $this->request->days;
            $data->schedule_base = $this->request->schedule_base;
            $data->streaming_provider = $this->request->streaming_provider;
            // $data->url = $this->request->url;
            // $data->playback_token = $this->request->playback_token;
            // $data->token_generator = $this->request->token_generator;
            $data->custom_streaming_url = $this->request->custom_streaming_url ? 1 : 0;
            $data->is_active = $this->request->is_active ? 1 : 0;

            if ($data->custom_streaming_url == 1) {
                $data->drm_type = $this->request->drm_type;
                $data->drm_profile = $this->request->drm_profile;
                $data->url = $this->request->url;
                $data->playback_token = $this->request->playback_token;
                $data->token_generator = $this->request->token_generator;
            } else {
                $data->drm_type = null;
                $data->drm_profile = null;
                $data->url = null;
                $data->playback_token = null;
                $data->token_generator = null;
            }
            $data->save();
            return true;
        } else {
            return false;
        }
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $channel = $this->_catchUpIndexModel->findOrFail($id);

            $channel->is_active = $this->request->is_active ? 1 : 0;
            $channel->save();

            return response()->json([
                'success' => true,
                'message' => 'Catch Up Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_catchUpIndexModel)
            ->setEagerLoadingModels('GetChannel');
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Channel Name', 'value' => '', 'sort' => true],
                ['name' => 'Channel Status', 'value' => '', 'sort' => false],
                ['name' => 'Description', 'value' => '', 'sort' => false],
                ['name' => 'Days', 'value' => '', 'sort' => false],
                ['name' => 'Schedule', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) &&
            is_array($this->request->input(StringLiterals::SEARCHRECORD)) ?
            $this->request->input(StringLiterals::SEARCHRECORD) : [];

        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'get_channel') {
                $builderCoupon = $builderCoupon->whereHas('GetChannel', function ($query) use ($value) {
                    $query->where('channel_name', 'like', "%{$value['channel_name']}%");
                });
                continue;
            }

            // if ($key == 'valid_till') {
            //     $date = date_create($value);
            //     $value = date_format($date, "Y-m-d");
            // }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
