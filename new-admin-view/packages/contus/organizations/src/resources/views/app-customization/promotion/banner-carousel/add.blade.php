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

    .price-input-wrapper {
        position: relative;
        display: inline-block;
    }

    .price-input {
        padding: 8px 40px 8px 12px;
        border-radius: 30px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 120px;
        box-sizing: border-box;

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

@section('stylesheet')
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="CustomizationController as ctzCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                @{{ pageTitle || 'Add Banner Carousel Subscription' }}
            </h4>
        </div>

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>

        <div class="contentpanel">
            <div class="form-page">
                <form method="post" data-base-validator enctype="multipart/form-data" id="channelSetForm"
                    data-ng-submit="ctzCtrl.save($event)">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- report name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Select resource type:
                                </label>
                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="resource_type" ng-model="bnrcrs.resource_type"
                                            value="internal">
                                        Internal
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="resource_type" ng-model="bnrcrs.resource_type"
                                            value="external">
                                        External
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="resource_type" ng-model="bnrcrs.resource_type"
                                            value="without link">
                                        Without Link
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Select Platform<span class="required">*</span>:
                                </label>
                                @php
                                    $selectedPlatforms = old(
                                        'select_platform',
                                        $organizationDetail->select_platform ?? '[]',
                                    );
                                    $selectedPlatforms = json_decode($selectedPlatforms, true) ?? [];
                                @endphp
                                <div class="col-sm-7" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;">
                                    @foreach (['Stb', 'Pc/Lg', 'Ios', 'tvOS', 'Android Mobile', 'Samsung Tv', 'Web', 'Others/Roku'] as $platform)
                                        <div>
                                            <input type="checkbox" class="form-check-input" name="select_platform[]"
                                                value="{{ $platform }}"
                                                ng-checked="bnrcrs.select_platform && bnrcrs.select_platform.indexOf('{{ $platform }}') !== -1">
                                            <label class="form-check-label">{{ $platform }}</label>
                                        </div>
                                    @endforeach
                                    <p class="error-msg">
                                        @{{ errors.select_platform.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->
                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="upload-cover-thumbnail flexbox"
                                data-ng-class="{'has-error': errors.poster_image.has}">
                                <!-- poster image code -->
                                <div class="cover-image">
                                    <h4>{{ __('video::videos.poster') }}</h4>
                                    <div class="image-content">
                                        <!-- image fetch code -->
                                        <img ng-show="bnrcrs.poster_image.length > 0"
                                            ng-class="{'active':bnrcrs.poster_image}"
                                            class="uploaded_poster_img uploaded_poster_img_@{{ bnrcrs.id }}" alt=""
                                            ng-src="@{{bnrcrs.poster_image  }}" />

                                        <img ng-show="bnrcrs.poster_image.length == 0"
                                            ng-class="{'active':bnrcrs.poster_image}"
                                            class="uploaded_poster_img uploaded_poster_img_@{{ bnrcrs.id }}" alt=""
                                            ng-src="" />

                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileuploadbox">
                                                <div class="input-append">
                                                    <div class="overlay-content"
                                                        data-ng-class="{'change-image': bnrcrs.poster_image.length > 0}">
                                                        <div class="input">
                                                            <div ng-hide="bnrcrs.poster_image.length"
                                                                class="flexbox align-items-center">
                                                                <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                                    width="27px" height="27px">
                                                                    <g>
                                                                        <path opacity="0.702"
                                                                            d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                            fill="#ffffff"></path>
                                                                    </g>
                                                                </svg>
                                                                <span>{{ __('video::videos.upload_cover_picture') }}</span>
                                                            </div>
                                                            <div ng-hide="!bnrcrs.poster_image.length"
                                                                class="flexbox align-items-center ng-hide">
                                                                <svg x="0px" y="0px" width="13" height="13"
                                                                    viewBox="0 0 528.899 528.899">
                                                                    <g>
                                                                        <path
                                                                            d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981   c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611   C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069   L27.473,390.597L0.3,512.69z"
                                                                            fill="#ffffff"></path>
                                                                    </g>
                                                                </svg>
                                                                <span>{{ __('video::videos.change_cover_picture') }}</span>
                                                            </div>
                                                            <input type="file" class="uploadPosterImg" name="image"
                                                                data-video-index="@{{ bnrcrs.id }}">
                                                        </div>
                                                        <p>{{ __('video::videos.poster_file_hint') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.poster_image.has">
                                        @{{errors.poster_image.message}}
                                    </p>
                                </div>

                                <!-- Thumbnail image code -->
                                <div class="thumbnail-image">
                                    <h4>{{ __('video::videos.thumbnail')}}</h4>
                                    <div class="image-content">
                                        <img ng-show="bnrcrs.thumbnail_image.length > 0"
                                            ng-class="{'active': bnrcrs.thumbnail_image}"
                                            class="uploaded_img uploaded_img_@{{ bnrcrs.id }}" alt=""
                                            ng-src="@{{ bnrcrs.thumbnail_image }}" />

                                        <img ng-show="bnrcrs.thumbnail_image.length == 0"
                                            ng-class="{'active': bnrcrs.thumbnail_image}"
                                            class="uploaded_img uploaded_img_@{{ bnrcrs.id }}" alt="" ng-src="" />
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileuploadbox">
                                                <div class="input-append">
                                                    <div class="overlay-content"
                                                        data-ng-class="{'change-image': bnrcrs.thumbnail_image.length > 0}">
                                                        <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px"
                                                            y="0px" width="27px" height="27px">
                                                            <g>
                                                                <path opacity="0.702"
                                                                    d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                    fill="#ffffff"></path>
                                                            </g>
                                                        </svg>
                                                        <div class="input">
                                                            <div ng-hide="bnrcrs.thumbnail_image.length">
                                                                <span>{{ __('video::videos.upload_thumbnail_image') }}</span>
                                                            </div>
                                                            <div ng-hide="!bnrcrs.thumbnail_image.length"
                                                                class="ng-hide flexbox align-items-center">
                                                                <svg class="change_img_ic" x="0px" y="0px" width="13"
                                                                    height="13" viewBox="0 0 528.899 528.899">
                                                                    <g>
                                                                        <path
                                                                            d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981 c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611 C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069 L27.473,390.597L0.3,512.69z"
                                                                            fill="#ffffff">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                <span>{{ __('video::videos.change_thumbnail_image') }}</span>
                                                            </div>
                                                            <input type="file" class="uploadImg" name="image"
                                                                data-video-index="@{{ bnrcrs.id }}">
                                                        </div>
                                                        <p>( Only jpeg, png files allowed with a minimum dimension of
                                                            338x170 )
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.thumbnail_image.has">
                                        @{{errors.thumbnail_image.message}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Name<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto price-input-wrapper">
                                    <input type="text" class="form-control price-input" name="name" placeholder="Enter Name"
                                        ng-model="bnrcrs.name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.name.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- content type -->
                            <div class="form-group row" style="margin-bottom: 15px;"
                                ng-if="bnrcrs.resource_type == 'internal'">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Content Type<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto price-input-wrapper">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="content_type"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="bnrcrs.content_type" myPlaceholder="Select Content Type"
                                        data-ng-model="bnrcrs.content_type">
                                        <option value="">--- Select ---</option>
                                        <option value="video">video</option>
                                        <option value="fm">fm</option>
                                        <option value="radio">radio</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Target Link -->
                            <div class="form-group row" style="margin-bottom: 15px;"
                                ng-if="bnrcrs.resource_type == 'external'">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Target Link<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto price-input-wrapper">
                                    <input type="url" class="form-control price-input" name="target_link"
                                        placeholder="Enter Target Link" ng-model="bnrcrs.target_link"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Active:
                                </label>
                                <label class="switch">
                                    <input type="checkbox" ng-model="bnrcrs.is_active" ng-true-value="1" ng-false-value="0"
                                        ng-checked="bnrcrs.is_active == 1" name="is_active"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <!-- button code -->
                    <div class="bottom-button text-center"
                        style="border-bottom: 0px; justify-content: center; border-top: 0px; box-shadow: none;">

                        <!-- Create mode: Save button -->
                        <button id="bannerCarouselFormSubmit" data-ng-click="ctzCtrl.save($event)" ng-hide="isEditMode"
                            class="publish-now">
                            Save
                        </button>

                        <!-- Edit mode: Update button -->
                        <button id="bannerCarouselFormUpdate" data-ng-click="ctzCtrl.save($event)" ng-show="isEditMode"
                            class="publish-now">
                            Update
                        </button>

                        <a class="save" href="{{ url()->previous() }}">
                            {{ __('video::videos.back') }}
                        </a>
                    </div>
                </form>
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


    <!-- Poster Modal -->
    <div class="custom-modal modal fade" id="poster_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="static" data-keyboard="false">
        <div class="custom-modal-dialog img-cropper" role="document">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    {{ __('video::videos.crop_image') }}
                </div>
                <div class="custom-modal-body">
                    <div class="poster_loader-container">
                        <img src="{{asset('adminview/assets/images/loader.gif')}}" />
                    </div>
                    <p class="poster_error_msg"></p>
                    <div class="crop-body">
                        <div class="img-container">
                            <img id="poster_image" src="" alt="Picture" />
                        </div>
                        <div class="poster_img-preview"></div>
                    </div>
                </div>
                <div class="custom-modal-footer text-right">
                    <button type="button" class="popup-button grey-color"
                        data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                    <button type="button" class="popup-button blue-color"
                        id="submit_poster_image">{{ __('video::videos.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/app-customization/customization.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection