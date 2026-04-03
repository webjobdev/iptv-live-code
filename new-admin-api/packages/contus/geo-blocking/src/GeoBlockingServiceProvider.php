<?php

namespace Contus\GeoBlocking;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class GeoBlockingServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $geoBlocking = 'geo-blocking';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $geoBlocking);
    }

    /**
     * Register any application services.
     */

    public function register() {
        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );

        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // (Optional) Publish configs, migrations, etc. here
    }
}
