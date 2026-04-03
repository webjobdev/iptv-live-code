<?php

/**
 * StaticContent Controller
 * To manage the functionalities related to the static content gird api methods
 *
 * @vendor Contus
 *
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\staticcontentsRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Repositories\StaticContentRepository;
use Contus\User\Models\SiteLanguage;


class StaticContentController extends ApiController {
    /**
     * class property to hold the instance of staticcontentsRepository
     *
     * @var \Contus\Base\Repositories\staticcontentsRepository
     */
    public $staticContentRepository;
    /**
     * Construct method
     */
    public function __construct(StaticContentRepository $staticContentRepository) {
        parent::__construct ();
        $this->repository = $staticContentRepository;
    }

    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $data = $this->repository->getAlltheStaticContents ();
        unset ( $data->id );
        return $this->getSuccessJsonResponse(
            ['info' =>
                ['rules' => $this->repository->getRules(),]
        ],['message' => $data]); 
    }

    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaticData($id) {
        $data = $this->repository->getStaticContent ( $id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data, 'rules' => $this->repository->getRules(), 'language' => SiteLanguage::where('is_active',1)->get()->toArray() ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * Store a newly created Static content.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addOrUpdateStaticContents ()) {
            $isCreated = true;
            // $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::staticcontent.adds.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.adds.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.adds.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($staticId) {
        $isCreated = false;
        if ($this->repository->addOrUpdateStaticContents ( $staticId )) {
            $isCreated = true;
            // $this->request->session ()->flash ( 'success', trans ( 'cms::staticcontent.updates.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.updates.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.updates.error' ) );
    }

    public function addLanguage ($id) 
    {
        try {
            $this->repository->updateStaticContenTranslation($id);
            return $this->getSuccessJsonResponse ( [ ],trans ( 'cms::staticcontent.updates.success' ));
        } catch (Exception $e) {
            return $this->getErrorJsonResponse ( [ ],trans ( 'cms::staticcontent.updates.error' )  );
        }
    }

    public function postFooterMenu($staticId) {
        $isUpdated = false;
        if ($this->repository->addOrUpdateFooterMenu ( $staticId )) {
            $isUpdated = true;
        }

        return ($isUpdated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.updates.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.updates.error' ) );
    }

    public function postBulkFooterStatus(){

        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'show') {

                $isActionCompleted = $this->repository->staticFooterStatus($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'show');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('cms::staticcontent.updates.success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'hide') {
                $isActionCompleted = $this->repository->staticFooterStatus($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'hide');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('cms::staticcontent.updates.success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }
}
