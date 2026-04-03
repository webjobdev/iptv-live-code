'use strict';

var audioArtists = angular.module('audioArtists', []);
var commonAPP = audioArtists;
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
  scope.hideRowsPerPage = true;
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
      scope.paginationList = ['10', '50','100'];
      requestFactory.paginate(scope,Math.ceil(scope.totalRecords / scope.rowsPerPage));
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
  $timeout(function () {
    angular.element('[data-toggle="popover"]').popover();
  }, 300);
}]);
audioArtists.filter('truncate', function () {
  return function (value, wordwise, max, tail) {
      if (!value) return '';
      max = parseInt(max, 10);
      if (!max) return value;
      if (value.length <= max) return value;

      value = value.substr(0, max);
      if (wordwise) {
          var lastspace = value.lastIndexOf(' ');
          if (lastspace != -1) {
              value = value.substr(0, lastspace);
          }
      }
      return value + (tail || ' …');
  };
});
/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
  angular.bootstrap(document, ['audioArtists']);
});
/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
  for(var controller in window.gridControllers){
    
      if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
        audioArtists.controller(controller,window.gridControllers[controller]);
      }
  }
}