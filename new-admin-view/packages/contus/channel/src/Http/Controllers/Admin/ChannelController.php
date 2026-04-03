<?php

namespace Contus\Channel\Http\Controllers\Admin;

use Contus\Base\Controller;

class ChannelController extends Controller
{
    public function getindex()
    {
        return view('channel::index');
    }

    public function gridList()
    {
        return view('channel::gridView');
    }

    public function ChannelAdd()
    {
        return view('channel::addchannel');
    }

    public function ChannelEdit($id){
        return view('channel::editchannel',['id' => $id]);
    }
}