@extends('base::layouts.default') 
@section('stylesheet')
<link href="//vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/select2.min.css')}}" />
<style>
    .img-container img {
        max-width: 100%;
    }

    .img-preview {
        display: none;
    }

    .img-cropper {
        width: 50%;
    }

    .uploaded_poster_img {
        display: none;
    }

    .uploaded_img.active {
        display: block;
    }

    .loader-container,
    .poster_loader-container {
        display: none;
        text-align: center;
    }
</style>
@endsection 
@section('header') 
@include('base::layouts.headers.dashboard') 
@endsection 
@section('content')
<style type="text/css">
    .custom-color {
        color: #a94442;
    }

    .kewwords_tag .tagBox {
        height: auto;
        padding: 0;
    }

    .kewwords_tag .edit_keywords {
        float: left;
        padding: 0px 10px;
        background: #fff;
    }

    .kewwords_tag .result_tag {
        display: inline-block;
        background-color: #e4e4e4;
        border: 1px solid #aaa;
        border-radius: 4px;
        cursor: default;
        float: left;
        margin-right: 5px;
        padding: 0 5px;
    }

    .kewwords_tag span.removetag {
        border: none;
        margin-left: 5px;
        padding: 1px;
        cursor: pointer;
    }

    .tagOuterBox:after {
        content: '';
        display: block;
        clear: both;
        background-color: #D0D0D0;
    }

    .tagBox {
        float: left;
    }

    .tagOuterBox .contentEditable {
        float: left;
    }

    div[contentEditable] {
        cursor: pointer;
        background-color: #D0D0D0;
    }
</style>

<div data-ng-controller="VideoUploadController as vgridCtrl">
    @include('video::admin.common.subMenu', ['template' => 'videoupload'])
    @include('video::admin.common.popup', ['control' => 'vgridCtrl'])
    <div class="contentpanel clearfix video_grid">
        @include('base::partials.errors')
        <div class="alert alert-success" data-ng-show="vgridCtrl.showResponseMessage">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span>@{{vgridCtrl.responseMessage}}</span>
        </div>
    </div>

    <!-- Add video container -->
    <div class="contentpanel upload_video_page add_video_container" id="video_frame">
        <div class="upload_video_container">
            <svg data-url="{{url('admin/videos')}}" x="0px" y="0px" width="18px" height="18px" viewBox="0 0 612 612" class="upload_cancel" data-ng-click="vgridCtrl.hideUploadOption($event)">
                <g>
                    <g id="cross">
                        <g>
                            <polygon points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397      306,341.411 576.521,611.397 612,575.997 341.459,306.011    "
                                fill="#252629" />
                        </g>
                    </g>
                </g>
            </svg>
            <form name="videoForm" enctype="multipart/form-data">
                <div id="file_drop_area">
                    <h3 class="upload_pg_title">{{ __('video::videos.upload_new_videos')}}</h3>
                    <div data-ng-show="vgridCtrl.numberOfActivePresets > 0" id="video-initial-upload-container">
                        <div style="display: none" id="upload_errors_wrap">
                            <h2 id="upload_error">{{ __('video::videos.upload_error') }}</h2>
                            <h2 id="upload_staus_when_error"></h2>
                        </div>
                        <h4 style="display: none" id="preset_error" data-ng-show="vgridCtrl.numberOfActivePresets == 0">{{
                            __('video::videos.preset_error') }}</h4>
                        <div class="upload_box">
                            <svg viewBox="0 0 46 47" x="0px" y="0px" width="46px" height="47px">
                                <g>
                                    <path d="M 0.4999 7.4999 L 45.4999 7.4999 L 45.4999 38.4999 L 36.4999 47.4999 L 0.4999 47.4999 L 0.4999 7.4999 Z"
                                        fill="#e7f7ff" />
                                    <path d="M 36.9999 47.4999 L 36.9999 38.4999 L 45.9999 38.4999 L 36.9999 47.4999 Z"
                                        fill="#6dbdcd" />
                                    <path d="M 28.9998 5.2749 C 28.9998 2.1249 24.5247 0.4999 22.9999 0.4999 C 21.5625 0.4999 17 2.1249 17 5.2749 L 17 16.2999 C 17 18.6099 18.9636 20.4999 21.3636 20.4999 C 23.7635 20.4999 25.7271 18.6099 25.7271 16.2999 L 25.7271 7.3749 C 25.7271 5.9049 24.5272 4.7499 22.9999 4.7499 C 21.4726 4.7499 20.2726 5.9049 20.2726 7.3749 L 20.2726 15.2499 L 21.909 15.2499 L 21.909 7.3749 C 21.909 6.7449 22.3453 6.3249 22.9999 6.3249 C 23.6544 6.3249 24.0908 6.7449 24.0908 7.3749 L 24.0908 16.2999 C 24.0908 17.77 22.8908 18.9249 21.3636 18.9249 C 19.8363 18.9249 18.6364 17.77 18.6364 16.2999 L 18.6364 5.2749 C 18.6364 2.965 22.3479 2.075 22.9999 2.075 C 23.652 2.075 27.3635 2.965 27.3635 5.2749 L 27.3635 7.2499 L 28.9998 7.2499 L 28.9998 5.2749 Z"
                                        fill="#3f69a1" />
                                </g>
                            </svg>
                            <h4 id="upload_title" class="drap_drop_title">{{ __('video::videos.drag_and_drop') }}</h4>
                        </div>
                        <span class="or">{{ __('video::videos.or') }}</span>
                        <div class="upload_file_input" id="upload_file_input">
                            <input type="file" accept="video/*,.mkv,.mov,.avi,.mp4" class="filestyle upload_video" id="video" name="video"
                                data-buttonName="btn-primary" multiple>
                            <span>{{ __('video::videos.browse_from_computer') }}</span>
                        </div>
                        <span class="tip_to_upload">{{ __('video::videos.tip')}}</span>
                        <p class="video-accepted-formats" id="video-accepted-formats-text">{{
                            __('video::videos.accepted_video_formats') }}</p>

                        <div style="display:none" id="video_upload_button_wrap" class="video_upload_div_btn">
                            <button class="btn btn-primary" type="button" title="{{ __('video::videos.upload') }}">{{
                                __('video::videos.upload') }}</button>
                        </div>
                    </div>
                    
                </div>
                <div style="display:none" data-ng-show="vgridCtrl.numberOfActivePresets > 0 && false" style=" text-align: center; padding-bottom: 20px">
                    <button id="google_drive_upload_button" style="padding: 10px" data-ng-click="vgridCtrl.onApiLoad()"
                        type="submit" value="Submit">
                        <img src="{{asset('adminview/assets/images/admin/google_drive.png')}}">
                    </button>

                    <!-- The Google API Loader script. -->
                    <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
                    <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>
                </div>
            </form>
        </div>
        <p class="error-msg" style="display: none" id="video_error">{{ __('video::videos.select_valid_file') }}</p>
    </div>
    <!--- Video Details form fields ---->
    
    <div class="contentpanel">
        <div id="video_accordion_wrapper" style="opacity: 0" class="video_accordion_overflow">
            <div class="form-page">
                @include('video::admin.videos.form')
                @include('video::admin.common.popup', ['control' => 'vgridCtrl'])
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
<script src="{{asset('adminview/assets/js/ng-tags-input.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/fine-uploader.min.js')}}"></script>
<script src="//vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
<script src="//vjs.zencdn.net/5.0.2/video.min.js"></script>
<script src="{{asset('adminview/assets/js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/angularjs-datetime-picker.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>

<script src="{{asset('adminview/assets/js/angular/angular-ui.js')}}"></script>

<script src="{{asset('adminview/assets/js/ng-flow-standalone.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/commonGeofencing.js')}}"></script>
<script src="{{asset('adminview/assets/js/videos/videoUpload.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>

@endsection

<!-- <div class="modal-footer">
                        <input type="hidden" class="video-index" name="video-index" value="" />
                        <button type="button" class="btn btn-primary" id="submit_mobile_poster_image">{{
                        __('video::videos.submit') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('base::general.close') }}</button>
                    </div> -->