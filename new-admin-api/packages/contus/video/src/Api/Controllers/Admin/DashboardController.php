<?php

/**
 * Dashboard Controller
 *
 * Manages the dashboard functionalities of the application.
 *
 * @name       Dashboard Controller
 * @version    1.0
 * @author     Contus Team
 * @copyright  Copyright (C) 2016 Contus.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Base\ApiController;
use Contus\Video\Models\VideoAnalytic;
use Contus\User\Models\SiteLanguage;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ApiController
{
    protected $video_analytics;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        parent::__construct();
        $this->repository = $dashboardRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
        $this->video_analytics = new VideoAnalytic();
    }

    /**
     * Get dashboard overview and user info.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo()
    {
        $user = Auth::user();
        $authID = $user->id ?? '';
        $name = $user->name ?? '';
        $phone = $user->phone ?? '';
        $profileImg = $user->profile_image ?? '';

        return $this->getSuccessJsonResponse([
            'info' => [
                'total_number_of_active_videos' => number_format_short($this->repository->getVideDocumentCount('active')),
                'total_revenue' => $this->repository->getRevenue(),
                'revenue_staus' => $this->repository->getRevenueData(),
                'subcribed_user' => number_format_short($this->repository->getSubscribedUserCount()),
                'register_user' => number_format_short($this->repository->getCustomersCountData()),
                'user_subscribers' => $this->repository->getSubscribedUserData(),
                'total_view_count' => number_format_short($this->repository->viewCountAnalytics(3)),
                'chart_date_filter' => $this->repository->chartDateFilter(),
                'language' => SiteLanguage::where('is_active', 1)->get()->toArray(),
                'session_language' => method_exists($this->request, 'session') && $this->request->hasSession()
                    ? $this->request->session()->get('site_language', '')
                    : '',

                // 'node_url' => env('NODE_URL'),
                'node_url' => env('NODE_URL'),
                'current_user' => [
                    'authID' => $authID,
                    'name' => $name,
                    'phone' => $phone,
                    'profileImg' => $profileImg,
                ],
            ]
        ]);
    }

    /**
     * Get statistics for videos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoStatistics()
    {
        $dateType = $this->request->type;

        return $this->getSuccessJsonResponse([
            'info' => [
                'top_categories' => $this->repository->getTopCategories(),
                'latest_videos' => $this->repository->getLatestVideos(),
                'favourite_videos' => $this->repository->getMostFavouriteVideos(),
                'most_commented_videos' => $this->repository->getMostCommentedVideos(),
                'regionwise_analytics' => $this->repository->getRegionWiseAnalytics($dateType),
                'platformwise_analytics_web' => $this->repository->getPlatformWiseAnalytics('web', $dateType),
                'platformwise_analytics_ios' => $this->repository->getPlatformWiseAnalytics('ios', $dateType),
                'platformwise_analytics_android' => $this->repository->getPlatformWiseAnalytics('android', $dateType),
            ]
        ]);
    }

    /**
     * Get region-wise video analytics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function regionWiseVideoCountAnalytics()
    {
        $dateType = $this->request->type;
        $data = $this->repository->getRegionWiseAnalytics($dateType);

        return $data
            ? $this->getSuccessJsonResponse(['info' => ['regionwise_analytics' => $data]])
            : $this->getSuccessJsonResponse([], 'Unknown error occurred');
    }

    /**
     * Get platform-wise video analytics.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get registered user count.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get subscribed user data.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get total revenue.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get revenue status breakdown.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get subscribed user count.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscribedUserCount()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'subcribed_user' => $this->repository->getSubscribedUserCount(),
            ]
        ]);
    }

    /**
     * Change session language.
     *
     * @return string
     */
    public function languageChange()
    {
        $languageCode = $this->request->selectedlanguage;

        if ($languageCode) {
            $this->request->session()->put('site_language', $languageCode);
        }

        return session()->get('site_language', 'en');
    }

    /**
     * Get dashboard overview stats based on a date filter.
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOverViewData($type)
    {
        $dateType = $this->request->type;

        return $this->getSuccessJsonResponse([
            'info' => [
                'total_number_of_active_videos' => number_format_short($this->repository->getVideDocumentCount('active')),
                'total_revenue' => $this->repository->getRevenue(),
                'subcribed_user' => number_format_short($this->repository->getSubscribedUserCount()),
                'register_user' => number_format_short($this->repository->getCustomersCountData()),
                'total_view_count' => $this->repository->viewCountAnalytics($dateType),
            ]
        ]);
    }

    // ========================================*******************************========================================
    // ========================================*******************************========================================

    public function fetchContent()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'total_tv_show' => $this->repository->TotalTvShow(),
                'total_movie' => $this->repository->TotalMovie(),
                'total_live_event' => $this->repository->LiveEvent(),
                'total_channel' => $this->repository->TotalChannel(),
                'total_catch_up' => $this->repository->TotalCatchUp(),
                'total_live_rewind' => $this->repository->TotalLiveRewind(),
            ]
        ]);
    }

    public function fetchSubCount()
    {
        return $this->getSuccessJsonResponse([
            'data' => [$this->repository->getSubscriberStats()]
        ]);

    }

    public function fetchstreams()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                $this->repository->getStreams()
            ]
        ]);
    }

    public function fetchepg()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'epg_data' => $this->repository->GetEpg(),
            ]
        ]);
    }

    public function fetchsubDevice()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'subscriber_device_data' => $this->repository->GetDeviceData(),
            ]
        ]);
    }

    public function fetchActiveCount()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'subscriber_active_data' => $this->repository->getActiveData()
            ]
        ]);
    }

    public function fetchDeviceCount()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'device_count' => $this->repository->GetDevieCount()
            ]
        ]);
    }

    public function fetchTotalRevenue()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'total_revenue' => $this->repository->GetTotalRevenue()
            ]
        ]);
    }

    public function fetchpyt()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'total_revenue' => $this->repository->GetPaymentGateway()
            ]
        ]);
    }

    public function fetchcurrency()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'all' => $this->repository->GetAllCurrency(),
                'gateway_type' => $this->repository->GetCurrencyType(),
            ]
        ]);
    }
}
