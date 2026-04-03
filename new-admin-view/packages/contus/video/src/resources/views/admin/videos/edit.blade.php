@extends('base::layouts.default') @section('stylesheet')
<link href="//vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}" />
<link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-datetimepicker.min.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.css" />
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
@include('video::admin.common.subMenu', ['template' => 'edit_video'])


<div class="form-page" data-base-validator data-ng-controller="VideoUploadController as vgridCtrl" id="video-detail-start" data-ng-init="vgridCtrl.fetchData('{{$id}}')">
    <div  class="loader-ring" >
            <div class="loader-ring-light"></div>
            <div class="loader-ring-track"></div>
    </div>
    <div id="video_accordion_wrapper" >
        <div class="form-page">
           @include('video::admin.videos.form')
            
           @include('video::admin.common.popup', ['control' => 'vgridCtrl'])
        </div>         
    </div>

    
</div>


@endsection @section('scripts')
<script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
<script src="{{asset('adminview/assets/js/ng-tags-input.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/angular/angular-ui.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/ng-flow-standalone.js')}}"></script>
<script src="{{asset('adminview/assets/js/fine-uploader.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/commonGeofencing.js')}}"></script>
<script src="{{asset('adminview/assets/js/videos/videoUpload.js')}}"></script>

<script src="{{asset('adminview/assets/js/moment.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
<script src="{{asset('adminview/assets/js/selectize.js')}}"></script>
<script src="{{asset('adminview/assets/js/angular-selectize.js')}}"></script>

@endsection