@extends('base::layouts.default')

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="AudioGridController as audiogridCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>{{trans('audio::audio.manage_audios')}}</h4>
            <div class="right-side flexbox align-items-center">
                <a title="Upload Audios" href="{{url('admin/audios/audio/add')}}" class="button button-blue">
                    <svg viewBox="0 0 16 18" x="0px" y="0px" width="15px" height="17px">
                        <g id="Layer%201">
                            <path id="Forma%201" d="M 0.4998 17.9998 L 0.4998 15.9999 L 15.4998 15.9999 L 15.4998 17.9998 L 0.4998 17.9998 ZM 11.2141 13.9999 L 4.7855 13.9999 L 4.7855 7.9999 L 0.4998 7.9999 L 7.9999 0.9999 L 15.4998 7.9999 L 11.2141 7.9999 L 11.2141 13.9999 Z" fill="#ffffff"></path>
                        </g>
                    </svg>
                    <span>Upload Audios</span>
                </a>
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
        <div class="contentpanel clearfix video_grid" >
            @include('base::partials.errors')
            <div
                data-grid-view
                data-rows-per-page="10"
                data-route-name="audios"
                data-template-route = "admin/audios/audios"
                data-request-grid="audios"
                data-count = "false"
            ></div>
        </div>
        @include('audio::admin.common.singleRecordDeleteModal')
        @include('audio::admin.common.singleRecordStatusUpdateModal')
    </div>
@endsection

@section('scripts')
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getAudioAssetsUrl('js/audios/grid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection
