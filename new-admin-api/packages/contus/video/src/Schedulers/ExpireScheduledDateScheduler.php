<?php

/**
 * Premium Sync Scheduler
 *
 * @name ExpireScheduledDateScheduler
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

class ExpireScheduledDateScheduler extends Scheduler
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
            $expireDatas = Video::where('is_live', 3)->where('is_active', 1)->where('is_archived', 0)->get();
             foreach ($expireDatas as $currentVideo) {
                
                $startDate =  $currentVideo->scheduledEndTime;
                $currentDate =  Carbon::now();

                if ($currentDate > $startDate)
                {
                    if (isset($currentVideo->id)) {
                        Video::where('id', $currentVideo->id)->update(['is_active' => 0,]);
                    }
                }

             }
        };
    }

}