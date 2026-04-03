<?php

namespace Contus\Tvshow\Repositories;

// use Auth;
use Illuminate\Support\Facades\Auth;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Tvshow\Model\TvShow;
use Contus\Tvshow\Model\TvShowOrganization;
use Contus\Tvshow\Model\TvShowSeason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

class TvShowIndexRepository extends Repository
{
    protected $_tvshow;
    protected $gridQuery;
    
    public function __construct(TvShow $tvShow)
    {
        parent::__construct();
        $this->_tvshow = $tvShow;
    }

    public function CreateTvShow()
    {
        return $this->createShow($this->request->all());
    }

    public function createShow($requestData)
    {
        $this->setRules([
            'organization' => 'required',
            'title' => 'required',
            'description' => 'required',
            'release_date' => 'required',
            'directors' => 'required',
            'presenter' => 'required',
            'scheduled_time' => 'nullable|required',
            'expire_scheduled_time' => 'nullable|required',
            'publish_date' => 'required',
            'trailer_url' => 'required|url',
            'playback_token' => 'required',
            'policy' => 'required',
            'category' => 'required|array',
            'age_rating' => 'required',
            'age_limit' => 'required',
            'is_parental' => 'required',
            'scheduled_publishing' => 'required',
            'publish_now' => 'nullable|required',
            'geo_policy' => 'nullable',
            'is_active' => 'nullable|required',
        ]);

        $this->_validate($this->request, $this->getRules());

        $tvShow = new TvShow();

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $tvShow->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $tvShow->thumbnail_image = $localIamgePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $tvShow->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $tvShow->poster_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $recordingdate = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
        $recordingendDate = (!empty($requestData['expire_scheduled_time'])) ? $requestData['expire_scheduled_time'] : '';
        $publishDate = (!empty($requestData['publish_date'])) ? $requestData['publish_date'] : '';

        // $tvShow->organization = $requestData['organization'];
        $tvShow->title = $requestData['title'];
        $tvShow->description = $requestData['description'];
        $tvShow->release_date = $requestData['release_date'];
        $tvShow->directors = $requestData['directors'];
        $tvShow->presenter = $requestData['presenter'];

        $tvShow->scheduled_time = $recordingdate;
        $tvShow->expire_scheduled_time = $recordingendDate;
        $tvShow->publish_date = $publishDate;

        // $tvShow->geo_block_country_list = $requestData['geo_block_country_list'];
        $tvShow->category = json_encode($requestData['category']);
        $tvShow->trailer_url = $requestData['trailer_url'];
        $tvShow->playback_token = $requestData['playback_token'];
        $tvShow->policy = $requestData['policy'];
        $tvShow->age_rating = $requestData['age_rating'];
        $tvShow->age_limit = $requestData['age_limit'];
        $tvShow->is_parental = $requestData['is_parental'];

        $tvShow->scheduled_publishing = $requestData['scheduled_publishing'] ? 1 : 0;
        $tvShow->publish_now = $requestData['publish_now'] ? 1 : 0;
        // $tvShow->geo_policy = $requestData['geo_policy'] ? 1 : 0;
        $tvShow->is_active = $requestData['is_active'] ? 1 : 0;

        $tvShow->save();

        $user = Auth::user();

        foreach ($requestData['organization'] as $orgId) {
            TvShowOrganization::updateOrCreate([
                'tv_show_id' => $tvShow->id,
                'organization_id' => $orgId
            ], [
                'created_by' => $user->id
            ]);
        }

        return 'success';
    }

    public function getTvShow($id)
    {
        $showId = explode(',', base64_decode($id));
        return $this->_tvshow->with('getAllOrganization')->find($showId);
    }

    public function TvShowUpdate($id)
    {
        if (!empty($id)) {
            $data = $this->_tvshow->findOrFail($id);

            $this->setRules([
                'title' => 'required',
                'description' => 'required'
            ]);

            $this->validate($this->request, $this->getRules());

            if (!empty($this->request->thumbnail_image)) {
                $thumbUrl = explode("/", $this->request->thumbnail_image);
                $fileName = $data->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $data->thumbnail_image = $localIamgePath;
                // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
            }
            if (!empty($this->request->poster_image)) {
                $thumbUrl = explode("/", $this->request->poster_image);
                $fileName = $data->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $data->poster_image = $localIamgePath;
                // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
            }

            $recordingdate = (!empty($this->request->scheduled_time)) ? $this->request->scheduled_time : '';
            $recordingendDate = (!empty($this->request->expire_scheduled_time)) ? $this->request->expire_scheduled_time : '';
            $publishDate = (!empty($this->request->publish_date)) ? $this->request->publish_date : '';

            $data->title = $this->request->title;
            // $data->organization = $this->request->organization;
            $data->description = $this->request->description;
            $data->release_date = $this->request->release_date;
            $data->directors = $this->request->directors;
            $data->presenter = $this->request->presenter;

            $data->scheduled_time = $recordingdate;
            $data->expire_scheduled_time = $recordingendDate;
            $data->publish_date = $publishDate;

            $data->geo_block_country_list = $this->request->geo_block_country_list;
            $data->category = json_encode($this->request->category);
            $data->trailer_url = $this->request->trailer_url;
            $data->playback_token = $this->request->playback_token;
            $data->policy = $this->request->policy;
            $data->age_rating = $this->request->age_rating;
            $data->age_limit = $this->request->age_limit;
            $data->is_parental = $this->request->is_parental;
            $data->content_sets = $this->request->content_sets;

            $data->scheduled_publishing = $this->request->scheduled_publishing ? 1 : 0;
            $data->publish_now = $this->request->publish_now ? 1 : 0;
            $data->geo_policy = $this->request->geo_policy ? 1 : 0;
            $data->is_active = $this->request->is_active ? 1 : 0;

            $data->save();

            $user = Auth::user();

            foreach ($this->request->organization as $orgId) {
                TvShowOrganization::updateOrCreate([
                    'tv_show_id' => $data->id,
                    'organization_id' => $orgId
                ], [
                    'created_by' => $user->id
                ]);
            }
            return
                true;

        } else {
            return
                false;
        }
    }

    // public function prepareGrid()
    // {
    //     $this->setGridModel($this->_tvshow);
    //     return $this;
    // }

    public function prepareGrid()
    {
        $this->setGridModel($this->_tvshow);

        $this->gridQuery = $this->getGridModel()
            ->selectRaw('tv_shows.*, COUNT(DISTINCT tv_show_seasons.id) as total_season, COUNT(tvshow_season_episodes.id) as total_episode')
            ->leftJoin('tv_show_seasons', 'tv_shows.id', '=', 'tv_show_seasons.tv_show_id')
            ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
            ->groupBy('tv_shows.id');

        return $this;
    }


    // public function fetchdata()
    // {
    //     $data = TvShowSeason::select(
    //         'tv_show_seasons.tv_show_id',
    //         DB::raw('COUNT(DISTINCT tv_show_seasons.id) as total_season'),
    //         DB::raw('COUNT(tvshow_season_episodes.id) as total_episode')
    //     )
    //         ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
    //         ->groupBy('tv_show_seasons.tv_show_id')
    //         ->paginate(10);

    //     return $data;
    // }

    // public function fetchdata(Request $request)
    // {
    //     $data = TvShow::select(
    //         'tv_shows.*',
    //         'streaming_url_policy.policy_name as policy_name',
    //         'streaming_url_policy.rules as policy_rules',
    //         'streaming_url_policy.condition as policy_condition',
    //         'play_back_token.name as playback_name',
    //         'play_back_token.type as playback_type',
    //         DB::raw('COUNT(DISTINCT tv_show_seasons.id) as total_season'),
    //         DB::raw('COUNT(tvshow_season_episodes.id) as total_episode')
    //     )
    //         ->with('getAllOrganization')
    //         ->leftJoin('tv_show_seasons', 'tv_shows.id', '=', 'tv_show_seasons.tv_show_id')
    //         ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
    //         ->leftJoin('streaming_url_policy', 'tv_shows.policy', '=', 'streaming_url_policy.id')
    //         ->leftJoin('play_back_token', 'tv_shows.playback_token', '=', 'play_back_token.id')
    //         ->groupBy('tv_shows.id')
    //         ->orderBy('tv_shows.id', 'desc')
    //         ->paginate(10);

    //     return $data;
    // }

    public function fetchdata(Request $request)
    {
        $query = TvShow::select(
            'tv_shows.*',
            'streaming_url_policy.policy_name as policy_name',
            'streaming_url_policy.rules as policy_rules',
            'streaming_url_policy.condition as policy_condition',
            'play_back_token.name as playback_name',
            'play_back_token.type as playback_type',
            DB::raw('COUNT(DISTINCT tv_show_seasons.id) as total_season'),
            DB::raw('COUNT(tvshow_season_episodes.id) as total_episode')
        )
            ->with('getAllOrganization')
            ->leftJoin('tv_show_seasons', 'tv_shows.id', '=', 'tv_show_seasons.tv_show_id')
            ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
            ->leftJoin('streaming_url_policy', 'tv_shows.policy', '=', 'streaming_url_policy.id')
            ->leftJoin('play_back_token', 'tv_shows.playback_token', '=', 'play_back_token.id')
            ->groupBy('tv_shows.id')
            ->orderBy('tv_shows.id', 'desc');

        // ✅ APPLY FILTER HERE
        $query = $this->searchFilter($query);

        $data = $query->paginate(10);

        return $data;
    }

    public function togglePublishNow($id)
    {
        $tvShow = $this->_tvshow->find($id);

        if ($tvShow) {
            $tvShow->is_active = $tvShow->is_active == 1 ? 0 : 1;
            $tvShow->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'TV Show Published Successfully.'
        ]);
    }


    // public function fetchdata()
    // {
    //     $data = TvShow::select(
    //         'tv_shows.*',
    //         'streaming_url_policy.*',
    //         'play_back_token.*',
    //         DB::raw('COUNT(DISTINCT tv_show_seasons.id) as total_season'),
    //         DB::raw('COUNT(tvshow_season_episodes.id) as total_episode')
    //     )
    //         ->leftJoin('tv_show_seasons', 'tv_shows.id', '=', 'tv_show_seasons.tv_show_id')
    //         ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
    //         ->groupBy('tv_shows.id')
    //         ->paginate(15);

    //     return $data;
    // }

    // public function fetchdata()
    // {
    //     $data = TvShowSeason::select(
    //         'tv_shows.*',
    //         DB::raw('COUNT(DISTINCT tv_show_seasons.id) as total_season'),
    //         DB::raw('COUNT(tvshow_season_episodes.id) as total_episode')
    //     )
    //         ->join('tv_shows', 'tv_shows.id', '=', 'tv_show_seasons.tv_show_id')
    //         ->leftJoin('tvshow_season_episodes', 'tv_show_seasons.id', '=', 'tvshow_season_episodes.season_id')
    //         ->groupBy('tv_shows.id')
    //         ->paginate(15);

    //     return $data;
    // }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Streaming Url Policy', 'value' => '', 'sort' => false],
                ['name' => 'Number Of Seasons', 'value' => '', 'sort' => false],
                ['name' => 'Number Of Episodes', 'value' => '', 'sort' => false],
                [
                    'name' => 'Pin Locked',
                    'value' => '',
                    'sort' => false,
                    'hint' => 'Green lock means that tv show is not Pin Locked, red lock means tv show is Pin Locked.'
                ],
                ['name' => 'List', 'value' => '', 'sort' => false],
                ['name' => 'Status', 'value' => '', 'sort' => false],
                ['name' => 'Added Date', 'value' => '', 'sort' => false],
                ['name' => 'Updated Date', 'value' => '', 'sort' => false],
                ['name' => 'Publish Date', 'value' => '', 'sort' => false],
                ['name' => 'Unpublish Date', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    // protected function searchFilter($builderCoupon)
    // {
    //     $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
    //         && is_array($this->request->input(StringLiterals::SEARCHRECORD))
    //         ? $this->request->input(StringLiterals::SEARCHRECORD)
    //         : [];

    //     foreach ($searchRecordUsers as $key => $value) {
    //         if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
    //             continue;
    //         }

    //         $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
    //     }

    //     return $builderCoupon;
    // }

    // protected function searchFilter($builderCoupon)
    // {
    //     $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
    //         && is_array($this->request->input(StringLiterals::SEARCHRECORD))
    //         ? $this->request->input(StringLiterals::SEARCHRECORD)
    //         : [];

    //     foreach ($searchRecordUsers as $key => $value) {

    //         if (in_array($key, ['is_active', 'is_parental'])) {
    //             if ($value === 'all') {
    //                 continue;
    //             }

    //             // Exact match for boolean/integer fields
    //             $builderCoupon = $builderCoupon->where($key, $value);
    //         } else {
    //             // LIKE for other fields
    //             $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
    //         }
    //     }

    //     return $builderCoupon;
    // }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {

            if (in_array($key, ['is_active', 'is_parental'])) {
                if ($value === 'all') {
                    continue;
                }

                $builderCoupon = $builderCoupon->where('tv_shows.' . $key, $value);
            } else {
                $builderCoupon = $builderCoupon->where('tv_shows.' . $key, 'like', "%$value%");
            }
        }

        return $builderCoupon;
    }
}
