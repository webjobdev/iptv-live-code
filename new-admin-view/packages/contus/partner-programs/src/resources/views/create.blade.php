@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/cropper.css') }}" />

    <style>
        /* Hide the real input */
        .hidden-file-input {
            position: absolute;
            left: -9999px;
            pointer-events: none;
        }

        /* Style the fake drop area */
        .custom-file-upload {
            border: 2px dashed lightgray;
            height: 150px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            text-align: center;
            background-color: #fff;
            transition: border-color 0.3s;
            font-size: medium;
        }

        .custom-file-upload:hover {
            border-color: #999;
        }

        /* .upload-content p,
                                                                                    .upload-content span,
                                                                                    .upload-content small {
                                                                                        margin: 5px 0;
                                                                                        font-family: Arial, sans-serif;
                                                                                    } */
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="PartnerProgramController as programCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    <li><a href="{{ route('partner.index') }}">{{ __('partner-programs::index.partner_program') }}</a></li>
                    <li class="active">Add Partner Program</li>
                </ol>
            </div>
            <br>

            <div id="home" class="tab-pane fade in active contentpanel form-page"><br>
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="programCtrl.savePartnerProgram($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="program-id" name="id" value="{{ request()->id }}">

                            <!-- program name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="program_name" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Program
                                        Name*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="programCtrl.programData.program_name"
                                        name="program_name"
                                        placeholder="{{ trans('partner-programs::index.pname_placeholder') }}" id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.program_name.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- partner provider -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Partner
                                        Provider*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="partner_provider" id="partner_provider"
                                        ng-model="programCtrl.programData.partner_provider" {{--
                                        ng-options="provd.partner_provider for provd in programCtrl.orgList" --}}
                                        class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Provider</option>
                                        <option value="option-1">option 1</option>
                                        <option value="option-2">option 2</option>
                                        <option value="option-3">option 3</option>
                                        <option value="option-4">option 4</option>
                                    </select>
                                    <p class="error-msg">
                                        @{{ errors.partner_provider.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- partner code -->
                            <div class="form-group row" style="margin-bottom: 15px">
                                <label for="partner_code" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Partner
                                        Code*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="programCtrl.programData.partner_code"
                                        name="partner_code"
                                        placeholder="{{ trans('partner-programs::index.code_placeholder') }}"
                                        id="partner_code"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.partner_code.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- partner app logo -->
                            <div class="upload-cover-thumbnail flexbox"
                                data-ng-class="{'has-error': errors.poster_image.has}">
                                <!-- Thumbnail image code -->
                                <div class="form-group row">
                                    <label for="partner_code" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px; font-weight: bold;">
                                        <strong>Partner App Logo:</strong>
                                    </label>
                                </div>
                                <div class="thumbnail-image">
                                    {{-- <h4></h4> --}}
                                    <div class="image-content">
                                        <img ng-show="programCtrl.programData.partner_app_logo.length > 0"
                                            ng-class="{'active': programCtrl.programData.partner_app_logo}"
                                            class="uploaded_img uploaded_img_@{{ programCtrl.product.id }}" alt=""
                                            ng-src="@{{ programCtrl.programData.partner_app_logo }}" />

                                        <img ng-show="programCtrl.programData.partner_app_logo.length == 0"
                                            ng-class="{'active': programCtrl.programData.partner_app_logo}"
                                            class="uploaded_img uploaded_img_@{{ programCtrl.product.id }}" alt=""
                                            ng-src="" />
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileuploadbox">
                                                <div class="input-append">
                                                    <div class="overlay-content"
                                                        data-ng-class="{'change-image': programCtrl.programData.partner_app_logo.length > 0}">
                                                        <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px"
                                                            y="0px" width="27px" height="27px">
                                                            <g>
                                                                <path opacity="0.702"
                                                                    d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                    fill="#ffffff"></path>
                                                            </g>
                                                        </svg>
                                                        <div class="input">
                                                            <div ng-hide="programCtrl.programData.partner_app_logo.length">
                                                                <span>Change Partner App Logo</span>
                                                            </div>
                                                            <div ng-hide="!programCtrl.programData.partner_app_logo.length"
                                                                class="ng-hide flexbox align-items-center">
                                                                <svg class="change_img_ic" x="0px" y="0px" width="13"
                                                                    height="13" viewBox="0 0 528.899 528.899">
                                                                    <g>
                                                                        <path
                                                                            d=" M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981 c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611 C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069 L27.473,390.597L0.3,512.69z"
                                                                            fill="#ffffff">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                <span>Change Partner App Logo</span>
                                                            </div>
                                                            <input type="file" class="uploadImg" name="image"
                                                                data-video-index="@{{ programCtrl.product.id }}">
                                                        </div>
                                                        <p>(Upload a partner app logo with minimum dimension of 355x200)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.partner_app_logo.has">
                                        @{{ errors.partner_app_logo.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- api key -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="api_key" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>API
                                        Key*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="programCtrl.programData.api_key"
                                        name="api_key"
                                        placeholder="{{ trans('partner-programs::index.api_key_placeholder') }}"
                                        id="api_key"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.api_key.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- api link -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="api_link" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Link of Partner
                                        Api*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="programCtrl.programData.api_link"
                                        name="api_link"
                                        placeholder="{{ trans('partner-programs::index.api_link_placeholder') }}"
                                        id="api_link"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.api_link.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- description -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="description" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Description*:</strong></label>
                                <div class="col-sm-10">
                                    <textarea type="text" class="form-control"
                                        ng-model="programCtrl.programData.description" name="description"
                                        placeholder="{{ trans('partner-programs::index.desc_placeholder') }}"
                                        id="description"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                                        </textarea>
                                    <p class="error-msg">
                                        @{{ errors.description.message }}
                                    </p>
                                </div>
                            </div>

                            {{-- <!-- publish now -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="status" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Status:</strong></label>
                                <label class="switch" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="programCtrl.accdrm.status" ng-checked="status == 1"
                                        name="status">
                                    <span class="slider round"></span>
                                </label>
                            </div> --}}

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="partnerprogramadd">
                                    <strong>Add</strong>
                                </button>

                                <button type="submit" data-ng-if="isEditMode" class="button button-blue"
                                    id="partnerprogramedit">
                                    <strong>Update</strong>
                                </button>

                                <button type="submit" data-ng-if="isEditMode" class="button button-red"
                                    id="partnerprogramedit"
                                    ng-click="programCtrl.removePartnerProgram({{ request()->id }})">
                                    <strong>Remove</strong>
                                </button>

                                <button type="button" class="button button-red" ng-click="cancelPartnerProgram()">
                                    <strong>Remove</strong>
                                </button>&nbsp;

                                <button type="button" class="button button-gray" ng-click="cancelPartnerProgram()">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- photo open model code -->
    <div class="custom-modal modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="static" data-keyboard="false">
        <div class="custom-modal-dialog img-cropper" role="document">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    {{ __('video::videos.crop_image') }}
                </div>
                <div class="custom-modal-body">
                    <div class="loader-container">
                        <img src="{{ asset('adminview/assets/images/loader.gif') }}">
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

@section('scripts')
    <script src="{{ asset('adminview/assets/js/cropper.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/partner-programs/index.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection