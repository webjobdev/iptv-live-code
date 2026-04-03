<?php

/**
 * Subscription Scheduler
 *
 * @name SubscriptionScheduler
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
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Cms\Models\EmailTemplates;
use Contus\Notification\Repositories\NotificationRepository;

class SubscriptionScheduler extends Scheduler
{
    /**
     * Function to set the frequency for the scheduler
     *
     * {@inheritDoc}
     * @see \Contus\Base\Schedulers\Scheduler::frequency()
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->dailyAt('00:01'); // Run the task every day at 12:01 AM (Midnight)
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
       \Log::info('SubscriptionScheduler....');
        return function () {
            \Log::info('SubscriptionScheduler called!');
            $user = Customer::whereHas('Subscriber', function ($query) {
                $query->where('subscribers.is_active', 1);
            })->get();

            $type = 'subscription';
            foreach ($user as $userData) {
                $subscriber = Subscribers::where('customer_id', $userData->id)->where('is_active', 1)->orderBy('id', 'desc')->first();

                if (!empty($subscriber)) {
                    $plan = SubscriptionPlan::where('id', $subscriber->subscription_plan_id)->where('is_active', 1)->orderBy('id', 'desc')->first();

                    if (isset($subscriber->end_date) && $subscriber->end_date !== '0000-00-00') {
                        $endDay = new \DateTime($subscriber->end_date);
                    } else {
                        continue;
                    }

                    $now = new \DateTime(Carbon::today()->toDateString());
                    $interval = $now->diff($endDay);
                    $days = $interval->format("%r%a");

                    if ($days <= 2) {
                        $notificationText = '';
                        $emailContent = '';
                        switch ($days) {
                            case '2':
                                $emailContent = trans('customer::subscription.expire_nth_day', ['day' => 3]);
                                $notificationText = trans('customer::subscription.subscription_reminder', ['content' => $emailContent]);
                                break;
                            case '1':
                                $emailContent = trans('customer::subscription.expire_nth_day', ['day' => 2]);
                                $notificationText = trans('customer::subscription.subscription_reminder', ['content' => $emailContent]);
                                break;
                            case '0':
                                $emailContent = trans('customer::subscription.expire_today');
                                $notificationText = trans('customer::subscription.subscription_reminder', ['content' => $emailContent]);
                                break;
                            default:
                                $emailContent = trans('customer::subscription.expired');
                                $notificationText = trans('customer::subscription.subscription_reminder', ['content' => $emailContent]);
                                break;
                        }

                        if (!empty($notificationText) && !empty($emailContent)) {
                            $notify = new NotificationRepository();
                            $notify->addNotifications($userData, null, $type, $notificationText);

                            // TODO
                            // Dispatch a reminder/expired Mail and Notification

                            // if (!empty($userData->device_token)) {
                            //     $notify->sendPushNotification($userData, $type, 0, $notificationText);
                            // }

                            /** Send email to user */
                            $email = EmailTemplates::where('slug', 'subscription_reminder')->first();
                            if (!empty($email)) {
                                $email->subject = str_replace(['##SITE_NAME##'], [config()->get('settings.general-settings.site-settings.site_name')], $email->subject);
                                $email->content = str_replace(['##USERNAME##', '##CONTENT##'], [$userData->name, $emailContent], $email->content);
                                $notify->email($userData, $email->subject, $email->content);
                            }
                        }
                    }

                    /** Update subscriber status */
                    if ($days < 0) {
                        \Log::info('SubscriptionScheduler Is Active = 0 for subscriberId  - ' . $subscriber->id . ' | Customer Id - ' . $userData->id);
                        $subscriberDetails = Subscribers::where('id', '=', $subscriber->id)->first();
                        $subscriberDetails->is_active = 0;
                        $subscriberDetails->save();
                    }
                }
            }
        };
    }
}
