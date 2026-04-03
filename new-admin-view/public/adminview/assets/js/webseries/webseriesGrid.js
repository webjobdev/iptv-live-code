'use strict';

var WebseriesGridController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.category = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.showcategory = true;
    this.category.is_image_updated = 0;
    scope.selectedRecords = [];
    scope.errors = {};
    this.languages = {};
    this.categoryTranslation = {};
    scope.translationError = false;
    requestFactory.setThisArgument(this);
    requestFactory.toggleLoader();
    requestFactory.getToaster();
   

    this.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    if (scope.translationError == true) {
                        scope.errors['trans_' + key] = {
                            has: true,
                            message: message[0]
                        };
                    } else {
                        scope.errors[key] = {
                            has: true,
                            message: message[0]
                        };
                    }
                }
            });
        }
    };

    this.closeCategoryEdit = function () {      
        scope.gridSideFormClose();
        scope.hideToster();
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        this.languages = data.info.language;
        this.categoryTranslation.language = data.info.language[0].id;
        
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('webseries/info'), this.defineProperties, function () { });
       
    };

    this.fetchInfo();

    this.changeWebseries = function () {
        this.showcategory = (this.category.is_web_series == 1) ? false : true;
    }
    
    scope.toggleTab = function (tab) {
        if (scope.tabSelected == tab) {
            scope.filters.tab = '';
            scope.tabSelected = '';
            scope.currentPage = 1;
            scope.showRecords = false;
            scope.gridLoadingBar = true;
            scope.getRecords(true);
        } else {
            scope.selectTab('live_videos');
        }
    }

    

    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'webseries';
        scope.updateStatus(record);
    };

    /*
     * Function to Confirm Active and In-Active Status.
     */
    this.statusChangeSingleRecord = function (record) {
        scope.statusParams = record;
        scope.ConfirmationStatusBox = true;
        this.isDeactivateBulkRecord = false;
        this.isActivateBulkRecord = false;
        this.isDeleteBulkRecord = false;
    };

    this.confirmStatus = function () {
        if (scope.statusParams) {
            self.updateStatus(scope.statusParams);
            scope.ConfirmationStatusBox = false;
            scope.statusParams = '';
            // scope.getRecords(true);
        } else {
            scope.ConfirmationStatusBox = false;
            scope.deleteParams = '';
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
      if (angular.element(eventCheckbox).is(':checked')) {
        angular.element('#move_collection').attr("data-toggle", "modal");
        if (scope.selectedRecords.indexOf(id) == -1) {
          scope.selectedRecords.push(id);
        }
      } else if (scope.selectedRecords.indexOf(id) > -1) {
        scope.selectedRecords.splice(scope.selectedRecords.indexOf(id), 1);
      }
    }
    if (scope.selectedRecords.length == 0) {
      angular.element('#move_collection').removeAttr('data-toggle');
    }
    this.checkMasterCheckbox();

  }
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
        scope.selectedRecords = requestFactory.selectBulkRecords();
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
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('webseries/action'), angular.extend({}, {
            selectedCheckbox: id,
            status: status
        }, scope.requestParams), function (data) {
            $('.accordion_wrapper_' + id).remove();
            requestFactory.setToaster('success', data.message);
            requestFactory.getToaster();
            if ($('.not-saved').length <= 0) {
                //$window.location = requestFactory.getTemplateUrl('admin/categories');
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('categories/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'activate'
            }, scope.requestParams), function (data) {
                requestFactory.setToaster('success', data.message);
                requestFactory.getToaster();
                scope.selectedRecords = [];
                angular.element('#selectall').removeAttr('checked');
                if (scope.records.length - activateIdLength > 0) {
                    scope.getRecords(true);
                } else {
                    scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
                    scope.getRecords(true);
                }
            });
        } else if (is_status == 0) {
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('categories/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'deactivate'
            }, scope.requestParams), function (data) {
                requestFactory.setToaster('success', data.message);
                requestFactory.getToaster();
                scope.selectedRecords = [];
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
        scope.deleteParams = scope.selectedRecords;
        this.isDeactivateBulkRecord = false;
        this.isActivateBulkRecord = false;
        scope.ConfirmationStatusBox = false;
        this.isDeleteBulkRecord = true;
    }

    this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
       
        scope.activateParams = scope.selectedRecords;
        if ($isActivateOrDeactivate == 'activate') {
            this.isDeleteBulkRecord = false;
            this.isDeactivateBulkRecord = false;
            scope.ConfirmationStatusBox = false;
            this.isActivateBulkRecord = true;
        } else if ($isActivateOrDeactivate == 'deactivate') {
            this.isDeleteBulkRecord = false;
            this.isActivateBulkRecord = false;
            scope.ConfirmationStatusBox = false;
            this.isDeactivateBulkRecord = true;
        }
    }
    /**
     * Function to select and unselect all checkboxes.
     */
    this.selectAllRecords = function () {
        if (angular.element('#selectall').prop('checked')) {
            scope.selectedRecords = [];
            angular.element('.checkbox').each(function () {
            var isDisabled = angular.element(this).prop('disabled');
         
               if(!isDisabled)
               {
                   
                angular.element(this).prop('checked', true);
                var id = Number(angular.element(this).val());
               }
                
              
                scope.selectedRecords.push(id);
            });
            angular.element('#move_collection').attr("data-toggle", "modal");
        } else {
            angular.element('.checkbox').each(function () {
                angular.element(this).prop('checked', false);
                var id = Number(angular.element(this).val());
                scope.selectedRecords.splice(scope.selectedRecords.indexOf(id), 1);
            });
        }
        if (scope.selectedRecords.length == 0) {
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

      /*   // Update categories in add/edit category form
        requestFactory.get(requestFactory.getUrl('webseries/updated-details'), function (data) {
            this.allCategoriesHTML = $sce.trustAsHtml(data.allCategoriesHTML);
            $timeout(function () {
                $compile(angular.element(".categoryList").contents())(scope);
            }, 100);
        }, function () { }); */

        setTimeout(function () {
            $("#fixTable").tableHeadFixer({
                "head": false,
                "right": 1
            });
        }, 500);

    });
}];

window.gridControllers = {
    WebseriesGridController: WebseriesGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};