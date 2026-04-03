<?php

/**
 * Transaction Repository
 *
 * To manage the functionalities related to the Transaction module from Transaction Controller
 *
 * @name TransactionRepository
 * @vendor Contus
 * @package Transaction
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Http\Request;
use Contus\Customer\Models\Customer;
use Contus\Payment\Models\PaymentTransactions;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Payment\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use DB;
class TransactionRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_transaction;

    /**
     * Class property to hold the key which hold the customer object
     *
     * @var object
     */
    protected $_customer;

    /**
     * Constructor function
     *
     * @param PaymentTransactions $transaction
     * @param CustomerRepository $customer
     * @param SubscriptionRepository $subscription
     */
    public function __construct(PaymentTransactions $transaction, CustomerRepository $customer, SubscriptionRepository $subscription)
    {
        parent::__construct();
        $this->_transaction = $transaction;
        $this->_subscription = $subscription;
        $this->_customer = $customer;
    }
    /**
     * Store a newly created payment transaction .
     *
     * @vendor Contus
     *
     * @package Transaction
     * @param $id input
     * @return boolean
     *
     */
    public function addTransactions($package_id = '', $user = '', $decryptValues = '')
    {
        $transactions = new PaymentTransactions();
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i ++) {
            $transaction = explode('=', $decryptValues [$i]);
            if ($i == 3) {
                $transactions->status = $transaction [1];
                $transactions->transaction_message = $transaction [1];
                $transactions->response = $transaction [1];
            }
        }
        dd($transaction);
        $orderId = explode('=', $decryptValues [0]) [1];
        $transactions->payment_method_id = 2;
        $transaction = explode('=', $decryptValues [26]);
        $transactions->customer_id = $user->id;
        $transaction = explode('=', $decryptValues [17]);
        $transactions->phone = $transaction [1];
        $transaction = explode('=', $decryptValues [18]);
        $transactions->email = $transaction [1];
        $transaction = explode('=', $decryptValues [11]);
        $transactions->name = $transaction [1];
        $transaction = explode('=', $decryptValues [1]);
        $transactions->transaction_id = $transaction [1];
        $transactions->creator_id = $transactions->customer_id;
        $transactions->subscriber_id = $transactions->customer_id;
        $transactions->subscription_plan_id = $orderId;
        $transaction = explode('=', $decryptValues [26]);
        $transactions->plan_name = $transaction [1];
        if ($transactions->save()) {
            if (! (auth()->user())) {
                auth()->loginUsingId($transactions->customer_id);
            }
            if ($transactions->status == 'Success') {
                $this->_subscription->addSubscriber($orderId);
            }
            return $transactions;
        } else {
            return false;
        }
    }
    /**
     * fetch all the transactions
     *
     * @vendor Contus
     *
     * @package Transaction
     * @return array
     */
    public function getAllTransactions()
    {
        if (\Auth::user()->id == 1) {
            return $this->_transaction->paginate(10)->toArray();
        } else {
            return $this->_transaction->with('getTransactionUser')->where('customer_id', \Auth::user()->id)->paginate(10)->toArray();
        }
    }
    /**
     * fetches one transaction
     *
     * @vendor Contus
     *
     * @package Transaction
     * @param int $transactionId
     * @return object
     */
    public function getTransaction($transactionId)
    {
        return $this->_transaction->find($transactionId);
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Payment
     * @return Contus\Payment\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_transaction)->setEagerLoadingModels([ 'getTransactionUser','video','getPaymentMethod','getSubscriptionPlan']);
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($transactionBuilder)
    {
        /*
         * updated the all user record only an superadmin user.
         */
        if (config()->get('auth.providers.users.table') === 'customers') {
            $transactionBuilder->where('customer_id', $this->authUser->id);
        } 
        return $transactionBuilder->selectRaw('payment_transactions.*, payment_transactions.id as formatted_created_date');
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Payment
     * @param mixed $builderTransaction
     * @return \Illuminate\Database\Eloquent\Builder $builderTransaction The builder object of users grid.
     */
    protected function searchFilter($builderTransaction)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [ ];

        /**
         * Loop the search fields of users grid and use them to filter search results.
         */
      
        foreach ($searchRecordUsers as $key => $value) {
           
            switch ($key) {
                case 'slug':
                    $builderTransaction = $builderTransaction->whereHas('getTransactionUser', function ($q) use ($value) {
                        $q->where('name', 'like', '%' . $value . '%');
                    });
                    break;
                case 'is_active':
                    if ($key == 'is_active' && $value == 'all') {
                        break;
                    }
                case 'payment_method_id':
                if ($key == 'payment_method_id' && $value == 'all') {
                    break;
                }
                case 'filter_created_at':
                if ($key == 'filter_created_at') {                   
                    
                  $createdDate=Carbon::createFromFormat ( 'd-m-Y', $value )->format ( 'Y-m-d' );             
                  $builderTransaction =  $builderTransaction->whereDate('created_at','=', $createdDate);
                     
                    break;
                }
                default:
                    $builderTransaction = $builderTransaction->where($key, 'like', "%$value%");
            }
        }
        return $builderTransaction;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getGridHeadings()
    {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans('payment::transaction.transaction_id'),StringLiterals::VALUE => '','sort' => true,'class' => false],
        [ 'name' => trans('payment::transaction.customer_name'),StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::transaction.video_name'),StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::transaction.plan_name'),StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::transaction.payment_method'),StringLiterals::VALUE => 'payment_method','sort' => false,'class' => true ],
        [ 'name' => trans('payment::transaction.amount') ,StringLiterals::VALUE => 'amount','sort' => false,'class' => false ],
        [ 'name' => trans('payment::transaction.status'),StringLiterals::VALUE => '','sort' => false,'class' => false ],
        [ 'name' => trans('payment::transaction.created_at'),StringLiterals::VALUE => '','sort' => true,'class' => false ],
         ] ];
    }

    /**
     * Function to fetch all the details of a transaction from the database.
     *
     * @param integer $id
     * The id of the transaction whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteTransaction($id)
    {
        return $this->_transaction->with([ 'getTransactionUser','getPaymentMethod' ])->where('id', $id)->first();
    }

    public function saveTransaction()
    {
        $this->setRules([
          'subscription_plan_id' => 'required',
          'payment_method_id' => 'required|integer'
        ]);
        $this->_validate();
        $return['success'] = false;
        $user = Auth::user();
        $response = $this->request->all();
        
        $subscriptionPlan = SubscriptionPlan::where($this->getKeySlugorId(), $response['subscription_plan_id'])->first();

        Subscribers::where('customer_id', $user->id)->update(['is_active' => 0]);
        $subscribers = new Subscribers();
        $subscribers->subscription_plan_id = $subscriptionPlan->id;
        $subscribers->customer_id = $user->id;
        $subscribers->start_date = date('Y-m-d');
        $subscribers->end_date = date('Y-m-d');
        $subscribers->is_active = 1;
        $subscribers->creator_id = $user->id;
        $subscribers->save();

        $paymentMethod = PaymentMethod::find($response['payment_method_id']);


        $transactions = new PaymentTransactions();
        $transactions->name = $user->name;
        $transactions->email = $user->email;
        $transactions->phone = $user->phone;
        $transactions->payment_method_id = $paymentMethod->id;
        $transactions->customer_id = $user->id;
        $transactions->transaction_id = $this->generateRandomString();
        $transactions->status = 'Paid';
        $transactions->subscription_plan_id = $response['subscription_plan_id'];
        $transactions->subscriber_id = $subscribers->id;
        $transactions->plan_name = $subscriptionPlan->name;
        $transactions->response = json_encode($response);
        $transactions->creator_id = $user->id;
        $transactions->updator_id = $user->id;

        if ($transactions->save()) {
            $return['success'] = true;
        }
        return $return;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getAllPlans() {
        $result['error'] = false;
        $result['message'] = '';
        $result['data']     = '';

        try {
            $data ['subscription'] = SubscriptionPlan::SelectRaw('*, id as is_subscribe')->orderBy('amount', 'asc')->get();
            $data ['subscribed_plan'] = (auth() && auth()->user()) ? auth()->user()->activeSubscriber()->first(): new \stdclass();
            $data ['plan_duration_left'] = '';
            $result['data'] = $data;
        }
        catch (\Exceptions $e) {
            $result['error'] = true;
            $result['message'] = 'Something Went wrong, Please try again later';
        }
        return $result;

    }
}
