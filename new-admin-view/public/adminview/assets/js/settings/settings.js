'use strict';

var settingModule = angular.module('settingModule', ['angular.filter']);
var commonAPP = settingModule;

settingModule.directive('baseValidator', validatorDirective);

settingModule.factory('requestFactory', requestFactory);

settingModule.controller('settingController', ['$window', '$scope', '$rootScope', 'requestFactory', function(win, scope, $rootScope, requestFactory) {
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
    this.settingsData = {};
    this.activeTab = function(tab) {
        this.selectedTab = tab;

    }
    this.defineProperties = function(data) {
        baseValidator.setRules(data.data.rules);
        this.settingCategories = data.data.setting_categories;
        this.settingFields = data.data.setting_fields;
        this.optionalFields = data.data.optional_fields;
        this.getFieldValue(this.settingFields);
    };
    this.getFieldValue = function(settingFields) {
        angular.forEach(settingFields, function(val, key) {
            if (typeof val === 'object' && val.hasOwnProperty('category')) {
                self.getFieldValue(val.category, key);
            }
            if (typeof val === 'object' && val.hasOwnProperty('settings')) {
                self.getFieldValue(val.settings, key);
            }
            if (typeof val === 'object' && !val.hasOwnProperty('category') && !val.hasOwnProperty('settins')) {
                self.settingsData[val.setting_name] = val.setting_value;
            }
        });
    }
    this.fetchInfo = function() {
        requestFactory.get(requestFactory.getUrl('settings/info'), this.defineProperties, function() {});
    };
    this.fetchInfo();
    this.updateSettings = function($event) {
        // Prevent submission if form is invalid
        if ($event && $event.target && !$event.target.checkValidity()) {
            $event.preventDefault();
            return false;
        }

        // Clear previous errors
        scope.errors = {};

        // Check Angular form validity
        var form = scope.userForm;
        if (form && form.$invalid) {
            // Trigger validation display
            form.$setSubmitted();
            return false;
        }

        requestFactory.post(requestFactory.getUrl('settings/update'), this.settingsData, function(response) {
            // Check if response contains validation errors
            if (response.hasOwnProperty('message') && typeof response.message === 'object') {
                this.fillError({
                    data: response
                });
                return;
            }

            requestFactory.setToaster('success', response.message);
            window.location = window.VPlay.route.viewURL + 'admin/settings';
        }, this.fillError);
    }

    this.fillError = (response) => {
        if (response && response.status === 422 && response.data.errors) {
            angular.forEach(response.data.errors, function(messages, field) {
                if (Array.isArray(messages) && messages.length > 0) {
                    scope.errors[field] = {
                        has: true,
                        message: messages[0]
                    };
                }
            });
        } else if (response && response.data && response.data.message) {
            requestFactory.setToaster('error', response.data.message);
            requestFactory.getToaster();
        } else {
            requestFactory.setToaster('error', 'Something went wrong.');
            requestFactory.getToaster();
        }

        scope.$applyAsync();
    };

    // this.fillError = function (response) {
    //   if (response.data.hasOwnProperty('message')) {
    //     angular.forEach(response.data.message, function (message, key) {
    //       if (scope.translationError == true) {
    //         scope.errors['trans_' + key] = {
    //           has: true,
    //           message: message
    //         };
    //       } else {
    //         scope.errors[key] = {
    //           has: true,
    //           message: message[0]
    //         };
    //       }
    //     });
    //   }
    // };
}]);
/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if (angular.isObject(window.gridControllers)) {
    for (var controller in window.gridControllers) {
        if (angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])) {
            window.gridControllers[controller].hideHeader = true;
            settingModule.controller(controller, window.gridControllers[controller]);
        }
    }
}

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function() {
    angular.bootstrap(document, ['settingModule']);
});