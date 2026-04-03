@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('stylesheet')
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
    <!-- <link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}"> -->
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <!-- <link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" /> -->
    <!-- <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-datetimepicker.min.css')}}" /> -->
@endsection

<style>
    .form-group {
        margin-bottom: 15px;
    }

    .no-select {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
    }

    /* Base nav tabs styling */
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
        background-color: #f8f9fa !important;
        border: 1px solid #ddd !important;
        border-bottom: 2px solid #00ACCD !important;
        color: #00ACCD !important;
    }

    /* SVG icons should align with text */
    .nav.nav-tabs li a svg {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .nav.nav-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            white-space: nowrap;
            border-bottom: none;
        }

        .nav.nav-tabs li {
            flex: 0 0 auto;
            margin-right: 6px;
        }

        .nav.nav-tabs li a {
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .nav.nav-tabs li.active a {
            border-bottom: 1px solid #00ACCD;
        }
    }

    @media (max-width: 576px) {
        .nav.nav-tabs {
            flex-direction: column;
        }

        .nav.nav-tabs li {
            width: 100%;
            margin: 4px 0;
        }

        .nav.nav-tabs li a {
            width: 100%;
            justify-content: flex-start;
            border-radius: 6px;
        }
    }
</style>

@section('content')
    <div data-ng-controller="AddOrganizationDetailController as adoCtrl">
        <div class="dashboard-page " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>{{ __('organizations::index.organization') }}</h4>
            </div>

            @include('base::layouts.subnav')

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#home" role="tab" data-toggle="tab" style="color: black;">General Settings</a>
                </li>
                <li>
                    <a href="#menu1" role="tab" data-toggle="tab" style="color: black;">Organization Settings</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- General Settings -->
                <div id="home" class="tab-pane fade in active"><br>
                    <h1 class="fs-4 fw-bold" style="font-size: 1.5rem;">Update Organization</h1><br>
                    <div class="row contentpanel">
                        <div class="form-page">
                            <div class="justify-content-center mx-auto" style="width:70%; margin-left: 10rem;">
                                <form method="POST" enctype="multipart/form-data" id="update_organization"
                                    data-base-validator data-ng-submit="adoCtrl.save($event)">

                                    {!! csrf_field() !!}

                                    <input type="hidden" id="org-id" name="id" value="{{ request()->id }}">

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="organization_logo"
                                            class="col-sm-2 text-dark control-label"><strong>Organization
                                                Logo:</strong></label>

                                        <div class="upload-cover-thumbnail flexbox"
                                            data-ng-class="{'has-error': errors.poster_image.has}">
                                            <div class="thumbnail-image">
                                                <div class="image-content">
                                                    <img ng-show="adoCtrl.organizationdetail.organization_logo.length > 0"
                                                        ng-class="{'active': adoCtrl.organizationdetail.organization_logo}"
                                                        class="uploaded_img uploaded_img_@{{ adoCtrl.organizationdetail.id }}"
                                                        alt=""
                                                        ng-src="@{{ adoCtrl.organizationdetail.organization_logo }}" />

                                                    <img ng-show="adoCtrl.organizationdetail.organization_logo.length == 0"
                                                        ng-class="{'active': adoCtrl.organizationdetail.organization_logo}"
                                                        class="uploaded_img uploaded_img_@{{ adoCtrl.organizationdetail.id }}"
                                                        alt="" ng-src="" />
                                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                                        <div class="fileuploadbox">
                                                            <div class="input-append">
                                                                <div class="overlay-content"
                                                                    data-ng-class="{'change-image': adoCtrl.organizationdetail.organization_logo.length > 0}">
                                                                    <svg class="upload_img_ic" viewBox="0 0 27 27"
                                                                        version="1.1" x="0px" y="0px" width="27px"
                                                                        height="27px">
                                                                        <g>
                                                                            <path opacity="0.702"
                                                                                d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                                fill="#ffffff"></path>
                                                                        </g>
                                                                    </svg>
                                                                    <div class="input">
                                                                        <div
                                                                            ng-hide="adoCtrl.organizationdetail.organization_logo.length">
                                                                            <span>Choose Organization Logo</span>
                                                                        </div>
                                                                        <div ng-hide="!adoCtrl.organizationdetail.organization_logo.length"
                                                                            class="ng-hide flexbox align-items-center">
                                                                            <svg class="change_img_ic" x="0px" y="0px"
                                                                                width="13" height="13"
                                                                                viewBox="0 0 528.899 528.899">
                                                                                <g>
                                                                                    <path
                                                                                        d="
                                                                                                                                            M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z
                                                                                                                                            M518.113,63.177l-47.981-47.981
                                                                                                                                            c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                                                                                                                            C532.495,100.753,532.495,77.559,518.113,63.177z
                                                                                                                                            M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                                                                                                                            L27.473,390.597L0.3,512.69z"
                                                                                        fill="#ffffff">
                                                                                    </path>
                                                                                </g>
                                                                            </svg>
                                                                            <span>Choose Organization Logo</span>
                                                                        </div>
                                                                        <input type="file" class="uploadImg" name="image"
                                                                            data-video-index="@{{ adoCtrl.organizationdetail.id }}">
                                                                    </div>
                                                                    </p>
                                                                    <p>( Only jpeg, png files allowed with a minimum
                                                                        dimension
                                                                        of 624x624 )</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="error-msg" data-ng-show="errors.organization_logo.has">
                                                    @{{errors.organization_logo.message}}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="organization_name"
                                            class="col-sm-2 text-dark control-label"><strong>Name*:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationdetail.organization_name"
                                                name="organization_name"
                                                placeholder="{{trans('organizations::index.organization_placeholder')}}"
                                                id="organization_name"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="id" class="col-sm-2 control-label"><strong>Organization
                                                Id:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="id"
                                                ng-model="adoCtrl.organizationdetail.organization_id"
                                                value="{{ request()->id }}" disabled
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="provider_id" class="col-sm-2 control-label">
                                            Organization Provider Id<span class="required">*</span>:
                                        </label>

                                        <div class="col-sm-10 m-auto" style="position: relative;">
                                            <input type="text" class="form-control" name="provider_id"
                                                ng-model="adoCtrl.organizationdetail.provider_id" id="provider_id"
                                                placeholder="Enter Provider id" id="provider_id" readonly
                                                style="border: 2px solid rgba(128, 130, 133, 0.36);  border-radius: 20px;  padding: 0px 0px 0px 9px; height: auto;">

                                            <!-- Copy Icon -->
                                            <span class="glyphicon glyphicon-copy" onclick="copyProviderId()"
                                                data-tooltip="Copied!" id="copyIcon"
                                                style="position: absolute;  right: 30px;  top: 50%;  transform: translateY(-50%);  cursor: pointer;  font-size: 18px;  color: #555;">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="prefix" class="col-sm-2 control-label"><strong>Prefix*:</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationdetail.prefix" name="prefix" id="prefix"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                placeholder="{{ trans('organizations::index.gnr_prefix') }}">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="checkbox" id="prefix-auto" data-id="{{ request()->id }}">
                                            <label for="prefix-auto"><strong>Auto</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"><strong>Select Platform:</strong></label>
                                        @php
                                            $selectedPlatforms = old('select_platform', $organizationDetail->select_platform ?? '[]');
                                            $selectedPlatforms = json_decode($selectedPlatforms, true) ?? [];
                                        @endphp
                                        <div class="col-sm-10">
                                            @foreach (["Stb", "Pc/Lg", "Ios", "tvOS", "Android Mobile", "Samsung Tv", "Web", "Others"] as $platform)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="form-check-input" name="select_platform[]"
                                                        value="{{ $platform }}"
                                                        ng-checked="adoCtrl.organizationdetail.select_platform && adoCtrl.organizationdetail.select_platform.includes('{{ $platform }}')"
                                                        ng-click="adoCtrl.togglePlatform('{{ $platform }}')">
                                                    <label class="form-check-label">{{ $platform }}</label>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"><strong>API Access:</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" ng-model="adoCtrl.organizationdetail.api_access"
                                                name="api_access" id="api_access" onchange="toggleApiAccess()"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="loginToken" class="col-sm-2 control-label"><strong>Login
                                                Token:</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationdetail.login_token" name="login_token"
                                                id="loginToken" disabled
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                placeholder="{{trans('organizations::index.gnr_login_token')}}">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-default" onclick="generateToken()"
                                                id="generateTokenBtn" title="Generate New Token">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="copyToken()"
                                                title="Copy Token">
                                                <i class="glyphicon glyphicon-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label for="apiToken" class="col-sm-2 control-label"><strong>API
                                                Token:</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationdetail.api_token" name="api_token"
                                                id="apiToken" disabled
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                placeholder="{{trans('organizations::index.gnr_api_token')}}">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-default" onclick="copyApiToken()"
                                                title="Copy Token">
                                                <i class="glyphicon glyphicon-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="button button-blue" id="organizationupdate">
                                            <strong>Update</strong>
                                        </button>&nbsp;&nbsp;
                                        <button type="button" class="button button-red"
                                            onclick="deleteOrganization({{ request()->id }})">
                                            <strong>Remove</strong>
                                        </button>&nbsp;&nbsp;
                                        <button type="button" class="button button-gray"
                                            onclick="cancelOrganization({{ request()->id }})">
                                            <strong>Cancel</strong>
                                        </button>&nbsp;&nbsp;
                                        <button type="button" class="button button-blue"
                                            onclick="cloneOrganization({{ request()->id }})">
                                            <strong>Clone</strong>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization Settings -->
                <div id="menu1" class="tab-pane fade"><br>
                    <h3 class="fs-4 fw-bold" style="font-size: 1.5rem;">Organization Settings</h3><br>
                    <div class="row">
                        <div class="justify-content-center mx-auto" style="width:70%; margin-left: 10rem;">
                            <form method="POST" enctype="multipart/form-data" data-base-validator
                                data-ng-submit="adoCtrl.settingsave($event)">
                                {!! csrf_field() !!}
                                <input type="hidden" id="id" name="id" value="{{ request()->id }}">
                                <!-- Default settings are now loaded dynamically into adoCtrl.defaultSettings via JS -->
                                <!-- Max Activation Length: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="max_activation_length" class="col-sm-3 control-label">Max Activation
                                        Length:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationsetting.max_activation_length"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px 0px 0 20px; padding: 0px 9px; height: auto;"
                                                placeholder="{{trans('organizations::index.org_length')}}"
                                                name="max_activation_length" id="max_activation_length">
                                            <span class="input-group-addon"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 0px 20px 20px 0px; padding: 0px 9px; height: auto;">days</span>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center gap-2">
                                        <input type="checkbox" id="unlimited"
                                            ng-model="adoCtrl.organizationsetting.unlimited" name="unlimited"
                                            ng-true-value="1" ng-false-value="0">
                                        <label for="unlimited"
                                            class="form-label fw-bold mb-0">Unlimited</label>&nbsp;&nbsp;&nbsp;

                                        <input type="checkbox" name="max_activation_length_system_default"
                                            ng-model="adoCtrl.organizationsetting.max_activation_length_system_default"
                                            ng-true-value="1" ng-false-value="0" id="use_system_default">
                                        <label for="use-system-default" class="form-label fw-bold mb-0">
                                            Use system default
                                        </label>
                                    </div>
                                </div>

                                <!-- Device Activation Limit: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="device_activation_limit" class="col-sm-3 control-label">Device Activation
                                        Limit:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control"
                                            ng-model="adoCtrl.organizationsetting.device_activation_limit"
                                            placeholder="{{trans('organizations::index.org_limit')}}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            name="device_activation_limit" id="device_activation_limit">
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="activation_limit_use_system_default"
                                                    ng-model="adoCtrl.organizationsetting.device_activation_limit_system_default"
                                                    name="device_activation_limit_system_default" ng-true-value="1"
                                                    ng-false-value="0"> Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Void Payment In: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="void_payment_in" class="col-sm-3 control-label">Void Payment In:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationsetting.void_payment_in"
                                                placeholder="{{trans('organizations::index.org_void')}}"
                                                name="void_payment_in" id="void_payment_in"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px 0px 0 20px; padding: 0px 9px; height: auto;">
                                            <span class="input-group-addon"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 0px 20px 20px 0px; padding: 0px 9px; height: auto;">hours</span>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center gap-2">
                                        <input type="checkbox" id="disallow_void"
                                            ng-model="adoCtrl.organizationsetting.disallow_void" name="disallow_void"
                                            ng-true-value="1" ng-false-value="0">
                                        <label for="disallow_void" class="form-label fw-bold mb-0">Disallow
                                            Void</label>&nbsp;&nbsp;&nbsp;

                                        <input type="checkbox" id="void_payment_use_system_default"
                                            ng-model="adoCtrl.organizationsetting.void_payment_in_system_default"
                                            name="void_payment_in_system_default" ng-true-value="1" ng-false-value="0">
                                        <label for="void-payment-use-system-default" class="form-label fw-bold mb-0">Use
                                            system default</label>
                                    </div>
                                </div>

                                <!-- Custom Charges -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="custom_charges" class="col-sm-3 control-label">Custom Charges:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.custom_charges" name="custom_charges"
                                                id="custom_charges_allow" value="1"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.custom_charges" name="custom_charges"
                                                id="custom_charges_disallow" value="0"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="custom_change_use_system_default"
                                                    name="custom_charges_system_default"
                                                    ng-model="adoCtrl.organizationsetting.custom_charges_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Subscription -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="custom_subscription" class="col-sm-3 control-label">Custom
                                        Subscription:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.custom_subscription"
                                                name="custom_subscription" id="custom_subscription_allow" value="1"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.custom_subscription"
                                                name="custom_subscription" id="custom_subscription" value="0"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="custom_subscription_use_system_default"
                                                    name="custom_subscription_system_default"
                                                    ng-model="adoCtrl.organizationsetting.custom_subscription_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Device Slots -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="device_slots" class="col-sm-3 control-label">Device Slots:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.device_slots" name="device_slots"
                                                id="device_slots_allow" value="1"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.device_slots" name="device_slots"
                                                id="device_slots" value="0"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="device_slot_use_system_default"
                                                    name="device_slots_system_default"
                                                    ng-model="adoCtrl.organizationsetting.device_slots_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Device Linking -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="device_linking" class="col-sm-3 control-label">Device Linking:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.device_linking" name="device_linking"
                                                id="device_linking_allow" value="1"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.device_linking" name="device_linking"
                                                id="device_linking" value="0"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="device_linking_use_system_default"
                                                    name="device_linking_system_default"
                                                    ng-model="adoCtrl.organizationsetting.device_linking_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Link Code Expiration: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="link_code_expiration" class="col-sm-3 control-label">Link Code
                                        Expiration:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationsetting.link_code_expiration"
                                                placeholder="{{trans('organizations::index.org_link')}}"
                                                name="link_code_expiration" id="link_code_expiration"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px 0px 0 20px; padding: 0px 9px; height: auto;">
                                            <span class="input-group-addon"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 0px 20px 20px 0px; padding: 0px 9px; height: auto;">days</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="link_code_use_system_default"
                                                    name="link_code_expiration_system_default"
                                                    ng-model="adoCtrl.organizationsetting.link_code_expiration_system_default"
                                                    ng-true-value="1" ng-false-value="0"> Use
                                                system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Active TOA: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="active_toa" class="col-sm-3 control-label">Active TOA:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.active_toa" value="0"
                                                name="active_toa" id="acceptance_toa"> Acceptance TOA
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.active_toa" value="1"
                                                name="active_toa" id="payment"> Payment
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="active_toa_use_system_default"
                                                    name="active_toa_system_default"
                                                    ng-model="adoCtrl.organizationsetting.active_toa_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subscription Activation: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="subscription_activation" class="col-sm-3 control-label">Subscription
                                        Activation:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.subscription_activation" value="1"
                                                name="subscription_activation" id="subscription_activation_allow"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.subscription_activation" value="0"
                                                name="subscription_activation" id="subscription_activation"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="subscription_activation_use_system_default"
                                                    name="subscription_activation_system_default"
                                                    ng-model="adoCtrl.organizationsetting.subscription_activation_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subscription Prorating: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="subscription_prorating" class="col-sm-3 control-label">Subscription
                                        Prorating:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.subscription_prorating" value="1"
                                                name="subscription_prorating" id="subscription_prorating_allow"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.subscription_prorating" value="0"
                                                name="subscription_prorating" id="subscription_prorating"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="prorating_use_system_default"
                                                    name="subscription_prorating_system_default"
                                                    ng-model="adoCtrl.organizationsetting.subscription_prorating_system_default"
                                                    ng-true-value="1" ng-false-value="0"> Use
                                                system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Add-on Prorating: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="content_add_on_prorating" class="col-sm-3 control-label">Content Add-on
                                        Prorating:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.content_add_on_prorating" value="1"
                                                name="content_add_on_prorating" id="content_add_on_prorating_allow"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.content_add_on_prorating" value="0"
                                                name="content_add_on_prorating" id="content_add_on_prorating"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="content_add_use_system_default"
                                                    name="content_add_on_prorating_system_default"
                                                    ng-model="adoCtrl.organizationsetting.content_add_on_prorating_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Voucher Subscribers: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="voucher_subscribers" class="col-sm-3 control-label">Voucher
                                        Subscribers:</label>
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.voucher_subscribers" value="1"
                                                name="voucher_subscribers" id="voucher_subscribers_allow"> Allow
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio"
                                                ng-model="adoCtrl.organizationsetting.voucher_subscribers" value="0"
                                                name="voucher_subscribers" id="voucher_subscribers"> Disallow
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="voucher_subscribers_use_system_default"
                                                    name="voucher_subscribers_system_default"
                                                    ng-model="adoCtrl.organizationsetting.voucher_subscribers_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expired Voucher Removal: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="expired_voucher_removal" class="col-sm-3 control-label">Expired Voucher
                                        Removal:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationsetting.expired_voucher_removal"
                                                placeholder="{{trans('organizations::index.org_voucher')}}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px 0px 0 20px; padding: 0px 9px; height: auto;"
                                                name="expired_voucher_removal" id="expired_voucher_removal">
                                            <span class="input-group-addon"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 0px 20px 20px 0px; padding: 0px 9px; height: auto;">days</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="expired_voucher_use_system_default"
                                                    name="expired_voucher_removal_system_default"
                                                    ng-model="adoCtrl.organizationsetting.expired_voucher_removal_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Voucher Slots: -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="voucher_slots" class="col-sm-3 control-label">Voucher Slots:</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                ng-model="adoCtrl.organizationsetting.voucher_slots"
                                                placeholder="{{trans('organizations::index.org_slote')}}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                name="voucher_slots" id="voucher_slots">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="voucher_slot_use_system_default"
                                                    name="voucher_slots_system_default"
                                                    ng-model="adoCtrl.organizationsetting.voucher_slots_system_default"
                                                    ng-true-value="1" ng-false-value="0">
                                                Use system default
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-6">
                                        <button type="submit" class="button button-blue" id="settingupdate">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- thumbnail image code -->
    <div class="custom-modal modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="static" data-keyboard="false">
        <div class="custom-modal-dialog img-cropper" role="document">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    {{ __('video::videos.crop_image') }}
                </div>
                <div class="custom-modal-body">
                    <div class="loader-container">
                        <img src="{{asset('adminview/assets/images/loader.gif')}}">
                    </div>
                    <p class="error_msg"></p>
                    <div class="crop-body">
                        <div class="img-container">
                            <img id="image" src="" alt="Picture">
                        </div>
                        <div class="img-preview"></div>
                    </div>
                </div>
                <div class="custom-modal-footer text-right">
                    <button type="button" class="popup-button grey-color"
                        data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                    <button type="button" class="popup-button blue-color"
                        id="submit-image">{{ __('video::videos.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function copyProviderId() {
        const value = document.getElementById("provider_id").value;

        navigator.clipboard.writeText(value)
            .then(() => {
                const icon = document.getElementById("copyIcon");
                icon.classList.remove("glyphicon-copy");
                icon.classList.add("glyphicon-ok");

                setTimeout(() => {
                    icon.classList.remove("glyphicon-ok");
                    icon.classList.add("glyphicon-copy");
                }, 1500);
            })
            .catch(() => {
                alert("Copy failed!");
            });
    }

    window.deletebtn = "{{ route('organizations.destroy') }}";
    window.cancelbtn = "{{ route('organizations.index') }}";
    window.clonebtn = "{{ route('organization.clone', ['id' => '__ID__']) }}";

    function copyProviderId() {
        const input = document.getElementById("provider_id");
        input.select();
        document.execCommand("copy");
    }

</script>

@include('organizations::layouts.default_script')
@include('organizations::layouts.scripts')

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/addorganizationdtl/addorganizationdtl.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection