<div class="responsive-box" id="partner">
    <div class="header-section flexbox align-items-center flex-wrap">
        <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
            Partner Products
        </h3>
    </div>

    <div class="row">
        <div class="justify-content-center mx-auto filter-wrapper">
            <!-- Basic Partner Products -->
            <div class="content-section text-center">
                <h4>Basic Partner Products</h4>
                <div class="left-side flexbox align-items-center">
                    <button type="button" class="btn btn-info button button-blue" data-toggle="modal"
                        data-target="#prdct-modal" ng-click="subscrCtrl.partnerProductsContentDragDrop()">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <svg viewBox="0 0 18 18" width="18px" height="18px">
                                <g>
                                    <path
                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                        fill="#ffffff" />
                                </g>
                            </svg>&nbsp;&nbsp;&nbsp;
                            <span>Add Basic Partner Products</span>
                        </div>
                    </button>
                </div>
                <div style="margin-top: 10px;">
                    <div style="max-height: 200px; overflow-y: auto; padding: 5px;">
                        <div class="bundle-item" ng-repeat="bundle in pProductContentSet.pProductSet"
                            data-id="@{{ bundle.id }}" data-ng-model="pProductContentSet.pProductSet"
                            style="border: 1px solid #ccc; padding: 10px; margin-bottom: 5px; border-radius: 4px;">
                            <span class="bundle-title">@{{ bundle.product_name }}</span>
                            <span class="bundle-delete" ng-click="subscrCtrl.removeBundle(bundle, 'pProduct')"
                                style="float: right; color: #585353; cursor: pointer;">
                                <i class="glyphicon glyphicon-remove-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <p class="assigned-text" data-ng-if="pProductContentSet.pProductSet.length === 0">
                    No Basic Partner Products were assigned
                </p>

                <div id="prdct-modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="padding: 10px;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                                <h4 class="modal-title">Add Partner Products Content Sets</h4>
                                <p style="margin: 0; font-size: 13px;"> <i class="glyphicon glyphicon-info-sign"></i>
                                    Please select the Partner Product Content sets you want to
                                    move</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="padding: 10px 30px;">
                                    <h4>Available Partner Products</h4>
                                    <input type="text" id="searchPproductAvailable" class="form-control search-box"
                                        placeholder="Search Partner Product Content Set">
                                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                        <div id="availablePproductBundles">
                                            <div class="panel panel-default content-container data-list"
                                                draggable="true"
                                                data-ng-repeat="product in subscrCtrl.partnerProductList track by product.id"
                                                data-id="@{{ product.id }}" data-name="@{{ product.name }}"
                                                data-bundle="@{{ product | json }}" {{--
                                                style="border: 2px solid gray; padding: 10px 5px; display: flex; justify-content: space-between;"
                                                --}}>
                                                <div class="channel-info content-header"><i
                                                        class="glyphicon glyphicon-blackboard"></i>@{{
                                                    product.product_name }}
                                                </div>
                                                <span class="channel-drag"><i
                                                        class="glyphicon glyphicon-move"></i></span>
                                                {{-- <div class="item-box">Post event</div>F --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="padding: 10px 30px;">
                                    <h4>Assigned Partner Products</h4>
                                    <input type="text" id="searchPproductAdded" class="form-control search-box"
                                        placeholder="Search Channel Content Set">

                                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                        <div class="data-list"
                                            ng-repeat="pProduct in toShowAssignedBundles.assignPartnerProductBundles">
                                            <div class="tvs-info">
                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                @{{ pProduct.product_name }}
                                            </div>
                                            <div>
                                                <span class="channel-drag"><i
                                                        class="glyphicon glyphicon-move"></i></span>
                                                <span class="bundle-delete" ng-click="removePproductBundles(pProduct)"
                                                    style="float: right; color: #585353; cursor: pointer;">
                                                    <i class="glyphicon glyphicon-remove-circle"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="" id="addedPproductBundles" style="min-height: 145px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="assign-btns">
                                <button type="button" class="button button-blue" data-dismiss="modal"
                                    ng-click="assignedPartnrProductBundles()">Assign</button>
                                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Partner Products -->
                <div class="content-section text-center">
                    <h4>Extra Partner Products</h4>
                    <div class="left-side flexbox align-items-center">
                        <button type="button" class="btn btn-info button button-blue" data-toggle="modal"
                            data-target="#extra-prdct-modal"
                            ng-click="subscrCtrl.extraPartnerProductsContentDragDrop()">
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <svg viewBox="0 0 18 18" width="18px" height="18px">
                                    <g>
                                        <path
                                            d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                            fill="#ffffff" />
                                    </g>
                                </svg>&nbsp;&nbsp;&nbsp;
                                <span>Add Extra Partner Products</span>
                            </div>
                        </button>
                    </div>
                    {{-- <p class="assigned-text">
                        No Extra Partner Products were assigned
                    </p> --}}

                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><i class="glyphicon glyphicon-filter"></i> Partner Product Name
                                    </th>
                                    <th>Length</th>
                                    <th>Price</th>
                                    <th>By User</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    data-ng-repeat="product in extraPproductContentSet.pProductContentAddOns track by $index">
                                    <td>@{{ product.product_name }}</td>
                                    <td>@{{ product.product_length || '-' }}</td>
                                    <td>@{{ product.product_price || '-' }}</td>
                                    <td>@{{ product.by_user.name }}</td>
                                    <td>@{{ product.updated_at | date:'dd-MM-yyyy' }}</td>
                                    <td class="table-action">
                                        <div class="flexbox align-items-center justify-center">
                                            <!-- edit button -->
                                            <div class="column edit_table_icon tooltip-parent">
                                                <button data-ng-click="subscrCtrl.fetchPrtnrPrdctData(product.id)"
                                                    data-toggle="modal" data-target="#product-editon-modal">
                                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                        <g>
                                                            <path
                                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                fill="#454545"></path>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <span class="tooltip_title">{{ trans('base::general.edit') }}</span>
                                            </div>

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
                                                    <span
                                                        class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="text-center text-muted"
                                        ng-if="extraPproductContentSet.pProductContentAddOns.length === 0">
                                        No Partner Product Sets were found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="extra-prdct-modal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="padding: 10px;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                    <h4 class="modal-title">Add Extra Partner Products Content Sets</h4>
                                    <p style="margin: 0; font-size: 13px;"> <i
                                            class="glyphicon glyphicon-info-sign"></i> Please select the Partner
                                        Product
                                        Content sets you want to
                                        move</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" style="padding: 10px 30px;">
                                        <h4>Available Extra Partner Products</h4>
                                        <input type="text" id="searchExtraPproductAvailable"
                                            class="form-control search-box" placeholder="Search Channel Content Set">
                                        <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                            <div id="availableExtraPproductBundles">
                                                <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                                    <div id="availablePproductBundles">
                                                        <div class="panel panel-default content-container data-list"
                                                            draggable="true"
                                                            data-ng-repeat="product in subscrCtrl.partnerProductList track by product.id"
                                                            data-id="@{{ product.id }}" data-name="@{{ product.name }}"
                                                            data-bundle="@{{ product | json }}" {{--
                                                            style="border: 2px solid gray; padding: 10px 5px; display: flex; justify-content: space-between;"
                                                            --}}>
                                                            <div class="channel-info content-header"><i
                                                                    class="glyphicon glyphicon-blackboard"></i>@{{
                                                                product.product_name }}
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

                                    <div class="col-md-6" style="padding: 10px 30px;">
                                        <h4>Assigned Extra Partner Products</h4>
                                        <input type="text" id="searchExtraPproductAdded" class="form-control search-box"
                                            placeholder="Search Channel Content Set">

                                        <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                                            <div class="data-list"
                                                ng-repeat="pProduct in toShowAssignedBundles.assignPartnerProductAddOnsBundles">
                                                <div class="tvs-info">
                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                    @{{ pProduct.product_name }}
                                                </div>
                                                <div>
                                                    <span class="channel-drag"><i
                                                            class="glyphicon glyphicon-move"></i></span>
                                                    <span class="bundle-delete"
                                                        ng-click="removePproductAddOnsBundles(pProduct)"
                                                        style="float: right; color: #585353; cursor: pointer;">
                                                        <i class="glyphicon glyphicon-remove-circle"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="" id="addedExtraPproductBundles" style="min-height: 145px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="assign-btns">
                                    <button type="button" class="button button-blue" data-dismiss="modal"
                                        ng-click="assignedPartnrProductAddOnsBundles()">Assign</button>
                                    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- edit Partner Product content set add on --}}
                    <div id="product-editon-modal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <form name="prtnrProduct-edit-modal" id="prtnrProduct-edit-modal" method="POST"
                                data-base-validator enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <!-- Modal content-->
                                <div class="modal-content" style="padding: 10px;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                        <h4 class="modal-title">Edit Partner Product Content Add-Ons</h4>
                                    </div>

                                    <div class="row">
                                        <div class="contentpanel form-page" style="padding: 10px 30px;">
                                            <!-- Partner Product Name -->
                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                <label class="col-sm-2 control-label" for="product_name"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Name<span class="required">*</span>:
                                                </label>
                                                <div class="col-sm-8 m-auto">
                                                    <input type="text" class="form-control" name="product_name"
                                                        id="product_name" placeholder="Enter Name"
                                                        ng-model="extraPproductContentSet.prtnrProductModalData.product_name"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                                </div>
                                            </div>

                                            <!-- Partner Product Cover Image -->
                                            <!-- image code -->
                                            <div class="upload-cover-thumbnail flexbox form-group row"
                                                data-ng-class="{'has-error': errors.product_image.has}">
                                                <!-- Thumbnail image code -->
                                                {{-- <h4>product Image</h4> --}}
                                                <label class="col-sm-2 control-label" for="product_name"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Product Image<span class="required">*</span>:
                                                </label>
                                                <div class="thumbnail-image">
                                                    <div class="image-content">
                                                        <img ng-show="extraPproductContentSet.prtnrProductModalData.product_image.length > 0"
                                                            ng-class="{'active': extraPproductContentSet.prtnrProductModalData.product_image}"
                                                            class="uploaded_img uploaded_img_@{{ extraPproductContentSet.prtnrProductModalData.id }}"
                                                            alt=""
                                                            ng-src="@{{ extraPproductContentSet.prtnrProductModalData.product_image }}" />

                                                        <img ng-show="extraPproductContentSet.prtnrProductModalData.product_image.length == 0"
                                                            ng-class="{'active': extraPproductContentSet.prtnrProductModalData.product_image}"
                                                            class="uploaded_img uploaded_img_@{{ extraPproductContentSet.prtnrProductModalData.id }}"
                                                            alt="" ng-src="" />
                                                        <div class="fileupload fileupload-new"
                                                            data-provides="fileupload">
                                                            <div class="fileuploadbox">
                                                                <div class="input-append">
                                                                    <div class="overlay-content"
                                                                        data-ng-class="{'change-image': extraPproductContentSet.prtnrProductModalData.product_image.length > 0}">
                                                                        <svg class="upload_img_ic" viewBox="0 0 27 27"
                                                                            version="1.1" x="0px" y="0px" width="27px"
                                                                            height="27px">
                                                                            <g>
                                                                                <path opacity="0.702"
                                                                                    d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                                    fill="#ffffff"></path>
                                                                            </g>
                                                                        </svg>
                                                                        <div class="input">
                                                                            <div
                                                                                ng-hide="extraPproductContentSet.prtnrProductModalData.product_image.length">
                                                                                <span>Change Product Image</span>
                                                                            </div>
                                                                            <div ng-hide="!extraPproductContentSet.prtnrProductModalData.product_image.length"
                                                                                class="ng-hide flexbox align-items-center">
                                                                                <svg class="change_img_ic" x="0px"
                                                                                    y="0px" width="13" height="13"
                                                                                    viewBox="0 0 528.899 528.899">
                                                                                    <g>
                                                                                        <path d="
                                                        M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z
                                                        M518.113,63.177l-47.981-47.981
                                                        c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                                        C532.495,100.753,532.495,77.559,518.113,63.177z
                                                        M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                                        L27.473,390.597L0.3,512.69z" fill="#ffffff"></path>
                                                                                    </g>
                                                                                </svg>
                                                                                <span>Change Product Image</span>
                                                                            </div>
                                                                            <input type="file" class="uploadImg"
                                                                                name="image"
                                                                                data-video-index="@{{ extraPproductContentSet.prtnrProductModalData.id }}">
                                                                        </div>
                                                                        <p>(Upload a cover image with minimum dimension
                                                                            of 355x200)</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="error-msg" data-ng-show="errors.product_image.has">
                                                        @{{ errors.product_image.message }}</p>
                                                </div>
                                            </div>

                                            <!-- Partner Product Description -->
                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                <label class="col-sm-2 control-label" for="product_description"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Description<span class="required">*</span>:
                                                </label>
                                                <div class="col-sm-8 m-auto">
                                                    <input type="text" class="form-control" name="product_description"
                                                        id="product_description" placeholder="Enter Description"
                                                        ng-model="extraPproductContentSet.prtnrProductModalData.product_description"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                                </div>
                                            </div>

                                            <!-- Partner Product Product Id -->
                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                <label class="col-sm-2 control-label" for="product_id"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Product Id<span class="required">*</span>:
                                                </label>
                                                <div class="col-sm-8 m-auto">
                                                    <input type="text" class="form-control" name="product_id"
                                                        id="product_id" placeholder="Enter Product Id"
                                                        ng-model="extraPproductContentSet.prtnrProductModalData.product_id"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                                </div>
                                            </div>

                                            <!-- Partner Product Length -->
                                            <div class="form-group row" style="margin-bottom: 15px;">
                                                <label class="col-sm-2 control-label" for="product_length"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Length :
                                                </label>
                                                <div class="col-sm-8 m-auto">
                                                    <input type="text" class="form-control" name="product_length"
                                                        id="product_length" placeholder="Enter Length" {{--
                                                        ng-model="extraPproductContentSet.prtnrProductModalData.product_length"
                                                        --}} ng-model="subscriptionData.subs_length"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                                                </div>
                                            </div>

                                            {{-- Price --}}
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="product_price"
                                                    style="font-size: 14px; color: #000; margin-top: 10px;">
                                                    Price :
                                                </label>
                                                <div class="col-sm-8 m-auto input-group" style="max-width: 250px;">
                                                    <input type="text" class="form-control" name="product_price"
                                                        id="product_price" {{--
                                                        ng-model="extraPproductContentSet.prtnrProductModalData.product_price"
                                                        --}} ng-model="subscriptionData.subs_price" required
                                                        placeholder="Enter price"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px 0 0 20px; padding: 5px 10px;">
                                                    <span class="input-group-addon"
                                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-left: 0; border-radius: 0 20px 20px 0;">
                                                        @{{ subscriptionData.currency }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="assign-btns">
                                        <button type="button" class="button button-blue" id="partner-product-edit-modal"
                                            ng-click="subscrCtrl.editPartnerAddONsModalData(extraPproductContentSet.prtnrProductModalData)">Save</button>
                                        <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- photo open model code -->
<!-- Poster Modal -->
<div class="custom-modal modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="false">
    <div class="custom-modal-dialog img-cropper" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                {{ __('video::videos.crop_image') }}
            </div>
            <div class="custom-modal-body">
                <div class="loader-container">
                    <img src="{{ asset('adminview/assets/images/loader.gif') }}">
                </div>
                <p class="error_msg"></p>
                <div class="crop-body">
                    <div class="img-container">
                        <img id="image" src="" alt="Picture">
                    </div>
                    <div class="img-preview"></div>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <button type="button" class="popup-button grey-color"
                    data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                <button type="button" class="popup-button blue-color"
                    id="submit-image">{{ __('video::videos.submit') }}</button>
            </div>
        </div>
    </div>
</div>
