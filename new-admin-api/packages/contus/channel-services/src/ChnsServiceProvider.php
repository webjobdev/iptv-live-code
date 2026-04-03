<?php

namespace Contus\ChannelServices;

use Carbon\Laravel\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class ChnsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $channel = 'channel-services';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $channel);
    }

    public function boot()
    {
        include __DIR__ . '/routes/api.php';

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Contus\ChannelServices\Console\Commands\RunEpgServices::class,
            ]);
        }
    }
}