<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table playlist_table" id="fixTable">
            <thead>
                <tr>
                    <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="selectall" value="1" data-ng-click="groupCtrl.selectAllRecords()" />
                            <label for="selectall" class="nopadding"></label>
                        </div>
                        <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="groupCtrl.selectedRecords != 0 && checkAccess('genre_all_write')"
                            data-original-title="Select video in the grid to perform a bulk action">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                {{__('video::videos.bulk_action')}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="groupCtrl.deleteBulkRecord()"
                                        href="#">{{__('video::videos.delete')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="groupCtrl.activateOrDeactivateBulkRecord('activate')"
                                        href="#">{{__('video::videos.activate')}}</a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="groupCtrl.activateOrDeactivateBulkRecord('deactivate')"
                                        href="#">{{__('video::videos.deactivate')}}</a>
                                </li>
                            </ul>
                        </div>
                    </th>
                    <th data-ng-repeat="field in heading" ng-class="{'centre': field.name == 'Order'|| field.name == 'Added On'}">
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
                        <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" title="{{trans('video::playlist.enter_group_name')}}">
                    </td>
                    <td></td>
                    <td>
                            <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                    <option value="all">{{__('base::general.all')}}</option>
                                    <option value='1'>{{__('video::playlist.banner.active')}}</option>
                                    <option value='0'>{{__('video::playlist.banner.inactive')}}</option>
                            </select>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="groupCtrl.selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td class="serial_number">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td> @{{record.name}}
                    </td>
                    <td class="center">
                        <p>@{{record.order}}</p>
                    </td>
                    <td>
                     
                        <div class="tooltip-parent" data-ng-if="checkAccess('genre_all_write')">
                            <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"  data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="groupCtrl.statusChangeSingleRecord(record)"   data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span>
                            <span class="tooltip_title">{{trans('video::collection.deactivate_collection')}}</span>
                        </div>
                        <div class="tooltip-parent"  data-ng-if="checkAccess('genre_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"  data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="groupCtrl.statusChangeSingleRecord(record)"  data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span>
                          
                            <span class="tooltip_title">{{trans('video::collection.activate_collection')}}</span>
                        </div>
                       
                        <div class="tooltip-parent"  data-ng-if="!checkAccess('genre_all_write')">
                            <button  disabled class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"><span  data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span></button>
                            <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                        </div>
                        <div class="tooltip-parent"  data-ng-if="!checkAccess('genre_all_write')">
                            <button  disabled class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"><span   data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span></button>
                            <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                        </div>
                      
                    </td>
                    <td class="center">@{{ record.formatted_created_date }}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                           
                            <div  data-ng-if="checkAccess('genre_all_write')" id="st-trigger-effects" class="column edit_table_icon tooltip-parent">
                                <button data-boot-tooltip="true" data-effect="st-effect-18" data-intialize-sidebar  data-ng-click="groupCtrl.getgroupEdit(record)" class="table_action sidepanel-open">
                                <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                    <g>
                                        <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                    </g>
                                </svg>
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                                </button>
                            </div>
                            <div class="tooltip-parent"  data-ng-if="checkAccess('genre_all_write')">
                                <span ng-mouseover="getTooltip($event)"  data-toggle="modal" data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>  
                                </span>
                                <span class="tooltip_title">{{trans('base::general.delete')}}</span>
                            </div>
                           
                            <div  data-ng-if="!checkAccess('genre_all_write')" id="st-trigger-effects" class="column edit_table_icon tooltip-parent">
                                    <button  disabled data-effect="st-effect-18"  class="table_action sidepanel-open">
                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px" aria-hidden="true"  data-boot-tooltip="true">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                            </g>
                                        </svg>
                                    </button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>

                            </div>
                            <div  data-ng-if="!checkAccess('genre_all_write')" class="tooltip-parent">
                                <button disabled class="delete_table_icon" >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px"   aria-hidden="true" data-boot-tooltip="true">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
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
        
        <form name="groupForm" id="groupForm" method="POST" data-base-validator data-ng-submit="groupCtrl.examgroupsave($event, groupCtrl.group.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!groupCtrl.group.id">{{trans('video::playlist.add_genre')}}</h5>
                <h5 data-ng-if="groupCtrl.group.id">{{trans('video::playlist.edit_genre')}}</h5>
                <div class="right-side" data-ng-if="groupCtrl.group.id"> 
                    {{-- <select data-jquery="select2_custom_ddl" class="edit-select-lang select2_custom_ddl" data-ng-change="groupCtrl.languageChange()" data-ng-model="groupCtrl.groupTranslation.language">
                        <option data-ng-repeat="language in groupCtrl.languages track by $index" value="@{{language.id}}">@{{language.title}}</option>
                    </select> --}}
                    <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="groupCtrl.languageChange()" myValue="groupCtrl.groupTranslation.language" data-ng-model="groupCtrl.groupTranslation.language"  data-ng-options="a.id as a.title  for a in groupCtrl.languages " ></select>
                </div>
            </div>

            <div class="sidepanel-scroll">                
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{trans('video::playlist.group_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" class="form-control"  data-unique="@{{groupCtrl.uniqueRoute}}" data-ng-model="groupCtrl.group.name" placeholder="{{trans('video::playlist.group_name')}}" value="{{old('name')}}" />
                    </div>                    
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>

                <div class="form-group" ng-hide=true data-ng-class="{'has-error': errors.category.has}">
                    <label>
                        Sub Genre
                        <span class="required">*</span>
                    </label>
					
                    <ul data-ng-repeat="cat in groupCtrl.category">
                        <li>
                            <input name="collection_id" type="radio" data-validation-name="Exam Name" data-ng-model="groupCtrl.group.collection_id" value="@{{cat.id}}" class="radio_prod" />
                            @{{cat.title}}
                        </li>
                    </ul>
                    <p class="error-msg" data-ng-show="errors.category.has">@{{ errors.category.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.order.has}">
                    <label>
                        {{trans('video::playlist.group_order')}}
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                        <input type="number" min="0" name="order" class="form-control" data-validation-name="Group Name" data-unique="@{{groupCtrl.uniqueRoute}}" data-ng-model="groupCtrl.group.order" placeholder="{{trans('video::playlist.group_order')}}" value="{{old('order')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.order.has">@{{ errors.order.message }}</p>
                </div>

                <!-- <div class="form-group">
                    <label>{{ trans('video::videos.status') }} </label>
                    <div class="form-input">
                        <select class="form-control" name="is_active" data-ng-model="groupCtrl.group.is_active">
                            <option value="1">{{ trans('video::videos.message.active') }}</option>
                            <option value="0">{{ trans('video::videos.message.inactive') }}</option>
                        </select>
                    </div>
                </div> -->

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
                                    <input type="checkbox" data-ng-model="groupCtrl.group.is_active" name="status">
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
                <a class="save" ng-click="groupCtrl.closegroupEdit()">
                    {{ trans('base::general.cancel') }}
                </a>
                <button class="publish-now">
                    {{trans('base::general.submit')}}
                </button>
            </div>
        </form>

        <form name="groupForm" style="display:none;" id="groupTranslationForm" method="POST" data-base-validator data-ng-submit="groupCtrl.examgroupTranslationsave($event, groupCtrl.group.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!groupCtrl.group.id">{{trans('video::playlist.add_genre')}}</h5>
                <h5 data-ng-if="groupCtrl.group.id">{{trans('video::playlist.edit_genre')}}</h5>
                <div class="right-side" data-ng-if="groupCtrl.group.id"> 
                   
                    <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="groupCtrl.languageChange()" myValue="groupCtrl.groupTranslation.language" data-ng-model="groupCtrl.groupTranslation.language"  data-ng-options="a.id as a.title  for a in groupCtrl.languages " ></select>
                </div>
            </div>
            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.name1.has}">
                    <label>
                        {{trans('video::playlist.group_name')}}
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" data-validation-name="Group Name" data-unique="@{{groupCtrl.uniqueRoute}}" data-ng-model="groupCtrl.group.name" placeholder="{{trans('video::playlist.group_name')}}" disabled value="{{old('name')}}" />
                    <p class="error-msg" data-ng-show="errors.name1.has">@{{ errors.name.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.trans_name.has}">
                    <label>
                        {{trans('video::playlist.group_name')}}
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="trans_name" class="form-control" data-validation-name="Group Name" data-unique="@{{groupCtrl.uniqueRoute}}" data-ng-model="groupCtrl.groupTranslation.name" placeholder="{{trans('video::playlist.group_name')}}" value="{{old('name')}}" />
                    <p class="error-msg" data-ng-show="errors.trans_name.has">@{{ errors.trans_name.message }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <button class="save" data-ng-click="groupCtrl.closegroupEdit()">
                    {{ trans('base::general.cancel') }}
                </button>
                <button class="publish-now submitbutton">
                    {{trans('base::general.submit')}}
                </button>
            </div>
        </form>
    </div>
</div>