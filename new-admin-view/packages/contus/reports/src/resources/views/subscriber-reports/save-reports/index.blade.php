@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection


@section('content')
    <div data-ng-controller="SubscriberReportController as subreCtrl">
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>
                    Saved Reports Templates
                </h4>
            </div>

            @include('reports::common.subscriber')

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view 
                data-rows-per-page="10" 
                data-route-name="subscriber-reports" 
                data-template-route="admin/subscriber/save-templates"
                    data-count="false">
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/reports/subscriber-report.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection