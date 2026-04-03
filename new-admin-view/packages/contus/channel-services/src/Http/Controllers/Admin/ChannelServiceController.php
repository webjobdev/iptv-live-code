<?php

namespace Contus\ChannelServices\Http\Controllers\Admin;

use Contus\Base\Controller;

class ChannelServiceController extends Controller
{
    public function catchUpIndex()
    {
        return view('channel-services::catch-up-tv.index');
    }

    public function gridListIndex()
    {
        return view('channel-services::catch-up-tv.gridview');
    }

    public function liveRewindIndex()
    {
        return view('channel-services::live-rewind.index');
    }

    public function liveRewindGridList()
    {
        return view('channel-services::live-rewind.gridview');
    }

    public function epgServiceIndex()
    {
        return view('channel-services::epg-service.index');
    }

    public function epgServiceGridList()
    {
        return view('channel-services::epg-service.gridview');
    }
}