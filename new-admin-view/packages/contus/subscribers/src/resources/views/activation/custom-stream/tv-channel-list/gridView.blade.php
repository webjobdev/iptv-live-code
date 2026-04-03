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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscribers'])
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
                    <td>
                        <input type="text" class="form-control" data-ng-model="searchRecords.channel_list"
                            placeholder="Channel Name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Channel Name">
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

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <td>
                        @{{ record.channel_list }}
                    </td>

                    <!-- status -->
                    <td class="center">
                        <div class="flexbox">
                            <div data-ng-if="checkAccess('subscribers')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.is_active == 1"
                                        ng-disabled="cmsCtrl.isExpired(record.end_date) || !record.end_at"
                                        ng-click="!(cmsCtrl.isExpired(record.end_date) || !record.end_at) && togglePublishNow(record, record.id)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>

                    <!-- edit button code -->
                    <td class="table-action center">
                        <div data-ng-if="checkAccess('subscribers')" class="column edit_table_icon tooltip-parent">
                            <button data-ng-click="cmsCtrl.editchannel(record)">
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

                        
                    </td>

                </tr>

            </tbody>
        </table>

    </div>
    <!-- @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal') -->
    @include('base::layouts.pagination')
</div>

<br>

<!-- insert and edit code for channel -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="channelcardForm" id="channelcardForm" method="POST" data-base-validator
            data-ng-submit="cmsCtrl.savechannel($event, cmsCtrl.channel.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <!-- <input type="hidden" id="subscriber-id" name="id">

            <script>
                document.getElementById('subscriber-id').value = window.location.pathname.split('/').pop();
            </script> -->

            <input type="hidden" id="subscriber-id" name="subscriber-id"
                value="{{ request()->query('subscriber-id') }}">

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!cmsCtrl.channel.id">Create Subscribers channel Card</h5>
                <h5 data-ng-if="cmsCtrl.channel.id">Edit Tv Channel</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- profile name -->
                <!-- <div class="form-group">
                    <label>Profile Name <span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text"
                            name="profile_name"
                            data-unique="@{{cmsCtrl.uniqueRoute}}"
                            data-ng-model="cmsCtrl.channel.channel_list"
                            class="form-control"
                            placeholder="Enter profile Name" />
                    </div>
                    <p class="error-msg">@{{ errors.first_name.message }}</p>
                </div> -->

                <!-- card type -->
                <div class="form-group">
                    <label>
                        <!-- {{trans('channel::adminchannel.security_type')}} -->
                        Month Type <span class="required">*</span></label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="end_at"
                            data-ng-change="cmsCtrl.updateCardPattern()" myPlaceholder="Choose Month"
                            data-ng-model="cmsCtrl.channel.end_at" myValue="cmsCtrl.channel.end_at">
                            <option value=""></option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Month">3 Month</option>
                            <option value="6 Month">6 Month</option>
                            <option value="12 Month">12 Month</option>
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.gender.has">@{{ errors.gender.message }}</p>
                </div>


            </div>

            <div class="flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="cmsCtrl.closeSubscriptionEdit()" name="cancel" class="button button-gray">
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit"
                    class="button button-blue">
            </div>

        </form>
    </div>
</div>