<!-- announcement table code -->
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.subject"
                            placeholder="search subject..." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Subject">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.created_by"
                            placeholder="search email.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter User Email">
                    </td>
                    <td>
                        <select class="select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl"
                            myPlaceholder="Choose Category" data-boot-tooltip="true"
                            data-ng-model="searchRecords.reminder_to" data-ng-change="search()" data-toggle="tooltip"
                            data-original-title="Select Category">
                            <option value="" disabled selected>Choose Category</option>
                            <option value="all">All Subscribers</option>
                            <option value="autopay">AutoPay</option>
                            <option value='non-autopay'>Non Autopay</option>
                        </select>
                    </td>
                    <td></td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                        {{ trans('base::general.not_found') }}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat-start="record in ReminderRecords track by $index"
                    class="list-repeat" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{ record.id }}"
                                ng-click="selectRecord($event, record.id)" value="@{{ record.id }}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <td class="">@{{ record.formatted_created_at }}</td>
                    <td class="">@{{ record.subject }}</td>
                    <td class="">@{{ record.user[0].email }}</td>
                    <td class="">@{{ record.reminder_to }}</td>
                    <td class="">@{{ record.day_before }}</td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" name="status" ng-checked="record.status == 1"
                                        ng-click="ancRemndrCtrl.toggleStatus(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <!-- edit button -->
                            <div class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="ancRemndrCtrl.editAncReminder(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{ trans('base::general.edit') }}</span>
                            </div>

                            <!-- delete button -->
                            <div class="tooltip-parent">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                    data-target="#deleteModal" ng-click="ancRemndrCtrl.deleteAncReminder(record.id)"
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

                <tr data-ng-repeat-end>

                </tr>

            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- create announcement reminder code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div id="home" class="pop_over_continer form-page">
        <form name="announcementForm" id="announcementForm" method="POST" data-base-validator
            ng-submit="ancRemndrCtrl.saveAncReminder($event)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}

            {{-- <input type="text" hidden id="org-id" name="id" value="{{ Request::url() }}"> --}}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!ancRemndrCtrl.ancReminderData.id">{{ trans('organizations::index.create_remndr') }}
                </h5>
                <h5 data-ng-if="ancRemndrCtrl.ancReminderData.id">{{ trans('organizations::index.edit_remndr') }}</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- Announcement Reminder Subject -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        Subject
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="subject" data-ng-model="ancRemndrCtrl.ancReminderData.subject"
                            class="form-control"
                            placeholder="{{ trans('organizations::index.remndr_subject_hldr') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.subject.has">@{{ errors.subject.message }}</p>
                </div>

                <!-- Announcement Reminder message -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.remndr_message') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <textarea id="message" name="message" rows="4" cols="50"
                            data-ng-model="ancRemndrCtrl.ancReminderData.message" class="form-control"
                            placeholder="{{ trans('organizations::index.remndr_message_hldr') }}"></textarea>
                    </div>
                    <p class="error-msg" data-ng-show="errors.message.has">@{{ errors.message.message }}</p>
                </div>

                <!-- Announcement Reminder day Before -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.remndr_day_before') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="day_before"
                            data-ng-model="ancRemndrCtrl.ancReminderData.day_before" class="form-control" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.day_before.has">@{{ errors.day_before.message }}</p>
                </div>

                <!-- Announcement Reminder reminder to -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.remndr_reminder_to') }}
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                        <input type="radio" name="reminder_to" id="all" value="All Subscribers"
                            data-ng-model="ancRemndrCtrl.ancReminderData.reminder_to" />
                        <label for="all">All Subscribers</label><br>

                        <input type="radio" name="reminder_to" id="autopay" value="Autopay Subscribers"
                            data-ng-model="ancRemndrCtrl.ancReminderData.reminder_to" />
                        <label for="autopay">Autopay Subscribers</label><br>

                        <input type="radio" name="reminder_to" id="non_autopay" value="Non-Autopay Subscribers"
                            data-ng-model="ancRemndrCtrl.ancReminderData.reminder_to" />
                        <label for="non_autopay">Non-Autopay Subscribers</label><br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.reminder_to.has">@{{ errors.reminder_to.message }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <button value="{{ trans('base::general.cancel') }}"
                    data-ng-click="ancRemndrCtrl.closeSubscriptionEdit()" name="cancel" id="addReminderBtn" class="save">Back </button>
                <input type="submit" value="{{ trans('base::general.save') }}" name="submit"
                    class="publish-now" />
            </div>
        </form>
    </div>
</div>
