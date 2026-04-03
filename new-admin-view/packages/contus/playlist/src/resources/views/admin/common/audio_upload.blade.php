<div class="contentpanel upload_video_page add_video_container album-upload-new" id="video_frame" style="display: block;">
   <form name="videoForm" enctype="multipart/form-data">
      <div id="file_drop_area" class="upload_video_container">
         @if($form_type !== 'addAlbum' && $form_type !== 'editAlbum')                          
            <svg data-url="{{url('admin/audios/audios')}}" x="0px" y="0px" width="18px"
               height="18px" viewBox="0 0 612 612" class="upload_cancel"
               data-ng-click="hideUploadOption($event)">
               <g>
                  <g id="cross">
                     <g>
                        <polygon
                           points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397      306,341.411 576.521,611.397 612,575.997 341.459,306.011    "
                           fill="#252629"></polygon>
                     </g>
                  </g>
               </g>
            </svg>
         @endif

         
         <h3 class="upload_pg_title">Upload New Audios</h3>
         <div id="video-initial-upload-container">
            <div class="upload_box">
               <svg viewBox="0 0 46 47" x="0px" y="0px" width="46px" height="47px">
                  <g>
                     <path
                        d="M 0.4999 7.4999 L 45.4999 7.4999 L 45.4999 38.4999 L 36.4999 47.4999 L 0.4999 47.4999 L 0.4999 7.4999 Z"
                        fill="#e7f7ff"></path>
                     <path d="M 36.9999 47.4999 L 36.9999 38.4999 L 45.9999 38.4999 L 36.9999 47.4999 Z" fill="#6dbdcd">
                     </path>
                     <path
                        d="M 28.9998 5.2749 C 28.9998 2.1249 24.5247 0.4999 22.9999 0.4999 C 21.5625 0.4999 17 2.1249 17 5.2749 L 17 16.2999 C 17 18.6099 18.9636 20.4999 21.3636 20.4999 C 23.7635 20.4999 25.7271 18.6099 25.7271 16.2999 L 25.7271 7.3749 C 25.7271 5.9049 24.5272 4.7499 22.9999 4.7499 C 21.4726 4.7499 20.2726 5.9049 20.2726 7.3749 L 20.2726 15.2499 L 21.909 15.2499 L 21.909 7.3749 C 21.909 6.7449 22.3453 6.3249 22.9999 6.3249 C 23.6544 6.3249 24.0908 6.7449 24.0908 7.3749 L 24.0908 16.2999 C 24.0908 17.77 22.8908 18.9249 21.3636 18.9249 C 19.8363 18.9249 18.6364 17.77 18.6364 16.2999 L 18.6364 5.2749 C 18.6364 2.965 22.3479 2.075 22.9999 2.075 C 23.652 2.075 27.3635 2.965 27.3635 5.2749 L 27.3635 7.2499 L 28.9998 7.2499 L 28.9998 5.2749 Z"
                        fill="#3f69a1"></path>
                  </g>
               </svg>
               <h4 id="upload_title" class="drap_drop_title">Just drag and drop Audio file</h4>
            </div>
            <!-- <h2 id="upload_title">
               <span>{{ __('audio::audio.drag_and_drop') }}</span>
            </h2> -->
            <span class="or" id="or">Or</span>
            <div class="upload_file_input">
               <input type="file" accept="audio/*" title= " " class="filestyle" id="audio" name="audio"
                  data-buttonName="btn-primary" multiple>
               <span>{{ __('audio::audio.browse_from_computer') }}</span>
            </div>            
            <div id="video_upload_button_wrap" class="video_upload_div_btn" style="display: block;">
               <button class="upload-new-button" type="button"
                  title="{{ __('audio::audio.upload_btn_txt') }}">{{ __('audio::audio.upload_btn_txt') }}</button>
            </div>
            <p class="video-accepted-formats" id="audio-formats-intimation">
               {{ __('base::audio.audio_formats_initmation') }}
            </p>
         </div>
         <div class="progress-container" id="progress-bar-wrap" style="display: none;">
            <span>Uploading...</span>
            <div class="progress progress-striped active">
               <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%">
                  <p id="upload_percentage"></p>
               </div>
            </div>
         </div>
      </div>
      <p id="video_error" class="error-msg" style="display: none;">{{ __('audio::audio.select_valid_file') }}</p>
      <div id="upload_errors_wrap">
         <p id="upload_error" class="error-msg" style="display: none;">{{ __('audio::audio.upload_error') }}</p>
         <p id="upload_staus_when_error" class="error-msg"></p>
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
   </form>
</div>
<div class="audio-upload-wrapper">
   <div class="audio-upload-lists audio_item_@{{$index}}" id="audio_itemname_@{{item.file_slug}}"
      data-ng-repeat="item in audioUploadLists">
      <div class="audio_accordion_wrapper upload_form_container">
         <div class="panel-group accordion_wrapper_@{{$index}} + " id="accordion">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <div class="panel-heading-left">
                     <h4 class="panel-title">
                        <a data-ng-show="albumPostData.audioPostData[$index].id" data-toggle="collapse" class=""
                           data-parent="#accordion" href="#collapse_@{{$index}}" aria-expanded="false">
                           @{{item.file_name}} </a>
                        <a data-ng-show="!albumPostData.audioPostData[$index].id" data-toggle="collapse" class=""
                           data-parent="#accordion" aria-expanded="false"> @{{item.file_name}}</a>
                     </h4>
                     <span class="fileSize">@{{item.file_size}}</span>
                     <div class="file-progress-wrapper">
                        <div class="progress progress-striped active">
                           <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%">
                              <p id="upload_percentage"></p>
                           </div>
                        </div>
                        <div class="file-complete-status"></div>
                        <div class="audio-status-status"></div>
                     </div>
                  </div>
                  <div class="panel-heading-right">
                     <a data-ng-if="albumPostData.audioPostData[$index].id" class="accordion_btn accordion_btn_edit"
                        data-toggle="collapse" data-parent="#accordion" href="#collapse_@{{$index}}">
                        <svg x="0px" y="0px" width="13px" height="13px" viewBox="0 0 469.331 469.331">
                           <g>
                              <path
                                 d="M438.931,30.403c-40.4-40.5-106.1-40.5-146.5,0l-268.6,268.5c-2.1,2.1-3.4,4.8-3.8,7.7l-19.9,147.4   c-0.6,4.2,0.9,8.4,3.8,11.3c2.5,2.5,6,4,9.5,4c0.6,0,1.2,0,1.8-0.1l88.8-12c7.4-1,12.6-7.8,11.6-15.2c-1-7.4-7.8-12.6-15.2-11.6   l-71.2,9.6l13.9-102.8l108.2,108.2c2.5,2.5,6,4,9.5,4s7-1.4,9.5-4l268.6-268.5c19.6-19.6,30.4-45.6,30.4-73.3   S458.531,49.903,438.931,30.403z M297.631,63.403l45.1,45.1l-245.1,245.1l-45.1-45.1L297.631,63.403z M160.931,416.803l-44.1-44.1   l245.1-245.1l44.1,44.1L160.931,416.803z M424.831,152.403l-107.9-107.9c13.7-11.3,30.8-17.5,48.8-17.5c20.5,0,39.7,8,54.2,22.4   s22.4,33.7,22.4,54.2C442.331,121.703,436.131,138.703,424.831,152.403z">
                              </path>
                           </g>
                        </svg>
                        {{__('base::general.edit')}}
                     </a>

                     <a data-ng-if="albumPostData.audioPostData[$index].id" title="{{__('audio::album.remove')}}"
                        href="javascript:void(0)" class="accordion_btn accordion_btn_delete" data-toggle="modal"
                        data-target="#singleDeleteModal"
                        data-ng-click="{{ $control }}.deleteSingleRecord(albumPostData.audioPostData[$index].id, $index, 'add')"
                        data-boot-tooltip="true" data-original-title="{{__('audio::album.remove')}}">
                        <svg x="0px" y="0px" width="13px" height="15px" viewBox="0 0 774.266 774.266">
                           <g>
                              <g>
                                 <path
                                    d="M640.35,91.169H536.971V23.991C536.971,10.469,526.064,0,512.543,0c-1.312,0-2.187,0.438-2.614,0.875    C509.491,0.438,508.616,0,508.179,0H265.212h-1.74h-1.75c-13.521,0-23.99,10.469-23.99,23.991v67.179H133.916    c-29.667,0-52.783,23.116-52.783,52.783v38.387v47.981h45.803v491.6c0,29.668,22.679,52.346,52.346,52.346h415.703    c29.667,0,52.782-22.678,52.782-52.346v-491.6h45.366v-47.981v-38.387C693.133,114.286,670.008,91.169,640.35,91.169z     M285.713,47.981h202.84v43.188h-202.84V47.981z M599.349,721.922c0,3.061-1.312,4.363-4.364,4.363H179.282    c-3.052,0-4.364-1.303-4.364-4.363V230.32h424.431V721.922z M644.715,182.339H129.551v-38.387c0-3.053,1.312-4.802,4.364-4.802    H640.35c3.053,0,4.365,1.749,4.365,4.802V182.339z" />
                                 <rect x="475.031" y="286.593" width="48.418" height="396.942" />
                                 <rect x="363.361" y="286.593" width="48.418" height="396.942" />
                                 <rect x="251.69" y="286.593" width="48.418" height="396.942" />
                              </g>
                           </g>
                        </svg>
                        {{__('audio::album.remove')}}
                     </a>
                     <a data-ng-if="!albumPostData.audioPostData[$index].id" title="{{__('audio::album.remove')}}"
                        href="javascript:void(0)" class="accordion_btn accordion_btn_delete" data-toggle="modal"
                        data-target="#singleDeleteModal"
                        data-ng-click="{{ $control }}.deleteSingleRecord('', $index, 'add')" data-boot-tooltip="true"
                        data-original-title="{{__('audio::album.remove')}}">
                        <svg x="0px" y="0px" width="13px" height="15px" viewBox="0 0 774.266 774.266">
                           <g>
                              <g>
                                 <path
                                    d="M640.35,91.169H536.971V23.991C536.971,10.469,526.064,0,512.543,0c-1.312,0-2.187,0.438-2.614,0.875    C509.491,0.438,508.616,0,508.179,0H265.212h-1.74h-1.75c-13.521,0-23.99,10.469-23.99,23.991v67.179H133.916    c-29.667,0-52.783,23.116-52.783,52.783v38.387v47.981h45.803v491.6c0,29.668,22.679,52.346,52.346,52.346h415.703    c29.667,0,52.782-22.678,52.782-52.346v-491.6h45.366v-47.981v-38.387C693.133,114.286,670.008,91.169,640.35,91.169z     M285.713,47.981h202.84v43.188h-202.84V47.981z M599.349,721.922c0,3.061-1.312,4.363-4.364,4.363H179.282    c-3.052,0-4.364-1.303-4.364-4.363V230.32h424.431V721.922z M644.715,182.339H129.551v-38.387c0-3.053,1.312-4.802,4.364-4.802    H640.35c3.053,0,4.365,1.749,4.365,4.802V182.339z" />
                                 <rect x="475.031" y="286.593" width="48.418" height="396.942" />
                                 <rect x="363.361" y="286.593" width="48.418" height="396.942" />
                                 <rect x="251.69" y="286.593" width="48.418" height="396.942" />
                              </g>
                           </g>
                        </svg>
                        {{__('audio::album.remove')}}
                     </a>
                     <a data-ng-if="albumPostData.audioPostData[$index].id" data-toggle="collapse"
                        data-parent="#accordion" href="#collapse_@{{$index}}" aria-expanded="true" class="panel_arrow">
                        <svg x="0px" y="0px" width="10px" height="16px" viewBox="0 0 284.929 284.929">
                           <g>
                              <path
                                 d="M282.082,76.511l-14.274-14.273c-1.902-1.906-4.093-2.856-6.57-2.856c-2.471,0-4.661,0.95-6.563,2.856L142.466,174.441   L30.262,62.241c-1.903-1.906-4.093-2.856-6.567-2.856c-2.475,0-4.665,0.95-6.567,2.856L2.856,76.515C0.95,78.417,0,80.607,0,83.082   c0,2.473,0.953,4.663,2.856,6.565l133.043,133.046c1.902,1.903,4.093,2.854,6.567,2.854s4.661-0.951,6.562-2.854L282.082,89.647   c1.902-1.903,2.847-4.093,2.847-6.565C284.929,80.607,283.984,78.417,282.082,76.511z" />
                           </g>
                        </svg>
                     </a>
                  </div>
               </div>
               <div id="collapse_@{{$index}}" class="panel-collapse collapse form-page" aria-expanded="false" >
                  <div class="panel-body form_container">
                     <form id="" class="not-saved" name="audioEditForm" method="POST" data-base-validator
                        data-ng-submit="{{ $control }}.updateAudio($event, $index)" enctype="multipart/form-data">
                        <div class="division flexbox">
                           <div class="one-set width-50">
                              <div class="form-group" data-ng-class="{'has-error': errors[$index].audio_title.has}">
                                 <label>
                                    {{ __('base::audio.title') }}
                                    <span class="required">*</span>
                                 </label>
                                 <div class="form-input">
                                    <input type="text" name="audio_title" class="form-control"
                                       data-ng-class="{'has-error': errors[$index].audio_title.has}"
                                       placeholder="{{ __('audio::audio.form_placeholders.audio_title_placeholder')}}"
                                       data-ng-model="albumPostData.audioPostData[$index].audio_title">
                                 </div>
                                 <p class="error-msg" data-ng-show="errors[$index].audio_title.has">@{{ errors.audio_title.message }}</p>
                              </div>
                              <div class="form-group" data-ng-class="{'has-error': errors[$index].audio_artists.has}">
                                 <label>
                                    {{ __('audio::audio.artist') }}
                                    <span class="required">*</span>
                                 </label>
                                 <div class="form-input">
                                    <select class="select2_custom_ddl" allowClear="1"    data-jquery="select2_custom_ddl"  name="audio_artists" class="admin_category_sub  form-control" myValue="albumPostData.audioPostData[$index].audio_artists"
                                    myPlaceholder="{{ __('audio::audio.select_artist') }}" 
                                       data-ng-model="albumPostData.audioPostData[$index].audio_artists">
                                       <option value="">{{ __('audio::audio.select_artist') }}</option>
                                       <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}">
                                          @{{audio_artist.artist_name}}</option>
                                    </select>
                                 </div>
                                 <p class="error-msg" data-ng-show="errors[$index].audio_artists.has">@{{ errors.audio_artists.message }}</p>
                              </div>
                              
                              @if($form_type === 'add')
                              <!-- Audio Thumbnail Upload -->
                              <div class="profile_image_upload upload-cover-thumbnail">
                                 <div class="thumbnail-image ml-zero" data-ng-class="{'has-error': errors[$index].thumbnail.has}">
                                    <h4>{{ __('base::audio.thumbnail') }}</h4>
                                    <div class="image-content">
                                          <img ng-src="@{{ albumPostData.audioPostData[$index].thumbnail_image }}"
                                             ng-class="{'active': albumPostData.audioPostData[$index].thumbnail_image}"
                                             class="uploaded_img uploaded_img_@{{$index}}" alt="">
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
                                                      <div ng-hide="albumPostData.audioPostData[$index].thumbnail_image.length">
                                                         <span>{{__('base::audio.select_image')}}</span>
                                                      </div>

                                                      <div ng-hide="!albumPostData.audioPostData[$index].thumbnail_image.length">
                                                         <span>{{__('base::audio.change')}}</span>
                                                      </div>                                                        
                                                      
                                                      <input type="file" accept="image/*" class="uploadImg" data-audio-index="@{{$index}}" name="image" />
                                                      <input type="hidden" class="module" id="module" name="module" value="album_image" />
                                                      <input type="hidden" class="size" id="size" name="size" value="thumb" />
                                                   </div>
                                                </div>
                                             </div>
                                             <p class="intimation">( {{ __('base::audio.image_formats_initmation') }} )
                                             </p>
                                          </div>
                                       </div>
                                    </div>
                                    <p class="error-msg thumbnail-error" data-ng-show="errors[$index].thumbnail.has">@{{errors[$index].thumbnail.message }}</p>
                                 </div> 
                              </div>   
                              @endif
                              
                           </div>
                           <div class="one-set width-50">
                              <div class="form-group">
                                 <label>
                                    {{ __('base::audio.description') }}
                                 </label>
                                 <div class="form-input" data-ng-class="{'has-error': errors[$index].audio_description.has}">                                    
                                       <textarea rows="7" name="audio_description" class="form-control"
                                       data-ng-model="albumPostData.audioPostData[$index].description"
                                       placeholder="{{ __('audio::audio.form_placeholders.audio_description_placeholder')}}"
                                       value=""></textarea>
                                 </div>
                                 <p class="error-msg" data-ng-show="errors[$index].audio_description.has">@{{ errors.audio_description.message }}</p>
                              </div>

                              @if($form_type === 'add')                          
                              <!-- Albums Dropdown -->
                              <div class="form-group" data-ng-class="{'has-error': errors[$index].thumbnail.has}">
                                 <label>
                                    {{ __('audio::audio.album') }}
                                 </label>
                                 <div class="form-input" data-ng-class="{'has-error': errors[$index].audio_album.has}">
                                    <select class="select2_custom_ddl" allowClear="1" data-jquery="select2_custom_ddl" myValue="albumPostData.audioPostData[$index].audio_album"
                                    myPlaceholder="{{ __('audio::audio.select_album') }}" name="audio_album" class="admin_category_sub  form-control"
                                       data-ng-model="albumPostData.audioPostData[$index].audio_album">
                                       <option value="">{{ __('audio::audio.select_album') }}</option>
                                       <option ng-repeat="audio_album in audio_albums" value="@{{audio_album.id}}">
                                          @{{audio_album.album_name}}</option>
                                    </select>
                                 </div>
                                 <p class="error-msg" data-ng-show="errors[$index].audio_album.has">
                                       @{{ errors.audio_album.message }}</p>
                              </div>
                              @endif

                              <div class="form-group" data-ng-class="{'has-error': errors[$index].is_active.has}">
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
                                             <input type="checkbox" name="is_active" ng-model="albumPostData.audioPostData[$index].is_active">
                                             <span class="slider round"></span>
                                          </label>
                                          <span class="text">(Active)</span>
                                       </div>
                                    </div>
                                 </div>                                
                                 <p class="error-msg" data-ng-show="errors[$index].is_active.has">@{{ errors[$index].is_active.message}}</p>
                              </div>
                           </div>                           
                        </div>
                        <div class="form_submit">
                           <input type="hidden" id="audio_id_@{{$index}}" name="audio_id"
                              data-ng-model="albumPostData.audioPostData[$index].id" value="" />
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('audio::admin.common.imageCropper')
@include('audio::admin.common.popup')