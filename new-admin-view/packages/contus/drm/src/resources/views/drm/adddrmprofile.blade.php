@extends('base::layouts.default')

@section('stylesheet')
<link rel="stylesheet" href="{{asset('adminview/assets/css/select2.min.css')}}" />
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="DrmController as drmCtrl">
    <div class=" " id="dashboard-page">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <!-- <h4>{{ __('drm::index.drm') }}</h4> -->
            <ol class="breadcrumb">
                <li><a href="{{ route('drm.index') }}">{{ __('drm::index.drm') }}</a></li>
                <li class="active">Add DRM Profile</li>
            </ol>
        </div>

        @include('drm::layouts.subnav')

        <br>

        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#home" role="tab" data-toggle="tab" style="color: black;">Add Drm Profile</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active"><br>
                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">Add DRM Profile</h1><br>
                <div class="row">
                    <div class="justify-content-center mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" id="customization" data-base-validator data-ng-submit="drmCtrl.saveprofiledetail($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="drm-id" name="id" value="{{ request()->id }}">

                            <div>
                                <!-- DRM Name -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="drm_name" class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Name*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.accdrm.drm_name"
                                            name="drm_name" id="drm_name"
                                            placeholder="{{ trans('drm::index.drm_pro_name') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- DRM Provider -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Drm Provider*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="drm_provider" id="drm_provider"
                                            ng-model="drmCtrl.accdrm.drm_provider"
                                            ng-disabled="drmCtrl.accdrm.drm_provider"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">Select Drm Provider</option>
                                            <option value="Pallycon">Pallycon</option>
                                            <option value="EZDRM">EZDRM</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- DRM Type -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Drm Type*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="drm_type" id="drm_type"
                                            ng-model="drmCtrl.prodrm.drm_type"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_type') }}</option>
                                            <option value="Widevine">Widevine</option>
                                            <option value="FairPlay">FairPlay</option>
                                            <option value="PlayReady">PlayReady</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Authorization URL (only for EZDRM + Widevine) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && 
                                    (drmCtrl.prodrm.drm_type == 'Widevine' || drmCtrl.prodrm.drm_type == 'FairPlay' || drmCtrl.prodrm.drm_type == 'PlayReady')">

                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Authorization Url*:</strong>
                                    </label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.accdrm.authorization_url"
                                            name="authorization_url"
                                            placeholder="{{ trans('drm::index.drm_pro_authorization') }}" disabled
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">

                                        <label class="control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                            Authorization URL will be generated automatically when Profile will be created
                                        </label>
                                    </div>
                                </div>

                                <!-- Integration Type (only for Pallycon + Widevine, PlayReady, FairPlay) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && 
                                    (drmCtrl.prodrm.drm_type == 'Widevine' || drmCtrl.prodrm.drm_type == 'FairPlay' || drmCtrl.prodrm.drm_type == 'PlayReady')">

                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Integration Type:</strong>
                                    </label>

                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.integration_type"
                                                name="integration_type" value="token"> Token
                                        </label>
                                    </div>
                                </div>

                            </div>


                            <!-- start provider = ezdrm && type = widevine -->
                            <div ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'Widevine'">

                                <hr style="border-top: 1px solid #000;">

                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1><br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation -->
                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.prodrm.license_duration"
                                            name="license_duration" placeholder="{{ trans('drm::index.drm_license_duration') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <hr style="border-top: 1px solid #000;">

                                <!-- HDCP Type -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>HDCP Type:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="hdcp_type" id="hdcp_type"
                                            ng-model="drmCtrl.prodrm.hdcp_type"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_hdcp_type') }}</option>
                                            <option value="None">None</option>
                                            <option value="type 0">Type 0 (All HDCP capable devices)</option>
                                            <option value="type 1">Type 1 (HDCP 2.2+ only)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Robustness -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Robustness:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="robustness" id="robustness"
                                            ng-model="drmCtrl.prodrm.robustness"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_robutness') }}</option>
                                            <option value="SW_SECURE_DECODE">SW_SECURE_DECODE</option>
                                            <option value="SW_SECURE_CRYPTO">SW_SECURE_CRYPTO</option>
                                            <option value="HW_SECURE_CRYPTO">HW_SECURE_CRYPTO</option>
                                            <option value="HW_SECURE_DECODE">HW_SECURE_DECODE</option>
                                            <option value="HW_SECURE_ALL">HW_SECURE_ALL</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!-- end provider = ezdrm && type = widevine -->


                            <!-- start provider = ezdrm && type = FairPlay -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'FairPlay'">
                                <label for="drm_name" class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Fps Certificate:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" ng-model="drmCtrl.prodrm.fps_certificate"
                                        name="fps_certificate" id="fps_certificate" accept=".cer, .der, .pdf"
                                        placeholder="{{ trans('drm::index.ctm_privacy') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <label class="control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Allowed only ".cer" or ".der" formats
                                    </label>
                                </div>
                            </div>


                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'FairPlay'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'FairPlay'">

                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1><br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation (only if persistent == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration (only if limitation == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.prodrm.license_duration"
                                            name="license_duration"
                                            placeholder="{{ trans('drm::index.drm_license_duration') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>
                            <!-- end provider = ezdrm && type = FairPlay -->


                            <!-- start provider = ezdrm && type = PlayReady -->
                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'PlayReady'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'PlayReady'">
                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1>

                                <br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation (only if persistent == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration (only if limitation == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.prodrm.license_duration"
                                            name="license_duration"
                                            placeholder="{{ trans('drm::index.drm_license_duration') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>

                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'PlayReady'" style="border-top: 1px solid #000;">

                            <div class="form-group row" style="margin-bottom: 15px;"
                                ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM' && drmCtrl.prodrm.drm_type == 'PlayReady'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Output Protection Level:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="output_protection_level" class="form-control" ng-model="drmCtrl.prodrm.output_protection_level"
                                        name="license_duration"
                                        placeholder="{{ trans('drm::index.drm_pro_op_protection') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>
                            <!-- end provider = ezdrm && type = PlayReady -->

                            <!-- start provider = Pallycon && type = widevine -->
                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'Widevine'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'Widevine'">
                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1>

                                <br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation (only if persistent == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration (only if limitation == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.prodrm.license_duration"
                                            name="license_duration"
                                            placeholder="{{ trans('drm::index.drm_license_duration') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>

                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'Widevine'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'Widevine'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong></strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.hardware_drm_required"
                                                name="hardware_drm_required" value="1"> Hardware DRM required
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>HDCP Type:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="hdcp_type" id="hdcp_type"
                                            ng-model="drmCtrl.prodrm.hdcp_type"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_hdcp_type') }}</option>
                                            <option value="None">None</option>
                                            <option value="type 0">Type 0 (All HDCP capable devices)</option>
                                            <option value="type 1">Type 1 (HDCP 2.2+ only)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong></strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.rooted_devices_allowed"
                                                name="rooted_devices_allowed" value="1"> Rooted devices allowed
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- end provider = Pallycon && type = widevine -->

                            <!-- start provider = Pallycon && type = FairPlay -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'FairPlay'">
                                <label for="fps_certificate" class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Fps Certificate:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" ng-model="drmCtrl.prodrm.fps_certificate"
                                        name="fps_certificate" id="fps_certificate" accept=".cer, .der, .pdf"
                                        placeholder="{{ trans('drm::index.ctm_privacy') }}"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <label class="control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Allowed only ".cer" or ".der" formats
                                    </label>
                                </div>
                            </div>

                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'FairPlay'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'FairPlay'">
                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1>

                                <br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation (only if persistent == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration (only if limitation == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.prodrm.license_duration"
                                            name="license_duration"
                                            placeholder="{{ trans('drm::index.drm_license_duration') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>

                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'FairPlay'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'FairPlay'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>HDCP Type:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="hdcp_type" id="hdcp_type"
                                            ng-model="drmCtrl.prodrm.hdcp_type"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_hdcp_type') }}</option>
                                            <option value="None">None</option>
                                            <option value="type 0">Type 0 (All HDCP capable devices)</option>
                                            <option value="type 1">Type 1 (HDCP 2.2+ only)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong></strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.rooted_devices_allowed"
                                                name="rooted_devices_allowed" value="1"> Rooted devices allowed
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- end provider = Pallycon && type = FairPlay -->

                            <!-- start provider = Pallycon && type = PlayReady -->
                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'PlayReady'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'PlayReady'">
                                <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; margin-left: -9rem;">
                                    Usage Rights Settings
                                </h1>

                                <br>

                                <!-- License label row -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License:</strong>
                                    </label>
                                    <div class="col-sm-10"></div>
                                </div>

                                <!-- Is License Persistent -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Is License Persistent:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_persistent"
                                                name="license_persistent" value="0"> No
                                        </label>
                                    </div>
                                </div>

                                <!-- License Limitation (only if persistent == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Limitation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="1"> Limited
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.license_limitation"
                                                name="license_limitation" value="0"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <!-- License Duration (only if limitation == 1) -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.prodrm.license_persistent == 1 && drmCtrl.prodrm.license_limitation == 1">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>License Duration, sec:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            ng-model="drmCtrl.prodrm.drm_license_duration"
                                            name="license_duration"
                                            placeholder="{{ trans('drm::index.ctm_agreement') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>

                            <hr ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'PlayReady'" style="border-top: 1px solid #000;">

                            <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon' && drmCtrl.prodrm.drm_type == 'PlayReady'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>HDCP Type:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="hdcp_type" id="hdcp_type"
                                            ng-model="drmCtrl.prodrm.hdcp_type"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_pro_hdcp_type') }}</option>
                                            <option value="None">None</option>
                                            <option value="type 0">Type 0 (All HDCP capable devices)</option>
                                            <option value="type 1">Type 1 (HDCP 2.2+ only)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong></strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="drmCtrl.prodrm.rooted_devices_allowed"
                                                name="rooted_devices_allowed" value="1"> Rooted devices allowed
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>PlayReady Security Level:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="playready_security_level" id="playready_security_level"
                                            ng-model="drmCtrl.prodrm.playready_security_level"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('drm::index.drm_playready_security') }}</option>
                                            <option value="100">100</option>
                                            <option value="150">150</option>
                                            <option value="200">200</option>
                                            <option value="250">250</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- end provider = Pallycon && type = PlayReady -->

                            <!-- Enable -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="is_active" class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Enable:</strong>
                                </label>
                                <label class="switch" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox"
                                        ng-model="drmCtrl.prodrm.is_active"
                                        ng-checked="is_active == 1"
                                        name="is_active">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="organizationupdate" data-ng-if="checkAccess('drm_profiles.create')">
                                    <strong>Create</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="button button-gray" onclick="cancelDrm({{ request()->id }})" data-ng-if="checkAccess('drm_profiles.create')">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

<script>
    window.cancelbtn = "{{ route('drm.index') }}";
</script>


@include('drm::layouts.scripts')

@section('scripts')

<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/drm/index.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection