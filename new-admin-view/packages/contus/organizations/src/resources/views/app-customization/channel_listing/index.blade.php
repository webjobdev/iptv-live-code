@extends('base::layouts.default')

@section('stylesheet')
    <link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link href="{{asset('adminview/assets/css/banner-default.css')}}" rel="stylesheet">
@endsection


@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

<style>
    .form-group {
        margin-bottom: 15px;
    }
</style>

@section('content')
    <div data-ng-controller="ChannelSettingController as chnlsetCtrl">
        <div class="contentpanel" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>{{ __('organizations::index.organization') }}</h4>
            </div>

            @include('base::layouts.subnav')
            <hr>
            @include('organizations::app-customization.common.MainSubNav')

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
            </div>

            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>Channel Listing</h4>
            </div>

            <div class="left-side flexbox align-items-center">
                📋 Here you can manage subscriptions channel list
            </div><br>

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view data-rows-per-page="10"
                    data-template-route="admin/org/app-customization/channel-listing" data-request-id="{{ request('id') }}"
                    data-route-name="organization/monetizationplanss" data-count="false">
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/ng-tags-input.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/angular/angular-ui.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/fine-uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/app-customization/channel-listing/chnl-listing.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection