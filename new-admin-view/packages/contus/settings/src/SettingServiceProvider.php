<?php

namespace Contus\Settings;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        $settings = 'settings';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $settings);
        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'views', $settings);
    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'settings');
    }
}
