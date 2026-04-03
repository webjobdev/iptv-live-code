@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'most_commented'])
<div data-ng-controller="MostCommentedVideosController as mostCommentedVideoCtrl">
    <div class="contentpanel clearfix">
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="most_commented/analytics"
            data-template-route = "admin/analytics/most_commented"
            data-request-grid="admin/analytics/most_commented"
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
    <script src="{{$getVideoAssetsUrl('js/reports/most_commented_videos.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection