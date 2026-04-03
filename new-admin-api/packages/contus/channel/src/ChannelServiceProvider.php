<?php

namespace Contus\Channel;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $channel = 'channel';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $channel);
    }

    public function register()
    {
        // include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';

    }
}