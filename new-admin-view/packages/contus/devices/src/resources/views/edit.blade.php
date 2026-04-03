@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
    <style>
        .nav-pills>li.active>a,
        .nav-pills>li.active>a:hover,
        .nav-pills>li.active>a:focus {
            background-color: #00ACCD;
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="DeviceController as deviceCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    <li><a href="{{ route('divice.index') }}">Back</a></li>
                    <li class="active">Edit Device</li>
                </ol>
            </div>

            <br>
            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="deviceCtrl.updateDevice($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="device-id" name="id" value="{{ request()->id }}">

                            <!-- mac address -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="mac_add" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>MAC
                                        Address*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac_add"
                                        name="mac_add" placeholder="{{ trans('devices::index.mac_placeholder') }}"
                                        id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- serial number -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="serial_no" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Serial
                                        Number*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial_no"
                                        name="serial_no" id="name" placeholder="{{ trans('devices::index.srno') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- Device Redirect -->
                            <div class="form-group row" style="margin-bottom: 15px; align-items: center; display: flex;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                                        Redirect*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="device_redirect" id="device_redirect"
                                        ng-model="deviceCtrl.deviceData.device_redirect"
                                        ng-checked="deviceCtrl.deviceData.device_redirect == '1'">
                                </div>
                            </div>

                            <!-- identifier -->
                            <div class="form-group row" style="margin-bottom: 15px">
                                <label for="identifier" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier*:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.identifier"
                                        name="identifier" id="identifier_inpt"
                                        placeholder="{{ trans('devices::index.identifier') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                                <input type="checkbox" name="identifier_auto" id="ident_auto"
                                    ng-model="deviceCtrl.deviceData.identifier_auto" onclick="autoGenerateIdentifier()">
                                <label for="basd_on_ip"
                                    style="font-size: 14px; color: #2c2c2c; margin-top: 10px;">Auto</label>
                            </div>

                            <!-- timezone -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="timezone" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Timezone*:</strong></label>
                                <div class="col-sm-8">
                                    <select name="timezone" id="timezone_select" ng-model="deviceCtrl.deviceData.timezone"
                                        ng-options="tz for tz in deviceCtrl.tzList" class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="" disabled selected>Select Timezone</option>
                                    </select>
                                </div>
                                <input type="checkbox" name="based_on_ip" id="based_on_ip_check"
                                    ng-model="deviceCtrl.deviceData.based_on_ip" onclick="getTimezoneOnIp()">
                                <label for="basd_on_ip" style="font-size: 14px; color: #2c2c2c; margin-top: 10px;">Based
                                    On IP</label>
                            </div>

                            <!-- organization -->
                            <div class="form-group row" style="margin-bottom: 15px; height: 100px;">
                                <label class="col-xs-12 col-sm-2 control-label" for="organization"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Assigned
                                        Organization*:</strong></label>
                                <div class="col-sm-9 col-xs-12">
                                    {{-- <input type="text" id="selected-option-input" class="form-control"
                                        placeholder="Selected Orgs" ng-model="deviceCtrl.deviceData.slectedOrgName"
                                        readonly
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; margin:10px 10px;"> --}}
                                    <select name="organization" id="organization_optns" data-jquery="select2_custom_ddl"
                                        myPlaceholder=" Select Organizations"
                                        myValue="deviceCtrl.deviceData.organization_id"
                                        ng-model="deviceCtrl.deviceData.organization_id"
                                        ng-options="rule.id as rule.organization_name for rule in deviceCtrl.orgList"
                                        class="form-control" multiple
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Organization</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Security Code -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Security
                                        Code*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="security_code_req" id="security_code_req"
                                        ng-model="deviceCtrl.deviceData.security_code_req"
                                        ng-checked="deviceCtrl.deviceData.security_code_req == 'true'">
                                </div>
                            </div>

                            <!-- Security Code -->
                            <div class="form-group row" style="margin-bottom: 15px;"
                                ng-if="deviceCtrl.deviceData.security_code_req">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Security
                                        Code*:</strong></label>
                                <div class="col-sm-10">
                                    {{-- <input type="checkbox" name="security_code" id="security_code"
                                        ng-model="deviceCtrl.deviceData.security_code"> --}}
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"
                                            ng-model="deviceCtrl.deviceData.security_code" name="security_code"
                                            id="security_code_input"
                                            placeholder="{{ trans('devices::index.security_code') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                    <input type="checkbox" name="security_auto" id="security_auto_check"
                                        ng-model="deviceCtrl.deviceData.security_auto" onclick="generateSecurityCode()">
                                    <label for="security_auto" name="security_auto" id="security_auto"
                                        style="font-size: 14px; color: #2c2c2c; margin-top: 10px;">Auto</label>
                                </div>
                            </div>

                            <!-- assignet to -->
                            <div class="form-group row" style="margin-bottom: 15px; height: 100px;">
                                <label for="assigned_to" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Assigned
                                        To:</strong></label>
                                <div class="col-sm-10">
                                    <select name="subscribers" id="subscribers" data-jquery="select2_custom_ddl"
                                        myPlaceholder=" Select Subscribers "
                                        myValue="deviceCtrl.deviceData.subscribers_id"
                                        ng-model="deviceCtrl.deviceData.subscribers_id"
                                        ng-options='rule.id as rule.first_name + " " + rule.last_name for rule in deviceCtrl.subsList'
                                        class="form-control" 
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Subscribers</option>
                                    </select>
                                </div>
                            </div>

                            <!-- device model -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="device_model" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                                        Model:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="device_model" id="name"
                                        placeholder="{{ trans('devices::index.device_model') }}"
                                        ng-model="deviceCtrl.deviceData.device_model"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- firmware version -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="firmware_version" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                                        Version:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="firmware_version" id="name"
                                        placeholder="{{ trans('devices::index.firmware_version') }}"
                                        ng-model="deviceCtrl.deviceData.firmware_version"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- IP address -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="ip_address" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                                        Address:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="ip_address" id="name"
                                        placeholder="{{ trans('devices::index.ip_address') }}"
                                        ng-model="deviceCtrl.deviceData.ip_address"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- ISP -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="isp" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">ISP:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="isp" id="name"
                                        placeholder="{{ trans('devices::index.isp') }}"
                                        ng-model="deviceCtrl.deviceData.isp"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- location -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="location" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Location:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="location" id="name"
                                        placeholder="{{ trans('devices::index.location') }}"
                                        ng-model="deviceCtrl.deviceData.location"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- status -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="status_toggle" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Status:</strong></label>
                                <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                    <label class="switch">
                                        <input type="checkbox" name="status" id="status_toggle"
                                            ng-model="deviceCtrl.deviceData.status"
                                            ng-checked="deviceCtrl.deviceData.status == 1" onchange="changeStatus()">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-sm-10 hidden">
                                    <input type="text" class="form-control" value="true" readonly name="status"
                                        id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="deviceupdate">
                                    <strong>Update</strong>
                                </button>&nbsp;&nbsp;
                                {{-- <button type="submit" data-ng-if="isEditMode" class="btn btn-success" id="deviceedit">
                                    <strong>Update</strong>
                                </button>&nbsp;&nbsp; --}}
                                <button type="button" class="button button-red" ng-click="deviceCtrl.removeDevice($event)">
                                    <strong>Remove</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="button button-gray" ng-click="cancelDevice()">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const timezoneJson = `{{ asset('/timezone.json') }}`;

        function changeStatus() {
            console.log(document.getElementById('status_toggle').value);
        }


        // get selected org options
        const selectOrg = document.getElementById('organization_optns');
        const selectedOrgsInput = document.getElementById('selected-option-input');

        selectOrg.addEventListener('change', () => {
            const selectedOptions = Array.from(selectOrg.options)
                .filter(opt => opt.selected)
                .map(opt => opt.text);

            selectedOrgsInput.value = selectedOptions.join(', ');
        });
    </script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/devices/devices.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection
