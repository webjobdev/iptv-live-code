<select class="select2_custom_ddl" minimumresults="-1" data-jquery="select2_custom_ddl" data-ng-change="search()" data-ng-init="searchRecords.is_active = 'all'" data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
    <option value="all">{{trans('base::general.all')}}</option>
    <option value='1'>{{trans('audio::general.record_status.active')}}</option>
    <option value='0'>{{trans('audio::general.record_status.inactive')}}</option>
</select>