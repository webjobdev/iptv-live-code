<div class="sidepanel">
   <div class="overlay"></div>
   <div class="pop_over_continer form-page">
      <form name="audio-playlist-form" method="POST" data-base-validator data-ng-submit="playlistgridCtrl.playlistSave($event, playlistgridCtrl.playlist.id)" enctype="multipart/form-data">
         {!! csrf_field() !!}
         <div class="sidepanel-header flexbox align-items-center">
            <h5 data-ng-if="!playlistgridCtrl.playlist.id">{{trans('audio::playlists.add_new_playlist')}} </h5>
            <h5 data-ng-if="playlistgridCtrl.playlist.id">{{trans('audio::playlists.edit_playlist')}} </h5>
         </div>
         <div class="sidepanel-scroll">
            @include('base::partials.errors')
            <div class="form-group" data-ng-class="{'has-error': errors.playlist_name.has}">
               <label>{{trans('audio::general.name')}} <span class="required">*</span></label>
               <div class="form-input">
                  <input type="text" name="playlist_name" maxlength="255" class="form-control" data-ng-model="playlistgridCtrl.playlist.playlist_name" placeholder="{{trans('audio::general.name')}}" value="{{old('name')}}" />
               </div>
               <p class="error-msg" data-ng-show="errors.playlist_name.has">@{{ errors.playlist_name.message }}</p>
            </div>
            <div class="form-group" data-ng-class="{'has-error': errors.playlist_audios.has}">
               <label>
                  {{trans('audio::playlists.audio')}}
               </label>
               <div class="form-input">
                  <input type="text" class="list-repeat" selectize="singleConfig" ng-model="playlistgridCtrl.playlist.playlist_audios"
                        options="singlePreload" />
               </div>
               <p class="error-msg" data-ng-show="errors.playlist_audios.has">@{{ errors.playlist_audios.message }}</p>
            </div>
            @include('audio::admin.common.commonFormFields',['field' =>  'status', 'ngmodel' => 'playlistgridCtrl.playlist.is_active'])
            <div class="form-group" data-ng-class="{'has-error': errors.playlist_image.has}">
               <label>{{ trans('audio::general.image') }} </label>
               <div class="fileupload fileupload-new" data-provides="fileupload">
                  <div class="input-append">
                     <button class="subtitle_btn">
                        <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                           <g>
                              <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                           </g>
                        </svg>
                        <span class="fileupload-new">{{trans('audio::general.select_image')}}</span> 
                        <span class="fileupload-exists">{{trans('audio::general.change')}}</span>
                        <input type="file" id ="playlist-image" accept="image/*" name="image" data-action="api/admin/playlists/playlist-image/audio_playlist_image" />
                     </button>
                     <span class="fileupload-preview"></span>
                  </div>
                  <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload" data-ng-click="playlistgridCtrl.removeThumbnailProperty()">{{trans('audio::general.remove')}}</a>
                  <p class="error-msg hide"></p>
                  <p class="description">( {{ __('audio::general.image_formats_initmation') }} )</p>
               </div>
               <p class="error-msg" data-ng-show="errors.playlist_image.has">@{{ errors.playlist_image.message }}</p>
               <div class="form-group">
                  <div class="clsFileUpload preview-image">
                     <span id="playlist-image-delete" class="delete-image" data-ng-click="playlistgridCtrl.deleteArtistImage()" data-ng-show="playlistgridCtrl.ads.ad_thumbnail" data-boot-tooltip="true" title="{{trans('audio::general.delete_image')}}"></span>
                     <img id="playlist-image-preview" class="preview-image" data-ng-show="playlistgridCtrl.ads.ad_thumbnail" data-ng-src="@{{playlistgridCtrl.ads.ad_thumbnail}}" width="180px" height="180px">
                     <div id="playlist-image-progress" class="hide clsProgressbar"></div>
                     <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                  </div>
               </div>
            </div>
         </div>
         @include('audio::admin.common.commonFormFields',['field' =>  'side-panel-form-btns'])
      </form>
   </div>
</div>