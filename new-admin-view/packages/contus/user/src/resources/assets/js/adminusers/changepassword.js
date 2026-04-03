'use strict';

var changepassword = angular.module('changepassword',[]);
var commonAPP = changepassword;
changepassword.directive('baseValidator',validatorDirective);

changepassword.factory('requestFactory',requestFactory);

changepassword.controller('ChangePasswordController',['$window','$scope','$rootScope','requestFactory',function(win,scope,$rootScope,requestFactory){
    requestFactory.setThisArgument(this);
    var self = this;
    scope.errors = {};
    this.setpassword = {};
    scope.passwordError = {has : {}};
    requestFactory.getToaster();
    /**
     *  To get the changepassword rules
     *  
     */ 
    this.getChangePasswordRules = function() {
      requestFactory.get(requestFactory.getUrl('users/change-password-info'),function(response){
        baseValidator.setRules({
            old_password:"required|min:6",
            password : "required|different:old_password|confirmed|min:6",
            password_confirmation : "required|same:password|min:6",
            
        });
        requestFactory.toggleLoader();
        
      });
    }
    this.getChangePasswordRules();
    
    /**
     *  Functtion is used to fill the error
     *  
     */ 
    this.fillError = function(response) {
      if(response.status == 422 && response.data.hasOwnProperty('messages')) {         
        angular.forEach(response.data.messages, function(message,key) {
          if(typeof message == 'object' && message.length > 0){         
            scope.errors[key] = {has : true , message : message[0]};
          
          }
        });
      }
    };
    
    /**
     *  Functtion is used to save the new password
     *  
     */ 
    this.save = function($event){
      if (baseValidator.validateAngularForm($event.target,scope)) {
        scope.passwordError.has['Oldpassword'] = false;
        scope.passwordError.has['reenterpasswordsame'] = false;
        if (this.setpassword.password == this.setpassword.password_confirmation) {
          var isValidateOption = true;
          requestFactory.post(requestFactory.getUrl('users/changepassword'),this.setpassword,function(response){
            requestFactory.setToaster('success', response.message); 
            win.location = requestFactory.getTemplateUrl('admin/users/changepassword');
          },function(){scope.passwordError.has['Oldpassword'] = true; });
        } else {
          scope.passwordError.has['reenterpasswordsame'] = true; 
        }
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
          changepassword.controller(controller,window.gridControllers[controller]);
      }
  }
}

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['changepassword']);
});

$(document).ready(function(){
  var loader = $('#preloader');
  loader.find('#status').css('display','none');
  loader.css('display','none');
});