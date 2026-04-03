<?php

namespace Contus\SystemUser;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class SystemUserServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $systemUsers = 'system-users';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $systemUsers);
    }

    /**
     * Register any application services.
     */
    public function register() {
        // include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );
    }
}
