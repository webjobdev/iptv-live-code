'use strict';

var transactionController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout',function(scope,rootScope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.transaction = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }

  $timeout( function () {
    $('#filter_created_at').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
   }, 1000 );

   $timeout( function () {
    $('#filter_end_date').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
   }, 1000 );
  
  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('messages')){
      angular.forEach(response.data.messages, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      requestFactory.toggleLoader();
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('transactions/info'),this.defineProperties,function(response){
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
        //scope.searchRecords.payment_method_id = 'all';
    }
    if(angular.isUndefined(scope.searchRecords.payment_method_id)){
      scope.searchRecords.payment_method_id = 'all';
  }

    setTimeout(function() {
      $("#fixTable").tableHeadFixer({"head": false, "right" : 1});
    },500);
  });
}];

window.gridControllers = {transactionController : transactionController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});