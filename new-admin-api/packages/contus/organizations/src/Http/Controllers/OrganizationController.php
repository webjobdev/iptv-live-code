<?php

namespace Contus\Organizations\Http\Controllers;

// use App\Http\Controllers\Controller;
use Contus\Base\Controller as Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller {
    public function index() {
        // dd(123);
        return view('organizations::index'); // adjust view path as needed
    }
}
