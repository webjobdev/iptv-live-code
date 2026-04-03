<?php

namespace Contus\PartnerProgram;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;


class PartnerProgramServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $partnerPrograms = 'partner-programs';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $partnerPrograms);
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'partner-programs');

        // (Optional) Publish configs, migrations, etc. here
    }
}
