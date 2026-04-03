'use strict';

var AlbumAddFormApp = angular.module('AlbumAddFormApp', ["ui"]);
var commonAPP = AlbumAddFormApp;
AlbumAddFormApp.directive('baseValidator', validatorDirective);
AlbumAddFormApp.factory('requestFactory', requestFactory);
AlbumAddFormApp.service('audioUploadService', audioUpload);
AlbumAddFormApp.controller('AlbumAddFormController', ['$scope', '$rootScope', 'requestFactory', 'audioUploadService', function (scope, rootScope, requestFactory, audioUploadService) {
  var self = this;
  this.info = {};
  scope.errors = {};
  scope.audio_artists = {};
  scope.audio_genres = {};
  scope.audio_languages = {};
  requestFactory.setThisArgument(this);
  this.showResponseMessage = false;
  scope.albumPostData = {};
  scope.albumPostData.audioPostData = [];
  scope.albumPostData.is_active = true;
  scope.singleAudioPostData = {};
  scope.itemId = {};
  scope.isAudioUploaded = false;
  scope.audioUploadLists = [];
  scope.allAudioUploadStatus = true;
  scope.fileUploadOptions = {};
  scope.fileElement = '';
  scope.deleteType = '';
  requestFactory.toggleLoader();


  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('albums/info'), this.defineProperties, function (response) { 
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.defineProperties = function (data) {
    audioUploadService.setupAudioUploader(scope);
    this.info = data.info;
    scope.audio_artists = data.info.artists;
    scope.audio_languages = data.info.audio_language;
    scope.audio_genres = data.info.audio_genres;
  };
  this.fetchInfo();

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

  $(document).ready(function () {
    $('#release_date').datepicker({
      format: "dd-mm-yyyy",
      autoclose: true,
      viewMode: 'years',
      todayHighlight: true,
    });
  });

  this.albumSave = function ($event) {
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
        scope.albumPostData.thumbnail_image = angular.element(".uploaded_img").attr("src");
        scope.albumPostData.is_thumbnail_updated = 1;
        requestFactory.post(requestFactory.getUrl('albums/add'), scope.albumPostData, function (response) {
          var albumId = response.albumId;
          var albumThumbnail = (response.albumThumbnail != '') ? response.albumThumbnail : '';
          if (scope.albumPostData.audioPostData.length != 0) {
            this.updateAudio(albumId, albumThumbnail);
          } else {
            window.location.href = requestFactory.getTemplateUrl('admin/audios/album');
          }
        }, this.fillError);
      }
    }
  };
  this.updateAudio = function (albumId, albumThumbnail) {
    scope.errors = {};
    baseValidator.setRules(this.info.audio_rules);
    requestFactory.post(requestFactory.getUrl('album/audio-update'), { 'audioPostData': scope.albumPostData.audioPostData, 'albumId': albumId, 'albumThumbnail': albumThumbnail, 'formType':'album-add' }, function (response) {
      this.responseMessage = response.message;
      this.showResponseMessage = true;
      window.location.href = requestFactory.getTemplateUrl('admin/audios/album');
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