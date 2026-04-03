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
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';
    }
}
