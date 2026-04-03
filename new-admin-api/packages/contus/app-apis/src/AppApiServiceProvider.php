<?php

namespace Contus\AppApi;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class AppApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $appApi = 'app-apis';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $appApi);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );
    }
}
