<!-- org_sub table code -->
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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'organizations'])
                    <!-- <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{ field.name }}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th> -->
                    <th>S.No</th>
                    <th>Account Number</th>
                    <th>User Name</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created</th>
                    <th>Subscription</th>
                    <th>Subscription Status</th>
                    <th>Subscription Length</th>
                    <th>Expries</th>
                    <th>Autopay</th>
                    <th>Active Device</th>
                    <th>Last Activity</th>
                    <th>Opration</th>
                </tr>
            </thead>

            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.account_number"
                            placeholder="Enter Account Number" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="account number" id="searchSubscriber"
                            ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()">
                    </td>
                    <td class="search_product td-custom-width">
                        <input type="text" class="form-control" data-ng-model="searchRecords.user_name"
                            placeholder="Enter User Name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="user name"
                            ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription" placeholder="Enter Full Name"
                            data-ng-model="searchRecords.first_name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="full name"
                            ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription" placeholder="Enter Email"
                            data-ng-model="searchRecords.email" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="email" ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription" placeholder="Enter Phone"
                            data-ng-model="searchRecords.phone_number" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="phone" ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()">
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <!-- <select class="form-control mb15 select2_custom_ddl" minimumResults="-1"
                            data-jquery="select2_custom_ddl" data-boot-tooltip="true"
                            ng-keyup="$event.keyCode == 13 && subCtrl.filterSubscribers()"
                            data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip"
                            data-original-title="{{ trans('base::general.select_status') }}">
                            <option value="all">{{ trans('base::general.all') }}</option>
                            <option value='1'>{{ trans('customer::subscription.active') }}</option>
                            <option value='0'>{{ trans('customer::subscription.inactive') }}</option>
                        </select> -->
                    </td>
                    <td></td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                        {{ trans('base::general.not_found') }}
                    </td>
                </tr>

                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" class="list-repeat"
                    data-ng-show="showRecords" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{ record.id }}"
                                ng-click="selectRecord($event, record.id)" value="@{{ record.id }}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>
                    <!-- subsctiber details start -->
                    <td> @{{ record.account_number }} </td>
                    <td> @{{ record.user_name }} </td>
                    <td> @{{ record.first_name + ' ' + record.last_name }} </td>
                    <td> @{{ record.email }} </td>
                    <td> @{{ record.phone_number_code + ' ' + record.phone_number || '-' }} </td>
                    <td> @{{ record.created_at | date: 'dd-MM-yyyy' }} </td>
                    <!-- subscriber details end -->

                    <!-- product_type -->
                    <td> @{{ record.product_type || '-' }} </td>

                    <!-- active status -->
                    <td
                        data-ng-class="{
                        'bg-success': record.is_active == 1 && !subCtrl.isExpired(record.end_date),
                        'bg-warning': record.is_active == 2 && !subCtrl.isExpired(record.end_date),
                        'bg-danger' : record.is_active != 1 && record.is_active != 2 || subCtrl.isExpired(record.end_date)}">

                        <!-- this code for active  -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-active"
                                ng-if="record.is_active == 1 && !subCtrl.isExpired(record.end_date)"
                                style="cursor: pointer;" data-toggle="modal">
                                {{ trans('customer::subscription.message.active') }}
                            </span>

                            <span class="status-inactive"
                                ng-if="record.is_active == 1 && subCtrl.isExpired(record.end_date)">
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
                            <!-- <span class="tooltip_title">
                                {{ trans('customer::subscription.deactivate_subscription') }}
                            </span> -->
                        </div>


                        <!-- this code for active end -->

                        <!-- this code or in-active -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-inactive" ng-if="record.is_active != 1 && record.is_active != 2"
                                style="cursor: pointer;" data-toggle="modal">
                                {{ trans('customer::subscription.message.inactive') }}
                            </span>
                            <span
                                class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}</span>
                        </div>


                        <!-- this code or in-active end -->

                        <!-- data-ng-click="confirmationPopupSingleRecordAction(record)" -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
                            <span class="status-waiting" ng-if="record.is_active == 2"
                                style="cursor: pointer; color: #ffa100;" data-toggle="modal">
                                Waiting
                            </span>
                            <span class="tooltip_title">
                                {{ trans('customer::subscription.activate_subscription') }}
                            </span>

                            <!-- &nbsp;&nbsp;&nbsp; -->

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

                    <!-- length -->
                    <td> @{{ calculateDays(record.start_date, record.end_date) || '-' }} </td>

                    <!-- end date -->
                    <td> @{{ record.end_date ? (record.end_date | date: 'dd-MM-yyyy') : '-' }} </td>

                    <!-- autopay -->
                    <td class="table-action">
                        <div class="flexbox ">
                            <div data-ng-if="checkAccess('subscribers')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" disabled ng-checked="record.auto_pay == 1"
                                        ng-click="togglePublishNow(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>

                    <!-- active device -->
                    <td> @{{ subCtrl.getDeviceCount(record.device) || '-' }} </td>

                    <!-- last activity -->
                    <td>
                        @{{ record.login_at || '-' }}
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div data-ng-if="checkAccess('subscribers')" class="column edit_table_icon tooltip-parent">
                                <button class="table_action" data-ng-click="subCtrl.editSubscriber(record.id)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{ trans('base::general.edit') }}</span>
                            </div>

                            <!-- credit card  -->
                            <div data-ng-if="checkAccess('subscribers')" class="column tooltip-parent">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                    data-ng-click="subCtrl.creditcard(record.id)" class="tooltips delete_table_icon"
                                    data-boot-tooltip="true" data-original-title="">
                                    <svg width="12" height="11" viewBox="0 0 24 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <rect width="24" height="16" rx="2" ry="2" fill="#454545" />
                                        <rect y="4" width="24" height="3" fill="#666666" />
                                        <rect x="2" y="10" width="6" height="2" fill="#CCCCCC" />
                                        <rect x="10" y="10" width="8" height="2" fill="#CCCCCC" />
                                    </svg>
                                </span>
                                <span class="tooltip_title">subscription settitng</span>
                            </div>&nbsp;&nbsp;&nbsp;&nbsp;

                            <!-- custom content hint -->
                            <div data-ng-if="record.custom_stream_count > 0" class="tooltip-parent">
                                <span ng-mouseover="getTooltip($event)" 
                                    class="tooltips delete_table_icon" data-boot-tooltip="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" stroke="#f39c12" stroke-width="2.5" fill="none" />
                                        <line x1="12" y1="8" x2="12" y2="13" stroke="#f39c12" stroke-width="2.5"
                                            stroke-linecap="round" />
                                        <circle cx="12" cy="17" r="1.2" fill="#f39c12" />
                                    </svg>
                                </span>
                                <span class="tooltip_title">Subscriber has Custom Content</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- create org_sub code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="organizationForm" id="organizationForm" method="POST" data-base-validator
            data-ng-submit="subCtrl.sidepanelsave($event, subCtrl)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <!-- Header -->
            <div class="sidepanel-header flexbox align-items-center">
                <h5>Create Subscribers</h5>
            </div>

            <!-- Form Scrollable Area -->
            <div class="sidepanel-scroll" style="max-height: calc(97vh - 120px);
                overflow-y: auto;
                overflow-x: hidden;">
                @include('base::partials.errors')

                <!-- User Name -->
                <div class="form-group">
                    <label>
                        {{ __('subscribers::index.user_name.user_name') }} <span class="required">*</span>
                    </label>
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" name="user_name" data-unique="@{{ subCtrl.uniqueRoute }}"
                                data-ng-model="subCtrl.org_sub.user_name" class="form-control" id="user_name"
                                placeholder="{{ trans('subscribers::index.user_name.name') }}" />
                        </div>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" id="username_auto" onclick="generateUsername()"
                                style="margin-right: 5px;">
                            <label for="username_auto"><strong>Auto</strong></label>
                        </div>
                    </div>
                    <p class="error-msg">@{{ errors.user_name.message }}</p>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label>{{ __('subscribers::index.first_name.first_name') }} <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text" name="first_name" data-unique="@{{ subCtrl.uniqueRoute }}"
                            data-ng-model="subCtrl.org_sub.first_name" class="form-control"
                            placeholder="{{ trans('subscribers::index.first_name.name') }}" />
                    </div>
                    <p class="error-msg">@{{ errors.first_name.message }}</p>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label>{{ __('subscribers::index.last_name.last_name') }} <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text" name="last_name" data-unique="@{{ subCtrl.uniqueRoute }}"
                            data-ng-model="subCtrl.org_sub.last_name" class="form-control"
                            placeholder="{{ trans('subscribers::index.last_name.name') }}" />
                    </div>
                    <p class="error-msg">@{{ errors.last_name.message }}</p>
                </div>

                <!-- Organization Dropdown -->
                <div class="form-group">
                    <label>{{ __('subscribers::index.organization.select_organization') }}
                        <span class="required">*</span>
                    </label>
                    <select class="form-control mb10 select2_custom_ddl" id="org-id" ng-model="subCtrl.org_sub.id"
                        data-jquery="select2_custom_ddl"
                        myPlaceholder="{{ trans('subscribers::index.organization.select_organization') }}"
                        ng-options="org.id as org.organization_name for org in subCtrl.orgList"
                        ng-change="subCtrl.setOrgName(subCtrl.org_sub.id)">
                        <option value="">-- Select Organization --</option>
                    </select>

                    <p class="error-msg">@{{ errors.organization_name.message }}</p>
                </div>


                <!-- Account Number -->
                <div class="form-group">
                    <label>{{ __('subscribers::index.account_number.account_number') }} <span
                            class="required">*</span></label>
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" name="account_number" id="account_number"
                                data-unique="@{{ subCtrl.uniqueRoute }}" data-ng-model="subCtrl.org_sub.account_number"
                                class="form-control"
                                placeholder="{{ trans('subscribers::index.account_number.name') }}" />
                        </div>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="checkbox" id="account_auto" onclick="generateAccountNumber()"
                                style="margin-right: 5px;">
                            <label for="account_auto"><strong>Auto</strong></label>
                        </div>
                    </div>
                    <p class="error-msg">@{{ errors.account_number.message }}</p>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>{{ __('subscribers::index.email.email') }} <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="email" name="email" data-unique="@{{ subCtrl.uniqueRoute }}"
                            data-ng-model="subCtrl.org_sub.email" class="form-control"
                            placeholder="{{ trans('subscribers::index.email.name') }}" />
                    </div>
                    <p class="error-msg">@{{ errors.email.message }}</p>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="subCtrl.closeSubscriptionEdit()" name="cancel" class="save">
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now">
            </div>
        </form>
    </div>
</div>