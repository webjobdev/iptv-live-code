<div class="panel main_container">
    <div id="latest_video">
        <div class="tab_search clearfix" style="display:none;">
            <a href="{{url('admin/youtube-live')}}" ng-if="livedetails.updated_at" data-boot-tooltip="true" data-toggle="tooltip"
                data-original-title="@{{(livedetails.status)?'Synced':'Sync Failed'}} @{{livedetails.updated_at}}@{{(livedetails.status)?'':', Please sync manually to re-initiate sync'}}"
                class="btn " ng-class="{'btn-info':livedetails.status,'btn-danger':!livedetails.status}">{{__('video::videos.synclivevideos')}}
                (@{{(livedetails.status)?'Synced':'Sync Failed'}} @{{livedetails.updated_at}})</a>
            <a ng-hide="true" href="{{url('admin/youtube-live')}}" ng-if="!livedetails.updated_at" class="btn btn-info "
                ng-class={}>{{__('video::videos.synclivevideos')}}</a>

        </div>
        <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
            <div class="table_loader">
                <div class="loader"></div>
            </div>
        </div>
        <div class="table_responsive" id="fixTable_parent">
            <table class="table tablesaw" id="fixTable" data-tablesaw-mode="columntoggle" data-ng-class="{'no-records': noRecords}">
                <thead>
                    <tr>
                        <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                       <!-- Check Menu Flag  -->
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" id="selectall" value="1" data-ng-click="vgridCtrl.selectAllRecords()" />
                                <label for="selectall" class="nopadding"></label>
                            </div>

                            @if ($page === 'radio')
                            <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="vgridCtrl.selectedRecords != 0 && checkAccess('radio_all_write')"
                                data-original-title="Select video in the grid to perform a bulk action">
                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{__('video::videos.bulk_action')}}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.editBulkRecord()"
                                        href="#">{{__('video::videos.edit')}}</a>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.deleteBulkRecord()"
                                            href="#">{{__('video::videos.delete')}}</a>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.activateOrDeactivateBulkRecord('activate')"
                                            href="#">{{__('video::videos.activate')}}</a>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.activateOrDeactivateBulkRecord('deactivate')"
                                            href="#">{{__('video::videos.deactivate')}}</a>
                                    </li>
                                </ul>
                            </div>
                            @endif
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
                    <tr  class="search_text">
                        <td></td>
                        <td></td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.title"
                                data-boot-tooltip="true" title="{{__('video::videos.enter_video_title')}}">
                        </td>
                        <td class="vod-grid-categ">
                            <input type="text" class="form-control" data-ng-model="searchRecords.category"
                                data-boot-tooltip="true" title="{{__('video::videos.enter_video_category')}}">
                        </td>
                        <!-- <td class="genre_search">
                            <input type="text" class="form-control" data-ng-model="searchRecords.genre"
                                data-boot-tooltip="true" title="Enter Genre Value">
                        </td> -->

                        <td class="search_product">
                            <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.hide_web" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                    <option value="all">{{__('base::general.all')}}</option>
                                    <option value='1'>Web Hide</option>
                                    <option value='0'>Web Show</option>
                            </select>
                        </td>

                        <td class="search_product">
                            <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                    <option value="all">{{__('base::general.all')}}</option>
                                    <option value='1'>{{__('video::collection.banner.active')}}</option>
                                    <option value='0'>{{__('video::collection.banner.inactive')}}</option>
                            </select>

                        </td>

                    </tr>
                    <tr data-ng-if="noRecords">
                        <td colspan="@{{heading.length + 2}}" class="no-data center">{{__('base::general.not_found')}}</td>
                    </tr>
                    <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords"
                        class="list-repeat" data-intialize-sidebar="">
                        <td>
                              <!-- Check Menu Flag  -->
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="vgridCtrl.selectRecord($event, record.id)"
                                    value="@{{record.id}}" name="selectedCheckbox[]">
                                <label for="roles_@{{record.id}}"></label>
                            </div>
                        </td>
                        <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                        <td>
                            <div class="product_img flexbox align-items-center" >
                                <a ng-if="record.is_live==0" href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="table-image-text flexbox align-items-center">
                                    <div class="image" bg-image="@{{record.thumbnail_image}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                    </div>
                                    <div class="product_description tooltip-parent "  data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                        <p  class="img_description" >@{{ record.title}}
                                            <span class="tooltip_title">@{{record.title}}</span>
                                        </p>
                                    </div>
                                </a>
                                <a ng-if="record.is_live==2" href="#" class="table-image-text flexbox align-items-center">
                                    <div class="image" bg-image="@{{record.thumbnail_image}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                    </div>
                                    <div class="product_description tooltip-parent "  data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                        <p  class="img_description" >@{{ record.title}}
                                            <span class="tooltip_title">@{{record.title}}
                                            @{{record.categories[0].title}}
                                            </span>
                                        </p>
                                    </div>
                                </a>
                                <a ng-if="record.is_live==1" href="{{url('admin/livevideos/view-details-video')}}/@{{record.id}}" class="table-image-text flexbox align-items-center">
                                    <div class="image" bg-image="@{{record.thumbnail_image}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                                    </div>
                                    <div class="product_description tooltip-parent "  data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                        <p  class="img_description" >@{{ record.title}}
                                            <span class="tooltip_title">@{{record.title}}</span>
                                        </p>
                                    </div>
                                </a>
                                <div class="live-server-grid-info" ng-if="record.is_live == 1 && record.username != 'wowza'">
                                    <button data-toggle="modal" data-target="#deleteModals@{{ record.id }}">
                                        <svg viewBox="0 0 15 14" x="0px" y="0px" width="15px" height="14px">
                                            <g>
                                                <path d="M 7.4996 -0.0004 C 3.634 -0.0004 0.4996 3.1341 0.4996 6.9997 C 0.4996 10.8655 3.634 13.9999 7.4996 13.9999 C 11.3655 13.9999 14.4999 10.8655 14.4999 6.9997 C 14.4999 3.1341 11.3655 -0.0004 7.4996 -0.0004 ZM 8.9568 10.8488 C 8.5969 10.9906 8.3098 11.0988 8.0945 11.1735 C 7.8804 11.2481 7.6307 11.2857 7.3469 11.2857 C 6.9105 11.2857 6.571 11.1781 6.3292 10.9663 C 6.0875 10.7533 5.9672 10.4836 5.9672 10.1561 C 5.9672 10.0287 5.9761 9.8979 5.9938 9.7654 C 6.0122 9.6329 6.0412 9.4836 6.0808 9.3157 L 6.532 7.7231 C 6.5716 7.5691 6.6061 7.4239 6.6333 7.2888 C 6.6605 7.1524 6.6736 7.0274 6.6736 6.9138 C 6.6736 6.7112 6.6315 6.5688 6.5479 6.489 C 6.4631 6.4088 6.3037 6.3697 6.0662 6.3697 C 5.9499 6.3697 5.8303 6.3868 5.7075 6.423 C 5.5861 6.4603 5.4805 6.4942 5.3939 6.5274 L 5.5131 6.0366 C 5.8083 5.9163 6.0909 5.8132 6.3607 5.7279 C 6.6304 5.6414 6.885 5.5985 7.1251 5.5985 C 7.5583 5.5985 7.8925 5.7042 8.1281 5.9128 C 8.3619 6.122 8.48 6.394 8.48 6.7283 C 8.48 6.7977 8.4718 6.9198 8.4557 7.094 C 8.4394 7.2688 8.4095 7.4279 8.3654 7.5749 L 7.9169 9.1629 C 7.8804 9.2908 7.8475 9.4361 7.8179 9.5987 C 7.7887 9.7619 7.7745 9.8864 7.7745 9.9709 C 7.7745 10.181 7.8214 10.325 7.9163 10.402 C 8.01 10.4784 8.1745 10.5172 8.4059 10.5172 C 8.5158 10.5172 8.6385 10.4975 8.7775 10.4587 C 8.9152 10.4211 9.0147 10.387 9.0772 10.3581 L 8.9568 10.8488 ZM 8.8776 4.4023 C 8.6686 4.5965 8.4164 4.6937 8.1218 4.6937 C 7.8283 4.6937 7.5743 4.5965 7.3634 4.4023 C 7.1536 4.2079 7.0472 3.9714 7.0472 3.6951 C 7.0472 3.4196 7.1541 3.1827 7.3634 2.9865 C 7.5743 2.7897 7.8283 2.6919 8.1218 2.6919 C 8.4164 2.6919 8.6686 2.7897 8.8776 2.9865 C 9.0865 3.1827 9.1918 3.4196 9.1918 3.6951 C 9.1918 3.972 9.0865 4.2079 8.8776 4.4023 Z"
                                                    fill="#ffb717" />
                                            </g>
                                        </svg>
                                    </button>
                                    {{--Modal Starting--}}
                                        <div class="modal fade" id="deleteModals@{{record.id}}" data-role="dialog">
                                            <div class="live_credentials_popup modal-transition">
                                                <div class="live_popup_head">
                                                    <h3 class="modal-title">{{__('video::videos.wowza_live_credentials')}}</h3>
                                                </div>
                                                <div class="live_popup_content">
                                                    <div class="live_popup_list flexbox align-items-center">
                                                        <span class="live_popup_label">{{__('video::videos.wowza_push_url')}}</span>
                                                        <span class="live_popup_url">@{{record.source_url}}</span>
                                                    </div>
                                                    <div class="live_popup_list flexbox align-items-center">
                                                        <span class="live_popup_label">{{__('video::videos.source_name')}}</span>
                                                        <span class="live_popup_url">@{{record.stream_name}}</span>
                                                    </div>
                                                    <div class="live_popup_list flexbox align-items-center">
                                                        <span class="live_popup_label">Stream ID</span>
                                                        <span class="live_popup_url">@{{record.stream_id}}</span>
                                                    </div>
                                                    <!-- <div class="live_popup_list flexbox align-items-center">
                                                        <span class="live_popup_label">{{__('video::videos.username')}}</span>
                                                        <span class="live_popup_url">@{{record.username}}</span>
                                                    </div>
                                                    <div class="live_popup_list flexbox align-items-center">
                                                        <span class="live_popup_label">{{__('video::videos.password')}}</span>
                                                        <span class="live_popup_url">@{{record.password}}</span>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    {{--Modal Ending--}}
                                </div>
                            </div>
                        </td>
                        <td>
                        <span ng-if="record.is_live==2" >
                                        <!-- <p  class="img_description" > @{{record.categories[0].title}}
                                        </p> -->
                                        <div data-ng-repeat="category in record.videocategory track by $index">
                                <span class="capitalize">@{{ category.category.title }}</span>
                                <span data-ng-if="record.videocategory.length != $index+1">,</span>
                            </div>
                                </span>
                        </td>


                        <!-- <td ng-if="record.is_live == 2" class="vod-grid-categ">
                            <div data-ng-repeat="genre in record.collections track by $index">
                                <span class="capitalize">@{{ genre.name }}</span>
                                <span data-ng-if="record.collections.length != $index+1">,</span>
                            </div>
                        </td> -->


                        <td>
                            <div class="tooltip-parent" >
                                <span ng-if="record.hide_web == 1" >
                                <b style="color: green;">Active</b> - Mobile, TV<br>
                                <b style="color: red;">In Active</b> - Website
                                </span>
                                <span ng-if="record.hide_web != 1" >
                                <b style="color: green;">Active</b> - Website, Mobile, TV</span>
                            </div>
                        </td>
                       

                        <td>
                            <div class="tooltip-parent" data-ng-if="checkAccess('radio_all_write')">
                                <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"  data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="vgridCtrl.statusChangeSingleRecord(record)"
                                >{{__('video::videos.message.active')}}</span>
                               <span class="tooltip_title">{{__('video::videos.deactivate_video')}}</span>
                            </div>
                            <div class="tooltip-parent" data-ng-if="checkAccess('radio_all_write')">
                            <span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"
                            data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="vgridCtrl.statusChangeSingleRecord(record)"
                               >{{ __('video::videos.message.inactive')}}</span>
                               <span class="tooltip_title">{{__('video::videos.activate_video')}}</span>
                            </div>

                            <div class="tooltip-parent"  data-ng-if="!checkAccess('radio_all_write')">
                                <button  disabled class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"><span  data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span></button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                            </div>
                            <div class="tooltip-parent"  data-ng-if="!checkAccess('radio_all_write')">
                                <button  disabled class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"><span   data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span></button>
                                <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                            </div>
                        </td>
                        <td ng-if="record.is_live === 0"class="center">@{{ record.price| number:2 }}</td>
                        <td ng-if="record.is_live == 1">@{{ record.scheduledStartTime }}</td>

                        <!-- <td class="center">@{{ record.view_count }}</td>
                        <td class="center">@{{ record.like_count }}</td>
                        <td class="center">@{{ record.dislike_count }}</td>
                        <td class="center">@{{ record.favourite_count }}</td>
                        <td class="center">@{{ record.comments_count }}</td>
                        <td>@{{ record.formatted_created_date }}</td>
                        <td>@{{ record.user.name }}</td>
                        <td>@{{ record.formatted_published_date }}</td> -->
                        <td class="table-action">
                            <div class="flexbox align-items-center justify-center">

                                <div ng-if="record.is_live==0" class="column edit_table_icon tooltip-parent">
                                    <a  class="table_action" href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">
                                        <svg x="0px" y="0px" viewBox="0 0 511.999 511.999" width="12px" fill="#454545">
                                            <g>
                                                <g>
                                                    <path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
                                                        c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
                                                        C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
                                                        c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
                                                        C447.361,287.923,358.746,385.406,255.997,385.406z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
                                                        s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
                                                        s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.view_video')}}</span>
                                </div>


                                <div ng-if="record.is_live==1" class="column edit_table_icon tooltip-parent">
                                    <a  class="table_action" href="{{url('admin/livevideos/view-details-video')}}/@{{record.id}}">
                                        <svg x="0px" y="0px" viewBox="0 0 511.999 511.999" width="12px" fill="#454545">
                                            <g>
                                                <g>
                                                    <path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
                                                        c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
                                                        C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
                                                        c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
                                                        C447.361,287.923,358.746,385.406,255.997,385.406z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
                                                        s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
                                                        s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.view_video')}}</span>
                                </div>



                                <div  ng-if="record.is_live === 0 && checkAccess('radio_all_write')"  class="column edit_table_icon tooltip-parent">
                                    <a class="table_action"
                                        href="{{url('admin/videos/details-video-edit')}}/@{{ record.id | btoa}} ">

                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545" />
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                </div>
                                <div  ng-if="record.is_live == 1 &&  checkAccess('radio_all_write')" class="column edit_table_icon tooltip-parent">
                                    <a  class="table_action"
                                        href="{{url('admin/livevideos/details-video-edit')}}/@{{ record.id | btoa}} ">

                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545" />
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                </div>

                                <div  ng-if="record.is_live == 2 &&  checkAccess('radio_all_write')" class="column edit_table_icon tooltip-parent">
                                    <a  class="table_action"
                                        href="{{url('admin/radio/details-radio-edit')}}/@{{ record.id | btoa}} ">

                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545" />
                                            </g>
                                        </svg>
                                    </a>
                                    <span class="tooltip_title">{{__('video::videos.edit_video')}}</span>
                                </div>

                                <div class="column edit_table_icon tooltip-parent">
                                    <a ng-if="record.is_live == 1 && record.liveStatus==='Uploaded'"  href="@{{ record.video_url }}" download="@{{ record.video_url }}" target="_blank">
                                        <i class="fa fa-download" style="color:#505050" aria-hidden="true"></i>
                                    </a>
                                    <a href="" ng-if="record.is_live == 1 && record.liveStatus!=='Uploaded'" target="_blank">
                                        <i class="fa fa-download" style="color:#ccc" aria-hidden="true"></i>
                                    </a>
                                    <span class="tooltip_title">Download Video</span>
                                </div>

                               <div class="tooltip-parent" data-ng-if="checkAccess('radio_all_write')">
                                    <span  data-ng-if="record.is_banner == 0" class="delete_table_icon"  data-toggle="modal"
                                    data-target="#videoDeleteModal" data-ng-click="vgridCtrl.deleteSingleRecordVideos(record.id,record.title)"
                                    >
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                fill="#454545" />
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{__('base::general.delete')}}</span>
                               </div>
                               <div class="tooltip-parent" data-ng-if="checkAccess('radio_all_write')">
                                <span data-ng-if="record.is_banner == 1"  class="delete_table_icon delete_disabled" >
                                        <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g>
                                                    <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545" />
                                                </g>
                                        </svg>
                                </span>
                                <span class="tooltip_title">{{trans('video::videos.banner_delete_disabled')}}</span>
                               </div>

                               <div  ng-if="!checkAccess('radio_all_write')" class="column edit_table_icon tooltip-parent">


                                        <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                            <g>
                                                <path d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                    fill="#454545" />
                                            </g>
                                        </svg>

                                    <span class="tooltip_title">{{__('video::videos.permission_denied')}}</span>
                                </div>
                                <div class="tooltip-parent" data-ng-if="!checkAccess('radio_all_write')">
                                <span  class="delete_table_icon" >
                                        <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g>
                                                    <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545" />
                                                </g>
                                        </svg>
                                    </span>
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
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{__('video::collection.add_to_exam')}}</h5>
                <p>Organize and display your subscription videos on the web and in your apps.</p>
            </div>
            <div class="modal-body">
                <form name="collectionForm" method="POST" data-base-validator="" data-ng-submit="vgridCtrl.save($event)"
                    enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="collection_model">
                        <div class="collection_listing">
                            <div class="rdio rdio-primary collection_listing_input" data-ng-repeat="(key, value) in vgridCtrl.allCollections">
                                <input type="radio" name="radio" data-ng-value="@{{key}}" data-ng-model="vgridCtrl.collection.id"
                                    data-ng-change="vgridCtrl.createCollection(key)" id="radioPrimary_@{{key}}">
                                <label for="radioPrimary_@{{key}}" data-ng-class="{'text-primary': key == 0}">@{{value}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default  mr10" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn btn-primary ">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="videoPresetsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{__('video::videos.presets_of_video')}}</h5>
            </div>
            <div class="modal-body">
                <div class="preset_wrap" data-ng-repeat="preset in vgridCtrl.commonVideoPresets track by $index">@{{
                    $index+1 }}. @{{ preset }}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr10" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
