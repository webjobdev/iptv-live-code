'use strict';

var AudioEditFormApp = angular.module('AudioEditFormApp', ["ui"]);
var commonAPP = AudioEditFormApp;
AudioEditFormApp.directive('baseValidator', validatorDirective);
AudioEditFormApp.factory('requestFactory', requestFactory);
AudioEditFormApp.controller('AudioEditFormController', ['$scope', '$rootScope', 'requestFactory', function (scope, rootScope, requestFactory) {
  var self = this;
  this.info = {};
  scope.errors = {};
  scope.audio_artists = {};
  scope.audio_languages = {};
  scope.audio_albums = {};
  requestFactory.setThisArgument(this);
  this.audioPostData = {};
  requestFactory.toggleLoader();

  function audioUploader(scope) {
    var self = this;
    this.initializeFineUploader = function () {
      window.fineUploader = new qq.FineUploaderBasic({
        element: document.getElementById('file_drop_area'),
        request: {
          endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader'
        },
        deleteFile: {
          enabled: true,
          endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader'
        },
        chunking: {
          enabled: true,
          concurrent: {
            enabled: true
          },
          success: {
            endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader?done'
          }
        },
        resume: {
          enabled: true
        },
        retry: {
          enableAuto: false
        },
        button: document.getElementById('select-files-button'),
        callbacks: {
          onComplete: function (id, name, response, xhr) {
            if (response.success == true) {
              var uploadResponse = {};
              uploadResponse.name = name;
              uploadResponse.uuid = response.uuid;
              self.uploadedAudiosDetails.push(uploadResponse);
              self.options.afterUpload(uploadResponse);

              $('.edit_video_upload .upload-status-wrapper').show();
              $('.edit_video_upload .upload-success').html(name);
              document.getElementById("progress-bar-wrap").style.display = 'none';
            }
          },
          onProgress: function (id, name, uploadedBytes, totalBytes) {
            document.getElementById("upload_percentage").style.display = 'block';
            document.getElementById("video_frame").style.display = 'none';
            var uploadedPercentage = parseInt((uploadedBytes * 100) / totalBytes);
            document.getElementById('progress-bar-wrap').style.display = 'block';
            var progressBar = document.getElementById('progress-bar');
            progressBar.style.width = uploadedPercentage + '%';
            document.getElementById("upload_percentage").innerHTML = uploadedPercentage + '% Uploaded';
            document.getElementById("upload_title").innerHTML = name;
            if (uploadedPercentage == 100) {
              document.getElementById("upload_percentage").innerHTML = 'Done...';
            }
          },
          onError: function (id, name, errorReason, xhr) {
            document.getElementById("upload_errors_wrap").style.display = "block";
            document.getElementById("upload_title").style.display = "none";
            self.resetUploader();
            self.initializeFineUploader();
            // Display alert message for the audios which are uploaded successfully before this error.
            var uploadedAudiosCount = self.uploadedAudiosDetails.length;
            if (uploadedAudiosCount > 0) {
              var audioListString = '';
              var audioText = '';
              if (uploadedAudiosCount == 1) {
                audioText = 'audio was';
              } else {
                audioText = 'audios were';
              }

              for (var i = 0; i < uploadedAudiosCount; i++) {
                audioListString = audioListString + self.uploadedAudiosDetails[i].name;
                if (i + 1 != uploadedAudiosCount) {
                  audioListString = audioListString + ", ";
                }
              }
              document.getElementById("upload_staus_when_error").innerHTML = "But " + uploadedAudiosCount + " " + audioText + " uploaded successfully(" + audioListString + ").";
            } else {
              document.getElementById("upload_staus_when_error").innerHTML = '';
            }
          },
          onUpload: function (id, name) {
            self.currentFileCount++;
          },
        },
      });
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
      this.style.boxShadow = "0px 0px 50px 10px rgba(0,0,0,0.75)";
      event.preventDefault();
      event.stopPropagation();
    };

    this.fileDragLeave = function (event) {
      // When the file is dragged out of the drop area.
      this.style.boxShadow = "none";
      event.preventDefault();
      event.stopPropagation();
    };

    this.handleFileDrop = function (event) {
      // When the file is dropped in the drop area.
      this.style.boxShadow = "none";
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
      document.getElementById("video_error").style.display = "none";
      document.querySelector('.add_video_container .fa-times').style.display = "inline";
      document.getElementsByClassName("upload_file_input")[0].style.display = "block";
      document.getElementById("upload_percentage").style.display = "none";
      document.getElementById("google_drive_upload_button").style.display = "inline";

      // Add back the drop event listener to the drop area.
      var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
      fileDropArea.addEventListener('drop', self.handleFileDrop);
      document.getElementById('progress-bar-wrap').style.display = 'none';
      document.getElementById('progress-bar').style.width = '0%';
      // Reset the input tag so that the same file can be selected again for upload.
      this.file.value = '';
    };

    this.startAudioUpload = function () {
      var files = self.uploadFiles;
      self.currentFileCount = 0;
      self.uploadedAudiosDetails = [];

      // Hide add audio close button, File selection container div and audio upload button
      document.querySelector('.add_video_container .fa-times').style.display = "none";
      document.getElementsByClassName("upload_file_input")[0].style.display = "none";
      document.getElementById("video_upload_button_wrap").style.display = "none";

      // Remove Drop event listener for file drop area.
      var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
      fileDropArea.removeEventListener('drop', self.handleFileDrop);

      document.getElementById("upload_percentage").style.display = "block";
      self.options.beforeUpload(self.uploadFiles.length);

      window.fineUploader.addFiles(files);
      window.fineUploader.uploadStoredFiles();
    };

    this.prepareUpload = function (files) {
      self.uploadFiles = [];
      var validFileTypes = ['audio/mp3', 'audio/wav'];

      document.getElementById("upload_title").innerHTML = "No. of selected files : " + files.length;
      document.getElementById("video_error").style.display = "none";
      document.getElementById("upload_title").style.display = "block";
      document.getElementById("upload_errors_wrap").style.display = "none";
      document.getElementById("upload_percentage").innerHTML = '0% Uploaded';
      document.getElementById("google_drive_upload_button").style.display = "none";

      for (var i = 0; i < files.length; i++) {
        if (files[i] && self.isFileValid(validFileTypes, files[i].type)) {
          self.uploadFiles.push(files[i]);
        }
      }
      var uploadButton = document.getElementById("video_upload_button_wrap");
      if (self.uploadFiles.length == 0) {
        // There are no valid files selected.
        document.getElementById("video_error").style.display = "block";
        uploadButton.style.display = "none";
      } else {
        // Enable upload button
        this.startAudioUpload();
        // uploadButton.addEventListener('click', this.startAudioUpload);
        uploadButton.style.display = "block";
      }
    };

    this.initiate = function (options) {
      this.options = options;
      this.file = document.getElementById(options.id);
      this.file.addEventListener('change', this.handleFileSelect);
      var fileDropArea = document.querySelector('#' + options.dropAreaId);
      fileDropArea.addEventListener('dragover', this.fileDragOver);
      fileDropArea.addEventListener('dragleave', this.fileDragLeave);
      fileDropArea.addEventListener('drop', this.handleFileDrop);
      this.initializeFineUploader();
    };
  }

  this.setupAudioUploader = function () {
    this.audioUploader = new audioUploader(scope);
    this.audioUploader.initiate({
      id: 'audio',
      dropAreaId: 'file_drop_area',
      afterUpload: function (response) {
        self.audioPostData.newAudioName = response.name;
        self.audioPostData.newAudioUUID = response.uuid;
      },
      beforeUpload: function (totalAudiosCount) {
        self.totalAudiosCount = totalAudiosCount;
        // Reset the values because this upload might be after failure.
        self.audioUploadCompleteCount = 0;
        self.uploadIntervalFlag = false;
        self.audioUploadRequestCount = 0;
      },
    });

  };

  this.resetAudio = function () {
    this.audioPostData.newAudioName = '';
    this.audioPostData.newAudioUUID = '';
    $('.edit_video_upload .upload-status-wrapper').hide();
  }

  /** Audio Upload Handler Ends */

  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('audios/info'), this.defineProperties, function (response) {
      rootScope.redirectUnauthenticated(response);
     });
  };

  this.defineProperties = function (data) {
    this.setupAudioUploader();
    this.info = data.info;
    scope.audio_artists = data.info.artists;
    scope.audio_albums = data.info.albums;
    scope.audio_languages = data.info.audio_language;
    
  };
  this.fetchInfo();

  /**
   *  Function is used to get the audio data
   *  
   */
  this.fetchData = function (id) {
    requestFactory.get(requestFactory.getUrl('audios/audio-to-edit/' + id), function (response) {
      this.audioPostData = response.response;
      this.audioPostData.audio_title = response.response.audio_title;
      this.audioPostData.audio_artist = (response.response.audio_artist_id == 0) ? '' : String(response.response.audio_artist_id);
      this.audioPostData.audio_album = (response.response.album_id == 0) ? '' : String(response.response.album_id);
      this.audioPostData.audio_description = response.response.audio_description;
      this.audioPostData.thumbnail_image = (response.response.audio_thumbnail != '') ? $('.uploaded_img').attr('src', response.response.audio_thumbnail) : 'contus/base/images/no-preview.png';
      this.audioPostData.thumbnail_image = response.response.audio_thumbnail;
      this.audioPostData.is_active = response.response.is_active;
    }, function (response) {
      self.notFoundFlag = true;
      requestFactory.toggleLoader();
    });
  }


  /**
   *  Function is used to show the error
   *  
   */
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
  /**
   *  Function is used to get the datepicker
   *  
   */
  $('#release_date').datepicker({
    format: "dd-mm-yyyy",
    viewMode: 'years',
    autoclose: true,
    todayHighlight: true,
  });

  /**
   *  Function is used to save the audio
   *  
   *  @param  $event, id
   */

  this.audioSave = function ($event, id) {
    scope.errors = {};
    baseValidator.setRules(this.info.audio_rules);
    if (baseValidator.validateAngularForm($event.target, scope)) {
      var isImgUpdated = angular.element('#isImgUpdated').val();
      if (isImgUpdated == 1) {
        this.audioPostData.thumbnail_image = angular.element(".uploaded_img").attr("src");
        this.audioPostData.is_thumbnail_updated = 1;
      }
      requestFactory.post(requestFactory.getUrl('audios/edit/' + id), this.audioPostData, function (response) {
        window.location.href = requestFactory.getTemplateUrl('admin/audios/audios');
        this.responseMessage = response.message;
        this.showResponseMessage = true;
      }, this.fillError);
    }
  };

}]);

/* window.gridControllers = {
  AudioEditFormApp: AudioEditFormApp
}; */

/**
 * Manually merging this controller with Common Controller for fetching header data
 */
/* if(angular.isObject(window.gridControllers)){
  for(var controller in window.gridControllers){
      if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
          window.gridControllers[controller].hideHeader=true;
          AudioEditFormApp.controller(controller,window.gridControllers[controller]);
      }
  }
} */

/**
* Manually bootstrap the Angular module here
*/
/* angular.element(document).ready(function() {
  angular.bootstrap(document, ['AudioEditFormApp']);
}); */