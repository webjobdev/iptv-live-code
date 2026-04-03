@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'most_viewed'])
<div data-ng-controller="MostViewedVideosController as MostViewedVideosCtrl">
    <div class="contentpanel clearfix">
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="most_viewed/analytics"
            data-template-route = "admin/analytics/most_viewed"
            data-request-grid="admin/analytics/most_viewed"
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
    <script src="{{$getVideoAssetsUrl('js/reports/most_viewed_videos.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection