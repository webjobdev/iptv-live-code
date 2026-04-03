'use strict';
var AlbumGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.info = {};
    this.albums = {};
    this.albums.is_image_updated = 0;
    scope.artists = {};
    this.selectedRecords = [];
  

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('albums/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        scope.artists = data.info.artists;
        requestFactory.toggleLoader();
    };

    this.fetchInfo();

    this.updateStatus = function (record) {
        scope.routeName = 'albums';
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
    /**
     * Function to select and unselect all checkboxes.
     */
    this.selectAllRecords = function () {
        self.selectedRecords = requestFactory.selectBulkRecords();
    };

}];
window.gridControllers = {
    AlbumGridController: AlbumGridController
};