<?php

namespace Contus\Organizations\Http\Controllers;

// use App\Http\Controllers\Controller;
use Contus\Base\Controller as Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('organizations::index');
    }

    public function getGridlist()
    {
        return view('organizations::gridView');
    }
}
