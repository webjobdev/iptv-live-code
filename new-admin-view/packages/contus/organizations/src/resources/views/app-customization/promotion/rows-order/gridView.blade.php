<style>
    .list-wrapper {
        /* max-width: 600px;
        margin: auto; */
    }

    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Drag handle */
    .drag-handle {
        font-size: 18px;
        color: #888;
        margin-right: 10px;
        cursor: grab;
    }

    .drag-checkbox {
        margin-right: 10px;
        margin-bottom: 2px;
    }

    /* Left section (number + title) */
    .left {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .left strong {
        margin-right: 8px;
        font-size: 14px;
        color: #000;
    }

    .left span {
        font-size: 15px;
        color: #657a86;
        font-weight: 500;
    }

    /* Middle section (badges) */
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

    /* Remove button */
    .remove-btn {
        font-size: 16px;
        color: #777;
        cursor: pointer;
        margin-left: 10px;
    }

    .remove-btn:hover {
        color: red;
    }
</style>

<div data-ng-if="noRecords">
    <div colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
        {{ trans('base::general.not_found') }}
    </div>
</div>


<div class="list-wrapper" data-ng-if="showRecords" data-ng-show="showRecords" class="list-repeat"
    data-intialize-sidebar="">

    <div class="list-item" draggable="true" data-ng-repeat="record in RowsorderRecords track by $index"
        data-id="@{{ record.id }}" data-title="@{{ record.title }}">
        <span class="drag-handle">⋮⋮</span>

        <div class="left">
            <strong>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</strong>
            <span>@{{ record.title }}</span>
        </div>

        <div class="badges">
            <div data-ng-repeat="row in record.assigne_row">
                <span class="badge channel" ng-if="row.row_type === 'channel'">CHANNEL</span>
                <span class="badge movies" ng-if="row.row_type === 'vod'">VOD</span>
                <span class="badge events" ng-if="row.row_type === 'liveevent'">EVENT</span>
                <span class="badge series" ng-if="row.row_type === 'tvshow'">Series</span>
                <span class="badge default" ng-if="row.row_type === 'radio'">Other</span>
            </div>
        </div>

        <td class="table-actions">
            <div class="flexbox align-items-center justify-center remove-btn">

                <div data-ng-if="checkAccess('subscribers')" class="column edit_table_icon tooltip-parent">
                    <button data-ng-click="ROCtrl.view(record, record.id)">
                        <svg fill="#000000" height="15px" width="15px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 42 42" enable-background="new 0 0 42 42" xml:space="preserve">
                            <path
                                d="M15.3,20.1c0,3.1,2.6,5.7,5.7,5.7s5.7-2.6,5.7-5.7s-2.6-5.7-5.7-5.7S15.3,17,15.3,20.1z M23.4,32.4 C30.1,30.9,40.5,22,40.5,22s-7.7-12-18-13.3c-0.6-0.1-2.6-0.1-3-0.1c-10,1-18,13.7-18,13.7s8.7,8.6,17,9.9 C19.4,32.6,22.4,32.6,23.4,32.4z M11.1,20.7c0-5.2,4.4-9.4,9.9-9.4s9.9,4.2,9.9,9.4S26.5,30,21,30S11.1,25.8,11.1,20.7z" />
                        </svg>
                    </button>
                    <span class="tooltip_title">view</span>
                </div>

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

</div>


@include('audio::admin.common.singleRecordDeleteModal')
@include('audio::admin.common.singleRecordStatusUpdateModal')
@include('base::layouts.pagination')