<?php

namespace Contus\GeoBlocking\Http\Controllers;

use Contus\Base\Controller;

class GeoRestrictionController extends Controller {

    public function index() {
        return view('geo-blocking::geo-restrictions.index');
    }

    public function index1() {
        return view('geo-blocking::ip-restrictions.index');
    }

    public function getGeoGridlist() {
        return view('geo-blocking::geo-restrictions.gridView');
    }

    public function getIpGridlist() {
        return view('geo-blocking::ip-restrictions.gridView');
    }

    public function addGeoRestriction() {
        return view('geo-blocking::geo-restrictions.create');
    }

    public function addIpRestriction() {
        return view('geo-blocking::ip-restrictions.create');
    }
}
