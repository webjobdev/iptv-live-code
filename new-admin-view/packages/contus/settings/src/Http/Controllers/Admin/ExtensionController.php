<?php

namespace Contus\Settings\Http\Controllers\Admin;

use Contus\Base\Controller;

class ExtensionController extends Controller
{
    // play back token
    public function index()
    {
        return view('settings::extension.play-back.index');
    }

    public function gridView()
    {
        return view('settings::extension.play-back.gridView');
    }

    public function RedirectIndex()
    {
        return view('settings::extension.device-redirect.index');
    }

    public function RedirectgridView()
    {
        return view('settings::extension.device-redirect.gridView');
    }

    // Dashboards Configuration
    public function dashIndex()
    {
        return view('settings::dashboard-configuration.index');
    }

    // M3U Channel
    public function m3uIndex()
    {
        return view('settings::m3u-channel.index');
    }

    public function m3uGridView()
    {
        return view('settings::m3u-channel.gridView');
    }

    public function m3uVodIndex()
    {
        return view('settings::m3u-vod.index');
    }

    public function m3uVodGridView()
    {
        return view('settings::m3u-vod.gridView');
    }

    public function m3uTvShowIndex()
    {
        return view('settings::m3u-tvshow.index');
    }

    public function m3uTvShowGridView()
    {
        return view('settings::m3u-tvshow.gridView');
    }
}