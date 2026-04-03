'use strict';

var CastGridController = ['$scope', '$rootScope','requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope,requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    scope.cast = [2];
    this.showcategory = true;
    scope.singlePreload = [];
    requestFactory.getToaster();
   
    scope.errors = {};
    scope.singleConfig = {
        create: false,
        valueField: 'id',
        labelField: 'title',
        searchField: 'title',
        plugins: {
            'remove_button': {},
            'no_results': {'message': 'Video not available. You can upload the video in Manage Videos section.'}
        },
        placeholder: 'Enter video by title and select...',
        preload: true,
        maxItems: 200,
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: $('meta[name="base-api-url"]').attr('content') + '/cast/searchvideos?search=' + query,
                beforeSend: function(request){request.setRequestHeader('Authorization', 'Bearer '+requestFactory.access_token)},
                type: 'GET',
                dataType: 'json',
                data: {
                    name: query,
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.response.search_videos.data);
                }
            });
        },
        onChange: function(value) {
        }
   };
    this.languages = {};
    this.castTranslation = {};
    this.selectedRecords = [];
    
    scope.translationError = false;
    requestFactory.setThisArgument(this);
  

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
        this.resetCategoryImageUpload();
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
        if ($('#categoriesForm').css('display') == 'none') {
            $("#categoriesForm").css('display', 'block');
            $("#categoriesTranslationForm").css('display', "none");
        } else {
            $("#categoriesForm").css('display', 'none');
            $("#categoriesTranslationForm").css('display', "block");
        }

        $(".sidepanel").removeClass("in");        
    };

    this.deleteCategoryImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('categories/delete-category-image/' + this.cast.id), this.cast, function (response) {
            requestFactory.toggleLoader();
            srequestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            scope.getRecords(true);
            self.closeCategoryEdit();
            self.resetCategoryImageUpload();
        }, function () { });
    };

    window.CategoryImageUploadHandler = new uploadHandler;
    window.CategoryImageUploadHandler.initate({
        file: 'cast-image',
        previewer: 'cast-image-preview',
        deleteIcon: 'cast-image-delete',
        progress: 'cast-image-progress',
        beforeUpload: function () {
            if (!scope.$$phase) {
                scope.$apply();
            }
        },
        afterUpload: function (response) {
            self.cast.cast_image = response.info;
            // self.ads.image = cast.info;
            self.cast.module = 'cast-image';
            self.cast.is_image_updated = 1;
            if (scope.errors.hasOwnProperty('cast_image'))
            delete scope.errors['cast_image'];
        }
    });

    this.resetCategoryImageUpload = function () {
        if (typeof window.CategoryImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.cast.image = '';
            self.cast.image_url = '';
        }
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        this.languages = data.info.language;
        this.castTranslation.language = data.info.language[0].id;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('cast/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();




    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addCategory = function ($event) {  
        scope.errors = {};
        self.cast = {};
        self.castFull = {};
        this.categoriesUniqueRoute = requestFactory.getUrl('categories/categories-unique');
        this.cast = {};
        this.castFull = {};
        this.cast.is_active = String(1);
        self.pref = 0;
        self.pref = '';
        this.showcategory = true;
        $("#categoriesForm").css('display', 'block');
        $("#categoriesTranslationForm").css('display', "none");
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.selectedRecords = [];
    }


    /**
     *  Function is used to edit the artists
     *  
     *  @param array records
     */
    this.editAd = function (records) {  
        $(".sidepanel").addClass("in"); 
        scope.errors = {};
        this.cast = {};
        this.cast.id = records.id;
        this.cast.external_url = records.external_url;
        this.cast.name = records.name;
        this.cast.cast_description = records.description;
        this.cast.is_active = String(records.is_active);
        var videos = []; 
        scope.singlePreload = [];
        angular.forEach(records.get_cast_videos, function(value, key) {
            var valueToPush = { };
            valueToPush.id = value.video_id;
            valueToPush.title = value.get_video_details.title;
            scope.singlePreload.push(valueToPush);
            videos.push(value.video_id);
        });
        this.cast.videos = videos;
        this.showcategory = true;
        $("#categoriesForm").css('display', 'block');
        $("#categoriesTranslationForm").css('display', "none");
        angular.element('#selectall').prop('checked', false);
        angular.element('.checkbox').prop('checked', false);
        this.selectedRecords = [];
    }
    /**
     *  Function is remove the uploaded image
     *  
     */
    this.removeThumbnailProperty = function () {
        this.cast.cast_image = '';
      };
    

    /**
     *  Function is used to save the category
     *  
     *  @param  $event, id
     */
    this.castSave = function ($event, id) {               

        if (baseValidator.validateAngularForm($event.target, scope)) {
            if (document.querySelector('select[data-ng-model="catgridCtrl.pref"]')) {
                this.cast.preference_order = (document.querySelector('select[data-ng-model="catgridCtrl.pref"]').value == '1') ? this.cast.preference_order : '';
            }
            this.cast.parent_id = (this.cast.is_web_series == 1) ? String(0) : String(this.cast.parent_id);
            if (id) {
                requestFactory.post(requestFactory.getUrl('cast/edit/' + id), this.cast, function (response) {
                
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closeCategoryEdit();
                }, this.fillError);
            } else {
               
                requestFactory.post(requestFactory.getUrl('cast/add'), this.cast, function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    this.closeCategoryEdit();
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
        scope.routeName = 'cast';
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
    
    this.cancelDelete = function () {
        scope.ConfirmationStatusBox = false;
        scope.deleteParams = '';
    };

    this.languageChange = function () {
        scope.errors = [];
        if (this.adsTranslation.language == this.languages[0].id) {
            $("#categoriesForm").css('display', 'block');
            $("#categoriesTranslationForm").css('display', "none");
        } else {
            self.castTranslation.title = '';
            self.castTranslation;
            self.cast_translation;
            angular.forEach(this.cast_translation, function (value) {
                if (value.language_id == self.castTranslation.language) {
                    self.castTranslation.languageCode = value.language_id;
                    self.castTranslation.title = value.title;
                }
            });

            $("#categoriesForm").css('display', 'none');
            $("#categoriesTranslationForm").css('display', 'block');
        }
    }

    this.castTranslateSave = function (event, id) {
        this.castTranslation.languageCode = this.castTranslation.language;
        requestFactory.post(requestFactory.getUrl('categories/addLanguage/' + id), this.castTranslation, function (response) {
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();

            var myIndex = scope.records.map(function (obj) {
                return obj.id;
            }).indexOf(id);
            var langIndex = scope.records[myIndex].category_translation.map(function (obj) {
                return obj.language_id;
            }).indexOf(parseInt(this.castTranslation.language));
            langIndex = (langIndex >= 0) ? langIndex : 0;

            scope.records[myIndex].category_translation[langIndex] = {
                language_id: this.castTranslation.language,
                title: this.castTranslation.title
            };
            this.closeCategoryEdit();

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
    this.confirmDeleteVideos = function (status) {
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
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('cast/action'), angular.extend({}, {
            selectedCheckbox: id,
            status: status
        }, scope.requestParams), function (data) {
            $('.accordion_wrapper_' + id).remove();
            requestFactory.setToaster('success', data.message);
            if ($('.not-saved').length <= 0) {
                $window.location = requestFactory.getTemplateUrl('admin/cast');
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('cast/bulk-update-status'), angular.extend({}, {
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
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('cast/bulk-update-status'), angular.extend({}, {
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
        this.isDeleteBulkRecord = true;
        scope.ConfirmationStatusBox = false;
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

        // Update categories in add/edit category form
       /* requestFactory.get(requestFactory.getUrl('categories/updated-details'), function (data) {
            this.allCategoriesHTML = $sce.trustAsHtml(data.allCategoriesHTML);
            $timeout(function () {
                $compile(angular.element(".categoryList").contents())(scope);
            }, 100);
        }, function () { });*/

        setTimeout(function () {
            $("#fixTable").tableHeadFixer({
                "head": false,
                "right": 1
            });
        }, 500);

    });
}];



window.gridControllers = {
    CastGridController: CastGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};

window.gridInitApp = angular.module('grid',['ng-selectize']);