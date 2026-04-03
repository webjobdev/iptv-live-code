<?php

namespace Contus\Subscribers;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use Contus\Subscribers\Observer\SubscriberAssignedDeviceObserver;

class SubscribersServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $subscribers = 'subscribers';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $subscribers);

        // Registered observer
        SubscriberAssignedDevice::observe(SubscriberAssignedDeviceObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register() {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );
    }
}
