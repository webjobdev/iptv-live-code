<!-- Album Form -->
<div class="contentpanel product order_list audio-edit">
    <div class="panel main_container clearfix">
        <div class="add_form form_container">
            @if($form_type == 'add')
                <form id="audioForm" name="audioForm" method="POST" data-base-validator data-ng-submit="{{ $control }}.audioSave($event)"
                enctype="multipart/form-data">
            @else 
                <form name="audioForm" method="POST" data-ng-init="{{ $control }}.fetchData({{$id}})"
                data-base-validator data-ng-submit="{{ $control }}.audioSave($event,'{{$id}}')"
                enctype="multipart/form-data">
            @endif
                {!! csrf_field() !!}
                <input type="hidden" name="isImgUpdated" id="isImgUpdated" class="form-control" data-ng-model="{{ $control }}.albumPostData.isImgUpdated">
                <div class="video-detail form-page profile-page">
                    <div class="page-padding">
                        <div class="division flexbox">
                            <div class="one-set width-50">
                                <div class="form-group" data-ng-class="{'has-error': errors.audio_title.has}">
                                    <label>
                                        {{ __('audio::audio.audio_title') }}
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <input type="text" name="audio_title" class="form-control"
                                            placeholder="{{ __('audio::audio.form_placeholders.audio_title_placeholder')}}"
                                            data-ng-model="{{ $control }}.audioPostData.audio_title">
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.audio_title.has">@{{ errors.audio_title.message }}</p>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.audio_artist.has}">
                                    <label>
                                        {{ __('audio::audio.artists') }}
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <select class="select2_custom_ddl" allowClear="1" data-jquery="select2_custom_ddl" myValue="{{ $control }}.audioPostData.audio_artist"
                                    myPlaceholder="{{ __('base::audio.select_artists') }}" name="audio_artist" class="admin_category_sub form-control"  data-ng-model="{{ $control }}.audioPostData.audio_artist">
                                            <option value="">{{ __('base::audio.select_artists') }}</option>
                                            <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}"> 
                                                @{{audio_artist.artist_name}}</option>
                                        </select> 
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.audio_artist.has">@{{ errors.audio_artist.message }}</p>
                                </div>

                                <div class="form-group">
                                    @if($form_type == 'add')
                                        <label>{{__('audio::audio.add_audio')}}</label>
                                    @else
                                        <label>{{__('audio::audio.edit_audio')}}</label>
                                    @endif
                                    <div class="form-input edit_video_upload">
                                        <div class="edit_video_file">
                                            <div class="upload_file_input subtitle-add subtitle_btn">
                                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                                    <g>
                                                        <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                                                    </g>
                                                </svg>
                                                <span class="btn btn-default btn-file">{{__('audio::audio.choose_file')}}</span>
                                                <input type="file" class="filestyle" accept="audio/*" id="audio" name="audio" data-buttonName="btn-primary">
                                            </div>
                                            <span class="upload-status-wrapper" style="display: none;"><span class="upload-success fileupload-preview">Upload Completed !!</span></span>
                                        </div>
                                        <p class="intimation">( {{ __('base::audio.audio_formats_initmation') }} )</p>
                                        <p class="error-msg" id="video_error" style="display:none;">@{{ errors.is_active.message }} {{ __('audio::audio.select_valid_file') }}</p>
                                        <div class="progress-container" id="progress-bar-wrap" style="display: none;">
                                            <div class="progress progress-striped active">
                                                <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 11%;"><p id="upload_percentage" style="display: block;">11%</p></div>
                                            </div>
                                        </div>
                                        <div class="upload-status-wrapper" style="display: none;">
                                            <span class="upload-success" style="display: none;">Upload Completed !!</span>
                                            <span class="reset category-image-remove" data-ng-click="{{ $control }}.resetAudio()">Remove</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="one-set width-50">
                                <div class="form-group">
                                    <label>
                                        {{ __('audio::audio.audio_description') }}
                                    </label>
                                    <div class="form-input">
                                        <textarea rows="7" name="audio_description" class="form-control" data-ng-model="{{ $control }}.audioPostData.audio_description" placeholder="{{ __('audio::audio.form_placeholders.audio_description_placeholder')}}" value=""></textarea>
                                    </div>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.audio_album.has}">
                                    <label>
                                        {{ __('audio::audio.albums') }}
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <select class="select2_custom_ddl" allowClear="1" data-jquery="select2_custom_ddl" myValue="{{ $control }}.audioPostData.audio_album"
                                    myPlaceholder="{{ __('audio::audio.select_albums') }}" name="audio_album" class="admin_category_sub  form-control"  data-ng-model="{{ $control }}.audioPostData.audio_album">
                                            <option value="">{{ __('audio::audio.select_albums') }}</option>
                                            <option ng-repeat="album in audio_albums" value="@{{album.id}}"> 
                                                @{{album.album_name}}</option>
                                        </select> 
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.audio_album.has">@{{ errors.audio_album.message }}</p>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
                                    <div class="switch-concept flexbox align-items-center">
                                        <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                            <g>
                                                <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
                                            </g>
                                        </svg>
                                        <div class="swich-content flexbox align-items-center flex-wrap">
                                            <span>{{ __('audio::album.status') }}</span>
                                            <div class="right-side flexbox align-items-center">
                                                <span class="text">(InActive)</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="is_active" ng-model="{{ $control }}.audioPostData.is_active">
                                                    <span class="slider round"></span>
                                                </label>
                                                <span class="text">(Active)</span>
                                            </div>
                                        </div>
                                    </div>                                
                                    <p class="error-msg" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="division flexbox">
                            <div class="one-set width-50">
                                <div class="profile_image_upload upload-cover-thumbnail">
                                    <div class="thumbnail-image ml-zero" data-ng-class="{'has-error': errors.thumbnail.has}">
                                        <h4>{{ __('base::audio.thumbnail') }}</h4>
                                        <div class="image-content">
                                            <img ng-src="@{{ $control.audioPostData.thumbnail_image }}" ng-class="{'active': {{ $control }}.audioPostData.thumbnail_image}" class="uploaded_img" alt="">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileuploadbox">
                                                    <div class="input-append">
                                                        <div class="overlay-content">
                                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px" width="27px" height="27px">
                                                                <g>
                                                                    <path opacity="0.702" d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z" fill="#ffffff"></path>
                                                                </g>
                                                            </svg>
                                                            <div class="input">
                                                                <div ng-hide="{{ $control }}.audioPostData.thumbnail_image.length">
                                                                    <span>{{__('base::audio.select_image')}}</span>
                                                                </div>
                                                                <div ng-hide="!{{ $control }}.audioPostData.thumbnail_image.length">
                                                                    <span>{{__('base::audio.change')}}</span>
                                                                </div>                                                            
                                                                <input type="file"  accept="image/*" class="uploadImg" name="image"/>
                                                                <input type="hidden" class="module" id="module" name="module" value="album_image"/>
                                                                <input type="hidden" class="size" id="size" name="size" value="thumb"/>   
                                                            </div>
                                                            <p>( {{ __('base::audio.image_formats_initmation') }} )</p>
                                                        </div>                                                
                                                    </div>                                                
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <p class="error-msg thumbnail-error" data-ng-show="errors.thumbnail.has">@{{errors.thumbnail.message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--AUDIO FILE UPLOAD -->
                        <div ng-hide="false" class="contentpanel clearfix add_video_container" id="video_frame" style="display:none;">
                                <i class="fa fa-times" aria-hidden="true" ></i>
                                <!-- <form name="videoForm" enctype="multipart/form-data"> -->
                                    <div id="file_drop_area" class="upload_video_container">
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        <div data-ng-show="{{ $control }}.numberOfActivePresets > 0">
                                            <div id="upload_errors_wrap">
                                                <h2 id="upload_error">{{ __('audio::audio.upload_error') }}</h2>
                                                <h2 id="upload_staus_when_error"></h2>
                                            </div>
                                            <h2 id="upload_title">
                                                <span>{{ __('audio::audio.drag_and_drop') }}</span>
                                            </h2> 
                                            <span class="or">Or</span>
                                            <div class="upload_file_input">
                                                <input type="file" class="filestyle" id="video_old" name="audio" data-buttonName="btn-primary"
                                                    multiple>
                                                <span>{{ __('audio::audio.browse_from_computer') }}</span>
                                            </div>
                                            <p>{{ __('audio::audio.accepted_video_formats') }}</p>
                                            <p id="video_error">{{ __('audio::audio.select_valid_file') }}</p>
                                            <p id="upload_percentage"></p>
                                            <div id="video_upload_button_wrap" class="video_upload_div_btn">
                                                <button class="btn btn-primary" type="button"
                                                title="{{ __('audio::audio.upload') }}">{{ __('audio::audio.upload') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-ng-show="{{ $control }}.numberOfActivePresets > 0 && false"
                                        style=" text-align: center; padding-bottom: 20px">
                                        <button id="google_drive_upload_button" style="padding: 10px" data-ng-click="{{ $control }}.onApiLoad()"
                                                type="submit" value="Submit">
                                            <img src="{{$getBaseAssetsUrl('images/admin/google_drive.png')}}">
                                        </button>
                                        <!-- The Google API Loader script. -->
                                        <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
                                        <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>
                                    </div>
                                <!-- </form> -->
                                <div class="col-xs-12 col-sm-12 progress-container">
                                    <div id="progress-bar-wrap" class="progress progress-striped active">
                                        <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                            <!--AUDIO FILE UPLOAD -->     
                        

                        <div class="clear"></div>

                    </div>
                    <div class="bottom-button text-right flexbox align-items-center fixed-btm-action">
                        <div class="text-right btn-invoice">				
                            <a class="btn btn-danger save" href="javascript:;" onclick="window.history.back();">{{__('base::general.cancel')}}</a>		
                            <button class="btn btn-primary submitbutton publish-now">{{__('base::general.submit')}}</button>
                        </div>
                    </div>
                </div>               
            </form>
        </div>
    </div>
</div>
@include('audio::admin.common.imageCropper')