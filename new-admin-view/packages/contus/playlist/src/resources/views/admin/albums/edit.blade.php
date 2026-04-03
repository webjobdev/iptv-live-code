@extends('base::layouts.default')

@section('stylesheet')
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}"/>
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/ng-tags-input.min.css')}}"/>
<link rel="stylesheet" href="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.css" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard') 
@endsection

@section('content')

    <div ng-app="AlbumEditFormApp" data-ng-controller="AlbumEditFormController as albumEditCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>{{trans('audio::album.edit_album')}}</h4>
        </div>
        @include('audio::admin.albums.album_edit',['form_type' => 'edit', 'control' => 'albumEditCtrl']) 
    </div>
@endsection

@section('scripts')
<script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
<!-- <script src="//vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
<script src="//vjs.zencdn.net/5.0.2/video.min.js"></script> -->
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/audioUpload.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getAudioAssetsUrl('js/albums/edit.js')}}"></script>
<script src="{{$getAudioAssetsUrl('js/common/imagecropper.js')}}"></script>
<script src="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.js"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection