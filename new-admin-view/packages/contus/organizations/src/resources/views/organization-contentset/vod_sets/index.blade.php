@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

<style>
    .form-group {
        margin-bottom: 15px;
    }
</style>

@section('content')
    <div data-ng-controller="VodContentSetsController as vodsetCtrl">
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>{{ __('organizations::index.organization') }}</h4>
            </div>

            @include('base::layouts.subnav')

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
            </div>

            @include('organizations::organization-contentset.common.SubMenu')

            <div class="page-heading flexbox align-items-center flex-wrap">
                <div class="right-side flexbox align-items-center">
                    <a data-ng-if="checkAccess('organization.create')" data-ng-click="vodsetCtrl.addContent($event)"
                        href="javascript:void(0)" class="button button-blue sidepanel-open">
                        <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                            <g>
                                <path
                                    d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                    fill="#ffffff">
                            </g>
                        </svg>
                        <span>
                            Add New
                        </span>
                    </a>

                    
                </div>
            </div>

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view data-rows-per-page="10" data-template-route="admin/vod/content-set"
                    data-route-name="vod/content-set" data-count="false">
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
    <script src="{{asset('adminview/assets/js/organization/contentset/vodsets.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection