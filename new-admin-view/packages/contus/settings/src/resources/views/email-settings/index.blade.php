@extends('base::layouts.default')

@section('stylesheet')
    <!-- <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" /> -->
@endsection

<style>
    .serch-btn {
        justify-content: end;
        display: flex;
    }
</style>

@section('header')
    <style>
     .form-section {
            margin-top: 20px;
        }

        .form-section label {
            font-weight: bold;
        }

        .required::after {
            content: " *";
            color: red;
        }

        .form-footer {
            margin-top: 20px;
            text-align: right;
        }

        .video-detail {
            background: #fff;
            box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }
        .timezone-card {
            max-width: 540%;
            margin: 0px auto;
            padding: 19px;
            border-radius: 0px;
            background: #ffffff;
            box-shadow: 0 15px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .timezone-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .required {
            color: red;
        }

        .timezone-select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        /* Focus effect */
        .timezone-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .error-msg {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="GenSettingController as genSetngCtrl">
       

        
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>General Settings</h4>
            </div>
            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#general" data-toggle="tab">Email Server Settings</a></li>
                    <li><a href="#payment" data-toggle="tab">Payment Settings</a></li>
                    <li><a href="#tenant" data-toggle="tab">Multi Tenant Settings</a></li>
                </ul>

                <div class="tab-content video-detail form-page">
                    <!-- Email Server Settings Tab -->
                    <div class="tab-pane fade in active" id="general">
                        <form class="form-section">
                            <div class="row ">
                                <!-- Left Column -->
                                <div class="col-md-6"
                                    data-ng-repeat="record in genSetngCtrl.emailSettingList track by $index">
                                    <div class="form-group">
                                        <label class="required" ng-model="record.key">@{{ record.key }}
                                        </label>
                                        <!-- Normal Input -->
                                        <div class="input-group col-lg-10" ng-if="record.key == 'password'">
                                            <div class="col-sm-7">
                                                <input type="@{{ record.showPassword ? 'text' : 'password' }}"
                                                    class="form-control" id="paswd" placeholder="Enter password"
                                                    ng-model="record.value">
                                            </div>

                                            <div class="input-group-append col-sm-4">
                                                <!-- Toggle visibility -->
                                                <button type="button" class="btn btn-outline-secondary"
                                                    ng-click="record.showPassword = !record.showPassword">
                                                    <i class="fa"
                                                        ng-class="record.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>

                                                <div class="tooltip-parent">
                                                    <!-- Copy -->
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        ng-click="genSetngCtrl.copyToClipboard(record.value)">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                    <span class="tooltip_title" id="tooltip">Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input ng-if="record.key != 'password'" type="text" class="form-control"
                                            placeholder="Specify site name" ng-model="record.value">
                                    </div>
                                </div>
                                <!-- Footer buttons -->
                                <div class="form-footer">
                                    <button type="button" class="button button-gray"
                                        ng-click="genSetngCtrl.cancelTenantSetting($event)">Cancel
                                    </button>
                                    <button type="button" class="button button-blue"
                                        data-ng-if="checkAccess('general_settings.create')"
                                        ng-click="genSetngCtrl.saveEmailSetting(genSetngCtrl.emailSettingList)">Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                                     
                    <div class="tab-pane fade in" id="payment">
                        <!-- <div class="form-group row" style="margin-bottom: 15px;">
                            <label for="timezone" class="col-sm-2 control-label"
                                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Timezone*:</strong></label>
                            <div class="col-sm-8">
                                <select name="timezone" id="timezone_select"
                                    ng-model="genSetngCtrl.deviceData.timezone"
                                    ng-options="tz for tz in genSetngCtrl.tzList" class="form-control"
                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <option value=""  selected>Select Timezone</option>
                                </select>
                                <p class="error-msg">
                                    @{{ errors.timezone.message }}
                                </p>
                            </div>
                        </div> -->

                        <div class="timezone-card">
                            <div class="form-group">
                                <label for="timezone" class="timezone-label">
                                    Timezone <span class="required"></span>
                                </label>

                                <select name="timezone" id="timezone_select"
                                    ng-model="genSetngCtrl.deviceData.timezone"
                                    ng-options="tz for tz in genSetngCtrl.tzList"
                                    class="timezone-select">
                                    
                                    <option value="">Select Timezone</option>
                                </select>

                                <p class="error-msg">
                                    @{{ errors.timezone.message }}
                                </p>
                            </div>
                        </div>

                        <form class="form-section">
                            <div class="row">
                                <!-- Loop through settings -->
                                <div class="col-md-6"
                                    data-ng-repeat="record in genSetngCtrl.paymentSettingList track by $index">

                                    <!-- String + Checkbox together -->
                                    <div class="form-group"
                                        ng-if="record.key == 'max_activation_length' || record.key == 'void_payment_in' || record.key == 'expired_voucher_removal' || record.key == 'link_code_expiration'">
                                        <label class="required">@{{ record.key }}</label>

                                        <div style="display: flex; align-items: center; gap: 4%;">
                                            <div>
                                                <input type="text" class="form-control mr-2"
                                                    placeholder="Enter number of days" ng-model="record.value"
                                                    ng-disabled="record.value == true">
                                            </div>

                                            <div>
                                                <label class="mr-3 mb-0"
                                                    data-ng-if="record.key == 'max_activation_length' || record.key == 'link_code_expiration' || record.key == 'expired_voucher_removal'">Days</label>
                                                <label class="mr-3 mb-0"
                                                    data-ng-if="record.key == 'void_payment_in'">Hours</label>
                                            </div>

                                            <div ng-if="record.key == 'max_activation_length'">
                                                <input type="checkbox" class="mr-1" ng-model="record.value"
                                                    ng-checked="record.value =='true'">
                                                <label class="mb-0">Unlimited</label>
                                            </div>

                                            <div ng-if="record.key == 'void_payment_in'">
                                                <input type="checkbox" class="mr-1" ng-model="record.value"
                                                    ng-checked="record.value =='true'">
                                                <label class="mb-0">Disallow Void</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- String + Radio together -->
                                    <div class="form-group" ng-if="record.type == 'radio'">
                                        <label class="required">@{{ record.key }}</label>

                                        <div style="display: flex; align-items: center; gap: 4%;">

                                            <div ng-if="record.key !== 'subscription_activation'">
                                                <input type="radio" value='true' id="allow-@{{ $index }}" class="mr-1"
                                                    ng-model="record.value">
                                                <label class="mb-0" for="allow-@{{ $index }}">Allow</label>

                                                <input type="radio" value='false' id="disallow-@{{ $index }}" class="mr-1"
                                                    ng-model="record.value">
                                                <label class="mb-0" for="disallow-@{{ $index }}">Disallow</label>
                                            </div>

                                            <div ng-if="record.key == 'subscription_activation'">
                                                <input type="radio" value='true' id="acceptance-@{{ $index }}" class="mr-1"
                                                    ng-model="record.value">
                                                <label class="mb-0" for="acceptance-@{{ $index }}">Acceptance</label>

                                                <input type="radio" value='false' id="payment-@{{ $index }}" class="mr-1"
                                                    ng-model="record.value">
                                                <label class="mb-0" for="payment-@{{ $index }}">Payment</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Other string-only inputs -->
                                    <div class="form-group"
                                        ng-if="record.type == 'string' && record.key != 'max_activation_length' && record.key != 'void_payment_in' && record.key != 'expired_voucher_removal' && record.key != 'link_code_expiration'">
                                        <label class="required">@{{ record.key }}</label>
                                        <input type="text" class="form-control" ng-model="record.value">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer buttons -->
                            <div class="form-footer">
                                <button type="button" class="button button-gray"
                                    ng-click="genSetngCtrl.cancelTenantSetting($event)">Cancel</button>
                                <button type="button" class="button button-blue"
                                    data-ng-if="checkAccess('general_settings.create')"
                                    ng-click="genSetngCtrl.savePaymentSetting(genSetngCtrl.paymentSettingList)">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!---- Multi Tenant settings ---->
                    <div class="tab-pane fade in" id="tenant">
                        <form class="form-section">
                            <div class="row">

                                <!--- Tenant Setting -->
                                <div class="col-md-12">
                                    <div class="page-heading flexbox align-items-center flex-wrap">
                                        <h4>Multi Tenant Settings</h4>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Multi Tenant Mode </label>
                                        <div>
                                            <label class="switch">
                                                <input type="checkbox" id="tenant-mode" name="multi-tenant-mode"
                                                    ng-checked="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!--- Guest Mode -->
                                <div class="col-md-12">
                                    <div class="page-heading flexbox align-items-center flex-wrap">
                                        <h4>Login Options</h4>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Guest Mode </label>
                                        <div>
                                            <label class="switch">
                                                <input type="checkbox" id="guest-mode"
                                                    ng-checked="genSetngCtrl.tenantData.guest_mode == 1"
                                                    ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!--- Guest Organization -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Guest Organization </label>
                                        <select name="guest_organization" id="guest_orgs"
                                            ng-model="genSetngCtrl.tenantData.guest_organization"
                                            ng-options="org.id as org.organization_name for org in genSetngCtrl.orgList"
                                            class="form-control"
                                            ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="" disabled selected>Select Organization</option>
                                        </select>
                                    </div>
                                </div>

                                <!--- Guest Subscription -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Guest Subscription </label>
                                        <select name="guest_subscription" id="guest_subs"
                                            ng-model="genSetngCtrl.tenantData.guest_subscription"
                                            ng-options="subs.id as subs.name for subs in genSetngCtrl.subsList"
                                            class="form-control"
                                            ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="" disabled selected>Select Subscription</option>
                                        </select>
                                    </div>
                                </div>

                                <!--- In App Registration -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">In-App Registration </label>
                                        <div>
                                            <label class="switch">
                                                <input type="checkbox" id="in-app-registration"
                                                    ng-checked="genSetngCtrl.tenantData.in_app_registration == 1"
                                                    ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!--- Default Organization -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Default Organization </label>
                                        <select name="default_organization" id="default_orgs"
                                            ng-model="genSetngCtrl.tenantData.default_organization"
                                            ng-options="org.id as org.organization_name for org in genSetngCtrl.orgList"
                                            class="form-control"
                                            ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="" disabled selected>Select Organization</option>
                                        </select>
                                    </div>
                                </div>

                                <!--- Default Subscription -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Default Subscription </label>
                                        <select name="default_subscription" id="defualt_subscription"
                                            ng-model="genSetngCtrl.tenantData.default_subscription"
                                            ng-options="subs.id as subs.name for subs in genSetngCtrl.subsList"
                                            class="form-control"
                                            ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="" disabled selected>Select Subscription</option>
                                        </select>
                                    </div>
                                </div>

                                <!--- Code Expiration Time -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Code Expiration Time </label>
                                        <div>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control" placeholder="Enter time"
                                                    ng-model="genSetngCtrl.tenantData.code_expiration_time"
                                                    ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1">
                                            </div>

                                            <div class="col-sm-2">
                                                <select name="code_expiration_time_type" id="time_type"
                                                    ng-model="genSetngCtrl.tenantData.code_expiration_time_type"
                                                    ng-disabled="genSetngCtrl.tenantData.multi_tenant_mode == 1" 
                                                    ng-options="tz for tz in deviceCtrl.tzList" class="form-control"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                    <option value="" disabled selected>Select Time Type</option>
                                                    <option value="hours">Hours</option>
                                                    <option value="minutes">Minutes</option>
                                                    <option value="days">Days</option>
                                                    <option value="weeks">Week</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer buttons -->
                            <div class="form-footer">
                                <button type="button" class="button button-gray"
                                    data-ng-if="checkAccess('tenant_settings.edit')"
                                    ng-click="genSetngCtrl.cancelTenantSetting($event)">Cancel</button>
                                <button type="button" class="button button-blue"
                                    data-ng-if="checkAccess('tenant_settings.edit')"
                                    ng-click="genSetngCtrl.saveTenantSetting(genSetngCtrl.tenantData)">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
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
    <script src="{{ asset('adminview/assets/js/settings/emailSetting.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
    <!-- <script src="{{ asset('adminview/assets/js/devices/devices.js') }}"></script> -->
@endsection