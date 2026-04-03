<?php

namespace Contus\Settings\Http\Controllers\Admin;

use Contus\Base\Controller;

class PaymentServicesController extends Controller
{
    public function serviceIndex()
    {
        return view('settings::payment-service.index');
    }

    public function serviceGridlist()
    {
        return view('settings::payment-service.gridView');
    }

    public function CreateService()
    {
        return view('settings::payment-service.create');
    }

    public function EditService()
    {
        return view('settings::payment-service.edit');
    }

    public function CurrencyIndex()
    {
        return view('settings::payment-service.currencies.index');
    }

    public function CurrencyGridView()
    {
        return view('settings::payment-service.currencies.gridView');
    }

    public function ConverterIndex()
    {
        return view('settings::payment-service.currency-converter.index');
    }

    public function ConverterGridView()
    {
        return view('settings::payment-service.currency-converter.gridView');
    }
}