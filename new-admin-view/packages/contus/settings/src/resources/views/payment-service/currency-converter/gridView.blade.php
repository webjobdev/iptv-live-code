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
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.refresh_rate_unit"
                            placeholder="Enter Refresh Rate Unit" data-boot-tooltip="true" title="Refresh Rate Unit">
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


                    <td>@{{ record.token }}</td>
                    <td>@{{ record.refresh_rate_mode }}</td>
                    <td>@{{ record.refresh_rate || '-' }}</td>
                    <td>@{{ record.refresh_rate_unit || '-' }}</td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <!-- edit button (class="table_action sidepanel-open")-->
                            <div data-ng-if="checkAccess('settings')" class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="ConvCtrl.editconverterdata(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>

                            <!-- delete button -->
                            <div class="tooltip-parent" data-ng-if="checkAccess('settings')">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal" data-target="#deleteModal"
                                    ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon"
                                    data-boot-tooltip="true" data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g data-original-title="" title="">
                                            <path
                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                    <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                                </span>
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
                    <p class="error-msg" data-ng-show="errors.token.has">
                        @{{ errors.token.message }}
                    </p>
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