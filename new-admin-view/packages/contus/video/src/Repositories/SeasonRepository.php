<?php

/**
 * Group Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Season;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\SeasonTranslation;


class SeasonRepository extends BaseRepository
{

    /**
     * Constructor method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Season $season
     */
    public function __construct(Season $season)
    {
        parent::__construct();
        $this->_season = $season;
        $this->setRules( [ StringLiterals::TITLE => 'required' ] );
    }   
    
    /**
     * function to get the season name
     *
     * @return object
     */
    public function getAllCollectionName() {
        return $this->_season->where ( 'is_active', 1 )->select ( 'id', 'title' )->get ();
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return object
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_season )->setEagerLoadingModels(['SeasonTranslation']);
        return $this;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [
            [ 'name' => trans ( 'video::season.title' ),trans ( 'video::season.value' ) => StringLiterals::TITLE,'sort' => true ],
            [ 'name' => trans ( 'video::season.status' ),trans ( 'video::season.value' ) => StringLiterals::TITLE,'sort' => false ],
            [ 'name' => trans ( 'video::season.added_on' ),trans ( 'video::season.value' ) => StringLiterals::TITLE,'sort' => false ],
            [ 'name' => trans ( 'video::season.action' ),trans ( 'video::season.value' ) => StringLiterals::TITLE,'sort' => false ]
        ] ];
    }
    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /**
         * updated the grid query by using this function and apply the video condition.
         */
        return $builder->selectRaw('seasons.*, seasons.id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of season grid
     *
     * @param mixed $builderVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builderVideos)
    {
        $searchRecordVideos = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = null;
        extract($searchRecordVideos);
        if ($title) {
            $builderVideos = $builderVideos->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $builderVideos = $builderVideos->where(StringLiterals::ISACTIVE, $is_active);
        }
        return $builderVideos;
    }

    /**
     * Funtion to add or update season details
     *
     * @vendor Contus
     *
     * @package Video
     * @param int $id
     * @return boolean
     */
    public function addOrUpdateSeason($id = null)
    {
        if (!empty ($id)) {
            $season = $this->_season->find($id);
            if (!is_object($season)) {
                return false;
            }
            $this->setRules([
                'title' => 'required|max:255|unique:seasons,title,'.$id, 
                'is_active' => 'required|boolean'
                ]);
        } else {
         $this->setRules([
             'title' => 'required|unique:seasons|max:255', 
             'is_active' => 'required|boolean'
             ]);
            $season = new Season ();
            $season->is_active = 1;
        }
        $this->_validate();
        $season->fill($this->request->except('_token'));
        $this->_season = $season;
        $season->save();
        return true;
    }

    /**
     * function to Get all the seasons
     *
     * @return array
     */
    public function getAllSeasons() {
        $lists = Season::pluck ('id', 'title')->toArray();
        return array_flip ( $lists );
    }

    public function updateSeasonTranslation ($id) {
        
        if(!empty($id)) {
            $this->setRules(['title'=>StringLiterals::REQUIRED]);
            $this->validate($this->request, $this->getRules());
            $season_translation;
            if(SeasonTranslation::where('season_id','=', $id)->where('language_id','=',$this->request->languageCode)->count() > 0) {
                $season_translation = SeasonTranslation::where('season_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $season_translation = new SeasonTranslation();
                $season_translation->season_id = $id;
                $season_translation->language_id = $this->request->languageCode;
            }
            $season_translation->title = $this->request->title;
            if($season_translation->save()) {
                $isUpdated = true;
            } else {
                return false;
            }
        }else {
            return false;
        }

    }

    /**
     * Function to activate the Category
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function categoryActivateOrDeactivate($ids, $isStatus){
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_season->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
           
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_season->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
         
            return $status;
        }
    }
}