'use strict';

var audioArtists = angular.module('audioArtists', []);

audioArtists.directive('baseValidator', validatorDirective);

audioArtists.factory('requestFactory', requestFactory);

audioArtists.controller('ViewAudioArtistsController', ['$window', '$scope', '$rootScope', 'requestFactory', '$timeout', function (win, scope, rootScope, requestFactory, $timeout) {
  var self = this;
  scope.errors = {};
  scope.currentPage = 1;
  this.audioAlbums = {};
  this.showResponseMessage = false;
  this.gridLoadingBar = false;
  this.audioListView = true;
  requestFactory.setThisArgument(this);
  this.notFoundFlag = false;

  /**
   * Function is used to call getRecords method to get required set or records
   * 
   * @param int id
   *
   */
  this.fetchData = function (id) {
    requestFactory.get(requestFactory.getUrl('artists/audio-artists/' + id, {
      page: scope.currentPage
    }), function (response) {
      requestFactory.toggleLoader();
      this.audioArtists = response.audioArtists.artist;
      this.audioArtists.audios = response.audioArtists.audios.data;
      this.audioAlbums.album = response.audioArtists.audios.data.album;
      scope.totalRecords = parseInt(response.audioArtists.audios.total);
      scope.rowsPerPage = parseInt(response.audioArtists.audios.per_page);
      scope.currentPage = parseInt(response.audioArtists.audios.current_page);
      this.paginate(Math.ceil(scope.totalRecords / scope.rowsPerPage));
    }, function (response) {
      self.notFoundFlag = true;
      requestFactory.toggleLoader();
    });
  }

  /**
   * Function is used to call getListRecord method to get required set or records
   * 
   * @param int pageNumber
   * @param boolean orderStatus
   * @return void
   */
  scope.loadRecords = function (pageNumber, orderStatus) {
    scope.currentPage = parseInt(pageNumber);
    self.fetchData(self.audioArtists.id);
    requestFactory.toggleLoader();
  }


  this.showGridView = function () {
    this.videoGridView = true;
    this.audioListView = false;
  }

  this.showListView = function () {
    this.audioGridView = false;
    this.audioListView = true;
  }

  this.paginate = function (totalLinks) {
    scope.links = [];
    if (scope.currentPage > totalLinks) {
      return false;
    }
    var counter = Math.floor(scope.currentPage / 5);
    if (counter == 0) {
      counter = 1;
    } else {
      counter = counter * 5;
    }
    if ((totalLinks - counter) >= 5) {
      var counterLimit = counter + 5;
    } else {
      var counterLimit = totalLinks;
    }
    var initialCounter = counter + 5;
    if ((scope.currentPage > 1) && (totalLinks > 1)) {
      scope.links.push({
        value: 'Previous',
        pageNumber: scope.currentPage - 1,
        current: false
      });
    }
    /*if((counter >= 5 ) && (totalLinks > 1) ) {
        scope.links.push({value:'First',pageNumber:1, current:false });
    }*/
    if ((counter >= 4) && (totalLinks > 1)) {
      scope.links.push({
        value: 'First',
        pageNumber: 1,
        current: false
      });
    }
    for (counter; counter <= counterLimit; counter++) {

      if (scope.currentPage == counter) {
        scope.links.push({
          value: counter,
          pageNumber: counter,
          current: true
        });
      } else {
        scope.links.push({
          value: counter,
          pageNumber: counter,
          current: false
        });
      }
    }

    if ((initialCounter < totalLinks - 1) && totalLinks > 1) {
      scope.links.push({
        value: '...',
        pageNumber: null,
        current: false
      });
      scope.links.push({
        value: totalLinks - 1,
        pageNumber: totalLinks - 1,
        current: false
      });
      scope.links.push({
        value: totalLinks,
        pageNumber: totalLinks,
        current: false
      });
      scope.links.push({
        value: 'Next',
        pageNumber: scope.currentPage + 1,
        current: false
      });
    }
    /*latest*/
    else if ((initialCounter == totalLinks - 1) && totalLinks > 1) {
      scope.links.push({
        value: totalLinks,
        pageNumber: totalLinks,
        current: false
      });
      scope.links.push({
        value: 'Next',
        pageNumber: scope.currentPage + 1,
        current: false
      });
    } else if (scope.currentPage != totalLinks && totalLinks > 1) {
      scope.links.push({
        value: 'Next',
        pageNumber: scope.currentPage + 1,
        current: false
      });
    } else {
      //
    }
  };

  $timeout(function () {
    angular.element('[data-toggle="popover"]').popover();
  }, 300);
}]);

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
  angular.bootstrap(document, ['audioArtists']);
});