<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
      url(/adminview/assets/images/Login_BG.png) no-repeat center center;
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
    margin-top: 10rem;
    margin-bottom: 10rem;
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
      margin-top: 5rem;
      margin-bottom: 5rem;
      padding: 30px 20px;
      box-shadow: 0px 0px 8px rgba(255, 255, 255, 0.6);
    }
  }

  /* 📱 Extra small screens (very small phones) */
  @media (max-width: 480px) {
    .new-login_container {
      width: 100%;
      margin-top: 3rem;
      margin-bottom: 3rem;
      padding: 25px 15px;
      border-radius: 15px;
    }
  }

  /* force correct layout */
  .password-wrapper {
    position: relative;
    width: 100%;
  }

  /* add space for eye icon */
  .password-wrapper .form-control {
    padding-right: 42px;
  }

  /* eye icon positioning */
  .password-eye {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #555;
    font-size: 16px;
    line-height: 1;
    z-index: 99;
  }

  /* hover effect */
  .password-eye:hover {
    color: #000;
  }
</style>

@section('content')

  <div class="login-container flexbox">
    <!-- <div class="login_bg">

                      </div> -->
    <div class="new-login_container">
      <div class="login-form flexbox align-items-center">
        <div class="login-form-content">
          <img class=" logo"
            src="{{asset('assets/images') . '/' . config('settings.general-settings.site-settings.logo')}}">
          {{-- @include('base::partials.errors') --}}
          @if (session()->has('success'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <span>{{ session('success') }}</span>
            </div>
          @endif
          <div data-ng-controller="loginController as loginCtrl">
            <form name="form" id="form" class="form-horizontal" data-base-validator
              data-ng-submit="loginCtrl.authenticate($event)" method="POST">
              {!! csrf_field() !!}

              <div class="input-group" data-ng-class="{'has-error': errors.email.has}">
                <label>Email Address</label>
                <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email"
                  value="{{ old('email') }}" data-ng-model="loginCtrl.authData.email">
                <p class="error-msg" data-ng-if="errors.email.has" ng-cloak>@{{errors.email.message}}</p>
              </div>


              <div class="input-group password-group" data-ng-class="{'has-error': errors.password.has}">
                <label>Password</label>

                <div class="password-wrapper">
                  <input type="@{{ loginCtrl.showPassword ? 'text' : 'password' }}" class="form-control"
                    ng-model="loginCtrl.authData.password" placeholder="Enter Password">

                  <span class="password-eye" ng-click="loginCtrl.showPassword = !loginCtrl.showPassword">
                    <i class="fa" ng-class="loginCtrl.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                  </span>
                </div>
              </div>


              <div class="align-right">
                <a title="{{trans('user::auth.login.forgot_password')}}" href="{{url('admin/auth/forgot-password')}}"
                  class="forgot_link">{{trans('user::auth.login.forgot_password')}}
                </a>
              </div>

              <button title="{{trans('user::auth.login.signin')}}"
                class="signin-btn">{{trans('user::auth.login.signin')}}</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection

@section('scripts')
  <script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
  <script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
  <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
  <script src="{{asset('adminview/assets/js/login/login.js')}}"></script>
  <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
  <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
@endsection