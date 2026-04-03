
@extends('base::layouts.default')
@section('stylesheet')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.css" />
<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="playlistController as playlistCtrl" >
    
        <div data-ng-if="checkAccess('playlists_all_write')">
    @include('audio::admin.common.gridHeaderLayout' ,
    [
        'pageHeader' => trans('audio::playlists.manage_playlists'), 
        'addBtnText' => "Add Video",
        'control' => 'playlistCtrl.addPlaylist($event)',
    ])
    </div>
   
        
                </div>        
    </div>
    <div class="contentpanel clearfix category_grid" >
        @include('base::partials.errors')
        @include('audio::admin.common.responses')
        <div class="response-msg"></div>
        <div
            data-grid-view
            data-rows-per-page="100"
            data-route-name="videos/playlists"
            data-template-route = "admin/playlists"
            data-request-grid="playlists"
            data-count = "false"
        ></div>
    </div>
    @include('video::admin.playlists.form')
</div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
	<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
	<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
	<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/selectize.js')}}"></script>
    <script src="{{asset('adminview/assets/js/angular-selectize.js')}}"></script>
    <script src="{{asset('adminview/assets/js/selectize_no_results.js')}}"></script>
    <script src="{{asset('adminview/assets/js/playlist/playlistGrid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script> 
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
