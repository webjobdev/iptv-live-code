<?php

/**
 * Dashboard Repository
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


class DashboardRepository extends BaseRepository
{
    use ReportTrait, DashboardTrait;
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
     * Function to get top categories(categories with most number of video views) from the database.
     *
     * @see \Contus\Video\Contracts\IDashboardRepository::getTopCategories()
     * @return array Top categories fetched from the database.
     */
    public function getTopCategories()
    {
        return $this->category->leftJoin('video_categories', 'categories.id', '=', 'video_categories.category_id')
            ->leftJoin('videos', 'video_categories.video_id', '=', 'videos.id')
            ->leftJoin('categories AS c2', 'categories.parent_id', '=', 'c2.id')
            ->select(DB::raw('categories.*, COUNT(videos.id) as videos_count, sum(videos.view_count) as videos_view_count, c2.title as parent_category'))
            ->where('categories.is_deletable', 1)
            ->where('videos.is_archived', 0)
            ->groupBy('categories.title')
            ->orderBy('videos_view_count', 'desc')->paginate(5)->toArray();
    }

    /**
     * Function to get latest videos
     *
     * @return array Latest videos fetched from the database.
     */
    public function getLatestVideos()
    {
        return $this->video->with('categories')->where('is_active', 1)->where('job_status', 'Complete')->where('is_archived', 0)->orderBy('view_count', 'desc')->paginate(5)->toArray();
    }


    /**
     * Function to get most favourite videos from the database.
     *
     * @return array Most favourite videos fetched from the database.
     */
    public function getMostFavouriteVideos()
    {
        return $this->video->has('favouriteVideo')->withCount('favourite')->where('is_active', 1)->where('is_archived', 0)->where('job_status', 'Complete')->orderBy('favourite_count','Desc')->paginate(5);  
    }

    /**
     * Function to get most commented videos from the database.
     *
     * @return array Most commented videos fetched from the database.
     */
    public function getMostCommentedVideos()
    {
        $comments = Comment::with('video.categories')->whereHas('video', function($query) {
            $query->where('is_active', 1)->where('is_archived', 0)->where('job_status', 'Complete');
        })->groupBy('video_id');
        $comments->getQuery()->aggregate = ['function' => 'count', 'columns' => ['count']];
        return $comments->orderBy('aggregate', -1)->paginate(5)->toArray();
    }
    /**
     * Method to get region wise analytics
     * 
     * @return array
     */
    public function getRegionWiseAnalytics($dateType = 4)
    {
        $date = '';
        $aggregate = [];
        $totalRecord = $totalResult = 0;
        $totalRecord = $this->video_analytics->count();
       
        /** Call to method to the aggregate query */
        $aggregateQuery = $this->regionWiseVideoViewCountAggregateQuery($totalRecord);
        $aggregate = $aggregateQuery;
        $date = $this->dateFilter($dateType);
        if ($date) {
            /** Convert Date format to MongoDB supported Date */
            $date = $this->mongoDBDateConversion($date);
            /** Aggreagate Query to fetch records based on the date*/
            $matchArr['created_at'] = ['$gte' => $date];
            /** performed array shift to make the $match MongoDB aggregate to be first */
            array_unshift($aggregate, ['$match' => $matchArr]);
        }
        array_push($aggregate, ['$skip' => 0]);
        array_push($aggregate, ['$limit' => 5]);
        /** Perform aggregation on MongoDB query */
        $regionwiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate) {
            return $collection->aggregate($aggregate, ["allowDiskUse" => true]);
        });
        $regionwiseAnalyticsArr = $regionwiseAnalytics->toArray();
        $totalResult = $this->getTotalRecords($this->video_analytics, $aggregateQuery);
        $totalResult = (!empty($regionwiseAnalyticsArr)) ? $totalResult : 0;
        /** Calling the standard pagination method as the raw method returns collection, where paginate method is not available */
        return new \Illuminate\Pagination\LengthAwarePaginator($regionwiseAnalytics, $totalResult, 5, 1, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
    }
    /**
     * Method to get platform wise analytics report
     * @param string $platformType
     * 
     * @return array
     */
    public function getPlatformWiseAnalytics($platformType, $dateType = 4)
    {
        
        $date = '';
        $matchArr = array();
        $aggregate = $this->platformWiseVideoViewCountAggregateQuery($platformType);
        $date = $this->dateFilter($dateType);
        if ($date) {
            /** Convert Date format to MongoDB supported Date */
            $date = $this->mongoDBDateConversion($date);
            /** Aggreagate Query to fetch records based on the date*/
            $matchArr['created_at'] = ['$gte' => $date];
            /** performed array shift to make the $match MongoDB aggregate to be first */
        }
        $matchArr['platform'] = $platformType;
        array_unshift($aggregate, ['$match' => $matchArr]);
        $platformWiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate) {
            return $collection->aggregate($aggregate, ["allowDiskUse" => true]);
        });
        return $platformWiseAnalytics->toArray();
    }
    /**
     * Function to get Revenue From Subscription
     */
    public function getRevenue()
    {

        $revenueData = [];
        $types = $this->request->type?$this->request->type:3;
        switch ($types) {
            case '4':
                $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(7))->where('status', 'Paid')->sum('amount');
                $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(7))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
                if($data){
                    $data = $data->Format('d M Y');                }
              
                $revenueData['revenueSince']= $data?$data:  Carbon::now()->subDays(7)->Format('d M Y');
                break;
            case '3':
                $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(30))->where('status', 'Paid')->sum('amount');
                $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(30))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
                if($data){
                    $data = $data->Format('d M Y');                }
              
                $revenueData['revenueSince']= $data?$data:  Carbon::now()->subDays(30)->Format('d M Y');
                break;
            case '2':
                $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->sum('amount');
                $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
                if($data){
                    $data = $data->Format('d M Y');                }
              
                $revenueData['revenueSince']= $data?$data: Carbon::now()->subDays(365)->Format('d M Y');
                break;
            case '1':
                $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->sum('amount');
                $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
                if($data){
                    $data = $data->Format('d M Y');                }
              
                $revenueData['revenueSince']= $data?$data: Carbon::now()->subDays(365)->Format('d M Y');
                break;
            default:              
                $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->sum('amount');
                $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
                if($data){
                    $data = $data->Format('d M Y');                }
              
                $revenueData['revenueSince']= $data?$data: Carbon::now()->subDays(365)->Format('d M Y');
        }
      

        return $revenueData;
    }

    /**
     * Function to get subscribed user count
     *
     * @return int
     */
    public function getSubscribedUserCount()
    {
        $activeSubscriber = '';
        $types = $this->request->type?$this->request->type:3;
        switch ($types) {
            case '4':
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(7))->where('is_active', 1)->count();
                break;
            case '3':
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(30))->where('is_active', 1)->count();
                break;
            case '2':
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
                break;
            case '1':
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
                break;
            default:
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
        }
      
        return $activeSubscriber ? $activeSubscriber : 0 ;
    }
    /**
     * Function to get all video count
     *
     * @param string $type
     * @return string
     */
    public function getVideDocumentCount($type)
    {
        $videoData = '';
        switch ($type) {
            case 'live':
                $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('is_live', 1)->count();
                break;
            case 'active':
                $videoActiveCount = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('is_active', 1)->count();
                $checkValue = '';
                $stringcount = strlen((string)$videoActiveCount);
                for ($i = 2; $i <= $stringcount; $i++) {
                    $checkValue = $checkValue . '0';
                }
                $checkValue = '1' . $checkValue;
                $intergercount = intval(($videoActiveCount / $checkValue) * 1);
                $videoData = $intergercount * $checkValue;
                break;
            case 'inactive':
                $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('is_active', 0)->count();
                break;
            case 'all':
                $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->count();
                break;
            case 'audio':
                $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('mp3', '!=', '')->count();
                break;
            default:
                $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('mp3', '!=', '')->count();
        }
        return $videoData ? $videoData : 0;
    }


    /**
     * Function to get customers count
     *
     * @param string $types
     * @return string
     */
    public function getCustomersCountData()
    {
        $customerData = '';
        $types = $this->request->type?$this->request->type:3;
        switch ($types) {
            case '4':
                $customerData = $this->customer->where('created_at', '>', Carbon::now()->subDays(7))->where('is_active', 1)->count();
                break;
            case '3':
                $customerData = $this->customer->where('created_at', '>', Carbon::now()->subDays(30))->where('is_active', 1)->count();
                break;
            case '2':
                $customerData = $this->customer->where('created_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
                break;
            case '1':
                $customerData = $this->customer->where('is_active', 1)->count();
                break;
            default:
                $customerData = $this->customer->where('created_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
        }
        return $customerData ? $customerData : 0;
    }

    /**
     * Function to get subscribed user data based on day, month and year
     *
     * @param string $select
     */
    public function getSubscribedUserData()
    {

        $subscribed = array();
        $types = $this->request->type?$this->request->type:3;
        switch ($types) {
            case '4':
                $day = ($types == 3) ? 30 : 7;
                $forToday = Carbon::now()->subDays($day);
                $subscribed = $this->subscribers->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE_FORMAT(updated_at, '%Y, %m, %d') as month"))
                    ->where('updated_at', '>=', $forToday->format('Y-m-d'))
                    ->where('is_active', 1)->groupBy(DB::raw("DATE_FORMAT(updated_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(updated_at, '%m-%d')"), 'asc')->get()->toArray();
                break;
            case '2':
                $forToday = Carbon::now()->subMonths(12);
                $subscribed = $this->subscribers->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE_FORMAT(updated_at, '%Y, %m') as month"))
                    ->where('updated_at', '>=', $forToday->format('Y-m-d'))
                    ->where('is_active', 1)->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"))->orderBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"), 'asc')->get()->toArray();

                break;
            case '1':
                $subscribed = $this->subscribers->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE_FORMAT(updated_at, '%Y') as month"))->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))->orderBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"), 'asc')->get()->toArray();
                break;
            default:
                $forToday = Carbon::now()->subDays(7);
                $subscribed = $this->subscribers->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE_FORMAT(updated_at, '%Y, %m, %d') as month"))
                    ->where('updated_at', '>=', $forToday->format('Y-m-d'))
                    ->where('is_active', 1)->groupBy(DB::raw("DATE_FORMAT(updated_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(updated_at, '%m-%d')"), 'asc')->get()->toArray();
        }
        return $subscribed;
    }


    /**
     * Function to get revenue status count data based on day, month and year
     *
     * @param string $select
     */
    
    public function getRevenueData()
    {
        $revenue = array();
        $types = $this->request->type?$this->request->type:3;

        switch ($types) {
           
            case '4':
                
                $forToday = Carbon::now()->subDays(7);
               
                $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
                    ->where('created_at', '>=', $forToday->format('Y-m-d'))
                    ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();
                break;
            case '3':
          
            $forToday = Carbon::now()->subDays(30);
            
            $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
                ->where('created_at', '>=', $forToday->format('Y-m-d'))
                ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();
                break;
            case '2':
                $forToday = Carbon::now()->subMonths(12);
                $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m') as month"))
                    ->where('created_at', '>=', $forToday->format('Y-m-d'))
                    ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'asc')->get()->toArray();
                break;
            case '1':
                $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"),DB::raw("DATE_FORMAT(created_at, '%Y') as month"))
                    ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y')"), 'asc')->get()->toArray();
                break;
            default:
                $forToday = Carbon::now()->subDays(7);
                $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
                    ->where('created_at', '>=', $forToday->format('Y-m-d'))
                    ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();

        }
       
        return $revenue;
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
  /**
   * Method to get get Performance Subscribe Analytics
   * 
   * @return Json
   */
  public function getPerformanceSubscribeAnalytics($video_id,$dateType)
  {
     
      $date = '';
      $aggregate = [];
      $totalRecord = $totalResult = 0;
     
      $totalRecord = $this->video_analytics->where('video_id',intval($video_id))->count();
     
      /** Call to method to the aggregate query */
      $aggregateQuery = $this->dateWiseVideoViewCountAggregateQuery($totalRecord,$video_id,$dateType);
      $aggregate = $aggregateQuery;
      
      $date = $this->dateFilter($dateType);
      if ($date) {
          /** Convert Date format to MongoDB supported Date */
          $date = $this->mongoDBDateConversion($date);
          /** Aggreagate Query to fetch records based on the date*/
          $matchArr['created_at'] = ['$gte' => $date];
          /** performed array shift to make the $match MongoDB aggregate to be first */
          array_unshift($aggregate, ['$match' => $matchArr]);
      }
      /**Used for Pagination */
    // array_push($aggregate, ['$skip' => 0]);
    // array_push($aggregate, ['$limit' => 5]);
   
      /** Perform aggregation on MongoDB query */
     
      $regionwiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate,$video_id) {
              
        return $collection->aggregate($aggregate, ["allowDiskUse" => true]);
      });
      
      $regionwiseAnalyticsArr = $regionwiseAnalytics->toArray();
      $totalResult = $this->getTotalRecords($this->video_analytics, $aggregateQuery);
      $totalResult = (!empty($regionwiseAnalyticsArr)) ? $totalResult : 0;
      /** Calling the standard pagination method as the raw method returns collection, where paginate method is not available */
      return new \Illuminate\Pagination\LengthAwarePaginator($regionwiseAnalytics, $totalResult, 5, 1, [
          'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
      ]);
  }
  /**
   * Method to get Geographic Analytics
   * 
   * @return Json
   */
  public function getGeographicAnalytics($video_id,$dateType)
  {

    $date = '';
    $aggregate = [];
    $totalRecord = $totalResult = 0;
   
    $totalRecord = $this->video_analytics->where('video_id',intval($video_id))->count();
   
    /** Call to method to the aggregate query */
    $aggregateQuery = $this->geographicWiseVideoViewCountAggregateQuery($totalRecord,$video_id);
    $aggregate = $aggregateQuery;
    
    $date = $this->dateFilter($dateType);
    if ($date) {
        /** Convert Date format to MongoDB supported Date */
        $date = $this->mongoDBDateConversion($date);
        /** Aggreagate Query to fetch records based on the date*/
        $matchArr['created_at'] = ['$gte' => $date];
        /** performed array shift to make the $match MongoDB aggregate to be first */
        array_unshift($aggregate, ['$match' => $matchArr]);
    }
    /**Used for Pagination */
     array_push($aggregate, ['$skip' => 0]);
     array_push($aggregate, ['$limit' => 5]);
 
    /** Perform aggregation on MongoDB query */
   
    $regionwiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate,$video_id) {
            
      return $collection->aggregate($aggregate, ["allowDiskUse" => true]);
    });
    
    $regionwiseAnalyticsArr = $regionwiseAnalytics->toArray();
    $totalResult = $this->getTotalRecords($this->video_analytics, $aggregateQuery);
    $totalResult = (!empty($regionwiseAnalyticsArr)) ? $totalResult : 0;
    /** Calling the standard pagination method as the raw method returns collection, where paginate method is not available */
    return new \Illuminate\Pagination\LengthAwarePaginator($regionwiseAnalytics, $totalResult, 5, 1, [
        'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
    ]);

  }

  public function viewCountAnalytics($dateType)
  { 
     
    $videocount = '';
    $types = $this->request->type;
    switch ($types) {
        case '4':
            $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(7))->count();
            break;
        case '3':
            $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(30))->count();
            break;
        case '2':
            $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(365))->count();
            break;
        case '1':
            $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(365))->count();
            break;
        default:
            $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(365))->count();
    }
  
  
    return $videocount ? $videocount : 0 ;
  }

}
