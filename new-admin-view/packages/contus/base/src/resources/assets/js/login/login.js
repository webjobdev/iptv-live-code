'use strict';
var loginController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    this.authData = {};
    scope.errors = {};
    this.defineProperties = function (data) {
        baseValidator.setRules(data.info.rules);
    };
    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('login/info'), this.defineProperties, function () { });
    };
    this.fetchInfo();
    this.authenticate = function($event){
        if (baseValidator.validateAngularForm($event.target, scope)) {
            requestFactory.post(requestFactory.getUrl('auth/login'), this.authData, function (response) {
                requestFactory.setAccessToken(response.data.token);
                // Update login.js to save the full permission object if 'all' is present
                if (response.data.menu_permissions[0] && (response.data.menu_permissions[0].all === true || response.data.menu_permissions[0].all === 'true' || response.data.menu_permissions[0].all == 1 || response.data.menu_permissions[0].all === 'all')) {
                    requestFactory.setUserPermission(response.data.menu_permissions[0]);
                } else {
                    requestFactory.setUserPermission(response.data.menu_permissions[0].permissions);
                }
                $window.location = window.VPlay.route.viewURL+'admin/dashboard';              
            }, this.fillError);
        }  else {
            angular.forEach(scope.errors, function (eachmessage, key) {
                if (typeof eachmessage == 'object' && eachmessage.hasOwnProperty('message')) {
                scope.errors[key] = {
                    has: true,
                    message: eachmessage.message
                };
                }
            });
        }
    };
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
                            message: message
                        };
                    }
            });
        }
    };

}];
window.gridControllers = { loginController : loginController };
window.gridDirectives = {  baseValidator: validatorDirective };