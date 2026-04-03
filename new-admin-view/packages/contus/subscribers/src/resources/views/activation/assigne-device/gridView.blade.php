<!-- credit table code -->
<div id="creditcard">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>

    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable">
            <thead>
                <tr>
                    <th style="width: 50px;">S.No</th>
                    <th></th>
                    <th>Identifier</th>
                    <th>Active Since</th>
                    <th>MAC</th>
                    <th>Model</th>
                    <th>Role</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <!-- Rows from actCtrl.deviceRecords -->
                <tr data-ng-repeat="record in actCtrl.deviceRecords track by $index"
                    data-ng-show="actCtrl.deviceRecords.length > 0 && record.deletable != 1" class="list-repeat"
                    data-intialize-sidebar="">

                    <!-- Col 1: S.No like image -->
                    <td style="vertical-align: middle; font-weight: bold; border-right: none;">
                        # @{{ $index + 1 }}
                    </td>

                    <!-- Col 2: Status Box like image -->
                    <td data-ng-style="{'background-color': record.device_id ? '#dff0d8' : '#dff0d8'}"
                        style="vertical-align: middle; border-left: none; padding: 8px 15px;">
                        <div class="flexbox align-items-center">
                            <i class="fa fa-circle" data-ng-style="{'color': record.device_id ? '#4caf50' : '#4caf50'}"
                                style="font-size: 11px; margin-right: 12px;"></i>

                            <span data-ng-if="record.device_id"
                                style="color: #3c763d; font-weight: 500; font-size: 14px; margin-right: 10px;">
                                Active
                            </span>
                            <span data-ng-if="!record.device_id"
                                style="color: #3c763d; font-weight: 500; font-size: 14px; margin-right: 10px;">
                                Active Slot
                            </span>
                        </div>
                    </td>

                    <td style="vertical-align: middle;">@{{ record.device_detaile.identifier || '' }}</td>
                    <td style="vertical-align: middle;">@{{ record.created_at ? (record.created_at | date:'dd MMM yyyy')
                        : '' }}</td>
                    <td style="vertical-align: middle;">@{{ record.device_detaile.mac_address || '' }}</td>
                    <td style="vertical-align: middle;">@{{ record.device_detaile.brand_model || '' }}</td>
                    <td style="vertical-align: middle;">
                        <span data-ng-if="record.device_id">
                            @{{ record.is_primary == 1 ? 'Primary' : 'Slave' }}
                        </span>
                    </td>

                    <td class="table-action text-center" style="vertical-align: middle; width: 80px;">
                        <div class="flexbox align-items-center justify-center">

                            <!-- Set Primary (Edit-like icon) -->
                            <div class="tooltip-parent" data-ng-if="record.device_id && record.is_primary != 1">
                                <button class="btn btn-default" data-ng-click="actCtrl.openPrimaryModal(record)"
                                    style="background-color: #fff; border-radius: 30px; padding: 8px 16px; border: 1.5px solid #eee; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                    <i class="fa fa-pencil-square-o" style="color: #00ACCD; font-size: 20px;"></i>
                                </button>
                                <span class="tooltip_title">Set Primary</span>
                            </div>

                            <!-- Unlink Slot (Broken chain icon) -->
                            <div class="tooltip-parent" data-ng-if="record.device_id && record.is_primary == 1">
                                <button class="btn btn-default" data-ng-click="actCtrl.openUnlinkModal(record)"
                                    style="background-color: #fff; border-radius: 30px; padding: 8px 16px; border: 1.5px solid #eee; transition: all 0.3s ease;">
                                    <i class="fa fa-chain-broken" style="color: #ff0000; font-size: 20px;"></i>
                                </button>
                                <span class="tooltip_title">Unlink Slot</span>
                            </div>

                            <!-- Delete Slot (Trash icon) -->
                            <div class="tooltip-parent" data-ng-if="!record.device_id">
                                <button class="btn btn-default" data-ng-click="actCtrl.removeLocalSlot($event, record)"
                                    style="background-color: #fff; border-radius: 30px; padding: 8px 16px; border: 1.5px solid #eee; transition: all 0.3s ease; margin-left: 5px;">
                                    <i class="fa fa-trash-o" style="color: #ff0000; font-size: 20px;"></i>
                                </button>
                                <span class="tooltip_title">Delete Slot</span>
                            </div>

                        </div>
                    </td>

                </tr>

                <tr data-ng-if="!actCtrl.deviceRecords || actCtrl.deviceRecords.length == 0">
                    <td colspan="8" class="no-data center">
                        {{ trans('base::general.not_found') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@include('audio::admin.common.singleRecordDeleteModal')
@include('audio::admin.common.singleRecordStatusUpdateModal')
@include('base::layouts.pagination')

<!-- set primary device model -->
<div class="alert-popup modal fade" id="setPrimaryModal" ng-show="actCtrl.primaryConfirmBox">
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>Set Primary Device</h3>
        </div>
        <div class="popup_content">
            <span class="conformation_txt">
                Are you sure you want to set this device as Primary?
            </span>
            <span class="delete_detail">
                @{{ actCtrl.selectedDevice.identifier }}
            </span>
            <div class="popup_btns text-center">
                <a data-ng-click="actCtrl.cancelPrimary()" class="pop_cancel_btn">
                    No
                </a>
                <a data-ng-click="actCtrl.confirmPrimary()" class="pop_confirm_btn">
                    Yes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- unlink device model -->
<div class="alert-popup modal fade" id="unlinkSlotModal" ng-show="actCtrl.unlinkConfirmBox">
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>Unlink Device</h3>
        </div>
        <div class="popup_content">
            <span class="conformation_txt">
                Are you sure you want to unlink this device?
            </span>
            <span class="delete_detail">
                @{{ actCtrl.selectedDevice.identifier }}
            </span>
            <div class="popup_btns text-center">
                <a data-ng-click="actCtrl.cancelUnlink()" class="pop_cancel_btn">
                    No
                </a>
                <a data-ng-click="actCtrl.confirmUnlink()" class="pop_confirm_btn">
                    Yes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- delete slot model -->
<div class="alert-popup modal fade" id="deleteSlotModal" ng-show="actCtrl.deleteSlotConfirmBox">
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>Delete Slot</h3>
        </div>
        <div class="popup_content">
            <span class="conformation_txt">
                Are you sure you want to delete this slot?
            </span>
            <span class="delete_detail">
                @{{ actCtrl.selectedDevice.identifier }}
            </span>
            <div class="popup_btns text-center">
                <a data-ng-click="actCtrl.cancelDeleteSlot()" class="pop_cancel_btn">
                    No
                </a>
                <a data-ng-click="actCtrl.confirmDeleteSlot()" class="pop_confirm_btn">
                    Yes
                </a>
            </div>
        </div>
    </div>
</div>