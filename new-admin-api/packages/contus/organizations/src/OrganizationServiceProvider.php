<?php

namespace Contus\Organizations;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

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
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        $this->mergeConfigFrom(
            __DIR__ . '/config/default.php',
            'default'
        );
    }
}
