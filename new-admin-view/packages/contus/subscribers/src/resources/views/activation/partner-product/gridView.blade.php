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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscribers'])
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
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product td-custom-width">
                        <input type="text" class="form-control search-amount-subscription" placeholder="Device Name"
                            data-ng-model="searchRecords.device_name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="device name">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="">
                        <select class="form-control mb15 select2_custom_ddl" minimumResults="-1"
                            data-jquery="select2_custom_ddl" data-boot-tooltip="true"
                            data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip"
                            data-original-title="{{trans('base::general.select_status')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value='1'>{{trans('customer::subscription.active')}}</option>
                            <option value='0'>{{trans('customer::subscription.inactive')}}</option>
                        </select>
                    </td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords && record.product_type !== 'add devices/slots'"
                    data-ng-repeat="record in records | filter:{ subscriber_id: subscriberIdFromUrl } track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <td class="">
                        @{{ record.device_name }}
                    </td>

                    <td class="">
                        @{{ record.subscription_and_payments_detaile.start_date | date:'dd-MM-yyyy' }}
                    </td>

                    <td class="">
                        @{{ record.subscription_and_payments_detaile.end_date | date:'dd-MM-yyyy' }}
                    </td>

                    <td class="">
                        <span
                            data-ng-if="record.subscription_and_payments_detaile.transaction_detail.status === 'PAYMENT_SUCCESS'">ok</span>
                        <span
                            data-ng-if="record.subscription_and_payments_detaile.transaction_detail.status === 'PAYMENT_FAILED'">failed</span>
                    </td>

                    <td class="">
                        @{{ ppCtrl.getStatus(record) }}
                    </td>

                    <td class="center">
                        <div data-ng-if="checkAccess('subscribers')" class="form-group row"
                            style="margin-bottom: 0px; margin-right: 5px;">
                            <label class="switch">
                                <input type="checkbox" ng-disabled="record.device_detaile.device_type !== 'tv'"
                                    ng-checked="record.device_detaile.device_type === 'tv' && record.is_active == 1"
                                    ng-click="record.device_detaile.device_type === 'tv' && togglePublishNow(record, record.id)">
                                <span class="slider round"
                                    ng-class="{'green': record.device_detaile.device_type === 'tv' && record.is_active == 1}"></span>
                            </label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <!-- @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal') -->
    @include('base::layouts.pagination')
</div>

<br>