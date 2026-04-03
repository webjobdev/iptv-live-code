<?php

/**
 * Premium Sync Scheduler
 *
 * @name PremimumStatusScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\LandingBanner;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class LandingBannerScheduler extends Scheduler
{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->video = new LandingBanner();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        // $event->everyMinute();
        $event->everyThirtyMinutes();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
        return function () {
            $is_active_exist = LandingBanner::where('is_status', 1)->orderBy('id','ASC')->first();
            if(!$is_active_exist) {
                $first_banner = LandingBanner::first();
                $first_banner->is_status = 1;
                $first_banner->save();
            } else {
                $next = LandingBanner::where('id', '>', $is_active_exist->id)->orderBy('id')->first();
                if($next) {
                    
                    $next->is_status = 1;
                    $next->save();
                    $is_active_exist->is_status = 0;
                    $is_active_exist->save();
                } else {
                    $previous = LandingBanner::where('id', '<', $is_active_exist->id)->orderBy('id','ASC')->first();
                    $previous->is_status = 1;
                    $previous->save();
                    $is_active_exist->is_status = 0;
                    $is_active_exist->save();
                }
            }
        };
    }

}