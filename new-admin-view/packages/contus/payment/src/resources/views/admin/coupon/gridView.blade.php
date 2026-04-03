<div id="latest_video">
	<div id="table_loader" class="table_loader_container"
		data-ng-show="gridLoadingBar">
		<div class="table_loader">
			<div class="loader"></div>
		</div>
	</div>
	<div class="table_responsive">
		<table class="table padding-table" id="fixTable"
			data-ng-class="{'no-records': noRecords}">
			<thead>
				<tr>
					<!-- @include('audio::admin.common.bulkActionLayout') -->
					@include('audio::admin.common.bulkActionLayout', ['access_type' => 'coupons_all_write'])
					<th data-ng-repeat="field in heading" data-ng-if="field.name=='offer'" class="center">@{{field.name}}
						<span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"	data-ng-click="fieldOrder($event,field.value)"></span>
						<span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
					</th>
					<th data-ng-repeat="field in heading" data-ng-if="field.name!='offer'" class="center">@{{field.name}}
						<span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"	data-ng-click="fieldOrder($event,field.value)"></span>
						<span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="search_text">
					<td></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.name" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="Enter Coupon name"></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.code" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::coupons.enter_code')}}"></td>
						<td></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.offer" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::coupons.enter_offer')}}"></td>
					<td class="search_coupon coupon-valid-date grid-date-filter">
							<input type="text" name="coupon-valid-date" autocomplete="off" id="coupon-valid-date" class="form-control"
								data-ng-model="searchRecords.valid_till" placeholder="DD-YYYY-MM"
								data-ng-change="search()" data-original-title="{{trans('payment::coupons.select_expire_date')}}" />
					</td>
					<td>
                        <select class="select2_custom_ddl" minimumresults="-1" data-boot-tooltip="true" data-ng-model="searchRecords.is_active"
                            data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('payment::coupons.select_status')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value="1">{{trans('payment::coupons.active')}}</option>
                            <option value="0">{{trans('payment::coupons.in_active')}}</option>
						</select>
					</td>
					<td></td>
				</tr>

				<tr data-ng-if="noRecords">
					<td colspan="@{{heading.length + 1}}"
						class="no-data center">{{trans('base::general.not_found')}}</td>
				</tr>
				<tr data-ng-if="showRecords"
					data-ng-repeat="record in records track by $index"
					data-ng-show="showRecords" class="list-repeat"
					data-intialize-sidebar="">
					<td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
					</td>
					<td>@{{record.name}}</td>			
					<td>@{{record.code}}</td>
					<td class="center">@{{record.offer_type}}</td>
					<td class="center">@{{record.offer_type =='flat'?'$':''}}@{{record.offer}}@{{record.offer_type =='percentage'?'%':''}}</td>
					<td class="center">@{{couponCtrl.changeDate(record.valid_till)}}</td>
					{{-- <td>@{{record.is_active? "Active": "Inactive"}}</td> --}}
					<td>
						<div class="tooltip-parent" data-ng-if="checkAccess('coupons_all_write')">
							<span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"
							data-toggle="modal"   data-target="#single-record-status-update-popup" data-ng-click="confirmationPopupSingleRecordAction(record)"  
								data-boot-tooltip="true">{{trans('user::user.message.active')}}</span>
								<span  class="tooltip_title">{{trans('user::user.deactivate_user')}}</span>
							</div>
							<div class="tooltip-parent" data-ng-if="checkAccess('coupons_all_write')">
								<span class="status-inactive" ng-if="record.is_active != 1 " style="cursor: pointer;"
							data-toggle="modal" data-target="#single-record-status-update-popup"  data-ng-click="confirmationPopupSingleRecordAction(record)" 
								data-boot-tooltip="true">{{trans('user::user.message.inactive')}}</span>
								<span  class="tooltip_title">{{trans('user::user.activate_user')}}</span>
							</div>
						   
							 
							 
					</td>
					<td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div class="tooltips edit_table_icon tooltip-parent" data-boot-tooltip="true" 
                                    data-ng-if="checkAccess('coupons_all_write')">
                                <button class="table_action sidepanel-open" data-ng-click="couponCtrl.editUser(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span  class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>

                            <div class="tooltip-parent"  data-ng-if="checkAccess('coupons_all_write')">
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


<!-- To add or edit the lastest news  -->
<div class="sidepanel">
	<div class="overlay"></div>
	<div class="pop_over_continer form-page">
		<form name="couponForm" id="couponForm" method="POST" data-base-validator data-ng-submit="couponCtrl.save($event,couponCtrl.coupon.id)"
		 enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="sidepanel-header flexbox align-items-center">
				<h5 data-ng-if="!couponCtrl.coupon.id">{{trans('payment::coupons.add_new_coupon')}}</h5>
				<h5 data-ng-if="couponCtrl.coupon.id">{{trans('payment::coupons.edit_coupon')}}</h5>
			</div>

			<div class="sidepanel-scroll">
				@include('base::partials.errors')

				<div class="form-group" data-ng-class="{'has-error': errors.name.has}">
					<label>
						<!-- {{trans('payment::coupons.code')}} -->
						Coupon name
						<span class="required">*</span>
					</label>
					<div class="form-input coupon-div">
						<input type="text" name="name" data-ng-model="couponCtrl.coupon.name"
						 class="form-control coupon-input" placeholder="Enter coupon name" value="{{old('name')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.name.has">
						Coupon name required</p>
				</div>

				<div class="form-group" data-ng-class="{'has-error': errors.code.has}">
					<label>
						<!-- {{trans('payment::coupons.code')}} -->
						Coupon code
						<span class="required">*</span>
					</label>
					<div class="form-input coupon-div">
						<input  type="text" name="code" data-ng-model="couponCtrl.coupon.code"
						 class="form-control coupon-input code-val" placeholder="{{trans('payment::coupons.code')}}" value="{{old('code')}}" />
						 <button data-ng-click="couponCtrl.autoGenCoupon($event)" class="auto-gen-coupon btn btn-success">{{trans('payment::coupons.generate')}}</button>
					</div>
					<p class="error-msg" data-ng-show="errors.code.has">
					@{{ errors.code.message }}
					</p>
				</div>

				<div class="form-group" data-ng-class="{'has-error': errors.offer.has}">
					<label>
						<!-- {{trans('payment::coupons.offer')}} -->
						Coupon Type
						<span class="required">*</span>
					</label>
					<div class="form-input offer-div">
						<div class="offer-type">
							<select data-ng-model="couponCtrl.coupon.offer_type">
								<option value="percentage">Percentage</option>
								<option value="flat">Flat</option>
								<option value="trial">Trial</option>
							</select>
						</div>
						<div class="offer" data-ng-if="couponCtrl.coupon.offer_type == 'trial'">
							<input  type="text" name="offer"  class="form-control offer-val"
							placeholder="Amount" value="0" style="display:none;"  />
						</div>
						<div class="offer" data-ng-if="couponCtrl.coupon.offer_type != 'trial'">
							<input data-ng-blur="couponCtrl.offerValidation($event)" ng-change="clickMe(couponCtrl.coupon.offer, couponCtrl.coupon.offer_type)" type="text" name="offer" data-ng-model="couponCtrl.coupon.offer" class="form-control offer-val"
							placeholder="Amount" value="{{old('offer')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  onKeyPress="if(this.value.length==9) return false;" />
							<p class="error-msg" data-ng-show="errors.offer.has">@{{ errors.offer.message }}</p>
						</div>
					</div>
				</div>

				<!-- user limit start -->
				<div class="form-group" data-ng-class="{'has-error': errors.user.has}">
					<label>
						{{trans('payment::coupons.user_limit')}}
						<span class="required">*</span>
					</label>
					<input type="number" min="0" name="user" data-ng-model="couponCtrl.coupon.user" class="form-control offer-val"
							placeholder="{{trans('payment::coupons.user_limit_placeholder')}}" value="{{old('user')}}"  />
					<p class="error-msg" data-ng-show="errors.user.has">
					User limit required
					</p>
				</div>
				<!-- user limit end -->

				<div class="form-group">
					<label>
						{{trans('payment::coupons.expire_date')}}
						<!-- <span class="required">*</span> -->
					</label>
					<div class="form-input">
						<input type="text" name="valid_till" id="expire-dates" class="form-control"
							data-ng-model="couponCtrl.coupon.valid_till" placeholder="DD-MM-YYYY"
							data-validation-name="Expire Date"/>
					</div>
					<p class="error-msg" data-ng-show="errors.valid_till.has">
						Expire date required
					</p>
				</div>

				<!-- <div class="form-group">
					<div class="switch-concept flexbox align-items-center">
						<div class="swich-content flexbox align-items-center flex-wrap">
							<span>Trial</span>
							<div class="right-side flexbox align-items-center">
								<span class="text">({{ trans('payment::coupons.message.inactive') }})</span>
								<label class="switch">
									<input type="checkbox" data-ng-model="couponCtrl.coupon.is_trial" name="is_trial">
									<span class="slider round"></span>
								</label>
								<span class="text">({{ trans('payment::coupons.message.active') }})</span>
							</div>
						</div>
					</div>
					<p class="error-msg"></p>
				</div> -->

				<div class="form-group">
					<div class="switch-concept flexbox align-items-center">
						<div class="swich-content flexbox align-items-center flex-wrap">
							<span>{{ trans('payment::coupons.status') }}</span>
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
				</div>
			</div>

			<div class="bottom-button text-right flexbox align-items-center">
				<input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="couponCtrl.closeCouponEdit()" name="cancel" class="save okkk" />
				<input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
			</div>
		</form>
	</div>
</div>