<?php

namespace Contus\Channel\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Channel\Model\Channel;
use Contus\Channel\Model\ChannelOrganization;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;

class ChannelRepository extends Repository
{
    protected $_channel;
    public function __construct(Channel $channel)
    {
        parent::__construct();
        $this->_channel = $channel;
    }

    public function Channel()
    {
        return $this->ChannelCreate($this->request->all());
    }

    /**
     * /**
     * This method is used to create live stream and save the details in db
     *
     * @param array $requestData
     * @return string
     */

    public function ChannelCreate($requestData)
    {
        $user = Auth::user();

        $this->setRules([
            'channel_name' => 'required',
            'sorting_number' => 'nullable',
            'language' => 'nullable',
            'video_quality' => 'nullable',
            'streaming_url' => 'required|url',
            'policy' => 'required',
            'playback_token' => 'required',
            'epg_id' => 'nullable',
            'drm_type' => 'required',
            'drm_profile' => 'nullable',
            'organization' => 'required',
            'geo_block_country_list' => 'nullable',
        ]);

        $this->_validate();

        $ChennlData = new Channel;

        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $ChennlData->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $ChennlData->poster_image = $localImagePath;
            // Log::info('Processed poster image.', ['poster_path' => $localImagePath]);
        }

        $ChennlData->channel_name = $requestData['channel_name'];
        $ChennlData->sorting_number = $requestData['sorting_number'];
        $ChennlData->language = $requestData['language'];
        $ChennlData->video_quality = $requestData['video_quality'];
        $ChennlData->streaming_url = $requestData['streaming_url'];
        $ChennlData->policy = $requestData['policy'];
        $ChennlData->playback_token = $requestData['playback_token'];
        $ChennlData->epg_id = $requestData['epg_id'];
        // $ChennlData->content_sets = $requestData['content_sets'];
        // $ChennlData->organization = $requestData['organization'];
        $ChennlData->drm_type = $requestData['drm_type'];
        $ChennlData->drm_profile = $requestData['drm_profile'];
        $ChennlData->geo_block_country_list = $requestData['geo_block_country_list'] ?? null;

        // FacadesLog::info('DRM Profile', ['drm_profile' => $requestData]);
        // FacadesLog::info('DRM Profile', [$requestData['pin_locked'] ? 1 : 0]);
        // Boolean fields
        // $ChennlData->pin_locked = isset($requestData['pin_locked']) && $requestData['pin_locked'] ? 1 : 0;

        $ChennlData->pin_locked = isset($requestData['pin_locked']) ? 1 : 0;
        $ChennlData->geo_policy = isset($requestData['geo_policy']) ? 1 : 0;
        $ChennlData->group_chat = isset($requestData['group_chat']) ? 1 : 0;
        $ChennlData->is_active = isset($requestData['is_active']) ? 1 : 0;

        $ChennlData->save();

        foreach ($requestData['organization'] as $orgId) {
            ChannelOrganization::updateOrCreate([
                'channel_id' => $ChennlData->id,
                'organization_id' => $orgId
            ], [
                'created_by' => $user->id
            ]);
        }

        return "success";
        // Log::info('Channel created successfully.', ['channel_id' => $ChennlData->id]);

    }

    public function getChannel($id)
    {
        $channelId = explode(',', base64_decode($id));
        return $this->_channel->with(['getAllOrganization'])->find($channelId);
    }

    public function channelUpdate($id)
    {
        if (!empty($id)) {
            $channel = $this->_channel->findOrFail($id);

            if ($channel->is_active === 1) {
                $this->setRules([
                    'streaming_url' => 'required',
                    'language' => 'required',
                ]);
            } else {
                $this->setRules([
                    'channel_name' => 'required',
                    'sorting_number' => 'required',
                    'playback_token' => 'required',
                    'epg_id' => 'required',
                ]);
            }

            $this->validate($this->request, $this->getRules());

            if (!empty($this->request->poster_image)) {
                $thumbUrl = explode("/", $this->request->poster_image);
                $fileName = $channel->getImageBaseName(end($thumbUrl));

                // Define local storage path
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");

                // Build local image path
                $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;

                // Assign processed path to channel
                $channel->poster_image = $localImagePath;

                // Log::info('Processed poster image.', ['poster_path' => $localImagePath]);
            }

            $channel->channel_name = $this->request->channel_name;
            $channel->sorting_number = $this->request->sorting_number;
            $channel->language = $this->request->language;
            $channel->video_quality = $this->request->video_quality;
            $channel->streaming_url = $this->request->streaming_url;
            $channel->policy = $this->request->policy;
            $channel->epg_id = $this->request->epg_id;
            $channel->content_sets = $this->request->content_sets;
            // $channel->organization = $this->request->organization;
            $channel->playback_token = $this->request->playback_token;
            $channel->drm_type = $this->request->drm_type;
            $channel->drm_profile = $this->request->drm_profile;
            $channel->geo_block_country_list = $this->request->geo_block_country_list;
            $channel->pin_locked = $this->request->pin_locked ? 1 : 0;
            $channel->geo_policy = $this->request->geo_policy ? 1 : 0;
            $channel->group_chat = $this->request->group_chat ? 1 : 0;
            $channel->is_active = $this->request->is_active ? 1 : 0;

            $channel->save();

            $user = Auth::user();

            foreach ($this->request->organization as $orgId) {

                ChannelOrganization::updateOrCreate([
                    'channel_id' => $channel->id,
                    'organization_id' => $orgId
                ], [
                    'created_by' => $user->id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Channel Data Update Successfully.',
            ]);
        } else {
            return
                false;
        }
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $channel = $this->_channel->findOrFail($id);

            $channel->is_active = $this->request->is_active ? 1 : 0;
            $channel->save();

            return response()->json([
                'success' => true,
                'message' => 'Channel Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function bulkFetch()
    {
        $data = Channel::all();
        return $data;
    }

    // public function prepareGrid()
    // {
    //     $this->setGridModel($this->_channel)
    //         ->setEagerLoadingModels(['getOrganization', 'getDrm', 'GetPolicy', 'GetPlayback_token']);
    //     return $this;
    // }

    public function prepareGrid()
    {
        $this->setGridModel($this->_channel)
            ->setEagerLoadingModels([
                'getOrganization',
                'getAllOrganization',
                'getDrm',
                'GetPolicy',
                'GetPlayback_token'
            ]);

        return $this;
    }

    protected function updateGridQuery($builder)
    {
        if ($this->request->has('organization_id')) {
            $builder->where('organization', $this->request->organization_id);
        }
        return $builder;
    }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => true],
                ['name' => 'Streaming URL Policy', 'value' => '', 'sort' => false],
                ['name' => 'Language', 'value' => '', 'sort' => false],
                [
                    'name' => 'Listed',
                    'value' => '',
                    'sort' => false,
                    'hint' => 'list show organization total'
                ],
                ['name' => 'EPG', 'value' => '', 'sort' => false],
                ['name' => 'Subscribers', 'value' => '', 'sort' => false],
                [
                    'name' => 'Pin Locked',
                    'value' => '',
                    'sort' => false,
                    'hint' => 'Green lock means that channel is not Pin Locked, red lock means channel is Pin Locked.'
                ],
                ['name' => 'Status', 'value' => '', 'sort' => false],
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
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
