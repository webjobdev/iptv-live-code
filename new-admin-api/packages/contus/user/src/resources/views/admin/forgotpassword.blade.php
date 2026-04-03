@extends('base::layouts.partials')
@section('content')

<div class="login-container flexbox">
    <div class="login_bg">

    </div>
    <div class="login-form flexbox align-items-center justify-center" data-ng-controller="forgotPwdController as forgotPwdCtrl">
        <div class="login-form-content">
        <img class="logo" src="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.logo' )}}">
            <div class="alert alert-success" data-ng-if="forgotPwdCtrl.showSuccess">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span>@{{forgotPwdCtrl.responseMessage}}</span>
           </div>
           <div class="alert alert-error" data-ng-if="forgotPwdCtrl.showError">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span>@{{forgotPwdCtrl.responseMessage}}</span>
            </div>
            <form name="form" id="form" class="form-horizontal" data-base-validator data-ng-submit="forgotPwdCtrl.authenticate($event)" method="POST">
                {!! csrf_field() !!}
                <div class="input-group" data-ng-class="{'has-error': errors.email.has}">
                    <label>Email Address</label>
                    <input type="text" class="form-control" id="email" autocomplete="Off" placeholder="{{trans('user::auth.forgotpassword.placeholder_email')}}"
                        name="email"  data-ng-model="forgotPwdCtrl.authData.email">
                    <p class="error-msg" data-ng-show="errors.email.has">@{{errors.email.message}}</p>
                </div>
                <div class="align-right">
                    <a title="{{trans('user::auth.forgotpassword.login')}}" href="{{ url('admin/auth/login') }}"
                        class="forgot_link">{{trans('user::auth.forgotpassword.login')}}</a>
                </div>
                <button id="submit" class="signin-btn">{{trans('user::auth.forgotpassword.button')}}</button>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/login/forgotpassword.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection
