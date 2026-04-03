@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'analytics_video'])
<div data-ng-controller="ReportsController as reportCtrl">
    <div class="contentpanel clearfix">
        <div class="panel main_container"> 
            <div class="tab-content">
                <div class="clearfix specific_video_analytic">
                    <div class="video_analytic_head">
                        <div class="selectize-control">
                            <div class="selectize-input">
                                <input type="text" autocomplete="off" tabindex="0" placeholder="Search for videos" >
                            </div>
                        </div>
                        <div class="select">
                            <div class="">

                            </div>
                        </div>
                    </div>
                    <div class="analytics_tab">
                        <ul>                            
                            <li class="active">
                                <a href="javascript:void(0)">Views (100)</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Likes (130)</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Dislikes (130)</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Comments (93)</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Favourites (200)</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 analytics-graph">
                        
                    </div>
                </div>
            </div>
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
    <script src="{{asset('adminview/assets/js/reports/reports.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
@endsection