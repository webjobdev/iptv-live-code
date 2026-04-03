@extends('base::layouts.default') 
@section('stylesheet') 

<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
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
<script src="{{asset('adminview/assets/js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/Uploaderseason.js')}}"></script>
<script src="{{asset('adminview/assets/js/ng-flow-standalone.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/season/seasonGrid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
