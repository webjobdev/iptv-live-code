@extends('base::layouts.default') 
@section('header')
    @include('base::layouts.headers.dashboard') 
@endsection 
@section('content')
<div data-ng-controller="countriesController as geofencingCtrl">
@include('base::partials.errors')
    <div class="page-heading flexbox align-items-center flex-wrap">
        <div class="left-side">
            <h4>{{trans('geofencing::geofencing.geo_fencing')}}</h4>
        </div>
    </div> 
    <div class="response-msg"></div>
    <div class="video-detail form-page profile-page">
        <form method='POST' name="geo-setting" data-base-validator data-ng-submit="geoSettingSave($event,'{{URL::previous()}}')"
                enctype="multipart/form-data">
            <div class="page-padding">
                <div class="geofence">
                    <div class="form-group">
                        <div class="ckbox ckbox-default">
                            <input type="radio"  class="geo-option" id="parent1" name="geo-setting" data-ng-model="geoType" class="parentCheckBox disabled" value="individual_allowed_countries">
                            <label>{{trans('geofencing::geofencing.enable_geofencing_option_for_each_video')}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="or">(or)</span>
                    </div>
                    <div class="form-group">
                        <div class="ckbox ckbox-default">
                            <input type="radio"  class="geo-option" name="geo-setting" data-ng-model="geoType"  class="parentCheckBox" value="all_countries">
                            <label>{{trans('geofencing::geofencing.allowed_countries_to_view_the_video')}}</label>
                        </div>

                        <div class="form-input tags-subtitles flexbox" data-ng-if="geoType != 'individual_allowed_countries'">
                            <div class="tags" data-ng-if="geoCountries == null || geoType === all_countries">
                                <ul>
                                    <li>
                                        <span>{{trans('geofencing::geofencing.all_countries')}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="tags" >
                                <ul>
                                    <li data-ng-show = "geoCountries.length > 0" data-ng-repeat="geoCountry in geoCountries">
                                        <span>@{{geoCountry.country_name}} </span>
                                    </li>
                                </ul>
                            </div>
                            <i data-ng-click="showCountries()"  data-ng-class="{'disabled': geoType == 'individual_allowed_countries'}" class="upload-subtitle flexbox align-items-center">
                                <svg viewBox="0 0 229.75 228.75">
                                    <path d="M113,264l-4,5L55,281l-3-3,12-53,5-5ZM258,119,214,75l16-16a23.542,23.542,0,0,1,33,0l12,12c9,8,9,23,0,32ZM129,249,85,204,201,88l44,44Z" transform="translate(-52 -52.25)" fill-rule="evenodd"/>
                                </svg>
                                <span>{{trans('geofencing::geofencing.modify')}}</span>
                            </i>
                        </div>
                    </div>
                    <div data-ng-if="geoType == 'all_countries' || geotype == 'global_allowed_countries'">
                    @include('geofencing::admin.allowed_countries', ['form_type' => 'allowed_countries', 'control' => 'geofencingCtrl'])
                    </div>
                   

                </div>
            </div>
            <div class="bottom-button text-right flexbox align-items-center">
                <a class="save">
                    {{trans('base::general.cancel')}}
                </a>
                <button class="publish-now">
                {{trans('base::general.submit')}}
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection
 @section('scripts')
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/commonGeofencing.js')}}"></script>
<script src="{{asset('adminview/assets/js/geofencing.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
