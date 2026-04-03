<style>
    /* General Styles */
    .filter-wrapper {
        width: 70%;
        margin: 0 auto;
        margin-left: 10rem;
        /* desktop spacing */
    }

    .filter-wrapper label {
        font-size: 14px;
        color: #000;
        margin-top: 10px;
        font-weight: bold;
    }

    .filter-wrapper select {
        border: 2px solid rgba(128, 130, 133, 0.36);
        border-radius: 20px;
        padding: 5px 9px;
        height: auto;
    }

    .price-input-wrapper {
        position: relative;
        display: inline-block;
    }

    .price-input {
        padding: 8px 40px 8px 12px;
        border-radius: 30px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 120px;
        box-sizing: border-box;
    }

    .currency-label {
        position: absolute;
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 14px;
        pointer-events: none;
    }

    /* Common card style */
    .channel-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 8px 14px;
        margin-bottom: 8px;
        font-size: 14px;
        cursor: move;
        transition: box-shadow 0.2s ease;
    }

    .channel-item:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Left drag handle */
    .channel-drag {
        color: #999;
        margin-right: 10px;
        cursor: grab;
        flex-shrink: 0;
    }

    /* Channel name with icon */
    .channel-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 8px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 500;
        color: #333;
    }

    /* Action button (right side) */
    .channel-action {
        color: #666;
        cursor: pointer;
        flex-shrink: 0;
        font-size: 16px;
        transition: color 0.2s;
    }

    .channel-action:hover {
        color: #e74c3c;
    }

    /* Scrollable container */
    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }

    /* Drop area */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 8px;
    }

    /* Medium Screens (tablets) */
    @media (max-width: 992px) {
        .filter-wrapper {
            width: 90%;
            margin-left: 2rem;
            /* reduce left margin */
        }

        .filter-wrapper label {
            font-size: 13px;
        }
    }

    /* Small Screens (mobile) */
    @media (max-width: 576px) {
        .filter-wrapper {
            width: 100%;
            margin-left: 0;
            padding: 0 10px;
        }

        .filter-wrapper .form-group {
            flex-direction: column;
        }

        .filter-wrapper label {
            margin-bottom: 8px;
            text-align: left;
        }

        .filter-wrapper select {
            width: 100%;
        }
    }
</style>

@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link rel="stylesheet" href="{{asset('adminview/assets/css/ng-tags-input.min.css')}}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="SettingController as settCtrl">

        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4 data-ng-if="!settCtrl.sett.id">
                Create Setting
            </h4>
            <h4 data-ng-if="settCtrl.sett.id">
                Edit Setting
            </h4>
        </div>

        <div class="contentpanel">
            <div class="form-page">
                <form method="post" id="" data-base-validator enctype="multipart/form-data"
                    data-ng-submit="settCtrl.save($event, settCtrl.sett.id)">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- time zone -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Time Zone:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <select class="form-control mb10 select2_custom_ddl" ng-model="settCtrl.sett.time_zone"
                                        data-ng-options="time for time in settCtrl.timezone"
                                        data-jquery="select2_custom_ddl" myPlaceholder="Time Zone" name="time_zone">
                                        <option value="">-- Select Time Zone --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 d-flex align-items-center">
                                    <input class="form-check-input mr-2" type="checkbox"
                                        ng-model="settCtrl.sett.system_default" id="systemDefault">
                                    <label class="form-check-label mb-0" for="systemDefault">
                                        System Default
                                    </label>
                                </div>
                            </div>

                            <!-- pin code -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-3 control-label"
                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                    Pin Code:
                                </label>
                                <div class="col-sm-4 m-auto">
                                    <input type="text" class="form-control" name="pin_code" placeholder="Enter Pin Code"
                                        ng-model="settCtrl.sett.pin_code" ng-readonly="settCtrl.sett.random"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                                <div class="col-sm-4 d-flex align-items-center">
                                    <input class="form-check-input mr-2" type="checkbox" ng-model="settCtrl.sett.random"
                                        id="rendom" ng-change="settCtrl.AutoRandom()">
                                    <label class="form-check-label mb-0">Random</label>
                                </div>
                            </div>

                            <!-- screen saver -->
                            <div class="form-group row" style="margin-bottom: 15px; align-items: center;">
                                <label class="col-sm-3 control-label" style="font-size: 14px; color: #000;">
                                    Screen Server:
                                </label>

                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="screen_server" required
                                        placeholder="Enter Screen Sever" ng-model="settCtrl.sett.screen_server"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>

                                <!-- Active Toggle -->
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label class="control-label" style="font-size: 14px; color: #000;">Minutes:</label>
                                    <label class="switch mb-0">
                                        <input type="checkbox" ng-model="settCtrl.sett.minutes" name="minutes">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- System Default -->
                                <div class="col-sm-3 d-flex align-items-center">
                                    <input class="form-check-input mr-2" type="checkbox"
                                        ng-model="settCtrl.sett.ss_system_default" id="SsSystemDefault">
                                    <label class="form-check-label mb-0">
                                        System Default
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- STB Start Channel -->
                            <div class="form-group row" style="margin-bottom: 15px; align-items: center;">
                                <label class="col-sm-3 control-label" style="font-size: 14px; color: #000;">
                                    STB Start Channel:
                                </label>
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label class="switch mb-0" style="margin-top: 11px;">
                                        <input type="checkbox" ng-model="settCtrl.sett.stb_start_channel" name="is_active">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Conditional Section -->
                            <div class="form-group row" style="margin-bottom: 15px;"
                                ng-if="settCtrl.sett.stb_start_channel">
                                <label class="col-sm-3 control-label"></label>

                                <!-- First Select -->
                                <div class="col-sm-3">
                                    <select class="form-control mb10 select2_custom_ddl" ng-model="settCtrl.sett.channel_id"
                                        data-ng-options="chnl.id as chnl.channel_name for chnl in settCtrl.channelList"
                                        data-jquery="select2_custom_ddl" myPlaceholder="Channel Name" name="channel_id">
                                        <option value="">-- Select Channel Name --</option>
                                    </select>
                                </div>

                                <!-- Second Select -->
                                <div class="col-sm-3">
                                    <select class="form-control mb10 select2_custom_ddl"
                                        ng-model="settCtrl.sett.sorting_number"
                                        data-ng-options="chnl.sorting_number as (chnl.sorting_number || '') + ' (' + chnl.channel_name + ')' for chnl in settCtrl.channelList | filter:{sorting_number: '!null'}"
                                        data-jquery="select2_custom_ddl" myPlaceholder="Channel Number"
                                        name="sorting_number">
                                        <option value="">-- Select Channel Number --</option>
                                    </select>
                                </div>

                                <!-- Button -->
                                <div class="col-sm-3">
                                    <button class="button button-blue w-100">
                                        Set Start Channel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <!-- button code -->
                    <div class="bottom-button text-center"
                        style="border-bottom: 0px; justify-content: center; border-top: 0px; box-shadow: none;">
                        <button id="channelEditFormSubmit" class="publish-now">
                            Save
                        </button>

                        <button type="submit" value="Save" ng-if="editPage" class="button button-blue"
                            ng-click="setCtrl.updatedata($event)">
                            <strong>Update</strong>
                        </button>

                        <a class="save" href="{{ url()->previous() }}">
                            {{ __('video::videos.back') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/app-customization/setting.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection