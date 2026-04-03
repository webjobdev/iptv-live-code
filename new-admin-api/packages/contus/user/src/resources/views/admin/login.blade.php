<!--  -->
@extends('base::layouts.partials')
@section('content')

<div class="login-container flexbox">
    <div class="login_bg">

    </div>
    <div class="login-form flexbox align-items-center">
        <div class="login-form-content">
            <!-- <img class="logo" src="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.logo' )}}"> -->
            <img class="logo" src="{{asset('storage/assets/images/email/logo.png')}}">
            {{-- @include('base::partials.errors') --}}
            @if (session()->has('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            <div data-ng-controller="loginController as loginCtrl">
                <form name="form" id="form" class="form-horizontal" data-base-validator data-ng-submit="loginCtrl.authenticate($event)" method="POST">
                    {!! csrf_field() !!}
                    <div class="input-group" data-ng-class="{'has-error': errors.email.has}">
                        <label>Email Address</label>
                        <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"
                            value="{{ old('email') }}" data-ng-model="loginCtrl.authData.email">
                        <p class="error-msg" data-ng-if="errors.email.has" ng-cloak>@{{errors.email.message}}</p>
                    </div>
                    <div class="input-group" data-ng-class="{'has-error': errors.password.has}">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" data-ng-model="loginCtrl.authData.password" placeholder="Enter Password"
                            name="password">
                        <p class="error-msg" data-ng-if="errors.password.has" ng-cloak>@{{errors.password.message}}</p>
                    </div>
                    <div class="align-right">
                        <a title="{{trans('user::auth.login.forgot_password')}}" href="{{url('admin/auth/forgot-password')}}"
                            class="forgot_link">{{trans('user::auth.login.forgot_password')}}</a>
                    </div>
                    <button title="{{trans('user::auth.login.signin')}}" class="signin-btn">{{trans('user::auth.login.signin')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/login/login.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script> -->

<script src="{{asset('storage/assets/js/Validate.js')}}"></script>
<script src="{{asset('storage/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('storage/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('storage/assets/js/login/login.js')}}"></script>
<script src="{{asset('storage/assets/js/gridView.js')}}"></script>
<script src="{{asset('storage/assets/js/grid.js')}}"></script>

@endsection