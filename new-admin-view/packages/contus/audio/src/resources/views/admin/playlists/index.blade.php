@extends('base::layouts.default')

@section('stylesheet')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.css" />
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="PlaylistsGridController as playlistgridCtrl" >
    @include('audio::admin.common.gridHeaderLayout' ,
    [
        'pageHeader' => trans('audio::playlists.manage_playlists'), 
        'addBtnText' => trans('audio::playlists.add_playlist'),
        'control' => 'playlistgridCtrl.addPlaylist($event)',
    ])
    <div class="contentpanel clearfix category_grid" >
        @include('base::partials.errors')
        @include('audio::admin.common.responses')
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="playlists"
            data-template-route = "admin/audios/playlists"
            data-request-grid="playlists"
            data-count = "false"
        ></div>
    </div>
    @include('audio::admin.playlists.form')
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
    <script src="{{$getBaseAssetsUrl('js/selectize.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/angular-selectize.js')}}"></script>
    <script src="{{$getAudioAssetsUrl('js/playlists/playlistsGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script> 
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection