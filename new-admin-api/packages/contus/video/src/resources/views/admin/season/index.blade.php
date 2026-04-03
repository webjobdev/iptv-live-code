@extends('base::layouts.default') 
@section('stylesheet') 

<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection 
@section('header') @include('base::layouts.headers.dashboard')
 @endsection 
 @section('content')
<div data-ng-controller="SeasonGridController as seasongridCtrl">
    @include('video::admin.common.subMenu', ['template' => 'seasons', 'control' => 'seasongridCtrl'])
    @include('video::admin.common.popup', ['template' => 'seasons', 'control' => 'seasongridCtrl'])
    <div class="contentpanel clearfix collection_grid">
        @include('base::partials.errors')
        <div class="response-msg"></div>
        <div data-grid-view data-rows-per-page="10" data-route-name="season" data-template-route="admin/season" data-request-grid="season" data-count="false"></div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploaderseason.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/season/seasonGrid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection
