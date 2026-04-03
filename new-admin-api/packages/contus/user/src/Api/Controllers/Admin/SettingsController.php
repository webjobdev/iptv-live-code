<?php
/**
 * Settings Controller
 *
 * To update the Settings
 *
 * @name       Settings Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Api\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Contus\User\Repositories\SettingsRepository;

class SettingsController extends BaseController {
    /**
   * Construct method
   */
  public function __construct(SettingsRepository $settingsRepository) {
    parent::__construct ();
    $this->repository = $settingsRepository;
  }
  /**
   * Method to fetch settings categories and field info
   * 
   * @return array;
   */
  public function getInfo(){
    $result = $this->repository->getSettingsInfo();
    return ($result) ? $this->getSuccessJsonResponse (['data' => $result], trans('user::settings.settings_fetch_successfully'))
    : $this->getErrorJsonResponse ( [ ], trans('user::settings.settings_fetch_error'));
  }
  /**
   * Method to update settings data
   *
   * @return \Illuminate\Http\Response
   */
  public function postUpdate() {
    $result = $this->repository->updateSettings();
    return ($result) 
    ? $this->getSuccessJsonResponse ([], trans('user::settings.updated'))
    : $this->getErrorJsonResponse ( [], trans('user::settings.updateerror'));
  }
}