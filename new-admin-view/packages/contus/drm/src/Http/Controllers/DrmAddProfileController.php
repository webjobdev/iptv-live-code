<?php

namespace Contus\Drm\Http\Controllers;

use Contus\Base\Controller;

class DrmAddProfileController extends Controller {
    public function index() {
        // dd(123);
        return view('drm::drm.adddrmprofile');
    }
}
