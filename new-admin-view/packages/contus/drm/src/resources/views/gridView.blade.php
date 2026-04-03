<!-- drm table code -->
<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <!-- <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'drm_profiles'])
                    <th class="center">Drm Name</th>
                    <th class="center">Drm Provider</th>
                    <th class="center">Drm Profiles</th>
                    <th class="center">Status</th>
                    <th class="center">Action</th>
                </tr> -->
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'drm_profiles'])
                    <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.drm_name"
                            placeholder="Enter Drm name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Drm name">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.drm_provider"
                            placeholder="Enter Provider name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter Provider name">
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <select class="select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl"
                            data-boot-tooltip="true" data-ng-model="searchRecords.publish_now" data-ng-change="search()"
                            data-toggle="tooltip" data-original-title="{{ trans('base::general.select_status') }}">
                            <option value="all">{{ trans('base::general.all') }}</option>
                            <option value='1'>{{ trans('customer::subscription.active') }}</option>
                            <option value='0'>{{ trans('customer::subscription.inactive') }}</option>
                        </select>
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

                    <td class="">
                        <a data-toggle="collapse" data-parent="#drmAccordion" href="#drm_@{{ record.id }}"
                            aria-expanded="false">
                            @{{ record.drm_name }}
                        </a>
                    </td>

                    <td class="">@{{ record.drm_provider }}</td>
                    <td class="">@{{ record.publish_now }}</td>

                    <td class="">
                        <!-- Show if active -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('drm_profiles.edit')">
                            <span class="status-active" ng-if="record.publish_now == 1" style="cursor: pointer;"
                                data-toggle="modal" data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.active') }}
                            </span>
                            <span
                                class="tooltip_title">{{ trans('customer::subscription.deactivate_subscription') }}</span>
                        </div>

                        <!-- Show if inactive -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('drm_profiles.edit')">
                            <span class="status-inactive" ng-if="record.publish_now != 1" style="cursor: pointer;"
                                data-toggle="modal" data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.inactive') }}
                            </span>
                            <span
                                class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}</span>
                        </div>

                        <!-- For users without write access -->
                        

                        <!-- data-target="#single-record-status-update-popup" -->

                        
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            <div data-ng-if="checkAccess('drm_profiles')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.publish_now == 1"
                                        ng-click="togglePublishNow(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <!-- edit button (class="table_action sidepanel-open")-->
                            <div data-ng-if="checkAccess('drm_profiles.edit')" class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="drmCtrl.editdrm(record.id)">
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
                            <div class="tooltip-parent" data-ng-if="checkAccess('drm_profiles.delete')">
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

                <tr data-ng-attr-id="drm_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="8">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="center">Drm Name</th>
                                <th class="center">Drm Type</th>
                                <th class="center">Drm Profiles</th>
                                <th class="center">Status</th>
                                <th class="center">Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">@{{ record.drm_name }}</td>
                                    <td class="center">@{{ record.drmprofile.drm_type }}</td>
                                    <td class="center">@{{ record.drmprofile.is_active }}</td>
                                    <td class="center">
                                        <div class="tooltip-parent" data-ng-if="checkAccess('drm_profiles.edit')">
                                            <span class="status-active" ng-if="record.drmprofile.is_active == 1"
                                                style="cursor: pointer;" data-toggle="modal"
                                                data-target="#single-record-status-update-popup"
                                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                                {{ trans('customer::subscription.message.active') }}
                                            </span>
                                            <span
                                                class="tooltip_title">{{ trans('customer::subscription.deactivate_subscription') }}</span>
                                        </div>

                                        <!-- Show if inactive -->
                                        <div class="tooltip-parent" data-ng-if="checkAccess('drm_profiles.edit')">
                                            <span class="status-inactive" ng-if="record.drmprofile.is_active != 1"
                                                style="cursor: pointer;" data-toggle="modal"
                                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                                {{ trans('customer::subscription.message.inactive') }}
                                            </span>
                                            <span
                                                class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}</span>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <div data-ng-if="checkAccess('drm_profiles.edit')" class="form-group row"
                                            style="margin-bottom: 0px; margin-right: 5px;">
                                            <label class="switch">
                                                <input type="checkbox" ng-checked="record.drmprofile.is_active == 1"
                                                    ng-click="toggleProfilePublishNow(record.drmprofile)">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </td>
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

<!-- create drm code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="organizationForm" id="organizationForm" method="POST" data-base-validator
            data-ng-submit="drmCtrl.save($event, drmCtrl.drm.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!drmCtrl.drm.id">{{ trans('drm::index.add_drm') }}</h5>
                <!-- <h5 data-ng-if="drmCtrl.drm.id">{{ trans('drm::index.edit_drm') }}</h5> -->
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- drm_name (shown always) -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('drm::index.drm_name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="drm_name" data-unique="@{{ drmCtrl.uniqueRoute }}"
                            data-ng-model="drmCtrl.drm.drm_name" class="form-control"
                            placeholder="{{ trans('drm::index.drm_placeholder') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.drm_name.has">@{{ errors.drm_name.message }}</p>
                </div>

                <!-- Additional fields shown only when editing -->
                <!-- <div data-ng-if="drmCtrl.drm.id"> -->

                <!-- drm_provider -->
                <!-- <div class="form-group" data-ng-class="{'has-error': errors.profile.has}">
                        <label>{{ trans('drm::index.add_provider') }}</label>
                        <div class="form-input">
                            <input type="text"
                                name="drm_profile"
                                data-ng-model="drmCtrl.drm.drm_profile"
                                class="form-control"
                                placeholder="{{ trans('drm::index.add_provider_plc') }}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.profile?.has">@{{ errors.profile.message }}</p>
                    </div> -->

                <!-- px_value -->
                <!-- <div class="form-group" data-ng-class="{'has-error': errors.profile?.has}">
                        <label>{{ trans('drm::index.add_acc_id') }}</label>
                        <div class="form-input">
                            <input type="text"
                                name="drm_profile"
                                data-ng-model="drmCtrl.drm.drm_profile"
                                class="form-control"
                                placeholder="{{ trans('drm::index.add_acc_id_plc') }}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.profile?.has">@{{ errors.profile.message }}</p>
                    </div> -->

                <!-- account_id -->
                <!-- <div class="form-group" data-ng-class="{'has-error': errors.profile?.has}">
                        <label>{{ trans('drm::index.add_site_key') }}</label>
                        <div class="form-input">
                            <input type="text"
                                name="drm_profile"
                                data-ng-model="drmCtrl.drm.drm_profile"
                                class="form-control"
                                placeholder="{{ trans('drm::index.add_site_key_plc') }}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.profile?.has">@{{ errors.profile.message }}</p>
                    </div> -->

                <!-- access_key -->
                <!-- <div class="form-group" data-ng-class="{'has-error': errors.profile?.has}">
                        <label>{{ trans('drm::index.add_acs_key') }}</label>
                        <div class="form-input">
                            <input type="text"
                                name="drm_profile"
                                data-ng-model="drmCtrl.drm.add_acs_key"
                                class="form-control"
                                placeholder="{{ trans('drm::index.add_acs_key_plc') }}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.profile?.has">@{{ errors.profile.message }}</p>
                    </div> -->

                <!-- publish_now -->
                <!-- <div class="form-group">
                        <div class="switch-concept flexbox align-items-center">
                            <div class="swich-content flexbox align-items-center flex-wrap">
                                <span>{{ trans('drm::index.drm_publish') }}</span>
                                <div class="right-side flexbox align-items-center">
                                    <span class="text">({{ trans('payment::coupons.message.inactive') }})</span>
                                    <label class="switch">
                                        <input type="checkbox" data-ng-model="couponCtrl.coupon.is_active" name="is_active">
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="text">({{ trans('payment::coupons.message.active') }})</span>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg"></p>
                    </div> -->

                <!-- <div class="bottom-button text-right flexbox align-items-center">
                        <input type="button"
                            value="Cancel"
                            data-ng-click="drmCtrl.closeSubscriptionEdit()"
                            name="cancel"
                            class="save">
                        <input type="submit"
                            value="Update"
                            name="submit"
                            class="publish-now">
                        <input type="submit"
                            value="Remove"
                            name="submit"
                            class="publish-now">
                    </div> -->

                <!-- </div> -->
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="drmCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>