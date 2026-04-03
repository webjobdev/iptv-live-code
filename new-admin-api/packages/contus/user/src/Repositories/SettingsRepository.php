<?php

/**
 * Settings Repository
 *
 * To manage the functionalities related to the settings module
 * @name       SettingsRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\User\Repositories;

use Contus\User\Models\SettingCategory;
use Contus\User\Models\Setting;
use Contus\User\Contracts\ISettingsRepository;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Support\Facades\Cache;

class SettingsRepository extends BaseRepository implements ISettingsRepository
{
    /**
     * Class property to hold the key which hold the settings object
     *
     * @var object
     */
    protected $_settings;

    /**
     * Class property to hold the key which hold the settings category object
     *
     * @var object
     */
    protected $_settingCategory;

    /**
     * Construct method
     */
    public function __construct(Setting $setting, SettingCategory $settingCategory)
    {
        parent::__construct();
        $this->_settings = $setting;
        $this->_settingCategory = $settingCategory;
        $this->optionalFields = [
            '_token',
            'activeTab',
            'fb_link',
            'twitter_link',
            'google_link',
            'instagram_link',
            'android_app_link',
            'ios_app_link',
            'aod_ios_app_link',
            'aod_android_app_link',
        ];
        $this->setRules([
            'site_name'          =>  'required',
            // 'premium_name'       =>  'required',
            'page_title'         =>  'required',
            'page_keywords'      =>  'required',
            'page_description'   =>  'required',
            'site_email_id'      =>  'required|email',
            // 'site_mobile_number' =>  'required|numeric|digits_between:6,16',
            'site_local_address' =>  'required',
            'video_view_count'   =>  'required|digits:1|numeric|not_in:0',
            'fb_link'  =>  'required',
            'twitter_link'  =>  'required',
            'google_link'  =>  'required',
            'instagram_link'  =>  'required',
            'android_app_link'  =>  'required',
            'ios_app_link'  =>  'required',
            'aod_ios_app_link'  =>  'required',
            'aod_android_app_link'  =>  'required',
        ]);
        $this->setMessages('video_view_count.not_in', "video view count is invalid");
    }

    /**
     * Fetch settings to display in admin block.
     *
     * @return response
     */
    public function getSettings()
    {
        return $this->_settingCategory->with([
            'category',
            'category.settings'
        ])->where('parent_id', NULL)->get();
    }

    /**
     * Fetch setting categories to display in admin block.
     *
     * @return response
     */
    public function getSettingCategory()
    {
        return $this->_settingCategory->where('parent_id', NULL)->get();
    }

    /**
     * Update the settings based on the inputs
     *
     * @return response
     */
    public function updateSettings()
    {
        $this->validate(app()->make('request'), $this->getRules());
        // Remove favicon and log from except method if included in settings
        foreach ($this->request->except('_token', 'activeTab', 'user_id', 'favicon', 'logo') as $key => $value) {
            $setting = $this->_settings->where('setting_name', $key)->first();
            if (isset($setting) && count($setting->toArray()) > 0) {
                // To be used when image upload fields are added in settings
                if ($setting->type == 'image') {
                    $settingCategory = $this->_settingCategory->where('slug', 'sitecategory')->first();
                    $fileExtension = $value->getClientOriginalExtension();
                    $this->__imageUpload($setting, $settingCategory, $fileExtension);
                    $settingValue = explode('.', $setting->setting_value)[0] . '.' . $fileExtension;
                }
                $setting->setting_value = ($setting->type == 'image') ? $settingValue : $value;
                $setting->save();
            }
        }
        $this->generateSettingsCache();
        return true;
    }

    /**
     * To generate cache file after updating the setting records.
     *
     * Cache file path configured in config file. Once the setting data updated the JSON file will be generated.
     *
     * @return response
     */
    public function generateSettingsCache()
    {
        $settingDetails = $this->getSettings();
        $result = [];
        foreach ($settingDetails as $settingDetail) {
            foreach ($settingDetail['category'] as $category) {
                foreach ($category['settings'] as $setting) {
                    $result[$settingDetail->slug][$category->slug][$setting->setting_name] = $setting->setting_value;
                }
            }
        }
        Cache::forever('settings_caches', json_encode($result));
    }

    /**
     * To generate cache file for validation rule.
     *
     * All the validation rule will be generated as JSON file .
     *
     * @return response
     */
    public function generateValidationCache()
    {
        $fileSystem = app()->make('files');
        $siteTranslationPath = config('contus.user.user.translation_cache_file_path') . '/translation_en.json';

        $fileSystem->delete($siteTranslationPath);

        if (! $fileSystem->exists($siteTranslationPath)) {
            $fileSystem->put($siteTranslationPath, json_encode(trans('validation')));
        }
    }

    /**
     * Generate validation rule for settings data
     *
     * All the fileds required. Form validation rule and set that rule
     *
     * @return response
     */
    public function generateDynamicRules()
    {
        $rules = [];

        foreach ($this->request->except('_token', 'activeTab') as $key => $value) {
            if (strpos($key, 'favicon') !== false) {
                $rules[$key] = 'required|mimes:ico,png';
            } elseif (strpos($key, 'logo') !== false) {
                $rules[$key] = 'required|mimes:jpeg,png';
            } elseif (strpos($key, 'site_email_id') !== false) {
                $rules[$key] = 'required|email';
            } elseif (strpos($key, 'site_mobile_number') !== false) {
                $rules[$key] = 'required|digits:10|numeric';
            } elseif (strpos($key, 'video_view_count') !== false) {
                $rules[$key] = 'required|digits:1|numeric|not_in:0';
            } elseif (($key == 'site-external-link-settings__fb_link') || ($key == 'site-external-link-settings__twitter_link') || ($key == 'site-external-link-settings__google_link') || ($key == 'site-external-link-settings__instagram_link') || ($key == 'site-external-link-settings__android_app_link') || ($key == 'site-external-link-settings__ios_app_link') || ($key == 'site-external-link-settings__aod_ios_app_link') || ($key == 'site-external-link-settings__aod_android_app_link')) {
                if (($this->request->input($key) != '' || null)) {
                    $rules[$key] = 'sometimes|url';
                }
            } else {
                if (($key != 'site-external-link-settings__fb_link') || ($key != 'site-external-link-settings__twitter_link') || ($key != 'site-external-link-settings__google_link') || ($key != 'site-external-link-settings__instagram_link') || ($key != 'site-external-link-settings__android_app_link') || ($key != 'site-external-link-settings__ios_app_link') || ($key != 'site-external-link-settings__aod_ios_app_link') || ($key != 'site-external-link-settings__aod_android_app_link')) {
                    $rules[$key] = 'required';
                }
            }
        }

        return $this->setRules($rules);
    }

    /**
     * Method is used to upload image for all process in settings
     *
     * @param $setting, $settingCategory
     *
     * @return boolean
     */
    public function __imageUpload($setting, $settingCategory, $fileExtension)
    {
        $fieldName = $settingCategory->slug . '__' . $setting->setting_name;
        if (isset($this->request[$fieldName]) && ! empty($this->request[$fieldName])) {
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images';
            if ($this->request[$fieldName]->move($destinationPath, $setting->setting_name . "." . $fileExtension)) {
                return true;
            }
        }
    }
    /**
     * Method to fetch settings categories and field info
     * 
     * @return array;
     */
    public function getSettingsInfo()
    {
        $settingsArray = array();
        $settingsArray['setting_categories'] = $this->getSettingCategory();
        $settingsArray['setting_fields'] = $this->getSettings();
        $settingsArray['optional_fields'] = $this->optionalFields;
        return $settingsArray;
    }
}
