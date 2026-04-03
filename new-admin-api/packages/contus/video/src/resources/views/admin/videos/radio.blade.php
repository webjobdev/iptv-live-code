@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/ng-tags-input.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-datetimepicker.min.css')}}" />

@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection
@section('content')
<div ng-controller="VideoUploadController as vgridCtrl" data-ng-init="vgridCtrl.init()" >
		@include('video::admin.common.subMenu', ['template' => 'add_radio_stream'])
		{!! csrf_field() !!}
		<div class="contentpanel">
			@include('base::partials.errors')

			<div class="form-page">
				@include('video::admin.videos.radioform')
				@include('video::admin.common.popup', ['control' => 'vgridCtrl'])
			</div>
		</div>
</div>

@endsection @section('scripts')


<script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-tags-input.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/latestnews/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getgeofencingAssetsUrl('js/commonGeofencing.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/radioUpload.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/common/bootstrap-datepicker.min.js')}}"></script>
<style>
	.st-container {
		overflow-x: inherit;
	}
</style>
<script src="{{$getBaseAssetsUrl('js/moment.min.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/common/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>


    
@endsection
