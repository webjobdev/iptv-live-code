@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

<style>
    #copyIcon[data-show="true"]::after {
        content: attr(data-tooltip);
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        background: #000;
        color: #fff;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
    }

    .nav.nav-tabs {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 2px solid #ddd;
        padding-left: 0;
        margin-bottom: 1rem;
    }

    .nav.nav-tabs li {
        margin: 0;
        list-style: none;
    }

    .nav.nav-tabs li a {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 500;
        color: #000;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s ease-in-out;
        text-decoration: none;
    }

    .nav.nav-tabs li.active a,
    .nav.nav-tabs li a:hover {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-bottom: 2px solid #00ACCD;
        color: #00ACCD !important;
    }
</style>

@section('content')
    <div data-ng-controller="SubscriberController as subCtrl">
        <div class="dashboard-page" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>{{ __('organizations::index.subscriber') }}</h4>
            </div>

            @if(isset($nav_type) && $nav_type == 'organization')
                @include('organizations::layouts.subscribernav')
            @else
                @include('subscribers::layouts.subscribernav')
            @endif

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#home" role="tab" data-toggle="tab" style="color: black;">General Information</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade in active"><br>
                    <h1 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900;">General Information</h1>
                    <br>
                    <h3>Note that address will be inherited in activation when adding a credit card on file</h3>
                    <br>

                    <div class="row">
                        <div class="justify-content-center mx-auto" style="width:70%; margin-left: 10rem;">
                            <form method="POST" enctype="multipart/form-data" id="update_plan" data-base-validator
                                data-ng-submit="subCtrl.save($event)">

                                {!! csrf_field() !!}

                                <input type="hidden" id="target-id" name="id" value="{{ $target_id }}">
                                <input type="hidden" id="form-mode" value="{{ $mode }}">

                                <!-- Organization -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="organization_name" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ trans('subscribers::index.organization.organization') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input type="text" class="form-control" name="organization_name"
                                            ng-model="subCtrl.subscriber.organization_name"
                                            placeholder="{{trans('organizations::index.organization_placeholder')}}"
                                            id="organization_name" ng-disabled="true"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- Org Provider Id -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="provider_id" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Organization Provider Id<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-10 m-auto" style="position: relative;">
                                        <input type="text" class="form-control" name="provider_id"
                                            ng-model="subCtrl.subscriber.provider_id" placeholder="Enter Provider id"
                                            id="provider_id" ng-disabled="true"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 0px 0px 9px; height: auto;">
                                        <span class="glyphicon glyphicon-copy" onclick="copyProviderId()"
                                            data-tooltip="Copied!" id="copyIcon"
                                            style="position: absolute; right: 30px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px; color: #555;">
                                        </span>
                                    </div>
                                </div>

                                <!-- Account Number -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="account_number" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.account_number.account_number') }}*:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="account_number" id="account_number"
                                            ng-model="subCtrl.subscriber.account_number"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"
                                            placeholder="{{ trans('organizations::index.acc_number') }}">
                                        <p class="error-msg" data-ng-show="errors.account_number.has">Account number required</p>
                                    </div>
                                    <div class="col-sm-2" style="margin-top: 0.5rem;">
                                        <input type="checkbox" id="account_number_auto" onclick="generateAccountNumber()">
                                        <label for="account_number_auto"><strong>{{ __('subscribers::index.auto') }}</strong></label>
                                    </div>
                                </div>

                                <!-- Pin Code -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="pin_code" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.pin_code.pin_code') }}*:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control rounded-pill border-3" type="text" name="pin_code"
                                            ng-model="subCtrl.subscriber.pin_code"
                                            placeholder="{{trans('organizations::index.pin_code')}}" id="pin_code"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.pin_code.has">Pin code required</p>
                                    </div>
                                    <!-- <div class="col-sm-2" style="margin-top: 0.5rem;">
                                        <input type="checkbox" id="pin_code_auto" onclick="generatePinCode()">
                                        <label for="pin_code_auto"><strong>{{ __('subscribers::index.auto') }}</strong></label>
                                    </div> -->
                                </div>

                                <!-- Username -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="user_name" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.user_name.user_name') }}*:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control rounded-pill border-3" type="text" name="user_name"
                                            ng-model="subCtrl.subscriber.user_name"
                                            placeholder="{{trans('organizations::index.username')}}" id="user_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.user_name.has">Username required</p>
                                    </div>
                                    <div class="col-sm-2" style="margin-top: 0.5rem;">
                                        <input type="checkbox" id="username_auto" onclick="generateUsername()">
                                        <label for="username_auto"><strong>{{ __('subscribers::index.auto') }}</strong></label>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="password" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.password.password') }}:</label>
                                    <div class="col-sm-10">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="password" class="form-control" id="accessKeyInput" name="password"
                                                ng-model="subCtrl.subscriber.password" min="6"
                                                placeholder="{{ __('subscribers::index.password.name') }}"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; flex: 1;">
                                            <button type="button" class="btn btn-default" onclick="toggleAccessKey()"
                                                title="Show/Hide Access Key">
                                                <i class="glyphicon glyphicon-eye-open" id="toggleaccessIcon"></i>
                                            </button>
                                        </div>
                                        <p class="error-msg" data-ng-show="update_plan.password.$error.minlength || errors.password.has"
                                            style="margin-top: 5px;">Password must be at least 8 characters</p>
                                    </div>
                                </div>

                                <!-- First and Last Name -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="first_name" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.first_name.first_name') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="first_name"
                                            ng-model="subCtrl.subscriber.first_name"
                                            placeholder="{{trans('organizations::index.first_name')}}" id="first_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.first_name.has">First name required</p>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="last_name" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.last_name.last_name') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="last_name"
                                            ng-model="subCtrl.subscriber.last_name"
                                            placeholder="{{trans('organizations::index.last_name')}}" id="last_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.last_name.has">Last name required</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="email" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.email.email') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="email" name="email" required
                                            ng-model="subCtrl.subscriber.email"
                                            placeholder="{{trans('organizations::index.email')}}" id="email"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.email.has">Email required</p>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="phone_number" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.phone_number.phone_number') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select class="form-control rounded-pill border-3"
                                                    ng-model="subCtrl.subscriber.phone_number_code" name="phone_number_code"
                                                    ng-options="code.code as (code.code + ' (' + code.country + ')') for code in subCtrl.countryCodeList"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                    <option value="">{{ __('subscribers::index.phone_number.name') }}</option>
                                                </select>
                                                <p class="error-msg" data-ng-show="errors.phone_number_code.has">Phone number code required</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <input class="form-control rounded-pill border-3" type="tel"
                                                    name="phone_number" ng-model="subCtrl.subscriber.phone_number"
                                                    placeholder="{{trans('organizations::index.phone_number')}}"
                                                    id="phone_number"
                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                <p class="error-msg" data-ng-show="errors.phone_number.has">Phone number required</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address, City, Zip, Country, State -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="address" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.address.address') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <textarea class="form-control rounded-pill border-3" name="address"
                                            ng-model="subCtrl.subscriber.address" rows="4"
                                            placeholder="{{trans('organizations::index.address')}}" id="address"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                        <p class="error-msg" data-ng-show="errors.address.has">Address required</p>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="city" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.city.city') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="city" required
                                            ng-model="subCtrl.subscriber.city" placeholder="{{trans('organizations::index.city')}}"
                                            id="city"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.city.has">City required</p>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="zip_code" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.zip_code.zip_code') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="zip_code"
                                            ng-model="subCtrl.subscriber.zip_code"
                                            placeholder="{{trans('organizations::index.zip_code')}}" id="zip_code"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                        <p class="error-msg" data-ng-show="errors.zip_code.has">Zip code required</p>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="country" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.country.country') }}*:</label>
                                    <div class="col-sm-10 m-auto">
                                        <select name="country" id="country" class="form-control rounded-pill border-3"
                                            ng-model="subCtrl.subscriber.country"
                                            ng-options="country for country in subCtrl.countryList"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{trans('organizations::index.country')}}</option>
                                        </select>
                                        <p class="error-msg" data-ng-show="errors.country.has">Country required</p>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="state" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.state.state') }}:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="text" name="state"
                                            ng-model="subCtrl.subscriber.state"
                                            placeholder="{{trans('organizations::index.state')}}" id="state"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <!-- Language, DOB, Timezone -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="language" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.language.language') }}:</label>
                                    <div class="col-sm-10 m-auto">
                                        <select name="language" id="language" class="form-control rounded-pill border-3"
                                            ng-model="subCtrl.subscriber.language"
                                            ng-options="language.code as language.label for language in subCtrl.languages"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{trans('organizations::index.language')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="date_of_birth" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.date_of_birth.date_of_birth') }}:</label>
                                    <div class="col-sm-10 m-auto">
                                        <input class="form-control rounded-pill border-3" type="date" name="date_of_birth"
                                            ng-model="subCtrl.subscriber.date_of_birth"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label for="timezone" class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">{{ __('subscribers::index.time_zone.time_zone') }}:</label>
                                    <div class="col-sm-10 m-auto">
                                        <select name="timezone" id="timezone" class="form-control rounded-pill border-3"
                                            ng-model="subCtrl.subscriber.timezone"
                                            ng-options="timezone.value as timezone.value for timezone in subCtrl.timezoneList"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <option value="">{{ trans('organizations::index.timezone') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bottom-button text-center">
                                    <button type="submit" class="button button-blue" id="add_sub">
                                        <strong>Save</strong>
                                    </button>
                                    @if($mode == 'edit')
                                        &nbsp;&nbsp;
                                        <button type="button" class="button button-red" onclick="deleteSub({{ $target_id }})">
                                            <strong>Remove</strong>
                                        </button>
                                    @endif
                                    &nbsp;&nbsp;
                                    @if(isset($nav_type) && $nav_type == 'organization')
                                        <a class="button button-gray" href="{{ url()->previous() }}">
                                            {{ __('video::videos.back') }}
                                        </a>
                                    @else
                                        <button type="button" class="button button-gray" ng-click="subCtrl.cancel()">
                                            <strong>Cancel</strong>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copyProviderId() {
            var input = document.getElementById("provider_id");
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value)
                .then(() => {
                    const icon = document.getElementById("copyIcon");
                    icon.setAttribute("data-show", "true");
                    setTimeout(() => { icon.removeAttribute("data-show"); }, 1500);
                })
                .catch(() => alert("Copy failed!"));
        }

        @if($mode == 'edit')
            window.deleteorgbtn = "{{ route('subscribers.destroy') }}";
        @else
            window.deleteorhbtn = "{{ route('organizations.index') }}";
        @endif
        window.cancelorgbtn = "{{ $mode == 'edit' ? route('subscribers.index') : route('organizations.index') }}";
    </script>

    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/subscribers/subscriber_controller.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
