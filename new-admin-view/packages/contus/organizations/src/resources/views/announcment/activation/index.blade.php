@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')

    <style>
        .add-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5%;
            width: 20%;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            position: absolute;
            width: 100%;
            padding: 1 10px;
            top: -7px;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div data-ng-controller="AncActivationController as ancActivationCtrl">
        <div class="" id="dashboard-page">
            <div class="page-heading flexbox align-items-center flex-wrap">
                <h4>{{ __('organizations::index.organization') }}</h4>
            </div>

            @include('base::layouts.subnav')

            <br><br>

            @include('organizations::announcment.nav-tabs')

            <br> <br>

            <div class="contentpanel">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div id="home" class="tab-pane fade in active"><br>
                    <div class="row">
                        <div class="mx-auto" style="width:70%; margin-left: 10rem;">
                            <form name="ancAvtivationForm" id="ancAvtivationForm" method="POST" data-base-validator
                                data-ng-submit="ancActivationCtrl.saveAncActivation($event)" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                @include('base::partials.errors')

                                {{-- <input type="text" hidden id="org-id" name="id" value="{{ Request::url() }}"> --}}

                                <!-- Announcement Activation Subject -->
                                <div class="form-group row" data-ng-class="{'has-error': errors.name.has}">
                                    <label for="name" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>
                                            {{ trans('organizations::index.anc_subject') }}<span class="required">*</span>:
                                        </strong>
                                    </label>
                                    <div class="form-input col-sm-10">
                                        <input type="text" name="subject"
                                            data-ng-model="ancActivationCtrl.ancActivationData.subject" class="form-control"
                                            placeholder="{{ trans('organizations::index.anc_subject_hldr') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;" />
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.subject.has">@{{ errors.subject.message }}</p>
                                </div>

                                <!-- Announcement Activation message -->
                                <div class="form-group row" data-ng-class="{'has-error': errors.name.has}">
                                    <label for="name" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        <strong>
                                            {{ trans('organizations::index.anc_message') }}<span class="required">*</span>:
                                        </strong>
                                    </label>
                                    <div class="form-input col-sm-10">
                                        <textarea type="text" rows="4" cols="50" name="message"
                                            data-ng-model="ancActivationCtrl.ancActivationData.message" class="form-control"
                                            placeholder="{{ trans('organizations::index.anc_message_hldr') }}"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.message.has">@{{ errors.message.message }}</p>
                                </div>

                                <!-- Announcement Activation agree or not -->
                                <div class="form-group row" data-ng-class="{'has-error': errors.name.has}">
                                    <label for="name" class="col-sm-2 control-label"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                    </label>
                                    <div class="form-input col-sm-10" style="display: flex; justify-content: space-around">
                                        <div>
                                            <input type="radio" name="activation_agree" id="agree"
                                                value="Agree TOA or Cancel"
                                                data-ng-model="ancActivationCtrl.ancActivationData.activation_agree" />
                                            <label for="agree">Agree TOA or Cancel</label><br>
                                        </div>
                                        <div>
                                            <input type="radio" name="activation_agree" id="okclose" value="Ok to Close"
                                                data-ng-model="ancActivationCtrl.ancActivationData.activation_agree" />
                                            <label for="okclose">Ok to Close</label><br>
                                        </div>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.activation_agree.has">
                                        @{{ errors.activation_agree.message }}
                                    </p>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group text-center">
                                    <button type="submit" class="button button-blue" id="ancActivationadd">
                                        <strong>Save</strong>
                                    </button>&nbsp;&nbsp;
                                    <button type="button" class="btn btn-default"
                                        ng-click="ancActivationCtrl.cancelAncAvtivation($event)">
                                        <strong>Cancel</strong>
                                    </button>
                                </div>

                                {{-- <div class="bottom-button text-right flexbox align-items-center">
                                    <input type="button" value="{{ trans('base::general.cancel') }}"
                                        data-ng-click="ancActivationCtrl.closeSubscriptionEdit()" name="cancel"
                                        class="save" />
                                    <input type="submit" value="{{ trans('base::general.save') }}" name="submit"
                                        class="publish-now" />
                                </div> --}}
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/organization/announcment/activation.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection