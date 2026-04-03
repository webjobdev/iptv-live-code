@include('organizations::app-customization.promotion.feature-row.platform')

<!-- movie -->
<div class="form-group row" style="margin-bottom: 15px;">
    <label for="prefix" class="col-sm-10 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
        Movie<span class="required">*</span>:
    </label>
    <label>Show In Liv Mode</label>
    <label class="switch" style="margin: 10px 0px 10px 16px;">
        <input type="checkbox" ng-model="fturow.show_in_live" name="show_in_live" ng-checked="record.content_sets.show_in_live == 1"
            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
        <span class="slider round"></span>
    </label>

    <div class="channel-wrapper">
        <div class="channel-card" ng-repeat="bundle in record.content_sets.vods" data-id="@{{bundle.id}}"
            ng-model="fturow.vodData">
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
                <span class="remove-btn" data-ng-click="fturCtrl.removeMovieBundle(bundle)">−</span>
            </div>
        </div>



        <div class="add-card" data-toggle="modal" data-target="#assigned-movie"
            ng-click="fturCtrl.openMovieModal(record)">
            + Add Channels
        </div>
    </div>
</div>

<style>
    .channel-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 8px 14px;
        margin-bottom: 8px;
        font-size: 14px;
        cursor: move;
        transition: box-shadow 0.2s ease;
    }

    .channel-item:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Left drag handle */
    .channel-drag {
        color: #999;
        margin-right: 10px;
        cursor: grab;
        flex-shrink: 0;
    }

    /* Channel name with icon */
    .channel-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 8px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 500;
        color: #333;
        /* justify-content: space-between; */
    }

    /* Action button (right side) */
    .channel-action {
        color: #666;
        cursor: pointer;
        flex-shrink: 0;
        font-size: 16px;
        transition: color 0.2s;
    }

    .channel-action:hover {
        color: #e74c3c;
    }

    /* Scrollable container */
    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }

    /* Drop area */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 8px;
    }

    .box-drop-zone {
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 8px;
    }

    .assign-btns {
        margin-top: 15px;
        text-align: center;
    }
</style>