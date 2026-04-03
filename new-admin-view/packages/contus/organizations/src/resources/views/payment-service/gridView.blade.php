<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'settings'])
                    <!-- <th class="text-center">#</th> -->
                    <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.payment_provider"
                            placeholder="Enter Service Type" data-boot-tooltip="true" title="Service Type">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.mode"
                            placeholder="Enter Mode" data-boot-tooltip="true" title="Mode">
                    </td>
                    <td class="">
                        <select class="form-control select2_custom_ddl" minimumResults="-1" data-jquery="select2_custom_ddl"
                            data-boot-tooltip="true" data-ng-model="searchRecords.is_active" data-ng-change="search()"
                            data-toggle="tooltip" data-original-title="{{trans('base::general.is_active')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value='1'>Enabled</option>
                            <option value='0'>Disabled</option>
                        </select>
                    </td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>

                    <td class="">@{{ record.payment_provider }}</td>

                    <td>
                        @{{ record.provider_data['mode'] || '-' }}
                    </td>

                    <td>
                        @{{ record.is_active == 1 ? 'Enabled' : 'Disabled' }}
                    </td>

                    <td>
                        <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" value="@{{record.id}}"
                            ng-checked="record.default == 1 || (record.system_default.length > 0 && record.system_default[0].payment_service_system_default == record.id)"
                            data-ng-click="sysdft(record, record.id, (record.default == 1 || (record.system_default.length > 0 && record.system_default[0].payment_service_system_default == record.id)) ? 0 : 1)">
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <a data-ng-if="checkAccess('payment_services.edit')"
                                data-ng-click="default(record, record.id, record.organization_default.length > 0 && record.organization_default[0].default == 1 ? 0 : 1)"
                                href="javascript:void(0)" class="button button-blue">
                                <span>
                                    @{{ record.organization_default.length > 0 &&
                                    record.organization_default[0].default == 1 ?
                                    'Default' : 'Make Default' }}
                                </span>
                            </a>

                            &nbsp;&nbsp;&nbsp;

                            <!-- edit button (class="table_action sidepanel-open")-->
                            <div data-ng-if="checkAccess('payment_services.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="pytsveCtrl.openEditPage(record, record.id)">
                                    <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                        <path
                                            d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z" />
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>