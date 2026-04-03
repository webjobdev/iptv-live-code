<?php

/**
 * Webseries Repository
 *
 * To manage the functionalities related to the Webseries module from Webseries Controller
 *
 * @name WebseriesRepository
 * @vendor Contus
 * @package Videos
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Webseries;
use Contus\Video\Models\WebseriesTranslation;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;

class WebseriesRepository extends BaseRepository {
    use CategoryTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_category;
    protected $_webseries;

    protected $uploadRepository;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCategories = 'q';
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Webseries $webseries
     */
    public function __construct(Webseries $webseries, Category $category, UploadRepository $uploadRepository) {
        parent::__construct();
        $this->_category = $category;
        $this->_webseries = $webseries;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->setRules([StringLiterals::TITLE => 'required|max:50', 'category' => 'required', /*'description' => 'required'*/]);
    }
    /**
     * Store a newly created webseries.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package Video
     * @return boolean
     */
    public function addOrUpdateWebseries($id = null) {
        if (!empty($id)) {
            $webseries = $this->_webseries->find($id);
            $category = $this->_category->where('video_webseries_detail_id', $id)->first();
            $this->setRules([StringLiterals::TITLE => 'required|max:50', 'category' => 'required', /*'description' => 'required'*/]);
        } else {
            if (empty(\Auth::user()->id)) {
                return "session_expire";
            } else {
                $webseries = new Webseries();
                $category = new Category();
                $webseries->creator_id = \Auth::user()->id;
                $category->creator_id = \Auth::user()->id;
            }
        }
        $this->_validate();
        $webseries->fill($this->request->except('_token'));
        if ($this->request->thumbnail && $this->request->is_thumbnail_updated == 1) {
            $this->deleteWebseriesImages($id, 'thumbnail');
            $fileName = $webseries->getImageBaseName($this->request->thumbnail);
            // $folderName = config("contus.base.image.webseries_thumbnails.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.webseries_thumbnails.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $localImgURL = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $webseries->thumbnail_image = $localImgURL;
        }
        if ($this->request->poster_image && $this->request->is_posterimg_updated == 1) {
            $this->deleteWebseriesImages($id, 'poster');
            $fileName = $webseries->getImageBaseName($this->request->poster_image);
            // $folderName = config("contus.base.image.webseries_posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.webseries_posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $localImgURL = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $webseries->poster_image = $localImgURL;
        }
        $webseries->updator_id = \Auth::user()->id;
        $webseries->parent_category_id = $this->request->category;
        $webseries->genre_id = $this->request->genre;
        if ($webseries->save()) {
            $category->title = $this->request->title;
            $category->parent_id = $this->request->category;
            $category->is_active = 1;
            $category->updator_id = \Auth::user()->id;
            $category->video_webseries_detail_id = $webseries->id;
            $category->save();
            return true;
        }
    }

    /**
     * Repository function to delete webseries thumbnail/poster images.
     *
     * @param integer $id
     * @param string $type poster or thumbnail
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deleteWebseriesImages($id, $type) {
        /**
         * Check if webseries id exists.
         */
        if (!empty($id)) {
            $webseries = $this->_webseries->findorfail($id);
            $imageData = ($type == 'thumbnail') ? $webseries->thumbnail_image : $webseries->poster_image;
            if (!empty($imageData)) {
                $URL = $webseries->getImageBaseNameWithDirectory($imageData);
                /** call to method to delete image in S3 bucket */
                // $imageURL = ($type == 'thumbnail') ?
                //     config("contus.base.image.webseries_thumbnails.s3_fetch_location") . $URL :
                //     config("contus.base.image.webseries_posters.s3_fetch_location") . $URL;
                // $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = ($type == 'thumbnail') ?
                    config("app.url") . DIRECTORY_SEPARATOR . config("contus.base.image.webseries_thumbnails.temporary_image_storage_path") : config("app.url") . DIRECTORY_SEPARATOR . config("contus.base.image.webseries_posters.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                ($type == 'thumbnail') ? $webseries->thumbnail_image = '' : $webseries->poster_image = '';
                $webseries->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getWebseries($id) {
        return $this->_webseries->where('id', $id)->with('webseriesTranslation', 'webseries_category')->first();
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Video
     * @return Contus\Video\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel($this->_webseries)->setEagerLoadingModels(['parent_category', 'webseries_category.videosCount']);
        return $this;
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('video::webseries.title'), 'value' => 'title', 'sort' => true],
            ['name' => trans('video::webseries.no_of_videos'), 'value' => '', 'sort' => false],
            ['name' => trans('video::webseries.parent_category'), 'value' => '', 'sort' => false],
            ['name' => trans('video::webseries.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('video::webseries.added_on'), 'value' => '', 'sort' => false],
            // ['name' => trans('video::webseries.web_series_order'), 'value' => '', 'sort' => false],
            ['name' => trans('video::webseries.action'), 'value' => '', 'sort' => false]
        ]];
    }

    /**
     * Function to apply filter for search of webseries grid
     *
     * @param mixed $builderWebseries
     * @return \Illuminate\Database\Eloquent\Builder $builderWebseries The builder object of webseries grid.
     */
    protected function searchFilter($builderWebseries) {
        $searchRecordWebseries = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = null;
        extract($searchRecordWebseries);

        /**
         * Check if the title of the webseries is present in the webseries search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderWebseries = $builderWebseries->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }

        /**
         * Check if the status of the webseries is present in the webseries search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderWebseries = $builderWebseries->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderWebseries;
    }

    /**
     * Check the collection name provied is unique.
     * check only if the request has the expected param
     *
     * @vendor Contus
     *
     * @package User
     * @param int $id
     * @return boolean
     */
    public function isUniqueCategories($id = null) {
        if ($this->request->has($this->requestedCategories)) {
            $uniqueQuery = $this->_category->where(StringLiterals::TITLE, $this->request->get($this->requestedCategories));
            if ($id) {
                $uniqueQuery->where('id', '!=', $id);
            }

            return $uniqueQuery->count() == 0;
        }
        return false;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder) {
        //$builder->with('CategoryTranslation');
        /*  $filters = $this->request->input ( 'filters' );
        if (! empty ( $filters )) {
        foreach ( $filters as $value ) {
        if($value == 'live_videos'){
        $builder->whereNotNull('preference_order')->orderBy('preference_order');
        }
        }
        }
         */
        $builder->selectRaw('video_webseries_detail.*, video_webseries_detail.id as formatted_created_date')->orderBy('webseries_order', 'asc');
        return $builder;
    }

    public function getAllSeries() {
        return Category::where('parent_id', '>', 0)->where('is_active', 1)->whereHas('parent_category', function ($query) {
            $query->where('is_web_series', 1);
        })->pluck('id');
    }

    public function deleteAction($ids, $isStatus) {
        $ids = is_array($ids) ? $ids : [$ids];
        $isDeleted = false;
        if (!empty($ids)) {
            $catIds = $this->_category->whereIn('video_webseries_detail_id', $ids)->pluck('id')->toArray();
            $catDeleted = $this->_category->whereIn('id', $catIds)->delete();
            $isDeleted = $this->_webseries->whereIn('id', $ids)->delete();
        }
        return $isDeleted;
    }
    /**
     * Function to activate the Category
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function webseriesActivateOrDeactivate($ids, $isStatus) {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            $catIds = $this->_category->whereIn('video_webseries_detail_id', $ids)->pluck('id')->toArray();
            $catStatus = empty($catIds) ? StringLiterals::LITERALFALSE : $this->_category->whereIn('id', $catIds)->update([StringLiterals::ISACTIVE => 1]);
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->_webseries->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);

            return $status;
        } else if ($isStatus == 'deactivate') {
            $catIds = $this->_category->whereIn('video_webseries_detail_id', $ids)->pluck('id')->toArray();
            $catStatus = empty($catIds) ? StringLiterals::LITERALFALSE : $this->_category->whereIn('id', $catIds)->update([StringLiterals::ISACTIVE => 0]);
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->_webseries->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);

            return $status;
        }
    }

    /**
     * Repository function to add language for video
     * The id of the video
     * @return boolean True if add language is successfully and false if not
     */
    public function updateVideoLanguage($id) {
        if (!empty($id)) {
            $this->setRules([
                StringLiterals::TITLE => StringLiterals::REQUIRED,
                'description' => StringLiterals::REQUIRED,
                'languageCode' => StringLiterals::REQUIRED,
            ]);
            $this->validate($this->request, $this->getRules());
            $video_tramslation = null;
            if (WebseriesTranslation::where('webseries_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->count() > 0) {
                $video_tramslation = WebseriesTranslation::where('webseries_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $video_tramslation = new WebseriesTranslation;
                $video_tramslation->webseries_id = $id;
                $video_tramslation->language_id = $this->request->languageCode;
            }
            $video_tramslation->title = $this->request->title;
            $video_tramslation->description = $this->request->description;
            $video_tramslation->presenter = ($this->request->has('presenter')) ? $this->request->presenter : '';
            if ($video_tramslation->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
