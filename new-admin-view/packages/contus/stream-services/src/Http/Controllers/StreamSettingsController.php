<?php

namespace Contus\StreamServices\Http\Controllers;

use Contus\Base\Controller;

class StreamSettingsController extends Controller {

    public function index() {
        return view('stream-services::stream-setting.index');
    }

    public function getGridlist() {
        return view('stream-services::stream-setting.gridView');
    }

    public function addSetting() {
        return view('stream-services::stream-setting.create');
    }

}
