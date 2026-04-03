@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
    <style>
        .responsive-box {
            box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .filter-wrapper {
            width: 70%;
            margin: 0 auto;
            margin-left: 10rem;
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

        .card {
            background: #fff;
            border-radius: 12px;
            border: 2px solid #d7d7d7;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            font-size: 16px;
            color: #444;
            background: #d7d7d778;
        }

        .card-header .icon {
            font-size: 18px;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            flex-wrap: wrap;
        }

        .label {
            font-size: 14px;
            color: #333;
        }

        .custom-div {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #d7d7d7;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .custom-div .form-group {
            margin: 0;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .custom-div .form-group .text {
            margin-right: 10px;
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .checkbox-group {
            flex-wrap: wrap;
            margin-top: 5px;
            gap: 10px 18px;
        }

        .custom-checkbox,
        .custom-radio {
            accent-color: #222;
            margin-right: 3px;
            vertical-align: middle;
            width: 16px;
            height: 16px;
        }

        .section {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .info-block {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 0.8rem 1rem;
            border: 2px solid #d7d7d7;
            border-radius: 25px;
            width: 100%;
            box-sizing: border-box;
        }

        .payment-information {
            padding: 5px;
        }

        .pyt-sym-typ {
            display: contents;
        }

        /* Responsive CSS */
        @media (max-width: 1200px) {
            .filter-wrapper {
                width: 95%;
                margin: 0 auto;
            }
        }

        @media (max-width: 992px) {
            .filter-wrapper {
                width: 90%;
                margin-left: 2rem;
            }

            .filter-wrapper label {
                font-size: 13px;
            }

            .card-body {
                gap: 15px;
            }

            .custom-div {
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .responsive-box {
                padding: 10px;
                margin-bottom: 15px;
            }

            .card-header {
                font-size: 15px;
            }

            /* Force columns to stack */
            .col-sm-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .card-body {
                padding: 10px;
                flex-direction: column;
                align-items: stretch;
            }

            .custom-div {
                padding: 15px;
                /* Comfortable padding */
                border-radius: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                height: auto;
                /* Allow height to grow */
            }

            .custom-div .label {
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 5px;
                width: 100%;
                line-height: normal;
                white-space: normal;
                /* Allow text wrapping */
            }

            .custom-div .form-group {
                margin: 0;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color: #f8f9fa;
                /* Slight background for the control area */
                padding: 8px 12px;
                border-radius: 10px;
            }

            .custom-div .form-group .text {
                margin-right: 0;
                font-size: 13px;
                font-weight: 500;
            }

            .checkbox-group,
            .radio-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                width: 100%;
            }

            .info-block {
                padding: 15px;
                border-radius: 15px;
                width: 100%;
            }

            /* Adjust the row inside info-block */
            .info-block .custom-div {
                background: none;
                border: none;
                padding: 0;
            }
        }

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

            .card {
                border-radius: 8px;
            }

            .card-header {
                font-size: 14px;
                padding: 8px 12px;
            }

            .card-body {
                padding: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="DashboardConfigurationController as dashconCtrl">
        <div class="form-page" id="dashboard-page">

            <div class="page-heading flexbox align-items-center flex-wrap">
                <h2 style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
                    Dashboard Configuration
                </h2>
            </div>
            <div class="page-heading flexbox align-items-center flex-wrap">
                <label>You can change the layout of your dashboard here</label>
            </div>

            <form method="post" id="dashboardForm" data-base-validator enctype="multipart/form-data" data-ng-submit="">
                {!! csrf_field() !!}

                <input type="hidden" id="config_id" name="id" value="dashboard_configuration">

                <!-- Subscriber Graphs -->
                <div class="responsive-box">
                    <div class="card" style="padding-left: 0px;">
                        <div class="card-header">
                            <span class="icon">👥</span>
                            <span class="title">Subscriber Graphs</span>
                        </div>
                        <div class="card-body">
                            <div class="custom-div">
                                <span class="label">Number of active subscribers</span>
                                <div class="form-group row">
                                    <span class="text">Status</span>
                                    <label class="switch">
                                        <input type="checkbox" ng-checked="record.number_of_active_subscriber == 1"
                                            ng-model="dashconCtrl.dashcon.number_of_active_subscriber">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Graphs -->
                <div class="responsive-box">
                    <div class="card" style="padding-left: 0px;">
                        <div class="card-header">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="20px" height="20px"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z" />
                                </svg>
                            </span>
                            <span class="title">Payment Graphs</span>
                        </div>

                        <div class="card-body">
                            <div class="info-block">
                                <div class="custom-div" style="margin: 0px; width: auto; border: none; padding: 0px;">
                                    <span class="label">Transactions Of Payment Services</span>
                                    <div  class="form-group row">
                                        <span class="text">Status</span>
                                        <label class="switch">
                                            <input type="checkbox" ng-change="toggleTransactionStatus()"
                                                ng-model="dashconCtrl.dashcon.transactions_of_payment_service">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="radio-group">
                                    <span class="label">Based on</span>

                                    <input type="radio" name="based_on" ng-model="dashconCtrl.dashcon.based_on"
                                        class="custom-radio" value="by_type">
                                    <label for="byTypes">By Types</label>

                                    <input type="radio" name="based_on" ng-model="dashconCtrl.dashcon.based_on"
                                        class="custom-radio" value="total_payment_amount">
                                    <label for="byAmount">Total payments amount</label>
                                </div>

                                <div class="checkbox-group">
                                    <span class="label">
                                        Payment System Type:
                                    </span>
                                    @php
                                        $selectedPlatforms = old(
                                            'payment_system_type',
                                            $organizationDetail->payment_system_type ?? '[]',
                                        );
                                        $selectedPlatforms = json_decode($selectedPlatforms, true) ?? [];
                                    @endphp
                                    <div class="pyt-sym-typ" style="">
                                        @foreach (['Cash', 'Authorize.net', 'Check', 'External Payment', 'Gr4vy', '2C2P', 'TrueMoney'] as $platform)
                                            <div>
                                                <input type="checkbox" class="form-check-input custom-checkbox"
                                                    name="payment_system_type[]" value="{{ $platform }}"
                                                    ng-checked="subscriptionData.payment_system_type && subscriptionData.payment_system_type.includes('{{ $platform }}')"
                                                    ng-model="dashconCtrl.dashcon.payment_system_type['{{ $platform }}']"
                                                    ng-click="togglePlatform('{{ $platform }}')">
                                                <label class="form-check-label">{{ $platform }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Cards -->
                <div class="responsive-box">
                    <div class="card" style="padding-left: 0px;">
                        <div class="card-header">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18 2H6C5.44772 2 5 2.44772 5 3V22L7.5 20L9.5 22L12 20L14.5 22L16.5 20L19 22V3C19 2.44772 18.5523 2 18 2Z"
                                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9 6H15" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9 10H15" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9 14H10" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="title">Payment Information Cards</span>
                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <!-- left side -->
                                <div class="col-lg-6 col-12">
                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Autopayment Amount</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.autopayment_amount">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of Cash Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_cash_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of Check Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_check_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of 2C2P Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_2c2p_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of TrueMoney Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_true_money_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- right side -->
                                <div class="col-lg-6 col-12">
                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of Authorize.net Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_authorize_net_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of External Payment Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_external_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of Gr4vy Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_gr4avy_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-information">
                                        <div class="custom-div">
                                            <span class="label">Amount Of Total Payments</span>
                                            <div  class="form-group row">
                                                <span class="text">Status</span>
                                                <label class="switch">
                                                    <input type="checkbox"
                                                        ng-model="dashconCtrl.dashcon.amount_of_total_payment">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- button code -->
            <div class="bottom-button text-center" style="border-radius: 15px; justify-content: center;">
                <button class="publish-now" ng-click="dashconCtrl.save($event)">
                    Save
                </button>

                <a class="save" href="{{ url()->previous() }}">
                    {{ __('video::videos.back') }}
                </a>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/settings/dashboard-configuration.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection