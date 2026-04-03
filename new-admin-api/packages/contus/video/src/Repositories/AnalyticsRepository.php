<?php

/**
 * Analytics Repository
 *
 * To manage the functionalities related to videos
 * @name       DashboardRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Option;
use Contus\Video\Models\Category;
use Contus\Base\Helpers\StringLiterals;
use DB;
use Contus\Video\Models\AwsMonthWiseBilling;
use Contus\Customer\Models\Customer;
use Contus\Customer\Models\Subscribers;
use Carbon\Carbon;
use Contus\Payment\Models\PaymentTransactions;
use Faker\Provider\Text;
use Contus\Video\Models\VideoAnalytic;
use Contus\Video\Traits\ReportTrait as ReportTrait;
use Contus\Video\Traits\DashboardTrait as DashboardTrait;
use Analytics;
use Spatie\Analytics\Period;


class AnalyticsRepository extends BaseRepository
{
    /**
     * class property to hold the instance of Video Model
     *
     * @var \Contus\Video\Models\Video
     */
    public $video;
    /**
     * class property to hold the instance of VideoPreset Model
     *
     * @var \Contus\Video\Models\VideoPreset
     */
    public $videoPreset;
    /**
     * class property to hold the instance of Option Model
     *
     * @var \Contus\Video\Models\Option
     */
    public $option;
    /**
     * class property to hold the instance of Category Model
     *
     * @var \Contus\Video\Models\Category
     */
    public $category;
    public $customer;
    public $comment;
    public $subscribers;

    /**
     * Constructor method of the class in which instances of the model files are fetched.
     *
     * @param Video $video object Instance of Video Model class.
     * @param VideoPreset $videoPreset object Instance of VideoPreset Model class.
     * @param Option $option object Instance of Option Model class.
     * @param Category $category object Instance of Category Model class.
     */
    public function __construct(Video $video, VideoPreset $videoPreset, Option $option, Category $category, Customer $customer, Comment $comment, subscribers $subscribers)
    {
        parent::__construct();

        /**
         * Set other class objects to properties of this class.
         */
        $this->video = $video;
        $this->videoPreset = $videoPreset;
        $this->option = $option;
        $this->category = $category;
        $this->customer = $customer;
        $this->comment = $comment;
        $this->subscribers = $subscribers;
        $this->video_analytics = new VideoAnalytic;
    }


    /**
     * Function to get top browsers list for google analytics
     *
     * @return array Top browsers fetched from the google analytics.
     */

    public function fetchTopBrowsers($type = '3'){
        $result = [];
        switch ($type) {
            case '4':
                $result = Analytics::fetchTopBrowsers(Period::days(7));
                break;
            case '3':
                $result = Analytics::fetchTopBrowsers(Period::months(1));
                break;
            case '2':
                $result = Analytics::fetchTopBrowsers(Period::years(1));
                break;
            case '1':
                $result = Analytics::fetchTopBrowsers(Period::years(2));
                break;
            default:              
                $result = Analytics::fetchTopBrowsers(Period::months(1));
                break;
        }
        return $result;
    }

    /**
     * Function to get Total visitors and Pageviews list for google analytics
     *
     * @return array  Total visitors and Pageviews fetched from the google analytics.
     */
    public function fetchTotalVisitorsAndPageViews($type = '3'){
        $result = [];
        switch ($type) {
            case '4':
                $result = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
                break;
            case '3':
                $result = Analytics::fetchTotalVisitorsAndPageViews(Period::days(15));
                break;
            case '2':
                $result = Analytics::fetchTotalVisitorsAndPageViews(Period::years(1));
                break;
            case '1':
                $result = Analytics::fetchTotalVisitorsAndPageViews(Period::years(2));
                break;
            default:              
                $result = Analytics::fetchTotalVisitorsAndPageViews(Period::months(1));
                break;
        }
        return $result;
    }

    /**
     * Function to get User types from google analytics
     *
     * @return array User types fetched from the google analytics.
     */

    public function fetchUserTypes($type = '3'){
        $result = [];
        switch ($type) {
            case '4':
                $result = Analytics::fetchUserTypes(Period::days(7));
                break;
            case '3':
                $result = Analytics::fetchUserTypes(Period::months(1));
                break;
            case '2':
                $result = Analytics::fetchUserTypes(Period::years(1));
                break;
            case '1':
                $result = Analytics::fetchUserTypes(Period::years(2));
                break;
            default:              
                $result = Analytics::fetchUserTypes(Period::months(1));
                break;
        }
        return $result;
    }


  /**
   * Method to get the chart data filter
   * 
   * @return Json
   */
  public function chartDateFilter(){
    $data = array();
    $chartDateFilterArr = array(
        trans('base::general.chart_date_filter.all') => "1", 
        trans('base::general.chart_date_filter.last_year') => "2", 
        trans('base::general.chart_date_filter.last_month') => "3", 
        trans('base::general.chart_date_filter.last_7_days') => "4"
    );
    foreach($chartDateFilterArr as $label => $value) {
        $data[] = ['id'=> (int) $value, 'name' => $label];
    }
   return $data;
  }
  
}
