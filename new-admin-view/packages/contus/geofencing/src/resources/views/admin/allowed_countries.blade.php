<div class="geofence-lists" data-ng-if="showCountryTab">
    <ul class="geofence-lists-ul">
        <li  class="geofence-lists-li" id="geoid-@{{$index}}" data-ng-repeat="geo_country in geo_countries track by $index">
            <div class="head flexbox align-items-center">
                <div class="ckbox ckbox-default">
                    <input type="hidden" id="country_id" value="@{{geo_country.country_id}}">
                    <input type="checkbox" id="parent1" name="geo-country" data-ng-model="geo_country.Selected" data-ng-click="toggleCountriesSelection(geo_country)" class="parentCheckBox disabled" value="@{{geo_country.short_code}}">
                    <label>@{{geo_country.country_name}}</label>
                </div>
                <div class="bg-overlay" data-ng-click="getRegions(geo_country,$index,null,$event)"></div>
                <svg viewBox="0 0 6 9" version="1.1" x="0px" y="0px" width="6px" height="9px" data-ng-click="getRegions(geo_country,$index,null,$event)">
                    <g>
                        <path d="M 0.7507 0.291 C 0.6434 0.3996 0.5897 0.5286 0.5897 0.6776 L 0.5897 8.3775 C 0.5897 8.5266 0.6434 8.6555 0.7507 8.7643 C 0.8583 8.8732 0.9853 8.9276 1.132 8.9276 C 1.2788 8.9276 1.4058 8.8732 1.5133 8.7643 L 5.31 4.9143 C 5.4172 4.8053 5.4712 4.6765 5.4712 4.5275 C 5.4712 4.3786 5.4172 4.2496 5.31 4.1409 L 1.5133 0.291 C 1.4058 0.1822 1.2788 0.1276 1.132 0.1276 C 0.9853 0.1276 0.8583 0.1822 0.7507 0.291 Z" fill="#000000"/>
                    </g>
                </svg>
            </div>
            <div class="content" id="content@{{$index}}">
                <ul class="geofence-lists-content-ul">
                    <li  data-ng-repeat="geo_region in geo_regions[geo_country.short_code]" data-ng-if= "geo_country.id == geo_region.country_id" class="geofence-lists-content-li" >
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" id="parent1" name="geo-region" data-ng-click="toggleRegionsSelection(geo_country, geo_region)" data-ng-model="geo_region.Selected" class="parentCheckBox disabled" value="@{{geo_region.short_code}}" ng-bind-html="geo_region.id">
                            <label>@{{geo_region.region_name}}</label>
                        </div>
                    </li>    
                </ul>    
            </div>    
        </li>
    </ul>
</div>

