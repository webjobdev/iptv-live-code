<div id="static_content">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                        <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                                <div class="ckbox ckbox-default">
                                    <input type="checkbox" id="selectall" value="1" data-ng-click="selectBulkRecords()" />
                                    <label for="selectall" class="nopadding"></label>
                                </div>
                                <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="selectedRecords != 0 && checkAccess('static_page_all_write')"
                                    data-original-title="Select video in the grid to perform a bulk action">
                                    <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                        {{__('audio::general.bulk_action')}}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                            <li>
                                                    <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('delete-Popup')"
                                                        href="#">{{__('audio::general.delete')}}</a>
                                            </li>
                                            <li>
                                                <a data-toggle="modal" data-target="#videoBulkActionModal" ng-click="staticCtrl.hideOrshowBulkRecord('show')"
                                                    href="#">{{__('base::general.show')}}</a>
                                            </li>
                                            <li>
                                                    <a data-toggle="modal" data-target="#videoBulkActionModal" ng-click="staticCtrl.hideOrshowBulkRecord('hide')"
                                                        href="#">{{__('base::general.hide')}}</a>
                                            </li>
                                       
                                    </ul>
                                </div>
                            </th>
                    <th data-ng-repeat="field in heading">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::staticcontent.enter_title')}}">
                    </td>
                    <td>
                        <select minimumResults="-1" class="form-control mb15 select2_custom_ddl"  data-jquery="select2_custom_ddl"  data-ng-change="search()"
                            data-ng-model="searchRecords.is_footer_menu" data-boot-tooltip="true"
                            title="{{trans('base::general.select_type')}}">
                                <option value="all">{{trans('base::general.all')}}</option>
                                <option value='1'>{{trans('cms::staticcontent.show')}}</option>
                                <option value='0'>{{trans('cms::staticcontent.hide')}}</option>
                        </select>
                    </td>
                    <td></td>
                    <td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td>@{{record.title}}</td>
                    <td>
                        <div class="tooltip-parent" data-ng-if="checkAccess('static_page_all_write')">
                        <span class="status-active"
                            ng-if="record.is_footer_menu == 1" style="cursor: pointer;"
                            data-toggle="modal" data-target="#static-content-status-update-popup"  data-ng-click="staticCtrl.statusChangeSingleRecord(record)"
                              data-boot-tooltip="true">{{trans('cms::staticcontent.show_in_footer')}}</span>
                            <span  class="tooltip_title">{{trans('cms::staticcontent.click_to_hide')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="checkAccess('static_page_all_write')">
                        <span class="status-inactive" ng-if="record.is_footer_menu != 1"
                            style="cursor: pointer;"
                            data-toggle="modal" data-target="#static-content-status-update-popup"  data-ng-click="staticCtrl.statusChangeSingleRecord(record)"
                            
                            data-boot-tooltip="true">{{trans('cms::staticcontent.hide_from_footer')}}</span>
                            <span  class="tooltip_title">{{trans('cms::staticcontent.click_to_show')}}</span>
                        </div>

                        
                        
                        </td>
                    <td>@{{record.formatted_updated_date}}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div data-ng-if="checkAccess('static_page_all_write')" class="column edit_table_icon tooltip-parent">
                                    <a data-boot-tooltip="true"  class="table_action" href="{{url('admin/static-content/edit-static-content')}}/@{{record.id}}" >
                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                            </g>
                                        </svg>
                                    </a>
                                    <span  class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                            <div class="tooltip-parent" sddfdsf data-ng-if="checkAccess('static_page_all_write')">
                            <span ng-mouseover="getTooltip($event)" class="delete_table_icon"  data-toggle="modal" data-target="#deletePopup" data-ng-click="staticCtrl.deleteSingleRecord(record.id)" data-boot-tooltip="true">
                                <i class="fa fa-trash-o"></i>
                            </span>
                            <span  class="tooltip_title">{{__('base::general.delete')}}</span>
                            </div>
                            
                                
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- @include('audio::admin.common.singleRecordDeleteModal') --}}
    @include('audio::admin.common.deletePopup',['modalHeader'=>trans('base::gridlist.delete_record'),'modalContent'=>trans('base::gridlist.delete_confirm')])

    @include('audio::admin.common.bulkActionModal')
    @include('base::layouts.pagination')
</div>
<div class="alert-popup modal fade" id="static-content-status-update-popup">             
        <div class="alert-popup-content">
            <div class="popup_head">
                <h3>{{__('base::gridlist.single_record_status_update_modal_title')}}</h3>
            </div>
            <div class="popup_content"> 
                <span class="conformation_txt">
                    {{__('base::gridlist.single_record_status_update_modal_content')}}
                </span>
                <div class="popup_btns text-center">
                    <a  data-ng-click="staticCtrl.cancelDelete()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</a>
                    <a data-ng-click="staticCtrl.confirmStatus()" href="javascript:void(0)" class="pop_confirm_btn" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="alert-popup modal fade" id="videoBulkActionModal" data-role="dialog">
        <div class="alert-popup-content">
          <div class="popup_head">
            <h3>{{__('base::gridlist.bulk_action')}}</h3>
          </div>
          <div class="popup_content" data-ng-show="isShowBulkRecord">
            <span class="conformation_txt">
              {{__('base::gridlist.bulk_action_show_the_content')}}
            </span>
            <div class="popup_btns text-center">
              <a class="pop_cancel_btn" data-ng-click="cancelDelete()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
              <a data-ng-click="confirmBulkStatusFooterUpdate(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
          </div>
          <div class="popup_content" data-ng-show="isHideBulkRecord">
            <span class="conformation_txt">
              {{__('base::gridlist.bulk_action_hide_the_content')}}
            </span>
            <div class="popup_btns text-center">
              <a class="pop_cancel_btn" data-ng-click="cancelDelete()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
              <a data-ng-click="confirmBulkStatusFooterUpdate(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
          </div>
        </div>
      </div>

