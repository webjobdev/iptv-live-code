<?php

/**
 * Audio Service Provider which defines all information about the playlist package.
 *
 * @name AudioServiceProvider
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Playlist;

use Illuminate\Support\ServiceProvider;
use Contus\Base\Helpers\StringLiterals;

class PlaylistServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @vendor Contus
     *
     * @package Audio
     * @return void
     */
    public function boot()
    {
        $playlist = 'playlist';
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang', $playlist);
        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views', $playlist);
        $this->publishes([__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets' => public_path('contus/' . $playlist)], $playlist . '_assets');
        $this->publishes([__DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path('contus/' . $playlist)], $playlist . '_config');
        $this->shareDataToView();
    }
    /**
     * Register the application services.
     *
     * @vendor Contus
     *
     * @package User
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes/web.php';
        // include __DIR__ . '/routes/api.php';
    }

    /**
     * Method used to share the data to blade file.
     *
     * Can access getUrl, auth, siteSettings in view files.
     *
     * @return void
     */
    public function shareDataToView()
    {
        view()->share('getAudioAssetsUrl', function ($url = '/') {
            return url(config('contus.playlist.playlist.vendor') . '/' . config('contus.playlist.playlist.package') . '/' . $url);
        });
    }
}
