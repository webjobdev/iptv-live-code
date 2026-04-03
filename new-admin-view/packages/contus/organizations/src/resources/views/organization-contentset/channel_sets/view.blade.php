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
    }

    .currency-label {
        position: absolute;
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 14px;
        pointer-events: none;
    }

    /* Dual List Layout */
    .dual-list-container {
        margin-top: 15px;
    }

    .dual-list-container>.col-md-6 {
        margin-bottom: 20px;
    }

    .list-wrapper {
        flex: 1;
        background: #fbfbfb;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #eee;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }

    .list-header {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-box {
        border-radius: 20px !important;
        border: 1px solid #ddd !important;
        padding: 8px 16px !important;
        font-size: 13px;
        margin-bottom: 15px;
        background-color: #fff;
        height: auto !important;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .search-box:focus {
        border-color: #00ACCD !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 172, 205, 0.15) !important;
    }

    /* Scrollable container */
    .scroll-box {
        max-height: 400px;
        overflow-y: auto;
        padding: 5px;
    }

    /* Custom scrollbar */
    .scroll-box::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-box::-webkit-scrollbar-track {
        background: transparent;
    }

    .scroll-box::-webkit-scrollbar-thumb {
        background: #d4d4d4;
        border-radius: 10px;
    }

    .scroll-box::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }

    /* Common card style */
    .channel-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 16px;
        padding: 10px 14px;
        margin-bottom: 10px;
        font-size: 14px;
        cursor: grab;
        transition: all 0.25s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .channel-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #d0d0d0;
        transform: translateY(-2px);
    }

    .channel-item:active {
        cursor: grabbing;
    }

    /* Left drag handle */
    .channel-drag {
        color: #a0a0a0;
        margin-right: 12px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    /* Channel name with icon */
    .channel-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 10px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 500;
        color: #444;
    }

    .channel-info i.glyphicon-blackboard {
        color: #666;
        font-size: 14px;
    }

    /* Action button (right side) */
    .channel-action {
        color: #999;
        cursor: pointer;
        flex-shrink: 0;
        font-size: 16px;
        transition: color 0.2s;
        margin-left: 10px;
    }

    .channel-action:hover {
        color: #e74c3c;
    }

    .bundle-remove {
        color: #999;
        cursor: pointer;
        font-size: 16px;
        transition: color 0.2s;
        margin-left: auto;
    }

    .bundle-remove:hover {
        color: #e74c3c;
    }

    /* Drop area */
    .drop-zone {
        border: 2px dashed #d0d0d0;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        color: #999;
        font-weight: 600;
        font-style: italic;
        letter-spacing: 1px;
        margin-top: 5px;
        background-color: rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 80px;
    }

    .drop-zone:hover {
        border-color: #00ACCD;
        color: #00ACCD;
        background-color: rgba(0, 172, 205, 0.03);
    }




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

    .responsive-center-container {
        width: 50%;
        /* margin: 0 auto; */
    }

    @media (max-width: 768px) {
        .responsive-center-container {
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
    <div data-ng-controller="ContentSetsController as setCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                View Channel Sets
            </h4>
        </div>

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>

        <div class="contentpanel">
            <div class="form-page">
                <form method="post" data-base-validator enctype="multipart/form-data" id="channelSetForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="chnl_id" name="id" value="{{ request()->id }}">
                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- report name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Name<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto">
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="Enter Channel Set Name" ng-model="channlset.name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                        Content Settings
                    </h3>

                    <p class="text-center" style="margin-bottom: 20px; margin-top: 20px;">
                        <i class="glyphicon glyphicon-info-sign"></i>
                        Please select the Channels you want to move
                    </p>

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper" style="width: 85%;">
                            <div class="row dual-list-container">
                                <!-- Available Channels -->
                                <div class="col-md-6">
                                    <div class="list-wrapper">
                                        <div class="list-header">
                                            Available Channels
                                        </div>

                                        <input type="text" id="searchAvailable" class="form-control search-box"
                                            placeholder="Search Channels">

                                        <div class="scroll-box" id="availableBundles">
                                            <div class="channel-item" draggable="false"
                                                data-ng-repeat="channel in setCtrl.channlset track by channel.id"
                                                data-id="@{{ channel.id }}">

                                                <span class="channel-drag">
                                                    <i class="glyphicon glyphicon-move"></i>
                                                </span>

                                                <div class="channel-info">
                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                    @{{ channel.channel_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assigned Channels -->
                                <div class="col-md-6">
                                    <div class="list-wrapper">
                                        <div class="list-header">
                                            Assigned Channels
                                        </div>

                                        <input type="text" id="searchAdded" class="form-control search-box"
                                            placeholder="Search Channels">

                                        <div class="scroll-box">
                                            <div class="channel-item" draggable="false"
                                                ng-model="channlset.assigned_channels"
                                                data-ng-repeat="show in channlset.selectedBundles" data-id="@{{ show.id }}">
                                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                                <div class="channel-info">
                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                    @{{ show.channel_name }}
                                                </div>
                                            </div>

                                            <div id="addedBundles" style="min-height: 145px;">
                                                <div class="drop-zone">DROP HERE</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                        Monitization Settings
                    </h3>

                    <p class="text-center" style="margin-bottom: 20px; margin-top: 20px;">
                        <i class="glyphicon glyphicon-info-sign"></i>
                        Set predefined pricing policy if the content set will be sold as additional extra charged content
                        for Subscription. The predefined pricing policy will be used as default.
                    </p>

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- subscription plan -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Subscription Plans:
                                </label>
                                <div class="col-sm-6">
                                    <label ng-repeat="currency in setCtrl.orgCurrency"
                                        for="@{{currency.short_code | lowercase}}" class="radio-inline me-3"
                                        style="vertical-align: unset;">
                                        <input type="radio" id="@{{currency.short_code | lowercase}}" name="currency"
                                            ng-model="channlset.currency" value="@{{currency.short_code}}">
                                        @{{currency.short_code}}
                                    </label>
                                </div>
                            </div>

                            <!-- monitization plan -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Monetization Type:
                                </label>

                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" ng-model="channlset.monitization_type"
                                            value="0">
                                        <label class="form-check-label">
                                            Rent
                                        </label>
                                    </div>

                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" ng-model="channlset.monitization_type"
                                            value="1">
                                        <label class="form-check-label">
                                            Buy
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <!-- payment method -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Payment Method:
                                </label>
                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_method" value="0"
                                            ng-model="channlset.payment_method">
                                        Per Bundle
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="payment_method" value="1"
                                            ng-model="channlset.payment_method">
                                        Per Item
                                    </label>
                                </div>
                            </div>

                            <p class="text-center"
                                style="margin-bottom: 20px; margin-top: 20px; font-size: 14px; color: #000;">
                                <i class="glyphicon glyphicon-info-sign"></i>
                                The Channel Set Rentl period will be determined by Length of the Subscription
                            </p>

                            <!-- price -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Price<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto price-input-wrapper">
                                    <input type="number" class="form-control price-input" name="price"
                                        placeholder="Enter Channel price" ng-model="channlset.price"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="currency-label">@{{ channlset.currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">
                        Shopping Cart & Client App Settings
                    </h3>

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <p class="text-center"
                                style="margin-bottom: 20px; margin-top: 20px; font-size: 14px; color: #000;">
                                <i class="glyphicon glyphicon-info-sign"></i>
                                Set settings which will be used for Shopping Cart and Client App. You can set access to
                                Shopping Cart in "API Access" tab or in General Settings of your Organization.
                            </p>
                            <!-- image code -->
                            <div class="upload-cover-thumbnail flexbox"
                                data-ng-class="{'has-error': errors.cover_image.has}">

                                <div class="cover-image">
                                    <div class="d-flex align-items-center">
                                        <h4 class="me-2">Cover Image</h4>
                                        <p class="mb-2" style="margin-bottom:10px;">
                                            <i class="glyphicon glyphicon-info-sign"></i>
                                            Please pay attention that the size of the uploaded image can affect Shopping
                                            Cart/client
                                            app performance.
                                        </p>
                                    </div>

                                    <div class="image-content responsive-center-container">
                                        <!-- image fetch code -->
                                        <img ng-show="channlset.cover_image.length > 0"
                                            ng-class="{'active':channlset.cover_image}"
                                            class="uploaded_poster_img uploaded_poster_img_@{{ channlset.id }}" alt=""
                                            ng-src="@{{channlset.cover_image  }}" />

                                        <img ng-show="channlset.cover_image.length == 0"
                                            ng-class="{'active':channlset.cover_image}"
                                            class="uploaded_poster_img uploaded_poster_img_@{{ channlset.id }}" alt=""
                                            ng-src="" />

                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileuploadbox">
                                                <div class="input-append">
                                                    <div class="overlay-content"
                                                        data-ng-class="{'change-image': channlset.cover_image.length > 0}">
                                                        <div class="input">
                                                            <div ng-hide="channlset.cover_image.length"
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
                                                            <div ng-hide="!channlset.cover_image.length"
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
                                                                data-video-index="@{{ channlset.id }}">
                                                        </div>
                                                        <p>{{ __('video::videos.poster_file_hint') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="error-msg" data-ng-show="errors.cover_image.has">
                                        @{{errors.cover_image.message}}</p>
                                </div>

                            </div>

                            <!-- description -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    description<span class="required">*</span>:
                                </label>
                                <div class="col-sm-10 m-auto">
                                    <textarea name="w3review" rows="4" class="form-control" cols="50" name="description"
                                        placeholder="Enter Channel Set Description" ng-model="channlset.description"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                </div>
                            </div>

                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Active:
                                </label>
                                <label class="switch">
                                    <input type="checkbox" ng-model="channlset.is_active" name="is_active"
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

                        <a class="save" href="{{ url()->previous() }}">
                            {{ __('video::videos.back') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- photo open model code -->
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
                            <img id="cover_image" src="" alt="Picture" />
                        </div>
                        <div class="poster_img-preview"></div>
                    </div>
                </div>
                <div class="custom-modal-footer text-right">
                    <button type="button" class="popup-button grey-color"
                        data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                    <button type="button" class="popup-button blue-color"
                        id="submit_cover_image">{{ __('video::videos.submit') }}</button>
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
    <script src="{{asset('adminview/assets/js/organization/contentset/contentset.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection