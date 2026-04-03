<div>
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
					<th></th>
					<th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'Payment Method' || field.name == 'Amount ($)'}">@{{field.name}}
						<span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"	data-ng-click="fieldOrder($event,'id')"></span>
						<span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="search_text">
					<td></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.transaction_id" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::transaction.enter_transaction_id')}}"></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.slug" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::transaction.enter_customer')}}"></td>
					<td class="center"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="search_product planstart_date grid-date-filter">
							<input type="text" name="filter_created_at" autocomplete="off" id="filter_created_at" class="form-control"
								data-ng-model="searchRecords.filter_created_at" placeholder="DD-MM-YYYY"
								data-ng-change="search()" data-original-title="{{trans('base::general.select_startdate')}}" />
					</td>
					<td class="search_product planstart_date grid-date-filter">
							<input type="text" name="filter_end_date" autocomplete="off" id="filter_end_date" class="form-control"
								data-ng-model="searchRecords.filter_end_date" placeholder="DD-MM-YYYY"
								data-ng-change="search()" data-original-title="{{trans('base::general.select_startdate')}}" />
					</td>
				</tr>

				<tr data-ng-if="noRecords">
					<td colspan="@{{heading.length + 1}}"
						class="no-data center">{{trans('base::general.not_found')}}</td>
				</tr>
				<tr data-ng-if="showRecords"
					data-ng-repeat="record in records track by $index"
					data-ng-show="showRecords" class="list-repeat"
					data-intialize-sidebar="">
					<td></td>
					<td>@{{record.transaction_id}}</td>
					<!-- <td>@{{record.get_transaction_user.name}}</td> -->
					 <td>@{{record.name}}</td>
					<td>@{{record.plan_name}}</td>
					<td class="center">@{{record.get_payment_method.name}}</td>

					<td data-ng-if="record.currency != 'ILS'" class="center">$ @{{record.amount}}</td>
					<td data-ng-if="record.currency != 'ILS'" class="center"> @{{record.paid_amount?'$':'';}} @{{record.paid_amount?record.paid_amount:'-';}}</td>

					<td data-ng-if="record.currency === 'ILS'" class="center">₪ @{{record.amount}}</td>
					<td data-ng-if="record.currency === 'ILS'" class="center"> @{{record.paid_amount?'₪':'';}} @{{record.paid_amount?record.paid_amount:'-';}}</td>

					<td class="center">@{{record.applied_coupon?record.applied_coupon:'-';}}</td>
					<td>@{{record.status}}</td>
					<td class="center">@{{record.formatted_created_date}}</td>
					<td class="center">@{{record.formatted_end_date}}</td>

				</tr>
			</tbody>
		</table>
	</div>
</div>

@include('base::layouts.pagination')
