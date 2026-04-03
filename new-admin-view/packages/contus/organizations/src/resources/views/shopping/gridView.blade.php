<style>
    .record-heading,
    .record-data {
        text-align: center !important;
    }
</style>

<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div style=" display: flex; align-items: center;">
        <p class="mb-2" style="margin-bottom:10px;">
            Susbcription Management for inactive Subscribers.
            <i class="glyphicon glyphicon-question-sign"></i>
        </p>
        <div class="form-group row" style="margin-bottom: 15px; margin-left: 25px;">
            <label class="switch" style="margin: 10px 0px 10px 16px;">
                <input type="checkbox" ng-model="shoppincgCart.is_active" name="is_active"
                    style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                <span class="slider round"></span>
            </label>
        </div>
    </div>
    <br>

    <div style="margin-bottom: 20px;">
        <span style="font-size: larger;
    font-weight: bold;">Display Configuration</span> <br>
        <div class="col-sm-10" style="margin: 10px 0px; padding: 0px;">
            <label class="radio-inline me-3" style="vertical-align: unset;">
                <input type="radio" name="display_configrtn" value="without_subscription"
                    ng-model="shoppincgCart.display_configrtn" data-ng-click="shgCtrl.classRemove()" />
                Without Subscription
            </label>

            <label class="radio-inline me-3" style="vertical-align: unset;">
                <input type="radio" name="display_configrtn" value="with_subscription"
                    ng-model="shoppincgCart.display_configrtn" ng-click="shgCtrl.ContentDragDrop()">
                With Subscription
            </label>
        </div>
    </div>

    <div class="response-msg" style="margin-top: 40px;"></div>

    <div class="col-sm-12" id="table-div" style="display: flex;gap: 3%; padding: 0px;">
        <div class="col-sm-12" id="left-div" style="padding: 0px;">
            <div style="margin: 35px 0px;">
                <div style="margin-bottom: 20px;">
                    <span style="font-size: larger; font-weight: bold;">Subscription</span> <br>
                </div>
                <div style="margin-bottom: 20px;">
                    <button class="button button-blue" data-ng-click="shgCtrl.createSubscription($event)"><i
                            class="glyphicon glyphicon-plus"></i> Create
                        Subscription</button>
                    <br>
                </div>
            </div>

            <div data-ng-if="shoppincgCart.display_configrtn == 'with_subscription'"
                style="margin: 10px 10px; width: 50%;">
                <input type="text" id="searchAvailabel" class="form-control search-box" placeholder="Search Plans"
                    style="border: 1px solid gray; border-radius: 10px;">
            </div>
            <div class="table_responsive" id="fixTable_parent">
                <table class="table subscription-plan-grid" id="fixTable">
                    <thead>
                        <tr>
                            <th class="record-heading">Order</th>
                            <th class="record-heading">Image</th>
                            <th class="record-heading">Heading</th>
                            <th class="record-heading">Subscription Name</th>
                            <th class="record-heading">Length</th>
                            <th class="record-heading">Price</th>
                            <th class="record-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="left-table">
                        <tr data-ng-if="plansList.length === 0">
                            <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                                {{ trans('base::general.not_found') }}
                            </td>
                        </tr>
                        <tr ng-repeat="record in plansList | filter:{ organization_id: orgIdFromUrl } track by record.id"
                            class="availableRecords" data-id="@{{ record.id }}"
                            data-name="@{{ record.subscription_name }}" draggable="true">

                            <td class="record-data">@{{ $index + 1 }}</td>
                            <td class="record-data" style="display: flex; justify-content: center;">
                                <img src="{{ asset('adminview/assets/images/default_image.png') }}" height="45vh"
                                    width="45vw" alt="" style="border: 1px solid gray; border-radius: 50px;">
                            </td>
                            <td class="record-data">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 2%;">

                                    @{{ record.heading ? record.headings : 'Base' }}
                                    <svg version="1.1" id="svg2" xmlns:dc="http://purl.org/dc/elements/1.1/"
                                        xmlns:cc="http://creativecommons.org/ns#"
                                        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                        xmlns:svg="http://www.w3.org/2000/svg"
                                        xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                        xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                        sodipodi:docname="tag.svg" inkscape:version="0.48.4 r9939"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px" viewBox="0 0 1200 1200"
                                        enable-background="new 0 0 1200 1200" xml:space="preserve">
                                        <path id="path23081" inkscape:connector-curvature="0"
                                            d="M30.702,440.542V92.705C33.358,39.559,76.183,0.64,123.407,0h347.836 c55.938,3.476,122.726,25.407,158.596,65.89l514.859,573.089c32.341,40.942,33.256,97.599,0,131.778l-403,403 c-39.514,35.748-98.47,34.227-131.779,0L96.591,599.137C59.17,554.944,31.218,495.859,30.702,440.542z M181.635,239.808 c2.193,51.71,42.984,89.029,88.875,89.641c51.859-2.316,89.028-43.651,89.641-89.641c-2.283-51.883-43.829-88.273-89.641-88.875 C218.775,153.094,182.234,194.096,181.635,239.808L181.635,239.808z" />
                                    </svg>
                                </div>

                            </td>
                            <td class="record-data">@{{ record.subscription_name }}</td>
                            <td class="record-data">@{{ record.subscription_length }}</td>
                            <td class="record-data">
                                <div data-boot-tooltip="true" data-toggle="tooltip"
                                    data-original-title="@{{ record.subscription_devices }}">
                                    @{{ record.price }}
                                </div>
                            </td>

                            <!-- table action -->
                            <td class="table-action">
                                <div class="flexbox align-items-center justify-center">

                                    <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                        <label class="switch">
                                            <input type="checkbox" ng-checked="record.status == 1"
                                                ng-click="toggleStatus(record)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="column edit_table_icon tooltip-parent">
                                        <a class="table_action" ng-click="shgCtrl.editData(record)">

                                            <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                <g>
                                                    <path
                                                        d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                        fill="#454545">
                                                    </path>
                                                </g>
                                            </svg>
                                        </a>
                                        <span class="tooltip_title">Edit Plan</span>
                                    </div>

                                    <div class="tooltip-parent">
                                        <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                            data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)"
                                            class="tooltips delete_table_icon" data-boot-tooltip="true"
                                            data-original-title="">
                                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g data-original-title="" title="">
                                                    <path
                                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span class="tooltip_title">Delete Plan</span>
                                        </span>
                                    </div>

                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            @include('audio::admin.common.singleRecordDeleteModal')
            @include('audio::admin.common.singleRecordStatusUpdateModal')
            @include('base::layouts.pagination')
        </div>

        <div class="col-sm-6" data-ng-if="shoppincgCart.display_configrtn == 'with_subscription'" style="padding: 0px;">
            <div style="margin: 35px 0px;">
                <div style="margin-bottom: 20px;">
                    <span style="font-size: larger; font-weight: bold;">Subscription Plans</span> <br>
                </div>
                <div style="margin-bottom: 20px;">
                    <button class="button button-blue" data-toggle="modal" data-target="#add-modal"><i
                            class="glyphicon glyphicon-plus"></i> Add Plans</button>
                    <br>
                </div>
            </div>

            <div style="margin: 10px 10px; width: 50%;">
                <input type="text" id="searchAdded" class="form-control search-box" placeholder="Search Plans"
                    style="border: 1px solid gray; border-radius: 10px;">
            </div>
            <div class="table_responsive">
                <table class="table subscription-plan-grid" id="fixTable2" data-ng-class="{'no-records': noRecords}">
                    <thead>
                        <tr>
                            <th class="record-heading">Order</th>
                            <th class="record-heading">Image</th>
                            <th class="record-heading">Heading</th>
                            <th class="record-heading">Subscription Name</th>
                            <th class="record-heading">Length</th>
                            <th class="record-heading">Price</th>
                            <th class="record-heading" style="text-align: center;">Actions</th>
                            <th class="record-heading"></th>
                        </tr>
                    </thead>

                    <tbody id="right-table">
                        <tr data-ng-if="noRecords || recordsRight.length === 0" id="no-record">
                            <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                                {{-- {{ trans('base::general.not_found') }} --}}
                                ---------- DROP HERE ----------</td>
                        </tr>
                        <tr ng-repeat="record in recordsRight track by record.id" class="addedRecords" draggable="true">

                            <td class="record-data">@{{ $index + 1 }}</td>
                            <td class="record-data" style="display: flex; justify-content: center;"><img
                                    src="{{ asset('adminview/assets/images/default_image.png') }}" height="45vh"
                                    width="45vw" alt="" style="border: 1px solid gray; border-radius: 50px;" /></td>
                            <td class="record-data">@{{ record.heading ? record.headings : 'Base' }}
                                <svg version="1.1" id="svg2" xmlns:dc="http://purl.org/dc/elements/1.1/"
                                    xmlns:cc="http://creativecommons.org/ns#"
                                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                    xmlns:svg="http://www.w3.org/2000/svg"
                                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                    sodipodi:docname="tag.svg" inkscape:version="0.48.4 r9939"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="20px" height="20px" viewBox="0 0 1200 1200"
                                    enable-background="new 0 0 1200 1200" xml:space="preserve">
                                    <path id="path23081" inkscape:connector-curvature="0"
                                        d="M30.702,440.542V92.705C33.358,39.559,76.183,0.64,123.407,0h347.836 c55.938,3.476,122.726,25.407,158.596,65.89l514.859,573.089c32.341,40.942,33.256,97.599,0,131.778l-403,403 c-39.514,35.748-98.47,34.227-131.779,0L96.591,599.137C59.17,554.944,31.218,495.859,30.702,440.542z M181.635,239.808 c2.193,51.71,42.984,89.029,88.875,89.641c51.859-2.316,89.028-43.651,89.641-89.641c-2.283-51.883-43.829-88.273-89.641-88.875 C218.775,153.094,182.234,194.096,181.635,239.808L181.635,239.808z" />
                                </svg>
                            </td>
                            <td class="record-data">@{{ record.plan_name ? record.plan_name : '0' }}</td>
                            <td class="record-data">@{{ record.plan_length ? record.plan_length : '0' }}</td>
                            <td class="record-data">@{{ record.price ? record.price : '0' }}</td>




                            <!-- table action -->
                            <td class="table-action">
                                <div class="flexbox align-items-center justify-center">

                                    <div class="form-group row" style="margin-bottom: 0px; margin-right: 5px;">
                                        <label class="switch">
                                            <input type="checkbox" ng-checked="record.status == 1"
                                                ng-click="toggleCustomPlanStatus(record)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="column edit_table_icon tooltip-parent">
                                        <a class="table_action" ng-click="editCustomPlan($event, record)">

                                            <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px" height="11px">
                                                <g>
                                                    <path
                                                        d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                        fill="#454545">
                                                    </path>
                                                </g>
                                            </svg>
                                        </a>
                                        <span class="tooltip_title">Edit Plan</span>
                                    </div>

                                    <div class="tooltip-parent">
                                        <span ng-mouseover="getTooltip($event)" data-toggle="modal"
                                            ng-click="removeCustomPlan($event, record)"
                                            class="tooltips delete_table_icon" data-boot-tooltip="true"
                                            data-original-title="">
                                            <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                                <g data-original-title="" title="">
                                                    <path
                                                        d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                        fill="#454545">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span class="tooltip_title">Delete Plan</span>
                                        </span>
                                    </div>

                                </div>
                            </td>

                            <td data-ng-if="!recordsRight.length > 0">
                                <button class="btn btn-xs btn-danger" ng-click="removeFromRight($index)">
                                    <span class="glyphicon glyphicon-minus"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @include('audio::admin.common.singleRecordDeleteModal')
            @include('audio::admin.common.singleRecordStatusUpdateModal')
            @include('base::layouts.pagination')
        </div>
    </div>

    <div class="assign-btns"
        style="display: flex; align-items: center; justify-content: flex-end; gap: 1.5%;padding: 10px 10px;">
        <button class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="button button-blue" data-dismiss="modal"
            ng-click="updateTableRecords($event)">Update</button>
    </div>
</div>

{{-- add custom subscription plan --}}
<div id="add-modal" class="modal fade contentpanel form-page" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h1 class="modal-title" style="font-size: 18px; font-weight: bold; text-align: center;">Add
                    Subscription Plan</h1>
                {{-- <p style="margin: 0; font-size: 13px;">
                    <i class="glyphicon glyphicon-info-sign"></i> Please select the Channel Content sets you want
                    to
                    move
                </p> --}}
            </div>

            <div class="row">
                <div style="padding: 30px 30px;">
                    <!-- Plan Name -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" for="plan_name"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Name<span class="required">*</span>:
                        </label>
                        <div class="col-sm-8 m-auto">
                            <input type="text" class="form-control" name="plan_name" id="plan_name"
                                placeholder="Enter Name" ng-model="shoppincgCart.plan_name"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                        </div>
                    </div>

                    <!-- Plan Description -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" for="plan_desc"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Description<span class="required">*</span>:
                        </label>
                        <div class="col-sm-8 m-auto">
                            <input type="text" class="form-control" name="plan_desc" id="plan_desc"
                                placeholder="Enter Description" ng-model="shoppincgCart.plan_desc"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                        </div>
                    </div>

                    <!-- Partner Product Cover Image -->
                    <!-- image code -->
                    <div class="upload-cover-thumbnail flexbox form-group row"
                        data-ng-class="{'has-error': errors.cover_image.has}">
                        <!-- Thumbnail image code -->
                        <label class="col-sm-2 control-label" for="plan_name"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Cover Image<span class="required">*</span>:
                        </label>
                        <div class="thumbnail-image">
                            <div class="image-content">
                                <img ng-show="shoppincgCart.cover_image.length > 0"
                                    ng-class="{'active': shoppincgCart.cover_image}"
                                    class="uploaded_img uploaded_img_@{{ shoppincgCart.id }}" alt=""
                                    ng-src="@{{ shoppincgCart.cover_image }}" />

                                <img ng-show="shoppincgCart.cover_image.length == 0"
                                    ng-class="{'active': shoppincgCart.cover_image}"
                                    class="uploaded_img uploaded_img_@{{ shoppincgCart.id }}" alt="" ng-src="" />
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileuploadbox">
                                        <div class="input-append">
                                            <div class="overlay-content"
                                                data-ng-class="{'change-image': shoppincgCart.cover_image.length > 0}">
                                                <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px"
                                                    y="0px" width="27px" height="27px">
                                                    <g>
                                                        <path opacity="0.702"
                                                            d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                            fill="#ffffff"></path>
                                                    </g>
                                                </svg>
                                                <div class="input">
                                                    <div ng-hide="shoppincgCart.cover_image.length">
                                                        <span>Change Cover Image</span>
                                                    </div>
                                                    <div ng-hide="!shoppincgCart.cover_image.length"
                                                        class="ng-hide flexbox align-items-center">
                                                        <svg class="change_img_ic" x="0px" y="0px" width="13"
                                                            height="13" viewBox="0 0 528.899 528.899">
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
                                                        <span>Change Cover Image</span>
                                                    </div>
                                                    <input type="file" class="uploadImg" name="image"
                                                        data-video-index="@{{ shoppincgCart.id }}">
                                                </div>
                                                <p>(Upload a cover image with minimum dimension
                                                    of 355x200)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="error-msg" data-ng-show="errors.cover_image.has">
                                @{{ errors.cover_image.message }}</p>
                        </div>
                    </div>

                    <!-- Label -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" for="plan_label"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Label<span class="required">*</span>:
                        </label>
                        <div class="col-sm-8 m-auto">
                            {{-- <div class="form-group row" style="margin-bottom: 15px; margin-left: 25px;"> --}}
                                <label class="switch" style="margin: 10px 0px 10px 16px;">
                                    <input type="checkbox" ng-model="shoppincgCart.label" name="label"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                    <span class="slider round"></span>
                                </label>
                                {{--
                            </div> --}}
                        </div>
                    </div>

                    <!-- Plan Additional Info -->
                    <div class="form-group row" style="margin-bottom: 15px;">
                        <label class="col-sm-2 control-label" for="additional_info"
                            style="font-size: 14px; color: #000; margin-top: 10px;">
                            Additional Info<span class="required">*</span>:
                        </label>
                        <div class="col-sm-8 m-auto">
                            <input type="text" class="form-control" name="additional_info" id="additional_info"
                                placeholder="Enter Description" ng-model="shoppincgCart.additional_info"
                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;margin: 0px 10px;">
                        </div>
                    </div>

                </div>
            </div>
            <div class="assign-btns"
                style="display: flex; align-items: center; justify-content: flex-end; gap: 1.5%;padding: 10px 10px;">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="button button-blue" data-ng-if="!isEditMode == true" data-dismiss="modal"
                    ng-click="addCustomPlan($event)">Save</button>
                <button type="button" class="button button-blue" data-ng-if="isEditMode == true" data-dismiss="modal"
                    ng-click="updateCustomPlan($event)">Update</button>
            </div>
        </div>
    </div>
</div>