<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive" style="overflow: auto;">
        <table class="table playlist_table padding-table" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="selectall" value="1" data-ng-click="pregridCtrl.selectAllRecords()" />
                            <label for="selectall" class="nopadding"></label>
                        </div>
                        <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="pregridCtrl.selectedRecords != 0 && checkAccess('preset_all_write')"
                            data-original-title="Select video in the grid to perform a bulk action">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                {{__('video::videos.bulk_action')}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-toggle="modal" data-target="#presetBulkDeleteModal" ng-click="pregridCtrl.activateOrDeactivateBulkRecord('activate')"
                                        href="#">{{__('video::videos.activate')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#presetBulkDeleteModal" ng-click="pregridCtrl.activateOrDeactivateBulkRecord('deactivate')"
                                        href="#">{{__('video::videos.deactivate')}}</a>
                                </li>
                            </ul>
                        </div>
                    </th>
                    <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'Format'|| field.name == 'Preset Max Height'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="search_text">
                <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true"
                            title="{{trans('video::presets.enter_preset_name')}}">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.aws_id" data-boot-tooltip="true"
                            title="{{trans('video::presets.enter_aws_identifier')}}">
                    </td>
                    <td class="search_product"></td>
                    <td>
                        <!-- <div class="presets_action">
                            <select class="form-control mb15" data-ng-model="searchRecords.is_active" data-ng-change="search()"
                                data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
                                <option value="all">{{trans('base::general.all')}}</option>
                                <option value='1'>{{trans('video::collection.banner.active')}}</option>
                                <option value='0'>{{trans('video::collection.banner.inactive')}}</option>
                            </select>
                            <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true"
                                title="{{trans('base::general.search_filter')}}">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true"
                                title="{{trans('base::general.reset')}}">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div> -->
                        <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                <option value="all">{{__('base::general.all')}}</option>
                                <option value='1'>{{__('video::collection.banner.active')}}</option>
                                <option value='0'>{{__('video::collection.banner.inactive')}}</option>
                        </select>
                    </td>
                    <td></td>
                    
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords"
                    class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="pregridCtrl.selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td class="serial_number">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td>@{{record.name}}</td>
                    <td>@{{record.aws_id}}</td>
                    <td class="center">@{{record.format}}</td>
                    <td>
                        
                        <div class="tooltip-parent" data-ng-if="checkAccess('preset_all_write')">
                            <span class="status-active" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets > 1"
                            style="cursor: pointer;" data-toggle="modal" data-target="#presetBulkDeleteModal" data-ng-click="pregridCtrl.statusChangeSingleRecord(record)"
                            data-boot-tooltip="true">{{trans('video::collection.message.active')}}
                            </span>
                            <span class="tooltip_title">{{trans('video::presets.deactivate_preset')}}</span>
                        </div>
                        <div class="tooltip-parent status-disable" data-ng-if="checkAccess('preset_all_write')">
                            <span class="status-active" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets == 1"
                            style="cursor: not-allowed;" 
                            data-boot-tooltip="true">{{trans('video::collection.message.active')}}</span>
                            <span class="tooltip_title">{{trans('video::presets.minimum_preset_limit')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="checkAccess('preset_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets < 30"
                            style="cursor: pointer;" data-toggle="modal" data-target="#presetBulkDeleteModal" data-ng-click="pregridCtrl.statusChangeSingleRecord(record)" 
                            data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span>
                            <span class="tooltip_title">{{trans('video::presets.activate_preset')}}</span>
                        </div>
                        <div class="tooltip-parent status-disable" data-ng-if="checkAccess('preset_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets >= 30"
                            style="cursor: not-allowed;" 
                            data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span>
                            <span class="tooltip_title">{{trans('video::presets.preset_limit_exceeded')}}</span>
                        </div>
                       
                        <div class="tooltip-parent" data-ng-if="!checkAccess('preset_all_write')">
                        <button disabled class="status-active" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets > 1"
                            style="cursor: pointer;"><span 
                                data-boot-tooltip="true">{{trans('video::collection.message.active')}}</span></button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="!checkAccess('preset_all_write')">
                        <button disabled class="status-active" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets == 1"
                            style="cursor: not-allowed;"><span 
                                data-boot-tooltip="true">{{trans('video::collection.message.active')}}</span></button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="!checkAccess('preset_all_write')">
                                <button disabled class="status-inactive" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets < 30"
                            style="cursor: pointer;"><span 
                                data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span></button>
                             <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="!checkAccess('preset_all_write')">
                                <button disabled class="status-inactive" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets >= 30"
                            style="cursor: not-allowed;"><span 
                                data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span></button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                            </div>
                        
                    </td>
                    <td class="center">@{{record.preset_max_height}}</td>
                    
                </tr>
            </tbody>
        </table>
    </div>
    <div class="alert-popup modal fade" id="presetBulkDeleteModal" data-role="dialog">
        <div class="alert-popup-content">
            <div class="popup_head">
                <h3>{{__('base::gridlist.bulk_action')}}</h3>
            </div>
            <div  class="popup_content" data-ng-show="pregridCtrl.isDeleteBulkRecord" >
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_delete_confirm')}}
                </span>

                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="pregridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="pregridCtrl.confirmDeleteVideos('bulk-video')" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>

            <div class="popup_content" data-ng-show="pregridCtrl.isActivateBulkRecord">
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_activate_confirm')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="pregridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="pregridCtrl.confirmActivateOrDeactivate(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>

            <div class="popup_content" data-ng-show="pregridCtrl.isDeactivateBulkRecord">
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_deactivate_confirm')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="pregridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="pregridCtrl.confirmActivateOrDeactivate(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>
            <div class="popup_content" data-ng-show="ConfirmationStatusBox">
                    <span class="conformation_txt">
                        {{__('base::gridlist.statusChange')}}
                    </span>
                    <div class="popup_btns text-center">
                        <a class="pop_cancel_btn" data-ng-click="pregridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                        <a data-ng-click="pregridCtrl.confirmStatus()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                    </div>
            </div>
        </div>      
    </div>
    @include('base::layouts.pagination')
</div>