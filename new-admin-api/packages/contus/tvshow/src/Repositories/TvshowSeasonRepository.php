<?php

namespace Contus\Tvshow\Repositories;

use Contus\Base\Repository;
use Contus\Tvshow\Model\TvShow;
use Contus\Tvshow\Model\TvShowSeason;
use Contus\Tvshow\Model\TvShowSeasonOrg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TvshowSeasonRepository extends Repository
{
    protected $_tvShowSeason;
    public function __construct(TvShowSeason $tvShowSeason)
    {
        parent::__construct();
        $this->_tvShowSeason = $tvShowSeason;
    }

    public function CreateSeason()
    {
        return $this->create($this->request->all());
    }

    public function create($requestData)
    {
        // dd($requestData);
        $this->setRules([
            'tv_show_id' => 'required',
            'poster_image' => 'nullable',
            'thumbnail_image' => 'nullable',
            'title' => 'required',
            'season_number' => 'required',
            'description' => 'required',
            'directors' => 'required',
            'presenter' => 'required',
            'release_date' => 'required',
            'scheduled_time' => 'required',
            // 'content_sets' => 'required',
            'expire_scheduled_time' => 'nullable',
            'expire_time_unlimited' => 'nullable',
            'publish_date' => 'required',
            'scheduled_publishing' => 'required',
            'publish_now' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $insert = new TvShowSeason();

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

        $insert->tv_show_id = $requestData['tv_show_id'];
        $insert->title = $requestData['title'];
        $insert->description = $requestData['description'];
        $insert->season_number = $requestData['season_number'];
        $insert->directors = $requestData['directors'];
        $insert->presenter = $requestData['presenter'];
        // $insert->content_sets = $requestData['content_sets'];

        $insert->release_date = $release_date;
        $insert->scheduled_time = $recordingdate;
        $insert->expire_scheduled_time = $recordingendDate;
        $insert->publish_date = $publishDate;

        $insert->scheduled_publishing = !empty($requestData['scheduled_publishing']) ? 1 : 0;
        $insert->publish_now = !empty($requestData['publish_now']) ? 1 : 0;
        $insert->expire_time_unlimited = !empty($requestData['expire_time_unlimited']) ? 1 : 0;
        $insert->is_active = !empty($requestData['is_active']) ? 1 : 0;
        // $insert->scheduled_publishing = $requestData['scheduled_publishing'] ? 1 : 0;
        // $insert->publish_now = $requestData['publish_now'] ? 0 : 1;
        // $insert->expire_time_unlimited = $requestData['expire_time_unlimited'] ? 1 : 0;
        // $insert->is_active = $requestData['is_active'] ? 1 : 0;

        $insert->save();

        $user = Auth::user();

        // foreach ($requestData['organization'] as $orgId) {
        //     TvShowSeasonOrg::updateOrCreate([
        //         'tv_show_season_id' => $insert->id,
        //         'organization_id' => $orgId
        //     ], [
        //         'created_by' => $user->id
        //     ]);
        // }

        return 'success';
    }

    public function getTvShowSeason($id)
    {
        $showId = explode(',', base64_decode($id));
        return $this->_tvShowSeason->find($showId);
        // ->with('getAllOrganization')
    }

    public function postEdit($id)
    {
        if (!empty($id)) {

            $editId = $this->_tvShowSeason->findOrFail($id);

            $this->setRules([
                'title' => 'required',
                'season_number' => 'required'
            ]);

            $this->validate($this->request, $this->getRules());

            if (!empty($this->request->thumbnail_image)) {
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
            if (!empty($this->request->poster_image)) {
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

            $editId->tv_show_id = $this->request->tv_show_id;
            $editId->title = $this->request->title;
            $editId->description = $this->request->description;
            $editId->season_number = $this->request->season_number;
            $editId->directors = $this->request->directors;
            $editId->presenter = $this->request->presenter;
            $editId->content_sets = $this->request->content_sets;

            $editId->release_date = $release_date;
            $editId->scheduled_time = $recordingdate;
            $editId->expire_scheduled_time = $recordingendDate;
            $editId->publish_date = $publishDate;

            $editId->scheduled_publishing = !empty($this->request->scheduled_publishing) ? 1 : 0;
            $editId->publish_now = !empty($this->request->publish_now) ? 1 : 0;
            $editId->expire_time_unlimited = !empty($this->request->expire_time_unlimited) ? 1 : 0;
            $editId->is_active = !empty($this->request->is_active) ? 1 : 0;

            $editId->save();

            // $user = Auth::user();

            // foreach ($this->request->organization as $orgId) {
            //     TvShowSeasonOrg::updateOrCreate([
            //         'tv_show_season_id' => $editId->id,
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

    public function postRemove($id)
    {
        $remove = $this->_tvShowSeason->findOrFail($id);
        if ($remove) {
            $remove->delete();
            return 'success';
        } else {
            return 'Id not found!';
        }
    }

    public function prepareGrid()
    {
        // dd();
        $this->setGridModel($this->_tvShowSeason)
            ->setEagerLoadingModels(['GetTvshow', 'getAllOrganization']);
        return $this;
    }

    public function getRecords()
    {
        $validate = Validator::make($this->request->all(), [
            'tv_show_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 400);
        }

        $records = $this->_tvShowSeason->with(['GetTvshow'])
            ->where('tv_show_id', $this->request->tv_show_id)
            ->orderBy('id', 'desc')
            ->get();
        return $records;
    }

    public function fetchRecords()
    {
        // $query = TvShow::with(['GetSeasonData', 'getAllOrganization']);


        // if ($this->request->has('organization_id')) {
        //     $query->where('organization', $this->request->organization_id);
        // }

        // $data = $query->paginate(15);
        // return $data;

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
}