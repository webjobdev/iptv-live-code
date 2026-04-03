<!-- epg table code -->
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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'organizations'])
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
                <!-- <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.account_number"
                            placeholder="Enter Account Number" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="account number">
                    </td>
                    <td class="search_product td-custom-width">
                        <input type="text" class="form-control" data-ng-model="searchRecords.user_name"
                            data-boot-tooltip="true" data-toggle="tooltip" data-original-title="user name">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription"
                            data-ng-model="searchRecords.first_name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="full name">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription"
                            data-ng-model="searchRecords.email" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="email">
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription"
                            data-ng-model="searchRecords.phone_number" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="phone">
                    </td>
                    <td>
                        <select class="form-control mb15 select2_custom_ddl" minimumResults="-1"
                            data-jquery="select2_custom_ddl" data-boot-tooltip="true"
                            data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip"
                            data-original-title="{{trans('base::general.select_status')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value='1'>{{trans('customer::subscription.active')}}</option>
                            <option value='0'>{{trans('customer::subscription.inactive')}}</option>
                        </select>
                    </td>
                </tr> -->

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>

                <tr data-ng-if="showRecords" data-ng-repeat-start="record in records track by $index"
                    class="list-repeat" data-ng-show="showRecords" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <td>
                        <a data-toggle="collapse" data-parent="#Accordion" href="#epg_@{{ record.id }}"
                            aria-expanded="false">
                            @{{ record.task_name }}
                        </a>
                    </td>

                    <td>
                        @{{ record.source_url }}
                    </td>
                    <td>
                        @{{ record.executions && record.executions.length ? (record.executions[0].status == 1 ? 'OK' :
                        (record.executions[0].status == 0 ? 'Failed' : 'Never Run')) : 'Never Run' }}
                    </td>
                    <!-- <td>
                        @{{ record.executions[0].status || 'Never Run' }}
                    </td> -->
                    <td>
                        @{{ record.schedule_base || 'Never' }}
                    </td>
                    <td>
                        @{{ record.last_run || '-' }}
                    </td>
                    <td>
                        @{{ record.next_run || '-' }}
                    </td>
                    <td ng-style="{'color': record.is_active == 1 ? '#5cb85c' : '#d9534f'}">
                        @{{ record.is_active == 1 ? 'Enable' : 'Disable' }}
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.is_active == 1"
                                        ng-click="changedata(record)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div ng-if="checkAccess('epg_service.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="epgCtrl.rundata(record)">
                                    <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.54,9,8.88,3.46a3.42,3.42,0,0,0-5.13,3V17.58A3.42,3.42,0,0,0,7.17,21a3.43,3.43,0,0,0,1.71-.46L18.54,15a3.42,3.42,0,0,0,0-5.92Zm-1,4.19L7.88,18.81a1.44,1.44,0,0,1-1.42,0,1.42,1.42,0,0,1-.71-1.23V6.42a1.42,1.42,0,0,1,.71-1.23A1.51,1.51,0,0,1,7.17,5a1.54,1.54,0,0,1,.71.19l9.66,5.58a1.42,1.42,0,0,1,0,2.46Z" />
                                    </svg>
                                </button>
                                <span class="tooltip_title">Run EPG XML Task</span>
                            </div>

                            <div ng-if="checkAccess('epg_service.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="epgCtrl.editdata(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">Edit EPG XML Task</span>
                            </div>

                            <div class="tooltip-parent" data-ng-if="checkAccess('epg_service.delete')">
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
                                    <span class="tooltip_title">Delete EPG XML Task</span>
                                </span>
                            </div>

                        </div>
                    </td>
                </tr>

                <tr data-ng-attr-id="epg_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="10">
                        <table class="table table-bordered table-striped center">
                            <thead>
                                <th class="center">Execution Result</th>
                                <th class="center">Completed Programmes</th>
                                <th class="center">Fail Reason</th>
                                <th class="center">Start date/time</th>
                                <th class="center">Finish date/time</th>
                                <th class="center">Executed by</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="exec in record.executions">
                                    <td class="center">
                                        @{{ exec.status == 1 ? 'Completed' : (exec.status == 0 ? 'Failed' : 'Running') }}
                                    </td>
                                    <td class="center">
                                        @{{ exec.completed_programmes }}
                                    </td>
                                    <td class="center">
                                        @{{ exec.fail_reason || '-' }}
                                    </td>
                                    <td class="center">
                                        @{{ exec.start_time }}
                                    </td>
                                    <td class="center">
                                        @{{ exec.finish_time }}
                                    </td>
                                    <td class="center">
                                        @{{ exec.executed_by }}
                                    </td>
                                </tr>
                                <tr ng-if="!record.executions.length">
                                    <td colspan="6" class="center">No executions found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<style>
    .time-picker-wrapper {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .time-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .arrow-btn {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        margin: 2px 0;
    }

    .circle-input {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #aaa;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 2px 0;
    }

    .time-label {
        margin-top: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }
</style>

<style>
    .sidepanel-scroll {
        max-height: calc(97vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
</style>

<!-- create epg code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="organizationForm" id="organizationForm" method="POST" data-base-validator
            data-ng-submit="epgCtrl.save($event, epg.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <!-- Header -->
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!epg.id">
                    Add EPG XML Task
                </h5>
                <h5 data-ng-if="epg.id">
                    Edit EPG XML Task
                </h5>
            </div>

            <!-- Form Scrollable Area -->
            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- User Name -->
                <div class="form-group">
                    <label>
                        Task Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="task_name" data-unique="@{{epgCtrl.uniqueRoute}}"
                        data-ng-model="epg.task_name" class="form-control" placeholder="Enter Task Name">
                    <p class="error-msg">@{{ errors.task_name.message }}</p>
                </div>

                <!-- Schedule Base -->
                <div class="form-group">
                    <label>
                        Schedule Base
                        <span class="required">*</span>
                    </label><br>
                    <select class="form-control mb10 select2_custom_ddl" ng-model="epg.schedule_base"
                        data-jquery="select2_custom_ddl" myPlaceholder="Select Schedule Base" name="schedule_base">
                        <option value="">-- Select Schedule Base --</option>
                        <option value="hourly">Hourly (1 Hour)</option>
                        <option value="daily_midnight">Daily at Midnight</option>
                        <option value="weekly_sunday">Weekly (Sunday at 2 AM)</option>
                        <option value="monthly_1st">Monthly (1st of Month at 3 AM)</option>
                    </select>
                    <p class="error-msg">@{{ errors.schedule_base.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.expire_scheduled_time.has}">
                    <label>
                        Start Time
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input calender-left">
                        <!-- <i class="calender-icon"></i> -->
                        <div class="time-picker-wrapper">
                            <div class="time-section" ng-repeat="unit in ['hour', 'minute']">
                                <button type="button" class="arrow-btn" ng-click="increment(unit)">&#9650;</button>
                                <input type="text" class="circle-input" style="padding-left: 3px;"
                                    ng-model="epg.start_time[unit]" ng-change="updateModel()" maxlength="2"
                                    placeholder="00">
                                <div class="time-label">
                                    @{{ unit === 'hour' ? 'Hours' : (unit === 'minute' ? 'Minutes' : 'Seconds')
                                    }}
                                </div>
                                <button type="button" class="arrow-btn" ng-click="decrement(unit)">&#9660;</button>
                            </div>
                        </div>
                    </div>
                    <p class="error-msg" data-ng-show="errors.expire_scheduled_time.has">
                        @{{ errors.expire_scheduled_time.message }}
                    </p>
                </div>

                <!-- Organization Dropdown -->
                <div class="form-group">
                    <label>EPG Time Zone<span class="required">*</span></label><br>
                    <select class="form-control mb10 select2_custom_ddl" ng-model="epg.time_zone"
                        data-jquery="select2_custom_ddl" myPlaceholder="EPG Time Zone" name="time_zone">
                        <option value="">-- Select Time Zone --</option>
                        <option value="Pacific/Midway">(UTC-11:00) Midway Island, Samoa</option>
                        <option value="Pacific/Niue">(UTC-11:00) Niue</option>
                        <option value="Pacific/Pago_Pago">(UTC-11:00) Pago Pago</option>
                        <option value="America/Adak">(UTC-10:00) Hawaii-Aleutian</option>
                        <option value="Pacific/Honolulu">(UTC-10:00) Honolulu</option>
                        <option value="Pacific/Marquesas">(UTC-09:30) Marquesas Islands</option>
                        <option value="America/Anchorage">(UTC-09:00) Alaska</option>
                        <option value="Pacific/Gambier">(UTC-09:00) Gambier Islands</option>
                        <option value="America/Los_Angeles">(UTC-08:00) Pacific Time (US & Canada)
                        </option>
                        <option value="America/Tijuana">(UTC-08:00) Tijuana</option>
                        <option value="Pacific/Pitcairn">(UTC-08:00) Pitcairn Islands</option>
                        <option value="America/Denver">(UTC-07:00) Mountain Time (US & Canada)</option>
                        <option value="America/Phoenix">(UTC-07:00) Arizona</option>
                        <option value="America/Chicago">(UTC-06:00) Central Time (US & Canada)</option>
                        <option value="America/Mexico_City">(UTC-06:00) Mexico City</option>
                        <option value="America/Belize">(UTC-06:00) Belize</option>
                        <option value="America/New_York">(UTC-05:00) Eastern Time (US & Canada)</option>
                        <option value="America/Toronto">(UTC-05:00) Toronto</option>
                        <option value="America/Havana">(UTC-05:00) Havana</option>
                        <option value="America/Caracas">(UTC-04:00) Caracas</option>
                        <option value="America/La_Paz">(UTC-04:00) La Paz</option>
                        <option value="America/Santiago">(UTC-04:00) Santiago</option>
                        <option value="America/Halifax">(UTC-04:00) Atlantic Time (Canada)</option>
                        <option value="America/St_Johns">(UTC-03:30) Newfoundland</option>
                        <option value="America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires</option>
                        <option value="America/Sao_Paulo">(UTC-03:00) São Paulo</option>
                        <option value="America/Godthab">(UTC-03:00) Nuuk</option>
                        <option value="Atlantic/South_Georgia">(UTC-02:00) South Georgia</option>
                        <option value="Atlantic/Azores">(UTC-01:00) Azores</option>
                        <option value="Atlantic/Cape_Verde">(UTC-01:00) Cape Verde</option>
                        <option value="UTC">(UTC+00:00) UTC (Coordinated Universal Time)</option>
                        <option value="Europe/London">(UTC+00:00) London</option>
                        <option value="Africa/Abidjan">(UTC+00:00) Abidjan</option>
                        <option value="Europe/Paris">(UTC+01:00) Paris, Berlin, Madrid</option>
                        <option value="Africa/Lagos">(UTC+01:00) Lagos</option>
                        <option value="Europe/Athens">(UTC+02:00) Athens, Bucharest</option>
                        <option value="Africa/Cairo">(UTC+02:00) Cairo</option>
                        <option value="Europe/Kaliningrad">(UTC+02:00) Kaliningrad</option>
                        <option value="Europe/Moscow">(UTC+03:00) Moscow</option>
                        <option value="Africa/Nairobi">(UTC+03:00) Nairobi</option>
                        <option value="Asia/Baghdad">(UTC+03:00) Baghdad</option>
                        <option value="Asia/Tehran">(UTC+03:30) Tehran</option>
                        <option value="Asia/Dubai">(UTC+04:00) Dubai</option>
                        <option value="Asia/Baku">(UTC+04:00) Baku</option>
                        <option value="Asia/Kabul">(UTC+04:30) Kabul</option>
                        <option value="Asia/Karachi">(UTC+05:00) Karachi, Tashkent</option>
                        <option value="Asia/Yekaterinburg">(UTC+05:00) Yekaterinburg</option>
                        <option value="Asia/Kolkata">(UTC+05:30) India Standard Time</option>
                        <option value="Asia/Colombo">(UTC+05:30) Sri Lanka</option>
                        <option value="Asia/Kathmandu">(UTC+05:45) Kathmandu</option>
                        <option value="Asia/Dhaka">(UTC+06:00) Dhaka</option>
                        <option value="Asia/Almaty">(UTC+06:00) Almaty</option>
                        <option value="Asia/Yangon">(UTC+06:30) Yangon</option>
                        <option value="Asia/Bangkok">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
                        <option value="Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>
                        <option value="Asia/Shanghai">(UTC+08:00) Beijing, Shanghai</option>
                        <option value="Asia/Singapore">(UTC+08:00) Singapore</option>
                        <option value="Asia/Taipei">(UTC+08:00) Taipei</option>
                        <option value="Australia/Perth">(UTC+08:00) Perth</option>
                        <option value="Asia/Tokyo">(UTC+09:00) Tokyo, Seoul</option>
                        <option value="Asia/Seoul">(UTC+09:00) Seoul</option>
                        <option value="Australia/Adelaide">(UTC+09:30) Adelaide</option>
                        <option value="Australia/Darwin">(UTC+09:30) Darwin</option>
                        <option value="Australia/Sydney">(UTC+10:00) Sydney, Melbourne</option>
                        <option value="Pacific/Guam">(UTC+10:00) Guam</option>
                        <option value="Asia/Vladivostok">(UTC+10:00) Vladivostok</option>
                        <option value="Asia/Magadan">(UTC+11:00) Magadan</option>
                        <option value="Pacific/Noumea">(UTC+11:00) New Caledonia</option>
                        <option value="Pacific/Auckland">(UTC+12:00) Auckland</option>
                        <option value="Pacific/Fiji">(UTC+12:00) Fiji</option>
                        <option value="Pacific/Chatham">(UTC+12:45) Chatham Islands</option>
                        <option value="Pacific/Tongatapu">(UTC+13:00) Nuku'alofa</option>
                        <option value="Pacific/Apia">(UTC+13:00) Apia</option>
                        <option value="Pacific/Kiritimati">(UTC+14:00) Kiritimati</option>
                    </select>

                    <p class="error-msg">@{{ errors.time_zone.message }}</p>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>EPG Shift Postfix <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text" name="shift_postfix" data-ng-model="epg.shift_postfix" class="form-control"
                            placeholder="Enter EPG Shift Postfix">
                    </div>
                    <p class="error-msg">@{{ errors.shift_postfix.message }}</p>
                </div>

                <!-- EPG Source URL -->
                <div class="form-group">
                    <label>EPG Source URL <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text" name="source_url" data-ng-model="epg.source_url" class="form-control"
                            placeholder="Enter EPG Source URL">
                    </div>
                    <p class="error-msg">@{{ errors.source_url.message }}</p>
                </div>

                <!-- is active -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <label>
                                Enable
                            </label>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">{{ __('video::videos.inactive') }}</span>
                                <label class="switch">
                                    <input type="checkbox" name="is_active" ng-model="epg.is_active">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">{{ __('video::videos.active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="epgCtrl.closeSubscriptionEdit()" name="cancel" class="save">
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now">
            </div>
        </form>
    </div>
</div>