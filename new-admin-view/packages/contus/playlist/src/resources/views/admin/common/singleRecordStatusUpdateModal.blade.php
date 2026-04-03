<div class="alert-popup modal fade" id="single-record-status-update-popup" data-ng-if="requestParams.grid != 'video'">             
    <div class="alert-popup-content">
        <div class="popup_head">
            <h3>{{__('base::gridlist.single_record_status_update_modal_title')}}</h3>
        </div>
        <div class="popup_content"> 
            <span class="conformation_txt">
                {{__('base::gridlist.single_record_status_update_modal_content')}}
            </span>
            <div class="popup_btns text-center">
                <a  data-ng-click="cancelDelete()" href="javascript:void(0)" class="pop_cancel_btn" id="pop_cancel_btn" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</a>
                <a data-ng-click="confirmSingleStatusUpdate()" href="javascript:void(0)" class="pop_confirm_btn" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</a>
            </div>
        </div>
    </div>
</div>