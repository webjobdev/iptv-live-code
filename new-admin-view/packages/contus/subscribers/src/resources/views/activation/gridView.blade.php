<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">

        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    <!-- @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscribers']) -->
                    <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                            data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords && record.product_type !== 'add devices/slots' && record.product_type !== 'accessories' && record.is_active == 1"
                    data-ng-repeat="record in subscriptionRecords | filter:{ subscriber_id: subscriberIdFromUrl } track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <!-- <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td> -->

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <!-- current status -->
                    <td
                        data-ng-class="{
                        'bg-success': record.is_active == 1 && !actCtrl.isExpired(record.end_date),
                        'bg-warning': record.is_active == 2 && !actCtrl.isExpired(record.end_date),
                        'bg-danger' : record.is_active != 1 && record.is_active != 2 || actCtrl.isExpired(record.end_date)}">

                        <!-- this code for active  -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-active"
                                ng-if="record.is_active == 1 && !actCtrl.isExpired(record.end_date)"
                                style="cursor: pointer;" data-toggle="modal"
                                data-target="#single-record-status-update-popup"
                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.active') }}
                            </span>

                            <span class="status-inactive"
                                ng-if="record.is_active == 1 && actCtrl.isExpired(record.end_date)">
                                {{ trans('customer::subscription.message.inactive') }}
                            </span>

                            &nbsp;&nbsp;&nbsp;

                            <!-- toa -->
                            <!-- <span class="label label-default"
                                ng-if="record.is_active == 1 && record.terms_of_agreement == 1">
                                TOA
                            </span> -->

                            <!-- pending toa -->
                            <!-- <span class="label label-warning"
                                ng-if="record.is_active == 1 && record.terms_of_agreement == 0">
                                Toa Pending
                            </span> -->

                            <!-- tooltip -->
                            <span class="tooltip_title">
                                {{ trans('customer::subscription.deactivate_subscription') }}
                            </span>
                        </div>


                        <!-- this code for active end -->

                        <!-- this code or in-active -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-inactive" ng-if="record.is_active != 1 && record.is_active != 2"
                                style="cursor: pointer;" data-toggle="modal"
                                data-target="#single-record-status-update-popup"
                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.inactive') }}
                            </span>
                            <span class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}
                            </span>
                        </div>


                        <!-- this code or in-active end -->


                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-waiting" ng-if="record.is_active == 2"
                                style="cursor: pointer; color: #ffa100;" data-toggle="modal"
                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                Waiting
                            </span>
                            <span class="tooltip_title">
                                {{ trans('customer::subscription.activate_subscription') }}
                            </span>

                            &nbsp;&nbsp;&nbsp;

                            <!-- toa -->
                            <!-- <span class="label label-default"
                                ng-if="record.is_active == 2 && record.terms_of_agreement == 1">
                                TOA
                            </span> -->

                            <!-- pending toa -->
                            <!-- <span class="label label-warning"
                                ng-if="record.is_active == 2 && record.terms_of_agreement == 0">
                                Toa Pending
                            </span> -->
                        </div>
                    </td>

                    <!-- active untill -->
                    <td class="">
                        @{{ record.end_date ? (record.end_date | date:'dd-MM-yyyy') : '-' }}
                    </td>

                    <!-- day left -->
                    <td>
                        @{{ calculateDays(record.start_date, record.end_date) }}
                    </td>

                    <!-- autopay code -->
                    <td class="table-action">
                        <div class="flexbox ">
                            <div data-ng-if="checkAccess('subscribers')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.auto_pay == 1"
                                        ng-click="togglePublishNow(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>

                    <!-- subscription code -->
                    <td>
                        @{{ record.plan_detail.subscription_name }}
                    </td>

                    <!-- device -->
                    <td>
                        @{{ actCtrl.getDeviceCount(record.device) }} Devices
                    </td>

                    <!-- status -->
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div data-ng-if="checkAccess('subscribers')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.is_active == 1" ng-disabled="true"
                                        ng-click="!actCtrl.isExpired(record.end_date) && togglePublishNow(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

@include('audio::admin.common.singleRecordDeleteModal')
@include('audio::admin.common.singleRecordStatusUpdateModal')
@include('base::layouts.pagination')