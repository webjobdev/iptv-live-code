'use strict';

var playlistVideos = angular.module('playlistVideos', []);
var commonAPP = playlistVideos;

playlistVideos.directive('baseValidator', validatorDirective);

playlistVideos.factory('requestFactory', requestFactory);

playlistVideos.controller('ViewPlaylistController', ['$window', '$scope', '$rootScope', 'requestFactory', '$timeout', function (win, scope, $rootScope, requestFactory, $timeout) {
  var self = this;
  scope.errors = {};
  scope.currentPage = 1;
  this.videoAlbums = {};
  this.showResponseMessage = false;
  this.gridLoadingBar = false;
  this.videoListView = true;
  requestFactory.setThisArgument(this);
  this.notFoundFlag = false;
  this.heading=[];
  scope.selectedRecords=[];
  this.ConfirmationDeleteBox = false;
  this.paginationList = ['10', '50','100'];
  this.rows = "10";
  this.selectedRecords = [];
  this.selectedId="";

  /**
   * Function is used to call getRecords method to get required set or records
   * 
   * @param int id
   *
   */
  this.fetchData = function (id) {
    requestFactory.get(requestFactory.getUrl('videos/playlists/playlist-videos/' + id, {
      page: scope.currentPage,rowsPerPage:this.rows
    }), function (response) {
      requestFactory.toggleLoader();
      this.playlistVideos = response.playlistVideos.playlist;
      this.playlistVideos.videos = response.playlistVideos.videos.data;
      this.heading = response.playlistVideos.gridHeadings.heading;
      // this.audioAlbums.album = response.playlistVideos.audios.data.album;
      scope.totalRecords = parseInt(response.playlistVideos.videos.total);
      scope.rowsPerPage = parseInt(response.playlistVideos.videos.per_page);
      scope.currentPage = parseInt(response.playlistVideos.videos.current_page);
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
    self.fetchData(self.playlistVideos.id);
    requestFactory.toggleLoader();
  }

  this.deleteSingleRecord = function (video_id) {
   this.selectedId = video_id;
    this.ConfirmationDeleteBox = true;
       
  };
  /**
             * Function is used to show list view with required number of rows
             * 
             * @return void
             */

      this.changeRows = function() {
      requestFactory.toggleLoader();
       
        scope.currentPage = 1;
        scope.searchTotal   = false;
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        scope.rowsPerPage = Number(this.rows);
        scope.fieldName     = '';
        scope.tableHeading  = true;
        scope.records = '';
        // if($scope.grid.searchBy !='' && $scope.searchValue !='') {
        //   $scope.searchTotal = true;
        // }
        scope.deleteId = [];
        this.fetchData(self.playlistVideos.id);
      }
this.confirmDelete = function () {
    requestFactory.toggleLoader();
this.selectedId = this.selectedId.length <= 0 ? this.selectedRecords:this.selectedId;
     scope.deleteParams = '';
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('playlists/delete-action'), angular.extend({}, {
          id:this.playlistVideos.id, 
          video_id:this.selectedId
        }, scope.requestParams), function (data) {
          angular.element('.checkbox').each(function () {
                angular.element(this).prop('checked', false);
            });
          this.selectedRecords = [];
          this.selectedId="";
            this.responseMessage = data.message;
            this.showResponseMessage = true;
            if(data.statusCode === 200){
              this.fetchData(self.playlistVideos.id);
            }
        });
  };

  this.showGridView = function () {
    this.videoGridView = true;
    this.videoListView = false;
  }

  this.showListView = function () {
    this.videoGridView = false;
    this.videoListView = true;
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
        angular.element('#move_collection').attr("data-toggle", "modal");

        if (this.selectedRecords.indexOf(id) == -1) {
          this.selectedRecords.push(id);
        }
       else if (this.selectedRecords.indexOf(id) > -1) {
        this.selectedRecords.splice(this.selectedRecords.indexOf(id), 1);
      }
    }

    if (this.selectedRecords.length == 0) {
      angular.element('#move_collection').removeAttr('data-toggle');
    }
    this.checkMasterCheckbox();

  }
  /**
     * Function to select and unselect all checkboxes.
     */
    this.selectAllRecords = function () {
        self.selectedRecords = requestFactory.selectBulkRecords();
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
            angular.element('#move_collection').attr("data-toggle", "modal");
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

  $timeout(function () {
    angular.element('[data-toggle="popover"]').popover();
  }, 300);
}]);

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
  angular.bootstrap(document, ['playlistVideos']);
});

/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
  for(var controller in window.gridControllers){
    
      if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
        playlistVideos.controller(controller,window.gridControllers[controller]);
      }
  }
}