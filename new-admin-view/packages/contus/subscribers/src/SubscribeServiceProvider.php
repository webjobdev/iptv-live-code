<?php

namespace Contus\Subscribers;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class SubscribeServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $subscriber = 'subscribers';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $subscriber);
        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $subscriber);

        View::composer('subscribers::layouts.subscribernav', function ($view) {
            $subscriberId = request()->query('subscriber-id');

            // Check if any record has is_active = '1'
            $isAnyActive = DB::table('org_subscription_and_payments')
                ->when($subscriberId, function ($query, $subscriberId) {
                    return $query->where('subscriber_id', $subscriberId);
                })
                ->where('is_active', '1')
                ->exists();

            // Default to 'Off' if no active record
            $statusText = $isAnyActive ? 'On' : 'Off';
            $view->with('activationStatus', $statusText);

            // Count all devices for this subscriber; default to 0 if none
            $viewdevice = DB::table('org_subscriber_devices')
                ->when($subscriberId, function ($query, $subscriberId) {
                    return $query->where('subscriber_id', $subscriberId);
                })
                ->count();

            $view->with('viewdevice', $viewdevice ?: 0);
        });
    }

    public function register()
    {
        include __DIR__ . '/routes/web.php';
    }
}
