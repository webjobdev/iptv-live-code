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

        .parse-input {
            width: 60%;
            background-color: #fff;
            height: 4%;
            gap: 3px;
            border: 2px solid rgba(128, 130, 133, 0.36);
            border-radius: 16px;
        }

        .parse-input svg {
            margin-left: 10px;
        }

        .parse-input label {
            margin-bottom: 0px;
            margin-left: 10px;
        }

        .parse-label {
            display: flex;
            /* justify-content: space-between; */
            align-items: center;
            gap: 2%;
        }

        .hidden-file-input {
            position: absolute;
            left: -9999px;
            pointer-events: none;
        }

        .upload-list-input label {
            width: 25%;
            background-color: #01badf;
            border: 2px solid rgba(128, 130, 133, 0.36);
            border-radius: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 90%;
            padding: 8px 10px;
        }

        .input-group {
            border: 2px solid rgba(128, 130, 133, 0.36);
            border-radius: 20px;
            padding: 1px 9px;
            height: auto;
            display: flex;
            align-items: center;
            gap: 10px
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="DeviceController as deviceCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>
                    Device
                </h4>
            </div>

            <nav class="nav nav-pills" style="margin: 1rem 0rem 1rem 0rem;">
                <li ng-class="{ 'active': deviceCtrl.btnNo == 0 }" style="margin-right: 0.5rem;">
                    <a href="" style="" class="btn btn-default" ng-click="deviceCtrl.btnNo=0">
                        <input type="hidden" name="id" value="0">
                        Add Device
                    </a>
                </li>

                <li ng-class="{ 'active': deviceCtrl.btnNo == 1 }" style="margin-right: 0.5rem;">
                    <a href="" style="" class="btn btn-default" ng-click="deviceCtrl.btnNo=1">
                        <input type="hidden" name="id" value="1">
                        Add Multiple Devices
                    </a>
                </li>
            </nav>

            <br>
            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;" ng-if="deviceCtrl.btnNo == 0">
                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="deviceCtrl.saveMultipleDevices($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="device-id" name="id" value="{{ request()->id }}">

                            <!-- mac address -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="mac_add" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>MAC
                                        Address*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[0]"
                                        name="mac_add" placeholder="{{ trans('devices::index.mac_placeholder') }}" id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.mac_add.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- serial number -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="serial_no" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Serial
                                        Number*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[0]"
                                        name="serial_no" id="name" placeholder="{{ trans('devices::index.srno') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.serial_no.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- Device Redirect -->
                            <div class="form-group row" style="margin-bottom: 15px; align-items: center; display: flex;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                                        Redirect*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="device_redirect" id="device_redirect"
                                        ng-model="deviceCtrl.deviceData.device_redirect">
                                </div>
                            </div>

                            <!-- identifier -->
                            <div class="form-group row" style="margin-bottom: 15px">
                                <label for="identifier" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier*:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.identifier"
                                        name="identifier" id="identifier_inpt"
                                        placeholder="{{ trans('devices::index.identfr') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.identifier.message }}
                                    </p>
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
                                    <p class="error-msg">
                                        @{{ errors.timezone.message }}
                                    </p>
                                </div>
                                <input type="checkbox" name="based_on_ip" id="based_on_ip_check"
                                    ng-model="deviceCtrl.deviceData.based_on_ip" onclick="getTimezoneOnIp()">
                                <label for="basd_on_ip" style="font-size: 14px; color: #2c2c2c; margin-top: 10px;">Based
                                    On IP</label>
                            </div>

                            <!-- organization -->
                            <div class="form-group row" style="margin-bottom: 15px; height: 13vh;">
                                <label class="col-xs-12 col-sm-2 control-label" for="organization"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Assigned
                                        Organization*:</strong></label>
                                <div class="col-sm-9 col-xs-12 col-sm-offset-1">
                                    <select name="organization" id="organization-optns" data-jquery="select2_custom_ddl"
                                        myPlaceholder=" Select Organizations" ng-model="deviceCtrl.deviceData.organization"
                                        ng-options="rule.organization_name for rule in deviceCtrl.orgList"
                                        class="form-control" multiple
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Organization</option>
                                    </select>
                                </div>
                            </div>
                            <p class="error-msg">
                                @{{ errors.organization.message }}
                            </p>

                            <!-- Security Code -->
                            <div class="form-group row" style="margin-bottom: 15px;  display: flex; align-items: center;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Security
                                        Code*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="security_code_req" id="security_code_req"
                                        ng-model="deviceCtrl.deviceData.security_code_req">
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
                            <div class="form-group row" style="margin-bottom: 15px; height: 13vh;">
                                <label for="assigned_to" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Assigned
                                        To:</strong></label>
                                <div class="col-sm-10">
                                    {{-- <input type="text" id="selected-subs-input" class="form-control"
                                        placeholder="Selected Subscribers" readonly
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; margin:10px 10px;">
                                    --}}
                                    <select name="subscribers" id="subscriber-optns" data-jquery="select2_custom_ddl"
                                        myPlaceholder=" Select Subscribers" ng-model="deviceCtrl.deviceData.subscribers"
                                        ng-options='rule.id as (rule.first_name + " " + rule.last_name) for rule in deviceCtrl.subsList'
                                        class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Subscribers</option>
                                    </select>
                                </div>
                            </div>
                            <p class="error-msg">
                                @{{ errors.subscribers.message }}
                            </p>

                            <!-- device model -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="device_model" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                                        Model:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="device_model" id="name"
                                        placeholder="{{ trans('devices::index.device_mdl') }}"
                                        ng-model="deviceCtrl.deviceData.device_model[0]"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.brand_model.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- firmware version -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="firmware_version" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                                        Version:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="firmware_version" id="name"
                                        placeholder="{{ trans('devices::index.firmware_versn') }}"
                                        ng-model="deviceCtrl.deviceData.firmware_version[0]"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.firmware_version.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- IP address -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="ip_address" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                                        Address:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="ip_address" id="name"
                                        placeholder="{{ trans('devices::index.ip_add') }}"
                                        ng-model="deviceCtrl.deviceData.ip_address[0]"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.ip_address.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- ISP -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="isp" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">ISP:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="isp" id="name"
                                        placeholder="{{ trans('devices::index.isp') }}" ng-model="deviceCtrl.deviceData.isp"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.isp.message }}
                                    </p>
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
                                    <p class="error-msg">
                                        @{{ errors.location.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- status -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="status_toggle" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Status:</strong></label>
                                <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                    <label class="switch">
                                        <input type="checkbox" name="status" id="status_toggle"
                                            ng-model="deviceCtrl.deviceData.status">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                {{-- <div class="col-sm-10">
                                    <input type="text" class="form-control" value="true" readonly name="status" id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div> --}}
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="deviceadd">
                                    <strong>Add</strong>
                                    {{-- <span class="btn-loader d-none">
                                        <i class="fa fa-spinner fa-spin"></i> Processing...
                                    </span> --}}
                                </button>&nbsp;&nbsp;
                                {{-- <button type="submit" data-ng-if="isEditMode" class="btn btn-success" id="deviceedit">
                                    <strong>Update</strong>
                                </button>&nbsp;&nbsp; --}}
                                <button type="button" class="button button-red"
                                    onclick="deleteApiUser({{ request()->id }})">
                                    <strong>Remove</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="button button-gray" ng-click="cancelDevice()">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mx-auto" style="width:70%; margin-left: 10rem;" id="create-form"
                        ng-if="deviceCtrl.btnNo == 1">
                        @include('devices::create-multi')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // get selected org options
        const selectOrg = document.getElementById('organization-optns');
        const selectedOrgsInput = document.getElementById('selected-option-input');

        selectOrg.addEventListener('change', () => {
            const selectedOptions = Array.from(selectOrg.options)
                .filter(opt => opt.selected)
                .map(opt => opt.text);

            selectedOrgsInput.value = selectedOptions.join(', ');
        });

        // get selected subscribers options
        const selectSubs = document.getElementById('subscriber-optns');
        const selectedSubsInput = document.getElementById('selected-subs-input');

        selectSubs.addEventListener('change', () => {
            const selectedOptions = Array.from(selectSubs.options)
                .filter(opt => opt.selected)
                .map(opt => opt.text);

            selectedSubsInput.value = selectedOptions.join(', ');
        });

        // multiple device script
        // const listFileInput = document.getElementById('browse_list');
        // listFileInput.addEventListener('change', (event) => {
        //     const listFile = event.target.files[0];

        //     if (listFile) {
        //         const fileName = listFile.name;
        //         const input = document.getElementById('list_file_inpt');
        //         input.value = fileName;
        //     }
        // });


        //     // show list file name in input
        //     const listFileInput = document.getElementById('browse_list');
        //     listFileInput.addEventListener('change', (event) => {
        //         const listFile = event.target.files[0];

        //         if (listFile) {
        //             const fileName = listFile.name;
        //             const input = document.getElementById('list_file_inpt');
        //             input.value = fileName;

        //             let reader = new FileReader();

        //             reader.onload = function(e) {
        //                 const text = e.target.result.trim();
        //                 const lines = text.split(/\r?\n/);
        //                 const headers = lines[0].split(',');
        //                 const outputDiv = document.getElementById('device_data');
        //                 outputDiv.innerHTML = '';

        //                 const rowDiv = document.createElement('div');
        //                 rowDiv.classList.add('form-group', 'row');
        //                 rowDiv.style.marginBottom = '15px';
        //                 rowDiv.innerHTML = `
        //                             <label class="col-sm-2 control-label"
        //                                 style="font-size: 14px; color: #000; margin-top: 10px;"><strong></strong></label>
        //                             <div class="col-sm-10"
        //                                 style="display: flex; gap:10px; justify-content: space-between;">
        //                                 <label class="col-sm-2 control-label"
        //                                     style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>${headers[0]}</strong></label>
        //                                 <label class="col-sm-2 control-label"
        //                                     style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>${headers[1]}</strong></label>
        //                             </div>`;
        //                 outputDiv.appendChild(rowDiv);

        //                 for (let i = 1; i < lines.length; i++) {
        //                     const values = lines[i].split(',');
        //                     const serial = values[0];
        //                     const mac = values[1];

        //                     // <!-- Device 1 -->
        //                     //             <div class="form-group row" style="margin-bottom: 15px;">
        //                     //                 <label class="col-sm-2 control-label"
        //                     //                     style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
        //                     //                         1*:</strong></label>
        //                     //                 <div class="col-sm-10" style="display: flex; gap:10px;">
        //                     //                     <input type="text" class="form-control"
        //                     //                         ng-model="deviceCtrl.deviceData.serial[0]" name="device1" id="name"
        //                     //                         style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        //                     //                     <input type="text" class="form-control"
        //                     //                         ng-model="deviceCtrl.deviceData.mac[0]" name="device1" id="name"
        //                     //                         placeholder="{{ trans('devices::index.mac_placeholder') }}"
        //                     //                         style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        //                     //                 </div>
        //                     //             </div>
        //                     const rowDiv = document.createElement('div');
        //                     rowDiv.classList.add('form-group', 'row');
        //                     rowDiv.style.marginBottom = '15px';
        //                     rowDiv.innerHTML = `
        //                             <label class="col-sm-2 control-label"
        //                                 style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
        //                                     ${i}*:</strong>
        //                             </label>
        //                             <div class="col-sm-10" style="display: flex; gap:10px;">
        //                                 <input type="text" class="form-control"
        //                                     ng-model="deviceCtrl.deviceData.serial[${i}]" name="device${i}" id="name" value="${serial}"
        //                                     style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        //                                 <input type="text" class="form-control"
        //                                     ng-model="deviceCtrl.deviceData.mac[${i}]" name="device${i}" id="name" value="${mac}"
        //                                     placeholder="{{ trans('devices::index.mac_placeholder') }}"
        //                                     style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        //                             </div>`;
        //                     outputDiv.appendChild(rowDiv);
        //                 }
        //             }
        //             reader.readAsText(listFile);
        //         }
        //     });
        // });

        // show parse file name in input
        // const parseFileInput = document.getElementById('parse-file-inpt');
        // parseFileInput.addEventListener('change',
        //     (event) => {
        //         const parseFile = event.target.files[0];

        //         if (parseFile) {
        //             const fileName = parseFile.name;
        //             const input = document.getElementById('parse-file-name');
        //             input.value = fileName;
        //         }
        //     });


        // get selected org options
        // const selectOrg = document.getElementById('organization_optns');
        // const selectedOrgsInput = document.getElementById('selectedOrgsInput');

        // selectOrg.addEventListener('change', () => {
        //     const selectedOptions = Array.from(selectOrg.options)
        //         .filter(opt => opt.selected)
        //         .map(opt => opt.text);

        //     selectedOrgsInput.value = selectedOptions.join(', ');
        // });
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