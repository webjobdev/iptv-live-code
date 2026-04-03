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
    <h4>{{trans('user::adminuser.add_user_group')}}</h4>
</div>
<div class="form-page video-detail single-page" data-ng-controller="adminGroupController as adminGrpCtrl">
    <form name="groupForm" method="POST" data-base-validator data-ng-submit="adminGrpCtrl.submit($event)">
        {!! csrf_field() !!}
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
                        {{-- @foreach(Config::get('access.modules') as $key => $modules)
                        @if(!$loop->last)
                        <li>
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="parentCheckBox" name="permissions[{{$key}}][]"
                                data-ng-model="{{$modules['access']}}"
                                    value="{{$modules['access']}}" />
                                <label>{{trans('user::adminuser.' .$modules['name'] )}}</label>
                            </div>
                            <ul>
                                @foreach($modules['sub_module'] as $eachModule => $moduleDetails)
                                <li>
                                    <div class="ckbox ckbox-default">
                                        <input type="checkbox" class="childCheckBox" name="permissions[{{$key}}][]"
                                        data-ng-model="{{$moduleDetails['access']}}"
                                        value="{{$moduleDetails['access']}}" 
                                    data-ng-click="adminGrpCtrl.handlePermissionssubModule({{$moduleDetails['access']}},{{$key}},'{{$modules['access']}}', '{{$moduleDetails['access']}}')"/>                                
                                        <label>{{trans('user::adminuser.' . $moduleDetails['name'] )}}</label>
                                    </div>
                                    <ul>
                                        @foreach($moduleDetails['permission'] as $modulePermission)
                                      
                                         <li>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" class="GrandchildCheckBox" 
                                                data-ng-model="{{$moduleDetails['access'].'_'.$modulePermission}}"
                                                name="permissions[{{$key}}][]" value="{{$moduleDetails['access'].'_'.$modulePermission}}" 
                                                data-ng-click="adminGrpCtrl.handlePermissionsGrandChildren({{$moduleDetails['access'].'_'.$modulePermission}},{{$key}},'{{$modules['access']}}', '{{$moduleDetails['access']}}', '{{$moduleDetails['access'].'_'.$modulePermission}}')"/>                                        
                                                <label>{{trans('user::adminuser.' . $modulePermission )}}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endForeach
                                @if(array_key_exists("permission",$modules))
                                @foreach($modules['permission'] as $key => $mainmodulePermission)
                                <li>
                                    <div class="ckbox ckbox-default">
                                        <input type="checkbox" class="GrandchildCheckBox"  name="permissions[{{$key}}][]"
                                            value="{{$modules['access'].'_'.$mainmodulePermission}}" />                                
                                        <label>{{trans('user::adminuser.' . $mainmodulePermission )}}</label>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if($loop->last)
                        <li style="display:none">
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="parentCheckBox "  name="permissions[{{$key}}][]"
                                    value="{{$modules['access']}}" checked  readonly />
                                <label>{{trans('user::adminuser.' .$modules['name'] )}}</label>
                            </div>
                            <ul>
                                @foreach($modules['sub_module'] as $eachModule => $moduleDetails)
                                <li>
                                    <div class="ckbox ckbox-default">
                                        <input type="checkbox" class="childCheckBox"  name="permissions[{{$key}}][]"
                                            value="{{$moduleDetails['access']}}" checked />                                
                                        <label>{{trans('user::adminuser.' . $moduleDetails['name'] )}}</label>
                                    </div>
                                    <ul>
                                        @foreach($moduleDetails['permission'] as $modulePermission)
                                      
                                         <li>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" class="GrandchildCheckBox" 
                                                    name="permissions[{{$key}}][]" value="{{$moduleDetails['access'].'_'.$modulePermission}}" checked/>                                        
                                                <label>{{trans('user::adminuser.' . $modulePermission )}}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endForeach
                                @if(array_key_exists("permission",$modules))
                                @foreach($modules['permission'] as $key => $mainmodulePermission)
                                <li>
                                    <div class="ckbox ckbox-default">
                                        <input type="checkbox" class="GrandchildCheckBox"  name="permissions[{{$key}}][]"
                                            value="{{$modules['access'].'_'.$mainmodulePermission}}" />                                
                                        <label>{{trans('user::adminuser.' . $mainmodulePermission )}}</label>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @endif
                        @endForeach --}}
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


<script>
    //clicking the parent checkbox should check or uncheck all child checkboxes
    $(".parentCheckBox").click(
        function () {
            $(this).parents('li').find('.childCheckBox').prop('checked', this.checked);
            $(this).parents('li ').find('.GrandchildCheckBox').prop('checked', this.checked);
        }
    );
    //clicking the last unchecked or checked checkbox should check or uncheck the parent checkbox
    $('.childCheckBox').click(
        function () {
            alert(2);
            if (this.checked == true) {
                var flag = true;
                var childArray = [];
                $(this).parents('li').find('.parentCheckBox').prop('checked', flag);
                $(this).parents('li li').find('.GrandchildCheckBox').prop('checked', this.checked);
                $(this).parents('li').find('.childCheckBox').each(
                    function () {
                        if (this.checked == false) {
                            childArray.push('false');
                            $(this).parents('li li').find('.GrandchildCheckBox').prop('checked', false);
                        }


                        if (this.checked == true) {
                            childArray.push('true');
                            //$(this).parents('li li').find('.GrandchildCheckBox').prop('checked', true);
                        }

                    }
                );
            }

            if (this.checked == false) {
                var flag;
                var childArray = [];
                $(this).parents('li').find('.childCheckBox').each(
                    function () {

                        if (this.checked == false) {
                            childArray.push('false');
                            $(this).parents('li li').find('.GrandchildCheckBox').prop('checked', false);
                        }

                        if (this.checked == true) {
                            childArray.push('true');
                            //$(this).parents('li li').find('.GrandchildCheckBox').prop('checked', true);
                        }
                    }
                );
                var childValue = $.unique(childArray.sort()).sort();
                if (childValue.length == 1) {
                    if (childValue[0] == 'false') {
                        flag = false;
                    }
                    if (childValue[0] == 'true') {
                        flag = true;
                    }
                }
                $(this).parents('li').find('.parentCheckBox').prop('checked', flag);
            }
        }
    );

    $('.GrandchildCheckBox').change(
        function () {
            alert(3);
            if (this.checked == true) {
                var flag = true;

                $(this).parents('li').find('.parentCheckBox').prop('checked', flag);
                $(this).parents('li li').find('.childCheckBox').prop('checked', flag);
            }

            if (this.checked == false) {
                var flag;
                var grandchildArray = [];
                $(this).parents('li li').find('.GrandchildCheckBox').each(
                    function () {
                        if (this.checked == false)
                            grandchildArray.push('false');

                        if (this.checked == true)
                            grandchildArray.push('true');
                    }
                );
                var grandchildValue = $.unique(grandchildArray.sort()).sort();
                if (grandchildValue.length == 1) {
                    if (grandchildValue[0] == 'false') {
                        flag = false;
                    }
                    if (grandchildValue[0] == 'true') {
                        flag = true;
                    }
                }

                var childArray = [];
                $(this).parents('li li').find('.childCheckBox').each(
                    function () {
                        var isChecked = $(".childCheckBox:checkbox").prop('checked')
                        if (isChecked == false) {
                          
                            childArray.push('false');
                        }
                        if (isChecked == true) {
                            childArray.push('true');
                        }

                    }
                );
                var childValue = $.unique(childArray.sort()).sort();

                if ((grandchildValue.length == 1) && (childValue.length == 1)) {
                    if ((grandchildValue[0] == 'false') && (childValue[0] == 'false')) {

                        $(this).parents('li').find('.parentCheckBox').prop('checked', false);
                    }
                    if ((grandchildValue[0] == 'false') && (childValue[0] == 'true')) {

                        $(this).parents('li').find('.parentCheckBox').prop('checked', true);
                    }

                }
                $(this).parents('li li').find('.childCheckBox').prop('checked', flag);

            }
        }
    );
</script>
@endsection