<?php

namespace Contus\Drm;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class DrmServiceProvider extends ServiceProvider {

    public function boot() {
        $drm = 'drm';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $drm);
    }

    public function register() {
        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/defailt.php',
        //     'default'
        // );

        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';


        // $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // $this->loadViewsFrom(__DIR__ . '/resources/views', 'drm');
    }
}
