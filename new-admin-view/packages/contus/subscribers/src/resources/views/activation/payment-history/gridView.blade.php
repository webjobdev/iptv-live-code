<!-- payment table code -->
<div id="paymentcard">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>

    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscribers'])
                    <th data-ng-repeat="field in heading" data-ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{ field.name }}
                        <span data-ng-if="field.sort" class="th-inner sortable both" data-ng-click="fieldOrder($event, field.value)"></span>
                        <span data-ng-if="!field.sort"></span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <!-- Search row -->
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.transaction_detail.payment_id" placeholder="Payment ID">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.created_at" placeholder="Date">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.email" placeholder="Email">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.payment_service" placeholder="Payment Service">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.source" placeholder="Source">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.status" placeholder="Status">
                    </td>
                    <td></td>
                    <td></td>
                    <!-- <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.total" placeholder="Total">
                    </td> -->
                </tr>

                <!-- No records -->
                <tr data-ng-if="noRecords">
                    <td colspan="@{{ heading.length + 1 }}" class="no-data center">{{ trans('base::general.not_found') }}</td>
                </tr>

                <!-- Records -->
                <tr data-ng-if="showRecords"
                    data-ng-repeat-start="record in records | filter:{ subscriber_id: subscriberIdFromUrl } track by $index"
                    class="list-repeat"
                    data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="roles_@{{ record.id }}" ng-click="selectRecord($event, record.id)"
                                value="@{{ record.id }}" name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>

                    <td class="serial_number">
                        @{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}
                    </td>

                    <!-- payment number -->
                    <td>
                        <a data-toggle="collapse" data-parent="#historyAccordian" href="#history_@{{ record.id }}" aria-expanded="false">
                            @{{ record.transaction_detail && record.transaction_detail.status === 'PAYMENT_REFUND' ? (record.transaction_detail.refund_id || 'cash') : (record.transaction_detail.payment_id || 'cash') }}
                        </a>
                    </td>

                    <!-- process date -->
                    <td>
                        @{{ record.created_at | date:'dd-MM-yyyy' }}
                    </td>

                    <!-- by user -->
                    <td>
                        @{{ record.subscriber_detail.email }}
                    </td>

                    <!-- payment service -->
                    <td>
                        @{{
                            record.payment_service === 'payment card' ? 'authorize.net' :
                            record.payment_service === 'cash' ? 'cash' :
                            record.payment_service === 'razorpay' ? 'razorpay' :
                            record.payment_service === 'autopay' ? 'autoPay' :
                            record.payment_service 
                        }}
                        <span data-ng-if="record.payment_service === 'cash'">
                            <i class="fa fa-money text-success fa-lg"></i>
                        </span>
                        <span data-ng-if="record.payment_service === 'razorpay'">
                            <i class="fa fa-google-wallet fa-lg"></i>
                        </span>
                        <span data-ng-if="record.payment_service === 'autopay'">
                            <i class="fa fa-refresh text-info fa-lg"></i>
                        </span>
                        <span data-ng-if="record.payment_service === 'payment card'">
                            <i class="fa fa-solid fa-hashtag fa-lg"></i>
                        </span>
                    </td>

                    <!-- source of purchase -->
                    <td>
                        @{{
                            record.payment_service === 'payment card' ? 'Client App' :
                            record.payment_service === 'cash' ? 'External Systems' :
                            record.payment_service 
                        }}
                    </td>

                    <!-- payment status -->
                    <td>
                        <span class="text-success"
                            data-ng-if="record.transaction_detail && record.transaction_detail.status === 'PAYMENT_SUCCESS'">
                            paid
                        </span>
                        <span class="text-warning"
                            data-ng-if="record.transaction_detail && record.transaction_detail.status === 'PAYMENT_REFUND'">
                            refund
                        </span>
                        <span class="text-danger"
                            data-ng-if="record.transaction_detail && record.transaction_detail.status === 'PAYMENT_FAILED'">
                            cancel
                        </span>
                        <span class="text-warning"
                            data-ng-if="record.transaction_detail && (!record.transaction_detail.status || record.transaction_detail.status === '')">
                            pending
                        </span>
                        <span class="text-success"
                            data-ng-if="!record.transaction_detail && record.payment_service === 'cash'">
                            paid
                        </span>
                    </td>

                    <!-- internal comment -->
                    <td class="table-action">
                        <div data-ng-if="checkAccess('subscribers')" class="column edit_table_icon tooltip-parent">
                            <button
                                class="table_action sidepanel-open"
                                data-ng-click="pysCtrl.payment.subscription_and_payments_id = record.id"
                                value="@{{ record.id }}">
                                <i class="fa fa-plus fa-lg"></i>
                            </button>
                            <span class="tooltip_title">Send Comment</span>
                        </div>
                    </td>

                    <!-- pdf code -->
                    <td class="table-action">
                        <div data-ng-if="checkAccess('subscribers')" class="column tooltip-parent">
                            <button ng-click="pysCtrl.downloadPdf(record.id)" value="@{{ record.id }}">
                                <i class="fa fa-file-pdf-o text-danger fa-2x" aria-hidden="true"></i>
                            </button>
                            <span class="tooltip_title">Download pdf</span>
                        </div>
                    </td>

                    <!-- total -->
                    <td>
                        @{{ record.payment_currency.split(' ')[0] + ' ' + record.total }}
                    </td>

                    <!-- void action -->
                    <td>
                        <div data-ng-if="checkAccess('subscribers')">
                            <button class="btn btn-default btn-s text-uppercase" value="@{{ record.id }}"
                                ng-click="pysCtrl.voidpmt(record)"
                                style="background-color: #fff; color: #ff0101b3; border-radius: 12px; padding: 4px 12px; font-weight: 600; letter-spacing: 0.5px;">
                                <i class="fa fa-ban"></i> Void
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Model (details) row -->
                <tr data-ng-attr-id="history_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="@{{ heading.length + 1 }}">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="center">Active From</th>
                                    <th class="center">Active Until</th>
                                    <th class="center">Length</th>
                                    <th class="center">Product Type</th>
                                    <th class="center">Name</th>
                                    <th class="center">Monetization Type</th>
                                    <th class="center">AutoPay</th>
                                    <th class="center">Price</th>
                                    <th class="center">Subscription Status</th>
                                    <th class="center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">
                                        @{{ record.start_date ? (record.start_date | date:'dd-MM-yyyy') : '-' }}
                                    </td>

                                    <td class="center">
                                        @{{ record.end_date ? (record.end_date | date:'dd-MM-yyyy') : '-' }}
                                    </td>

                                    <td class="center">
                                        @{{ calculateDays(record.start_date, record.end_date) || '-'}}
                                    </td>

                                    <td class="center">
                                        <span data-ng-if="record.product_type === 'custom subscription'">
                                            custom subscription <i class="fa fa-cogs text-primary fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'subscription sets'">
                                            subscription sets <i class="fa fa-laptop text-info fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'free subscription'">
                                            free subscription <i class="fa fa-gift text-success fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'add devices/slots'">
                                            add devices/slots <i class="fa fa-mobile text-warning fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'accessories'">
                                            accessories <i class="fa fa-headphones text-secondary fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'custom charge'">
                                            custom charge <i class="fa fa-dollar text-danger fa-2x"></i>
                                        </span>
                                        <span data-ng-if="record.product_type === 'bundles'">
                                            bundles <i class="fa fa-cube text-purple fa-2x"></i>
                                        </span>
                                        <span data-ng-if="!record.product_type">
                                            -
                                        </span>
                                    </td>

                                    <td class="center">
                                        @{{ record.subscription || '-' }}
                                    </td>

                                    <td class="center">
                                        <!-- @{{ record.subscribable_type === 'buy' ? 'Buy' }} -->
                                        buy
                                    </td>

                                    <td class="center">
                                        @{{ record.payment_service === 'autopay' ? 'Yes' : 'No' }}
                                    </td>

                                    <td class="center">
                                        @{{ record.payment_currency.split(' ')[0] + ' ' + record.total }}
                                    </td>

                                    <td data-ng-class="{'bg-success': record.is_active == 1, 'bg-danger': record.is_active != 1}">
                                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                                            <span class="status-active"
                                                ng-if="record.is_active == 1 && !pysCtrl.isExpired(record.end_date)"
                                                style="cursor: pointer;"
                                                data-toggle="modal"
                                                data-target="#single-record-status-update-popup"
                                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                                {{ trans('customer::subscription.message.active') }}
                                            </span>

                                            <span class="status-inactive"
                                                ng-if="record.is_active == 1 && pysCtrl.isExpired(record.end_date)">
                                                {{ trans('customer::subscription.message.inactive') }}
                                            </span>
                                            &nbsp;&nbsp;&nbsp;
                                            <span class="label label-default"
                                                ng-if="record.is_active == 1 && record.terms_of_agreement == 1">
                                                TOA
                                            </span>

                                            <span class="tooltip_title">
                                                {{ trans('customer::subscription.deactivate_subscription') }}
                                            </span>
                                        </div>

                                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                                            <span class="status-inactive" ng-if="record.is_active != 1"
                                                style="cursor: pointer;"
                                                data-toggle="modal"
                                                data-target="#single-record-status-update-popup"
                                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                                {{ trans('customer::subscription.message.inactive') }}
                                            </span>
                                            <span class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}</span>
                                        </div>

                                        

                                        
                                    </td>

                                    <td class="center">
                                        <button class="btn btn-default btn-s text-uppercase"
                                            data-toggle="modal" data-target="#flipFlop" data-ng-click="pysCtrl.adjustlength(record)"
                                            style="background-color: #fff; color: black; border-radius: 12px; padding: 4px 12px; font-weight: 600; letter-spacing: 0.5px;">
                                            <i class="fa fa-adjust"></i> Adjust Length
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal') -->
    @include('base::layouts.pagination')

</div>


<div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel">Adjust Length</h4>
            </div>

            <form method="POST" enctype="multipart/form-data" data-base-validator data-ng-submit="pysCtrl.updatesubscription($event)">
                {!! csrf_field() !!}

                <input type="hidden" id="subscriber-id" name="subscriber-id" value="{{ request()->query('subscriber-id') }}">
                <input type="hidden" id="id" name="id" ng-model="pysCtrl.payment.id">


                <div class="modal-body" style="padding: 20px;">

                    <!-- Section Title -->
                    <div class="text-center" style="margin-bottom: 20px;">
                        <h4 style="font-weight: bold; color: #333; text-transform: uppercase; margin: 0;">Subscription</h4>
                    </div>

                    <div class="panel panel-default" style="border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        <div class="panel-body">

                            <!-- Active From -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label" style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Active From:
                                </label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control"
                                        ng-disabled="pysCtrl.payment.start_date"
                                        ng-model="pysCtrl.payment.start_date"
                                        placeholder="Select start date">
                                </div>
                            </div>


                            <!-- Length -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label" style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Length:
                                </label>
                                <div class="col-sm-9">
                                    <div class="well well-sm text-center"
                                        ng-model="pysCtrl.payment.subscription"
                                        style="font-size: 14px; font-weight: bold; margin-bottom: 0;">
                                        @{{ pysCtrl.payment.subscription }}
                                    </div>
                                </div>
                            </div>

                            <!-- length type -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label" style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Length Type:
                                </label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="pysCtrl.payment.length_type" value="days_of_adjustment">
                                            Days of adjustment
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="pysCtrl.payment.length_type" value="expiration">
                                            Expiration date
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- length adjustment -->
                            <div class="form-group row" style="margin-bottom: 15px;" data-ng-if="pysCtrl.payment.length_type == 'days_of_adjustment'">
                                <label class="col-sm-3 control-label"
                                    style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Length Adjustment:
                                </label>

                                <div class="col-sm-9">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-4 col-sm-3 mb-2 mb-sm-0">
                                            <button type="button"
                                                class="well well-sm text-center"
                                                style="font-size: 14px; font-weight: bold; margin-bottom: 0;"
                                                ng-click="pysCtrl.setSign('-')">
                                                Subtract
                                            </button>
                                        </div>
                                        <div class="col-4 col-sm-3 mb-2 mb-sm-0">
                                            <button type="button"
                                                class="well well-sm text-center"
                                                style="font-size: 14px; font-weight: bold; margin-bottom: 0;"
                                                ng-click="pysCtrl.setSign('+')">
                                                Add
                                            </button>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    ng-model="pysCtrl.payment.days"
                                                    ng-change="pysCtrl.daycount()"
                                                    placeholder="Enter days">
                                                <span class="input-group-text">Day(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" style="margin-bottom: 15px;" data-ng-if="pysCtrl.payment.length_type == 'expiration'">
                                <label class="col-sm-3 control-label"
                                    style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Length Adjustment:
                                </label>

                                <div class="col-sm-9">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-4 col-sm-3 mb-2 mb-sm-0">
                                            <button type="button"
                                                class="well well-sm text-center"
                                                style="font-size: 14px; font-weight: bold; margin-bottom: 0;"
                                                ng-click="pysCtrl.setSign('-')">
                                                Subtract
                                            </button>
                                        </div>
                                        <div class="col-4 col-sm-3 mb-2 mb-sm-0">
                                            <button type="button"
                                                class="well well-sm text-center"
                                                style="font-size: 14px; font-weight: bold; margin-bottom: 0;"
                                                ng-click="pysCtrl.setSign('+')">
                                                Add
                                            </button>
                                        </div>
                                        <div class="col-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    ng-change="pysCtrl.daycount()"
                                                    ng-model="pysCtrl.payment.days"
                                                    placeholder="Enter days">
                                                <span class="input-group-text">Day(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Until -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label" style="font-weight: 600; color: #555; margin-top: 7px;">
                                    Active Until:
                                </label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control"
                                        ng-model="pysCtrl.payment.end_date"
                                        placeholder="Select start date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top: 20px;">
                        <h4 style="font-weight: bold; color: #333; text-transform: uppercase; margin: 0;">Content Add-On</h4>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bottom-button text-right flexbox align-items-center">
                    <input type="button" value="{{ trans('base::general.cancel') }}" data-dismiss="modal"
                        data-ng-click="pysCtrl.closeSubscriptionEdit()" name="cancel" class="save btn btn-default">

                    <input type="submit" value="{{ trans('base::general.submit') }}"
                        style="background-color: #00ACCD; color: #fff;"
                        name="submit" class="publish-now btn">
                </div>
            </form>

        </div>
    </div>
</div>



<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="subscriptionForm" id="subscriptionForm" method="POST"
            data-base-validator
            data-ng-submit="pysCtrl.savecomment($event, pysCtrl.payment.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}

            <!-- <input type="hidden" id="subscriber-id" name="id">
            <script>
                document.getElementById('subscriber-id').value = window.location.pathname.split('/').pop();
            </script> -->

            <input type="hidden" id="subscriber-id" name="subscriber-id" value="{{ request()->query('subscriber-id') }}">

            <div class="sidepanel-header flexbox align-items-center">
                <!-- <h5 data-ng-if="!pysCtrl.payment.id">
                    Edit Comment
                </h5 data-ng-if="pysCtrl.payment.id"> -->
                <h5>
                    Send Comment For Subscription
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        Send Comment For Subscription
                        <span class="required">*</span>
                    </label>
                    <input type="text"
                        name="comment"
                        data-ng-model="pysCtrl.payment.comment"
                        class="form-control"
                        placeholder="{{trans('organizations::index.anc_name')}}" />
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>
            </div>



            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}" data-ng-click="pysCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>