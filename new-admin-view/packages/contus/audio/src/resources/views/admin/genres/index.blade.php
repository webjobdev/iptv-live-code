@extends('base::layouts.default')

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="GenresGridController as genregridCtrl" >
    <div class="page-heading flexbox align-items-center flex-wrap">
        <h4>{{trans('audio::genres.manage_genres')}}</h4>
        <div class="right-side flexbox align-items-center">
            <a data-ng-click="genregridCtrl.addGenre($event)"  href="javascript:void(0)" class="button button-blue sidepanel-open upload_video">
                <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px" height="18px">
                    <g>
                        <path d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z" fill="#ffffff"/>
                    </g>
                </svg>
                <span>{{trans('audio::genres.add_genre')}}</span>
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
    <div class="contentpanel clearfix category_grid" >
        @include('base::partials.errors')
        <div
            data-grid-view
            data-rows-per-page="10"
            data-route-name="genres"
            data-template-route = "admin/audios/genres"
            data-request-grid="genres"
            data-count = "false"
        ></div>
    </div>
    <div class="sidepanel">
        <div class="overlay"></div>
        <div class="pop_over_continer form-page">
            <form name="GenresForm" method="POST" data-base-validator data-ng-submit="genregridCtrl.genreSave($event, genregridCtrl.genre.id)" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="sidepanel-header flexbox align-items-center">
                    <h5 data-ng-if="!genregridCtrl.genre.id">{{trans('audio::genres.add_new_genre')}} </h5>
                    <h5 data-ng-if="genregridCtrl.genre.id">{{trans('audio::genres.edit_genre')}} </h5>
                </div>

                <div class="sidepanel-scroll">                    
                    @include('base::partials.errors')
                    <div class="form-group" data-ng-class="{'has-error': errors.genre_name.has}">
                        <label>{{trans('audio::genres.name')}} <span class="required">*</span></label>
                        <div class="form-input">
                            <input type="text" name="genre_name" maxlength="255" class="form-control" data-ng-model="genregridCtrl.genre.genre_name" placeholder="{{trans('audio::genres.name')}}" value="{{old('name')}}" />
                        </div>
                        <p class="error-msg" data-ng-show="errors.genre_name.has">@{{ errors.genre_name.message }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>{{trans('audio::genres.order')}}</label>
                        <div class="form-input">
                            <input type="text" name="order" maxlength="255" class="form-control" data-ng-model="genregridCtrl.genre.order" placeholder="{{trans('audio::genres.order')}}" value="{{old('order')}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="switch-concept flexbox align-items-center">
                            <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                <g>
                                    <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                     fill="#3d3d3d"></path>
                                </g>
                            </svg>
                            <div class="swich-content flexbox align-items-center flex-wrap">
                                <span>{{ trans('audio::general.status') }}</span>
                                <div class="right-side flexbox align-items-center">
                                    <span class="text">({{ trans('audio::general.record_status_grid.inactive') }})</span>
                                    <label class="switch">
                                        <input type="checkbox" name="status" name="is_active" data-ng-model="genregridCtrl.genre.is_active">
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="text">({{ trans('audio::general.record_status_grid.active') }})</span>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg"></p>
                    </div>                    
                </div>

                <div class="bottom-button text-right flexbox align-items-center">
                    <button class="save" data-ng-click="genregridCtrl.closeGenreEdit()">
                        {{ trans('base::general.cancel') }}
                    </button>
                    <button class="publish-now">
                        {{trans('base::general.submit')}}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getAudioAssetsUrl('js/genres/genresGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script> 
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection