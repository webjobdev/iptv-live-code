<form method="POST" enctype="multipart/form-data" data-base-validator
    data-ng-submit="deviceCtrl.saveMultipleDevices($event)">
    {!! csrf_field() !!}
    <input type="hidden" id="device-id" name="id" value="{{ request()->id }}">

    <!-- Device Redirect -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                Redirect*:</strong></label>
        <div class="col-sm-10">
            <input type="checkbox" name="device_redirect" id="device_redirect"
                ng-model="deviceCtrl.deviceData.device_redirect">
        </div>
    </div>

    <!-- organization -->
    <div class="form-group row" style="margin-bottom: 15px; height: 100px;">
        <label class="col-xs-12 col-sm-2 control-label" for="organization"
            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Assigned
                Organization*:</strong></label>
        <div class="col-sm-9">
            {{-- <input type="text" id="selectedOrgsInput" class="form-control" placeholder="Selected Orgs" readonly
                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; margin:10px 10px;">
            --}}
            <select name="organization" id="organization_optns" data-jquery="select2_custom_ddl"
                myPlaceholder="  Select Organizations" myValue="deviceCtrl.deviceData.organization"
                ng-model="deviceCtrl.deviceData.organization"
                ng-options="rule.organization_name for rule in deviceCtrl.orgList" class="form-control" multiple
                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; margin:10px 10px;">
                <option value="" disabled>Select Organization</option>
            </select>
        </div>
    </div>

    <!-- Create Subscribers -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Create
                Subscribers*:</strong></label>
        <div class="col-sm-10">
            <input type="checkbox" name="create_subscribers" id="create_subscribers"
                ng-model="deviceCtrl.deviceData.create_subscribers">
        </div>
    </div>

    <!-- Upload List -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Upload
                List*:</strong></label>
        <div class="col-sm-10 upload-list-input" style="display: flex; gap: 15px; align-items: center;">
            <input type="checkbox" name="upload_list" id="upload_list" ng-model="deviceCtrl.deviceData.upload_list">

            <input type="text" class="form-control" readonly ng-model="deviceCtrl.deviceData.list_file" name="list_file"
                id="list_file_inpt"
                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                data-ng-if="deviceCtrl.deviceData.upload_list == true">

            <label for="browse_list" style="margin-bottom: 0px;" data-ng-if="deviceCtrl.deviceData.upload_list == true">
                <svg fill="#000000" width="20px" height="15px" viewBox="0 -1 26 26" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m.061 8.78 4.801 14.399c.164.481.611.82 1.138.821h19.199c.048 0 .095-.003.141-.009l-.006.001.036-.006c.033-.005.066-.01.094-.018l.038-.009c.031-.008.062-.018.094-.028l.036-.013c.031-.012.061-.025.094-.041l.029-.014c.044-.023.08-.044.114-.068l-.004.003c.04-.027.074-.053.106-.081l-.002.001.028-.025c.023-.021.045-.042.067-.064l.031-.034c.021-.023.04-.047.058-.072l.017-.021.008-.012q.034-.048.063-.1l.007-.011c.019-.033.038-.073.055-.115l.003-.008.007-.019q.021-.052.036-.106c0-.012.007-.024.009-.037.008-.03.014-.06.02-.094 0-.015.005-.03.007-.045s.006-.057.008-.085 0-.034 0-.051 0-.015 0-.023v-21.596c0-.661-.534-1.197-1.193-1.2h-9.6c-.663 0-1.2.537-1.2 1.2v1.2h-8.4c-.663 0-1.2.537-1.2 1.2v3.6h-3.599-.001c-.662 0-1.199.537-1.199 1.199 0 .135.022.266.064.387l-.003-.008zm23.938-6.38v13.003l-2.462-7.385c-.164-.481-.611-.821-1.138-.821h-13.199v-2.397h8.4c.663 0 1.2-.537 1.2-1.2v-1.199zm-21.134 7.2h16.67l4 12h-16.67z" />
                </svg>
                Browse
            </label>
            <input type="file" name="list" id="browse_list" style="display: none;">
            <div data-ng-if="deviceCtrl.deviceData.upload_list == true">
                {{-- <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.seperator" name="seperator"
                    id="name" placeholder="/s"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                --}}
                <svg fill="#000000" width="25px" height="25px" viewBox="0 0 256 256" id="Flat"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M128.00146,23.99963a104,104,0,1,0,104,104A104.11791,104.11791,0,0,0,128.00146,23.99963ZM128.002,192a12,12,0,1,1,12-12A12,12,0,0,1,128.002,192Zm7.99951-48.891v.89551a8,8,0,1,1-16,0v-8a8.0004,8.0004,0,0,1,8-8,20,20,0,1,0-20-20,8,8,0,0,1-16,0,36,36,0,1,1,44,35.10449Z" />
                </svg>
            </div>
        </div>
    </div>

    <!--- upload file data --->
    <div class="form-group row" id="device_data" style="display: none">
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong></strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px; justify-content: space-between;">
                <label class="col-sm-2 control-label"
                    style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>Serial</strong></label>
                <label class="col-sm-2 control-label"
                    style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>MAC</strong></label>
            </div>
        </div>

        <div class="form-group row" style="margin-bottom: 15px;"
            ng-repeat="data in deviceCtrl.deviceData.serial track by $index">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    @{{ $index + 1 }}*:</strong>
            </label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[$index]"
                    name="device$index" id="name"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[$index]" name="device$index"
                    id="name" placeholder="{{ trans('devices::index.mac_placeholder') }}"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
    </div>

    <!--- Individual Devices --->
    <div class="form-group row" id="devices_data" data-ng-if="!deviceCtrl.deviceData.upload_list == true">
        <!-- headings -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong></strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px; justify-content: space-between;">
                <label class="col-sm-2 control-label"
                    style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>Serial</strong></label>
                <label class="col-sm-2 control-label"
                    style="font-size: 14px; color: #000; margin-top: 10px; margin-right: 39%;"><strong>MAC</strong></label>
            </div>
        </div>

        <!-- Device 1 -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    1*:</strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[0]" name="device1"
                    id="name" placeholder="Enter Serial No"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[0]" name="device1" id="name"
                    placeholder="{{ trans('devices::index.mac_placeholder') }}"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>

        <!-- Device 2 -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    2*:</strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[1]" name="device2"
                    id="name" placeholder="Enter Serial No"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[1]" name="device2" id="name"
                    placeholder="{{ trans('devices::index.mac_placeholder') }}"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <!-- Device 3 -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    3*:</strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[2]" name="device3"
                    id="name" placeholder="Enter Serial No"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[2]" name="device3" id="name"
                    placeholder="{{ trans('devices::index.mac_placeholder') }}"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>

        <!-- Device 4 -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    4*:</strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.serial[3]" name="device4"
                    id="name" placeholder="Enter Serial No"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.mac[3]" name="device4" id="name"
                    placeholder="{{ trans('devices::index.mac_placeholder') }}"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>

        <!-- total devices -->
        <div class="form-group row" style="margin-bottom: 15px;">
            <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Total
                    No of
                    Devices*:</strong></label>
            <div class="col-sm-10" style="display: flex; gap:10px;">
                <label class="col-sm-2 control-label"
                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>4</strong></label>
            </div>
        </div>
    </div>

    <!-- First Value -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">First
            Value*:</label>

        <div class="col-sm-10" style="display: flex; align-items:center; justify-content: flex-start;">
            <div class="col-sm-5">
                <input type="radio" ng-model="deviceCtrl.deviceData.firstValue" name="firstValue" id="serial"
                    value="Serial"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <label for="serial"><strong>Serial</strong></label>
            </div>

            <div class="col-sm-5">
                <input type="radio" ng-model="deviceCtrl.deviceData.firstValue" name="firstValue" id="mac" value="MAC"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <label for="mac"><strong>MAC</strong></label>
            </div>
        </div>
    </div>

    <!-- Serial & Mac Seperator -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Serial &
                Mac
                Seperator*:</strong></label>
        <div class="col-sm-10" style="display: flex; gap:10px;">
            <input type="text" class="form-control" ng-model="deviceCtrl.deviceData.seperator" name="seperator"
                id="name" placeholder="/s"
                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            <svg fill="#000000" width="30px" height="30px" viewBox="0 0 256 256" id="Flat"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M128.00146,23.99963a104,104,0,1,0,104,104A104.11791,104.11791,0,0,0,128.00146,23.99963ZM128.002,192a12,12,0,1,1,12-12A12,12,0,0,1,128.002,192Zm7.99951-48.891v.89551a8,8,0,1,1-16,0v-8a8.0004,8.0004,0,0,1,8-8,20,20,0,1,0-20-20,8,8,0,0,1-16,0,36,36,0,1,1,44,35.10449Z" />
            </svg>
        </div>
    </div>

    <!-- Parse File -->
    <div class="form-group row" style="margin-bottom: 15px; border-radius:150px;">
        <label for="parse-file" class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
            <strong>Parse File*:</strong>
        </label>
        <div class="parse-label row">
            <!-- Input to display file name -->
            {{-- <div class="col-sm-10" style="padding-left: 0px">
                <input type="text" class="form-control" name="parse-file-inpt" id="parse-file-name"
                    placeholder="No file chosen" ng-model="deviceCtrl.deviceData.selectedFileName" readonly
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div> --}}
            <!-- Parse button styled like input -->
            <div class="col-sm-3" style="margin-top: 10px;">
                <button type="button" ng-click="deviceCtrl.parseFile(event)" style="background-color: white;">
                    <label for="parse-file-inpt" style="width: 100%">
                        <div class="input-group"
                            style="border: 2px solid rgba(128,130,133,0.36); border-radius: 20px; padding: 2px 12px;">
                            {{-- ng-click="deviceCtrl.triggerFileInput()"> --}}

                            <!-- Icon -->
                            <span class="input-group-addon" style="background: transparent; border: none;">
                                <svg fill="#000000" width="20px" height="20px" viewBox="-2 -2 24 24"
                                    xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin"
                                    class="jam jam-arrow-circle-up">
                                    <path
                                        d="M11 8.414V14a1 1 0 0 1-2 0V8.414L6.464 10.95A1 1 0 1 1 5.05 9.536l4.243-4.243a.997.997 0 0 1 1.414 0l4.243 4.243a1 1 0 1 1-1.414 1.414L11 8.414zM10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z" />
                                </svg>
                            </span>

                            <!-- Label inside styled input -->
                            <span style="flex: 1; font-weight: 500;">Parse</span>
                        </div>
                    </label>
                </button>

                <!-- Hidden actual file input -->
                {{-- <input type="file" id="parse-file-inpt" class="hidden-file-input" style="display: none;" /> --}}
            </div>
        </div>
    </div>

    <!-- identifiers -->
    <div class="form-group row" id="device_data" style="display: none;">
        <div class="form-group row" style="margin-bottom: 15px;"
            ng-repeat="iden in deviceCtrl.deviceData.serial track by $index">
            <label for="identifier" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier
                    @{{ $index + 1 }}*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="identifier" id="identifier_inpt[0]"
                    placeholder="Type Identifier of device @{{ $index + 1 }}"
                    ng-model="deviceCtrl.deviceData.identifier[$index]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        {{-- <div class="form-group row" style="margin-bottom: 15px;">
            <label for="identifier" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier
                    2*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="identifier" id="identifier_inpt[1]"
                    placeholder="{{ trans('devices::index.identifier1') }}"
                    ng-model="deviceCtrl.deviceData.identifier[1]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="identifier" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier
                    3*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="identifier" id="identifier_inpt[2]"
                    placeholder="{{ trans('devices::index.identifier2') }}"
                    ng-model="deviceCtrl.deviceData.identifier[2]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="identifier" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Identifier
                    4*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="identifier" id="identifier_inpt[3]"
                    placeholder="{{ trans('devices::index.identifier3') }}"
                    ng-model="deviceCtrl.deviceData.identifier[3]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div> --}}
    </div>

    <!-- device models -->
    <div class="form-group row" id="device_data" style="display: none;">
        <div class="form-group row" style="margin-bottom: 15px;"
            ng-repeat="devc in deviceCtrl.deviceData.serial track by $index">
            <label for="device_model" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    Model @{{ $index + 1 }}*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="device_model" id="name"
                    placeholder="Type model of device @{{ $index + 1 }}"
                    ng-model="deviceCtrl.deviceData.device_model[$index]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        {{-- <div class="form-group row" style="margin-bottom: 15px;">
            <label for="device_model" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    Model 2*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="device_model" id="name"
                    placeholder="{{ trans('devices::index.device_model1') }}"
                    ng-model="deviceCtrl.deviceData.device_model[1]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="device_model" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    Model 3*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="device_model" id="name"
                    placeholder="{{ trans('devices::index.device_model2') }}"
                    ng-model="deviceCtrl.deviceData.device_model[2]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>

        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="device_model" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Device
                    Model 4*:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="device_model" id="name"
                    placeholder="{{ trans('devices::index.device_model3') }}"
                    ng-model="deviceCtrl.deviceData.device_model[3]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>

        </div> --}}
    </div>

    <!-- firmware versions -->
    <div class="form-group row" id="device_data" style="display: none;">
        <div class="form-group row" style="margin-bottom: 15px;"
            ng-repeat="firm in deviceCtrl.deviceData.serial track by $index">
            <label for="firmware_version" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                    Version @{{ $index + 1 }}:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="firmware_version" id="name"
                    placeholder="Type firmware version of device @{{ $index + 1 }}"
                    ng-model="deviceCtrl.deviceData.firmware_version[$index]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        {{-- <div class="form-group row" style="margin-bottom: 15px;">
            <label for="firmware_version" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                    Version 2:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="firmware_version" id="name"
                    placeholder="{{ trans('devices::index.firmware_version1') }}"
                    ng-model="deviceCtrl.deviceData.firmware_version[1]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="firmware_version" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                    Version 3:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="firmware_version" id="name"
                    placeholder="{{ trans('devices::index.firmware_version2') }}"
                    ng-model="deviceCtrl.deviceData.firmware_version[2]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="firmware_version" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Firmware
                    Version 4:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="firmware_version" id="name"
                    placeholder="{{ trans('devices::index.firmware_version3') }}"
                    ng-model="deviceCtrl.deviceData.firmware_version[3]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div> --}}
    </div>

    <!-- IP addresses -->
    <div class="form-group row" id="device_data" style="display: none;">
        <div class="form-group row" style="margin-bottom: 15px;"
            ng-repeat="identfr in deviceCtrl.deviceData.serial track by $index">
            <label for="ip_address" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                    Address @{{ $index + 1 }}:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ip_address" id="name"
                    placeholder="Type IP Address of device @{{ $index + 1 }}"
                    ng-model="deviceCtrl.deviceData.ip_address[$index]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        {{-- <div class="form-group row" style="margin-bottom: 15px;">
            <label for="ip_address" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                    Address 2:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ip_address" id="name"
                    placeholder="{{ trans('devices::index.ip_address1') }}"
                    ng-model="deviceCtrl.deviceData.ip_address[1]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="ip_address" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                    Address 3:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ip_address" id="name"
                    placeholder="{{ trans('devices::index.ip_address2') }}"
                    ng-model="deviceCtrl.deviceData.ip_address[2]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 15px;">
            <label for="ip_address" class="col-sm-2 control-label"
                style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                    Address 4:</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ip_address" id="name"
                    placeholder="{{ trans('devices::index.ip_address3') }}"
                    ng-model="deviceCtrl.deviceData.ip_address[3]"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
            </div>
        </div> --}}
    </div>

    <!-- ISP -->
    <div class="form-group row" style="margin-bottom: 15px;">
        <label for="isp" class="col-sm-2 control-label"
            style="font-size: 14px; color: #000; margin-top: 10px;">ISP:</strong></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="isp" id="name" placeholder="{{ trans('devices::index.isp') }}"
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
                placeholder="{{ trans('devices::index.location') }}" ng-model="deviceCtrl.deviceData.location"
                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        </div>
    </div>

    <!-- button group -->
    <div class="form-group text-center">
        <button type="submit" class="button button-blue" id="deviceadd">
            <strong>Add Devices</strong>
        </button>&nbsp;&nbsp;
        {{-- <button type="submit" data-ng-if="isEditMode" class="btn btn-success" id="deviceedit">
            <strong>Update</strong>
        </button>&nbsp;&nbsp; --}}
        <button type="button" class="button button-red" onclick="deleteApiUser({{ request()->id }})">
            <strong>Remove</strong>
        </button>&nbsp;&nbsp;
        <button type="button" class="button button-gray" ng-click="cancelDevice()">
            <strong>Cancel</strong>
        </button>
    </div>
</form>

<script>
    const listFileInput = document.getElementById('browse_list');
    listFileInput.addEventListener('change', (event) => {
        const listFile = event.target.files[0];

        if (listFile) {
            const fileName = listFile.name;
            const input = document.getElementById('list_file_inpt');
            input.value = fileName;
        }
    });
</script>