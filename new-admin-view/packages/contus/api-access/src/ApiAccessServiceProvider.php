<?php

namespace Contus\ApiAccess;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class ApiAccessServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $apiAccess = 'api-access';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $apiAccess);
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'api-access');

        // (Optional) Publish configs, migrations, etc. here
    }
}
