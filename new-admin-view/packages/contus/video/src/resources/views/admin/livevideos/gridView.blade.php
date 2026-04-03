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
            <table class="table tablesaw" id="fixTable" data-tablesaw-mode="columntoggle">
                <thead>
                    <tr>
                        <th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" id="selectall" value="1" data-ng-click="vgridCtrl.selectAllRecords()" />
                                <label for="selectall" class="nopadding"></label>
                            </div>
                            <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="vgridCtrl.selectedRecords != 0"
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
                    <tr class="search_text">
                        <td></td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.title"
                                data-boot-tooltip="true" title="{{__('video::videos.enter_video_title')}}">
                        </td>
                        <td></td>
                        {{-- <td class="search_product">
                                <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('video::videos.select_video_type')}}"  data-ng-model="searchRecords.video_type" data-boot-tooltip="true">
                                        <option value="all">{{__('base::general.all')}}</option>
                                        <option value='0'>{{__('video::videos.uploaded')}}</option>
                                        <option value='1'>{{__('video::videos.live')}}</option>
                                </select>
                        </td> --}}

                        <td></td>
                        <td class="search_product">
                            <select class="select2_custom_ddl" minimumresults="-1"   data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.video_type = 'all'" myPlaceholder="{{__('base::general.select_status')}}"  data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{__('base::general.select_status')}}">
                                    <option value="all">{{__('base::general.all')}}</option>
                                    <option value='1'>{{__('video::collection.banner.active')}}</option>
                                    <option value='0'>{{__('video::collection.banner.inactive')}}</option>
                            </select>

                        </td>
                        <td>
                            <input type="text" class="form-control" data-ng-model="searchRecords.category"
                                data-boot-tooltip="true" title="{{__('video::videos.enter_video_category')}}">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center">
                            <svg viewBox="0 0 16 16" x="0px" y="0px" width="16px" height="16px">
                                <g>
                                    <path fill="#595b62" d="M 6.1018 0.1552 L 5.7013 2.1744 C 5.0333 2.4227 4.4158 2.7698 3.8735 3.2076 L 1.9009 2.5386 L 0.0031 5.774 L 1.5815 7.1228 C 1.5255 7.4603 1.4892 7.8042 1.4892 8.1574 C 1.4892 8.5107 1.5255 8.8542 1.5815 9.1921 L 0.0031 10.541 L 1.9009 13.7764 L 3.8735 13.1073 C 4.4158 13.5451 5.0333 13.8918 5.7013 14.1404 L 6.1018 16.1597 L 9.8976 16.1597 L 10.2981 14.1404 C 10.966 13.8918 11.5837 13.5451 12.126 13.1073 L 14.0986 13.7764 L 15.9964 10.541 L 14.418 9.1921 C 14.4739 8.8542 14.5102 8.5107 14.5102 8.1574 C 14.5102 7.8042 14.4739 7.4603 14.418 7.1228 L 15.9964 5.774 L 14.0986 2.5386 L 12.126 3.2076 C 11.5837 2.7698 10.966 2.4227 10.2981 2.1744 L 9.8976 0.1552 L 6.1018 0.1552 ZM 7.9997 4.1406 C 10.2123 4.1406 12.0061 5.9392 12.0061 8.1574 C 12.0061 10.3757 10.2123 12.1743 7.9997 12.1743 C 5.7872 12.1743 3.9932 10.3757 3.9932 8.1574 C 3.9932 5.9392 5.7872 4.1406 7.9997 4.1406 Z"></path>
                                </g>
                            </svg>
                        </td>

                    </tr>
                    <tr data-ng-if="noRecords">
                        <td colspan="@{{heading.length + 2}}" class="no-data center">{{__('base::general.not_found')}}</td>
                    </tr>
                    <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords"
                        class="list-repeat" data-intialize-sidebar="">
                        <td>
                            <div class="ckbox ckbox-default">
                                <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="vgridCtrl.selectRecord($event, record.id)"
                                    value="@{{record.id}}" name="selectedCheckbox[]">
                                <label for="roles_@{{record.id}}"></label>
                            </div>
                        </td>
                        <td>
                            <div class="product_img flexbox align-items-center" >
                                <a href="{{url('admin/livevideos/view-details-video')}}/@{{record.id}}" class="table-image-text flexbox align-items-center">
                                    {{-- <div class="image" data-ng-if="record.thumbnail_image.length == 0" style="background-image: url({{url('contus/base/images/no-preview.png')}})" >
                                    </div>
                                    <div class="image" data-ng-if="record.thumbnail_image.length > 0" style="background-image: url(@{{record.thumbnail_image}})" >
                                    </div> --}}
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
                        <td class="video-type">
                            <span class="type-label type_live" ng-if="record.is_live == 1 && record.live_recorded_status != 1 && record.liveStatus!='started'">{{__('video::videos.live')}}</span>
                            <span class="type-label type_live" ng-if="record.username !== ''" ng-show="record.liveStatus==='started'">
                                <svg viewBox="0 0 49 40" x="0px" y="0px" width="16px" height="11px">
                                    <g>
                                        <path d="M 39.5624 38.5821 C 38.8504 39.1976 37.785 39.1037 37.1868 38.3699 C 36.92 38.0455 36.7909 37.6502 36.7909 37.256 C 36.7909 36.7611 36.9957 36.2697 37.3926 35.9266 C 41.9957 31.9486 44.6367 26.1437 44.6367 19.9982 C 44.6367 13.8533 41.9957 8.0473 37.3915 4.0698 C 36.9946 3.7267 36.7898 3.2352 36.7898 2.7403 C 36.7898 2.3463 36.92 1.9521 37.1857 1.6271 C 37.785 0.8927 38.849 0.7983 39.5624 1.4143 C 44.9327 6.0542 48.0119 12.8268 48.0119 19.9982 C 48.0119 27.1696 44.9327 33.9434 39.5624 38.5821 ZM 35.2283 33.2723 C 34.5161 33.8889 33.4504 33.7938 32.8516 33.0601 C 32.5843 32.7356 32.4558 32.3403 32.4558 31.9462 C 32.4558 31.4525 32.6589 30.961 33.058 30.618 C 36.1271 27.9659 37.8876 24.0946 37.8876 19.9982 C 37.8876 15.9019 36.1271 12.0312 33.058 9.3803 C 32.66 9.0377 32.4558 8.5469 32.4558 8.0519 C 32.4558 7.6578 32.5864 7.262 32.8516 6.9374 C 33.4504 6.2032 34.5152 6.1082 35.2283 6.7248 C 39.0628 10.0381 41.2619 14.8756 41.2619 19.9982 C 41.2619 25.1214 39.0628 29.9584 35.2283 33.2723 ZM 30.8926 27.9636 C 30.1798 28.5791 29.1152 28.484 28.5154 27.7504 C 28.2491 27.4258 28.1206 27.0293 28.1206 26.6352 C 28.1206 26.1415 28.3237 25.65 28.7218 25.307 C 30.2566 23.9809 31.1385 22.0452 31.1385 19.9982 L 29.6963 15.7361 C 29.4084 15.3588 29.0866 15.0046 28.7218 14.6894 C 28.3248 14.3471 28.1206 13.8556 28.1206 13.3606 C 28.1206 12.9665 28.2502 12.5713 28.5154 12.2461 C 29.1152 11.5124 30.1798 11.4174 30.8926 12.0334 C 33.1933 14.0195 34.5128 16.9231 34.5128 19.9982 C 34.5128 23.0733 33.1933 25.9768 30.8926 27.9636 ZM 24.611 23.472 C 22.7478 23.472 21.2353 21.9163 21.2353 19.9982 C 21.2353 18.0802 22.7478 16.5238 24.611 16.5238 C 26.4739 16.5238 27.9864 18.0802 27.9864 19.9982 C 27.9864 21.9163 26.4739 23.472 24.611 23.472 ZM 20.278 25.3157 C 20.6761 25.6593 20.8792 26.1516 20.8792 26.6462 C 20.8792 27.0409 20.7504 27.438 20.4844 27.7632 C 19.8843 28.4979 18.8197 28.5932 18.1072 27.9767 C 15.8065 25.9867 14.487 23.0784 14.487 19.9982 C 14.487 16.9181 15.8065 14.0098 18.1072 12.0203 C 18.8197 11.4032 19.8843 11.4985 20.4844 12.2334 C 20.7496 12.559 20.8792 12.9549 20.8792 13.3497 C 20.8792 13.8455 20.675 14.3378 20.278 14.6808 C 19.9132 14.9964 19.5915 15.3511 19.3035 15.7289 L 17.8613 19.9982 C 17.8613 22.0485 18.7429 23.9875 20.278 25.3157 ZM 11.1119 19.9982 C 11.1119 24.1013 12.8724 27.979 15.9418 30.6354 C 16.3409 30.979 16.5438 31.4714 16.5438 31.9659 C 16.5438 32.3606 16.4153 32.7566 16.1482 33.0816 C 15.5492 33.8165 14.4835 33.9117 13.7715 33.2941 C 9.9371 29.9748 7.7379 25.1299 7.7379 19.9982 C 7.7379 14.8672 9.9371 10.0217 13.7715 6.7029 C 14.4846 6.0853 15.5492 6.1805 16.1482 6.916 C 16.4131 7.2411 16.5438 7.6375 16.5438 8.0322 C 16.5438 8.528 16.3399 9.0197 15.9418 9.3628 C 12.8724 12.018 11.1119 15.8952 11.1119 19.9982 ZM 4.3628 19.9982 C 4.3628 26.1539 7.0041 31.9683 11.6072 35.9528 C 12.0042 36.2965 12.2086 36.7887 12.2086 37.2844 C 12.2086 37.6793 12.0796 38.0752 11.813 38.4002 C 11.2146 39.1352 10.1494 39.2292 9.4372 38.6127 C 4.0668 33.9664 0.988 27.1814 0.988 19.9982 C 0.988 12.8151 4.0668 6.0313 9.4372 1.3838 C 10.1505 0.7667 11.2146 0.8612 11.8141 1.5967 C 12.0796 1.9224 12.21 2.3171 12.21 2.7119 C 12.21 3.2076 12.0053 3.6999 11.6083 4.0436 C 7.0041 8.0276 4.3628 13.8432 4.3628 19.9982 Z"
                                            fill="#5cb85c" />
                                    </g>
                                </svg>
                                <span class="live_now">Live Now</span>
                            </span>
                            <span class="type-label type_uploaded" ng-if="record.is_live==0">{{__('video::videos.uploaded')}}</span>
                            <span class="type-label type_uploaded type_live" ng-if="record.is_live == 1 && record.live_recorded_status == 1">{{__('video::videos.recorded')}}</span>
                        </td>
                        <td class="upload-status">
                            <div class="status-inprogress" ng-if="record.job_status == 'Progressing'">
                                <span class="progress-title">Inprogress</span>
                                <div class="flexbox align-items-center">
                                    <div class="upload-progress">
                                        <span style="width:@{{record.upload_percentage}}%"></span>
                                    </div>
                                    <span class="count">@{{record.upload_percentage}}%</span>
                                </div>
                            </div>
                            <span class="status-label primary" ng-if="record.job_status == 'Video Uploaded' && record.is_live==0">{{__('video::videos.video_upload_status.uploaded_status')}}</span>
                            {{-- <span class="status-label warning" ng-if="record.job_status == 'Progressing'  && record.is_live==0">{{__('video::videos.video_upload_status.progressing')}}</span> --}}
                            <span class="status-label warning" ng-if="record.job_status == 'Convert to MP4'  && record.is_live==0">{{__('video::videos.video_upload_status.convert_to_mp4')}}</span>
                            {{-- <span class="status-label success" ng-if="record.job_status == 'Complete'  && record.is_live==0 ">{{__('video::videos.video_upload_status.complete')}}</span> --}}
                            <span class="status-label success" ng-if="record.job_status == 'Complete'  && (record.is_live==0 || (record.is_live==1 && record.is_hls ==1))">{{__('video::videos.video_upload_status.complete')}}</span>

                            <span class="status-label  failed" ng-if="record.job_status == 'Error'  && record.is_live==0">{{__('video::videos.video_upload_status.error')}}</span>
                            <span class="status-label  failed" ng-if="record.job_status == 'Canceled'  && record.is_live==0">{{__('video::videos.video_upload_status.cancelled')}}</span>
                            <span class="status-label info" ng-if="record.job_status == 'Uploading' && record.is_live==0">{{__('video::videos.video_upload_status.uploading')}}</span>
                            <span class="status-label info" ng-if="record.job_status == 'Uploaded'  && record.is_live==0">{{__('video::videos.video_upload_status.uploaded')}}</span>
                            <span class="status-label info" ng-if="record.job_status == 'Submitted' && record.is_live==0">{{__('video::videos.video_upload_status.submitted')}}</span>
                            <span class="status-label info" ng-if="record.job_status == 'Added'">{{__('video::videos.video_upload_status.added')}}</span>
                            <span class="status-label  failed" ng-if="record.job_status == 'Error Recording'  && record.is_live==0">{{__('video::videos.video_upload_status.error_recording')}}</span>
                            <button ng-if="record.username !== '' && record.is_live==1  && record.username != 'wowza'" ng-show="record.liveStatus==='ready'||record.liveStatus==='stopped'" ng-click="startlivestream(record)" class="status-label start-btn" title="{{__('video::videos.video_upload_status.start_livestreaming')}}" data-boot-tooltip="true" data-ng-class="{'live-error':record.recording_status.length > 0}">{{__('video::videos.start_live')}}</button>


                            <div class="tooltip-parent">
                                <svg ng-if="record.username !== '' && record.is_live==1  && record.username != 'wowza' && record.recording_status.length > 0" ng-show="record.liveStatus==='ready'||record.liveStatus==='stopped'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 451.74 451.74" style="enable-background:new 0 0 451.74 451.74;" xml:space="preserve" width="25px" height="15px"><path style="fill:#E24C4B;" d="M446.324,367.381L262.857,41.692c-15.644-28.444-58.311-28.444-73.956,0L5.435,367.381  c-15.644,28.444,4.267,64,36.978,64h365.511C442.057,429.959,461.968,395.825,446.324,367.381z"/>
                            <path style="fill:#FFFFFF;" d="M225.879,63.025l183.467,325.689H42.413L225.879,63.025L225.879,63.025z"/>
                            <g><path style="fill:#3F4448;" d="M196.013,212.359l11.378,75.378c1.422,8.533,8.533,15.644,18.489,15.644l0,0   c8.533,0,17.067-7.111,18.489-15.644l11.378-75.378c2.844-18.489-11.378-34.133-29.867-34.133l0,0   C207.39,178.225,194.59,193.87,196.013,212.359z"/>
                                <circle style="fill:#3F4448;" cx="225.879" cy="336.092" r="17.067"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                <span ng-if="record.recording_status.length > 0" class="tooltip_title">@{{record.recording_status}}</span>
                            </div>

                            <span class="type-label type_live" ng-show="record.liveStatus==='starting'">{{__('video::videos.video_upload_status.live_video_initializing')}}</span>
                            <span class="type-label type_live" ng-show="record.liveStatus==='recording'">{{__('video::videos.video_upload_status.live_video_recording')}}</span>
                            <button ng-if="record.username !== '' && record.is_live==1 && record.username != 'wowza'"  data-toggle="modal" data-target="#livestreamStopModal" ng-click="stoplivestream(record)" ng-show="record.liveStatus==='started'" class="status-label stop-btn" title="{{__('video::videos.video_upload_status.stop_livestreaming')}}" data-boot-tooltip="true" >{{__('video::videos.stop_live')}}</button>

                        </td>

                        <td>
                            <div class="tooltip-parent">
                                <span class="status-active" ng-if="record.is_active == 1" style="cursor: pointer;"  data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="vgridCtrl.statusChangeSingleRecord(record)"
                                >{{__('video::videos.message.active')}}</span>
                               <span class="tooltip_title">{{__('video::videos.deactivate_video')}}</span>
                            </div>
                            <div class="tooltip-parent">
                            <span class="status-inactive" ng-if="record.is_active != 1" style="cursor: pointer;"
                            data-toggle="modal" data-target="#videoBulkDeleteModal" data-ng-click="vgridCtrl.statusChangeSingleRecord(record)"
                               >{{ __('video::videos.message.inactive')}}</span>
                               <span class="tooltip_title">{{__('video::videos.activate_video')}}</span>
                            </div>
                        </td>
                        <td>
                            <div data-ng-repeat="category in record.videocategory track by $index">
                                <span class="capitalize">@{{ category.category.title }}</span>
                                <span data-ng-if="record.videocategory.length != $index+1">,</span>
                            </div>
                        </td>
                        <td>@{{ record.scheduledStartTime }}</td>
                        <td class="center">@{{ record.view_count }}</td>
                        <td class="center">@{{ record.like_count }}</td>
                        <td class="center">@{{ record.dislike_count }}</td>
                        <td class="center">@{{ record.favourite_count }}</td>
                        <td class="center">@{{ record.comments_count }}</td>
                        <td>@{{ record.formatted_created_date }}</td>
                        <td>@{{ record.user.name }}</td>
                        <td>@{{ record.formatted_published_date }}</td>
                        <td class="table-action">
                            <div class="flexbox align-items-center justify-center">
                                <div class="column edit_table_icon tooltip-parent">
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


                               <div class="tooltip-parent" data-ng-if="checkAccess('videos_all_write')">
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
