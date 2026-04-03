<style>
    /* General Styles */
    .filter-wrapper {
        width: 70%;
        margin: 0 auto;
        margin-left: 10rem;
        /* desktop spacing */
    }

    /* .filter-wrapper label {
        font-size: 14px;
        color: #000;
       
        font-weight: bold;
    } */

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

    .date-range {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .date-label {
        font-size: 14px;
        font-weight: 500;
        margin: 0 5px;
    }

    .input-wrapper {
        position: relative;
        display: inline-block;
    }

    .input-wrapper input {
        border: 2px solid rgba(128, 130, 133, 0.36);
        border-radius: 30px;
        padding: 6px 35px 6px 12px;
        height: auto;
        min-width: 180px;
    }

    .calendar-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #555;
        pointer-events: none;
    }

    /* Mobile: stack inputs vertically */
    @media (max-width: 768px) {
        .date-range {
            flex-direction: column;
            align-items: flex-start;
        }

        .input-wrapper input {
            width: 100%;
        }
    }
</style>

@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-datetimepicker.min.css')}}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="ActivationReportController as actCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                Activation Audit
            </h4>
        </div>

        @include('reports::common.activation')

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>


        <form method="post" data-base-validator enctype="multipart/form-data">
            {!! csrf_field() !!}
            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                Activation Audit Reports
            </h3>
            <div class="row" style="margin-top:20px;">
                <div class="justify-content-center mx-auto filter-wrapper">
                    <!-- report name -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000;">
                            Report Name<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <input type="text" class="form-control" name="report_name" ng-model="act.report_name"
                                id="report_name" placeholder="Enter Report Name"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                            <p class="error-msg">
                                @{{ errors.report_name.message }}
                            </p>
                        </div>
                    </div>

                    <!-- type -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                            Report Period<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="report_period" id="report_period" class="form-control rounded-pill border-3"
                                ng-model="act.report_period"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Report Type</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="3_days">3 Days</option>
                                <option value="1_week">1 Week</option>
                                <option value="2_weeks">2 Weeks</option>
                                <option value="1_month">Month</option>
                                <option value="3_months">3 Months</option>
                                <option value="6_months">6 Months</option>
                                <option value="1_year">1 Year</option>
                                <option value="custom">Custom</option>
                            </select>
                            <p class="error-msg">
                                @{{ errors.report_period.message }}
                            </p>
                        </div>
                    </div>

                    <!-- organization -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                            Organization<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="organization" id="organization" class="form-control rounded-pill border-3"
                                ng-model="act.organization"
                                ng-options="org.id as org.organization_name for org in actCtrl.orglist"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Organization</option>
                                <!-- <option value="jiocinema">JioCinema</option>
                                                    <option value="animal">Animal</option>
                                                    <option value="abmarco">Marco</option> -->
                            </select>
                            <p class="error-msg">
                                @{{ errors.organization.message }}
                            </p>
                        </div>
                    </div>

                    <!-- users -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                            Users:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="users" id="users" class="form-control rounded-pill border-3" ng-model="act.users"
                                required ng-options="user.id as user.user_name for user in actCtrl.userlist"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Usres</option>
                                <!-- <option value="jiocinema">JioCinema</option>
                                            <option value="animal">Animal</option>
                                            <option value="abmarco">Marco</option> -->
                            </select>
                        </div>
                    </div>

                    <!-- Subscription Plans -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                            Subscription Plans:
                        </label>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input class="radio" type="radio" ng-model="act.subscription_plan" name="subscription_plan"
                                    value="1">Subscription Length
                            </label>
                            <label class="radio-inline">
                                <input class="radio" type="radio" ng-model="act.subscription_plan" name="subscription_plan"
                                    value="0">Subscription Plans
                            </label>
                        </div>
                    </div>

                    <div ng-if="act.subscription_plan == '1'">
                        <!-- information -->
                        <div class="form-group row">
                            <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                <span class="glyphicon glyphicon-question-sign"></span>
                            </label>
                            <div class="col-sm-10 m-auto">
                                <label class="fw-bold">
                                    Please pay attention if you select daily Subscription Plan type Activation Audit Report
                                    will
                                    be generated using only Subscription Plans with length set in days, for monthly
                                    Subscription
                                    Plan type Activation Audit Report will be generated using only Subscription Plans with
                                    length set in months. You can select both types of Subscription Plans to generate report
                                </label>
                            </div>
                        </div>

                        <!-- subscription plan type -->
                        <div class="form-group row" style="margin-bottom: 15px;">
                            <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                Subscription Plan Type <span class="required">*</span>:
                            </label>
                            <div class="col-auto align-items-center gap-4">
                                <!-- Daily -->
                                <input type="radio" id="daily" ng-model="act.subscription_plan_type"
                                    name="subscription_plan_type" value="0">
                                <label for="daily" class="form-label fw-bold mb-0">
                                    Daily
                                </label>&nbsp;&nbsp;&nbsp;

                                <!-- Month -->
                                <input type="radio" id="month" ng-model="act.subscription_plan_type"
                                    name="subscription_plan_type" value="1">
                                <label for="month" class="form-label fw-bold mb-0">
                                    Month
                                </label>
                            </div>
                        </div>

                        <!-- Subscription length: -->
                        <div class="form-group row" style="margin-bottom: 15px;" ng-if="act.subscription_plan_type == '0'">
                            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000;">
                                Subscription length:
                            </label>
                            <div class="col-sm-10 d-flex align-items-center date-range">
                                <!-- From -->
                                <label class="date-label">From:</label>
                                <div class="input-wrapper">
                                    <input type="date" class="form-control" placeholder="dd.mm.yyyy"
                                        ng-model="act.subscription_length_from_date" />
                                    <!-- <i class="fa fa-calendar calendar-icon"></i> -->
                                </div>

                                <!-- To -->
                                <label class="date-label">To:</label>
                                <div class="input-wrapper">
                                    <input type="date" class="form-control" placeholder="dd.mm.yyyy"
                                        ng-model="act.subscription_length_to_date" />
                                    <!-- <i class="fa fa-calendar calendar-icon"></i> -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" style="margin-bottom: 15px;" ng-if="act.subscription_plan_type == '1'">
                            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000;">
                                Subscription length:
                            </label>
                            <div class="col-sm-10 d-flex align-items-center date-range">
                                <!-- From -->
                                <label class="date-label">From:</label>
                                <div class="input-wrapper">
                                    <input type="month" class="form-control" ng-model="act.subscription_length_from_date" />
                                </div>

                                <!-- To -->
                                <label class="date-label">To:</label>
                                <div class="input-wrapper">
                                    <input type="month" class="form-control" ng-model="act.subscription_length_to_date" />
                                </div>
                            </div>
                        </div>

                        <!-- payment Service -->
                        <div class="form-group row" style="margin-bottom: 15px;">
                            <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                Pament Service:
                            </label>
                            <div class="col-sm-10 m-auto">
                                <select name="payment_service" id="payment_service"
                                    class="form-control rounded-pill border-3" ng-model="act.payment_service"
                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <option value="" disabled>Select Payment Service</option>
                                    <option value="authorize_net">Authorize.net</option>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="external_payments">External Payments</option>
                                </select>
                            </div>
                        </div>

                        <!-- auto pay -->
                        <div class="form-group row" style="margin-bottom: 15px;">
                            <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">

                            </label>
                            <div class="col-auto d-flex align-items-center gap-2">

                                <input type="checkbox" name="use_system_default" ng-model="act.autopay"
                                    id="use_system_default">
                                <label for="use-system-default" class="form-label fw-bold mb-0">
                                    Autopay included
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- select plan -->
                    <div ng-if="act.subscription_plan == '0'" class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                            Available Subscription Plans:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="available_plan" class="form-control rounded-pill border-3"
                                ng-model="act.available_plan"
                                ng-options="plan.id as plan.month_type for plan in actCtrl.planlist"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Payment Service</option>
                                <!-- <option value="jiocinema">Authorize.net</option>
                                            <option value="1">1 Month</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="12">12 Months</option> -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========***********========== -->
            <!-- ==========***********========== -->

            <div class="form-group text-center" data-ng-if="checkAccess('activation_audit_reports.create')">
                <button type="submit" value="Generate" class="btn btn-success" ng-click="actCtrl.generate($event)"
                    style="box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.12);line-height: 25px; border: none;">
                    <strong>Generate</strong>
                </button>&nbsp;&nbsp;

                <button type="submit" value="Save" class="button button-blue" ng-click="actCtrl.save($event)">
                    <strong>Save</strong>
                </button>&nbsp;&nbsp;
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/reports/activation-report.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection