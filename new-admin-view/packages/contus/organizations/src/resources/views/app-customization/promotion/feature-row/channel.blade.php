@include('organizations::app-customization.promotion.feature-row.platform')

<!-- channels -->
<div class="form-group row" style="margin-bottom: 15px;">
    <label for="prefix" class="col-sm-10 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
        Channels<span class="required">*</span>:
    </label>
    <label>Show In Liv Mode</label>
    <label class="switch" style="margin: 10px 0px 10px 16px;">
        <input type="checkbox" ng-model="fturow.show_in_live" name="show_in_live" ng-checked="record.content_sets.show_in_live == 1"
            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        <span class="slider round"></span>
    </label>

    <div class="channel-wrapper">
        <div class="channel-card" ng-repeat="bundle in record.content_sets.channels" data-id="@{{bundle.id}}">
            <div class="card-header">
                <span class="drag-handle">⋮⋮</span>
                <span class="status" ng-if="bundle.is_active == 1">Active</span>
                <span class="danger" ng-if="bundle.is_active == 0">Inactive</span>
            </div>
            <div class="card-body">
                <img src="@{{ bundle.cover_image }}" alt="@{{bundle.name}}">
            </div>
            <div class="card-footer">
                <div>
                    <label style="font-size: 14px; color: #000; margin-top: 10px;">@{{bundle.id}}</label>
                    <label>@{{bundle.name}}</label>
                </div>
                <span class="remove-btn" ng-click="fturCtrl.removeChannelBundle(bundle)">−</span>
            </div>
        </div>



        <div class="add-card" data-toggle="modal" data-target="#assigned-content"
            ng-click="fturCtrl.openChannelModal(record)">
            + Add Channels
        </div>
    </div>
</div>