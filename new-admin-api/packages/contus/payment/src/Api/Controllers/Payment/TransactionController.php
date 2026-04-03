<?php

/**
 * Transaction Controller
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
use Contus\Payment\Repositories\TransactionRepository;
use Contus\Payment\Models\PaymentMethod;

class TransactionController extends ApiController {
    /**
     * class property to hold the instance of SmsTemplatesRepository
     *
     * @var \Contus\Base\Repositories\SmsTemplatesRepository
     */
    public $transactionRepository;
    /**
     * Construct method
     */
    public function __construct(TransactionRepository $transactionRepository) {
        parent::__construct();
        $this->repository = $transactionRepository;
        $this->repoArray = ['repository'];
        $this->paymentMethod = new PaymentMethod();
    }

    /**
     * To get the Transaction Controller info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse(
            [
                'info' => [
                    'rules' => $this->repository->getRules(),
                    'payment_methods' => $this->paymentMethod
                        ->where('is_active', 1)
                        ->get()
                ]
            ]
        );
    }

    /**
     * Function to get complete transaction details .
     *
     * @param integer $id
     * The id of the transactio whose details are to be fetched.
     * @return \Contus\Base\response A JSON string which contains all the information of the transactio.
     */
    public function getCompleteTransactionDetails($id) {
        $transactionDetails = $this->repository->getCompleteTransaction($id);
        return (is_null($transactionDetails)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $transactionDetails]);
    }

    public function postTransaction() {
        $payment = $this->repository->saveTransaction();
        if ($payment['success']) {
            return $this->getSuccessJsonResponse(['message' => 'Payment completed successfully']);
        } else {
            return $this->getErrorJsonResponse(['message' => 'Payment Failure, Please try again later'], null, 404);
        }
    }

    public function getSubscriptions() {
        $result = $this->repository->getAllPlans();

        if ($result['error']) {
            return $this->getErrorJsonResponse([], 'Something went wrong, Please try again later');
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        }
    }
}
