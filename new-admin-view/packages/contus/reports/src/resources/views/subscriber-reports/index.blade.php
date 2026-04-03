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
</style>

@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="SubscriberReportController as subreCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                Subscriber Reports
            </h4>
        </div>

        @include('reports::common.subscriber')

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>


        <form method="post" data-base-validator enctype="multipart/form-data">
            {!! csrf_field() !!}
            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                Report
            </h3>

            <div class="row">
                <div class="justify-content-center mx-auto filter-wrapper">
                    <!-- report name -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                            Report Name<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <input type="text" class="form-control" name="report_name" ng-model="subre.report_name"
                                id="report_name" placeholder="Enter Report Name"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                            <p class="error-msg" data-ng-show="errors.report_name.has">
                                @{{ errors.report_name.message }}
                            </p>
                        </div>
                    </div>

                    <!-- type -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Type<span class="required">*</span>:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="report_type" id="report_type" class="form-control rounded-pill border-3"
                                ng-model="subre.report_type"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Report Type</option>
                                <option value="subscriber">Subscriber</option>
                                <!-- <option value="drm">DRM</option> -->
                                <!-- <option value="organization">Organization</option> -->
                            </select>
                            <p class="error-msg" data-ng-show="errors.report_type.has">
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
                                ng-model="subre.organization"
                                ng-options="org.id as org.organization_name for org in subreCtrl.orglist"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Organization</option>
                            </select>
                            <p class="error-msg">
                                @{{ errors.organization.message }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>

            <!-- ==========***********========== -->
            <!-- ==========***********========== -->

            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                Fildes Report
            </h3>

            <div class="row">
                <div class="justify-content-center mx-auto filter-wrapper">
                    <!-- Fildes -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Fildes:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="report_fields" id="report_fields" class="form-control rounded-pill border-3"
                                ng-model="subre.report_fields"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Select Report Fildes</option>
                                <option value="address">Address</option>
                                <option value="city">City</option>
                                <option value="country">Country</option>
                                <option value="email">E-mail</option>
                                <option value="expiration_time">Expiration Time</option>
                                <option value="first_name">First Name</option>
                                <option value="last_access_time">Last Access Time</option>
                                <option value="last_name">Last Name</option>
                                <option value="phone">Phone</option>
                                <option value="state">State</option>
                                <option value="zip_code">Zip Code</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========***********========== -->
            <!-- ==========***********========== -->

            <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                Filters
            </h3>

            <div class="row">
                <div class="justify-content-center mx-auto filter-wrapper">
                    <!-- Filters -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 fw-bold col-form-label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Filter:
                        </label>
                        <div class="col-sm-10 m-auto">
                            <select name="report_filter" id="report_filter" class="form-control rounded-pill border-3"
                                ng-model="subre.report_filter"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                <option value="" disabled>Selet Report Filter</option>
                                <option value="auto_pay">Auto Pay</option>
                                <option value="subscriber_status">Subscriber Enable Status</option>
                                <option value="time_period_new_subscribers">Time Period to Count Quantity of New Subscribers
                                </option>
                                <option value="payment_status">Subscriber Current Payment Statement</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-center" data-ng-if="checkAccess('subscriber_reports.create')">
                <button type="submit" value="Generate" class="btn btn-success" ng-click="subreCtrl.reportGenret($event)"
                    style="box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.12);line-height: 25px; border: none;">
                    <strong>Generate</strong>
                </button>&nbsp;&nbsp;

                <button type="submit" value="Save" class="button button-blue" ng-click="subreCtrl.save($event)">
                    <strong>Save</strong>
                </button>&nbsp;&nbsp;
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/reports/subscriber-report.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection