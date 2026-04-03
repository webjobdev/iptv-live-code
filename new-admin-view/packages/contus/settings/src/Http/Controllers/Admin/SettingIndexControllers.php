<?php

namespace Contus\settings\Http\Controllers\Admin;

use Contus\Base\Controller;

class SettingIndexControllers extends Controller {

    public function index() {
        return view('settings::subscriber-setting.index');
    }

    public function getgridList() {
        return view('settings::subscriber-setting.gridView');
    }

    public function emailIndex() {
        return view('settings::email-settings.index');
    }

    public function getEmailGridList() {
        return view('settings::email-settings.gridView');
    }
}
