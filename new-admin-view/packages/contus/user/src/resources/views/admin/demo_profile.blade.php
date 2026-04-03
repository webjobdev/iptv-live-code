@extends('base::layouts.default')
@section('header')
    @include('base::layouts.headers.dashboard')
@endsection
@section('stylesheet')
    <link href="{{ asset('adminview/assets/css/bootstrap-fileupload.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminview/assets/css/uploader.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/cropper.css') }}" />
@endsection
@section('content')
    <style type="text/css">
        .custom-color {
            color: #a94442;
        }
    </style>
    <div class="page-heading flexbox align-items-center flex-wrap">
        <div class="left-side">
            <h4>{{ trans('user::adminuser.my_profile') }}</h4>
        </div>
    </div>
    @include('base::partials.errors')
    <div class="response-msg"></div>
    <div class="video-detail form-page profile-page" data-base-validator data-ng-controller="ProfileController as prfCtrl">
        <form name="profileForm" method="POST" data-ng-init="prfCtrl.fetchData()" data-ng-submit="prfCtrl.save($event)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="page-padding">
                <div id="table_loader" class="table_loader_container" data-ng-if="prfCtrl.gridLoadingBar">
                    <div class="table_loader">
                        <div class="loader"></div>
                    </div>
                </div>

                <div class="profile_image_upload">

                    <div class="division flexbox">
                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                                <label>{{ trans('user::adminuser.username') }} <span class="required">*</span></label>
                                <div class="form-input">
                                    <input type="text" name="name" data-ng-model="prfCtrl.user.name" class="form-control"
                                        placeholder="{{ trans('user::adminuser.username_placeholder') }}"
                                        value="{{ old('name') }}" />
                                </div>
                                <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                            </div>
                        </div>
                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                                <label>{{ trans('user::adminuser.email') }} <span class="required">*</span></label>
                                <div class="form-input">
                                    <input type="text" readonly name="email"
                                        data-unique="{{ url('api/admin/users/unique') }}@{{ '/' + prfCtrl.user.id }}"
                                        data-ng-model="prfCtrl.user.email" class="form-control"
                                        placeholder="{{ trans('user::adminuser.email_placeholder') }}"
                                        value="{{ old('email') }}" />
                                </div>
                                <p class="error-msg" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="division flexbox">
                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
                                <label>{{ trans('user::adminuser.phone') }} <span class="required">*</span></label>
                                <div class="form-input">
                                    <input type="text" name="phone" maxlength="15" class="form-control"
                                        data-validation-name="Phone Number" data-ng-model="prfCtrl.user.phone"
                                        placeholder="{{ trans('user::adminuser.phone_placeholder') }}"
                                        value="{{ old('phone') }}" />
                                </div>
                                <p class="error-msg" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
                            </div>
                        </div>
                        <div class="one-set width-50">
                            <div class="form-group">
                                <label>{{ trans('user::adminuser.gender') }}</label>
                                <div class="form-input">

                                    <select allowClear="1" name="gender" data-jquery="select2_custom_ddl"
                                        myValue="prfCtrl.user.gender"
                                        myPlaceholder="{{ trans('user::adminuser.select_gender') }}" class="form-control"
                                        data-ng-model="prfCtrl.user.gender">
                                        <option value="" disabled>{{ trans('user::adminuser.select_gender') }}
                                        </option>
                                        <option value="male">{{ trans('user::adminuser.male') }}</option>
                                        <option value="female">{{ trans('user::adminuser.female') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="division flexbox">
                        <div class="one-set width-50">

                            <div class="upload-cover-thumbnail">
                                <div class="thumbnail-image ml-zero"
                                    data-ng-class="{'has-error': errors.profile_image.has}">
                                    <h4>{{ trans('user::adminuser.profile_image') }}</h4>
                                    <div class="image-content">
                                        <img ng-class="{'active': prfCtrl.user.profile_image}" alt=""
                                            class="uploaded_img uploaded_img_@{{ prfCtrl.user.id }}"
                                            ng-src="@{{ prfCtrl.user.profile_image }}">
                                        {{-- <img ng-class="{'active': prfCtrl.profile_image}" class="uploaded_img" alt="">
                                        --}}
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileuploadbox">
                                                <div class="input-append">
                                                    <div class="overlay-content"
                                                        data-ng-class="{'change-image':  prfCtrl.user.profile_image.length > 0}">
                                                        <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px"
                                                            y="0px" width="27px" height="27px">
                                                            <g>
                                                                <path opacity="0.702"
                                                                    d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                    fill="#ffffff"></path>
                                                            </g>
                                                        </svg>
                                                        <div class="input">
                                                            <div ng-hide="prfCtrl.user.profile_image.length">
                                                                <span>Upload Image</span>
                                                            </div>
                                                            <div ng-hide="!prfCtrl.user.profile_image.length"
                                                                class="ng-hide flexbox align-items-center">
                                                                <svg class="change_img_ic" x="0px" y="0px" width="13"
                                                                    height="13" viewBox="0 0 528.899 528.899" "="">
                                                                                                    <g>
                                                                                                        <path d="
                                                                    M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z
                                                                    M518.113,63.177l-47.981-47.981
                                                                    c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                                                    C532.495,100.753,532.495,77.559,518.113,63.177z
                                                                    M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                                                    L27.473,390.597L0.3,512.69z" fill="#ffffff"></path>
                                                                    </g>
                                                                </svg>
                                                                <span>Change Image</span>
                                                            </div>
                                                            <input type="file" accept="image/*" class="uploadImg"
                                                                name="image">
                                                            <input type="hidden" class="module" id="module" name="module"
                                                                value="album">
                                                            <input type="hidden" class="size" id="size" name="size"
                                                                value="thumb">
                                                        </div>
                                                        <p>( Only jpeg, png files allowed )</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.profile_image.has">@{{
                                        errors.profile_image.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-button text-right flexbox align-items-center">
                {{-- <a class="save" href="{{url('admin/users/profile')}}">
                    {{trans('base::general.cancel')}}
                </a> --}}
                <button class="publish-now feedback" type="button" ng-click="prfCtrl.getFeedback()">
                    {{ trans('user::settings.feedback') }}
                </button>
                <button class="publish-now">
                    {{ trans('base::general.submit') }}
                </button>
            </div>
        </form>

        <div class="custom-modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
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

                        <button type="button" class="popup-button grey-color" data-dismiss="modal">Cancel</button>
                        <button type="button" class="popup-button blue-color"
                            id="submit-image">{{ __('video::videos.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container" style="display: none">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <div class="left-side">
                <h4>Geofencing</h4>
            </div>
        </div>

        <div class="video-detail form-page profile-page">
            <form>
                <div class="page-padding">
                    <div class="geofence">
                        <div class="form-group">
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" id="parent1" name="permissions[0][]" class="parentCheckBox disabled"
                                    value="dashboard_all">
                                <label>Enable Geofencing option for each video</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="or">(or)</span>
                        </div>
                        <div class="form-group">
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" id="parent1" name="permissions[0][]" class="parentCheckBox"
                                    value="dashboard_all" checked="">
                                <label>Enable Geofencing option for each video</label>
                            </div>

                            <div class="form-input tags-subtitles flexbox">
                                <div class="tags">
                                    <ul>
                                        <li>
                                            <span>sdfsf</span>
                                        </li>
                                    </ul>
                                </div>
                                <i class="upload-subtitle flexbox align-items-center">
                                    <svg viewBox="0 0 229.75 228.75">
                                        <path
                                            d="M113,264l-4,5L55,281l-3-3,12-53,5-5ZM258,119,214,75l16-16a23.542,23.542,0,0,1,33,0l12,12c9,8,9,23,0,32ZM129,249,85,204,201,88l44,44Z"
                                            transform="translate(-52 -52.25)" fill-rule="evenodd" />
                                    </svg>
                                    <span>Modify</span>
                                </i>
                            </div>
                        </div>

                        <div class="geofence-lists">
                            <ul class="geofence-lists-ul">
                                <li class="geofence-lists-li">
                                    <div class="head flexbox align-items-center">
                                        <div class="ckbox ckbox-default">
                                            <input type="checkbox" id="parent1" name="permissions[0][]"
                                                class="parentCheckBox disabled" value="dashboard_all">
                                            <label>India</label>
                                        </div>

                                        <svg viewBox="0 0 6 9" version="1.1" x="0px" y="0px" width="6px" height="9px">
                                            <g>
                                                <path
                                                    d="M 0.7507 0.291 C 0.6434 0.3996 0.5897 0.5286 0.5897 0.6776 L 0.5897 8.3775 C 0.5897 8.5266 0.6434 8.6555 0.7507 8.7643 C 0.8583 8.8732 0.9853 8.9276 1.132 8.9276 C 1.2788 8.9276 1.4058 8.8732 1.5133 8.7643 L 5.31 4.9143 C 5.4172 4.8053 5.4712 4.6765 5.4712 4.5275 C 5.4712 4.3786 5.4172 4.2496 5.31 4.1409 L 1.5133 0.291 C 1.4058 0.1822 1.2788 0.1276 1.132 0.1276 C 0.9853 0.1276 0.8583 0.1822 0.7507 0.291 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="content">
                                        <ul class="geofence-lists-content-ul">
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="geofence-lists-li">
                                    <div class="head flexbox align-items-center">
                                        <div class="ckbox ckbox-default">
                                            <input type="checkbox" id="parent1" name="permissions[0][]"
                                                class="parentCheckBox disabled" value="dashboard_all">
                                            <label>India</label>
                                        </div>

                                        <svg viewBox="0 0 6 9" version="1.1" x="0px" y="0px" width="6px" height="9px">
                                            <g>
                                                <path
                                                    d="M 0.7507 0.291 C 0.6434 0.3996 0.5897 0.5286 0.5897 0.6776 L 0.5897 8.3775 C 0.5897 8.5266 0.6434 8.6555 0.7507 8.7643 C 0.8583 8.8732 0.9853 8.9276 1.132 8.9276 C 1.2788 8.9276 1.4058 8.8732 1.5133 8.7643 L 5.31 4.9143 C 5.4172 4.8053 5.4712 4.6765 5.4712 4.5275 C 5.4712 4.3786 5.4172 4.2496 5.31 4.1409 L 1.5133 0.291 C 1.4058 0.1822 1.2788 0.1276 1.132 0.1276 C 0.9853 0.1276 0.8583 0.1822 0.7507 0.291 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="content">
                                        <ul class="geofence-lists-content-ul">
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="geofence-lists-li">
                                    <div class="head flexbox align-items-center">
                                        <div class="ckbox ckbox-default">
                                            <input type="checkbox" id="parent1" name="permissions[0][]"
                                                class="parentCheckBox disabled" value="dashboard_all">
                                            <label>India</label>
                                        </div>

                                        <svg viewBox="0 0 6 9" version="1.1" x="0px" y="0px" width="6px" height="9px">
                                            <g>
                                                <path
                                                    d="M 0.7507 0.291 C 0.6434 0.3996 0.5897 0.5286 0.5897 0.6776 L 0.5897 8.3775 C 0.5897 8.5266 0.6434 8.6555 0.7507 8.7643 C 0.8583 8.8732 0.9853 8.9276 1.132 8.9276 C 1.2788 8.9276 1.4058 8.8732 1.5133 8.7643 L 5.31 4.9143 C 5.4172 4.8053 5.4712 4.6765 5.4712 4.5275 C 5.4712 4.3786 5.4172 4.2496 5.31 4.1409 L 1.5133 0.291 C 1.4058 0.1822 1.2788 0.1276 1.132 0.1276 C 0.9853 0.1276 0.8583 0.1822 0.7507 0.291 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="content">
                                        <ul class="geofence-lists-content-ul">
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="geofence-lists-li open">
                                    <div class="head flexbox align-items-center">
                                        <div class="ckbox ckbox-default">
                                            <input type="checkbox" id="parent1" name="permissions[0][]"
                                                class="parentCheckBox disabled" value="dashboard_all">
                                            <label>India</label>
                                        </div>

                                        <svg viewBox="0 0 6 9" version="1.1" x="0px" y="0px" width="6px" height="9px">
                                            <g>
                                                <path
                                                    d="M 0.7507 0.291 C 0.6434 0.3996 0.5897 0.5286 0.5897 0.6776 L 0.5897 8.3775 C 0.5897 8.5266 0.6434 8.6555 0.7507 8.7643 C 0.8583 8.8732 0.9853 8.9276 1.132 8.9276 C 1.2788 8.9276 1.4058 8.8732 1.5133 8.7643 L 5.31 4.9143 C 5.4172 4.8053 5.4712 4.6765 5.4712 4.5275 C 5.4712 4.3786 5.4172 4.2496 5.31 4.1409 L 1.5133 0.291 C 1.4058 0.1822 1.2788 0.1276 1.132 0.1276 C 0.9853 0.1276 0.8583 0.1822 0.7507 0.291 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="content">
                                        <ul class="geofence-lists-content-ul">
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                            <li class="geofence-lists-content-li">
                                                <div class="ckbox ckbox-default">
                                                    <input type="checkbox" id="parent1" name="permissions[0][]"
                                                        class="parentCheckBox disabled" value="dashboard_all">
                                                    <label>Andra Pradesh</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bottom-button text-right flexbox align-items-center">
                    <a class="save">
                        {{ trans('base::general.cancel') }}
                    </a>
                    <button class="publish-now">
                        {{ trans('base::general.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('adminview/assets/js/cropper.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/Validate.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/validatorDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/adminusers/profile.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection