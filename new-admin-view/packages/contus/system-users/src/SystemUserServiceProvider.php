<?php

namespace Contus\SystemUser;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class SystemUserServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $sysUsers = 'system-users';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $sysUsers);
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'system-users');

        // (Optional) Publish configs, migrations, etc. here
    }
}
