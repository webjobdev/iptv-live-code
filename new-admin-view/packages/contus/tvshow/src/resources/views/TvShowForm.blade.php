<style>
    .responsive-center-container {
        width: 50%;
        /* margin: 0 auto; */
    }

    @media (max-width: 768px) {
        .responsive-center-container {
            width: 100%;
        }
    }
</style>

<div class="card main-container">
    <div class="card-right">
        <div class="card-content">
            <div class="header-section flexbox align-items-center flex-wrap">
                <h3>@{{ tvsSelectedVideo.title}}</h3>
                <div data-ng-if="!livePage" class="page-heading flexbox align-items-center flex-wrap"
                    style="margin-bottom: 0px;">
                    <div class="right-side flexbox align-items-center">
                        <a data-ng-if="checkAccess('tv_shows')" data-ng-click="tvsGridCtrl.addSeason($event)"
                            class="button button-blue sidepanel-open">
                            <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                                <g>
                                    <path
                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                        fill="#ffffff" />
                                </g>
                            </svg>
                            <span>Season And Details</span>
                        </a>

                        
                    </div>
                </div>
            </div>

            <form id="videoEditForm" name="videoEditForm" method="POST" data-base-validator
                enctype="multipart/form-data" data-ng-submit="tvsGridCtrl.saveVideoEdit($event, tvsSelectedVideo.id)">

                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <!-- poster image code -->
                    <div class="cover-image">
                        <h4>{{ __('video::videos.poster') }}</h4>
                        <div class="image-content responsive-center-container">
                            <!-- image fetch code -->
                            <img ng-show="tvsSelectedVideo.poster_image.length > 0"
                                ng-class="{'active':tvsSelectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ tvsSelectedVideo.id }}" alt=""
                                ng-src="@{{tvsSelectedVideo.poster_image  }}" />

                            <img ng-show="tvsSelectedVideo.poster_image.length == 0"
                                ng-class="{'active':tvsSelectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ tvsSelectedVideo.id }}" alt=""
                                ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': tvsSelectedVideo.poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="tvsSelectedVideo.poster_image.length"
                                                    class="flexbox align-items-center">
                                                    <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px" width="27px"
                                                        height="27px">
                                                        <g>
                                                            <path opacity="0.702"
                                                                d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.upload_cover_picture') }}</span>
                                                </div>
                                                <div ng-hide="!tvsSelectedVideo.poster_image.length"
                                                    class="flexbox align-items-center ng-hide">
                                                    <svg x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899">
                                                        <g>
                                                            <path
                                                                d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981   c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611   C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069   L27.473,390.597L0.3,512.69z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.change_cover_picture') }}</span>
                                                </div>
                                                <input type="file" class="uploadPosterImg" name="image"
                                                    data-video-index="@{{ tvsSelectedVideo.id }}">
                                            </div>
                                            <p>{{ __('video::videos.poster_file_hint') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.poster_image.has">@{{errors.poster_image.message}}</p>
                    </div>

                    <!-- Thumbnail image code -->
                    <div class="thumbnail-image">
                        <h4>{{ __('video::videos.thumbnail')}}</h4>
                        <div class="image-content">
                            <img ng-show="tvsSelectedVideo.thumbnail_image.length > 0"
                                ng-class="{'active': tvsSelectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ tvsSelectedVideo.id }}" alt=""
                                ng-src="@{{ tvsSelectedVideo.thumbnail_image }}" />

                            <img ng-show="tvsSelectedVideo.thumbnail_image.length == 0"
                                ng-class="{'active': tvsSelectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ tvsSelectedVideo.id }}" alt="" ng-src="" />
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': tvsSelectedVideo.thumbnail_image.length > 0}">
                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                width="27px" height="27px">
                                                <g>
                                                    <path opacity="0.702"
                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                        fill="#ffffff"></path>
                                                </g>
                                            </svg>
                                            <div class="input">
                                                <div ng-hide="tvsSelectedVideo.thumbnail_image.length">
                                                    <span>{{ __('video::videos.upload_thumbnail_image') }}</span>
                                                </div>
                                                <div ng-hide="!tvsSelectedVideo.thumbnail_image.length"
                                                    class="ng-hide flexbox align-items-center">
                                                    <svg class="change_img_ic" x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899"">
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
                                                    <span>{{ __('video::videos.change_thumbnail_image') }}</span>
                                                </div>
                                                <input type="file" class="uploadImg" name="image"
                                                    data-video-index="@{{ tvsSelectedVideo.id }}">
                                            </div>
                                            <p ng-if="!livePage">{{ __('video::videos.thumb_file_hint') }}</p>
                                            <p>( Only jpeg, png files allowed with a minimum dimension of 338x170 )</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.thumbnail_image.has">
                            @{{errors.thumbnail_image.message}}</p>
                    </div>
                </div>

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                            <label>
                                Title
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.title.has}">
                                <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                    data-ng-model="tvsSelectedVideo.title">
                            </div>
                            <p class="error-msg" data-ng-show="errors.title.has">@{{errors.title.message}}</p>
                        </div>

                        <div class="form-group" data-ng-class="{'has-error': errors.scheduled_time.has}">
                            <label>
                                Release Date
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input ng-if="!editPage" futureDate="true" myValue="tvsSelectedVideo.release_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="release_date" id="release_date" data-ng-model="tvsSelectedVideo.release_date"
                                    size="30" placeholder="Enter Release Date" data-validation-name="release_date"
                                    value="{{date("Y-m-d H:i:s")}}" class="form-control"
                                    ng-blur="dateBlur($event,tvsSelectedVideo.release_date)"
                                    ng-keyup="dateKeyup($event,tvsSelectedVideo.release_date)" />

                                <i class="calender-icon"></i>
                                <input ng-if="editPage" futureDate="true" myValue="tvsSelectedVideo.release_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="release_date" id="release_date" data-ng-model="tvsSelectedVideo.release_date"
                                    size="30" placeholder="Enter Release Date" data-validation-name="release_date"
                                    value="{{date("Y-m-d H:i:s")}}" class="form-control"
                                    ng-blur="dateBlur($event,tvsSelectedVideo.release_date)"
                                    ng-keyup="dateKeyup($event,tvsSelectedVideo.release_date)" />
                            </div>
                            <p class="error-msg" data-ng-show="errors.scheduled_time.has">
                                @{{ errors.scheduled_time.message }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Directors</label>
                            <div class="form-input">
                                <input type="text" name="directors" class="form-control"
                                    placeholder="Enter Director Name" data-ng-model="tvsSelectedVideo.directors">
                            </div>
                            <p class="error-msg" data-ng-show="errors.directors.has">@{{errors.directors.message}}</p>
                        </div>

                        <div class="form-group">
                            <label>{{ __('video::videos.presenter') }}</label>
                            <div class="form-input">
                                <input type="text" name="presenter" class="form-control"
                                    placeholder="Enter Cast & Crew Name" data-ng-model="tvsSelectedVideo.presenter">
                            </div>
                            <p class="error-msg" data-ng-show="errors.presenter.has">@{{errors.presenter.message}}</p>
                        </div>


                        <div class="form-group">
                            <label>
                                Organizations
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <select multiple data-jquery="select2_custom_ddl"
                                    myValue="tvsSelectedVideo.organization" myPlaceholder="Select organization"
                                    ng-init="vodGridCtrl.editVideo.category" name="organization"
                                    class="admin_category_sub form-control select2_custom_ddl"
                                    data-ng-model="tvsSelectedVideo.organization" style="width: 100%;"
                                    data-ng-options="org.id as org.organization_name for org in tvsGridCtrl.OrganizationList">
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.organization.has">
                                @{{ errors.organization.message }}
                            </p>
                        </div>
                    </div>

                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                            <label>
                                {{ __('video::videos.description') }}
                                <!-- <span class="required">*</span> -->
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.description.has}">
                                <textarea rows="7" name="description" maxlength="50000" class="form-control"
                                    data-ng-model="tvsSelectedVideo.description"
                                    placeholder="  {{ __('video::videos.description_message') }}"></textarea>
                            </div>
                            <p class="error-msg" data-ng-show="errors.description.has">@{{errors.description.message}}
                            </p>
                        </div>

                        <p id="data"></p>

                        <!-- Scheduled Publishing start  -->
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                    <g>
                                        <path
                                            d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Scheduled Publishing</span>
                                    <!-- <span>{{ __('video::videos.status') }}</span> -->
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="scheduled_publishing"
                                                ng-model="tvsSelectedVideo.scheduled_publishing"
                                                data-ng-change="scheduledDate()">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Scheduled Publishing end -->

                        <div ng-if="tvsSelectedVideo.scheduled_publishing">
                            <div class="form-group" data-ng-class="{'has-error': errors.scheduled_time.has}">
                                <label>
                                    {{__('video::videos.scheduled_time')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input calender-left">
                                    <i class="calender-icon"></i>
                                    <input ng-if="!editPage" futureDate="true" myValue="tvsSelectedVideo.scheduled_time"
                                        autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                        name="scheduled_time" id="scheduled_time"
                                        data-ng-model="tvsSelectedVideo.scheduled_time" size="30"
                                        placeholder="{{__('video::videos.scheduled_time')}}"
                                        data-validation-name="scheduled_time" value="{{date("Y-m-d H:i:s")}}"
                                        class="form-control jay"
                                        ng-blur="dateBlur($event,tvsSelectedVideo.scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsSelectedVideo.scheduled_time)" />

                                    <i class="calender-icon"></i>
                                    <input ng-if="editPage" futureDate="true" myValue="tvsSelectedVideo.scheduled_time"
                                        autocomplete="off" type="text" name="scheduled_time" id="scheduled_time"
                                        data-ng-model="tvsSelectedVideo.scheduled_time" size="30"
                                        placeholder="{{__('video::videos.scheduled_time')}}"
                                        data-validation-name="scheduled_time" value="{{date("Y-m-d H:i:s")}}"
                                        class="form-control hello"
                                        ng-blur="dateBlur($event,tvsSelectedVideo.scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsSelectedVideo.scheduled_time)" />
                                </div>
                                <p class="error-msg" data-ng-show="errors.scheduled_time.has">
                                    @{{ errors.scheduled_time.message }}</p>
                            </div>

                            <div class="form-group" data-ng-class="{'has-error': errors.expire_scheduled_time.has}">
                                <label>
                                    Expire Scheduled Time
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input calender-left">
                                    <i class="calender-icon"></i>
                                    <input ng-if="!editPage" futureDate="true"
                                        myValue="tvsSelectedVideo.expire_scheduled_time" autocomplete="off"
                                        data-jquery="date_time_picker" datetime-picker type="text"
                                        name="expire_scheduled_time" id="expire_scheduled_time"
                                        data-ng-model="tvsSelectedVideo.expire_scheduled_time" size="30"
                                        placeholder="Expire Scheduled Time" data-validation-name="expire_scheduled_time"
                                        value="{{date("Y-m-d H:i:s")}}" class="form-control"
                                        ng-blur="dateBlur($event,tvsSelectedVideo.expire_scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsSelectedVideo.expire_scheduled_time)">

                                    <i class="calender-icon"></i>
                                    <input ng-if="editPage" futureDate="true"
                                        myValue="tvsSelectedVideo.expire_scheduled_time" autocomplete="off" type="text"
                                        name="expire_scheduled_time" id="expire_scheduled_time"
                                        data-ng-model="tvsSelectedVideo.expire_scheduled_time" size="30"
                                        placeholder="Expire Scheduled Time" data-validation-name="expire_scheduled_time"
                                        value="{{date("Y-m-d H:i:s")}}" class="form-control hello"
                                        ng-blur="dateBlur($event,tvsSelectedVideo.expire_scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsSelectedVideo.expire_scheduled_time)">
                                </div>
                                <p class="error-msg" data-ng-show="errors.expire_scheduled_time.has">
                                    @{{ errors.expire_scheduled_time.message }}</p>
                            </div>
                        </div>

                        <!-- publish code -->
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Publish Now</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="publish_now"
                                                ng-model="tvsSelectedVideo.publish_now" ng-change="togglePublishDate()">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Show manual input when NOT active (is_active != 1) -->
                        <div class="form-group" ng-if="tvsSelectedVideo.publish_now != 1">
                            <label>
                                Enter Publish Time
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input ng-if="!editPage" futureDate="true" myValue="tvsSelectedVideo.publish_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="publish_date" id="publish_date" data-ng-model="tvsSelectedVideo.publish_date"
                                    size="30" placeholder="Enter Publish Time" data-validation-name="publish_date"
                                    class="form-control" ng-blur="dateBlur($event, tvsSelectedVideo.publish_date)"
                                    ng-keyup="dateKeyup($event, tvsSelectedVideo.publish_date)"
                                    value="{{date("Y-m-d H:i:s")}}">

                                <input ng-if="editPage" futureDate="true" myValue="tvsSelectedVideo.publish_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="publish_date" id="publish_date" data-ng-model="tvsSelectedVideo.publish_date"
                                    size="30" placeholder="Enter Publish Time" data-validation-name="publish_date"
                                    class="form-control" ng-blur="dateBlur($event, tvsSelectedVideo.publish_date)"
                                    ng-keyup="dateKeyup($event, tvsSelectedVideo.publish_date)"
                                    value="{{date("Y-m-d H:i:s")}}">
                            </div>
                            <p class="error-msg" data-ng-show="errors.publish_date.has">
                                @{{ errors.publish_date.message }}</p>
                        </div>

                        <!-- Show auto-filled date/time only when active (is_active == 1) -->
                        <div class="form-group" ng-if="tvsSelectedVideo.publish_now == 1">
                            <label>
                                Auto Publish Date/Time
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input readonly type="text" name="publish_date" class="form-control"
                                    ng-model="tvsSelectedVideo.publish_date" placeholder="Auto Publish Time" />
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;">

                <div class="panel-heading">
                    <label class="fs-4 fw-bold"
                        style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                        Trailers Streaming Url
                    </label>

                    <div class="division flexbox">
                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.trailer_url.has}">
                                <label>
                                    Trailer Url
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input" data-ng-class="{'has-error': errors.trailer_url.has}">
                                    <input type="text" name="trailer_url" class="form-control"
                                        placeholder="Enter Trailer Url" data-ng-model="tvsSelectedVideo.trailer_url">
                                </div>
                                <p class="error-msg" data-ng-show="errors.trailer_url.has">
                                    @{{errors.trailer_url.message}}</p>
                            </div>
                        </div>

                        <div class="one-set width-50">
                            <!-- Playback Token Generator -->
                            <div class="form-group">
                                <label>
                                    Select Playback Token Generator
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="playback_token"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="tvsSelectedVideo.playback_token"
                                        myPlaceholder="Select Playback Token Generator"
                                        ng-options="platback.id as platback.name for platback in tvsGridCtrl.playbackTokenList"
                                        data-ng-model="tvsSelectedVideo.playback_token">
                                        <option value="">--- Select ---</option>
                                        <!-- <option value="hello">hello</option>
                                        <option value="byy">byy</option> -->
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.playback_token.has">The playback token field
                                    is required.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion-advanced-url" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default" style="border-radius: 5px;">
                            <!-- Panel Heading -->
                            <div class="panel-heading" role="tab" id="heading-advanced-url">
                                <a role="button" data-toggle="collapse" data-parent="#accordion-advanced-url"
                                    href="#collapse-advanced-url" aria-expanded="false"
                                    aria-controls="collapse-advanced-url" class="collapsed"
                                    style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                    <i class="fa fa-caret-right" style="margin-right: 8px;"></i>
                                    <label class="fs-4 fw-bold"
                                        style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                                        Advanced URL Setting
                                    </label>
                                </a>
                            </div>

                            <!-- Panel Body -->
                            <div id="collapse-advanced-url" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading-advanced-url">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>
                                                    Select Policy
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="form-input">
                                                    <select allowClear="1" data-jquery="select2_custom_ddl"
                                                        name="policy"
                                                        class="admin_category_sub form-control select2_custom_ddl"
                                                        myValue="tvsSelectedVideo.policy" myPlaceholder="Select Policy"
                                                        ng-options="policy.id as policy.policy_name for policy in tvsGridCtrl.PolicyList"
                                                        data-ng-model="tvsSelectedVideo.policy">
                                                        <option value="">--- Select ---</option>
                                                        <!-- <option value="hello">hello</option>
                                                        <option value="byy">byy</option> -->
                                                    </select>
                                                </div>
                                                <p class="error-msg" data-ng-show="errors.policy.has">@{{
                                                    errors.policy.has }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- categories -->
                <div class="panel-heading">
                    <label class="fs-4 fw-bold"
                        style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                        Categories
                    </label>

                    <div class="form-group" data-ng-class="{'has-error': errors.category.has}">
                        <label>
                            <span>
                                {{ __('video::videos.category') }}
                            </span>
                            <!-- <span class="required">*</span> -->
                        </label>
                        <div class="form-input">
                            <select multiple data-jquery="select2_custom_ddl" myValue="tvsSelectedVideo.category"
                                myPlaceholder="Select Country" ng-init="vgridCtrl.editVideo.category" name="category"
                                class="admin_category_sub form-control select2_custom_ddl"
                                data-ng-model="tvsSelectedVideo.category" data-ng-change="vgridCtrl.changeCategory()"
                                ng-options="Series.series_categorie_name as Series.series_categorie_name for Series in tvsGridCtrl.SeriesCategoryList">
                            </select>
                        </div>
                        <p class="error-msg" data-ng-show="errors.category.has">@{{errors.category.message}}</p>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;">

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            Age Rating and Parental Control
                        </label>
                        <div class="form-group">
                            <label>
                                Age Rating
                                <!-- <span class="required">*</span> -->
                            </label>
                            <!-- <div class="col-sm-6"> -->
                            <div class="form-group row" style="margin-top: 20px;">
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="age_rating"
                                            data-ng-model="tvsSelectedVideo.age_rating" value="0"> Default Age
                                        Rating
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="age_rating"
                                            data-ng-model="tvsSelectedVideo.age_rating" value="1"> Country based Age
                                        Rating
                                    </label>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>

                        <div class="form-group">
                            <label>
                                Age limit
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <select allowClear="1" data-jquery="select2_custom_ddl" name="age_limit"
                                    class="admin_category_sub form-control select2_custom_ddl"
                                    myValue="tvsSelectedVideo.age_limit" myPlaceholder="Select age limit"
                                    data-ng-model="tvsSelectedVideo.age_limit" ng-init="tvsSelectedVideo.age_limit">
                                    <option value="">--- Select ---</option>
                                    <option ng-selected="tvsSelectedVideo.age_limit == 'G'" value="G">G</option>
                                    <option ng-selected="tvsSelectedVideo.age_limit == 'PG'" value="PG">PG</option>
                                    <option ng-selected="tvsSelectedVideo.age_limit == '13+'" value="13+">13+
                                    </option>
                                    <option ng-selected="tvsSelectedVideo.age_limit == '16+'" value="16+">16+
                                    </option>
                                    <option ng-selected="tvsSelectedVideo.age_limit == 'R'" value="R">R</option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.age_limit.has">Age limit required</p>
                        </div>

                        <div class="form-group">
                            <label>Parental Lock Restricted Video</label>
                            <div class="form-input">
                                <select data-jquery="select2_custom_ddl" name="status"
                                    class="admin_category_sub form-control select2_custom_ddl"
                                    myValue="tvsSelectedVideo.is_parental" myPlaceholder="Select parental Lock"
                                    data-ng-model="tvsSelectedVideo.is_parental" ng-init="tvsSelectedVideo.is_parental">
                                    <option value=""></option>
                                    <option ng-selected="tvsSelectedVideo.is_parental == 1" value="1">Yes</option>
                                    <option ng-selected="tvsSelectedVideo.is_parental == 0" value="0">No</option>

                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.group.has">@{{errors.group.message}}</p>
                        </div>
                    </div>

                    <div class="one-set width-50">
                        <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            Geo Blocking Policy
                        </label>
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                    <g>
                                        <path
                                            d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Geo Blocking Policy</span>
                                    <!-- <span>{{ __('video::videos.status') }}</span> -->
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="geo_policy"
                                                ng-model="tvsSelectedVideo.geo_policy">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div><br>

                            <div class="form-group" data-ng-class="{'has-error': errors.category.has}"
                                ng-if="tvsSelectedVideo.geo_policy">
                                <label>
                                    <span>
                                        Geo Country
                                    </span>
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select multiple data-jquery="select2_custom_ddl"
                                        myValue="tvsSelectedVideo.geo_block_country_list"
                                        myPlaceholder="{{ __('video::videos.select_category') }}"
                                        name="geo_block_country_list"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        data-ng-model="tvsSelectedVideo.geo_block_country_list"
                                        ng-options="geoblock.name as geoblock.name for geoblock in tvsGridCtrl.geoBlockList">
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.category.has">@{{ errors.category.message }}
                                </p>
                            </div>
                        </div><br><br>

                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                    <g>
                                        <path
                                            d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Active</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="is_active"
                                                ng-model="tvsSelectedVideo.is_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;" data-ng-if="editPage">

                <div class="panel-group" id="accordian-content-set" role="tablist" aria-multiselectable="true"
                    data-ng-if="editPage">
                    <div class="panel panel-default" style="margin-bottom: 20px; border-radius: 5px;">
                        <div class="panel-heading" role="tab" id="heading-content-set">
                            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                                href="#collapse-content-set" aria-expanded="false" aria-controls="collapse-content-set"
                                class="collapsed"
                                style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                <label
                                    style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                                    Assigned Content Sets
                                </label>
                                <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;"></i>
                            </a>
                        </div>

                        <div id="collapse-content-set" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="heading-content-set">
                            <div class="panel-body">

                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <!-- <h1 style="font-weight: 900; font-size: 1.2rem;">Credit Card</h1><br> -->
                                    <div class="right-side flexbox align-items-center" data-toggle="modal"
                                        data-target="#assigned-content">
                                        <a data-ng-if="checkAccess('tv_show')"
                                            data-ng-click="dashCtrl.addOrganization($event)" href="javascript:void(0)"
                                            class="button button-blue sidepanel-open">
                                            <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px"
                                                height="18px">
                                                <g>
                                                    <path
                                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                                        fill="#ffffff" />
                                                </g>
                                            </svg>
                                            <!-- <span>{{trans('subscribers::index.add_subscribers')}}</span> -->
                                            <span>Assigned Content Sets</span>
                                        </a>
                                    </div>
                                </div>

                                <div style="margin-top: 10px;">
                                    <div style="max-height: 200px; overflow-y: auto; padding: 5px;">
                                        <!-- <div class="bundle-item"
                                            ng-repeat="bundle in tvsnGridCtrl.selectedVideo.bundles"
                                            data-id="@{{bundle.id}}" data-ng-model="tvsnSelectedVideo.content_sets"
                                            style="border: 1px solid #ccc; padding: 10px; margin-bottom: 5px; border-radius: 4px;"> -->

                                        <div class="bundle-item" ng-repeat="org in tvsGridCtrl.selectedVideo.bundles"
                                            data-id="@{{org.organization_id}}"
                                            data-ng-model="tvsSelectedVideo.content_sets"
                                            style="border:1px solid #ccc; padding:10px; margin-bottom:5px; border-radius:4px;">

                                            <!-- Organization Name -->
                                            <span class="bundle-title" style="font-weight:bold;">
                                                @{{org.organization_name}}
                                            </span>
                                            <br>

                                            <!-- Bundle names (show max 3) -->
                                            <span class="bundle-sub center">
                                                <span ng-repeat="bundle in org.bundles | limitTo:3">
                                                    @{{bundle.name}}<span ng-if="!$last">, </span>
                                                </span>
                                                <span ng-if="org.bundles.length > 3">, more</span>
                                            </span>

                                            <!-- Delete organization -->
                                            <span class="bundle-delete" ng-click="removeBundle(org)"
                                                style="float:right; color:red; cursor:pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span>
                                        </div>


                                        <!-- <span class="bundle-title">@{{bundle.organization_name}}</span>
                                            <span class="bundle-sub center"> Post Event</span>
                                            <span class="bundle-rent">Rent</span>
                                            <span class="bundle-delete" ng-click="removeBundle(bundle)"
                                                style="float: right; color: red; cursor: pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span> -->
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- bottom button -->
        <div class="bottom-button text-right ">
            <a ng-if="tvsSelectedVideo.id" class="delete-button" href="javascript:void(0)" data-toggle="modal"
                data-target="#videoDeleteModal"
                data-ng-click="tvsGridCtrl.deleteSingleRecordVideos(tvsSelectedVideo.id, tvsSelectedVideo.title)">
                <svg viewBox="0 0 14 17" version="1.1" x="0px" y="0px" width="14px" height="17px">
                    <g>
                        <path
                            d="M 12.9751 3.1697 L 1.0323 3.1697 C 0.7284 3.1697 0.4821 2.9233 0.4821 2.6193 L 0.4821 1.574 C 0.4821 1.2699 0.7284 1.0238 1.0323 1.0238 L 4.8785 1.0238 C 4.9061 0.7457 5.1406 0.019 5.426 0.019 L 8.5814 0.019 C 8.8668 0.019 9.1013 0.7457 9.1289 1.0238 L 12.9751 1.0238 C 13.2791 1.0238 13.5254 1.2699 13.5255 1.5741 L 13.5255 2.6193 C 13.5255 2.9231 13.2791 3.1697 12.9751 3.1697 ZM 12.3715 15.5684 C 12.3715 15.8722 12.1252 16.1185 11.8212 16.1185 L 2.1863 16.1185 C 1.8822 16.1185 1.6359 15.8722 1.6359 15.5684 L 1.6359 4.2266 L 12.3715 4.2266 L 12.3715 15.5684 ZM 5.6652 6.7257 C 5.6652 6.3907 5.3936 6.1191 5.0585 6.1191 C 4.7233 6.1191 4.4518 6.3907 4.4518 6.7257 L 4.4518 12.7393 C 4.4518 13.0741 4.7233 13.3459 5.0585 13.3459 C 5.3936 13.3459 5.6652 13.0741 5.6652 12.7393 L 5.6652 6.7257 ZM 9.5558 6.7257 C 9.5558 6.3907 9.2839 6.1191 8.949 6.1191 C 8.6139 6.1191 8.3422 6.3907 8.3422 6.7257 L 8.3422 12.7393 C 8.3422 13.0741 8.6138 13.3459 8.949 13.3459 C 9.2841 13.3459 9.5558 13.0741 9.5558 12.7393 L 9.5558 6.7257 Z"
                            fill="#fc4e4e" />
                    </g>
                </svg>
            </a>

            <a class="save" ng-if="livePage || tvsSelectedVideo.is_live" href="{{ url('admin/tvshow') }}">
                {{ __('video::videos.back') }}
            </a>

            <a class="save" ng-if="!livePage || tvsSelectedVideo.is_live" href="{{ url('admin/tvshow') }}">
                {{ __('video::videos.back') }}
            </a>

            <button ng-if="livePage && !editPage" id="videoEditFormSubmit"
                data-ng-click="tvsGridCtrl.saveTvShow($event, tvsSelectedVideo.id)" class="publish-now">
                {{ __('video::videos.publish_now') }}
            </button>

            <button ng-if="!livePage && editPage" id="videoEditFormSubmit"
                data-ng-click="tvsGridCtrl.saveTvshowEdit($event, tvsSelectedVideo.id)" class="publish-now">
                {{ __('video::videos.publish_now') }}
            </button>
        </div>
    </div>
</div>


<style>
    .content-container {
        border: 1px solid #ccc;
        /* border: 2px dashed #337ab7; */
        background-color: #f9f9f9;
        border-radius: 8px;
        /* min-height: 150px; */
        padding: 10px;
        margin-bottom: 15px;
        background: #fff;
        cursor: move;
    }

    .content-header {
        font-weight: bold;
        margin-bottom: 5px;
        display: flex;
    }

    .item-box {
        border: 1px solid #ddd;
        padding: 8px;
        margin: 5px 0;
        border-radius: 4px;
        background-color: #f9f9f9;
        /* cursor: move; */
    }

    .drop-zone {
        border: 2px dashed #ccc;
        padding: 10px;
        text-align: center;
        color: #999;
        font-style: italic;
        margin-top: 10px;
    }

    .assign-btns {
        margin-top: 15px;
        text-align: center;
    }

    .search-box {
        margin-bottom: 10px;
    }

    .bundle-item {
        background-color: #f5f5f5;
        border-radius: 50px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .bundle-title {
        font-weight: 600;
        flex-shrink: 0;
    }

    .bundle-sub {
        flex-grow: 1;
        color: #555;
    }

    .bundle-price {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .bundle-price del {
        color: #999;
        margin-right: 3px;
    }

    .bundle-rent {
        color: #333;
        font-weight: 500;
        margin-left: 10px;
    }

    .bundle-remove {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        /* position: absolute;
        top: 5.2rem;
        right: 2rem; */
    }

    .bundle-delete {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        /* position: absolute;
        top: 5.2rem;
        right: 2rem; */
    }

    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }
</style>

<!-- Assigned Content Sets model code start -->
<div class="modal fade" id="assigned-content" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>

                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4 class="modal-title">Add Bundles</h4>
                </div>

                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap" style="margin-top: 15px;">
                        <h4>Available Content Sets</h4>
                    </div>

                    <input type="text" id="searchAvailable" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">

                        <div id="availableBundles">
                            <div class="content-container panel panel-default" draggable="true"
                                data-ng-repeat="orgbundles in TvShowSetList"
                                data-id="@{{ orgbundles.organization_id }}">

                                <div class="content-header">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    @{{ orgbundles.organization_name }}
                                </div>

                                <div class="item-box" ng-repeat="bundle in orgbundles.bundles">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    <i class="glyphicon glyphicon-blackboard"></i>
                                    @{{ bundle.name }}
                                </div>

                                <!-- <div class="content-header">@{{ orgbundles.organization_name }}</div>
                                <div class="item-box">Post event</div> -->
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap" style="margin-top: 15px;">
                        <h4>Assigned Content Sets</h4>
                    </div>

                    <input type="text" id="searchAdded" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box">
                        <div class="content-container panel panel-default" draggable="true"
                            data-ng-repeat="org in tvsSelectedVideo.selectedBundles"
                            data-id="@{{ org.organization_id }}">
                            <div class="content-header">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                @{{ org.organization_name }}
                                <span class="bundle-remove" ng-click="removeBundle(org)">
                                    <i class="glyphicon glyphicon-remove-circle"></i>
                                </span>
                            </div>
                            <div class="item-box" ng-repeat="bundle in org.bundles">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ bundle.name }}
                            </div>
                        </div>

                        <div id="addedBundles" style="min-height: 145px;">
                            <div class="drop-zone">DROP HERE</div>
                        </div>
                    </div>

                    <!-- <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                        <div class="" id="addedBundles" style="min-height: 339px; padding: 8px;">
                        </div>
                    </div> -->


                </div>
            </div>

            <div class="assign-btns">
                <button type="button" class="button button-blue" data-dismiss="modal"
                    ng-click="tvsGridCtrl.assignSelectedBundles()">
                    Assign
                </button>&nbsp;
                <button class="button button-gray" data-dismiss="modal">
                    Cancel
                </button>
            </div>

        </div>
    </div>
</div>