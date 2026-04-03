<div class="contentpanel clearfix add_video_container" id="video_frame" style="display: block;">
  
</div>
<div class="audio-upload-wrapper edit_album">
   <div class="audio-upload-lists audio_item_@{{$index}}" id="audio_itemname_@{{item.file_slug}}" data-ng-repeat = "item in albumPostData.audioEditPostData">
      <div class="audio_accordion_wrapper upload_form_container">
         <div class="panel-group accordion_wrapper_@{{$index}} + " id="accordion">
         </form>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <div class="panel-heading-left">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" class="" data-parent="#accordion" href="#collapse_edit@{{$index}}" aria-expanded="false"> @{{item.audio_title}}  </a>
                     </h4>
                     <span class="fileSize">@{{item.file_size}}</span>
                     <div class="file-progress-wrapper">
                        <div class="file-complete-status"></div>
                        <div class="audio-status-status"></div>
                     </div>
                  </div>
                  <div class="panel-heading-right">
                     <a class="accordion_btn accordion_btn_edit" data-toggle="collapse" data-parent="#accordion" href="#collapse_edit@{{$index}}" >
                        <svg x="0px" y="0px" width="13px" height="13px" viewBox="0 0 469.331 469.331">
                            <g>
                                <path
                                    d="M438.931,30.403c-40.4-40.5-106.1-40.5-146.5,0l-268.6,268.5c-2.1,2.1-3.4,4.8-3.8,7.7l-19.9,147.4   c-0.6,4.2,0.9,8.4,3.8,11.3c2.5,2.5,6,4,9.5,4c0.6,0,1.2,0,1.8-0.1l88.8-12c7.4-1,12.6-7.8,11.6-15.2c-1-7.4-7.8-12.6-15.2-11.6   l-71.2,9.6l13.9-102.8l108.2,108.2c2.5,2.5,6,4,9.5,4s7-1.4,9.5-4l268.6-268.5c19.6-19.6,30.4-45.6,30.4-73.3   S458.531,49.903,438.931,30.403z M297.631,63.403l45.1,45.1l-245.1,245.1l-45.1-45.1L297.631,63.403z M160.931,416.803l-44.1-44.1   l245.1-245.1l44.1,44.1L160.931,416.803z M424.831,152.403l-107.9-107.9c13.7-11.3,30.8-17.5,48.8-17.5c20.5,0,39.7,8,54.2,22.4   s22.4,33.7,22.4,54.2C442.331,121.703,436.131,138.703,424.831,152.403z">
                                </path>
                            </g>
                        </svg>
                        {{__('base::general.edit')}}
                    </a>
                     <a title="{{__('audio::album.remove')}}" href="javascript:void(0)" class="accordion_btn accordion_btn_delete" data-toggle="modal" data-target="#singleDeleteModal" data-ng-click="{{ $control }}.deleteSingleRecord(item.id, $index, 'edit')" data-boot-tooltip="true" data-original-title="{{__('audio::album.remove')}}">
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
                     <a data-toggle="collapse" data-parent="#accordion" href="#collapse_edit@{{$index}}"  aria-expanded="true" class="panel_arrow">
                        <svg x="0px" y="0px" width="10px" height="16px" viewBox="0 0 284.929 284.929">
                            <g>
                                <path d="M282.082,76.511l-14.274-14.273c-1.902-1.906-4.093-2.856-6.57-2.856c-2.471,0-4.661,0.95-6.563,2.856L142.466,174.441   L30.262,62.241c-1.903-1.906-4.093-2.856-6.567-2.856c-2.475,0-4.665,0.95-6.567,2.856L2.856,76.515C0.95,78.417,0,80.607,0,83.082   c0,2.473,0.953,4.663,2.856,6.565l133.043,133.046c1.902,1.903,4.093,2.854,6.567,2.854s4.661-0.951,6.562-2.854L282.082,89.647   c1.902-1.903,2.847-4.093,2.847-6.565C284.929,80.607,283.984,78.417,282.082,76.511z" />
                            </g>
                        </svg>
                     </a>
                  </div>
               </div>
               <div id="collapse_edit@{{$index}}" class="panel-collapse collapse form-page" aria-expanded="false">
                    <div class="panel-body form_container">
                        <form id="" class="not-saved" name="audioEditForm" method="POST" data-base-validator data-ng-submit="{{ $control }}.updateAudio($event, $index)" enctype="multipart/form-data">
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
                                                data-ng-model="albumPostData.audioEditPostData[$index].audio_title">
                                        </div>
                                        <p class="error-msg" data-ng-show="errors[$index].audio_title.has">@{{ errors.audio_title.message }}</p>
                                    </div>
                                    <div class="form-group" data-ng-class="{'has-error': errors[$index].audio_artists.has}">
                                        <label>
                                            {{ __('base::audio.artist') }}
                                            <span class="required">*</span>
                                        </label>
                                        <div class="form-input">
                                            <select class="select2_custom_ddl" data-jquery="select2_custom_ddl" myValue="albumPostData.audioEditPostData[$index].audio_artists"
                                    myPlaceholder="{{ __('base::audio.select_artists') }}" name="audio_artists" class="admin_category_sub form-control"  data-ng-model="albumPostData.audioEditPostData[$index].audio_artists">
                                                <option value="">{{ __('base::audio.select_artists') }}</option>
                                                <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}"> 
                                                    @{{audio_artist.artist_name}}</option>
                                            </select>
                                        </div>
                                        <p class="error-msg" data-ng-show="errors[$index].audio_artists.has">@{{ errors[$index].audio_artists.message }}</p>
                                    </div>
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
                                                        <input type="checkbox" name="is_active" ng-model="albumPostData.audioEditPostData[$index].is_active">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span class="text">(Active)</span>
                                                </div>
                                            </div>
                                        </div>                                
                                        <p class="error-msg" data-ng-show="errors[$index].is_active.has">@{{ errors[$index].is_active.message}}</p>
                                    </div>
                                </div>
                                <div class="one-set width-50">
                                    <div class="form-group">
                                        <label>
                                            {{ __('base::audio.description') }}
                                        </label>
                                        <div class="form-input" data-ng-class="{'has-error': errors[$index].audio_description.has}">                                    
                                            <textarea rows="7" name="audio_description" class="form-control" data-ng-model="albumPostData.audioEditPostData[$index].description" placeholder="{{ __('audio::audio.form_placeholders.audio_description_placeholder')}}" value=""></textarea>
                                        </div>
                                        <p class="error-msg" data-ng-show="errors[$index].audio_description.has">@{{ errors.audio_description.message }}</p>
                                    </div>                            
                                </div>
                            </div>
                            <div class="form_submit">
                                <input type="hidden" id="audio_id_@{{$index}}" name="audio_id" data-ng-model="albumPostData.audioEditPostData[$index].id" value="" />  
                            </div>
                        </form>
                    </div>
                  <div class="panel-body form_container col-sm-12 col-md-12 col-lg-12" style="display: none;">
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
                                          data-ng-model="albumPostData.audioEditPostData[$index].audio_title">
                                    <p class="help-block" data-ng-show="errors[$index].audio_title.has">@{{ errors[$index].audio_title.message }}</p>
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
                                    <textarea rows="7" name="audio_description" class="form-control" data-ng-model="albumPostData.audioEditPostData[$index].description" placeholder="{{ __('audio::audio.form_placeholders.audio_description_placeholder')}}" value=""></textarea>
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
                                    <select name="audio_artists" class="admin_category_sub  form-control"  data-ng-model="albumPostData.audioEditPostData[$index].audio_artists">
                                       <option value="">{{ __('base::audio.select_artists') }}</option>
                                       <option ng-repeat="audio_artist in audio_artists" value="@{{audio_artist.id}}"> 
                                          @{{audio_artist.artist_name}}</option>
                                    </select>  
                                    <p class="help-block" data-ng-show="errors[$index].audio_artists.has">@{{ errors[$index].audio_artists.message }}</p>
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
                                          <input type="checkbox" name="is_active" ng-model="albumPostData.audioEditPostData[$index].is_active">
                                          <span class="slider round"></span>
                                    </label>
                                    <p class="help-block" data-ng-show="errors[$index].is_active.has">@{{ errors[$index].is_active.message}}</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form_submit">
                           <input type="hidden" id="audio_id_@{{$index}}" name="audio_id" data-ng-model="albumPostData.audioEditPostData[$index].id" value="" />  
                        </div>
                     </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>