<?php

/**
 * Subscription Scheduler
 *
 * @name SubscriptionExpireScheduler
 * @vendor Contus
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Schedulers;

use Carbon\Carbon;
use Contus\Base\Schedulers\Scheduler;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\DeviceLmit;
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Cms\Models\EmailTemplates;
use Contus\Notification\Repositories\NotificationRepository;

class SubscriptionExpireScheduler extends Scheduler
{
    /**
     * Function to set the frequency for the scheduler
     *
     * {@inheritDoc}
     * @see \Contus\Base\Schedulers\Scheduler::frequency()
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->dailyAt('00:02'); // Run the task every day at 12:02 AM (Midnight)
        //  $event->everyMinute();
    }

    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
    //    \Log::info('Subscription Expire Scheduler....');
        return function () {
            // \Log::info('Subscription...........Scheduler called!');
            $allSbscribers = Subscribers::where('is_active', 0)->get();

            foreach ($allSbscribers as $userData) {
                $customer_id =  $userData->customer_id;
                // \Log::info($customer_id);

                if (isset($customer_id)) {
                    // \Log::info('Deleted'); 
                    // \Log::info($customer_id);  
                    DeviceLmit::where('customer_id', $customer_id)->delete();
                }

            }

        };
    }
}
