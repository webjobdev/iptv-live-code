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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'currencys'])
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.token"
                            placeholder="Enter Token" data-boot-tooltip="true" title="Token">
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.refresh_rate_mode"
                            placeholder="Enter Refresh Rate (Mode)" data-boot-tooltip="true"
                            title="Refresh Rate (Mode)">
                    </td>
                    <td></td>
                    <!-- <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.refresh_rate_unit"
                            placeholder="Enter Refresh Rate Unit" data-boot-tooltip="true" title="Refresh Rate Unit">
                    </td> -->
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


                    <td>@{{ record.token }}</td>
                    <td>@{{ record.refresh_rate_mode }}</td>
                    <td>@{{ '0' }}</td>

                    <td>
                        <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" value="@{{record.id}}"
                            ng-checked="record.organization_details.length > 0 
                    && record.organization_details[0].currency_converter_system_default == record.id"
                            data-ng-click="sysdft(record, record.id)">
                    </td>
                    
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div data-ng-if="checkAccess('settings')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.is_active == 1"
                                        ng-click="togglePublishNow(record, record.id)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div data-ng-if="checkAccess('settings')" class="column edit_table_icon tooltip-parent">
                                <button>
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

<!-- form code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="ConverterForm" id="ConverterForm" method="POST" data-base-validator
            data-ng-submit="ConvCtrl.save($event, ConvCtrl.converter.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!ConvCtrl.converter.id">
                    Fixer.io Currency Converter
                </h5>
                <h5 data-ng-if="ConvCtrl.converter.id">
                    Edit Currency Data
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group">
                    <label>Token<span class="required">*</span>:</label>
                    <div class="form-input">
                        <input type="text" name="token" data-unique="@{{ConvCtrl.uniqueRoute}}"
                            data-ng-model="ConvCtrl.converter.token" class="form-control" placeholder="Enter Token" />
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>
                        Refresh Rate (Mode):
                    </label>
                    <div class="col-sm-10">
                        <div class="d-flex flex-wrap gap-4">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input me-1" name="converter"
                                    ng-model="ConvCtrl.converter.refresh_rate_mode" value="cache"> Cache Rate
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input me-1" name="converter"
                                    ng-model="ConvCtrl.converter.refresh_rate_mode" value="live"> Live Rate
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.offer.has}"
                    ng-show="ConvCtrl.converter.refresh_rate_mode == 'cache'">
                    <div class="offer-div d-flex align-items-center">
                        <div class="offer me-3">
                            <input type="text" name="refresh_rate" class="form-control offer-val"
                                placeholder="Refresh Rate" ng-model="ConvCtrl.converter.refresh_rate">
                        </div>
                        <div class="form-check" style="margin-left: 25px;">
                            <select class="form-control" data-jquery="select2_custom_ddl" name="refresh_rate_unit"
                                myPlaceholder="Select Unit" data-ng-model="ConvCtrl.converter.refresh_rate_unit"
                                myValue="ConvCtrl.converter.refresh_rate_unit">
                                <option disabled value="">Choose Unit</option>
                                <option value="Hourly">Hourly</option>
                                <option value="Minute">Minute</option>
                                <option value="Second">Second</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="ConvCtrl.closeconverterEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>