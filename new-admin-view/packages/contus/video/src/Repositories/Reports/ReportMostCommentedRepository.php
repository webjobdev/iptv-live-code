<?php
/**
 * Report Most Commented Repository
 *
 * To manage the report functionalities related to most commented videos
 * @name       ReportMostCommentedRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories\Reports;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Comment;

class ReportMostCommentedRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
        $this->comments = new Comment();
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Video
     * @return Contus\Video\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ($this->comments);
       return $this;
    }
    /**
     * update grid records collection query
     *
     * @vendor Contus
     *
     * @package Base
     * @param mixed $builder 
     * @return mixed
     */
    public function updateGridQuery($builder){
        $builder = $builder->with(['video','video.categories'])->whereHas('video', function($query) {
            $query->where('is_active', 1)->where('is_archived', 0)->where('job_status', 'Complete');
        });

        $builder =   $builder->groupBy('video_id');
        $builder->getQuery()->aggregate = [ 'function' => 'count', 'columns' => [ 'count' ] ];
        $builder->orderBy('aggregate',-1);
       return $builder;
    }
     /**
     * Prepare the Grid Headings for poster grid
     * set the grid Headings to the resource be loaded
     *
     * @vendor Contus
     *
     * @package Video
     * @return Contus\Video\Repositories\BaseRepository
     */
    public function getGridHeadings() {
        return [ 'heading' => [
                    [ 'name' => trans('base::general.s_no'),'value' => 'sno','sort' => false,'class' => true], 
                    [ 'name' => trans('video::report.video_name'),'value' => 'title','sort' => false,'class' => false],
                    [ 'name' => trans('video::report.category'),'value' => 'category','sort' => false,'class' => false],
                    ['name' => trans('video::report.comments'),'value' => 'comments', 'sort' => false,'class' => true]
                  ] 
                ];
     }
}       