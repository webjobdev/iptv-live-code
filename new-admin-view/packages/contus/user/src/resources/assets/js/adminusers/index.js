'use strict';

var UserController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout',function(scope,rootScope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.user = {};
  requestFactory.setThisArgument(this);
  requestFactory.getToaster();
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
  /**
   *  Function is used to add the user
   *  @param $event
   */ 
  this.addUser = function ($event){
    scope.errors = {};
    this.user={};
    this.user.name = '';
    this.user.email = '';
    this.user.phone = '';
    this.user.gender = '';
    this.user.is_active = true;
    this.user.user_group_id = String(1);
  }
   /**
   *  Function is used to edit the user
   *  
   *  @param records
   */ 
  this.editUser = function (records) {
    $(".sidepanel").addClass("in"); 
    scope.errors = {};
    this.user.id = records.id;
    this.user.name = records.name;
    this.user.email = records.email;
    this.user.phone = parseInt(records.phone);
    this.user.is_active = (records.is_active) ? true : false;
    this.user.user_group_id = String(records.user_group_id);
    this.user.gender = String(records.gender);
  }

  this.fillError = function(response){
    $('#loaderimg').hide();
   if(response.status == 422 && response.data.hasOwnProperty('message')){
      angular.forEach(response.data.message, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : requestFactory.capitalize(message[0])};
        }
      });
    }
  };

   /**
   *  Function is used to save the user
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      console.log(this.user);
      if(this.user.name != '' && this.user.email != '' && this.user.phone != '' && this.user.gender != '' ) {
        $('#loaderimg').show();
    }
      if (id) { 
        requestFactory.post(requestFactory.getUrl('users/edit/'+id),this.user,function(response){
          requestFactory.toggleLoader();
          scope.getRecords(true);
          requestFactory.setToaster('success', response.message);
          $('#loaderimg').hide();
          requestFactory.getToaster();
          this.closeUserEdit();
          $timeout(function(){
            self.user = {};
          },100);
        },this.fillError);
        
      } else {
        requestFactory.post(requestFactory.getUrl('users/add'),this.user,function(response){
          requestFactory.toggleLoader();
          scope.getRecords(true);
          requestFactory.setToaster('success', response.message);
          $('#loaderimg').hide();
          requestFactory.getToaster();
          this.closeUserEdit();
        },this.fillError);
      }
    }
  }
  /**
   * Function to close the sidebar which is used to edit user information.
   */
  this.closeUserEdit = function() {
    scope.gridSideFormClose();
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      this.allUserGroups = data.info.allUserGroups;
      requestFactory.toggleLoader();
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('users/info'),this.defineProperties,function(response){
        rootScope.redirectUnauthenticated(response);
      });
  };

  this.fetchInfo();

  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
    }
    setTimeout(function() {
      $("#fixTable").tableHeadFixer({"head": false, "right" : 1});
      sidebarMenuEffectsInit();
    },500);
  });
}];

window.gridControllers = {UserController : UserController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});