<?php

namespace Contus\Organizations;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class OrganizationServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $organizations = 'organizations';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $organizations);
    }

    /**
     * Register any application services.
     */

    public function register() {
        $this->mergeConfigFrom(
            __DIR__ . '/config/default.php',
            'default'
        );

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'organizations');

        // (Optional) Publish configs, migrations, etc. here
    }
}
