@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />

    <style>
        /* Main Container */
        .permission-form-container {
            background: #fff;
            padding: 25px 35px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            max-width: 100%;
            margin: 0 auto;
        }

        /* Typography & Labels */
        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            display: block;
        }

        /* Permission Rows - General */
        .panel-heading,
        .permission-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
            margin: 0;
            min-height: 70px;
        }

        .panel-heading:last-child {
            border-bottom: none;
        }

        .heading-div {
            flex: 0 0 25%;
            /* Fixed width for labels to ensure alignment */
            max-width: 25%;
            padding-right: 15px;
        }

        .heading-div .panel-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            color: #333;
            display: flex;
            align-items: center;
        }

        .label-div {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        /* Checkbox Area - Grid Layout */
        .check-div {
            flex: 0 0 75%;
            /* Occupy remaining space */
            max-width: 75%;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            /* 5 Equal Columns */
            gap: 15px;
            align-items: start;
            /* Align to top to handle multi-line items like View + Security Search */
        }

        .checkbox {
            margin: 0;
            display: flex;
            align-items: center;
            min-height: 24px;
        }

        /* Handle grouped checkboxes (like View + Security Search) */
        .checkbox.d-flex.flex-column {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            /* Gap between vertically stacked items */
        }

        .checkbox label {
            padding-left: 0;
            font-weight: 500;
            color: #555;
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: 0;
            white-space: nowrap;
            /* Prevent wrapping */
        }

        .checkbox input[type="checkbox"] {
            margin: 0 8px 0 0;
            position: relative;
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 1px solid #ccc;
        }

        /* Accordion / Collapsible Sections */
        .panel-group {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            background: #fff;
        }

        .panel-group .panel-heading {
            background-color: #fafafa;
            border-bottom: 1px solid #e5e5e5;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .panel-group .panel-heading:hover {
            background-color: #f1f1f1;
        }

        .panel-body {
            padding: 0;
            border-top: none;
        }

        .panel-body .panel-heading {
            background-color: #fff;
            padding-left: 30px;
            /* Indent sub-items */
            border-bottom: 1px solid #f0f0f0;
        }

        .panel-body .panel-heading:last-child {
            border-bottom: none;
        }

        /* Individual Rules Container */
        .individual-rules {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            margin-bottom: 20px;
            background: #fff;
        }

        /* Toggle Icons */
        .accordion-arrow-toggle:before {
            font-family: 'Glyphicons Halflings';
            /* Or FontAwesome if available */
            content: "\e259";
            /* Chevron down */
            font-size: 12px;
            color: #888;
            transition: transform 0.3s;
        }

        .accordion-arrow-toggle.collapsed:before {
            transform: rotate(-90deg);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .heading-div {
                flex: 0 0 30%;
                max-width: 30%;
            }

            .check-div {
                flex: 0 0 70%;
                max-width: 70%;
                grid-template-columns: repeat(3, 1fr);
                /* 3 columns on tablet */
            }
        }

        @media (max-width: 768px) {
            .panel-heading {
                flex-direction: column;
                align-items: flex-start;
                height: auto;
                padding: 15px;
            }

            .heading-div {
                width: 100%;
                max-width: 100%;
                margin-bottom: 15px;
            }

            .check-div {
                width: 100%;
                max-width: 100%;
                grid-template-columns: repeat(2, 1fr);
                /* 2 columns on mobile */
                gap: 15px;
            }
        }
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="PermissionRuleController as permsnRuleCtrl">
        <div class=" " id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <ol class="breadcrumb">
                    <li><a href="{{ route('permission.index') }}">Back</a></li>
                    <li class="active">Add Permission Rule</li>
                </ol>
            </div>

            <div id="edit-form-div" class="tab-pane fade in active">
                <div class="row">
                    <div class="permission-form-container mx-auto">
                        <form method="POST" enctype="multipart/form-data" data-base-validator
                            data-ng-submit="permsnRuleCtrl.savePermissionRule($event)">
                            {!! csrf_field() !!}

                            <input type="hidden" id="permission-rule-id" ng-model="permsnRuleCtrl.permissionRuleData.id"
                                value="{{ request()->id }}">

                            <!-- rule name -->
                            <div class="form-group row" style="margin-bottom: 25px;">
                                <label for="rule_name" class="col-xs-12 col-sm-3 control-label form-label">Rule
                                    Name*:</label>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" class="form-control custom-input"
                                        ng-model="permsnRuleCtrl.permissionRuleData.rule_name"
                                        placeholder="{{ trans('partner-programs::index.pname_placeholder') }}" id="name">
                                </div>
                            </div>

                            <!-- organization -->
                            <div class="form-group row" style="margin-bottom: 30px;">
                                <label class="col-xs-12 col-sm-3 control-label form-label" for="organization">Organization
                                    Restrictions*:</label>
                                <div class="col-sm-9 col-xs-12">
                                    <select multiple data-jquery="select2_custom_ddl"
                                        myValue="permsnRuleCtrl.permissionRuleData.organization_id"
                                        myPlaceholder="Select organization"
                                        ng-init="permsnRuleCtrl.permissionRuleData.organization_id" name="organization"
                                        class="admin_category_sub form-control select2_custom_ddl custom-input"
                                        data-ng-model="permsnRuleCtrl.permissionRuleData.organization_id"
                                        style="width: 100%;"
                                        ng-options="org.id as org.organization_name for org in permsnRuleCtrl.orgList">
                                    </select>
                                </div>
                            </div><br><br>

                            <!-- permission rule tables -->

                            <!-- Dashboard Permissions -->
                            <div class="panel-heading">
                                <div class="heading-div">
                                    <h5 class="panel-title">
                                        <div class="label-div">
                                            <input type="hidden" name="module_name"
                                                ng-model="permsnRuleCtrl.permissionRuleData.modules['dashboard'].name"
                                                ng-init="permsnRuleCtrl.initModule('dashboard', 'dashboard')">
                                            <label for="dashboard">Dashboard</label>
                                        </div>
                                    </h5>
                                </div>

                                <!-- Dashboard Permissions -->
                                <div class="check-div col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="dash_all" id="dash_all" value="All"
                                                ng-change="permsnRuleCtrl.toggleModulePermissions('dashboard', 'All')"
                                                ng-model="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['All']"
                                                ng-disabled="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['Hide']">All</label>
                                    </div>
                                    <div class="checkbox">
                                        <label for="view">
                                            <input type="checkbox" name="dash_view" id="dash_view" value="view"
                                                ng-model="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['View']"
                                                ng-disabled="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['Hide']">View</label>
                                    </div>
                                    <!-- <div class="checkbox"> -->
                                    {{-- <label for="create"><input type="checkbox" name="dash_create"
                                            id="dash_create">Create</label> --}}
                                    <!-- </div> -->
                                    <div class="checkbox">
                                        <label for="dash_edit"><input type="checkbox" name="dash_edit" id="dash_edit"
                                                value="edit"
                                                ng-model="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['Edit']"
                                                ng-disabled="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['Hide']">Edit</label>
                                    </div>
                                    <div class="checkbox">
                                        {{-- <label for="delete"><input type="checkbox" name="dash_delete"
                                                id="dash_delete">Delete</label> --}}
                                    </div>
                                    <div class="checkbox">
                                        <label for="dash_hide">
                                            <input type="checkbox" name="dash_hide" id="dash_hide" value="hide"
                                                ng-model="permsnRuleCtrl.permissionRuleData.modules['dashboard'].permissions['Hide']">
                                            Hide
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Organizations Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145675">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145675"
                                                    aria-expanded="false" aria-controls="collapse-3145675">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].name"
                                                            ng-init="permsnRuleCtrl.initModule('organization', 'organization')">
                                                        Organization
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>

                                        <div class="check-div col-sm-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="org_all" value="All"
                                                        ng-change="permsnRuleCtrl.toggleModulePermissions('organization', 'All')"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['All']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">All</label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="view"><input type="checkbox" name="org_view"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['View']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">View
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="create"><input type="checkbox" name="org_create"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Create']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">Create</label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="edit"><input type="checkbox" name="org_edit"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Edit']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">Edit</label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="delete"><input type="checkbox" name="org_delete"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Delete']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">Delete</label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="hide"><input type="checkbox" name="org_hide"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['organization'].permissions['Hide']">Hide</label>
                                            </div>
                                        </div>

                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Organizations Permissions -->
                                <div id="collapse-3145675" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145675">
                                    <div class="panel-body">

                                        <!------------->
                                        <div class="panel-heading" ng-repeat="orgMod in permsnRuleCtrl.orgModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(orgMod.key, orgMod.label)">
                                                @{{ orgMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Create') === -1">
                                                </div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Delete') === -1">
                                                </div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ orgMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(orgMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[orgMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="orgMod.permissions.indexOf('Hide') === -1">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End of Organizations Permissions -->

                            <!-- Subscribers Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145676">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="accordion-toggle collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145676"
                                                    aria-expanded="false" aria-controls="collapse-3145676">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].name"
                                                            ng-init="permsnRuleCtrl.initModule('subscribers', 'subscribers')">
                                                        Subscribers
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>

                                        <div class="check-div col-sm-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="subs_all" value="All"
                                                        ng-change="permsnRuleCtrl.toggleModulePermissions('subscribers', 'All')"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['All']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    All
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox d-flex flex-column">
                                                <label for="view">
                                                    <input type="checkbox" name="subs_view"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['View']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    View
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="create">
                                                    <input type="checkbox" name="subs_create"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Create']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    Create
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="edit">
                                                    <input type="checkbox" name="subs_edit"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Edit']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    Edit
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="delete">
                                                    <input type="checkbox" name="subs_delete"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Delete']"
                                                        ng-disabled="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    Delete
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="ss">
                                                    <input type="checkbox" name="subs_ss"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Security Search']">
                                                    Security Search
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="hide">
                                                    <input type="checkbox" name="subs_hide"
                                                        ng-model="permsnRuleCtrl.permissionRuleData.modules['subscribers'].permissions['Hide']">
                                                    Hide
                                                </label>
                                            </div>
                                        </div>

                                        <div class="accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Subscribers Permissions -->
                                <div id="collapse-3145676" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145676">
                                    <div class="panel-body">
                                        <!------------->
                                        <div class="panel-heading" ng-repeat="subsMod in permsnRuleCtrl.subsModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(subsMod.key, subsMod.label)">
                                                @{{ subsMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Create') === -1">
                                                </div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Delete') === -1">
                                                </div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="subsMod.permissions.indexOf('Hide') === -1">
                                                </div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in subsMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ subsMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(subsMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[subsMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Subscribers Permissions -->

                            <!-- Individual1 Rules -->
                            <div class="individual-rules">
                                <!-- Module -->
                                <div class="panel-heading" ng-repeat="ind1Mod in permsnRuleCtrl.indModules1">
                                    <div class="heading-div">
                                        <h5 class="panel-title">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind1Mod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(ind1Mod.key, ind1Mod.label)">
                                                @{{ ind1Mod.label }}
                                            </div>
                                        </h5>
                                    </div>

                                    <!-- Permissions -->
                                    <div class="check-div col-sm-9">
                                        <div class="checkbox" ng-repeat="permsn in ind1Mod.permissions">
                                            <label for="view">
                                                <input type="checkbox" name="@{{ ind1Mod.key }}[]" value="@{{ permsn }}"
                                                    ng-change="permsnRuleCtrl.toggleModulePermissions(ind1Mod.key, permsn)"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind1Mod.key].permissions[permsn]"
                                                    ng-disabled="permsn!='Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[ind1Mod.key].permissions['Hide']">
                                                @{{ permsn }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Categories Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145677">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145677"
                                                    aria-expanded="false" aria-controls="collapse-3145677">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['categories'].name"
                                                            ng-init="permsnRuleCtrl.initModule('categories', 'categories')">
                                                        Categories
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Categories Permissions -->
                                <div id="collapse-3145677" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145677">
                                    <div class="panel-body">
                                        <div class="panel-heading" ng-repeat="catMod in permsnRuleCtrl.catgryModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(catMod.key, catMod.label)">
                                                @{{ catMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Create') === -1">
                                                </div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Delete') === -1">
                                                </div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="catMod.permissions.indexOf('Hide') === -1">
                                                </div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in catMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ catMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(catMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[catMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Categories Permissions -->

                            <!-- Channel Service Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145681">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145681"
                                                    aria-expanded="false" aria-controls="collapse-3145681">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['channel-services'].name"
                                                            ng-init="permsnRuleCtrl.initModule('channel-services', 'channel_services')">
                                                        Channel Service
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Channel Service Permissions -->
                                <div id="collapse-3145681" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145681">
                                    <div class="panel-body">
                                        <!------------->
                                        <div class="panel-heading"
                                            ng-repeat="chnlSrvcMod in permsnRuleCtrl.chanlSrvcModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(chnlSrvcMod.key, chnlSrvcMod.label)">
                                                @{{ chnlSrvcMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="chnlSrvcMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="chnlSrvcMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('View') === -1"></div>

                                                <!-- Create -->
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]"
                                                            value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Create') === -1"></div>

                                                <!-- Edit -->
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Edit') === -1"></div>

                                                <!-- Delete -->
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]"
                                                            value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Delete') === -1"></div>

                                                <!-- Hide -->
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="chnlSrvcMod.permissions.indexOf('Hide') === -1"></div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in chnlSrvcMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ chnlSrvcMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(chnlSrvcMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[chnlSrvcMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Channel Service Permissions -->

                            <!-- Settings Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145678">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145678"
                                                    aria-expanded="false" aria-controls="collapse-3145678">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['settings'].name"
                                                            ng-init="permsnRuleCtrl.initModule('settings', 'settings')">
                                                        Settings
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Settings Permissions -->
                                <div id="collapse-3145678" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145678">
                                    <div class="panel-body">
                                        <!------------->
                                        <div class="panel-heading" ng-repeat="settingMod in permsnRuleCtrl.settingModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(settingMod.key, settingMod.label)">
                                                @{{ settingMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox"
                                                    ng-if="settingMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="settingMod.permissions.indexOf('Create') === -1"></div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox"
                                                    ng-if="settingMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="settingMod.permissions.indexOf('Delete') === -1"></div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="settingMod.permissions.indexOf('Hide') === -1">
                                                </div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in settingMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ settingMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(settingMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[settingMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Settings Permissions -->

                            <!-- Individual2 Rules -->
                            <div class="individual-rules">

                                <!-- Module -->
                                <div class="panel-heading" ng-repeat="ind2Mod in permsnRuleCtrl.indModules2">
                                    <div class="heading-div">
                                        <h5 class="panel-title">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind2Mod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(ind2Mod.key, ind2Mod.label)">
                                                @{{ ind2Mod.label }}
                                            </div>
                                        </h5>
                                    </div>

                                    <!-- Permissions -->
                                    <div class="check-div col-sm-9">
                                        <div class="checkbox" ng-repeat="permsn in ind2Mod.permissions">
                                            <label for="view">
                                                <input type="checkbox" name="@{{ ind2Mod.key }}[]" value="@{{ permsn }}"
                                                    ng-change="permsnRuleCtrl.toggleModulePermissions(ind2Mod.key, permsn)"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind2Mod.key].permissions[permsn]"
                                                    ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[ind2Mod.key].permissions['Hide']">
                                                @{{ permsn }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reports Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145679">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145679"
                                                    aria-expanded="false" aria-controls="collapse-3145679">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['reports'].name"
                                                            ng-init="permsnRuleCtrl.initModule('reports', 'reports')">
                                                        Reports
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>

                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- Reports Permissions -->
                                <div id="collapse-3145679" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145679">
                                    <div class="panel-body">
                                        <!------------->
                                        <div class="panel-heading" ng-repeat="reprtMod in permsnRuleCtrl.reportModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(reprtMod.key, reprtMod.label)">
                                                @{{ reprtMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Create') === -1">
                                                </div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Delete') === -1">
                                                </div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="reprtMod.permissions.indexOf('Hide') === -1">
                                                </div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in reprtMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ reprtMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(reprtMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[reprtMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Reports Permissions -->

                            <!-- DRM Service Permissions -->
                            <div class="panel-group" id="accordion-7544257" role="tablist" aria-multiselectable="false">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-3145680">
                                        <div class="heading-div">
                                            <h5 class="panel-title">
                                                <a role="button" data-toggle="collapse" class="collapsed"
                                                    data-parent="#accordion-7544257" href="#collapse-3145680"
                                                    aria-expanded="false" aria-controls="collapse-3145680">
                                                    <div class="label-div">
                                                        <svg fill="#000000" width="17px" height="17px" viewBox="0 0 24 24"
                                                            id="down-arrow-circle" data-name="Flat Color"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                            <circle id="primary" cx="12" cy="12" r="10"
                                                                style="fill: rgb(0, 0, 0);"></circle>
                                                            <path id="secondary"
                                                                d="M14.14,13H13V7a1,1,0,0,0-2,0v6H9.86a1,1,0,0,0-.69,1.5l2.14,3.12a.82.82,0,0,0,1.38,0l2.14-3.12A1,1,0,0,0,14.14,13Z"
                                                                style="fill: rgb(44, 169, 188);"></path>
                                                        </svg>
                                                        <input type="hidden" name="module_name"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules['drm-services'].name"
                                                            ng-init="permsnRuleCtrl.initModule('drm-services', 'drm_services')">
                                                        DRM Service
                                                    </div>
                                                </a>
                                            </h5>
                                        </div>

                                        <div class="arrow-icon accordion-toggle accordion-arrow-toggle collapsed"></div>
                                    </div>
                                </div>

                                <!-- DRM Service Permissions -->
                                <div id="collapse-3145680" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="heading-3145680">
                                    <div class="panel-body">
                                        <!------------->
                                        <div class="panel-heading"
                                            ng-repeat="drmSrvcMod in permsnRuleCtrl.drmServiceModules">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(drmSrvcMod.key, drmSrvcMod.label)">
                                                @{{ drmSrvcMod.label }}
                                            </div>

                                            <div class="check-div col-sm-9">
                                                <!-- All -->
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('All') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="All"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'All')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['All']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('All') === -1">
                                                </div>

                                                <!-- View -->
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('View') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="View"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'View')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['View']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        View
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('View') === -1">
                                                </div>

                                                <!-- Create -->
                                                <div class="checkbox"
                                                    ng-if="drmSrvcMod.permissions.indexOf('Create') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="Create"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'Create')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Create']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        Create
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="drmSrvcMod.permissions.indexOf('Create') === -1"></div>

                                                <!-- Edit -->
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('Edit') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="Edit"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'Edit')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Edit']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        Edit
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('Edit') === -1">
                                                </div>

                                                <!-- Delete -->
                                                <div class="checkbox"
                                                    ng-if="drmSrvcMod.permissions.indexOf('Delete') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="Delete"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'Delete')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Delete']"
                                                            ng-disabled="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        Delete
                                                    </label>
                                                </div>
                                                <div class="checkbox"
                                                    ng-if="drmSrvcMod.permissions.indexOf('Delete') === -1"></div>

                                                <!-- Hide -->
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('Hide') !== -1">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]" value="Hide"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, 'Hide')"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        Hide
                                                    </label>
                                                </div>
                                                <div class="checkbox" ng-if="drmSrvcMod.permissions.indexOf('Hide') === -1">
                                                </div>

                                                <!-- Other Permissions -->
                                                <div class="checkbox" ng-repeat="permsn in drmSrvcMod.permissions"
                                                    ng-if="permsn != 'All' && permsn != 'View' && permsn != 'Create' && permsn != 'Edit' && permsn != 'Delete' && permsn != 'Hide'">
                                                    <label>
                                                        <input type="checkbox" name="@{{ drmSrvcMod.key }}[]"
                                                            value="@{{ permsn }}"
                                                            ng-change="permsnRuleCtrl.toggleModulePermissions(drmSrvcMod.key, permsn)"
                                                            ng-model="permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions[permsn]"
                                                            ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[drmSrvcMod.key].permissions['Hide']">
                                                        @{{ permsn }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of DRM Service Permissions -->

                            <!-- Individual3 Rules -->
                            <div class="individual-rules" style="margin-bottom: 20px;">
                                <!-- Module -->
                                <div class="panel-heading" ng-repeat="ind3Mod in permsnRuleCtrl.indModules3">
                                    <div class="heading-div">
                                        <h5 class="panel-title">
                                            <div class="label-div">
                                                <input type="hidden" name="module_name"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind3Mod.key].name"
                                                    ng-init="permsnRuleCtrl.initModule(ind3Mod.key, ind3Mod.label)">
                                                @{{ ind3Mod.label }}
                                            </div>
                                        </h5>
                                    </div>

                                    <!-- Permissions -->
                                    <div class="check-div col-sm-9">
                                        <div class="checkbox" ng-repeat="permsn in ind3Mod.permissions">
                                            <label for="@{{ ind3Mod.key }}">
                                                <input type="checkbox" name="@{{ ind3Mod.key }}[]" value="@{{ permsn }}"
                                                    ng-change="permsnRuleCtrl.toggleModulePermissions(ind3Mod.key, permsn)"
                                                    ng-model="permsnRuleCtrl.permissionRuleData.modules[ind3Mod.key].permissions[permsn]"
                                                    ng-disabled="permsn != 'Hide' && permsn != 'All' && permsnRuleCtrl.permissionRuleData.modules[ind3Mod.key].permissions['Hide']">
                                                @{{ permsn }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group text-center">
                                <button type="submit" class="button button-blue" id="permissionruleadd">
                                    <strong>Add</strong>
                                </button>&nbsp;&nbsp;

                                <button type="button" class="button button-gray"
                                    ng-click="permsnRuleCtrl.cancelPermissionRule($event)">
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
    <script src="{{ asset('adminview/assets/js/permission-rules/index.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection