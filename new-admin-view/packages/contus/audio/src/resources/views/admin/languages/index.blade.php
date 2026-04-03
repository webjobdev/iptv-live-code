@extends('base::layouts.default')

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="LanguagesGridController as langgridCtrl" >
    @include('audio::admin.common.gridHeaderLayout' ,
    [
        'pageHeader' => trans('audio::languages.manage_languages'), 
        'addBtnText' => trans('audio::languages.add_language'),
        'control' => 'langgridCtrl.addLanguage($event)',
    ])
    <div class="contentpanel clearfix category_grid" >
        @include('base::partials.errors')
        @include('audio::admin.common.responses')
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="languages"
            data-template-route = "admin/audios/languages"
            data-request-grid="languages"
            data-count = "false"
        ></div>
    </div>
    @include('audio::admin.languages.form')
</div>
@endsection

@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getAudioAssetsUrl('js/languages/languagesGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script> 
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection