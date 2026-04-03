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
                        <!-- <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.title"
                                placeholder="Enter Title" data-boot-tooltip="true" title="Enter Title">
                        </td> -->
                        <!-- <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.policy"
                                placeholder="Enter Policy" data-boot-tooltip="true" title="Enter Policy">
                        </td> -->
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
                        <!-- <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.publish_date"
                                placeholder="Enter Publish Date" data-boot-tooltip="true" title="Enter Publish Date">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.expire_scheduled_time"
                                placeholder="Enter Unpublish Date" data-boot-tooltip="true"
                                title="Enter Unpublish Date">
                        </td> -->
                    </tr>

                    <tr data-ng-if="noRecords">
                        <td colspan="@{{heading.length +1}}" class="no-data center">{{__('base::general.not_found')}}
                        </td>
                    </tr>

                    <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
                        data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                        <td ng-if="record.generate == 0">
                            <!-- Check Menu Flag  -->
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                    ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                    name="selectedCheckbox[]">
                                <label for="roles_@{{record.id}}"></label>
                            </div>
                        </td>

                        <td ng-if="record.generate == 0">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>

                        <td ng-if="record.generate == 0">@{{ record.id }}</td>
                        <td ng-if="record.generate == 0">@{{ record.report_name }}</td>
                        <td ng-if="record.generate == 0">@{{ record.created_at | date:'dd-MM-yyyy HH:MM:ss' }}</td>
                        <td ng-if="record.generate == 0">@{{ '-' }}</td>
                        <td ng-if="record.generate == 0">@{{ record.get_org.organization_name }}</td>
                        <td ng-if="record.generate == 0">@{{ '-' }}</td>

                        <td class="table-actions" ng-if="record.generate == 0">
                            <div class="flexbox align-items-center justify-center">

                                <!-- edit button (class="table_action sidepanel-open")-->
                                <div class="column edit_table_icon tooltip-parent" data-ng-if="checkAccess('activation_audit_reports.create')">
                                    <a class="table_action" ng-click="generateButton(record, record.id)">
                                        <svg fill="#000000" width="15px" height="15px" viewBox="-1.5 -2.5 24 24"
                                            xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin"
                                            class="jam jam-refresh-reverse">
                                            <path
                                                d='M4.859 5.308l1.594-.488a1 1 0 0 1 .585 1.913l-3.825 1.17a1 1 0 0 1-1.249-.665L.794 3.413a1 1 0 1 1 1.913-.585l.44 1.441C5.555.56 10.332-1.035 14.573.703a9.381 9.381 0 0 1 5.38 5.831 1 1 0 1 1-1.905.608A7.381 7.381 0 0 0 4.86 5.308zm12.327 8.195l-1.775.443a1 1 0 1 1-.484-1.94l3.643-.909a.997.997 0 0 1 .61-.08 1 1 0 0 1 .84.75l.968 3.88a1 1 0 0 1-1.94.484l-.33-1.322a9.381 9.381 0 0 1-16.384-1.796l-.26-.634a1 1 0 1 1 1.851-.758l.26.633a7.381 7.381 0 0 0 13.001 1.25z' />
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">Generate Report</span>
                                </div>

                                <!-- delete button -->
                                <div class="tooltip-parent" data-ng-if="checkAccess('activation_audit_reports.delete')">
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