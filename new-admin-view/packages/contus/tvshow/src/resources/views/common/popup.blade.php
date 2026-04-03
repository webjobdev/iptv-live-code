<div class="alert-popup modal fade" id="videoDeleteModal" ng-show="videoConfirmationDeleteBox">             
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>{{__('base::gridlist.delete_record')}}</h3>
        </div>
        <div class="popup_content"> 
            <svg x="0px" y="0px" viewBox="0 0 512 512" width="26px" height="26px">
                <g>
                    <g>
                        <g>
                            <polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804" fill="#7f7f7f"/>
                            <rect x="235.948" y="175.791" width="40.104" height="237.285" fill="#7f7f7f"/>
                            <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804" fill="#7f7f7f"/>
                            <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474     L380.665,471.896z" fill="#7f7f7f"/>
                        </g>
                    </g>
                </g>
                <g>
                    <g>
                        <path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z" fill="#7f7f7f"/>
                    </g>
                </g>
            </svg>                           
            <span class="conformation_txt">{{__('base::gridlist.delete_confirm')}}</span>
            <!-- data-ng-show="videoConfirmationDeleteBox" -->
            <span class="delete_detail">@{{ vodSelectedVideo.title }}</span>
            <div class="popup_btns text-center">
                <a data-ng-click="{{ $control }}.cancelDeleteVideos()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" >{{__('base::gridlist.no')}}</a>
                <a data-ng-click="{{ $control }}.confirmDeleteVideos('single-video')" href="javascript:void(0)" class="pop_confirm_btn" >{{__('base::gridlist.yes')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="alert-popup modal fade" id="removeTrailerModel" ng-show="removeTrailerConfirmation">             
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>Confirmation</h3>
        </div>
        <div class="popup_content"> 
            <svg x="0px" y="0px" viewBox="0 0 512 512" width="26px" height="26px">
                <g>
                    <g>
                        <g>
                            <polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804" fill="#7f7f7f"/>
                            <rect x="235.948" y="175.791" width="40.104" height="237.285" fill="#7f7f7f"/>
                            <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804" fill="#7f7f7f"/>
                            <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474     L380.665,471.896z" fill="#7f7f7f"/>
                        </g>
                    </g>
                </g>
                <g>
                    <g>
                        <path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z" fill="#7f7f7f"/>
                    </g>
                </g>
            </svg>                           
            <span class="conformation_txt">Are you sure you want to remove the Trailer for the video ?</span>
            <!-- data-ng-show="videoConfirmationDeleteBox" -->
            {{-- <span class="delete_detail">@{{ vodSelectedVideo.title }}</span> --}}
            <div class="popup_btns text-center">
                <a data-ng-click="{{ $control }}.cancelRemoveTrailer()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" >{{__('base::gridlist.no')}}</a>
                <a data-ng-click="{{ $control }}.confirmRemoveTrailer('remove-trailer')" href="javascript:void(0)" class="pop_confirm_btn" >{{__('base::gridlist.yes')}}</a>
            </div>
        </div>
    </div>
</div>


<div class="alert-popup modal fade" id="videoReplaceModal" ng-show="videoConfirmationReplaceBox">             
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>{{__('base::gridlist.cancel_video_upload')}}</h3>
        </div>
        <div class="popup_content"> 
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 442.035 442.035" style="enable-background:new 0 0 442.035 442.035;" xml:space="preserve" width="28px" height="28px">
            <g>
                <path fill="#7f7f7f" d="M248.227,399.201c-9.616,0-17.483,7.867-17.483,17.483s7.867,17.483,17.483,17.483H408.49
                    c9.616,0,17.483-7.867,17.483-17.483V138.409c0-4.954-2.04-9.616-5.536-12.821L289.313,4.662C286.108,1.748,281.737,0,277.366,0
                    H82.136C72.52,0,64.653,7.867,64.653,17.483v189.402c0,9.616,7.867,17.483,17.483,17.483s17.483-7.867,17.483-17.483V34.967
                    h139.866v151.522c0,9.616,7.867,17.483,17.483,17.483h131.125c0.874,0,2.04,0,2.914-0.291v195.521H248.227z M388.093,169.005
                    H274.452V38.463l116.555,107.522v23.311C390.133,169.005,388.967,169.005,388.093,169.005z M21.236,412.022l41.086-41.086
                    l-39.046-39.046c-6.702-6.702-6.702-17.775,0-24.768c6.702-6.702,17.775-6.702,24.768,0l39.046,39.046l37.006-37.006
                    c6.702-6.702,17.775-6.702,24.768,0c6.702,6.702,6.702,17.775,0,24.768l-37.006,37.006l39.046,39.046
                    c6.702,6.702,6.702,17.775,0,24.768c-3.497,3.497-7.867,5.245-12.238,5.245s-9.033-1.748-12.238-5.245l-39.046-39.046
                    L46.295,436.79c-3.497,3.497-7.867,5.245-12.238,5.245s-9.033-1.748-12.238-5.245C14.243,430.089,14.243,419.016,21.236,412.022z"
                    />
            </g>
            </svg>
                           
            <span class="conformation_txt">{{__('base::gridlist.cancel_video_upload_msg')}}</span>
            <!-- data-ng-show="videoConfirmationDeleteBox" -->
            <span class="delete_detail">@{{ vodSelectedVideo.title }}</span>
            <div class="popup_btns text-center">
                <a data-ng-click="{{ $control }}.cancelReplaceVideos()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" >{{__('base::gridlist.no')}}</a>
                <a data-ng-click="{{ $control }}.confirmReplaceVideos(vodSelectedVideo)" href="javascript:void(0)" class="pop_confirm_btn" >{{__('base::gridlist.yes')}}</a>
            </div>
        </div>
    </div>
</div>

<!-- Live Stream Stop record requesting Modal -->
<div class="alert-popup modal fade" id="livestreamStopModal" data-role="dialog">
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>{{__('base::gridlist.convert_live_to_VOD_modal_title')}}</h3>
        </div>
        <div class="popup_content">
            <span class="conformation_txt">{{__('base::gridlist.convert_live_to_VOD_modal_content')}}</span>
            <div class="popup_btns text-center">
                <a data-ng-click="{{ $control }}.cancelLiveStreamRecording()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                <a data-ng-click="{{ $control }}.confirmLiveStreamRecording()" href="javascript:void(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
        </div>
    </div>
</div>

<!-- bulk edit delete code -->
<div class="alert-popup modal fade" id="videoBulkDeleteModal" data-role="dialog">
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>{{__('base::gridlist.bulk_action')}}</h3>
        </div>
        <div  class="popup_content" data-ng-show="{{ $control }}.isDeleteBulkRecord" >
            <span class="conformation_txt">
                {{__('base::gridlist.bulk_delete_confirm')}}
            </span>

            <div class="popup_btns text-center">
                <a class="pop_cancel_btn" data-ng-click="{{ $control }}.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                <a data-ng-click="{{ $control }}.confirmDeleteVideos('bulk-video')" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
        </div>

        <div class="popup_content" data-ng-show="{{ $control }}.isActivateBulkRecord">
            <span class="conformation_txt">
                {{__('base::gridlist.bulk_activate_confirm')}}
            </span>
            <div class="popup_btns text-center">
                <a class="pop_cancel_btn" data-ng-click="{{ $control }}.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                <a data-ng-click="{{ $control }}.confirmActivateOrDeactivateVideos(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
        </div>

        <div class="popup_content" data-ng-show="{{ $control }}.isDeactivateBulkRecord">
            <span class="conformation_txt">
                {{__('base::gridlist.bulk_deactivate_confirm')}}
            </span>
            <div class="popup_btns text-center">
                <a class="pop_cancel_btn" data-ng-click="{{ $control }}.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                <a data-ng-click="{{ $control }}.confirmActivateOrDeactivateVideos(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
            </div>
        </div>
        <div class="popup_content" data-ng-show="ConfirmationStatusBox">
                <span class="conformation_txt">
                        {{__('base::gridlist.statusChange')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="{{ $control }}.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="{{ $control }}.confirmStatus()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
        </div>
        <div class="popup_content" data-ng-show="{{ $control }}.isEditBulkRecord">
                <span class="conformation_txt">
                        {{__('base::gridlist.bulk_edit_confirm')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="{{ $control }}.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="{{ $control }}.confirmEditVideos()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
        </div>
    </div>    
</div>


<!-- thumbnail image code -->
<div class="custom-modal modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="custom-modal-dialog img-cropper" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                {{ __('video::videos.crop_image') }}
            </div>
            <div class="custom-modal-body">
                <div class="loader-container">
                    <img src="{{asset('adminview/assets/images/loader.gif')}}">
                </div>
                <p class="error_msg"></p>
                <div class="crop-body">
                    <div class="img-container">
                        <img id="image" src="" alt="Picture">
                    </div>
                    <div class="img-preview"></div>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <button type="button" class="popup-button grey-color" data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                <button type="button" class="popup-button blue-color" id="submit-image">{{ __('video::videos.submit') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- Poster Modal -->
<div class="custom-modal modal fade" id="poster_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="custom-modal-dialog img-cropper" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                {{ __('video::videos.crop_image') }}
            </div>
            <div class="custom-modal-body">
                <div class="poster_loader-container">
                    <img src="{{asset('adminview/assets/images/loader.gif')}}" />
                </div>
                <p class="poster_error_msg"></p>
                <div class="crop-body">
                    <div class="img-container">
                        <img id="poster_image" src="" alt="Picture" />
                    </div>
                    <div class="poster_img-preview"></div>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <button type="button" class="popup-button grey-color" data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                <button type="button" class="popup-button blue-color" id="submit_poster_image">{{ __('video::videos.submit') }}</button>
            </div>
        </div>
    </div>
</div>