<!-- api access table code -->
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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'system_users'])
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.name"
                            placeholder="Enter The Full Name.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter name">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.rule"
                            placeholder="Enter The Rule.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter rule">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.email"
                            placeholder="Enter The Email Id.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter email">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.company"
                            placeholder="Enter The Company.." data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter company">
                    </td>
                    <td></td>
                    <td>
                        <select class="select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl"
                            data-boot-tooltip="true" data-ng-model="searchRecords.status" data-ng-change="search()"
                            data-toggle="tooltip" data-original-title="Select Status">
                            <option value="">All</option>
                            <option value="all" selected>All</option>
                            <option value='online'>Active</option>
                            <option value='offline'>Inactive</option>
                        </select>
                    <td></td>
                    <td></td>
                    </td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                        {{ trans('base::general.not_found') }}
                    </td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat-start="record in records track by $index"
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

                    {{-- <td class="">@{{ record }}</td> --}}
                    <td class="">@{{ record.first_name + ' ' + record.last_name }}</td>
                    <td class="">@{{ record.rules.rule_name }}</td>
                    <td class="">@{{ record.email }}</td>
                    <td class="">@{{ record.company }}</td>
                    <td class="">@{{ record.is_log_in_at ? record.is_log_in_at + ' / ' + record.ip_address :
                        record.formatted_updated_at + ' / ' + record.ip_address }}</td>
                    <td class="">@{{ record.status == '1' ? 'Inactive' : 'Active' }}</td>
                    <!-- <td class="">@{{ record.status == '1' ? 'Enable' : 'Disable' }}</td> -->
                    {{-- <td class="">@{{ record.user_logs }}</td> --}}
                    <!-- <td class="" style="display: flex; align-items: center; gap: 5%; cursor: pointer;">
                        <label for="download" style="cursor: pointer;">Download</label>
                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 512 512"
                            xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;">
                            <title>download</title>
                            <path
                                d="M232 64L280 64 280 214 277 270 300 242 356 189 388 221 256 353 124 221 156 189 212 242 235 270 232 214 232 64ZM64 400L448 400 448 448 64 448 64 400Z" />
                        </svg>
                    </td> -->

                    <td style="display:flex; align-items:center; gap:5%; cursor:pointer;"
                        ng-click="sysUserCtrl.downloadUserLog(record.id)">

                        <label style="cursor:pointer;">Download</label>

                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 512 512"
                            xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
                            <title>download</title>
                            <path
                                d="M232 64L280 64 280 214 277 270 300 242 356 189 388 221 256 353 124 221 156 189 212 242 235 270 232 214 232 64ZM64 400L448 400 448 448 64 448 64 400Z" />
                        </svg>
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            {{-- <div data-ng-if="checkAccess('system_users')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" name="status" ng-checked="record.status == 1"
                                        ng-click="sysUserCtrl.toggleStatus(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div> --}}

                            <!-- edit button -->
                            <div data-ng-if="checkAccess('system_users.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button class="sidepanel-open" data-ng-click="sysUserCtrl.editSysUser(record)">
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
                            <div class="tooltip-parent" data-ng-if="checkAccess('system_users.delete')">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal" data-target="#deleteModal"
                                    ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon"
                                    data-boot-tooltip="true" data-original-title="">
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
                    {{-- <td colspan="8">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="center">Name</th>
                                <th class="center">Login</th>
                                <th class="center">Organization</th>
                                <th class="center">Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">@{{ record.name }}</td>
                                    <td class="center">@{{ record.login }}</td>
                                    <td class="center">@{{ record.organization.organization_name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td> --}}
                </tr>

            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- create system-users code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="systemuserForm" id="systemuserForm" method="POST" data-base-validator enctype="multipart/form-data">

            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!sysUserCtrl.systemUserData.id">Create System User</h5>
                <h5 data-ng-if="sysUserCtrl.systemUserData.id">Edit System User</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- first name -->
                <div class="form-group" data-ng-class="{'has-error': errors.first_name.has}">
                    <label>
                        First Name
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="first_name" data-ng-model="sysUserCtrl.systemUserData.first_name"
                            class="form-control" placeholder="Enter First Name" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.first_name.has">@{{ errors.first_name.message }}</p>
                </div>

                <!-- last name -->
                <div class="form-group" data-ng-class="{'has-error': errors.last_name.has}">
                    <label>
                        Last Name
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="last_name" data-ng-model="sysUserCtrl.systemUserData.last_name"
                            class="form-control" placeholder="Enter Last Name" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.last_name.has">@{{ errors.last_name.message }}</p>
                </div>

                <!-- Password -->
                <div class="form-group" data-ng-class="{'has-error': errors.password.has}" ng-if="isEditMode == false">
                    <label>
                        Password
                        <span class="required">*</span>
                    </label>

                    <div class="form-input">
                        <div class="col-sm-12 position-relative flexbox" style="padding: 0px;">
                            <input ng-attr-type="@{{ sysUserCtrl.showPassword ? 'text' : 'password' }}" name="password"
                                data-ng-model="sysUserCtrl.systemUserData.password" class="form-control"
                                placeholder="Enter Password" />

                            <!-- Toggle visibility -->
                            <button type="button" class="btn btn-outline-secondary toggle-eye"
                                ng-click="sysUserCtrl.showPassword = !sysUserCtrl.showPassword">
                                <i class="fa" ng-class="sysUserCtrl.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>

                        <p class="error-msg" data-ng-show="errors.password.has">
                            @{{ errors.password.message }}
                        </p>
                    </div>
                </div>


                <!-- Confirm Password -->
                <div class="form-group" data-ng-if="isEditMode == false"
                    data-ng-class="{'has-error': sysUserCtrl.passwordMismatch || errors.confirm_password.has}">
                    <label>
                        Confirm Password
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="password" name="password" class="form-control" placeholder="Re-Enter Password" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.confirm_password.has">
                        @{{ errors.confirm_password.message }}
                    </p>
                </div>

                <!-- rule -->
                <div class="form-group" data-ng-class="{'has-error': errors.rule.has}">
                    <label>
                        Rule
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <select allowClear="1" data-jquery="select2_custom_ddl" name="permission_rule"
                            class="admin_category_sub form-control select2_custom_ddl"
                            myValue="sysUserCtrl.systemUserData.permission_rule" myPlaceholder="Select Rule"
                            data-ng-options="record.id as record.rule_name for record in sysUserCtrl.rulesList"
                            data-ng-model="sysUserCtrl.systemUserData.permission_rule">
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.rule.has">@{{ errors.rule.message }}</p>
                </div>

                <!-- email -->
                <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                    <label>
                        Email
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="email" data-ng-model="sysUserCtrl.systemUserData.email"
                            class="form-control" placeholder="Enter Email" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
                </div>

                <!-- phone_number -->
                <div class="form-group" data-ng-class="{'has-error': errors.phone_number.has}">
                    <label>
                        Phone Number
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="phone_number" data-ng-model="sysUserCtrl.systemUserData.phone_number"
                            class="form-control" placeholder="Enter your Phone Number" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.phone_number.has">@{{ errors.phone_number.message }}</p>
                </div>

                <!-- company -->
                <div class="form-group" data-ng-class="{'has-error': errors.company.has}">
                    <label>
                        Company
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="company" data-ng-model="sysUserCtrl.systemUserData.company"
                            class="form-control" placeholder="Enter Company Name" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.company.has">@{{ errors.company.message }}</p>
                </div>

                <!-- location -->
                <div class="form-group" data-ng-class="{'has-error': errors.location.has}">
                    <label>
                        Location
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="location" data-ng-model="sysUserCtrl.systemUserData.location"
                            class="form-control" placeholder="Enter your location" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.location.has">@{{ errors.location.message }}</p>
                </div>

                <!-- max_failed_logins -->
                <div class="form-group" data-ng-class="{'has-error': errors.max_failed_logins.has}">
                    <label>
                        Max Failed Logins
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="max_failed_logins" placeholder="Max Failed Logins.."
                            data-ng-model="sysUserCtrl.systemUserData.max_failed_logins" class="form-control"
                            placeholder="" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.max_failed_logins.has">@{{
                        errors.max_failed_logins.message }}</p>
                </div>

                <!-- Enabled -->
                <div class="form-group row" style="margin-bottom: 15px; display: flex; align-items: center; gap: 5%;">
                    <label>
                        Enabled
                        <span class="required">*</span>
                    </label>

                    <label class="switch" style="margin: 10px 0px 10px 16px;">
                        <input type="checkbox" ng-model="sysUserCtrl.systemUserData.status" name="status"
                            ng-checked="sysUserCtrl.systemUserData.status == 'enable'"
                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- super Admin -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <div class="form-input">
                        <input type="checkbox" ng-model="sysUserCtrl.systemUserData.is_super_admin"
                            ng-checked="sysUserCtrl.systemUserData.is_super_admin == 'true'" name="is_super_admin">
                        <label for="super-admin">Super Admin</label>
                    </div>
                </div>

                <!-- Change password for next login -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <div class="form-input">
                        <input type="checkbox" ng-model="sysUserCtrl.systemUserData.change_password"
                            ng-checked="sysUserCtrl.systemUserData.change_password == 'yes'" name="change_password">
                        <label for="change-password">Change Password For Next Login</label>
                    </div>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="sysUserCtrl.cancelSysUser(event)" name="cancel" class="save" />

                <input type="button" value="Update" data-ng-if="isEditMode == true" name="update" class="publish-now"
                    data-ng-click="sysUserCtrl.updateSystemUser(sysUserCtrl.systemUserData)" />

                <input type="button" value="Save" data-ng-if="isEditMode == false" name="save" class="publish-now"
                    data-ng-click="sysUserCtrl.saveSystemUser($event)" />
            </div>
        </form>
    </div>
</div>