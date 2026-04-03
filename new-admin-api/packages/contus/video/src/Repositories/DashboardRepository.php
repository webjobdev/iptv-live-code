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
use Contus\Channel\Model\Channel;
use Contus\ChannelServices\Model\CatchUpIndex;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Model\LiveRewind;
use Contus\Organizations\Model\OrganizationPayment;
use Contus\StreamServices\Model\StreamingUrlPolicy;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\OrgSubscriberPayment;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Tvshow\Model\TvShow;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Option;
use Contus\Video\Models\Category;
use Contus\Base\Helpers\StringLiterals;
// use DB;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Support\Facades\DB;
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
        return $this->video->has('favouriteVideo')->withCount('favourite')->where('is_active', 1)->where('is_archived', 0)->where('job_status', 'Complete')->orderBy('favourite_count', 'Desc')->paginate(5);
    }

    /**
     * Function to get most commented videos from the database.
     *
     * @return array Most commented videos fetched from the database.
     */
    public function getMostCommentedVideos()
    {
        $comments = Comment::with('video.categories')->whereHas('video', function ($query) {
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
    // public function getRevenue()
    // {

    //     $revenueData = [];
    //     $types = $this->request->type?$this->request->type:3;
    //     switch ($types) {
    //         case '4':
    //             $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(7))->where('status', 'Paid')->sum('amount');
    //             $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(7))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
    //             if($data){
    //                 $data = $data->Format('d M Y');                }

    //             $revenueData['revenueSince']= $data?$data:  Carbon::now()->subDays(7)->Format('d M Y');
    //             break;
    //         case '3':
    //             $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(30))->where('status', 'Paid')->sum('amount');
    //             $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(30))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
    //             if($data){
    //                 $data = $data->Format('d M Y');                }

    //             $revenueData['revenueSince']= $data?$data:  Carbon::now()->subDays(30)->Format('d M Y');
    //             break;
    //         case '2':
    //             $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->sum('amount');
    //             $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
    //             if($data){
    //                 $data = $data->Format('d M Y');                }

    //             $revenueData['revenueSince']= $data?$data: Carbon::now()->subDays(365)->Format('d M Y');
    //             break;
    //         case '1':
    //             $revenueData['revenue'] = PaymentTransactions::where('status', 'Paid')->sum('amount');
    //             $data = PaymentTransactions::where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
    //             if($data){
    //                 $data = $data->Format('d M Y');
    //             }

    //             $revenueData['revenueSince']= $data;
    //             break;
    //         default:              
    //             $revenueData['revenue'] = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->sum('amount');
    //             $data = PaymentTransactions::where('created_at', '>', Carbon::now()->subDays(365))->where('status', 'Paid')->orderBy('created_at','asc')->value('created_at');
    //             if($data){
    //                 $data = $data->Format('d M Y');                }

    //             $revenueData['revenueSince']= $data?$data: Carbon::now()->subDays(365)->Format('d M Y');
    //     }


    //     return $revenueData;
    // }

    public function getRevenue()
    {
        $revenueData = [];

        $type = $this->request->type ?? 3;
        $days = null;

        switch ($type) {
            case '4':
                $days = 7;
                break;
            case '3':
                $days = 30;
                break;
            case '2':
                $days = 365;
                break;
            case '1':
                $days = null;
                break;
            default:
                $days = 365;
        }

        // Prepare base queries
        $ptQuery = PaymentTransactions::where('status', 'Paid');
        $opQuery = OrganizationPayment::where('status', 'PAYMENT_SUCCESS');

        if ($days !== null) {
            $fromDate = Carbon::now()->subDays($days);
            $ptQuery->where('created_at', '>', $fromDate);
            $opQuery->where('created_at', '>', $fromDate);
        }

        // Sum revenue from both models
        $ptSum = $ptQuery->sum('amount');
        $opSum = $opQuery->sum('amount');
        $revenueData['revenue'] = $ptSum + $opSum;

        // Get earliest payment date from both models
        $ptDate = $ptQuery->orderBy('created_at', 'asc')->value('created_at');
        $opDate = $opQuery->orderBy('created_at', 'asc')->value('created_at');

        // Determine earliest date
        if ($ptDate && $opDate) {
            $earliestDate = $ptDate < $opDate ? $ptDate : $opDate;
        } else {
            $earliestDate = $ptDate ?? $opDate;
        }

        if (!isset($earliestDate) && isset($fromDate)) {
            $earliestDate = $fromDate;
        }

        $revenueData['revenueSince'] = $earliestDate ? Carbon::parse($earliestDate)->format('d M Y') : null;

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
        $types = $this->request->type ? $this->request->type : 3;
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
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('is_active', 1)->count();
                break;
            default:
                $activeSubscriber = $this->subscribers->selectRaw('count(customer_id) as count')->where('updated_at', '>', Carbon::now()->subDays(365))->where('is_active', 1)->count();
        }

        return $activeSubscriber ? $activeSubscriber : 0;
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
                $stringcount = strlen((string) $videoActiveCount);
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
        $types = $this->request->type ? $this->request->type : 3;
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
        $types = $this->request->type ? $this->request->type : 3;
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
                $subscribed = $this->subscribers->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE_FORMAT(updated_at, '%Y') as month"))->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))->orderBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"), 'asc')
                    ->where('is_active', 1)
                    ->get()->toArray();
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

    // public function getRevenueData() {
    //     $revenue = array();
    //     $types = $this->request->type ? $this->request->type : 3;

    //     switch ($types) {

    //         case '4':

    //             $forToday = Carbon::now()->subDays(7);

    //             $revenue = PaymentTransactions::select(\Illuminate\Support\Facades\DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
    //                 ->where('created_at', '>=', $forToday->format('Y-m-d'))
    //                 ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();
    //             break;
    //         case '3':

    //             $forToday = Carbon::now()->subDays(30);

    //             $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
    //                 ->where('created_at', '>=', $forToday->format('Y-m-d'))
    //                 ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();
    //             break;
    //         case '2':
    //             $forToday = Carbon::now()->subMonths(12);
    //             $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m') as month"))
    //                 ->where('created_at', '>=', $forToday->format('Y-m-d'))
    //                 ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'asc')->get()->toArray();
    //             break;
    //         case '1':
    //             $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y') as month"))
    //                 ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y')"), 'asc')->get()->toArray();
    //             break;
    //         default:
    //             $forToday = Carbon::now()->subDays(7);
    //             $revenue = PaymentTransactions::select(DB::raw("(SUM(amount)) as count"), DB::raw("DATE_FORMAT(created_at, '%Y, %m, %d') as month"))
    //                 ->where('created_at', '>=', $forToday->format('Y-m-d'))
    //                 ->where('status', 'Paid')->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%m-%d')"), 'asc')->get()->toArray();
    //     }

    //     return $revenue;
    // }


    public function getRevenueData()
    {
        $type = $this->request->type ?? 3;
        $revenue = [];

        // Set date formatting rules based on type
        switch ($type) {
            case '4': // Last 7 days - daily
                $dateFormat = '%Y, %m, %d';
                $groupFormat = '%m-%d';
                $fromDate = Carbon::now()->subDays(7);
                break;
            case '3': // Last 30 days - daily
                $dateFormat = '%Y, %m, %d';
                $groupFormat = '%m-%d';
                $fromDate = Carbon::now()->subDays(30);
                break;
            case '2': // Last 12 months - monthly
                $dateFormat = '%Y, %m';
                $groupFormat = '%Y-%m';
                $fromDate = Carbon::now()->subMonths(12);
                break;
            case '1': // All time - yearly
                $dateFormat = '%Y';
                $groupFormat = '%Y';
                $fromDate = null;
                break;
            default: // Default to last 7 days
                $dateFormat = '%Y, %m, %d';
                $groupFormat = '%m-%d';
                $fromDate = Carbon::now()->subDays(7);
        }

        // Helper to get revenue from a model with a custom status
        $fetchRevenue = function ($model, $status, $fromDate, $dateFormat, $groupFormat) {
            $query = $model::select(
                DB::raw("SUM(amount) as count"),
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as month"),
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as group_key")
            )
                ->where('status', $status);

            if ($fromDate) {
                $query->where('created_at', '>=', $fromDate->format('Y-m-d'));
            }

            return $query->groupBy('group_key')
                ->orderBy('group_key', 'asc')
                ->get()
                ->toArray();
        };

        // Fetch data from both models with correct status filters
        $ptRevenue = $fetchRevenue(PaymentTransactions::class, 'Paid', $fromDate, $dateFormat, $groupFormat);
        $opRevenue = $fetchRevenue(OrganizationPayment::class, 'PAYMENT_SUCCESS', $fromDate, $dateFormat, $groupFormat);

        // Merge results
        $merged = [];

        foreach (array_merge($ptRevenue, $opRevenue) as $row) {
            $key = $row['group_key'];
            if (!isset($merged[$key])) {
                $merged[$key] = [
                    'month' => $row['month'],
                    'count' => 0
                ];
            }
            $merged[$key]['count'] += $row['count'];
        }

        return array_values($merged);
    }





    /**
     * Method to get the chart data filter
     * 
     * @return Json
     */
    public function chartDateFilter()
    {
        $data = array();
        $chartDateFilterArr = array(
            trans('base::general.chart_date_filter.all') => "1",
            trans('base::general.chart_date_filter.last_year') => "2",
            trans('base::general.chart_date_filter.last_month') => "3",
            trans('base::general.chart_date_filter.last_7_days') => "4"
        );
        foreach ($chartDateFilterArr as $label => $value) {
            $data[] = ['id' => (int) $value, 'name' => $label];
        }
        return $data;
    }
    /**
     * Method to get get Performance Subscribe Analytics
     * 
     * @return Json
     */
    public function getPerformanceSubscribeAnalytics($video_id, $dateType)
    {

        $date = '';
        $aggregate = [];
        $totalRecord = $totalResult = 0;

        $totalRecord = $this->video_analytics->where('video_id', intval($video_id))->count();

        /** Call to method to the aggregate query */
        $aggregateQuery = $this->dateWiseVideoViewCountAggregateQuery($totalRecord, $video_id, $dateType);
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

        $regionwiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate, $video_id) {

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
    public function getGeographicAnalytics($video_id, $dateType)
    {

        $date = '';
        $aggregate = [];
        $totalRecord = $totalResult = 0;

        $totalRecord = $this->video_analytics->where('video_id', intval($video_id))->count();

        /** Call to method to the aggregate query */
        $aggregateQuery = $this->geographicWiseVideoViewCountAggregateQuery($totalRecord, $video_id);
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

        $regionwiseAnalytics = $this->video_analytics::raw(function ($collection) use ($aggregate, $video_id) {

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
                $videocount = $this->video_analytics->count();
                break;
            default:
                $videocount = $this->video_analytics->where('created_at', '>', Carbon::now()->subDays(365))->count();
        }


        return $videocount ? $videocount : 0;
    }

    // ========================================*******************************========================================
    // ========================================*******************************========================================

    public function TotalTvShow()
    {
        $show = TvShow::count();
        // dd($show);
        return $show;
    }

    public function TotalMovie()
    {
        $movie = VideoOnDemad::where('is_active', '1')->count();
        // dd($movie);
        return $movie;
    }
    public function LiveEvent()
    {
        $live = Video::where('is_live', '3')->count();
        // dd($live);
        return $live;
    }
    public function TotalChannel()
    {
        $channl = Channel::where('is_active', '1')->count();
        return $channl;
    }
    public function TotalCatchUp()
    {
        $cath = CatchUpIndex::where('is_active', '1')->count();
        return $cath;
    }

    public function TotalLiveRewind()
    {
        $live = LiveRewind::where('is_active', '1')->count();
        return $live;
    }

    public function getSubscriberStats()
    {
        $types = ['custom subscription', 'subscription sets'];

        return [
            'total_subscribers' => OrgSubscribers::count(),
            'active_subscribers' => OrgSubscriberAndPayment::whereIn('product_type', [
                'custom subscription',
                'subscription sets',
                'free subscription',
            ])
            ->where('is_active', '1')
                ->select('subscriber_id')
                ->distinct()
                ->count(),


            'expired_subscribers' => OrgSubscriberAndPayment::where('end_date', '<', Carbon::now())  // <<-- expired = end_date before now
                ->whereIn('product_type', $types)
                ->distinct()
                ->count('subscriber_id'),

            'inactive_subscribers' => OrgSubscriberAndPayment::where('terms_of_agreement', '0')
                ->whereIn('product_type', $types)
                ->distinct()
                ->count('subscriber_id'),

            'new_subscribers' => OrgSubscribers::where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
        ];
    }

    public function getStreams()
    {
        return [
            'enabled_stream' => StreamingUrlPolicy::where('status', '1')->count(),

            // 'restart_stream' => StreamingUrlPolicy::where('restart', '1')->count(),

            'disabled_stream' => StreamingUrlPolicy::where('status', '0')->count(),
        ];
    }

    public function GetEpg()
    {
        return EpgService::all();
    }

    public function GetDeviceData()
    {
        $data = OrgSubscribers::withCount('devices')->get();
        // dd($data);
        // DB::table('org_subscribers as s')
        //     ->leftJoin('org_subscriber_devices as d', 's.id', '=', 'd.subscriber_id')
        //     ->select('s.*', DB::raw('COUNT(d.id) as device_count'))
        //     ->groupBy('s.id')
        //     ->get();

        return $data;
    }

    // public function getActiveData()
    // {
    //     $data = DB::table('org_subscribers as sub')
    //         ->join('org_subscription_and_payments as payment', 'sub.id', '=', 'payment.subscriber_id')
    //         ->select(
    //             'sub.country',
    //             DB::raw('COUNT(sub.id) as active_count')
    //         )
    //         ->whereDate('payment.end_date', '>=', Carbon::now())
    //         ->groupBy('sub.country')
    //         ->get();

    //     return $data;
    // }

    public function getActiveData()
    {
        $limit = request()->input('limit', 5); // default top 5

        $data = DB::table('org_subscribers as sub')
            ->join('org_subscription_and_payments as payment', 'sub.id', '=', 'payment.subscriber_id')
            ->select(
                'sub.country',
                DB::raw('COUNT(sub.id) as active_count')
            )
            ->whereDate('payment.end_date', '>=', Carbon::now())
            ->groupBy('sub.country')
            ->orderByDesc('active_count')
            ->limit($limit)
            ->get();

        return $data;
    }

    // public function getActiveData()
// {
//     $period = $this->request->input('period', 7); // Default 7 days
//     $top = $this->request->input('top', 5);       // Default Top-5

    //     $endDate = Carbon::now()->endOfDay();
//     $startDate = Carbon::now()->subDays($period - 1)->startOfDay();

    //     // Fetch all relevant subscribers and their end dates
//     $subscribers = DB::table('org_subscribers as sub')
//         ->join('org_subscription_and_payments as payment', 'sub.id', '=', 'payment.subscriber_id')
//         ->select('sub.country', 'payment.end_date')
//         ->whereNotNull('sub.country')
//         ->whereDate('payment.end_date', '>=', $startDate)
//         ->get();

    //     $results = [];

    //     // Loop through each day in selected period
//     for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
//         $formattedDate = $date->format('Y-m-d');

    //         // Count active subscribers per country on this date
//         $dailyCounts = $subscribers
//             ->filter(fn($s) => Carbon::parse($s->end_date)->gte($date))
//             ->groupBy('country')
//             ->map(fn($group) => $group->count())
//             ->sortDesc()
//             ->take($top) // Limit to top N countries
//             ->map(fn($count, $country) => [
//                 'country' => $country,
//                 'active_count' => $count,
//             ])
//             ->values()
//             ->toArray();

    //         $results[] = [
//             'date' => $formattedDate,
//             'data' => $dailyCounts,
//         ];
//     }

    //     return [
//         'period' => $period . ' Days',
//         'start_date' => $startDate->toDateString(),
//         'end_date' => $endDate->toDateString(),
//         'top' => 'Top-' . $top,
//         'active_subscribers' => $results,
//     ];
// }


    public function GetDevieCount()
    {
        return [
            'android' => OrgDevices::where('device_type', 'android')
                ->count(),

            'ios' => OrgDevices::where('device_type', 'ios')
                ->count(),

            'web' => OrgDevices::where('device_type', 'web')
                ->count(),

            'samsung_tv' => OrgDevices::where('device_type', 'samsung_tv')
                ->count(),

            'pc' => OrgDevices::where('device_type', 'pc')
                ->count(),

            'stb' => OrgDevices::where('device_type', 'stb')
                ->count(),

            'tvos' => OrgDevices::where('device_type', 'tv_os')
                ->count(),

            'others' => OrgDevices::where('device_type', 'others')
                ->count(),
        ];
    }

    public function GetTotalRevenue()
    {
        $period = $this->request->input('period', 7);

        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($period - 1)->startOfDay();

        $data = OrgSubscriberPayment::select(
            DB::raw('DATE(created_at) as date'),
            'currency',
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('status', 'PAYMENT_SUCCESS')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'), 'currency')
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('currency')
            ->map(function ($items, $currency) use ($startDate, $endDate) {
                // Create a daily date range to fill missing days with zero
                $periodRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());
                $filledData = [];

                foreach ($periodRange as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $dayData = $items->firstWhere('date', $dateStr);

                    $filledData[] = [
                        'date' => $dateStr,
                        'currency' => $currency,
                        'total_amount' => $dayData ? $dayData->total_amount : 0
                    ];
                }

                return [
                    'currency' => $currency,
                    'data' => $filledData
                ];
            })
            ->values();

        // Step 4: Return formatted response
        return [
            'period' => $period . ' Days',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_revenue' => $data
        ];
    }

    public function GetPaymentGateway()
    {
        $period = $this->request->input('period', 7); // Default 7 days
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($period - 1)->startOfDay();

        // Fetch payment data within the selected period
        $data = OrgSubscriberPayment::select(
            DB::raw('DATE(created_at) as date'),
            'currency',
            'payment_gateway',
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('status', 'PAYMENT_SUCCESS')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'), 'currency', 'payment_gateway')
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(['payment_gateway', 'currency'])
            ->map(function ($currencyGroups, $paymentGateway) use ($startDate, $endDate) {
                return $currencyGroups->map(function ($items, $currency) use ($paymentGateway, $startDate, $endDate) {
                    // Create a complete date range for the selected period
                    $dates = [];
                    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                        $dates[$date->format('Y-m-d')] = [
                            'date' => $date->format('Y-m-d'),
                            'total_amount' => 0,
                        ];
                    }

                    // Fill in the actual data
                    foreach ($items as $item) {
                        $dates[$item->date] = [
                            'date' => $item->date,
                            'total_amount' => $item->total_amount,
                        ];
                    }

                    return [
                        'payment_gateway' => $paymentGateway,
                        'currency' => $currency,
                        'data' => array_values($dates),
                    ];
                })->values();
            })
            ->flatten(1)
            ->values();

        return $data;
    }

    public function GetAllCurrency()
    {
        return [
            'USD' => OrgSubscriberPayment::where('currency', 'USD')
                ->sum('amount'),

            'INR' => OrgSubscriberPayment::where('currency', 'INR')
                ->sum('amount'),

            'LAK' => OrgSubscriberPayment::where('currency', 'LAK')
                ->sum('amount'),

            'BOB' => OrgSubscriberPayment::where('currency', 'BOB')
                ->sum('amount'),

            'THB' => OrgSubscriberPayment::where('currency', 'THB')
                ->sum('amount'),

            'EUR' => OrgSubscriberPayment::where('currency', 'EUR')
                ->sum('amount'),
        ];
    }

    public function GetCurrencyType()
    {
        return [
            'authorize.net' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'authorize.net')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'razorpay' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'razorpay')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'cash' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'cash')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'autopayment' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'autopayment')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'check' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'check')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'exteranl' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'exteranl')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            '2c2p' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', '2c2p')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'gr4vy' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'gr4vy')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],

            'true_money' => [
                'USD' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'USD')
                    ->sum('amount'),
                'INR' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'INR')
                    ->sum('amount'),
                'LAK' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'LAK')
                    ->sum('amount'),
                'BOB' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'BOB')
                    ->sum('amount'),
                'THB' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'THB')
                    ->sum('amount'),
                'EUR' => OrgSubscriberPayment::where('payment_gateway', 'true_money')
                    ->where('currency', 'EUR')
                    ->sum('amount'),
            ],
        ];


    }
}

