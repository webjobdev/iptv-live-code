<?php

namespace Contus\Devices\Http\Controllers;

use App\Http\Controllers\Controller;
use Contus\Devices\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller {

    public function index() {
        return view('devices::index');
    }

    public function getGridlist() {
        return view('devices::gridView');
    }

    public function addDevices() {
        return view('devices::create');
    }

    public function addMultipleDevices() {
        return view('devices::create-multi');
    }

    public function editDevices() {
        return view('devices::edit');
    }

    public function getTimezones() {
        return '999';
        return config('timezone');
    }
}
