@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
@include('video::admin.common.subMenu', ['template' => 'region_view'])
<div data-ng-controller="ReportsController as reportCtrl">
    <div class="contentpanel clearfix">
        <div class="panel main_container">            
            <div class="tab-content">
                <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                    <div class="table_loader">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="table_responsive mini-height">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="center">{{trans('base::general.s_no')}}</th>
                                <th>{{ __('video::dashboard.country') }}</th>
                                <th class="center">{{ __('video::dashboard.view_counts') }}</th>
                                <th class="center">{{ __('video::dashboard.percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="center"><!-- @{{((currentPage - 1) * rowsPerPage) + $index +1}} -->1</td>
                                <td><p class="img_description ng-binding">India</p></td>
                                <td class="center">2,000</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">2</td>
                                <td><p class="img_description ng-binding">Australia</p></td>
                                <td class="center">1,000</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">3</td>
                                <td><p class="img_description ng-binding">Usa</p></td>
                                <td class="center">3,000</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">4</td>
                                <td><p class="img_description ng-binding">Japan</p></td>
                                <td class="center">500</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">5</td>
                                <td><p class="img_description ng-binding">China</p></td>
                                <td class="center">1,200</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">6</td>
                                <td><p class="img_description ng-binding">Belgium</p></td>
                                <td class="center">1,200</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">7</td>
                                <td><p class="img_description ng-binding">Australia</p></td>
                                <td class="center">1,200</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">8</td>
                                <td><p class="img_description ng-binding">Usa</p></td>
                                <td class="center">3,000</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">9</td>
                                <td><p class="img_description ng-binding">Japan</p></td>
                                <td class="center">500</td>
                                <td class="center">20%</td>
                            </tr>
                            <tr>
                                <td class="center">10</td>
                                <td><p class="img_description ng-binding">China</p></td>
                                <td class="center">1,200</td>
                                <td class="center">20%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @include('base::layouts.pagination')
            </div>
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
    <script src="{{$getVideoAssetsUrl('js/reports/reports.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection