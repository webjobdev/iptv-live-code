'use strict';
var forgotPwdController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    this.authData = {};
    scope.errors = {};
    this.showSuccess = false;
    this.showError = false;
    this.responseMessage;
    this.defineProperties = function (data) {
        baseValidator.setRules(data.info.rules);
    };
    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('forgot-password/info'), this.defineProperties, function () { });
    };
    this.fetchInfo();
    this.authenticate = function($event){
        $('#submit').prop('disabled', true);
        $("#submit").html('Processing...');
        if (baseValidator.validateAngularForm($event.target, scope)) {
            requestFactory.post(requestFactory.getUrl('auth/forgot-password'), this.authData, function (response) {
                self.showSuccess = true;
                self.showError = false;
                self.responseMessage = response.data;
                angular.element('.alert').fadeIn(1000).delay(5000).fadeOut(1000);
                $('#submit').prop('disabled', false);
                $("#submit").html('Submit');
                $window.location = window.VPlay.route.viewURL+'admin/auth/login';   
                self.authData.email = "";
            }, this.fillError);
        }  else {
            $('#submit').prop('disabled', false);
            $("#submit").html('Submit');
            self.authData.email = "";
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
        $('#submit').prop('disabled', false);
        $("#submit").html('Submit');
        self.authData.email = "";
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
window.gridControllers = { forgotPwdController : forgotPwdController };
window.gridDirectives = {  baseValidator: validatorDirective };