<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class ContetntSetsController extends Controller
{
    public function showdetails()
    {
        return view('organizations::organization-contentset.contentset');
    }

    // ==========***********==========
    // ==========***********==========

    public function ChannelDetails()
    {
        return view('organizations::organization-contentset.channel_sets.index');
    }

    public function ChannelGridlist()
    {
        return view('organizations::organization-contentset.channel_sets.gridView');
    }

    public function addChannelContetnt()
    {
        return view('organizations::organization-contentset.channel_sets.add');
    }

    public function chnledit()
    {
        $id = request('id');
        return view('organizations::organization-contentset.channel_sets.edit', ['id' => $id]);
    }

    public function chnlview()
    {
        $id = request('id');
        return view('organizations::organization-contentset.channel_sets.view', ['id' => $id]);
    }

    // ==========***********==========
    // ==========***********==========

    public function LiveEventDetails()
    {
        return view('organizations::organization-contentset.live_event_sets.index');
    }

    public function LiveGridlist()
    {
        return view('organizations::organization-contentset.live_event_sets.gridView');
    }

    public function addLiveEventContetnt()
    {
        return view('organizations::organization-contentset.live_event_sets.add');
    }

    public function liveedit()
    {
        $id = request('id');
        return view('organizations::organization-contentset.live_event_sets.edit', ['id' => $id]);
    }

    public function liveview()
    {
        $id = request('id');
        return view('organizations::organization-contentset.live_event_sets.view', ['id' => $id]);
    }

    // ==========***********==========
    // ==========***********==========F

    public function VodDetails()
    {
        return view('organizations::organization-contentset.vod_sets.index');
    }

    public function VodGridlist()
    {
        return view('organizations::organization-contentset.vod_sets.gridView');
    }

    public function addVodContetnt()
    {
        return view('organizations::organization-contentset.vod_sets.add');
    }

    public function vodedit()
    {
        $id = request('id');
        return view('organizations::organization-contentset.vod_sets.edit', ['id' => $id]);
    }

    public function vodview()
    {
        $id = request('id');
        return view('organizations::organization-contentset.vod_sets.view', ['id' => $id]);
    }

    // ==========***********==========
    // ==========***********==========

    public function TvShowDetails()
    {
        return view('organizations::organization-contentset.tv_show_sets.index');
    }

    public function TvShowGridlist()
    {
        return view('organizations::organization-contentset.tv_show_sets.gridView');
    }

    public function addTvShowContetnt()
    {
        return view('organizations::organization-contentset.tv_show_sets.add');
    }

    public function tvshowedit()
    {
        $id = request('id');
        return view('organizations::organization-contentset.tv_show_sets.edit', ['id' => $id]);
    }

    public function tvshowview()
    {
        $id = request('id');
        return view('organizations::organization-contentset.tv_show_sets.view', ['id' => $id]);
    }

    // ==========***********==========
    // ==========***********==========
}
