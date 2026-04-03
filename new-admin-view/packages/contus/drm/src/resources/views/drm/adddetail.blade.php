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
                    <li class="active">Add DRM Account</li>
                </ol>
            </div>

            @include('drm::layouts.subnav')

            <br>

            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#home" role="tab" data-toggle="tab" style="color: black;">DRM Account</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade in active"><br>
                    <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">Add DRM Account</h1><br>
                    <div class="row">
                        <div class="justify-content-center mx-auto" style="width:70%; margin-left: 10rem;">
                            <form method="POST" enctype="multipart/form-data" id="customization" data-base-validator
                                data-ng-submit="drmCtrl.saveaccountdetail($event)">
                                {!! csrf_field() !!}
                                <input type="hidden" id="drm-id" name="id" value="{{ request()->id }}">

                                <!-- drm name -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="drm_name" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Name*:</strong></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.accdrm.drm_name"
                                            disabled="drmCtrl.accdrm.drm_name" name="drm_name"
                                            placeholder="{{trans('drm::index.drm_acc_name')}}" id="drm_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.drm_name.has">
                                            @{{ errors.drm_name.message }}
                                        </p>
                                    </div>
                                </div>

                                <!-- choose provider -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Drm Provider*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="drm_provider" id="drm_provider" ng-model="drmCtrl.accdrm.drm_provider"
                                            ng-disabled="drmCtrl.accdrm.drm_provider" class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">Select Drm Provider</option>
                                            <option value="Pallycon">Pallycon</option>
                                            <option value="EZDRM">EZDRM</option>
                                        </select>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.drm_provider.has">
                                        @{{ errors.drm_provider.message }}
                                    </p>
                                </div>

                                <!-- Show only for EZDRM -->
                                <!-- px value -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="drmCtrl.accdrm.drm_provider == 'EZDRM'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;"><strong>PX
                                            Value*:</strong></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="drmCtrl.accdrm.px_value"
                                            name="px_value" id="px_value"
                                            placeholder="{{ trans('drm::index.drm_px_value') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- Show only for Pallycon -->
                                <div ng-if="drmCtrl.accdrm.drm_provider == 'Pallycon'">
                                    <!-- acccount id -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Account
                                                Id*:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="account_id" id="account_id"
                                                placeholder="{{ trans('drm::index.drm_acc_id') }}"
                                                ng-model="drmCtrl.accdrm.account_id"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>

                                    <!-- site key -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Site Key*:</strong>
                                        </label>
                                        <div class="col-sm-10" style="display: flex; align-items: center; gap: 10px;">
                                            <input type="password" class="form-control" id="siteKeyInput"
                                                ng-model="drmCtrl.accdrm.site_key" name="site_key"
                                                placeholder="{{ trans('drm::index.drm_site_key') }}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; flex: 1;">
                                            <button type="button" class="btn btn-default" onclick="toggleSiteKey()"
                                                title="Show/Hide Site Key">
                                                <i class="glyphicon glyphicon-eye-open" id="togglesiteIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- access key -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Access Key*:</strong>
                                        </label>
                                        <div class="col-sm-10" style="display: flex; align-items: center; gap: 10px;">
                                            <input type="password" class="form-control" id="accessKeyInput"
                                                ng-model="drmCtrl.accdrm.access_key" name="access_key"
                                                placeholder="{{ trans('drm::index.drm_acc_key') }}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; flex: 1;">
                                            <button type="button" class="btn btn-default" onclick="toggleAccessKey()"
                                                title="Show/Hide Access Key">
                                                <i class="glyphicon glyphicon-eye-open" id="toggleaccessIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- publish now -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="publish_now" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Publish
                                            Now:</strong></label>
                                    <label class="switch" style="margin: 10.5px 10px 0px 10px;">
                                        <input type="checkbox" ng-model="drmCtrl.accdrm.publish_now"
                                            ng-checked="publish_now == 1" name="publish_now">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- button group -->
                                <div class="bottom-button form-group text-center">
                                    <button type="submit" class="button button-blue" id="organizationupdate" data-ng-if="checkAccess('drm_accounts.create')">
                                        <strong>Update</strong>
                                    </button>&nbsp;&nbsp;
                                    <button type="button" class="button button-red"
                                        onclick="deleteDrm({{ request()->id }})" data-ng-if="checkAccess('drm_accounts.create')">
                                        <strong>Remove</strong>
                                    </button>&nbsp;&nbsp;
                                    <button type="button" class="button button-gray"
                                        onclick="cancelDrm({{ request()->id }})" data-ng-if="checkAccess('drm_accounts.create')">
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
    window.deletebtn = "{{ route('drm.destroy') }}";
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