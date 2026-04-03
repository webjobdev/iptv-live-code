<?php

/**
 * Language Repository
 *
 * To manage the functionalities related to the Languages module from Languages Controller
 *
 * @name LanguageRepository
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Repositories;

use Contus\Playlist\Models\AudioLanguageCategory;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;

class LanguageRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_language;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedLanguages = 'q';
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Audio
     * @param Contus\Playlist\Models\AudioLanguageCategory $audioLanguageCategory
     */
    public function __construct(AudioLanguageCategory $audioLanguageCategory)
    {
        parent::__construct();
        $this->_language = $audioLanguageCategory;
        $this->setRules(['language_name' => 'required|unique:audio_language_category,language_name', 'order' => 'sometimes|nullable|numeric']);
    }
    /**
     * Store a newly created Languages.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package audio
     * @return boolean
     */

    public function addOrUpdateLanguage($id = null)
    {
        if (!empty($id)) {
            $language = $this->_language->find($id);
            $this->setRule('language_name', 'required|unique:audio_language_category,language_name,'.$id);
        } else {
            if (empty($this->authUser->id)) {
                return "session_expire";
            } else {
                $language = new AudioLanguageCategory();
            }
        }
        $this->_validate();
        $language->fill($this->request->except('_token'));
        $language->order = ($this->request->has('order') && !empty($this->request->order))
                            ? $this->request->order : '0';
        if ($language->save()) {
            return true;
        }
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Audio
     * @return Contus\Playlist\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_language);
        return $this;
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => trans('audio::languages.language_name'), 'value' => 'language_name', 'sort' => true],
            ['name' => trans('audio::languages.order'), 'value' => 'order', 'sort' => false],
            ['name' => trans('audio::languages.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::languages.added_on'), 'value' => 'created_at', 'sort' => false],
            ['name' => trans('audio::languages.action'), 'value' => '', 'sort' => false]
        ]];
    }

    /**
     * Function to apply filter for search of Languages grid
     *
     * @param mixed $builderLanguages
     * @return \Illuminate\Database\Eloquent\Builder $builderLanguages The builder object of Languages grid.
     */
    protected function searchFilter($builderLanguages)
    {

        $searchRecordLanguages = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $language_name = $is_active = null;
        extract($searchRecordLanguages);

        /**
         * Check if the name of the langauge is present in the language search.
         * If yes, then use it in filter.
         */
        if ($language_name) {
            $builderLanguages = $builderLanguages->where('language_name', 'like', '%' . $language_name . '%');
        }

        /**
         * Check if the status of the language is present in the language search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderLanguages = $builderLanguages->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderLanguages;
    }


    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        return $builder->selectRaw('audio_language_category.*,audio_language_category.id as formatted_created_date');
    }

}
