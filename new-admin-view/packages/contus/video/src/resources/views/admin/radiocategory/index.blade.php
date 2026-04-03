@extends('base::layouts.default')

@section('stylesheet')
<link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')

<div data-ng-controller="CategoryGridController as catgridCtrl">
        @include('video::admin.common.popup', ['template' => 'category_video', 'control' => 'catgridCtrl'])
    <div class="page-heading flexbox align-items-center flex-wrap">
        <h4>Manage radio category</h4>
        <div class="right-side flexbox align-items-center">
        <a data-ng-if="checkAccess('radiocategory_all_write')" data-ng-click="catgridCtrl.addCategory($event)"  href="javascript:void(0)" class="button button-blue sidepanel-open">
                    <svg viewBox="0 0 18 18" x="0px" y="0px" width="18px" height="18px">
                        <g>
                            <path d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z" fill="#ffffff"></path>
                        </g>
                    </svg>
                    <span>{{trans('video::categories.add_category')}}</span>
            </a>
            
            <a href="javascript:void(0)" class="video-demo-ancher" ng-click="showDemoVideo = !showDemoVideo">
                <div class="hotspot__positioner--01">
                    <div class="hotspot__container">
                        <div class="hotspot hotspot--01"></div>
                        <div class="hotspot hotspot--02"></div>
                        <div class="hotspot hotspot--03"></div>
                    </div>
                </div>
                <svg viewBox="0 0 47 48" version="1.1" x="0px" y="0px" width="55px" height="55px" class="demo-ic">
                    <g>
                        <path d="M 31.4865 29.034 C 31.0336 29.034 28.1232 26.3365 28.1232 25.9814 L 28.1232 22.4468 C 28.1232 22.0919 30.969 19.3943 31.4865 19.3943 C 32.0038 19.3943 32.0038 19.8762 32.0038 19.8762 L 32.0038 28.552 C 32.0038 28.552 31.9392 29.034 31.4865 29.034 ZM 26.8619 28.3109 L 16.7722 28.3109 C 16.3435 28.3109 15.9961 28.0664 15.9961 27.7648 L 15.9961 20.6636 C 15.9961 20.3619 16.3435 20.1172 16.7722 20.1172 L 26.8619 20.1172 C 27.2906 20.1172 27.6381 20.3619 27.6381 20.6636 L 27.6381 27.7648 C 27.6381 28.0664 27.2906 28.3109 26.8619 28.3109 ZM 24.2424 19.8419 C 22.903 19.8419 21.817 18.7628 21.817 17.4319 C 21.817 16.1009 22.903 15.0219 24.2424 15.0219 C 25.582 15.0219 26.6678 16.1009 26.6678 17.4319 C 26.6678 18.7628 25.582 19.8419 24.2424 19.8419 ZM 18.9065 19.8419 C 17.835 19.8419 16.9662 18.9786 16.9662 17.9139 C 16.9662 16.8491 17.835 15.986 18.9065 15.986 C 19.9781 15.986 20.8469 16.8491 20.8469 17.9139 C 20.8469 18.9786 19.9781 19.8419 18.9065 19.8419 Z"
                            fill="#ffffff">
                        </path>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="contentpanel clearfix category_grid">
        @include('base::partials.errors')
        <div class="response-msg"></div>
        <div data-grid-view data-rows-per-page="10" data-route-name="radiocategory" data-template-route="admin/radiocategory"
            data-request-grid="radiocategory" data-count="false"></div>
    </div>

    <div class="sidepanel">
        <div class="overlay"></div>
        <div class="pop_over_continer form-page">
            <form name="categoriesForm" id="categoriesForm" method="POST" data-base-validator data-ng-submit="catgridCtrl.categorySave($event, catgridCtrl.category.id)"
                enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="sidepanel-header flexbox align-items-center">
                    <h5 data-ng-if="!catgridCtrl.category.id">{{trans('video::categories.add_new_categories')}} </h5>
                    <h5 data-ng-if="catgridCtrl.category.id">{{trans('video::categories.edit_category')}} </h5>
                    <div data-ng-if="catgridCtrl.category.id" class="right-side">
                        <select minimumResults="-1" width="100px" data-jquery="select2_custom_ddl" name="language"
                            class="select2_custom_ddl" ng-change="catgridCtrl.languageChange()"
                            myValue="catgridCtrl.categoryTranslation.language"
                            data-ng-model="catgridCtrl.categoryTranslation.language"
                            data-ng-options="a.id as a.title  for a in catgridCtrl.languages ">
                        </select>
                    </div>
                </div>
                <div class="sidepanel-scroll mCustomScrollbar" data-mcs-theme="dark">
                    @include('base::partials.errors')
                    <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                        <label>
                            {{trans('video::categories.title')}} 
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="text" name="title" maxlength="255" class="form-control"
                                data-unique="@{{catgridCtrl.categoriesUniqueRoute}}"
                                data-ng-model="catgridCtrl.category.title"
                                placeholder="{{trans('video::categories.title')}}" value="{{old('title')}}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                    </div>

                    <div class="form-group row radio-wrapper" data-ng-hide="true">
                        <div class="col-md-4 col-sm-12">
                            <label class="control-label">{{ trans('video::categories.web_series') }}</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <input type="radio" id="series-yes" name="is_web_series" data-ng-click="catgridCtrl.changeWebseries()"
                                value="1" data-ng-model="catgridCtrl.category.is_web_series" class="ng-pristine ng-untouched ng-valid">
                            <label for="series-yes" class="radio-custom-label">Yes</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <input type="radio" id="series-no" name="is_web_series" data-ng-click="catgridCtrl.changeWebseries()"
                                value="0" data-ng-model="catgridCtrl.category.is_web_series" class="ng-pristine ng-untouched ng-valid">
                            <label for="series-no" class="radio-custom-label">No</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">{{ trans('video::categories.order') }} </label>
                        <div class="form-input">
                            <input type="text" name="category_order" maxlength="10" class="form-control" data-ng-model="catgridCtrl.category.category_order" placeholder="Add order value" value="{{old('category_order')}}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="switch-concept flexbox align-items-center">
                            <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                <g>
                                    <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
                                </g>
                            </svg>
                            <div class="swich-content flexbox align-items-center flex-wrap">
                                <span>{{ trans('video::videos.status') }}</span>
                                <div class="right-side flexbox align-items-center">
                                    <span class="text">({{ trans('video::videos.message.inactive') }})</span>
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" data-ng-model="catgridCtrl.category.is_active">
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="text">({{ trans('video::videos.message.active') }})</span>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg"></p>
                    </div>

                    <div data-ng-hide="true" class="form-group" data-ng-class="{'has-error': errors.category_image.has}">
                        <label>{{ trans('video::categories.category_image') }} </label>
                        <div class="form-input">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="input-append">
                                    <button class="subtitle_btn">
                                        <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                            <g>
                                                <path d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z" fill="#ffffff"></path>
                                            </g>
                                        </svg>
                                        <span class="fileupload-new">Upload Image</span> 
                                        <span class="fileupload-exists">Change Image</span>
                                        <input type="file" id="category-image" name="image" accept="image/x-png,image/jpeg"  data-action="api/admin/categories/category-image" />
                                    </button>

                                    <span class="fileupload-preview"></span>
                                </div>

                                <a href="#" class="fileupload-exists category-image-remove"
                                        data-dismiss="fileupload" data-ng-click="catgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                                    <p class="help-block hide"></p>
                            </div>
                        </div>
                        <p class="help-block" data-ng-show="errors.category_image.has">@{{ errors.category_image.message }}</p>
                        <div class="form-group">
                            <div class="clsFileUpload preview-image">
                                <span id="category-image-delete" data-ng-click="catgridCtrl.deleteCategoryImage()"
                                    data-ng-show="catgridCtrl.category.image_url" data-boot-tooltip="true" title="{{trans('video::videos.delete_category_image')}}"><i
                                        class="fa fa-remove" aria-hidden="true"></i></span>
                                <img id="category-image-preview" data-ng-show="catgridCtrl.category.image_url"
                                    data-ng-src="@{{catgridCtrl.category.image_url}}"  width="180px" height="180px">
                                <div id="category-image-progress" class="hide clsProgressbar "></div>
                                <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom-button text-right flexbox align-items-center">
                    
                    <a class="save" data-ng-click="catgridCtrl.closeCategoryEdit()">
                        {{ trans('base::general.cancel') }}
                    </a>
                    <button class="publish-now">
                        {{trans('base::general.submit')}}
                    </button>
                </div>
            </form>


            <form name="categoriesTranslationForm" style="display:none;" id="categoriesTranslationForm" method="POST"
            data-base-validator data-ng-submit="catgridCtrl.categoryTranslateSave($event, catgridCtrl.category.id)"
            enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="sidepanel-header flexbox align-items-center">
                    <h5 data-ng-if="!catgridCtrl.category.id">{{trans('video::categories.add_new_categories')}} </h5>
                    <h5 data-ng-if="catgridCtrl.category.id">{{trans('video::categories.edit_category')}} </h5>
                    <div data-ng-if="catgridCtrl.category.id" class="right-side">
                        <select minimumResults="-1"  data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="catgridCtrl.languageChange()" myValue="catgridCtrl.categoryTranslation.language" data-ng-model="catgridCtrl.categoryTranslation.language"  data-ng-options="a.id as a.title  for a in catgridCtrl.languages " ></select>
                    </div>
                </div>
                <div class="sidepanel-scroll">                    
                    @include('base::partials.errors')
                    <div class="form-group" data-ng-class="{'has-error': errors.title1.has}">
                        <label>
                            {{trans('video::categories.title')}} 
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="text" name="title" disabled="disabled" maxlength="255" class="form-control" data-unique="@{{catgridCtrl.categoriesUniqueRoute}}" data-ng-model="catgridCtrl.category.title" placeholder="{{trans('video::categories.title')}}" value="{{old('title')}}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.title1.has">@{{ errors.title.message }}</p>
                    </div>

                    <div class="form-group" data-ng-class="{'has-error': errors.trans_title.has}">
                        <label>
                            {{trans('video::categories.title')}} 
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="text" name="trans_title" maxlength="255" class="form-control" data-unique="@{{catgridCtrl.categoriesUniqueRoute}}" data-ng-model="catgridCtrl.categoryTranslation.title" placeholder="{{trans('video::categories.title')}}" value="{{old('title')}}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.trans_title.has">@{{ errors.trans_title.message }}</p>
                    </div>
                </div>
                <div class="bottom-button text-right flexbox align-items-center">
                    <button class="save" data-ng-click="catgridCtrl.closeCategoryEdit()">
                        {{ trans('base::general.cancel') }}
                    </button>
                    <button class="publish-now">
                        {{trans('base::general.submit')}}
                    </button>
                </div>
            </form>
        </div>
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
                <div class="clearfix modal-footer video_delete_footer">
                    <span data-ng-click="catgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right"
                        data-dismiss="modal">{{__('base::gridlist.cancel')}}</span>
                    <span data-ng-click="catgridCtrl.confirmDeleteVideos('single-video')"
                        class="btn btn-primary pull-right mr10"
                        data-dismiss="modal">{{__('base::gridlist.confirm')}}</span>
                </div>
            </div>
        </div>
    </div>
      
</div>
@endsection

@section('scripts')
<script src="{{asset('adminview/assets/js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/videos/categoryGrid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
