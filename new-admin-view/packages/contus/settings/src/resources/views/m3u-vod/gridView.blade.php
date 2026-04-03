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
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'settings'])
                    <!-- <th class="text-center">#</th> -->
                    <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                            data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <!-- <tr class="search_text">
                    <td></td>
                    <td></td>
                </tr> -->
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>

                    <td>
                        <div class="product_img flexbox align-items-center">
                            <a class="table-image-text flexbox align-items-center">
                                <div class="image" bg-image="@{{record.get_channel.poster_image.replaceAll('\\','/')}}"
                                    on-error-src="{{url('adminview/assets/images/default_image.png')}}">
                                </div>
                                <!-- <div class="product_description tooltip-parent "
                                    data-ng-class="{'failed': record.job_status == 'Error'||record.job_status == 'Canceled'||record.job_status == 'Error Recording'}">
                                    <p class="img_description">@{{ record.get_channel.channel_name}}</p>
                                </div> -->
                            </a>

                        </div>
                    </td>

                    <td class="center">@{{ record.get_vod.title || '-' }}</td>

                    <!-- <td class="">@{{ record.get_channel.epg_id || '-' }}</td> -->

                    <!-- <td class="">@{{ record.get_channel.streaming_url || '-' }}</td> -->
                </tr>
            </tbody>
        </table>
    </div>


    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- form code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="settingForm" id="settingForm" method="POST" data-base-validator
            data-ng-submit="m3uCtrl.save($event, m3uCtrl.setting.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <input type="hidden" id="subscriber-id" name="id">

            <script>
                document.getElementById('subscriber-id').value = window.location.pathname.split('/').pop();
            </script>


            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!m3uCtrl.setting.id">
                    Create M3U VOD
                </h5>
                <h5 data-ng-if="m3uCtrl.setting.id">
                    Edit M3U VOD
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group">
                    <label>Enter M3U Url<span class="required">*</span></label>
                    <div class="form-input">
                        <input type="text" name="m3u_url" data-unique="@{{m3uCtrl.uniqueRoute}}"
                            data-ng-model="m3uCtrl.setting.m3u_url" class="form-control" placeholder="Enter M3U Url" />
                    </div>
                </div>

            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="m3uCtrl.closesettingEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>