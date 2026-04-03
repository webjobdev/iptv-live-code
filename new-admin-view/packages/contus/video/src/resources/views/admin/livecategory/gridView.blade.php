<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table" id="fixTable">
            <thead>
                <tr>
                    <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="selectall" value="1" data-ng-click="selectBulkRecords()" />
                            <label for="selectall" class="nopadding"></label>
                        </div>
                        <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="selectedRecords != 0 && checkAccess('livecategory_all_write')"
                            data-original-title="Select video in the grid to perform a bulk action">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                {{__('audio::general.bulk_action')}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                               
                                <li>
                                    <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('status-bulk-update','activate')"
                                        href="#">{{__('audio::general.activate')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('status-bulk-update','deactivate')"
                                        href="#">{{__('audio::general.deactivate')}}</a>
                                </li>
                            </ul>
                        </div>
                    </th>
                    <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" title="{{trans('video::categories.enter_category_name')}}">
                    </td>
                    <!-- <td></td>
                    <td></td> -->
                    <td>
                        <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                            <option value="all">{{__('base::general.all')}}</option>
                            <option value='1'>{{__('video::collection.banner.active')}}</option>
                            <option value='0'>{{__('video::collection.banner.inactive')}}</option>
                        </select>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="10" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input  type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="catgridCtrl.selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td class="serial_number">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td>
                        <div class="product_img flexbox align-items-center">
                            <a ng-if="record.videocategory.length" href="javascript:void(0);" class="table-image-text flexbox align-items-center">
                                <!-- <div class="image" bg-image="@{{record.image_url}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                </div> -->
                                <div class="product_description tooltip-parent">
                                    <p class="img_description">@{{record.title}}
                                        <span class="tooltip_title">@{{record.title}}</span>
                                    </p>
                                </div>
                            </a>
                            <a ng-if="!record.videocategory.length" href="javascript:void(0);" class="disabled-cursor table-image-text flexbox align-items-center">
                                <!-- <div class="image tyt" bg-image="@{{record.image_url}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                </div> -->
                                <div class="product_description">
                                    <p class="img_description">@{{record.title}}</p>
                                </div>
                            </a>
                        </div>
                    </td>
                    <!-- <td class="center">
                        <span>@{{record.videocategory.length}}</span>
                    </td> -->
                    <!-- <td>
                        <span data-ng-if="record.parent_category.parent_category != null">@{{record.parent_category.parent_category.title}} > </span>
                        <span data-ng-if="record.parent_category != null">@{{record.parent_category.title}}</span>
                        <span data-ng-if="record.parent_category == null">-</span> -->
                    </td>
                     <td>
                        <div class="tooltip-parent" data-ng-if="checkAccess('livecategory_all_write')">
                            <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"  
                             data-boot-tooltip="false">{{trans('video::categories.message.active')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="checkAccess('livecategory_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"   data-boot-tooltip="false">{{trans('video::categories.message.inactive')}}</span>
                        </div>

                        
                        
                    </td> 
                    <td class="center">@{{ record.category_order }}</td>
                    <td>@{{ record.formatted_created_date }}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                          
                          
                            <div data-ng-if="checkAccess('livecategory_all_write')" class="column edit_table_icon tooltip-parent" data-ng-show="record.is_deletable == 1">
                                <button class="table_action sidepanel-open" data-ng-click="catgridCtrl.editCategory(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>

                            </div>
                            <div class="tooltip-parent" data-ng-if="checkAccess('livecategory_all_write')">
                                <span data-ng-if="record.is_deletable == 1 && record.videocategory.length == 0 && record.child_category.length == 0" ng-mouseover="getTooltip($event)" class="delete_table_icon" data-toggle="modal" data-target="#deleteModal" data-ng-click="deleteSingleRecord(record.id)" >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                            </div>
                            <div class="tooltip-parent category-delete-disable" data-ng-if="checkAccess('livecategory_all_write')">
                                <span  data-ng-if="record.is_deletable == 1 && (record.videocategory.length > 0 || record.child_category.length > 0)" ng-mouseover="getTooltip($event)" class="delete_table_icon delete_disabled"  >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{trans('video::categories.delete_disabled')}}</span>
                            </div>
                          
                             
                                                      
                          
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('audio::admin.common.bulkActionModal')
    @include('base::layouts.pagination')
</div>