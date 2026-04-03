<style>
    .sidepanel-scroll {
        max-height: calc(97vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
</style>

<!-- push notification table code -->
<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table tablesaw" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'api_access'])
                    <th data-ng-repeat="field in heading" ng-class="">
                        @{{ field.name }}
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
                    <td class="search_product">
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">From</label>
                            <input type="date" class="form-control" id="create_from_date_inpt"
                                data-ng-model="searchRecords.created_at_from" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">To</label>
                            <input type="date" class="form-control" id="create_to_date_inpt"
                                data-ng-model="searchRecords.created_at_to" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>
                    </td>
                    <td class="search_product">
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">From</label>
                            <input type="date" class="form-control" id="update_from_date_inpt"
                                data-ng-model="searchRecords.updated_at_from" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">To</label>
                            <input type="date" class="form-control" id="update_to_date_inpt"
                                data-ng-model="searchRecords.updated_at_to" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>

                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.created_by"
                            placeholder="search user email.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter User Email">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.name"
                            placeholder="search name.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Name">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.subscription"
                            placeholder="search subscription.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Subscription">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.title"
                            placeholder="search title.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Title">
                    </td>
                    <td>
                        <select class="select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl"
                            myPlaceholder="Choose Status" data-boot-tooltip="true" data-ng-model="searchRecords.status"
                            data-ng-change="search()" data-toggle="tooltip" data-original-title="Select status">
                            <option value="" disabled selected>Choose Status</option>
                            <option value="0">Send Out</option>
                            <option value='1'>Pending</option>
                            <option value='2'>Deleted</option>
                            <option value='3'>Failed</option>
                        </select>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                        {{ trans('base::general.not_found') }}
                    </td>
                </tr>

                <tr data-ng-if="showRecords" data-ng-repeat-start="record in PushNotRecords track by $index"
                    class="list-repeat" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{ record.id }}"
                                ng-click="selectRecord($event, record.id)" value="@{{ record.id }}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>

                    {{-- <pre>@{{ permsnList.announcements | json }}</pre> --}}

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>
                    <td class="">@{{ record.formatted_created_at }}</td>
                    <td class="">@{{ record.formatted_updated_at }}</td>
                    <td class="">@{{ record.user[0].email }}</td>
                    <td class="">@{{ record.name }}</td>
                    <td class="">@{{ record.org_subscription[0].name }}</td>
                    <td class="">@{{ record.title }}</td>
                    <td class="">@{{ record.status == 0 ? 'Send Out' : record.status == 1 ? 'Pending' : record.status ==
    2 ? 'Deleted' : record.status == 3 ? 'Failed' : '' }}</td>
                    <td class="" style="text-align: center">
                        <div class="flexbox align-items-center justify-center">
                            <!-- view button -->
                            <div class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="ancPushNotifctnCtrl.viewPushNotification(record)">
                                    <svg width="15px" height="15px" viewBox="0 0 1024 1024" class="icon"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#000000"
                                            d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352zm0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 110 448 224 224 0 010-448zm0 64a160.192 160.192 0 00-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160z" />
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{ trans('base::general.view') }}</span>
                            </div>

                            <!-- copy button -->
                            <div class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="ancPushNotifctnCtrl.copyPushNotification(record)">
                                    <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="style=fill">
                                            <g id="copy">
                                                <path id="Subtract" fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.1667 0.25C8.43733 0.25 6.25 2.50265 6.25 5.25H12.8333C15.5627 5.25 17.75 7.50265 17.75 10.25V18.75H17.8333C20.5627 18.75 22.75 16.4974 22.75 13.75V5.25C22.75 2.50265 20.5627 0.25 17.8333 0.25H11.1667Z"
                                                    fill="#000000" />
                                                <path id="rec"
                                                    d="M2 10.25C2 7.90279 3.86548 6 6.16667 6H12.8333C15.1345 6 17 7.90279 17 10.25V18.75C17 21.0972 15.1345 23 12.8333 23H6.16667C3.86548 23 2 21.0972 2 18.75V10.25Z"
                                                    fill="#000000" />
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title" id="copy-btn">Copy</span>
                            </div>

                            <!-- delete button -->
                            <div class="tooltip-parent">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                    data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                    class="tooltips delete_table_icon" data-boot-tooltip="true"
                                    data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g data-original-title="" title="">
                                            <path
                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                    <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr data-ng-attr-id="anc_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="8">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="center">Message</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">@{{ record.message }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<style>
    .sidepanel-scroll {
        max-height: calc(97vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
</style>

<!-- create announcement push notification code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="ancPushNotificationForm" id="ancPushNotificationForm" method="POST" data-base-validator
            data-ng-submit="ancPushNotifctnCtrl.savePushNotification($event)" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <input type="hidden" id="organization-id" value="{{ request()->id }}">
            <input type="text" hidden id="org-id" name="id" value="{{ Request::url() }}">

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!ancPushNotifctnCtrl.pushNotificationData.id">
                    {{ trans('organizations::index.add_notifictn') }}
                </h5>
                <h5 data-ng-if="ancPushNotifctnCtrl.pushNotificationData.id">
                    {{ trans('organizations::index.view_notifictn') }}
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- Name -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name"
                            data-ng-readonly="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.name" class="form-control"
                            placeholder="{{ trans('organizations::index.notifictn_name_hldr') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>

                <!-- Title -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_title') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="title"
                            data-ng-readonly="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.title" class="form-control"
                            placeholder="{{ trans('organizations::index.notifictn_title_hldr') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                </div>

                <!-- Description -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_description') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="message"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.description" class="form-control"
                            data-ng-readonly="ancPushNotifctnCtrl.pushNotificationData.id"
                            placeholder="{{ trans('organizations::index.notifictn_desc_hldr') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.description.has">@{{ errors.description.message }}</p>
                </div>

                <!-- Subscription -->
                <div class="form-group" data-ng-class="{'has-error': errors.subscription.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_subsc') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <select class="select2_custom_ddl" allowClear="1" data-jquery="select2_custom_ddl"
                            name="subscription" id="subscriber-optns" class="admin_category_sub  form-control"
                            myPlaceholder="Choose Subscription"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            ng-model="ancPushNotifctnCtrl.pushNotificationData.subscription"
                            ng-options='rule.id as rule.name for rule in ancPushNotifctnCtrl.orgSubscriptnList'>
                            <option value="">Choose Subscription</option>
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.subscription.has">@{{ errors.subscription.message }}</p>
                </div>

                <!-- subscriber status group -->
                <div class="form-group" data-ng-class="{'has-error': errors.status_group.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_status_grp') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="checkbox" name="status_group" id="active" value="Active"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.status_group" />
                        <label for="active">Active</label><br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.status_group.has">@{{ errors.status_group.message }}</p>
                </div>

                <!-- Platforms -->
                <div class="form-group" data-ng-class="{'has-error': errors.platform.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_pltfrm') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="checkbox" id="ios" value="IOS"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.platform['ios']" />
                        <label for="ios">IOS</label><br>

                        <input type="checkbox" id="android" value="Android Mobile"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.platform['android']" />
                        <label for="android">Android Mobile</label><br>

                        <input type="checkbox" id="web" value="WEB"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.platform['web']" />
                        <label for="web">WEB</label><br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.platform.has">@{{ errors.platform.message }}</p>
                </div>

                <!-- Reosurce Type -->
                <div class="form-group" data-ng-class="{'has-error': errors.resource_type.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_type') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="radio" name="resource_type" id="content-link" value="Content Link"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.resource_type" />
                        <label for="content-link">Content Link</label><br>

                        <input type="radio" name="resource_type" id="without-link" value="Without Link"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.resource_type" />
                        <label for="without-link">Without Link</label><br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.resource_type.has">@{{ errors.resource_type.message }}</p>
                </div>

                <!-- Publish -->
                <div class="form-group" data-ng-class="{'has-error': errors.publish.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_publish') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="radio" name="publish" id="now" value="Now"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.publish" />
                        <label for="now">Now</label><br>

                        <input type="radio" name="publish" id="scheduled" value="Scheduled Sending"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            data-ng-model="ancPushNotifctnCtrl.pushNotificationData.publish" />
                        <label for="scheduled">Scheduled Sending</label><br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.publish.has">@{{ errors.publish.message }}</p>
                </div>

                <!-- Status -->
                <div class="form-group" data-ng-class="{'has-error': errors.status.has}">
                    <label>
                        {{ trans('organizations::index.notifictn_status') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <select class="select2_custom_ddl form-control" allowClear="1" myPlaceholder="Select Status"
                            data-jquery="select2_custom_ddl" name="status"
                            data-ng-disabled="ancPushNotifctnCtrl.pushNotificationData.id"
                            ng-model="ancPushNotifctnCtrl.pushNotificationData.status">
                            <option value="" selected disabled>Choose Status</option>
                            <option value="0">Send Out</option>
                            <option value="1">Pending</option>
                            <option value="2">Deleted</option>
                            <option value="3">Failed</option>
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.status.has">@{{ errors.status.message }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-if="!ancPushNotifctnCtrl.pushNotificationData.id"
                    data-ng-click="ancPushNotifctnCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.save') }}" name="submit"
                    data-ng-if="!ancPushNotifctnCtrl.pushNotificationData.id" class="publish-now" />
            </div>
        </form>
    </div>
</div>
