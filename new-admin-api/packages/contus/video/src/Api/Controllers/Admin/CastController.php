<?php
/**
 * Category Controller
 *
 * To manage the video categories.
 *
 * @name       Category Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\CastRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\User\Models\SiteLanguage;
use Contus\Video\Models\Category;
use Contus\Video\Models\CategoryTranslation;


class CastController extends ApiController {
  /**
   * class property to hold the instance of UploadRepository
   *
   * @var \Contus\Base\Repositories\UploadRepository
   */
  public $uploadRepository;
  /**
   * Construct method
   */
  public function __construct(CastRepository $castRepository, UploadRepository $uploadRepository) {
    parent::__construct ();
    $this->repository = $castRepository;
    $this->uploadRepository = $uploadRepository;
  }
  /**
   * get Information for create form
   * return various information request by the form
   * request will be having query param which refer to category
   *
   * @return \Illuminate\Http\Response
   */
  public function getAdd() {
    return $this->getSuccessJsonResponse ( [
        StringLiterals::RULES => $this->repository->getRules (),
    ] );
  }

  /**
   * Add the specified resource in storage.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function postAdd() {
    $addCategory = $this->repository->addOrUpdateCategory ();
    if($addCategory==="session_expire"){
     return redirect('admin/auth/login')->with('message', trans ( 'video::categories.session_expire' ));
    }else{
    $isCategoryAdd = false;
    if ($addCategory) {
      $isCategoryAdd = true;
    //   $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::cast.added' ) );
    }
    return ($isCategoryAdd) ? $this->getSuccessJsonResponse ( [
        StringLiterals::STATUS => 'success',
        StringLiterals::MESSAGE => trans ( 'video::cast.success.added' )
    ] ) : $this->getErrorJsonResponse ( [
        [
            StringLiterals::STATUS => 'error',
            StringLiterals::MESSAGE => trans ( 'video::cast.error.added' )
        ]
    ] );
    }
  }

  /**
   * get Information for create form
   * return various information request by the form
   * request will be having query param which refer to category
   *
   * @return \Illuminate\Http\Response
   */
  public function getEdit($id) {
    $getCategory = $this->repository->getCategory ( $id );
    return (is_null ( $getCategory )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'response' => $getCategory,
        StringLiterals::RULES => $this->repository->getrules ()
    ] );
  }

  /**
   * Add the specified resource in storage.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function postEdit($id) {
      
    $editCategory = $this->repository->addOrUpdateCategory ($id);

    $isCategoryEdit = false;
    if ($editCategory) {
      $isCategoryEdit = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::cast.updated' ) );
    }
    return ($isCategoryEdit) ? $this->getSuccessJsonResponse ( [
        StringLiterals::STATUS => 'success',
        StringLiterals::MESSAGE => trans ( 'video::cast.success.updated' )
    ] ) : $this->getErrorJsonResponse ( [
        [
            StringLiterals::STATUS => 'error',
            StringLiterals::MESSAGE => trans ( 'video::cast.error.updated' )
        ]
    ] );
  }
  /**
   * Upload the image for a category.
   *
   * @param string $modelIdentifier
   * @return Response
   */
  public function postCategoryImage() {
      $tempImageInfo = $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_ADS )->tempPrepare ()->tempUpload ();
      // echo "<pre>==========>".UploadRepository::MODEL_IDENTIFIER_ADS;die;

      return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [
              'info' => array_shift ( $tempImageInfo )
              ] );
  }
  /**
   * Controller function to delete image of a category.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function postDeleteCategoryImage($id) {
      $isImageDeleted = false;

      try {
          /**
           * Call the deleteCategoryImage repository method to delete image of a category.
           */
          if ($this->repository->deleteCategoryImage ( $id )) {
              $isImageDeleted = true;
              $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::categories.message.image-delete-success' ) );
          }
      } catch ( Exception $e ) {
          /**
           * Handle the error exception when the category of the image does not exist.
           */
          $this->request->session ()->flash ( StringLiterals::ERROR, trans ( 'video::categories.category_not_exist' ) );
          $isImageDeleted = true;
      }
      /**
       * If the image of the category is deleted successfully, return the success response.
       * If the image of the category is not deleted successfully, return the failure resposne.
       */
      return ($isImageDeleted) ? $this->getSuccessJsonResponse ( [
              StringLiterals::MESSAGE => trans ( 'video::categories.message.image-delete-success' )
              ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::categories.message.image-delete-error' ) );
  }
 

  /**
   * Controller function to get the category related videos.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getVideoAds($id) {
    $getVideoCategories = $this->repository->getVideoCategories ( $id );
    return (is_null ( $getVideoCategories )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'videoCategories' => $getVideoCategories,
    ] );
  }

  

  /**
   * get Information for create form
   * return various information request by the form
   *
   * @return \Illuminate\Http\Response
   */
  public function getInfo() {
      return $this->getSuccessJsonResponse ( [
              'info' => [
                      StringLiterals::RULES => $this->repository->getRules (),
                      'locale' => trans ( 'validation' ),
                      'isActive' => [
                          'In-active',
                          'Active'
                      ],
                      'language' => SiteLanguage::where('is_active',1)->get()->toArray(),
              ]
      ] );
  }
  /**
   * Function to get parent categories, active categories and number of active presets.
   *
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getUpdatedDetails() {
      return $this->getSuccessJsonResponse ( [
              'allCategoriesHTML' => $this->repository->getAllCategoryList(),
      ] );
  }

  

    public function addLanguage($id) 
    {
        try {
            $this->repository->updateCategoriesTranslation($id);
            return $this->getSuccessJsonResponse ( [ ],trans ( 'video::ads.success.updated' ));
        } catch (Exception $e) {
            return $this->getErrorJsonResponse ( [ ],trans ( 'video::ads.error.updated' )  );
        }
    }

    /**
     * Function to bulk activate or deactivate the category in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'activate') {

                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::ads.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::ads.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

        /**
   * Upload the image for a category.
   *
   * @param string $modelIdentifier
   * @return Response
   */
  public function postCastImage() {
    $tempImageInfo = $this->uploadRepository->setModelIdentifier ( uploadRepository::MODEL_IDENTIFIER_CAST_IMAGE )->tempPrepare ()->tempUpload ();

    return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [
            'info' => array_shift ( $tempImageInfo )
            ] );
  }



    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function searchCast() {
        $fetch ['search_videos'] = $this->repository->getSearchCast();
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }
    /**
     * Method to fetch videos for adding into cast (X-RAY)
     * @return \Illuminate\Http\Response
    */
    public function searchVideos(){
        $data = array();
        $data['search_videos'] = $this->repository->fetchVideos();
        return ($data) ? $this->getSuccessJsonResponse(['response' =>$data], trans('video::cast.videos_fetch_success')) 
        : $this->getErrorJsonResponse([],trans('video::cast.videos_fetch_error'));
    }
}
