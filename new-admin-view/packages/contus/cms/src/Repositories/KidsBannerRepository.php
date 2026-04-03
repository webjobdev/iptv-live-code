<?php

/**
 * Banner Repository
 *
 * To manage the functionalities related to the Banner Management
 *
 * @vendor Contus
 *
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\KidsBanner;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Base\Repositories\UploadRepository;

class KidsBannerRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the Banner object
     *
     * @var object
     */
    protected $_banner;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\Banner $bannerContent
     */
    public function __construct(KidsBanner $bannerContent, Video $video) {
        parent::__construct ();
        $this->_banner = $bannerContent;
       
        $this->video = new Video ();
        $this->uploadRepository = new UploadRepository();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
    }
    /**
     * Store a newly created banners content or update the banner content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateBannerContents($id = null) { 
        if (! empty ( $id )) {
            $bannerContent = $this->_banner->find ( $id ); 
        }
        else {
            $bannerContent = new Banner(); 
        }

        if (! is_object ( $bannerContent )) {
            return false;
        }

        
        $this->setRules ( [ 
            'video' => 'required',
            'is_active' => 'filled|boolean',
            'banner_image' => 'required'
        ] );

        $this->_validate ();
     
        if($this->request->has('video')) {
            $bannerContent->video_id= $this->request->video; 
        }
        $bannerContent->title= $this->request->title; 
        $bannerContent->url = $this->request->imageUrl; 
        $bannerContent->type = $this->request->type; 
        $bannerContent->category_title = $this->request->category;
        if ($this->request->banner_image && $this->request->is_image_updated == 1) {
            $this->deleteBannerImage($id);
            $fileName =  $bannerContent->getImageBaseName($this->request->banner_image);
            $folderName = config("contus.base.image.banner_image.s3_location");
            $localStoragePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.banner_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName,$folderName,$localStoragePath);
            $s3BucketImgURL = $folderName.$s3BucketImgFilename;
            $bannerContent->banner_image =  $s3BucketImgURL;
        }
         
        return ($bannerContent->save ()) ? 1 : 0;
        
     
    }
    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deleteBannerImage($id){
        /**
         * Check if category id exists.
         */
         if (!empty ($id)) {
            $banner =  $this->_banner->findorfail($id);
            $bannerImage = $banner->banner_image;
            if(!empty($bannerImage)){
                $URL = $banner->getImageBaseName($banner->banner_image);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.banner_image.s3_location").$URL;
                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.banner_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath,$URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $banner->banner_image = '';
                $banner->save();
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
     }
    /**
     * Get banners contents like(images or video)
     *
     * @param int $id
     * @return object
     */
    public function getBanners() {
        return $this->_banner->paginate ( 10 )->toArray ();
    }

    /**
     * This function used to get the recent banners list
     */
    public function getBannerlists() {
        return $this->_banner->orderBy ( 'id', 'DESC' )->take ( 8 )->get ();
    }
    /**
     * Delete one Banner using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_banner->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the home banner image
     */
    public function getBannerImage() {
     return $this->_banner->where ( 'is_active', 1 )->select ( 'banner_image','video_image','type','category_title','title','url' )->first ();
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
       
     return $this->setGridModel ( $this->_banner );
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($bannerBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
      
        $bannerBuilder->with(['videos' => function ($query) {
            $query->selectRaw('videos.id, videos.title, videos.slug, videos.is_live,videos.is_archived');
        }]);
        return $bannerBuilder->selectRaw('banners.*, banners.id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderStatic
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($bannerStatic) {
        $searchbannerRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */
        foreach ( $searchbannerRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $bannerStatic = $bannerStatic->where ( $key, 'like', "%$value%" );
        }

        return $bannerStatic;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ 
            [ 'name' => trans ( 'cms::testimonial.name' ),'value' => 'name','sort' => false ],
            [ 'name' => trans ( 'cms::testimonial.video_type' ),'value' => 'Video Type','sort' => false ],
            [ 'name' => trans ( 'cms::staticcontent.created_at' ),'value' => '','sort' => false ],
            [ 'name' => trans ( 'cms::smstemplate.action' ),'value' => '','sort' => false ] 
        ] ];
    }

    public function getSearachVideo() {

        $this->setRules(['search' => 'required', 'order'=>'sometimes|in:title', 'sort'=>'sometimes|in:asc,desc']);
        $this->validate($this->request, $this->getRules());

        $searchKey = $this->request->search;
        $bannerVideos = Banner::where('is_active',1)->pluck('video_id');
        
        $video = $this->video->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->where(function($query) use ($searchKey) {
            $query->orwhere('slug', 'like', '%'.$searchKey.'%')->orwhere('title', 'like', '%'.$searchKey.'%');
        })->whereNotIn('id', $bannerVideos);

        $fields = 'videos.id, videos.title, videos.slug';

        $video->selectRaw($fields)->groupBy('videos.id');

        $inputArray = $this->request->all();
        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
        $video->orderBy($inputArray['order'], $inputArray['sort']);
        }
        else {
        $video->orderBy('id', 'desc');
        }

        return $video->paginate(config('access.perpage'));
    }
}