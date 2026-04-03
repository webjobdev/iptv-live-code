@extends('base::layouts.default')

@section('stylesheet')

@endsection

@section('header')
@include('base::layouts.headers.dashboard') 
@endsection

@section('content')
<div data-ng-controller="CollectionGridController as colgridCtrl" >
@include('video::admin.common.subMenu') 
<div class="contentpanel clearfix collection_grid" >
                @include('base::partials.errors')  
    <div class="alert alert-success" data-ng-show="colgridCtrl.showResponseMessage">
       <button type="button" class="close" data-dismiss="alert">×</button>
        <span>@{{colgridCtrl.responseMessage}}</span>
  </div>
    <div 
        data-grid-view 
        data-rows-per-page="10"
        data-route-name="collections"
        data-template-route = "admin/collections"
        data-request-grid="collections"
        data-count = "false"
    ></div>
            </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
	<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
	<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
	<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/collections/collectionGrid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    
@endsection