<?php

/**
 * removed unused customer for after few days
 *
 * @name LoginDeviceScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\Customer;
use Contus\Video\Models\DeviceLmit;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Illuminate\Support\Facades\Request;


class LoginDeviceScheduler extends Scheduler
{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->video = new Customer();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        //$event->hourly();
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
            $customers = DeviceLmit::all();
            foreach ($customers as $customer) {
                $startDate =  $customer->updated_at;
                //per day 86400
                $tmrtime = strtotime($startDate) + 432000;
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
                    if (isset($customer->id)) {
                    DeviceLmit::where('id', $customer->id)->delete();
                    }
                }
            }
        };
    }

}