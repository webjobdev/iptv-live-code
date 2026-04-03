<?php

/**
 * Preset Controller
 *
 * To manage the video presets.
 *
 * @name       Preset Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\PresetRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;

class PresetController extends ApiController {
	public $presetRepository;
	
	/**
	 * Constructer method which defines the objects of the classes used.
	*
	* @param object $videosRepository
	*         The object of VideoRepository class
	*/
	public function __construct(PresetRepository $presetRepository) {
	parent::__construct ();
	$this->repository = $presetRepository;
	$this->repository->setRequestType ( static::REQUEST_TYPE );
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
			'locale' => trans ( 'validation' ),
			'isActive' => [
				'In-active',
				'Active'
			],
			'numberOfActivePresets' => $this->repository->getNumberOfActivePresets (),
		]
	] );
	}
	/**
	 * Method to get presets from AWS cloud.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function getPresets(){
		$data = $this->repository->getPresetsFromCloud();
		if($data){
			return $this->getSuccessJsonResponse ([],  trans('video::presets.message.list_preset_success'));
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
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::presets.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->categoryActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::presets.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }
}
