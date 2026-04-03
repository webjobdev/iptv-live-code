<style>
    /* General Styles */
    .filter-wrapper {
        width: 70%;
        margin: 0 auto;
        margin-left: 10rem;
        /* desktop spacing */
    }

    .filter-wrapper label {
        font-size: 14px;
        color: #000;
        margin-top: 10px;
        font-weight: bold;
    }

    .filter-wrapper select {
        border: 2px solid rgba(128, 130, 133, 0.36);
        border-radius: 20px;
        padding: 5px 9px;
        height: auto;
    }

    /* Medium Screens (tablets) */
    @media (max-width: 992px) {
        .filter-wrapper {
            width: 90%;
            margin-left: 2rem;
            /* reduce left margin */
        }

        .filter-wrapper label {
            font-size: 13px;
        }
    }

    /* Small Screens (mobile) */
    @media (max-width: 576px) {
        .filter-wrapper {
            width: 100%;
            margin-left: 0;
            padding: 0 10px;
        }

        .filter-wrapper .form-group {
            flex-direction: column;
        }

        .filter-wrapper label {
            margin-bottom: 8px;
            text-align: left;
        }

        .filter-wrapper select {
            width: 100%;
        }
    }

    .chip {
        display: inline-block;
        padding: 6px 12px;
        margin: 4px;
        background-color: #f1f1f1;
        border-radius: 25px;
        font-size: 14px;
        color: #333;
    }

    .chip .close {
        font-size: 16px;
        margin-left: 8px;
        color: #555;
        opacity: 0.6;
    }
</style>

@extends('base::layouts.default')

@section('stylesheet')
    <link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link href="{{asset('adminview/assets/css/banner-default.css')}}" rel="stylesheet">
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')

    <div data-ng-controller="PaymentServiceController as pytsveCtrl">

        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                Add Payment Provider
            </h4>
        </div>

        <div class="contentpanel" id="dashboard-page">
            <div class="form-page">

                <form method="post" id="paymentserviceForm" data-base-validator enctype="multipart/form-data"
                    data-ng-submit="">
                    {!! csrf_field() !!}

                    <input type="hidden" id="service_id" name="service_id" value="{{ request()->id }}">

                    <!-- select redio button -->
                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Select Payment Provider:
                                </label>
                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider"
                                            ng-model="pytsveCtrl.services.payment_provider" value="Authorize.net" checked>
                                        Authorize.net
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider"
                                            ng-model="pytsveCtrl.services.payment_provider" value="Cash">
                                        Cash
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider" checked
                                            ng-model="pytsveCtrl.services.payment_provider" value="External Payments">
                                        External Payments
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider"
                                            ng-model="pytsveCtrl.services.payment_provider" value="Gr4vy">
                                        Gr4vy
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider"
                                            ng-model="pytsveCtrl.services.payment_provider" value="2C2P">
                                        2C2P
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_provider"
                                            ng-model="pytsveCtrl.services.payment_provider" value="TrueMoney"> TrueMoney
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Authorize.net -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == 'Authorize.net'">
                        <div class="justify-content-center mx-auto filter-wrapper">

                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    Authorize.net Settings
                                </h4>
                            </div>

                            <!-- api id -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Api Id<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="api_id" placeholder="Enter Api Id"
                                        ng-model="pytsveCtrl.services.api_id"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- transaction key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Transaction Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="tansaction_key"
                                        placeholder="Enter Transaction Key" ng-model="pytsveCtrl.services.tansaction_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- public client key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Public Client Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="public_client_key"
                                        placeholder="Enter Public Client Key"
                                        ng-model="pytsveCtrl.services.public_client_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- Currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="select_currency">
                                        <option value="">Select Currency</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- mode -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Mode:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="live" ng-model="pytsveCtrl.services.mode">
                                        Live
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="sandbox" ng-model="pytsveCtrl.services.mode">
                                        SandBox
                                    </label>
                                </div>
                            </div>

                            <!-- allow autopay -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Allow AutoPay:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="yes"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        Yes
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="no"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cash -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == 'Cash'">
                        <div class="justify-content-center mx-auto filter-wrapper">

                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    Cash Settings
                                </h4>
                            </div>

                            <!-- Currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="select_currency">
                                        <option value="">Select Currency</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- location filed -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Location Field:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="switch" style="margin-top: 10px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.location_filed"
                                            name="location_filed"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- location type -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    location:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.location" data-jquery="select2_custom_ddl"
                                        myPlaceholder="Select Location" name="location">
                                        <option value="">-- Select Select Currency --</option>
                                        <option value="office">Office</option>
                                        <option value="home">Home</option>
                                        <option value="remote">Remote</option>
                                    </select>
                                </div>
                            </div>

                            <!-- required -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Required:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="checkbox-inline" style="margin-left:15px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.required" value="1">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- external payment -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == 'External Payments'">
                        <div class="justify-content-center mx-auto filter-wrapper">

                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    External Payments Settings
                                </h4>
                            </div>

                            <!-- Currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="select_currency">
                                        <option value="">Select Currency</option>

                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Authorize.net Manual -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Authorize.net Manual:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="switch">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.authorize_manual"
                                            name="authorize_manual"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>

                                    <label class="checkbox-inline" style="margin-left:15px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.Authorize_id" value="1">
                                        Required Transaction ID
                                    </label>
                                </div>
                            </div>

                            <!-- if Gr4vy Transaction ID true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.Authorize_id">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Transaction ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="transaction_id"
                                                placeholder="Enter Transaction ID"
                                                ng-model="pytsveCtrl.services.transaction_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MoneyGram -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    MoneyGram:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="switch">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.MoneyGram" name="MoneyGram"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>

                                    <label class="checkbox-inline" style="margin-left:15px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.MoneyGram_id" value="1">
                                        Required Check ID
                                    </label>
                                </div>
                            </div>

                            <!-- if MoneyGram Required Check ID true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.MoneyGram_id">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Check ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="check_id"
                                                placeholder="Enter Check ID" ng-model="pytsveCtrl.services.check_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- if MoneyGram true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.MoneyGram">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Client ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="client_id"
                                                placeholder="Enter Client ID" ng-model="pytsveCtrl.services.client_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Client Secret:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="client_secret"
                                                placeholder="Enter Client Secret"
                                                ng-model="pytsveCtrl.services.client_secret"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Base URL:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="base_url"
                                                placeholder="Enter Base URL" ng-model="pytsveCtrl.services.base_url"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Express -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    PayPal Express:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="switch">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.PayPal" name="PayPal"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>

                                    <label class="checkbox-inline" style="margin-left:15px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.PayPal_id" value="1">
                                        Required Sale ID
                                    </label>
                                </div>
                            </div>

                            <!-- if PayPal Required Sale ID true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.PayPal_id">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Sale ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="sale_id"
                                                placeholder="Enter Sale ID" ng-model="pytsveCtrl.services.sale_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- if PayPal true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.PayPal">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Client ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="client_id"
                                                placeholder="Enter Client ID" ng-model="pytsveCtrl.services.client_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Client Secret:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="client_secret"
                                                placeholder="Enter Client Secret"
                                                ng-model="pytsveCtrl.services.client_secret"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            App ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="app_id" placeholder="Enter App ID"
                                                ng-model="pytsveCtrl.services.app_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Western Union -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Western Union:
                                </label>
                                <div class="col-sm-9" style="display:flex; align-items:center;">
                                    <label class="switch">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.Western" name="Western"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>

                                    <label class="checkbox-inline" style="margin-left:15px;">
                                        <input type="checkbox" ng-model="pytsveCtrl.services.Western_id" value="1">
                                        Required Check ID
                                    </label>
                                </div>
                            </div>

                            <!-- if Western Union Check ID true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.Western_id">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Check ID:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="check_id"
                                                placeholder="Enter Check ID" ng-model="pytsveCtrl.services.check_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- if Western Union true then show -->
                            <div class="row" ng-show="pytsveCtrl.services.Western">
                                <div class="justify-content-center mx-auto filter-wrapper">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Api Key:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="api_key"
                                                placeholder="Enter Api Key" ng-model="pytsveCtrl.services.api_key"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Api Secret:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="api_secret"
                                                placeholder="Enter Api Secret" ng-model="pytsveCtrl.services.api_secret"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 13px; color: #000; margin-top: 10px;">
                                            Api Endpoint:
                                        </label>
                                        <div class="col-sm-8 m-auto">
                                            <input type="text" class="form-control" name="api_endpoint"
                                                placeholder="Enter Api Endpoint" ng-model="pytsveCtrl.services.api_endpoint"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- gr4vy -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == 'Gr4vy'">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    Gr4vy Settings
                                </h4>
                            </div>

                            <!-- login -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Login<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="login" placeholder="Enter Login"
                                        ng-model="pytsveCtrl.services.login"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Private Key<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="private_key"
                                        placeholder="Enter Private Key" ng-model="pytsveCtrl.services.private_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency<span class="required">*</span>:
                                </label>

                                <!-- Dropdown -->
                                <div class="col-sm-3 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="currency_@{{$index}}">
                                        <option value="">Select Currency</option>
                                    </select>
                                </div>

                                <!-- Chip and Button -->
                                <div class="col-sm-3 d-flex align-items-center">
                                    <div class="chip mr-2">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>

                                <div class="col-sm-2 d-flex align-items-center">
                                    <button type="button" class="button button-blue">
                                        Add Currency
                                    </button>
                                </div>
                            </div>


                            <!-- Countries -->
                            <div class="form-group row" style="margin-bottom: 60px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Countries:
                                </label>
                                <div class="form-input">
                                    <select multiple data-jquery="select2_custom_ddl"
                                        myValue="pytsveCtrl.services.countries" myPlaceholder="select Countries"
                                        ng-init="vgridCtrl.editVideo.category" name="countries"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        data-ng-model="pytsveCtrl.services.countries">
                                        <option value="">Select Country</option>
                                        <option value="Afghanistan">Afghanistan</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="American Samoa">American Samoa</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Anguilla">Anguilla</option>
                                        <option value="Antarctica">Antarctica</option>
                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Aruba">Aruba</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bermuda">Bermuda</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Cayman Islands">Cayman Islands</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Congo, Democratic Republic">Congo, Democratic Republic</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guam">Guam</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hong Kong">Hong Kong</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Iran">Iran</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="North Korea">North Korea</option>
                                        <option value="South Korea">South Korea</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Lao People's Democratic Republic">Lao People's Democratic Republic
                                        </option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libya">Libya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macao">Macao</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia">Micronesia</option>
                                        <option value="Moldova">Moldova</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montenegro">Montenegro</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Puerto Rico">Puerto Rico</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russia">Russia</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia">Saint Lucia</option>
                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                        </option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Serbia">Serbia</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                        <option value="Taiwan">Taiwan</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Timor-Leste">Timor-Leste</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Türkiye">Türkiye</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                        <option value="Uruguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Vietnam">Vietnam</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.category.has">@{{errors.category.message}}</p>
                            </div>

                            <!-- mode -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Mode:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="live" ng-model="pytsveCtrl.services.mode">
                                        Live
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="sabdbox" ng-model="pytsveCtrl.services.mode">
                                        SandBox
                                    </label>
                                </div>
                            </div>

                            <!-- allow autopay -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Allow AutoPay:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="yes"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        Yes
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="no"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2C2P -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == '2C2P'">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    2C2P Settings
                                </h4>
                            </div>

                            <!-- merchant id -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Merchant ID<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="marchant_id"
                                        placeholder="Enter Merchant ID" ng-model="pytsveCtrl.services.marchant_id"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- secret key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Secret Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="secret_key" placeholder="Enter Secret Key"
                                        ng-model="pytsveCtrl.services.secret_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- Merchant Private Key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Merchant Private Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="merchant_private_key"
                                        placeholder="Enter Merchant Private Key"
                                        ng-model="pytsveCtrl.services.merchant_private_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- 2C2P Public key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    2C2P Public key (from merchant portal):
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="public_key"
                                        placeholder="Enter 2C2P Public key" ng-model="pytsveCtrl.services.public_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="select_currency">
                                        <option value="">Select Currency</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- mode -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Mode:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="live" ng-model="pytsveCtrl.services.mode">
                                        Live
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="sandbox" ng-model="pytsveCtrl.services.mode">
                                        SandBox
                                    </label>
                                </div>
                            </div>

                            <!-- allow autopay -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Allow AutoPay:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="yes"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        Yes
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="no"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TrueMoney -->
                    <div class="row" ng-show="pytsveCtrl.services.payment_provider == 'TrueMoney'">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>
                                    TrueMoney Settings
                                </h4>
                            </div>

                            <!-- merchant id -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Merchant ID<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="merchant_id"
                                        placeholder="Enter Merchant ID" ng-model="pytsveCtrl.services.merchant_id"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- api key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Api Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="api_key" placeholder="Enter Api Key"
                                        ng-model="pytsveCtrl.services.api_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!--Redirect URL-->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Redirect URL<span class="required">*</span>:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="url" class="form-control" name="redirect_url"
                                        placeholder="Enter Redirect URL" ng-model="pytsveCtrl.services.redirect_url"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- Merchant Private Key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Merchant Private Key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="merchant_private_key"
                                        placeholder="Enter Merchant Private Key"
                                        ng-model="pytsveCtrl.services.merchant_private_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- TrueMoney Public key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    TrueMoney Public key:
                                </label>
                                <div class="col-sm-8 m-auto">
                                    <input type="text" class="form-control" name="true_money_public_key"
                                        placeholder="Enter TrueMoney Public key"
                                        ng-model="pytsveCtrl.services.true_money_public_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ pytsveCtrl.services.currency }}</span>
                                </div>
                            </div>

                            <!-- currency -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Currency:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="pytsveCtrl.services.select_currency" data-jquery="select2_custom_ddl"
                                        ng-options="currency.currency_code as currency.currency_code for currency in pytsveCtrl.CurrencyList"
                                        myPlaceholder="Select Currency" name="select_currency">
                                        <option value="">Select Currency</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        @{{ pytsveCtrl.services.select_currency }}
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- mode -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Mode:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="live" ng-model="pytsveCtrl.services.mode">
                                        Live
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="mode" value="sandbox" ng-model="pytsveCtrl.services.mode">
                                        SandBox
                                    </label>
                                </div>
                            </div>

                            <!-- allow autopay -->
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Allow AutoPay:
                                </label>

                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="yes"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        Yes
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="auto_pay" value="no"
                                            ng-model="pytsveCtrl.services.auto_pay">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-button text-center"
                        style="border-bottom: 0px; justify-content: center; border-top: 0px; box-shadow: none;">
                        <button class="publish-now" ng-click="pytsveCtrl.save($event)" data-ng-if="checkAccess('payment_services.create')">
                            Save
                        </button>

                        <button type="submit" value="Save" ng-if="editPage" class="button button-blue"
                            ng-click="chnlsetCtrl.updatedata($event)" data-ng-if="checkAccess('payment_services.edit')">
                            <strong>Update</strong>
                        </button>

                        <a class="save" href="{{ url()->previous() }}">
                            {{ __('video::videos.back') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/settings/payment-service.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection