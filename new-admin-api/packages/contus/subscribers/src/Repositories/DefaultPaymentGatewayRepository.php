<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Repository;
use Contus\Settings\Model\PaymentService;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Organizations\Model\OrganizationDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DefaultPaymentGatewayRepository extends Repository
{


    public $_paymentService;
    public $_organizationDetail;
    public $_orgSubscribers;

    public function __construct(PaymentService $paymentService, OrganizationDetail $organizationDetail, OrgSubscribers $orgSubscribers)
    {
        parent::__construct();
        $this->_paymentService = $paymentService;
        $this->_organizationDetail = $organizationDetail;
        $this->_orgSubscribers = $orgSubscribers;
    }

    // public function prepareGrid()
    // {
    //     $user = Auth::user();
    //     Log::info($user);

    //     $orgSubscriber = $this->_orgSubscribers->where('id', $user->id)->get();
    //     Log::info($orgSubscriber);
    // }
}
