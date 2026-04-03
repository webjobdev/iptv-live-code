@extends('base::layouts.default') @section('stylesheet')
<link href="//vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}" />
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/ng-tags-input.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-datetimepicker.min.css')}}" />
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
<script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-tags-input.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getgeofencingAssetsUrl('js/commonGeofencing.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/livevideoUpload.js')}}"></script>

<script src="{{$getBaseAssetsUrl('js/moment.min.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/common/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/selectize.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular-selectize.js')}}"></script>

@endsection