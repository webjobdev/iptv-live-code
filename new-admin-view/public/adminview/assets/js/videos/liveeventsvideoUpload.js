'use strict';

var videoUpload = angular.module('videoUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = videoUpload;

videoUpload.directive('baseValidator', validatorDirective);
videoUpload.factory('requestFactory', requestFactory);
videoUpload.service('commonGeofencingService', commonGeofencing);
videoUpload.directive('ngFiles', [
  '$parse',
  function ($parse) {
    function fn_link(scope, element, attrs) {
      var onChange = $parse(attrs.ngFiles);
      element.on('change', function (event) {
        onChange(scope, { $files: event.target.files });
      });
    }

    return {
      link: fn_link
    };
  }
]);

videoUpload.controller('VideoUploadController', [
  'flowFactory',
  '$scope',
  'requestFactory',
  '$rootScope',
  '$window',
  '$sce',
  '$timeout',
  '$compile',
  '$interval',
  'commonGeofencingService',
  '$location',
  function (
    flowFactory,
    scope,
    requestFactory,
    rootScope,
    $window,
    $sce,
    $timeout,
    $compile,
    $interval,
    commonGeofencingService,
    $location
  ) {
    var self = this;
    this.info = {};
    this.category = {};
    this.collection = {};
    this.allCollection = {};
    this.allSeasons = {};
    this.language = {};
    this.selectedRecords = [];
    this.videoSubmitted = false;
    scope.translationError = false;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    this.showcreateCollection = false;
    this.video = {};
    this.editVideo = [];
    scope.selectedVideo = {};
    scope.selectedLanguageVideo = {};
    scope.languageVideo = [];
    scope.showSeasons = false;
    scope.videoConfirmationDeleteBox = false;
    scope.removeTrailerConfirmation = false;
    scope.selectedVideo.trailer_updated = 0;
    scope.videoArray = [];
    scope.selectedLanguage = 0;
    scope.defaultLanguage = 0;
    scope.subTitleValidation = [];
    scope.subTitles = [];
    scope.subTitleForm = [];
    scope.transcodeStatus = '';
    scope.encrytpedVideoId = '';
    var totalVideobytes = 0;
    var completedVideobytes = 0;
    scope.geo_video_id;
    scope.singlePreload = [];
    scope.currentIndex = 0;
    this.formatCategories = [];
    this.radioCategories = [];
    this.liveCategories = [];
    scope.livePage = false;
    scope.radiopage = false;
    scope.editPage = false;
    scope.randomKeys = [];
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
    scope.AudioValidation = [];
    scope.AudioForm = [];
    scope.audios = [];
    scope.tariler = [];
    scope.trailer = '';
    scope.isAudioFileUploaded = false;
    scope.audioFilename = '';
    scope.audioUploadStatus = {
      isAudioAdded: false,
      isAudioUploading: false,
      isAudioUploaded: false,
      error: false
    };
    scope.audioUploadErrMsg = '';
    scope.TrailerForm = [];
    scope.trailer = [];
    scope.trailerFilename = '';
    scope.trailerUploadStatus = {
      isTrailerAdded: false,
      isTrailerUploading: false,
      isTrailerUploaded: false,
      error: false
    };
    scope.trailerUploadErrMsg = '';

    scope.defineGeoProperties = function (data) {
      commonGeofencingService.defineGeoProperties(data, scope.geo_video_id);
    };
    /**
     *  This function is to  get the countries
     *  list from the database to display for user.
     */
    scope.showCountries = function (id = null) {
      commonGeofencingService.getCountries(id);
    };

    scope.fetchGeoInfo = function (id = null) {
      requestFactory.get(
        requestFactory.getUrl('indiGeofencing/info/' + id),
        this.defineGeoProperties,
        function () { }
      );
    };

    /**
     *  This function is to  get the selected
     *  region details from the database to display under the region.
     */
    scope.getRegions = function (geoCountry, index, videoID, $event) {
      commonGeofencingService.getRegions(geoCountry, index, videoID, $event);
    };
    /**
     *  This function is to used to toggle the countries column
     *  and make it selected if it already saved previously.
     */
    scope.toggleCountriesSelection = function (geoCountry, index, videoID) {
      var index = null;
      commonGeofencingService.toggleCountriesSelection(
        geoCountry,
        index,
        videoID
      );
    };
    /**
     *  This function is to used to toggle the region column
     *  and make the regions of that country selected
     *  if it already saved previously.
     */
    scope.toggleRegionsSelection = function (
      geoCountry,
      geoRegions,
      geo_video_id
    ) {
      commonGeofencingService.toggleRegionsSelection(
        geoCountry,
        geoRegions,
        geo_video_id
      );
    };

    this.addMoreVideos = function () {
      scope.selectedVideo.replaceVideo = false;
      var fileElem = document.getElementById('video');
      if (fileElem) {
        fileElem.click();
      }
    };

    this.hideUploadOption = function (event) {
      var targetPage = event.currentTarget.getAttribute('data-url');
      window.location.href = targetPage;
    };




    this.setupVideoUploader = function () {
      this.videoUploader = new videoUploader(scope, requestFactory);

      if (!scope.livePage && !scope.editPage) {
        var body = document.getElementsByTagName('body');
        body[0].classList.add('video_accordion_overflow');
      }

      if (!scope.livePage) {
        this.videoUploader.initiate({
          id: 'video',
          class: 'upload_video',
          dropAreaId: 'file_drop_area',
          afterUpload: function (response) {
            self.video.video_details = response;
            scope.videoData(response);
            self.saveSingleVideo();
          },
          beforeUpload: function (totalVideosCount, currentCount, files) {
            self.totalVideosCount = totalVideosCount;
            // Reset the values because this upload might be after failure.
            self.videoUploadCompleteCount = 0;
            self.uploadIntervalFlag = false;
            self.videoUploadRequestCount = 0;
          }
        });
      }
    };

    this.saveSingleVideo = function () {
      if (this.video.video_details.replaceVideo) {
        /*
     NOTE : If the replace tag of the video is true, then don't do new insert into db.
     In the form submit based on the set uuid param and replaceVideo tag in the object update the video status and the uuid
    */
        // Update new UUID to the selected Video to update the video from backend
        scope.selectedVideo.newVideoUUID = this.video.video_details.uuid;
        scope.selectedVideo.newVideoName = this.video.video_details.name;

        requestFactory.post(
          requestFactory.getUrl(
            'videos/edit-video/' + this.video.video_details.id
          ),
          scope.selectedVideo,
          function (response) { },
          function (response) { }
        );
      } else {
        self.videoUploadRequestCount++;
        /*
        Only if the replaceVideo tag is false we need to add new inserting into DB
       */
        requestFactory.post(
          requestFactory.getUrl('videos/add'),
          this.video,
          function (response) {
            if (!response.error && response.hasOwnProperty('video')) {
              if (
                scope.videoArray.indexOf(response.video.id) == -1 &&
                response.video.id != ''
              ) {
                scope.fetchGeoInfo(response.video.id);
                var foundIndex = scope.searchVideo(
                  scope.videoArray,
                  response.video.title
                );
                scope.videoArray[foundIndex] = {
                  ...scope.videoArray[foundIndex],
                  ...response.video
                };
                if (scope.selectedVideo.title == response.video.title) {
                  scope.selectedVideo.id = response.video.id;
                  rootScope.detailVideo.push(response.video.id);
                }
                delete scope.randomKeys[response.video.title];
                scope.videoData(scope.videoArray[foundIndex]);
              }
            }
          },
          function () { }
        );
      }
    };

    this.handleDateFormat = function (scheduled_date, type) {
      var result;
      var splitDate = scheduled_date.split('-');
      return splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];
    };

    this.handleTimeFormat = function (scheduled_time) {
      var splitDate = scheduled_time.split(':');
      return splitDate[0] + '-' + splitDate[1] + '-' + splitDate[2];
    };

    this.formatDate = function (date) {
      var month = ('0' + (date.getMonth() + 1)).slice(-2);
      return (
        ('0' + date.getDate()).slice(-2) +
        '-' +
        month +
        '-' +
        date.getFullYear()
      );
    };

    /*
     * Function to set categories of a video in the video edit form.
     */
    this.setCategoriesOfVideos = function () {
      if (scope.currentVideo.is_webseries || scope.currentVideo.is_webseries === 0 || scope.currentVideo.is_webseries === '0') {
        angular.forEach(scope.currentVideo.categories, function (value, key) {
          scope.currentVideo.category = value.id;
        });
      } else {
        if (scope.currentVideo.is_live_value === 1) {
          // scope.currentVideo.category = [];
          // angular.forEach(scope.currentVideo.categories, function(value, key) {
          //   scope.currentVideo.category = value.id;
          // });
          scope.currentVideo.category = [];
          angular.forEach(scope.currentVideo.categories, function (value, key) {
            scope.currentVideo.category.push(value.id);
          });
        } else {
          scope.currentVideo.category = [];
          angular.forEach(scope.currentVideo.categories, function (value, key) {
            scope.currentVideo.category.push(value.id);
          });
        }

      }

    };

    // this.setCategoriesOfVideos = function() {
    //   scope.currentVideo.category = [];
    //   angular.forEach(scope.currentVideo.categories, function(value, key) {
    //     scope.currentVideo.category.push(value.id);
    //   });
    // };

    // this.setGenreOfVideos = function() {
    //   angular.forEach(scope.currentVideo.collections, function(value, key) {
    //     scope.currentVideo.group = value.id.toString();
    //   });
    // };

    this.setGenreOfVideos = function () {
      if (scope.currentVideo.is_webseries || scope.currentVideo.is_webseries === 0 || scope.currentVideo.is_webseries === '0') {
        angular.forEach(scope.currentVideo.collections, function (value, key) {
          scope.currentVideo.group = value.id.toString();
        });
      }
      else {
        scope.currentVideo.group = [];
        angular.forEach(scope.currentVideo.collections, function (value, key) {
          scope.currentVideo.group.push(value.id.toString());
        });
      }
    };

    this.setSeasonsOfVideos = function () {
      angular.forEach(scope.currentVideo.seasons, function (value, key) {
        scope.currentVideo.season = value.id.toString();
      });
    };



    scope.startlivestream = function (record) {
      requestFactory.toggleLoader();
      requestFactory.post(
        requestFactory.getUrl('startlivestream'),
        record,
        function () {
          requestFactory.toggleLoader();
          scope.getRecords(true);
        },
        function () {
          requestFactory.toggleLoader();
        }
      );
    };
    scope.getStatusLive = function () {
      for (var i = 0, len = scope.selectId.length; i < len; i++) {
        getStatusLiveUpdating(scope.selectId[i]);
      }
    };
    var getStatusLiveUpdating = function (record) {
      setTimeout(function () {
        requestFactory.post(
          requestFactory.getUrl('satuslivestream'),
          record,
          function (response) {
            if (response.response === 'starting') {
              getStatusLiveUpdating(record);
            } else {
              scope.getRecords(true);
            }
          },
          function (response) {
            scope.getRecords(true);
          }
        );
      }, 15000);
    };
    scope.stoplivestream = function (record) {
      scope.individualRecord = record;
      scope.stoppedStreamId = record.id;
    };
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
      requestFactory.post(
        requestFactory.getUrl('stoplivestream'),
        scope.individualRecord,
        function () {
          requestFactory.toggleLoader();
          scope.getRecords(true);
        },
        function () {
          requestFactory.toggleLoader();
        }
      );
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
      scope.resetVideo = {};
      scope.resetVideo = { ...scope.resetVideo, ...data };
      scope.selectedVideo = data;
      if (scope.selectedVideo.hasOwnProperty('video_translation')) {
        scope.languageVideo = data.video_translation;
      } else {
        scope.languageVideo = [];
      }

      rootScope.$watch('transcodeVideoDetails', function () {
        if (typeof rootScope.transcodeVideoDetails != 'undefined') {
          rootScope.transcodeVideoDetails.filter(function (item, key) {
            if (item.id == scope.selectedVideo.id) {
              scope.selectedVideo.uploading_percentage = 100;
              scope.selectedVideo.transcodingPercentage =
                item.upload_percentage;
              var transcodeText =
                item.upload_percentage != 100
                  ? 'Transcoding ' + item.upload_percentage + '%'
                  : 'Transcoded';
              scope.selectedVideo.uploading_text = 'Uploaded';
              scope.selectedVideo.uploading_class = 'progress-success active';

              scope.selectedVideo.transcoding_text = transcodeText;

              if (item.job_status == 'Error') {
                scope.selectedVideo.transcoding_class = 'failed active';
              } else {
                scope.selectedVideo.transcoding_class = 'inprogress active';
              }
              scope.selectedVideo.completed_class = '';
              if (item.upload_percentage == '100') {
                scope.selectedVideo.transcoding_text = 'Transcoded';
                scope.selectedVideo.transcoding_class =
                  'progress-success active';
                scope.selectedVideo.completed_class = 'progress-success active';
                scope.selectedVideo.transcodingPercentage = 100;
                scope.transcodeStatus = 'Complete';
                requestFactory.post(
                  requestFactory.getUrl('videos/transcode-status/' + item.id),
                  scope.transcodeStatus,
                  function (response) { }
                );
              }
            }

            var foundIndex = scope.videoArray.findIndex(function (eachVideo) {
              return eachVideo.id === item.id;
            });

            if (typeof foundIndex != 'undefined' && foundIndex >= 0) {
              scope.videoArray[foundIndex].transcodingPercentage =
                item.upload_percentage;
              scope.videoArray[foundIndex].job_status = item.job_status;
            }
          });
        }
      });

      scope.languageChange();
      /** Geo location configurations */
      scope.geo_video_id = scope.selectedVideo.id;
      scope.fetchGeoInfo(scope.selectedVideo.id);
      if (angular.element('.geofence-lists-li').length) {
        angular.element('.geofence-lists-li').removeClass('open');
        angular.element('.geofence-lists-li .content').removeClass('open');
      }
      scope.errors = {};
    };

    this.showReplaceVideo = function (title) {
      window.fineUploader.cancel(0, 'test');
      scope.videoConfirmationReplaceBox = true;
      scope.videotitle = title;
    };

    this.browseReplaceVideo = function () {
      scope.selectedVideo.replaceVideo = true;
    };

    this.saveVideoEdit = function ($event, videoItem) {
      scope.errors = {};
      var videoId = videoItem;
      console.log("submit data:", scope.selectedVideo);

      if (baseValidator.validateAngularForm($event.target, scope)) {
        var isActive = angular.copy(scope.selectedVideo.is_active);
        var isError = 0;
        angular.forEach(scope.subTitles[videoItem], function (value, key) {
          if (!isError && value.language == '' && value.url != '') {
            isError = 1;
          }
        });

        if (isError) {
          return false;
        }
        scope.selectedVideo.geoType = scope.geoType;
        scope.selectedVideo.allowedData = Object.assign(
          {},
          scope.allowedData[videoId]
        );

        let bundles = scope.vgridCtrl.selectedVideo.bundles || [];

        scope.selectedVideo.content_sets = bundles.map(org => {
          return {
            organization_id: org.organization_id || org.id,
            organization_name: org.organization_name,
            liveevent_contentset: (org.bundles || []).map(b => b.id)
          };
        });

        requestFactory.post(
          requestFactory.getUrl('videos/edit/' + videoId),
          scope.selectedVideo,
          function (response) {
            scope.selectedVideo.is_active = scope.selectedVideo.is_active
              ? true
              : false;
            requestFactory.setToaster('success', response.message);

            // To remove not saved status
            $('#' + videoId).removeClass('not-saved');

            // To slide the accordin back to closed state
            var notSaved = document.getElementsByClassName('not-saved');
            if (notSaved.length <= 0) {
              if (scope.selectedVideo.is_live == true) {
                $window.location = requestFactory.getTemplateUrl(
                  'admin/liveevents'
                );
              } else {
                $window.location = requestFactory.getTemplateUrl(
                  'admin/videos'
                );
              }
            }
          },
          function (response) {
            this.fillError(response, videoItem);
          }
        );
      } else {
        scope.errors = {};
        angular.forEach(scope.errors, function (eachmessage, key) {
          if (
            typeof eachmessage == 'object' &&
            eachmessage.hasOwnProperty('message')
          ) {
            scope.errors[key] = {
              has: true,
              message: eachmessage.message
            };
          }
        });
      }
    };

    // ==================================================**************************************************==================================================
    this.saveRadioEdit = function ($event, videoItem) {
      scope.errors = {};
      var videoId = videoItem;

      if (baseValidator.validateAngularForm($event.target, scope)) {
        var isActive = angular.copy(scope.selectedVideo.is_active);
        var isError = 0;
        angular.forEach(scope.subTitles[videoItem], function (value, key) {
          if (!isError && value.language == '' && value.url != '') {
            isError = 1;
          }
        });

        if (isError) {
          return false;
        }
        scope.selectedVideo.geoType = scope.geoType;
        scope.selectedVideo.allowedData = Object.assign(
          {},
          scope.allowedData[videoId]
        );

        requestFactory.post(
          requestFactory.getUrl('videos/edit/' + videoId),
          scope.selectedVideo,
          function (response) {
            scope.selectedVideo.is_active = scope.selectedVideo.is_active
              ? true
              : false;
            requestFactory.setToaster('success', 'Radio saveVideoEditd successfully');

            // To remove not saved status
            $('#' + videoId).removeClass('not-saved');

            // To slide the accordin back to closed state
            var notSaved = document.getElementsByClassName('not-saved');
            if (notSaved.length <= 0) {
              $window.location = requestFactory.getTemplateUrl(
                'admin/radio'
              );

            }
          },
          function (response) {
            this.fillError(response, videoItem);
          }
        );
      } else {
        scope.errors = {};
        angular.forEach(scope.errors, function (eachmessage, key) {
          if (
            typeof eachmessage == 'object' &&
            eachmessage.hasOwnProperty('message')
          ) {
            scope.errors[key] = {
              has: true,
              message: eachmessage.message
            };
          }
        });
      }
    };
    // ==================================================**************************************************==================================================

    this.liveeventFillError = (response) => {
      if (response && response.status === 422 && response.data.errors) {
        angular.forEach(response.data.errors, function (messages, field) {
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
    }

    this.saveLiveVideo = function ($event) {
      scope.errors = {};
      // console.log(scope.selectedVideo);

      if (baseValidator.validateAngularForm($event.target, scope)) {
        scope.selectedVideo.is_active = scope.selectedVideo.is_active
          ? true
          : false;
        scope.selectedVideo.is_notify = scope.selectedVideo.is_notify
          ? true
          : false;
        scope.selectedVideo.is_premium = scope.selectedVideo.is_premium
          ? true
          : false;
        // scope.selectedVideo.is_live = scope.selectedVideo.is_live;

        if (this.videoSubmitted == false) {
          this.videoSubmitted = true;
          requestFactory.post(
            requestFactory.getUrl('createevent'),
            scope.selectedVideo,
            function (response) {
              requestFactory.setToaster('success', response.message);
              window.location.href = requestFactory.getTemplateUrl(
                'admin/liveevents'
              );
            },
            this.liveeventFillError
          );
        }
      }
    };


    this.saveradio = function ($event) {
      scope.errors = {};
      scope.radiopage = true;

      if (baseValidator.validateAngularForm($event.target, scope)) {
        scope.selectedVideo.is_active = scope.selectedVideo.is_active
          ? true
          : false;
        scope.selectedVideo.is_notify = scope.selectedVideo.is_notify
          ? true
          : false;
        scope.selectedVideo.is_premium = scope.selectedVideo.is_premium
          ? true
          : false;

        if (this.videoSubmitted == false) {
          this.videoSubmitted = true;
          requestFactory.post(
            requestFactory.getUrl('createradio'),
            scope.selectedVideo,
            function (response) {
              requestFactory.setToaster('success', response.message);
              window.location.href = requestFactory.getTemplateUrl(
                'admin/radio'
              );
            },
            this.fillError
          );
        }
      }
    };

    this.fillError = function (response) {
      this.videoSubmitted = false;
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
    this.saveLanguage = function (event) {
      scope.selectedLanguageVideo.languageCode = scope.selectedLanguage;
      scope.selectedLanguageVideo.language_id = scope.selectedLanguage;
      requestFactory.post(
        requestFactory.getUrl('videos/addLanguage/' + scope.selectedVideo.id),
        scope.selectedLanguageVideo,
        function (response) {
          requestFactory.setToaster('success', response.message);
          var foundIndex = scope.selectedVideo.video_translation.findIndex(
            function (person) {
              return person.language_id == scope.selectedLanguage;
            }
          );

          if (foundIndex == -1) {
            var newIndex = scope.selectedVideo.video_translation.length;
            scope.selectedVideo.video_translation[newIndex] =
              scope.selectedLanguageVideo;
          }

          if (scope.selectedVideo.is_live == true) {
            $window.location = requestFactory.getTemplateUrl(
              'admin/liveevents'
            );
          } else {
            $window.location = requestFactory.getTemplateUrl('admin/videos');
          }

          // win.location = requestFactory.getTemplateUrl('admin/videos');
        },
        function (response) {
          scope.translationError = true;
          this.fillError(response);
        }
      );
    };

    this.changeCategory = function () {
      if (
        self.allSeries.indexOf(parseInt(scope.selectedVideo.category)) != -1
      ) {
        scope.showSeasons = true;
      } else {
        scope.showSeasons = false;
        scope.selectedVideo.season = '';
      }
    };

    /**
     * Web series on change. Getting web series category groups
     */

    this.webseriesChange = function () {
      scope.showSeasons = false;
      scope.selectedVideo.season = '';
      scope.selectedVideo.category = '';
      if (scope.selectedVideo.is_webseries) {
        this.fetchCategories();
      } else {
        this.setFormCategoriesData(this.allCategories);
      }
    };

    this.changeKids = function () {
      // scope.showSeasons = false;
      // scope.selectedVideo.is_webseries = false;
      // scope.selectedVideo.season = '';
      scope.selectedVideo.is_parental = 0;
      scope.selectedVideo.age_limit = '';
      // scope.selectedVideo.category = '';
      // if (scope.selectedVideo.is_webseries) {
      //   this.fetchCategories();
      // } else {
      //   this.setFormCategoriesData(this.allCategories);
      // }
    };

    /**
     * Get web series categories
     */
    this.fetchCategories = function () {
      requestFactory.get(requestFactory.getUrl('webcategories'), function (
        response
      ) {
        // this.formatCategories = angular.copy(response['webseries_categories']);
        this.setFormCategoriesData(response['webseries_categories']);
      });
    };

    /**
     * method to get from categories
     */
    this.setFormCategoriesData = function (allCategories) {
      this.formatCategories = [];
      this.formatCategories = angular.copy(allCategories);
      var result = [];
      this.formatCategories.forEach(function (item, index) {
        if (item.id) {
          if (item.child_category.length > 0) {
            item.child_category.forEach(function (child, i) {
              var newIndex = result.length;
              result[newIndex] = {};
              result[newIndex].id = child.id;
              result[newIndex].title = child.title;
              result[newIndex].parent = item.title;
            });
          } else {
            var newIndex = result.length;
            result[newIndex] = {};
            result[newIndex].id = '';
            result[newIndex].title = '';
            result[newIndex].parent = item.title;
          }
        }
      });
      this.formatCategories = result;
    };

    this.defineProperties = function (data) {
      this.info = data.info;
      this.allCollection = data.info.allCollection;
      this.allSeasons = data.info.allSeasons;
      this.allExams = data.info.allCollection;
      this.allCategories = data.info.allCategories;
      this.radioCategories = data.info.allRdioCategories;
      this.liveCategories = data.info.allLiveCategories;
      this.formatCategories = angular.copy(this.allCategories);
      var result = [];
      this.formatCategories.forEach(function (item, index) {
        if (item.id) {
          if (item.child_category.length > 0) {
            item.child_category.forEach(function (child, i) {
              var newIndex = result.length;
              result[newIndex] = {};
              result[newIndex].id = child.id;
              result[newIndex].title = child.title;
              result[newIndex].parent = item.title;
            });
          } else {
            var newIndex = result.length;
            result[newIndex] = {};
            result[newIndex].id = '';
            result[newIndex].title = '';
            result[newIndex].parent = item.title;
          }
        }
      });
      this.formatCategories = result;
      this.allSeries = data.info.allSeries;
      this.language = data.info.language;
      this.ads_info = data.info.ads_info;
      if (this.language.length != 0) {
        scope.selectedLanguage = this.language[0].id;
        scope.defaultLanguage = this.language[0].id;
      }
      this.transcodedInfo = data.info.transcodedInfo;
      scope.livedetails = data.info.livesyncdata[0];
      this.numberOfActivePresets = data.info.numberOfActivePresets;
      baseValidator.setRules(this.info.video_edit_rules);
      angular.element('#move_collection').removeAttr('data-toggle');
      this.setupVideoUploader();

      requestFactory.toggleLoader();
    };

    this.resetFormData = function (event) {
      this.collection = {};
      scope.errors = {};
      requestFactory.get(
        requestFactory.getUrl('videos/collection-update'),
        function (response) {
          this.allCollection = response.info.allCollection;
          baseValidator.setRules(this.info.video_edit_rules);
        }
      );
      this.showcreateCollection = true;
      this.collection.id = String(0);
    };

    this.deleteBulkRecord = function () {
      scope.deleteParams = this.selectedRecords;
      this.isDeactivateBulkRecord = false;
      this.isActivateBulkRecord = false;
      this.isDeleteBulkRecord = true;
    };

    this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
      scope.activateParams = this.selectedRecords;
      if ($isActivateOrDeactivate == 'activate') {
        this.isDeleteBulkRecord = false;
        this.isDeactivateBulkRecord = false;
        this.isActivateBulkRecord = true;
      } else if ($isActivateOrDeactivate == 'deactivate') {
        this.isDeleteBulkRecord = false;
        this.isActivateBulkRecord = false;
        this.isDeactivateBulkRecord = true;
      }
    };

    this.cancelDeleteVideos = function () {
      scope.videoConfirmationDeleteBox = false;
      scope.deleteParams = '';
    };

    this.cancelResetForm = function () {
      var myIndex = scope.searchVideo(
        scope.videoArray,
        scope.selectedVideo.title
      );
      scope.videoArray[myIndex] = {
        ...scope.videoArray[myIndex],
        ...scope.resetVideo
      };
      scope.selectedVideo = {};
      scope.selectedVideo = { ...scope.selectedVideo, ...scope.resetVideo };
    };

    scope.searchVideo = function (inputArray, title) {
      return inputArray.findIndex(function (person) {
        return person.key == scope.randomKeys[title];
      });
    };

    this.cancelReplaceVideos = function () {
      scope.videoConfirmationReplaceBox = false;
    };

    this.confirmReplaceVideos = function () {
      scope.videoConfirmationReplaceBox = false;
      scope.selectedVideo.showProgress = false;
      scope.selectedVideo.showReplace = true;
    };

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
    };

    this.activateOrDeactivateRecordsVideos = function (id, is_status) {
      scope.activateParams = '';
      scope.showRecords = false;
      scope.gridLoadingBar = true;
      var activateIdLength = id.length;
      var isStatus = is_status == 1 ? 'activate' : 'deactivate';

      requestFactory.toggleLoader();
      scope.deleteRequest = requestFactory.post(
        requestFactory.getUrl('videos/bulk-update-status'),
        angular.extend(
          {},
          {
            selectedCheckbox: id,
            isStatus: isStatus
          },
          scope.requestParams
        ),
        function (data) {
          requestFactory.toggleLoader();
          requestFactory.setToaster('success', data.message);
          this.selectedRecords = [];
          angular.element('#selectall').removeAttr('checked');
          if (scope.records.length - activateIdLength > 0) {
            scope.getRecords(true);
          } else {
            scope.currentPage =
              scope.currentPage - 1 == 0 ? 1 : scope.currentPage - 1;
            scope.getRecords(true);
          }
        }
      );
      angular.element('#move_collection').removeAttr('data-toggle');
    };

    this.init = function () {
      scope.livePage = true;
      scope.selectedVideo.liveType = 'hls';
      scope.selectedVideo.aspect_ratio = '640X360';
    };

    this.fetchInfo = function () {
      requestFactory.get(
        requestFactory.getUrl('videos/info'),
        this.defineProperties,
        function (response) {
          rootScope.redirectUnauthenticated(response);
        }
      );
    };

    this.fetchInfo();

    if ($('#thumb-image').length) {
      window.VideoThumbnailUploadHandler = new uploadHandler();
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
          document.getElementById('menu-7').style.transitionProperty = 'none';
          document.querySelector('.st-pusher').style.transitionProperty =
            'none';
        } else {
          // Remove back the transition value none so that the video edit sidebar closes and opens smoothly.
          document.getElementById('menu-7').style.removeProperty('transition');
          document
            .querySelector('.st-pusher')
            .style.removeProperty('transition');
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
          angular.element('#move_collection').attr('data-toggle', 'modal');

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
    };
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
        angular.element('#move_collection').attr('data-toggle', 'modal');
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
    };
    /**
     *  Function is used to save the collection
     *
     *  @param $event
     *
     */
    this.save = function ($event) {
      if (baseValidator.validateAngularForm($event.target, scope)) {
        this.collection.selectedVideos = this.selectedRecords;
        requestFactory.post(
          requestFactory.getUrl('collections/add'),
          this.collection,
          function (response) {
            this.fetchInfo();
            angular.element('.close').click();
            requestFactory.toggleLoader();
            angular.element('.checkbox').attr('checked', false);
            angular.element('#selectall').prop('checked', false);
            this.selectedRecords = [];
            requestFactory.setToaster('success', response.message);
          },
          this.fillError
        );
      }
    };

    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
      scope.routeName = 'videos';
      scope.updateStatus(record);
    };

    /**
     *  Listen to the records to update property
     *
     */
    scope.$on('afterGetRecords', function (e, data) {
      if (angular.isUndefined(scope.searchRecords.is_active)) {
        scope.searchRecords.is_active = 'all';
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
      angular.element('.checkbox').attr('checked', false);
      scope.getStatusLive();

      setTimeout(function () {
        $('#fixTable').tableHeadFixer({ head: false, right: 1 });
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
          self.fetchProgress();
        }, 2000);
      }
    });

    this.fetchProgress = function () {
      requestFactory.post(
        requestFactory.getUrl('videos/progress'),
        angular.extend(
          {},
          {
            video_ids: scope.progressArray
          }
        ),
        function (response) {
          if (response.response.video_info.length > 0) {
            response.response.video_info.filter(function (item, key) {
              var index = scope.indexArray[item.id];

              scope.records[index].upload_percentage = item.upload_percentage;

              scope.records[index].job_status = item.job_status;
            });

            self.transcodedInfo = response.response.transcode_info;
          }
        },
        function () { }
      );
    };
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
    }

    $(document).ready(function () {
      /*
       * Thumb Image Upload Part
       */
      var image = document.getElementById('image');
      $(document).on('change', '.uploadImg', function (e) {
        var videoItem = $(this).data('video-index');
        scope.errors = {};
        var ValidImageTypes = ['image/jpeg', 'image/png'];
        var files = e.target.files;
        var fileType = files[0].type;
        if ($.inArray(fileType, ValidImageTypes) < 0) {
          scope.$apply();

          // BEGIN : To show invalid error message in the croppre box
          $('#modal').modal('show');
          $('.crop-body').hide();
          $('#submit-image').hide();
          $('.error_msg')
            .show()
            .text(
              'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
            );
          // END : To show invalid error message in the croppre box
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
        // By default hide the error and show submit button when popup opens, then based on the validation we hide/show the details in the same popup
        $('#submit-image').show();
        $('.error_msg').hide();
        setTimeout(function () {
          cropper = new Cropper(image, {
            autoCropArea: 1,
            viewMode: 1,
            aspectRatio: 338 / 170,
            preview: '.img-preview',
            cropBoxResizable: false,
            minCropBoxWidth: 338,
            minCropBoxHeight: 170,
            autoCrop: true,
            dragCrop: false,
            mouseWheelZoom: false,
            resizable: false,
            ready: function () {
              //Should set crop box data first here
              var config = { left: 0, top: 0, width: 338, height: 170 };
              cropper.setCropBoxData(config).setCanvasData(canvasData);
            }
          });
        }, 500);
      });
      $(document).on('hidden.bs.modal', '#modal', function () {
        document.getElementsByClassName('uploadImg')[0].value = '';
        $('#submit-image').prop('disabled', false);
        cropper.destroy();
      });
      $(document).on(
        'click',
        '#submit-image',
        requestFactory.access_token,
        function () {
          cropBoxData = cropper.getCropBoxData();
          canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
            var formData = new FormData();
            formData.append('module', 'video');
            formData.append('size', 'thumb');
            formData.append('image', blob);
            $('.crop-body').hide();
            $('.loader-container').show();
            $('#submit-image').prop('disabled', true);
            $.ajax(
              $('meta[name="base-api-url"]').attr('content') +
              '/videos/thumbnail',
              {
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function (request) {
                  request.setRequestHeader(
                    'Authorization',
                    'Bearer ' + requestFactory.access_token
                  );
                },
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
                  $('.error_msg')
                    .show()
                    .text(
                      'Please upload bigger image, click cancel to continue'
                    );
                }
              }
            );
          }, 'image/jpeg');
        }
      );

      /*
       * Post Image Upload Part
       */
      var posterImage = document.getElementById('poster_image');
      $(document).on('change', '.uploadPosterImg', function (e) {
        var videoItem = $(this).data('video-index');
        scope.errors[videoItem] = {};
        var ValidImageTypes = ['image/jpeg', 'image/png'];
        var files = e.target.files;
        var fileType = files[0].type;
        if ($.inArray(fileType, ValidImageTypes) < 0) {
          scope.$apply();
          // BEGIN : To show invalid error message in the croppre box
          $('#poster_modal').modal('show');
          $('.crop-body').hide();
          $('#submit_poster_image').hide();
          $('.poster_error_msg')
            .show()
            .text(
              'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
            );
          // END : To show invalid error message in the croppre box
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
        $('#submit_poster_image').show();
        $('.poster_error_msg').hide();
        setTimeout(function () {
          cropperImg = new Cropper(posterImage, {
            autoCropArea: 1,
            viewMode: 3,
            aspectRatio: 1180 / 600,
            preview: '.poster_img-preview',
            cropBoxResizable: false,
            minCropBoxWidth: 1180,
            minCropBoxHeight: 600,
            autoCrop: true,
            dragCrop: false,
            mouseWheelZoom: false,
            resizable: false,
            ready: function () {
              //Should set crop box data first here
              var config = { left: 0, top: 0, width: 1180, height: 600 };
              cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
            }
          });
        }, 500);
      });
      $(document).on('hidden.bs.modal', '#poster_modal', function () {
        document.getElementsByClassName('uploadPosterImg')[0].value = '';
        $('#submit_poster_image').prop('disabled', false);
        cropperImg.destroy();
      });
      $(document).on(
        'click',
        '#submit_poster_image',
        requestFactory.access_token,
        function () {
          cropBoxImgData = cropperImg.getCropBoxData();
          canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
            var formImgData = new FormData();
            formImgData.append('module', 'video');
            formImgData.append('size', 'poster');
            formImgData.append('image', blob);
            $('.crop-body').hide();
            $('.poster_loader-container').show();
            $('#submit_poster_image').prop('disabled', true);
            $.ajax(
              $('meta[name="base-api-url"]').attr('content') + '/videos/poster',
              {
                method: 'POST',
                data: formImgData,
                processData: false,
                contentType: false,
                beforeSend: function (request) {
                  request.setRequestHeader(
                    'Authorization',
                    'Bearer ' + requestFactory.access_token
                  );
                },
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
                  $('.poster_error_msg')
                    .show()
                    .text(
                      'Please upload bigger image, click cancel to continue'
                    );
                }
              }
            );
          }, 'image/jpeg');
        }
      );
    });
    /**
     * End of image upload script
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
    }

    scope.$watch('videoConfirmationDeleteBox', function () {
      if (!scope.videoConfirmationDeleteBox) {
        $('#videoDeleteModal').modal('hide');
      }
    });

    scope.$watch('videoConfirmationReplaceBox', function () {
      if (!scope.videoConfirmationReplaceBox) {
        $('#videoReplaceModal').modal('hide');
      }
    });
    scope.$watch('removeTrailerConfirmation', function () {
      if (!scope.removeTrailerConfirmation) {
        $('#removeTrailerModel').modal('hide');
      }
    });

    /*
     * Function to delete admin video view detail page.
     */
    this.deleteSingleRecordVideos = function (id, title) {
      scope.deleteParams = [id];
      scope.videoConfirmationDeleteBox = true;
      scope.videotitle = title;
    };

    scope.languageChange = function () {
      scope.errors = [];

      if (scope.defaultLanguage == scope.selectedLanguage) {
        $('#videoEditForm').css('display', 'block');
        $('#languageForm').css('display', 'none');
        $('#videoEditFormSubmit').css('display', 'inline-block');
        $('#videoLanguageEditFormSubmit').css('display', 'none');
      } else {
        // self.languageVideo = {};
        scope.selectedLanguageVideo = {};
        angular.forEach(scope.languageVideo, function (value) {
          if (value.language_id == scope.selectedLanguage) {
            scope.selectedLanguageVideo = value;
          }
        });
        $('#videoEditForm').css('display', 'none');
        $('#languageForm').css('display', 'block');
        $('#videoEditFormSubmit').css('display', 'none');
        $('#videoLanguageEditFormSubmit').css('display', 'inline-block');
      }
    };

    // BEGIN : SUBTITLE BLOCK
    this.addSubTitle = function () {
      // To reset subtitle
      var subTitleElem = document.getElementsByName('subtitle_file');

      if (subTitleElem) {
        subTitleElem[0].value = '';
        subTitleElem[0].title = '';
      }
      scope.subTitles = [];
      scope.subTitleValidation = false;
      scope.subTitles.push({
        label: '',
        kind: '',
        url: '',
        language: ''
      });
      scope.errors = {};
    };

    this.deleteRecordsVideos = function (id, videoStatus) {
      var globalScope = scope;
      scope.deleteParams = '';

      scope.deleteRequest = requestFactory.post(
        requestFactory.getUrl('videos/delete-action'),
        angular.extend(
          {},
          {
            selectedCheckbox: id,
            videoStatus: videoStatus
          },
          scope.requestParams
        ),
        function (data) {
          var result = globalScope.videoArray.filter(function (item) {
            if (id.indexOf(item.id) == -1) {
              return item;
            }
          });

          globalScope.videoArray = result;
          if (result.length > 0) {
            globalScope.videoData(globalScope.videoArray[0]);
          }

          requestFactory.setToaster('success', data.message);

          let currentUrl = window.location.href;
          if (
            ($('.not-saved').length <= 0 || result.length <= 0) &&
            currentUrl.includes('videos')
          ) {
            $window.location = requestFactory.getTemplateUrl('admin/videos');
          }
          if (
            ($('.not-saved').length <= 0 || result.length <= 0) &&
            currentUrl.includes('liveevents')
          ) {
            $window.location = requestFactory.getTemplateUrl(
              'admin/liveevents'
            );
          }
          if (
            ($('.not-saved').length <= 0 || result.length <= 0) &&
            currentUrl.includes('radio')
          ) {
            requestFactory.setToaster('success', 'Radio deleted successfully');
            $window.location = requestFactory.getTemplateUrl(
              'admin/radio'
            );
          }
        }
      );
    };

    scope.getTheFiles = function ($files, videoItem) {
      scope.subTitleValidation = true;
      scope.subTitleForm = true;
      angular.forEach($files, function (value, key) {
        scope.$apply(function () {
          var fileName = value.type;
          if (fileName != '') {
            var extension = fileName.split('/')[1];
          } else {
            var extension = value.name.split('.').pop();
          }

          if (
            extension == 'vtt' ||
            extension == 'srt' ||
            extension == 'x-subrip'
          ) {
            scope.subTitles['url'] = value;

            document.getElementById('subtitle_file').title = value.name;
          } else {
            document.getElementById('subtitle_file').title = '';
            scope.subTitles['url'] = 'error';
            scope.errors['url'] = {
              has: false
            };
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
    };

    this.deleteSubtitle = function (videoId) {
      requestFactory.post(
        requestFactory.getUrl('videos/delete-subtitle/' + videoId),
        { subtitle: scope.subTitles },
        function () { },
        function () { }
      );
    };

    scope.subTitleSubmit = function (videoItem) {
      var formdatas = new FormData();
      if (
        scope.subTitles.language != undefined &&
        scope.subTitles.url != undefined &&
        scope.subTitles.language != '' &&
        scope.subTitles.url != '' &&
        scope.subTitles.url != 'error'
      ) {
        scope.subTitleForm = true;
        formdatas.append(scope.subTitles.language, scope.subTitles.url);
      } else {
        if (
          scope.subTitles.language == '' ||
          scope.subTitles.language == undefined
        ) {
          scope.errors['language'] = {
            has: true
          };
        } else {
          scope.errors['language'] = {
            has: false
          };
        }
        if (scope.subTitles.url == '' || scope.subTitles.url == undefined) {
          scope.errors['url'] = {
            has: true
          };
        } else {
          scope.errors['url'] = {
            has: false
          };
        }
        scope.subTitleForm = false;
        //return
      }
      if (scope.subTitleForm) {
        // Inorder to make browser to detech the request and set content-type automatically set it to undefined
        requestFactory.setHeaders('Content-Type', undefined);
        requestFactory.post(
          requestFactory.getUrl('videos/upload-subtitles?id=' + videoItem),
          formdatas,
          function (response) {
            scope.selectedVideo.showsubTitleList = true;
            scope.selectedVideo.showMainSubtitle = false;
            scope.selectedVideo.subTitleList = response.response;
            $('#subtitle').modal('hide');
          },
          function (response) {
            this.fillError(response);
          }
        );

        requestFactory.setHeaders('Content-Type', 'application/json');
      }
    };
    // END : SUBTITLE BLOCK

    // BEGIN : AUDIOS UPLOAD BLOCK
    this.prepareAudioUpload = function () {
      scope.isAudioFileUploaded = false;
      // To reset subtitle
      var audioFileElem = document.getElementsByName('audio_file');
      if (audioFileElem && audioFileElem.length > 0) {
        audioFileElem[0].value = '';
        audioFileElem[0].title = '';
      }
      scope.audios = [];
      scope.AudioValidation = false;
      scope.errors = {};
    };

    // Video trailer upload start
    scope.validateTrailer = function (file, videoItem) {
      angular.forEach(file, function (value, key) {
        if (value) {
          var fileName = value.type;
          document.getElementById('trailer-show-name').innerHTML = value.name;
          document.getElementById('trailer-show-success').innerHTML = '';
          if (fileName != '') {
            var extension = fileName.split('/')[1];
          } else {
            var extension = value.name.split('.').pop();
          }
          var fileSize = Math.round(value.size / (1024 * 1024));
          if (extension === 'mp4' && fileSize < 15) {
            scope.trailer['trailer_file'] = value;
            document.getElementById('trailer_file').title = value.name;
            scope.trailerFilename = value.name;
          } else {
            if (!(extension === 'mp4')) {
              scope.trailer['trailer_file'] = 'error';
            }
            if (!(fileSize < 15)) {
              scope.trailer['trailer_file'] = 'size-error';
            }
            scope.TrailerForm = false;
          }
        }
        $('#trailer-show-button').css('display', 'block');
        $('#trailer-show-name').css('display', 'block');
      });
    };
    scope.addTrailer = function (videoItem) {
      var formdata = new FormData();
      if (
        scope.trailer['trailer_file'] !== 'error' &&
        scope.trailer['trailer_file'] !== 'size-error'
      ) {
        scope.TrailerForm = true;
        formdata.append('trailer', scope.trailer.trailer_file);
        formdata.append('trailer_name', scope.trailerFilename);
      } else {
        scope.TrailerForm = false;
      }
      if (scope.TrailerForm) {
        // Inorder to make browser to detech the request and set content-type automatically set it to undefined
        requestFactory.setHeaders('Content-Type', undefined);
        $('#trailer-uploading-button').css('display', 'block');
        $('#trailer-show-button').css('display', 'none');

        requestFactory.post(
          requestFactory.getUrl('videos/upload-trailer?id=' + videoItem),
          formdata,
          function (response) {
            scope.selectedVideo.trailer_url = response
              ? response.response.trailer_url
              : '';
            scope.selectedVideo.trailer_updated = 1;
            scope.trailerSucess = response;
            if (response.error === false) {
              document.getElementById('trailer-show-success').innerHTML =
                'File uploaded. Please save the changes.';
              $('#trailer-uploading-button').css('display', 'none');
              $('#trailer-show-button').css('display', 'none');
            }
          },
          function (response) {
            this.trailerFillError(response);
          }
        );
        requestFactory.setHeaders('Content-Type', 'application/json');
      }
    };
    this.removeTrailer = function (id, title) {
      scope.removeTrailerConfirmation = true;
      scope.videotitle = title;
    };
    this.confirmRemoveTrailer = function (videoStatus) {
      scope.removeTrailerConfirmation = false;
      scope.selectedVideo.trailer_url = '';
      scope.selectedVideo.trailer_updated = 0;
      if (scope.trailerSucess) scope.trailerSucess.error = '';
      document.getElementById('trailer-show-success').innerHTML = '';
      document.getElementById('trailer-show-name').innerHTML = '';
    };
    this.cancelRemoveTrailer = function () {
      scope.removeTrailerConfirmation = false;
      selectedVideo.trailer_url = '';
      scope.selectedVideo.trailer_updated = 0;
      if (scope.trailerSucess) scope.trailerSucess.error = '';
      document.getElementById('trailer-show-success').innerHTML = '';
      document.getElementById('trailer-show-name').innerHTML = '';
    };
    this.cancelRemoveTrailer = function () {
      scope.removeTrailerConfirmation = false;
    };
    this.trailerFillError = function (response) {
      scope.trailerUploadStatus.error = true;
      scope.trailerUploadStatus.isTrailerUploading = false;
      scope.trailerUploadStatus.isTrailerUploaded = false;
      scope.trailerUploadStatus.isTrailerAdded = false;
      $('#trailer-uploading-button').css('display', 'none');
      $('#trailer-show-button').css('display', 'block');
      if (response) {
        if (response.data.hasOwnProperty('exception')) {
          scope.trailerUploadErrMsg = response.statusText;
        } else if (
          response.data.hasOwnProperty('message') &&
          response.data.message != null
        ) {
          scope.trailerUploadErrMsg = response.data.message;
        } else {
          scope.trailerUploadErrMsg = 'Unknown Error occured';
        }
      }
    };
    // Video trailer upload end

    scope.validateAudioFile = function ($file, videoItem) {
      scope.audioUploadStatus.isAudioAdded = true;
      scope.audioUploadStatus.isAudioUploading = false;
      scope.audioUploadStatus.isAudioUploaded = false;
      scope.audioUploadStatus.error = false;
      scope.AudioValidation = true;
      scope.AudioForm = true;
      angular.forEach($file, function (value, key) {
        scope.$apply(function () {
          var fileName = value.type;
          if (fileName != '') {
            var extension = fileName.split('/')[1];
          } else {
            var extension = value.name.split('.').pop();
          }
          if (extension === 'mp3' || extension === 'mpeg') {
            scope.audios['audio_file'] = value;
            document.getElementById('audio_file').title = value.name;
            scope.isAudioFileUploaded = true;
            scope.audioFilename = value.name;
          } else {
            scope.audioUploadStatus.isAudioAdded = false;
            document.getElementById('audio_file').title = '';
            scope.isAudioFileUploaded = false;
            scope.audios['audio_file'] = 'error';
            scope.errors['audio_file'] = {
              has: false
            };
          }
        });
      });
    };
    scope.addAudio = function (videoItem) {
      var formdatas = new FormData();
      if (
        scope.audios.audio_name != undefined &&
        scope.audios.audio_file != undefined &&
        scope.audios.audio_name != '' &&
        scope.audios.audio_file != '' &&
        scope.audios.audio_file != 'error'
      ) {
        scope.AudioForm = true;
        formdatas.append(scope.audios.audio_name, scope.audios.audio_file);
        formdatas.append('audio_name', scope.audios.audio_name);
        scope.audioUploadStatus.isAudioAdded = false;
        scope.audioUploadStatus.error = false;
        scope.audioUploadStatus.isAudioUploading = true;
      } else {
        if (
          scope.audios.audio_name == '' ||
          scope.audios.audio_name == undefined
        ) {
          scope.errors['audio_name'] = { has: true };
        } else {
          scope.errors['audio_name'] = { has: false };
        }
        if (
          scope.audios.audio_file == '' ||
          scope.audios.audio_file == undefined
        ) {
          scope.errors['audio_file'] = { has: true };
        } else {
          scope.errors['audio_file'] = { has: false };
        }
        scope.AudioForm = false;
      }
      if (scope.AudioForm) {
        // Inorder to make browser to detech the request and set content-type automatically set it to undefined
        requestFactory.setHeaders('Content-Type', undefined);
        requestFactory.post(
          requestFactory.getUrl('videos/upload-audios?id=' + videoItem),
          formdatas,
          function (response) {
            scope.selectedVideo.showIfNoAudios = false;
            scope.selectedVideo.audioTrackList = response.response;
            scope.audioUploadStatus.isAudioUploading = false;
            scope.audioUploadStatus.isAudioUploaded = true;
            scope.audioUploadStatus.error = false;
            setTimeout(function () {
              $('#audios').modal('hide');
              scope.audios = [];
            }, 2000);
          },
          function (response) {
            this.audioFillError(response);
          }
        );
        requestFactory.setHeaders('Content-Type', 'application/json');
      }
    };
    this.audioFillError = function (response) {
      scope.audioUploadStatus.error = true;
      scope.audioUploadStatus.isAudioUploading = false;
      scope.audioUploadStatus.isAudioUploaded = false;
      scope.audioUploadStatus.isAudioAdded = false;
      if (response) {
        if (response.data.hasOwnProperty('exception')) {
          scope.audioUploadErrMsg = response.statusText;
        } else if (
          response.data.hasOwnProperty('message') &&
          response.data.message != null
        ) {
          if (typeof response.data.message == 'string') {
            scope.audioUploadErrMsg = response.data.message;
          } else {
            angular.forEach(response.data.message, function (message, key) {
              if (typeof message == 'object' && message.length > 0) {
                scope.audioUploadErrMsg = message[0];
              }
            });
          }
        } else {
          scope.audioUploadErrMsg = 'Unknown Error occured';
        }
      }
    };
    this.audioTrackDelete = function (index, videoId, audioTrackID) {
      scope.audios = scope.selectedVideo.audioTrackList[index];
      if (scope.audios.url != '') {
        this.deleteAudioTrack(audioTrackID);
      }
      scope.selectedVideo.audioTrackList.splice(index, 1);
      scope.selectedVideo.showIfNoAudios =
        scope.selectedVideo.audioTrackList.length == 0 ? true : false;
    };
    this.deleteAudioTrack = function (audioTrackID) {
      requestFactory.post(
        requestFactory.getUrl('videos/delete-audiotrack/' + audioTrackID),
        {},
        function () { },
        function () { }
      );
    };

    // END : AUDIOS UPLOAD BLOCK

    // ==================================================**************************************************==================================================
    this.fetchData = function (id) {
      requestFactory.get(
        requestFactory.getUrl('videos/video-id/' + id),
        function (response) {
          scope.encrytpedVideoId = response.response;
        }
      );

      scope.editPage = true;
      requestFactory.get(
        requestFactory.getUrl('videos/video-to-edit/' + id),
        function (response) {
          var multipleVideos = response.response;
          var videoSelf = this;

          if (response && response.response && response.response.length > 0) {
            scope.selectedVideo = response.response[0];
            // console.log("hello:", scope.selectedVideo);

            // ✅ Parse content_sets safely
            let bundles = [];
            let parsedContentSets = [];

            if (typeof scope.selectedVideo.content_sets === "string") {
              try {
                parsedContentSets = JSON.parse(scope.selectedVideo.content_sets);
              } catch (e) {
                console.error("❌ Invalid JSON in content_sets:", e);
              }
            }

            // ✅ Populate selectedVideo.organization for the UI Dropdown using get_all_organization if available
            if (scope.selectedVideo.get_all_organization && Array.isArray(scope.selectedVideo.get_all_organization)) {
              scope.selectedVideo.organization = scope.selectedVideo.get_all_organization.map(org => org.id);
            } else {
              scope.selectedVideo.organization = parsedContentSets.map(org => org.organization_id);
            }

            // ✅ Determine which set to use for Assigned Bundle List
            // User wants ONLY content_sets data to be shown
            if (parsedContentSets.length > 0) {
              bundles = parsedContentSets;
            } else if (scope.selectedVideo.get_all_organization && Array.isArray(scope.selectedVideo.get_all_organization)) {
              bundles = scope.selectedVideo.get_all_organization.map(org => ({
                organization_id: org.id,
                organization_name: org.organization_name
              }));
            } else if (Array.isArray(scope.selectedVideo.content_sets)) {
              bundles = scope.selectedVideo.content_sets;
            }

            // scope.selectedVideo.organization = bundles.map(org => org.organization_id); [Handled above]
            const allBundles = scope.selectedVideo.channel_sets || [];

            const mergedOrganizations = bundles.map(org => {
              const orgBundles = allBundles.filter(b => b.organization_id === org.organization_id);
              return {
                organization_id: org.organization_id,
                organization_name: org.organization_name,
                bundles: orgBundles
              };
            });

            this.selectedVideo = this.selectedVideo || {};
            this.selectedVideo.bundles = mergedOrganizations;

            scope.selectedVideo.selectedBundles = mergedOrganizations;
            // console.log("✅ Final selectedBundles:", scope.selectedVideo.selectedBundles);

            scope.selectedVideo.scheduled_publishing = (scope.selectedVideo.scheduled_publishing == 1);
            scope.selectedVideo.catch_up_status = (scope.selectedVideo.catch_up_status == 1);
            scope.selectedVideo.live_rewind_status = (scope.selectedVideo.live_rewind_status == 1);

            scope.selectedVideo.playback_token = parseInt(scope.selectedVideo.playback_token);
            scope.selectedVideo.policy = parseInt(scope.selectedVideo.policy);
          }

          setTimeout(() => {
            $('.hello').datetimepicker({
              format: "YYYY-MM-DD HH:mm:ss",
            })
          }, 1000);

          multipleVideos.map(function (eachVideo, index) {
            var videoDetails = eachVideo;
            if (eachVideo.subtitle != '') {
              scope.subTitles = JSON.parse(eachVideo.subtitle);
            }
            if (eachVideo.video_audio_tracks != '') {
              scope.audios = eachVideo.video_audio_tracks;
            }
            scope.currentVideo = {};
            scope.currentVideo = { ...scope.currentVideo, ...eachVideo };

            scope.currentVideo.is_live_value = scope.currentVideo.is_live;

            scope.currentVideo.is_active = scope.currentVideo.is_active
              ? true
              : false;
            scope.currentVideo.is_kids = scope.currentVideo.is_kids
              ? true
              : false;
            scope.currentVideo.is_notify = scope.currentVideo.is_notify
              ? true
              : false;
            scope.currentVideo.is_premium = scope.currentVideo.is_premium
              ? true
              : false;
            scope.currentVideo.is_webseries = scope.currentVideo.is_webseries
              ? true
              : false;
            scope.currentVideo.episode_order = scope.currentVideo.episode_order;
            scope.currentVideo.is_live = scope.currentVideo.is_live
              ? true
              : false;
            scope.currentVideo.trailer_url = scope.currentVideo.trailer_url;
            scope.selectedVideo.trailer_updated = 0;

            if (scope.currentVideo.is_live) {
              scope.currentVideo.liveType = scope.currentVideo.is_hls
                ? 'hls'
                : 'aspect_ratio';
              scope.currentVideo.hls = scope.currentVideo.is_hls
                ? scope.currentVideo.hls_playlist_url
                : '';
              scope.currentVideo.aspect_ratio = scope.currentVideo.is_hls
                ? '640X360'
                : '1280X720';
              scope.livePage = true;
            } else {
              scope.livePage = false;
            }
            scope.currentVideo.showMainSubtitle = true;
            scope.currentVideo.showIfNoAudios = true;

            scope.currentVideo.showReplace = scope.currentVideo.is_live
              ? false
              : true;
            scope.currentVideo.replaceVideo = false;
            scope.currentVideo.uuid = '';

            if (scope.currentVideo.subtitle != '') {
              scope.currentVideo.subTitleList =
                scope.currentVideo.subtitle != ''
                  ? JSON.parse(scope.currentVideo.subtitle)
                  : '';
              scope.currentVideo.subtitle =
                scope.currentVideo.subtitle != ''
                  ? JSON.parse(scope.currentVideo.subtitle)
                  : '';
              if (scope.currentVideo.subTitleList.length >= 1) {
                scope.currentVideo.showsubTitleList = true;
                scope.currentVideo.showMainSubtitle = false;
              }
            }

            if (scope.currentVideo.video_audio_tracks != '') {
              scope.currentVideo.audioTrackList =
                scope.currentVideo.video_audio_tracks != ''
                  ? scope.currentVideo.video_audio_tracks
                  : '';
              scope.currentVideo.audios =
                scope.currentVideo.video_audio_tracks != ''
                  ? scope.currentVideo.video_audio_tracks
                  : '';
              if (scope.currentVideo.audioTrackList.length >= 1) {
                scope.currentVideo.showIfNoAudios = false;
              }
            }

            scope.currentVideo.thumbnail = scope.currentVideo.thumbnail_image;
            scope.currentVideo.is_thumbnail_updated = 0;
            scope.currentVideo.is_posterimg_updated = 0;
            scope.currentVideo.is_scheduled_date_updated = 0;

            if (scope.currentVideo.ads.length > 0) {
              scope.currentVideo.ads = parseInt(videoDetails.ads[0].id);
            }

            scope.currentVideo.scheduled_time =
              scope.currentVideo.scheduledStartTime;

            scope.currentVideo.expire_scheduled_time =
              scope.currentVideo.scheduledEndTime;

            scope.showSeasons =
              scope.currentVideo.seasons.length > 0 ? true : false;
            if (!scope.currentVideo.published_on == true) {
              var getDate = scope.currentVideo.created_at;
              var date = new Date(getDate.split(' ')[0]);
              scope.currentVideo.published_on = self.formatDate(date);
            } else {
              var date = new Date(scope.currentVideo.published_on);
              scope.currentVideo.published_on = self.formatDate(date);
            }

            scope.currentVideo.search_tag = [];
            if (scope.currentVideo.tags.length > 0) {
              scope.currentVideo.search_tag = scope.currentVideo.tags.map(
                function (val) {
                  var obj = {};
                  obj.text = val.name;
                  return obj;
                }
              );
            }

            self.setCategoriesOfVideos();
            self.setGenreOfVideos();
            self.setSeasonsOfVideos();

            scope.videoArray.push(scope.currentVideo);

            rootScope.detailVideo.push(scope.currentVideo.id);

            if (index == 0) {
              scope.videoData(scope.currentVideo);
            }

            // Fetching we series categories and assign to fromcategories
            if (scope.currentVideo.is_webseries) {
              self.fetchCategories();
            } else {
              self.setFormCategoriesData(self.allCategories);
            }
          });

          scope.$applyAsync();

          scope.$applyAsync(() => {
            self.fetchLiveEventSet();
          });
        },
        function (response) {
          win.location = requestFactory.getTemplateUrl('admin/videos');
        }
      );
    };

    // ==================================================**************************************************==================================================
    // organization fetch code
    // ==================================================**************************************************==================================================
    this.fetchLiveEventSet = function () {
      requestFactory.post(
        requestFactory.getUrl('live-event/content-set/records '), scope.defineProperties,
        // function (response) {
        //   if (response && response.data && Array.isArray(response.data.data)) {
        //     this.OrganizationList = response.data.data;
        //     // orgbundles(this.OrganizationList);
        //     // console.log("✅ Organization data fetch successfully.", this.OrganizationList);
        //   } else {
        //     console.error("❌ Organization data not fetch!");
        //   }
        // }

        (response) => {
          const data = response?.data?.data;
          if (!Array.isArray(data)) {
            console.error("❌ Invalid organization data!");
            return;
          }

          // Group by organization_id
          const grouped = {};

          data.forEach(item => {
            const orgId = item.organization_id;
            if (!grouped[orgId]) {
              grouped[orgId] = {
                organization_id: orgId,
                organization_name: item.getorg?.organization_name || "Unknown",
                bundles: []
              };
            }
            grouped[orgId].bundles.push({
              id: item.id,
              name: item.name
            });
          });

          // Convert object to array
          let availableList = Object.values(grouped);

          // 🛠️ Hydrate selectedBundles if bundles are missing (Fix for empty "Assigned" view)
          if (scope.selectedVideo && scope.selectedVideo.selectedBundles) {
            scope.selectedVideo.selectedBundles.forEach(assignedOrg => {
              const orgData = grouped[assignedOrg.organization_id];
              if (orgData && (!assignedOrg.bundles || assignedOrg.bundles.length === 0)) {
                // If no specific bundles assigned, assume ALL are relevant (or at least show them)
                // Cloning to avoid reference issues
                assignedOrg.bundles = orgData.bundles.map(b => ({ ...b }));
              }
            });
          }

          // 🔍 Filter availableList to ONLY include organizations present in the current vod's allowed list
          if (scope.selectedVideo && Array.isArray(scope.selectedVideo.organization) && scope.selectedVideo.organization.length > 0) {
            const allowedOrgIds = scope.selectedVideo.organization.map(id => parseInt(id));
            availableList = availableList.filter(org => allowedOrgIds.includes(parseInt(org.organization_id)));
          }

          if (scope.selectedVideo?.selectedBundles?.length) {
            availableList = availableList.map(org => {
              const assignedOrg = scope.selectedVideo.selectedBundles.find(
                o => o.organization_id === org.organization_id
              );
              if (assignedOrg) {
                // Remove bundles that are already assigned
                org.bundles = org.bundles.filter(
                  bundle => !assignedOrg.bundles.some(b => b.id === bundle.id)
                );
              }
              return org;
            }).filter(org => org.bundles.length > 0); // Remove orgs with no available bundles
          }

          scope.LiveEventList = availableList;

          // console.log("✅ Grouped Organization Data:", this.LiveEventList);
        }
      );
    }
    // this.fetchLiveEventSet();

    scope.vgridCtrl.assignSelectedBundles = function () {
      const ctrl = scope.vgridCtrl;

      if (!ctrl.selectedVideo) {
        ctrl.selectedVideo = {};
      }

      if (Array.isArray(ctrl.selectedBundles) && ctrl.selectedBundles.length > 0) {
        ctrl.selectedVideo.bundles = ctrl.selectedBundles.map(org => ({
          organization_id: org.organization_id,
          organization_name: org.organization_name,
          bundles: org.bundles.map(b => ({
            id: b.id,
            name: b.name
          }))
        }));

        console.log("✅ Assigned grouped bundles to selectedVideo:", ctrl.selectedVideo.bundles);
      } else {
        ctrl.selectedVideo.bundles = [];
        console.warn("⚠️ No bundles selected.");
      }

      $('#add-bundles').modal('hide');
    };

    // scope.vgridCtrl.assignSelectedBundles = function () {
    //   const ctrl = scope.vgridCtrl;

    //   if (!ctrl.selectedVideo) ctrl.selectedVideo = {};
    //   ctrl.selectedVideo.bundles = angular.copy(ctrl.selectedBundles || []);
    //   // console.log("✅ Assigned Bundles to selectedVideo:", ctrl.selectedVideo.bundles);

    //   $('#add-bundles').modal('hide');
    // };

    scope.removeBundle = function (org) {
      const ctrl = scope.vgridCtrl;

      scope.LiveEventList = scope.LiveEventList || [];
      scope.selectedVideo.selectedBundles = scope.selectedVideo.selectedBundles || [];

      if (ctrl.selectedVideo?.bundles?.length) {
        ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(
          b => b.organization_id !== org.organization_id
        );
        console.log("🗑️ Removed from selectedVideo.bundles:", org);
      }

      scope.selectedVideo.selectedBundles = scope.selectedVideo.selectedBundles.filter(
        o => o.organization_id !== org.organization_id
      );
      console.log("🧹 Removed organization from selectedBundles:", org);

      // 🔁 3. Return this org (and its bundles) to LiveEventList
      const existingOrg = scope.LiveEventList.find(
        o => o.organization_id === org.organization_id
      );

      if (existingOrg) {
        // Merge bundles without duplication
        org.bundles.forEach(bundle => {
          const alreadyExists = existingOrg.bundles.some(b => b.id === bundle.id);
          if (!alreadyExists) {
            existingOrg.bundles.push({
              id: bundle.id,
              name: bundle.name
            });
          }
        });
        console.log("↩️ Merged bundles back to existing organization in LiveEventList:", org);
      } else {
        // Add as new org entry
        scope.LiveEventList.push({
          organization_id: org.organization_id,
          organization_name: org.organization_name,
          bundles: org.bundles.map(b => ({ id: b.id, name: b.name }))
        });
        console.log("🆕 Returned full organization to LiveEventList:", org);
      }

      // const ctrl = scope.vgridCtrl;
      // ctrl.selectedVideo.bundles = (ctrl.selectedVideo.bundles || []).filter(b => b.id !== bundle.id);
      // // console.log("🗑️ Removed Bundle:", bundle);

      // const exists = ctrl.OrganizationList.some(b => b.id === bundle.id);
      // if (!exists) {
      //   ctrl.OrganizationList.push(bundle);
      //   // console.log("🔁 Returned to OrganizationList:", bundle);
      // }

      scope.$applyAsync();
    };

    $('#assigned-content').on('shown.bs.modal', function () {
      // console.log("✅ Modal opened — initializing drag-and-drop...");
      ContentDragDrop();
    });

    function ContentDragDrop() {
      const addedBundles = document.getElementById('addedBundles');
      const availableBundles = document.getElementById('availableBundles');

      if (!addedBundles || !availableBundles) {
        console.warn("❌ Drop zones not found!");
        return;
      }

      addedBundles.innerHTML = '';
      // console.log("🧹 Cleared previous added bundles.");

      // Drag events
      document.querySelectorAll('#availableBundles .content-container').forEach(card => {
        card.addEventListener('dragstart', e => {
          const id = card.getAttribute('data-id');
          e.dataTransfer.setData('text/plain', id);
          e.target.classList.add('dragging');
          // console.log(`🚀 Drag started: Bundle ID = ${id}`);
        });

        card.addEventListener('dragend', e => {
          e.target.classList.remove('dragging');
          // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
        });
      });

      // Drop zone setup
      addedBundles.addEventListener('dragover', e => {
        e.preventDefault();
        // console.log("📥 Dragging over addedBundles drop zone...");
      });

      addedBundles.addEventListener('drop', e => {
        e.preventDefault();
        const draggedId = e.dataTransfer.getData('text/plain');
        // console.log(`📤 Drop detected. Dropped Bundle ID = ${draggedId}`);

        const card = availableBundles.querySelector(`[data-id="${draggedId}"]`);
        if (!card) {
          console.warn(`❌ No card found for ID ${draggedId}`);
          return;
        }

        if (addedBundles.querySelector(`[data-id="${draggedId}"]`)) {
          console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
          return;
        }

        const clone = card.cloneNode(true);
        clone.classList.remove('dragging');

        const removeBtn = document.createElement('span');
        removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
        removeBtn.className = 'remove-btn';
        removeBtn.style.cssText = 'cursor:pointer; float:right;';
        removeBtn.onclick = () => {
          addedBundles.removeChild(clone);
          availableBundles.appendChild(card);
          // console.log(`🗑️ Removed from assigned. Returned ID = ${draggedId}`);
          updateSelectedBundles();
        };

        clone.appendChild(removeBtn);
        clone.setAttribute('data-id', draggedId);
        clone.setAttribute('draggable', 'true');

        addedBundles.appendChild(clone);
        card.remove();

        // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
        updateSelectedBundles();
      });

      function updateSelectedBundles() {
        const scope = angular.element(document.getElementById('videoEditForm')).scope();
        const ctrl = scope?.vgridCtrl;

        if (!ctrl) {
          console.warn("⚠️ Angular scope or vgridCtrl not found.");
          return;
        }

        ctrl.selectedBundles = [];

        // Loop through each organization container in the addedBundles section
        addedBundles.querySelectorAll('.content-container').forEach(orgCard => {
          const orgId = parseInt(orgCard.getAttribute('data-id'));
          const orgData = ctrl.OrganizationList.find(o => o.organization_id === orgId);

          if (orgData) {
            // Prepare bundles array from HTML (to stay consistent with structure)
            const bundles = [];
            orgCard.querySelectorAll('.item-box').forEach(bundleElem => {
              const bundleName = bundleElem.textContent.trim();
              const matchedBundle = orgData.bundles.find(b => b.name === bundleName);
              if (matchedBundle) {
                bundles.push(matchedBundle);
              }
            });

            // Push complete organization data (with bundles)
            ctrl.selectedBundles.push({
              organization_id: orgData.organization_id,
              organization_name: orgData.organization_name,
              bundles: bundles
            });

            // console.log(`📦 Added Organization: ${orgData.organization_name} with ${bundles.length} bundles`);
          }
        });

        // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
        scope.$applyAsync();
      }

      // function updateSelectedBundles() {
      //   const scope = angular.element(document.getElementById('videoEditForm')).scope();
      //   const ctrl = scope?.vgridCtrl;

      //   if (!ctrl) {
      //     console.warn("⚠️ Angular scope or vgridCtrl not found.");
      //     return;
      //   }

      //   ctrl.selectedBundles = [];
      //   addedBundles.querySelectorAll('.content-container').forEach(card => {
      //     const id = parseInt(card.getAttribute('data-id'));
      //     const bundle = ctrl.OrganizationList.find(b => b.id === id);
      //     if (bundle) {
      //       ctrl.selectedBundles.push(bundle);
      //       // console.log(`📦 Added to selectedBundles: ID = ${id}`);
      //     }
      //   });

      //   // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
      //   scope.$applyAsync();
      // }

      function setupSearch(inputId, containerId) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);

        if (!input || !container) {
          console.warn(`🔍 Search setup skipped for: ${inputId}`);
          return;
        }

        input.addEventListener('input', () => {
          const query = input.value.toLowerCase();
          const cards = container.querySelectorAll('.content-container');
          // console.log(`🔎 Searching for "${query}" in #${containerId}`);

          cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            const match = text.includes(query);
            card.classList.toggle('hidden', !match);
          });
        });
      }

      setupSearch('searchAvailable', 'availableBundles');
      setupSearch('searchAdded', 'addedBundles');
    }

    // ============***************=============
    this.fetchDrm = function () {
      requestFactory.post(
        requestFactory.getUrl("drm/profile/records"), this.defineProperties,
        function (response) {
          if (response && response.data && Array.isArray(response.data.data)) {
            this.DrmList = response.data.data
            // console.log("✅ DRM data fetched:", this.DrmList);
          } else {
            console.error("❌ Drm data not fetch!");
          }
        }
      );
    }
    this.fetchDrm();

    // ============***************=============
    this.fetchOrganization = function () {
      requestFactory.post(
        requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
        function (response) {
          if (response && response.data && Array.isArray(response.data.data)) {
            this.OrganizationList = response.data.data;
          }
        }
      );
    }
    this.fetchOrganization();

    // ============***************=============
    this.fetchPolicy = function () {
      requestFactory.post(
        requestFactory.getUrl('stream-services/streaming-url-policy/records'),
        this.defineProperties,
        (response) => {
          if (response && response.data && Array.isArray(response.data.data)) {
            const data = response.data.data;
            const filter = data.filter(policy => policy.status == 1);
            this.PolicyList = filter;
          }
          // console.log(response);
        }

      );
    }
    this.fetchPolicy();

    // ============***************=============
    this.fetchPBT = function () {
      requestFactory.post(
        requestFactory.getUrl('setting/play-back-token/records'),
        this.defineProperties,
        (response) => {
          if (response && response.data && Array.isArray(response.data.data)) {
            const pbt = response.data.data;
            const filterpbt = pbt.filter(playback => playback.is_active == 1);
            this.playbackTokenList = filterpbt;
          }
        }
      )
    }
    this.fetchPBT();

    // ============***************=============





    scope.togglePublishDate = function () {
      if (scope.selectedVideo.is_active == 1) {
        const now = new Date();
        // Format as YYYY-MM-DD HH:MM:SS
        const formatted = now.getFullYear() + '-' +
          String(now.getMonth() + 1).padStart(2, '0') + '-' +
          String(now.getDate()).padStart(2, '0') + ' ' +
          String(now.getHours()).padStart(2, '0') + ':' +
          String(now.getMinutes()).padStart(2, '0') + ':' +
          String(now.getSeconds()).padStart(2, '0');

        scope.selectedVideo.publish_date = formatted;
      }
    };

    scope.updateFinalDate = function () {
      // console.log("👉 Function Called: updateFinalDate");

      if (scope.selectedVideo.recordingStartTime && scope.selectedVideo.days) {
        // console.log("Start Time (raw):", scope.selectedVideo.recordingStartTime);
        // console.log("Days (raw):", scope.selectedVideo.days);

        let start = new Date(scope.selectedVideo.recordingStartTime);
        let daysToAdd = parseInt(scope.selectedVideo.days);

        // console.log("Parsed Start Date:", start);
        // console.log("Days to Add:", daysToAdd);

        if (!isNaN(start.getTime()) && daysToAdd > 0) {
          start.setDate(start.getDate() + daysToAdd);

          // Format date as YYYY-MM-DD HH:mm:ss
          let yyyy = start.getFullYear();
          let mm = ('0' + (start.getMonth() + 1)).slice(-2);
          let dd = ('0' + start.getDate()).slice(-2);
          let hh = ('0' + start.getHours()).slice(-2);
          let min = ('0' + start.getMinutes()).slice(-2);
          let ss = ('0' + start.getSeconds()).slice(-2);

          scope.finalDate = `${yyyy}-${mm}-${dd} ${hh}:${min}:${ss}`;
          scope.selectedVideo.available_until = scope.finalDate;

          // console.log("✅ Final Date Calculated:", scope.finalDate);
        } else {
          scope.finalDate = null;
          scope.selectedVideo.available_until = null;
          console.warn("⚠️ Invalid date or days entered");
        }
      } else {
        scope.finalDate = null;
        scope.selectedVideo.available_until = null;
        console.warn("⚠️ Missing recordingStartTime or days");
      }
    };




  }
]);

function videoUploader(scope, requestFactory) {
  var self = this;
  this.globalScope = scope;
  this.initializeFineUploader = function () {
    window.fineUploader = new qq.FineUploaderBasic({
      element: document.getElementById('file_drop_area'),
      request: {
        endpoint:
          window.VPlay.route.videoUploadEndpoint +
          'api/media/admin/upload-video',
        customHeaders: {
          Authorization: 'Bearer ' + requestFactory.access_token
        }
      },
      button: document.getElementById('select-files-button'),
      callbacks: {
        onComplete: function (id, name, response, xhr) {
          if (response.success == true) {
            var uploadResponse = {};
            uploadResponse.name = name;
            uploadResponse.uuid = response.uuid;
            uploadResponse.video_url = response.s3_url;
            var foundIndex = self.globalScope.searchVideo(
              self.globalScope.videoArray,
              self.convertToSlug(name)
            );
            if (typeof foundIndex != 'undefined') {
              self.globalScope.videoArray[foundIndex] = {
                ...self.globalScope.videoArray[foundIndex],
                ...uploadResponse
              };
              self.globalScope.$apply();
              uploadResponse = self.globalScope.videoArray[foundIndex];
            }
            self.uploadedVideosDetails.push(uploadResponse);
            self.options.afterUpload(uploadResponse);
          }
        },
        onProgress: function (id, name, uploadedBytes, totalBytes) {
          var uploadedPercentage = parseInt((uploadedBytes * 100) / totalBytes);
          document.getElementById('upload_percentage').style.display = 'block';
          document.getElementById('progress-bar-wrap').style.display =
            'block !important';
          var completedVideobytes = self.bytesToSize(uploadedBytes);
          var totalVideobytes = self.bytesToSize(totalBytes);
          if (uploadedPercentage > 0) {
            // document.getElementById("upload_percentage").innerHTML = 'Processing ' + uploadedPercentage + '%' ;
          }

          if (uploadedPercentage == 100) {
            // document.getElementById("upload_percentage").innerHTML = 'Done...';
          }

          var frame = document.getElementById('video_frame');
          if (typeof frame != 'undefined' && frame != null) {
            frame.style.display = 'none';
            document.getElementById('upload_title').innerHTML = name;
            document.getElementsByClassName('or')[0].style.display = 'none';
            document.getElementById(
              'video-accepted-formats-text'
            ).style.display = 'none';
          }

          var accordin = document.getElementById('video_accordion_wrapper');
          accordin.style.transition = '1s all ease-out';
          accordin.classList.remove('video_accordion_overflow');
          accordin.style.opacity = '1';
          var foundIndex = self.globalScope.searchVideo(
            self.globalScope.videoArray,
            self.convertToSlug(name)
          );
          if (typeof foundIndex != 'undefined') {
            var uploadText =
              uploadedPercentage != 100
                ? 'Uploading ' + uploadedPercentage + '%'
                : 'Uploaded';
            self.globalScope.videoArray[foundIndex].showProgress = true;
            self.globalScope.videoArray[
              foundIndex
            ].uploading_percentage = uploadedPercentage;
            self.globalScope.videoArray[foundIndex].uploading_text = uploadText;
            if (uploadedPercentage == 100) {
              self.globalScope.videoArray[foundIndex].uploading_class =
                'progress-success active';
              self.globalScope.videoArray[foundIndex].transcoding_class =
                'inprogress active';
              self.globalScope.videoArray[foundIndex].uploading_sidebar_class =
                'success';
              self.globalScope.videoArray[
                foundIndex
              ].transcoding_sidebar_class = 'inprogress';
            } else {
              self.globalScope.videoArray[foundIndex].uploading_class =
                'inprogress active';
              self.globalScope.videoArray[foundIndex].uploading_sidebar_class =
                'inprogress';
              self.globalScope.videoArray[foundIndex].transcoding_class = '';
              self.globalScope.videoArray[
                foundIndex
              ].transcoding_sidebar_class = '';
            }
            self.globalScope.videoArray[foundIndex].transcoding_text =
              'Transcoding';
            self.globalScope.videoArray[foundIndex].completed_class = '';
            self.globalScope.videoArray[
              foundIndex
            ].completedVideobytes = completedVideobytes;
            self.globalScope.videoArray[
              foundIndex
            ].totalVideobytes = totalVideobytes;
            self.globalScope.videoArray[foundIndex].uploader_id = id;

            if (uploadedPercentage == 100) {
              self.globalScope.videoArray[foundIndex].job_status =
                'Video Uploaded';
            }
            self.globalScope.$apply();
          }
        },
        onAllComplete: function () {
          var frame = document.getElementById('video_frame');
          if (typeof frame != 'undefined' && frame != null) {
            frame.style.display = 'none';
            document.getElementById('upload_title').innerHTML =
              'DRAG & DROP FILES HERE';
          }
          self.resetUploader();
          document.getElementById('video_accordion_wrapper').style.display =
            'block';
        },
        onError: function (id, name, errorReason, xhr) {
          var frame = document.getElementById('upload_errors_wrap');
          var errFrame = document.getElementById('upload_staus_when_error');
          if (typeof frame != 'undefined' && frame != null) {
            frame.style.display = 'block';
            document.getElementById('upload_title').style.display = 'none';
          }
          self.resetUploader();
          self.initializeFineUploader();
          // Display alert message for the videos which are uploaded successfully before this error.
          var uploadedVideosCount = self.uploadedVideosDetails.length;
          if (uploadedVideosCount > 0) {
            var videoListString = '';
            var videoText = '';
            if (uploadedVideosCount == 1) {
              videoText = 'video was';
            } else {
              videoText = 'videos were';
            }

            for (var i = 0; i < uploadedVideosCount; i++) {
              videoListString =
                videoListString + self.uploadedVideosDetails[i].name;
              if (i + 1 != uploadedVideosCount) {
                videoListString = videoListString + ', ';
              }
            }
            if (errFrame) {
              errFrame.innerHTML =
                'But ' +
                uploadedVideosCount +
                ' ' +
                videoText +
                ' uploaded successfully(' +
                videoListString +
                ').';
            }
          } else {
            if (errFrame) {
              errFrame.innerHTML = '';
            }
          }
        },
        onUpload: function (id, name) {
          self.currentFileCount++;
          // self.globalScope.currentIndex++;
        },
        onCancel: function (id, name) {
          $('.block' + id).remove();
        }
      }
    });
  };

  this.bytesToSize = function (bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
  };

  this.isFileValid = function (validFileTypes, fileType) {
    if (validFileTypes.indexOf(fileType) != -1) {
      return true;
    } else {
      return false;
    }
  };

  this.fileDragOver = function (event) {
    // When the file is dragged over the drop area.
    this.style.boxShadow = '0px 0px 12px 0px rgba(70, 70, 70, 0.5)';
    event.preventDefault();
    event.stopPropagation();
  };

  this.fileDragLeave = function (event) {
    // When the file is dragged out of the drop area.
    this.style.boxShadow = 'none';
    event.preventDefault();
    event.stopPropagation();
  };

  this.handleFileDrop = function (event) {
    // When the file is dropped in the drop area.
    this.style.boxShadow = 'none';
    var files = event.dataTransfer.files;
    self.prepareUpload(files);
    event.preventDefault();
    event.stopPropagation();
  };

  this.handleFileSelect = function () {
    // When the file is selected using file select.
    var files = this.files;
    self.prepareUpload(files);
  };
  this.resetUploader = function () {
    var targetElem = document.getElementById('video_error');

    if (typeof targetElem != 'undefined' && targetElem != null) {
      document.getElementById('video_error').style.display = 'none';
      document.querySelector('.add_video_container').style.display = 'none';
      document.getElementById('google_drive_upload_button').style.display =
        'inline';
    }

    // Add back the drop event listener to the drop area.
    var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
    if (fileDropArea) {
      fileDropArea.addEventListener('drop', self.handleFileDrop);
    }
    this.file.value = '';
  };

  this.startVideoUpload = function () {
    var files = scope.uploadFiles;
    self.currentFileCount = 0;
    self.uploadedVideosDetails = [];

    var targetElem = document.getElementById('video_upload_button_wrap');

    if (typeof targetElem != 'undefined' && targetElem != null) {
      // Hide add video close button, File selection container div and video upload button
      document.querySelector('.add_video_container').style.display = 'none';
      // document.getElementsByClassName("upload_file_input")[0].style.display = "none";
      document.getElementById('video_upload_button_wrap').style.display =
        'none';

      // Remove Drop event listener for file drop area.
      var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
      fileDropArea.removeEventListener('drop', self.handleFileDrop);
    }

    document.getElementById('upload_percentage').style.display = 'block';
    self.options.beforeUpload(
      scope.uploadFiles.length,
      self.currentFileCount,
      files
    );

    window.fineUploader.addFiles(files);
    window.fineUploader.uploadStoredFiles();
  };

  this.convertToSlug = function (text) {
    var fileName;
    fileName = text;
    fileName = fileName.substr(0, fileName.lastIndexOf('.'));
    return fileName;
  };

  this.prepareUpload = function (files) {
    scope.uploadFiles = [];
    scope.videoUploadLists = [];
    var errorFlag = false;

    var validFileTypes = [
      'video/mp4',
      'video/quicktime',
      'video/avi',
      // 'video/x-ms-wmv',
      'video/msvideo',
      'video/x-msvideo',
      // 'video/3gpp',
      'video/x-matroska'
    ];

    for (var i = 0; i < files.length; i++) {
      if (files[i] && self.isFileValid(validFileTypes, files[i].type)) {
        scope.uploadFiles.push(files[i]);

        if (self.globalScope.selectedVideo.replaceVideo) {
          // Get the first index of recently uploaded video set and select that video

          // FIND VIDEO INDEX
          var foundIndex = self.globalScope.videoArray.findIndex(function (
            person
          ) {
            return person.title == self.globalScope.selectedVideo.title;
          });

          var myKey = new Date().getTime();
          var myName = self.convertToSlug(files[i].name);
          self.globalScope.randomKeys[myName] = myKey;

          self.globalScope.videoArray[foundIndex].title = self.convertToSlug(
            files[i].name
          );
          self.globalScope.videoArray[foundIndex].job_status = 'Uploading';
          self.globalScope.videoArray[foundIndex].showProgress = true;
          self.globalScope.videoArray[foundIndex].showReplace = false;
          self.globalScope.videoArray[foundIndex].key = myKey;
        } else {
          var myKey = new Date().getTime();
          var myName = self.convertToSlug(files[i].name);

          self.globalScope.videoArray.push({
            id: '',
            title: myName,
            description: '',
            tags: '',
            starring: '',
            is_active: 0,
            is_premium: 0,
            is_webseries: false,
            episode_order: '',
            is_notify: 0,
            job_status: 'Uploading',
            uploading_percentage: 0,
            transcodingPercentage: 0,
            completedVideobytes: 0,
            totalVideobytes: 0,
            key: myKey,
            showMainSubtitle: true,
            showProgress: false,
            showReupload: false,
            replaceVideo: false,
            showIfNoAudios: true
          });

          self.globalScope.randomKeys[myName] = myKey;
        }

        if (i == 0) {
          // Get the first index of recently uploaded video set and select that video
          // FIND VIDEO INDEX
          /*var foundIndex = self.globalScope.videoArray.findIndex(function(person) {
            return person.title == self.convertToSlug(files[i].name);
          });*/

          var foundIndex = self.globalScope.searchVideo(
            self.globalScope.videoArray,
            self.convertToSlug(files[i].name)
          );

          if (!self.globalScope.selectedVideo.replaceVideo) {
            self.globalScope.videoData(self.globalScope.videoArray[foundIndex]);
          }
        }

        // To Populate the videos newly uploaded by default in the video form

        // Force scope update
        self.globalScope.$apply();
      } else {
        errorFlag = true;
      }
    }
    var uploadButton = document.getElementById('video_upload_button_wrap');

    if (errorFlag) {
      // BEGIN : To show invalid error message for video upload
      $('#video_modal').modal('show');
      $('.error_msg').show();
      // END : To show invalid error message for video upload
    }

    if (scope.uploadFiles.length > 0) {
      // Animate the page
      this.animate(files);
      // Enable upload button
      //uploadButton.addEventListener('click', this.startVideoUpload);
      var timeOut =
        self.globalScope.videoArray.length <= 1 &&
          !self.globalScope.videoArray[0].id
          ? 1000
          : 0;
      setTimeout(function () {
        self.startVideoUpload();
        if (uploadButton) {
          uploadButton.style.display = 'block';
        }
      }, timeOut);
    }
  };

  this.animate = function (files) {
    var targetElem = document.getElementById('video_frame');
    if (typeof targetElem != 'undefined' && targetElem != null) {
      document.getElementById('upload_title').innerHTML =
        'No. of selected files : ' + files.length;
      document.getElementById('video_error').style.display = 'none';
      document.getElementById('upload_title').style.display = 'block';
      document.getElementById('upload_errors_wrap').style.display = 'none';
      // document.getElementById("upload_percentage").innerHTML = '0% Uploaded';
      document.getElementById('google_drive_upload_button').style.display =
        'none';

      var copyElem = document.getElementById('upload_file_input');

      var targetClient = targetElem.getBoundingClientRect();
      var copyClient = copyElem.getBoundingClientRect();

      var newTop = parseInt(copyClient.y) - parseInt(targetClient.y);
      var newLeft = copyClient.x - targetClient.x;

      var top = targetElem.offsetTop;
      var left = targetElem.offsetLeft;

      var classInfo = document.getElementById('upload_file_input');
      // classInfo.className = classInfo.className + ' hide ';
      targetElem.style.transition = '1s all ease-out';
      targetElem.style.opacity = '0';

      copyElem.style.transition = '1s all ease-out';
      copyElem.style.transform =
        'translate(-' + newLeft + 'px,-' + newTop + 'px)';

      var body = document.getElementsByTagName('body');
      body[0].classList.remove('video_accordion_overflow');
    }
  };

  this.initiate = function (options) {
    this.options = options;

    this.fileList = document.getElementsByClassName(options.class);
    if (this.fileList) {
      for (var i = 0; i < this.fileList.length; i++) {
        this.fileList[i].addEventListener('change', this.handleFileSelect);
      }
    }

    var fileDropArea = document.querySelector('#' + options.dropAreaId);
    if (fileDropArea) {
      fileDropArea.addEventListener('dragover', this.fileDragOver);
      fileDropArea.addEventListener('dragleave', this.fileDragLeave);
      fileDropArea.addEventListener('drop', this.handleFileDrop);
    }

    this.initializeFineUploader();
  };
}

/**
 * Manually merging this controller with Common Controller for fetching header data
 */

if (angular.isObject(window.gridControllers)) {
  for (var controller in window.gridControllers) {
    if (
      angular.isArray(window.gridControllers[controller]) ||
      angular.isFunction(window.gridControllers[controller])
    ) {
      videoUpload.controller(controller, window.gridControllers[controller]);
    }
  }
}
/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
  angular.bootstrap(document, ['videoUpload']);
});
