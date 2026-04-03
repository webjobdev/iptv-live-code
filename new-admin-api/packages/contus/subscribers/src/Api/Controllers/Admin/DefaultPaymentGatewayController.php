<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Settings\Model\PaymentService;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DefaultPaymentGatewayController extends ApiController
{
    // public function __construct(DefaultPaymentGatewayRepository $defaultPaymentGatewayRepository)
    // {
    //     parent::__construct();
    //     $this->repository = $defaultPaymentGatewayRepository;
    //     $this->repository->setRequestType(static::REQUEST_TYPE);
    // }

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

    public function getRecords()
    {
        $data = $this->_paymentService
            ->with(['SystemDefault', 'organizationDefault', 'organizationDefault.subscribers'])
            ->get();

        Log::info($data->toArray());
    }
}
