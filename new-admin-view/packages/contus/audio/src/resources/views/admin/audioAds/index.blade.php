<style>
#ad-image-progress .progress, #ad-image-progress .file-success p {
    display: none;
}
</style>

@extends('base::layouts.default')

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="AdsGridController as adsgridCtrl" >
    <div class="page-heading flexbox align-items-center flex-wrap">
        <h4>{{trans('audio::audioAds.manage_ads')}}</h4>
        <div class="right-side flexbox align-items-center">
         
                <a data-ng-if="checkAccess('category_all_write')" data-ng-click="adsgridCtrl.addAd($event)"  href="javascript:void(0)" class="button button-blue sidepanel-open">
                    <svg viewBox="0 0 18 18" x="0px" y="0px" width="18px" height="18px">
                        <g>
                            <path d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z" fill="#ffffff"></path>
                        </g>
                    </svg>
                    <span>{{trans('audio::audioAds.add_new_ad')}}</span>
                </a>
         
                
          
        </div>
    </div>

<div class="contentpanel clearfix category_grid" >
    @include('base::partials.errors')
    @include('audio::admin.common.responses')
    <div
        data-grid-view
        data-rows-per-page="10"
        data-route-name="audios/ads"
        data-template-route = "admin/audios/ads"
        data-request-grid="audios/ads"
        data-count = "false"
    ></div>
</div>
<!-- Begin adsForm -->

  <div class="sidepanel">
    <div class="overlay"></div>
      <div class="pop_over_continer form-page">
   <form name="adsForm" id="adsForm" method="POST" data-base-validator data-ng-submit="adsgridCtrl.adsSave($event, adsgridCtrl.ads.id)" enctype="multipart/form-data">
   {!! csrf_field() !!}
    <div class="sidepanel-header flexbox align-items-center">
        <h5 data-ng-if="!adsgridCtrl.ads.id">{{trans('audio::audioAds.add_new_ad')}} </h5>
        <h5 data-ng-if="adsgridCtrl.ads.id">{{trans('audio::audioAds.edit_ad')}} </h5>
    </div>
    <div class="sidepanel-scroll">
        @include('base::partials.errors')
        <!-- Ad Title -->
        <div class="form-group" data-ng-class="{'has-error': errors.ad_name.has}">
            <label>
                {{trans('audio::audioAds.name')}} 
                <span class="required">*</span>
            </label>
            <div class="form-input">
                <input type="text" name="ad_name" maxlength="255" class="form-control" data-ng-model="adsgridCtrl.ads.ad_name" placeholder="{{trans('audio::audioAds.name')}}" value="{{old('title')}}" />
            </div>
            <p class="error-msg" data-ng-show="errors.ad_name.has">@{{ errors.ad_name.message }}</p>
        </div>
        <!-- Ad Title End -->
        <!-- Ad Audio -->
        <div class="form-group" data-ng-class="{'has-error': errors.ad_audio.has}">
            <label>{{__('audio::audioAds.ad_audio')}}</label>
            <div class="form-input edit_video_upload">
                <div class="fileupload fileupload-exists" data-provides="fileupload"><input type="hidden" value="" name="">
                    <div class="input-append">
                        <button class="subtitle_btn">
                            <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                <g>
                                    <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                                </g>
                            </svg>
                            <span class="fileupload-new">Upload Audio</span> 
                            <span class="fileupload-exists">{{ __('audio::audioAds.browse_from_computer') }}</span>
                            <input type="file" class="filestyle" id="ad_audio" name="ad_audio" data-buttonName="btn-primary">
                        </button>                        
                        <div class="upload-status-wrapper" style="display: none;">
                            <span class="upload-success fileupload-preview" ></span>                            
                        </div>
                        <p class="intimation">( {{ __('audio::audioAds.audio_formats_initmation') }} )</p>
                        <p class="error-msg" id="video_error" style="display:none;">{{ __('audio::audioAds.select_valid_file') }}</p>
                        <p class="error-msg" id="size_error" style="display:none;">{{ __('audio::audioAds.ad_filesize_error') }}</p>
                        <p class="error-msg" data-ng-show="errors.ad_audio.has && !adsgridCtrl.ads.newAudioName">@{{ errors.ad_audio.message }}</p>
                    </div>
                    
                </div>
            </div>                     
        </div>
        <!-- Ad Audio End-->
        <!-- Ad Image -->
        <div class="form-group" data-ng-class="{'has-error': errors.image.has}">
            <label class="control-label">{{ trans('audio::audioAds.image') }} </label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-append">
                    <button class="subtitle_btn">
                        <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                            <g>
                                <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                            </g>
                        </svg>                        
                        <span class="fileupload-new">{{trans('audio::audioAds.select_image')}}</span>
                        <span class="fileupload-exists">{{trans('audio::audioAds.change')}}</span>
                        <input type="file" id ="ad-image" accept="image/*" name="image" data-action="api/admin/audios/ads/ad-image"/>                     
                    </button>
                    <span class="fileupload-preview"></span>                    
                    <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload" data-ng-click="adsgridCtrl.removeThumbnailProperty()">{{trans('audio::audioAds.remove')}}</a>
                    <p class="error-msg"></p>
                    <p class="intimation">( {{ __('base::audio.image_formats_initmation') }} )</p>
                </div>
            </div>
            <p class="error-msg" data-ng-show="errors.image.has">@{{ errors.image.message }}</p>
            <div class="form-group">
                <div class="clsFileUpload preview-image">
                    <span id="ad-image-delete" class="delete-image" data-ng-click="adsgridCtrl.deleteArtistImage()" data-ng-show="adsgridCtrl.ads.ad_thumbnail" data-boot-tooltip="true" title="{{trans('audio::audioAds.delete_image')}}"></span>
                    <img id="ad-image-preview" class="preview-image" data-ng-show="adsgridCtrl.ads.ad_thumbnail" data-ng-src="@{{adsgridCtrl.ads.ad_thumbnail}}" width="180px" height="180px">
                    <div id="ad-image-progress" class="hide clsProgressbar"></div>
                    <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                </div>
            </div>
        </div>
        <!-- Ad Image End-->
        <!-- Ad URL -->
        <div class="form-group" data-ng-class="{'has-error': errors.ad_url.has}">
            <label class="control-label">{{trans('audio::audioAds.ad_url')}} <span class="asterisk required">*</span></label>
            <input type="text" name="ad_url" maxlength="255" class="form-control" data-ng-model="adsgridCtrl.ads.ad_url" placeholder="{{trans('audio::audioAds.ad_url')}}" value="{{old('title')}}" />
            <p class="error-msg" data-ng-show="errors.ad_url.has">@{{ errors.ad_url.message }}</p>
        </div>
        <!-- Ad URL End-->
        <!-- Ad Status -->
        <div class="form-group">
            <div class="switch-concept flexbox align-items-center">
                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                    <g>
                        <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
                    </g>
                </svg>
                <div class="swich-content flexbox align-items-center flex-wrap">
                    <span>{{ trans('audio::audioAds.status') }} </span>
                    <div class="right-side flexbox align-items-center">
                        <span class="text">{{ trans('audio::audioAds.message.inactive') }}</span>
                        <label class="switch">
                            <input type="checkbox" name="is_active" data-ng-model="adsgridCtrl.ads.is_active" ng-true-value="'1'" ng-false-value="'0'">
                            <span class="slider round"></span>
                        </label>
                        <span class="text">{{ trans('audio::audioAds.message.active') }}</span>
                    </div>
                </div>
            </div>
            <p class="error-msg"></p>          
        </div> 
        <!-- Ad Status End -->             
    </div>
    @include('audio::admin.common.commonFormFields',['field' =>  'side-panel-form-btns'])

        <!-- AUDIO FILE UPLOAD -->
        <div ng-hide="false" class="contentpanel clearfix add_video_container" id="video_frame" style="display:none;">
            <i class="fa fa-times" aria-hidden="true" ></i>
                <div id="file_drop_area" class="upload_video_container">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <div data-ng-show="adsgridCtrl.numberOfActivePresets > 0">
                        <div id="upload_errors_wrap">
                            <h2 id="upload_error">{{ __('audio::audioAds.upload_error') }}</h2>
                            <h2 id="upload_staus_when_error"></h2>
                        </div>
                        <h2 id="upload_title">
                            <span>{{ __('audio::audioAds.drag_and_drop') }}</span>
                        </h2> 
                        <span class="or">Or</span>
                        <div class="upload_file_input">
                            <input type="file" class="filestyle" id="video_old" name="audio" data-buttonName="btn-primary"
                                    multiple>
                            <span>{{ __('audio::audioAds.browse_from_computer') }}</span>
                        </div>
                        <p>{{ __('audio::audio.accepted_video_formats') }}</p>
                        <p id="video_error">{{ __('audio::audio.select_valid_file') }}</p>
                        <p id="upload_percentage"></p>
                        <div id="video_upload_button_wrap" class="video_upload_div_btn">
                            <button class="btn btn-primary" type="button"
                            title="{{ __('audio::audio.upload') }}">{{ __('audio::audio.upload') }}</button>
                        </div>
                    </div>
                </div>
                <div data-ng-show="adsgridCtrl.numberOfActivePresets > 0 && false"
                        style=" text-align: center; padding-bottom: 20px">
                    <button id="google_drive_upload_button" style="padding: 10px" data-ng-click="adsgridCtrl.onApiLoad()"
                            type="submit" value="Submit">
                        <img src="{{$getBaseAssetsUrl('images/admin/google_drive.png')}}">
                    </button>
                    <!-- The Google API Loader script. -->
                    <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
                    <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>
                </div>
            <div class="col-xs-12 col-sm-12 progress-container">
                <div id="progress-bar-wrap" class="progress progress-striped active">
                    <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <!--AUDIO FILE UPLOAD      -->       
        
    </form>
    </div>
    </div>

</div>
@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getAudioAssetsUrl('js/audioAds/adsGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection
