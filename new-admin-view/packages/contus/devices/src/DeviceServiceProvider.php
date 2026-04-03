<?php

namespace Contus\Devices;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class DeviceServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $device = 'devices';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $device);
    }

    /**
     * Register any application services.
     */

    public function register() {
        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'devices');

        // (Optional) Publish configs, migrations, etc. here
    }
}
