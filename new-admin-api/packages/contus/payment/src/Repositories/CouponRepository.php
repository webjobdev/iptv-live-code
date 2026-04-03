<?php

/**
 * Coupon Repository
 *
 * To manage the functionalities related to the Coupon module from Coupon Controller
 *
 * @name CouponRepository
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<nagaraj.r@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Http\Request;
use Contus\Payment\Models\Coupon;
use Contus\Base\Helpers\StringLiterals;

class CouponRepository extends BaseRepository {

    protected $coupon;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Coupon
     * @param Contus\Payment\Models\Coupon $coupon
     */
    public function __construct(Coupon $Coupon) {
        parent::__construct ();
        $this->coupon = $Coupon;
    }

    /**
     * Store a newly created Coupon or update the Coupon.
     *
     * @vendor Contus
     *
     * @package Payment
     * @return boolean
     */
    public function addCouponData() {
        $msg = "";
        $errMsg = "";
        $messages = [];
        $errors=[];
        // $this->setRules([ 
        //     'name' => 'required',
        //     'code' => 'required',
        //     'user' => 'required',
        //     'valid_till' => 'required'
        // ]);
        // $this->setMessages('name.required', "The mobile number has already been taken.");
        if($this->request->id){
            $coupon = $this->coupon->find($this->request->id);
            $msg = "coupon successfully updated";
            $errMsg = "error updating the coupon";
        } else {
            $msg = "coupon successfully created";
            $errMsg = "error creating the coupon";
            $coupon = new Coupon();
            $coupon->creator_id = \Auth::user()->id;
        }
        // $this->validate ( $this->request, $this->getRules (), $messages, [] );
        $coupon->code = trim($this->request->code);
        $coupon->offer = $this->request->offer;
        $coupon->name = $this->request->name;
        $coupon->offer_type = $this->request->offer_type;
        $coupon->user = $this->request->user;
        // $coupon->is_trial = $this->request->is_trial;
        if($this->request->offer_type == "trial"){
            $coupon->is_trial = 1;
            $coupon->offer = 0;
        } else {
            $coupon->is_trial = 0;
            $coupon->offer = $this->request->offer;
        }
        $valid_till=date_create($this->request->valid_till);
        $coupon->valid_till = date_format($valid_till,"Y-m-d");
        $coupon->is_active = $this->request->is_active;
        $coupon->updator_id = \Auth::user()->id;
        if($coupon->save()){
            $res = [ "error" => false, "message" => $msg ];
        } else {
            $res = [ "error" => true, "message" => $errMsg ];
        }
        return $res;

    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Payment
     * @return Contus\Payment\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel( $this->coupon)->setEagerLoadingModels ( [] );
        return $this;
    }

    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [
        [ 'name' => 'Coupon name',StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => 'Coupon code',StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => 'Coupon Type',StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => 'Amount',StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::coupon.expire_date'),StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::coupon.status'),StringLiterals::VALUE => '','sort' => false,'class' => true ],
        [ 'name' => trans('payment::coupon.action'),StringLiterals::VALUE => '','sort' => false,'class' => true ]
         ] ];   
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Payment
     * @return \Illuminate\Database\Eloquent\Builder $builderTransaction The builder object of users grid.
     */
    protected function searchFilter($builderCoupon) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            if($key == 'valid_till') {
                $date=date_create($value);
                $value =  date_format($date,"Y-m-d");
            }
                $builderCoupon = $builderCoupon->where ( $key, 'like', "%$value%" );
        }
        return $builderCoupon;
    }
}