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

<div class="product order_list"  data-ng-controller="ViewAudioArtistsController as vAudioArtistCtrl" data-ng-init=vAudioArtistCtrl.fetchData('{{$id}}')>
    
@include('audio::admin.common.subMenu', ['template' => 'audio_track_list'])

    <div class="contentpanel clearfix video-conatiner" data-ng-if="!vAudioArtistCtrl.notFoundFlag">
        <div data-ng-if="vAudioArtistCtrl.audioArtists.audios == ''" style="text-align: center;width: 100%;margin-top:15px;" colspan="@{{heading.length + 2}}" class="no-data center">{{trans('base::general.not_found')}}</div>

        <div class="video-list-grid">
            <div data-ng-if="vAudioArtistCtrl.audioArtists.audios != ''"  class="pagination_bredrumbs clearfix">
            <h4 class="pull-left">@{{vAudioArtistCtrl.audioArtists.artist_name}} </h4>
            </div>
        </div>
        <div class="video_listing_view" data-ng-if="vAudioArtistCtrl.audioArtists.audios != ''" data-ng-show="vAudioArtistCtrl.audioListView">
            <div data-ng-repeat = "record in vAudioArtistCtrl.audioArtists.audios track by $index" class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <a href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}">
                    <div class="video_list_img">
                        <img class="img-responsive" src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.audio_thumbnail}}" alt="" />
                        <i class="fa fa-play-circle" aria-hidden="true"></i>
                    </div>
                    <div class="video_list_info">
                        <a href="{{url('admin/audios/view-details-audio')}}/@{{record.id}}" class="video-list-title">@{{record.audio_title}}</a>
                        <span> @{{record.album.album_name}}</span>
                        <span data-ng-if="record.album.album_name != null">,</span>
                        <span>@{{record.view_count}} Plays</span>
                        <p>@{{record.audio_description}}</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="pagination_custom clearfix">
            <ul class="pagination pagination-split nomargin pull-right" data-ng-if="links.length > 0">
                <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                    <a href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="pageLink" >@{{link.value}}</a>
                </li>
            </ul>
        </div>
    </div>
   
    <div class="error-page" data-ng-if="vAudioArtistCtrl.notFoundFlag">
        <h4>{{ trans('base::general.404_not_found') }}</h4>
        <p>{{ trans('base::general.not_found_text') }}</p>
    </div>
</div>
@endsection
@section('scripts')
  <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
  <script src="{{$getAudioAssetsUrl('js/artists/audios.js')}}"></script>
@endsection