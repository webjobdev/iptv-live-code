<?php

namespace Contus\Vod\Http\Controllers\Admin;

use Contus\Base\Controller;

class VideoOnDemandController extends Controller
{

    public function getindex()
    {
        return view('vod::index');
    }

    public function gridList()
    {
        return view('vod::gridView');
    }

    public function videoAdd()
    {
        return view('vod::addvod');
    }

    public function VodEdit($id)
    {
        return view('vod::editvod', ['id' => $id]);
    }
}
