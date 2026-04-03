<?php

namespace Contus\Vod\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Vod\Model\VideoOnDemad;
use Contus\Vod\Model\VodOrganization;
use Illuminate\Support\Facades\Auth;


class VodIndexRepository extends Repository
{

    protected $_vod;

    public function __construct(VideoOnDemad $videoOnDemad)
    {
        parent::__construct();
        $this->_vod = $videoOnDemad;
    }

    public function VideoOnDemand()
    {
        return $this->CreateVod($this->request->all());
    }

    /**
     * /**
     * This method is used to create live stream and save the details in db
     *
     * @param array $requestData
     * @return boolean
     */

    public function CreateVod($requestData)
    {
        $this->setRules([
            'organization' => 'required',
            'drm_type' => 'required',
            'drm_profile' => 'required',
            'title' => 'required',
            'description' => 'required',
            'release_year' => 'required',
            'directors' => 'required',
            'presenter' => 'required',
            'timeParts' => 'required',
            'scheduled_time' => 'nullable|required',
            'expire_scheduled_time' => 'nullable|required',
            'publish_date' => 'required',
            'video_quality' => 'required',
            'streaming_url' => 'required|url',
            'trailer_url' => 'required|url',
            'playback_token' => 'required',
            'policy' => 'required',
            'category' => 'nullable|array',
            'age_rating' => 'required',
            'age_limit' => 'required',
            'is_parental' => 'required',
            'scheduled_publishing' => 'required',
            'publish_now' => 'nullable|required',
            'geo_policy' => 'nullable',
            'is_active' => 'nullable|required',
        ]);
        $this->_validate();

        $vodData = new VideoOnDemad();

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $vodData->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $vodData->thumbnail_image = $localIamgePath;
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $vodData->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $vodData->poster_image = $localIamgePath;
        }

        $recordingdate = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
        $recordingendDate = (!empty($requestData['expire_scheduled_time'])) ? $requestData['expire_scheduled_time'] : '';
        $publishDate = (!empty($requestData['publish_date'])) ? $requestData['publish_date'] : '';

        // $vodData->organization = json_encode($requestData['organization']);
        $vodData->drm_type = $requestData['drm_type'];
        $vodData->drm_profile = $requestData['drm_profile'];


        $vodData->video_quality = $requestData['video_quality'];
        $vodData->streaming_url = $requestData['streaming_url'];
        $vodData->policy = $requestData['policy'];
        $vodData->title = $requestData['title'];
        $vodData->description = $requestData['description'];
        $vodData->release_year = $requestData['release_year'];
        $vodData->directors = $requestData['directors'];
        $vodData->presenter = $requestData['presenter'];
        $vodData->timeParts = json_encode($requestData['timeParts']);

        $vodData->scheduled_time = $recordingdate;
        $vodData->expire_scheduled_time = $recordingendDate;
        $vodData->publish_date = $publishDate;

        $vodData->geo_block_country_list = $requestData['geo_block_country_list'] ?? null;
        $vodData->trailer_url = $requestData['trailer_url'];
        $vodData->playback_token = $requestData['playback_token'];
        // $vodData->category = json_encode($requestData['category']);
        $vodData->age_rating = $requestData['age_rating'];
        $vodData->age_limit = $requestData['age_limit'];
        $vodData->is_parental = $requestData['is_parental'];

        $vodData->scheduled_publishing = $requestData['scheduled_publishing'] ? 1 : 0;
        $vodData->publish_now = $requestData['publish_now'] ? 1 : 0;
        // $vodData->geo_policy = $requestData['geo_policy'] ? 1 : 0;
        $vodData->is_active = $requestData['is_active'] ? 1 : 0;

        $vodData->save();

        $user = Auth::user();

        foreach ($requestData['organization'] as $orgId) {

            VodOrganization::updateOrCreate([
                'vod_id' => $vodData->id,
                'organization_id' => $orgId
            ], [
                'created_by' => $user->id
            ]);
        }

        return "success";
    }

    public function getVod($id)
    {
        $vodId = explode(',', base64_decode($id));
        return $this->_vod->with('getAllOrganization')->find($vodId);
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $channel = $this->_vod->findOrFail($id);

            $channel->is_active = $this->request->is_active ? 1 : 0;
            $channel->save();

            return response()->json([
                'success' => true,
                'message' => 'Channel Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function VodUpdate($id)
    {
        if (!empty($id)) {
            $Vod = $this->_vod->findOrFail($id);
            // dd($Vod);

            if ($Vod->is_active == 1) {
                $this->setRules([
                    'title' => 'nullable|required',
                    'description' => 'nullable|required',
                ]);
            } else {
                $this->setRules([
                    'streaming_url' => 'required',
                    'trailer_url' => 'required',
                    'release_year' => 'required',
                    'directors' => 'required',
                    'presenter' => 'required',
                    'timeParts' => 'required',
                ]);
            }

            $this->validate($this->request, $this->getRules());

            if (isset($this->request->thumbnail_image) && $this->request->thumbnail_image != '') {
                $thumbUrl = explode("/", $this->request->thumbnail_image);
                $fileName = $Vod->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $Vod->thumbnail_image = $localIamgePath;
            }
            if (isset($this->request->poster_image) && $this->request->poster_image != '') {
                $thumbUrl = explode("/", $this->request->poster_image);
                $fileName = $Vod->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $Vod->poster_image = $localIamgePath;
            }

            // $Vod->organization = $this->request->organization;
            $Vod->drm_type = $this->request->drm_type;
            $Vod->drm_profile = $this->request->drm_profile;
            $Vod->title = $this->request->title;
            $Vod->description = $this->request->description;
            $Vod->release_year = $this->request->release_year;
            $Vod->directors = $this->request->directors;
            $Vod->presenter = $this->request->presenter;
            $Vod->timeParts = $this->request->timeParts;
            $Vod->scheduled_time = $this->request->scheduled_time;
            $Vod->expire_scheduled_time = $this->request->expire_scheduled_time;
            $Vod->geo_block_country_list = $this->request->geo_block_country_list;
            $Vod->publish_date = $this->request->publish_date;
            $Vod->video_quality = $this->request->video_quality;
            $Vod->streaming_url = $this->request->streaming_url;
            $Vod->trailer_url = $this->request->trailer_url;
            $Vod->playback_token = $this->request->playback_token;
            $Vod->policy = $this->request->policy;
            $Vod->content_sets = $this->request->content_sets;
            $Vod->category = $this->request->category;
            $Vod->age_rating = $this->request->age_rating;
            $Vod->age_limit = $this->request->age_limit;
            $Vod->is_parental = $this->request->is_parental ? 1 : 0;
            $Vod->scheduled_publishing = $this->request->scheduled_publishing ? 1 : 0;
            $Vod->publish_now = $this->request->publish_now ? 1 : 0;
            $Vod->geo_policy = $this->request->geo_policy ? 1 : 0;
            $Vod->is_active = $this->request->is_active ? 1 : 0;

            $Vod->save();

            $user = Auth::user();

            foreach ($this->request->organization as $orgId) {
                VodOrganization::updateOrCreate([
                    'vod_id' => $Vod->id,
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

    public function prepareGrid()
    {

        $this->setGridModel($this->_vod)->setEagerLoadingModels([
            'GetPlayback_token',
            'getAllOrganization',
            'GetPolicy',
            'getOrganization'
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
                ['name' => 'Title', 'value' => '', 'sort' => true],
                ['name' => 'Streaming Url Policy', 'value' => '', 'sort' => false],
                ['name' => 'Listed', 'value' => '', 'sort' => false],
                ['name' => 'Subscribers', 'value' => '', 'sort' => false],
                [
                    'name' => 'Pin Locked',
                    'value' => '',
                    'sort' => false,
                    'hint' => 'Green lock means that VOD is not Pin Locked, red lock means VOD is Pin Locked.'
                ],
                ['name' => 'Status', 'value' => '', 'sort' => false],
                ['name' => 'Added Date', 'value' => '', 'sort' => false],
                ['name' => 'Publish Date', 'value' => '', 'sort' => false],
                ['name' => 'Unpublish Date', 'value' => '', 'sort' => false],
                ['name' => 'Views', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }

}
