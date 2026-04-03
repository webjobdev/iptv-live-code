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
   .views{
    justify-content: center;
   }
   .views svg{
        width: 20px;
        height: 20px;
        fill: #27caf0;
        margin-right: 5px;
   }
   .table>thead>tr>th:last-child {
    text-align: inherit !important;
   }
   .playlist-head {
    position: static !important; 
   }

</style>
<div class="product order_list" data-ng-controller="ViewPlaylistController as playlistViewCtrl" data-ng-init=playlistViewCtrl.fetchData('{{$id}}')>
   @include('audio::admin.common.subMenu', ['template' => 'playlist_detail'])
   @include('base::partials.errors')
   @include('audio::admin.common.responses')
   <div id="latest_video">
      <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
         <div class="table_loader">
            <div class="loader"></div>
         </div>
      </div>
      <div class="table_responsive">
         <table class="table" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
               <tr>
               
                  <th class="bulkth" style="width:100px;" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                     <div class="ckbox ckbox-default">
                        <input type="checkbox" id="selectall" value="playlistViewCtrl.selectedRecords.length != 0" data-ng-click="playlistViewCtrl.selectAllRecords()" />
                        <label for="selectall" class="nopadding"></label>
                     </div>
                     <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="playlistViewCtrl.selectedRecords.length != 0"
                        data-original-title="Select video in the grid to perform a bulk action">
                        <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                        {{__('audio::general.bulk_action')}}
                        <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                           <li>
                              <a data-toggle="modal" data-target="#single-song-record-delete-popup" 
                                 href="#">{{__('audio::general.delete')}}</a>
                           </li>
                        </ul>
                     </div>
                  </th>
                  <th data-ng-repeat="field in playlistViewCtrl.heading" ng-class="{'centre': field.name == 'No. Of Audio(s)'}" class="playlist-head">
                     @{{field.name}}
                     <span data-ng-if="field.sort==true" id="" class="th-inner sortable both " data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                     <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr data-ng-if="playlistViewCtrl.playlistVideos.videos.length == 0">
                  <td colspan="5" class="no-data center">{{trans('base::general.not_found')}}</td>
               </tr>
               <tr data-ng-if="playlistViewCtrl.playlistVideos.videos.length > 0" data-ng-repeat="record in playlistViewCtrl.playlistVideos.videos track by $index" data-ng-show="playlistViewCtrl.playlistVideos.videos.length > 0" class="list-repeat" data-intialize-sidebar="">
                  <td>
                     <div class="ckbox ckbox-default">
                        <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="playlistViewCtrl.selectRecord($event, record.id)"
                           value="@{{record.id}}" name="selectedCheckbox[]">
                        <label for="roles_@{{record.id}}"></label>
                     </div>
                  </td>
                  <td style="display: flex;">
                     <div class="product_img flexbox align-items-center table-image-text">
                        <!-- @{{record}} -->
                        <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="table-image-text flexbox align-items-center">
                                                
                                <div class="product_description tooltip-parent">
                                    <p class="img_description">@{{record.title}}
                                        <span class="tooltip_title">@{{record.title}}</span>
                                    </p>
                                </div>
                            </a>
                     </div>
                  </td>
                 
               </tr>
            </tbody>
         </table>
      </div>
      @include('playlist::admin.common.PlaylistSongsDeleteModal')
      @include('audio::admin.common.singleRecordStatusUpdateModal')
   </div>

   <div class="pagination_custom flexbox align-items-center">
      <div class="cs-showentry">
         <div data-ng-if="!hideRowsPerPage" class="show_entries" data-ng-if="!filters.collectionId && !filters.categoryId"> 
            <label for="" class="">{{trans('base::general.show')}}</label>
            <label for="" class="">
            <select minimumResults="-1" data-jquery="select2_custom_ddl" class="select2_custom_ddl" width="100px" ng-model="playlistViewCtrl.rows" data-ng-change="playlistViewCtrl.changeRows()" ng-value ="playlistViewCtrl.rows" ng-options="item for item in playlistViewCtrl.paginationList" class="form-control"></select>
            </label>
         </div>
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
   <div class="error-page" data-ng-if="playlistViewCtrl.notFoundFlag">
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
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/selectize.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular-selectize.js')}}"></script>

<script src="{{$getVideoAssetsUrl('js/playlist/playlist_videos.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection