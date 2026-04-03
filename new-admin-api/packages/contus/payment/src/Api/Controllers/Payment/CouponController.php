<?php
/**
* Coupon Controller
* To manage the coupon related function are there
*
* @name Coupon Controller
* @vendor Contus
* @package Coupon
* @version 1.0
* @author Contus<nagaraj.r@contus.in>
* @copyright Copyright (C) 2016 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Payment\Api\Controllers\Payment;

use Contus\Base\ApiController;
use Contus\Customer\Models\Coupon;
use Contus\Payment\Repositories\CouponRepository;

class CouponController extends ApiController {

    public $couponRepository;
    /**
     * Construct method
     */
    public function __construct(CouponRepository $couponRepository) {
        parent::__construct ();
        $this->repository = $couponRepository;
    }

    /**
     * To add and update the coupon
     *
     * @return \Illuminate\Http\Response
     */
    public function addCoupon(){
        // $data = $this->repository->addCouponData();
        // return $data;
        // try{
        //     if(!$data['error']) {
        //         return $this->getSuccessJsonResponse( [ ], trans($data['message']));
        //     } else {
        //         return $this->getErrorJsonResponse ( [ ], trans ($data['message']) );
        //     }
        // } catch(\Exception $e){
        //     return $this->getErrorJsonResponse(['message' => 'Coupon Failure, Please try again later'], null, 404);
        // }
        $payment = $this->repository->addCouponData();
        if ($payment) {
            return $this->getSuccessJsonResponse( [ ], trans($payment['message']));
        } else {
            return $this->getErrorJsonResponse(['message' => 'Payment Failure, Please try again later'], null, 404);
        }
    }

    /**
     * To get the Transaction Controller info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse([ 'info' => [ 'rules' => [ 'name' => 'required','code' => 'required|min:6|max:8','user' => 'required','offer' => 'sometimes|required|numeric','is_active' => 'sometimes|required|boolean' ] ] ]);
    }
}