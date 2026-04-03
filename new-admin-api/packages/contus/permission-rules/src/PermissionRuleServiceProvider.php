<?php

namespace Contus\PermissionRule;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class PermissionRuleServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $permissionRule = 'permission-rules';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $permissionRule);
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
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        // $this->loadViewsFrom(__DIR__ . '/resources/views', 'permission-rules');

        // (Optional) Publish configs, migrations, etc. here
    }
}
