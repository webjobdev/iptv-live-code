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

namespace Contus\User\Http\Controllers\Admin;

use Contus\User\Repositories\SettingsRepository;
use Contus\User\Models\SettingCategory;
use Session;
use Contus\Base\Controller as BaseController;

class SettingsController extends BaseController
{
  /**
   * Construct method
   */
  public function __construct(SettingsRepository $settingsRepository)
  {
    parent::__construct();
    $this->_settingsRepository = $settingsRepository;
    $this->_settingsRepository->setRequestType(static::REQUEST_TYPE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex()
  {


    $errors = Session::get('errors');
    if ($errors) {

      $isSiteExternalSetting = last($errors->all());
      $siteExternalPosition = strpos($isSiteExternalSetting, 'site-external-link-settings');
      if (($siteExternalPosition != false) || ($siteExternalPosition == 0)) {
        $siteExternalPosition = true;
      }
      $isSiteSetting = $errors->all();

      $siteSettingPosition = strpos($isSiteSetting[0], 'Site-settings');
      Session::forget('setting_active_tab');

      if (($siteExternalPosition === true) && ($siteSettingPosition === true)) {
        Session::put('setting_active_tab', 1);
      } elseif (($siteExternalPosition === true) && ($siteSettingPosition === false)) {
        Session::put('setting_active_tab', 2);
      } else {
        Session::put('setting_active_tab', 1);
      }
    }

    return view('user::admin.settings.settings', [
      'settingDetails' => $this->_settingsRepository->getSettings(),
      'settingCategories' => $this->_settingsRepository->getSettingCategory(),
      'optionalFields' => $this->_settingsRepository->optionalFields
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function postUpdate()
  {
    $this->_settingsRepository->updateSettings();
    return redirect('admin/settings')->withSuccess(trans('user::settings.updated'));
  }
}
