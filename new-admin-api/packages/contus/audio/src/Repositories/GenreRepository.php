<?php

/**
 * Genre Repository
 *
 * To manage the functionalities related to the Audio module from Genre Controller
 *
 * @name GenreRepository
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Contus\Audio\Models\AudioGenres;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository as BaseRepository;

class GenreRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_genre;
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
     * @param Contus\Audio\Models\AudioGenres $audioGenres
     */
    public function __construct(AudioGenres $audioGenres)
    {
        parent::__construct();
        $this->_genre = $audioGenres;
        $this->setRules(['genre_name' => 'required|unique:audio_genres,genre_name']);
    }
    /**
     * Store a newly created Genres.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package audio
     * @return boolean
     */

    public function addOrUpdateGenre($id = null)
    {
        if (!empty($id)) {
            $genre = $this->_genre->find($id);
            $this->setRules(['genre_name' => 'required']);
            $genre->updator_id = \Auth::user()->id;
            $this->setRule('genre_name', 'required|unique:audio_genres,genre_name,'.$id);
        } else {
            if (empty(\Auth::user()->id)) {
                return "session_expire";
            } else {
                $genre = new AudioGenres();
                $genre->creator_id = \Auth::user()->id;
            }
        }
        $this->_validate();
        $genre->fill($this->request->except('_token'));
        $genre->order = ($this->request->has('order') && !empty($this->request->order))
        ? $this->request->order : '0';
        if ($genre->save()) {
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
     * @return Contus\Audio\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_genre);
        return $this;
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => trans('audio::genres.genre_name'), 'value' => 'genre_name', 'sort' => true],
            ['name' => trans('audio::genres.order'), 'value' => 'order', 'sort' => false],
            ['name' => trans('audio::genres.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::genres.added_on'), 'value' => 'created_at', 'sort' => false],
            ['name' => trans('audio::genres.action'), 'value' => '', 'sort' => false],
        ]];
    }

    /**
     * Function to apply filter for search of genre grid
     *
     * @param mixed $builderGenres
     * @return \Illuminate\Database\Eloquent\Builder $builderGenres The builder object of genres grid.
     */
    protected function searchFilter($builderGenres)
    {
        $searchRecordGenres = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $genre_name = $is_active = null;
        extract($searchRecordGenres);

        /**
         * Check if the name of the genre is present in the genre search.
         * If yes, then use it in filter.
         */
        if ($genre_name) {
            $builderGenres = $builderGenres->where('genre_name', 'like', '%' . $genre_name . '%');
        }

        /**
         * Check if the status of the genre is present in the genre search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderGenres = $builderGenres->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderGenres;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        return $builder->selectRaw('audio_genres.*,audio_genres.id as formatted_created_date');
    }

}
