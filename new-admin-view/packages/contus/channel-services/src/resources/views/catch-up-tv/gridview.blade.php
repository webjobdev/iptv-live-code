<!-- catchUp table code -->
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
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.get_channel.channel_name"
                            placeholder="Enter channel name" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="channel name">
                    </td>
                    <td>
                        <select class="form-control mb15 select2_custom_ddl" minimumResults="-1"
                            data-jquery="select2_custom_ddl" data-boot-tooltip="true"
                            data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip"
                            data-original-title="{{trans('base::general.select_status')}}">
                            <option value="all">{{trans('base::general.all')}}</option>
                            <option value='1'>Enable</option>
                            <option value='0'>Disable</option>
                        </select>
                    </td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription"
                            placeholder="Enter description" data-ng-model="searchRecords.description"
                            data-boot-tooltip="true" data-toggle="tooltip" data-original-title="description">
                    </td>
                    <td></td>
                    <td class="search_product center td-custom-width">
                        <input type="text" class="form-control search-amount-subscription" placeholder="Enter schedule"
                            data-ng-model="searchRecords.schedule_base" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="schedule">
                    </td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>

                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" class="list-repeat"
                    data-ng-show="showRecords" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td class="serial_number">
                        @{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}
                    </td>

                    <td>
                        <div class="product_img flexbox align-items-center">
                            <a class="table-image-text flexbox align-items-center">
                                <div class="image" bg-image="@{{record.get_channel.poster_image.replaceAll('\\','/')}}"
                                    on-error-src="{{url('adminview/assets/images/default_image.png')}}">
                                </div>
                            </a>
                            <div class="product_description tooltip-parent "
                                data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                <p class="img_description" style="max-width: 170px;">@{{
                                    record.get_channel.channel_name}}</p>
                            </div>

                        </div>
                    </td>

                    <td ng-style="{'color': record.is_active == 1 ? '#5cb85c' : '#d9534f'}">
                        @{{ record.is_active == 1 ? 'Enable' : 'Disable' }}
                    </td>

                    <td>
                        @{{ record.description }}
                    </td>

                    <td>
                        @{{ record.days }}
                    </td>

                    <td>
                        @{{ record.schedule_base || '-' }}
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

                            <div ng-if="checkAccess('catch_up_tv.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="catchUpCtrl.editdata(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                            </div>

                            <div class="tooltip-parent" data-ng-if="checkAccess('catch_up_tv.delete')">
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
                                    <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                </span>
                            </div>

                            <!-- not allowed code strat -->



                            <!-- not allowed code end -->

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

<style>
    .sidepanel-scroll {
        max-height: calc(97vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
</style>

<!-- create catchUp code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="organizationForm" id="organizationForm" method="POST" data-base-validator
            data-ng-submit="catchUpCtrl.save($event, catchUpCtrl.catchUp.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <!-- Header -->
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!catchUpCtrl.catchUp.id">
                    Add Catch Up Tv
                </h5>
                <h5 data-ng-if="catchUpCtrl.catchUp.id">
                    Edit Catch Up Tv
                </h5>
            </div>

            <!-- Form Scrollable Area -->
            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- tv channel -->
                <div class="form-group">
                    <label>
                        Tv Channel
                        <span class="required">*</span>
                    </label><br>
                    <select class="form-control mb10 select2_custom_ddl" ng-model="catchUpCtrl.catchUp.channel_id"
                        name="channel_id" data-jquery="select2_custom_ddl" myPlaceholder="Select Tv Channel"
                        data-ng-options="chnl.id as chnl.channel_name for chnl in catchUpCtrl.channelList">
                        <option value="">-- Select Tv Channel --</option>
                    </select>
                    <p class="error-msg" data-ng-show="errors.channel_id.has">Select channel</p>
                </div>

                <!-- description -->
                <div class="form-group">
                    <label>
                        Description
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                        <input type="text" name="first_name" data-unique="@{{catchUpCtrl.uniqueRoute}}"
                            data-ng-model="catchUpCtrl.catchUp.description" class="form-control"
                            placeholder="Enter Description (optional)">
                    </div>
                    <p class="error-msg">@{{ errors.description.message }}</p>
                </div>

                <!-- days -->
                <div class="form-group">
                    <label>
                        Days
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="number" name="days" data-unique="@{{catchUpCtrl.uniqueRoute}}"
                            data-ng-model="catchUpCtrl.catchUp.days" class="form-control" placeholder="Enter Days">
                    </div>
                    <p class="error-msg">@{{ errors.days.message }}</p>
                </div>

                <!-- Schedule Base -->
                <div class="form-group">
                    <label>
                        Schedule Base
                        <span class="required">*</span>
                    </label><br>
                    <select class="form-control mb10 select2_custom_ddl" ng-model="catchUpCtrl.catchUp.schedule_base"
                        data-jquery="select2_custom_ddl" myPlaceholder="Select Tv Channel">
                        <option value="">-- Select Schedule Base --</option>
                        <option value="hourly">Hourly - catch ups of the TV Channel are created at each hour</option>
                        <option value="epg">EPG - catch ups of the TV Channel are created according to the EPG schedule
                        </option>
                    </select>
                    <p class="error-msg">@{{ errors.schedule_base.message }}</p>
                </div>

                <!-- Streaming Provider -->
                <div class="form-group">
                    <label>
                        Streaming Provider
                        <span class="required">*</span>
                    </label>
                    <select class="form-control mb10 select2_custom_ddl"
                        ng-model="catchUpCtrl.catchUp.streaming_provider" data-jquery="select2_custom_ddl"
                        myPlaceholder="Select Tv Channel">
                        <option value="">-- Select Streaming Provider --</option>
                        <option value="akamai">Akamai</option>
                        <option value="flussonic">Flussonic</option>
                        <option value="wowza">Wowza</option>
                        <option value="nimble streamer">Nimble Streamer</option>

                    </select>
                    <p class="error-msg">@{{ errors.streaming_provider.message }}</p>
                </div>

                <!-- Custom Streaming Url -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <label style="margin-bottom: 0px;">
                                Custom Streaming Url
                            </label>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">{{ __('video::videos.inactive') }}</span>
                                <label class="switch">
                                    <input type="checkbox" name="custom_streaming_url"
                                        ng-model="catchUpCtrl.catchUp.custom_streaming_url">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">{{ __('video::videos.active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show DRM settings only if Custom Streaming Url is enabled -->
                <div ng-if="catchUpCtrl.catchUp.custom_streaming_url" class="form-group">
                    <!-- url -->
                    <div class="form-group">
                        <label>
                            Url
                            <span class="required">*</span>
                        </label>

                        <div class="form-input">
                            <input type="text" name="url" data-unique="@{{catchUpCtrl.uniqueRoute}}"
                                data-ng-model="catchUpCtrl.catchUp.url" class="form-control" placeholder="Enter Url">
                        </div>
                        <p class="error-msg">@{{ errors.url.message }}</p>
                    </div>

                    <!-- DRM Type -->
                    <div class="form-group">
                        <label>
                            Select Drm Provider
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <select allowClear="1" class="admin_category_sub form-control select2_custom_ddl"
                                data-ng-model="catchUpCtrl.catchUp.drm_type" data-jquery="select2_custom_ddl"
                                myPlaceholder="Select DRM Type"
                                data-ng-options="provider for provider in catchUpCtrl.drmProviders">
                                <option value="">--- Select DRM Type ---</option>
                            </select>

                        </div>
                        <p class="error-msg" data-ng-show="errors.drm_type.has">
                            The drm type field is required.
                        </p>
                    </div>

                    <!-- DRM Profile: PallyCon -->
                    <div class="form-group"
                        data-ng-if="catchUpCtrl.catchUp.drm_type === 'Pallycon' || catchUpCtrl.catchUp.drm_type === 'EZDRM'">
                        <label>
                            Select DRM Profile
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <select allowClear="1" class="admin_category_sub form-control select2_custom_ddl"
                                data-ng-model="catchUpCtrl.catchUp.drm_profile" data-jquery="select2_custom_ddl"
                                data-ng-options="drm.drmprofile.id as drm.drmprofile.drm_name for drm in catchUpCtrl.drmProfiles 
                                | filter:{drm_provider: catchUpCtrl.catchUp.drm_type, drmprofile: {drm_name: ''}}"
                                myPlaceholder="Select DRM Profile">
                                <option value="">--- Select DRM Profile ---</option>
                            </select>
                        </div>
                        <p class="error-msg" data-ng-show="errors.drm_profile.has">
                            @{{ errors.drm_profile.has }}
                        </p>
                    </div>

                    <!-- Playback Token Generator -->
                    <div class="form-group">
                        <label>
                            Select Playback Token Generator
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <select allowClear="1" data-jquery="select2_custom_ddl" name="playback_token"
                                class="admin_category_sub form-control select2_custom_ddl"
                                myValue="catchUpCtrl.catchUp.playback_token"
                                myPlaceholder="Select Playback Token Generator"
                                data-ng-options="pbt.id as pbt.name for pbt in catchUpCtrl.playbackTokenList"
                                data-ng-model="catchUpCtrl.catchUp.playback_token">
                                <option value="">--- Select ---</option>
                                <!-- <option value="hello">hello</option>
                                <option value="byy">byy</option> -->
                            </select>
                        </div>
                        <p class="error-msg" data-ng-show="errors.playback_token.has">
                            The playback token field is required.
                        </p>
                    </div>

                    <!-- toekn Generator -->
                    <div class="form-group">
                        <label>
                            Token Generator
                            <span class="required">*</span>
                        </label>
                        <!-- <div class="form-input"> -->
                        <select allowClear="1" data-jquery="select2_custom_ddl" name="token_generator"
                            class="admin_category_sub form-control select2_custom_ddl" myValue="catchUp.token_generator"
                            myPlaceholder="Select Token Generator" data-ng-model="catchUpCtrl.catchUp.token_generator">
                            <option value="">--- Select ---</option>
                            <option value="hello">hello</option>
                            <option value="byy">byy</option>
                        </select>
                        <!-- </div> -->
                        <p class="error-msg" data-ng-show="errors.token_generator.has">
                            The token generator field is required.
                        </p>
                    </div>
                </div>

                <!-- is active -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <label style="margin-bottom: 0px;">
                                Enable
                            </label>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">{{ __('video::videos.inactive') }}</span>
                                <label class="switch">
                                    <input type="checkbox" name="is_active" ng-model="catchUpCtrl.catchUp.is_active">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">{{ __('video::videos.active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="bottom-button text-right flexbox align-items-center">
                    <input type="button" value="{{ trans('base::general.cancel') }}"
                        data-ng-click="catchUpCtrl.closeSubscriptionEdit()" name="cancel" class="save">
                    <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now">
                </div>

            </div>
        </form>
    </div>
</div>