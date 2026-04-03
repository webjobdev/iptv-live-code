@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />
    <style>
        .page-header-container {
            margin-bottom: 25px;
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            border: 1px solid #edf2f7;
            width: 100%;
            max-width: 900px;
        }

        .form-card-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #f7fafc;
            padding-bottom: 15px;
        }

        .form-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .premium-label {
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            display: block;
        }

        .required {
            color: #e53e3e;
            margin-left: 2px;
        }

        .premium-input {
            border: 2px solid rgba(128, 130, 133, 0.36) !important;
            border-radius: 20px !important;
            padding: 12px 15px !important;
            height: 48px !important;
            font-size: 14px !important;
            transition: all 0.3s ease;
            width: 100%;
            font-weight: 600;
        }

        select.premium-input {
            padding: 0 15px !important;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .premium-input:focus {
            border-color: #00ACCD !important;
            outline: none;
        }

        .error-msg {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }

        .subscription-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid #e2e8f0;
        }

        .subscription-title {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .subscription-title i {
            margin-right: 8px;
            color: #00ACCD;
        }

        .premium-table {
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .premium-table thead th {
            background: #edf2f7;
            color: #4a5568;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            border: none;
            padding: 12px 15px;
            white-space: nowrap;
        }

        .premium-table tbody td {
            padding: 12px 15px;
            font-size: 13px;
            color: #2d3748;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        .action-row {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .action-group {
            flex: 1;
            min-width: 280px;
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .btn-add {
            height: 48px;
            padding: 0 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 10px;
            background: #00ACCD;
            border: none;
            color: #fff;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-add:hover {
            background: #0089a3;
            transform: translateY(-1px);
        }

        .footer-actions {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer-actions .button {
            padding: 0px 10px;
            border-radius: 10px;
            font-weight: 700;
            min-width: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
                border-radius: 0;
            }

            .action-group {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-add {
                width: 100%;
            }

            .footer-actions {
                flex-direction: column;
            }

            .footer-actions .button {
                width: 100%;
            }
        }

        /* Select2 Premium Styling Fix */
        .select2-container--default .select2-selection--single {
            border: 2px solid rgba(128, 130, 133, 0.36) !important;
            border-radius: 20px !important;
            height: 48px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px !important;
            padding-left: 15px !important;
            font-weight: 600;
            color: #4a5568 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 10px !important;
        }
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="ApiAccessController as apiAccessCtrl">
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap" style="margin-bottom: 20px;">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin-bottom: 10px;">
                    <li><a href="{{ route('api-access.index') }}">{{ __('api-access::index.api_access') }}</a></li>
                    <li class="active">Edit API User</li>
                </ol>
            </div>

            <div class="contentpanel">
                <div
                    style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; padding: 20px 10px;">
                    <div class="form-card">
                        <div class="form-card-header">
                            <h2 class="form-card-title">Edit API User Account</h2>
                            <p class="text-muted" style="margin-top: 5px; font-size: 13px;">Modify the details below to
                                update the API user settings.</p>
                        </div>

                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="apiAccessCtrl.updateApiUser($event, {{ request()->id }})">
                            {!! csrf_field() !!}
                            <input type="hidden" id="api-access-id" name="id" value="{{ request()->id }}">

                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label" for="name">
                                            Full Name <span class="required">*</span>
                                        </label>
                                        <input type="text" class="premium-input form-control"
                                            ng-model="apiAccessCtrl.apiAccessData.name" name="name"
                                            placeholder="{{ trans('api-access::index.name_placeholder') }}" id="name">
                                        <p class="error-msg" ng-show="errors.name.has">@{{ errors.name.message }}</p>
                                    </div>
                                </div>

                                <!-- Login -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="premium-label" for="login">
                                            Login Username <span class="required">*</span>
                                        </label>
                                        <input type="text" class="premium-input form-control"
                                            ng-model="apiAccessCtrl.apiAccessData.login" name="login"
                                            placeholder="{{ trans('api-access::index.lgn_placeholder') }}" id="login">
                                        <p class="error-msg" ng-show="errors.login.has">@{{ errors.login.message }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <!-- Token -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="premium-label" for="token">
                                            Access Token <span class="required">*</span>
                                        </label>
                                        <input type="text" class="premium-input form-control"
                                            ng-model="apiAccessCtrl.apiAccessData.token" name="token"
                                            placeholder="{{ trans('api-access::index.tkn_placeholder') }}" id="token">
                                        <p class="error-msg" ng-show="errors.token.has">@{{ errors.token.message }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <!-- Organization -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="premium-label" for="organization">
                                            Associated Organization <span class="required">*</span>
                                        </label>
                                        <select name="organization" id="organization"
                                            ng-model="apiAccessCtrl.apiAccessData.organization"
                                            ng-options="org.organization_name for org in apiAccessCtrl.orgList"
                                            class="premium-input form-control"
                                            infinite-scroll-select="apiAccessCtrl.loadMoreOrg()">
                                            <option value="">Select Organization</option>
                                        </select>
                                        <p class="error-msg" ng-show="errors.organization.has">@{{
                                            errors.organization.message }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Subscriptions List -->
                            <div class="subscription-section" ng-if="apiAccessCtrl.apiAccessData.organization">
                                <h3 class="subscription-title">
                                    <i class="fa fa-tags"></i> Active Subscriptions
                                </h3>
                                <div class="table-responsive">
                                    <table class="table premium-table">
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
                                            <tr ng-repeat="plan in apiAccessCtrl.apiAccessData.selectedPlans">
                                                <td>@{{ plan.subscription_name }}</td>
                                                <td>@{{ plan.subscription_type }}</td>
                                                <td>@{{ plan.subscription_length }} @{{ plan.subs_length_time_type }}</td>
                                                <td>@{{ plan.currency }}</td>
                                                <td>@{{ plan.autopay == 1 ? 'Yes' : 'No' }}</td>
                                                <td>@{{ plan.subscription_devices }}</td>
                                                <td>@{{ plan.price || 'Free' }}</td>
                                                <td class="text-danger" style="cursor: pointer;"
                                                    ng-click="apiAccessCtrl.removeSubscription($index)">
                                                    <i class="fa fa-minus-circle"></i>
                                                </td>
                                            </tr>
                                            <tr
                                                ng-if="!apiAccessCtrl.apiAccessData.selectedPlans || apiAccessCtrl.apiAccessData.selectedPlans.length == 0">
                                                <td colspan="8" class="text-center">No monetization plans found for this
                                                    organization.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Assignment Actions -->
                            <div class="action-row">
                                <div class="action-group">
                                    <div style="flex: 1;">
                                        <label class="premium-label">Assign Subscription</label>
                                        <select name="subscription" id="subscription"
                                            ng-model="apiAccessCtrl.apiAccessData.subscription"
                                            class="premium-input form-control"
                                            ng-options="subs.subscription_name for subs in apiAccessCtrl.apiAccessData.organization.mon_plan">
                                            <option value="">Choose Subscription</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-add"
                                        ng-click="apiAccessCtrl.addSubscription()">ADD</button>
                                </div>

                                <div class="action-group">
                                    <div style="flex: 1;">
                                        <label class="premium-label">Assign Add-Ons</label>
                                        <select name="add-on" id="add-on" ng-model="apiAccessCtrl.apiAccessData.addon"
                                            class="premium-input form-control">
                                            <option value="">Choose Add-Ons</option>
                                            <option value="org_1">Org One</option>
                                            <option value="org_2">Org Two</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-add">ADD</button>
                                </div>
                            </div>

                            <!-- Form Footer -->
                            <div class="footer-actions">
                                <button type="submit" class="button button-blue" style="min-width: 120px;">
                                    Update
                                </button>
                                <button type="button" class="button button-red" style="min-width: 120px;"
                                    ng-click="apiAccessCtrl.removeApiAccess({{ request()->id }})">
                                    Delete
                                </button>
                                <button type="button" class="button button-gray" ng-click="cancelApiAccess()"
                                    style="min-width: 120px;">
                                    Cancel
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