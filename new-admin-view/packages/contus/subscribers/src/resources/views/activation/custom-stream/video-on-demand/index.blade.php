@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
    <style>
        .bundle-card {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 6px;
            background-color: #f9f9f9;
            cursor: move;
            font-size: 15px;
            line-height: 1.4;
        }

        .sub-text {
            color: #666;
            font-size: 11px;
        }

        .price-line {
            text-decoration: line-through;
            font-size: 11px;
            color: #999;
        }

        /* .dragging {
                        opacity: 0.5;
                    } */

        .drop-zone {
            border: 2px dashed #ccc;
            border-radius: 4px;
            padding: 10px;
            min-height: 200px;
            background-color: #f9f9f9;
        }

        .drop-zone.over {
            border: 2px dashed #337ab7;
            background-color: #eef5ff;
        }

        .bundle-card {
            position: relative;
            padding: 10px;
            /* any other styles */
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 10;
            float: right;
            color: red;
            font-weight: bold;
            cursor: pointer;
            margin-left: 5px;
        }

        .hidden {
            display: none;
        }

        .btn-disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .bundle-item {
            background-color: #f5f5f5;
            border-radius: 50px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .bundle-title {
            font-weight: 600;
            flex-shrink: 0;
        }

        .bundle-sub {
            flex-grow: 1;
            color: #555;
        }

        .bundle-price {
            white-space: nowrap;
            flex-shrink: 0;
        }

        .bundle-price del {
            color: #999;
            margin-right: 3px;
        }

        .bundle-rent {
            color: #333;
            font-weight: 500;
            margin-left: 10px;
        }

        .bundle-remove {
            color: red;
            margin-left: auto;
            cursor: pointer;
            font-size: 16px;
        }

        /* Base nav tabs styling */
        .nav.nav-tabs {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 2px solid #ddd;
            padding-left: 0;
            margin-bottom: 1rem;
        }

        /* Tab items */
        .nav.nav-tabs li {
            margin: 0;
            list-style: none;
        }

        .nav.nav-tabs li a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            color: #000;
            border: 1px solid transparent;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
        }

        /* Active tab */
        .nav.nav-tabs li.active a,
        .nav.nav-tabs li a:hover {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-bottom: 2px solid #00ACCD;
            color: #00ACCD !important;
        }

        /* SVG icons should align with text */
        .nav.nav-tabs li a svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .nav.nav-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                white-space: nowrap;
                border-bottom: none;
            }

            .nav.nav-tabs li {
                flex: 0 0 auto;
                margin-right: 6px;
            }

            .nav.nav-tabs li a {
                border-radius: 6px;
                border: 1px solid #ddd;
            }

            .nav.nav-tabs li.active a {
                border-bottom: 1px solid #00ACCD;
            }
        }

        @media (max-width: 576px) {
            .nav.nav-tabs {
                flex-direction: column;
            }

            .nav.nav-tabs li {
                width: 100%;
                margin: 4px 0;
            }

            .nav.nav-tabs li a {
                width: 100%;
                justify-content: flex-start;
                border-radius: 6px;
            }
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="VideoOnDemadController as vodCtrl">
        <div class="" id="dashboard-page">
            @include('subscribers::layouts.subscribernav')

            <h1 class="mt-3 mb-2" style="font-size: 1.7rem; font-weight: 900;">Custom Media</h1>
            <p>Assign special movies and TV Channels to this subscriber that will be visible only to this account.</p>

            @include('subscribers::layouts.custom-stream-tabview')
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h1 style="font-weight: 900; font-size: 1.2rem;">VOD List</h1><br>
                <div class="right-side flexbox align-items-center">
                    <a data-ng-if="checkAccess('subscribers')" data-toggle="modal" data-target="#add-bundles"
                        class="button button-blue">
                        <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                            <g>
                                <path
                                    d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                    fill="#ffffff" />
                            </g>
                        </svg>
                        <span>Assign VODs</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" enctype="multipart/form-data" id="vod" data-base-validator
                data-ng-submit="vodCtrl.saveVOD($event)">
                {!! csrf_field() !!}
                <input type="hidden" name="subscriber-id" value="{{ request()->query('subscriber-id') }}">

                <!-- Modal -->
                <div class="modal fade" id="add-bundles" tabindex="-1" role="dialog" aria-labelledby="addBundlesLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content p-3">
                            <div class="modal-header">
                                <h4 class="modal-title" id="addBundlesLabel">Add Bundles</h4>
                                <p class="mb-0" style="font-size: 13px;">Drag and drop to assign bundles</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <!-- Available Bundles -->
                                    <div class="col-sm-6">
                                        <strong>Available Bundles</strong>
                                        <input type="text" id="searchAvailable" class="form-control input-sm mb-2"
                                            placeholder="Search Bundles">
                                        <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                            <div id="availableBundles" class="drop-zone panel panel-default p-2"
                                                style="min-height: 130px;">
                                                <div class="bundle-card" ng-repeat="bundle in vodCtrl.VODList"
                                                    draggable="true" data-id="@{{bundle.id}}"
                                                    ng-attr-data-title="@{{bundle.title}}"
                                                    ng-disabled="!vodCtrl.VOD.bundles || !vodCtrl.VOD.bundles.length"
                                                    style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; cursor: move;">
                                                    <i class="fa fa-regular fa-film"></i>
                                                    <strong>@{{bundle.title}}</strong><br>
                                                    <span class="sub-text">Video On Demand</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Added Bundles -->
                                    <div class="col-sm-6">
                                        <strong>Added Bundles</strong>
                                        <input type="text" id="searchAdded" class="form-control input-sm mb-2"
                                            placeholder="Search Bundles">
                                        <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                            <div id="addedBundles" class="drop-zone panel panel-default p-2"
                                                style="min-height: 130px;" ng-model="vodCtrl.VOD.video_on_demand_list">
                                                <!-- Added bundles will appear here via AngularJS -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button id="assignBtn" class="btn btn-primary btn-sm"
                                    ng-click="vodCtrl.assignSelectedBundles();">Assign</button>
                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TV VOD Table Content -->
            <div class="contentpanel product order_list mt-4">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view data-rows-per-page="10" data-route-name="subscriber/video-on-demand"
                    data-request-subscriber-id="{{ request('subscriber-id') }}"
                    data-template-route="admin/subscriber/custom-stream/video-on-demand" data-count="false">
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
    <script src="{{asset('adminview/assets/js/subscribers/video-demand.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection