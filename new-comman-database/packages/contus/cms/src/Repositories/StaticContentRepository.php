<?php

/**
 * Static Content Repository
 *
 * To manage the functionalities related to the Static Content Controller
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

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository as BaseRepository;
use Contus\Cms\Models\Category;
use Contus\Cms\Models\NotificationUser;
use Contus\Cms\Models\Setting;
use Contus\Cms\Models\StaticPages;

class StaticContentRepository extends BaseRepository
{

    // use CategoryTrait;

    /**
     * Class property to hold the key which hold the static content object
     *
     * @var object
     */
    protected $_staticContent;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\StaticPages $staticContent
     */
    public function __construct(StaticPages $staticContent)
    {
        parent::__construct();
        $this->_staticContent = $staticContent;
        $this->setRules(['title' => 'sometimes|required', 'is_active' => 'sometimes|required|boolean', 'content' => 'sometimes|required']);
    }
    /**
     * Store a newly created static content or update the static content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateStaticContents($id = null)
    {
        if (!empty($id)) {
            $contactUs = $this->_staticContent->find($id);
            if (!is_object($contactUs)) {
                return false;
            }
            $this->setRules(['title' => 'sometimes|required', 'is_active' => 'sometimes|required|boolean', 'content' => 'sometimes|required']);
            $contactUs->updated_at = NOW();
            $contactUs->updator_id = $this->authUser->id;
        } else {
            $this->setRules(['title' => 'required|max:255', 'content' => 'required']);
            $contactUs = new StaticPages();
            $contactUs->is_active = 1;
            $contactUs->creator_id = $this->authUser->id;
        }
        $this->_validate();
        $contactUs->fill($this->request->except('_token'));
        return ($contactUs->save()) ? 1 : 0;
    }

    /**
     * Get one static content using id
     *
     * @param int $id
     * @return object
     */
    public function getStaticContent($id)
    {
        return $this->_staticContent->find($id);
    }

    /**
     * fetches one Static content using slug
     *
     * @param int $subscriptionSlug
     * @return object
     */
    public function getStaticcontentSlug($subscriptionSlug)
    {
        return $this->_staticContent->where('slug', $subscriptionSlug)->where('is_active', 1)->select('id', 'title', 'slug', 'content', 'is_active')->first();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAlltheStaticContents()
    {
        return $this->_staticContent->paginate(10)->toArray();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAllStaticContents()
    {
        return $this->_staticContent->paginate(10)->toArray();
    }
    /**
     * Delete one static content using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id)
    {
        $data = $this->_staticContent->find($id);
        if ($data) {
            $data->delete();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_staticContent);
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($staticContentBuilder)
    {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $staticContentBuilder->where('id', $this->authUser->id)->orWhere('parent_id', $this->authUser->id);
        }

        return $staticContentBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderStatics)
    {
        $searchstaticcontentRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ($searchstaticcontentRecordUsers as $key => $value) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderStatics = $builderStatics->where($key, 'like', "%$value%");
        }

        return $builderStatics;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings()
    {
        return [StringLiterals::GRIDHEADING => [['name' => trans('cms::staticcontent.title'), StringLiterals::VALUE => 'name', 'sort' => false],
            ['name' => trans('cms::staticcontent.updated_at'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('cms::smstemplate.action'), StringLiterals::VALUE => '', 'sort' => false]]];
    }
    /**
     * Method to fetch footer content
     *
     * @return array
     */
    public function getFooterContents()
    {
        $result['static_contents'] = StaticPages::select('id', 'title', 'slug')->where('is_active', 1)->where('is_footer_menu', 1)->get();

        $result['site_link'] = Setting::whereIn('setting_name', ['fb_link', 'twitter_link', 'google_link', 'instagram_link', 'android_app_link', 'ios_app_link', 'aod_android_app_link', 'aod_ios_app_link'])->pluck('setting_value', 'setting_name');

        $ContactData = Setting::whereIn('setting_name', ['site_email_id', 'site_mobile_number', 'site_local_address', 'premium_name'])->pluck('setting_value', 'setting_name');

        $result['address'] = [
            'email' => $ContactData['site_email_id'],
            'phone' => $ContactData['site_mobile_number'],
            'address' => $ContactData['site_local_address'],
            'premium_name' => $ContactData['premium_name'],
        ];
        if (isset(authUser()->id)) {
            $notificationUser = NotificationUser::where('user_id', authUser()->id)->first();
            $result['notification_count'] = ($notificationUser) ? $notificationUser->count : 0;
        }
        return $result;
    }
    /**
     * Method to fetch the categories to be displayed as menus in the web
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMainmenuCategories()
    {
        return Category::where('parent_id', 0)->where('level', 0)->where('is_active', 1)->where('is_radio', 0)->orderBy('category_order', 'asc')->paginate(50);
    }
    public function getAudioFooterContents()
    {
        $result['address'] = [
            'email' => config()->get('settings.general-settings.site-settings.site_email_id'),
            'phone' => config()->get('settings.general-settings.site-settings.site_mobile_number'),
            'address' => config()->get('settings.general-settings.site-settings.site_local_address'),
        ];
        $result['static_contents'] = StaticPages::select('id', 'title', 'slug')->where('is_active', 1)->where('is_footer_menu', 1)->get();
        $result['site_link'] = Setting::whereIn('setting_name', ['fb_link', 'twitter_link', 'google_link', 'instagram_link', 'android_app_link', 'ios_app_link', 'aod_android_app_link', 'aod_ios_app_link'])->pluck('setting_value', 'setting_name');
        return $result;
    }
}
