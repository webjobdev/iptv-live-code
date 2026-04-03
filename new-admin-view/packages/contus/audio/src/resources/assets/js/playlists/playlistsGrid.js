'use strict';

var PlaylistsGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.playlist = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.showplaylist = true;
    this.playlist.is_image_updated = 0;
    scope.errors = {};
    scope.singlePreload = [];
    scope.singleConfig = {
        create: false,
        valueField: 'id',
        labelField: 'audio_title',
        searchField: 'audio_title',
        plugins: ['remove_button'],
        placeholder: 'Search audio by title and select...',
        preload: true,
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: $('meta[name="base-api-url"]').attr('content') + '/playlists/searchaudios?search=' + query,
                type: 'GET',
                dataType: 'json',
                data: {
                    name: query,
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.response.data);
                }
            });
        },
        onChange: function(value) {
        }
   };


    requestFactory.setThisArgument(this);
  

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

    this.closePlaylistEdit = function () {
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
    };

    this.deletePlaylistImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('playlists/delete-playlist-image/' + this.playlist.id), this.playlist, function (response) {
            requestFactory.toggleLoader();
            self.responseMessage = response.message;
            self.showResponseMessage = true;
            scope.getRecords(true);
            self.closePlaylistEdit();
            self.resetPlaylistImageUpload();
        }, function () { });
    };

    this.resetPlaylistImageUpload = function () {
        if (typeof window.PlaylistImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.playlist.image = '';
            self.playlist.image_url = '';
        }
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('playlists/info'), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
         });
    };

    this.fetchInfo();
    window.PlaylistImageUploadHandler = new uploadHandler;
    window.PlaylistImageUploadHandler.initate({
        file: 'playlist-image',
        previewer: 'playlist-image-preview',
        deleteIcon: 'playlist-image-delete',
        progress: 'playlist-image-progress',
        beforeUpload: function () {
            if (!scope.$$phase) {
                scope.$apply();
            }
            self.playlist.module = 'playlist-image';
        },
        afterUpload: function (response) {
            self.playlist.image = response.info;
            self.playlist.module = 'playlist-image';
            self.playlist.is_image_updated = 1;
        }
    });


    /**
     *  Function is used to add the playlist
     *  
     *  @param  $event
     */


    this.addPlaylist = function (event) {
        self.resetPlaylistImageUpload();
        this.playlist = {};
        this.playlist.id = '';
        this.playlist.playlist_name = '';
        this.playlist.is_active = String(1);
        scope.errors = {};
    }

    /**
     *  Function is used to edit the playlist
     *  
     *  @param array records
     */

    this.editPlaylist = function (records) {
        self.resetPlaylistImageUpload();
        this.playlist.id = records.id;
        this.playlist.playlist_name = records.playlist_name;
        this.playlist.is_active = String(records.is_active);
        this.playlist.playlist_thumbnail = records.playlist_thumbnail;
        this.playlist.is_image_updated = 0;
        scope.errors = {};
    }


    /**
     *  Function is used to save the playlist
     *  
     *  @param  $event, id
     */

    this.playlistSave = function ($event, id) {
        if (id) {
            requestFactory.post(requestFactory.getUrl('playlists/edit/' + id), this.playlist, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                self.resetPlaylistImageUpload();
                scope.closesidePanelForm();

            }, this.fillError);
        } else {
            requestFactory.post(requestFactory.getUrl('playlists/add'), this.playlist, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                scope.closesidePanelForm();
                self.resetPlaylistImageUpload();
            }, this.fillError);
        }


    }

    /**
     * Function to update status of a playlist
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'playlists';
        scope.updateStatus(record);
    };

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
    });
}];
window.gridInitApp = angular.module('grid',['ng-selectize']);
window.gridControllers = {
    PlaylistsGridController: PlaylistsGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};