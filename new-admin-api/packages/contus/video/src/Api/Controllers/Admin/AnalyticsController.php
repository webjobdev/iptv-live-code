<?php

/**
 * Analytics Controller
 *
 * To manage the Analytics dashboard of the application.
 *
 * @name       Analytics Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\AnalyticsRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoAnalytic;
use Contus\User\Models\SiteLanguage;

class AnalyticsController extends ApiController
{
    public function __construct(AnalyticsRepository $analyticsRepository)
    {
        parent::__construct();
        $this->repository = $analyticsRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
        $this->video_analytics = new VideoAnalytic();
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
                'fetch_top_browsers' => $this->repository->fetchTopBrowsers(),
                'total_visitors' => $this->repository->fetchTotalVisitorsAndPageViews(),
                'user_types' => $this->repository->fetchUserTypes(),
            ]
        ]);
    }

    /**
     * Get informtion on video statistics
     * 
     * @return \Illuminate\Http\Response
     */

    public function fetchTotalVisitorsAndPageViews($type){
        $result = $this->repository->fetchTotalVisitorsAndPageViews($type);
        return ($result) ? $this->getSuccessJsonResponse(['total_visitors' => $result]) : $this->getErrorJsonResponse(['status' => 'error', 'message' => 'unknown error occured']);
    }

    /**
     * Get informtion on video statistics
     * 
     * @return \Illuminate\Http\Response
     */

    public function fetchTopBrowsers($type){
        $result = $this->repository->fetchTopBrowsers($type);
        return ($result) ? $this->getSuccessJsonResponse(['top_browsers' => $result]) : $this->getErrorJsonResponse(['status' => 'error', 'message' => 'unknown error occured']);
    }

    /**
     * Get informtion on video statistics
     * 
     * @return \Illuminate\Http\Response
     */

    public function fetchUserTypes($type){
        $result = $this->repository->fetchUserTypes($type);
        return ($result) ? $this->getSuccessJsonResponse(['user_types' => $result]) : $this->getErrorJsonResponse([], 'unknown error occured');

    }
}
