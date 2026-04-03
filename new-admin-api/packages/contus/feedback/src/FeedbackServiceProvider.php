<?php

namespace Contus\Feedback;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $feedback = 'feedback';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $feedback);
    }

    /**
     * Register any application services.
     */
    public function register() {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );
    }
}
