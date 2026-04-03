'use strict';

var GenreGridController = ['flowFactory', '$scope', '$rootScope','requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (flowFactory, scope, rootScope,requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.group = {};
    scope.errors = {};
    this.languages = {};
    this.groupTranslation = {};
    this.selectedRecords = [];
    this.groupTranslation = {};
    scope.translationError = false;
    requestFactory.setThisArgument(this);
    requestFactory.getToaster();
    this.uniqueRoute = requestFactory.getUrl('genre/examgroups-unique');
    
    scope.existingFlowObject = flowFactory.create({
        target: document.querySelector('meta[name="base-api-url"]').getAttribute('content') + '/image?types=groups',
        permanentErrors: [404, 500, 501],
        testChunks: false,
        maxChunkRetries: 1,
        chunkRetryInterval: 5000,
        simultaneousUploads: 4,
        singleFile: true
    });
    scope.existingFlowObject.on('fileSuccess', function (event, message) {
        if (message) {
            self.group.group_image = message;
            angular.element('.loaders').hide();
            angular.element('.submitbutton').attr('disabled', false);
        }
    });
    scope.existingFlowObject.on('fileAdded', function (file) {
        if (file.size > 2097152) {
            scope.errors[key] = {
                has: true,
                message: 'Image should be below 2 mb'
            };
            return false;
        }
        angular.element('.loaders').show();
        angular.element('.submitbutton').attr('disabled', true);

    });
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

    this.closegroupEdit = function () {
        scope.gridSideFormClose();
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        this.category = data.info.category;
        this.languages = data.info.language;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('genre/info'), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();

    /**
     *  Function is used to get the genre rules
     *  
     */
    this.getgroupEdit = function (record) {

        $(".sidepanel").addClass("in"); 
        scope.existingFlowObject.cancel();
        scope.errors = {};
        this.uniqueRoute = requestFactory.getUrl('genre/' + record.id);
        this.group.name = record.name;
        this.group.order = record.order;
        this.group.group_image = record.group_image;
        this.group.collection_id = record.collection_id;
        this.group.is_active = (record.is_active) ? true : false;
        this.group.id = record.id;
        this.group_translation = record.group_translation;
        this.groupTranslation.language = parseInt(this.languages[0].id);
        $("#groupForm").css('display', 'block');
        $("#groupTranslationForm").css('display', "none");
        self.groupTranslation.name = '';
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.selectedRecords = [];
    }

    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addgroup = function ($event) {
        $(".sidepanel").addClass("in");
        scope.existingFlowObject.cancel();
        scope.errors = {};
        self.group = {};
        this.uniqueRoute = requestFactory.getUrl('genre/examgroups-unique');
        this.group = {};
        this.group.is_active = true;
        $("#groupForm").css('display', 'block');
        $("#groupTranslationForm").css('display', "none");
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.selectedRecords = [];
    }

    /**
     *  Function is used to save the group
     *  
     *  @param  $event, id
     */
    this.examgroupsave = function ($event, id) {
        if (baseValidator.validateAngularForm($event.target, scope)) {
            if (id) {
                requestFactory.post(requestFactory.getUrl('genre/edit/' + id), this.group, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closegroupEdit();
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('genre/add'), this.group, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();                  
                    scope.getRecords(true);
                    this.closegroupEdit();
                }, this.fillError);
            }
        }
    }

      
       
    /**
     * Function to update status of a preset,group,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'genre';
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
        } else {
            scope.ConfirmationStatusBox = false;
            scope.deleteParams = '';
        }
    };

    this.languageChange = function () {
        scope.errors = [];
        if (this.groupTranslation.language == this.languages[0].id) {
            $("#groupForm").css('display', 'block');
            $("#groupTranslationForm").css('display', "none");
        } else {
            self.groupTranslation.name = '';
            self.groupTranslation;
            self.group_translation;
            angular.forEach(this.group_translation, function (value) {
                if (value.language_id == self.groupTranslation.language) {
                    self.groupTranslation.languageCode = value.language_id;
                    self.groupTranslation.name = value.name;
                }
            });
            $("#groupForm").css('display', 'none');
            $("#groupTranslationForm").css('display', 'block');
        }
    }

    this.examgroupTranslationsave = function (event, id) {
        this.groupTranslation.languageCode = this.groupTranslation.language;

        requestFactory.post(requestFactory.getUrl('genre/addLanguage/' + id), this.groupTranslation, function (response) {
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            var myIndex = scope.records.map(function (obj) {
                return obj.id;
            }).indexOf(id);
            var langIndex = scope.records[myIndex].group_translation.map(function (obj) {
                return obj.language_id;
            }).indexOf(parseInt(this.groupTranslation.language));
            langIndex = (langIndex >= 0) ? langIndex : 0;
            scope.records[myIndex].group_translation[langIndex] = {
                language_id: this.groupTranslation.language,
                name: this.groupTranslation.name
            };

            this.closegroupEdit();

        }, function (e) {
            scope.translationError = true;
            this.fillError(e);
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
      if (angular.element(eventCheckbox).is(':checked')) {

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
    
    this.confirmActivateOrDeactivateVideos = function (is_status) {
        
        if (is_status == 1) {
            this.isActivateBulkRecord = false;
        } else if (is_status == 0) {
            this.isDeactivateBulkRecord = false;
        }
        self.activateOrDeactivateRecords(scope.activateParams, is_status);
    }

    this.activateOrDeactivateRecords = function (id, is_status) {console.log('bulk activate deactivate');
        scope.activateParams = '';
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        var activateIdLength = id.length;

        if (is_status == 1) {
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('genre/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'activate'
            }, scope.requestParams), function (data) {
                requestFactory.setToaster('success', data.message);
                requestFactory.getToaster();
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('genre/bulk-update-status'), angular.extend({}, {
                selectedCheckbox: id,
                isStatus: 'deactivate'
            }, scope.requestParams), function (data) {
                requestFactory.setToaster('success', data.message);
                requestFactory.getToaster();
              
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
        scope.ConfirmationStatusBox = false;
        this.isDeleteBulkRecord = true;
    }

    this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
       
        scope.activateParams = this.selectedRecords;
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
     * Function to delete bulk records
     */
    this.cancelDeleteVideos = function () {
        scope.videoConfirmationDeleteBox = false;
        scope.deleteParams = '';
    };

    this.confirmDeleteVideos = function (recordStatus) {
        if (scope.deleteParams.length > 0) {
            self.deleteRecordsVideos(scope.deleteParams, recordStatus);
            scope.videoConfirmationDeleteBox = false;
            scope.deleteParams = '';
        } else {
            scope.videoConfirmationDeleteBox = false;
            scope.deleteParams = '';
        }
    };

    this.deleteRecordsVideos = function (id, recordStatus) {
        scope.deleteParams = '';
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        var deleteIdLength = id.length;

        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('genre/action'), angular.extend({}, {
            selectedCheckbox: id,
            recordStatus: recordStatus
        }, scope.requestParams), function (data) {
            requestFactory.setToaster('success', data.message);
            requestFactory.getToaster();
          
            angular.element('#selectall').removeAttr('checked');
            if (scope.records.length - deleteIdLength > 0) {
                scope.getRecords(true);             
              
            } else {
                scope.currentPage = (scope.currentPage - 1 == 0) ? 1 : scope.currentPage - 1;
                scope.getRecords(true);
            }
            
        });
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

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }

        setTimeout(function () {
            $("#fixTable").tableHeadFixer({
                "head": false,
                "right": 1
            });
        }, 500);
    });
}];

window.gridControllers = {
    GenreGridController: GenreGridController
};
window.gridInitApp = angular.module('grid', ['flow']);
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};