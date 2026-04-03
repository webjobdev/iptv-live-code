<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class PartnerProductController extends Controller{
    public function index(){
        return view('organizations::partner-product.index');
    }

    public Function PpGridList(){
        return view('organizations::partner-product.gridView');
    }
}
