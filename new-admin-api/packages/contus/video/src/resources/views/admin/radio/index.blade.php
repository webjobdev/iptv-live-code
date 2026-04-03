@extends('base::layouts.default') @section('stylesheet')
<link href="//vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/cropper.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/ng-tags-input.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/select2.min.css')}}"/>
<style>
    .img-container img {
        max-width: 100%;
    }

    .img-preview {
        width: 200px;
        height: 200px;
        overflow: hidden;
    }

    .img-cropper {
        width: 50%;
    }

    .uploaded_img {
        width: 200px;
        height: 215px;
        margin: 10px;
        display: none;
    }

    .uploaded_poster_img {
        width: 400px;
        height: 250px;
        margin: 10px;
        display: none;
    }

    .uploaded_img.active {
        display: block;
    }

    .loader-container,
    .poster_loader-container {
        display: none;
        text-align: center;
    }

    p.error_msg,
    p.poster_error_msg {
        display: none;
        color: red;
        text-align: center;
    }
</style>
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
    <style type="text/css">
        .custom-color {
            color: #a94442;
        }

        .kewwords_tag .tagBox {
            height: auto;
            padding: 0;
        }

        .kewwords_tag .edit_keywords {
            float: left;
            padding: 0px 10px;
            background: #fff;
        }

        .kewwords_tag .result_tag {
            display: inline-block;
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            padding: 0 5px;
        }

        .kewwords_tag span.removetag {
            border: none;
            margin-left: 5px;
            padding: 1px;
            cursor: pointer;
        }

        .tagOuterBox:after {
            content: '';
            display: block;
            clear: both;
            background-color: #D0D0D0;
        }

        .tagBox {
            float: left;
        }

        .tagOuterBox .contentEditable {
            float: left;
        }

        div[contentEditable] {
            cursor: pointer;
            background-color: #D0D0D0;
        }
    </style>
    <div data-ng-controller="VideoGridController as vgridCtrl" >
        @include('video::admin.common.subMenu', ['template' => 'radiolist'])
        <div class="contentpanel clearfix video_grid">
            @include('base::partials.errors')
            <div class="response-msg"></div>
            <div data-grid-view data-rows-per-page="10" data-route-name="radio" data-template-route="admin/radio"
                 data-request-grid="radio" data-count="false"></div>
        </div>
   

    <div class="modal fade" id="videoDeleteModal" data-role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{__('base::gridlist.delete_record')}}</h5>
                </div>
                <div class="modal-body">
                    <div data-ng-show="videoConfirmationDeleteBox">
                        <p>{{__('base::gridlist.delete_confirm')}}</p>
                    </div>
                </div>
                <div class="clearfix modal-footer video_delete_footer radio-popup">
                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right" data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vgridCtrl.confirmDeleteVideos('single-video')" class="btn btn-primary pull-right mr10"
                        data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="alert-popup modal fade" id="videoBulkDeleteModal" data-role="dialog">
        <div class="alert-popup-content">
            <div class="popup_head">
                <h3>{{__('base::gridlist.bulk_action')}}</h3>
            </div>
            <div  class="popup_content" data-ng-show="vgridCtrl.isDeleteBulkRecord" >
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_delete_confirm')}}
                </span>

                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="vgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="vgridCtrl.confirmDeleteVideos('bulk-video')" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>

            <div class="popup_content" data-ng-show="vgridCtrl.isActivateBulkRecord">
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_activate_confirm')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="vgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>

            <div class="popup_content" data-ng-show="vgridCtrl.isDeactivateBulkRecord">
                <span class="conformation_txt">
                    {{__('base::gridlist.bulk_deactivate_confirm')}}
                </span>
                <div class="popup_btns text-center">
                    <a class="pop_cancel_btn" data-ng-click="vgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                    <a data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                </div>
            </div>
            <div class="popup_content" data-ng-show="ConfirmationStatusBox">
                    <span class="conformation_txt">
                            {{__('base::gridlist.statusChange')}}
                    </span>
                    <div class="popup_btns text-center">
                        <a class="pop_cancel_btn" data-ng-click="vgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                        <a data-ng-click="vgridCtrl.confirmStatus()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                    </div>
            </div>
            <div class="popup_content" data-ng-show="vgridCtrl.isEditBulkRecord">
                    <span class="conformation_txt">
                            {{__('base::gridlist.bulk_edit_confirm')}}
                    </span>
                    <div class="popup_btns text-center">
                        <a class="pop_cancel_btn" data-ng-click="vgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                        <a data-ng-click="vgridCtrl.confirmEditRadio()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                    </div>
            </div>
        </div>      
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog img-cropper" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">{{ __('video::videos.crop_image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 loader-container">
                            <img src="{{url('contus/base/images/loader.gif')}}">
                        </div>
                        <p class="error_msg"></p>
                        <div class="crop-body">
                            <div class="col-md-8">
                                <div class="img-container">
                                    <img id="image" src="" alt="Picture">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="img-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="video-index" name="video-index" value="" />
                    <button type="button" class="btn btn-primary" id="submit-image">{{ __('video::videos.submit') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('base::general.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Poster Modal -->
    <div class="modal fade" id="poster_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog img-cropper" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">{{ __('video::videos.crop_image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 poster_loader-container">
                            <img src="{{url('contus/base/images/loader.gif')}}">
                        </div>
                        <p class="poster_error_msg"></p>
                        <div class="crop-body">
                            <div class="col-md-8">
                                <div class="img-container">
                                    <img id="poster_image" src="" alt="Picture">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="poster_img-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="video-index" name="video-index" value="" />
                    <button type="button" class="btn btn-primary" id="submit_poster_image">{{
                        __('video::videos.submit') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('base::general.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div> 

@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/cropper.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-tags-input.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
<script src="//vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
<script src="//vjs.zencdn.net/5.0.2/video.min.js"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>

<script src="{{$getBaseAssetsUrl('js/angular/angular-ui.js')}}"></script>

<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/videoGrid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>

@endsection
