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
        /* justify-content: space-between; */
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

    .box-drop-zone {
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

    .group-box {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 20px;
        background-color: #f9f9f9;
    }

    .group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        padding: 5px 0;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        margin-bottom: 5px;
    }

    .group-item button {
        padding: 4px 10px;
        border-radius: 4px;
        border: 1px solid #007bff;
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    .group-header button {
        margin-left: 5px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .edit-btn,
    .delete-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
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
    <div data-ng-controller="ChannelSettingController as chnlsetCtrl">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>
                View Channel Listing
            </h4>
        </div>

        <hr>

        <div class="contentpanel product order_list">
            @include('base::partials.errors')
            <div class="response-msg"></div>
        </div>

        <div class="contentpanel">
            <div class="form-page">
                <form method="post" data-base-validator enctype="multipart/form-data" id="channel-listing-form">
                    {!! csrf_field() !!}

                    <input type="hidden" id="chnl_listing_id" name="id" value="{{ request()->id }}">

                    <div class="row">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <!-- Channel Listing -->
                            <div class="form-group row" style="margin-bottom: 15px;">
                                <label class="col-sm-2 fw-bold col-form-label" style="font-size: 14px; color: #000;">
                                    Channel Listing:
                                </label>
                                <div class="col-sm-6">
                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="channel_listing" value="sequence_list"
                                            ng-model="chnlsetCtrl.chnlset.channel_listing">
                                        Sequence List
                                    </label>

                                    <label class="radio-inline me-3" style="vertical-align: unset;">
                                        <input type="radio" name="channel_listing" value="group_list"
                                            ng-model="chnlsetCtrl.chnlset.channel_listing">
                                        Group List
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->
                    <!-- Sequence List code start  -->
                    <div class="row" ng-show="chnlsetCtrl.chnlset.channel_listing == 'sequence_list'">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="row">
                                <!-- Available Channels -->
                                <div class="col-sm-6">
                                    <div class="page-heading flexbox align-items-center flex-wrap">
                                        <h5>Available Channels</h5>
                                    </div>
                                    <input type="text" id="searchAvailableChannels" class="form-control search-box"
                                        placeholder="Search Available Channels" style="margin-bottom: 10px;">

                                    <div class="scroll-box" id="availableChannelList">
                                        <div class="channel-item" draggable="false"
                                            data-ng-repeat="channel in chnlsetCtrl.channlset" data-id="@{{ channel.id }}">
                                            <div class="channel-info">
                                                @{{ channel.id }}
                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                @{{ channel.channel_name }}
                                            </div>
                                            <!-- <div class="column edit_table_icon tooltip-parent">
                                                <a class="table_action"
                                                    href="{{url('admin/channel/channel-details-edit')}}/@{{ encodeId(channel.id) }}">
                                                    <svg viewBox="0 0 12 11" width="12px" height="11px">
                                                        <g>
                                                            <path
                                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                fill="#454545" />
                                                        </g>
                                                    </svg>
                                                </a>
                                                <span class="tooltip_title">Edit Channel</span>
                                            </div> -->
                                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assigned Channels -->
                                <div class="col-sm-6">

                                    <div class="page-heading flexbox align-items-center flex-wrap">
                                        <h5>Assigned Channels</h5>
                                    </div>

                                    <input type="text" id="searchAssignedChannels" class="form-control search-box"
                                        placeholder="Search Assigned Channels" style="margin-bottom: 10px;">

                                    <div class="scroll-box">
                                        <div class="channel-item" draggable="false"
                                            data-ng-repeat="channel in chnlsetCtrl.chnlset.selectedBundles"
                                            data-id="@{{ channel.id }}">
                                            <div class="channel-info">
                                                @{{ channel.id }}
                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                @{{ channel.channel_name }}
                                            </div>
                                            <!-- <div class="column edit_table_icon tooltip-parent">
                                                <a class="table_action"
                                                    href="{{url('admin/channel/channel-details-edit')}}/@{{ encodeId(channel.id) }}">
                                                    <svg viewBox="0 0 12 11" width="12px" height="11px">
                                                        <g>
                                                            <path
                                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                fill="#454545" />
                                                        </g>
                                                    </svg>
                                                </a>
                                                <span class="tooltip_title">Edit Channel</span>
                                            </div> -->
                                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                        </div>

                                        <div id="assignedChannelList" style="min-height: 145px;">
                                            <div class="drop-zone">DROP HERE</div>
                                        </div>
                                    </div>

                                    <!-- <div class="scroll-box" id="assignedChannelList" style="min-height: 339px;"
                                                                                                ng-model="channlset.chnlset.sequence_assigned_channels">
                                                                                                <div class="drop-zone">DROP HERE</div>
                                                                                            </div> -->
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- <pre>@{{ chnlsetCtrl.chnlset.selecstedBundles | json }}</pre> -->
                    <!-- Sequence List code end  -->

                    <!-- ==========***********========== -->
                    <!-- ==========***********========== -->

                    <!-- Group List start -->
                    <div class="row" ng-show="chnlsetCtrl.chnlset.channel_listing == 'group_list'">
                        <div class="justify-content-center mx-auto filter-wrapper">
                            <div class="col-md-6">
                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <h5>Available Channels</h5>
                                </div>
                                <input type="text" id="searchAvailable" class="form-control search-box"
                                    placeholder="Search Channels" style="margin-bottom: 10px;">

                                <div class="scroll-box" id="availableChannels">
                                    <div class="channel-item" draggable="false"
                                        data-ng-repeat="channel in chnlsetCtrl.channlset" data-id="@{{ channel.id }}">
                                        <div class="channel-info">
                                            @{{ channel.id }}
                                            <i class="glyphicon glyphicon-blackboard"></i>
                                            @{{ channel.channel_name }}
                                        </div>
                                        <!-- <div class="column edit_table_icon tooltip-parent">
                                            <a class="table_action"
                                                href="{{url('admin/channel/channel-details-edit')}}/@{{ encodeId(channel.id) }}">
                                                <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                    <g>
                                                        <path
                                                            d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                            fill="#454545" />
                                                    </g>
                                                </svg>
                                            </a>
                                            <span class="tooltip_title">Edit Channel</span>
                                        </div> -->
                                        <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Channels -->
                            <div class="col-md-6">
                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <h5>Assigned Channels</h5>
                                </div>

                                <input type="text" id="searchAdded" class="form-control search-box"
                                    placeholder="Search Channels" style="margin-bottom: 10px;">

                                <div class="scroll-box">
                                    <div class="group-box"
                                        data-ng-repeat="channel in chnlsetCtrl.chnlset.GroupSelectedBundles"
                                        data-id="@{{ channel.id }}">

                                        <!-- Header -->
                                        <div class="group-header">
                                            <span>@{{channel.from}}–@{{channel.to}} @{{channel.group_id}}</span>
                                            <div class="header-actions">
                                                <button type="button" class="edit-btn">✏️</button>
                                                <button type="button" class="delete-btn">🗑️</button>
                                            </div>
                                        </div>

                                        <!-- Drop Area -->
                                        <div class="channel-item" data-ng-repeat="show in channel.channel_list">
                                            <div class="channel-info">
                                                @{{ show.id }}
                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                @{{ show.channel_name }}
                                            </div>
                                            <!-- <div class="column edit_table_icon tooltip-parent">
                                                <a class="table_action"
                                                    href="{{url('admin/channel/channel-details-edit')}}/@{{ encodeId(channel.id) }}">
                                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                        <g>
                                                            <path
                                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                fill="#454545" />
                                                        </g>
                                                    </svg>
                                                </a>
                                                <span class="tooltip_title">Edit Channel</span>
                                            </div> -->
                                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                        </div>
                                        <div class="box-drop-zone">DROP HERE</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <pre>@{{ chnlsetCtrl.chnlset.GroupSelectedBundles | json }}</pre> -->


                    <!-- Group List end -->

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Number Group</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        style="margin-top: -20px">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- number -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            Numbers<span class="required">*</span>:
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <!-- From -->
                                                <div class="col-sm-5">
                                                    <label style="font-size: 14px; color: #000;">From</label>
                                                    <input type="number" class="form-control" placeholder="e.g. 1"
                                                        id="fromInput" ng-model="channlset.chnlset.form"
                                                        style="border:2px solid rgba(128,130,133,0.36); border-radius:20px; padding:0 9px; height:auto;">
                                                </div>
                                                <!-- To -->
                                                <div class="col-sm-5">
                                                    <label style="font-size: 14px; color: #000;">To</label>
                                                    <input type="number" class="form-control" placeholder="e.g. 55"
                                                        id="toInput" ng-model="channlset.chnlset.to"
                                                        style="border:2px solid rgba(128,130,133,0.36); border-radius:20px; padding:0 9px; height:auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div class="form-group row" style="margin-bottom: 15px;">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;">
                                            Description<span class="required">*</span>:
                                        </label>
                                        <div class="col-sm-10 m-auto">
                                            <textarea name="w3review" rows="4" class="form-control" cols="50"
                                                name="description" placeholder="e.g. Kid's  Tv"
                                                ng-model="channlset.chnlset.description" id="descriptionInput"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom-button">
                                    <button type="button" class="save" data-ng-click="chnlsetCtrl.resetForm()"
                                        data-dismiss="modal">
                                        Close
                                    </button>
                                    &nbsp;&nbsp;&nbsp;

                                    <button type="button" class="button button-blue" data-ng-click="chnlsetCtrl.addGroup()"
                                        data-dismiss="modal">
                                        Save changes
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
    <script
        src="{{asset('adminview/assets/js/organization/app-customization/channel-listing/view-chnl-listing.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection