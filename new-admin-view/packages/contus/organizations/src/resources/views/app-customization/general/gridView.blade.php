<style>
    .table-custom td .multi-value {
        display: flex;
        flex-direction: column;
        /* stack values vertically */
        gap: 2px;
        /* spacing between lines */
        font-size: 14px;
    }

    .table-custom td .multi-value div {
        line-height: 1.3;
    }

    .table-custom td .multi-value hr {
        width: 133%;
        border-top: 1px dashed #030303be;
        margin-top: 0px;
        margin-bottom: 0px;
    }
</style>

<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid table-custom" id="fixTable"
            data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscribers'])
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
                
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in GeneralRecords track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">

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
                                <div class="image" bg-image="@{{record.thumbnail_image.replaceAll('\\','/')}}"
                                    on-error-src="{{url('adminview/assets/images/default_image.png')}}">
                                </div>
                            </a>
                        </div>
                    </td>

                    <td>
                        @{{ record.live == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td>
                        @{{ record.epg == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td>
                        @{{ record.catchup == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td>
                        @{{ record.movie == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td>
                        @{{ record.sereis == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td>
                        @{{ record.event == 1 ? 'Active' : 'InActive' }}
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <!-- edit button (class="table_action sidepanel-open")-->
                            <div data-ng-if="checkAccess('subscribers')" class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="GenCtrl.editdata(record, record.id)">
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

                            <!-- delete button -->
                            <div class="tooltip-parent" data-ng-if="checkAccess('subscribers')">
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

<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        
        <form name="GeneralForm" id="GeneralForm" method="POST" data-base-validator
            data-ng-submit="GenCtrl.save($event, GenCtrl.general.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <!-- Header -->
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!GenCtrl.general.id">
                    Create General
                </h5>
                <h5 data-ng-if="GenCtrl.general.id">
                    Edit General
                </h5>
            </div>

            <!-- Form Scrollable Area -->
            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- image code -->
                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <!-- Thumbnail image code -->
                    <div class="thumbnail-image">
                        <h4>Logo</h4>
                        <h4>You can change your app logo that will be displayed in all your apps.</h4>
                        <div class="image-content">
                            <img ng-show="GenCtrl.general.thumbnail_image.length > 0"
                                ng-class="{'active': GenCtrl.general.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ GenCtrl.general.id }}" alt=""
                                ng-src="@{{ GenCtrl.general.thumbnail_image }}" />

                            <img ng-show="GenCtrl.general.thumbnail_image.length == 0"
                                ng-class="{'active': GenCtrl.general.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ GenCtrl.general.id }}" alt="" ng-src="" />
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': GenCtrl.general.thumbnail_image.length > 0}">
                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                width="27px" height="27px">
                                                <g>
                                                    <path opacity="0.702"
                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                        fill="#ffffff"></path>
                                                </g>
                                            </svg>
                                            <div class="input">
                                                <div ng-hide="GenCtrl.general.thumbnail_image.length">
                                                    <span>Change general Image</span>
                                                </div>
                                                <div ng-hide="!GenCtrl.general.thumbnail_image.length"
                                                    class="ng-hide flexbox align-items-center">
                                                    <svg class="change_img_ic" x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899">
                                                        <g>
                                                            <path d="
                                                        M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z
                                                        M518.113,63.177l-47.981-47.981
                                                        c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                                        C532.495,100.753,532.495,77.559,518.113,63.177z
                                                        M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                                        L27.473,390.597L0.3,512.69z" fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>Change general Image</span>
                                                </div>
                                                <input type="file" class="uploadImg" name="image"
                                                    data-video-index="@{{ GenCtrl.general.id }}">
                                            </div>
                                            <p>(Upload a cover image with minimum dimension of 500x294)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.thumbnail_image.has">
                            @{{errors.thumbnail_image.message}}</p>
                    </div>
                </div>

                <!-- live -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg width="20px" height="20px" viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full"
                            enable-background="new 0 0 76.00 76.00" xml:space="preserve">
                            <path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round"
                                d="M 19,15.8333L 57,15.8333L 57,53.8333L 19,53.8333L 19,15.8333 Z M 26.9166,20.5834L 26.9166,33.2501L 49.0832,33.2501L 49.0832,20.5834L 26.9166,20.5834 Z M 26.9166,36.4168L 26.9166,49.0834L 49.0832,49.0834L 49.0832,36.4168L 26.9166,36.4168 Z M 22.9583,19.0001C 21.6466,19.0001 20.5833,20.0634 20.5833,21.3751C 20.5833,22.6868 21.6466,23.7501 22.9583,23.7501C 24.27,23.7501 25.3333,22.6868 25.3333,21.3751C 25.3333,20.0634 24.27,19.0001 22.9583,19.0001 Z M 22.9583,45.9167C 21.6466,45.9167 20.5833,46.98 20.5833,48.2917C 20.5833,49.6034 21.6466,50.6667 22.9583,50.6667C 24.27,50.6667 25.3333,49.6034 25.3333,48.2917C 25.3333,46.98 24.27,45.9167 22.9583,45.9167 Z M 22.9583,39.1876C 21.6466,39.1876 20.5833,40.2509 20.5833,41.5626C 20.5833,42.8742 21.6466,43.9375 22.9583,43.9375C 24.27,43.9375 25.3333,42.8742 25.3333,41.5626C 25.3333,40.2509 24.27,39.1876 22.9583,39.1876 Z M 22.9583,32.4584C 21.6466,32.4584 20.5833,33.5217 20.5833,34.8334C 20.5833,36.1451 21.6466,37.2084 22.9583,37.2084C 24.27,37.2084 25.3333,36.1451 25.3333,34.8334C 25.3333,33.5217 24.27,32.4584 22.9583,32.4584 Z M 22.9583,25.7293C 21.6466,25.7293 20.5833,26.7926 20.5833,28.1043C 20.5833,29.4159 21.6466,30.4793 22.9583,30.4793C 24.27,30.4793 25.3333,29.4159 25.3333,28.1043C 25.3333,26.7926 24.27,25.7293 22.9583,25.7293 Z M 53.0416,19.0001C 51.7299,19.0001 50.6666,20.0634 50.6666,21.3751C 50.6666,22.6868 51.7299,23.7501 53.0416,23.7501C 54.3533,23.7501 55.4166,22.6868 55.4166,21.3751C 55.4166,20.0634 54.3533,19.0001 53.0416,19.0001 Z M 53.0416,45.9167C 51.7299,45.9167 50.6666,46.98 50.6666,48.2917C 50.6666,49.6034 51.7299,50.6667 53.0416,50.6667C 54.3533,50.6667 55.4166,49.6034 55.4166,48.2917C 55.4166,46.98 54.3533,45.9167 53.0416,45.9167 Z M 53.0416,39.1876C 51.7299,39.1876 50.6666,40.2509 50.6666,41.5625C 50.6666,42.8742 51.7299,43.9375 53.0416,43.9375C 54.3533,43.9375 55.4166,42.8742 55.4166,41.5625C 55.4166,40.2509 54.3533,39.1876 53.0416,39.1876 Z M 53.0416,32.4584C 51.7299,32.4584 50.6666,33.5217 50.6666,34.8334C 50.6666,36.1451 51.7299,37.2084 53.0416,37.2084C 54.3533,37.2084 55.4166,36.1451 55.4166,34.8334C 55.4166,33.5217 54.3533,32.4584 53.0416,32.4584 Z M 53.0416,25.7292C 51.7299,25.7292 50.6666,26.7926 50.6666,28.1042C 50.6666,29.4159 51.7299,30.4792 53.0416,30.4792C 54.3533,30.4792 55.4166,29.4159 55.4166,28.1042C 55.4166,26.7926 54.3533,25.7292 53.0416,25.7292 Z M 26.9166,57L 28.5,57L 28.5,63.3333L 31.6667,63.3333L 31.6667,64.9167L 28.5,64.9167L 26.9166,64.9167L 26.9166,57 Z M 33.25,64.9167L 33.25,57L 34.8333,57L 34.8333,64.9167L 33.25,64.9167 Z M 36.8125,57L 38.7916,57L 40.375,62.2779L 41.9583,57L 43.9375,57L 41.1667,64.9167L 41.1666,64.9167L 39.5833,64.9167L 39.5833,64.9167L 36.8125,57 Z M 45.9166,57L 47.5,57L 50.6667,57L 50.6667,58.5833L 47.5,58.5833L 47.5,60.1667L 50.6667,60.1667L 50.6667,61.75L 47.5,61.75L 47.5,63.3333L 50.6667,63.3333L 50.6667,64.9167L 47.5,64.9167L 45.9166,64.9167L 45.9166,57 Z " />
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Live</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="live" ng-model="GenCtrl.general.live">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- epg -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19,14.5 L19,5.5 C19,4.67157288 18.3284271,4 17.5,4 L6.5,4 C5.67157288,4 5,4.67157288 5,5.5 L5,18.5 C5,19.3284271 5.67157288,20 6.5,20 L13.5,20 C14.3284271,20 15,19.3284271 15,18.5 C15,17.1192881 16.1192881,16 17.5,16 C18.3284271,16 19,15.3284271 19,14.5 L19,14.5 Z M18.5014408,16.7913481 C18.1948298,16.9255432 17.8561101,17 17.5,17 C16.6715729,17 16,17.6715729 16,18.5 C16,18.8561101 15.9255432,19.1948298 15.7913481,19.5014408 C16.9873685,18.9526013 17.9526013,17.9873685 18.5014408,16.7913481 L18.5014408,16.7913481 Z M4,5.5 C4,4.11928813 5.11928813,3 6.5,3 L17.5,3 C18.8807119,3 20,4.11928813 20,5.5 L20,14.5 C20,18.0898509 17.0898509,21 13.5,21 L6.5,21 C5.11928813,21 4,19.8807119 4,18.5 L4,5.5 Z M8.5,9 C8.22385763,9 8,8.77614237 8,8.5 C8,8.22385763 8.22385763,8 8.5,8 L15.5,8 C15.7761424,8 16,8.22385763 16,8.5 C16,8.77614237 15.7761424,9 15.5,9 L8.5,9 Z M8.5,12 C8.22385763,12 8,11.7761424 8,11.5 C8,11.2238576 8.22385763,11 8.5,11 L15.5,11 C15.7761424,11 16,11.2238576 16,11.5 C16,11.7761424 15.7761424,12 15.5,12 L8.5,12 Z M8.5,15 C8.22385763,15 8,14.7761424 8,14.5 C8,14.2238576 8.22385763,14 8.5,14 L13.5,14 C13.7761424,14 14,14.2238576 14,14.5 C14,14.7761424 13.7761424,15 13.5,15 L8.5,15 Z" />
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Epg</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="epg" ng-model="GenCtrl.general.epg">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- catchup -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg width="20px" height="20px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"
                            fill="none">
                            <path stroke="#535358" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M23 27H7a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v6m0 14h2a2 2 0 002-2V15a2 2 0 00-2-2h-2m0 14V13M9 10h4M9 17h10M9 21h6" />
                            <path fill="#535358" d="M20 10a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Catchup</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="catchup" ng-model="GenCtrl.general.catchup">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- movie -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 330.008 330.008"
                            style="enable-background:new 0 0 330.008 330.008;" xml:space="preserve">
                            <g id="XMLID_924_">
                                <g id="XMLID_925_">
                                    <path id="XMLID_926_"
                                        d="M315.008,300.004h-55.242c42.45-29.886,70.242-79.258,70.242-135.004
			c0-90.979-74.017-164.996-164.996-164.996C74.024,0.004,0,74.021,0,165c0,90.983,74.024,165.004,165.012,165.004h149.996
			c8.284,0,15-6.716,15-15C330.008,306.72,323.292,300.004,315.008,300.004z M30,165C30,90.563,90.566,30.004,165.012,30.004
			c74.437,0,134.996,60.559,134.996,134.996c0,74.441-60.559,135.004-134.996,135.004C90.566,300.004,30,239.441,30,165z" />
                                </g>
                                <g id="XMLID_929_">
                                    <circle id="XMLID_930_" cx="165.004" cy="225.004" r="30" />
                                </g>
                                <g id="XMLID_931_">
                                    <circle id="XMLID_932_" cx="165.004" cy="105.004" r="30" />
                                </g>
                                <g id="XMLID_933_">
                                    <circle id="XMLID_934_" cx="105.004" cy="165.004" r="30" />
                                </g>
                                <g id="XMLID_935_">
                                    <circle id="XMLID_936_" cx="225.004" cy="165.004" r="30" />
                                </g>
                            </g>
                        </svg>

                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Movies</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="movie" ng-model="GenCtrl.general.movie">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sereis -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 1024 1024"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M768 588.8H358.4V307.2H768v281.6zM665.6 691.2V640H307.2V409.6H256v281.6h409.6z" />
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Sereis</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="sereis" ng-model="GenCtrl.general.sereis">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- events -->
                <div class="form-group">
                    <div class="switch-concept flexbox align-items-center">
                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 52 52"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path
                                    d="m49.83 32.45a1.43 1.43 0 0 0 -1.39-1.45h-12a1.43 1.43 0 0 0 -1.44 1.44v1.44a1.43 1.43 0 0 0 1.4 1.44h6.14l-11 11a1.42 1.42 0 0 0 0 2l1 1a1.54 1.54 0 0 0 1.09.45 1.32 1.32 0 0 0 .94-.38l11-11v6a1.43 1.43 0 0 0 1.43 1.44h1.53a1.43 1.43 0 0 0 1.47-1.4z" />
                                <path
                                    d="m3.45 12.48h31.91a1.24 1.24 0 0 0 1.25-1.23v-1.25a3.8 3.8 0 0 0 -3.76-3.69h-3.06v-1.31a2.43 2.43 0 0 0 -4.86 0v1.28h-11.06v-1.28a2.44 2.44 0 0 0 -4.87 0v1.28h-3.12a3.69 3.69 0 0 0 -3.69 3.72v1.28a1.24 1.24 0 0 0 1.25 1.23z" />
                                <path
                                    d="m36.53 25.13v-7.79a1.25 1.25 0 0 0 -1.22-1.26h-31.86a1.24 1.24 0 0 0 -1.26 1.22v18.46a3.69 3.69 0 0 0 3.69 3.69h18.5a16.82 16.82 0 0 1 12.15-14.32zm-11-2.85a1.24 1.24 0 0 1 1.2-1.28h2.43a1.24 1.24 0 0 1 1.26 1.22v2.47a1.24 1.24 0 0 1 -1.17 1.31h-2.52a1.25 1.25 0 0 1 -1.25-1.23zm-12.23 11a1.23 1.23 0 0 1 -1.25 1.23h-2.49a1.24 1.24 0 0 1 -1.25-1.23v-2.46a1.25 1.25 0 0 1 1.23-1.25h2.46a1.25 1.25 0 0 1 1.26 1.22zm0-8.54a1.24 1.24 0 0 1 -1.22 1.26h-2.52a1.25 1.25 0 0 1 -1.25-1.23v-2.49a1.24 1.24 0 0 1 1.2-1.28h2.49a1.24 1.24 0 0 1 1.26 1.22zm8.54 8.54a1.25 1.25 0 0 1 -1.22 1.26h-2.51a1.24 1.24 0 0 1 -1.26-1.22v-2.5a1.24 1.24 0 0 1 1.23-1.25h2.46a1.25 1.25 0 0 1 1.25 1.23zm-1.3-7.28h-2.43a1.25 1.25 0 0 1 -1.26-1.22v-2.5a1.24 1.24 0 0 1 1.2-1.28h2.49a1.24 1.24 0 0 1 1.25 1.23v2.46a1.24 1.24 0 0 1 -1.17 1.31z" />
                            </g>
                        </svg>
                        <div class="swich-content flexbox align-items-center flex-wrap">
                            <span>Events</span>
                            <div class="right-side flexbox align-items-center">
                                <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                <label class="switch">
                                    <input type="checkbox" name="event" ng-model="GenCtrl.general.event">
                                    <span class="slider round"></span>
                                </label>
                                <span class="text">({{ trans('video::videos.message.active') }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="GenCtrl.closeSubscriptionEdit()" name="cancel" class="save">
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now">
            </div>

        </form>
    </div>
</div>