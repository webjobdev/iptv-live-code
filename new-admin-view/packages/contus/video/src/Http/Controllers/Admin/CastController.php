<?php

/**
 * Categories Controller
 *
 * To manage the Categories such as create, edit and delete
 *
 * @name       Categories Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Contus\Base\Repositories\UploadRepository;

class CastController extends BaseController {
 /**
  * Construct method
  */
 public function __construct(CategoryRepository $categoryRepository,UploadRepository $uploadRepository) {
  parent::__construct ();
  $this->_categoryRepository = $categoryRepository;
  $this->_categoryRepository->setRequestType ( static::REQUEST_TYPE );
  $this->uploadRepository = $uploadRepository;
 }
 
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\View
  */
 public function getIndex() {
  return view ( 'video::admin.cast.index' );
 }
 
 /**
  * get Grid template
  *
  * @return \Illuminate\Http\View
  */
 public function getGridlist() {
  return view ( 'video::admin.cast.gridView' );
 }
 /**
  * Function to get list of categories with their hierarchy.
  */
 public function getCategoryList() {
     return $this->_categoryRepository->getAllCategoryList ();
 }
 /**
 * Controller function to get the category related videos.
 *
 * @param integer $id The id of the category.
 * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
 */
  public function getVideos($id) {
    return view ( 'video::admin.cast.videos', [ 
      'id' => $id 
    ] );
  }

    /**
   * Upload the image for a category.
   *
   * @param string $modelIdentifier
   * @return Response
   */
  public function postCastImage() {
    $tempImageInfo = $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_CAST_IMAGE )->tempPrepare ()->tempUpload ();

    return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [
            'info' => array_shift ( $tempImageInfo )
            ] );
  }
  
}
