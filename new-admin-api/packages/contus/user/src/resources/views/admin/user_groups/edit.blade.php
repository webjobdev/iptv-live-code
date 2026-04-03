@extends('base::layouts.default')

@section('header')
<style>
    li ul {
        padding-left: 25px;
        border: none;
        display:block;
    }

        
</style>
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div class="page-heading flexbox align-items-center flex-wrap">
    <h4>{{trans('user::adminuser.edit_user_group')}}</h4>
</div>
<div class="form-page video-detail single-page" data-ng-controller="adminGroupController as adminGrpCtrl">
    <form name="groupForm" method="POST" data-base-validator data-ng-submit="adminGrpCtrl.submit($event, {{$id}})">
        {!! csrf_field() !!}
        <span ng-hide="true" id="formid">{{$id}}</span>
        <div class="tab-content division">
            <div class="one-set width-50">
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>{{trans('user::adminuser.group_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" class="form-control"  data-ng-model="adminGrpCtrl.adminGroupData.name"
                        placeholder="{{trans('user::adminuser.group_name')}}" value="{{old('name')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{errors.name.message}}</p>
                </div>
                <div class="form-group admin-group" data-ng-class="{'has-error': errors.permissions.has}">
                    <label>{{trans('user::adminuser.permissions')}}
                        <span class="required">*</span>
                    </label>
                    <p class="error-msg" data-ng-show="errors.permissions.has">@{{errors.permissions.message}}</p>
                    <ul id="tree">
                        <li data-ng-repeat="grandparentItem in adminGrpCtrl.accessModules track by $index">
                            <div class="ckbox ckbox-default">
                                <input checked type="checkbox" class="parentCheckBox" name="permissions"
                                    data-ng-model="grandparentItem.Selected"
                                    value="@{{grandparentItem.access}}"
                                    data-ng-click="adminGrpCtrl.toggleAllCheckboxes($event, grandparentItem.children)" 
                                />
                                <label>@{{grandparentItem.name}}</label>
                            </div>
                            <ul>
                                <li data-ng-repeat="parentItem in grandparentItem.children">
                                    <div class="ckbox ckbox-default">
                                        <input checked type="checkbox" class="childCheckBox" name="permissions"
                                        data-ng-model="parentItem.Selected"
                                        value="@{{parentItem.access_name}}" 
                                        data-ng-click="adminGrpCtrl.toggleAllCheckboxes($event, parentItem.children, grandparentItem)"
                                        />
                                        <label>@{{parentItem.name}}</label>
                                    </div>
                                    <ul>
                                        <li data-ng-repeat="childItem in parentItem.children">
                                            <div class="ckbox ckbox-default">
                                                <input checked type="checkbox" class="GrandchildCheckBox" name="permissions" 
                                                data-ng-model="childItem.Selected"
                                                value="@{{childItem.access_name}}" 
                                                data-ng-click="adminGrpCtrl.toggleAllCheckboxes($event, null, parentItem, grandparentItem)"
                                                />
                                                <label>@{{childItem.name}}</label>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>        
                    </ul>                        
                </div>
            </div>
        </div>
        <div class="bottom-button text-right flexbox align-items-center">
            <a href="{{url('admin/groups')}}" class="save">
                {{trans('base::general.cancel')}}
            </a>
            <button class="publish-now">
                {{trans('base::general.submit')}}
            </button>
        </div>
    </form>
</div>

@endsection
@section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getUserAssetsUrl('js/admingroup/add.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection