@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
    <style>
        .nav-pills>li.active>a,
        .nav-pills>li.active>a:hover,
        .nav-pills>li.active>a:focus {
            background-color: #00ACCD;
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="IpRestrictionController as ipRestrctnsCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    {{-- <li><a href="{{ route('api-access.index') }}">{{ __('api-access::index.api_access') }}</a></li>
                    --}}
                    <li><a href="{{ route('ip-restriction.index') }}">Back</a></li>
                    <li class="active" data-ng-if="!isEditMode">Add IP Restrictions</li>
                    <li class="active" data-ng-if="isEditMode">Edit IP Restrictions</li>
                </ol>
            </div>

            <br>
            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" data-base-validator>
                            {!! csrf_field() !!}
                            <input type="hidden" id="ip-restriction-id" name="id" value="{{ request()->id }}">
                            {{-- <div data-grid-view data-rows-per-page="10" data-route-name="geo-blocking"
                                data-template-route="admin/geo-blocking" data-count="false"></div> --}}

                            <!-- geo/ip restriction -->
                            <div class="form-group row" style="margin-bottom: 15px; display: flex; align-items: center;">
                                <label for="ip_restrictions" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Enable
                                        IP Restriction:</strong></label>
                                <label class="switch col-sm-push-1" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="ipRestrctnsCtrl.ipRestrictionData.ip_restrictions"
                                        ng-checked="ipRestrctnsCtrl.ipRestrictionData.ip_restrictions == '1'"
                                        name="ip_restrictions">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p class="error-msg">
                                @{{ errors.ip_restrictions.message }}
                            </p>

                            <!-- geo blocking mode -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Mode*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="mode" id="mode" ng-model="ipRestrctnsCtrl.ipRestrictionData.mode"
                                        {{--
                                        ng-options="org.organization_name for org in apiAccessCtrl.orgList" --}} class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="" disabled selected>Select Mode</option>
                                        <option value="block">Block</option>
                                        <option value="Allow">Allow</option>
                                    </select>
                                    <p class="error-msg">
                                        @{{ errors.mode.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- geo blocking ip_address -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="ip_address" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>IP
                                        Address*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        ng-model="ipRestrctnsCtrl.ipRestrictionData.ip_address" mode="ip_address"
                                        ng-checked="ipRestrctnsCtrl.ipRestrictionData.ip_address ==  1"
                                        placeholder="Enter IP Address" id="ip_address"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.ip_address.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="geoblockadd" data-ng-if="!isEditMode"
                                    data-ng-click="ipRestrctnsCtrl.saveIpRestrictions($event)" data-ng-if="checkAccess('geo_blocking.create')">
                                    <strong>Add</strong>
                                </button>
                                <button type="submit" class="button button-blue" data-ng-if="isEditMode" id="geoblockadd"
                                    data-ng-click="ipRestrctnsCtrl.updateIpRestrictions($event)" data-ng-if="checkAccess('geo_blocking.edit')">
                                    <strong>Update</strong>
                                </button>&nbsp;
                                <button type="button" class="button button-gray" ng-click="cancelIpRestrictions()">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/geo-blocking/ipRestrictions.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection
