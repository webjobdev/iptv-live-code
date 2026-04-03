<?php

/**
 * Groups Controller
 *
 * To manage the Exam Groups such as create, edit and delete
 *
 * @name Groups Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\GenreRepository;
use Contus\Video\Repositories\CollectionRepository;
use Contus\User\Models\SiteLanguage;
use Contus\Base\Helpers\StringLiterals;

class GenreController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $GroupRepository
     */
    public function __construct(GenreRepository $groupRepository,CollectionRepository $category,GenreRepository $grouprepositary) {
      
        parent::__construct ();
        $this->repository = $groupRepository;
        $this->collection = $category;
        $this->group = $grouprepositary;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

     /**
     * Function to assign videos to Group
     *
     * @return \Contus\Base\response
     */
    public function postAdd() {
        $save = $this->repository->addOrUpdateGroup ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::collection.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::collection.error.updated' ) );
    }
    
    /**
     * Function to eidt the groups exams
     *
     * @return \Contus\Base\response
     */
    public function postEdit($id) {
        $save = $this->repository->addOrUpdateGroup ($id);
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::collection.success.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::collection.error.updated' ) );
    }
    /**
     * Function to delete videos from Group
     *
     * @return \Contus\Base\response
     */
    public function postDelete() {
        $save = $this->repository->deletePlaylistVideos ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.removed' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.removederror' ) );
    }
    
    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $subcategory = $this->collection->getAllCollectionName();
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'locale' => trans ( 'validation' ),'isActive' => [ 'In-active','Active' ],
            'category'=>$subcategory,
            StringLiterals::RULES => $this->repository->getRules (),
            'language' => SiteLanguage::where('is_active',1)->get()->toArray(), ] ] );   
    }

    /**
     * Get Group related exams videos
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideoCollections($id) {
        $subcategory = $this->group->getAllVideos($id);
        return $this->getSuccessJsonResponse ( [ 'message' => $subcategory ] );
    }

    public function addLanguage($id) 
    {
        try {
            $this->repository->updateGroupTranslation($id);
            return $this->getSuccessJsonResponse ( [ ],trans ( 'video::collection.success.updated' ));
        } catch (Exception $e) {
            return $this->getErrorJsonResponse ( [], trans ( 'video::collection.error.updated' ) );
        }
    }

     /**
     * Function to bulk activate or deactivate the Genre in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'activate') {

                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::collection.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::collection.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

    /**
     * Function to archive category in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postDeleteAction(){
        $result = '';
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            $isActionCompleted = $this->repository->categoryDelete($this->request->input(StringLiterals::SELECTED_CHECKBOX));
           
            if ($this->request->get('videoStatus') == 'single-video') {
                $result =  $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('videoStatus') == 'bulk-video') {
                $result =  $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
        return $result;
    }


}
