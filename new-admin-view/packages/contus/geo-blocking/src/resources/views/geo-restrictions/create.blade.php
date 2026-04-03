@extends('base::layouts.default')

@section('stylesheet')
    <!-- <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" /> -->
@endsection

<style>
    .serch-btn {
        justify-content: end;
        display: flex;
    }
</style>

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="GeoRestrictionController as geoRestrctnsCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    {{-- <li><a href="{{ route('api-access.index') }}">{{ __('api-access::index.api_access') }}</a></li>
                    --}}
                    <li><a href="{{ route('geo-restriction.index') }}">Back</a></li>
                    <li class="active" data-ng-if="!isEditMode">Add Geo Restrictions</li>
                    <li class="active" data-ng-if="isEditMode">Edit Geo Restrictions</li>
                </ol>
            </div>
            <br>
            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" data-base-validator>
                            {!! csrf_field() !!}
                            <input type="hidden" id="geo-restriction-id" name="id" value="{{ request()->id }}">

                            <!-- geo/ip restriction -->
                            {{-- <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="geo_restrictions" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Enable
                                        GEO/IP Restriction:</strong></label>
                                <label class="switch" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="geoRestrctnsCtrl.geoRestrictionData.geo_restrictions"
                                        ng-checked="geoRestrctnsCtrl.geoRestrictionData.geo_restrictions == '1'"
                                        name="geo_restrictions">
                                    <span class="slider round"></span>
                                </label>
                            </div> --}}
                            <div class="form-group row" style="margin-bottom: 15px; display: flex; align-items: center;">
                                <label for="geo_restrictions" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Enable
                                        GEO/IP Restriction:</strong></label>
                                <label class="switch col-sm-push-1" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="geoRestrctnsCtrl.geoRestrictionData.geo_restrictions"
                                        ng-checked="geoRestrctnsCtrl.geoRestrictionData.geo_restrictions == 1"
                                        name="geo_restrictions">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <!-- geo blocking name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="name" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Name*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        ng-model="geoRestrctnsCtrl.geoRestrictionData.name" name="name"
                                        placeholder="Enter Name" id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.name.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- geo blocking type -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Type*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="type" id="type"
                                        ng-model="geoRestrctnsCtrl.geoRestrictionData.type" {{--
                                        ng-options="org.organization_name for org in geoRestrctnsCtrl.orgList" --}}
                                        class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Type</option>
                                        <option value="o1">Option 1</option>
                                        <option value="o2">Option 2</option>
                                        <option value="o3">Option 3</option>
                                    </select>
                                    <p class="error-msg">
                                        @{{ errors.type.message }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="page-heading flexbox align-items-center flex-wrap">
                                <h4>Geo Restrictions</h4>
                            </div>

                            <!-- geo protection -->
                            <div class="form-group row" style="margin-bottom: 15px; display: flex; align-items: center;">
                                <label for="ip_restriction" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Enable
                                        GEO/IP Restriction:</strong></label>
                                <label class="switch col-sm-push-1" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="geoRestrctnsCtrl.geoRestrictionData.ip_restriction"
                                        ng-checked="geoRestrctnsCtrl.geoRestrictionData.ip_restriction == '1'"
                                        name="ip_restriction">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p class="error-msg">
                                @{{ errors.ip_restriction.message }}
                            </p>

                            <!-- geo restrictions mode -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Mode*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="mode" id="mode"
                                        ng-model="geoRestrctnsCtrl.geoRestrictionData.mode" class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option value="">Select Mode</option>
                                        <option value="block">Block</option>
                                        <option value="allow">Allow</option>
                                    </select>
                                    <p class="error-msg">
                                        @{{ errors.countries.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- geo restrictions countries -->
                            <div class="form-group row" style="margin-bottom: 15px; margin-top: 15px;">
                                <label class="col-xs-12 col-sm-2 control-label" for="countries"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Countries*:</strong></label>
                                <div class="col-sm-9 col-xs-12">
                                    <select name="Countries" id="countries" data-jquery="select2_custom_ddl"
                                        myPlaceholder="Select Countries"
                                        myValue="geoRestrctnsCtrl.geoRestrictionData.countries"
                                        ng-model="geoRestrctnsCtrl.geoRestrictionData.countries"
                                        ng-options="countri for countri in geoRestrctnsCtrl.countryList"
                                        class="form-control" multiple
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; width: 0px;">
                                        <option value="">Select Countries</option>
                                        {{-- <option value="india">India</option>
                                        <option value="thailand">Thailand</option>
                                        <option value="china">China</option>
                                        <option value="australia">Australia</option> --}}
                                    </select>
                                </div>
                            </div>

                            <!-- geo restriction override -->
                            <div class="form-group row" style="margin-bottom: 15px; margin-top: 85px;">
                                <label for="overide" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>overide*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        ng-model="geoRestrctnsCtrl.geoRestrictionData.overide" overide="overide"
                                        placeholder="Add IP Address" id="overide"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.overide.message }}
                                    </p>
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="geoblockadd" data-ng-if="checkAccess('geo_blocking.create')"
                                    data-ng-if="!isEditMode" data-ng-click="geoRestrctnsCtrl.saveGeoRestrictions($event)">
                                    <strong>Add</strong>
                                </button>
                                <button type="submit" class="button button-blue" id="geoblockEdit" data-ng-if="checkAccess('geo_blocking.edit')"
                                    data-ng-if="isEditMode"
                                    data-ng-click="geoRestrctnsCtrl.updateGeoRestrictions($event)">
                                    <strong>Update</strong>
                                </button>&nbsp;
                                <button type="button" class="button button-gray" ng-click="cancelGeoRestrictions()">
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
    {{-- <script>
        const apiUrl = `{{ env('API_URL') }}`;
    </script> --}}
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/geo-blocking/geoRestrictions.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection
