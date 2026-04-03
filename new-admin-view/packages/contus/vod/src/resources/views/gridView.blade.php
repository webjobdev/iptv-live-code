<div class="panel main_container">
    <div id="latest_video">
        <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
            <div class="table_loader">
                <div class="loader"></div>
            </div>
        </div>
        <div class="table_responsive" id="fixTable_parent">

            <table class="table tablesaw" id="fixTable" data-tablesaw-mode="columntoggle"
                data-ng-class="{'no-records': noRecords}">
                <thead>
                    <tr>
                        @include('audio::admin.common.bulkActionLayout', ['access_type' => 'vod'])
                        <th data-ng-repeat="field in heading">
                            @{{field.name}}
                            <span ng-if="field.hint" class="ms-1" data-bs-toggle="tooltip" title="@{{field.hint}}">
                                <i class="fa fa-question-circle text-muted"></i>
                            </span>
                            <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                                data-ng-class="{showGridArrow:field.sort}"
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
                            <input type="text" class="form-control" data-ng-model="searchRecords.title"
                                placeholder="Enter Title" data-boot-tooltip="true" title="Enter Title">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.policy"
                                placeholder="Enter Policy" data-boot-tooltip="true" title="Enter Policy">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="search_product">
                            <select class="select2_custom_ddl" minimumresults="-1" data-jquery="select2_custom_ddl"
                                data-ng-change="search()" data-ng-init="searchRecords.is_parental = 'all'"
                                myPlaceholder="{{__('base::general.select_status')}}"
                                data-ng-model="searchRecords.is_active" data-boot-tooltip="true"
                                title="{{__('base::general.select_status')}}">
                                <option value="all">{{__('base::general.all')}}</option>
                                <option value='1'>Pin Lock</option>
                                <option value='0'>Not Pin Lock</option>
                            </select>
                        </td>
                        <td class="search_product">
                            <select class="select2_custom_ddl" minimumresults="-1" data-jquery="select2_custom_ddl"
                                data-ng-change="search()" data-ng-init="searchRecords.is_active = 'all'"
                                myPlaceholder="{{__('base::general.select_status')}}"
                                data-ng-model="searchRecords.is_active" data-boot-tooltip="true"
                                title="{{__('base::general.select_status')}}">
                                <option value="all">{{__('base::general.all')}}</option>
                                <option value='1'>{{__('video::collection.banner.active')}}</option>
                                <option value='0'>{{__('video::collection.banner.inactive')}}</option>
                            </select>
                        </td>
                        <td></td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.publish_date"
                                placeholder="Enter Publish Date" data-boot-tooltip="true" title="Enter Publish Date">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.expire_scheduled_time"
                                placeholder="Enter Unpublish Date" data-boot-tooltip="true"
                                title="Enter Unpublish Date">
                        </td>
                    </tr>

                    <tr data-ng-if="noRecords">
                        <td colspan="@{{heading.length +1}}" class="no-data center">{{__('base::general.not_found')}}
                        </td>
                    </tr>

                    <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
                        data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                        <td>
                            <!-- Check Menu Flag  -->
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                    ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                    name="selectedCheckbox[]">
                                <label for="roles_@{{record.id}}"></label>
                            </div>
                        </td>
                        <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                        <td>
                            <div class="product_img flexbox align-items-center">
                                <a class="table-image-text flexbox align-items-center">
                                    <div class="image" bg-image="@{{record.thumbnail_image.replaceAll('\\','/')}}"
                                        on-error-src="{{url('adminview/assets/images/default_image.png')}}">
                                    </div>
                                    <div class="product_description tooltip-parent "
                                        data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                        <p class="img_description">@{{ record.title}}
                                            <span class="tooltip_title">@{{record.title}}</span>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </td>

                        <td>
                            @{{ record.get_policy.policy_name || '-'}}
                        </td>

                        <td>
                            <div class="product_img flexbox align-items-center">
                                <a class="table-image-text flexbox align-items-center">
                                    <div class="product_description tooltip-parent "
                                        data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                        <p class="img_description">@{{record.get_all_organization.length}}
                                            <span class="tooltip_title">
                                                <span
                                                    data-ng-repeat="org in record.get_all_organization">@{{ org.organization_name }}@{{ $last ? '' : ', ' }}</span>
                                            </span>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </td>

                        <td>
                            @{{ record.subscriber_count }}
                        </td>

                        <td>
                            <div data-ng-if="record.is_parental != 1">
                                <svg fill="#3cff00ff" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px"
                                    viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                    <g>
                                        <path d="M11,19h4c0.6,0,1-0.3,1-0.9V18c0-5.7,4.9-10.4,10.7-10C32,8.4,36,13,36,18.4v-0.3c0,0.6,0.4,0.9,1,0.9h4
		c0.6,0,1-0.3,1-0.9V18c0-9.1-7.6-16.4-16.8-16c-8.5,0.4-15,7.6-15.2,16.1C10.1,18.6,10.5,19,11,19z" />
                                        <path d="M10,18.1v0.4C10,18.4,10,18.3,10,18.1L10,18.1z" />
                                        <path d="M46,27c0-2.2-1.8-4-4-4H10c-2.2,0-4,1.8-4,4v19c0,2.2,1.8,4,4,4h32c2.2,0,4-1.8,4-4V27z M30.6,42.7
		c0.2,0.6-0.3,1.3-1,1.3h-7.3c-0.7,0-1.1-0.6-1-1.3l1.8-6c-1.5-1-2.4-2.8-2.1-4.8c0.4-1.9,1.9-3.4,3.9-3.8c3.2-0.6,6,1.7,6,4.7
		c0,1.6-0.8,3.1-2.1,3.9L30.6,42.7z" />
                                    </g>
                                </svg>
                            </div>
                            <div data-ng-if="record.is_parental == 1">
                                <svg fill="#ff0000ff" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px"
                                    viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                    <g>
                                        <path d="M11,19h4c0.6,0,1-0.3,1-0.9V18c0-5.7,4.9-10.4,10.7-10C32,8.4,36,13,36,18.4v-0.3c0,0.6,0.4,0.9,1,0.9h4
		c0.6,0,1-0.3,1-0.9V18c0-9.1-7.6-16.4-16.8-16c-8.5,0.4-15,7.6-15.2,16.1C10.1,18.6,10.5,19,11,19z" />
                                        <path d="M10,18.1v0.4C10,18.4,10,18.3,10,18.1L10,18.1z" />
                                        <path d="M46,27c0-2.2-1.8-4-4-4H10c-2.2,0-4,1.8-4,4v19c0,2.2,1.8,4,4,4h32c2.2,0,4-1.8,4-4V27z M30.6,42.7
		c0.2,0.6-0.3,1.3-1,1.3h-7.3c-0.7,0-1.1-0.6-1-1.3l1.8-6c-1.5-1-2.4-2.8-2.1-4.8c0.4-1.9,1.9-3.4,3.9-3.8c3.2-0.6,6,1.7,6,4.7
		c0,1.6-0.8,3.1-2.1,3.9L30.6,42.7z" />
                                    </g>
                                </svg>
                            </div>
                        </td>

                        <td>
                            <div data-ng-if="record.is_active == 1">
                                <svg fill="#000000" width="30px" height="30px" viewBox="0 0 24 24" id="play"
                                    data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                                    <polygon id="secondary" points="16 12 10 16 10 8 16 12"
                                        style="fill: none; stroke: rgba(61, 188, 44, 1); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                    </polygon>
                                    <circle id="primary" cx="12" cy="12" r="9"
                                        style="fill: none; stroke: rgba(81, 255, 0, 1); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                    </circle>
                                </svg>
                            </div>
                            <div data-ng-if="record.is_active != 1">
                                <svg fill="#ff0101ff" width="30px" height="25px" viewBox="0 0 32 32"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="Group_22" data-name="Group 22" transform="translate(-670.002 -321.695)">
                                        <path id="Path_360" data-name="Path 360"
                                            d="M686,353.7a16,16,0,1,0-16-16A16,16,0,0,0,686,353.7Zm0-28a12,12,0,1,1-12,12A12,12,0,0,1,686,325.7Z" />
                                        <rect id="Rectangle_32" data-name="Rectangle 32" width="3" height="9.999"
                                            transform="translate(681.002 332.696)" />
                                        <rect id="Rectangle_33" data-name="Rectangle 33" width="3" height="9.999"
                                            transform="translate(688.002 332.696)" />
                                    </g>
                                </svg>
                            </div>
                        </td>

                        <td>
                            @{{ record.created_at | date:'d/M/y H:m:s' }}
                        </td>

                        <td>
                            @{{ record.publish_date ? (record.publish_date | date:'dd/MM/yy HH:mm:ss') : '-' }}
                        </td>

                        <td>
                            @{{ record.expire_scheduled_time || '-' }}
                        </td>

                        <td>
                            @{{ '0' }}
                        </td>

                        <td class="table-action">
                            <div class="flexbox align-items-center justify-center">

                                <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                    <label class="switch">
                                        <input type="checkbox" ng-checked="record.is_active == 1"
                                            ng-click="statusChangeSingleRecord(record, record.id)">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div ng-if="checkAccess('video_on_demand.edit')"
                                    class="column edit_table_icon tooltip-parent">
                                    <a class="table_action"
                                        href="{{url('admin/vod/vod-details-edit')}}/@{{ encodeId(record.id)}} ">

                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path
                                                    d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545" />
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                </div>

                                <div class="tooltip-parent" data-ng-if="checkAccess('video_on_demand.delete')">
                                    <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                        data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                        class="tooltips delete_table_icon" data-boot-tooltip="true"
                                        data-original-title="">
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

                    </tr>
                </tbody>

            </table>

        </div>

        @include('base::layouts.pagination')

    </div>
</div>