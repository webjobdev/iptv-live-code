<div id="latest_video">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', [
                        'access_type' => 'vod_categories',
                    ])
                    <!-- <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{ field.heading.name }}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                            data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th> -->
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Categories</th>
                    <th>Sub Categories</th>
                    <th>Organizations</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" id="category-search-input" ng-model="searchCategory"
                            ng-change="onCategorySearch($event)" placeholder="Enter Category Name"
                            data-boot-tooltip="true" title="{{ trans('video::categories.enter_category_name') }}">
                    </td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="10" class="no-data center">{{ trans('base::general.not_found') }}
                    </td>
                </tr>

                <tr data-ng-if="showRecords" data-ng-repeat-start="record in records track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{ record.id }}"
                                ng-click="vodcatgridCtrl.selectRecord($event, record.id)" value="@{{ record.id }}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>
                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}
                    </td>
                    <td>
                        <a data-toggle="collapse" data-parent="#categoryAccordion"
                            href="#vodCategory_@{{ record.id }}" aria-expanded="false"
                            data-ng-click="vodcatgridCtrl.ctgrydropdown(record)">
                            @{{ record.vod_categorie_name }}
                        </a>
                    </td>
                    <td>
                        @{{ record.categories.length }}
                    </td>
                    <td>
                        @{{ countSubCategories(record) }}
                    </td>
                    <td>
                        <span title="@{{ getFormattedOrgNames(record.get_organization) }}" style="cursor: pointer;">
                            <span data-ng-repeat="org in record.get_organization | limitTo: 3">
                                @{{ org.organization_name }}<span data-ng-if="!$last">, </span>
                            </span>
                            <span data-ng-if="record.get_organization.length > 3">...</span>
                        </span>
                    </td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">
                            <div data-ng-if="checkAccess('vod_categories.edit')"
                                class="column edit_table_icon tooltip-parent">
                                <button data-ng-click="vodcatgridCtrl.editCategory(record)">
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

                            <div class="tooltip-parent" data-ng-if="checkAccess('vod_categories.delete')">
                                <span ng-mouseover="getTooltip($event)" class="delete_table_icon" data-toggle="modal"
                                    data-target="#deleteModal" data-ng-click="deleteSingleRecord(record.id)">
                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px" height="12px">
                                        <g>
                                            <path
                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                fill="#454545"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                            </div>


                            

                            <!-- permission denied -->
                            

                        </div>
                    </td>
                </tr>

                <tr data-ng-attr-id="vodCategory_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="8">
                        <!-- add category -->
                        <div class="left-side flexbox align-items-center" style="padding: 13px 0px 13px 0px;">
                            <a data-ng-if="checkAccess('vod_categories.create')" data-toggle="modal" href="#"
                                class="button button-blue" data-ng-click="openCategoryModal(record)">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <svg viewBox="0 0 18 18" width="18px" height="18px">
                                        <g>
                                            <path
                                                d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                                fill="#ffffff" />
                                        </g>
                                    </svg>&nbsp;&nbsp;&nbsp;
                                    <span>Add Category</span>
                                </div>
                            </a>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <!-- @include('audio::admin.common.bulkActionLayout', [
                                        'access_type' => 'vod_categories',
                                    ]) -->
                                    <th class="center">Category</th>
                                    <th class="center">Order</th>
                                    <th class="center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-ng-repeat-start="category in record.categories track by category.id">
                                    <td class="center">
                                        <a data-toggle="collapse" href="#SubCategories_@{{ category.id }}"
                                            aria-expanded="false"
                                            aria-controls="SubCategories_@{{ category.id }}">
                                            @{{ category.category_name || '-' }}
                                        </a>
                                    </td>

                                    <td class="center">
                                        @{{ category.category_order || '-' }}
                                    </td>

                                    <!-- table Action -->
                                    <td class="table-action">
                                        <div class="flexbox align-items-center justify-center">
                                            <!-- Edit Sub Category -->
                                            <div data-ng-if="checkAccess('vod_categories')"
                                                class="column edit_table_icon tooltip-parent"
                                                value="@{{ category.id }}">
                                                <button data-ng-click="vodcatgridCtrl.editSubCategory(category)">
                                                    <svg viewBox="0 0 12 11" x="0px" y="0px" width="12px"
                                                        height="11px">
                                                        <g>
                                                            <path
                                                                d="M 10.7871 0.7184 C 9.8401 -0.2303 8.3003 -0.2303 7.3535 0.7184 L 1.0581 7.0082 C 1.0085 7.0574 0.9783 7.1206 0.969 7.1885 L 0.5024 10.6415 C 0.4885 10.74 0.5237 10.8383 0.5916 10.9062 C 0.6504 10.9648 0.7322 10.9998 0.8145 10.9998 C 0.8284 10.9998 0.8423 10.9998 0.8564 10.9976 L 2.9377 10.7165 C 3.1111 10.693 3.2332 10.5337 3.2097 10.3604 C 3.186 10.1871 3.0269 10.0652 2.8535 10.0886 L 1.1846 10.3135 L 1.5103 7.9054 L 4.0464 10.4401 C 4.105 10.4985 4.1873 10.5337 4.269 10.5337 C 4.3511 10.5337 4.4333 10.5009 4.4919 10.4401 L 10.7871 4.1502 C 11.2463 3.6911 11.4998 3.0821 11.4998 2.4332 C 11.4998 1.7842 11.2463 1.1752 10.7871 0.7184 ZM 7.4753 1.4914 L 8.5325 2.5479 L 2.7876 8.2896 L 1.7307 7.2331 L 7.4753 1.4914 ZM 4.2712 9.77 L 3.2378 8.737 L 8.9822 2.9954 L 10.0159 4.0284 L 4.2712 9.77 ZM 10.4568 3.5763 L 7.9277 1.0486 C 8.2488 0.784 8.6497 0.6387 9.0715 0.6387 C 9.5518 0.6387 10.0022 0.8261 10.3418 1.1634 C 10.6816 1.5008 10.8669 1.9529 10.8669 2.4332 C 10.8669 2.8572 10.7214 3.2554 10.4568 3.5763 Z"
                                                                fill="#454545"></path>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <span class="tooltip_title">{{ trans('base::general.edit') }}</span>
                                            </div>

                                            <!-- Delete Sub Category -->
                                            <div class="tooltip-parent"
                                                data-ng-if="checkAccess('vod_categories.delete')">
                                                <span ng-mouseover="getTooltip($event)" class="delete_table_icon"
                                                    data-toggle="modal" data-target="#deleteModal"
                                                    data-ng-click="deleteSingleRecord(category)">
                                                    <svg viewBox="0 0 11 12" x="0px" y="0px" width="11px"
                                                        height="12px">
                                                        <g>
                                                            <path
                                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                                fill="#454545"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                            </div>

                                            <!-- Permission Denied -->
                                            

                                            <!-- Permission Denied -->
                                            

                                        </div>
                                    </td>
                                </tr>

                                <tr id="SubCategories_@{{ category.id }}" class="collapse" data-ng-repeat-end>
                                    <td colspan="4" class="p-0">
                                        <!-- add sub category -->
                                        <div class="left-side flexbox align-items-center" style="padding: 13px 0px;">
                                            <a data-ng-if="checkAccess('vod_categories.create')" href="#"
                                                class="button button-blue" data-toggle="modal"
                                                data-ng-click="SubCategoryModel(category)">
                                                <div
                                                    style="display: flex; justify-content: center; align-items: center;">
                                                    <svg viewBox="0 0 18 18" width="18px" height="18px">
                                                        <g>
                                                            <path
                                                                d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                                                fill="#ffffff" />
                                                        </g>
                                                    </svg>&nbsp;&nbsp;&nbsp;
                                                    <span>Add Sub Category</span>
                                                </div>
                                            </a>
                                        </div>

                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="center">Sub Category Name</th>
                                                    <th class="center">Order</th>
                                                    <th class="center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr data-ng-repeat="sub in category.get_sub_category track by sub.id">
                                                    <td class="center">
                                                        @{{ sub.category_name || '-' }}
                                                    </td>

                                                    <td class="center">
                                                        @{{ sub.category_order || '-' }}
                                                    </td>

                                                    <!-- <td class="center">1</td> -->
                                                    <td class="table-action">
                                                        <div class="flexbox align-items-center justify-center">
                                                            <!-- Delete Sub Category -->
                                                            <div class="tooltip-parent"
                                                                data-ng-if="checkAccess('vod_categories.delete')">
                                                                <span ng-mouseover="getTooltip($event)"
                                                                    class="delete_table_icon" data-toggle="modal"
                                                                    data-target="#deleteModal"
                                                                    data-ng-click="deleteSingleRecord(sub)">
                                                                    <svg viewBox="0 0 11 12" x="0px" y="0px"
                                                                        width="11px" height="12px">
                                                                        <g>
                                                                            <path
                                                                                d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z"
                                                                                fill="#454545"></path>
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                                <span
                                                                    class="tooltip_title">{{ trans('base::general.delete') }}</span>
                                                            </div>

                                                            <!-- Permission Denied -->
                                                            

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    @include('audio::admin.common.bulkActionModal')
    @include('base::layouts.pagination')
</div>



<!-- this code for add vod category  -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="categoriesForm" id="categoriesForm" method="POST" data-base-validator
            data-ng-submit="vodcatgridCtrl.categorySave($event, vodcatgridCtrl.category.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!vodcatgridCtrl.category.id">
                    Add New Vod Category
                </h5>
                <h5 data-ng-if="vodcatgridCtrl.category.id">
                    Edit Vod Category
                </h5>
            </div>
            <div class="sidepanel-scroll mCustomScrollbar" data-mcs-theme="dark">
                @include('base::partials.errors')

                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                    <label>
                        Name
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="vod_categorie_name" maxlength="255" class="form-control"
                            data-ng-model="vodcatgridCtrl.category.vod_categorie_name"
                            placeholder="Enter Category Name" value="{{ old('title') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.vod_categorie_name.has">@{{ errors.vod_categorie_name.message }}</p>
                </div>

                <!-- Organization Dropdown -->
                <div class="form-group">
                    <label>
                        {{ __('subscribers::index.organization.select_organization') }}
                        <span class="required">*</span>
                    </label>
                    <select multiple data-jquery="select2_custom_ddl" myValue="vodcatgridCtrl.category.organization"
                        myPlaceholder="Select organization" ng-init="vodcatgridCtrl.category.organization"
                        name="organization" class="admin_category_sub form-control select2_custom_ddl"
                        data-ng-model="vodcatgridCtrl.category.organization" style="width: 100%;"
                        ng-options="org.id as org.organization_name for org in vodcatgridCtrl.orgList">
                        <option value="">-- Select Organization --</option>
                    </select>
                    <p class="error-msg">@{{ errors.organization.message }}</p>
                </div>

            </div>
            <div class="bottom-button text-right flexbox align-items-center">

                <a class="save" data-ng-click="vodcatgridCtrl.closeCategoryEdit()">
                    {{ trans('base::general.cancel') }}
                </a>
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit"
                    class="publish-now"
                    data-ng-click="vodcatgridCtrl.dataSubmit($event,vodcatgridCtrl.category.id)" />
            </div>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 data-ng-if="!vodcatgridCtrl.category.id" id="exampleModalLabel"
                    style="font-size: 20px; font-weight: 700; color: #000;">
                    Add Category
                </h5>
                <h5 data-ng-if="vodcatgridCtrl.category.id" id="exampleModalLabel"
                    style="font-size: 20px; font-weight: 700; color: #000;">
                    Edit Category
                </h5>
            </div>

            <form method="post" data-base-validator
                data-ng-submit="vodcatgridCtrl.Savecategory($event, vodcatgridCtrl.category.id)"
                enctype="multipart/form-data">

                {!! csrf_field() !!}


                <div class="modal-body">
                    @include('base::partials.errors')
                    <div class="form-group" data-ng-class="{'has-error': errors.category_name.has}">
                        <label>
                            Category Name
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="text" name="category_name" maxlength="255" class="form-control"
                                data-ng-model="vodcatgridCtrl.category.category_name" placeholder="Enter Name">
                        </div>
                        <p class="error-msg" data-ng-show="errors.category_name.has">@{{ errors.category_name.message }}
                        </p>
                    </div>

                    <div class="form-group" data-ng-class="{'has-error': errors.category_order.has}">
                        <label>
                            Order
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="number" name="category_order" maxlength="255" class="form-control" s
                                data-ng-model="vodcatgridCtrl.category.category_order" placeholder="Add Order Value">
                        </div>
                        <p class="error-msg" data-ng-show="errors.category_order.has">@{{ errors.category_order.message }}
                        </p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="button button-blue">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sub Category Modal -->
<div class="modal fade" id="SubCategoryModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 20px; font-weight: 700; color: #000;"
                    id="exampleModalLabel">
                    Add Sub Category</h5>
            </div>

            <form method="post" data-base-validator
                data-ng-submit="vodcatgridCtrl.SaveSubctgry($event, vodcatgridCtrl.category.id)"
                enctype="multipart/form-data">

                {!! csrf_field() !!}

                <div class="modal-body">
                    @include('base::partials.errors')
                    <div class="form-group" data-ng-class="{'has-error': errors.category_name.has}">
                        <label>
                            Name
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="text" name="category_name" maxlength="255" class="form-control"
                                data-ng-model="vodcatgridCtrl.category.category_name" placeholder="Enter Name">
                        </div>
                        <p class="error-msg" data-ng-show="errors.category_name.has">@{{ errors.category_name.message }}</p>
                    </div>

                    <div class="form-group" data-ng-class="{'has-error': errors.category_order.has}">
                        <label>
                            Order
                            <span class="required">*</span>
                        </label>
                        <div class="form-input">
                            <input type="number" name="category_order" maxlength="255" class="form-control" s
                                data-ng-model="vodcatgridCtrl.category.category_order" placeholder="Add Order Value">

                        </div>
                        <p class="error-msg" data-ng-show="errors.category_order.has">@{{ errors.category_order.message }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="button button-blue">Save changes</button>
                </div>

            </form>
        </div>
    </div>
</div>
