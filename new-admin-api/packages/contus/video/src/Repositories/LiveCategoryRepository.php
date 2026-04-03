<?php

/**
 * Category Repository
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @name CategoriesRepository
 * @vendor Contus
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\LiveCategoryRepository as BaseRepository;
use Contus\Video\Contracts\ICategoryRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Season;
use Contus\Video\Models\CategoryTranslation;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;

class LiveCategoryRepository extends BaseRepository implements ICategoryRepository
{
    use CategoryTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_category;

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
     * @param Contus\Video\Models\Categories $categories
     */
    public function __construct(Category $category, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->_category = $category;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->setRules([StringLiterals::TITLE => 'required']);
    }
    /**
     * Store a newly created categories.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package Video
     * @return boolean
     */
    public function addOrUpdateCategory($id = null)
    {
        if (!empty($id)) {
            $category = $this->_category->find($id);
            // $this->setRule(StringLiterals::TITLE, 'required|unique:categories,' . StringLiterals::TITLE . ',' . $id . 'id');
        } else {

            if (empty(\Auth::user()->id)) {

                return "session_expire";
            } else {
                // $this->setRule(StringLiterals::TITLE, 'required|unique:categories');
                $this->setRules([StringLiterals::TITLE => 'required']);

                $category = new Category();
                $category->creator_id = \Auth::user()->id;
            }
        }

        $this->_validate();

        $category->fill($this->request->except('_token'));
        $category->is_leaf_category = $this->request->is_leaf_category;
        if (empty($this->request->parent_id)) {
            $category->level = '0';
            $category->parent_id = 0;
        } else {
            $category->level = $this->getHieraechyCountLevel($this->request->parent_id);
            $category->parent_id = $this->request->parent_id;
        }
        if ($this->request->image && $this->request->is_image_updated == 1) {
            $this->deleteCategoryImage($id);
            $fileName = $category->getImageBaseName($this->request->image);
            $folderName = config("contus.base.image.category_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.category_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $category->image_url = $s3BucketImgURL;
        }
        if ($this->request->has('category_order') && $this->request->category_order != "") {
            $category->category_order = $this->request->category_order;
        }
        $category->preference_order = ((int) $this->request->preference_order) ? $this->request->preference_order : null;
        $category->updator_id = \Auth::user()->id;
        $category->is_web_series = $this->request->is_web_series;
        $category->is_radio = 2;
        if ($category->save()) {
            return true;
        }
    }
    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deleteCategoryImage($id)
    {
        /**
         * Check if category id exists.
         */
        if (!empty($id)) {
            $category = $this->_category->findorfail($id);
            $categoryImage = $category->image_url;
            if (!empty($categoryImage)) {
                $URL = $category->getImageBaseName($category->image_url);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.category_image.s3_location") . $URL;
                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.category_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $category->image_url = '';
                $category->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // public function deleteSeasonImage($id)
    // {
    //     /**
    //      * Check if category id exists.
    //      */
    //     if (!empty($id)) {
    //         $season = $this->_season->findorfail($id);
    //         // $season = Season::where('id', $id)->get();
    //         \Log::info($season);

    //         $seasonImage = $season->image;
    //         if (!empty($seasonImage)) {
    //             $URL = $season->getImageBaseName($season->image);
    //             /** call to method to delete image in S3 bucket */
    //             $imageURL = config("contus.base.image.season_image.s3_location") . $URL;
    //             $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
    //             /** Process to delete image from local storage path */
    //             $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.season_image.temporary_image_storage_path");
    //             $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
    //             /**
    //              * Empty the image_url and image_path field in the database.
    //              */
    //             $season->image = '';
    //             $season->save();
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         return false;
    //     }
    // }
    /**
     * Function to get hierarchy level of a category.
     *
     * @param integer $parentId
     * The parent id of the category.
     * @return string The hierarchy string.
     */
    public function getHieraechyLevel($parentId)
    {
        $category = new Category();
        $parentLevel = $category->where('id', $parentId)->value('level');
        return $parentLevel . '/' . $parentId;
    }
    /**
     * Function to get hierarchy level of a category.
     *
     * @param integer $parentId
     * The parent id of the category.
     * @return string The hierarchy string.
     */
    public function getHieraechyCountLevel($parentId)
    {
        $category = new Category();
        $parentLevel = $category->where('id', $parentId)->value('level');
        return $parentLevel + 1;
    }
    /**
     * Fetch users to display in admin block.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getCategories($status)
    {
        return $this->_category->filter($status)->paginate(10);
    }
    /**
     * Fetch user to edit.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getCategory($id)
    {
        return $this->_category->find($id);
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
    public function prepareGrid()
    {
        $this->setGridModel($this->_category)->setEagerLoadingModels(['parent_category.parent_category', 'child_category', 'videocategory' => function ($query) {
            $query->whereHas('video', function ($query) {
                $query->where(StringLiterals::IS_ARCHIVED, 0);

            });
        }, 'CategoryTranslation']);
        return $this;
    }

    /**
     * Function to apply filter for search of categories grid
     *
     * @param mixed $builderCategories
     * @return \Illuminate\Database\Eloquent\Builder $builderCategories The builder object of categories grid.
     */
    protected function searchFilter($builderCategories)
    {
        $searchRecordCategories = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = null;
        extract($searchRecordCategories);

        /**
         * Check if the title of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderCategories = $builderCategories->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }

        /**
         * Check if the status of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderCategories = $builderCategories->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderCategories;
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
    public function isUniqueCategories($id = null)
    {
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
     * Function used to retrieve parent catgory with its child category in (tree structure) format
     *
     * @return string
     */
    public function getAllCategoryList()
    {
        $categories = Category::where('parent_id', 0)->where('is_web_series', 0)->where('is_deletable', 1)->select('id', StringLiterals::TITLE)->get();
        $categoryList = '<ul>';

        if (sizeof($categories) > 0) {
            foreach ($categories as $category) {
                $categoryList = $categoryList . '<li id="category_id_' . $category->id . '"><div class="rdio"><input type="radio" name="parent_id" data-ng-model="catgridCtrl.category.parent_id" value=' . $category->id . '> <label> <svg viewBox="0 0 201 156" version="1.1" x="0px" y="0px" width="13px" height="10px"> <g> <path d="M 1 156 L 1 1 L 64 1 L 91 46 L 201 46 L 201 156 L 1 156 ZM 91 16 L 201 16 L 201 31 L 100 31 L 91 16 Z"  fill="#00b6df"/> </g> </svg> <span>' . $category->title . '</span></label></div>';
                $categoryStatus = $this->hasChild($category->id);

                if (!empty($categoryStatus)) {
                    $categoryList .= $categoryStatus;
                }
                $categoryList .= "</li>";
            }
        }
        return $categoryList .= "</ul>";
    }
    /**
     * Get the child category for the playlist
     *
     * @return string
     */
    public function getChildCategoryList()
    {
        $categoryStatus = [];
        $categories = Category::where('parent_id', 0)->where('is_deletable', 1)->select('id', StringLiterals::TITLE)->get();
        if (sizeof($categories) > 0) {
            foreach ($categories as $category) {
                $categoryStatus = $this->hasparentChild($category->id);
            }
        }
        return $categoryStatus;
    }
    /**
     * Get child category slug
     *
     * @param unknown $id
     */
    public function hasparentChild($id)
    {
        return Category::where('parent_id', $id)->select('id', StringLiterals::TITLE)->get();
    }
    /**
     * Function used to retrieve child category in tree structure format
     *
     * @param int $id
     * @return string
     */
    public function hasChild($id)
    {
        $categories = Category::where('parent_id', $id)->select('id', 'level', StringLiterals::TITLE)->get();
        $categoryList = '';
        if (sizeof($categories) > 0) {
            $categoryList .= "<ul>";
            foreach ($categories as $category) {
                if ($category->level == 2) {
                    $categoryList .= '<li id="category_id_' . $category->id . '"><label><svg viewBox="0 0 256 256" version="1.1" x="0px" y="0px" width="15px" height="15px"> <g> <path d="M 165.6923 241 C 166.5239 238.651 167 236.134 167 233.5 C 167 230.866 166.5239 228.349 165.6923 226 L 256 226 L 256 241 L 165.6923 241 ZM 28 0 L 91 0 L 118 45 L 228 45 L 228 155 L 28 155 L 28 0 ZM 118 15 L 228 15 L 228 30 L 127 30 L 118 15 ZM 89.789 227.6746 C 89.7626 227.772 89.7388 227.8702 89.7136 227.9681 C 89.5877 228.465 89.4717 228.9645 89.3795 229.474 C 89.3311 229.7342 89.2985 229.9993 89.2592 230.2625 C 89.2039 230.6448 89.1412 231.0241 89.1054 231.4123 C 89.0395 232.0997 89 232.7949 89 233.5 C 89 234.2051 89.0395 234.9003 89.1054 235.5877 C 89.1412 235.9759 89.2039 236.3552 89.2592 236.7375 C 89.2985 237.0007 89.3311 237.2658 89.3795 237.526 C 89.4717 238.0354 89.5877 238.535 89.7136 239.0319 C 89.7388 239.1298 89.7626 239.228 89.789 239.3254 C 89.9411 239.8931 90.1133 240.4509 90.3077 241 L 0 241 L 0 226 L 90.3077 226 C 90.1133 226.5491 89.9411 227.1069 89.789 227.6746 ZM 121 195.9625 L 121 170 L 136 170 L 136 196.6737 C 133.3761 195.6016 130.5097 195 127.5 195 C 125.2388 195 123.0589 195.3419 121 195.9625 ZM 128.5 211 C 140.9264 211 151 221.0736 151 233.5 C 151 245.9264 140.9264 256 128.5 256 C 116.0736 256 106 245.9264 106 233.5 C 106 221.0736 116.0736 211 128.5 211 Z" fill="#ff971d"/> </g> </svg><span>' . $category->title . '</span></label>';
                } else {
                    $categoryList .= '<li id="category_id_' . $category->id . '"><input type="hidden" name="parent_id" data-ng-model="catgridCtrl.category.parent_id" value=' . $category->id . '><label><svg viewBox="0 0 257 257" version="1.1" x="0px" y="0px" width="15px" height="15px"> <g> <path d="M 166.6923 242 C 167.5239 239.651 168 237.134 168 234.5 C 168 231.866 167.5239 229.349 166.6923 227 L 257 227 L 257 242 L 166.6923 242 ZM 29 1 L 92 1 L 119 46 L 229 46 L 229 156 L 29 156 L 29 1 ZM 119 16 L 229 16 L 229 31 L 128 31 L 119 16 ZM 90.789 228.6746 C 90.7626 228.772 90.7388 228.8702 90.7136 228.9681 C 90.5877 229.465 90.4717 229.9645 90.3795 230.474 C 90.3311 230.7342 90.2985 230.9993 90.2592 231.2625 C 90.2039 231.6448 90.1412 232.0241 90.1054 232.4123 C 90.0395 233.0997 90 233.7949 90 234.5 C 90 235.2051 90.0395 235.9003 90.1054 236.5877 C 90.1412 236.9759 90.2039 237.3552 90.2592 237.7375 C 90.2985 238.0007 90.3311 238.2658 90.3795 238.526 C 90.4717 239.0354 90.5877 239.535 90.7136 240.0319 C 90.7388 240.1298 90.7626 240.228 90.789 240.3254 C 90.9411 240.8931 91.1133 241.4509 91.3077 242 L 1 242 L 1 227 L 91.3077 227 C 91.1133 227.5491 90.9411 228.1069 90.789 228.6746 ZM 122 196.9625 L 122 171 L 137 171 L 137 197.6737 C 134.3761 196.6016 131.5097 196 128.5 196 C 126.2388 196 124.0589 196.3419 122 196.9625 ZM 129.5 212 C 141.9264 212 152 222.0736 152 234.5 C 152 246.9264 141.9264 257 129.5 257 C 117.0736 257 107 246.9264 107 234.5 C 107 222.0736 117.0736 212 129.5 212 Z" fill="#959595"/> </g> </svg><span>' . $category->title . '</label></span>';
                }
                $categoryStatus = $this->hasChild($category->id);
                if (!empty($categoryStatus)) {
                    $categoryList .= $categoryStatus;
                }
                $categoryList .= "</li>";
            }
            $categoryList .= "</ul>";
        }
        return $categoryList;
    }
    /**
     * Repository function to get the category breadcrumb
     *
     * @param integer $id.
     * @return variable
     */
    public function getBreadcrumb($id)
    {
        $categoryLevel = $this->getCategory($id);
        $categoryBreadcrumb = [];
        if ($categoryLevel->parent_id != 0) {
            $parentCategory = $this->_category->find($categoryLevel->parent_id);
            $categoryBreadcrumb['parent']['id'] = $categoryLevel->parent_id;
            $categoryBreadcrumb['parent']['name'] = $parentCategory->title;
        }

        $categoryBreadcrumb['child'] = $categoryLevel->title;
        return $categoryBreadcrumb;
    }

    /**
     * Repository function to get the parentcategory list
     *
     * @param integer $id
     * @return variable
     */
    public function getParentCategory($id)
    {
        $categoryData = $this->_category->find($id);
        $categoryData = explode('/', $categoryData->parent_id);
        $parentCategoryTitle = [];
        $parentcategoryData = [];
        foreach ($categoryData as $value) {
            if ($value != 0) {
                $parentcategoryTitleData = $this->_category->select('id', StringLiterals::TITLE)->find($value);
                $parentCategoryTitle[$parentcategoryTitleData->id] = $parentcategoryTitleData->title;
                $parentcategoryData[] = $this->_category->find($value);
            }
        }
        return array('parentcategoryTitle' => $parentCategoryTitle, 'parentcategoryData' => $parentcategoryData);
    }

    /**
     * Repository function to get the category related videos list
     *
     * @param integer $id
     * @return variable
     */
    public function getVideoCategories($id)
    {
        $this->_category = $this->_category->find($id);
        if (is_null($this->_category)) {
            return $this->_category;
        }

        return ['category' => $this->_category, 'videos' => $this->_category->videos()->with(['transcodedvideos.presets', 'videocategory.category', 'recent'])->where('is_archived', 0)->paginate(10)->toArray()];
    }

    /**
     * Repository function to get the childcategory list
     *
     * @param integer $id
     * @return variable
     */
    public function getCategoryWithChild($id)
    {
        return $this->_category->with('child_category')->findOrFail($id);
    }
    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getAllCategories($slug = '')
    {
        $subcatvalue = [];
        if ($slug) {
            $categoryinfo = $this->_category->where($this->getKeySlugorId(), $slug)->where('is_active', 1)->where('parent_id', 0)->with('child_category.child_category')->get();
        } else {
            $categoryinfo = $this->_category->where('parent_id', 0)->where('is_active', 1)->with('child_category.child_category')->get()->toArray();
        }
        if (count($categoryinfo) > 0) {
            foreach ($categoryinfo as $value) {
                if (count($value['child_category']) > 0) {

                    $subcatvalue = $subcatvalue + $this->getChildCategoryEach($value);
                }
            }
        }

        return $subcatvalue;
    }

    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getAllCategoryInfo($slug = '')
    {
        return $this->_category->selectRaw('id, title')->has('child_category', '>', 0)->where('parent_id', 0)->where('is_web_series', 0)->where('is_active', 1)->with('child_category.child_category')->get()->toArray();
    }

    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getWebSeriesCategoryInfo($slug = '')
    {
        $catInfo = $this->_category->selectRaw('id, title')->where('parent_id', 0)->where('is_web_series', 1)->where('is_active', 1)->with('child_category.child_category')->get()->toArray();
        return $catInfo;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        //$builder->with('CategoryTranslation');
        $filters = $this->request->input('filters');
        if (!empty($filters)) {
            foreach ($filters as $value) {
                if ($value == 'live_videos') {
                    $builder->whereNotNull('preference_order')->orderBy('preference_order');
                }
            }
        }
        $builder->where('video_webseries_detail_id', null);
        $builder->selectRaw('categories.*, categories.id as formatted_created_date')->where('is_deletable', 1)->where('is_radio', 2);
        return $builder;
    }

    public function getAllSeries()
    {
        return Category::where('parent_id', '>', 0)->where('is_active', 1)->whereHas('parent_category', function ($query) {
            $query->where('is_web_series', 1);
        })->pluck('id');
    }

    public function updateCategoriesTranslation($id)
    {

        if (!empty($id)) {
            $this->setRules(['title' => StringLiterals::REQUIRED]);
            $this->validate($this->request, $this->getRules());
            $category_translation;
            if (CategoryTranslation::where('category_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->count() > 0) {
                $category_translation = CategoryTranslation::where('category_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $category_translation = new CategoryTranslation();
                $category_translation->category_id = $id;
                $category_translation->language_id = $this->request->languageCode;
            }
            $category_translation->title = $this->request->title;
            return $category_translation->save();
        }

    }

    /**
     * Function to activate the Category
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function categoryActivateOrDeactivate($ids, $isStatus)
    {
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
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->_category->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);

            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->_category->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);

            return $status;
        }
    }

}
