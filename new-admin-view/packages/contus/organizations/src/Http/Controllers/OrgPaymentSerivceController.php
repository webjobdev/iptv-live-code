<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class OrgPaymentSerivceController extends Controller
{
    public function index()
    {
        return view('organizations::payment-service.index');
    }

    public function GridView()
    {
        return view('organizations::payment-service.gridView');
    }

    public function currencyIndex()
    {
        return view('organizations::payment-service.currencies.index');
    }

    public function currencyGridView()
    {
        return view('organizations::payment-service.currencies.gridView');
    }

    public function converterIndex()
    {
        return view('organizations::payment-service.currency-converter.index');
    }

    public function converterGridView()
    {
        return view('organizations::payment-service.currency-converter.gridView');
    }
}