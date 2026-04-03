<div class="tab-pane active" id="banner">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table banner-table" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                        <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                                <div class="ckbox ckbox-default">
                                    <input type="checkbox" id="selectall" value="1" data-ng-click="selectBulkRecords()" />
                                    <label for="selectall" class="nopadding"></label>
                                </div>
                                <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="selectedRecords != 0 && checkAccess('kidsbanner_all_write')"
                                    data-original-title="Select video in the grid to perform a bulk action">
                                    <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                        {{__('audio::general.bulk_action')}}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('delete-Popup')"
                                                href="#">{{__('audio::general.delete')}}</a>
                                        </li>
                                      
                                    </ul>
                                </div>
                            </th>
                    <th data-ng-repeat="field in heading">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords"
                    class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="selectRecord($event, record.id)"
                                value="@{{record.id}}" name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="product_img flexbox align-items-center">
                        <a ng-if="record.videos.title==null" href="#" class="table-image-text flexbox align-items-center">
                                <div class="image" bg-image="@{{record.banner_url}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                </div>
                                <div class="product_description">
                                    <p class="img_description">@{{record.videos.title}}
                                        <span class="tooltip_title">@{{record.videos.title}}</span>
                                    </p>
                                </div>
                            </a>
                            <a ng-if="record.videos.is_archived==0" href="{{url('admin/videos/view-details-video')}}/@{{ record.videos.id }}" class="table-image-text flexbox align-items-center tooltip-parent">
                                <div class="image" bg-image="@{{record.banner_url}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                </div>
                                <div class="product_description">
                                    <p class="img_description">@{{record.videos.title}}
                                        <span class="tooltip_title">@{{record.videos.title}}</span>
                                    </p>
                                </div>
                            </a>
                            <a ng-if="record.videos.is_archived==1" href="#" class="table-image-text flexbox align-items-center tooltip-parent disabled-cursor">
                                <div class="image" data-ng-if="record.banner_url.length == 0" style="background-image: url({{url('contus/base/images/no-preview.png')}})" >
                                </div>
                                <div class="image" data-ng-if="record.banner_url.length > 0" style="background-image: url(@{{record.banner_url}})" >
                                    </div>
                                <div class="product_description">
                                    <p class="img_description disabled-cursor">@{{record.videos.title}}
                                        <span class="tooltip_title">{{ __('cms::staticcontent.video_not_found')}}</span>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </td>
                    <td ng-hide="true">
                        <span class="type-label type_live  ng-scope" ng-if="record.videos.is_live == 1">{{trans('cms::staticcontent.live')}}</span>
                        <span class="type-label type_live  ng-scope" ng-if="record.videos.is_live == 0">{{trans('cms::staticcontent.uploaded')}}</span>
                    </td>
                    <td>@{{record.formatted_created_date}}</td>
                    <td>@{{record.banner_order}}</td>
                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                           
                            
                            <div data-ng-if="checkAccess('kidsbanner_all_write')" class="tooltips edit_table_icon tooltip-parent" data-boot-tooltip="true">
                                <button class="table_action sidepanel-open" data-ng-click="bannerCtrl.editStaticContent(record)">
                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                                </button>
                                <span  class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>
                            <div class="tooltip-parent" data-ng-if="checkAccess('kidsbanner_all_write')">
                            <span ng-mouseover="getTooltip($event)" title="" data-toggle="modal" data-target="#deleteModal"
                                ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon ng-scope"
                                data-boot-tooltip="true" >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" fill="#454545"></path>
                                        </g>
                                    </svg>
                            </span>
                            <span  class="tooltip_title">{{trans('base::general.delete')}}</span>
                            </div>
                         
                            
                            </div>
                            
                          
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @include('base::layouts.pagination')
</div>
<!-- To add or edit the lastest news  -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="bannerForm" method="POST" data-base-validator data-ng-submit="bannerCtrl.save($event,bannerCtrl.banner.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!bannerCtrl.banner.id">{{trans('cms::staticcontent.banner_heading')}} -
                    {{trans('cms::staticcontent.add_new_banner')}}</h5>
                <h5 data-ng-if="bannerCtrl.banner.id">{{trans('cms::staticcontent.banner_heading')}} -
                    {{trans('cms::staticcontent.edit_new_banner')}}</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group" ng-hide=false data-ng-class="{'has-error': errors.video_title.has}">
                    <label>
                        Video Title
                    </label>
                    <div class="form-input">
                    <input type="text" ng-model="bannerCtrl.banner.video_title" placeholder="Enter video title" class="form-control"  id="video_title" name="video_title" >
                    </div>
                    <p class="error-msg" data-ng-show="errors.video_title.has">@{{ errors.video.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.video.has}">
                    <label>
                        Video url
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                        <input type="text" class="list-repeat" selectize="singleConfig" ng-model="bannerCtrl.banner.video"
                            options="singlePreload" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.video.has">@{{ errors.video.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.type.has}" ng-hide=true>
                    <label>
                        {{trans('cms::staticcontent.type')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="radio" value="image" data-ng-model="bannerCtrl.banner.type">{{
                        trans('cms::staticcontent.image')}} <br>
                        <input type="radio" value="video" data-ng-model="bannerCtrl.banner.type">{{
                        trans('cms::staticcontent.video')}} <br>
                    </div>
                    <p class="error-msg" data-ng-show="errors.type.has">@{{ errors.description.message }}</p>
                </div>
            <!-- order start -->
                <div class="form-group" ng-hide=false>
                    <label>
                        Order
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                    <input type="number" ng-model="bannerCtrl.banner.banner_order" placeholder="Enter banner order" class="form-control"  id="banner_order" name="banner_order" min="1" max="15">

                    </div>
                    <!-- <p class="error-msg" data-ng-show="errors.type.has">@{{ errors.description.message }}</p> -->
                </div>

                <!-- Banner Image Upload -->
                <div class="form-group" data-ng-class="{'has-error': errors.banner_image.has}">
                    <label>{{ trans('cms::staticcontent.banner_image') }} 
                        <span class="required">*</span>
                    </label>
                   
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <p class="intimation">Allowed file extensions are jpeg, jpg, png and image resolution should be minimum 1500*560</p>
                        <div class="input-append">
                            <button class="subtitle_btn">
                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                    <g>
                                        <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                            fill="#ffffff"></path>
                                    </g>
                                </svg>
                                <span class="fileupload-new">{{trans('video::videos.select_image')}}</span>
                                <span class="fileupload-exists">{{trans('video::videos.change')}}</span>
                                <input type="file" id="banner-image" name="image" data-action="api/admin/kidsbanner/banner-image" />
                            </button>
                            <span class="fileupload-preview"></span>
                        </div>

                        <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload"
                            data-ng-click="bannerCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                        <p class="error-msg hide"></p>
                    </div>
                    <p class="error-msg" data-ng-show="errors.banner_image.has">@{{ errors.banner_image.message }}</p>
                    <div class="form-group">
                        <div class="clsFileUpload preview-image">
                            <img id="banner-image-preview-new" on-error="{{url('contus/base/images/no-preview.png')}}" data-ng-show="bannerCtrl.banner.banner_url" data-ng-src="@{{bannerCtrl.banner.banner_url}}">
                            <div id="banner-image-progress" class="hide clsProgressbar"></div>
                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                        </div>
                    </div>
                </div>
                

 <!-- mobile Banner Image Upload -->
        <div class="form-group" data-ng-class="{'has-error': errors.mobile_image.has}">
                    <label>Mobile banner image
                        <span class="required">*</span>
                    </label>
                   
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <p class="intimation">Allowed file extensions are jpeg, jpg, png and image resolution should be minimum 380*500</p>
                        <div class="input-append">
                            <button class="subtitle_btn">
                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                    <g>
                                        <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                            fill="#ffffff"></path>
                                    </g>
                                </svg>
                                <span class="fileupload-new">{{trans('video::videos.select_image')}}</span>
                                <span class="fileupload-exists">{{trans('video::videos.change')}}</span>
                                <input type="file" id="mobile-image" name="image" data-action="api/admin/kidsbanner/mobile-banner-image"  />
                            </button>
                            <span class="fileupload-preview"></span>
                        </div>

                        <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload"
                            data-ng-click="bannerCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                        <p class="error-msg hide"></p>
                    </div>
                    <p class="error-msg" data-ng-show="errors.mobile_image.has">@{{ errors.mobile_image.message }}</p>
                    <div class="form-group">
                        <div class="clsFileUpload preview-image">
                            <img id="mobile-banner-image-preview-new" on-error="{{url('contus/base/images/no-preview.png')}}" data-ng-show="bannerCtrl.banner.mobile_url" data-ng-src="@{{bannerCtrl.banner.mobile_url}}">
                            <div id="mobile-banner-image-progress" class="hide clsProgressbar"></div>
                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                        </div>
                    </div>
        </div>

<!-- mobile banner end -->


                <div id="fine-uploader-gallery" ng-show="bannerCtrl.banner.type=='video'">
                    <input type="hidden" name="video_image" id="postImage" data-ng-model="bannerCtrl.banner.video_image"
                        value="{{old('video_image')}}" />
                    <div ng-if="bannerCtrl.banner.type =='video'">
                        <video width="320" height="240" controls ng-src="@{{bannerCtrl.banner.video_image}}" src="@{{bannerCtrl.banner.video_image}}" />
                    </div>
                    <div>
                        <div id="upload_errors_wrap">
                            <h6 id="upload_error">{{ trans('video::videos.upload_error') }}</h6>
                        </div>
                        <h6 id="upload_title">
                        </h6>
                        <span>{{ trans('video::videos.note') }}</span>
                        <p class="intimation">{{ trans('video::videos.accepted_banner_video_formats') }}</p>
                        <p id="video_error">{{ trans('video::videos.select_valid_file') }}</p>
                        <p id="upload_percentage"></p>
                        <div class="upload_file_input">
                            <input type="file" class="filestyle" id="video" title="Click to Upload Banner Video" name="video"
                                data-buttonName="btn-primary">
                            <span>{{ trans('video::videos.browse_from_computer') }}</span>
                        </div>
                        <div id="video_upload_button_wrap" class="video_upload_div_btn">
                            <button class="btn btn-primary" type="button">{{ trans('video::videos.upload') }}</button>
                        </div>
                        <div class="col-xs-12 col-sm-12 progress-container">
                            <div id="progress-bar-wrap" class="progress progress-striped active">
                                <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                    <input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="bannerCtrl.closeUserEdit()" name="cancel" class="save" />
					<input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" data-ng-click="bannerCtrl.dataSubmit($event,bannerCtrl.banner.id)"/>
              
            </div>
        </form>
    </div>
</div>
<div id="loaderimg" style=" position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url('{{asset('adminview/assets/images/admin/pl.gif')}}') 
              50% 50% no-repeat rgb(0, 0, 0, 0.09); 
              display:none;
               "></div>
@include('audio::admin.common.bulkActionModal')
