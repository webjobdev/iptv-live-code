@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'top_category'])
<div data-ng-controller="TopCategoriesController as TopCategoriesCtrl">
    <div class="contentpanel clearfix">
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="top_category/analytics"
            data-template-route = "admin/analytics/top_category"
            data-request-grid="admin/analytics/top_category"
            data-count = "false">
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/raphael-min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/canvasjs.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/morris-0.4.1.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/jquery-plugin-progressbar.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/reports/top_categories.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection