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
                        <td ng-if="record.generate == 1">
                            <!-- Check Menu Flag  -->
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                    ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                    name="selectedCheckbox[]">
                                <label for="roles_@{{record.id}}"></label>
                            </div>
                        </td>

                        <td ng-if="record.generate == 1">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>

                        <td ng-if="record.generate == 1">@{{ record.id }}</td>
                        <td ng-if="record.generate == 1">@{{ record.report_name }}</td>
                        <td ng-if="record.generate == 1">@{{ '-' }}</td>
                        <td ng-if="record.generate == 1">@{{ record.created_at | date:'dd-MM-yyyy HH:MM:ss' }}</td>
                        <td ng-if="record.generate == 1">@{{ record.get_user.email || '-' }}</td>
                        <td ng-if="record.generate == 1">@{{ record.get_org.organization_name }}</td>
                        <td ng-if="record.generate == 1">@{{ '-' }}</td>

                        <td class="table-actions" ng-if="record.generate == 1">
                            <div class="flexbox align-items-center justify-center">

                                <!-- edit button (class="table_action sidepanel-open")-->
                                <div class="column edit_table_icon tooltip-parent" data-ng-if="checkAccess('activation_audit_reports.create')">
                                    <a class="table_action" ng-click="actCtrl.exportCsv(record, record.id)">
                                        <svg width="15px" height="15px" viewBox="0 0 15 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.5 3.5H14V3.29289L13.8536 3.14645L13.5 3.5ZM10.5 0.5L10.8536 0.146447L10.7071 0H10.5V0.5ZM6.5 6.5V6H6V6.5H6.5ZM6.5 8.5H6V9H6.5V8.5ZM8.5 8.5H9V8H8.5V8.5ZM8.5 10.5V11H9V10.5H8.5ZM10.5 9.5H10V9.70711L10.1464 9.85355L10.5 9.5ZM11.5 10.5L11.1464 10.8536L11.5 11.2071L11.8536 10.8536L11.5 10.5ZM12.5 9.5L12.8536 9.85355L13 9.70711V9.5H12.5ZM2.5 6.5V6H2V6.5H2.5ZM2.5 10.5H2V11H2.5V10.5ZM2 5V1.5H1V5H2ZM13 3.5V5H14V3.5H13ZM2.5 1H10.5V0H2.5V1ZM10.1464 0.853553L13.1464 3.85355L13.8536 3.14645L10.8536 0.146447L10.1464 0.853553ZM2 1.5C2 1.22386 2.22386 1 2.5 1V0C1.67157 0 1 0.671573 1 1.5H2ZM1 12V13.5H2V12H1ZM2.5 15H12.5V14H2.5V15ZM14 13.5V12H13V13.5H14ZM12.5 15C13.3284 15 14 14.3284 14 13.5H13C13 13.7761 12.7761 14 12.5 14V15ZM1 13.5C1 14.3284 1.67157 15 2.5 15V14C2.22386 14 2 13.7761 2 13.5H1ZM9 6H6.5V7H9V6ZM6 6.5V8.5H7V6.5H6ZM6.5 9H8.5V8H6.5V9ZM8 8.5V10.5H9V8.5H8ZM8.5 10H6V11H8.5V10ZM10 6V9.5H11V6H10ZM10.1464 9.85355L11.1464 10.8536L11.8536 10.1464L10.8536 9.14645L10.1464 9.85355ZM11.8536 10.8536L12.8536 9.85355L12.1464 9.14645L11.1464 10.1464L11.8536 10.8536ZM13 9.5V6H12V9.5H13ZM5 6H2.5V7H5V6ZM2 6.5V10.5H3V6.5H2ZM2.5 11H5V10H2.5V11Z"
                                                fill="#000000" />
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