<style>
    /* Arnewdata icon rotation */
    #accordian-content-set .arnewdata-icon {
        margin-right: 12px;
        font-size: 16px;
        /* transition: transform 0.3s ease; */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #accordian-content-set .arnewdata-icon {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        #accordian-content-set .arnewdata-icon {
            margin: 0 0 6px 0;
        }
    }

    /* Flex layout for panel heading */
    .panel-heading.d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .panel-heading.d-flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .panel-heading .heading-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    .chip {
        display: inline-block;
        padding: 6px 12px;
        margin: 4px;
        background-color: #f1f1f1;
        border-radius: 25px;
        font-size: 14px;
        color: #333;
    }

    .chip .close {
        font-size: 16px;
        margin-left: 8px;
        color: #555;
        opacity: 0.6;
    }

    #banner-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .upload-cover-thumbnail img {
        width: 100%;
        border-radius: 6px;
        height: auto;
    }

    .add-banner {
        border: 2px dashed #aaa;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        color: #666;
        margin: 10px;
        cursor: pointer;
        flex: 1 1 220px;
        max-width: 250px;
    }

    .add-banner:hover {
        background: #f9f9f9;
    }

    .banner-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .banner-actions .status {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: #333;
    }

    /* .channel-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-start;
    } */

    .channel-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-start;

        padding: 0 15px;
        /* left and right gap */
        box-sizing: border-box;
    }

    .channel-card {
        flex: 1 1 calc(25% - 15px);
        min-width: 220px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Header (drag + status) */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 10px;
    }

    .drag-handle {
        font-size: 18px;
        color: #888;
        cursor: grab;
    }

    .status {
        font-size: 12px;
        background: #e6f8eb;
        color: #2e7d32;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: bold;
    }

    .danger {
        font-size: 12px;
        background: #ecd4d4ff;
        color: #d11717ff;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: bold;
    }

    /* Logo container */
    .card-body {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        background: #f9f9f9;
    }

    .card-body img {
        max-width: 70%;
        height: auto;
    }

    /* Footer (id + name + remove) */
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border-top: 1px solid #eee;
    }

    .card-footer strong {
        font-size: 14px;
    }

    .card-footer small {
        color: #555;
        font-size: 14px;
        margin-left: 5px;
    }

    .remove-btn {
        font-size: 16px;
        color: #777;
        cursor: pointer;
        background: #f4f4f4;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-btn:hover {
        background: #ddd;
        color: red;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .channel-card {
            flex: 1 1 calc(33.33% - 15px);
        }
    }

    @media (max-width: 768px) {
        .channel-card {
            flex: 1 1 calc(50% - 15px);
        }
    }

    @media (max-width: 480px) {
        .channel-card {
            flex: 1 1 100%;
        }
    }

    /* Add Channel Card */
    .add-card {
        flex: 1 1 calc(25% - 15px);
        min-width: 220px;
        border: 2px dashed #bbb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        cursor: pointer;
        font-size: 16px;
        color: #555;
        height: 160px;
    }

    .add-card:hover {
        background: #f0f0f0;
    }

    /* Top right controls */
    .top-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    /* Responsive */
    @media (max-width: 1024px) {

        .channel-card,
        .add-card {
            flex: 1 1 calc(33.33% - 15px);
        }
    }

    @media (max-width: 768px) {

        .channel-card,
        .add-card {
            flex: 1 1 calc(50% - 15px);
        }
    }

    @media (max-width: 480px) {

        .channel-card,
        .add-card {
            flex: 1 1 100%;
        }
    }
</style>

<div data-ng-if="noRecords">
    <div colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
        {{ trans('base::general.not_found') }}
    </div>
</div>

<!-- db fetch featureRows -->
<div class="panel-group list-repeat" id="accordion-content-set" role="tablist" aria-multiselectable="false"
    style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;"
    data-ng-if="showRecords" data-ng-repeat="record in FeaturedRowsrecords track by $index" data-ng-show="showRecords"
    data-intialize-sidebar="">

    <div class="panel panel-default" style="border-radius: 5px;">
        <div class="panel-heading d-flex" role="tab" id="heading-@{{ record.id }}">
            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                data-ng-click="fturCtrl.fetchData(record)" href="#collapse-@{{ record.id }}" aria-expanded="false"
                aria-controls="collapse-@{{ record.id }}" class="collapsed"
                style="display: flex; align-items: center; text-decoration: none; color: #333;">
                <i class="arnewdata-icon fa fa-chevron-down" style="margin-right: 12px;"></i>
                <label style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                    Featured Rows for @{{ record.subscription_name }}
                </label>
            </a>
            <td class="table-actions">
                <div class="flexbox align-items-center justify-center">
                    <div class="tooltip-parent" data-ng-if="checkAccess('organizations')">
                        <span ng-mouseover="getTooltip($event)" data-toggle="modal" data-target="#deleteModal"
                            ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon"
                            data-boot-tooltip="true" data-original-title="">
                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                <g data-original-title="" title="">
                                    <path
                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                        fill="#454545"></path>
                                </g>
                            </svg>
                            <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                        </span>
                    </div>
                </div>
            </td>
        </div>
    </div>

    <div id="collapse-@{{ record.id }}" class="panel-collapse collapse" role="tabpanel"
        aria-labelledby="heading-@{{ record.id }}">
        <div class="panel-body">
            <form id="Featured_Rows" name="Featured_Rows" method="POST" data-base-validator
                enctype="multipart/form-data">
                {!! csrf_field() !!}

                <input type="hidden" id="plan_id" value="@{{ record.id }}">

                <!-- Subscriptions -->
                <div class="form-group row" style="margin-bottom: 15px;">
                    <label for="prefix" class="col-sm-4 control-label"
                        style="font-size: 14px; color: #000; margin-top: 10px;">
                        Subscriptions<span class="required">*</span>:
                    </label>
                    <div class="col-sm-4">
                        <div class="form-input">
                            <select allowClear="1" data-jquery="select2_custom_ddl" name="organization"
                                class="admin_category_sub form-control select2_custom_ddl"
                                myValue="record.subscription_name" myPlaceholder="Select Subscriptions"
                                data-ng-model="record.subscription_name">
                                <option value="">--- Select Subscriptions ---</option>
                                <option value="@{{ record.subscription_name }}">@{{ record.subscription_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="chip">
                            @{{ record.subscription_name }}
                            <span class="close" data-dismiss="chip">&times;</span>
                        </div>
                    </div>
                </div>

                <!-- tab view -->
                @include('organizations::app-customization.common.SubscriptionsubNav')

                <div ng-if="fturCtrl.btnNo == 0">
                    @include('organizations::app-customization.promotion.feature-row.channel')
                </div>

                <div ng-if="fturCtrl.btnNo == 1">
                    @include('organizations::app-customization.promotion.feature-row.tv-show')
                </div>

                <div ng-if="fturCtrl.btnNo == 2">
                    @include('organizations::app-customization.promotion.feature-row.movie')
                </div>


                <!-- bottom button code -->
                <div class="bottom-button text-right ">
                    <button data-ng-click="fturCtrl.UpdateRecord($event, record.id)" class="button button-blue">
                        Update
                    </button>&nbsp;&nbsp;&nbsp;

                    <button data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)"
                        class="button button-red">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- data base loop drag and drop model -->
<style>
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

    .assign-btns {
        margin-top: 15px;
        text-align: center;
    }
</style>

<!-- Assigned Content Sets channel model code start -->
<div class="modal fade" id="assigned-content" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4 class="modal-title">Add Bundles</h4>
                </div>
                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap"
                        style="margin-top: 10px; margin-bottom: 10px;">
                        <h5>Available Channels</h5>
                    </div>
                    <input type="text" id="searchAvailable" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box" id="availableChannelList">
                        <div class="channel-item" draggable="true" data-ng-repeat="orgbundles in fturCtrl.ChannelList"
                            data-id="@{{ orgbundles.id }}">
                            <div class="channel-info">
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ orgbundles.name }}
                            </div>
                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap"
                        style="margin-top: 10px; margin-bottom: 10px;">
                        <h5>Assigned Channels</h5>
                    </div>
                    <input type="text" id="searchAdded" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <!-- <div class=""> -->
                    <div class="scroll-box" id="AddChannel" style="min-height: 339px;">
                        <div class="drop-zone">DROP HERE</div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>

            <div class="bottom-button text-center">
                <button type="button" class="button button-blue" ng-click="fturCtrl.assignSelectedBundles()"
                    data-dismiss="modal">Assign
                </button>
                <button class="button button-gray" data-dismiss="modal" style="line-height: 39px;">Cancel</button>
            </div>

        </div>
    </div>
</div>

<!-- Assigned Content Sets tv show model code start -->
<div class="modal fade" id="assigned-show" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4 class="modal-title">Add Bundles</h4>
                </div>
                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap"
                        style="margin-top: 10px; margin-bottom: 10px;">
                        <h5>Available Channels</h5>
                    </div>

                    <input type="text" id="searchAvailable" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box" id="availableShowList">
                        <div class="channel-item" draggable="true" data-ng-repeat="orgbundles in fturCtrl.TvShowList"
                            data-id="@{{ orgbundles.id }}">
                            <div class="channel-info">
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ orgbundles.name }}
                            </div>
                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap"
                        style="margin-top: 10px; margin-bottom: 10px;">
                        <h5>Assigned Channels</h5>
                    </div>
                    <input type="text" id="searchAdded" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box" id="AddShow" style="min-height: 339px;">
                        <div class="drop-zone">DROP HERE</div>
                    </div>
                </div>
            </div>

            <div class="bottom-button text-center">
                <button type="button" class="button button-blue" ng-click="fturCtrl.assignSelecteShow()"
                    data-dismiss="modal">Assign
                </button>
                <button class="button button-gray" data-dismiss="modal" style="line-height: 39px;">Cancel</button>
            </div>

        </div>
    </div>
</div>

<!-- Assigned Content Sets movie model code start -->
<div class="modal fade" id="assigned-movie" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">Add Bundles</h4>
                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap">
                        <h5>Available Channels</h5>
                    </div>
                    <input type="text" id="searchAvailable" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box" id="availableMovieList">
                        <div class="channel-item" draggable="true" data-ng-repeat="orgbundles in fturCtrl.MovieList"
                            data-id="@{{ orgbundles.id }}">
                            <div class="channel-info">
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ orgbundles.name }}
                            </div>
                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap">
                        <h5>Assigned Channels</h5>
                    </div>
                    <input type="text" id="searchAdded" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <!-- <div class=""> -->
                    <div class="scroll-box" id="AddMovie" style="min-height: 339px;">
                        <div class="drop-zone">DROP HERE</div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>

            <div class="bottom-button text-center">
                <button type="button" class="button button-blue" ng-click="fturCtrl.assignSelecteMovie()"
                    data-dismiss="modal">Assign
                </button>
                <button class="button button-gray" data-dismiss="modal" style="line-height: 39px;">Cancel</button>
            </div>

        </div>
    </div>
</div>

<!-- manually create featureRows -->
<div class="panel-group" id="accordion-content-set" role="tablist" aria-multiselectable="false"
    ng-repeat="newdata in featureRows track by newdata.id"
    style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0,0,0,0.2); border-radius: 5px; background-color: #fff;">
    <div class="panel panel-default" style="border-radius: 5px;">

        <div class="panel-heading d-flex" role="tab" id="heading-@{{ newdata.id }}">
            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                href="#collapse-@{{ newdata.id }}" aria-expanded="false" aria-controls="collapse-@{{ newdata.id }}"
                class="collapsed" style="display:flex; align-items:center; text-decoration:none; color:#333;">
                <i class="arnewdata-icon fa fa-chevron-down" style="margin-right: 12px;"></i>
                <label style="flex-gnewdata:1; font-size:1.3rem; font-weight:900; padding: 0.5rem 0 0.5rem 0;">
                    Featured Rows for &nbsp;&nbsp;&nbsp; @{{ newdata.title }}
                </label>
            </a>
            <td class="table-actions">
                <div class="flexbox align-items-center justify-center">
                    <div class="tooltip-parent" data-ng-if="checkAccess('channels')">
                        <span ng-mouseover="getTooltip($event)" data-toggle="modal" data-target="#deleteModal"
                            ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon"
                            data-boot-tooltip="true" data-original-title="">
                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                <g data-original-title="" title="">
                                    <path
                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                        fill="#454545"></path>
                                </g>
                            </svg>
                            <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                        </span>
                    </div>
                </div>
            </td>
        </div>

        <div id="collapse-@{{ newdata.id }}" class="panel-collapse collapse" role="tabpanel"
            aria-labelledby="heading-@{{ newdata.id }}">
            <div class="panel-body">
                <!-- Subscriptions -->
                <div class="form-group row" style="margin-bottom: 15px;">
                    <label for="prefix" class="col-sm-4 control-label"
                        style="font-size: 14px; color: #000; margin-top: 10px;">
                        Subscriptions<span class="required">*</span>:
                    </label>
                    <div class="col-sm-4">
                        <div class="form-input">
                            <select allowClear="1" data-jquery="select2_custom_ddl" name="organization"
                                class="admin_category_sub form-control select2_custom_ddl"
                                myValue="channel.organization" myPlaceholder="Select Subscriptions"
                                data-ng-model="channel.organization">
                                <option value="">--- Select Subscriptions ---</option>
                                <option value="Days">Dimond</option>
                                <option value="Months">Bronz</option>
                                <option value="Months">Silver</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="chip">
                            Premium Plan
                            <span class="close" data-dismiss="chip">&times;</span>
                        </div>
                    </div>
                </div>

                <!-- tab view -->
                @include('organizations::app-customization.common.SubscriptionsubNav')

                <div ng-if="fturCtrl.btnNo == 0">
                    @include('organizations::app-customization.promotion.feature-row.channel')
                </div>

                <div ng-if="fturCtrl.btnNo == 1">
                    @include('organizations::app-customization.promotion.feature-row.tv-show')
                </div>

                <div ng-if="fturCtrl.btnNo == 2">
                    @include('organizations::app-customization.promotion.feature-row.movie')
                </div>


                <!-- bottom button code -->
                <div class="bottom-button text-right ">
                    <button data-ng-click="channelGridCtrl.saveChannel($event, channel.id)" class="button button-blue">
                        Update
                    </button>&nbsp;&nbsp;&nbsp;

                    <button data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)"
                        class="button button-red">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>