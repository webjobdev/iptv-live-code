'use strict';
var GeofencingApp = angular.module('GeofencingApp', []);
var commonAPP = GeofencingApp;
GeofencingApp.directive('baseValidator', validatorDirective);
GeofencingApp.factory('requestFactory', requestFactory);
GeofencingApp.service('commonGeofencingService', commonGeofencing);
GeofencingApp.controller('countriesController', ['$scope', '$rootScope', 'requestFactory','commonGeofencingService',  function ($scope, $rootScope, requestFactory,commonGeofencingService) {
  var self = this;
  this.info = {};
  $scope.errors = {};
  requestFactory.setThisArgument(this);
  requestFactory.toggleLoader();
  requestFactory.getToaster();
    /**
     *  This function is to  get the selected 
     *  country and region details from the database.
     */
  this.defineGeoProperties = function (data) {
    commonGeofencingService.defineGeoProperties(data);
  };

  this.fetchGeoInfo = function () {
      requestFactory.get(requestFactory.getUrl('geofencing/info'), this.defineGeoProperties, function (response) {
        rootScope.redirectUnauthenticated(response);
      });
  };

  this.fetchGeoInfo();

    /**
     *  This function is to  get the countries 
     *  list from the database to display for user.
     */
  $scope.showCountries = function () {
    commonGeofencingService.getCountries();
  }  
    /**
     *  This function is to  get the selected 
     *  region details from the database to display under the region.
     */
  $scope.getRegions = function (geoCountry, index, videoID, $event){
    commonGeofencingService.getRegions(geoCountry, index, videoID, $event);
  };
    /**
     *  This function is to used to toggle the countries column
     *  and make it selected if it already saved previously.
     */
  $scope.toggleCountriesSelection = function(geoCountry){
    commonGeofencingService.toggleCountriesSelection(geoCountry);
  }
    /**
     *  This function is to used to toggle the region column
     *  and make the regions of that country selected
     *  if it already saved previously.
     */
  $scope.toggleRegionsSelection = function(geoCountry, geoRegions){
    commonGeofencingService.toggleRegionsSelection(geoCountry, geoRegions);
  }
    /**
     *  This function is to used to save the updated
     *  details of the user in the database
     */
  $scope.geoSettingSave = function() {
    if(($scope.geoType == 'all_countries') && (Object.keys($scope.allowedData).length > 0)) {
      $scope.geoType = 'global_allowed_countries';
    }
    requestFactory.post(requestFactory.getUrl('add-geo-settings'), {'geoType' : $scope.geoType, 'allowedData' : $scope.allowedData}, function (response) {
      requestFactory.setToaster('success', response.message); 
      window.location = requestFactory.getTemplateUrl('admin/geo-management'); 
    });
    
  }


}]);

    /**
    * Manually merging this controller with Common Controller for fetching     header data
    */
  if (angular.isObject(window.gridControllers)) {
      for (var controller in window.gridControllers) {
        if (angular.isArray(window.gridControllers[controller]) ||  angular.isFunction(window.gridControllers[controller])) {
          window.gridControllers[controller].hideHeader = true;
          GeofencingApp.controller(controller, window.gridControllers [controller]);
        }
      }
    }
  angular.element(document).ready(function () {
      angular.bootstrap(document, ['GeofencingApp']);
    });
    
    $(document).ready(function(){
      var loader = $('#preloader');
      loader.find('#status').css('display','none');
      loader.css('display','none');
    });