'use strict';

var settingModule = angular.module('settingModule',['angular.filter']);
var commonAPP = settingModule;

settingModule.directive('baseValidator',validatorDirective);

settingModule.factory('requestFactory',requestFactory);

settingModule.controller('settingController',['$window','$scope','$rootScope','requestFactory',function(win,scope,$rootScope,requestFactory){
    requestFactory.setThisArgument(this);
    requestFactory.toggleLoader();
    requestFactory.getToaster();
    var self = this;
    scope.errors = {};
    this.activetab = 1;
    this.settingCategories;
    this.settingFields;
    this.optionalFields;
    this.selectedTab = 'general-settings';
    this.fields;
    this.settingsData ={};
    this.activeTab=function(tab){
      this.selectedTab = tab;

    }
  this.defineProperties = function (data) {
    baseValidator.setRules(data.data.rules);
    this.settingCategories = data.data.setting_categories;
    this.settingFields = data.data.setting_fields;
    this.optionalFields = data.data.optional_fields;
    this.getFieldValue(this.settingFields);
  };
  this.getFieldValue = function (settingFields) {
    angular.forEach(settingFields, function(val, key){
      if(typeof val === 'object' && val.hasOwnProperty('category')){
        self.getFieldValue(val.category, key);
      }
      if(typeof val === 'object' && val.hasOwnProperty('settings')){
        self.getFieldValue(val.settings, key);
      }
      if(typeof val === 'object' && !val.hasOwnProperty('category') && !val.hasOwnProperty('settins')){
        self.settingsData[val.setting_name] = val.setting_value;
      }
    });
  }
  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('settings/info'), this.defineProperties, function () { });
  };
  this.fetchInfo();
  this.updateSettings = function ($event) {
      requestFactory.post(requestFactory.getUrl('settings/update'), this.settingsData, function (response) {
        requestFactory.setToaster('success', response.message);
        window.location = window.VPlay.route.viewURL+'admin/settings';              
      }, this.fillError);
  }
  this.fillError = function (response) {
    if (response.data.hasOwnProperty('message')) {
        angular.forEach(response.data.message, function (message, key) {
                if (scope.translationError == true) {
                    scope.errors['trans_' + key] = {
                        has: true,
                        message: message
                    };
                } else {
                    scope.errors[key] = {
                        has: true,
                        message:  message[0]
                    };
                }
        });
    }
  };
}]);
/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
  for(var controller in window.gridControllers){
      if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
          window.gridControllers[controller].hideHeader=true;
          settingModule.controller(controller,window.gridControllers[controller]);
      }
  }
}

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['settingModule']);
});
