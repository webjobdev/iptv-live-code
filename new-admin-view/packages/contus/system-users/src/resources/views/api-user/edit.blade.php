@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="ApiAccessController as apiAccessCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    {{-- <li><a href="{{ route('api-access.index') }}">{{ __('api-access::index.api_access') }}</a></li> --}}
                    <li><a href="{{ route('api-access.index') }}">{{ __('api-access::index.api_access') }}</a></li>
                    <li class="active">Edit Api User</li>
                </ol>
            </div>
            <br>

            <div id="form-div" class="tab-pane fade in active"><br>
                {{-- <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">Add DRM Account</h1><br> --}}
                <div class="row">
                    <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" id="customization" data-base-validator
                            data-ng-submit="apiAccessCtrl.updateApiUser($event, id)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="api-access-id" name="id" value="{{ request()->id }}">

                            <!-- api access name -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="name" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Name*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="apiAccessCtrl.apiAccessData.name"
                                        name="name" placeholder="{{ trans('api-access::index.name_placeholder') }}"
                                        id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- api access login -->
                            <div class="form-group row" style="margin-bottom: 15px">
                                <label for="login" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Login*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="apiAccessCtrl.apiAccessData.login"
                                        name="login" placeholder="{{ trans('api-access::index.lgn_placeholder') }}"
                                        id="login"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- api access token -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="token" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Token*:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="apiAccessCtrl.apiAccessData.token"
                                        name="token" placeholder="{{ trans('api-access::index.tkn_placeholder') }}"
                                        id="token"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- choose provider -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Organization*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="organization" id="org-id"
                                        ng-model="apiAccessCtrl.apiAccessData.organization_id" class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                        ng-options="org.id as org.organization_name for org in apiAccessCtrl.orgList">
                                        <option value="">Select Organization</option>
                                    </select>
                                </div>
                            </div>

                            <!-- subscriptions value -->
                            <div class="form-group row table-responsive" style="margin-bottom: 15px;"
                                ng-if="apiAccessCtrl.apiAccessData.organization_id">
                                <h1 style="margin: 10px 10px;"><strong>Subscriptions</strong></h1>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>Organization</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <td>User Device</td>
                                        <td>
                                            <h5 class="mt-4 fw-bold">Subscriptions</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Type</th>
                                                            <th>Length</th>
                                                            <th>Currency</th>
                                                            <th>Autopay</th>
                                                            <th>Devices</th>
                                                            <th>Base Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Test</td>
                                                            <td>Gpay</td>
                                                            <td>100</td>
                                                            <td>INR</td>
                                                            <td>yes</td>
                                                            <td>3</td>
                                                            <td>299</td>
                                                            <td>Minus</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </td>
                                    </tbody>
                                </table>

                            </div>

                            <div class="d-flex justify-between"
                                style="display: flex; justify-content: space-between; margin-top: 50px; width: 100%">

                                <!-- api access add subscription -->
                                <div class="form-group row" style="margin-bottom: 15px; width: 40%;">
                                    {{-- <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Organization*:</strong></label> --}}
                                    <div class="col-sm-10">
                                        <select name="subscription" id="subscription"
                                            ng-model="apiAccessCtrl.apiAccessData.subscription_id" class="form-control"
                                            ng-options="subs.id as subs.name for subs in apiAccessCtrl.subscriptionList"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">Assign Subscription</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-primary">ADD</button>
                                </div>

                                <!-- api access add accessory -->
                                <div class="form-group row" style="margin-bottom: 15px; width: 40%;">
                                    {{-- <label class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Organization*:</strong></label> --}}
                                    <div class="col-sm-10">
                                        <select name="add-on" id="add-on" ng-model="apiAccessCtrl.apiAccessData.add_on"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">Assign Add-Ons</option>
                                            <option value="org_1">Org One</option>
                                            <option value="org_2">Org Two</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">ADD</button>
                                </div>
                            </div>

                            {{-- <!-- publish now -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label for="status" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Status:</strong></label>
                                <label class="switch" style="margin: 10.5px 10px 0px 10px;">
                                    <input type="checkbox" ng-model="apiAccessCtrl.apiAccessData.status"
                                        ng-checked="status == 1" name="status">
                                    <span class="slider round"></span>
                                </label>
                            </div> --}}

                            <!-- button group -->
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-success" id="organizationupdate">
                                    <strong>Update</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-danger"  ng-click="apiAccessCtrl.removeApiAccess({{ request()->id }})">
                                    <strong>Remove</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-default"
                                    ng-click="apiAccessCtrl.cancelApiAccess($event)">
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
    <script src="{{ asset('adminview/assets/js/api-access/index.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection
