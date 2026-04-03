@extends('base::layouts.partials')

<style>
    /* body {
    position: relative;
    height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url(/adminview/assets/images/Login_BG.png) no-repeat center center;
    background-size: cover;
    opacity: 0.3;
    z-index: -1;
  } */

    body {
        position: relative;
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            linear-gradient(rgb(0 0 0 / 75%), rgb(0 0 0 / 80%)),
            url(/ott-laravel/new-admin-view/public/adminview/assets/images/Login_BG.png) no-repeat center center;
        background-size: cover;
        opacity: 1;
        z-index: -1;
    }

    /* .new-login_container {
    margin-top: 15rem;
    margin-bottom: 15rem;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0px 0px 10px rgb(255 255 255);
    width: 500px;
    max-width: 90vw;
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
  } */

    .new-login_container {
        margin-top: 25rem;
        margin-bottom: 19rem;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.8);
        width: 500px;
        max-width: 90vw;
        padding: 40px 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.3s ease;
    }

    /* 🌐 Medium screens (tablets) */
    @media (max-width: 992px) {
        .new-login_container {
            width: 400px;
            margin-top: 8rem;
            margin-bottom: 8rem;
            padding: 35px 25px;
        }
    }

    /* 📱 Small screens (mobiles) */
    @media (max-width: 768px) {
        .new-login_container {
            /* width: 90%; */
            margin-top: 15rem;
            margin-bottom: 20rem;
            padding: 30px 20px;
            box-shadow: 0px 0px 8px rgba(255, 255, 255, 0.6);
        }
    }

    /* 📱 Extra small screens (very small phones) */
    @media (max-width: 480px) {
        .new-login_container {
            width: 100%;
            margin-top: 15rem;
            margin-bottom: 18rem;
            padding: 25px 15px;
            border-radius: 15px;
        }
    }
</style>

@section('content')

    <div class="login-container flexbox">
        <!-- <div class="login_bg">

        </div> -->
        <div class="new-login_container">
            <div class="login-form flexbox align-items-center justify-center"
                data-ng-controller="forgotPwdController as forgotPwdCtrl">
                <div class="login-form-content">
                    <img class="logo"
                        src="{{asset('assets/images') . '/' . config('settings.general-settings.site-settings.logo')}}">
                    <div class="alert alert-success" data-ng-if="forgotPwdCtrl.showSuccess">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span>@{{forgotPwdCtrl.responseMessage}}</span>
                    </div>
                    <div class="alert alert-error" data-ng-if="forgotPwdCtrl.showError">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span>@{{forgotPwdCtrl.responseMessage}}</span>
                    </div>
                    <form name="form" id="form" class="form-horizontal" data-base-validator
                        data-ng-submit="forgotPwdCtrl.authenticate($event)" method="POST">
                        {!! csrf_field() !!}
                        <div class="input-group" data-ng-class="{'has-error': errors.email.has}">
                            <label>Email Address</label>
                            <input type="text" class="form-control" id="email" autocomplete="Off"
                                placeholder="{{trans('user::auth.forgotpassword.placeholder_email')}}" name="email"
                                data-ng-model="forgotPwdCtrl.authData.email">
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
    </div>

@endsection
@section('scripts')
    <script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
    <script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/login/forgotpassword.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
@endsection