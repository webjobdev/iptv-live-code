<!-- announcement table code -->
<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'api_access'])
                    <th data-ng-repeat="field in heading" ng-class="">
                        @{{ field.name }}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                            data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>

            <tbody>

                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">From</label>
                            <input type="date" class="form-control" id="create_from_date_inpt"
                                data-ng-model="searchRecords.created_at_from" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>
                        <div class="">
                            <label style="color: black; margin: 4px 0px;">To</label>
                            <input type="date" class="form-control" id="create_to_date_inpt"
                                data-ng-model="searchRecords.created_at_to" data-ng-change="search()"
                                data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Enter Date">
                        </div>
                    </td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.created_by"
                            placeholder="Enter User Email" data-boot-tooltip="true" data-toggle="tooltip"
                            data-original-title="Enter User Email">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{ heading.length + 1 }}" class="no-data center">
                        {{ trans('base::general.not_found') }}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat-start="record in AncRecords track by $index"
                    class="list-repeat" data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{ record.id }}"
                                ng-click="selectRecord($event, record.id)" value="@{{ record.id }}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{ record.id }}"></label>
                        </div>
                    </td>

                    <td class="serial_number">@{{ ((currentPage - 1) * rowsPerPage) + $index + 1 }}</td>

                    <td class="">@{{ record.formatted_created_at }}</td>
                    <td class="">@{{ record.user[0].email }}</td>
                    <td class="">@{{ record.announcement_subscribers[0].subscriber.first_name + ' ' + record.announcement_subscribers[0].subscriber.last_name }}</td>
                    <td class="">@{{ record.subject }}</td>
                    <td class="" style="text-align: center">
                        <a data-toggle="collapse" data-parent="#ancMessageAccordion" href="#anc_@{{ record.id }}"
                            aria-expanded="false">
                            <div class="column edit_table_icon tooltip-parent">
                                <button>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28.027px"
                                        height="28.028px" viewBox="0 0 28.027 28.028"
                                        style="enable-background:new 0 0 28.027 28.028;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M17.146,13.426l10.77-5.383c-0.4-1.682-1.91-2.947-3.71-2.947H3.823c-1.799,0-3.311,1.265-3.71,2.947l10.769,5.383
                                                C12.502,14.237,15.526,14.237,17.146,13.426z" />
                                                <path
                                                    d="M17.717,14.565c-0.996,0.499-2.311,0.772-3.703,0.772s-2.707-0.274-3.703-0.772l-2.978-1.489L0,16.743v2.367
                                                c0,2.102,1.72,3.821,3.822,3.821h20.383c2.102,0,3.822-1.72,3.822-3.821v-2.367l-7.333-3.666L17.717,14.565z" />
                                                <polygon points="22.119,12.365 28.027,15.319 28.027,9.411 		" />
                                                <polygon points="0.001,9.41 0.001,15.319 5.909,12.365 		" />
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </a>
                    </td>
                </tr>

                <tr data-ng-attr-id="anc_@{{ record.id }}" class="collapse" data-ng-repeat-end>
                    <td colspan="8">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="center">Message</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="center">@{{ record.message }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- create announcement code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="announcementForm" id="announcementForm" method="POST" data-base-validator
            data-ng-submit="ancCtrl.saveAnnouncement($event)" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5>{{ trans('organizations::index.create_anc') }}</h5>
            </div>

            <input type="text" hidden id="org-id" name="id" value="{{ Request::url() }}">

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <!-- Announcement Subject -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.anc_subject') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="subject" data-unique="@{{ drmCtrl.uniqueRoute }}"
                            data-ng-model="ancCtrl.announcementData.subject" class="form-control"
                            placeholder="{{ trans('organizations::index.anc_subject_hldr') }}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.subject.has">@{{ errors.subject.message }}</p>
                </div>

                <!-- Announcement message -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.anc_message') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <textarea id="message" name="message" rows="4" cols="50"
                            data-ng-model="ancCtrl.announcementData.message" class="form-control"
                            placeholder="{{ trans('organizations::index.anc_message_hldr') }}"></textarea>
                    </div>
                    <p class="error-msg" data-ng-show="errors.message.has">@{{ errors.message.message }}</p>
                </div>

                <!-- Announcement Subscribers -->
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{ trans('organizations::index.anc_subscribers') }}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <select name="subscribers" id="subscriber-optns" data-jquery="select2_custom_ddl"
                            myPlaceholder=" Select Subscribers" ng-model="ancCtrl.announcementData.subscribers"
                            ng-options='rule.first_name + " " + rule.last_name for rule in ancCtrl.subsList'
                            class="form-control" multiple
                            style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                            <option value="">Select Subscribers</option>
                        </select>
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="ancCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <button type="submit" value="{{ trans('base::general.save') }}" name="submit" class="publish-now"
                    id="addAnnouncemntBtn" ng-disabled="ancCtrl.isSubmitting">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
