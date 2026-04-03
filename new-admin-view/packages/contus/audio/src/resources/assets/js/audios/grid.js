'use strict';
var AudioGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.info = {};
    this.audios = {};
    this.selectedRecords = [];
    this.albums = {};
    this.artists = {};
    requestFactory.toggleLoader();

  

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('audios/info'), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
         });
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        this.albums = data.info.albums;
        this.artists = data.info.artists;
    };

    this.fetchInfo();

    this.updateStatus = function (record) {
        scope.routeName = 'audios';
        scope.updateStatus(record);
    };

    this.deleteSingleRecord = function (id) {

        scope.deleteParams = '';
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/delete-action'), angular.extend({}, {
            selectedCheckbox: id
        }, scope.requestParams), function (data) {

            $('.accordion_wrapper_' + id).remove();
            this.responseMessage = data.message;
            this.showResponseMessage = true;
            if ($('.not-saved').length <= 0) {
                $window.location = requestFactory.getTemplateUrl('admin/audios/audios');
            }
        });
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
      if (angular.element(eventCheckbox).is('checked')) {

        angular.element('#move_collection').attr("data-toggle", "modal");

        if (this.selectedRecords.indexOf(id) == -1) {
          this.selectedRecords.push(id);
        }
      } else if (this.selectedRecords.indexOf(id) > -1) {
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

    /*
     * Function to delete admin audio view detail page.
     */
    this.deleteSingleRecord = function (id) {
        scope.deleteParams = [id];
        scope.ConfirmationDeleteBox = true;
    };
    this.cancelDelete = function () {
        scope.ConfirmationDeleteBox = false;
        scope.deleteParams = '';
    };
    this.confirmDelete = function (status) {
        if (scope.deleteParams.length > 0) {
            self.deleteBulkRecords(scope.deleteParams, status);
            scope.ConfirmationDeleteBox = false;
            scope.deleteParams = '';
        } else {
            scope.ConfirmationDeleteBox = false;
            scope.deleteParams = '';
        }
    };
    this.deleteBulkRecords = function (id, status) {
        scope.deleteParams = '';
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/delete-action'), angular.extend({}, {
            selectedCheckbox: id,
            status: status
        }, scope.requestParams), function (data) {
            $('.accordion_wrapper_' + id).remove();
            this.responseMessage = data.message;
            this.showResponseMessage = true;
            if ($('.not-saved').length <= 0) {
                $window.location = requestFactory.getTemplateUrl('admin/audios/audios');
            }
        });
    };

    this.confirmActivateOrDeactivate = function (is_status) {
        if (is_status == 1) {
            this.isActivateBulkRecord = false;
        } else if (is_status == 0) {
            this.isDeactivateBulkRecord = false;
        }
        self.activateOrDeactivateRecords(scope.activateParams, is_status);
    }

    this.activateOrDeactivateRecords = function (id, is_status) {
        scope.activateParams = '';
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        var activateIdLength = id.length;

        if (is_status == 1) {
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'activate'
            }, scope.requestParams), function (data) {
                this.responseMessage = data.message;
                this.showResponseMessage = true;
                this.selectedRecords = [];
                angular.element('#selectall').removeAttr('checked');
                if (scope.records.length - activateIdLength > 0) {
                    scope.getRecords(true);
                } else {
                    scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
                    scope.getRecords(true);
                }
            });
        } else if (is_status == 0) {
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'deactivate'
            }, scope.requestParams), function (data) {
                this.responseMessage = data.message;
                this.showResponseMessage = true;
                this.selectedRecords = [];
                angular.element('#selectall').removeAttr('checked');
                if (scope.records.length - activateIdLength > 0) {
                    scope.getRecords(true);
                } else {
                    scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
                    scope.getRecords(true);
                }
            });
        }
        angular.element('#move_collection').removeAttr('data-toggle');
    }


    this.deleteBulkRecord = function () {
        scope.deleteParams = this.selectedRecords;
        this.isDeactivateBulkRecord = false;
        this.isActivateBulkRecord = false;
        this.isDeleteBulkRecord = true;
    }

    this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
        scope.activateParams = this.selectedRecords;
        if ($isActivateOrDeactivate == 'activate') {
            this.isDeleteBulkRecord = false;
            this.isDeactivateBulkRecord = false;
            this.isActivateBulkRecord = true;
        } else if ($isActivateOrDeactivate == 'deactivate') {
            this.isDeleteBulkRecord = false;
            this.isActivateBulkRecord = false;
            this.isDeactivateBulkRecord = true;
        }
    }
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
    AudioGridController: AudioGridController
};