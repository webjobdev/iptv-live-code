<div class="sidepanel">
   <div class="overlay"></div>
   <div class="pop_over_continer form-page">
      <form name="artistsForm" method="POST" data-base-validator data-ng-submit="artgridCtrl.artistSave($event, artgridCtrl.artist.id)" enctype="multipart/form-data">
         {!! csrf_field() !!}
         <div class="sidepanel-header flexbox align-items-center">
            <h5 data-ng-if="!artgridCtrl.artist.id">{{trans('audio::artists.add_new_artist')}} </h5>
            <h5 data-ng-if="artgridCtrl.artist.id">{{trans('audio::artists.edit_artist')}} </h5>
         </div>
         <div class="sidepanel-scroll">
            @include('base::partials.errors')
            <div class="form-group" data-ng-class="{'has-error': errors.artist_name.has}">
               <label>{{trans('audio::artists.name')}} <span class="required">*</span></label>
               <div class="form-input">
                  <input type="text" name="artist_name" maxlength="255" class="form-control" data-unique="@{{artgridCtrl.categoriesUniqueRoute}}" data-ng-model="artgridCtrl.artist.artist_name" placeholder="{{trans('audio::artists.name')}}" value="{{old('title')}}" />
               </div>
               <p class="error-msg" data-ng-show="errors.artist_name.has">@{{ errors.artist_name.message }}</p>
            </div>
            <div class="form-group">
               <label>{{trans('audio::artists.biography')}} </label>
               <div class="form-input">
                  <textarea name="artist_biography" class="form-control" data-ng-model="artgridCtrl.artist.artist_biography" placeholder="{{trans('audio::artists.biography')}}" value="{{old('artist_biography')}}" > </textarea>
               </div>
            </div>
            @include('audio::admin.common.commonFormFields',['field' =>  'status', 'ngmodel' => 'artgridCtrl.artist.is_active'])
            <div class="form-group" data-ng-class="{'has-error': errors.artist_image.has}">
               <label>{{ trans('audio::artists.artist_image') }} </label>
               <div class="fileupload fileupload-new" data-provides="fileupload">
                  <div class="input-append">
                     <button class="subtitle_btn">
                        <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                           <g>
                              <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                           </g>
                        </svg>
                        <span class="fileupload-new">{{trans('audio::artists.select_image')}}</span> 
                        <span class="fileupload-exists">{{trans('audio::artists.change')}}</span>
                        <input type="file" id ="artist-image" accept="image/*" name="image" data-action="api/admin/artists/artist-image" />
                     </button>
                     <span class="fileupload-preview"></span>
                  </div>
                  <a href="#" class="fileupload-exists category-image-remove" data-dismiss="fileupload" data-ng-click="artgridCtrl.removeThumbnailProperty()">{{trans('audio::artists.remove')}}</a>
                  <p class="error-msg hide"></p>
                  <p class="description">( {{ __('base::audio.image_formats_initmation') }} )</p>
               </div>
               <p class="error-msg" data-ng-show="errors.artist_image.has">@{{ errors.artist_image.message }}</p>
               <div class="form-group">
                <div class="clsFileUpload preview-image">
                    <span id="artist-image-delete" class="delete-image" data-ng-click="adsgridCtrl.deleteArtistImage()" data-ng-show="adsgridCtrl.ads.ad_thumbnail" data-boot-tooltip="true" title="{{trans('audio::audioAds.delete_image')}}"></span>
                    <img id="artist-image-preview" class="preview-image" data-ng-show="adsgridCtrl.ads.ad_thumbnail" data-ng-src="@{{adsgridCtrl.ads.ad_thumbnail}}" width="180px" height="180px">
                    <div id="artist-image-progress" class="hide clsProgressbar"></div>
                    <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                </div>
            </div>
            </div>
         </div>
            @include('audio::admin.common.commonFormFields',['field' =>  'side-panel-form-btns'])
      </form>
   </div>
</div>