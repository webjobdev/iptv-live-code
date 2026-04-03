@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'most_favourite'])
<div data-ng-controller="MostFavouriteVideosController as MostFavouriteVideosCtrl">
    <div class="contentpanel clearfix">
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="most_favourite/analytics"
            data-template-route = "admin/analytics/most_favourite"
            data-request-grid="admin/analytics/most_favourite"
            data-count = "false">
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('adminview/assets/js/raphael-min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/morris-0.4.1.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/jquery-plugin-progressbar.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/reports/most_favourite_videos.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection