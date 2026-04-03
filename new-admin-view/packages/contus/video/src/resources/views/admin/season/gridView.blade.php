<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table playlist_table" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="selectall" value="1" data-ng-click="seasongridCtrl.selectAllRecords()" />
                            <label for="selectall" class="nopadding"></label>
                        </div>
                        <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="seasongridCtrl.selectedRecords != 0 && checkAccess('season_all_write')"
                            data-original-title="Select video in the grid to perform a bulk action">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                {{__('video::videos.bulk_action')}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="seasongridCtrl.deleteBulkRecord()"
                                        href="#">{{__('video::videos.delete')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="seasongridCtrl.activateOrDeactivateBulkRecord('activate')"
                                        href="#">{{__('video::videos.activate')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="seasongridCtrl.activateOrDeactivateBulkRecord('deactivate')"
                                        href="#">{{__('video::videos.deactivate')}}</a>
                                </li>
                            </ul>
                        </div>
                    </th>
                    <th data-ng-repeat="field in heading">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="search_text">
                <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" title="{{trans('video::season.enter_season_name')}}">
                    </td>
                    <td>

                        <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                <option value="all">{{__('base::general.all')}}</option>
                                <option value='1'>{{__('video::playlist.banner.active')}}</option>
                                <option value='0'>{{__('video::playlist.banner.inactive')}}</option>
                        </select>
                    </td>
                    <td></td>
                    <td></td>
                    <!-- <td class="center">
                        <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" title="{{trans('base::general.search_filter')}}">
                            <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </td> -->
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="seasongridCtrl.selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td class="serial_number">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td>@{{record.title}}</td>
                    <td>

                        <div class="tooltip-parent" data-ng-if="checkAccess('season_all_write')">
                            <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"  data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="seasongridCtrl.statusChangeSingleRecord(record)"    data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span>
                            <span class="tooltip_title">{{trans('video::season.deactivate_season')}}</span>
                        </div>
                        <div class="tooltip-parent" data-ng-if="checkAccess('season_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;" data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="seasongridCtrl.statusChangeSingleRecord(record)"   data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span>
                            <span class="tooltip_title">{{trans('video::season.activate_season')}}</span>
                        </div>

                        
                        

                    </td>
                    <td>@{{ record.formatted_created_date }}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            <div data-ng-if="checkAccess('season_all_write')" id="st-trigger-effects" class="column edit_table_icon tooltip-parent">
                                <button data-boot-tooltip="true" data-effect="st-effect-18" data-intialize-sidebar  data-ng-click="seasongridCtrl.getgroupEdit(record)" class="table_action sidepanel-open">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                            <div data-ng-if="checkAccess('season_all_write')" class="tooltip-parent">
                                <span ng-mouseover="getTooltip($event)"  data-toggle="modal" data-target="#videoDeleteModal" ng-click="seasongridCtrl.deleteSingleRecordVideos(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                            </div>

                            
                            

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @include('base::layouts.pagination')
</div>
<!-- To Edit the playlist  -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="groupForm" id="seasonForm" method="POST" data-base-validator
            data-ng-submit="seasongridCtrl.save($event, seasongridCtrl.season.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!seasongridCtrl.season.id">{{ trans('video::season.add_season') }} </h5>
                <h5 data-ng-if="seasongridCtrl.season.id">{{ trans('video::season.edit_season') }} </h5>
                <div class="right-side" data-ng-if="seasongridCtrl.season.id">
                    {{-- <select class="edit-select-lang" data-ng-change="seasongridCtrl.languageChange()"  data-ng-model="seasongridCtrl.seasonTranslation.language">
                        <option data-ng-repeat="language in seasongridCtrl.languages track by $index" value="@{{ language.id }}">@{{ language.title }}</option>
                    </select> --}}
                    <select minimumResults="-1" data-jquery="select2_custom_ddl" name="language"
                        class="select2_custom_ddl" ng-change="seasongridCtrl.languageChange()"
                        myValue="seasongridCtrl.seasonTranslation.language"
                        data-ng-model="seasongridCtrl.seasonTranslation.language"
                        data-ng-options="a.id as a.title  for a in seasongridCtrl.languages "></select>
                </div>
            </div>
            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                    <label>
                        {{ trans('video::season.season_name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="title" class="form-control" data-validation-name="Season name"
                            data-ng-model="seasongridCtrl.season.title"
                            placeholder="{{ trans('video::season.enter_season_name') }}"
                            value="{{ old('title') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                </div>

                <!-- <div class="form-group" data-ng-class="{'has-error': errors.season_order.has}">
                    <label>
                        {{ trans('video::season.season_order') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="number" min="1" name="season_order" class="form-control" data-validation-name="Season Name" data-ng-model="seasongridCtrl.season.season_order" placeholder="{{ trans('video::season.enter_season_order') }}" value="{{ old('order') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.season_order.has">@{{ errors.season_order.message }}</p>
                </div> -->
                <!-- <div class="form-group">
                    <label>{{ trans('video::videos.status') }} </label>
                    <div class="form-input">
                        <select class="form-control" name="is_active" data-ng-model="seasongridCtrl.season.is_active">
                            <option value="1">{{ trans('video::videos.message.active') }}</option>
                            <option value="0">{{ trans('video::videos.message.inactive') }}</option>
                        </select>
                    </div>
                </div> -->

                <!-- image upload start -->
                <div class="form-group" data-ng-class="{'has-error': errors.image.has}">
                    <label>Season image</label>
                    <div class="form-input">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <button class="subtitle_btn">
                                    <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px"
                                        height="15px">
                                        <g>
                                            <path
                                                d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                                fill="#ffffff"></path>
                                        </g>
                                    </svg>
                                    <span class="fileupload-new">Upload Image</span>
                                    <span class="fileupload-exists">Change Image</span>
                                    <input type="file" id="category-image" name="image"
                                        accept="image/x-png,image/jpeg"
                                        data-action="api/admin/categories/category-image" />
                                </button>

                                <span class="fileupload-preview"></span>
                            </div>

                            <a href="#" class="fileupload-exists category-image-remove"
                                data-dismiss="fileupload"
                                data-ng-click="seasongridCtrl.removeThumbnailProperty($event, seasongridCtrl.season.id)">{{ trans('video::videos.remove') }}</a>
                            <p class="help-block hide"></p>
                        </div>
                    </div>
                    <!-- <p class="help-block" data-ng-show="errors.image.has">@{{ errors.image.message }}</p> -->
                    <p class="error-msg" data-ng-show="errors.image.has">@{{ errors.image.message }}</p>
                    <div class="form-group">
                        <div class="clsFileUpload preview-image">
                            <span id="category-image-delete" data-ng-click="seasongridCtrl.deleteCategoryImage()"
                                data-ng-show="seasongridCtrl.season.image" data-boot-tooltip="true"
                                title="Click to delete the image of the season.">
                                <!-- <i class="fa fa-remove" aria-hidden="true"></i> -->
                            </span>
                            <!-- @{{ seasongridCtrl.season.image }} -->
                            <img id="category-image-preview" data-ng-show="seasongridCtrl.season.image"
                                data-ng-src="@{{ seasongridCtrl.season.image }}" width="280px" height="100px">
                            <div id="category-image-progress" class="hide clsProgressbar "></div>
                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                        </div>
                    </div>
                </div>
                <!-- image upload end -->

                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                            <g>
                                <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
                            </g>
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>{{ trans('video::videos.status') }}</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" ng-model="seasongridCtrl.season.is_active" name="status">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                    <p class="error-msg"></p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <a class="save" data-ng-click="seasongridCtrl.closegroupEdit()">
                    {{ trans('base::general.cancel') }}
                </a>
                <button class="publish-now submitbutton">
                    {{ trans('base::general.submit') }}
                </button>
            </div>
        </form>

        <form name="groupForm" style="display:none;" id="seasonTranslationForm" method="POST" data-base-validator data-ng-submit="seasongridCtrl.seasonTranslationSave($event, seasongridCtrl.season.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!seasongridCtrl.season.id">{{ trans('video::season.add_season')}} </h5>
                <h5 data-ng-if="seasongridCtrl.season.id">{{ trans('video::season.edit_season')}} </h5>
                <div class="right-side"  data-ng-if="seasongridCtrl.season.id">
                    {{-- <select class="edit-select-lang" data-ng-change="seasongridCtrl.languageChange()"  data-ng-model="seasongridCtrl.seasonTranslation.language">
                        <option data-ng-repeat="language in seasongridCtrl.languages track by $index" value="@{{language.id}}">@{{language.title}}</option>
                    </select> --}}
                    <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="seasongridCtrl.languageChange()" myValue="seasongridCtrl.seasonTranslation.language" data-ng-model="seasongridCtrl.seasonTranslation.language"  data-ng-options="a.id as a.title  for a in seasongridCtrl.languages " ></select>
                </div>
            </div>
            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.title1.has}">
                    <label>
                        {{trans('video::season.season_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class='form-input'>
                        <input type="text" name="title" class="form-control" data-validation-name="Season Name" data-ng-model="seasongridCtrl.season.title" placeholder="{{trans('video::season.season_name')}}" disabled value="{{old('title')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.title1.has">@{{ errors.title.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.trans_title.has}">
                    <label>
                        {{trans('video::season.season_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="trans_title" class="form-control" data-validation-name="Season Name" data-ng-model="seasongridCtrl.seasonTranslation.title" placeholder="{{trans('video::season.season_name')}}" value="{{old('title')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.trans_title.has">@{{ errors.trans_title.message }}</p>
                </div>

            </div>
            <div class="bottom-button text-right flexbox align-items-center">
                <button class="save" data-ng-click="seasongridCtrl.closegroupEdit()">
                    {{ trans('base::general.cancel') }}
                </button>
                <button class="publish-now submitbutton">
                    {{trans('base::general.submit')}}
                </button>
            </div>
        </form>
    </div>
</div>
