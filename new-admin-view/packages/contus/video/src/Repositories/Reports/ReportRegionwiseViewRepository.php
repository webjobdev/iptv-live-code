<?php
/**
 * Report Region wise View Repository
 *
 * To manage the report functionalities related to region wise video view count
 * @name       ReportRegionwiseViewRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories\Reports;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\VideoAnalytic;
use Contus\Video\Traits\ReportTrait as ReportTrait;
use DB;

class ReportRegionwiseViewRepository extends BaseRepository {
    use ReportTrait;
    public function __construct() {
        parent::__construct();
        $this->video_analytics = new VideoAnalytic();
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
       $totalRecord = $this->video_analytics->count();
       $this->setGridModel ($this->video_analytics)->setAggregate($this->regionWiseVideoViewCountAggregateQuery($totalRecord));
       return $this;
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
                    [ 'name' => trans('base::general.s_no'),'value' => 'sno','sort' => false,'class' => false],  
                    [ 'name' => trans('video::report.region_wise_grid.country'),'value' => 'country','sort' => false,'class'=>false],
                    [ 'name' => trans('video::report.total_videos'),'value' => 'video_counts','sort' => false,'class'=>true],
                    ['name' =>  trans('video::report.region_wise_grid.percentage'),'value' => 'views', 'sort' => false,'class'=>true]
                  ] 
                ];
     }
}       