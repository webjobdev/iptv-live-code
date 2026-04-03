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
use Contus\Video\Repositories\SeasonRepository;
use Contus\User\Models\SiteLanguage;
use Contus\Base\Helpers\StringLiterals;

class SeasonController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $GroupRepository
     */
    public function __construct(SeasonRepository $seasonRepository) {
      
        parent::__construct ();
        $this->repository = $seasonRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $subcategory = $this->repository->getAllCollectionName();
        return $this->getSuccessJsonResponse ( [ 
            'info' => [ 'locale' => trans ( 'validation' ),
            'isActive' => [ 'In-active','Active' ],
            'category'=>$subcategory,
            StringLiterals::RULES => $this->repository->getRules (),
            'language' => SiteLanguage::where('is_active',1)->get()->toArray(),

            ] 
            ] );   
    }
    
    /**
     * Function to assign videos to season
     *
     * @return \Contus\Base\response
     */
    public function postAdd() {
        $save = $this->repository->addOrUpdateSeason ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::season.created.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::season.error.added' ) );
    }
    
    /**
     * Function to eidt the season
     *
     * @return \Contus\Base\response
     */
    public function postEdit($id) {
        $save = $this->repository->addOrUpdateSeason ($id);
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::season.created.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::season.error.updated' ) );
    }

    public function addLanguage($id) 
    {
       
        $isUpdated = false;
        
        try {
            $this->repository->updateSeasonTranslation($id);
            $isUpdated = true;
            return $this->getSuccessJsonResponse ( [ ],trans ( 'video::season.translation.updated' ));
        } catch (Exception $e) {
            $isUpdated = true;
            return $this->getErrorJsonResponse ( [], trans ( 'video::season.translation.error' ) );
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
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::season.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::season.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

    public function postDeleteSeasonImage($id) {
        $isImageDeleted = false;
    
        try {
            /**
             * Call the deleteCategoryImage repository method to delete image of a category.
             */
            if ($this->repository->deleteSeasonImage ( $id )) {
                $isImageDeleted = true;
                // $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::categories.message.image-delete-success' ) );
            }
        } catch ( Exception $e ) {
            /**
             * Handle the error exception when the category of the image does not exist.
             */
            // $this->request->session ()->flash ( StringLiterals::ERROR, trans ( 'video::categories.category_not_exist' ) );
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
}
