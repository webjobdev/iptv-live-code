@extends('base::layouts.default') 

@section('header')
@include('base::layouts.headers.dashboard') 
@endsection

@section('content')
<div class='response-msg'></div>
<div class="setting-page" data-ng-controller="settingController as settingCtrl" >
	<form name="userForm" method="POST" data-ng-submit="settingCtrl.updateSettings($event)" enctype="multipart/form-data">
		{!! csrf_field() !!}
		<div class="video-detail form-page settings">
			<ul class="flexbox custom-tabs">
				<li class="tab-link" data-ng-if="categoryItem.slug !== 'settings'" ng-class="{active:settingCtrl.selectedTab === categoryItem.slug}" ng-repeat="categoryItem in settingCtrl.settingCategories">
					<a title="categoryItem.name" href="#@{{categoryItem.slug}}" data-toggle="tab" class="flexbox align-items-center" ng-click="settingCtrl.activeTab('categoryItem.slug')"> 
						@{{categoryItem.name}}
					</a>
				</li>
			</ul>
			<div class="metadata">
				<div class="tab-content" ng-class="{active:settingCtrl.selectedTab === parentItem.slug}" ng-repeat="parentItem in settingCtrl.settingFields" id="@{{parentItem.slug}}">
					<div class="tab-pane flexbox flex-wrap text-style ">
						<div class="col-divide" ng-class="{hide:field.is_hidden}" ng-repeat="categoryItem in parentItem.category | map: 'settings' | flatten">
							<div class="form-group" data-ng-class="{'has-error': errors.@{{categoryItem.setting_name}}.has}">
								<label ng-class="{hide:categoryItem.is_hidden == 1}">
									@{{categoryItem.display_name}}
									<span ng-if="settingCtrl.optionalFields.indexOf(categoryItem.setting_name) === -1" class="required">
									<span ng-if="categoryItem.display_name != 'Site Mobile Number' && categoryItem.display_name != 'Premium Name'">*</span>
									</span>
								</label>
								<!-- Dropdown -->
								<select ng-class="{hide:categoryItem.is_hidden == 1}" data-ng-if="categoryItem.type === 'dropdown'" class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-model="settingCtrl.settingsData[categoryItem.setting_name]" name="@{{categoryItem.setting_name}}"  id="@{{categoryItem.setting_name}}">
								</select>
								<!-- Image Type -->
								<div ng-class="{hide:categoryItem.is_hidden == 1}" class="subtitle_btn" data-ng-if="categoryItem.type === 'image'">
									<svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
										<g>
											<path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
										</g>
									</svg>
									<span>Upload Image</span>
									<input type="file" data-ng-model="settingCtrl.settingsData.categoryItem.setting_name"
									data-ng-model="settingCtrl.settingsData[categoryItem.setting_name]" name="@{{categoryItem.setting_name}}" class="form-control" id="@{{categoryItem.setting_name}}">
								</div>
								<img ng-class="{hide:categoryItem.is_hidden == 1}" data-ng-if="categoryItem.type === 'image'" alt="" src="{{asset('assets/images/')}}/@{{categoryItem.setting_value}}">
								<!-- Text & Email Type -->
								<input ng-class="{hide:categoryItem.is_hidden == 1}" data-ng-model="settingCtrl.settingsData[categoryItem.setting_name]" data-ng-if="categoryItem.type === 'text' || categoryItem.type === 'email'" type="text" name="@{{categoryItem.setting_name}}"  class="form-control" id="@{{categoryItem.setting_name}}" value="@{{categoryItem.setting_value}}">
								<!-- Descrption -->
								<p ng-class="{hide:categoryItem.is_hidden == 1}" data-ng-if="categoryItem.description" class="description">@{{categoryItem.description}}</p>
								<p class="error-msg" data-ng-if="errors[categoryItem.setting_name].has">@{{errors[categoryItem.setting_name].message}}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="bottom-button text-right flexbox align-items-center btn-invoice fixed-btm-action">
				<a class="save" href="{{url('admin/dashboard')}}">
					{{ trans('base::general.cancel') }}
				</a>
				<button class="publish-now">
					{{ trans('base::general.submit') }}
				</button>
			</div>
		</div>
	</form>	
</div>
@endsection

@section('scripts')
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular-filter.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getUserAssetsUrl('js/settings/settings.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection