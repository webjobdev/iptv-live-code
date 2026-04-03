<?php

/**
 * Payment Method Model is used to manage the payment gatways in database
 *
 * @name PaymentMethod
 * @vendor Contus
 * @package payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Models;

use Contus\Base\Model;

class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package payment
     * @var array
     */
    public $table = "coupon";

    protected $fillable = [ 'name','code','offer','user','valid_till','is_active','creator_id','updator_id' ];
}
