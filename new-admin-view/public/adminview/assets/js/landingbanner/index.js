'use strict';

var bannerController = ['$scope','$rootScope', 'flowFactory','requestFactory','$window','$sce','$timeout',function(scope,rootScope,flowFactory,requestFactory,$window,$sce,$timeout){
  
    var self = this;
  this.banner = {};
  scope.errors = {};
  this.banner_image='';
  this.mobile_image='';
  this.video_image='';
  scope.singlePreload = [];
  requestFactory.setThisArgument(this);
  requestFactory.getToaster();
  this.banner.is_image_updated = 0;
  this.banner.is_mobile_image_updated = 0;
    scope.singleConfig= {
      create: false,
      valueField: 'id',
      labelField: 'title',
      searchField: 'title',
      plugins: {
        'remove_button': {},
        'no_results': {'message': 'Video not available. You can upload the video in Manage Videos section.'}
      },
      placeholder: 'Search video by title and select...',
      preload: true,
      maxItems: 1,
      load: function(query, callback) {
          if (!query.length) return callback();
          $.ajax({
              url: $('meta[name="base-api-url"]').attr('content') + '/landingbanner/searchvideos?search=' + query,
              beforeSend: function(request){request.setRequestHeader('Authorization', 'Bearer '+requestFactory.access_token)},
              type: 'GET',
              dataType: 'json',
              data: {
                  name: query,
              },
              error: function() {
                  callback();
              },
              success: function(res) {
                  callback(res.response.search_videos.data);
              }
          });
      },
      onChange: function(value) {
      }
 };

scope.clearbannerImage = function(){
    self.banner.banner_image = "";
    self.banner.mobile_image = "";
}

  
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
    $(".sidepanel").addClass("in");
    self.resetBannerImageUpload();
    self.webUrl();
    self.mobileUrl();
    // $('img#banner-image-preview-new').empty();
    document.getElementById( 'banner-image-preview-new' ).style.display = 'none';

    scope.errors = {};
    // scope.existingFlowObject.cancel();
    scope.singlePreload = [];
    self.banner={};
    self.banner.is_active = String(0);
    self.banner.banner_image = '';
    self.banner.banner_url = '';
    self.banner.mobile_image = '';
    self.banner.mobile_url = '';

    $('#banner-image-progress').html('');
    $('#mobile-banner-image-progress').html('');
    angular.element('#selectall').prop('checked', false);
    angular.element('.checkbox').prop('checked', false);
    scope.selectedRecords = [];
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editStaticContent = function (records) {
    $(".sidepanel").addClass("in");
    $('#stat-0').empty();
    document.getElementById( 'banner-image-preview-new' ).style.display = 'block';

    self.resetBannerImageUpload();
    self.webUrl();
    self.mobileUrl();
    scope.errors = {};
    self.banner.id = records.id;
    self.banner.title = records.title;
    self.banner.banner_order = records.banner_order;
    self.banner.banner_url = records.banner_url;
    self.banner.mobile_url = records.mobile_url;
    self.banner.type = records.type;
    self.banner.extension = records.extension;
    self.banner.imageUrl = records.url;
    self.banner.category = records.category_title;
    self.banner.url = records.url;
    self.banner.is_active = String(records.is_active);
    self.banner.image = records.image;
    self.banner.banner_image = records.banner_image;
    self.banner.mobile_image = records.mobile_image;
    self.banner.is_image_updated = 0;
    self.banner.is_mobile_image_updated = 0;
    var temp_audio = {}; 
    temp_audio.id = records.videos.id; 
    temp_audio.title = records.videos.title; 
    scope.singlePreload.push(temp_audio);
    self.banner.video = records.video_id;
    angular.element('#selectall').prop('checked', false);
    angular.element('.checkbox').prop('checked', false);
    scope.selectedRecords = [];
  }

  this.fillError = function(response){
    $('#loaderimg').hide();
   if(response.status == 422 && response.data.hasOwnProperty('message')){
      angular.forEach(response.data.message, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
          // $('#loaderimg').hide();
        }
        // if(typeof message == 'object' && message.length > 0 && key == 'banner_image' && key == 'banner_image'){
        //   scope.errors['banner_url'] = {has : true , message : message[0]};
        //   scope.errors['mobile_url'] = {has : true , message : message[0]};
        // }
      });
    }
  };

  this.closeUserEdit = function () {
    console.log(this.banner);
    document.getElementById( 'stat-0' ).style.display = 'none';
    scope.gridSideFormClose();
    $('#stat-0').empty();
    self.resetBannerImageUpload();
    location.reload();
};

  
  /**
   *  Function is used to save the latestnews
   *  
   *  @param $event,id
   */
  this.dataSubmit = function(e, id) {
    this.save = function ($event,id) {
      console.log(this.banner);
      // $('#loaderimg').show();
      if(this.banner.banner_image != '' && this.banner.banner_url != '' && this.banner.mobile_image != '' && this.banner.mobile_url != '' ) {
        $('#loaderimg').show();
      }
        if (baseValidator.validateAngularForm($event.target,scope) && !scope.errors.hasOwnProperty('banner_url') && !scope.errors.hasOwnProperty('mobile_url')) {
            var Apiurl = (id) ? 'landingbanner/edit/' + id : 'landingbanner/add';

            requestFactory.post(requestFactory.getUrl(Apiurl),this.banner,function(response){
              scope.getRecords(true);
              requestFactory.setToaster('success', response.message);
              $('#loaderimg').hide();
              requestFactory.getToaster();
              this.closeStaticContentEdit();
              $timeout( function () {
                  self.banner = {};
                  location.reload();
              }, 1000 );
            }, this.fillError );
        }
      }
  }
  
  this.resetUploadArea = function(){
	  document.getElementById( "video_error" ).style.display = "none";        
      document.getElementsByClassName( "upload_file_input" ) [0].style.display = "block";
      document.getElementById( "upload_percentage" ).style.display = "none";
      document.getElementById( 'upload_title' ).style.display = 'none';
      document.getElementById( 'video_upload_button_wrap' ).style.display = 'none';      
      document.getElementById( 'progress-bar' ).style.width = '0%';
      document.getElementById( 'progress-bar-wrap' ).style.display = 'none';
	  
  }
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeStaticContentEdit = function() {
   
      scope.gridSideFormClose();
      this.resetUploadArea();
      $('#banner-image-progress').html('');
      $('#mobile-banner-image-progress').html('');
  };
  
  this.defineProperties = function(data) {
      requestFactory.toggleLoader();
      this.info = data.info;
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('landingbanner/info'),this.defineProperties,function(response){
        rootScope.redirectUnauthenticated(response);
      });
  };

  this.fetchInfo();

  this.webUrl = function() {
    $timeout(function(){
      window.BannerImageUploadHandler = new uploadHandler;
      window.BannerImageUploadHandler.initate({
          file: 'banner-image',
          previewer: 'banner-image-preview-new',
          deleteIcon: 'banner-image-delete',
          progress: 'banner-image-progress',
          resolution:'1500*730',
          beforeUpload: function () {
            self.banner.banner_url = undefined;
              if (!scope.$$phase) {
                  scope.$apply();
              }
          },
          afterUpload: function (response) {
              self.banner.banner_image = response.info;
              self.banner.module = 'banner-image';
              self.banner.is_image_updated = 1;
              if(scope.errors.hasOwnProperty('banner_image'))
                delete scope.errors['banner_image'];
          }
      });
    },1000);
  };

  this.mobileUrl = function() {
    $timeout(function(){
      window.BannerImageUploadHandler = new uploadHandler;
      window.BannerImageUploadHandler.initate({
          file: 'mobile-image',
          previewer: 'mobile-banner-image-preview-new',
          deleteIcon: 'mobile-banner-image-delete',
          progress: 'mobile-banner-image-progress',
          resolution:'540*960',
          beforeUpload: function () {
            self.banner.mobile_url = undefined;
              if (!scope.$$phase) {
                  scope.$apply();
              }
          },
          afterUpload: function (response) {
              self.banner.mobile_image = response.info;
              self.banner.module = 'mobile-image';
              self.banner.is_mobile_image_updated = 1;
              if(scope.errors.hasOwnProperty('mobile_image'))
                delete scope.errors['mobile_image'];
          }
      });
    },1000);
  };


    


    this.deleteBannerImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('landingbanner/delete-banner-image/' + this.banner.id), this.banner, function (response) {
            requestFactory.toggleLoader();
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            scope.getRecords(true);
            self.resetBannerImageUpload();
        }, function () {});
    };
    this.resetBannerImageUpload = function () {
        if (typeof window.BannerImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.banner.banner_image = '';
            self.banner.banner_url = '';
            self.banner.mobile_image = '';
            self.banner.mobile_url = '';
        }
    };

    
  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
    }
    if(scope.records[0].type && scope.records[0].banner_image )
        scope.records[0].banner_image = $sce.trustAsResourceUrl( scope.records[0].banner_image);
  });
}];
window.gridInitApp = angular.module('grid',['flow', 'ng-selectize']);
window.gridControllers = {bannerController : bannerController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});