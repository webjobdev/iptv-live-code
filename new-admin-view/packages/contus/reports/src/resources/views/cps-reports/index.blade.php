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
    <div data-ng-controller="CpsReportController as cpsCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                Cps Reports
            </h4>
        </div>

        @include('reports::common.cps')

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>


        <form method="post" data-base-validator enctype="multipart/form-data">
            {!! csrf_field() !!}
            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                CPS Report
            </h3>
            <div class="row">
                <div class="justify-content-center mx-auto filter-wrapper">
                    <!-- report name -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                            Report Name<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <input type="text" class="form-control" name="report_name" ng-model="cps.report_name"
                                id="report_name" placeholder="Enter Report Name"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                            <p class="error-msg">
                                @{{ errors.report_name.message }}
                            </p>
                        </div>
                    </div>

                    <!-- type -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Report Type<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="report_type" id="report_type" ng-model="cps.report_type"
                                class="form-control rounded-pill border-3"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Report Type</option>
                                <option value="active_subscribers">Active Subscribers</option>
                                <option value="expiring_subscribers">Expiring Subscribers</option>
                                <option value="activation_subscribers">Activation of Subscribers</option>
                            </select>
                            <p class="error-msg">
                                @{{ errors.report_type.message }}
                            </p>
                        </div>
                    </div>

                    <!-- organization -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Organization<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="organization" id="organization" class="form-control rounded-pill border-3"
                                ng-model="cps.organization"
                                ng-options="org.id as org.organization_name for org in cpsCtrl.orglist"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Organization</option>
                            </select>
                            <p class="error-msg">
                                @{{ errors.organization.message }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                            Report Date:
                        </label>
                        <div class="col-sm-10 d-flex align-items-center date-range">
                            <!-- From -->
                            <label class="date-label">From:</label>
                            <div class="input-wrapper">
                                <input type="date" class="form-control" placeholder="dd.mm.yyyy"
                                    ng-model="cps.report_from_date" max="@{{cps . maxDate}}" min="@{{cps . minDate}}" />
                            </div>

                            <!-- To -->
                            <label class="date-label">To:</label>
                            <div class="input-wrapper">
                                <input type="date" class="form-control" placeholder="dd.mm.yyyy"
                                    ng-model="cps.report_to_date" max="@{{cps . maxDate}}" min="@{{cps . minDate}}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========***********========== -->
            <!-- ==========***********========== -->

            <div class="form-group text-center" data-ng-if="checkAccess('cps_reports.create')">
                <button type="submit" value="Save" class="button button-blue" ng-click="cpsCtrl.save($event)">
                    <strong>Generate</strong>
                </button>&nbsp;&nbsp;
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/reports/cps-report.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/moment.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection