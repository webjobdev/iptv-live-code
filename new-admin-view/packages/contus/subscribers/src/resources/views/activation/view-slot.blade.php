@extends('base::layouts.default')

@section('header')
@include('base::layouts.headers.dashboard')

@endsection

@section('content')
<div data-ng-controller="ActivationController as actCtrl">
    <div class="" id="dashboard-page">
        @include('subscribers::layouts.subscribernav')<br>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>

        <form method="POST" enctype="multipart/form-data" id="devicesoltadd" data-base-validator data-ng-submit="actCtrl.saveslot($event)">
            {!! csrf_field() !!}
            <!-- <input type="hidden" id="subscriber-id" name="id">
            <script>
                document.getElementById('subscriber-id').value = window.location.pathname.split('/').pop();
            </script> -->
            <input type="hidden" id="subscriber-id" name="subscriber-id" value="{{ request()->query('subscriber-id') }}">
            <div class="row">
                <!-- Product & Payment Details -->
                <div class="col-sm-8">
                    <div class="panel panel-default" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                        <div class="panel-heading" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                            <strong>Product Details</strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Product Type*:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <!-- <select name="product_type" id="product_type"
                                        ng-model="actCtrl.addslot.product_type"
                                        class="form-control" style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option disabled value="">Choose Product Type</option>
                                        <option value="custom subscription">Custom Subscription</option>
                                        <option value="subscription sets">Subscription Sets</option>
                                        <option value="free subscription">Free Subscription</option>
                                        <option value="add devices/slots">add devices/slots</option>
                                        <option value="accessories">Accessories</option>
                                        <option value="custom charge">Custom Charge</option>
                                        <option value="bundles">Bundles</option>
                                    </select> -->
                                    <select name="product_type" id="product_type"
                                        class="form-control"
                                        ng-if="actCtrl.isExpired(record.end_date)"
                                        ng-model="actCtrl.addslot.product_type"
                                        ng-disabled="actCtrl.isOtherProductAlreadyAdded()"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option disabled value="">Choose Product Type</option>
                                        <option value="custom subscription">Custom Subscription</option>
                                        <option value="subscription sets">Subscription Sets</option>
                                        <option value="free subscription">Free Subscription</option>
                                        <option value="add devices/slots" ng-disabled="actCtrl.isAddDeviceSlotAlreadyAdded()">add devices/slots</option>
                                        <option value="accessories">Accessories</option>
                                        <option value="custom charge">Custom Charge</option>
                                        <option value="bundles">Bundles</option>
                                    </select>
                                </div>
                            </div>

                            <!-- activation -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'custom subscription'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Acticvation:</strong>
                                </label>
                                <div class="col-sm-10" style="margin-top: 8px;">
                                    <label class="radio-inline">
                                        <input class="radio" type="radio" ng-model="actCtrl.addslot.activation" ng-disabled="actCtrl.addslot.activation"
                                            name="activation" value="override"> Override
                                    </label>
                                    <label class="radio-inline">
                                        <input class="radio" type="radio" ng-model="actCtrl.addslot.activation" ng-disabled="actCtrl.addslot.activation"
                                            name="activation" value="top-up"> Top-Up
                                    </label>
                                </div>
                            </div>

                            <!-- subscription -->
                            <div ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Subscription*:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="subscription" id="subscription"
                                            ng-model="actCtrl.addslot.subscription"
                                            ng-change="actCtrl.onSubscriptionChange()"
                                            class="form-control"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            ng-options="plan.id as plan.subscription_name for plan in actCtrl.subscriptionPlans">
                                            <option disabled value="">Select Subscription</option>
                                            <option value="free">Free</option>
                                        </select>

                                    </div>
                                </div>

                                <!-- prorate subsription -->
                                <div class="form-group row" style="margin-bottom: 15px;"
                                    ng-if="actCtrl.addslot.activation == '1' && (actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription')">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 8px;">
                                        <strong></strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                    ng-model="actCtrl.addslot.prorate_subscription"
                                                    name="prorate_subscription"
                                                    value="1"> Prorate Subscription
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <!-- Display extracted length -->
                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.subscription">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Length:</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6" style="margin-top: 5px;">
                                                <strong style="font-weight: bold;" ng-model="actCtrl.addslot.end_date">
                                                    <!-- @{{ actCtrl.addslot.subscription == 'free' ? 'Free' : actCtrl.addslot.subscription + ' Month' }} -->
                                                    @{{ actCtrl.addslot.subscription == 'free' ? 'Free' : actCtrl.addslot.durationText || (actCtrl.addslot.subscription + ' Month') }}
                                                </strong>
                                            </div>

                                            <div class="col-sm-6" style="margin-top: 5px;">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"
                                                            data-toggle="modal"
                                                            name="adjust_length"
                                                            data-target="#flipFlop"
                                                            ng-model="actCtrl.addslot.adjust_length"
                                                            value="1"> Adjust Length
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- The modal -->
                                <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">Adjust Length</h4>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.start_date && actCtrl.addslot.end_date">
                                                    <label class="col-sm-12 control-label" style="font-size: 18px; color: #000; font-weight: bold;">
                                                        Subscription
                                                    </label>

                                                    <div class="col-sm-12">
                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label for="start_date" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">Active from:</label>
                                                            <div class="col-sm-10 m-auto">
                                                                <input class="form-control rounded-pill border-3"
                                                                    type="date"
                                                                    name="start_date"
                                                                    ng-model="actCtrl.addslot.start_date"
                                                                    placeholder="{{trans('organizations::index.start_date')}}"
                                                                    id="start_date"
                                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label for="end_date" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">Active untill:</label>
                                                            <div class="col-sm-10 m-auto">
                                                                <input class="form-control rounded-pill border-3"
                                                                    type="date"
                                                                    id="end_date"
                                                                    name="end_date"
                                                                    ng-model="actCtrl.addslot.end_date"
                                                                    placeholder="{{trans('organizations::index.end_date')}}"
                                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">

                                                                <!-- ng-change="actCtrl.calculateDurationAndPriceFromDates()" -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr ng-if="actCtrl.addslot.product_type == 'subscription sets'" style="border: 0.8px solid rgba(128, 130, 133, 0.36);">

                                                <div class="form-group row" style="margin-bottom: 15px;">
                                                    <label class="col-sm-5 control-label" style="font-size: 18px; color: #000;">
                                                        <strong>Content Add-On</strong>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- add device -->
                            <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);"
                                ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription'">

                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'add devices/slots'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Devices:</strong>
                                </label>

                                <div class="col-sm-10">
                                    <!-- 🔁 Device List -->
                                    <div class="device-row"
                                        ng-repeat="device in actCtrl.addslot.devices"
                                        style="display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;"
                                        ng-if="actCtrl.addslot.devices.length">

                                        <span class="control-label" style="flex: 1; font-size: 14px; color: #000;">
                                            @{{ device.device_name || device.brand_model || 'Unnamed Device' }}
                                        </span>

                                        <!-- ✅ Conditional Price Display -->
                                        <span class="device-type" style="flex: 1; font-size: 14px; color: #000;">
                                            @{{ actCtrl.addslot.product_type === 'add devices/slots' ? (device.price == 0 ? 'Free' : device.price) : 'Free' }}
                                        </span>

                                        <label class="switch" style="margin: 0;">
                                            <input type="checkbox"
                                                name="device"
                                                ng-model="device.selected"
                                                ng-change="updateSelectedDevices()" />
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <!-- ❌ Fallback message when no devices exist -->
                                    <span ng-if="!actCtrl.addslot.devices || actCtrl.addslot.devices.length === 0"
                                        style="display: block; font-size: 14px; color: red; padding: 10px;">
                                        First add device
                                    </span>
                                </div>
                            </div>

                            <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);">
                            <!-- add device -->

                            <!-- length type -->
                            <div ng-if="actCtrl.addslot.product_type == 'custom subscription'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 8px;">
                                        <strong>Length Type:</strong>
                                    </label>
                                    <div class="col-sm-10" style="padding-top: 8px;">
                                        <label class="radio-inline">
                                            <input type="radio"
                                                ng-model="actCtrl.addslot.length_type"
                                                name="length_type"
                                                value="day-month">
                                            Days, Months
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio"
                                                ng-model="actCtrl.addslot.length_type"
                                                name="length_type"
                                                value="celnder">
                                            Calendar
                                        </label>
                                    </div>
                                </div>

                                <!-- choose day -->
                                <div class="form-group row" ng-if="actCtrl.addslot.length_type == 'day-month'" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 8px;">
                                        <strong>Choose Days or Months:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="actCtrl.addslot.day_month_type" ng-disabled="actCtrl.addslot.day_month_type" value="0"> Days
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="actCtrl.addslot.day_month_type" ng-disabled="actCtrl.addslot.day_month_type" value="1"> Months
                                        </label>
                                    </div>
                                </div>

                                <!-- days -->
                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.day_month_type == 0">
                                    <label for="start_date" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">Days*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="day" name="start_date" required
                                            ng-model="actCtrl.addslot.start_date"
                                            ng-change="actCtrl.calculateDateOrMonthDifference()"
                                            placeholder="Enter Days" id="start_date"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- month -->
                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.day_month_type == 1">
                                    <label for="start_date" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">Month*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="month" name="start_date" required
                                            ng-model="actCtrl.addslot.start_date"

                                            ng-change="actCtrl.calculateDateOrMonthDifference()"
                                            placeholder="Enter Month" id="start_date"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- set date -->
                                <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.length_type == 'celnder'">
                                    <label for="start_date" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">Set Date:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="date" name="start_date" required
                                            id="set_date"
                                            ng-model="actCtrl.addslot.start_date"
                                            ng-change="actCtrl.calculateDateOrMonthDifference()"
                                            placeholder="{{trans('organizations::index.start_date')}}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- length -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Length:</strong>
                                    </label>
                                    <strong class="col-sm-10 m-auto" ng-model="actCtrl.addslot.end_date"
                                        style="margin-top: 0.5rem; font-weight: bold;">
                                        @{{ actCtrl.dateDifferenceText }}
                                    </strong>
                                </div>
                            </div>

                            <!-- accessories -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'free subscription'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Accessories*:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <select name="accessory"
                                        id="accessory"
                                        ng-model="actCtrl.addslot.accessory"
                                        ng-disabled="actCtrl.addslot.accessory"
                                        class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option disabled value="">Select Accessory</option>
                                        <option value="remote control">Remote Control</option>
                                        <option value="hdmi cable">HDMI Cable</option>
                                        <option value="power adapter">Power Adapter</option>
                                        <option value="wall mount">Wall Mount</option>
                                        <option value="usb hub">USB Hub</option>
                                    </select>
                                </div>
                            </div>

                            <hr style="border: 0.8px solid rgba(128, 130, 133, 0.36);" ng-if="actCtrl.addslot.product_type == 'subscription sets'">

                            <!-- bundles -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.product_type === 'subscription sets'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    <strong>Bundles*:</strong>
                                </label>
                                <div class="col-sm-10">
                                    + Add Bundles
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="panel panel-default" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                        <div class="panel-heading" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                            <strong>Payment Details</strong>
                        </div>
                        <div class="panel-body">
                            <!-- payment service -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Payment Service*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="payment_service" id="payment_service"
                                        ng-model="actCtrl.addslot.payment_service"
                                        class="form-control"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <!-- If NOT free subscription: show all others -->
                                        <option disabled value="">Select Payment Service</option>
                                        <option value="razorpay" ng-if="actCtrl.addslot.product_type != 'free subscription'">Razorpay</option>
                                        <option value="credit card" ng-if="actCtrl.addslot.product_type != 'free subscription'">Credit Card</option>
                                        <option value="autopay" ng-if="actCtrl.addslot.product_type != 'free subscription'">AutoPay</option>
                                        <option value="cash" ng-if="actCtrl.addslot.product_type != 'free subscription'">Cash</option>
                                        <!-- If free subscription: only show Free -->
                                        <option value="free" ng-if="actCtrl.addslot.product_type == 'free subscription'">Free</option>
                                    </select>

                                </div>
                            </div>

                            <!-- cash location -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.payment_service == 'cash'">
                                <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Cash Location*:</strong></label>
                                <div class="col-sm-10">
                                    <select name="cash_location" id="cash_location"
                                        ng-model="actCtrl.addslot.cash_location"
                                        class="form-control" style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <option disabled value="">Select Cash Location</option>
                                        <option value="Delhi Office - Connaught Place">Delhi Office - Connaught Place</option>
                                        <option value="Mumbai Branch - Andheri East">Mumbai Branch - Andheri East</option>
                                        <option value="Bangalore HQ - MG Road">Bangalore HQ - MG Road</option>
                                        <option value="Hyderabad Center - Banjara Hills">Hyderabad Center - Banjara Hills</option>
                                        <option value="Chennai Office - T Nagar">Chennai Office - T Nagar</option>
                                    </select>
                                </div>
                            </div>

                            <!-- currency -->
                            <div ng-if="actCtrl.addslot.product_type == 'subscription sets' || actCtrl.addslot.product_type == 'custom subscription' || actCtrl.addslot.product_type == 'free subscription' || actCtrl.addslot.product_type == 'add devices/slots'">
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>Payment Currency:</strong>
                                    </label>
                                    <div class="col-sm-10" style="margin-top: 8px;">
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.payment_currency"
                                                name="payment_currency" value="₭ lak"> LAK
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.payment_currency"
                                                name="payment_currency" value="฿ thb"> THB
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.payment_currency"
                                                name="payment_currency" value="$ usd"> USD
                                        </label>
                                        <label class="radio-inline">
                                            <input class="radio" type="radio" ng-model="actCtrl.addslot.payment_currency"
                                                name="payment_currency" value="€ eur"> EUR
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- total -->
                            <div class="form-group row" style="margin-bottom: 15px;" ng-if="actCtrl.addslot.product_type == 'custom subscription'">
                                <label for="total" class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Total*:
                                </label>
                                <div class="col-sm-10 m-auto">
                                    <input class="form-control rounded-pill border-3" type="text" name="total" required
                                        ng-model="actCtrl.addslot.total"
                                        placeholder="Enter Total Amount" id="total"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-sm-4">
                    <div class="panel panel-default" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 2px solid rgba(128, 130, 133, 0.36)">
                        <div class="panel-heading" style="font-size: 22px; color: #000; font-weight: 900; padding: 15px; border-radius: 5px; border: 1px solid rgba(128, 130, 133, 0.36)">
                            <strong>Order Summary</strong>
                        </div>

                        <div class="panel-body">
                            <!-- Subscription Section -->
                            <div>
                                <p style="margin-bottom: 5px; font-weight: bold;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                    <strong>Subscription</strong>
                                </p>
                                <div>
                                    <div ng-if="actCtrl.addslot.product_type === 'subscription sets' || actCtrl.addslot.product_type === 'custom subscription' || actCtrl.addslot.product_type === 'free subscription'">
                                        <h5 class="text-muted" style="margin-top: -5px; font-size: medium;">
                                            <!-- @{{ actCtrl.addslot.subscription === 'free' ? 'Free' : (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' : 'None') }} -->
                                            @{{ actCtrl.addslot.subscription == 'free' ? 'Free' : (actCtrl.dateDifferenceText || actCtrl.addslot.durationText || (actCtrl.addslot.subscription ? actCtrl.addslot.subscription + ' Month' : 'None')) }}
                                            <span class="pull-right" ng-model="actCtrl.addslot.total">@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</span>
                                        </h5>
                                    </div>

                                    <div ng-if="actCtrl.addslot.product_type !== 'subscription sets' && actCtrl.addslot.product_type !== 'custom subscription' && actCtrl.addslot.product_type !== 'free subscription'">
                                        <p class="text-muted" style="font-size: medium;">None</p>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0.5px solid rgba(128, 130, 133, 0.36);">

                            <!-- Devices Section -->
                            <div>
                                <p style="margin-bottom: 5px; font-weight: bold;">
                                    <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
                                    <strong>Devices</strong>
                                </p>
                                <div>
                                    <div ng-if="actCtrl.addslot.product_type === 'subscription sets' || actCtrl.addslot.product_type === 'custom subscription' || actCtrl.addslot.product_type === 'free subscription' || actCtrl.addslot.product_type === 'add devices/slots'">
                                        <div ng-if="selectedDevices && selectedDevices.length > 0">
                                            <div ng-repeat="device in selectedDevices">
                                                <h5 class="text-muted" style="font-size: medium;">
                                                    @{{ device.device_name || device.brand_model || 'Unnamed Device' }}
                                                    <span class="pull-right">
                                                        @{{ actCtrl.addslot.product_type === 'add devices/slots' ? (device.price == 0 ? 'Free' : (device.price)) : 'Free' }}
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div ng-if="actCtrl.addslot.product_type !== 'subscription sets' && actCtrl.addslot.product_type !== 'custom subscription' && actCtrl.addslot.product_type !== 'free subscription' && actCtrl.addslot.product_type !== 'add devices/slots'">
                                        <p class="text-muted" style="font-size: medium;">None</p>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0.5px solid rgba(128, 130, 133, 0.36);">

                            <!-- Total -->
                            <!-- <p style="margin-bottom: 5px;">
                                <strong>Total</strong>
                                <span class="pull-right" ng-if="actCtrl.addslot.product_type === 'free subscription'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} 0</strong>
                                </span>
                                <span class="pull-right" ng-if="actCtrl.addslot.product_type !== 'free subscription'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</strong>
                                </span>
                            </p> -->

                            <p style="margin-bottom: 5px;">
                                <strong>Total</strong>

                                <!-- For free subscription, always show 0 -->
                                <span class="pull-right" ng-if="actCtrl.addslot.product_type === 'free subscription'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} 0</strong>
                                </span>

                                <!-- For add devices/slots, show dynamic total -->
                                <span class="pull-right" ng-if="actCtrl.addslot.product_type === 'add devices/slots'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</strong>
                                </span>

                                <!-- For others (subscription sets, Custom, etc.) show original total -->
                                <!-- <span class="pull-right"
                                    ng-if="actCtrl.addslot.product_type !== 'add devices/slots' && actCtrl.addslot.product_type !== 'free subscription'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</strong>
                                </span> -->
                                <span class="pull-right"
                                    ng-if="actCtrl.addslot.product_type !== 'add devices/slots' && actCtrl.addslot.product_type !== 'free subscription'">
                                    <strong>@{{ actCtrl.addslot.payment_currency.split(' ')[0] }} @{{ actCtrl.addslot.total }}</strong>
                                </span>

                            </p>

                            <div class="form-group row" style="margin-bottom: 15px;">
                                <!-- <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 8px;">
                                    <strong></strong>
                                </label> -->
                                <div class="col-sm-10" style="margin-top: 8px;">
                                    <div class="checkbox">
                                        <label style="margin-bottom: 0px;">
                                            <input type="checkbox" ng-model="actCtrl.addslot.terms_of_agreement" name="terms_of_agreement">
                                            Terms of Agreement.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Process Button -->
                            <!-- <button class="btn btn-block" style="background-color: #00ACCD; color: #fff; font-weight: bold;">Process</button> -->
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