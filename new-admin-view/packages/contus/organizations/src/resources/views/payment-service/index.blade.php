@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')

    <div data-ng-controller="PaymentServiceController as pytsveCtrl">
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>Payment Service Setting</h4>
            </div>

            @include('base::layouts.subnav')
            @include('organizations::payment-service.common.menu')

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view data-rows-per-page="10" data-route-name="organization/payment-service" 
                    data-template-route="admin/organizations/payment-service" data-count="false"></div>
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
    <script src="{{asset('adminview/assets/js/organization/payment-service/payment-service.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection