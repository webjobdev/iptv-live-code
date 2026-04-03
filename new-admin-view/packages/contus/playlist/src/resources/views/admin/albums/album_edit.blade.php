<!-- Album Form -->
<div class="contentpanel product order_list">
    <div class="panel main_container clearfix album-panel">
        <div class="add_form form_container">
            <form id="albumForm" name="albumForm" method="POST" data-ng-init="{{ $control }}.fetchData({{$id}})"
                data-base-validator data-ng-submit="{{ $control }}.albumSave($event,{{$id}})"
                enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="isImgUpdated" id="isImgUpdated" class="form-control" data-ng-model="albumPostData.isImgUpdated">
                <div class="video-detail form-page profile-page ">
                    <div class="page-padding">
                        <div class="division flexbox">
                            <div class="one-set width-50">
                                <div class="form-group" data-ng-class="{'has-error': errors.album_name.has}">
                                    <label>
                                        {{ __('audio::album.album_name') }} 
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <input type="text" name="album_name" class="form-control"
                                            placeholder="{{ __('audio::album.form_placeholders.album_name_placeholder')}}"
                                            data-ng-model="albumPostData.album_name">
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.album_name.has">@{{ errors.album_name.message }}</p>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.album_artists.has}">
                                    <label>
                                        {{ __('audio::album.artist') }}
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <select class="select2_custom_ddl" data-jquery="select2_custom_ddl" myValue="albumPostData.album_artists"
                                    myPlaceholder="{{ __('base::audio.select_artists') }}" name="album_artists" class="admin_category_sub  form-control"  data-ng-model="albumPostData.album_artists">
                                            <option value="">{{ __('base::audio.select_artists') }}</option>
                                            <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}"> 
                                                @{{audio_artist.artist_name}}</option>
                                        </select> 
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.album_artists.has">@{{ errors.album_artists.message }}</p>
                                </div>

                                
                                <div class="form-group" data-ng-class="{'has-error': errors.audio_language.has}">
                                    <label>
                                        {{ __('audio::album.language') }}
                                        <span class="required">*</span>
                                    </label>
                                    <div class="form-input">
                                        <select class="select2_custom_ddl" data-jquery="select2_custom_ddl" myValue="albumPostData.audio_language"
                                    myPlaceholder="{{ __('audio::album.select_language') }}" name="audio_language" class="admin_category_sub form-control"  data-ng-model="albumPostData.audio_language">
                                            <option value="">{{ __('audio::album.select_language') }}</option>
                                        <option ng-repeat="language in audio_languages" value="@{{language.id}}">@{{language.language_name}}</option>
                                        </select> 
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.audio_language.has">@{{ errors.audio_language.message }}</p>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.album_release_date.has}">
                                    <label>{{ __('audio::album.release_date') }}</label>
                                    <div class="form-input">
                                        <input datetime-picker  type="text" name="release_date" id="release_date" data-ng-model="albumPostData.album_release_date" size="30"  placeholder="{{ __('audio::album.release_date') }}" data-validation-name = "release_date" value="{{date ( "Y-m-d H:i:s")}}" class="form-control" ng-blur="dateBlur($event,albumPostData.album_release_date)" ng-keyup="dateKeyup($event,albumPostData.album_release_date)"/>
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.album_release_date.has">@{{
                                        errors.album_release_date.message }}</p>
                                </div>
                            </div>
                            <div class="one-set width-50">
                                <div class="form-group">
                                    <label>
                                        {{ __('audio::album.album_description') }}
                                    </label>
                                    <div class="form-input">
                                        <textarea rows="7" name="album_description" class="form-control" data-ng-model="albumPostData.album_description" placeholder="{{ __('audio::album.form_placeholders.album_description_placeholder')}}" value=""></textarea>
                                    </div>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.audio_genre.has}">
                                    <label>
                                        {{ __('audio::album.genre') }}
                                    </label>
                                    <div class="form-input">
                                        <select class="select2_custom_ddl" data-jquery="select2_custom_ddl" myValue="albumPostData.audio_genre"
                                    myPlaceholder="{{ __('audio::album.select_genre') }}"  name="audio_genre" class="admin_category_sub  form-control"  data-ng-model="albumPostData.audio_genre">
                                            <option value="">{{ __('audio::album.select_genre') }}</option>
                                            <option ng-repeat="audio_genre in audio_genres" value="@{{audio_genre.id}}"> 
                                                @{{audio_genre.genre_name}}</option>
                                        </select> 
                                    </div>
                                    <p class="error-msg" data-ng-show="errors.audio_genre.has">@{{ errors.audio_genre.message }}</p>
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
                                                    <input data-ng-if="{{ $control }}.audioLength > 0" type="checkbox" disabled name="is_active" ng-model="albumPostData.is_active">
                                                
                                                    <input data-ng-if="{{ $control }}.audioLength == 0" type="checkbox" name="is_active" ng-model="albumPostData.is_active">
                                                    <span class="slider round"></span>
                                                </label>
                                                <span class="text">(Active)</span>
                                            </div>
                                        </div>
                                    </div>                                
                                    <p class="error-msg" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
                                </div>

                                <div class="form-group" data-ng-class="{'has-error': errors.is_notify_customer.has}" style="display: none">
                                    <div class="switch-concept flexbox align-items-center">
                                        <svg viewBox="0 0 14 18" version="1.1" x="0px" y="0px" width="14px" height="18px">
                                            <g>
                                                <path id="Forma%201" d="M 13.9663 13.1473 C 13.899 12.7867 13.5536 12.5493 13.1948 12.6168 C 12.8361 12.6844 12.5996 13.0314 12.6669 13.3918 C 12.7167 13.6593 12.5997 13.8499 12.5255 13.9397 C 12.4515 14.0292 12.287 14.1794 12.0172 14.1794 L 1.9827 14.1794 C 1.7128 14.1794 1.5484 14.0293 1.4744 13.9397 C 1.4003 13.8499 1.2832 13.6593 1.3331 13.3918 C 1.5206 12.3867 1.8969 11.7256 2.2607 11.0863 C 2.7026 10.3098 3.1595 9.507 3.1595 8.2694 L 3.1595 7.2733 C 3.1595 5.1792 4.8539 3.4525 6.9374 3.4221 L 7.0624 3.4221 C 9.1389 3.4524 10.8276 5.1791 10.8276 7.2733 L 10.8276 8.2694 C 10.8276 9.3226 11.1706 10.0475 11.4921 10.6442 C 11.6118 10.8663 11.8392 10.9923 12.074 10.9923 C 12.1803 10.9923 12.2881 10.9664 12.3881 10.912 C 12.7092 10.7375 12.8286 10.3345 12.6548 10.012 C 12.3349 9.4183 12.1497 8.9427 12.1497 8.2694 L 12.1497 7.2733 C 12.1497 5.9066 11.6228 4.6158 10.6659 3.6387 C 9.8459 2.8013 8.7929 2.2787 7.6546 2.1344 L 7.6546 1.1639 C 7.6546 0.7972 7.3586 0.4999 6.9936 0.4999 C 6.6285 0.4999 6.3325 0.7972 6.3325 1.1639 L 6.3325 2.1364 C 3.8054 2.4658 1.8374 4.6561 1.8374 7.2733 L 1.8374 8.2694 C 1.8374 9.1542 1.5177 9.7159 1.1129 10.4271 C 0.7142 11.1279 0.2622 11.9221 0.0336 13.1473 C -0.0754 13.7322 0.0789 14.3302 0.4571 14.788 C 0.835 15.2454 1.3912 15.5077 1.9827 15.5077 L 5.0232 15.5077 C 5.0232 16.6062 5.9128 17.4998 7.0063 17.4998 C 8.0999 17.4998 8.9895 16.6062 8.9895 15.5077 L 12.0172 15.5077 C 12.6088 15.5077 13.1649 15.2454 13.5428 14.788 C 13.921 14.3302 14.0754 13.7322 13.9663 13.1473 ZM 7.0063 16.1716 C 6.6418 16.1716 6.3453 15.8738 6.3453 15.5077 L 7.6674 15.5077 C 7.6674 15.8738 7.3709 16.1716 7.0063 16.1716 Z" fill="#3d3d3d"></path>
                                            </g>
                                        </svg>
                                        <div class="swich-content flexbox align-items-center flex-wrap">
                                            <span>{{ __('audio::album.notify_customer') }}</span>
                                            <div class="right-side">
                                                <label class="switch">
                                                    <input type="checkbox" name="is_notify_customer" ng-model="albumPostData.is_notify_customer">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <p>Message will be trigged to customer through mail once you keep status on.</p>
                                        </div>
                                    </div>
                                    
                                    <p class="error-msg" data-ng-show="errors.is_notify_customer.has">@{{ errors.is_notify_customer.message }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="division flexbox">
                            <div class="one-set width-50">
                                <div class="profile_image_upload upload-cover-thumbnail">
                                    <div class="thumbnail-image ml-zero" data-ng-class="{'has-error': errors.thumbnail.has}">
                                        <h4>{{ __('base::audio.thumbnail') }}</h4>
                                        <div class="image-content">
                                            <img ng-src="@{{ albumPostData.thumbnail_image }}" ng-class="{'active': albumPostData.thumbnail_image}" class="uploaded_img" alt="">
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
                                                                <div ng-hide="albumPostData.thumbnail_image.length">
                                                                    <span>{{__('base::audio.select_image')}}</span>
                                                                </div>
                                                                <div ng-hide="!albumPostData.thumbnail_image.length">
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
                                            <p class="error-msg thumbnail-error" data-ng-show="errors.thumbnail.has">@{{errors.thumbnail.message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-button buttom-button-fixed text-right flexbox align-items-center  fixed-btm-action">
                        <div class="text-right btn-invoice">					
                            <a class="btn btn-danger save" href="{{url('admin/audios/album')}}">{{__('base::general.cancel')}}</a>	
                            <button class="btn btn-primary submitbutton publish-now">{{__('base::general.submit')}}</button>
                        </div>
                    </div>   
                </div>             
                @include('audio::admin.albums.audio_edit_upload',['form_type' => 'add', 'control' => 'albumEditCtrl']) 
                @include('audio::admin.common.audio_upload',['form_type' => 'editAlbum', 'control' => 'albumEditCtrl']) 
            </form>
        </div>
    </div>
</div>
@include('audio::admin.common.imageCropper');
@include('audio::admin.albums.modal');
@include('audio::admin.common.popup');