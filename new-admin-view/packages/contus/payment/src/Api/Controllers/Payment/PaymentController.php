<?php

/**
 * Payment Controller
* To manage the functionalities related to the Transaction Controller gird api methods
*
* @name Transaction Controller
* @vendor Contus
* @package Payment
* @version 1.0
* @author Contus<developers@contus.in>
* @copyright Copyright (C) 2016 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Payment\Api\Controllers\Payment;

use Contus\Base\ApiController;
use Contus\Payment\Repositories\PaymentRepository;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Base\Repositories\Config;

class PaymentController extends ApiController {
    /**
     * class property to hold the instance of PaymentRepository
     *
     * @var \Contus\Base\Repositories\SmsTemplatesRepository
     */
    public $paymentRepository;
    /**
     * Construct method
     */
    public function __construct(PaymentRepository $paymentRepository, SubscriptionPlan $subscriptionrepositary) {
        parent::__construct ();
        $this->repository = $paymentRepository;
        $this->subscription = $subscriptionrepositary;
    }

    /**
     * To get the Transaction Controller info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' =>  [ [ 'name' => 'sometimes|required','type' => 'sometimes|required','description' => 'sometimes|required','is_test' => 'sometimes|required|boolean','is_active' => 'sometimes|required|boolean' ] ] ,'allPayments' => $this->repository->getAllPayments () ] ] );
    }
    /**
     * Store a newly created payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->updatePayments ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'payment::payment.add.success' ) );
        }
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.add.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($paymentId) {
        $isCreated = false;
        if ($this->repository->updatePayments ( $paymentId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'payment::payment.update.success' ) );
        }
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'payment::payment.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.update.error' ) );
    }
}