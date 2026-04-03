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


use Contus\Video\Models\Cast;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\VideoXrayCast;

class CastRepository extends BaseRepository {
    use CategoryTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_category;
    protected $cast;
    protected $uploadRepository;
    protected $awsRepository;
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
    public function __construct(Cast $cast, UploadRepository $uploadRepository,AWSUploadRepository $awsRepository) {
        parent::__construct ();
        $this->cast = $cast;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = $awsRepository;
        $this->video = new Video ();
        $this->setRule ( 'name', 'required' );
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
       if (! empty ( $this->request->id )) {
            $category = $this->cast->find ($this->request->id );
            $this->setRule ( 'name', 'required' );
        } else {
            $this->setRule ( 'name', 'required' );
         if(empty(\Auth::user()->id)){
           
          return "session_expire";
         }else{
          
            $category = new Cast ();
            $category->creator_id = \Auth::user()->id;
         }
        }

        $this->_validate ();
        if(!empty($this->request->id) && $this->request->cast_image!=null){
            $category->banner_image = $this->castImageUpload();
        }if(empty($this->request->id) && $this->request->cast_image!=null){
           $category->banner_image = $this->castImageUpload();
        }

        $category->fill ( $this->request->except ( '_token' ) );
        $category->external_url = $this->request->external_url;
        $category->is_active = ($this->request->is_active) ? 1 : 0;
        $category->description = $this->request->cast_description;
        $category->updator_id = \Auth::user()->id;
        if ($category->save ()) {
            $this->videoCastUpdate($this->request->videos,$category);
            return true; 
        }
    }



    public function videoCastUpdate($videos,$category){
         $trash = VideoXrayCast::where('x_ray_cast_id',$this->request->id)->delete();
         if($videos!=null){
            if(is_array($videos)){
                $videolist = $videos;  
            }else{
               $videolist = explode (",", $videos);  
            }
         foreach($videolist as $videoId){
            $video = new VideoXrayCast();
            $video->video_id = $videoId;
            if($category->id!=null){
                $video->x_ray_cast_id = $category->id;
            }else{
            $video->x_ray_cast_id = $this->request->id;
            }
            $video->save();
         }
        }
    }



    public function castImageUpload(){
        $fileName = $this->cast->getImageBaseName($this->request->cast_image);
        // $folderName = config("contus.base.image.cast_images.s3_location");
        $localStoragePath = config("app.url") . config("contus.base.image.cast_images.temporary_image_storage_path");
        // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
        // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
        $localImageUrl = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
        return $localImageUrl;
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
            $category =  $this->cast->findorfail($id);
            $categoryImage = $category->image_url;
            if(!empty($categoryImage)){
                $URL = $category->getImageBaseName($category->image_url);
                /** call to method to delete image in S3 bucket */
                // $imageURL = config("contus.base.image.category_image.s3_location").$URL;
                // $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = config("app.rl") . DIRECTORY_SEPARATOR . config("contus.base.image.category_image.temporary_image_storage_path");
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
        return $this->cast->filter ( $status )->with('getCastVideos')->paginate ( 10 );
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
        $this->setGridModel ( $this->cast )->setEagerLoadingModels(['getCastVideos']);
        return $this;
    }

    public function getGridHeadings(){
        return ['heading' => 
        [
        ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
        ['name' => trans('video::cast.cast_name'), 'value' => 'name', 'sort' => true],        
        ['name' => trans('video::cast.cast_url'), 'value' => 'is_active', 'sort' => false],        
        ['name' => trans('video::cast.status'), 'value' => 'is_active', 'sort' => false], 
        ['name' => trans('video::cast.added_on'), 'value' => '', 'sort' => false], 
        ['name' => trans('video::cast.action'), 'value' => '', 'sort' => false]]];
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
        $name = $is_active = null;
        extract ( $searchRecordCategories );

        /**
         * Check if the title of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if ($name) {
            $builderCategories = $builderCategories->where ( StringLiterals::NAME, 'like', '%' . $name . '%' );
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
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->cast->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
           
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->cast->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
         
            return $status;
        }
    }
    public function getSearchCast() {
        $this->setRules(['search' => 'required', 'order'=>'sometimes|in:title', 'sort'=>'sometimes|in:asc,desc']);
        $this->validate($this->request, $this->getRules());
        $searchKey = $this->request->search;
        $cast = $this->cast->where(function($query) use ($searchKey) {
            $query->orwhere('name', 'like', '%'.$searchKey.'%')->orwhere('description', 'like', '%'.$searchKey.'%');
        });
        $fields = 'x_ray_cast.id, x_ray_cast.name, x_ray_cast.description';
        $cast->selectRaw($fields)->groupBy('x_ray_cast.id');
        $inputArray = $this->request->all();
        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
        $cast->orderBy($inputArray['order'], $inputArray['sort']);
        }
        else {
        $cast->orderBy('id', 'desc');
        }
        return $cast->paginate(config('access.perpage'));
    }
    /**
     * Method to fetch videos for adding into cast (X-RAY)
     * @return array
    */
    public function fetchVideos(){
        $this->setRules(['search' => 'required']);
        $this->validate($this->request, $this->getRules());
        $searchKey = $this->request->search;
        $videoData = $this->video->where( 'videos.is_active', '1' )
        ->where( 'videos.is_active', '1' )
        ->where('videos.is_live' , 0)
        ->where ( 'job_status', 'Complete' )
        ->where ( 'is_archived', 0 )
        ->where('title', 'like', '%'.$searchKey.'%');
        return $videoData->selectRaw('videos.id, videos.title, videos.slug')
        ->groupBy('videos.id')
        ->orderBy('id', 'desc')
        ->paginate(config('access.perpage'));

    }
}