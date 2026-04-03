@extends('base::layouts.default')
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
<style type="text/css">
.custom-color {
color: #a94442;
}
</style>
<div class="page-heading flexbox align-items-center flex-wrap">
    <div class="left-side">
        <h4>{{trans('user::adminuser.changepassword.changepassword')}}</h4>
    </div>
</div>
<div class="response-msg"></div>
<div class="video-detail form-page change-pass-page" data-base-validator data-ng-controller="ChangePasswordController as chngPassCtrl">
    <form name="changePasswordForm" method="POST" data-ng-submit="chngPassCtrl.save($event)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        @include('base::partials.errors')
        <div class="page-padding">
            <div class="tab-contentt">
                <div class="division flexbox">
                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.old_password.has}">
                            <label>{{trans('user::adminuser.changepassword.oldpassword')}} <span class="required">*</span></label>
                            <div class="form-input">
                                <input type="password" name="old_password"  data-ng-model="chngPassCtrl.setpassword.old_password" class="form-control" data-validation-name="Old Password" placeholder="{{trans('user::adminuser.changepassword.placeholder_oldpassword')}}"/>
                            </div>
                            <p class="error-msg" data-ng-show="errors.old_password.has">@{{ errors.old_password.message }}</p>
                            <p class="error-msg" data-ng-if="passwordError.has.Oldpassword">{{trans('user::adminuser.changepassword.wrong_old')}}</p>
                        </div>
                        
                        <div class="form-group" data-ng-class="{'has-error': errors.password.has}">
                            <label>{{trans('user::adminuser.changepassword.newpassword')}} <span class="required">*</span></label>
                            <div class="form-input">
                                <input type="password" name="password" class="form-control" maxlength="16"  data-ng-model="chngPassCtrl.setpassword.password" data-validation-name="New Password" placeholder="{{trans('user::adminuser.changepassword.placeholder_newpassword')}}"/>
                            </div>
                            <p class="error-msg" data-ng-show="errors.password.has">@{{ errors.password.message }}</p>
                        </div>
                        
                        <div class="form-group" data-ng-class="{'has-error': errors.password_confirmation.has}">
                            <label>{{trans('user::adminuser.changepassword.confirmpassword')}} <span class="required">*</span></label>
                            <div class="form-input">
                                <input type="password" name="password_confirmation" maxlength="16" class="form-control"  data-ng-model="chngPassCtrl.setpassword.password_confirmation" data-validation-name="Confirm Password" placeholder="{{trans('user::adminuser.changepassword.placeholder_confirmpassword')}}"/>
                            </div>
                            <p class="error-msg" data-ng-show="errors.password_confirmation.has">@{{ errors.password_confirmation.message }}</p>
                            <p class="error-msg custom-color"  data-ng-if="passwordError.has.reenterpasswordsame">{{trans('user::adminuser.changepassword.not_match')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-button text-right flexbox align-items-center">
            {{-- <a class="save" href="{{url('admin/users/changepassword')}}">
                {{trans('base::general.cancel')}}
            </a> --}}
            <input type="reset" value="Reset" class="btn_gray">
            <button class="publish-now">
            {{trans('base::general.submit')}}
            </button>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/adminusers/changepassword.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection