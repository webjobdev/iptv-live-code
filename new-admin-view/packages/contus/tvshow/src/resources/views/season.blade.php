@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-datetimepicker.min.css')}}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')

    <div ng-controller="TvShowSeasonUploadController as tvsnGridCtrl" data-ng-init="tvsnGridCtrl.init()">
        @include('tvshow::common.subMenu', ['template' => 'add_tvshow_season'])
        {!! csrf_field() !!}
        <div class="contentpanel">
            @include('base::partials.errors')
            <div class="form-page">
                @include('tvshow::SeasonForm')
                @include('vod::common.popup', ['control' => 'tvsnGridCtrl'])
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/ng-tags-input.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/angular/angular-ui.js')}}"></script>
    <script src="{{asset('adminview/assets/js/latestnews/ng-flow-standalone.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
    <script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/commonGeofencing.js')}}"></script>
    <script src="{{asset('adminview/assets/js/tvshow/Tvshow-season-Upload.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/bootstrap-datepicker.min.js')}}"></script>
    <style>
        .st-container {
            overflow-x: inherit;
        }
    </style>
    <script src="{{asset('adminview/assets/js/moment.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection