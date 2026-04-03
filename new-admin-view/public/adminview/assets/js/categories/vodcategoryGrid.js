'use strict';

var VODCategoryGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval',
    function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
        var self = this;
        this.info = {};
        this.category = {};
        this.showcategory = true;
        scope.selectedRecords = [];
        scope.errors = {};
        this.languages = {};
        this.categoryTranslation = {};
        scope.translationError = false;
        requestFactory.setThisArgument(this);
        requestFactory.toggleLoader();
        requestFactory.getToaster();

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('tvcategory/info'),
                this.defineProperties, function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fillError = (response) => {
            if (response && response.status === 422 && response.data.errors) {
                angular.forEach(response.data.errors, function (messages, field) {
                    if (Array.isArray(messages) && messages.length > 0) {
                        scope.errors[field] = {
                            has: true,
                            message: messages[0]
                        };
                    }
                });
            } else if (response && response.data && response.data.message) {
                requestFactory.setToaster('error', response.data.message);
                requestFactory.getToaster();
            } else {
                requestFactory.setToaster('error', 'Something went wrong.');
                requestFactory.getToaster();
            }

            scope.$applyAsync();
        };
        // ==================================================***************************************************==========================================
        // send data code
        // ==================================================***************************************************==========================================

        this.categorySave = function ($event, id) {
            $event.preventDefault();
            var vodid = id;

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('vod-category/edit/categorie/' + vodid),
                    this.category,
                    // console.log("✅ API Response:", this.category),
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(function () {
                            location.reload();
                        }, 650);
                    },
                    this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('vod-category/add'),
                    this.category,
                    // console.log("✅ API Response:", this.category),
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(function () {
                            location.reload();
                        }, 650);
                    },
                    this.fillError
                );
            }
        }

        this.Savecategory = function ($event, id) {
            $event.preventDefault();
            var categorieId = scope.categoryId;
            var categorieEditId = id;

            const payload = {
                categorie_id: categorieId,
                category_name: this.category.category_name,
                category_order: this.category.category_order,
            }

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('vod-category/edit/categories/' + categorieEditId), this.category,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(function () {
                            location.reload();
                        }, 650);
                    },
                    this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('vod-category/add/categorie'), payload,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(function () {
                            location.reload();
                        }, 650);
                    },
                    this.fillError
                );
            }
        }

        this.SaveSubctgry = function ($event) {
            $event.preventDefault();
            var categoryId = scope.categoryId;
            const payload = {
                sub_category_id: categoryId,
                category_name: this.category.category_name,
                category_order: this.category.category_order,
            };

            requestFactory.post(
                requestFactory.getUrl('vod-category/add/sub-categorie'), payload,
                function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        location.reload();
                    }, 650);
                }, this.fillError
            );
        }

        // ==================================================***************************************************==========================================
        // fetch data code
        // ==================================================***************************************************==========================================

        this.fetchOrganization = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.orgList = response.data.data;
                    }
                }
            );
        }
        this.fetchOrganization();

        // ==================================================***************************************************==========================================
        // open model code & etc
        // ==================================================***************************************************==========================================

        scope.getFormattedOrgNames = function (organizations) {
            if (!organizations || !Array.isArray(organizations)) return '';
            return organizations.map(function (org) { return org.organization_name; }).join(', ');
        };

        this.addCategory = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.category = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#categoriesForm").css('display', 'block');
            $("#categoriesTranslationForm").css('display', "none");
        }

        this.editCategory = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.category.id = records.id;
            this.category.vod_categorie_name = records.vod_categorie_name;
            if (records.get_organization && Array.isArray(records.get_organization)) {
                this.category.organization = records.get_organization.map(function (org) {
                    return org.id;
                });
            } else if (Array.isArray(records.organization)) {
                this.category.organization = records.organization.map(function (org) {
                    return org.id;
                });
            } else {
                this.category.organization = records.organization;
            }
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 50);
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.category = {};
            });
        }

        scope.openCategoryModal = function (record) {
            scope.category = angular.copy(record);
            scope.categoryId = record.id;
            $('#categoryModel').modal('show');
            // console.log('click button id:', record.id);
        };

        scope.SubCategoryModel = function (category) {
            scope.category = angular.copy(category);
            scope.categoryId = category.id;
            $('#SubCategoryModel').modal('show');
        }

        this.editSubCategory = function (category) {
            this.category = angular.copy(category);
            this.CategoryEditId = category.id;
            this.category.category_order = Number(this.category.category_order);
            $('#categoryModel').modal('show');
            // console.log('click button id:', category.id);
        }

        scope.countSubCategories = function (record) {
            let count = 0;
            if (record.categories && record.categories.length > 0) {
                record.categories.forEach(function (category) {
                    if (category.get_sub_category && Array.isArray(category.get_sub_category)) {
                        count += category.get_sub_category.length;
                    }
                });
            }
            return count;
        };


        scope.$watch('records', function (newVal) {
            if (newVal && Array.isArray(newVal) && !scope.allRecords) {
                scope.allRecords = angular.copy(newVal);
                console.log(scope.allRecords);

            }
        });

        scope.onCategorySearch = function (event) {
            var searchValue = (scope.searchCategory || '').trim().toLowerCase();
            if (!searchValue) {
                scope.records = angular.copy(scope.allRecords);
                return;
            }

            // if (event.which === 13) {
            scope.records = scope.allRecords.filter(function (record) {
                return record.vod_categorie_name &&
                    record.vod_categorie_name.toLowerCase().includes(searchValue);
            });

            // }
        }
    }
];


window.gridControllers = {
    VODCategoryGridController: VODCategoryGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};