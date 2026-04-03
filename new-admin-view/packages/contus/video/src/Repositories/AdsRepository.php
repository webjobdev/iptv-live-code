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

// use Contus\Video\Contracts\ICategoryRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Ads;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;
use Illuminate\Support\Facades\DB;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\CategoryTranslation;

class AdsRepository extends BaseRepository {
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
    public function __construct(Ads $ads, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->ads = $ads;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
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
    public function addOrUpdateCategory($id = null) {
       if (! empty ( $id )) {
            $category = $this->ads->find ( $id );
            $this->setRule ( StringLiterals::TITLE, 'required' );
            $this->setRule ( 'ad_tag', 'required' );
            $this->setRule ( 'ad_tag', 'required|active_url' );
        } else {
          
            $this->setRule ( StringLiterals::TITLE, 'required' );
            $this->setRule ( 'ad_tag', 'required' );
            $this->setRule ( 'ad_tag', 'required|active_url' );

         if(empty($this->authUser->id)){
           
          return "session_expire";
         }else{
          
            $category = new Ads ();
            $category->creator_id = $this->authUser->id;
         }
        }

        $this->_validate ();

        $category->fill ( $this->request->except ( '_token' ) );
        $category->ads_url = $this->request->ad_tag;
        $category->is_active = ($this->request->is_active) ? 1 : 0;
        $category->updator_id = $this->authUser->id;
        if ($category->save ()) {
            return true;
        }
    }
     /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */
     public function deleteCategoryImage($id){
        /**
         * Check if category id exists.
         */
         if (!empty ($id)) {
            $category =  $this->ads->findorfail($id);
            $categoryImage = $category->image_url;
            if(!empty($categoryImage)){
                $URL = $category->getImageBaseName($category->image_url);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.category_image.s3_location").$URL;
                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.category_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath,$URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $category->image_url = '';
                $category->save();
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
     }

    /**
     * Fetch users to display in admin block.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getCategories($status) {
        return $this->ads->filter ( $status )->paginate ( 10 );
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
        $this->setGridModel ( $this->ads );
        return $this;
    }

    public function getGridHeadings(){
        return ['heading' => [['name' => trans('video::ads.ads_name'), 'value' => 'title', 'sort' => true],        
        ['name' => trans('video::ads.ads_url'), 'value' => 'is_active', 'sort' => false],        
        ['name' => trans('video::ads.status'), 'value' => 'is_active', 'sort' => false], 
        ['name' => trans('video::ads.added_on'), 'value' => '', 'sort' => false], 
        ['name' => trans('video::ads.action'), 'value' => '', 'sort' => false]]];
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /**
         * updated the grid query by using this function and apply the video condition.
         */
       
        return $builder->selectRaw('*, id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of categories grid
     *
     * @param mixed $builderCategories
     * @return \Illuminate\Database\Eloquent\Builder $builderCategories The builder object of categories grid.
     */
    protected function searchFilter($builderCategories) {
        $searchRecordCategories = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        $title = $is_active = null;
        extract ( $searchRecordCategories );

        /**
         * Check if the title of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderCategories = $builderCategories->where ( StringLiterals::TITLE, 'like', '%' . $title . '%' );
        }

        if ($is_active != 'all' && $is_active != null) {
            $builderCategories = $builderCategories->where ('is_active', (int) $is_active);
        }

        return $builderCategories;
    }

    /**
     * Function to activate the Category
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function categoryActivateOrDeactivate($ids, $isStatus){
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
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->ads->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
           
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->ads->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
         
            return $status;
        }
    }

   
}
