<?php

/**
 * Countries Controller
 * To manage the Countires details such as create, edit and delete
 * 
 * @name Countries Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Geofencing\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class CountriesController extends BaseController {
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() { 
        return view ( 'geofencing::admin.geofencing');
    }
}
