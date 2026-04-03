<?php

namespace Contus\StreamServices;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class StreamServicesServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $streamServices = 'stream-services';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $streamServices);
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'stream-services');

        // (Optional) Publish configs, migrations, etc. here
    }
}
