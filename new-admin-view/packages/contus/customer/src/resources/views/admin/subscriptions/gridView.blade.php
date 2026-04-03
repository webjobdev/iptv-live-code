<div id="subscriptions_plans">
	<div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
		<div class="table_loader">
			<div class="loader"></div>
		</div>
	</div>
	<div class="table_responsive">
		<table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
			<thead>
				<tr>
					@include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscription_all_write'])
					<th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'Amount' || field.name == 'Duration (in days)'}">@{{field.name}} <span data-ng-if="field.sort==true"
							id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,'name')"></span>
						<span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="search_text">
					<td></td>
					<td class="search_product"><input type="text" class="form-control" data-ng-model="searchRecords.name"
							data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.enter_name')}}">
					</td>
					<td class="search_product td-custom-width"><input type="text" class="form-control" data-ng-model="searchRecords.type"
							data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.enter_type')}}"></td>
					<td class="search_product center td-custom-width">
						<input type="text" class="form-control search-amount-subscription" data-ng-model="searchRecords.amount"
							data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.enter_amount')}}">
					</td>
					<td class="search_product center td-custom-width">
						<input type="text" class="form-control search-amount-subscription" data-ng-model="searchRecords.amount_israel"
							data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('customer::subscription.enter_amount')}}">
					</td>
					<!-- <td></td> -->
					<td></td>
					<td>
						<select class="form-control mb15 select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl" data-boot-tooltip="true" data-ng-model="searchRecords.is_active"
							data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_status')}}">
							<option value="all">{{trans('base::general.all')}}</option>
							<option value='1'>{{trans('customer::subscription.active')}}</option>
							<option value='0'>{{trans('customer::subscription.inactive')}}</option>
						</select>
					</td>
					<td>
					</td>
				</tr>
				<tr data-ng-if="noRecords">
					<td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
				</tr>
				<tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat"
					data-intialize-sidebar="">
					<td>
						<div class="ckbox ckbox-default">
							<input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
								value="@{{record.id}}" name="selectedCheckbox[]">
							<label for="roles_@{{record.id}}"></label>
						</div>
					</td>

					<td>@{{record.name}}</td>
					<td class="td-custom-width">@{{record.type}}</td>
					<td class="center td-custom-width">@{{record.amount}}</td>
					<!-- <td class="center td-custom-width">@{{record.amount_israel}}</td> -->
					<td class="center">@{{record.duration}}</td>
					<td>@{{record.formatted_created_date}}</td>

					<td>
						<div class="tooltip-parent" data-ng-if="checkAccess('subscription_all_write')">
							<span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;" data-toggle="modal" data-target="#single-record-status-update-popup" data-ng-click="confirmationPopupSingleRecordAction(record)">{{ trans('customer::subscription.message.active')}}</span>
							<span class="tooltip_title">{{trans('customer::subscription.deactivate_subscription')}}</span>
						</div>
						<div class="tooltip-parent" data-ng-if="checkAccess('subscription_all_write')">
							<span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;" data-toggle="modal" data-target="#single-record-status-update-popup" data-ng-click="confirmationPopupSingleRecordAction(record)">{{trans('customer::subscription.message.inactive')}}</span>
							<span class="tooltip_title">{{trans('customer::subscription.activate_subscription')}}</span>
						</div>
						
						
					</td>

					<td class="table-action">
						<div class="flexbox align-items-center justify-center">
							<div data-ng-if="checkAccess('subscription_all_write')" class="column edit_table_icon tooltip-parent">
								<button class="table_action sidepanel-open" data-ng-click="subscriptionCtrl.
								(record)">
									<svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
										<g>
											<path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
										</g>
									</svg>
								</button>
								<span class="tooltip_title">{{trans('base::general.edit')}}</span>
							</div>
							<div data-ng-if="checkAccess('subscription_all_write')" class="tooltip-parent">
								<span ng-mouseover="getTooltip($event)" data-toggle="modal"
									data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon"
									data-boot-tooltip="true" data-original-title="">
									<svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
										<g data-original-title="" title="">
											<path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
										</g>
									</svg>
								</span>
								<span class="tooltip_title">{{trans('base::general.delete')}}</span>
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
		<form name="subscriptionForm" id="subscriptionForm" method="POST" data-base-validator
			data-ng-submit="subscriptionCtrl.save($event,subscriptionCtrl.subscriptions_plans.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="sidepanel-header flexbox align-items-center">
				<h5 data-ng-if="!subscriptionCtrl.subscriptions_plans.id">{{trans('customer::subscription.add_new_subscription')}}</h5>
				<h5 data-ng-if="subscriptionCtrl.subscriptions_plans.id">{{trans('customer::subscription.edit_new_subscription')}}</h5>
				<div data-ng-if="subscriptionCtrl.subscriptions_plans.id" class="right-side">
					<select minimumResults="-1" data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="subscriptionCtrl.languageChange()" myValue="subscriptionCtrl.subscribeTranslation.language" data-ng-model="subscriptionCtrl.subscribeTranslation.language" data-ng-options="a.id as a.title  for a in subscriptionCtrl.languages "></select>
				</div>
			</div>

			<div class="sidepanel-scroll">
				@include('base::partials.errors')

				<div class="form-group" data-ng-class="{'has-error': errors.name.has}">
					<label>
						{{trans('customer::subscription.subscription_name')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="name" data-unique="@{{subscriptionCtrl.uniqueRoute}}" data-ng-model="subscriptionCtrl.subscriptions_plans.name"
							class="form-control" placeholder="{{trans('customer::subscription.subscription_placeholder')}}" value="{{old('title')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
				</div>

				<div class="form-group" data-ng-class="{'has-error': errors.type.has}">
					<label>
						{{trans('customer::subscription.type')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="type" data-ng-model="subscriptionCtrl.subscriptions_plans.type" class="form-control"
							placeholder="{{trans('customer::subscription.type_placeholder')}}" value="{{old('type')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.type.has">@{{ errors.type.message }}</p>
				</div>

				<div class="form-group" data-ng-class="{'has-error': errors.amount.has}">
					<label>
						{{trans('customer::subscription.amount')}} ($)
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="amount" data-ng-model="subscriptionCtrl.subscriptions_plans.amount" class="form-control"
							placeholder="{{trans('customer::subscription.amount_placeholder')}}" value="{{old('amount')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.amount.has">@{{ errors.amount.message }}</p>
				</div>

				<!-- israel amount start -->

				<!-- <div class="form-group" data-ng-class="{'has-error': errors.amount_israel.has}">
					<label>
						{{trans('customer::subscription.amount')}} (₪)
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="amount_israel" data-ng-model="subscriptionCtrl.subscriptions_plans.amount_israel" class="form-control"
							placeholder="{{trans('customer::subscription.amount_placeholder')}}" value="{{old('amount_israel')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.amount_israel.has">@{{ errors.amount_israel.message }}</p>
				</div> -->

				<div class="form-group" data-ng-class="{'has-error': errors.duration.has}">
					<label>
						{{trans('customer::subscription.duration')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="duration" data-ng-model="subscriptionCtrl.subscriptions_plans.duration" class="form-control"
							placeholder="{{trans('customer::subscription.duration_placeholder')}}" value="{{old('amount')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.duration.has">@{{ errors.duration.message }}</p>
				</div>


				<div class="form-group" data-ng-class="{'has-error': errors.no_of_device.has}">
					<label>
						{{trans('customer::subscription.no_of_device')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="no_of_device" data-ng-model="subscriptionCtrl.subscriptions_plans.no_of_device" class="form-control"
							placeholder="{{trans('customer::subscription.no_of_device_placeholder')}}" value="{{old('no_of_device')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.no_of_device.has">@{{ errors.no_of_device.message }}</p>
				</div>

				<!-- No of device end -->

				<!-- <div class="form-group">
					<label>{{trans('customer::subscription.status')}}</label>
					<div class="form-input">
						<select class="form-control mb10" name="is_active"
						data-ng-model="subscriptionCtrl.subscriptions_plans.is_active">
							<option value="1">{{trans('customer::subscription.active')}}</option>
							<option value="0">{{trans('customer::subscription.inactive')}}</option>
						</select>
					</div>
				</div> -->


				<!-- trial status start -->
				<!-- <div class="form-group">
					<div class="switch-concept flexbox align-items-center">
						<svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
							<g>
								<path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
								 fill="#3d3d3d"></path>
							</g>
						</svg>
						<div class="swich-content flexbox align-items-center flex-wrap">
							<span>Trial</span>
							<div class="right-side flexbox align-items-center">
								<span class="text">({{ trans('video::videos.message.inactive') }})</span>
								<label class="switch">
									<input type="checkbox" data-ng-model="subscriptionCtrl.subscriptions_plans.trial" name="status">
									<span class="slider round"></span>
								</label>
								<span class="text">({{ trans('video::videos.message.active') }})</span>
							</div>
						</div>
					</div>
					<p class="error-msg"></p>
				</div> -->

				<!-- trial status end -->

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
									<input type="checkbox" data-ng-model="subscriptionCtrl.subscriptions_plans.is_active" name="status">
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
				<input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="subscriptionCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
				<input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
			</div>
		</form>

		<!-- <form name="subscriptionTranslationForm" id="subscriptionTranslationForm" style="display:none;" method="POST"
			data-base-validator data-ng-submit="subscriptionCtrl.saveTranslation($event,subscriptionCtrl.subscriptions_plans.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="sidepanel-header flexbox align-items-center">
				<h5 data-ng-if="!subscriptionCtrl.subscriptions_plans.id"> {{trans('customer::subscription.add_new_subscription')}}</h5>
				<h5 data-ng-if="subscriptionCtrl.subscriptions_plans.id"> {{trans('customer::subscription.edit_new_subscription')}}</h5>
				<div data-ng-if="subscriptionCtrl.subscriptions_plans.id" class="right-side">

					<select minimumResults="-1" data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="subscriptionCtrl.languageChange()" myValue="subscriptionCtrl.subscribeTranslation.language" data-ng-model="subscriptionCtrl.subscribeTranslation.language" data-ng-options="a.id as a.title  for a in subscriptionCtrl.languages "></select>

				</div>
			</div>

			<div class="sidepanel-scroll">
				@include('base::partials.errors')
				<div class="form-group" data-ng-class="{'has-error': errors.name1.has}">
					<label>
						{{trans('customer::subscription.subscription_name')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="name" data-unique="@{{subscriptionCtrl.uniqueRoute}}" data-ng-model="subscriptionCtrl.subscriptions_plans.name"
							class="form-control" placeholder="{{trans('customer::subscription.subscription_placeholder')}}" value="{{old('title')}}"
							disabled />
					</div>
					<p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
				</div>
				<div class="form-group" data-ng-class="{'has-error': errors.trans_name.has}">
					<label>
						{{trans('customer::subscription.subscription_name')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="trans_name" data-unique="@{{subscriptionCtrl.uniqueRoute}}" data-ng-model="subscriptionCtrl.subscribeTranslation.name"
							class="form-control" placeholder="{{trans('customer::subscription.subscription_placeholder')}}" value="{{old('title')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.trans_name.has">@{{ errors.trans_name.message }}</p>
				</div>

				<div class="form-group" data-ng-class="{'has-error': errors.type1.has}">
					<label>
						{{trans('customer::subscription.type')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="type" data-ng-model="subscriptionCtrl.subscriptions_plans.type" class="form-control"
							placeholder="{{trans('customer::subscription.type_placeholder')}}" value="{{old('type')}}" disabled />
					</div>
					<p class="error-msg" data-ng-show="errors.type.has">@{{ errors.type.message }}</p>
				</div>
				<div class="form-group" data-ng-class="{'has-error': errors.trans_type.has}">
					<label>
						{{trans('customer::subscription.type')}}
						<span class="required">*</span>
					</label>
					<div class="form-input">
						<input type="text" name="trans_type" data-ng-model="subscriptionCtrl.subscribeTranslation.type" class="form-control"
							placeholder="{{trans('customer::subscription.type_placeholder')}}" value="{{old('type')}}" />
					</div>
					<p class="error-msg" data-ng-show="errors.trans_type.has">@{{ errors.trans_type.message }}</p>
				</div>
			</div>

			<div class="bottom-button text-right flexbox align-items-center">
				<button class="save" data-ng-click="subscriptionCtrl.closeSubscriptionEdit()">
					{{ trans('base::general.cancel') }}
				</button>
				<button class="publish-now">
					{{trans('base::general.submit')}}
				</button>

			</div>
		</form> -->
	</div>
</div>