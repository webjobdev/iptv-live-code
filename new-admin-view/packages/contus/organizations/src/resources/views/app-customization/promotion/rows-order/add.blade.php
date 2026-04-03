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

    /* .filter-wrapper select {
        border: 2px solid rgba(128, 130, 133, 0.36);
        border-radius: 20px;
        padding: 5px 9px;
        height: auto;
    } */

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
        border-radius: 10px;
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
        /* display: flex; */
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

    svg {
        margin-right: 10px;
    }

    /* Existing styles */
    .poster-options {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .options {
        display: flex;
        gap: 20px;
        justify-content: flex-start;
    }

    .option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        color: #888;
    }

    .option svg {
        fill: #888;
        transition: fill 0.3s ease;
        margin-bottom: 5px;
    }

    .horizontal {
        width: 15rem;
        height: 8.5rem;
    }

    .vertical {
        height: 13rem;
        width: 9rem;
    }

    .option input[type="radio"] {
        display: none;
    }

    /* Styles for the CHECKED state */
    .option .horizontal input[type="radio"]:checked+label {
        border-color: #00ACCD;
        color: #000;
    }

    .option input[type="radio"]:checked+label svg {
        fill: #000;
    }

    /* Checkmark icon styles */
    .option .checkmark {
        position: absolute;
        top: -12px;
        right: 0px;
        background-color: #00ACCD;
        border-radius: 50%;
        color: white;
        width: 24px;
        height: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        font-weight: bold;
        visibility: hidden;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease;
    }

    /* Show checkmark when radio is checked */
    .option input[type="radio"]:checked+label .checkmark {
        visibility: visible;
        opacity: 1;
        transform: scale(1);

    }

    /* Adjust label styling so content is centered properly */
    .option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        cursor: pointer;
        transition: color 0.3s ease, border-color 0.3s ease;
        position: relative;
    }

    /* Remove default color for checked label from previous iteration as we now set explicit colors */
    .option input[type="radio"]:checked+label {
        color: initial;
    }

    .poster-type,
    .poster-size {
        width: 45%;
    }

    .small {
        width: 5rem;
        height: 6.8rem;
    }

    .medium {
        height: 10rem;
        width: 7rem;
    }

    .large {
        width: 8rem;
        height: 13rem;
    }

    /* Responsive CSS */
    @media (max-width: 1024px) {
        .poster-options {
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .poster-type,
        .poster-size {
            width: 48%;
        }

        .option {
            font-size: 13px;
            padding: 8px;
        }

        .horizontal {
            width: 12rem;
            height: 7rem;
        }

        .vertical {
            width: 7rem;
            height: 11rem;
        }
    }

    @media (max-width: 768px) {
        .poster-options {
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .poster-type,
        .poster-size {
            width: 100%;
        }

        .option {
            font-size: 12px;
            padding: 6px;
        }

        .horizontal {
            width: 10rem;
            height: 6rem;
        }

        .vertical {
            width: 6rem;
            height: 9rem;
        }

        .small {
            width: 4rem;
            height: 5.5rem;
        }

        .medium {
            width: 5.5rem;
            height: 8rem;
        }

        .large {
            width: 6.5rem;
            height: 10rem;
        }
    }

    @media (max-width: 480px) {
        .poster-options {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .option {
            width: 100%;
            font-size: 11px;
            padding: 5px;
        }

        .horizontal {
            width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        .vertical {
            width: 60%;
            aspect-ratio: 3 / 4;
        }

        .small,
        .medium,
        .large {
            width: 50%;
            height: auto;
            aspect-ratio: 2 / 3;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            font-size: 13px;
        }
    }

    .badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .badge {
        font-size: 11px;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
        background: #eee;
        color: #555;
    }

    .badge.default {
        background: #f2f2f2;
        color: #555;
    }

    .badge.movies {
        background: #f3e8ff;
        color: #7b2cbf;
    }

    .badge.series {
        background: #e0f7ff;
        color: #0077b6;
    }

    .badge.events {
        background: #eafbea;
        color: #2e7d32;
    }

    .badge.channel {
        background: #eafbea;
        color: rgba(56, 223, 214, 0.8);
    }

    .item-box {
        /* border: 1px solid #ddd; */
        padding: 8px;
        /* margin: 5px 0; */
        /* border-radius: 4px; */
        /* background-color: #f9f9f9; */
        /* cursor: move; */
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
<div data-ng-controller="RowOrderController as ROCtrl">
    <div class="page-heading flexbox align-items-center flex-wrap">
        <h4>
            Add Rows Order
        </h4>
    </div>

    <div class="contentpanel">
        <div class="form-page">
            <form method="post" data-base-validator enctype="multipart/form-data" id="channelSetForm">
                {!! csrf_field() !!}
                <!-- ==========***********========== -->
                <!-- drag & drop code start -->
                <!-- ==========***********========== -->
                <p class="text-center fs-4 fw-bold" style="margin-bottom: 20px; font-size: 1.5rem; font-weight: 900;">
                    Assign rows for Home Page
                </p>

                <p class="text-center" style="margin-bottom: 20px; margin-top: 20px;">
                    Please select Rows you want to assign to this Home Page
                </p>

                <div class="row" style="margin-bottom: 20px;">
                    <div class="justify-content-center mx-auto filter-wrapper">
                        <div class="row">
                            <!-- Available Rows -->
                            <div class="col-md-6">
                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <h5>Available Rows</h5>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 5px;">
                                        <input type="text" id="searchAvailable" class="form-control search-box"
                                            placeholder="Search Rows" style="border-radius: 4px;">
                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 5px;">
                                        <select class="form-control select2_custom_ddl"
                                            ng-model="ROCtrl.filterAvailableType" id="AvailableType"
                                            data-ng-options="item.type as item.type for item in uniqueTypes"
                                            data-jquery="select2_custom_ddl" myPlaceholder="Type" style="width: 100%;">
                                            <option value="">All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="scroll-box" id="availableBundles">
                                    <div class="channel-item" draggable="true" ng-repeat="item in ROCtrl.allBundles"
                                        data-id="@{{ item.id }}" data-type="@{{ item.type }}">
                                        <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                        <div class="channel-info">
                                            <i class="glyphicon glyphicon-blackboard"></i>
                                            @{{ item.channel_name || item.title || item.name }}
                                            <div class="item-box" ng-if="item.type === 'channel'">
                                                <span class="badge channel">CHANNEL</span>
                                            </div>

                                            <div class="item-box" ng-if="item.type === 'vod'">
                                                <span class="badge movies">VOD</span>
                                            </div>

                                            <div class="item-box" ng-if="item.type === 'liveevent'">
                                                <span class="badge events">EVENT</span>
                                            </div>

                                            <div class="item-box" ng-if="item.type === 'tvshow'">
                                                <span class="badge series">Series</span>
                                            </div>

                                            <div class="item-box" ng-if="item.type === 'radio'">
                                                <span class="badge default">Other</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Assigned Rows -->
                            <div class="col-md-6">
                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <h5>Assigned Rows</h5>
                                </div>

                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 5px;">
                                        <input type="text" id="searchAdded" class="form-control search-box"
                                            placeholder="Search Rows" style="border-radius: 4px;">
                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 5px;">
                                        <select class="form-control select2_custom_ddl"
                                            ng-model="ROCtrl.filterAssignedType" id="AssignedType"
                                            data-ng-options="item.type as item.type for item in uniqueTypes"
                                            data-jquery="select2_custom_ddl" myPlaceholder="Type" style="width: 100%;">
                                            <option value="">All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="scroll-box" id="addedBundles" style="min-height: 339px;"
                                    ng-model="rows.assigne_row">
                                    <div class="drop-zone">DROP HERE</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==========***********========== -->
                <!-- drag & drop code end -->
                <!-- ==========***********========== -->

                <!-- ==========***********========== -->
                <!-- poster code start -->
                <!-- ==========***********========== -->
                <div class="row">
                    <div class="justify-content-center mx-auto filter-wrapper">
                        <div class="panel-group" id="accordian-content-set" role="tablist" aria-multiselectable="false"
                            style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;">
                            <div class="panel panel-default" style="border-radius: 5px;">
                                <div class="panel-heading d-flex" role="tab" id="heading-content-set">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                                        href="#collapse-content-set" aria-expanded="false"
                                        aria-controls="collapse-content-set" class="collapsed"
                                        style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18,15V5a3,3,0,0,0-3-3H5A3,3,0,0,0,2,5V15a3,3,0,0,0,3,3H15A3,3,0,0,0,18,15ZM4,5A1,1,0,0,1,5,4H15a1,1,0,0,1,1,1V9.36L14.92,8.27a2.56,2.56,0,0,0-1.81-.75h0a2.58,2.58,0,0,0-1.81.75l-.91.91-.81-.81a2.93,2.93,0,0,0-4.11,0L4,9.85Zm.12,10.45A.94.94,0,0,1,4,15V12.67L6.88,9.79a.91.91,0,0,1,1.29,0L9,10.6Zm8.6-5.76a.52.52,0,0,1,.39-.17h0a.52.52,0,0,1,.39.17L16,12.18V15a1,1,0,0,1-1,1H6.4ZM21,6a1,1,0,0,0-1,1V17a3,3,0,0,1-3,3H7a1,1,0,0,0,0,2H17a5,5,0,0,0,5-5V7A1,1,0,0,0,21,6Z" />
                                        </svg>
                                        <label
                                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0; margin-top: 0px;">
                                            Poster
                                        </label>
                                        <i class="arrow-icon fa fa-chevron-down"
                                            style="transition: transform 0.3s;"></i>
                                    </a>
                                </div>
                            </div>

                            <div id="collapse-content-set" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading-content-set">
                                <div class="panel-body">
                                    <div class="poster-options">
                                        <!-- Posters type -->
                                        <div class="poster-type">
                                            <label style="margin: 0px 0px 10px 0px;">Posters Type</label>
                                            <div class="options">
                                                <div class="option horizontal">
                                                    <input type="radio" name="poster_type" id="horizontal"
                                                        value="horizontal" ng-model="rows.poster_type">
                                                    <label for="horizontal">
                                                        <svg fill="#000000" width="20px" height="20px"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M18,15V5a3,3,0,0,0-3-3H5A3,3,0,0,0,2,5V15a3,3,0,0,0,3,3H15A3,3,0,0,0,18,15ZM4,5A1,1,0,0,1,5,4H15a1,1,0,0,1,1,1V9.36L14.92,8.27a2.56,2.56,0,0,0-1.81-.75h0a2.58,2.58,0,0,0-1.81.75l-.91.91-.81-.81a2.93,2.93,0,0,0-4.11,0L4,9.85Zm.12,10.45A.94.94,0,0,1,4,15V12.67L6.88,9.79a.91.91,0,0,1,1.29,0L9,10.6Zm8.6-5.76a.52.52,0,0,1,.39-.17h0a.52.52,0,0,1,.39.17L16,12.18V15a1,1,0,0,1-1,1H6.4ZM21,6a1,1,0,0,0-1,1V17a3,3,0,0,1-3,3H7a1,1,0,0,0,0,2H17a5,5,0,0,0,5-5V7A1,1,0,0,0,21,6Z" />
                                                        </svg>
                                                        Horizontal
                                                        <span class="checkmark">✓</span>
                                                    </label>
                                                </div>
                                                <div class="option vertical">
                                                    <input type="radio" name="poster_type" id="vertical"
                                                        value="vertical" ng-model="rows.poster_type">
                                                    <label for="vertical">
                                                        <svg fill="#000000" width="20px" height="20px"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M18,15V5a3,3,0,0,0-3-3H5A3,3,0,0,0,2,5V15a3,3,0,0,0,3,3H15A3,3,0,0,0,18,15ZM4,5A1,1,0,0,1,5,4H15a1,1,0,0,1,1,1V9.36L14.92,8.27a2.56,2.56,0,0,0-1.81-.75h0a2.58,2.58,0,0,0-1.81.75l-.91.91-.81-.81a2.93,2.93,0,0,0-4.11,0L4,9.85Zm.12,10.45A.94.94,0,0,1,4,15V12.67L6.88,9.79a.91.91,0,0,1,1.29,0L9,10.6Zm8.6-5.76a.52.52,0,0,1,.39-.17h0a.52.52,0,0,1,.39.17L16,12.18V15a1,1,0,0,1-1,1H6.4ZM21,6a1,1,0,0,0-1,1V17a3,3,0,0,1-3,3H7a1,1,0,0,0,0,2H17a5,5,0,0,0,5-5V7A1,1,0,0,0,21,6Z" />
                                                        </svg>
                                                        Vertical
                                                        <span class="checkmark">✓</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Posters Size -->
                                        <div class="poster-size">
                                            <label style="margin: 0px 0px 10px 0px;">Posters Size</label>
                                            <div class="options">
                                                <div class="option small">
                                                    <input type="radio" name="poster_size" id="size-s" value="samll"
                                                        ng-model="rows.poster_size">
                                                    <label for="size-s">
                                                        S
                                                        <span class="checkmark">✓</span>
                                                    </label>
                                                </div>
                                                <div class="option medium">
                                                    <input type="radio" name="poster_size" id="size-m" value="medium"
                                                        ng-model="rows.poster_size">
                                                    <label for="size-m">
                                                        M
                                                        <span class="checkmark">✓</span>
                                                    </label>
                                                </div>
                                                <div class="option large">
                                                    <input type="radio" name="poster_size" id="size-l" value="large"
                                                        ng-model="rows.poster_size">
                                                    <label for="size-l">
                                                        L
                                                        <span class="checkmark">✓</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ==========***********========== -->
                <!-- poster code end -->
                <!-- ==========***********========== -->

                <!-- ==========***********========== -->
                <!-- background code start -->
                <!-- ==========***********========== -->
                <div class="row">
                    <div class="justify-content-center mx-auto filter-wrapper">
                        <div class="panel-group" id="background-accordian" role="tablist" aria-multiselectable="false"
                            style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;">
                            <div class="panel panel-default" style="border-radius: 5px;">
                                <div class="panel-heading d-flex" role="tab" id="heading-content-set">
                                    <a role="button" data-toggle="collapse" data-parent="#background-accordian"
                                        href="#background-collapse-row" aria-expanded="false"
                                        aria-controls="background-collapse-row" class="collapsed"
                                        style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                        <svg width="20px" height="20px" viewBox="0 0 16 16"
                                            xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8 1.003a7 7 0 0 0-7 7v.43c.09 1.51 1.91 1.79 3 .7a1.87 1.87 0 0 1 2.64 2.64c-1.1 1.16-.79 3.07.8 3.2h.6a7 7 0 1 0 0-14l-.04.03zm0 13h-.52a.58.58 0 0 1-.36-.14.56.56 0 0 1-.15-.3 1.24 1.24 0 0 1 .35-1.08 2.87 2.87 0 0 0 0-4 2.87 2.87 0 0 0-4.06 0 1 1 0 0 1-.9.34.41.41 0 0 1-.22-.12.42.42 0 0 1-.1-.29v-.37a6 6 0 1 1 6 6l-.04-.04zM9 3.997a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 7.007a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-7-5a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm7-1a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM13 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                        </svg>
                                        <label
                                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0; margin-top: 0px;">
                                            Background
                                        </label>
                                        <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;">
                                        </i>
                                    </a>
                                </div>
                            </div>

                            <div id="background-collapse-row" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading-content-set">
                                <div class="panel-body">
                                    @include('organizations::app-customization.common.BackgroundNav')

                                    <div ng-if="ROCtrl.btnNo == 0">
                                        @include('organizations::app-customization.promotion.rows-order.background.image')
                                    </div>

                                    <div ng-if="ROCtrl.btnNo == 1">
                                        @include('organizations::app-customization.promotion.rows-order.background.gradient')
                                    </div>

                                    <div ng-if="ROCtrl.btnNo == 2">
                                        <h1>Not Set</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ==========***********========== -->
                <!-- background code end -->
                <!-- ==========***********========== -->

                <!-- ==========***********========== -->
                <!-- info code start -->
                <!-- ==========***********========== -->
                <div class="row">
                    <div class="justify-content-center mx-auto filter-wrapper">
                        <div class="panel-group" id="info-accordian" role="tablist" aria-multiselectable="false"
                            style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;">
                            <div class="panel panel-default" style="border-radius: 5px;">
                                <div class="panel-heading d-flex" role="tab" id="heading-content-set">
                                    <a role="button" data-toggle="collapse" data-parent="#info-accordian"
                                        href="#info-collapse-row" aria-expanded="false"
                                        aria-controls="info-collapse-row" class="collapsed"
                                        style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                        <svg fill="#000000" width="20px" height="20px" viewBox="-6.5 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m8.436.006c.051-.004.111-.007.172-.007 1.237 0 2.239 1.003 2.239 2.239 0 .041-.001.081-.003.122v-.006c-.079 1.679-1.46 3.01-3.151 3.01-.022 0-.043 0-.065-.001h.003c-.069.008-.15.012-.231.012-1.188 0-2.151-.963-2.151-2.151 0-.088.005-.174.015-.259l-.001.01c.063-1.655 1.419-2.972 3.084-2.972.031 0 .062 0 .093.001h-.005zm-4.947 23.994c-1.268 0-2.199-.783-1.311-4.226l1.456-6.108c.254-.978.295-1.369 0-1.369-1.141.293-2.142.752-3.035 1.359l.033-.021-.633-1.057c3.086-2.622 6.638-4.159 8.158-4.159 1.268 0 1.48 1.526.845 3.874l-1.666 6.421c-.296 1.135-.168 1.526.126 1.526 1.106-.256 2.069-.761 2.863-1.456l-.008.007.72.979c-3.004 3.052-6.281 4.232-7.549 4.232z" />
                                        </svg>
                                        <label
                                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0; margin-top: 0px;">
                                            Info
                                        </label>
                                        <i class="arrow-icon fa fa-chevron-down"
                                            style="transition: transform 0.3s;"></i>
                                    </a>
                                </div>
                            </div>

                            <div id="info-collapse-row" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading-content-set">
                                <div class="panel-body">
                                    <!-- original name -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            Original Name:
                                        </label>
                                        <div class="col-sm-10 m-auto">
                                            <label>
                                                @{{ rows.title }}
                                            </label>
                                        </div>
                                    </div>

                                    <!-- title -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            Title<span class="required">*</span>:
                                        </label>
                                        <div class="col-sm-10 m-auto">
                                            <input type="text" class="form-control" name="title"
                                                placeholder="Enter Title" ng-model="rows.title"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                            <p class="error-msg">
                                                @{{ errors.title.message }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            description:
                                        </label>
                                        <div class="col-sm-10 m-auto">
                                            <textarea name="w3review" rows="4" class="form-control" cols="50"
                                                name="description" placeholder="Enter Description"
                                                ng-model="rows.description"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ==========***********========== -->
                <!-- info code end -->
                <!-- ==========***********========== -->

                <!-- button code -->
                <div class="bottom-button text-center"
                    style="border-bottom: 0px; justify-content: center; border-top: 0px; box-shadow: none;">
                    <button id="channelEditFormSubmit" data-ng-click="ROCtrl.save($event, vodSelectedVideo.id)"
                        class="publish-now">
                        Save
                    </button>

                    <button type="submit" value="Save" ng-if="editPage" class="button button-blue"
                        ng-click="ROCtrl.updatedata($event)">
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

<!-- photo open model code -->
@endsection

@section('scripts')
<script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/organization/app-customization/row-order.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection