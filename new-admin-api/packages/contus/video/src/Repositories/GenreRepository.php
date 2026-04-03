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
use Contus\Video\Models\Group;
use Contus\Video\Models\Video;
use Contus\Customer\Models\MypreferencesVideo;
use Illuminate\Support\Facades\DB;
use Contus\Video\Models\Collection;
use Illuminate\Support\Facades\Cache;
use Contus\Video\Models\GroupTranslation;
use Contus\Base\Helpers\StringLiterals;

class GenreRepository extends BaseRepository
{

    /**
     * Constructor method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Playlist $play
     */
    public function __construct(Group $group, MypreferencesVideo $mypreference)
    {
        parent::__construct();
        $this->_group = $group;
        $this->_preference = $mypreference;
        $this->setRules( [ 'name' => 'required' ] );
    }

    /**
     * Funtion to add or update playlist details
     *
     * @vendor Contus
     *
     * @package Video
     * @param int $id
     * @return boolean
     */
    public function addOrUpdateGroup($id = null)
    {
        if (!empty ($id)) {
            $group = $this->_group->find($id);
            if (!is_object($group)) {
                return false;
            }
            $this->setRules(['name' => 'sometimes|required|max:255|unique:groups,name,'.$id.'id', 'is_active' => 'sometimes|required|boolean']);
            $group->updator_id = \Auth::user()->id;
        } else {
         $this->setRules(['name' => 'required|unique:groups', 'is_active' => 'required|boolean']);
            $group = new Group ();// 'category' => 'required',
        }
        $this->_validate();
        $group->fill($this->request->except('_token'));
        $this->_group = $group;
        $group->save();
        return true;
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('video::playlist.group_name'), 'value' => 'name', 'sort' => true], 
            ['name' => trans('video::playlist.group_order'), 'value' => '', 'sort' => false], 
            ['name' => trans('video::playlist.status'), 'value' => 'is_active', 'sort' => false], 
            ['name' => trans('video::collection.added_on'), 'value' => '', 'sort' => false], 
            ['name' => trans('video::collection.action'), 'value' => '', 'sort' => false]]];
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_group)->setEagerLoadingModels(['group_videos' => function ($query) {
            $query->where('is_archived', 0);
        },'GroupTranslation']);
        return $this;
    }

    protected function updateGridQuery($builder) {
        // return $builder->selectRaw('groups.*,groups.id as formatted_created_date');
        return $builder->selectRaw('`groups`.*,`groups`.id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of Playlists grid
     *
     * @param mixed $builderPlaylists
     * @return \Illuminate\Database\Eloquent\Builder $builderPlaylists The builder object of collections grid.
     */
    protected function searchFilter($builderPlaylists)
    {
        $searchRecordGroups = $this->request->has('searchRecord') && is_array($this->request->input('searchRecord')) ? $this->request->input('searchRecord') : [];
        $title = $is_active = null;
        extract($searchRecordGroups);
        if ($title) {
            $builderPlaylists = $builderPlaylists->where('name', 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $builderPlaylists = $builderPlaylists->where('is_active', $is_active);
        }
        return $builderPlaylists;
    }

    /**
     * Funtion to get all groups from exam id or slug
     *
     *
     * @param string|id $examId
     * @return object
     */
    public function getAllVideos($groupId)
    {
        $sgroup = $this->_group->whereIn($this->getKeySlugorId(), explode(",", $groupId))->with('exams')->where('is_active', 1)->first();
        $group = (is_object($sgroup)) ? clone $sgroup : $this->throwJsonResponse();
        if (($this->request->header('x-request-type') !== 'mobile')) {
            if (Cache::has('groupList' . $group->slug) || ($this->request->input('page') > 1)) {
                $group = Cache::rememberForever('groupList' . $group->slug, function () use ($group) {
                    if (Cache::has('cache_keys_playlist')) {
                        $previouscache = Cache::get('cache_keys_playlist');
                        if (!(strpos($previouscache, 'groupList' . $group->slug) !== false)) {
                            Cache::put('cache_keys_playlist', $previouscache . ',groupList' . $group->slug, 0);
                        }
                    } else {
                        Cache::put('cache_keys_playlist', 'groupList' . $group->slug, 0);
                    }
                    $group = $group->group_videos();
                    $group = $group->with(['categories' => function ($q) {
                        $q->addSelect('title');
                    }]);
                    $group = $group->select('videos.id', 'selected_thumb', 'slug', 'title', 'video_duration')->orderBy('video_order', 'asc')->get()->toArray();
                    return ['next_page_url' => null, 'data' => $group];
                });
            } else {
                $group = $group->group_videos()->selectRaw('videos.id');
                $group = $group->with(['categories' => function ($q) {
                    $q->addSelect('title');
                }]);
                $group = $group->orderBy('video_order', 'asc')->paginate(5)->toArray();
            }
        } else {
            $group = $group->group_videos()->leftJoin('favourite_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
            })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id');
            $group = $group->orderBy('video_order', 'asc')->paginate(10)->toArray();
        }
        $group ['group_id'] = $sgroup->toArray();
        return $group;
    }

    /**
     * Function to get all the recommended videos
     *
     * @return array
     */
    public function getRecommendedVideos($skip = '')
    {
        $exams = "";
        $exams = auth()->user()->exams()->where('is_active', 1)->pluck('collections.id')->toArray();
        if (!$exams) {
            $exams = Collection::where('is_active', 1)->pluck('id')->toArray();
            auth()->user()->exams()->attach($exams);
        } // groups
        if ($this->request->has('exam') && $this->request->has('group')) {
            $examSlug = $this->request->exam;
            $groupSlug = $this->request->group;
            $exams = Collection::where($this->getKeySlugorId(), $examSlug)->first()->groups()->where($this->getKeySlugorId(), '!=', $groupSlug)->pluck('id')->toArray();
            $video = new Video ();
            return $video->whereCustomer()->join('collections_videos as examvideos', function ($join) use ($exams) {
                $join->on('examvideos.video_id', '=', 'videos.id')->whereIn('group_id', $exams);
            })->selectRaw('videos.*')->groupBy('videos.id')->with('categories.parent_category.parent_category')->paginate(10)->toArray();
        } else {
            $exams = Group::where($this->getKeySlugorId(), '!=', $skip)->whereIn('collection_id', $exams)->where('is_active', 1)->pluck('id')->toArray();
            $video = new Video ();
            return $video->whereCustomer()->join('collections_videos as examvideos', function ($join) use ($exams) {
                $join->on('examvideos.video_id', '=', 'videos.id')->whereIn('group_id', $exams);
            })->selectRaw('videos.*')->groupBy('videos.id')->with('categories.parent_category.parent_category')->paginate(10)->toArray();
        }
    }

    public function updateGroupTranslation ($id) {
        
        if(!empty($id)) {
            $this->setRules(['name'=>StringLiterals::REQUIRED]);
            $this->validate($this->request, $this->getRules());
            $group_translation;
            if(GroupTranslation::where('group_id','=', $id)->where('language_id','=',$this->request->languageCode)->count() > 0) {
                $group_translation = GroupTranslation::where('group_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $group_translation = new GroupTranslation();
                $group_translation->group_id = $id;
                $group_translation->language_id = $this->request->languageCode;
            }
            $group_translation->name = $this->request->name;
            return $group_translation->save();
        }
    }

     /**
     * Function to activate the Genre
     *
     * @param integer|array $ids
     * The ids of the Genre which are to be activated.
     * @return boolean True if the Genre are archived successfully and false if not.
     */
    public function categoryActivateOrDeactivate($ids, $isStatus){
        /**
         * Activate/Decativate the Genre by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_group->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
           
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_group->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
         
            return $status;
        }
    }

    /**
     * Function to archive videos in the database.
     * 
     *
     * @param integer|array $ids
     * The ids of the category which are to be deleted.
     * @return boolean True if the category are archived successfully and false if not.
     */
    public function categoryDelete($ids)
    {
        /**
         * Delete the _category by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        $status = false;

        if(!empty($ids)) {
            $this->_group->whereIn('id', $ids)->update([StringLiterals::IS_ARCHIVED => 1, 'archived_on' => Carbon::now()]);
            
           
            $status = true;
        }
        return $status;
    }

}
