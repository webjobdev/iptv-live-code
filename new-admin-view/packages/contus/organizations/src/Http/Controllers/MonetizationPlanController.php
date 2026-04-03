<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class MonetizationPlanController extends Controller
{
    public function Index()
    {
        return view('organizations::monetization-plan.subscription.index');
    }

    public function subscrGridList()
    {
        return view('organizations::monetization-plan.subscription.gridView');
    }

    public function addSubscription()
    {
        return view('organizations::monetization-plan.subscription.addSubscription');
    }

    public function editSubscription()
    {
        return view('organizations::monetization-plan.subscription.editSubscription');
    }


    // ==============================***********************************==============================
    // ==============================***********************************==============================

    public function AccIndex()
    {
        return view('organizations::monetization-plan.accessories.index');
    }

    public function AccGridList()
    {
        return view('organizations::monetization-plan.accessories.gridView');
    }

    // ==============================***********************************==============================
    // ==============================***********************************==============================

    public function paymentDetail()
    {
        return view('organizations::payment-service.index');
    }
}
