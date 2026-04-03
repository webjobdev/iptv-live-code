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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'settings'])
                    <!-- <th class="text-center">#</th> -->
                    <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td ></td>
                    <td class="">
                        <select class="select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl" data-boot-tooltip="true" data-ng-model="searchRecords.is_active"
                            data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.is_active')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value='1'>{{trans('customer::subscription.active')}}</option>
                            <option value='0'>{{trans('customer::subscription.inactive')}}</option>
                        </select>
                    </td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat"
                    data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td class="">@{{record.product_type}}</td>
                    <td class="">@{{ record.days || '-' }}</td>
                    <td class="">@{{ record.accessories_name || '-' }}</td>
                    <td class="">@{{record.month_type || '-'}}</td>
                    <td class="">@{{record.device_type || '-'}}</td>
                    <td class="">@{{record.price}}</td>
                    
                    <td class="">
                        <!-- Show if active -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('settings')">
                            <span class="status-active"
                                ng-if="record.is_active == 1"
                                style="cursor: pointer;"
                                data-toggle="modal"
                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.active') }}
                            </span>
                            <span class="tooltip_title">{{ trans('customer::subscription.deactivate_subscription') }}</span>
                        </div>

                        <!-- Show if inactive -->
                        <div class="tooltip-parent" data-ng-if="checkAccess('settings')">
                            <span class="status-inactive"
                                ng-if="record.is_active != 1"
                                style="cursor: pointer;"
                                data-toggle="modal"
                                data-ng-click="confirmationPopupSingleRecordAction(record)">
                                {{ trans('customer::subscription.message.inactive') }}
                            </span>
                            <span class="tooltip_title">{{ trans('customer::subscription.activate_subscription') }}</span>
                        </div>

                        <!-- For users without write access -->
                        

                        <!-- data-target="#single-record-status-update-popup" -->

                        
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <!-- <div data-ng-if="checkAccess('settings')" class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox"
                                        ng-checked="record.is_active == 1"
                                        ng-click="togglePublishNow(record, record.id)">
                                    <span class="slider round"></span>
                                </label>
                            </div> -->

                            <!-- edit button (class="table_action sidepanel-open")-->
                            <div data-ng-if="checkAccess('settings')" class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="settCtrl.editsettingdata(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>

                            <!-- delete button -->
                            <div class="tooltip-parent" data-ng-if="checkAccess('settings')">
                                <span ng-mouseover="getTooltip($event)"
                                    data-toggle="modal"
                                    data-target="#deleteModal"
                                    ng-click="deleteSingleRecord(record.id)"
                                    class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g data-original-title="" title="">
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                    <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                                </span>
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

<!-- form code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="settingForm"
            id="settingForm"
            method="POST"
            data-base-validator
            data-ng-submit="settCtrl.save($event, settCtrl.setting.id)"
            enctype="multipart/form-data">

            {!! csrf_field() !!}

            <input type="hidden" id="subscriber-id" name="id">

            <script>
                document.getElementById('subscriber-id').value = window.location.pathname.split('/').pop();
            </script>


            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!settCtrl.setting.id">
                    <!-- {{ trans('setting::index.add_setting') }} -->
                    Create Setting Data
                </h5>
                <h5 data-ng-if="settCtrl.setting.id">
                    <!-- {{trans('customer::subscription.edit_new_subscription')}} -->
                    Edit Setting Data
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group">
                    <label>
                        <!-- {{trans('setting::adminsetting.product_type')}} -->
                        Product Type <span class="required">*</span></label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="product_type" data-ng-change="settCtrl.updateCardPattern()"
                            myPlaceholder="Select Product Type" data-ng-model="settCtrl.setting.product_type" myValue="settCtrl.setting.product_type">
                            <option disabled value="">Choose Product Type</option>
                            <option value="custom subscription">Custom Subscription</option>
                            <option value="subscription sets">Subscription Sets</option>
                            <option value="free subscription">Free Subscription</option>
                            <option value="add device/slots">Add Device/Slots</option>
                            <option value="accessories">Accessories</option>
                            <option value="custom charge">Custom Charge</option>
                            <option value="bundles">Bundles</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" ng-if="settCtrl.setting.product_type == 'custom subscription'">
                    <label>Enter Days <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text"
                            name="day"
                            data-unique="@{{settCtrl.uniqueRoute}}"
                            data-ng-model="settCtrl.setting.days"
                            class="form-control"
                            placeholder="Enter Dyas" />
                    </div>
                </div>

                <div class="form-group" ng-if="settCtrl.setting.product_type == 'subscription sets'">
                    <label>
                        <!-- {{trans('setting::adminsetting.month_type')}} -->
                        Month Type <span class="required">*</span></label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="month_type" data-ng-change="settCtrl.updateCardPattern()"
                            myPlaceholder="Select Month Type" data-ng-model="settCtrl.setting.month_type" myValue="settCtrl.setting.month_type">
                            <option disabled value="">Choose Product Type</option>
                            <option value="0.5 month">0.5 Month</option>
                            <option value="1 month">1 Month</option>
                            <option value="1.5 month">1.5 Months</option>
                            <option value="2 month">2 Months</option>
                            <option value="3 month">3 Months</option>
                            <option value="6 month">6 Months</option>
                            <option value="9 month">9 Months</option>
                            <option value="12 month">12 Months</option>
                            <option value="18 month">18 Months</option>
                            <option value="24 month">24 Months</option>

                        </select>
                    </div>
                </div>

                <div class="form-group" ng-if="settCtrl.setting.product_type == 'add device/slots'">
                    <label>
                        <!-- {{trans('setting::adminsetting.device_type')}} -->
                        Device Type <span class="required">*</span></label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="device_type" data-ng-change="settCtrl.updateCardPattern()"
                            myPlaceholder="Select Product Type" data-ng-model="settCtrl.setting.device_type" myValue="settCtrl.setting.device_type">
                            <option disabled value="">Choose Product Type</option>
                            <option value="phone (mobile)">Phone(Mobile)</option>
                            <option value="tablate">Tablate</option>
                            <option value="laptop">Laptop</option>
                            <option value="desktop">Desktop</option>
                            <option value="tv">Tv</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" ng-if="settCtrl.setting.product_type == 'accessories' || settCtrl.setting.product_type == 'bundles'">
                    <label>Enter Accessories Name <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text"
                            name="accessories_name"
                            data-unique="@{{settCtrl.uniqueRoute}}"
                            data-ng-model="settCtrl.setting.accessories_name"
                            class="form-control"
                            placeholder="Enter Accessories Name" />
                    </div>
                </div>

                <div class="form-group">
                    <label>Enter Price <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="number"
                            name="price"
                            data-unique="@{{settCtrl.uniqueRoute}}"
                            data-ng-model="settCtrl.setting.price"
                            class="form-control"
                            placeholder="Enter Dyas" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                            <g>
                                <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                    fill="#3d3d3d"></path>
                            </g>
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>{{ trans('video::videos.status') }}</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" data-ng-model="settCtrl.setting.is_active" name="is_active">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                    <p class="error-msg"></p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button"
                    value="{{ trans('base::general.cancel') }}"
                    data-ng-click="settCtrl.closesettingEdit()"
                    name="cancel"
                    class="save" />
                <input type="submit"
                    value="{{ trans('base::general.submit') }}"
                    name="submit"
                    class="publish-now" />
            </div>
        </form>
    </div>
</div>