'use strict';

var AudioAddFormApp = angular.module('AudioAddFormApp', ["ui"]);
var commonAPP = AudioAddFormApp;
AudioAddFormApp.directive('baseValidator', validatorDirective);
AudioAddFormApp.factory('requestFactory', requestFactory);
AudioAddFormApp.service('audioUploadService', audioUpload);
AudioAddFormApp.controller('AudioAddFormController', ['$scope', '$rootScope', 'requestFactory', 'audioUploadService', function (scope, rootScope, requestFactory, audioUploadService) {
  var self = this;
  this.info = {};
  scope.errors = {};
  scope.audio_artists = {};
  scope.audio_languages = {};
  scope.audio_albums = {};
  requestFactory.setThisArgument(this);
  scope.audioPostData = {};
  scope.albumPostData = {};
  scope.albumPostData.is_active = true;
  scope.albumPostData.audioPostData = [];
  scope.isAudioUploaded = false;
  scope.audioUploadLists = [];
  scope.singleAudioPostData = {};
  scope.allAudioUploadStatus = true;
  scope.fileUploadOptions = {};
  scope.fileElement = '';
  scope.getresult = 0;
  //requestFactory.toggleLoader();

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
      var audioIndex = e.target.getAttribute('data-audio-index');
      if(scope.errors[audioIndex] == undefined){
        scope.errors[audioIndex] = {};
      }
      scope.errors[audioIndex]['thumbnail'] = {};
      var ValidImageTypes  = ["image/gif", "image/jpeg", "image/png"];
      var files = e.target.files;
      var fileType = files[0].type;
      if ($.inArray(fileType, ValidImageTypes) < 0) {
          scope.errors[audioIndex]['thumbnail'] = {has : true , message :'Invalid file format. Upload only jpeg and png file formats.'};
          scope.$apply();
        return;
      }else{
        scope.errors[audioIndex]['thumbnail'] = '';
        scope.$apply();
      }
      $('.crop-body').show();
      $('#modal .audio-index').val(audioIndex);
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
          aspectRatio: 40 / 43,
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
        var module = document.getElementById('module').value;
        var size = document.getElementById('size').value;
        var formData = new FormData();
        formData.append('module', module);
        formData.append('size', size);
        formData.append('image', blob);
        $('.crop-body').hide();
        $('.loader-container').show();
        $('#submit-image').prop('disabled', true);
        $.ajax($('meta[name="base-api-url"]').attr('content') + '/audio-base/thumbnail', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success(data) {
            var audioIndex = $('#modal .audio-index').val();
            $('.uploaded_img_' + audioIndex).attr('src', data.info);
            $('.uploaded_img_' + audioIndex).show();
            scope.albumPostData.audioPostData[audioIndex].thumbnail_image = data.info;
            scope.albumPostData.audioPostData[audioIndex].is_thumbnail_updated = 1;
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
  })
  /**
   * End of image upload script
   *
   * */

  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('audios/info'), this.defineProperties, function (response) { 
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.defineProperties = function (data) {
    requestFactory.toggleLoader();
    audioUploadService.setupAudioUploader(scope);
    this.info = data.info;
    scope.audio_artists = data.info.artists;
    scope.audio_albums = data.info.albums;
    scope.audio_languages = data.info.audio_language;
  };
  this.fetchInfo();

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
   *  Function is used to save the audio
   *  
   *  @param  $event
   */
  this.audioSave = function ($event) {
    baseValidator.setRules(this.info.rules);
    var isAllAudioFormUpdated = true;
    scope.errors = {};
    if (scope.allAudioUploadStatus == false) {
      angular.element('#audioUploadErrorModal').modal('show');
      return false;
    }
    if (scope.albumPostData.audioPostData.length != 0) {
      angular.forEach(scope.albumPostData.audioPostData, function (value, key) {
        var form = angular.element(".audio_item_" + key + " .audio_accordion_wrapper form")
        if (!baseValidator.validateAngularForm(form, scope)) {
          isAllAudioFormUpdated = false;
          angular.element('#collapse_' + key).addClass('in');
          scope.errors[key] = {};
          angular.forEach(scope.errors, function (eachmessage, errorkey) {
            if (typeof eachmessage == 'object' && eachmessage.hasOwnProperty('message')) {
              scope.errors[key][errorkey] = {
                has: true,
                message: eachmessage.message
              };
            }
          });
        }
      });
    }
    if (baseValidator.validateAngularForm($event.target, scope)) {
      if (isAllAudioFormUpdated) {
        if (scope.albumPostData.audioPostData.length != 0) {
          this.updateAudio();
        }
      }
    }
  };
  this.updateAudio = function () {
    scope.errors = {};
    baseValidator.setRules(this.info.rules);
    var isImgUpdated = angular.element('#isImgUpdated').val();
    requestFactory.post(requestFactory.getUrl('album/audio-update'), { 'audioPostData': scope.albumPostData.audioPostData, 'formType':'audio-add'}, function (response) {
      this.responseMessage = response.message;
      this.showResponseMessage = true;
      window.location.href = requestFactory.getTemplateUrl('admin/audios/audios');
    }, function (response) {
      this.fillErrorAudio(response, item);
    });
  }

  this.fillErrorAudio = function (response, item) {
    scope.errors[item] = {};
    if (response.status == 422 && response.data.hasOwnProperty('messages')) {
      angular.forEach(response.data.messages, function (message, key) {
        if (typeof message == 'object' && message.length > 0) {
          scope.errors[item][key] = {
            has: true,
            message: message[0]
          };
        }
      });
    }
  };

  scope.hideUploadOption = function(event) {
    var targetPage = event.currentTarget.getAttribute('data-url');
    window.location.href = targetPage;
  }
  /*
     * Function to delete admin audio view detail page.
     */
  this.deleteSingleRecord = function (id, index, type) {
    scope.deleteParams = [id];
    scope.ConfirmationDeleteBox = true;
    scope.index = index;
    scope.deleteType = type;
  };
  this.cancelDelete = function () {
    scope.ConfirmationDeleteBox = false;
    scope.deleteParams = '';
  };
  this.confirmDelete = function (status) {
    if (scope.deleteParams.length > 0) {
      audioUploadService.deleteAudioRecord(scope.deleteParams, status, scope.index, scope.deleteType, scope);
      scope.ConfirmationDeleteBox = false;
      scope.deleteParams = '';
    } else {
      scope.ConfirmationDeleteBox = false;
      scope.deleteParams = '';
    }
  };

}]);
/* window.gridControllers = {
  AudioAddFormApp: AudioAddFormApp
}; */


/**
 * Manually merging this controller with Common Controller for fetching header data
 */

if(angular.isObject(window.gridControllers)){
  
  for(var controller in window.gridControllers){
   
      if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
        AudioAddFormApp.controller(controller,window.gridControllers[controller]);
        ;
      }
  }
}
/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function() {
  angular.bootstrap(document, ['AudioAddFormApp']);
  
});
