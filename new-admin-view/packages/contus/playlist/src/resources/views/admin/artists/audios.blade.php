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
<div data-no-grid-common-directives class="product order_list"  data-ng-controller="ViewAudioArtistsController as audioArtistCtrl" data-ng-init=audioArtistCtrl.fetchData('{{$id}}')>
    @include('audio::admin.common.subMenu', ['template' => 'audio_track_list'])
    <div class="contentpanel clearfix video-conatiner" data-ng-if="!audioArtistCtrl.notFoundFlag">
        <div>
            <div class="category-detail video-detail">
                <h4 class="heading">
                    <span>{{trans('audio::general.artist')}} :</span> 
                    <span class="hightlight">
                      
                        @{{audioArtistCtrl.audioArtists.artist_name}}
                    </span>
                </h4>
                <div data-ng-if="audioArtistCtrl.audioArtists.audios == ''" class="no-data center">
                    {{trans('base::general.not_found')}}
                </div>
                <ul class="category-lists flexbox flex-wrap" data-ng-if="audioArtistCtrl.audioArtists.audios != ''" data-ng-show="audioArtistCtrl.audioListView">
                    <li class="flexbox" data-ng-repeat = "record in audioArtistCtrl.audioArtists.audios track by $index">
                        <div class="single-category-list flexbox">

                            <a data-ng-if="record.audio_thumbnail == ''" class="image" href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}">
                                <img class="img-responsive" src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                            </a>
                            <a data-ng-if="record.audio_thumbnail != ''" class="image" href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}">
                                <img class="img-responsive" src="@{{record.audio_thumbnail}}" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                            </a>
        
                            <div class="content">
                                <div class="title-views flexbox">
                                    <a class="title" href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}">@{{record.audio_title}}</a>
                                    <div class="views flexbox align-items-center play">
                                        <svg _ngcontent-c12="" class="ng-tns-c12-0" version="1.1" viewBox="0 0 60 60"><path _ngcontent-c12="" class="ng-tns-c12-0" d="M53.901,26.173L10.284,1.524C6.309-0.724,3.103,1.073,3.125,5.528l0.223,48.927
                                c0.021,4.452,3.266,6.267,7.253,4.035l43.281-24.214C57.864,32.049,57.874,28.421,53.901,26.173z"></path></svg>
                                        <span>@{{record.play_count}} Plays</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{url('admin/albums/audios')}}/@{{record.album.id}}" class="sub-title">@{{record.album.album_name}}</a>
                                </div>
                                <p>@{{record.audio_description | truncate:true:100:' ...'}}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            @include('base::layouts.pagination')
        </div>
    </div>
    <div class="error-page" data-ng-if="audioArtistCtrl.notFoundFlag">
        <h4> {{ trans('base::general.404_not_found') }}</h4>
        <p> {{ trans('base::general.not_found_text') }}</p>
    </div>
</div>
@endsection
@section('scripts')
  <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/common/nogridPaginationController.js')}}"></script>
  <script src="{{$getAudioAssetsUrl('js/artists/audios.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>


@endsection