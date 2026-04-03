<?php
/**
 * Report Most Viewed videos Repository
 *
 * To manage the report functionalities related to most favourite videos
 * @name       ReportMostViewedRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories\Reports;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use DB;

class ReportMostViewedRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
        $this->videos = new Video();
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
       $this->setGridModel ($this->videos)->setEagerLoadingModels(['categories']);
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
       $builder = $builder->where('is_active', 1)->where('job_status', 'Complete')->where('is_archived', 0)->orderBy ( 'view_count', 'desc' );
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
                    ['name' => trans('video::report.views'),'value' => 'views', 'sort' => false,'class' => true]
                  ] 
                ];
     }
}       