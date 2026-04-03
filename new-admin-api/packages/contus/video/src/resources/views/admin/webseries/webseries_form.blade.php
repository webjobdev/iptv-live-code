<!-- Webseries Form -->
<div class="video-detail form-page profile-page contentpanel product order_list">

    @if($form_type == 'edit')
    <div class="card-right" style="margin: 20px;">
        <div class="card-content">
            <div class="header-section flexbox align-items-center flex-wrap">
                <div class="right-side">
                    <a ng-if="checkAccess('videos_all_write')" title="{{__('video::videos.video_upload')}}" href="{{url('admin/videos/upload_video')}}?&is_webseries=true&webseries_id={{ $category->id }}"
                        class="button button-blue">
                        <svg viewBox="0 0 16 18" x="0px" y="0px" width="15px" height="17px">
                            <g id="Layer%201">
                                <path id="Forma%201" d="M 0.4998 17.9998 L 0.4998 15.9999 L 15.4998 15.9999 L 15.4998 17.9998 L 0.4998 17.9998 ZM 11.2141 13.9999 L 4.7855 13.9999 L 4.7855 7.9999 L 0.4998 7.9999 L 7.9999 0.9999 L 15.4998 7.9999 L 11.2141 7.9999 L 11.2141 13.9999 Z"
                                fill="#ffffff" />
                            </g>
                        </svg>
                        <span>{{__('video::videos.video_upload')}}</span>
                    </a>
                    <a ng-if="checkAccess('videos_all_write')" title="{{__('video::webseries.webseries_video_list')}}" href="{{url('admin/webserier/videos/'.$category->id)}}"
                        class="button button-blue">
                        <span>{{__('video::webseries.webseries_video_list')}}</span>
                    </a>
                        <select data-jquery="select2_custom_ddl" minimumResults="-1" class="lang-select select2_custom_ddl"
                            style="width:100px" ng-change="languageChange()" data-ng-model="selectedLanguage"
                            data-ng-options="lang.id as lang.title  for lang in webseriesEditCtrl.language ">

                        </select>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="panel main_container clearfix" style="border: 1px solid __parent;">
        <div class="add_form form_container">
            @if($form_type == 'edit')
                 <form id="webseriesForm" name="webseriesForm" method="POST" data-ng-init="{{ $control }}.fetchData({{$id}})"
                data-base-validator data-ng-submit="{{ $control }}.webseriesSave($event,'{{$id}}')"
                enctype="multipart/form-data">
            @else
                 <form id="webseriesForm" name="webseriesForm" method="POST"
                data-base-validator data-ng-submit="{{ $control }}.webseriesSave($event,'{{URL::previous()}}')"
                enctype="multipart/form-data">
            @endif
                {!! csrf_field() !!}
                <div class="page-padding">
                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <div class="cover-image">
                        <h4>{{ __('video::videos.poster') }}</h4>
                        <div class="image-content">
                            <img ng-show="webseriesPostData.poster_image.length > 0"
                                ng-class="{'active':webseriesPostData.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ webseriesPostData.id }}" alt=""
                                ng-src="@{{webseriesPostData.poster_image  }}" />

                            <img ng-show="webseriesPostData.poster_image.length == 0"
                                ng-class="{'active':webseriesPostData.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ webseriesPostData.id }}" alt=""
                                ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': webseriesPostData.poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="webseriesPostData.poster_image.length"
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
                                                <div ng-hide="!webseriesPostData.poster_image.length"
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
                                                <input type="file" class="uploadPosterImg" name="image" accept="image/png,image/jpg, image/jpeg"
                                                    data-video-index="@{{ webseriesPostData.id }}">
                                            </div>
                                            <p>{{ __('video::videos.poster_file_hint') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.poster_image.has">@{{errors.poster_image.message}}</p>
                    </div>
                    <div class="thumbnail-image">
                        <h4>{{ __('video::videos.thumbnail')}}</h4>
                        <div class="image-content">
                            <img ng-show="webseriesPostData.thumbnail_image.length > 0"
                                ng-class="{'active': webseriesPostData.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ webseriesPostData.id }}" alt=""
                                ng-src="@{{ webseriesPostData.thumbnail_image }}" />

                            <img ng-show="webseriesPostData.thumbnail_image.length == 0"
                                ng-class="{'active': webseriesPostData.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ webseriesPostData.id }}" alt="" ng-src="" />
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': webseriesPostData.thumbnail_image.length > 0}">
                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                width="27px" height="27px">
                                                <g>
                                                    <path opacity="0.702"
                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                        fill="#ffffff"></path>
                                                </g>
                                            </svg>
                                            <div class="input">
                                                <div ng-hide="webseriesPostData.thumbnail_image.length">
                                                    <span>{{ __('video::videos.upload_thumbnail_image') }}</span>
                                                </div>
                                                <div ng-hide="!webseriesPostData.thumbnail_image.length"
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
                                                <input type="file" class="uploadImg" name="image" accept="image/png,image/jpg, image/jpeg"
                                                    data-video-index="@{{ webseriesPostData.id }}">
                                            </div>
                                            <p>{{ __('video::videos.thumb_file_hint') }}</p>
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
                                    {{ __('video::webseries.title') }}
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ __('video::webseries.form_placeholders.webseries_placeholder')}}"
                                        data-ng-model="webseriesPostData.title">
                                </div>
                                <p class="error-msg" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('video::videos.presenter') }}</label>
                                <div class="form-input">
                                    <input type="text" name="starring" class="form-control"
                                        placeholder="{{ __('video::videos.principal_performer') }}"
                                        data-ng-model="webseriesPostData.starring">
                                </div>
                                <p class="error-msg" data-ng-show="errors.starring.has">@{{errors.starring.message}}</p>
                            </div>

                            <input type="hidden" id="webseries-category" name="category" value="@{{webseries_categories[0].id}}">

                            <!-- <div class="form-group" data-ng-class="{'has-error': errors.webseries_order.has}">
                            <label>
                                {{ __('video::webseries.webseries_order') }}
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.webseries_order.has}">
                                <input type="number" class="form-control" min="1"
                                    placeholder="{{ __('video::webseries.enter_webseries_order') }}"
                                    data-ng-model="webseriesPostData.webseries_order">
                            </div>
                            <p class="error-msg" data-ng-show="errors.webseries_order.has">@{{errors.webseries_order.message}}</p>
                             </div> -->
                        </div>

                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                                <label>
                                    {{ __('video::webseries.description') }}
                                    {{-- <span class="required">*</span> --}}
                                </label>
                                <div class="form-input">
                                    <textarea rows="7" name="description" class="form-control"
                                        data-ng-model="webseriesPostData.description"
                                        placeholder="{{ __('video::webseries.form_placeholders.webseries_description_placeholder')}}"
                                        value="{{old('description')}}"></textarea>
                                </div>
                                <p class="error-msg" data-ng-show="errors.description.has">@{{errors.description.message
                                    }}</p>
                            </div>

                            <!-- <div class="form-group">
                                <label>
                                    {{ __('video::webseries.description') }}
                                </label>
                                <div class="form-input">
                                    <textarea rows="7" name="description" class="form-control" data-ng-model="webseriesPostData.description" placeholder="{{ __('video::webseries.form_placeholders.webseries_description_placeholder')}}" value=""></textarea>
                                </div>
                            </div> -->

                            <div class="form-group" data-ng-class="{'has-error': errors.genre.has}">
                                <label>
                                    {{ __('video::webseries.genre') }}
                                </label>
                                <div class="form-input">
                                    <select class="select2_custom_ddl" allowClear="1" data-jquery="select2_custom_ddl" name="genre" class="admin_category_sub  form-control"  myValue="webseriesPostData.genre"
                                    myPlaceholder="{{ __('video::webseries.select_genre') }}" data-ng-model="webseriesPostData.genre">
                                        <option value="">{{ __('video::webseries.select_genre') }}</option>
                                        <option ng-repeat="genre in video_genres" value="@{{genre.id}}">
                                            @{{genre.name}}</option>
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.genre.has">@{{ errors.genre.message }}</p>
                            </div>

                            <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
                                <div class="switch-concept flexbox align-items-center">
                                    <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                        <g>
                                            <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
                                        </g>
                                    </svg>
                                    <div class="swich-content flexbox align-items-center flex-wrap">
                                        <span>{{ __('base::audio.status') }}</span>
                                        <div class="right-side flexbox align-items-center">
                                            <span class="text">(InActive)</span>
                                            <label class="switch">
                                                <input type="checkbox" name="is_active" ng-model="webseriesPostData.is_active">
                                                <span class="slider round"></span>
                                            </label>
                                            <span class="text">(Active)</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="error-msg" data-ng-show="errors.is_active.has">@{{ errors.is_active.message}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom-button buttom-button-fixed text-right flexbox align-items-center fixed-btm-action">
                    <div class="text-right btn-invoice">
                        <a class="btn btn-danger save" href="{{url('admin/webseries')}}">{{__('base::general.cancel')}}</a>
                        <button id="webseries-submit-btn" class="btn btn-primary submitbutton publish-now">{{__('base::general.submit')}}</button>
                    </div>
                </div>
            </form>


            @if($form_type == 'edit')
            <!-- Language Form -->
            <form
            id="languageForm"
            style="display: none; margin: 20px;"
            name="languageForm"
            method="POST"
            data-base-validator
            data-ng-submit="webseriesEditCtrl.saveLanguage($event, webseriesPostData.id)"
            >
                <div class="division flexbox">
                    <div class="one-set width-50">
                    <div class="form-group">
                        <label>
                        {{ __('video::videos.title') }}
                        <span class="required">*</span>
                        </label>
                        <div class="form-input">
                        <!-- <input type="text" disabled="disabled" name="title" class="form-control"
                                        placeholder=" {{ __('video::videos.enter_title') }}"
                                        data-ng-model="selectedVideo.title"> -->
                        <input
                            type="text"
                            disabled="disabled"
                            name="title"
                            class="form-control"
                            placeholder="{{ __('video::webseries.form_placeholders.webseries_placeholder')}}"
                            data-ng-model="webseriesPostData.title"
                        />
                        </div>
                    </div>
                    <div class="form-group">
                        <label
                        >{{ __('video::videos.description') }}
                        <span class="required">*</span>
                        </label>
                        <div class="form-input">
                        <textarea
                            rows="7"
                            disabled="disabled"
                            name="description"
                            class="form-control"
                            data-ng-model="webseriesPostData.description"
                            placeholder="{{ __('video::videos.description_message') }}"
                            value="{{old('content')}}"
                        ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('video::videos.presenter') }}</label>
                        <div class="form-input">
                        <input
                            data-validation-name="Cast"
                            disabled="disabled"
                            type="text"
                            name="presenter"
                            class="form-control"
                            placeholder="{{ __('video::videos.principal_performer') }}"
                            data-ng-model="webseriesPostData.starring"
                        />
                        </div>
                        <p class="error-msg" data-ng-show="errors.presenter.has">
                        @{{errors.presenter.message}}
                        </p>
                    </div>
                    </div>
                    <div class="one-set width-50 new-arrow">
                    <div class="form-group">
                        <label>
                        {{ __('video::videos.title') }}
                        <span class="required">*</span>
                        </label>
                        <div class="form-input">
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder=" {{ __('video::videos.enter_title') }}"
                            data-ng-model="selectedLanguageVideo.title"
                        />
                        </div>
                        <p class="error-msg" data-ng-show="errors.title.has">
                        @{{errors.title.message }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label>
                        {{ __('video::videos.description') }}
                        <span class="required">*</span>
                        </label>
                        <div class="form-input">
                        <textarea
                            rows="7"
                            name="description"
                            class="form-control"
                            data-ng-model="selectedLanguageVideo.description"
                            placeholder=" {{ __('video::videos.description_message') }}"
                            value="{{old('content')}}"
                        ></textarea>
                        </div>
                        <p class="error-msg" data-ng-show="errors.description.has">
                        @{{errors.description.message }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('video::videos.presenter') }}</label>
                        <div class="form-input">
                        <input
                            data-validation-name="Cast"
                            type="text"
                            name="presenter"
                            class="form-control"
                            placeholder="{{ __('video::videos.principal_performer') }}"
                            data-ng-model="selectedLanguageVideo.presenter"
                        />
                        </div>
                        <p class="error-msg" data-ng-show="errors.presenter.has">
                        @{{errors.presenter.message}}
                        </p>
                    </div>
                    </div>

                    <div
                    class="bottom-button buttom-button-fixed text-right flexbox align-items-center fixed-btm-action"
                    >
                    <div class="text-right btn-invoice">
                        <a class="btn btn-danger save" href="{{url('admin/webseries')}}"
                        >{{__('base::general.cancel')}}</a
                        >
                        <button
                        id="videoLanguageEditFormSubmit"
                        data-ng-click="webseriesEditCtrl.saveLanguage($event, webseriesPostData.id)"
                        class="publish-now"
                        >
                        {{ __('base::general.submit') }}
                        </button>
                    </div>
                    </div>
                </div>
            </form>
            @endif



        </div>
    </div>
</div>
