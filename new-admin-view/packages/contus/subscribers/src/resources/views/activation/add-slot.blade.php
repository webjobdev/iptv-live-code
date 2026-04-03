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
    </style>

@endsection

<style>
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
</style>


@section('content')
    <div data-ng-controller="ActivationController as actCtrl">
        <div class="" id="dashboard-page">
            @include('subscribers::layouts.subscribernav')<br>

            <form method="POST" enctype="multipart/form-data" id="devicesoltadd" data-base-validator
                data-ng-submit="actCtrl.saveslot($event)">

                {!! csrf_field() !!}

                <input type="hidden" id="subscriber-id" name="subscriber-id"
                    value="{{ request()->query('subscriber-id') }}">

                <div class="row">
                    <!-- Product & Payment Details -->
                    <div class="col-sm-8">
                        <div class="panel panel-default"
                            style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                            <div class="panel-heading"
                                style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                                <strong>Product Details</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Product Type*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="product_type" id="product_type"
                                            ng-model="actCtrl.addslot.product_type" class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option disabled value="">Choose Product Type</option>
                                            <option value="custom subscription">Custom Subscription</option>
                                            <option value="subscription sets">Subscription Sets</option>
                                            <option value="free subscription">Free Subscription</option>
                                            <option value="add devices/slots">add devices/slots</option>
                                            <option value="accessories">Accessories</option>
                                            <option value="custom charge">Custom Charge</option>
                                            <option value="bundles">Bundles</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- activation -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'custom subscription'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Acticvation:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.activation"
                                                name="activation" value="override"> Override
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.activation"
                                                name="activation" value="top-up"> Top-Up
                                        </label>
                                    </div>
                                </div>

                                <!-- subscription -->
                                <div
                                    ng-if="actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription'">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Subscription*:</strong>
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="subscription" id="subscription"
                                                ng-model="actCtrl.addslot.subscription"
                                                ng-change="actCtrl.onSubscriptionChange()" class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                ng-options="plan.id as plan.subscription_name for plan in actCtrl.subscriptionPlans">
                                                <option disabled value="">Select Subscription</option>
                                                <option value="free">Free</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- prorate subsription -->
                                    <div class="form-group row" style="margin-bottom: 15px;"
                                        ng-if="actCtrl.addslot.activation == '1' && (actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'custom subscription')">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 8px;">
                                            <strong></strong>
                                        </label>
                                        <div class="col-sm-10" style="margin-top: 8px;">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" ng-model="actCtrl.addslot.prorate_subscription"
                                                        name="prorate_subscription" value="1"> Prorate Subscription
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Display extracted length -->
                                    <div class="form-group row" style="margin-bottom: 15px;"
                                        ng-if="actCtrl.addslot.subscription">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Length:</strong>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <div class="col-sm-6" style="margin-top: 5px;">
                                                    <strong style="font-weight: bold;" ng-model="actCtrl.addslot.end_date">
                                                        <!-- @{{ actCtrl.addslot.subscription == 'free' ? 'Free' : actCtrl.addslot.subscription + ' Month' }} -->
                                                        @{{ actCtrl.addslot.subscription == 'free' ? 'Free' :
                                                        actCtrl.addslot.durationText || (actCtrl.addslot.subscription + '
                                                        Month') }}
                                                    </strong>
                                                </div>

                                                <div class="col-sm-6" style="margin-top: 5px;">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" data-toggle="modal" name="adjust_length"
                                                                data-target="#flipFlop"
                                                                ng-model="actCtrl.addslot.adjust_length"
                                                                value="adjust_length"> Adjust Length
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- The modal -->
                                    <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog"
                                        aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="modalLabel">Adjust Length</h4>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <div class="form-group row" style="margin-bottom: 15px;"
                                                        ng-if="actCtrl.addslot.start_date && actCtrl.addslot.end_date">
                                                        <label class="col-sm-12 control-label"
                                                            style="font-size: 18px; color: #000; font-weight: bold;">
                                                            Subscription
                                                        </label>

                                                        <div class="col-sm-12">
                                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                                <label for="start_date"
                                                                    class="col-sm-2 fw-bold col-form-label"
                                                                    style="font-size: 14px; color: #000; margin-top: 10px;">Active
                                                                    from:</label>
                                                                <div class="col-sm-10 m-auto">
                                                                    <input class="form-control rounded-pill border-3"
                                                                        type="date" id="start_date" name="start_date"
                                                                        ng-model="actCtrl.addslot.start_date"
                                                                        placeholder="{{trans('organizations::index.start_date')}}"
                                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                                <label for="end_date"
                                                                    class="col-sm-2 fw-bold col-form-label"
                                                                    style="font-size: 14px; color: #000; margin-top: 10px;">Active
                                                                    untill:</label>
                                                                <div class="col-sm-10 m-auto">
                                                                    <input class="form-control rounded-pill border-3"
                                                                        type="date" id="end_date" name="end_date"
                                                                        ng-model="actCtrl.addslot.end_date"
                                                                        placeholder="{{trans('organizations::index.end_date')}}"
                                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">

                                                                    <!-- ng-change="actCtrl.calculateDurationAndPriceFromDates()" -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr ng-if="actCtrl.addslot.product_type == 'subscription sets'"
                                                        style="border: 0.8px solid rgba(128, 130, 133, 0.36);">

                                                    <div class="form-group row" style="margin-bottom: 15px;">
                                                        <label class="col-sm-5 control-label"
                                                            style="font-size: 18px; color: #000;">
                                                            <strong>Content Add-On</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success"
                                                        data-dismiss="modal">Save</button>
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- add device -->
                                <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);"
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription'">

                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'add devices/slots'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Devices:</strong>
                                    </label>

                                    <div class="col-sm-10">
                                        <!-- 🔁 Device List -->
                                        <div class="device-row" ng-repeat="device in actCtrl.addslot.devices"
                                            style="display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;"
                                            ng-if="actCtrl.addslot.devices.length">

                                            <span class="control-label" style="flex: 1; font-size: 14px; color: #000;">
                                                @{{ device.device_name || device.brand_model || 'Unnamed Device' }}
                                            </span>

                                            <span class="device-type" style="flex: 1; font-size: 14px; color: #000;">
                                                @{{ actCtrl.addslot.product_type === 'add devices/slots' ? (device.price ==
                                                0 ? 'Free' : device.price) : 'Free' }}
                                            </span>

                                            <label class="switch" style="margin: 0;">
                                                <input type="checkbox" name="device" ng-model="device.selected"
                                                    ng-change="updateSelectedDevices(device)" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <!-- ❌ Fallback message when no devices exist -->
                                        <div style="display: flex;">
                                            <span ng-if="!actCtrl.addslot.devices || actCtrl.addslot.devices.length === 0"
                                                style="display: block; font-size: 14px; color: red; padding: 10px;">
                                                First add device
                                            </span>

                                            <button type="button" class="button button-blue" data-toggle="modal"
                                                data-ng-click="actCtrl.addDevice()"
                                                ng-if="!actCtrl.addslot.devices || actCtrl.addslot.devices.length === 0">
                                                Add Device
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);">
                                <!-- add device -->

                                <!-- length type -->
                                <div ng-if="actCtrl.addslot.product_type == 'custom subscription'">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 8px;">
                                            <strong>Length Type:</strong>
                                        </label>
                                        <div class="col-sm-10" style="padding-top: 8px;">
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="actCtrl.addslot.length_type"
                                                    data-ng-click="actCtrl.DayMonthClick()" name="length_type"
                                                    value="day-month">
                                                Days, Months
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="actCtrl.addslot.length_type"
                                                    data-ng-click="actCtrl.CelnderClick()" name="length_type"
                                                    value="celnder">
                                                Calendar
                                            </label>
                                        </div>
                                    </div>

                                    <!-- choose day -->
                                    <div class="form-group row" ng-if="actCtrl.addslot.length_type == 'day-month'"
                                        style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 8px;">
                                            <strong>Choose Days or Months:</strong>
                                        </label>
                                        <div class="col-sm-10" style="margin-top: 8px;">
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="actCtrl.addslot.day_month_type" value="day"
                                                    data-ng-click="actCtrl.DayMonthClick()">
                                                Days
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="actCtrl.addslot.day_month_type" value="month"
                                                    data-ng-click="actCtrl.DayMonthClick()">
                                                Months
                                            </label>
                                        </div>
                                    </div>

                                    <!-- days -->
                                    <div class="form-group row" style="margin-bottom: 15px;"
                                        ng-if="actCtrl.addslot.day_month_type == 'day'">
                                        <label for="start_date" class="col-sm-2 fw-bold col-form-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">Days*:</label>
                                        <div class="col-sm-10 m-auto">
                                            <input class="form-control rounded-pill border-3" type="day" name="start_date"
                                                required ng-model="actCtrl.addslot.start_date"
                                                ng-change="actCtrl.onCustomSubscriptionChange()" placeholder="Enter Days"
                                                id="start_date"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>

                                    <!-- month -->
                                    <div class="form-group row" style="margin-bottom: 15px;"
                                        ng-if="actCtrl.addslot.day_month_type == 'month' && actCtrl.addslot.length_type != 'celnder'">
                                        <label for="start_date" class="col-sm-2 fw-bold col-form-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">Month*:</label>
                                        <div class="col-sm-10 m-auto">
                                            <select name="subscription" id="subscription"
                                                ng-model="actCtrl.addslot.subscription"
                                                ng-change="actCtrl.onSubscriptionChange()" class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                                ng-options="plan.id as plan.subscription_name for plan in actCtrl.subscriptionPlans">
                                                <option disabled value="">Select Subscription</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- set date -->
                                    <div class="form-group row" style="margin-bottom: 15px;"
                                        ng-if="actCtrl.addslot.length_type == 'celnder'">
                                        <label for="start_date" class="col-sm-2 fw-bold col-form-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">Set Date:</label>
                                        <div class="col-sm-10 m-auto">
                                            <input class="form-control rounded-pill border-3" type="date" name="start_date"
                                                required id="set_date" ng-model="actCtrl.addslot.start_date"
                                                ng-change="actCtrl.calculateCustomSubscriptionPrice()"
                                                placeholder="{{trans('organizations::index.start_date')}}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        </div>
                                    </div>

                                    <!-- length -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Length:</strong>
                                        </label>
                                        <strong class="col-sm-10 m-auto" ng-model="actCtrl.addslot.end_date" id="length"
                                            style="margin-top: 0.5rem; font-weight: bold;">
                                            @{{ actCtrl.dateDifferenceText || actCtrl.addslot.durationText }}
                                            <!-- @{{ actCtrl.dateDifferenceText || actCtrl.addslot.durationText || (actCtrl.addslot.subscription) }} -->
                                        </strong>
                                    </div>
                                </div>

                                <!-- accessories -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Accessories*:</strong>
                                    </label>
                                    <div class="col-sm-10 form-input">
                                        <select class="form-control admin_category_sub select2_custom_ddl" multiple
                                            id="accessory-id" data-jquery="select2_custom_ddl"
                                            ng-model="actCtrl.addslot.accessory" myValue="selectedVideo.category"
                                            my-placeholder="{{ __('video::videos.select_category') }}"
                                            ng-options="ASRY.accessory as ASRY.accessory for ASRY in actCtrl.accessory"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option disabled value="">Select Accessory</option>
                                        </select>
                                    </div>
                                </div><br><br>

                                <div class="form-group" ng-if="actCtrl.addslot.product_type == 'accessories'"
                                    ng-class="{'has-error': errors.category.has}">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <span>Accessories</span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-sm-10 form-input">
                                        <select multiple name="accessory" myValue="hello"
                                            ng-model="actCtrl.addslot.accessory"
                                            class="admin_category_sub form-control select2_custom_ddl"
                                            data-jquery="select2_custom_ddl" myValue="selectedVideo.category"
                                            my-placeholder="{{ __('video::videos.select_category') }}"
                                            style="width:100px; height: auto;">
                                            <option disabled value="">Select Accessory</option>
                                            <option ng-repeat="item in actCtrl.accessoriesList" value="@{{item.name}}">
                                                @{{item.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="error-msg" ng-show="errors.category.has">
                                        @{{errors.category.message}}
                                    </p>

                                </div>
                                <!-- accessories end-->

                                <!-- custom_charge_comment -->
                                <div class="form-group" ng-if="actCtrl.addslot.product_type == 'custom charge'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <span>Comment </span>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-sm-10 m-auto">
                                        <textarea class="form-control rounded-pill border-3" type="text"
                                            name="custom_charge_comment" required
                                            ng-model="actCtrl.addslot.custom_charge_comment" rows="4" cols="50"
                                            placeholder="{{trans('organizations::index.address')}}"
                                            id="custom_charge_comment"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                    </div>
                                </div>

                                <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);"
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets'">

                                <!-- ==============================***********************************============================== -->
                                <!-- drag and drop bundles code start -->
                                <!-- ==============================***********************************============================== -->

                                <!-- out side bundles code -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.product_type === 'subscription sets' || actCtrl.addslot.product_type === 'bundles'">

                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Bundles*:</strong>
                                    </label>

                                    <!-- Assigned Bundles + Add Button -->
                                    <div class="col-sm-10" style="margin-top: 5px;">

                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 10px;">
                                            <span data-toggle="modal" data-target="#add-bundles"
                                                style="cursor: pointer; font-weight: bold;">
                                                + Add Bundles
                                            </span>

                                            <div class="checkbox" style="margin: 0;">
                                                <label style="margin: 0;">
                                                    <input type="checkbox" id="adjust-length-checkbox" name="adjust_length"
                                                        data-toggle="modal" data-target="#bundle_adjust_length"
                                                        data-boot-tooltip="true" title="" value="adjust_length"
                                                        ng-model="actCtrl.addslot.adjust_length"
                                                        ng-mouseenter="checkBundleWarning($event)">
                                                    Adjust Length
                                                </label>
                                            </div>


                                        </div>

                                        <div ng-if="actCtrl.addslot.bundles && actCtrl.addslot.bundles.length"
                                            style="margin-top: 10px;">
                                            <div style="max-height: 200px; overflow-y: auto; padding: 5px;">
                                                <div class="bundle-item" ng-repeat="bundle in actCtrl.addslot.bundles"
                                                    draggable="true" data-model="actCtrl.addslot.bundels"
                                                    data-id="@{{bundle.id}}" ng-attr-data-title="@{{bundle.channel_list}}"
                                                    style="border: 1px solid #ccc; padding: 10px; margin-bottom: 5px; border-radius: 4px;">

                                                    <span class="bundle-title">@{{bundle.channel_list}}</span>
                                                    <span class="bundle-sub center">TV Channel <strong>22 D</strong></span>
                                                    <span class="bundle-price">
                                                        <!-- <del>€5</del> -->
                                                        <strong>@{{bundle.price}}</strong>
                                                    </span>
                                                    <span class="bundle-rent">Rent</span>
                                                    <span class="bundle-remove" ng-click="removeBundle(bundle)"
                                                        style="float: right; color: red; cursor: pointer;">
                                                        <i class="glyphicon glyphicon-remove-circle"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- add bundle Modal code -->
                                <div class="modal fade" id="add-bundles" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content" style="padding: 10px;">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                                <h4 class="modal-title">Add Bundles</h4>
                                                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
                                            </div>

                                            <div class="row">
                                                <!-- Available Bundles -->
                                                <div class="col-sm-6">
                                                    <strong>Available Bundles</strong>
                                                    <input type="text" id="searchAvailable" class="form-control input-sm"
                                                        placeholder="Search Bundles" style="margin-bottom: 10px;">
                                                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                                        <div id="availableBundles" class="drop-zone panel panel-default"
                                                            style="min-height: 130px; padding: 8px;">
                                                            <input type="hidden" id="availableBundles"
                                                                ng-model="actCtrl.addslot.id">

                                                            <div class="bundle-card" draggable="true"
                                                                ng-repeat="bundle in actCtrl.channelList"
                                                                data-id="@{{bundle.id}}"
                                                                style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; cursor: move;">
                                                                <div
                                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                                    <div>
                                                                        <strong
                                                                            ng-model="actCtrl.addslot.bundle">@{{bundle.name}}</strong><br>
                                                                        <span class="sub-text">TV Channel</span><br>
                                                                        <strong>@{{bundle.price}}
                                                                            @{{bundle.monitization_type == 0 ? 'Rent' :
                                                                            'Buy'}}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Added Bundles -->
                                                <div class="col-sm-6">
                                                    <strong>Added Bundles</strong>
                                                    <input type="text" id="searchAdded" class="form-control input-sm"
                                                        placeholder="Search Bundles" style="margin-bottom: 10px;">
                                                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                                        <div id="addedBundles" class="drop-zone panel panel-default"
                                                            style="min-height: 130px; padding: 8px;"> </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 15px;">
                                                <div class="col-sm-12 text-right">
                                                    <button type="button" id="assignBtn" class="button button-blue"
                                                        ng-click="actCtrl.assignSelectedBundles()">Assign</button>
                                                    <button class="button button-gray" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- adjust length model code -->
                                <div class="modal fade" id="bundle_adjust_length" tabindex="-1" role="dialog"
                                    aria-labelledby="modalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">Adjust Length</h4>
                                            </div>
                                            <div class="modal-body" style="padding: 20px;">

                                                <div class="text-center" style="margin-bottom: 20px;">
                                                    <h4
                                                        style="font-weight: bold; color: #333; text-transform: uppercase; margin: 0;">
                                                        Subscription</h4>
                                                </div>

                                                <div class="panel panel-default"
                                                    style="border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);"
                                                    ng-repeat="bundle in actCtrl.addslot.bundles"
                                                    data-model="actCtrl.addslot.bundels" data-id="@{{bundle.id}}">
                                                    <div class="panel-body">
                                                        <!-- Active From -->
                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label class="col-sm-3 control-label"
                                                                style="font-weight: 600; color: #555; margin-top: 7px;">
                                                                Active From:
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <input type="date" class="form-control"
                                                                    ng-model="bundle.start_at"
                                                                    placeholder="Select start date">
                                                            </div>
                                                        </div>

                                                        <!-- Active Until -->
                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label class="col-sm-3 control-label"
                                                                style="font-weight: 600; color: #555; margin-top: 7px;">
                                                                Active Until:
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <input type="date" class="form-control"
                                                                    ng-model="bundle.end_at" placeholder="Select end date"
                                                                    min="{{ date('Y-01-01') }}" max="{{ date('Y-12-31') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center" style="margin-top: 20px;">
                                                    <h4
                                                        style="font-weight: bold; color: #333; text-transform: uppercase; margin: 0;">
                                                        Content Add-On</h4>
                                                    <div class="panel panel-default"
                                                        style="border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                        <div class="panel-body">

                                                            <div class="form-group" style="margin-bottom: 15px;">
                                                                <div class="switch-concept flexbox align-items-center">
                                                                    <div class="swich-content flexbox align-items-center flex-wrap"
                                                                        style="flex: unset;">
                                                                        <span>Adjust Length</span>
                                                                        <div class="right-side flexbox align-items-center">
                                                                            <label class="switch">
                                                                                <input type="checkbox"
                                                                                    data-ng-model="settCtrl.setting.is_active"
                                                                                    name="is_active">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="error-msg"></p>


                                                                <div class="checkbox" style="margin-left: 15.5px;">
                                                                    <label
                                                                        class="swich-content flexbox align-items-center flex-wrap"
                                                                        style="margin: 0;">
                                                                        <input type="checkbox"
                                                                            ng-model="actCtrl.selectAllBundles"
                                                                            ng-change="toggleAllBundles()">
                                                                        Select All
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div
                                                                style="max-height: 200px; overflow-y: auto; padding: 5px; border-radius: 6px;">

                                                                <!-- Error Message -->
                                                                <div ng-if="!actCtrl.addslot.bundles || !actCtrl.addslot.bundles.length"
                                                                    class="text-danger"
                                                                    style="margin-bottom: 10px; font-weight: bold;">
                                                                    ⚠️ No bundles available.
                                                                </div>

                                                                <!-- Bundle List -->
                                                                <div class="bundle-item"
                                                                    ng-repeat="bundle in actCtrl.addslot.bundles"
                                                                    data-model="actCtrl.addslot.bundels"
                                                                    data-id="@{{bundle.id}}"
                                                                    ng-attr-data-title="@{{bundle.channel_list}}"
                                                                    style="border: 1px solid #ccc; padding: 10px; margin-bottom: 5px; border-radius: 4px;">

                                                                    <div class="checkbox" style="margin: 0;">
                                                                        <label style="margin: 0;">
                                                                            <input type="checkbox"
                                                                                ng-disabled="!bundle.end_at"
                                                                                ng-model="bundle.selected">
                                                                        </label>
                                                                    </div>

                                                                    <span
                                                                        class="bundle-title">@{{bundle.channel_list}}</span>
                                                                    <span class="bundle-sub center">TV Channel</span>
                                                                    <span class="center"><strong>22 D</strong></span>
                                                                    <span class="bundle-price">
                                                                        <strong>@{{bundle.price}}</strong>
                                                                    </span>
                                                                    <span class="bundle-rent">Until: @{{ bundle.end_at|
                                                                        date:'d-M-y' || 'date not available' }}</span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bottom-button text-right flexbox align-items-center">
                                                <input type="button" value="{{ trans('base::general.cancel') }}"
                                                    data-dismiss="modal" data-ng-click="actCtrl.closeSubscriptionEdit()"
                                                    name="cancel" class="save btn btn-default">

                                                <input type="button" value="{{ trans('base::general.submit') }}"
                                                    style="background-color: #00ACCD; color: #fff;" name="submit"
                                                    class="publish-now btn">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ==============================***********************************============================== -->
                                <!-- drag and drop bundles code end  -->
                                <!-- ==============================***********************************============================== -->

                            </div>
                        </div>

                        <!-- ==============================***********************************============================== -->
                        <!-- Payment Details -->
                        <!-- ==============================***********************************============================== -->
                        <div class="panel panel-default" ng-if="actCtrl.addslot.product_type !== 'free subscription'"
                            style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                            <div class="panel-heading"
                                style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                                <strong>Payment Details</strong>
                            </div>
                            <div class="panel-body">
                                <!-- payment service -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Payment
                                            Service*:</strong></label>
                                    <div class="col-sm-10">
                                        <select name="payment_service" id="payment_service"
                                            ng-model="actCtrl.addslot.payment_service"
                                            ng-change="actCtrl.onPaymentServiceChange()" class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option disabled value="">Select Payment Service</option>
                                            <option ng-repeat="service in actCtrl.availablePaymentServices"
                                                value="@{{ service.value }}"
                                                ng-if="actCtrl.addslot.product_type != 'free subscription'">
                                                @{{ service.label }}
                                            </option>
                                            <!-- If free subscription: only show Free -->
                                            <option value="free"
                                                ng-if="actCtrl.addslot.product_type == 'free subscription'">
                                                Free
                                            </option>
                                        </select>

                                        <!-- Display Saved Cards for AuthorizeNet -->
                                        <div
                                            ng-if="(actCtrl.addslot.payment_service === 'authorizenet' || actCtrl.addslot.payment_service === 'authorize_manual')">

                                            <div ng-if="actCtrl.creditCard && actCtrl.creditCard.length > 0"
                                                style="margin-top: 15px;">
                                                <label
                                                    style="font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">Select
                                                    Payment Card</label>

                                                <div ng-repeat="card in actCtrl.creditCard"
                                                    style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; margin-bottom: 10px; cursor: pointer;">
                                                    <label
                                                        style="display: flex; align-items: center; margin: 0; width: 100%; cursor: pointer; font-weight: normal;">
                                                        <input type="radio" name="selected_credit_card_id"
                                                            ng-model="actCtrl.addslot.selected_credit_card_id"
                                                            ng-value="card.id" style="margin-right: 12px;">
                                                        <i class="fa fa-credit-card"
                                                            style="color: #00ACCD; margin-right: 8px; font-size: 18px;"></i>
                                                        <span style="font-size: 14px; color: #333; flex-grow: 1;">
                                                            <strong ng-if="card.profile_name">@{{ card.profile_name }} -
                                                            </strong>
                                                            <span>@{{ card.card_number.slice(-4) }}</span>
                                                            <span style="color: #888; margin-left: 5px;">(Exp: @{{
                                                                card.expiration_month }}/@{{ card.expiration_year }})</span>
                                                        </span>
                                                        <span
                                                            style="font-size: 13px; font-weight: bold; background: #f0f4f8; padding: 3px 8px; border-radius: 4px; color: #555; text-transform: uppercase;">
                                                            @{{ card.card_type || 'CARD' }}
                                                        </span>
                                                    </label>
                                                </div>

                                                <div
                                                    style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; margin-bottom: 10px; cursor: pointer;">
                                                    <label
                                                        style="display: flex; align-items: center; margin: 0; width: 100%; cursor: pointer; font-weight: normal;">
                                                        <input type="radio" name="selected_credit_card_id"
                                                            ng-model="actCtrl.addslot.selected_credit_card_id"
                                                            ng-value="'new'" ng-change="actCtrl.onSelectedCardChange()"
                                                            style="margin-right: 12px;">
                                                        <i class="fa fa-plus-circle"
                                                            style="color: #00ACCD; margin-right: 8px; font-size: 18px;"></i>
                                                        <span style="font-size: 14px; color: #333;">Use a new credit
                                                            card</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Display Masked Card and Edit Button if NO saved cards exist or NEW is selected, but user already input some details -->
                                            <div ng-if="(!actCtrl.creditCard || actCtrl.creditCard.length === 0 || actCtrl.addslot.selected_credit_card_id === 'new') && actCtrl.addslot.cc_number"
                                                style="margin-top: 15px; background: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 15px; display: flex; justify-content: space-between; align-items: center;">
                                                <div style="display: flex; align-items: center;">
                                                    <i class="fa fa-credit-card"
                                                        style="font-size: 20px; color: #00ACCD; margin-right: 12px;"></i>
                                                    <div>
                                                        <span
                                                            style="font-size: 14px; font-weight: 600; color: #333; display: block; margin-bottom: 2px;">New
                                                            Card Added</span>
                                                        <span
                                                            style="font-size: 13px; color: #666; font-family: monospace;">@{{
                                                            actCtrl.getMaskedCardSource() }}</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="button button-blue"
                                                    ng-click="actCtrl.openAuthorizeNetModal($event)"
                                                    style="font-size: 12px; border-radius: 6px;">
                                                    <i class="fa fa-pencil" style="margin-right: 4px;"></i> Edit
                                                </button>
                                            </div>

                                        </div>

                                        <!-- Authorize.net Credit Card Modal -->
                                        <div class="modal fade" id="authorizeNetModal" tabindex="-1" role="dialog"
                                            aria-labelledby="authorizeNetModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content"
                                                    style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; border: none;">
                                                    <div class="modal-header"
                                                        style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; padding: 20px 25px;">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close" style="margin-top: 2px;">
                                                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="authorizeNetModalLabel"
                                                            style="font-weight: 700; color: #333; margin: 0; font-size: 18px;">
                                                            <i class="fa fa-credit-card"
                                                                style="margin-right: 8px; color: #00ACCD;"></i>Card Details
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body" style="padding: 25px;">

                                                        <div class="alert alert-info"
                                                            style="border-radius: 8px; font-size: 13px; margin-bottom: 20px; border-left: 4px solid #00ACCD; background-color: #e8f7fa; color: #0b7a91; border-top: none; border-bottom: none; border-right: none;">
                                                            <i class="fa fa-lock" style="margin-right: 5px;"></i> Your
                                                            payment information is secured and encrypted.
                                                        </div>

                                                        <div class="form-group" style="margin-bottom: 20px;">
                                                            <label
                                                                style="font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                                                                <span>Card Number<span class="required"
                                                                        style="color: red;">*</span></span>
                                                                <span style="display: flex; gap: 8px; font-size: 20px;">
                                                                    <i class="fa fa-cc-visa" style="color: #1a1f71;"
                                                                        title="Visa"></i>
                                                                    <i class="fa fa-cc-mastercard" style="color: #eb001b;"
                                                                        title="Mastercard"></i>
                                                                    <i class="fa fa-cc-amex" style="color: #2e77bc;"
                                                                        title="American Express"></i>
                                                                    <i class="fa fa-cc-discover" style="color: #e55c20;"
                                                                        title="Discover"></i>
                                                                </span>
                                                            </label>
                                                            <div style="position: relative;">
                                                                <input type="text" class="form-control" name="cc_number"
                                                                    ng-model="actCtrl.addslot.cc_number"
                                                                    placeholder="0000 0000 0000 0000"
                                                                    style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px 15px 10px 40px; height: 46px; font-size: 15px; width: 100%; transition: all 0.3s; box-shadow: none;"
                                                                    onfocus="this.style.borderColor='#00ACCD';"
                                                                    onblur="this.style.borderColor='#e2e8f0';">
                                                                <i class="fa fa-credit-card"
                                                                    style="position: absolute; left: 15px; top: 15px; color: #a0aec0; font-size: 16px;"></i>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-7">
                                                                <div class="form-group" style="margin-bottom: 20px;">
                                                                    <label
                                                                        style="font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">
                                                                        Expiry Date<span class="required"
                                                                            style="color: red;">*</span>
                                                                    </label>
                                                                    <div class="row" style="margin: 0 -5px;">
                                                                        <div class="col-xs-6" style="padding: 0 5px;">
                                                                            <input type="text" class="form-control"
                                                                                name="cc_exp_month"
                                                                                ng-model="actCtrl.addslot.cc_exp_month"
                                                                                placeholder="MM"
                                                                                style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px; height: 46px; font-size: 15px; text-align: center; transition: all 0.3s; box-shadow: none;"
                                                                                onfocus="this.style.borderColor='#00ACCD';"
                                                                                onblur="this.style.borderColor='#e2e8f0';">
                                                                        </div>
                                                                        <div class="col-xs-6" style="padding: 0 5px;">
                                                                            <input type="text" class="form-control"
                                                                                name="cc_exp_year"
                                                                                ng-model="actCtrl.addslot.cc_exp_year"
                                                                                placeholder="YYYY"
                                                                                style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px; height: 46px; font-size: 15px; text-align: center; transition: all 0.3s; box-shadow: none;"
                                                                                onfocus="this.style.borderColor='#00ACCD';"
                                                                                onblur="this.style.borderColor='#e2e8f0';">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-5">
                                                                <div class="form-group" style="margin-bottom: 20px;">
                                                                    <label
                                                                        style="font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">
                                                                        CVV<span class="required"
                                                                            style="color: red;">*</span>
                                                                    </label>
                                                                    <div style="position: relative;">
                                                                        <input type="text" class="form-control"
                                                                            name="cc_cvv" ng-model="actCtrl.addslot.cc_cvv"
                                                                            placeholder="123"
                                                                            style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px 15px 10px 40px; height: 46px; font-size: 15px; transition: all 0.3s; box-shadow: none;"
                                                                            onfocus="this.style.borderColor='#00ACCD';"
                                                                            onblur="this.style.borderColor='#e2e8f0';">
                                                                        <i class="fa fa-lock"
                                                                            style="position: absolute; left: 15px; top: 15px; color: #a0aec0; font-size: 16px;"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer"
                                                        style="padding: 15px 25px; border-top: 1px solid #e9ecef; background-color: #f8f9fa;">
                                                        <button type="button" class="button button-gray"
                                                            data-dismiss="modal"
                                                            style="border-radius: 8px; font-weight: 600;">
                                                            Cancel
                                                        </button>
                                                        <button type="button" class="button button-blue"
                                                            data-dismiss="modal"
                                                            style="border-radius: 8px; font-weight: 600; margin-left: 10px; border: none;">
                                                            Save Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <!-- cash location -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.payment_service == 'cash'">
                                    <label class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Cash
                                            Location*:</strong></label>
                                    <div class="col-sm-10">
                                        <select name="cash_location" id="cash_location"
                                            ng-model="actCtrl.addslot.cash_location" class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option disabled value="">Select Cash Location</option>
                                            <option value="Delhi Office - Connaught Place">Delhi Office - Connaught Place
                                            </option>
                                            <option value="Mumbai Branch - Andheri East">Mumbai Branch - Andheri East
                                            </option>
                                            <option value="Bangalore HQ - MG Road">Bangalore HQ - MG Road</option>
                                            <option value="Hyderabad Center - Banjara Hills">Hyderabad Center - Banjara
                                                Hills</option>
                                            <option value="Chennai Office - T Nagar">Chennai Office - T Nagar</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- currency -->
                                <div
                                    ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'add devices/slots' || actCtrl.addslot.product_type == 'accessories' || actCtrl.addslot.product_type == 'custom charge' || actCtrl.addslot.product_type == 'bundles'">
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            <strong>Payment Currency:</strong>
                                        </label>
                                        <div class="col-sm-10" style="margin-top: 8px;">
                                            <label class="radio-inline">
                                                <input class="radio" type="radio"
                                                    ng-model="actCtrl.addslot.payment_currency" name="payment_currency"
                                                    value="₭ lak"> LAK
                                            </label>
                                            <label class="radio-inline">
                                                <input class="radio" type="radio"
                                                    ng-model="actCtrl.addslot.payment_currency" name="payment_currency"
                                                    value="฿ thb"> THB
                                            </label>
                                            <label class="radio-inline">
                                                <input class="radio" type="radio"
                                                    ng-model="actCtrl.addslot.payment_currency" name="payment_currency"
                                                    value="$ usd"> USD
                                            </label>
                                            <label class="radio-inline">
                                                <input class="radio" type="radio"
                                                    ng-model="actCtrl.addslot.payment_currency" name="payment_currency"
                                                    value="€ eur"> EUR
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- total -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'custom charge'">
                                    <label for="total" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Total*:
                                    </label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="total" required
                                            ng-model="actCtrl.addslot.total" placeholder="Enter Total Amount" id="total"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ==============================***********************************============================== -->
                    <!-- Order Summary -->
                    <!-- ==============================***********************************============================== -->
                    <div class="col-sm-4">
                        <div class="panel panel-default"
                            style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                            <div class="panel-heading"
                                style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                                <strong>Order Summary</strong>
                            </div>

                            <div class="panel-body">
                                <!-- Subscription Section -->
                                <div ng-if="actCtrl.addslot.product_type !== 'custom charge'">
                                    <p style="margin-bottom: 5px; font-weight: bold;"><span
                                            class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                        <strong>Subscription</strong>
                                    </p>
                                    <div>
                                        <div
                                            ng-if="actCtrl.addslot.product_type === 'subscription sets' || actCtrl.addslot.product_type === 'custom subscription' || actCtrl.addslot.product_type === 'accessories'">
                                            <h5 class="text-muted" style="margin-top: -5px; font-size: medium;">
                                                <!-- @{{ actCtrl.addslot.subscription === 'free' ? 'Free' : (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' : 'None') }} -->
                                                @{{ actCtrl.addslot.subscription == 'free' ? 'Free' :
                                                (actCtrl.dateDifferenceText || actCtrl.addslot.durationText ||
                                                (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' :
                                                'None')) }}
                                                <span class="pull-right" ng-model="actCtrl.addslot.total">@{{
                                                    actCtrl.addslot.payment_currency.split(' ')[0] }} @{{
                                                    actCtrl.addslot.total }}</span>
                                            </h5>
                                        </div>

                                        <div ng-if="actCtrl.addslot.product_type === 'free subscription'">
                                            <h5 class="text-muted" style="margin-top: -5px; font-size: medium;">
                                                <!-- @{{ actCtrl.addslot.subscription === 'free' ? 'Free' : (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' : 'None') }} -->
                                                @{{ actCtrl.addslot.subscription == 'free' ? 'Free' :
                                                (actCtrl.dateDifferenceText || actCtrl.addslot.durationText ||
                                                (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' :
                                                'None')) }}
                                                <!-- <span class="pull-right" ng-model="actCtrl.addslot.total">@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</span> -->
                                                <span class="pull-right"
                                                    ng-if="actCtrl.addslot.product_type === 'free subscription'">
                                                    Free
                                                </span>
                                            </h5>
                                        </div>

                                        <div
                                            ng-if="actCtrl.addslot.product_type !== 'subscription sets' && actCtrl.addslot.product_type !== 'custom subscription' && actCtrl.addslot.product_type !== 'free subscription' && actCtrl.addslot.product_type !== 'accessories'">
                                            <p class="text-muted" style="font-size: medium;">None</p>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border: 0.5px solid rgba(128, 130, 133, 0.36);"
                                    ng-if="actCtrl.addslot.product_type !== 'custom charge'">

                                <!-- Devices Section -->
                                <div ng-if="actCtrl.addslot.product_type !== 'custom charge'">
                                    <p style="margin-bottom: 5px; font-weight: bold;">
                                        <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
                                        <strong>Devices</strong>
                                    </p>
                                    <div>
                                        <div
                                            ng-if="actCtrl.addslot.product_type === 'subscription sets' || actCtrl.addslot.product_type === 'custom subscription' || actCtrl.addslot.product_type === 'free subscription' || actCtrl.addslot.product_type === 'add devices/slots'">
                                            <div ng-if="selectedDevices && selectedDevices.length > 0">
                                                <div ng-repeat="device in selectedDevices">
                                                    <h5 class="text-muted" style="font-size: medium;">
                                                        @{{ device.device_name || device.brand_model || 'Unnamed Device' }}
                                                        <span class="pull-right">
                                                            @{{ actCtrl.addslot.product_type === 'add devices/slots' ?
                                                            (device.price == 0 ? 'Free' : (device.price)) : 'Free' }}
                                                        </span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            ng-if="actCtrl.addslot.product_type !== 'subscription sets' && actCtrl.addslot.product_type !== 'custom subscription' && actCtrl.addslot.product_type !== 'free subscription' && actCtrl.addslot.product_type !== 'add devices/slots'">
                                            <p class="text-muted" style="font-size: medium;">None</p>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border: 0.5px solid rgba(128, 130, 133, 0.36);"
                                    ng-if="actCtrl.addslot.product_type !== 'custom charge'">

                                <!-- Total -->

                                <p style="margin-bottom: 5px;">
                                    <strong>Total</strong>

                                    <!-- For free subscription, always show 0 -->
                                    <span class="pull-right" ng-if="actCtrl.addslot.product_type === 'free subscription'">
                                        <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} 0</strong>
                                    </span>

                                    <!-- For add devices/slots, show dynamic total -->
                                    <span class="pull-right" ng-if="actCtrl.addslot.product_type === 'add devices/slots'">
                                        <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{
                                            actCtrl.addslot.total }}</strong>
                                    </span>

                                    <!-- For others (subscription sets, Custom, etc.) show original total -->
                                    <span class="pull-right"
                                        ng-if="actCtrl.addslot.product_type !== 'add devices/slots' && actCtrl.addslot.product_type !== 'free subscription'">
                                        <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{
                                            actCtrl.addslot.total }}</strong>
                                    </span>

                                </p>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <div class="checkbox">
                                            <label style="margin-bottom: 0px;">
                                                <input type="checkbox" ng-model="actCtrl.addslot.terms_of_agreement"
                                                    name="terms_of_agreement">
                                                Terms of Agreement.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Process Button -->
                                <button class="btn btn-block"
                                    style="background-color: #00ACCD; color: #fff; font-weight: bold;">Process</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script ng-if="actCtrl.addslot.payment_service === 'razorpay' || actCtrl.addslot.payment_service === 'autopay'">
    window.RAZORPAY_KEY = "{{ config('services.razorpay.key') }}";
    window.RAZORPAY_AMOUNT = "@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}";
</script>


@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/subscribers/activation.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection