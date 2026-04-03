<?php

namespace Contus\Reports;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'reports');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $reports = 'reports';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $reports);
    }
}
