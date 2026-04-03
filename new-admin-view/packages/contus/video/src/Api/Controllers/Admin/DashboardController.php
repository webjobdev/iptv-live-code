<?php

/**
 * Dashboard Controller
 *
 * To manage the dashboard of the application.
 *
 * @name       Dashboard Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoAnalytic;
use Contus\User\Models\SiteLanguage;
class DashboardController extends ApiController
{
    public function __construct(DashboardRepository $dashboardRepository)
    {
        parent::__construct();
        $this->repository = $dashboardRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
        $this->video_analytics = new VideoAnalytic();
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {
       
        return $this->getSuccessJsonResponse([
            'info' => [
                'total_number_of_active_videos' => number_format_short($this->repository->getVideDocumentCount('active')),
                'total_revenue' => $this->repository->getRevenue(),
                'revenue_staus' => $this->repository->getRevenueData(),
                'subcribed_user'    => number_format_short($this->repository->getSubscribedUserCount()),
                'register_user'    => number_format_short($this->repository->getCustomersCountData()),
                'user_subscribers'    => $this->repository->getSubscribedUserData(),              
                'total_view_count'=>number_format_short($this->repository->viewCountAnalytics(3)),
                'chart_date_filter' => $this->repository->chartDateFilter(),
                'language' => SiteLanguage::where('is_active', 1)->get()->toArray(),
                'session_language' =>  $this->request->session()->get('site_language'),
                'node_url' => env('NODE_URL'),
            ]
        ]);
    }
    /**
     * Get informtion on video statistics
     * 
     * @return \Illuminate\Http\Response
     */
    public function getVideoStatistics()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'top_categories' => $this->repository->getTopCategories(),
                'latest_videos' => $this->repository->getLatestVideos(),
                'favourite_videos' => $this->repository->getMostFavouriteVideos(),
                'most_commented_videos' => $this->repository->getMostCommentedVideos(),
                'regionwise_analytics' => $this->repository->getRegionWiseAnalytics(),
                'platformwise_analytics_web' => $this->repository->getPlatformWiseAnalytics('web'),
                'platformwise_analytics_ios' => $this->repository->getPlatformWiseAnalytics('ios'),
                'platformwise_analytics_android' => $this->repository->getPlatformWiseAnalytics('android'),
            ]
        ]);
    }
    /**
     * Method to get region wise video count analytics based on datefilter
     * 
     * @return \Illuminate\Http\Response
     */
    public function regionWiseVideoCountAnalytics()
    {
        $dateType = $this->request->type;
        $data = $this->repository->getRegionWiseAnalytics($dateType);
        return ($data) ? $this->getSuccessJsonResponse(['info' => ['regionwise_analytics' => $data]])
            : $this->getSuccessJsonResponse([], 'unknown error occured');
    }
    /**
     * Method to get region wise video count analytics based on datefilter
     * 
     * @return \Illuminate\Http\Response
     */
    public function platformWiseVideoCountAnalytics()
    {
        $dateType = $this->request->type;
        return $this->getSuccessJsonResponse([
            'info' => [
                'platformwise_analytics_web' => $this->repository->getPlatformWiseAnalytics('web', $dateType),
                'platformwise_analytics_ios' => $this->repository->getPlatformWiseAnalytics('ios', $dateType),
                'platformwise_analytics_android' => $this->repository->getPlatformWiseAnalytics('android', $dateType),
            ]
        ]);
    }
    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getSignedCustomer()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'register_user' => $this->repository->getCustomersCountData(),
            ]
        ]);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscribedUserData()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'user_subscribers' => $this->repository->getSubscribedUserData(),
            ]
        ]);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getRevenue()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'total_revenue' => $this->repository->getRevenue(),
            ]
        ]);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getRevenueData()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'revenue_staus' => $this->repository->getRevenueData(),
            ]
        ]);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscribedUserCount()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'subcribed_user' => $this->repository->getSubscribedUserCount(),
            ]
        ]);
    }

    public function languageChange()
    {
       $language_code=$this->request->selectedlanguage;
       $response = 'en';
       if($language_code){          
          $this->request->session()->put('site_language',$language_code);
          $response = session()->get('site_language');
       }       
       return $response;
    }

    public function getOverViewData($type){
        $dateType = $this->request->type;
        return $this->getSuccessJsonResponse([
            'info' => [
                'total_number_of_active_videos' => number_format_short($this->repository->getVideDocumentCount('active')),
                'total_revenue' => $this->repository->getRevenue(),             
                'subcribed_user'    => number_format_short($this->repository->getSubscribedUserCount()),
                'register_user'    => number_format_short($this->repository->getCustomersCountData()), 
                'total_view_count'    => $this->repository->viewCountAnalytics( $dateType),
            ]
        ]);

    }




}
