<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

    /* Pricing Section */
    .pricing-section {
        margin-bottom: 30px;
    }

    .pricing-section h4 {
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .pricing-section p {
        margin-top: 10px;
        font-size: 13px;
    }

    /* Content Section */
    .content-section {
        margin: 30px 0;
    }

    .content-section h4 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .assigned-text {
        margin-top: 10px;
        font-size: 13px;
        color: #555;
    }

    /* Table */
    .table th {
        font-size: 13px;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .table i.glyphicon-filter {
        font-size: 11px;
        margin-right: 4px;
        color: #777;
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {

        .pricing-section h4,
        .content-section h4 {
            font-size: 16px;
        }

        .pricing-section p,
        .assigned-text,
        .table th,
        .table td {
            font-size: 12px;
        }
    }

    @media (max-width: 767px) {

        .pricing-section,
        .content-section {
            margin: 20px 0;
        }

        .pricing-section h4,
        .content-section h4 {
            font-size: 15px;
            text-align: center;
        }

        .pricing-section p,
        .assigned-text {
            text-align: center;
            font-size: 12px;
        }

        /* Make table scrollable on small screens */
        .table-responsive {
            border: 0;
            margin-bottom: 15px;
        }

        .table-responsive>.table {
            margin-bottom: 0;
        }

        .table-responsive td,
        .table-responsive th {
            white-space: nowrap;
        }
    }

    .responsive-box {
        box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2);
        background-color: #fff;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    /* Responsive tweaks */
    @media (max-width: 767px) {
        .responsive-box {
            padding: 10px;
            margin-bottom: 15px;
        }
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

    .accordian-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f2f2f2;
        padding: 10px 0px;
        border-bottom: 2px solid #e3e3e3;
    }

    .accordian-div {
        border: 2px solid #e3e3e3;
        border-radius: 5px
    }

    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }

    /* .channel-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 8px 14px;
        margin-bottom: 8px;
        font-size: 14px;
        cursor: move;
        transition: box-shadow 0.2s ease;
    }

    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 8px;
    }

    .content-container {
        border: 1px solid #ccc;
        border: 2px dashed #337ab7;
        background-color: #f9f9f9;
        border-radius: 8px;
        min-height: 150px;
        padding: 10px;
        margin-bottom: 15px;
        background: #fff;
        cursor: move;
    }

    .content-header {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .item-box {
        border: 1px solid #ddd;
        padding: 8px;
        margin: 5px 0;
        border-radius: 4px;
        background-color: #f9f9f9;
        cursor: move;
    }

    .drop-zone {
        border: 2px dashed #ccc;
        padding: 10px;
        text-align: center;
        color: #999;
        font-style: italic;
        margin-top: 10px;
    } */
    /* Common card style */
    .channel-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 8px 14px;
        margin-bottom: 8px;
        font-size: 14px;
        cursor: move;
        transition: box-shadow 0.2s ease;
    }

    .channel-item:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Left drag handle */
    .channel-drag {
        color: #999;
        margin-right: 10px;
        cursor: grab;
        flex-shrink: 0;
    }

    /* Channel name with icon */
    .channel-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 8px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 500;
        color: #333;
        /* justify-content: space-between; */
    }

    /* Action button (right side) */
    .channel-action {
        color: #666;
        cursor: pointer;
        flex-shrink: 0;
        font-size: 16px;
        transition: color 0.2s;
    }

    .channel-action:hover {
        color: #e74c3c;
    }

    /* Scrollable container */
    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }

    /* Drop area */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 8px;
    }

    .assign-btns {
        margin-top: 15px;
        text-align: center;
    }

    .search-box {
        margin-bottom: 10px;
    }

    .bundle-item {
        background-color: #f5f5f5;
        border-radius: 50px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .bundle-title {
        font-weight: 600;
        flex-shrink: 0;
    }

    .bundle-sub {
        flex-grow: 1;
        color: #555;
    }

    .bundle-price {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .bundle-price del {
        color: #999;
        margin-right: 3px;
    }

    .bundle-rent {
        color: #333;
        font-weight: 500;
        margin-left: 10px;
    }

    .bundle-remove {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        position: absolute;
        top: 5.2rem;
        right: 2rem;
    }

    .bundle-delete {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        /* position: absolute;
        top: 5.2rem;
        right: 2rem; */
    }

    .devc-inpts {
        border: 2px s1olid rgba(128, 130, 133, 0.36) !important;
    }

    .nav.nav-tabs {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 2px solid #ddd;
        padding-left: 0;
        margin-bottom: 1rem;
    }

    /* Tab items */
    .nav.nav-tabs li {
        margin: 0;
        list-style: none;
    }

    .nav.nav-tabs li a {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 500;
        color: #000;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s ease-in-out;
        text-decoration: none;
    }

    /* Active tab */
    .nav.nav-tabs li.active a,
    .nav.nav-tabs li a:hover {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-bottom: 2px solid #00ACCD;
        color: #00ACCD !important;
    }

    /* SVG icons should align with text */
    .nav.nav-tabs li a svg {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
    }

    .nav-tabs i {
        margin-right: 6px;
    }

    .nav-tabs li.active i {
        color: #000;
    }

    .nav-tabs li.active a {
        background-color: #007bff;
        color: #fff;
    }

    #scrollTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: none;
        /* hidden by default */
        padding: 10px 15px;
        font-size: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        z-index: 1000;
    }

    #scrollTopBtn:hover {
        background-color: #555;
    }
</style>

@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/cropper.css') }}" />
@endsection

@section('content')
    <div data-ng-controller="SubscriptionController as subscrCtrl">
        @include('base::layouts.subnav')

        <!-- ==========***********========== -->
        <!-- ==========***********========== -->
        <hr style="border: 1px dashed #eee;">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h2 style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
                Edit Subscription
            </h2>
        </div>
        <hr style="border: 1px dashed #eee;">
        <div ng-init="activeTab='general'">
            <ul class="nav nav-tabs" role="tablist">
                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('general')">
                        <i class="fas fa-cogs"></i>
                        General Settings
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('price')">
                        <i class="fas fa-money-bill-wave"></i>
                        Price Settings
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('conditional')">
                        <i class="fas fa-sliders-h"></i>
                        Conditional Price Settings
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('subscription')">
                        <i class="fas fa-layer-group"></i>
                        Subscription Content Sets
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('accessories')">
                        <i class="fas fa-tools"></i>
                        Accessories
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)" onclick="scrollToSection('partner')">
                        <i class="fas fa-handshake"></i>
                        Partner Products
                    </a>
                </li>
            </ul>
        </div>
        <!-- ==========***********========== -->
        <!-- ==========***********========== -->

        <!-- ==========***********========== -->
        <!-- ==========***********========== -->

        <div class="contentpanel" id="edit-form-div">
            <div class="form-page">
                <form name="monetization-planForm" id="monetization-planForm" method="POST" data-base-validator
                    enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <input type="hidden" id="plan-edit-id" name="id" value="{{ request()->route('id') }}">
                    <input type="hidden" id="edit-org-id" name="org_id" value="{{ request()->query('org_id') }}">

                    <!-- General Settings -->
                    <div class="responsive-box" id="general">
                        <div class="header-section flexbox align-items-center flex-wrap">
                            {{-- <h3>@{{ channel.channel_name }}</h3> --}}
                            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
                                General Settings
                            </h3>
                        </div>

                        <!-- General Settings -->
                        <div class="row">
                            <div class="justify-content-center mx-auto filter-wrapper">
                                <!-- name -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    data-ng-class="{'has-error': errors.name.has}">
                                    <label class="col-sm-2 control-label" for="subs_name"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Name<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-10 m-auto">
                                        <input type="text" class="form-control" name="name" id="subs_name" required
                                            placeholder="{{ trans('organizations::index.mp_gnr_name') }}"
                                            ng-model="subscriptionData.subs_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                                </div>

                                <!-- identifier -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="identifier" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Identifiers<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" ng-model="subscriptionData.identifier"
                                            name="identifier" id="identifier"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            placeholder="{{ trans('organizations::index.mp_gnr_identifier') }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" id="identifier-auto" onclick="autogenerateIdentifier()"
                                            data-id="{{ request()->id }}">
                                        <label for="identifier-auto"><strong>Auto</strong></label>
                                    </div>
                                </div>

                                <!-- platform -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Select Platform:
                                    </label>
                                    @php
                                        $selectedPlatforms = old(
                                            'select_platform',
                                            $organizationDetail->select_platform ?? '[]',
                                        );
                                        $selectedPlatforms = json_decode($selectedPlatforms, true) ?? [];
                                    @endphp
                                    <div class="col-sm-7" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;">
                                        @foreach (['Stb', 'Pc/Lg', 'Ios', 'tvOS', 'Android Mobile', 'Samsung Tv', 'Web', 'Others/Roku'] as $platform)
                                            <div>
                                                <input type="checkbox" class="form-check-input" name="select_platform[]"
                                                    value="{{ $platform }}"
                                                    ng-checked="subscriptionData.select_platform && subscriptionData.select_platform.includes('{{ $platform }}')"
                                                    ng-model="subscriptionData.select_platform['{{ $platform }}']"
                                                    ng-disabled="subscriptionData.org_settings"
                                                    ng-click="togglePlatform('{{ $platform }}')">
                                                <label class="form-check-label">{{ $platform }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-1 col-sm-offset-1 col-sm-1">
                                        <input type="checkbox" id="org_settings"
                                            data-ng-model="subscriptionData.org_settings" data-id="{{ request()->id }}">
                                        <label for="org_settings"><strong>Use Organizations settings</strong></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Settings -->
                    <div class="responsive-box" id="price">
                        <div class="header-section flexbox align-items-center flex-wrap">
                            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
                                Price Settings
                            </h3>
                        </div>

                        <!-- Price Settings -->
                        <div class="row">
                            <div class="justify-content-center mx-auto filter-wrapper">
                                <!-- subscription length -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="subs_length" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Subscription Length<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" ng-model="subscriptionData.subs_length"
                                            name="subs_length" id="subs_length"
                                            ng-disabled="subscriptionData.subs_unlimited_time"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            placeholder="{{ trans('organizations::index.mp_sub_length') }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-input">
                                            <select allowClear="1" data-jquery="select2_custom_ddl" name="subs_time_type"
                                                class="admin_category_sub form-control select2_custom_ddl"
                                                ng-disabled="subscriptionData.subs_unlimited_time"
                                                myValue="subscriptionData.subs_time_type" myPlaceholder="Select Time Type"
                                                data-ng-model="subscriptionData.subs_time_type">
                                                <option value="">----- Select -----</option>
                                                <option value="Days">Days</option>
                                                <option value="Months">Months</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" id="unlimited-auto"
                                            ng-checked="subscriptionData.subs_unlimited_time == 'unlimited'"
                                            data-ng-model="subscriptionData.subs_unlimited_time">
                                        <label for="unlimited-auto"><strong>Unlimited</strong></label>
                                    </div>
                                </div>

                                <!-- Subscription Type -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                        Subscription Type:
                                    </label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline me-3" style="vertical-align: unset;">
                                            <input type="radio" name="payment_method" value="paid"
                                                ng-model="subscriptionData.payment_method">
                                            Paid
                                        </label>

                                        <label class="radio-inline me-3" style="vertical-align: unset;">
                                            <input type="radio" name="payment_method" value="free"
                                                ng-model="subscriptionData.payment_method">
                                            Free
                                        </label>
                                    </div>
                                </div>

                                <!-- Advertising  -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Advertising :
                                    </label>
                                    <label class="switch" style="margin: 10px 0px 10px 16px;">
                                        <input type="checkbox" ng-model="subscriptionData.is_advertise" name="is_advertise"
                                            ng-checked="subscriptionData.is_advertise == '1'"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- Currency  -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                        Currency :
                                    </label>
                                    <div class="col-sm-6">
                                        <label ng-repeat="currency in subscrCtrl.orgCurrency"
                                            for="@{{currency.short_code | lowercase}}" class="radio-inline me-3"
                                            style="vertical-align: unset;">
                                            <input type="radio" id="@{{currency.short_code | lowercase}}" name="currency"
                                                ng-model="subscriptionData.currency" value="@{{currency.short_code}}">
                                            @{{currency.short_code}}
                                        </label>
                                    </div>
                                </div>
                                <!-- <div class="form-group row" style="margin-bottom: 15px;">
                                            <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                                Currency :
                                            </label>
                                            <div class="col-sm-6">
                                                <label for="usd" class="radio-inline me-3" style="vertical-align: unset;">
                                                    <input type="radio" id="usd" name="currency"
                                                        ng-model="subscriptionData.currency" value="USD">
                                                    USD
                                                </label>

                                                <label for="eur" class="radio-inline me-3" style="vertical-align: unset;">
                                                    <input type="radio" id="usd" name="currency"
                                                        ng-model="subscriptionData.currency" value="EUR">
                                                    EUR
                                                </label>

                                                <label for="inr" class="radio-inline me-3" style="vertical-align: unset;">
                                                    <input type="radio" id="inr" name="currency"
                                                        ng-model="subscriptionData.currency" value="INR">
                                                    INR
                                                </label>

                                                <label for="aed" class="radio-inline me-3" style="vertical-align: unset;">
                                                    <input type="radio" id="aed" name="currency"
                                                        ng-model="subscriptionData.currency" value="AED">
                                                    AED
                                                </label>

                                                <label for="bob" class="radio-inline me-3" style="vertical-align: unset;">
                                                    <input type="radio" id="bob" name="currency"
                                                        ng-model="subscriptionData.currency" value="BOB">
                                                    BOB
                                                </label>
                                            </div>
                                        </div> -->

                                <!-- price -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="subscriptionData.payment_method == 'paid'">
                                    <label class="col-sm-2 control-label" for="subs_price"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Subscription price<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-10 m-auto">
                                        <input type="text" class="form-control" name="price" id="subs_price" required
                                            placeholder="{{ trans('organizations::index.mp_sub_price') }}"
                                            ng-model="subscriptionData.subs_price"
                                            ng-required="subscriptionData.payment_method == 'paid'"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- AutoPay  -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        AutoPay :
                                    </label>
                                    <label class="switch" style="margin: 10px 0px 10px 16px;">
                                        <input type="checkbox" ng-model="subscriptionData.is_autopay" name="is_autopay"
                                            placeholder="{{ trans('organizations::index.mp_sub_devices') }}"
                                            ng-checked="subscriptionData.is_autopay =='1'"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- Subscription Devices -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="devices" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Subscription Devices<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" ng-model="subscriptionData.subs_devices"
                                            name="devices" id="device_inpt"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            placeholder="{{ trans('organizations::index.mp_sub_devices') }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <button id="extaPriceBtn" class="button button-blue" ng-click="addDeviceInputs()">
                                            Extra Price Settings
                                        </button>
                                    </div>

                                    <div class="col-sm-6 form-group row" id="inpt-div">
                                        <input type="text" class="form-control mb-2 devc-inpts" name="device"
                                            ng-repeat="inpts in deviceInputs.devices track by $index"
                                            placeholder="Device @{{ $index + 1 }} Price" ng-model="inpts.inputedVal"
                                            ng-change="calculateTotalDevicePrice()"
                                            style="border:2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; display: visible; margin-top: 5px;">
                                    </div>
                                </div>

                                <!-- total -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="total_device" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Total<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-6" style="margin: 10px 0px 10px 16px;">
                                        @{{ subscriptionData.currency == 'USD' ? '$' : subscriptionData.currency == 'EUR' ?
                                        '€' : subscriptionData.currency == 'INR' ? '₹' : subscriptionData.currency == 'AED'
                                        ? 'AED' : subscriptionData.currency == 'BOB' ? 'Bs' : '' }}
                                        @{{ subscriptionData.totalDevicesPrice }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conditional Price Settings -->
                    @include('organizations::monetization-plan.subscription.conditional-price-settings')

                    <!-- Subscription Content Set -->
                    <div class="responsive-box" id="subscription">
                        <div class="header-section flexbox align-items-center flex-wrap">
                            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
                                Subscription Content Sets
                            </h3>
                        </div>

                        <div class="row">
                            <div class="justify-content-center mx-auto filter-wrapper">
                                @include('organizations::monetization-plan.common.SubscriptionsubNav')

                                <div ng-if="subscrCtrl.btnNo == 0">
                                    @include('organizations::monetization-plan.subscription.channel-set')
                                </div>

                                <div ng-if="subscrCtrl.btnNo == 1">
                                    @include('organizations::monetization-plan.subscription.live-event')
                                </div>

                                <div ng-if="subscrCtrl.btnNo == 2">
                                    @include('organizations::monetization-plan.subscription.vod')
                                </div>

                                <div ng-if="subscrCtrl.btnNo == 3">
                                    @include('organizations::monetization-plan.subscription.tvshow')
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accessories -->
                    @include('organizations::monetization-plan.subscription.accessories')

                    <!-- Partner Products -->
                    @include('organizations::monetization-plan.subscription.subs-partner-products')
                </form>

                <div class="bottom-button text-right">
                    <button data-ng-click="subscrCtrl.editSubscription($event)" id="channelEditFormSubmit"
                        class="button button-blue">
                        Save
                    </button>

                    <button id="channelEditFormSubmit" data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)"
                        class="button button-red" style="margin-left: 7px;">
                        Remove
                    </button>

                    <a class="save" href="{{ url()->previous() }}">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
        <!-- <button id="scrollTopBtn" onclick="scrollToTop()">⬆ Top</button> -->
    </div>
    <!-- ==========***********========== -->
    <!-- ==========***********========== -->
@endsection

@section('scripts')
    <script>
        function scrollToSection(id) {
            var el = document.getElementById(id);

            if (el) {
                var headerOffset = 100; // 🔥 adjust this (80–120 based on your header height)

                var elementPosition = el.getBoundingClientRect().top + window.pageYOffset;
                var offsetPosition = elementPosition - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });
            }
        }

        // Show button when scrolling down
        // window.onscroll = function () {
        //     let btn = document.getElementById("scrollTopBtn");

        //     if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        //         btn.style.display = "block";
        //     } else {
        //         btn.style.display = "none";
        //     }
        // };

        // // Scroll to top function
        // function scrollToTop() {
        //     window.scrollTo({
        //         top: 0,
        //         behavior: "smooth"
        //     });
        // }
    </script>
    <script src="{{ asset('adminview/assets/js/cropper.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/organization/monetization-plan/subscription/index.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection