<?php
/**
 * Report Top Categories Repository
 *
 * To manage the report functionalities related to top categories
 * @name       ReportTopCategoriesRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories\Reports;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Category;
use DB;

class ReportTopCategoriesRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
        $this->category = new Category();
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
       $this->setGridModel ($this->category);
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
        $builder =  $builder->leftJoin('video_categories', 'categories.id', '=', 'video_categories.category_id')
        ->leftJoin('videos', 'video_categories.video_id', '=', 'videos.id')
        ->leftJoin('categories AS c2', 'categories.parent_id', '=', 'c2.id')
        ->select(DB::raw('categories.*, COUNT(videos.id) as videos_count, sum(videos.view_count) as videos_view_count, c2.title as parent_category'))
        ->where('categories.is_deletable', 1)
        ->where('videos.is_archived', 0)
        ->groupBy('categories.title')
        ->orderBy('videos_view_count', 'desc');
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
                    [ 'name' => trans('video::report.category'),'value' => 'category','sort' => false,'class'=>false],
                    [ 'name' => trans('video::report.total_videos'),'value' => 'video_counts','sort' => false,'class'=>true],
                    ['name' => trans('video::report.views'),'value' => 'views', 'sort' => false,'class'=>true]
                  ] 
                ];
     }
}       