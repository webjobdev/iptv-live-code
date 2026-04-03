@extends('base::layouts.default') @section('stylesheet') @endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div data-ng-controller="PresetGridController as pregridCtrl" >
@include('video::admin.common.subMenu', ['template' => 'presets', 'control' => 'pregridCtrl'])
    @include('video::admin.common.popup', ['template' => 'presets', 'control' => 'pregridCtrl'])
<div class="contentpanel clearfix collection_grid" >
                @include('base::partials.errors')  
                <div class="response-msg"></div>
    <div 
        data-grid-view 
        data-rows-per-page="10"
        data-route-name="presets"
        data-template-route = "admin/presets"
        data-request-grid="presets"
        data-count = "false"
    ></div>
            </div>
            <div class="alert-popup modal fade" id="videoBulkDeleteModal" data-role="dialog">
                    <div class="alert-popup-content">
                        <div class="popup_head">
                            <h3>{{__('base::gridlist.bulk_action')}}</h3>
                        </div>
                        <div  class="popup_content" data-ng-show="catgridCtrl.isDeleteBulkRecord" >
                            <span class="conformation_txt">
                                {{__('base::gridlist.bulk_delete_confirm')}}
                            </span>
            
                            <div class="popup_btns text-center">
                                <a class="pop_cancel_btn" data-ng-click="catgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                                <a data-ng-click="catgridCtrl.confirmDeleteVideos('bulk-video')" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                            </div>
                        </div>
            
                        <div class="popup_content" data-ng-show="catgridCtrl.isActivateBulkRecord">
                            <span class="conformation_txt">
                                {{__('base::gridlist.bulk_activate_confirm')}}
                            </span>
                            <div class="popup_btns text-center">
                                <a class="pop_cancel_btn" data-ng-click="catgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                                <a data-ng-click="catgridCtrl.confirmActivateOrDeactivateVideos(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                            </div>
                        </div>
            
                        <div class="popup_content" data-ng-show="catgridCtrl.isDeactivateBulkRecord">
                            <span class="conformation_txt">
                                {{__('base::gridlist.bulk_deactivate_confirm')}}
                            </span>
                            <div class="popup_btns text-center">
                                <a class="pop_cancel_btn" data-ng-click="catgridCtrl.cancelDeleteVideos()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                                <a data-ng-click="catgridCtrl.confirmActivateOrDeactivateVideos(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                            </div>
                        </div>
                    </div>      
                </div>
</div>
@endsection
@section('scripts')
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/presets/presetGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
@endsection