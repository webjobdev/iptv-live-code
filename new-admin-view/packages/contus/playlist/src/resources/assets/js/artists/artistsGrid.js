'use strict';

var ArtistsGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.artist = {};
    scope.responseMessage = false;
    scope.showResponseMessage = false;
    this.showartist = true;
    this.audioLength = 0;
    this.albumLength = null;
    this.artist.is_image_updated = 0;
    scope.isArtistImgDeleted= false;
    scope.artistImgDeletedMessage = '';
    scope.errors = {};
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

    this.closeArtistEdit = function () {
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
    };

    this.deleteArtistImage = function () {
        requestFactory.post(requestFactory.getUrl('artists/delete-artist-image/' + this.artist.id), this.artist, function (response) {
            scope.isArtistImgDeleted = true;
            scope.artistImgDeletedMessage = response.message;
            scope.getRecords(true);
            self.resetArtistImageUpload();
        }, function () { });
    };

    this.resetArtistImageUpload = function () {

        if (typeof window.ArtistImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            this.artist.image = '';
            this.artist.artist_thumbnail = '';
        }
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('artists/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();
    $timeout(function () {
        window.ArtistImageUploadHandler = new uploadHandler;
        window.ArtistImageUploadHandler.initate({
            file: 'artist-image',
            previewer: 'artist-image-preview',
            deleteIcon: 'artist-image-delete',
            progress: 'artist-image-progress',
            beforeUpload: function () {
                self.artist.artist_thumbnail = undefined;
                if (!scope.$$phase) {
                    scope.$apply();
                }
            },
            afterUpload: function (response) {
                self.artist.image = response.info;
                self.artist.artist_image = response.info;
                angular.element('#artist-image-delete').removeClass('hide');
                self.artist.module = 'artist-image';
                self.artist.is_image_updated = 1;
                if (scope.errors.hasOwnProperty('artist_image'))
                    delete scope.errors['artist_image'];
            }
        });
    }, 1000);

    /**
     *  Function is used to add the artist
     *  
     *  @param  $event
     */


    this.addArtist = function (event) {
        $(".sidepanel").addClass("in");
        self.resetArtistImageUpload();
        this.artist = {};
        this.artist.id = '';
        this.artist.artist_name = '';
        this.artist.artist_biography = '';
        this.artist.is_active = String(1);
        this.artist.artist_thumbnail = '';
        this.artist.is_image_updated = 0;
        this.artist.image = '';
        this.audioLength = 0;
        this.albumLength = null;
        scope.isUpdated = false;
        scope.isArtistImgDeleted = false;
        $('#artist-image-progress').html('');
        scope.errors = {};
    }

    /**
     *  Function is used to edit the artists
     *  
     *  @param array records
     */

    this.editArtist = function (records) {
        $(".sidepanel").addClass("in");
        self.resetArtistImageUpload();
        this.artist.id = records.id;
        this.artist.artist_name = records.artist_name;
        this.artist.artist_biography = records.artist_biography;
        this.artist.is_active = String(records.is_active);
        this.artist.artist_thumbnail = records.artist_thumbnail;
        this.artist.is_image_updated = 0;
        this.audioLength = records.audio.length;
        this.albumLength = records.album;
        scope.isArtistImgDeleted = false;
        if (records.artist_thumbnail != '') {
            $('.preview-image').removeClass('hide');
            $('.clsProgressbar').addClass('hide');
        }
        scope.errors = {};
    }

    this.removeThumbnailProperty = function () {
        $('#artist-image-progress').html('');
        self.artist.image = '';
        self.artist.artist_image = '';
        self.artist.is_image_updated = 0;
    }

    /**
     *  Function is used to save the artist
     *  
     *  @param  $event, id
     */

    this.artistSave = function ($event, id) {
        if (id) {
            requestFactory.post(requestFactory.getUrl('artists/edit/' + id), this.artist, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                self.resetArtistImageUpload();
                scope.closesidePanelForm();

            }, this.fillError);
        } else {
            requestFactory.post(requestFactory.getUrl('artists/add'), this.artist, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                scope.closesidePanelForm();
                self.resetArtistImageUpload();
            }, this.fillError);
        }


    }
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

window.gridControllers = {
    ArtistsGridController: ArtistsGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};