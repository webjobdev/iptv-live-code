'use strict';

var staticContentController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout',function(scope,rootScope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.static_content = {};
  this.selectedRecords = [];
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
   *  Function is used to add the latest news
   *  @param $event
   */ 
  this.addStaticContent = function ($event){
    scope.errors = {};
    this.static_content={};
    this.static_content.is_active = String(0);
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editStaticContent = function (records) {
    scope.errors = {};
    this.static_content.id = records.id;
    this.static_content.title = records.title;
    this.static_content.slug = records.slug;
    this.static_content.content = records.content;
    this.static_content.is_active = String(records.is_active);
  }

  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('messages')){
      angular.forEach(response.data.messages, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeStaticContentEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      requestFactory.toggleLoader();
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('static-content/info'),this.defineProperties,function(response){
        rootScope.redirectUnauthenticated(response);
      });
  };

  this.fetchInfo();
  
  this.deleteSingleRecord = function(id) {
    scope.deleteParams = [id];
    scope.videoConfirmationDeleteBox = true;
};

this.cancelDeleteVideos = function() {
    scope.videoConfirmationDeleteBox = false;
    scope.deleteParams = '';
};

this.confirmDeleteVideos = function(recordStatus) {
    if (scope.deleteParams.length > 0) {
      self.deleteRecords(scope.deleteParams, recordStatus);
      scope.videoConfirmationDeleteBox = false;
      scope.deleteParams = '';
    } else {
      scope.videoConfirmationDeleteBox = false;
      scope.deleteParams = '';
    }
  };

  this.deleteRecords = function(id, recordStatus) {
    scope.deleteParams = '';
    scope.showRecords = false;
    scope.gridLoadingBar = true;
    var deleteIdLength = id.length;

    scope.deleteRequest = requestFactory.post(requestFactory.getUrl('static-content/action'), angular.extend({}, {
      selectedCheckbox: id,
      recordStatus: recordStatus
    }, scope.requestParams), function(data) {
      requestFactory.setToaster('success', data.message);
      angular.element('#selectall').removeAttr('checked');
      if (scope.records.length - deleteIdLength > 0) {
        scope.getRecords(true);
      } else {
        scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
        scope.getRecords(true);
      }
    });
  };

  /**
   * Function to update status of a preset,collection,category and video
   *
   * @param object record
   * @return void
   */
  this.updateFooterMenu = function ( record ) {
    
      var footerMenu = record.is_footer_menu == 1 ? 0 : 1;
      requestFactory.post(requestFactory.getUrl('static-content/update-menu/'+record.id),{is_footer_menu:footerMenu},function() {
        record.is_footer_menu = footerMenu;
      },function(){

      });
  };
  this.statusChangeSingleRecord = function (record) {
    scope.statusParams = record;
    scope.ConfirmationStatusBox = true;      
   
};

this.confirmStatus = function () {
  if (scope.statusParams) {
      self.updateFooterMenu(scope.statusParams);
      scope.ConfirmationStatusBox = false;
      scope.statusParams = '';
      scope.getRecords(true);
  } else {
      scope.ConfirmationStatusBox = false;
      scope.deleteParams = '';
  }
};

this.statusChangeBulkRecord = function (record) {
  scope.statusParams = record;
  scope.ConfirmationStatusBox = true;      
 
};

this.hideOrshowBulkRecord = function ($isActivateOrDeactivate) {
      
  scope.activateParams = scope.selectedRecords;
  if ($isActivateOrDeactivate == 'show') {
      this.isDeleteBulkRecord = false;
      scope.isHideBulkRecord = false;
      scope.ConfirmationStatusBox = false;
      scope.isShowBulkRecord = true;
      
  } else if ($isActivateOrDeactivate == 'hide') {
      this.isDeleteBulkRecord = false;
      scope.isShowBulkRecord = false;
      scope.ConfirmationStatusBox = false;
      scope.isHideBulkRecord = true;
  }
}
scope.confirmBulkStatusFooterUpdate= function (is_status){
    if (is_status == 1) {
      this.isShowBulkRecord = false;
  } else if (is_status == 0) {
      this.isHideBulkRecord = false;
  }
  self.showOrHideRecords(scope.activateParams, is_status);
}

this.showOrHideRecords = function (id, is_status) {
  
  scope.activateParams = '';
  scope.showRecords = false;
  scope.gridLoadingBar = true;
  var activateIdLength = id.length;

  if (is_status == 1) {
      scope.deleteRequest = requestFactory.post(requestFactory.getUrl('static/bulkstatusfooter'), angular.extend({}, {
          selectedCheckbox: id,
          isStatus: 'show'
      }, scope.requestParams), function (data) {
        requestFactory.setToaster('success', data.message);
        requestFactory.getToaster();
          scope.selectedRecords = [];
          angular.element('#selectall').removeAttr('checked');
          if (scope.records.length - activateIdLength > 0) {
              scope.getRecords(true);
          } else {
              scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
              scope.getRecords(true);
          }
      });
  } else if (is_status == 0) {
      scope.deleteRequest = requestFactory.post(requestFactory.getUrl('static/bulkstatusfooter'), angular.extend({}, {
          selectedCheckbox: id,
          isStatus: 'hide'
      }, scope.requestParams), function (data) {
        requestFactory.setToaster('success', data.message);
        requestFactory.getToaster();
          scope.selectedRecords = [];
          angular.element('#selectall').removeAttr('checked');
          if (scope.records.length - activateIdLength > 0) {
              scope.getRecords(true);
          } else {
              scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
              scope.getRecords(true);
          }
      });
  }
  angular.element('#move_collection').removeAttr('data-toggle');
}

  /**
   * Function to update status of a preset,collection,category and video
   *
   * @param object record
   * @return void
   */
  this.updateFooterMenu = function ( record ) {
     
      var footerMenu = record.is_footer_menu == 1 ? 0 : 1;
      requestFactory.post(requestFactory.getUrl('static-content/update-menu/'+record.id),{is_footer_menu:footerMenu},function() {
        record.is_footer_menu = footerMenu;
      },function(){

      });
      // scope.updateStatus( record );
  };

  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
        scope.searchRecords.is_footer_menu = 'all';
    }
  });
}];

window.gridControllers = {staticContentController : staticContentController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});