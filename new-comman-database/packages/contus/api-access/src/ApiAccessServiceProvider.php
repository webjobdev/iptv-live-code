<?php

namespace Contus\ApiAccess;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class ApiAccessServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $subscribers = 'api-access';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $subscribers);
    }

    /**
     * Register any application services.
     */
    public function register() {
        // include __DIR__ . '/routes/web.php';
        // include __DIR__ . '/routes/api.php';
    }
}
