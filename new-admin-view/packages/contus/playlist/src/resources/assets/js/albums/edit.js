'use strict';

var AlbumEditFormApp = angular.module('AlbumEditFormApp', ["ui"]);
var commonAPP = AlbumEditFormApp;
AlbumEditFormApp.directive('baseValidator', validatorDirective);
AlbumEditFormApp.factory('requestFactory', requestFactory);
AlbumEditFormApp.service('audioUploadService', audioUpload);
AlbumEditFormApp.controller('AlbumEditFormController', ['$scope', '$rootScope', 'requestFactory', 'audioUploadService', function (scope, rootScope, requestFactory, audioUploadService) {

  var self = this;
  this.info = {};
  scope.errors = {};
  requestFactory.setThisArgument(this);
  scope.albumPostData = {};
  scope.audio_artists = {};
  scope.audio_genres = {};
  scope.audio_languages = {};
  this.showResponseMessage = false;
  scope.albumPostData.audioPostData = [];
  scope.albumPostData.audioEditPostData = [];
  scope.albumPostData.is_active = true;
  scope.singleAudioPostData = {};
  this.audioUploadLists = {};
  this.audioLength = 0;
  scope.itemId = {};
  scope.isAudioUploaded = false;
  scope.audioUploadLists = [];
  scope.allAudioUploadStatus = true;
  scope.album_id = 0;
  scope.album_thumbnail = '';
  scope.isUpdated = false;
  scope.index = {};
  scope.fileUploadOptions = {};
  scope.fileElement = '';
  scope.deleteType = '';
  requestFactory.toggleLoader();

  this.fetchInfo = function () {
    audioUploadService.setupAudioUploader(scope);
    requestFactory.get(requestFactory.getUrl('albums/info'), this.defineProperties, function (response) { 
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.defineProperties = function (data) {
    this.info = data.info;
    scope.audio_artists = data.info.artists;
    scope.audio_languages = data.info.audio_language;
    scope.audio_genres = data.info.audio_genres;
    //requestFactory.toggleLoader();
  };
  this.fetchInfo();

  this.formatDate = function (date) {
    var date = new Date(date);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    return ("0" + (date.getDate())).slice(-2) + "-" + month + "-" + date.getFullYear();
  }

  this.fetchData = function (id) {
    scope.album_id = id;
    requestFactory.get(requestFactory.getUrl('albums/album-edit/' + id), function (response) {
      var albumDetails = response.response;
      this.audioUploadLists = response.response.audios;
      scope.album_thumbnail = response.response.album_thumbnail;
      scope.albumPostData.audioEditPostData = [];
      angular.forEach(this.audioUploadLists, function (value, key) {
        var form = angular.element(".audio_item_" + key + " .audio_accordion_wrapper form")
        scope.albumPostData.audioEditPostData[key] = {};
        scope.albumPostData.audioEditPostData[key].id = value.id;
        scope.albumPostData.audioEditPostData[key].audio_title = value.audio_title;
        scope.albumPostData.audioEditPostData[key].description = value.audio_description;
        scope.albumPostData.audioEditPostData[key].audio_artists = (value.audio_artist_id == 0) ? '' : String(value.audio_artist_id);
        scope.albumPostData.audioEditPostData[key].is_active = value.is_active ;
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
      scope.albumPostData.album_name = albumDetails.album_name;
      scope.albumPostData.album_description = albumDetails.album_description;
      scope.albumPostData.album_artists = (albumDetails.album_artist_id == 0) ? '' : String(albumDetails.album_artist_id);
      scope.albumPostData.audio_genre = String(albumDetails.genre_id);
      scope.albumPostData.audio_language = (albumDetails.audio_language_category_id == 0) ? '' : String(albumDetails.audio_language_category_id);
      scope.albumPostData.is_active = albumDetails.is_active;
      scope.albumPostData.album_release_date = this.formatDate(albumDetails.album_release_date);
      if(albumDetails.album_thumbnail != '') {
        scope.albumPostData.thumbnail_image = $('.uploaded_img').attr('src', albumDetails.album_thumbnail);
      }    
      scope.albumPostData.thumbnail_image = albumDetails.album_thumbnail;
      this.audioLength = response.response.audios.length;
    });
  };

  $('#release_date').datepicker({
    format: "dd-mm-yyyy",
    viewMode: 'years',
    autoclose: true,
    todayHighlight: true,
  });


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

  this.albumSave = function ($event, id) {
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
    if (scope.albumPostData.audioEditPostData.length != 0) {
      angular.forEach(scope.albumPostData.audioEditPostData, function (value, key) {
        var form = angular.element(".audio_item_" + key + " .audio_accordion_wrapper form");
        if (!baseValidator.validateAngularForm(form, scope)) {
          angular.element('#collapse_edit' + key).addClass('in');
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
        var isImgUpdated = angular.element('#isImgUpdated').val();
        if (isImgUpdated == 1) {
          scope.albumPostData.thumbnail_image = angular.element(".uploaded_img").attr("src");
          scope.albumPostData.is_thumbnail_updated = 1;
        }
        requestFactory.post(requestFactory.getUrl('albums/edit/' + id), scope.albumPostData, function (response) {
          if (scope.albumPostData.audioEditPostData.lenth != 0) {
            this.updateEditAudio(scope.album_id);
          }
          if (scope.albumPostData.audioPostData.length != 0) {
            this.updateAudio(scope.album_id, scope.album_thumbnail);
          }
        }, this.fillError);
      }
    }
  };

  this.updateAudio = function (albumId, albumThumbnail) {
    scope.errors = {};
    baseValidator.setRules(this.info.audio_rules);
    requestFactory.post(requestFactory.getUrl('album/audio-update'), { 'audioPostData': scope.albumPostData.audioPostData, 'albumId': albumId, 'albumThumbnail': albumThumbnail, 'formType':'album-edit' }, function (response) {
      this.responseMessage = response.message;
      this.showResponseMessage = true;
      window.location.href = requestFactory.getTemplateUrl('admin/audios/album');
    }, function (response) {
      this.fillErrorAudio(response, item);
    });
  }
  this.updateEditAudio = function (albumId) {
    scope.errors = {};
    baseValidator.setRules(this.info.audio_rules);
    requestFactory.post(requestFactory.getUrl('album/audio-update'), { 'audioPostData': scope.albumPostData.audioEditPostData, 'albumId': albumId, 'formType':'album-edit' }, function (response) {
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