<style>
    /* Modern look for Bootstrap 3 list */
    .season-list .list-group-item {
        display: flex;
        background-color: #00ACCD;
        border-color: #00ACCD;
        align-items: center;
        justify-content: flex-start;
        border-radius: 6px;
        margin-bottom: 6px;
        transition: background-color 0.2s ease;
    }

    .season-list .list-group-item:hover {
        background-color: #00abcdf1;
    }

    .season-badge {
        background-color: #fff;
        color: #00ACCD;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-weight: bold;
        font-size: 13px;
    }

    .season-title {
        color: #fff;
        font-size: 14px;
    }

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
    <div class="card-left">
        <ul>
            <button class="button button-blue" style="background-color: #00ACCD;" ng-if="!livePage"
                data-ng-click="tvsnGridCtrl.BackToSeason(tvsnSelectedVideo.tv_show_id)">
                Back
            </button>
        </ul>

        <br>

        <div class="season-list">
            <a ng-repeat="season in tvsnGridCtrl.seasons track by season.id" class="list-group-item d-block"
                ng-class="{'active bg-primary text-white': $first}"
                href="{{url('admin/tvshow/edit-tv-show-season/season-id')}}/@{{ encodeId(season.id) }}">

                <!-- If no season number -->
                <div ng-if="!season.season_number">
                    <span>No Season Added</span>
                </div>

                <!-- If season number exists -->
                <div class="season-badge">@{{season.season_number}}</div>
                <div class="season-title">@{{season.title}} @{{season.season_number}}</div>
            </a>
        </div>

    </div>

    <div class="card-right">
        <div class="card-content">
            <div class="header-section flexbox align-items-center flex-wrap">
                <h3 ng-if="!editPage">
                    @{{ tvsnSelectedVideo.title}}
                </h3>
                <h3 ng-if="editPage">
                    @{{ tvsnSelectedVideo.title }} @{{ tvsnSelectedVideo.season_number }}
                </h3>
                <!-- language code -->
                <div class="right-side">
                    <!-- <select data-jquery="select2_custom_ddl" minimumResults="-1" class="lang-select select2_custom_ddl"
                        style="width:100px" ng-change="languageChange()" data-ng-model="selectedLanguage"
                        data-ng-options="lang.id as lang.title  for lang in tvsGridCtrl.language ">
                    </select> -->
                </div>

                <div style="display: none" class="upload_error_alert">
                    <span>{{ __('video::videos.select_valid_file') }}</span>
                    <svg x="0px" y="0px" width="8px" height="8px" viewBox="0 0 612 612"
                        data-ng-click="tvsGridCtrl.hideUploadOption()">
                        <g>
                            <g id="cross">
                                <g>
                                    <polygon
                                        points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397 306,341.411 576.521,611.397 612,575.997 341.459,306.011"
                                        fill="#6b7d8b">
                                    </polygon>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>

            <form id="videoEditForm" name="videoEditForm" method="POST" data-base-validator
                enctype="multipart/form-data"
                data-ng-submit="tvsnGridCtrl.saveSeasonEdit($event, tvsnSelectedVideo.id)">

                <input type="hidden" data-ng-model="tvsnSelectedVideo.tv_show_id" id="tv_show_id" value="">

                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <!-- poster image code -->
                    <div class="cover-image">
                        <h4>{{ __('video::videos.poster') }}</h4>
                        <div class="image-content responsive-center-container">
                            <!-- image fetch code -->
                            <img ng-show="tvsnSelectedVideo.poster_image.length > 0"
                                ng-class="{'active':tvsnSelectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ tvsnSelectedVideo.id }}" alt=""
                                ng-src="@{{tvsnSelectedVideo.poster_image  }}" />

                            <img ng-show="tvsnSelectedVideo.poster_image.length == 0"
                                ng-class="{'active':tvsnSelectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ tvsnSelectedVideo.id }}" alt=""
                                ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': tvsnSelectedVideo.poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="tvsnSelectedVideo.poster_image.length"
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
                                                <div ng-hide="!tvsnSelectedVideo.poster_image.length"
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
                                                    data-video-index="@{{ tvsnSelectedVideo.id }}">
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
                            <img ng-show="tvsnSelectedVideo.thumbnail_image.length > 0"
                                ng-class="{'active': tvsnSelectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ tvsnSelectedVideo.id }}" alt=""
                                ng-src="@{{ tvsnSelectedVideo.thumbnail_image }}" />

                            <img ng-show="tvsnSelectedVideo.thumbnail_image.length == 0"
                                ng-class="{'active': tvsnSelectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ tvsnSelectedVideo.id }}" alt="" ng-src="" />
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': tvsnSelectedVideo.thumbnail_image.length > 0}">
                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                width="27px" height="27px">
                                                <g>
                                                    <path opacity="0.702"
                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                        fill="#ffffff"></path>
                                                </g>
                                            </svg>
                                            <div class="input">
                                                <div ng-hide="tvsnSelectedVideo.thumbnail_image.length">
                                                    <span>{{ __('video::videos.upload_thumbnail_image') }}</span>
                                                </div>
                                                <div ng-hide="!tvsnSelectedVideo.thumbnail_image.length"
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
                                                    <span>{{ __('video::videos.change_thumbnail_image') }}</span>
                                                </div>
                                                <input type="file" class="uploadImg" name="image"
                                                    data-video-index="@{{ tvsnSelectedVideo.id }}">
                                            </div>
                                            <p ng-if="!livePage">{{ __('video::videos.thumb_file_hint') }}</p>
                                            <p>( Only jpeg, png files allowed with a minimum dimension of 540x800 )</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.thumbnail_image.has">
                            @{{errors.thumbnail_image.message}}</p>
                    </div>

                    <!-- mobile image code -->
                    <div ng-if="!livePage && !editPage" class="upload-cover-thumbnail flexbox"
                        data-ng-class="{'has-error': errors.mobile_poster_image.has}">
                        <div class="thumbnail-image">
                            <h4>Mobile poster image</h4>
                            <div class="image-content">
                                <img ng-show="tvsnSelectedVideo.mobile_poster_image.length > 0"
                                    ng-class="{'active':tvsnSelectedVideo.mobile_poster_image}"
                                    class="uploaded_mobile_poster_img uploaded_mobile_poster_img_@{{ tvsnSelectedVideo.id }}"
                                    alt="" ng-src="@{{tvsnSelectedVideo.mobile_poster_image  }}" />

                                <img ng-show="tvsnSelectedVideo.mobile_poster_image.length == 0"
                                    ng-class="{'active':tvsnSelectedVideo.mobile_poster_image}"
                                    class="uploaded_mobile_poster_img uploaded_mobile_poster_img_@{{ tvsnSelectedVideo.id }}"
                                    alt="" ng-src="" />

                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileuploadbox">
                                        <div class="input-append">
                                            <div class="overlay-content"
                                                data-ng-class="{'change-image': tvsnSelectedVideo.mobile_poster_image.length > 0}">
                                                <div class="input">
                                                    <div ng-hide="tvsnSelectedVideo.mobile_poster_image.length"
                                                        class="flexbox align-items-center">
                                                        <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                            width="27px" height="27px">
                                                            <g>
                                                                <path opacity="0.702"
                                                                    d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                    fill="#ffffff"></path>
                                                            </g>
                                                        </svg>
                                                        <span>upload cover picture</span>
                                                    </div>
                                                    <div ng-hide="!tvsnSelectedVideo.mobile_poster_image.length"
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
                                                    <input type="file" class="uploadMobilePosterImg" name="image"
                                                        data-video-index="@{{ tvsnSelectedVideo.id }}">
                                                </div>
                                                <p>( Only jpeg, png files allowed with a minimum dimension of 380x500 )
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="error-msg" data-ng-show="errors.mobile_poster_image.has">
                                @{{errors.mobile_poster_image.message}}</p>
                        </div>
                    </div>
                </div>

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                            <label>
                                Season Name
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.title.has}">
                                <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                    data-ng-model="tvsnSelectedVideo.title">
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
                                <input ng-if="!editPage" myValue="tvsnSelectedVideo.release_date" type="month"
                                    name="release_date" id="release_date" data-ng-model="tvsnSelectedVideo.release_date"
                                    size="30" placeholder="Enter Release Date" data-validation-name="release_date"
                                    class="form-control">
                                <input ng-if="editPage" myValue="tvsnSelectedVideo.release_date" type="month"
                                    name="release_date" id="release_date" data-ng-model="tvsnSelectedVideo.release_date"
                                    size="30" placeholder="Enter Release Date" data-validation-name="release_date"
                                    class="form-control">
                            </div>
                            <p class="error-msg" data-ng-show="errors.release_date.has">
                                @{{ errors.release_date.message }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Directors</label>
                            <div class="form-input">
                                <input type="text" name="directors" class="form-control"
                                    placeholder="Enter Director Name" data-ng-model="tvsnSelectedVideo.directors">
                            </div>
                            <p class="error-msg" data-ng-show="errors.director.has">@{{errors.presenter.message}}</p>
                        </div>

                        <div class="form-group">
                            <label>{{ __('video::videos.presenter') }}</label>
                            <div class="form-input">
                                <input type="text" name="presenter" class="form-control"
                                    placeholder="Enter Cast & Crew Name" data-ng-model="tvsnSelectedVideo.presenter">
                            </div>
                            <p class="error-msg" data-ng-show="errors.presenter.has">@{{errors.presenter.message}}</p>
                        </div>
                    </div>

                    <div class="one-set width-50">

                        <div class="form-group" data-ng-class="{'has-error': errors.season_number.has}">
                            <label>
                                Season Number
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.season_number.has}">
                                <input type="number" name="season_number" class="form-control"
                                    placeholder="Enter Season Number" data-ng-model="tvsnSelectedVideo.season_number">
                            </div>
                            <p class="error-msg" data-ng-show="errors.season_number.has">season number is required</p>
                        </div>

                        <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                            <label>
                                {{ __('video::videos.description') }}
                                <!-- <span class="required">*</span> -->
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.description.has}">
                                <textarea rows="7" name="description" maxlength="50000" class="form-control"
                                    data-ng-model="tvsnSelectedVideo.description"
                                    placeholder="  {{ __('video::videos.description_message') }}"></textarea>
                            </div>
                            <p class="error-msg" data-ng-show="errors.description.has">@{{errors.description.message}}
                            </p>
                        </div>

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
                                    <span>Is Active</span>
                                    <!-- <span>{{ __('video::videos.status') }}</span> -->
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="is_active"
                                                ng-model="tvsnSelectedVideo.is_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;">

                <div class="division flexbox">
                    <div class="one-set width-50">
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
                                                ng-model="tvsnSelectedVideo.scheduled_publishing"
                                                data-ng-change="scheduledDate()">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Scheduled Publishing end -->

                        <div ng-if="tvsnSelectedVideo.scheduled_publishing">
                            <div class="form-group" data-ng-class="{'has-error': errors.scheduled_time.has}">
                                <label>
                                    From
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input calender-left">
                                    <i class="calender-icon"></i>
                                    <input ng-if="!editPage" futureDate="true"
                                        myValue="tvsnSelectedVideo.scheduled_time" autocomplete="off"
                                        data-jquery="date_time_picker" datetime-picker type="text" name="scheduled_time"
                                        id="scheduled_time" data-ng-model="tvsnSelectedVideo.scheduled_time" size="30"
                                        placeholder="{{__('video::videos.scheduled_time')}}"
                                        data-validation-name="scheduled_time" value="{{date("Y-m-d H:i:s")}}"
                                        class="form-control" ng-blur="dateBlur($event,tvsnSelectedVideo.scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsnSelectedVideo.scheduled_time)" />

                                    <input ng-if="editPage" futureDate="true" myValue="tvsnSelectedVideo.scheduled_time"
                                        autocomplete="off" type="text" name="scheduled_time" id="scheduled_time"
                                        data-ng-model="tvsnSelectedVideo.scheduled_time" size="30"
                                        placeholder="{{__('video::videos.scheduled_time')}}"
                                        data-validation-name="scheduled_time" value="{{date("Y-m-d H:i:s")}}"
                                        class="form-control hello"
                                        ng-blur="dateBlur($event,tvsnSelectedVideo.scheduled_time)"
                                        ng-keyup="dateKeyup($event,tvsnSelectedVideo.scheduled_time)" />
                                    <!-- @{{ tvsnSelectedVideo.scheduled_time }} -->
                                </div>
                                <p class="error-msg" data-ng-show="errors.scheduled_time.has">
                                    @{{ errors.scheduled_time.message }}
                                </p>
                            </div>

                            <div class="form-group" data-ng-class="{'has-error': errors.expire_scheduled_time.has}">
                                <label>
                                    To <span class="required">*</span>
                                </label>

                                <div class="form-inline">
                                    <!-- Date Picker -->
                                    <div class="form-input calender-left"
                                        style="display:inline-flex; align-items:center; margin-right:10px;">
                                        <i class="calender-icon"></i>
                                        <input ng-if="!editPage" futureDate="true"
                                            myValue="tvsnSelectedVideo.expire_scheduled_time" autocomplete="off"
                                            data-jquery="date_time_picker" datetime-picker type="text"
                                            name="expire_scheduled_time" id="expire_scheduled_time"
                                            data-ng-model="tvsnSelectedVideo.expire_scheduled_time" size="30"
                                            placeholder="Expire Scheduled Time" class="form-control"
                                            data-validation-name="expire_scheduled_time" value="{{date('Y-m-d H:i:s')}}"
                                            ng-blur="dateBlur($event,tvsnSelectedVideo.expire_scheduled_time)"
                                            ng-keyup="dateKeyup($event,tvsnSelectedVideo.expire_scheduled_time)">

                                        <input ng-if="editPage" futureDate="true"
                                            myValue="tvsnSelectedVideo.expire_scheduled_time" autocomplete="off"
                                            type="text" name="expire_scheduled_time" id="expire_scheduled_time"
                                            data-ng-model="tvsnSelectedVideo.expire_scheduled_time" size="30"
                                            placeholder="Expire Scheduled Time" class="form-control hello"
                                            data-validation-name="expire_scheduled_time" value="{{date('Y-m-d H:i:s')}}"
                                            ng-blur="dateBlur($event,tvsnSelectedVideo.expire_scheduled_time)"
                                            ng-keyup="dateKeyup($event,tvsnSelectedVideo.expire_scheduled_time)">
                                    </div>

                                    <!-- Unlimited Checkbox -->
                                    <div class="checkbox" style="margin-top:0;">
                                        <label>
                                            <input type="checkbox" id="unlimited"
                                                data-ng-model="tvsnSelectedVideo.expire_time_unlimited"> Unlimited
                                        </label>
                                    </div>
                                </div>

                                <p class="error-msg" data-ng-show="errors.expire_scheduled_time.has">
                                    @{{ errors.expire_scheduled_time.message }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="one-set width-50">
                        <!-- publish code -->
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Publish Now</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="publish_now"
                                                ng-model="tvsnSelectedVideo.publish_now"
                                                ng-change="togglePublishDate()">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Show manual input when NOT active (publish_now != 1) -->
                        <div class="form-group" ng-if="tvsnSelectedVideo.publish_now != 1">
                            <label>
                                Enter Publish Time
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input ng-if="!editPage" futureDate="false" myValue="tvsnSelectedVideo.publish_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="publish_date" id="publish_date" data-ng-model="tvsnSelectedVideo.publish_date"
                                    size="30" placeholder="Enter Publish Time" data-validation-name="publish_date"
                                    class="form-control" ng-blur="dateBlur($event, tvsnSelectedVideo.publish_date)"
                                    ng-keyup="dateKeyup($event, tvsnSelectedVideo.publish_date)" />

                                <input ng-if="editPage" futureDate="true" myValue="tvsnSelectedVideo.publish_date"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="publish_date" id="publish_date" data-ng-model="tvsnSelectedVideo.publish_date"
                                    size="30" placeholder="Enter Publish Time" data-validation-name="publish_date"
                                    class="form-control" ng-blur="dateBlur($event, tvsnSelectedVideo.publish_date)"
                                    ng-keyup="dateKeyup($event, tvsnSelectedVideo.publish_date)" />
                            </div>
                            <p class="error-msg" data-ng-show="errors.publish_date.has">
                                @{{ errors.publish_date.message }}
                            </p>
                        </div>

                        <!-- Show auto-filled date/time only when active (is_active == 1) -->
                        <div class="form-group" ng-if="tvsnSelectedVideo.publish_now == 1">
                            <label>
                                Auto Publish Date/Time
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input readonly type="text" name="publish_date" class="form-control"
                                    ng-model="tvsnSelectedVideo.publish_date" placeholder="Auto Publish Time" />
                            </div>
                        </div>
                    </div>
                </div><br>

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

                                        <div class="bundle-item" ng-repeat="org in tvsnGridCtrl.selectedVideo.bundles"
                                            data-id="@{{org.organization_id}}" data-ng-model="channel.content_sets"
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

            <a class="save" data-ng-click="tvsnGridCtrl.BackToSeason(tvsnSelectedVideo.tv_show_id)" ng-if="!livePage">
                {{ __('video::videos.back') }}
            </a>

            <a class="save" data-ng-click="tvsnGridCtrl.BackToTvShow(tvsnGridCtrl.tvShowId)" ng-if="livePage">
                {{ __('video::videos.back') }}
            </a>

            <button id="videoEditFormSubmit" ng-if="!livePage"
                data-ng-click="tvsnGridCtrl.removeSeason($event, tvsnSelectedVideo.id)" class="save">
                Remove
            </button>

            <button id="videoEditFormSubmit" ng-if="!editPage"
                data-ng-click="tvsnGridCtrl.saveSeason($event, tvsnSelectedVideo.id)" class="publish-now">
                Save
            </button>

            <button id="videoEditFormSubmit" ng-if="!livePage"
                data-ng-click="tvsnGridCtrl.updateSeason($event, tvsnSelectedVideo.id)" class="publish-now">
                Update
            </button>
        </div>
    </div>
</div>

<br><br>

<!-- episode list -->
<div ng-if="!livePage" class="main-container"
    style="background: #fff; box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.2);">
    <div class="page-heading flexbox align-items-center flex-wrap">
        <div class="right-side flexbox align-items-center" style="padding: 13px;">
            <a ng-if="checkAccess('tv_shows')" title="Video On Demand"
                data-ng-click="tvsnGridCtrl.addSeasonEpisode($event)" class="button button-blue">
                <svg viewBox="0 0 16 18" x="0px" y="0px" width="15px" height="17px">
                    <g id="Layer%201">
                        <path id="Forma%201"
                            d="M 0.4998 17.9998 L 0.4998 15.9999 L 15.4998 15.9999 L 15.4998 17.9998 L 0.4998 17.9998 ZM 11.2141 13.9999 L 4.7855 13.9999 L 4.7855 7.9999 L 0.4998 7.9999 L 7.9999 0.9999 L 15.4998 7.9999 L 11.2141 7.9999 L 11.2141 13.9999 Z"
                            fill="#ffffff" />
                    </g>
                </svg>
                <span>Add An Episode</span>
            </a>
            

        </div>
    </div>

    <style>
        .episode-table-wrapper {
            width: 100%;
            overflow-x: auto;
            /* Allows horizontal scroll on small screens */
        }

        .episode-table {
            width: 100%;
            border-collapse: collapse;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: #fff;
        }

        .episode-table th,
        .episode-table td {
            padding: 12px 14px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #e6e6e6;
            white-space: nowrap;
            /* Prevents text wrapping */
        }

        .episode-table th {
            background: #f4f6f9;
            font-weight: 600;
            color: #333;
        }

        .episode-row:hover {
            background: #f9fafc;
        }

        .drag-icon {
            cursor: move;
            font-size: 16px;
            color: #888;
        }

        .action-icons i {
            margin: 0 6px;
            cursor: pointer;
            font-size: 16px;
            color: #666;
            transition: color 0.2s ease;
        }

        .action-icons i:hover {
            color: #007bff;
            /* Blue hover effect */
        }
    </style>

    <div class="episode-table-wrapper">
        <table class="episode-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Number</th>
                    <th>Episode Name</th>
                    <th>Publish Time</th>
                    <th>Unpublish Time</th>
                    <th>Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="episode-row" ng-repeat="record in tvsnGridCtrl.episodeList">
                    <td>
                        <span class="drag-icon">☰</span>
                    </td>

                    <td>
                        @{{ $index + 1 }}
                    </td>

                    <td>
                        @{{ record.episode_name }}
                    </td>

                    <td>
                        @{{ record.publish_date }}
                    </td>

                    <td>
                        <span ng-if="record.expire_time_unlimited == 1">Unlimited</span>
                        <span ng-if="record.expire_time_unlimited != 1">
                            @{{ record.expire_scheduled_time | date:'d/M/y HH:mm:ss' }}
                        </span>
                    </td>

                    <td>
                        0
                    </td>
                    <td class="action-icons">
                        <div class="flexbox align-items-center">
                            <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.is_active == 1"
                                        ng-click="tvsnGridCtrl.episodeToggleButton(record, record.id)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div data-ng-if="checkAccess('tv_show')" class="column edit_table_icon tooltip-parent">
                                <!-- <button data-ng-click="PpCtrl.editdata(record, record.id)"> -->
                                <a class="table_action"
                                    href="{{url('/admin/tvshow/season/episode-edit/episode-id/')}}/@{{ encodeId(record.id) }} ">
                                    <svg viewBox=" 0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                        <g>
                                            <path
                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </a>
                                <!-- </button> -->
                                <span class="tooltip_title">{{trans('base::general.edit')}}</span>
                            </div>

                            <!-- delete -->
                            <div class="tooltip-parent" data-ng-if="checkAccess('tv_show')">
                                <span ng-mouseover="getTooltip($event)" data-toggle="modal" data-target="#deleteModal"
                                    ng-click="deleteSingleRecord(record, record.id)" class="tooltips delete_table_icon"
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
        position: absolute;
        top: 5.2rem;
        right: 2rem;
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
        min-height: 200px;
        /* border: 1px dashed #ddd; */
        /* background: #f9f9f9; */
    }

    .scroll-box.drag-over {
        border-color: #00ACCD;
        background: #e6f7ff;
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

                            <!-- <div class="content-container panel panel-default" draggable="true"
                                data-ng-repeat="orgbundles in tvsnGridCtrl.OrganizationList"
                                data-id="@{{ orgbundles.id }}">
                                <div class="content-header">@{{ orgbundles.organization_name }}</div>
                                <div class="item-box">Post event</div>
                            </div> -->

                            <div class="content-container panel panel-default" draggable="true"
                                data-ng-repeat="org in tvsnGridCtrl.TvShowSetList" data-id="@{{ org.organization_id }}">
                                <div class="content-header">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    @{{ org.organization_name }}
                                </div>
                                <div class="item-box" ng-repeat="bundle in org.bundles">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    <i class="glyphicon glyphicon-blackboard"></i>
                                    @{{ bundle.name }}
                                </div>
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

                    <div class="scroll-box" id="addedBundles">
                        <div class="content-container panel panel-default" draggable="true"
                            data-ng-repeat="org in tvsnSelectedVideo.selectedBundles"
                            data-id="@{{ org.organization_id }}">
                            <div class="content-header">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                @{{ org.organization_name }}
                                <span class="remove-btn" style="cursor:pointer; float:right;"
                                    ng-click="removeBundle(org)">
                                    <i class="glyphicon glyphicon-remove-circle"></i>
                                </span>
                            </div>
                            <div class="item-box" ng-repeat="bundle in org.bundles">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ bundle.name }}
                            </div>
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
                    ng-click="tvsnGridCtrl.assignSelectedBundles()">
                    Assign
                </button>&nbsp;
                <button class="button button-gray" data-dismiss="modal">
                    Cancel
                </button>
            </div>

        </div>
    </div>
</div>