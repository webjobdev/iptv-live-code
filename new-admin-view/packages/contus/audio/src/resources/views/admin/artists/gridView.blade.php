<div id="latest_video">
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
                    <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'No. Of Audio(s)'}">
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.artist_name" data-boot-tooltip="true" title="{{trans('audio::artists.enter_artist_name')}}">
                    </td>
                    <td></td>
                    <td>
                        @include('audio::admin.common.gridStatusFilter')
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="5" class="no-data center">{{trans('base::general.not_found')}}</td>
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
                            <a href="{{url('admin/artists/audios')}}/@{{record.id}}" class="table-image-text flexbox align-items-center">
                                <div  data-ng-if="record.artist_thumbnail.length > 0" class="image ng-scope" data-ng-src = "@{{ record.artist_thumbnail | trusted}}" data-default-src = "{{url('contus/base/images/no-preview.png')}}">
                                </div> 
                                <div data-ng-if="record.artist_thumbnail.length == 0" class="image ng-scope" style="background-image: url({{url('contus/base/images/no-preview.png')}})">
                                </div>                           
                                <div class="product_description tooltip-parent">
                                    <p>@{{record.artist_name}}
                                        <span class="tooltip_title">@{{record.artist_name}}</span>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </td>
                    <td class="center"><span>@{{record.audio.length}}</span></td>
                    <td>
                        <div class="tooltip-parent">
                            <span class="status-active" data-toggle="modal" data-target="#single-record-status-update-popup" ng-if="record.is_active == 1" data-ng-click="confirmationPopupSingleRecordAction(record)" style="cursor: pointer;"  data-boot-tooltip="true">{{trans('audio::general.record_status_grid.active')}}</span>
                            <span class="tooltip_title">{{trans('audio::artists.tooltips.click_to_deactivate')}}</span>
                        </div>
                        <div class="tooltip-parent">
                            <span class="status-inactive" data-toggle="modal" data-target="#single-record-status-update-popup" ng-if="record.is_active != 1" data-ng-click="confirmationPopupSingleRecordAction(record)" style="cursor: pointer;"  data-boot-tooltip="true">{{trans('audio::general.record_status_grid.inactive')}}</span>
                            <span class="tooltip_title">{{trans('audio::artists.tooltips.click_to_activate')}}</span>
                        </div>
                    </td>
                    <td>@{{ record.formatted_created_date }}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div class="column edit_table_icon tooltip-parent ">
                                <a data-boot-tooltip="true" class="table_action" href="{{url('admin/artists/audios')}}/@{{record.id}}">
                                    <svg x="0px" y="0px" width="12px" height="12px" viewBox="0 0 511.626 511.626">
                                        <g>
                                            <path d="M505.918,236.117c-26.651-43.587-62.485-78.609-107.497-105.065c-45.015-26.457-92.549-39.687-142.608-39.687   c-50.059,0-97.595,13.225-142.61,39.687C68.187,157.508,32.355,192.53,5.708,236.117C1.903,242.778,0,249.345,0,255.818   c0,6.473,1.903,13.04,5.708,19.699c26.647,43.589,62.479,78.614,107.495,105.064c45.015,26.46,92.551,39.68,142.61,39.68   c50.06,0,97.594-13.176,142.608-39.536c45.012-26.361,80.852-61.432,107.497-105.208c3.806-6.659,5.708-13.223,5.708-19.699   C511.626,249.345,509.724,242.778,505.918,236.117z M194.568,158.03c17.034-17.034,37.447-25.554,61.242-25.554   c3.805,0,7.043,1.336,9.709,3.999c2.662,2.664,4,5.901,4,9.707c0,3.809-1.338,7.044-3.994,9.704   c-2.662,2.667-5.902,3.999-9.708,3.999c-16.368,0-30.362,5.808-41.971,17.416c-11.613,11.615-17.416,25.603-17.416,41.971   c0,3.811-1.336,7.044-3.999,9.71c-2.667,2.668-5.901,3.999-9.707,3.999c-3.809,0-7.044-1.334-9.71-3.999   c-2.667-2.666-3.999-5.903-3.999-9.71C169.015,195.482,177.535,175.065,194.568,158.03z M379.867,349.04   c-38.164,23.12-79.514,34.687-124.054,34.687c-44.539,0-85.889-11.56-124.051-34.687s-69.901-54.2-95.215-93.222   c28.931-44.921,65.19-78.518,108.777-100.783c-11.61,19.792-17.417,41.207-17.417,64.236c0,35.216,12.517,65.329,37.544,90.362   s55.151,37.544,90.362,37.544c35.214,0,65.329-12.518,90.362-37.544s37.545-55.146,37.545-90.362   c0-23.029-5.808-44.447-17.419-64.236c43.585,22.265,79.846,55.865,108.776,100.783C449.767,294.84,418.031,325.913,379.867,349.04   z" fill="#454545"/>
                                        </g>
                                    </svg>
                                </a>
                                <span class="tooltip_title">{{trans('video::videos.view')}}</span>
                            </div>
                            <div class="column edit_table_icon tooltip-parent">
                                <button class="table_action sidepanel-open" data-ng-click="artgridCtrl.editArtist(record)" title="{{trans('base::general.view_or_edit')}}">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                            <div class="tooltip-parent">
                                <span ng-mouseover="getTooltip($event)" class="delete_table_icon" data-toggle="modal" data-target="#single-record-delete-popup" data-ng-click="deleteSingleRecord(record.id)" >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                            </div>
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