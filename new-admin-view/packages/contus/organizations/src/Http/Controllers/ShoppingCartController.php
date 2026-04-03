<?php 

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class ShoppingCartController extends Controller{

    public function showdetails(){
        return view ('organizations::shopping.index');
    }


    public function getGridlist() {
        return view('organizations::shopping.gridView');
    }

}