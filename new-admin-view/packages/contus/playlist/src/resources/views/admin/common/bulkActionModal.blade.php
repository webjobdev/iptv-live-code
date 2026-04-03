<div class="alert-popup modal fade" id="audioBulkActionModal" data-role="dialog">
  <div class="alert-popup-content">
    <div class="popup_head">
      <h3>{{__('base::gridlist.bulk_action')}}</h3>
    </div>
    <div  class="popup_content" data-ng-show="gridBulkActionsFlag.isDeleteBulkRecord" >
      <span class="conformation_txt">
        {{__('base::gridlist.bulk_delete_confirm')}}
      </span>
      <div class="popup_btns text-center">
        <a class="pop_cancel_btn" data-ng-click="cancelDelete()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
        <a data-ng-click="confirmDelete()" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
      </div>
    </div>
    <div class="popup_content" data-ng-show="gridBulkActionsFlag.isActivateBulkRecord">
      <span class="conformation_txt">
        {{__('base::gridlist.bulk_activate_confirm')}}
      </span>
      <div class="popup_btns text-center">
        <a class="pop_cancel_btn" data-ng-click="cancelDelete()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
        <a data-ng-click="confirmBulkStatusUpdate(1)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
      </div>
    </div>
    <div class="popup_content" data-ng-show="gridBulkActionsFlag.isDeactivateBulkRecord">
      <span class="conformation_txt">
        {{__('base::gridlist.bulk_deactivate_confirm')}}
      </span>
      <div class="popup_btns text-center">
        <a class="pop_cancel_btn" data-ng-click="cancelDelete()" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
        <a data-ng-click="confirmBulkStatusUpdate(0)" class="pop_confirm_btn" data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
      </div>
    </div>
  </div>
</div>