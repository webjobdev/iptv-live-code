<?php

namespace Contus\ApiAccess\Http\Controllers;

// use App\Http\Controllers\Controller;
use Contus\Base\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class ApiAccessController extends Controller {

    public function index() {
        return view('api-access::index');
    }

    public function getGridlist() {
        return view('api-access::gridView');
    }

    public function addApiUser(){
        return view('api-access::api-user.create');
    }

    public function editApiUser(){
        return view('api-access::api-user.edit');
    }
}
