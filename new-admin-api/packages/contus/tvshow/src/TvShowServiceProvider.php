<?php

namespace Contus\Tvshow;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class TvShowServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $tvShow = 'tvshow';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $tvShow);
    }

    public function register()
    {
        include __DIR__ . '/routes/api.php';
    }
}