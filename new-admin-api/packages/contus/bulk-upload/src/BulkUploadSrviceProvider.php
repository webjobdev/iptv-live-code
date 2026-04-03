<?php

namespace Contus\BulkUpload;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class BulkUploadSrviceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $bulkUpload = 'bulk-upload';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $bulkUpload);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        include __DIR__ . '/routes/api.php';

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/default.php',
        //     'default'
        // );
    }
}
