<?php

namespace Contus\Tvshow\Repositories;

use Auth;
use Contus\Base\Repository;
use Contus\Tvshow\Model\SeasonEpisode;
use Contus\Tvshow\Model\TvShow;
use Contus\Tvshow\Model\TvShowSeason;
use Contus\Tvshow\Model\TvShowSeasonEpisodeOrg;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SeasonEpisodeRepository extends Repository
{
    protected $_seasonEpisode;
    protected $_tvShowSeason;
    public function __construct(SeasonEpisode $seasonEpisode, TvShowSeason $tvShowSeason)
    {
        parent::__construct();
        $this->_seasonEpisode = $seasonEpisode;
        $this->_tvShowSeason = $tvShowSeason;
    }

    public function CreateEpisode()
    {
        return $this->create($this->request->all());
    }

    public function create($requestData)
    {
        $this->setRules([
            'tv_show_id' => 'nullable',
            'season_id' => 'nullable',
            'poster_image' => 'nullable',
            'thumbnail_image' => 'nullable',
            'episode_name' => 'required',
            'episode_number' => 'required',
            'streaming_url' => 'required',
            'description' => 'nullable',
            'directors' => 'nullable',
            'presenter' => 'nullable',
            'resolution' => 'required',
            'timeParts' => 'required',
            // 'content_sets' => 'nullable',
            'release_date' => 'required',
            'scheduled_time' => 'nullable',
            'expire_scheduled_time' => 'nullable',
            'publish_date' => 'nullable',
            'drm_type' => 'required',
            'drm_profile' => 'nullable',
            'policy' => 'required',
            'playback_token' => 'required',
            'scheduled_publishing' => 'nullable',
            'publish_now' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $insert = new SeasonEpisode();

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->thumbnail_image = $localIamgePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->poster_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $release_date = (!empty($requestData['release_date'])) ? $requestData['release_date'] : '';
        $recordingdate = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
        $recordingendDate = (!empty($requestData['expire_scheduled_time'])) ? $requestData['expire_scheduled_time'] : '';
        $publishDate = (!empty($requestData['publish_date'])) ? $requestData['publish_date'] : '';

        $tvShowId = $this->_tvShowSeason->find($requestData['season_id']);
        // Log::info("season_id: " . json_encode($tvShowId));

        $insert->tv_show_id = $tvShowId->tv_show_id;
        // Log::info("tv_show_id: " . $tvShowId->tv_show_id);
        $insert->season_id = $requestData['season_id'];
        $insert->episode_name = $requestData['episode_name'];
        $insert->episode_number = $requestData['episode_number'];
        $insert->streaming_url = $requestData['streaming_url'];
        $insert->description = $requestData['description'];
        $insert->directors = $requestData['directors'];
        $insert->presenter = $requestData['presenter'];
        $insert->resolution = $requestData['resolution'];
        $insert->timeParts = json_encode($requestData['timeParts']);
        // $insert->content_sets = json_encode($requestData['content_sets']);
        $insert->drm_type = $requestData['drm_type'];
        $insert->drm_profile = $requestData['drm_profile'];
        $insert->policy = $requestData['policy'];
        $insert->playback_token = $requestData['playback_token'];

        $insert->release_date = $release_date;
        $insert->scheduled_time = $recordingdate;
        $insert->expire_scheduled_time = $recordingendDate;
        $insert->publish_date = $publishDate;

        $insert->scheduled_publishing = !empty($requestData['scheduled_publishing']) ? 1 : 0;
        $insert->publish_now = !empty($requestData['publish_now']) ? 1 : 0;
        $insert->is_active = !empty($requestData['is_active']) ? 1 : 0;

        $insert->save();

        $user = Auth::user();

        // foreach ($requestData['organization'] as $orgId) {
        //     TvShowSeasonEpisodeOrg::updateOrCreate([
        //         'tv_show_season_episode_id' => $insert->id,
        //         'organization_id' => $orgId
        //     ], [
        //         'created_by' => $user->id
        //     ]);
        // }

        return 'success';
    }

    public function getEpisodeToEdit($id)
    {
        $episodeId = explode(',', base64_decode($id));
        return $this->_seasonEpisode->find($episodeId);
        // return $this->_seasonEpisode->with('getAllOrganization')->find($episodeId);
    }

    public function postEdit($id)
    {
        if (!empty($id)) {

            $editId = $this->_seasonEpisode->findOrFail($id);

            $this->setRules([
                'episode_name' => 'required',
                'episode_number' => 'required'
            ]);

            $this->validate($this->request, $this->getRules());

            if (isset($this->request->thumbnail_image)) {
                $thumbUrl = explode("/", $this->request->thumbnail_image);
                $fileName = $editId->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $editId->thumbnail_image = $localIamgePath;
                // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
            }
            if (isset($this->request->poster_image)) {
                $thumbUrl = explode("/", $this->request->poster_image);
                $fileName = $editId->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $editId->poster_image = $localIamgePath;
                // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
            }

            $release_date = (!empty($this->request->release_date)) ? $this->request->release_date : '';
            $recordingdate = (!empty($this->request->scheduled_time)) ? $this->request->scheduled_time : '';
            $recordingendDate = (!empty($this->request->expire_scheduled_time)) ? $this->request->expire_scheduled_time : '';
            $publishDate = (!empty($this->request->publish_date)) ? $this->request->publish_date : '';

            $tvShowId = $this->_tvShowSeason->find($this->request->season_id);

            $editId->tv_show_id = $tvShowId->tv_show_id;
            $editId->season_id = $this->request->season_id;
            $editId->episode_name = $this->request->episode_name;
            $editId->episode_number = $this->request->episode_number;
            $editId->streaming_url = $this->request->streaming_url;
            $editId->description = $this->request->description;
            $editId->directors = $this->request->directors;
            $editId->presenter = $this->request->presenter;
            $editId->resolution = $this->request->resolution;
            $editId->timeParts = json_encode($this->request->timeParts);
            $editId->content_sets = json_encode($this->request->content_sets);
            $editId->drm_type = $this->request->drm_type;
            $editId->drm_profile = $this->request->drm_profile;
            $editId->policy = $this->request->policy;
            $editId->playback_token = $this->request->playback_token;

            $editId->release_date = $release_date;
            $editId->scheduled_time = $recordingdate;
            $editId->expire_scheduled_time = $recordingendDate;
            $editId->publish_date = $publishDate;

            $editId->scheduled_publishing = !empty($this->request->scheduled_publishing) ? 1 : 0;
            $editId->publish_now = !empty($this->request->publish_now) ? 1 : 0;
            $editId->is_active = !empty($this->request->is_active) ? 1 : 0;

            $editId->save();

            // $user = Auth::user();

            // foreach ($this->request->organization as $orgId) {
            //     TvShowSeasonEpisodeOrg::updateOrCreate([
            //         'tv_show_season_episode_id' => $editId->id,
            //         'organization_id' => $orgId
            //     ], [
            //         'created_by' => $user->id
            //     ]);
            // }

            return true;

        } else {
            return false;
        }
    }

    public function postDelete($id)
    {
        $remove = $this->_seasonEpisode->findOrFail($id);
        if ($remove) {
            $remove->delete();
            return 'success';
        } else {
            return 'Id not found!';
        }
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $toggle = $this->_seasonEpisode->findOrFail($id);

            $toggle->is_active = $this->request->is_active ? 1 : 0;
            $toggle->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function prepareGrid()
    {
        $json = $this->setGridModel($this->_seasonEpisode)
            ->setEagerLoadingModels(['GetSeason', 'GetTvShow', 'DrmProfile', 'GetPolicy', 'GetPlayback_token', 'getAllOrganization']);
        return $json;
    }

    public function fetchEpisodeRecords()
    {
        $query = TvShow::with(['getSeasons.getEpisodes', 'getAllOrganization']);

        if ($this->request->has('organization_id')) {
            $query->whereHas('getAllOrganization', function ($q) {
                $q->where('organization_id', $this->request->organization_id);
            });
            // $query->where('organization', $this->request->organization_id);
        }

        $data = $query->get();

        return $data;
    }

    public function fetchRecords()
    {
        $validate = Validator::make($this->request->all(), [
            'season_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 400);
        }

        $episodes = SeasonEpisode::where('season_id', $this->request->season_id)
            ->orderBy('episode_number', 'desc')
            ->get();

        return $episodes;
    }


    // public function fetchRecords()
    // {
    //     $validate = Validator::make($this->request->all(), [
    //         'season_id' => 'required|integer',
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validate->errors()->first()
    //         ], 400);
    //     }

    //     $seasonId = $this->request->season_id;

    //     $data = TvShow::with([
    //         'getSeasons' => function ($query) use ($seasonId) {
    //             $query->where('id', $seasonId)
    //                 ->with('getEpisodes');
    //         }
    //     ])
    //         ->whereHas('getSeasons', function ($query) use ($seasonId) {
    //             $query->where('id', $seasonId);
    //         })
    //         ->paginate(15);

    //     return $data;
    // }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Number', 'value' => '', 'sort' => false],
                ['name' => 'Episode Name', 'value' => '', 'sort' => false],
                ['name' => 'Publish Time', 'value' => '', 'sort' => false],
                ['name' => 'Unpublish Time', 'value' => '', 'sort' => false],
                ['name' => 'Views', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }
}