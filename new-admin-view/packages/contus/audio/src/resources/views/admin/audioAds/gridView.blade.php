<div class="panel main_container">            
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="table_responsive">
                <table class="table" id="fixTable" data-ng-class="{'no-records': noRecords}">
                    <thead>
                        <tr>
                            @include('audio::admin.common.bulkActionLayout')
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
                                <input type="text" class="form-control" data-ng-model="searchRecords.ad_name" data-boot-tooltip="true" title="{{trans('audio::audioAds.enter_ad_name')}}">
                            </td>                            
                            <td></td>
                            <td>
                                @include('audio::admin.common.gridStatusFilter')
                            </td>
                            <td></td>
                            <td>
                            </td>
                        </tr>
                        <tr data-ng-if="noRecords">
                            <td colspan="10" class="no-data center">{{trans('base::general.not_found')}}</td>
                        </tr>
                        
                        <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                            <td>
                                <div class="ckbox ckbox-default">
                                    <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
                                        value="@{{record.id}}" name="selectedCheckbox[]">
                                    <label for="roles_@{{record.id}}"></label>
                                </div>
                            </td>
                            <td>
                                <div class="product_img flexbox align-items-center table-image-text"> 
                                    <div data-ng-if="record.ad_image.length > 0" class="image ng-scope" style="background-image: url(@{{record.ad_image}})">
                                        </div>                                                             
                                    <div class="product_description">
                                        <p class="grid-title">@{{record.ad_name}}</p>
                                    </div>
                                </div>
                            </td>
                           
                            <td class="upload-status">
                                <span class="status-label primary" ng-if="record.audio_ad_job_status == 'Audio Uploaded' || record.audio_ad_job_status == 'Submitted'">{{trans('audio::audio.uploaded_status')}}</span>
                                <span class="status-label warning" ng-if="record.audio_ad_job_status == 'Progressing'">@{{record.audio_ad_job_status}}</span>
                                <span class="status-label success" ng-if="record.audio_ad_job_status == 'Complete'">@{{record.audio_ad_job_status}}</span>
                                <span class="status-label danger" ng-if="record.audio_ad_job_status == 'Error' || record.audio_ad_job_status == 'Canceled'">{{__('audio::audio.error_status')}}</span>
                                <span class="status-label info" ng-if="record.audio_ad_job_status == 'Uploading'">@{{record.audio_ad_job_status}}</span>
                                <span class="status-label info" ng-if="record.audio_ad_job_status == 'Uploaded'">@{{record.audio_ad_job_status}}</span>
                                <span class="status-label info" ng-if="record.audio_ad_job_status == 'Added'">@{{record.audio_ad_job_status}}</span>
                            </td>
                            <td>                                
                                <div class="tooltip-parent">
                                    <span class="status-active" data-toggle="modal" data-target="#single-record-status-update-popup" ng-if="record.is_active == 1" data-ng-click="confirmationPopupSingleRecordAction(record)" style="cursor: pointer;"  data-boot-tooltip="true">{{trans('audio::audioAds.message.active')}}</span>
                                    <span class="tooltip_title">{{trans('audio::artists.tooltips.click_to_deactivate')}}</span>
                                </div>
                                <div class="tooltip-parent">
                                    <span class="status-inactive" data-toggle="modal" data-target="#single-record-status-update-popup" ng-if="record.is_active != 1" data-ng-click="confirmationPopupSingleRecordAction(record)" style="cursor: pointer;"  data-boot-tooltip="true">{{trans('audio::audioAds.message.inactive')}}</span>
                                    <span class="tooltip_title">{{trans('audio::artists.tooltips.click_to_activate')}}</span>
                                </div>
                            </td>
                            <td>@{{ record.formatted_created_date }}</td>
                           
                            <td class="table-action">
                                <div class="flexbox align-items-center justify-center">
                                    <div id="st-trigger-effects" class="column edit_table_icon">
                                        <button class="table_action sidepanel-open" data-ng-click="adsgridCtrl.editAd(record)" title="{{trans('base::general.view_or_edit')}}">
                                            <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                <g>
                                                    <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                    <span ng-mouseover="getTooltip($event)" class="delete_table_icon" title="{{trans('base::general.delete')}}" data-toggle="modal" data-target="#single-record-delete-popup" data-ng-click="deleteSingleRecord(record.id)" data-boot-tooltip="true">
                                        <i class="fa fa-trash-o"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @include('base::layouts.pagination')
            @include('audio::admin.common.singleRecordDeleteModal')
            @include('audio::admin.common.singleRecordStatusUpdateModal')            
</div>
