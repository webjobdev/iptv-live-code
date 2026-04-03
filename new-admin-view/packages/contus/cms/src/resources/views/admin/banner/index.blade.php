@extends('base::layouts.default') 

@section('stylesheet')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.css" />
	<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/banner-default.css')}}" rel="stylesheet">

@endsection

@section('header')

@include('base::layouts.headers.dashboard') @endsection

@section('content')

<div data-ng-controller="bannerController as bannerCtrl">
	<div class="page-heading flexbox align-items-center flex-wrap">
        <h4>{{ trans('cms::staticcontent.banner_management') }}</h4>
        <div class="right-side flexbox align-items-center">
                <a data-ng-if="checkAccess('banner_all_write') && records.length < 15" data-ng-click="bannerCtrl.addStaticContent(record)"  href="javascript:void(0)" class="button button-blue sidepanel-open">
                    <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                        <g>
                            <path d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z" fill="#ffffff"/>
                        </g>
                    </svg>
                    <span>{{trans('cms::staticcontent.add_new_banner')}}</span>
                </a>


                <span data-ng-if="records.length >= 15"> 
                    <a data-ng-if="checkAccess('banner_all_write')"  href="javascript:void(0)" class="button button-blue sidepanel-open disabled">
                        <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                                    <g>
                                        <path d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z" fill="#ffffff"/>
                                    </g>
                            </svg>
                    <span>{{trans('cms::staticcontent.add_new_banner')}}</span>
                    </a>
                </span>



                
            <a href="javascript:void(0)" class="video-demo-ancher" ng-click="showDemoVideo = !showDemoVideo">
                <div class="hotspot__positioner--01">
                    <div class="hotspot__container">
                        <div class="hotspot hotspot--01"></div>
                        <div class="hotspot hotspot--02"></div>
                        <div class="hotspot hotspot--03"></div>
                    </div>
                </div>
                <svg viewBox="0 0 47 48" version="1.1" x="0px" y="0px" width="55px" height="55px" class="demo-ic">
                    <g>
                        <path d="M 31.4865 29.034 C 31.0336 29.034 28.1232 26.3365 28.1232 25.9814 L 28.1232 22.4468 C 28.1232 22.0919 30.969 19.3943 31.4865 19.3943 C 32.0038 19.3943 32.0038 19.8762 32.0038 19.8762 L 32.0038 28.552 C 32.0038 28.552 31.9392 29.034 31.4865 29.034 ZM 26.8619 28.3109 L 16.7722 28.3109 C 16.3435 28.3109 15.9961 28.0664 15.9961 27.7648 L 15.9961 20.6636 C 15.9961 20.3619 16.3435 20.1172 16.7722 20.1172 L 26.8619 20.1172 C 27.2906 20.1172 27.6381 20.3619 27.6381 20.6636 L 27.6381 27.7648 C 27.6381 28.0664 27.2906 28.3109 26.8619 28.3109 ZM 24.2424 19.8419 C 22.903 19.8419 21.817 18.7628 21.817 17.4319 C 21.817 16.1009 22.903 15.0219 24.2424 15.0219 C 25.582 15.0219 26.6678 16.1009 26.6678 17.4319 C 26.6678 18.7628 25.582 19.8419 24.2424 19.8419 ZM 18.9065 19.8419 C 17.835 19.8419 16.9662 18.9786 16.9662 17.9139 C 16.9662 16.8491 17.835 15.986 18.9065 15.986 C 19.9781 15.986 20.8469 16.8491 20.8469 17.9139 C 20.8469 18.9786 19.9781 19.8419 18.9065 19.8419 Z"
                            fill="#ffffff">
                        </path>
                    </g>
                </svg>
            </a>
        </div>
	</div>
	<div class="contentpanel product order_list">
		@include('base::partials.errors')
        <div class="response-msg"></div>
		<div data-grid-view data-rows-per-page="10"
			data-route-name="banner"
			data-template-route="admin/banner"  data-count="false"></div>
	</div>
</div>

@endsection 
@section('scripts')
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script	src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/fine-uploader.js')}}"></script>
<script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
<script src="{{asset('adminview/assets/js/ng-flow-standalone.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/selectize.js')}}"></script>
<script src="{{asset('adminview/assets/js/angular-selectize.js')}}"></script>
<script src="{{asset('adminview/assets/js/selectize_no_results.js')}}"></script>
<script src="{{asset('adminview/assets/js/banner/index.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection