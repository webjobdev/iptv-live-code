<?php

namespace Contus\Tvshow\Http\Controllers\Admin;

use Contus\Base\Controller;

class TvShowController extends Controller
{
    public function index()
    {
        return view('tvshow::index');
    }

    public function gridList()
    {
        return view('tvshow::gridView');
    }

    public function add()
    {
        return view('tvshow::addTvShow');
    }

    public function editTvShow($id)
    {
        return view('tvshow::editTvShow', ['id' => $id]);
    }

    public function editSeason($id)
    {
        return view('tvshow::editTvShowSeason', ['id' => $id]);
    }

    public function season()
    {
        return view('tvshow::season');
    }

    public function episode($id)
    {
        return view('tvshow::episode.season-episode', ['id' => $id]);
    }

    public function editEpisode($id)
    {
        return view('tvshow::episode.editEpisode', ['id' => $id]);
    }
}