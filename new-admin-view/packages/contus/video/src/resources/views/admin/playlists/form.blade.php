<style>
.pop_over_continer{
   display: flex;
   justify-content: space-evenly;
}
.sidepanel .pop_over_continer{
   max-width: 700px;
}
.wordwrap{
   overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 200px;
}
.video-order .form-group{
   margin-bottom: 0px;
}
.error-msg{
   color:red;
}
.video_order{
   display: flex;
   position: relative;
}
.video_order .errormsg{
   top: 17px;
   right: -8px;
   font-size: 10px;
   color: red;
   position: absolute;
}
.no-record{
   position: absolute !important;
   right: 16% !important;
}

.video-order.playlist-scroll .sidepanel-scroll{
overflow-y: auto;
overflow-x: hidden;
width: 100%;
}
.video-order.playlist-scroll table .form-input.video_order.errormsg{
font-size: 10px;
}

.video-order.playlist-scroll table{
table-layout: fixed;
}

.video-order.playlist-scroll table .form-input.video_order input{
width: 100%;
}
</style>
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
      <div style="width:45%">
         <form name="audio-playlist-form" id="seasonForm" method="POST" data-base-validator enctype="multipart/form-data">
         <!-- data-ng-submit="playlistCtrl.playlistSave($event, playlistCtrl.playlist.id)" -->
            {!! csrf_field() !!}



            <div class="sidepanel-header flexbox align-items-center">
               <h5 data-ng-if="!playlistCtrl.playlist.id">Add Video</h5>
               <h5 data-ng-if="playlistCtrl.playlist.id">Edit Video</h5>
               <!-- Translate select start -->
               <div class="right-side"  data-ng-if="playlistCtrl.playlist.id">
                     <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="playlistCtrl.languageChange()" myValue="playlistCtrl.seasonTranslation.language" data-ng-model="playlistCtrl.seasonTranslation.language"  data-ng-options="a.id as a.title  for a in playlistCtrl.languages " ></select>
                </div>
               <!-- translate select end -->

            </div>
            <div class="sidepanel-scroll">
               @include('base::partials.errors')
               <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                  <label>Category Name <span class="required">*</span></label>
                  <div class="form-input">
                     <input type="text" name="name" maxlength="255" class="form-control" data-ng-model="playlistCtrl.playlist.name" placeholder="Category Name" value="{{old('name')}}" />
                  </div>
                  <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
               </div>               
               <div class="form-group" data-ng-class="{'has-error': errors.order.has}">
                  <label>Category Order </label>
                  <div class="form-input">
                     <input type="text" name="order" maxlength="255" class="form-control" data-ng-model="playlistCtrl.playlist.order" placeholder="Category Order" value="{{old('order')}}" />
                  </div>
                  <p class="error-msg" data-ng-show="errors.order.has">@{{ errors.order.message }}</p>
               </div>
               <!--previous code commented-->
               <div class="form-group" data-ng-class="{'has-error': errors.video.has}">
                  <label>
                    Video<span class="required">*</span>
                  </label>
                  <div class="form-input">
                     <input name="video" data-ng-change="playlistCtrl.updateVideoList()" type="text" class="list-repeat" selectize="singleConfig" ng-model="playlistCtrl.playlist.video"
                        options="singlePreload" />
                  </div>
                  <p class="error-msg" data-ng-show="errors.video.has">@{{ errors.video.message }}</p>
               </div>
               <!-- @include('video::admin.common.commonFormFields',['field' =>  'status', 'ngmodel' => 'playlistCtrl.playlist.is_active']) -->
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
                                    <span>{{ __('video::videos.status') }}</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="is_active" data-ng-model="playlistCtrl.playlist.is_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

               <div style="display:none" class="form-group" data-ng-class="{'has-error': errors.playlist_image.has}">
                  <label>{{ trans('video::general.image') }} </label>
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                     <div class="input-append">
                        <button class="subtitle_btn">
                           <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                              <g>
                                 <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                              </g>
                           </svg>
                           <span class="fileupload-new">{{trans('video::general.select_image')}}</span> 
                           <span class="fileupload-exists">{{trans('video::general.change')}}</span>
                           <input type="file" id ="playlist-image" accept="image/*" name="image" data-action="{{env('DATA_ACTION_URL')}}api/admin/playlists/playlist-image/audio_playlist_image" />
                        </button>
                        <span class="fileupload-preview"></span>
                     </div>
                     <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload" data-ng-click="playlistCtrl.removeThumbnailProperty()">{{trans('video::general.remove')}}</a>
                     <p class="error-msg hide"></p>
                     <p class="description">( {{ __('video::general.image_formats_initmation') }}. Image resolution should be 438*243 )</p>
                  </div>
                  <p class="error-msg" data-ng-show="errors.playlist_image.has">@{{ errors.playlist_image.message }}</p>
                  <p class="error-msg image-error"></p>
                  <div class="form-group">
                     <div class="clsFileUpload preview-image">
                        <span id="playlist-image-delete" class="delete-image" data-ng-click="playlistCtrl.deleteArtistImage()" data-ng-show="playlistCtrl.playlist_thumbnail" data-boot-tooltip="true" title="{{trans('video::general.delete_image')}}"></span>
                        <div id="playlist-image-progress" class="hide clsProgressbar"></div>
                        <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                     </div>
                  </div>
               </div>
            </div>
            <div class="bottom-button text-right flexbox align-items-center">
               <span class="save" data-ng-click="closesidePanelForm()">{{ trans('base::general.cancel') }}</span>
               <button data-ng-click="playlistCtrl.playlistSave($event, playlistCtrl.playlist.id)" class="publish-now">{{trans('base::general.submit')}}</button>
            </div>
           
         </form>

         <form name="groupForm" style="display:none;" id="seasonTranslationForm" method="POST" data-base-validator data-ng-submit="playlistCtrl.seasonTranslationSave($event, playlistCtrl.playlist.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">

                <h5 data-ng-if="playlistCtrl.playlist.id">Edit Video</h5>
                <div class="right-side"  data-ng-if="playlistCtrl.playlist.id">
                    <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="playlistCtrl.languageChange()" myValue="playlistCtrl.seasonTranslation.language" data-ng-model="playlistCtrl.seasonTranslation.language"  data-ng-options="a.id as a.title  for a in playlistCtrl.languages " ></select>
                </div>
            </div>
            <div class="sidepanel-scroll">
                @include('base::partials.errors')
                <div class="form-group">
                    <label>
                    Category Name
                        <span class="required">*</span>
                    </label>
                    <div class='form-input'>
                        <input type="text" name="title" class="form-control" data-validation-name="Season Name" data-ng-model="playlistCtrl.playlist.name" placeholder="{{trans('video::season.season_name')}}" disabled value="{{old('name')}}" />
                    </div>
                    <!-- <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p> -->
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.trans_name.has}">
                    <label>
                    Category Name
                        <!-- <span class="required">*</span> -->
                    </label>
                    <div class="form-input">
                        <input type="text" name="trans_name" class="form-control" data-validation-name="Category Name" data-ng-model="playlistCtrl.seasonTranslation.name" placeholder="Category Name" value="{{old('name')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.trans_name.has">@{{ errors.trans_name.message }}</p>
                </div>

            </div>
            <div class="bottom-button text-right flexbox align-items-center">
                <button class="save" data-ng-click="playlistCtrl.closesidePanelForm()">
                    {{ trans('base::general.cancel') }}
                </button>
                <button class="publish-now submitbutton">
                    {{trans('base::general.submit')}}
                </button>
            </div>
        </form>

      </div>
      <!-- video order list -->
      <div style="width:45%" class="video-order playlist-scroll" >
         <div  id="hidevideo_order">
            <div class="sidepanel-header flexbox align-items-center">
               <h5 class="ng-scope">Video Order</h5>
            </div>
            <div class="sidepanel-scroll">
               <table class="table" id="fixTable" data-ng-class="{'no-records': noRecords}">
                  <thead>
                     <tr>
                        <th>
                           Video Title
                        </th>
                        <th>
                           Video Order
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr data-ng-if="playlistCtrl.playlist.video_order" data-ng-repeat="record in playlistCtrl.playlist.video_order track by $index" class="list-repeat" data-intialize-sidebar="">
                        <td class="wordwrap">@{{record.title}}</td>
                        <td>
                           <div class="form-group" data-ng-class="{'has-error': errors.order.has}">
                              <div class="form-input video_order">
                                 <input data-ng-blur="playlistCtrl.videoPrderValidation()" class="video-order-val" data-video-id="@{{playlistCtrl.playlist.video_order[$index]['id']}}" data-ng-model="playlistCtrl.playlist.video_order[$index]['video_order']" type="number" name="video_order" max="100000000" min="0" class="form-control" placeholder="Video order" />
                                 <div class="errormsg"></div>
                              </div>
                           </div>   
                        </td>
                     </tr>
                     
                  </tbody>
               </table>
            </div>
         </div>
    </div>
 </div>