'use strict';

var contactusController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout',function(scope,rootScope,requestFactory,$window,$sce,$timeout){
  
  this.contactus = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
    
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
   requestFactory.get(requestFactory.getUrl('contactus/info'),this.defineProperties,function(){
    rootScope.redirectUnauthenticated(response);
   });
   $timeout(function () {

    var mainPanel = document.getElementsByClassName('mainpanel');
    if(mainPanel.length > 0) {
      var mainContent = document.getElementsByClassName('mainpanel')[0];
      mainContent.className += ' show-content';
    }

    }, 500);
      
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
  });
}];

window.gridControllers = {contactusController : contactusController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});