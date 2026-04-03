'use strict';

var SeasonGridController = ['flowFactory', '$scope', '$rootScope','requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (flowFactory, scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.season = {};
    this.season.is_image_updated = 0;
    scope.errors = {};
    this.languages = {};
    this.seasonTranslation = {};
    this.group_translation = {};
    this.groupTranslation = {};
    this.selectedRecords = [];
    scope.translationError = false;
    requestFactory.setThisArgument(this);
    requestFactory.getToaster();
    scope.videoConfirmationDeleteBox = false;

    this.resetCategoryImageUpload = function () {
        if (typeof window.CategoryImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.season.image = '';
            // self.season.image_url = '';
        }
    };

//    this.removeThumbnailProperty = function (event, id) {
//        console.log(event);
//        console.log(id);
//     // angular.element('#removeimg').each(function () {
//     //     // if (angular.element(this).prop('checked') == false) {
//     //     //     mainCheckbox = false;
//     //     // }
//     //     console.log('click');
//     //     // self.season.image = '';
//     //     angular.element(document.querySelector("#category-image-preview")).addClass("ng-hide");

//     // });
//     // self.season.image = '';
//    };

this.removeThumbnailProperty = function (event, id) {
    $('#category-image-progress').html('');
    self.season.image = '';
    // self.season.category-image = '';
    // self.artist.is_image_updated = 0;
    self.season.module = 'category-image';
    }

   this.deleteCategoryImage = function () {
    requestFactory.toggleLoader();
    requestFactory.post(requestFactory.getUrl('season/delete-season-image/' + this.season.id), this.season, function (response) {
        requestFactory.toggleLoader();
        requestFactory.setToaster('success', response.message);
        requestFactory.getToaster();
        scope.getRecords(true);
        // self.closeCategoryEdit();
        self.resetCategoryImageUpload();
    }, function () { });
    this.season.image = '';

   };

  
    this.defineProperties = function (data) {
        this.info = data.info;
        this.languages = data.info.language;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('season/info'), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();


    // window.CategoryImageUploadHandler = new uploadHandler;
    // window.CategoryImageUploadHandler.initate({
    //     file: 'category-image',
    //     previewer: 'category-image-preview',
    //     deleteIcon: 'category-image-delete',
    //     progress: 'category-image-progress',
    //     isImageFileType:true,
    //     beforeUpload: function () {
    //         if (!scope.$$phase) {
    //             scope.$apply();
    //         }
    //     },
    //     afterUpload: function (response) {
    //         console.log(response);
    //         self.season.image = response.info;
    //         self.season.module = 'category-image';
    //         self.season.is_image_updated = 1;
    //     },
    // });


    this.upload = function () {
        window.CategoryImageUploadHandler = new uploadHandler;
        window.CategoryImageUploadHandler.initate({
            file: 'category-image',
            previewer: 'category-image-preview',
            deleteIcon: 'category-image-delete',
            progress: 'category-image-progress',
            isImageFileType:true,
            beforeUpload: function () {
                if (!scope.$$phase) {
                    scope.$apply();
                }
            },
            afterUpload: function (response) {
                self.season.image = response.info;
                self.season.module = 'category-image';
                self.season.is_image_updated = 1;
                if (scope.errors.hasOwnProperty('image'))
                delete scope.errors['image'];
            },
        });
    }
    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addgroup = function ($event) {
        $(".sidepanel").addClass("in");
        this.resetCategoryImageUpload();
        scope.errors = {};
        self.season = {};
        this.season = {};
        this.season.is_active = true;
        this.season.image = self.season.image;
        $("#seasonForm").css('display', 'block');
        $("#seasonTranslationForm").css('display', "none");
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.upload();
        this.selectedRecords = [];
    }

    /**
     *  Function is used to save the season
     *  
     *  @param  $event, id
     */
    this.save = function ($event, id) {
        if (baseValidator.validateAngularForm($event.target, scope)) {
            if (id) {
                requestFactory.post(requestFactory.getUrl('season/edit/' + id), this.season, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closegroupEdit();
                    this.resetCategoryImageUpload();
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('season/add'), this.season, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closegroupEdit();
                    this.resetCategoryImageUpload();
                }, this.fillError);
            }
        }
    }

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

    /**
     *  Function is used to get the categories rules
     *  
     */
    this.getgroupEdit = function (record) {
        $(".sidepanel").addClass("in"); 
        this.upload();
        scope.errors = {};
        this.season.title = record.title;
        this.season.season_order = record.season_order;
        this.season.is_active = (record.is_active) ? true : false;
        this.season.id = record.id;
        this.season.image = record.image;
        self.season.image = record.image;
        this.group_translation = record.season_translation;
        this.seasonTranslation.language = parseInt(this.languages[0].id);
        $("#seasonForm").css('display', 'block');
        $("#seasonTranslationForm").css('display', "none");
        self.seasonTranslation.title = '';
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.selectedRecords = [];
        // this.resetCategoryImageUpload();
    }

    this.closegroupEdit = function () {
        scope.gridSideFormClose();
        this.resetCategoryImageUpload();
    };

    /**
     * Function to update status of a preset,group,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'season';
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

    this.deleteSingleRecordVideos = function (id) {
        scope.deleteParams = [id];
        scope.videoConfirmationDeleteBox = true;
    };

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

        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('season/action'), angular.extend({}, {
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


    this.languageChange = function () {
        scope.errors = [];
        self.groupTranslation;
        self.group_translation;

        if (this.seasonTranslation.language == this.languages[0].id) {
            $("#seasonForm").css('display', 'block');
            $("#seasonTranslationForm").css('display', "none");
        } else {
            self.seasonTranslation.title = '';
            angular.forEach(this.group_translation, function (value) {
                if (value.language_id == self.seasonTranslation.language) {
                    console.log(value);
                    self.seasonTranslation.languageCode = value.language_id;
                    self.seasonTranslation.title = value.name ?value.name:value.title;
                   
                }
            });
            $("#seasonForm").css('display', 'none');
            $("#seasonTranslationForm").css('display', 'block');
        }
    }

    this.seasonTranslationSave = function (event, id) {
        this.seasonTranslation.languageCode = this.seasonTranslation.language;
        requestFactory.post(requestFactory.getUrl('season/addLanguage/' + id), this.seasonTranslation, function (response) {           
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            var myIndex = scope.records.map(function (obj) {
                return obj.id;
            }).indexOf(id);
            var langIndex = scope.records[myIndex].season_translation.map(function (obj) {
                return obj.language_id;
            }).indexOf(parseInt(this.seasonTranslation.language));
            langIndex = (langIndex >= 0) ? langIndex : 0;
            scope.records[myIndex].season_translation[langIndex] = {
                language_id: this.seasonTranslation.language,
                name: this.seasonTranslation.title
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
        scope.isSeason=true;
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
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('season/delete-action'), angular.extend({}, {
            selectedCheckbox: id,
            status: status
        }, scope.requestParams), function (data) {
            $('.accordion_wrapper_' + id).remove();
            requestFactory.setToaster('success', data.message);
            requestFactory.getToaster();
            if ($('.not-saved').length <= 0) {
                $window.location = requestFactory.getTemplateUrl('admin/season');
            }
        });
    };

    this.confirmActivateOrDeactivateVideos = function (is_status) {
        
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('season/bulk-update-status'), angular.extend({}, {
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('season/bulk-update-status'), angular.extend({}, {
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
            scope.ConfirmationStatusBox = false;
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
        setTimeout(function () {
            $("#fixTable").tableHeadFixer({
                "head": false,
                "right": 1
            });
        }, 500);
    });
    scope.$watch('videoConfirmationDeleteBox', function() {
        if(!scope.videoConfirmationDeleteBox) {
          $('#videoDeleteModal').modal('hide');
        }
    });
}];



window.gridControllers = {
    SeasonGridController: SeasonGridController
};
window.gridInitApp = angular.module('grid', ['flow']);
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};