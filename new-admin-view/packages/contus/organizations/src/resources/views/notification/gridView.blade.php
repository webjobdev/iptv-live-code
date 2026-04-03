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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'subscription_all_write'])
                    <th class="center">Id</th>
                    <th></th>
                    <th class="center">Message</th>
                    <th></th>
                    <th class="center">Date - Time</th>
                </tr>
            </thead>

            <tbody>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords"
                    data-ng-repeat="announcement in announcment_info track by $index"
                    class="list-repeat"
                    data-intialize-sidebar="">

                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox"
                                class="checkbox"
                                id="roles_@{{announcement.id}}"
                                ng-click="selectRecord($event, announcement.id)"
                                value="@{{announcement.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{announcement.id}}"></label>
                        </div>
                    </td>

                    <td class="center">@{{announcement.user_id}}</td>
                    <td></td>
                    <td class="center">@{{announcement.announcement}}</td>
                    <td></td>
                    <td class="center">@{{ announcement.created_at | date:'dd-MM-yyyy h:mm a' }}</td>
                </tr>

            </tbody>
        </table>

        <!-- <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Toggle</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat-start="announcement in announcment_info">
                    <td>@{{ announcement.announcement }}</td>
                    <td>@{{ announcement.user_id }}</td>
                    <td><button class="btn btn-primary" ng-click="toggle(announcement)">Toggle</button></td>
                </tr>
                <tr ng-repeat-end ng-show="announcement.expanded">
                    <td colspan="3">
                        <strong>Details:</strong><br>
                        Joined: @{{ announcement.created_at }}<br>
                    </td>
                </tr>
            </tbody>
        </table> -->

    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>


<!-- To add or edit the lastest news  -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="subscriptionForm" id="subscriptionForm" method="POST" data-base-validator data-ng-submit="ancCtrl.save($event,ancCtrl.announcment.id)"
            enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!ancCtrl.announcment.id">{{trans('customer::subscription.add_new_subscription')}}</h5>
                <h5 data-ng-if="ancCtrl.announcment.id">{{trans('customer::subscription.edit_new_subscription')}}</h5>
                <div data-ng-if="ancCtrl.announcment.id" class="right-side">

                    <select minimumResults="-1" data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="ancCtrl.languageChange()" myValue="ancCtrl.subscribeTranslation.language" data-ng-model="ancCtrl.subscribeTranslation.language" data-ng-options="a.id as a.title  for a in ancCtrl.languages "></select>
                </div>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{trans('customer::subscription.subscription_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" data-unique="@{{ancCtrl.uniqueRoute}}" data-ng-model="ancCtrl.announcment.name"
                            class="form-control" placeholder="{{trans('customer::subscription.subscription_placeholder')}}" value="{{old('title')}}" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>

            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{trans('base::general.cancel')}}" data-ng-click="ancCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>