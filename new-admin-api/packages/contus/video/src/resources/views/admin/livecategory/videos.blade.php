@extends('base::layouts.default')
@section('stylesheet')
@endsection
@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
<style type="text/css">
.custom-color {
color: #a94442;
}
</style>
<div class="product order_list"  data-ng-controller="ViewVideoCategoriesController as vVideoCategoriesCtrl" data-ng-init=vVideoCategoriesCtrl.fetchData('{{$id}}')>
    @include('video::admin.common.subMenu', ['template' => 'category_video'])
    <div class="contentpanel clearfix video-conatiner" data-ng-if="!vVideoCategoriesCtrl.notFoundFlag">

        <div data-ng-init="vVideoCategoriesCtrl.parentCategory('{{$id}}')">
            <div class="category-detail video-detail">
                <h4 class="heading">
                    <span>{{trans('video::videos.category')}} :</span> 
                    <span class="hightlight">
                        <i data-ng-if="vVideoCategoriesCtrl.parentCategoryTitle" data-ng-repeat="(key, record) in vVideoCategoriesCtrl.parentCategoryTitle">
                            @{{record}} /
                        </i> 
                        @{{vVideoCategoriesCtrl.videoCategories.title}}
                    </span>
                </h4>

                <div data-ng-if="vVideoCategoriesCtrl.videoCategories.videos == ''" class="no-data center">
                    {{trans('base::general.not_found')}}
                </div>

                <ul class="category-lists flexbox flex-wrap" data-ng-if="vVideoCategoriesCtrl.videoCategories.videos != ''" data-ng-show="vVideoCategoriesCtrl.videoListView">
                    <li class="flexbox" data-ng-repeat = "record in vVideoCategoriesCtrl.videoCategories.videos track by $index">
                        <div class="single-category-list flexbox">
                            <a class="image" href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">
                                <img class="img-responsive" src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                            </a>

                            <div class="content">
                                <div class="title-views flexbox">
                                    <a class="title" href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">@{{record.title}}</a>
                                    <div class="views flexbox align-items-center">
                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 511.999 511.999">
                                            <g>
                                                <g>
                                                    <path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
                                                        c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
                                                        C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
                                                        c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
                                                        C447.361,287.923,358.746,385.406,255.997,385.406z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
                                                        s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
                                                        s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z"/>
                                                </g>
                                            </g>
                                        </svg>

                                        <span>@{{record.view_count}} {{trans('video::videos.views')}}</span>
                                    </div>
                                </div>
                                
                                <div data-ng-repeat="category in record.videocategory track by $index">
                                    <a href="{{url('admin/categories/videos')}}/@{{category.category_id}}" class="sub-title">@{{ category.category.title }}</a><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                </div>

                                
                                <p>@{{record.description}}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="pagination_custom flexbox align-items-center">
                <div class="cs-showentry">
                    <!-- ngIf: !filters.collectionId && !filters.categoryId -->
                   <!--  <div class="show_entries ng-scope" data-ng-if="!filters.collectionId &amp;&amp; !filters.categoryId"> 
                        <label for="" class="">Show</label>
                        <label for="" class="">
                        <select data-ng-model="grid.rows" data-jquery="select2_custom_ddl" class="select2_custom_ddl" data-ng-change="changeRows()" class="form-control ng-pristine ng-untouched ng-valid ng-not-empty">
                            <option value="10" selected>10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        </label>
                    
                    </div> --><!-- end ngIf: !filters.collectionId && !filters.categoryId -->                        
                </div>
                <div data-ng-if="totalRecords != 0" class="pagination_div">
                        <ul class="table-pagination" data-ng-if="links.length > 0">
                            <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                                <a ng-if="link.value=='Previous'" href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="page_previous pageLink" >@{{link.value}}</a>
                                <a ng-if="link.value!='Previous' && link.value!='Next'"href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="pageLink" >@{{link.value}}</a>
                                <a ng-if="link.value=='Next'"href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="page_next pageLink" >@{{link.value}}</a>
                            </li>
                        </ul>
                    </div>
                
            </div>
            
            <!-- @include('base::layouts.pagination') -->

        </div>
        
    </div>

</div>
    <div class="error-page" data-ng-if="vVideoCategoriesCtrl.notFoundFlag">
        <h4>{{ trans('base::general.404_not_found') }}</h4>
        <p>{{ trans('base::general.not_found_text') }}</p>
    </div>
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
                    <select class="edit-select-lang" data-ng-change="catgridCtrl.languageChange()" data-ng-model="catgridCtrl.categoryTranslation.language">
                        <option data-ng-repeat="language in catgridCtrl.languages track by $index" value="@{{language.id}}">@{{language.title}}</option>
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
                        <input type="text" name="title" maxlength="255" class="form-control" data-unique="@{{catgridCtrl.categoriesUniqueRoute}}" data-ng-model="catgridCtrl.category.title" placeholder="{{trans('video::categories.title')}}" value="{{old('title')}}" />
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
                <div class="form-group" ng-show="catgridCtrl.showcategory">
                    <label class="control-label">{{ trans('video::categories.parent_category') }} </label>
                    <div class="category_tree">
                        <div class="categoryList" data-ng-bind-html="catgridCtrl.allCategoriesHTML"></div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label">{{ trans('video::videos.status') }} </label>
                    <div class="form-input">
                        <select class="form-control" name="is_active" data-ng-model="catgridCtrl.category.is_active">
                            <option value="1">{{ trans('video::videos.message.active') }}</option>
                            <option value="0">{{ trans('video::videos.message.inactive') }}</option>
                        </select>
                    </div>
                </div> -->
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
                                <input type="checkbox" name="status">
                                <span class="slider round"></span>
                            </label>
                            <span class="text">({{ trans('video::videos.message.active') }})</span>
                        </div>
                    </div>
                </div>
                <p class="error-msg"></p>
                <div class="form-group">
                    <label class="control-label">{{ trans('video::categories.order') }} </label>
                    <div class="form-input">
                        <input type="text" name="category_order" maxlength="10" class="form-control" data-ng-model="catgridCtrl.category.category_order" placeholder="Add order value" value="{{old('category_order')}}" />
                    </div>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.category_image.has}">
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
                                    <input type="file" id="category-image" name="image" data-action="api/admin/categories/category-image" />
                                </button>
                                <span class="fileupload-preview"></span>
                            </div>
                            <a href="#" class="fileupload-exists category-image-remove"
                            data-dismiss="fileupload" data-ng-click="catgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                            <p class="help-block hide"></p>
                        </div>
                    </div>
                    <p class="help-block" data-ng-show="errors.category_image.has">@{{ errors.category_image.message }}</p>
                    <div class="form-group" style="display:none">
                        <div class="clsFileUpload preview-image">
                            <span id="category-image-delete" data-ng-click="catgridCtrl.deleteCategoryImage()"
                                data-ng-show="catgridCtrl.category.image_url" data-boot-tooltip="true" title="{{trans('video::videos.delete_category_image')}}"><i
                            class="fa fa-remove" aria-hidden="true"></i></span>
                            <img id="category-image-preview" data-ng-show="catgridCtrl.category.image_url"
                            data-ng-src="@{{catgridCtrl.category.image_url}}" width="180px" height="180px">
                            <div id="category-image-progress" class="hide clsProgressbar "></div>
                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                        </div>
                    </div>
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
        <form name="categoriesTranslationForm" style="display:none;" id="categoriesTranslationForm" method="POST"
            data-base-validator data-ng-submit="catgridCtrl.categoryTranslateSave($event, catgridCtrl.category.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!catgridCtrl.category.id">{{trans('video::categories.add_new_categories')}} </h5>
                <h5 data-ng-if="catgridCtrl.category.id">{{trans('video::categories.edit_category')}} </h5>
                <div data-ng-if="catgridCtrl.category.id" class="right-side">
                    <select class="edit-select-lang" data-ng-change="catgridCtrl.languageChange()" data-ng-model="catgridCtrl.categoryTranslation.language">
                        <option data-ng-repeat="language in catgridCtrl.languages track by $index" value="@{{language.id}}">@{{language.title}}</option>
                    </select>
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
@endsection
@section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/livecategory/videos.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>

@endsection