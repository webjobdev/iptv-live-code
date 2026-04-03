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
<div class="product order_list"  data-ng-controller="ViewAudioAlbumsController as vAudioAlbumsCtrl" data-ng-init=vAudioAlbumsCtrl.fetchData('{{$id}}')>

    @include('audio::admin.common.subMenu', ['template' => 'album_audio_list'])

    <div class="contentpanel clearfix video-conatiner" data-ng-if="!vAudioAlbumsCtrl.notFoundFlag">
        <div data-ng-if="vAudioAlbumsCtrl.audioAlbums.audios == ''" style="text-align: center;width: 100%;margin-top:15px;" colspan="@{{heading.length + 2}}" class="no-data">{{trans('base::general.not_found')}}</div>

        <div class="video-list-grid category-detail video-detail"  data-ng-if="vAudioAlbumsCtrl.audioAlbums.audios != ''">
            <h4 class="heading">
                <span>Album :</span>
                <span class="hightlight">@{{vAudioAlbumsCtrl.audioAlbums.album_name}}</span>
            </h4>
            
            <ul class="video_listing_view category-lists flexbox flex-wrap" data-ng-show="vAudioAlbumsCtrl.audioListView">
                <li data-ng-repeat = "record in vAudioAlbumsCtrl.audioAlbums.audios track by $index" class="flexbox">
                    <div class="single-category-list flexbox">
                        <a href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}" class="image">
                            <img class="img-responsive" src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.audio_thumbnail}}" alt="" />
                        </a>
                        <div class="content">
                            <div class="title-views flexbox">
                                <a href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}" class="video-list-title">@{{record.audio_title}}</a>
                            </div>

                            <span>@{{record.artist.artist_name}}<span data-ng-if="record.artist.artist_name != null">,</span></span>  <span>@{{record.play_count}} Plays</span>

                            <p ng-init="limit = 150; moreShown = false">
                                @{{ record.audio_description| limitTo: limit}}@{{ record.audio_description.length > limit ? '...' : ''}}
                                <a class="read-more-trigger" ng-show="record.audio_description.length > limit"
                                    href ng-click="limit=record.audio_description.length; moreShown = true">  {{ __('video::videos.more') }}
                                </a>
                                <a class="read-more-trigger" ng-show="moreShown" href ng-click="limit=200; moreShown =    false"> {{ __('video::videos.less') }} </a>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="pagination_custom flexbox align-items-center">
            <div class="pagination_div">
                <ul class="table-pagination" data-ng-if="links.length > 0">
                    <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                        <a href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="pageLink" >@{{link.value}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
   
    <div class="error-page" data-ng-if="vAudioAlbumsCtrl.notFoundFlag">
        <h4>{{ trans('base::general.404_not_found') }}</h4>
        <p>{{ trans('base::general.not_found_text') }}</p>
    </div>

    <div class="error-page" data-ng-if="vAudioAlbumsCtrl.notFoundFlag">
        <h4>{{ trans('base::general.404_not_found') }}</h4>
        <p>{{ trans('base::general.not_found_text') }}</p>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getAudioAssetsUrl('js/albums/audios.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>

@endsection