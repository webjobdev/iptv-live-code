<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class AddSubscriberController extends Controller
{

    public function index()
    {
        return view('organizations::subscribers.add-subscriber.general-information.subscriber');
    }

    public function view()
    {
        return view('organizations::subscribers.add-subscriber.general-information.index');
    }

    public function getGridlist()
    {
        return view('organizations::subscribers.add-subscriber.general-information.gridView');
    }

    public function deviceIndex()
    {
        return view('organizations::subscribers.add-subscriber.devices.index');
    }

    public function deviceGridList()
    {
        return view('organizations::subscribers.add-subscriber.devices.gridView');
    }

    public function activationIndex()
    {
        return view('organizations::subscribers.add-subscriber.activation.index');
    }
}
