<style>
    .subscription-container {
        /* max-width: 600px; */
        margin: 0 0 10px 0;
    }

    .subscription-container p {
        font-weight: bold;
        margin-bottom: 15px;
    }

    .list-group {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .list-group-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #e5e5e5;
        border-radius: 25px;
        padding: 10px 15px;
        margin-bottom: 10px;
        background: #fff;
    }

    .badge-num {
        background: #f5f5f5;
        color: #333;
        padding: 6px 12px;
        border-radius: 50px;
        margin-right: 15px;
        font-weight: bold;
    }

    .item-left {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .item-name {
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-listing {
        background: #000;
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 6px 15px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-listing:hover {
        background: #333;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .list-group-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-listing {
            margin-top: 8px;
            align-self: flex-end;
        }
    }
</style>

<div data-ng-if="noRecords">
    <div colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
        {{ trans('base::general.not_found') }}
    </div>
</div>

<div class="subscription-container" data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
    <ul class="list-group">
        <li class="list-group-item" style="border-radius: 10px;">
            <div class="item-left">
                <span class="badge-num">
                    @{{((currentPage - 1) * rowsPerPage) + $index +1}}
                </span>
                <span class="item-name">
                    @{{ record.subscription_name }}
                </span>
            </div>


            <td class="table-actions">
                <div class="flexbox align-items-center justify-center remove-btn">

                    <button class="button button-blue" data-ng-click="chnlsetCtrl.openPage(record, record.id)"
                        style="margin-right: 15px;">
                        TV Channel Listing
                    </button>

                    <!-- <div data-ng-if="checkAccess('organizations')" class="column edit_table_icon tooltip-parent">
                        <button data-ng-click="chnlsetCtrl.viewPage(record, record.id)">
                            <svg fill="#000000" height="15px" width="15px" version="1.1" id="Layer_1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 42 42" enable-background="new 0 0 42 42" xml:space="preserve">
                                <path
                                    d="M15.3,20.1c0,3.1,2.6,5.7,5.7,5.7s5.7-2.6,5.7-5.7s-2.6-5.7-5.7-5.7S15.3,17,15.3,20.1z M23.4,32.4 C30.1,30.9,40.5,22,40.5,22s-7.7-12-18-13.3c-0.6-0.1-2.6-0.1-3-0.1c-10,1-18,13.7-18,13.7s8.7,8.6,17,9.9 C19.4,32.6,22.4,32.6,23.4,32.4z M11.1,20.7c0-5.2,4.4-9.4,9.9-9.4s9.9,4.2,9.9,9.4S26.5,30,21,30S11.1,25.8,11.1,20.7z" />
                            </svg>
                        </button>
                        <span class="tooltip_title">view</span>
                    </div> -->
                </div>
            </td>

        </li>
    </ul>
</div>

@include('audio::admin.common.singleRecordDeleteModal')
@include('audio::admin.common.singleRecordStatusUpdateModal')
@include('base::layouts.pagination')