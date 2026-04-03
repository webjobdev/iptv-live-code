<?php

/**
 * Coupon Controller
 *
 * To manage the LatestNews such as create, edit and delete the admin users
 *
 * @name Coupon Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class CouponController extends BaseController {
    /**
     * Construct method
     */
    public function __construct() {
        parent::__construct ();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'payment::admin.coupon.index');
    }

    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'payment::admin.coupon.gridView' );
    }
}
