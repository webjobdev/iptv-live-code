<?php

namespace Contus\ChannelServices\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\ChannelServices\Model\LiveRewind;
use Contus\Cms\Models\LiveBanner;

class LiveRewindRepository extends Repository
{
    protected $_liveRewindModel;

    public function __construct(LiveRewind $liveRewindModel)
    {
        parent::__construct();
        $this->_liveRewindModel = $liveRewindModel;
    }

    public function create()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'channel_id' => 'required',
            'drm_type' => 'required',
            'drm_profile' => 'nullable',
            'live_rewind_node' => 'nullable',
            'streaming_provider' => 'required',
            // 'custome_streaming_url' => 'required',
            'url' => 'required|url',
            'playback_token' => 'required',
            'token_generator' => 'required',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $insert = new LiveRewind();

        $insert->channel_id = $requestData['channel_id'];
        $insert->drm_type = $requestData['drm_type'];
        $insert->drm_profile = $requestData['drm_profile'];
        $insert->live_rewind_node = $requestData['live_rewind_node'] ?? null;
        $insert->streaming_provider = $requestData['streaming_provider'];
        $insert->custome_streaming_url = $requestData['custome_streaming_url'] ? 1 : 0;
        $insert->url = $requestData['url'];
        $insert->playback_token = $requestData['playback_token'];
        $insert->token_generator = $requestData['token_generator'];
        $insert->is_active = isset($requestData['is_active']) ? 1 : 0;

        $insert->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {

            $data = $this->_liveRewindModel->findOrFail($id);

            $this->setRules([
                'channel_id' => 'required',
                // 'drm_type' => 'required',
                // 'drm_profile' => 'required',
                // 'live_rewind_node' => 'nullable',
                // 'streaming_provider' => 'required',
                // 'custome_streaming_url' => 'required',
                // 'url' => 'required|url',
                // 'playback_token' => 'required',
                // 'token_generator' => 'required',
                // 'is_active' => 'required',
            ]);

            $this->_validate();

            $data->channel_id = $this->request->channel_id;
            $data->live_rewind_node = $this->request->live_rewind_node ?? null;
            $data->streaming_provider = $this->request->streaming_provider;
            $data->custome_streaming_url = $this->request->input('custome_streaming_url') ? 1 : 0;
            $data->is_active = $this->request->is_active ? 1 : 0;

            if ($data->custome_streaming_url == 1) {
                // Use request values
                $data->drm_type = $this->request->drm_type;
                $data->drm_profile = $this->request->drm_profile;
                $data->url = $this->request->url;
                $data->playback_token = $this->request->playback_token;
                $data->token_generator = $this->request->token_generator;
            } else {
                // Reset fields if custom_streaming_url is 0
                $data->drm_type = null;
                $data->drm_profile = null;
                $data->url = null;
                $data->playback_token = null;
                $data->token_generator = null;
            }

            $data->save();
            return true;
        }

        return false;
    }


    public function postToggleEdit($id)
    {
        if (!empty($id)) {
            $data = $this->_liveRewindModel->findOrFail($id);

            $data->is_active = $this->request->is_active ? 1 : 0;
            $data->save();

            return
                true;
        } else {
            return
                false;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_liveRewindModel)
            ->setEagerLoadingModels(['getChannel']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Channel Name', 'value' => '', 'sort' => true],
                ['name' => 'Channel Status', 'value' => '', 'sort' => false],
                ['name' => 'Streaming Provider', 'value' => '', 'sort' => false],
                ['name' => 'Live Rewind Node Name', 'value' => '', 'sort' => false],
                ['name' => 'Live Rewind Node Status', 'value' => '', 'sort' => false],
                ['name' => 'Health', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
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

            if ($key == 'get_channel') {
                $builderCoupon = $builderCoupon->whereHas('GetChannel', function ($query) use ($value) {
                    $query->where('channel_name', 'like', "%{$value['channel_name']}%");
                });
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
