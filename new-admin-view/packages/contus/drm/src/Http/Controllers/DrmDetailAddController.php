<?php

namespace Contus\Drm\Http\Controllers;

use Contus\Base\Controller;

class DrmDetailAddController extends Controller{
    public function index(){
        return view('drm::drm.adddetail');
    }
}