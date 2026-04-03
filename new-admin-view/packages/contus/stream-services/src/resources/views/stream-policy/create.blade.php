@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />

    <style>
        .accordian-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f2f2f2;
            padding: 10px 0px;
            border-bottom: 2px solid #e3e3e3;
        }

        .toggle {
            margin-right: 0;
        }

        .select-buttons {
            display: flex;
            gap: 10px
        }

        .accordian-div {
            border: 2px solid #e3e3e3;
            border-radius: 5px
        }

        .condn-btn {
            background-color: white;
            border: 2px solid #e3e3e3;
            border-radius: 20px;
            padding: 0px 10px;
            height: 40px;
            width: 80px;
        }

        .condn-btn:hover {
            background-color: #e3e3e3;
        }

        .icon-div {
            display: flex;
        }

        .name-div {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        @media (max-width: 768px) {
            .form-main-div {
                margin-left: 3rem !important;
            }
        }

        @media (max-width: 650px) {
            .name-div {
                flex-direction: column-reverse;
                align-items: flex-start;
            }

            .name-div,
            .name-div .col-sm-6 {
                width: 100%;
            }
        }

        @media (max-width: 415px) {
            .sub-rule-div {
                padding: 20px 20px !important;
            }
        }


        @media (max-width: 400px) {
            .sub-rule-div {
                padding: 20px 15px;
            }
        }
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="StreamingUrlPolicyController as strmUrlCtrl">
        <div class="dashboard-page " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    {{-- <li><a href="{{ route('stream-url-policy.index') }}">{{
                            __('stream-services::index.stream_url_policy') }}</a>
                    </li> --}}
                    <li><a href="{{ route('stream-url-policy.index') }}">Back</a>
                    </li>
                    <li class="active">Add Streaming Url Policy</li>
                </ol>
            </div>
            <br>

            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <div class="mx-auto form-main-div" style="width:70%; margin-left: 10rem;">
                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="strmUrlCtrl.saveStreamUrlPolicy($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="stream-url-id" name="id" value="{{ request()->id }}">

                            <!-- stream policy name -->
                            <div class="form-group row name-div" style="margin-bottom: 15px;">
                                <label for="policy_name" class="col-sm-2 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Name*:</strong></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control"
                                        ng-model="strmUrlCtrl.streamingUrlData.policy_name" name="policy_name"
                                        placeholder="{{ trans('stream-services::index.policy_name_plachldr') }}" id="name"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <p class="error-msg">
                                        @{{ errors.policy_name.message }}
                                    </p>
                                </div>
                                <div class="media" style="margin-bottom: 20px; border-radius: 30px;">
                                    <button type="button" class="button button-blue add-rule-btn" id=""
                                        ng-click="strmUrlCtrl.addRuleSec()">
                                        + Add Rule
                                    </button>
                                </div>
                            </div>

                            <div class="accordion accordian-div" id="accordionExample"
                                ng-repeat="rule in strmUrlCtrl.rules track by $index" style="margin-top: 20px">
                                <div class="card">
                                    <div class="card-header accordian-header" id="headingOne">
                                        <h2 class="mb-0" data-toggle="collapse" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            <button class="btn" type="button">
                                                Rule
                                            </button>
                                        </h2>
                                        <div data-ng-if="checkAccess('stream_services')" class="form-group row toggle"
                                            style="margin-bottom: 0px">
                                            <label class="switch">
                                                <input type="checkbox" name="status" ng-checked="record.status == 1"
                                                    ng-click="strmUrlCtrl.toggleStatus(record)">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="collapseOne" class="collapse show border-2 row" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="form-group row card-body"
                                                style="margin-bottom: 15px; padding: 20px 40px">
                                                <label class="col-sm-2 control-label"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Where
                                                    </strong></label>
                                                <div
                                                    class="row col-lg-10 col-xs-pull-1 col-sm-1 col-sm-offset-2 col-xs-offset-0 col-xs-offset-2">
                                                    <!-- choose Where -->
                                                    <div class="col-lg-5 col-md-6 col-sm-6 m-3" style="margin: 10px;">
                                                        <select name="where" id="where" ng-model="rule.where"
                                                            class="form-control"
                                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                            <option value="platform" selected>Platform</option>
                                                            <option value="os">OS</option>
                                                            <option value="os version">OS Version</option>
                                                            <option value="browser">Browser</option>
                                                            <option value="device type">Device Type</option>
                                                            <option value="network">Network</option>
                                                            <option value="network(ip/subnet)">Network(IP/Subnet)</option>
                                                            <option value="device model">Device Model</option>
                                                            <option value="subscription monetization type">Subscription
                                                                Monetization Type</option>
                                                        </select>
                                                    </div>

                                                    <!-- choose Operator -->
                                                    <div class="col-lg-5 col-md-6 col-sm-6 m-3" style="margin: 10px;">
                                                        <select name="operator" id="" ng-model="rule.operator"
                                                            class="form-control"
                                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                            <option value="">One of</option>
                                                            <option value="includes">Includes</option>
                                                            <option value="excludes">Excludes</option>
                                                        </select>
                                                    </div>

                                                    <!-- choose Condition -->
                                                    <div class="col-lg-5 col-md-6 col-sm-6 m-3" style="margin: 10px;">
                                                        <select name="condition" id="" ng-model="rule.condition"
                                                            class="form-control"
                                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                            <option value="" disabled>Select Plaform</option>
                                                            <optgroup label="Platforms">
                                                                <option value="stb">STB</option>
                                                                <option value="pc-lg">PC/LG</option>
                                                                <option value="ios">ios/tvOS</option>
                                                                <option value="android">Android</option>
                                                                <option value="samsung tv">Samsung TV</option>
                                                                <option value="roke-others">Roke/Others</option>
                                                                <option value="web">WEB</option>
                                                            </optgroup>
                                                            <optgroup label="OS">
                                                                <option value="android">Android</option>
                                                                <option value="ios">iOS</option>
                                                                <option value="tvos">tvOS</option>
                                                                <option value="mac-os">macOS</option>
                                                                <option value="windows">Windows</option>
                                                                <option value="linux">Linux</option>
                                                                <option value="fire-os">FireOS</option>
                                                                <option value="roku">Roku</option>
                                                                <option value="tizen">Tizen</option>
                                                                <option value="web-os">webOs</option>
                                                            </optgroup>
                                                            <optgroup label="Browsers">
                                                                <option value="chrome">Chrome</option>
                                                                <option value="safari">Safari</option>
                                                                <option value="firefox">Firefox</option>
                                                                <option value="opera">Opera</option>
                                                            </optgroup>
                                                            <optgroup label="Device Types">
                                                                <option value="tv">TV</option>
                                                                <option value="stb">STB</option>
                                                                <option value="mobile">Mobile</option>
                                                                <option value="tablet">Tablet</option>
                                                                <option value="desktop">Desktop</option>
                                                            </optgroup>
                                                            <optgroup label="Device Models">
                                                                <option value="amazon">Amazon</option>
                                                            </optgroup>
                                                            <optgroup label="Subscription Monetization Type">
                                                                <option value="paid">Paid</option>
                                                                <option value="free">Free</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="media" style="margin-left: 40px; margin-bottom: 20px;">
                                                <input ng-model="rule.logical_operator" type="button" value="+ OR"
                                                    class="btn condn-btn">
                                                <input ng-model="rule.logical_operator" type="button" value="+ AND"
                                                    class="btn condn-btn">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" style="margin-left: 20px; background-color: #fafafa;"
                                        ng-click="strmUrlCtrl.addSubRuleSec(rule)">+ Add Sub Rule</button>

                                    <div class="accordion accordian-div"
                                        ng-repeat="subRule in rule.subRules track by $index" style="margin: 20px 10px;">
                                        <div class="card">
                                            <div class="card-header accordian-header" id="headingTwo">
                                                <h2 class="mb-0" data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="true" aria-controls="collapseTwo">
                                                    <button class="btn" id="sub-heading" type="button">
                                                        Sub Rule
                                                    </button>
                                                </h2>
                                                <div class="icon-div">
                                                    <div data-ng-if="checkAccess('stream_services')"
                                                        class="form-group row toggle" style="margin-bottom: 0px">
                                                        <label class="switch">
                                                            <input type="checkbox" name="toggle_status"
                                                                ng-checked="record.status == 1"
                                                                ng-click="strmUrlCtrl.toggleStatus(record)">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <button type="button"
                                                        ng-click="strmUrlCtrl.removeSubRuleSec($index, rule)"
                                                        class="glyphicon glyphicon-minus-sign"
                                                        style="margin: 0px 20px; cursor: pointer;">
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="collapseTwo" class="collapse show border-2 row"
                                                aria-labelledby="headingTwo" data-parent="#subAccordionExample"
                                                style="padding: 5px;">
                                                <div class="card-body">
                                                    <div class="form-group row card-body sub-rule-div col-xs-offset-0"
                                                        style="margin-bottom: 15px; padding: 20px 30px">
                                                        <label class="col-sm-2 control-label"
                                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Where
                                                            </strong></label>
                                                        <div class="col-sm-6 col-xs-pull-1 col-sm-offset-1"
                                                            style="right: 6%;">
                                                            <!-- choose Where -->
                                                            <select name="criteria" id="criteria"
                                                                ng-model="subRule.criteria" class="form-control"
                                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                                <option value="" disabled>Criteria</option>
                                                                <option value="platform">Platform</option>
                                                                <option value="os">OS</option>
                                                                <option value="os version">OS Version</option>
                                                                <option value="browser">Browser</option>
                                                                <option value="device type">Device Type</option>
                                                                <option value="network">Network</option>
                                                                <option value="network(ip/subnet)">Network(IP/Subnet)
                                                                </option>
                                                                <option value="device model">Device Model</option>
                                                                <option value="subscription-monetization-type">
                                                                    Subscription
                                                                    Monetization Type</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="media" style="margin-left: 40px; margin-bottom: 20px;">
                                                        <input ng-model="subRule.sub_logical_operator" type="button"
                                                            value="+ OR" class="btn condn-btn">
                                                        <input ng-model="subRule.sub_logical_operator" type="button"
                                                            value="+ AND" class="btn condn-btn">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newRule">
                                    </div>
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center media">
                                <button type="submit" class="button button-blue" id="streamserviceadd">
                                    <strong>Add</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="button button-red" ng-click="cancelStreamUrlPolicy()">
                                    <strong>Delete</strong>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="button button-gray" ng-click="cancelStreamUrlPolicy()">
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
    <script src="{{ asset('adminview/assets/js/stream-services/streamUrl.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection