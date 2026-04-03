'use strict';

var CategoryGridController = ['$scope', '$rootScope','requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.category = {};
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
                            message: requestFactory.capitalize(message[0])
                        };
                    } else {
                        scope.errors[key] = {
                            has: true,
                            message: requestFactory.capitalize(message[0])
                        };
                    }
                }
            });
        }
    };

    this.closeCategoryEdit = function () {      
        scope.gridSideFormClose();
    };

    this.deleteCategoryImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('livecategory/delete-category-image/' + this.category.id), this.category, function (response) {
            requestFactory.toggleLoader();
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            scope.getRecords(true);
            self.closeCategoryEdit();
            self.resetCategoryImageUpload();
        }, function () { });
    };

    this.resetCategoryImageUpload = function () {
        if (typeof window.CategoryImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.category.image = '';
            self.category.image_url = '';
        }
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        this.languages = data.info.language;
        this.categoryTranslation.language = data.info.language[0].id;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('livecategory/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
       
    };

    this.fetchInfo();

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
            self.category.image = response.info;
            self.category.module = 'category-image';
            self.category.is_image_updated = 1;
        }
    });

    this.changeWebseries = function () {
        this.showcategory = (this.category.is_web_series == 1) ? false : true;
    }
    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addCategory = function ($event) {
        $(".sidepanel").addClass("in");
        self.resetCategoryImageUpload();
        angular.element(".categoryList li").show();
        scope.errors = {};
        self.category = {};
        self.categoryFull = {};
        this.categoriesUniqueRoute = requestFactory.getUrl('livecategory/categories-unique');
        this.category = {};
        this.categoryFull = {};
        this.category.is_active = String(0);
        this.category.is_web_series = String(0);
        this.category.preference_order = null;
        this.category.is_leaf_category = String(0);
        self.pref = 0;
        self.pref = '';
        this.showcategory = true;
        $("#categoriesForm").css('display', 'block');
        $("#categoriesTranslationForm").css('display', "none");
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        scope.selectedRecords = [];
    }

    /**
     *  Function is used to edit the categories
     *  
     *  @param array records
     */
    this.editCategory = function (records) {
        $(".sidepanel").addClass("in"); 
        self.resetCategoryImageUpload();
        angular.element(".categoryList li").show();
        angular.element("#category_id_" + records.id).hide();
        scope.errors = {};
        this.categoryFull = records;
        self.categoryFull = records;
        this.categoriesUniqueRoute = requestFactory.getUrl('livecategory/categories-unique/' + records.id);
        this.category.title = records.title;
        this.category.parent_id = String(records.parent_id);
        this.category.is_active = (records.is_active) ? true : false;
        this.category.is_web_series = (records.is_web_series ===  1) ? true : false;
        this.category.preference_order = String((records.preference_order === null) ? '' : records.preference_order);
        self.pref = 0;
        self.pref = '';
        this.category.is_leaf_category = String(records.is_leaf_category);
        this.category.category_order = records.category_order;
        self.pref = ((records.preference_order === null) ? '0' : '1')
        this.category.id = records.id;
        this.category.image_url = records.image_url;
        this.showcategory = (this.category.is_web_series == 1) ? false : true;
        this.category.is_image_updated = 0;
        this.category_translation = records.category_translation;
        
        this.categoryTranslation.title = '';
        $("#categoriesForm").css('display', 'block');
        $("#categoriesTranslationForm").css('display', "none");
                 
        this.categoryTranslation.language = parseInt(this.languages[0].id);
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        scope.selectedRecords = [];
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
    this.removeThumbnailProperty = function () {
        this.category.image_url = '';
        this.category.image = '';
      };
    /**
     *  Function is used to save the category
     *  
     *  @param  $event, id
     */
    this.categorySave = function ($event, id) {
        
        if (baseValidator.validateAngularForm($event.target, scope)) {
            if (document.querySelector('select[data-ng-model="catgridCtrl.pref"]')) {
                this.category.preference_order = (document.querySelector('select[data-ng-model="catgridCtrl.pref"]').value == '1') ? this.category.preference_order : '';
            }
            this.category.parent_id = (this.category.is_web_series == 1) ? String(0) : String(this.category.parent_id);
            this.category.parent_id  = (this.category.parent_id === 'undefined') ? '' : this.category.parent_id;
            this.category.is_category = 2;
            if (id) {
                requestFactory.post(requestFactory.getUrl('livecategory/edit/' + id), this.category, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closeCategoryEdit();
                    self.pref = 0;
                    self.pref = '';
                    self.resetCategoryImageUpload();
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('livecategory/add'), this.category, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closeCategoryEdit();
                    self.resetCategoryImageUpload();
                    self.pref = 0;
                    self.pref = '';
                }, this.fillError);
            }
        }
    }

    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'categories';
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

   
    this.categoryTranslateSave = function (event, id) {
        this.categoryTranslation.languageCode = this.categoryTranslation.language;
        requestFactory.post(requestFactory.getUrl('livecategory/addLanguage/' + id), this.categoryTranslation, function (response) {
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            var myIndex = scope.records.map(function (obj) {
                return obj.id;
            }).indexOf(id);
            var langIndex = scope.records[myIndex].category_translation.map(function (obj) {
                return obj.language_id;
            }).indexOf(parseInt(this.categoryTranslation.language));
            langIndex = (langIndex >= 0) ? langIndex : 0;

            scope.records[myIndex].category_translation[langIndex] = {
                language_id: this.categoryTranslation.language,
                title: this.categoryTranslation.title
            };
            this.closeCategoryEdit();

        }, function (e) {
            scope.translationError = true;
            this.fillError(e);
        });


    };

    this.languageChange = function () {
        scope.errors = [];
        if (this.categoryTranslation.language == this.languages[0].id) {
            $("#categoriesForm").css('display', 'block');
            $("#categoriesTranslationForm").css('display', "none");
        } else {
            self.categoryTranslation.title = '';
            self.categoryTranslation;
            self.category_translation;
            angular.forEach(this.category_translation, function (value) {
                if (value.language_id == self.categoryTranslation.language) {
                    self.categoryTranslation.languageCode = value.language_id;
                    self.categoryTranslation.title = value.title;
                }
            });
            
            $("#categoriesForm").css('display', 'none');
            $("#categoriesTranslationForm").css('display', 'block');
        }
    }


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
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('livecategory/action'), angular.extend({}, {
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('livecategory/bulk-update-status'), angular.extend({}, {
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('livecategory/bulk-update-status'), angular.extend({}, {
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

        // Update categories in add/edit category form
        requestFactory.get(requestFactory.getUrl('livecategory/updated-details'), function (data) {
            this.allCategoriesHTML = $sce.trustAsHtml(data.allCategoriesHTML);
            $timeout(function () {
                $compile(angular.element(".categoryList").contents())(scope);
            }, 100);
        }, function () { });

        setTimeout(function () {
            $("#fixTable").tableHeadFixer({
                "head": false,
                "right": 1
            });
        }, 500);

    });
}];
window.gridControllers = {
    CategoryGridController: CategoryGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};