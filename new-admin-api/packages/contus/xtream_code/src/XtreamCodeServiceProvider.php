<?php

namespace Contus\XtreamCode;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\ServiceProvider;

class XtreamCodeServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $xtream_code = 'xtream_code';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . StringLiterals::RESOURCES . DIRECTORY_SEPARATOR . 'lang', $xtream_code);
    }

    public function register()
    {
        include __DIR__ . '/routes/web.php';
        include __DIR__ . '/routes/api.php';
    }
}
