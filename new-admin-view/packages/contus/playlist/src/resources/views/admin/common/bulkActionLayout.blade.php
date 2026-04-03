<th class="bulkth" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">
    <div class="ckbox ckbox-default">
        <input type="checkbox" id="selectall" value="1" data-ng-click="selectBulkRecords()" />
        <label for="selectall" class="nopadding"></label>
    </div>
    <div class="dropdown bulkaction" style="float: left; right: 20px;" data-ng-show="selectedRecords != 0 && checkAccess('{{$access_type}}')"
        data-original-title="Select video in the grid to perform a bulk action">
        <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
            {{__('audio::general.bulk_action')}}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('delete-Popup')"
                    href="#">{{__('audio::general.delete')}}</a>
            </li>
            <li>
                <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('status-bulk-update','activate')"
                    href="#">{{__('audio::general.activate')}}</a>
            </li>
            <li>
                <a data-toggle="modal" data-target="#audioBulkActionModal" ng-click="confirmationPopupBulkAction('status-bulk-update','deactivate')"
                    href="#">{{__('audio::general.deactivate')}}</a>
            </li>
        </ul>
    </div>
</th>
@include('audio::admin.common.bulkActionModal')
