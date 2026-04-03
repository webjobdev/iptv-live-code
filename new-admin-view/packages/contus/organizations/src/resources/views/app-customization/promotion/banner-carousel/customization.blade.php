<style>
    /* Arrow icon rotation */
    #accordian-content-set .arrow-icon {
        margin-right: 12px;
        font-size: 16px;
        /* transition: transform 0.3s ease; */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #accordian-content-set .arrow-icon {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        #accordian-content-set .arrow-icon {
            margin: 0 0 6px 0;
        }
    }

    /* Flex layout for panel heading */
    .panel-heading.d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .panel-heading.d-flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .panel-heading .heading-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    .chip {
        display: inline-block;
        padding: 6px 12px;
        margin: 4px;
        background-color: #f1f1f1;
        border-radius: 25px;
        font-size: 14px;
        color: #333;
    }

    .chip .close {
        font-size: 16px;
        margin-left: 8px;
        color: #555;
        opacity: 0.6;
    }
</style>


<style>
    #banner-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .upload-cover-thumbnail {
        /* border: 1px solid #ddd; */
        /* border-radius: 8px; */
        /* padding: 8px; */
        /* margin: 10px; */
        /* background: #fff; */
        /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
        /* max-width: 250px; */
        /* flex: 1 1 220px; */
        /* position: relative; */
    }

    .upload-cover-thumbnail img {
        width: 100%;
        border-radius: 6px;
        height: auto;
    }

    .fileuploadbox {
        /* text-align: center;
        padding: 10px;
        border: 2px dashed #aaa;
        border-radius: 6px;
        cursor: pointer; */
    }

    .fileuploadbox:hover {
        /* background: #f9f9f9; */
    }

    .add-banner {
        border: 2px dashed #aaa;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        color: #666;
        margin: 10px;
        cursor: pointer;
        flex: 1 1 220px;
        max-width: 250px;
    }

    .add-banner:hover {
        background: #f9f9f9;
    }

    /* Switch style */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
        margin-right: 6px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    .banner-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .banner-actions .status {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: #333;
    }
</style>

@extends('base::layouts.default')

@section('stylesheet')
    <link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link href="{{asset('adminview/assets/css/banner-default.css')}}" rel="stylesheet">
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="CustomizationController as ctzCtrl">
        <div class="contentpanel" id="dashboard-page">
            <div class="form-page">
                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4>{{ __('organizations::index.organization') }}</h4>
                </div>

                @include('base::layouts.subnav')
                <hr>
                @include('organizations::app-customization.common.MainSubNav')
                <hr>
                @include('organizations::app-customization.common.SubNav')
                <hr>

                <!-- accordian -->
                <div class="panel-group" id="accordian-content-set" role="tablist" aria-multiselectable="true"
                    ng-repeat="record as ctzCtrl.fetchMonPlan track by $index"
                    style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;">
                    <div class="panel panel-default" style="border-radius: 5px;">
                        <div class="panel-heading d-flex" role="tab" id="heading-content-set">
                            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                                href="#collapse-content-set" aria-expanded="false" aria-controls="collapse-content-set"
                                class="collapsed"
                                style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;"></i>
                                <label
                                    style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                                    Banner Carousel for &nbsp;&nbsp;&nbsp; Premium Plan
                                </label>
                            </a>
                            <td class="table-actions">
                                <div class="flexbox align-items-center justify-center">

                                    <div class="column edit_table_icon tooltip-parent">
                                        <label class="table_action">1 Item</label>
                                        <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                    </div>

                                    <div data-ng-if="checkAccess('channels')" class="form-group row"
                                        style="margin-bottom: 0px; margin-right: 5px;">
                                        <label class="switch">
                                            <input type="checkbox" ng-checked="record.is_active == 1"
                                                ng-click="togglePublishNow(record, record.id)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="tooltip-parent" data-ng-if="checkAccess('channels')">
                                        <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                            data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                            class="tooltips delete_table_icon" data-boot-tooltip="true"
                                            data-original-title="">
                                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g data-original-title="" title="">
                                                    <path
                                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545"></path>
                                                </g>
                                            </svg>
                                            <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </div>
                    </div>

                    <div id="collapse-content-set" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="heading-content-set">
                        <div class="panel-body">
                            <!-- Subscriptions -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="prefix" class="col-sm-4 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Subscriptions<span class="required">*</span>:
                                </label>
                                <div class="col-sm-4">
                                    <div class="form-input">
                                        <select allowClear="1" data-jquery="select2_custom_ddl" name="organization"
                                            class="admin_category_sub form-control select2_custom_ddl"
                                            myValue="channel.organization" myPlaceholder="Select Subscriptions"
                                            data-ng-model="channel.organization">
                                            <option value="">--- Select Subscriptions ---</option>
                                            <option value="Days">Dimond</option>
                                            <option value="Months">Bronz</option>
                                            <option value="Months">Silver</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="chip">
                                        Premium Plan
                                        <span class="close" data-dismiss="chip">&times;</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto scrolling -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="prefix" class="col-sm-4 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Auto Scrolling<span class="required">*</span>:
                                </label>
                                <div class="col-sm-4">
                                    <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                        <label class="switch">
                                            <input type="checkbox" ng-checked="record.is_active == 1"
                                                ng-click="togglePublishNow(record, record.id)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <div class="col-sm-2 m-auto">
                                        <input type="text" class="form-control" name="name" required
                                            placeholder="Enter Seconds" ng-model="subscr.name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Seconds
                                    </label>
                                </div>
                            </div>

                            <!-- banner code -->
                            <div id="banner-wrapper">
                                <div class="upload-cover-thumbnail flexbox"
                                    ng-repeat="banner in ctzCtrl.banners track by $index"
                                    data-ng-class="{'has-error': errors.poster_image.has}">
                                    <div class="thumbnail-image">
                                        <h4>Banners</h4>
                                        <div class="image-content">
                                            <img ng-show="ctzCtrl.product.product_image.length > 0"
                                                ng-class="{'active': ctzCtrl.product.product_image}"
                                                class="uploaded_img uploaded_img_@{{ ctzCtrl.product.id }}" alt=""
                                                ng-src="@{{ ctzCtrl.product.product_image }}">

                                            <img ng-show="ctzCtrl.product.product_image.length == 0"
                                                ng-class="{'active': ctzCtrl.product.product_image}"
                                                class="uploaded_img uploaded_img_@{{ ctzCtrl.product.id }}" alt=""
                                                ng-src="">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileuploadbox">
                                                    <div class="input-append">
                                                        <div class="overlay-content"
                                                            data-ng-class="{'change-image': ctzCtrl.product.product_image.length > 0}">
                                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1"
                                                                x="0px" y="0px" width="27px" height="27px">
                                                                <g>
                                                                    <path opacity="0.702"
                                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                        fill="#ffffff"></path>
                                                                </g>
                                                            </svg>
                                                            <div class="input">
                                                                <div ng-hide="ctzCtrl.product.product_image.length">
                                                                    <span>Change Product Image</span>
                                                                </div>
                                                                <div ng-hide="!ctzCtrl.product.product_image.length"
                                                                    class="ng-hide flexbox align-items-center">
                                                                    <svg class="change_img_ic" x="0px" y="0px" width="13"
                                                                        height="13" viewBox="0 0 528.899 528.899">
                                                                        <g>
                                                                            <path
                                                                                d=" M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981 c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611 C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069 L27.473,390.597L0.3,512.69z"
                                                                                fill="#ffffff"></path>
                                                                        </g>
                                                                    </svg>
                                                                    <span>Change Product Image</span>
                                                                </div>
                                                                <input type="file" class="uploadImg" name="image"
                                                                    onchange="ctzCtrl.previewImage($event, $index)"
                                                                    data-video-index="@{{ ctzCtrl.product.id }}">
                                                            </div>
                                                            <p>(Upload a cover image with minimum dimension of 355x200)</p>
                                                        </div><br>
                                                        <h4>Banner @{{$index + 1}}</h4>
                                                        <div class="banner-actions">
                                                            <div data-ng-if="checkAccess('channels')" class="form-group row"
                                                                style="margin-bottom: 0px; margin-right: 5px;">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        ng-checked="record.is_active == 1"
                                                                        ng-click="togglePublishNow(record, record.id)">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <!-- edit button code -->
                                                                <div class="column edit_table_icon tooltip-parent">
                                                                    <a class="table_action"
                                                                        href="{{url('admin/channel/channel-details-edit')}}/@{{ encodeId(record.id) }}">

                                                                        <svg viewBox="0 0 12 11" x="0px" y="0px"
                                                                            width="12px" height="11px">
                                                                            <g>
                                                                                <path
                                                                                    d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                                    fill="#454545" />
                                                                            </g>
                                                                        </svg>
                                                                    </a>
                                                                    <span
                                                                        class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                                                </div>

                                                                <!-- delete button code -->
                                                                <div class="tooltip-parent banner-remove"
                                                                    data-ng-if="checkAccess('channels')">
                                                                    <span ng-mouseover="getTooltip($event)"
                                                                        data-toggle="modal" data-target="#deleteModal"
                                                                        ng-click="ctzCtrl.removeBanner(banner)"
                                                                        class="tooltips delete_table_icon"
                                                                        data-boot-tooltip="true" data-original-title="">
                                                                        <svg viewBox="0 0 11 12" x="0px" y="0px"
                                                                            width="11px" height="12px">
                                                                            <g data-original-title="" title="">
                                                                                <path
                                                                                    d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                                                    fill="#454545">
                                                                                </path>
                                                                            </g>
                                                                        </svg>
                                                                        <span
                                                                            class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add Banner Button -->
                                <div class="add-banner" ng-click="ctzCtrl.addBanner()"
                                    ng-if="ctzCtrl.banners.length < ctzCtrl.maxBanners">
                                    <div style="font-size: 24px;">+</div>
                                    <div>Add Banner</div>
                                </div>
                            </div>

                            <!-- bottom button code -->
                            <div class="bottom-button text-right ">
                                <button data-ng-click="channelGridCtrl.saveChannel($event, channel.id)" class="publish-now">
                                    Update
                                </button>&nbsp;&nbsp;&nbsp;

                                <button data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)"
                                    class="button button-red">
                                    Delete
                                </button>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- open image mode -->
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
    // Simple remove functionality
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("close")) {
            e.target.parentElement.remove();
        }
    });
</script>

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/fine-uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/app-customization/customization.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection