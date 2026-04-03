<?php

namespace Contus\Vod;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class VideoOnDemandServiceProvider extends ServiceProvider {

    public function boot() {
        $vod = 'vod';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $vod);
    }

    public function register() {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'vod');
    }
}
