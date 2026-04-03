<?php

namespace Contus\Devices;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class DeviceServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $devices = 'devices';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $devices);
    }

    /**
     * Register any application services.
     */

    public function register() {
        // $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        // include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // (Optional) Publish configs, migrations, etc. here
    }
}
