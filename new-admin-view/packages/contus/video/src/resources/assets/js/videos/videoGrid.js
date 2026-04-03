'use strict';

var VideoGridController = ['flowFactory', '$scope', 'requestFactory','$rootScope', '$window', '$sce', '$timeout', '$compile', '$interval', function (flowFactory, scope, requestFactory, rootScope, $window, $sce, $timeout, $compile, $interval) {
  var self = this;
  this.info = {};
  this.category = {};
  this.collection = {};
  this.allCollection = {};
  this.allSeasons = {};
  this.language = {};
  this.selectedRecords = [];
  // this.responseMessage = false;
  // this.showResponseMessage = false;
  scope.translationError = false;
  scope.errors = {};
  requestFactory.setThisArgument(this);
  this.showcreateCollection = false;
  this.video = {};
  this.editVideo = [];
  this.languageVideo = {};
  scope.editVideo = {};
  scope.showSeasons = false;
  scope.videoConfirmationDeleteBox = false;
  scope.videoArray = [];
  var selectedLanguage=0;
  scope.subTitleValidation = [];
  scope.subTitles = [];
  scope.subTitleForm = [];
  scope.transcodeStatus = '';
  var totalVideobytes=0;
  var completedVideobytes=0;

  this.responseMessage = requestFactory.getToaster();

  this.formatCategories = [];
  this.cast = [2];

  scope.videoArray.forEach(function (index, item) {
    scope.subTitleValidation[item] = false;
    scope.subTitles[item] = [];
  });

  this.categorySuggestions = [];
  this.multipleCategories = [];
  this.videoUploadCompleteCount = 0;
  this.uploadIntervalFlag = false;
  this.videoUploadRequestCount = 0;
  this.totalVideosCount = 0;
  this.videoGridView = true;
  scope.addVideoFields = [];
  this.btnStatus = true;

  this.allExams = {};
  this.multipleExams = [];
  this.showGridView = function () {
    this.videoGridView = true;
    this.videoListView = false;
  }
  this.addMoreVideos = function(){
  
   //const fileSelect = document.getElementById("fileSelect"),
   var fileElem = document.getElementById("video");

   // fileSelect.addEventListener("click", function (e) {
      if (fileElem) {
        fileElem.click();
      }
   // }, false);

   };
  this.showListView = function () {
    this.videoGridView = false;
    this.videoListView = true;
  }
  scope.toggleTab = function (tab) {
    if (scope.tabSelected == tab) {
      scope.filters.tab = '';
      scope.tabSelected = '';
      scope.currentPage = 1;
      scope.showRecords = false;
      scope.gridLoadingBar = true;
      scope.getRecords(true);
    } else {
      scope.selectTab('live_videos');
    }
  }
  
  this.saveSingleVideo = function () {
    self.videoUploadRequestCount++;
          
    requestFactory.post(requestFactory.getUrl('videos/add'), this.video, function (response) {
   
      if (!response.error && response.hasOwnProperty('video')) {
        if (scope.videoArray.indexOf(response.video.id) == -1 && response.video.id != '') {
          if(scope.videoArray[self.videoUploadRequestCount]!='')
          {
            scope.videoArray[scope.videoArray.length] = response.video;
          }
        }
        

        if (scope.videoArray) {
          scope.videoArray = scope.videoArray.filter(function (e, item) {
            if (e.hasOwnProperty('title')) {
              return e;
            }
          });

      
          scope.videoArray.forEach(function (item, index) {
            
           
            self.editVideo[index] = item;
            self.editVideo[index].title = item.title;
            self.editVideo[index].category_ids = [];
            self.editVideo[index].exam_ids = [];
            self.editVideo[index].is_active = false;
            self.editVideo[index].is_notify = false;
            self.editVideo[index].is_premium = false;
            self.editVideo[index].is_thumbnail_updated = 0;
            self.editVideo[index].is_posterimg_updated = 0;
            
            self.multipleExams[index] = [];
            self.editVideo[index].showsubTitleList=false;
            self.editVideo[index].showMainSubtitle=true;
            self.editVideo[index].subTitleList={};
            scope.subTitleValidation[index] = false;
            //scope.subTitles[index] = [];

          }); 
          
          scope.videoData(self.editVideo[0]); 
         
        }
      }
    }, function () {

    });
  };
   
  scope.startlivestream = function (record) {
    requestFactory.toggleLoader();
    requestFactory.post(requestFactory.getUrl('startlivestream'), {'id':record.id}, function () {
      requestFactory.toggleLoader();
      scope.getRecords(true);
    }, function () {
      requestFactory.toggleLoader();
    });
  }
  scope.getStatusLive = function () {
    for (var i = 0, len = scope.selectId.length; i < len; i++) {
      getStatusLiveUpdating(scope.selectId[i]);
    }
  }
  var getStatusLiveUpdating = function (record) {
    setTimeout(function () {
      requestFactory.post(requestFactory.getUrl('satuslivestream'), record, function (response) {
        if (response.response === 'starting') {
          getStatusLiveUpdating(record);
        } else {
          scope.getRecords(true);
        }
      }, function (response) {
        scope.getRecords(true);
      });
    }, 15000);
  }
  scope.stoplivestream = function (record) {
    scope.individualRecord = record;
    scope.stoppedStreamId = record.id;
  }
  this.cancelLiveStreamRecording = function () {
    scope.stoppedStreamId = '';
    self.stopLiveStreamingAfterConfirmation(0);
  };
  this.confirmLiveStreamRecording = function () {
    if (scope.stoppedStreamId != '') {
      scope.stoppedStreamId = '';
      self.stopLiveStreamingAfterConfirmation(1);
    } else {
      scope.stoppedStreamId = '';
    }
  };
  this.stopLiveStreamingAfterConfirmation = function (record_status) {
    scope.individualRecord.record_status = record_status;
    requestFactory.toggleLoader();
    requestFactory.post(requestFactory.getUrl('stoplivestream'), scope.individualRecord, function () {
      requestFactory.toggleLoader();
      scope.getRecords(true);
    }, function () {
      requestFactory.toggleLoader();
    });
  };
  this.saveVideo = function () {
    requestFactory.toggleLoader();
    requestFactory.post(requestFactory.getUrl('videos/add'), this.video, function () {
      $window.location = requestFactory.getTemplateUrl('admin/videos');
    }, function () { });
  };

  this.showUploadOption = function () {
    document.querySelector('.video_grid').style.display = 'none';
    document.querySelector('.add_video_container').style.display = 'block';
    document.getElementById("video_accordion_wrapper").style.display = 'none';
  };

  this.hideUploadOption = function () {
   // document.querySelector('.video_grid').style.display = 'block';
  //  document.querySelector('.add_video_container').style.display = 'none';
   // document.querySelector('#video_form_fields').style.display = 'none';
   $window.location = requestFactory.getTemplateUrl('admin/videos');
  };

  /*
   * Function to pause the video if it is playing when video edit sidebar is closed.
   */
  this.pauseVideo = function () {
    if (document.getElementsByClassName('st-menu-open').length > 0) {
      var myPlayer = videojs('video_player');
      if (!myPlayer.paused()) {
        myPlayer.pause();
      }
    }
  };
  /** 
   * Function to Show Video Detail on Clicking Videos in List of Videos
   */
  scope.videoData = function (data) {
    scope.selectedVideo = data;   
    scope.errors={};
 
  }
  /*
 * Function to set categories of a video in the video edit form.
 */
  this.setCategoriesOfVideos = function (videoDetails) {
    scope.selectedVideo.category_ids = [];
    self.multipleCategories = [];
    angular.forEach(videoDetails.videocategory, function (value, key) {
      scope.selectedVideo.category = String(value.category_id);
      scope.selectedVideo.category_ids.push(value.category_id);
      self.multipleCategories.push({
        id: value.category_id,
        name: self.allCategories[value.category_id]
      });
    });
  };

  this.saveVideoEdit = function ($event, videoItem) {
     
    scope.errors = {};
    var videoId = videoItem;

    if (baseValidator.validateAngularForm($event.target, scope)) {
      var isActive = angular.copy(scope.selectedVideo.is_active);
      scope.selectedVideo.is_active = (isActive == true) ? 1 : 0;
      var isError = 0;
      angular.forEach(scope.subTitles[videoItem], function (value, key) {
        if (!isError && (value.language == '' && value.url != '')) {
          isError = 1;
        }
      });

      if (isError) {
        return false;
      }

      requestFactory.post(requestFactory.getUrl('videos/edit/' + videoId),scope.selectedVideo, function (response) {

        requestFactory.toggleLoader();
        this.responseMessage = response.message;
        this.showResponseMessage = true;

        // To save subtitle
        //self.subTitleSubmit(videoItem, videoId);


        //scope.getRecords(true);
      //  this.closeVideoEdit();
        self.resetVideoThumbnailUpload();

        // To remove not saved status    
       
        $('#'+videoId).removeClass("not-saved");
      
        
        // To slide the accordin back to closed state
       // $($event.currentTarget).parents('.panel-collapse').removeClass('in');
       var notSaved = document.getElementsByClassName("not-saved");
      
        if (notSaved.length <= 0) {
       
          $window.location = requestFactory.getTemplateUrl('admin/videos');
        }
      }, function (response) {
        requestFactory.toggleLoader();
        this.fillError(response, videoItem);
      });
    } else {

      scope.errors = {};
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

  this.setVideoEditRules = function () {
    // Set rules for edit video form
    baseValidator.setRules(this.info.video_edit_rules);
  };

  this.setThumbUploadRules = function () {
    // Set rules for thumbnail upload form
    baseValidator.setRules(this.info.thumb_upload_rules);
  };

  this.fillError = function (response) {
    if (response.status == 422 && response.data.hasOwnProperty('message')) {
      angular.forEach(response.data.message, function (message, key) {
        if (typeof message == 'object' && message.length > 0) {
          scope.errors[key] = {
            has: true,
            message: message[0]
          };
        }
      });
    }
  };
  this.saveLanguage = function(event) {
    this.languageVideo.languageCode = scope.selectedVideo.language;
   requestFactory.post(requestFactory.getUrl('videos/addLanguage/' + scope.selectedVideo.id), this.languageVideo, function() {
       this.showResponseMessage = true;
      // win.location = requestFactory.getTemplateUrl('admin/videos');
   }, function(response) {
 
     scope.translationError = true;
     this.fillError(response);
   });
  };
  scope.existingFlowObject = flowFactory.create({
    target: document.querySelector('meta[name="base-api-url"]').getAttribute('content') + '/image',
    permanentErrors: [404, 500, 501],
    testChunks: false,
    chunkSize: 9007199254740992,
    maxChunkRetries: 1,
    chunkRetryInterval: 5000,
    simultaneousUploads: 4,
    singleFile: true
  });
  scope.existingFlowObject.on('fileSuccess', function (event, message) {
    if (message) {
      self.editVideo.pdf = message;delete-action
      angular.element('#loaderspdf').hide();
      angular.element('.submitbutton').attr('disabled', false)
    }
  });
  scope.existingFlowObject.on('fileAdded', function (file) {
    angular.element('#loaderspdf').show();
    angular.element('.submitbutton').attr('disabled', true)
  });
  scope.existingFlowObjectword = flowFactory.create({
    target: document.querySelector('meta[name="base-api-url"]').getAttribute('content') + '/image',
    permanentErrors: [404, 500, 501],
    testChunks: false,
    chunkSize: 9007199254740992,
    maxChunkRetries: 1,
    chunkRetryInterval: 5000,
    simultaneousUploads: 4,
    singleFile: true
  });
  scope.existingFlowObjectword.on('fileSuccess', function (event, message) {
    if (message) {
      self.editVideo.word = message;
      angular.element('#loadersword').hide();
      angular.element('.submitbutton').attr('disabled', false)
    }
  });
  scope.existingFlowObjectword.on('fileAdded', function (file) {
    angular.element('#loadersword').show();
    angular.element('.submitbutton').attr('disabled', true)
  });
  this.thumbnailUpload = function ($event) {
    if (baseValidator.validateAngularForm($event.target, scope)) {
      requestFactory.toggleLoader();
      requestFactory.post(requestFactory.getUrl('videos/upload-thumbnail/' + this.editVideo.id), this.editVideo, function (response) {
        requestFactory.toggleLoader();
        this.responseMessage = response.message;
        this.showResponseMessage = true;
        scope.getRecords(true);
        this.closeVideoEdit();
        self.resetVideoThumbnailUpload();
      }, function (response) {
        requestFactory.toggleLoader();
        this.fillError(response);
      });
    }
  };

  this.removeThumbnailProperty = function () {
    self.editVideo.thumbnail = '';
  };
  /*
   * Function to delete custom thumbnail of a video.
   */
  this.deleteThumbnail = function () {
    requestFactory.toggleLoader();
    requestFactory.post(requestFactory.getUrl('videos/delete-thumbnail/' + this.editVideo.id), this.editVideo, function (response) {
      requestFactory.toggleLoader();
      self.responseMessage = response.message;
      self.showResponseMessage = true;
      scope.getRecords(true);
      self.closeVideoEdit();
      self.resetVideoThumbnailUpload();
    }, function () { });
  };

  this.closeVideoEdit = function () {
    self.pauseVideo();
    classie.remove(document.getElementById('st-container'), 'st-menu-open');
  };

  this.resetVideoThumbnailUpload = function () {
    if (typeof window.VideoThumbnailUploadHandler == 'object') {
      $timeout(function () {
        angular.element('[data-dismiss="fileupload"]').trigger("click");
      }, 0, true);
      scope.selectedVideo.thumbnail = '';
      scope.selectedVideo.thumbnail_image = '';
    }
  };

  this.changeCategory = function () {

    if (self.allSeries.indexOf(parseInt(scope.selectedVideo.category)) != -1) {
      scope.showSeasons = true;
    }
    else {
      scope.showSeasons = false;
      scope.selectedVideo.season = '';
    }
  }

  this.defineProperties = function (data) {
     requestFactory.toggleLoader();
    this.info = data.info;
    this.allCollection = data.info.allCollection;
    this.allSeasons = data.info.allSeasons;
    this.allExams = data.info.allCollection;
    this.allCategories = data.info.allCategories;
    this.allSeries = data.info.allSeries; 
    this.language = data.info.language;  
    this.ads_info = data.info.ads_info;  
    this.cast = data.info.cast;   

    this.formatCategories = angular.copy(this.allCategories);

    var result = []; 
    this.formatCategories.forEach(function(item, index) { 
        if(item.id) {
          if(item.child_category.length > 0) {
            item.child_category.forEach(function(child, i) { 
              var newIndex = result.length;
              result[newIndex]         = {};
              result[newIndex].id      = child.id;
              result[newIndex].title   = child.title;
              result[newIndex].parent  = item.title;
            });
          }
          else {
            var newIndex = result.length;
            result[newIndex]         = {};
            result[newIndex].id      = '';
            result[newIndex].title   = '';
            result[newIndex].parent  = item.title;
          }
        }
      }
    );

    this.formatCategories = result;
    
    if(this.language.length != 0){
   
       selectedLanguage = String(this.language[0].id);
      
    }
    this.transcodedInfo = data.info.transcodedInfo;
    scope.livedetails = data.info.livesyncdata[0];
    this.numberOfActivePresets = data.info.numberOfActivePresets;
    baseValidator.setRules(this.info.video_edit_rules);
    angular.element('#move_collection').removeAttr('data-toggle');
    requestFactory.toggleLoader();
  };
  this.resetFormData = function (event) {
    this.collection = {};
    scope.errors = {};
    requestFactory.get(requestFactory.getUrl('videos/collection-update'), function (response) {
      this.allCollection = response.info.allCollection;
      baseValidator.setRules(this.info.video_edit_rules);
    });
    this.showcreateCollection = true;
    this.collection.id = String(0);
  }

  this.editBulkRecord = function () {
    scope.editParams = this.selectedRecords;
    this.isDeactivateBulkRecord = false;
    this.isActivateBulkRecord = false;
    this.isDeleteBulkRecord = false;
    scope.ConfirmationStatusBox = false;    
    this.isEditBulkRecord = true;
    
  }

  this.deleteBulkRecord = function () {
    scope.deleteParams = this.selectedRecords;
    this.isDeactivateBulkRecord = false;
    this.isActivateBulkRecord = false;
    this.isDeleteBulkRecord = true;
    this.isEditBulkRecord = false;
    scope.ConfirmationStatusBox = false;   
    
  }

  this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
    scope.activateParams = this.selectedRecords;
    if ($isActivateOrDeactivate == 'activate') {
      this.isDeleteBulkRecord = false;
      this.isDeactivateBulkRecord = false;
      this.isEditBulkRecord = false;
      scope.ConfirmationStatusBox = false;
      this.isActivateBulkRecord = true;
    } else if ($isActivateOrDeactivate == 'deactivate') {
      this.isDeleteBulkRecord = false;
      this.isActivateBulkRecord = false;
      scope.ConfirmationStatusBox = false;
      this.isDeactivateBulkRecord = true;
      this.isEditBulkRecord = false;
    }
   
  }

  this.cancelDeleteVideos = function () {
    scope.videoConfirmationDeleteBox = false;
    scope.deleteParams = '';
    $('#videoDeleteModal').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
  };

  this.confirmEditVideos = function () {
    if (scope.editParams.length > 0) {
      self.editMultipleVideos(scope.editParams);
      scope.videoConfirmationDeleteBox = false;
      if (videoStatus == 'bulk-video') {
        this.selectedRecords = [];
      }
      scope.editParams = '';
    } else {
      scope.videoConfirmationDeleteBox = false;
      scope.editParams = '';
    }
  };

  this.editMultipleVideos=function(id)
  {
    scope.editParams = '';
    var video_ids= btoa(id.join());    
    $window.location = requestFactory.getTemplateUrl('admin/videos/details-video-edit/'+ video_ids );
  }

  this.confirmEditRadio = function () {
    if (scope.editParams.length > 0) {
      self.editMultipleRadio(scope.editParams);
      scope.videoConfirmationDeleteBox = false;
      if (videoStatus == 'bulk-video') {
        this.selectedRecords = [];
      }
      scope.editParams = '';
    } else {
      scope.videoConfirmationDeleteBox = false;
      scope.editParams = '';
    }
  };

  this.editMultipleRadio=function(id)
  {
    scope.editParams = '';
    var video_ids= btoa(id.join());    
    $window.location = requestFactory.getTemplateUrl('admin/radio/details-radio-edit/'+ video_ids );
  }


  this.confirmEditEvents = function () {
    if (scope.editParams.length > 0) {
      self.editMultipleEvents(scope.editParams);
      scope.videoConfirmationDeleteBox = false;
      if (videoStatus == 'bulk-video') {
        this.selectedRecords = [];
      }
      scope.editParams = '';
    } else {
      scope.videoConfirmationDeleteBox = false;
      scope.editParams = '';
    }
  };

  this.editMultipleEvents=function(id)
  {
    scope.editParams = '';
    var video_ids= btoa(id.join());    
    $window.location = requestFactory.getTemplateUrl('admin/liveevents/details-liveevents-edit/'+ video_ids );
  }


  this.confirmDeleteVideos = function (videoStatus) {
    if (scope.deleteParams.length > 0) {
      self.deleteRecordsVideos(scope.deleteParams, videoStatus);
      scope.videoConfirmationDeleteBox = false;
      if (videoStatus == 'bulk-video') {
        this.selectedRecords = [];
      }
      scope.deleteParams = '';
    } else {
      scope.videoConfirmationDeleteBox = false;
      scope.deleteParams = '';
    }
  };

  this.confirmActivateOrDeactivateVideos = function (is_status) {
    if (is_status == 1) {
      this.isActivateBulkRecord = false;
    } else if (is_status == 0) {
      this.isDeactivateBulkRecord = false;
    }
   
    self.activateOrDeactivateRecordsVideos(scope.activateParams, is_status);
  }

  this.deleteRecordsVideos = function (id, videoStatus) {   
   
    scope.deleteParams = '';

    if(addForm != 'block') {
      scope.showRecords = false;
      scope.gridLoadingBar = true;
      var deleteIdLength = id.length;
    }
    
    // scope.deleteRequest = requestFactory.post(requestFactory.getUrl('videos/delete-action'), angular.extend({}, {
    //   selectedCheckbox: id,
    //   videoStatus: videoStatus
    // }, scope.requestParams), function(data) {
    //   this.responseMessage      = data.message;
    //   this.showResponseMessage  = true;

    //   if(addForm != 'block') {
      
    //     scope.deleteId = [];
    //     angular.element('#selectall').removeAttr('checked');
    //     if (scope.records.length - deleteIdLength > 0) {
    //       scope.getRecords(true);
    //     } else {
    //       scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
    //       scope.getRecords(true);
    //     }
    //   }
    //   else {
      
    //     $('#'+scope.selectedVideo.id).remove();
    
    //     if($('.not-saved').length <= 0) {
    //       $window.location = requestFactory.getTemplateUrl('admin/videos');
    //     }
    //   }
    // });
  };

  this.activateOrDeactivateRecordsVideos = function(id, is_status) {
    scope.activateParams = '';
    scope.showRecords = false;
    scope.gridLoadingBar = true;
    var activateIdLength = id.length;

    if (is_status == 1) {
      scope.deleteRequest = requestFactory.post(requestFactory.getUrl('videos/bulk-update-status'), angular.extend({}, {
        selectedCheckbox: id,
        isStatus: 'activate'
      }, scope.requestParams), function (data) {
        requestFactory.setToaster('success', data.message);
        self.responseMessage = requestFactory.getToaster();
        let currentUrl=window.location.href;
        if(currentUrl.includes('radio'))
        {
            requestFactory.setToaster('success', 'Radio activated successfully');
            self.responseMessage = requestFactory.getToaster(); 
        }
        this.selectedRecords = [];
        angular.element('#selectall').removeAttr('checked');
        if (scope.records.length - activateIdLength > 0) {
          scope.getRecords(true);
        } else {
          scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
          scope.getRecords(true);
        }
      });
    } else if (is_status == 0) {
      scope.deleteRequest = requestFactory.post(requestFactory.getUrl('videos/bulk-update-status'), angular.extend({}, {
        selectedCheckbox: id,
        isStatus: 'deactivate'
      }, scope.requestParams), function (data) {
        requestFactory.setToaster('success', data.message);
        self.responseMessage = requestFactory.getToaster();
        let currentUrl=window.location.href;
        if(currentUrl.includes('radio'))
        {
            requestFactory.setToaster('success', 'Radio deactivated successfully');
            self.responseMessage = requestFactory.getToaster(); 
        }
        this.selectedRecords = [];
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

  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('videos/info'), this.defineProperties, function (response) { 
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.fetchInfo();

  if ($('#thumb-image').length) {
    window.VideoThumbnailUploadHandler = new uploadHandler;
    window.VideoThumbnailUploadHandler.initate({
      file: 'thumb-image',
      previewer: 'thumb-preview',
      progress: 'thumb-progress',
      deleteIcon: 'thumb-delete',
      beforeUpload: function () {
        scope.errors = {};
        if (!scope.$$phase) {
          scope.$apply();
        }
      },
      afterUpload: function (response) {
        self.editVideo.thumbnail = response.info;
        self.editVideo.selected_thumb = response.info;
      }
    });
  }

  this.addFullScreenEventListener = function () {
    var myPlayer = videojs('video_player');
    myPlayer.on('fullscreenchange', function () {
      if (myPlayer.isFullscreen()) {
        // Change transition property to none to avoid layout shake while exit.
        document.getElementById("menu-7").style.transitionProperty = "none";
        document.querySelector(".st-pusher").style.transitionProperty = "none";
      } else {
        // Remove back the transition value none so that the video edit sidebar closes and opens smoothly.
        document.getElementById("menu-7").style.removeProperty('transition');
        document.querySelector(".st-pusher").style.removeProperty('transition');
      }
    });
  };
  // this.addFullScreenEventListener();

  /**
   *  Function is used to select the move collection Button
   *
   *  @param $event, id
   *
   */
  this.selectRecord = function ($event, id) {
    var isCheckboxSelected = false;
    var eventCheckbox = $event.target || $event.srcElement;

    if (angular.isObject(eventCheckbox)) {
      if (angular.element(eventCheckbox).is(':checked')) {

        angular.element('#move_collection').attr("data-toggle", "modal");

        if (this.selectedRecords.indexOf(id) == -1) {
          this.selectedRecords.push(id);
        }
      } else if (this.selectedRecords.indexOf(id) > -1) {
        this.selectedRecords.splice(this.selectedRecords.indexOf(id), 1);
      }
    }

    if (this.selectedRecords.length == 0) {
      angular.element('#move_collection').removeAttr('data-toggle');
    }
    this.checkMasterCheckbox();

  }
  /**
   * Function to check and uncheck master checkbox when all the checkboxes are checked or not.
   */
  this.checkMasterCheckbox = function () {
    var mainCheckbox = true;
    angular.element('.checkbox').each(function () {
      if (angular.element(this).prop('checked') == false) {
        mainCheckbox = false;
      }
    });

    if (mainCheckbox == false) {
      // Uncheck the main checkbox
      angular.element('#selectall').prop('checked', false);
    } else {
      // Check the main checkbox
      angular.element('#selectall').prop('checked', true);
    }
  };
  /**
   * Function to select and unselect all checkboxes.
   */
  this.selectAllRecords = function () {
    if (angular.element('#selectall').prop('checked')) {
      self.selectedRecords = [];
      angular.element('.checkbox').each(function () {
        angular.element(this).prop('checked', true);
        var id = Number(angular.element(this).val());
        self.selectedRecords.push(id);
      });
      angular.element('#move_collection').attr("data-toggle", "modal");
    } else {
      angular.element('.checkbox').each(function () {
        angular.element(this).prop('checked', false);
        var id = Number(angular.element(this).val());
        self.selectedRecords.splice(self.selectedRecords.indexOf(id), 1);
      });
    }
    if (this.selectedRecords.length == 0) {
      angular.element('#move_collection').removeAttr('data-toggle');
    }
  };

  /**
   *  Function is used to select the create collection
   *
   *  @param string collection
   *  @return void
   */
  this.createCollection = function (collection) {
    if (parseInt(collection) === 0) {
      this.showcreateCollection = true;
    } else {
      scope.errors = {};
      this.showcreateCollection = false;
    }
  }
  /**
   *  Function is used to save the collection
   *
   *  @param $event
   *
   */
  this.save = function ($event) {
    if (baseValidator.validateAngularForm($event.target, scope)) {
      this.collection.selectedVideos = this.selectedRecords;
      requestFactory.post(requestFactory.getUrl('collections/add'), this.collection, function (response) {
        this.fetchInfo();
        angular.element(".close").click();
        requestFactory.toggleLoader();
        angular.element(".checkbox").attr("checked", false);
        angular.element('#selectall').prop('checked', false);
        this.selectedRecords = [];
        this.responseMessage = response.message;
        this.showResponseMessage = true;
      }, this.fillError);
    }
  }

  /**
   * Function to update status of a preset,collection,category and video
   *
   * @param object record
   * @return void
   */
  this.updateStatus = function (record) {
    if(record.is_live==1)
    {
      scope.routeName = 'livevideos';
    }
    if(record.is_live==0)
    {
      scope.routeName = 'videos';
    }
    if(record.is_live==2)
    {
      scope.routeName = 'radio';
    }

    if(record.is_live==3)
    {
      scope.routeName = 'liveevents';
    }
    
    scope.updateStatus(record);
  };

  /*
     * Function to Confirm Active and In-Active Status.
     */
    this.statusChangeSingleRecord = function (record) {
      scope.statusParams = record;
      scope.ConfirmationStatusBox = true;      
      this.isDeactivateBulkRecord = false;
      this.isActivateBulkRecord = false;
      this.isDeleteBulkRecord = false;
      this.isEditBulkRecord = false;
  };

  this.confirmStatus = function () {
      if (scope.statusParams) {
          self.updateStatus(scope.statusParams);
          scope.ConfirmationStatusBox = false;
          scope.statusParams = '';
      } else {
          scope.ConfirmationStatusBox = false;
          scope.deleteParams = '';
      }
  };
  
  /*
   * Function to add a category to the category field in video edit form.
   */
  this.addCategoriesToVideos = function (id, categoryName, videoIndex) {
    self.editVideo[videoIndex].category_ids = [];
    self.multipleCategories[videoIndex] = [];
    self.editVideo[videoIndex].category_ids.push(id);
    self.multipleCategories[videoIndex].push({
      id: id,
      name: categoryName
    });
    self.categoryField = '';
    self.examField = '';
    self.categorySuggestions = [];
  };
  this.addExamToVideos = function (id, examName, videoIndex) {
    self.editVideo[videoIndex].exam_ids.push(id);
    self.multipleExams[videoIndex].push({
      id: id,
      title: examName
    });
    self.examField = '';
    self.examSuggestions = [];
  };

  this.showExamsSuggestions = function ($event, videoIndex) {
    var title = $event.target.value;
    self.examSuggestions = [];
    if (typeof title === 'string' && title != '' && title.length >= 1) {
      angular.forEach(self.allExams, function (value, key) {
        key = Number(key);
        if (value.toLowerCase().indexOf(title.toLowerCase()) != -1 && self.editVideo[videoIndex].exam_ids.indexOf(key) == -1) {
          self.examSuggestions.push({
            id: key,
            title: value
          });
        }
      });
    } else {
      self.examSuggestions = [];
    }
  };
  /*
   * Function to show categories suggestions in category field of video edit form.
   */
  this.showCategoriesSuggestions = function ($event, videoIndex) {
    var name = $event.target.value;
    self.categorySuggestions = [];
    if (typeof name === 'string' && name != '' && name.length >= 1) {
      angular.forEach(self.allCategories, function (value, key) {
        key = Number(key);
        if (value.toLowerCase().indexOf(name.toLowerCase()) != -1 && self.editVideo[videoIndex].category_ids.indexOf(key) == -1) {
          self.categorySuggestions.push({
            id: key,
            name: value
          });
        }
      });
    } else {
      self.categorySuggestions = [];
    }
  };

  /*
   * Function to remove a category from the category field in video edit form.
   */
  this.removeCategoriesFromVideos = function (index, videoIndex) {
    // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
    if (self.editVideo[videoIndex].category_ids.length > 0) {
      var categoryId = self.multipleCategories[videoIndex][index].id;
      var categoryIdIndex = self.editVideo[videoIndex].category_ids.indexOf(categoryId);
      if (categoryIdIndex > -1) {
        self.editVideo[videoIndex].category_ids.splice(categoryIdIndex, 1);
      }
      self.multipleCategories[videoIndex].splice(index, 1);
    }
  };
  /*
   * Function to remove a category from the category field in video edit form.
   */
  this.removeExamsFromVideos = function (index, videoItem) {
    // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
    if (self.editVideo[videoItem].exam_ids.length > 0) {
      var examId = self.multipleExams[videoItem][index].id;
      var examIdIndex = self.editVideo[videoItem].exam_ids.indexOf(examId);
      if (examIdIndex > -1) {
        self.editVideo[videoItem].exam_ids.splice(examIdIndex, 1);
      }
      self.multipleExams[videoItem].splice(index, 1);
    }
  };
  /*
   * Function to display presets of a video in bootstrap modal in the videos grid page.
   */
  this.showVideoPresetsInModal = function (transcodedvideos) {
    self.commonVideoPresets = [];
    transcodedvideos.forEach(function (item, index) {
      if (angular.isObject(item.presets)) {
        self.commonVideoPresets.push(item.presets.name + ' - ' + item.presets.format);
      }
    });
    jQuery('#videoPresetsModal').modal('show');
  };

  this.viewDetailsVideoCollection = function (filters) {
    scope.filters.collectionId = filters.collectionId;
    scope.filters.collectionName = filters.collectionName;
  }

  /**
   *  Listen to the records to update property
   *
   */
  scope.$on('afterGetRecords', function (e, data) {

    if (angular.isUndefined(scope.searchRecords.is_active)) {
      scope.searchRecords.is_active = 'all';
      scope.searchRecords.type = 'all';
    }

    if (angular.isUndefined(scope.searchRecords.hide_web)) {
      scope.searchRecords.hide_web = 'all';
      scope.searchRecords.type = 'all';
    }

    scope.selectId = [];
    for (var i = 0, len = data.data.data.length; i < len; i++) {
      if (data.data.data[i].liveStatus === 'starting') {
        scope.selectId.push(data.data.data[i]);
      }
    }
    angular.element('#move_collection').removeAttr('data-toggle');
    self.selectedRecords = [];
    angular.element(".checkbox").attr("checked", false);
    scope.getStatusLive();

    setTimeout(function () {
      $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
    }, 500);

    scope.progressArray = [];
    scope.indexArray = [];
    data.data.data.filter(function (item, key) {
      if (item.job_status != 'Complete' && item.job_status != 'Error') {
        scope.progressArray.push(item.id);
      }

      scope.indexArray[item.id] = key;
    });
    if (scope.progressArray.length > 0) {
      $interval(function () {
        //self.fetchProgress(); //Database hit Progress status
      }, 2000);
    }


  });

  /** Database hit Progress status Start*/
  this.fetchProgress = function () {
    requestFactory.post(requestFactory.getUrl('videos/progress'), angular.extend({}, {
      video_ids: scope.progressArray
    }), function (response) {
      if (response.response.video_info.length > 0) {
        response.response.video_info.filter(function (item, key) {
          var index = scope.indexArray[item.id];

          scope.records[index].upload_percentage = item.upload_percentage;

          scope.records[index].job_status = item.job_status;

        });

        self.transcodedInfo = response.response.transcode_info;
      }
    }, function () { });
  }
  /** Database hit Progress status End*/
  
  $interval(function () {
  if(rootScope.transcodeVideoCount > 0) {
    rootScope.transcodeVideoDetails.filter(function (item, key) {
      var index = scope.indexArray[item.id];
      if(item.job_status == 'Progressing' || item.job_status=='Error')
      {
          scope.records[index].upload_percentage = item.upload_percentage;
          scope.records[index].job_status = item.job_status;
      }
      if(item.upload_percentage == '100') {
        scope.records[index].upload_percentage = '100';
        scope.records[index].job_status = 'Complete';
        if(item.transcode_status != 'Complete') {
          requestFactory.post( requestFactory.getUrl('videos/transcode-status/' + item.id), scope.transcodeStatus, function ( response ) {
          });
        }

      }  
    });
  }
}, 2000);
  

   
  /**
   * Image Upload Script
   *
   * */
  function readAsUrl(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('image').src = e.target.result;
      };
      reader.onloadend = function (e) {
        $('#modal').modal('show');
      };
      reader.readAsDataURL(input.files[0]);
    }
  };

  $(document).ready(function () {
    var image = document.getElementById('image');


    $(document).on('change', '.uploadImg', function (e) {
      var videoItem = $(this).data("video-index");
      scope.errors[videoItem] = {};
      var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
      var files = e.target.files;
      var fileType = files[0].type;
      if ($.inArray(fileType, ValidImageTypes) < 0) {
        scope.errors[videoItem]['thumbnail_image'] = { has: true, message: 'Invalid file format. Upload only jpeg and png file formats.' };
        scope.$apply();
        return;
      }
      $('.crop-body').show();
      var videoIndex = e.target.getAttribute('data-video-index');
      $('#modal .video-index').val(videoIndex);
      readAsUrl(this);
    });

    var cropBoxData;
    var canvasData;
    var cropper;


    $(document).on('show.bs.modal', '#modal', function () {
      $('.error_msg').hide();
      setTimeout(function () {
        cropper = new Cropper(image, {
          autoCropArea: 1,
          viewMode: 3,
          aspectRatio: 12 / 13,
          preview: '.img-preview',
          cropBoxResizable: false,
          minCropBoxWidth: 200,
          minCropBoxHeight: 245,
          dragCrop: false,
          mouseWheelZoom: false,
          resizable: false,
          ready: function () {
            //Should set crop box data first here
            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
          }
        });
      }, 500);
    });
    $(document).on('hidden.bs.modal', '#modal', function () {
      document.getElementsByClassName("uploadImg")[0].value = "";
      $('#submit-image').prop('disabled', false);
      cropper.destroy();
    });
    $(document).on('click', '#submit-image', function () {
      cropBoxData = cropper.getCropBoxData();
      canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
        var formData = new FormData();
        formData.append('module', 'video');
        formData.append('size', 'thumb');
        formData.append('image', blob);
        $('.crop-body').hide();
        $('.loader-container').show();
        $('#submit-image').prop('disabled', true);
        $.ajax($('meta[name="base-api-url"]').attr('content') + '/videos/thumbnail', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success(data) {
            var videoIndex = $('#modal').val();
            $('.uploaded_img').attr('src', data.info);
            $('.uploaded_img').show();
            scope.selectedVideo.thumbnail = data.info;
            scope.selectedVideo.thumbnail_image = data.info;
            scope.selectedVideo.selected_thumb = data.info;
            scope.selectedVideo.is_thumbnail_updated = 1;
            scope.$apply();
            $('.loader-container').hide();
            $('#modal').modal('hide');
          },
          error() {
            $('.loader-container').hide();
            $('.error_msg').show().text("Please upload bigger image");
          },
        })
      }, 'image/jpeg');
    });
  });
  /**
   * End of image upload script
   *
   * */
  /**
   * Poster Image Upload Script
   *
   * */
  function readAsPosterUrl(input, videoIndex) {
    if (input.files && input.files[0]) {
      var readerImg = new FileReader();
      readerImg.onload = function (e) {
        document.getElementById('poster_image').src = e.target.result;
      };
      readerImg.onloadend = function (e) {
        $('#poster_modal').modal('show');
      };
      readerImg.readAsDataURL(input.files[0]);
    }
  };

  $(document).ready(function () {
    var posterImage = document.getElementById('poster_image');
    $(document).on('change', '.uploadPosterImg', function (e) {
      var videoItem = $(this).data("video-index");
      scope.errors[videoItem] = {};
      var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
      var files = e.target.files;
      var fileType = files[0].type;
      if ($.inArray(fileType, ValidImageTypes) < 0) {
        scope.errors[videoItem]['poster'] = { has: true, message: 'Invalid file format. Upload only jpeg and png file formats.' };
        scope.$apply();
        return;
      }
      $('.crop-body').show();

      var videoIndex = e.target.getAttribute('data-video-index');
      $('#poster_modal .video-index').val(videoIndex);
      readAsPosterUrl(this, videoIndex);
    });
    var cropBoxImgData;
    var canvasImgData;
    var cropperImg;
    $(document).on('show.bs.modal', '#poster_modal', function () {
      $('.poster_error_msg').hide();
      setTimeout(function () {
        cropperImg = new Cropper(posterImage, {
          autoCropArea: 1,
          viewMode: 3,
          aspectRatio: 940 / 500,
          preview: '.poster_img-preview',
          cropBoxResizable: false,
          minCropBoxWidth: 400,
          minCropBoxHeight: 300,
          dragCrop: false,
          mouseWheelZoom: false,
          resizable: false,
          ready: function () {
            //Should set crop box data first here
            cropperImg.setCropBoxData(cropBoxImgData).setCanvasData(canvasImgData);
          }
        });
      }, 500);
    });
    $(document).on('hidden.bs.modal', '#poster_modal', function () {
      document.getElementsByClassName("uploadPosterImg")[0].value = "";
      $('#submit_poster_image').prop('disabled', false);
      cropperImg.destroy();
    });
   
    $(document).on('click', '#submit_poster_image', requestFactory.access_token, function () {
      cropBoxImgData = cropperImg.getCropBoxData();
      canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
        var formImgData = new FormData();
        formImgData.append('module', 'video');
        formImgData.append('size', 'poster');
        formImgData.append('image', blob);
        $('.crop-body').hide();
        $('.poster_loader-container').show();
        $('#submit_poster_image').prop('disabled', true);
        $.ajax($('meta[name="base-api-url"]').attr('content') + '/videos/poster', {
          method: "POST",
          data: formImgData,
          processData: false,
          contentType: false,
          beforeSend: function(request){request.setRequestHeader('Authorization', 'Bearer '+requestFactory.access_token)},
          success(data) {
            var videoIndex = $('#poster_modal').val();
            $('.uploaded_poster_img').attr('src', data.info);
            $('.uploaded_poster_img').show();
            scope.selectedVideo.poster_image = data.info;
            scope.selectedVideo.is_posterimg_updated = 1;
            scope.$apply();
            $('.poster_loader-container').hide();
            $('#poster_modal').modal('hide');
          },
          error() {
            $('.poster_loader-container').hide();
            $('.poster_error_msg').show().text("Please upload bigger image");
          },
        })
      }, 'image/jpeg');
    });
  })
  /**
   * End of poster image upload script
   *
   * */

   /**
   * Mobile Poster Image Upload Script
   *
   * */
  function readAsPosterUrl(input, videoIndex) {
    if (input.files && input.files[0]) {
      var readerImg = new FileReader();
      readerImg.onload = function (e) {
        document.getElementById('mobile_poster_image').src = e.target.result;
      };
      readerImg.onloadend = function (e) {
        $('#poster_modal').modal('show');
      };
      readerImg.readAsDataURL(input.files[0]);
    }
  };

  $(document).ready(function () {
    var posterImage = document.getElementById('mobile_poster_image');
    $(document).on('change', '.uploadPosterImg', function (e) {
      var videoItem = $(this).data("video-index");
      scope.errors[videoItem] = {};
      var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
      var files = e.target.files;
      var fileType = files[0].type;
      if ($.inArray(fileType, ValidImageTypes) < 0) {
        scope.errors[videoItem]['poster'] = { has: true, message: 'Invalid file format. Upload only jpeg and png file formats.' };
        scope.$apply();
        return;
      }
      $('.crop-body').show();

      var videoIndex = e.target.getAttribute('data-video-index');
      $('#mobile_poster_modal .video-index').val(videoIndex);
      readAsPosterUrl(this, videoIndex);
    });
    var cropBoxImgData;
    var canvasImgData;
    var cropperImg;
    $(document).on('show.bs.modal', '#mobile_poster_modal', function () {
      $('.poster_error_msg').hide();
      setTimeout(function () {
        cropperImg = new Cropper(posterImage, {
          autoCropArea: 1,
          viewMode: 3,
          aspectRatio: 380 / 500,
          preview: '.mobile_poster_img-preview',
          cropBoxResizable: false,
          minCropBoxWidth: 380,
          minCropBoxHeight: 500,
          dragCrop: false,
          mouseWheelZoom: false,
          resizable: false,
          ready: function () {
            //Should set crop box data first here
            cropperImg.setCropBoxData(cropBoxImgData).setCanvasData(canvasImgData);
          }
        });
      }, 500);
    });
    $(document).on('hidden.bs.modal', '#mobile_poster_modal', function () {
      document.getElementsByClassName("uploadPosterImg")[0].value = "";
      $('#submit_poster_image').prop('disabled', false);
      cropperImg.destroy();
    });

    $(document).on('click', '#submit_poster_image', requestFactory.access_token, function () {
      cropBoxImgData = cropperImg.getCropBoxData();
      canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
        var formImgData = new FormData();
        formImgData.append('module', 'video');
        formImgData.append('size', 'poster');
        formImgData.append('image', blob);
        $('.crop-body').hide();
        $('.poster_loader-container').show();
        $('#submit_poster_image').prop('disabled', true);
        $.ajax($('meta[name="base-api-url"]').attr('content') + '/videos/poster', {
          method: "POST",
          data: formImgData,
          processData: false,
          contentType: false,
          beforeSend: function(request){request.setRequestHeader('Authorization', 'Bearer '+requestFactory.access_token)},
          success(data) {
            var videoIndex = $('#mobile_poster_modal').val();
            $('.uploaded_poster_img').attr('src', data.info);
            $('.uploaded_poster_img').show();
            scope.selectedVideo.mobile_poster_image = data.info;
            scope.selectedVideo.is_posterimg_updated = 1;
            scope.$apply();
            $('.mobile_poster_loader-container').hide();
            $('#mobile_poster_modal').modal('hide');
          },
          error() {
            $('.poster_loader-container').hide();
            $('.poster_error_msg').show().text("Please upload bigger image");
          },
        })
      }, 'image/jpeg');
    });
  })
  /**
   * End of poster image upload script
   *
   * */


   /*
    * Function to delete admin video view detail page.
    */
    this.deleteSingleRecordVideos = function(id,title) {
        scope.deleteParams = [id];
        scope.videoConfirmationDeleteBox = true;
        scope.videotitle=title;
    };
    scope.languageChange = function() {
      scope.errors = [];
     
     if(selectedLanguage == scope.selectedVideo.language) {
      $('#videoEditForm').css('display', 'block');
      $('#languageForm').css('display', 'none');
      $('#videoEditFormSubmit').css('display', 'inline-block');
      $('#videoLanguageEditFormSubmit').css('display', 'none');
     } else {
        self.languageVideo = {};
        angular.forEach(this.video_translation, function(value) {
          if(value.language_id == self.editVideo.language) {
            
            self.languageVideo.title = value.title;
            self.languageVideo.description = value.description;
            self.languageVideo.presenter = value.presenter;
          }
        });
        $('#videoEditForm').css('display', 'none');
        $('#languageForm').css('display', 'block');
        $('#videoEditFormSubmit').css('display', 'none');
        $('#videoLanguageEditFormSubmit').css('display', 'inline-block');
     }
   };
    // BEGIN : SUBTITLE BLOCK
    this.addSubTitle = function() {
   
      scope.subTitleValidation = false;
      scope.subTitles.push({
        'label': '',
        'kind': '',
        'url': '',
        'language': '',
      });
    }
  
  this.deleteRecordsVideos = function (id, videoStatus) {

    scope.deleteParams = '';
    scope.deleteRequest = requestFactory.post(requestFactory.getUrl('videos/delete-action'), angular.extend({}, {
      selectedCheckbox: id,
      videoStatus: videoStatus
    }, scope.requestParams), function (data) {
      $('.accordion_wrapper_' + id).remove();
      requestFactory.setToaster('success', data.message);
      if ($('.not-saved').length <= 0) {
      
        let currentUrl=window.location.href;
        if(currentUrl.includes('videos'))
        {
          $window.location = requestFactory.getTemplateUrl('admin/videos');
        }
        if(currentUrl.includes('livevideos'))
        {
          $window.location = requestFactory.getTemplateUrl('admin/livevideos');
        }
        if(currentUrl.includes('radio'))
        {
          requestFactory.setToaster('success', 'Radio deleted successfully');
          $window.location = requestFactory.getTemplateUrl('admin/radio');
        }

        if(currentUrl.includes('liveevents'))
        {
          requestFactory.setToaster('success', 'Event deleted successfully');
          $window.location = requestFactory.getTemplateUrl('admin/liveevents');
        }

       
      }
    });
  };

  
  
  scope.getTheFiles = function ($files,videoItem) {
  
    scope.subTitleValidation = true;
    scope.subTitleForm = true;
    angular.forEach($files, function (value, key) {
     
      scope.$apply(function () {
        var fileName = value.type;
        if (fileName != '') {
          var extension = fileName.split('/')[1];
        }
        else {
          var extension = value.name.split('.').pop();
        }

        if (extension == 'vtt' || extension == 'srt' || extension == 'x-subrip') {
          scope.subTitles['url'] = value;
        } else {
          scope.subTitles['url'] = 'error';
        }
     
      });
    });
  };
  this.subTitleDelete = function (index, videoId) {
    scope.subTitles = scope.selectedVideo.subTitleList[index];
    if (scope.subTitles.url != '') {
      this.deleteSubtitle(videoId);
    }
    scope.selectedVideo.subTitleList.splice(index, 1);
  }
  this.deleteSubtitle = function (videoId) {
    requestFactory.post(requestFactory.getUrl('videos/delete-subtitle/' + videoId), { "subtitle": scope.subTitles }, function () {
    }, function () { });
  };
  
  scope.subTitleSubmit = function (videoItem) {
  
    var formdatas = new FormData();
      if (scope.subTitles.language!=undefined &&scope.subTitles.url!=undefined && scope.subTitles.language != '' && scope.subTitles.url != '' && scope.subTitles.url != 'error') {
        formdatas.append(scope.subTitles.language, scope.subTitles.url);
      
      } else {
            if(scope.subTitles.language != '')
            {
              scope.errors['language'] = {
                has: true,            
            }; 
            }
            if(scope.subTitles.url != '')
            {
              scope.errors['url'] = {
                has: true,            
            }; 
            }
        scope.subTitleForm = false;
        return
      }

    if (scope.subTitleForm) {
      
      // Inorder to make browser to detech the request and set content-type automatically set it to undefined
      requestFactory.setHeaders('Content-Type', undefined);
      requestFactory.post(requestFactory.getUrl('videos/upload-subtitles?id=' + videoItem), formdatas, function (response) {
        scope.selectedVideo.showsubTitleList=true;
        scope.selectedVideo.showMainSubtitle=false;
        scope.selectedVideo.subTitleList=response.response
        $('#subtitle').modal('hide');
      }, function (response) {
        this.fillError(response);
       });

      requestFactory.setHeaders('Content-Type', 'application/json');
    }
  }
  // END : SUBTITLE BLOCK
}];


window.gridInitApp = angular.module('grid', ['flow', 'ngTagsInput', 'ui']);

window.gridInitApp.filter('btoa', function () {
  return function(input){
      return btoa(input);
  }
});

window.gridInitApp.directive('selectTwo', function () {
  return {
    link: function (scope, elm, attr) {
      scope.keywords = [];
    }
  };
}).directive('keywordEditable', ['$document', '$timeout', '$http', function ($document, $timeout, $http) {
  return {
    require: 'ngModel',
    link: function (scope, elm, attrs, ctrl) {
      var routeName = attrs.routeName;
      elm.on('keydown', function () {
        if (event.keyCode == 13 || event.keyCode == 188) {
          if (scope.keywords.indexOf(elm.text()) == -1) {
            if (elm.text() != "") {
              scope.keywords.push(elm.text().trim());
            }
          }
          ctrl.$setViewValue('');
          elm.text('');
          event.preventDefault();
        }
        if (event.keyCode == 8) {
          if (elm.text() == '') {
            scope.keywords.splice(scope.keywords.length - 1, 1);
            ctrl.$setViewValue();
          }
        }
      });

      scope.removeKeyword = function (index) {
        scope.keywords.splice(index, 1);
      };
      scope.addTag = function (name) {
        scope.keywords.push(name);
        ctrl.$setViewValue('');
        elm.text('');
      }

      // load init value from DOM
      ctrl.$setViewValue(elm.text());
    }
  };
}]);

var fileDirective = ['$parse', function ($parse) {
  function fn_link(scope, element, attrs) {
    var onChange = $parse(attrs.ngFiles);
    element.on('change', function (event) {
      onChange(scope, { $files: event.target.files });
    });
  };
  return {
    link: fn_link
  }
}];

window.gridControllers = {
  VideoGridController: VideoGridController
};
window.gridDirectives = {
  baseValidator: validatorDirective,
  intializeSidebar: intializeSidebar,
  ngFiles: fileDirective,
};
