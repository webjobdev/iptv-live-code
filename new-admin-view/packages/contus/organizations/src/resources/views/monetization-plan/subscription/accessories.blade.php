<div class="responsive-box" id="accessories">
    <div class="header-section flexbox align-items-center flex-wrap" style="margin-bottom: 10px;">
        <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
            Accessories
        </h3>
    </div>

    <div class="row">
        <div class="justify-content-center mx-auto filter-wrapper">
            <div class="left-side flexbox align-items-center">
                <button type="button" class="btn btn-info button button-blue" data-toggle="modal"
                    data-target="#accessories-modal" ng-click="subscrCtrl.accessoriesContentDragDrop()">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <svg viewBox="0 0 18 18" width="18px" height="18px">
                            <g>
                                <path
                                    d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                    fill="#ffffff" />
                            </g>
                        </svg>&nbsp;&nbsp;&nbsp;
                        <span>Add Accessories</span>
                    </div>
                </button>
            </div>

            <div class="table-responsive" style="margin-top: 10px;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><i class="glyphicon glyphicon-filter"></i>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>By User</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="acc in accessoriesContentSet.accessories track by $index">
                            <td>@{{ acc.accessories }}</td>
                            <td>@{{ acc.accessories_type }}</td>
                            <td>@{{ acc.price }}</td>
                            <td>@{{ acc.by_user.name }}</td>
                            <td class="table-action">
                                <div class="flexbox align-items-center justify-center">
                                    <!-- delete button -->
                                    <div class="tooltip-parent">
                                        <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                            data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                            class="tooltips delete_table_icon" data-boot-tooltip="true"
                                            data-original-title="">
                                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g data-original-title="" title="">
                                                    <path
                                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545"></path>
                                                </g>
                                            </svg>
                                            <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-center text-muted"
                                ng-if="accessoriesContentSet.accessories.length === 0">No Partner Product Sets
                                were
                                found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="accessories-modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="padding: 10px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                            <h4 class="modal-title">Add Accessories Content Sets</h4>
                            <p style="margin: 0; font-size: 13px;"> <i class="glyphicon glyphicon-info-sign"></i> Please
                                select the Accessories Content sets you want to
                                move</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6" style="padding: 10px 30px;">
                                <h4>Available Accessories</h4>
                                <input type="text" id="searchAccessoriesAvailable" class="form-control search-box"
                                    placeholder="Search Accessories Content Set">
                                <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                    <div id="availableAccessoriesBundles">
                                        <div class="panel panel-default content-container data-list" draggable="true"
                                            data-ng-repeat="acc in subscrCtrl.accessoriesList track by acc.id"
                                            data-id="@{{ acc.id }}" data-name="@{{ acc.name }}"
                                            data-bundle="@{{ acc | json }}" {{-- style="border: 2px solid gray; padding: 10px 5px; display: flex; justify-content: space-between;" --}}>
                                            <div class="channel-info content-header"><i
                                                    class="glyphicon glyphicon-blackboard"></i>@{{ acc.accessories }}
                                            </div>
                                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                            {{-- <div class="item-box">Post event</div>F --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding: 10px 30px;">
                                <h4>Assigned Accessories</h4>
                                <input type="text" id="searchAccessoriesAdded" class="form-control search-box"
                                    placeholder="Search Channel Content Set">

                                <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                    <div class="data-list"
                                        ng-repeat="accessory in toShowAssignedBundles.assignAccessoriesBundles">
                                        <div class="tvs-info">
                                            <i class="glyphicon glyphicon-blackboard"></i>
                                            @{{ accessory.accessories }}
                                        </div>
                                        <div>
                                            <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                            <span class="bundle-delete" ng-click="removeAccessoriesBundles(accessory)"
                                                style="float: right; color: #585353; cursor: pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="" id="addedAccessoriesBundles" style="min-height: 145px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="assign-btns">
                            <button type="button" class="button button-blue" data-dismiss="modal"
                                ng-click="assignedAccessoriesBundles()">Assign</button>
                            <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            {{--  edit Accessories content set add on --}}
            <div id="accessories-editon-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content" style="padding: 10px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                            <h4 class="modal-title">Edit Accessories Content Add-Ons</h4>
                            {{-- <p style="margin: 0; font-size: 13px;">
                        <i class="glyphicon glyphicon-info-sign"></i> Please select the Channel Content sets you want
                        to
                        move
                    </p> --}}
                        </div>

                        <div class="row">
                            <div style="padding: 10px 30px;">
                                <!-- Accessories Name -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" for="chnl_name"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Name<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-8 m-auto">
                                        <input type="text" class="form-control" name="chnl_name" id="chnl_name"
                                            placeholder="Enter Name" ng-model="subscriptionData.chnl_name"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                    </div>
                                </div>

                                <!-- Accessories Description -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" for="chnl_desc"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Description<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-8 m-auto">
                                        <input type="text" class="form-control" name="chnl_desc" id="chnl_desc"
                                            placeholder="Enter Description" ng-model="subscriptionData.chnl_desc"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                    </div>
                                </div>

                                <!-- Accessories Cover Image -->
                                <div class="form-group row" style="margin-bottom: 15px;">
                                    <label class="col-sm-2 control-label" for="chnl_cvr_img"
                                        style="font-size: 14px; color: #000; margin-top: 10px;">
                                        Cover Image<span class="required">*</span>:
                                    </label>
                                    <div class="col-sm-8 m-auto">
                                        <input type="text" class="form-control" name="chnl_cvr_img"
                                            id="chnl_cvr_img" placeholder="Cover Image"
                                            ng-model="subscriptionData.chnl_cvr_img"
                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                    </div>
                                </div>

                                <!-- Accessories Monetization Type -->
                                <div class="form-group row mb-4">
                                    <label class="col-sm-2 fw-bold col-form-label"
                                        style="font-size: 14px; color: #000;">
                                        Monetization Type:
                                    </label>

                                    <div class="col-sm-10">
                                        <div class="row">
                                            <!-- Buy -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 h-100">
                                                    <label class="checkbox-inline d-block mb-3">
                                                        <input type="checkbox" ng-model="vodset.monitization_type_buy"
                                                            value="1">
                                                        Buy
                                                    </label>

                                                    <div ng-if="vodset.monitization_type_buy">
                                                        <!-- payment method -->
                                                        <div class="form-group row mb-3" style="margin-bottom: 15px;">
                                                            <label class="col-sm-3 col-form-label fw-bold"
                                                                style="font-size:14px; color:#000;">
                                                                Payment Method:
                                                            </label>

                                                            <div class="col-sm-6">
                                                                <label class="radio-inline me-3"
                                                                    style="vertical-align: unset;">
                                                                    <input type="radio" class="form-check-input"
                                                                        id="bundleBuy" name="payment_method_buy"
                                                                        value="0"
                                                                        ng-model="vodset.payment_method_buy">
                                                                    Per Bundle
                                                                </label>

                                                                <label class="radio-inline me-3"
                                                                    style="vertical-align: unset;">
                                                                    <input type="radio" class="form-check-input"
                                                                        id="itemBuy" name="payment_method_buy"
                                                                        value="1"
                                                                        ng-model="vodset.payment_method_buy">
                                                                    Per Item
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Price -->
                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label class="col-sm-2 control-label"
                                                                style="font-size: 14px; color: #000; margin-top: 10px;">
                                                                Price<span class="required">*</span>:
                                                            </label>
                                                            <div class="col-sm-10 m-auto price-input-wrapper">
                                                                <input type="number" class="form-control price-input"
                                                                    name="price" placeholder="Enter vod price"
                                                                    ng-model="vodset.buy_price"
                                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                                <span
                                                                    class="currency-label">@{{ vodset.currency }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rent -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 h-100">
                                                    <label class="checkbox-inline d-block mb-3">
                                                        <input type="checkbox"
                                                            ng-model="vodset.monitization_type_rent" value="0">
                                                        Rent
                                                    </label>

                                                    <div ng-if="vodset.monitization_type_rent">
                                                        <!-- payment method -->
                                                        <div class="form-group row mb-3" style="margin-bottom: 15px;">
                                                            <label class="col-sm-3 col-form-label fw-bold"
                                                                style="font-size:14px; color:#000;">
                                                                Payment Method:
                                                            </label>

                                                            <div class="col-sm-6">
                                                                <label class="radio-inline me-3"
                                                                    style="vertical-align: unset;">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="payment_method_rent" value="0"
                                                                        ng-model="vodset.payment_method_rent"> Per
                                                                    Bundle
                                                                </label>

                                                                <label class="radio-inline me-3"
                                                                    style="vertical-align: unset;">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="payment_method_rent" value="1"
                                                                        ng-model="vodset.payment_method_rent"> Per Item
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Price -->
                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label class="col-sm-2 control-label"
                                                                style="font-size: 14px; color: #000; margin-top: 10px;">
                                                                Price<span class="required">*</span>:
                                                            </label>
                                                            <div class="col-sm-10 m-auto price-input-wrapper">
                                                                <input type="number" class="form-control price-input"
                                                                    name="price" placeholder="Enter vod price"
                                                                    ng-model="vodset.rent_price"
                                                                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                                <span
                                                                    class="currency-label">@{{ vodset.currency }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" style="margin-bottom: 15px;">
                                                            <label class="col-sm-2 control-label"
                                                                style="font-size: 14px; color: #000; margin-top: 10px;">
                                                                Period<span class="required">*</span>:
                                                            </label>
                                                            <div class="row align-items-center">
                                                                <!-- Period -->
                                                                <div class="col-md-4">
                                                                    <div
                                                                        class="price-input-wrapper d-flex align-items-center">
                                                                        <input type="number"
                                                                            class="form-control price-input"
                                                                            name="period" placeholder="Enter Period"
                                                                            ng-model="vodset.period"
                                                                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <select class="form-control select2_custom_ddl"
                                                                        data-jquery="select2_custom_ddl"
                                                                        myPlaceholder="Period Type"
                                                                        ng-model="vodset.period_type">
                                                                        <option value="">-- Select Schedule Base
                                                                            --
                                                                        </option>
                                                                        <option value="day">Day(s)</option>
                                                                        <option value="month">Month(s)</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assigned Live Events --}}
                                <div class="" style="padding: 10px 30px;">
                                    <h4>Assigned Channels</h4>
                                    <input type="text" id="searchChannelAddOnsAvailable"
                                        class="form-control search-box" placeholder="Search Channels">
                                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                        <div id="availableChannelAddOnsBundles">
                                            <div>
                                                <input type="checkbox" ng-model="channel.selected" ng-true-value="1"
                                                    ng-false-value="0">
                                            </div>
                                            <div class="content-container panel panel-default data-list"
                                                draggable="true"
                                                data-ng-repeat="channel in channlContentAddOnsSet.channelAddOnsId.channels track by channel.id"
                                                data-id="@{{ channel.id }}" data-name="@{{ channel.name }}"
                                                data-bundle="@{{ channel | json }}" {{-- style="border: 2px solid gray; padding: 10px 5px; display: flex; justify-content: space-between;" --}}>
                                                <div class="channel-info content-header"><i
                                                        class="glyphicon glyphicon-blackboard"></i>@{{ channel.name }}
                                                </div>
                                                <span class="channel-drag"><i
                                                        class="glyphicon glyphicon-move"></i></span>
                                                {{-- <div class="item-box">Post event</div>F --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="assign-btns">
                            <button type="button" class="button button-blue" data-dismiss="modal"
                                ng-click="channelGridCtrl.assignSelectedBundles()">Save</button>
                            <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
