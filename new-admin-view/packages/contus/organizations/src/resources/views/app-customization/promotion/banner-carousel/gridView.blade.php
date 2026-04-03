<!-- accordian -->
<div data-ng-if="noRecords">
    <div colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
        {{ trans('base::general.not_found') }}
    </div>
</div>

<div class="panel-group list-repeat" id="accordian-content-set" role="tablist" aria-multiselectable="true"
    style="border: 1px solid #eee; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2); border-radius: 5px; background-color: #fff;"
    data-ng-if="showRecords" data-ng-repeat="record in BannerCarouselrecords track by $index" data-ng-show="showRecords"
    data-intialize-sidebar="">

    <div class="panel panel-default" style="border-radius: 5px;">
        <div class="panel-heading d-flex" role="tab" id="heading-@{{ record.id }}">
            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                data-ng-click="ctzCtrl.fetchData(record)" href="#collapse-@{{ record.id }}" aria-expanded="false"
                aria-controls="collapse-@{{ record.id }}" class="collapsed"
                style="display: flex; align-items: center; text-decoration: none; color: #333;">
                <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;"></i>
                <label style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                    Banner Carousel for @{{ record.subscription_name }}
                </label>
            </a>

            <td class="table-actions">
                <div class="flexbox align-items-center justify-center">

                    <div class="column edit_table_icon tooltip-parent">
                        <label class="table_action">@{{ record.banners.length }} Item</label>
                        <span class="tooltip_title">{{ __('video::videos.edit_video') }}</span>
                    </div>

                    <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                        <label class="switch">
                            <input type="checkbox" ng-checked="record.banner_carousel_is_active == 1"
                                ng-click="Updatetoggledata(record, record.id)">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="tooltip-parent">
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
                </div>
            </td>

        </div>
    </div>

    <div id="collapse-@{{ record.id }}" class="panel-collapse collapse" role="tabpanel"
        aria-labelledby="heading-@{{ record.id }}">
        <div class="panel-body">
            <form name="banner_form" method="POST" data-base-validator enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" id="banner_id-@{{ record.id }}" value="@{{ record.id }}">

                <!-- Subscriptions -->
                <div class="form-group row" style="margin-bottom: 15px;">
                    <label for="prefix" class="col-sm-4 control-label"
                        style="font-size: 14px; color: #000; margin-top: 10px;">
                        Subscriptions<span class="required">*</span>:
                    </label>
                    <div class="col-sm-4">
                        <div class="form-input">
                            <select allowClear="1" data-jquery="select2_custom_ddl" name="subscription_name"
                                class="admin_category_sub form-control select2_custom_ddl"
                                myValue="record.subscription_name" myPlaceholder="Select Subscriptions"
                                data-ng-model="record.subscription_name">
                                <option value="">--- Select Subscriptions ---</option>
                                <option value="@{{ record.subscription_name }}">@{{ record.subscription_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="chip">
                            @{{ record.subscription_name }}
                            <span class="close" data-dismiss="chip">&times;</span>
                        </div>
                    </div>
                </div>

                <!-- Auto scrolling -->
                <div class="form-group row" style="margin-bottom: 15px;">
                    <label for="prefix" class="col-sm-4 control-label"
                        style="font-size: 14px; color: #000; margin-top: 10px;">
                        Auto Scrolling<span class="required">*</span>:
                    </label>
                    <div class="col-sm-4">
                        <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                            <label class="switch">
                                <input type="checkbox" data-ng-model="record.auto_scrolling"
                                    ng-checked="record.auto_scrolling == 1">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <div class="col-sm-2 m-auto">
                            <input type="number" class="form-control" name="name" required placeholder="Enter Seconds"
                                ng-model="record.second"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                        </div>
                        <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
                            Seconds
                        </label>
                    </div>
                </div>

                <!-- banner code -->
                <div id="banner-wrapper" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <div class="banner-card" ng-repeat="banner in record.banners track by $index"
                        style="width: 280px; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; position: relative; display: flex; flex-direction: column;"
                        data-ng-class="{'has-error': errors.poster_image.has}">

                        <!-- Image Area -->
                        <div class="thumbnail-image"
                            style="position: relative; height: 160px; width: 100%; background: #f0f0f0;">

                            <!-- Display Image -->
                            <img ng-show="banner.banner_image.length > 0" ng-src="@{{ banner.banner_image }}"
                                class="uploaded_img uploaded_img_@{{ banner.id }}" data-banner-id="@{{ banner.id }}"
                                style="width: 100%; height: 100%; object-fit: cover; display: block;">

                            <!-- Placeholder if no image -->
                            <div ng-show="banner.banner_image.length == 0"
                                class="uploaded_img uploaded_img_@{{ banner.id }}"
                                style="width: 100%; height: 100%; background: #ccc; display: block;"></div>

                            <!-- Overlay / File Upload -->
                            <div class="fileupload fileupload-new" data-provides="fileupload"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); transition: background 0.3s;">
                                <div class="input-append" style="text-align: center; width: 100%; height: 100%;">
                                    <div class="overlay-content"
                                        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                        <div class="input"
                                            style="position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.8); padding: 10px 20px; border-radius: 4px; background: rgba(0,0,0,0.5); cursor: pointer;">
                                            <span
                                                style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: #fff;">
                                                <i class="fa fa-pencil" style="margin-right: 5px;"></i> CHANGE BANNER
                                                IMAGE
                                            </span>
                                            <input type="file" class="uploadImg" name="image"
                                                data-banner-id="@{{ banner.id }}"
                                                file-change="ctzCtrl.onFileChange($index, $files)"
                                                style="position: absolute; top: 0; right: 0; margin: 0; opacity: 0; filter: alpha(opacity=0); font-size: 200px; height: 100%; width: 100%; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner Label (Overlay at bottom) -->
                            <div
                                style="position: absolute; bottom: 10px; width: 100%; text-align: center; color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 1px 2px rgba(0,0,0,0.8);">
                                BANNER @{{ $index + 1 }}
                            </div>

                        </div>

                        <!-- Footer Actions -->
                        <div class="banner-footer"
                            style="background: #e9ecef; padding: 12px 15px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #dee2e6;">

                            <!-- Left: Status Toggle -->
                            <div class="form-group row" style="margin-bottom: 0px;">
                                <label class="switch" style="margin-bottom: 0;">
                                    <input type="checkbox" ng-model="banner.banner_is_active" ng-true-value="1"
                                        ng-false-value="0">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <!-- Center: Status Text -->
                            <div class="status-text">
                                <span class="badge" ng-if="banner.banner_is_active == 1"
                                    style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 5px 12px; border-radius: 12px; font-weight: 600; font-size: 12px;">Active</span>
                                <span class="badge" ng-if="banner.banner_is_active == 0"
                                    style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 5px 12px; border-radius: 12px; font-weight: 600; font-size: 12px;">Inactive</span>
                            </div>

                            <div class="flexbox align-items-center justify-center">
                                <div class="column edit_table_icon tooltip-parent">
                                    <button data-ng-click="ctzCtrl.addBannerCarouselSubscription(banner, record)">
                                        <svg viewBox=" 0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path
                                                    d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>
                                    <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                                </div>

                                <div class="tooltip-parent">
                                    <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                        data-target="#deleteModal" ng-click="ctzCtrl.removeBanner(banner)"
                                        class="tooltips delete_table_icon" data-boot-tooltip="true"
                                        data-original-title="">
                                        <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                            <g data-original-title="" title="">
                                                <path
                                                    d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                    fill="#454545">
                                                </path>
                                            </g>
                                        </svg>
                                        <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- <div class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="bcscCtrl.edit(record, record.id)">
                                    <svg viewBox=" 0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>

                            <div class="tooltip-parent banner-remove"
                                style="cursor: pointer; display: flex; align-items: center;"
                                ng-click="ctzCtrl.removeBanner(banner)">
                                <span class="tooltips delete_table_icon" title="{{ trans('base::general.delete') }}">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="14px" height="15px"
                                        style="display: block; opacity: 0.6; transition: opacity 0.2s;">
                                        <g>
                                            <path
                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div> -->

                        </div>
                    </div>

                    <!-- Add Banner Button -->
                    <div class="add-banner"
                        ng-click="ctzCtrl.banners = record.banners = record.banners || []; ctzCtrl.addBanner()"
                        ng-if="!record.banners || record.banners.length < ctzCtrl.maxBanners"
                        style="width: 280px; height: 212px; background: #fff; border: 2px dashed #ccc; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; color: #888; transition: all 0.3s; margin-left: 0;">
                        <div style="font-size: 32px; font-weight: 300; margin-bottom: 5px;">+</div>
                        <div style="font-size: 14px; font-weight: 500;">Add Banner</div>
                    </div>
                </div>

                <!-- bottom button code -->
                <div class="bottom-button text-right" style="margin-top: 30px;">
                    <button data-ng-click="ctzCtrl.UpdateRecord($event, record.id)" class="publish-now">
                        Update
                    </button>&nbsp;&nbsp;&nbsp;

                    <button data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)"
                        class="button button-red">
                        Delete
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@include('audio::admin.common.singleRecordDeleteModal')
@include('audio::admin.common.singleRecordStatusUpdateModal')
@include('base::layouts.pagination')