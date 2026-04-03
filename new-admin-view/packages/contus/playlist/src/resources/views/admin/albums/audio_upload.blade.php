<!-- Add Audio container -->
<div class="contentpanel upload_video_page add_video_container" id="video_frame">
   <div class="upload_video_container">
       <svg x="0px" y="0px" width="18px" height="18px" viewBox="0 0 612 612" class="upload_cancel" data-ng-click="vgridCtrl.hideUploadOption()">
           <g>
               <g id="cross">
                   <g>
                       <polygon points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397      306,341.411 576.521,611.397 612,575.997 341.459,306.011    "
                           fill="#252629" />
                   </g>
               </g>
           </g>
       </svg>
       <form name="videoForm" enctype="multipart/form-data">
           <div id="file_drop_area">
               <h3 class="upload_pg_title">Upload New Files</h3>
               <div id="video-initial-upload-container">
                   <div style="display: none" id="upload_errors_wrap">
                       <h2 id="upload_error" class="error-msg">{{ __('audio::audio.upload_error') }}</h2>
                       <h2 id="upload_staus_when_error"></h2>
                   </div>
                   <div class="upload_box">
                       <svg viewBox="0 0 46 47" x="0px" y="0px" width="46px" height="47px">
                           <g>
                               <path d="M 0.4999 7.4999 L 45.4999 7.4999 L 45.4999 38.4999 L 36.4999 47.4999 L 0.4999 47.4999 L 0.4999 7.4999 Z"
                                   fill="#e7f7ff" />
                               <path d="M 36.9999 47.4999 L 36.9999 38.4999 L 45.9999 38.4999 L 36.9999 47.4999 Z"
                                   fill="#6dbdcd" />
                               <path d="M 28.9998 5.2749 C 28.9998 2.1249 24.5247 0.4999 22.9999 0.4999 C 21.5625 0.4999 17 2.1249 17 5.2749 L 17 16.2999 C 17 18.6099 18.9636 20.4999 21.3636 20.4999 C 23.7635 20.4999 25.7271 18.6099 25.7271 16.2999 L 25.7271 7.3749 C 25.7271 5.9049 24.5272 4.7499 22.9999 4.7499 C 21.4726 4.7499 20.2726 5.9049 20.2726 7.3749 L 20.2726 15.2499 L 21.909 15.2499 L 21.909 7.3749 C 21.909 6.7449 22.3453 6.3249 22.9999 6.3249 C 23.6544 6.3249 24.0908 6.7449 24.0908 7.3749 L 24.0908 16.2999 C 24.0908 17.77 22.8908 18.9249 21.3636 18.9249 C 19.8363 18.9249 18.6364 17.77 18.6364 16.2999 L 18.6364 5.2749 C 18.6364 2.965 22.3479 2.075 22.9999 2.075 C 23.652 2.075 27.3635 2.965 27.3635 5.2749 L 27.3635 7.2499 L 28.9998 7.2499 L 28.9998 5.2749 Z"
                                   fill="#3f69a1" />
                           </g>
                       </svg>
                       <h4 id="upload_title" class="drap_drop_title">Just drag and drop album file</h4>
                   </div>
                   <span class="or">{{ __('video::videos.or') }}</span>
                   <div class="upload_file_input">
                     <input type="file" class="filestyle" id="audio" name="audio" data-buttonName="btn-primary"
                     multiple>
                     <span>{{ __('audio::audio.browse_from_computer') }}</span>
                   </div>
                   <span class="tip_to_upload">Tip</span>
                   <p class="video-accepted-formats" id="video-accepted-formats-text">{{ __('base::audio.audio_formats_initmation') }}</p>
                   <p style="display:none" id="video_error" class="error-msg">{{ __('audio::audio.select_valid_file') }}</p>
                   <div style="display:none" id="video_upload_button_wrap" class="video_upload_div_btn" style="display: block;">
                     <button class="btn btn-primary" type="button"
                        title="{{ __('audio::audio.upload_btn_txt') }}">{{ __('audio::audio.upload_btn_txt') }}</button>
                  </div>                   
               </div>
               <div style="display:none" class="progress-container" id="progress-bar-wrap">
                  <span>Uploading...</span>
                  <div class="progress progress-striped active">
                     <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%">
                        <p id="upload_percentage"></p>
                     </div>
                  </div>
               </div>
           </div>
           <div style="display:none" data-ng-show="{{ $control }}.numberOfActivePresets > 0 && false" style=" text-align: center; padding-bottom: 20px">
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
</div>

<div class="audio-upload-wrapper">
   <div class="audio-upload-lists audio_item_@{{$index}}" id="audio_itemname_@{{item.file_slug}}" data-ng-repeat = "item in audioUploadLists">
      <div class="audio_accordion_wrapper upload_form_container">
         <div class="panel-group accordion_wrapper_@{{$index}} + " id="accordion">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <div class="panel-heading-left">
                     <h4 class="panel-title">
                        <a data-ng-show="{{ $control }}.albumPostData.audioPostData[$index].id" data-toggle="collapse" class="" data-parent="#accordion" href="#collapse_@{{$index}}" aria-expanded="false"> @{{item.file_name}} </a>
                        <a data-ng-show="!{{ $control }}.albumPostData.audioPostData[$index].id" data-toggle="collapse" class="" data-parent="#accordion"  aria-expanded="false"> @{{item.file_name}} </a>
                     </h4>
                     <span class="fileSize">@{{item.file_size}}</span>
                     <div class="file-progress-wrapper">
                        <div class="progress progress-striped active">
                              <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%">
                                 <p id="upload_percentage">0% Uploaded</p>
                              </div>
                        </div>
                        <div class="file-complete-status"></div>
                        <div class="audio-status-status"></div>
                     </div>
                  </div>

                  <div class="panel-heading-right">
                     <a data-ng-if="{{ $control }}.albumPostData.audioPostData[$index].id" class="accordion_btn accordion_btn_edit" data-toggle="collapse" data-parent="#accordion" href="#collapse_@{{$index}}" >{{__('base::general.edit')}}</a>

                     <a data-ng-if="{{ $control }}.albumPostData.audioPostData[$index].id" title="{{__('audio::album.remove')}}" href="javascript:void(0)" class="accordion_btn accordion_btn_delete" data-toggle="modal" data-target="#singleDeleteModal" data-ng-click="{{ $control }}.deleteSingleRecord({{ $control }}.albumPostData.audioPostData[$index].id, $index, 'add')" data-boot-tooltip="true" data-original-title="{{__('audio::album.remove')}}">{{__('audio::album.remove')}}</a>

                     <a data-ng-if="!{{ $control }}.albumPostData.audioPostData[$index].id" title="{{__('audio::album.remove')}}" href="javascript:void(0)" class="accordion_btn accordion_btn_delete" data-toggle="modal" data-target="#singleDeleteModal" data-ng-click="{{ $control }}.deleteSingleRecord('', $index, 'add')" data-boot-tooltip="true" data-original-title="{{__('audio::album.remove')}}">{{__('audio::album.remove')}}</a>

                     <a data-ng-if="{{ $control }}.albumPostData.audioPostData[$index].id" data-toggle="collapse" data-parent="#accordion" href="#collapse_@{{$index}}"  aria-expanded="true" class="panel_arrow">
                           <i class="angle_ic" aria-hidden="true"></i>
                     </a>
                  </div>
               </div>
               <div id="collapse_@{{$index}}" class="panel-collapse collapse" aria-expanded="false">
                  <div class="panel-body form_container col-sm-12 col-md-12 col-lg-12">
                     <form id="" class="not-saved" name="audioEditForm" method="POST" data-base-validator data-ng-submit="{{ $control }}.updateAudio($event, $index)" enctype="multipart/form-data">
                        <div class="row mb-10">
                           <!-- Title -->
                           <div class="col-md-4 col-lg-4 col-sm-12">
                              <div class="form-group row">
                                 <div class="col-md-12 col-sm-12">
                                    <label class="control-label">
                                    {{ __('base::audio.title') }}
                                    <span class="asterisk">*</span>
                                    </label>
                                 </div>
                                 <div class="col-md-12 col-sm-12" data-ng-class="{'has-error': errors[$index].audio_title.has}">
                                    <input type="text" name="audio_title" class="form-control" data-ng-class="{'has-error': errors[$index].audio_title.has}"
                                          placeholder="{{ __('audio::audio.form_placeholders.audio_title_placeholder')}}"
                                          data-ng-model="{{ $control }}.albumPostData.audioPostData[$index].audio_title">
                                    <p class="help-block" data-ng-show="errors[$index].audio_title.has">@{{ errors.audio_title.message }}</p>
                                 </div>
                              </div>
                           </div>
                           <!-- Description -->
                           <div class="col-md-4 col-lg-4 col-sm-12">
                              <div class="form-group row">
                                 <div class="col-md-12 col-sm-12">
                                    <label class="control-label">
                                       {{ __('base::audio.description') }}
                                    </label>
                                 </div>
                                 <div class="col-md-12 col-sm-12" data-ng-class="{'has-error': errors[$index].audio_description.has}">
                                    <textarea rows="7" name="audio_description" class="form-control" data-ng-model="{{ $control }}.albumPostData.audioPostData[$index].description" placeholder="{{ __('audio::audio.form_placeholders.audio_description_placeholder')}}" value=""></textarea>
                                    <p class="help-block" data-ng-show="errors[$index].audio_description.has">@{{ errors.audio_description.message }}</p>
                                 </div>
                              </div>
                           </div>
                           <!-- Artists Dropdown -->
                           <div class="col-md-4 col-lg-4 col-sm-12">
                              <div class="form-group row">
                                 <div class="col-md-12 col-sm-12">
                                    <label class="control-label">
                                    {{ __('base::audio.artist') }}
                                    <span class="asterisk">*</span>
                                    </label>
                                 </div>
                                 <div class="col-md-12 col-sm-12" data-ng-class="{'has-error': errors[$index].audio_artists.has}">
                                    <select name="audio_artists" class="admin_category_sub  form-control"  data-ng-model="{{ $control }}.albumPostData.audioPostData[$index].audio_artists">
                                       <option value="">{{ __('base::audio.select_artists') }}</option>
                                       <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}"> 
                                          @{{audio_artist.artist_name}}</option>
                                    </select>  
                                    <p class="help-block" data-ng-show="errors[$index].audio_artists.has">@{{ errors.audio_artists.message }}</p>
                                 </div>
                              </div>
                           </div>
                           <!-- Status -->
                           <div class="col-md-4 col-lg-4 col-sm-12">
                              <div class="form-group row" data-ng-class="{'has-error': errors[$index].is_active.has}">
                                 <div class="col-md-12 col-sm-12">
                                    <label class="control-label">{{ __('base::audio.status') }}</label>
                                 </div>
                                 <div class="col-md-12 col-sm-12">
                                    <label class="switch">
                                          <input type="checkbox" name="is_active" ng-model="{{ $control }}.albumPostData.audioPostData[$index].is_active">
                                          <span class="slider round"></span>
                                    </label>
                                    <p class="help-block" data-ng-show="errors[$index].is_active.has">@{{ errors[$index].is_active.message}}</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form_submit">
                           <input type="hidden" id="audio_id_@{{$index}}" name="audio_id" data-ng-model="{{ $control }}.albumPostData.audioPostData[$index].id" value="" />  
                        </div>
                     </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>