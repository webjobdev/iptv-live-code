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
use Contus\Video\Models\Video;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class PremimumStatusScheduler extends Scheduler
{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->video = new Video();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->everyMinute();
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
           // \Log::info("premium scheduler");
            $premiums = Video::where('is_premium', 1)->get();
            //\Log::info($premiums);
             foreach ($premiums as $premium) {
                
                $startDate =  $premium->created_at;
                $tmrtime = strtotime($startDate) + 86400;
                $tmrdat = date('Y-m-d H:i:s', $tmrtime);
                $endDate = $tmrdat;
                $currentDate =  Carbon::now();

                if ($currentDate > $startDate && $currentDate < $endDate)
                {
                //\Log::info('Between');
                }
                else
                {
                    //\Log::info('In Not Between');
                    if (isset($premium->id)) {
                    Video::where('id', $premium->id)->update(['is_premium' => 0,]);
                    }
                }
             }
        };
    }

}