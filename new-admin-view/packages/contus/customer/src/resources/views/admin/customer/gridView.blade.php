<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table" id="fixTable"  data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'customer_all_write'])
                    <th data-ng-repeat="field in heading">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"
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
                            data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_user_name')}}">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.email"
                            data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_email')}}">
                    </td>
                    <td class="search_product userphone">
                        <input type="text" maxlength="13" class="form-control" data-ng-model="searchRecords.phone"
                            data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('base::general.enter_phone')}}">
                    </td>
                    <td></td>
                    <td>
                        <select class="select2_custom_ddl" minimumresults="-1" data-jquery="select2_custom_ddl" data-boot-tooltip="true" data-ng-model="searchRecords.subscriber"
                            data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_plan')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value="NA">{{trans('user::adminuser.na')}}</option>
                            <option ng-repeat="subcription_plan in subcription_plans" value="@{{subcription_plan.id}}">@{{subcription_plan.name}}</option>
                        </select>
                    </td>
                    <td class="search_product planstart_date grid-date-filter">
                        <input type="text" name="filter_startdate" id="filter_startdate" class="form-control"
                            data-ng-model="searchRecords.filter_startdate" placeholder="DD-MM-YYYY"
                            data-ng-change="search()" data-original-title="{{trans('base::general.select_startdate')}}" />
                    </td>
                    <td class="grid-date-filter">
                        <input type="text" name="filter_enddate" id="filter_enddate" class="form-control"
                            data-ng-model="searchRecords.filter_enddate" placeholder="DD-MM-YYYY"
                            data-ng-change="search()" data-original-title="{{trans('base::general.select_enddate')}}" />
                    </td>
                    <td>
                        @include('audio::admin.common.gridStatusFilter')
                    </td>
                    <td>
                    </td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords"
                    class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td>@{{record.name}}</td>
                    <td>@{{record.email}}</td>
                    <td>@{{record.phone}}</td>
                    <td>@{{record.formatted_created_date}}</td>
                    <td>@{{record.active_subscriber[0].name || 'NA'}}</td>
                    <td>@{{record.active_subscriber[0].pivot.start_date || "NA"}}</td>
                    <td>@{{record.active_subscriber[0].pivot.end_date || "NA"}}</td>
                    <td>
                        <div class="tooltip-parent" data-ng-if="checkAccess('customer_all_write')">
                        <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"
                        data-toggle="modal"   data-target="#single-record-status-update-popup" data-ng-click="confirmationPopupSingleRecordAction(record)"  
                            data-boot-tooltip="true">{{trans('user::user.message.active')}}</span>
                            <span  class="tooltip_title">{{trans('user::user.deactivate_user')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="checkAccess('customer_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1 " style="cursor: pointer;"
                        data-toggle="modal" data-target="#single-record-status-update-popup"  data-ng-click="confirmationPopupSingleRecordAction(record)" 
                            data-boot-tooltip="true">{{trans('user::user.message.inactive')}}</span>
                            <span  class="tooltip_title">{{trans('user::user.activate_user')}}</span>
                        </div>
                       
                         
                         
                      
                    </td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div class="tooltips edit_table_icon tooltip-parent" data-boot-tooltip="true" 
                                    data-ng-if="checkAccess('customer_all_write')">
                                <button class="table_action sidepanel-open" data-ng-click="usrCtrl.editUser(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span  class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                            <div class="tooltip-parent"  data-ng-if="checkAccess('customer_all_write')">
                            <span ng-mouseover="getTooltip($event)"
                                data-toggle="modal" data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g data-original-title="" title="">
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                    <span  class="tooltip_title">{{trans('base::general.delete')}}</span>
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
<!-- To add or edit the user  -->
<div class="sidepanel" ng-show="!usrCtrl.user.subsciptionform">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="userForm" method="POST" data-base-validator data-ng-submit="usrCtrl.save($event, usrCtrl.user.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!usrCtrl.user.id">{{trans('customer::customer.customer')}} -
                    {{trans('customer::customer.add_new_customer')}}</h5>
                <h5 data-ng-if="usrCtrl.user.id">{{trans('customer::customer.customer')}} -
                    {{trans('customer::customer.edit_new_customer')}}</h5>
            </div>
            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{trans('customer::customer.name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" data-ng-model="usrCtrl.user.name" class="form-control"
                            placeholder="{{trans('customer::customer.customername_placeholder')}}" value="{{old('name')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                    <label>
                        {{trans('customer::customer.email')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input data-ng-if="usrCtrl.user.id" readonly type="text" name="email" data-ng-model="usrCtrl.user.email"
                            class="form-control" placeholder="{{trans('customer::customer.email_placeholder')}}" value="{{old('email')}}" />
                        <input data-ng-if="!usrCtrl.user.id"  type="text" name="email" data-ng-model="usrCtrl.user.email"
                            class="form-control" placeholder="{{trans('customer::customer.email_placeholder')}}" value="{{old('email')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
                    <label>
                        {{trans('customer::customer.phone')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" onkeydown="return ( event.ctrlKey || event.altKey 
                || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                || (95<event.keyCode && event.keyCode<106)
                || (event.keyCode==8) || (event.keyCode==9) 
                || (event.keyCode>34 && event.keyCode<40) 
                || (event.keyCode==46) )" name="phone" maxlength="15" class="form-control" data-validation-name="Phone Number" data-ng-model="usrCtrl.user.phone"
                            placeholder="{{trans('customer::customer.phone_placeholder')}}" value="{{old('phone')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.age.has}">
                    <label>
                        {{trans('customer::customer.dob')}}
                    </label>
                    <div class="form-input">
                        <input type="text" name="age" id="age" data-ng-model="usrCtrl.user.age" size="30" placeholder="DD-MM-YYYY" autocomplete="off"
                            data-validation-name="DOB" value="{{old('age')}}" class="form-control" ng-blur="dateBlur($event,usrCtrl.user.age)"
                            ng-keyup="dateKeyup($event,usrCtrl.user.age)" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.age.has">@{{ errors.age.message }}</p>
                </div>

                <div class="form-group cs-newstudent" data-ng-class="{'has-error': errors.exam.has}" style="display:none">
                    <label>{{trans('video::videos.exam')}} <span class="required">*</span></label>
                    <label for="@{{exam.id}}" ng-repeat="exam in exams">
                        <input type="checkbox" value="@{{exam.id}}" id="@{{exam.id}}" ng-click="selectexam(exam.id)"
                            ng-checked="examSelection.indexOf(exam.id) > -1">
                        @{{exam.title}}
                    </label>
                    <div class="form-input">
                        <input type="text" style="display: none;" name="exam" data-ng-model="usrCtrl.user.exam" value="@{{examSelection}}">
                    </div>
                    <p class="error-msg" data-ng-show="errors.exam.has">{{trans('video::videos.genre_required')}}</p>
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
                                    <input type="checkbox" name="is_active" data-ng-model="usrCtrl.user.is_active">
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
              
                <input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="usrCtrl.closeUserEdit()" name="cancel" class="save" />
                <input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
                <!-- <div id="loaderimg" style="display:none;">loading....</div> -->
            </div>
        </form>
    </div>
</div>
<div class="sidepanel" ng-show="usrCtrl.user.subsciptionform">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="subscriptionForm" method="POST" data-base-validator data-ng-submit="usrCtrl.saveSubcription($event, usrCtrl.user.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5>{{trans('customer::customer.customer')}} - {{trans('customer::customer.add_new_subscription')}}</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.orderid.has}">
                    <label>
                        {{trans('customer::customer.orderid')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="orderid" data-ng-model="usrCtrl.user.orderid" data-validation-name="Transaction Id"
                            class="form-control" placeholder="{{trans('customer::customer.orderid_placeholder')}}"
                            value="{{old('orderid')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.orderid.has">@{{ errors.orderid.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.subscription_plan.has}">
                    <label>
                        {{trans('customer::customer.plan')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <select class="form-control mb10" name="subscription_plan" data-ng-model="usrCtrl.user.subscription_plan"
                            data-validation-name="Subscription Plan">
                            <option value="">Please Select Plan</option>
                            <option ng-repeat="subcription_plan in subcription_plans" value="@{{subcription_plan.id}}">@{{subcription_plan.name}}</option>
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.subscription_plan.has">@{{
                        errors.subscription_plan.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.start_date.has}">
                    <label>
                        {{trans('customer::customer.start_date')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="start_date" id="start_date" data-ng-model="usrCtrl.user.start_date"
                            size="30" placeholder="DD-MM-YYYY" data-validation-name="Start Date" value="{{old('start_date')}}"
                            class="form-control" ng-blur="dateBlur($event,usrCtrl.user.start_date)" ng-keyup="dateKeyup($event,usrCtrl.user.start_date)" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.start_date.has">@{{ errors.start_date.message }}</p>
                </div>
            </div>
            <input type="hidden" name="userid" data-ng-model="usrCtrl.user.id" />

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="usrCtrl.closeUserEdit()" name="cancel" class="save" />
				<input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>
<div id="loaderimg" style=" position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url('{{$getBaseAssetsUrl('images/admin/pl.gif')}}') 
              50% 50% no-repeat rgb(0, 0, 0, 0.7); 
              display:none; "></div>


