var TvCategoryGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval',
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
        // ==========***********==========
        // ==========***********==========

        this.defineProperties = function (data) {
            this.info = data.info;
            this.category = {};
            requestFactory.toggleLoader();
        };

        // console.log(scope.record);


        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('tvcategory/info'),
                this.defineProperties, function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        scope.getFormattedOrgNames = function (organizations) {
            if (!organizations || !Array.isArray(organizations)) return '';
            return organizations.map(function (org) { return org.organization_name; }).join(', ');
        };

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
        // ==========***********==========
        // ==========***********==========

        this.fetchOrganization = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.organizationList = response.data.data;
                    }
                }
            );
        }
        this.fetchOrganization();

        this.fetchChannel = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.ChannelList = response.data.data;
                    }
                }
            );
        }
        this.fetchChannel();

        // ==========***********==========
        // ==========***********==========

        this.addCategory = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
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
            this.category.tv_categorie_name = records.tv_categorie_name;
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

        // ==========***********==========
        // ==========***********==========

        this.tvcategorySave = function ($event, id) {
            $event.preventDefault();
            var TvCategoryId = id;

            const payload = {
                tv_categorie_name: this.category.tv_categorie_name || '',
                organization: this.category.organization || '',
            }

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('tvcategory/categories/' + TvCategoryId),
                    payload,
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
                    requestFactory.getUrl('tvcategory/add'),
                    payload,
                    function (response) {
                        // console.log(response);
                        $(".sidepanel").removeClass("in");
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

        scope.openCategoryModal = function (record) {
            scope.category = angular.copy(record);
            scope.categoryId = record.id;
            $('#categoryModel').modal('show');
            // console.log('click button id:', record.id);
        };

        this.editSubCategory = function (category) {
            this.category = angular.copy(category);
            this.CategoryEditId = category.id;
            $('#categoryModel').modal('show');
            this.category.category_order = Number(this.category.category_order);
            // console.log('click button id:', category.id);
        }
        // ==========***********==========
        // ==========***********==========

        this.Savecategory = function ($event, id) {
            $event.preventDefault();
            var categorieId = scope.categoryId;
            var categorieEditId = id;

            const payload = {
                categorie_id: categorieId,
                category_name: this.category.category_name,
                category_order: this.category.category_order,
            }

            // console.log('Sending Data:', payload);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('tv-category/edit/category/' + categorieEditId), this.category,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('tv-category/add/category'), payload,
                    function (response) {
                        requestFactory.setToaster('suucess', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            }
        }

        scope.openChannelModel = function (category) {
            scope.category = angular.copy(category);
            scope.categoryId = category.id;
            $('#ChannelModel').modal('show');
        }
        // ==========***********==========
        // ==========***********==========

        this.Savechannel = function ($event) {
            $event.preventDefault();
            var categoryId = scope.categoryId;

            const payload = {
                sub_category_id: categoryId,
                channel_id: this.category.channel_id
            };

            console.log('Sending Channel Data:', payload);

            requestFactory.post(
                requestFactory.getUrl('tv-category/add/channel'), payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        location.reload();
                    }, 650);
                }
            );
        }

        scope.deleteCategory = function (category) {
            if (!category.id) {
                console.error("category ID missing");
                return;
            }

            var categoryId = category.id;
            requestFactory.post(
                requestFactory.getUrl('tv-category/delete-category/' + categoryId), this.defineProperties,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        location.reload();
                    }, 650)
                }
            );
        }

        scope.deleteChannel = function (chnl) {
            if (!chnl.get_channel) {
                console.error("Channel ID missing");
                return;
            }
            var channelId = chnl.get_channel.id;
            requestFactory.post(
                requestFactory.getUrl('tv-category/delete-channel/' + channelId), this.defineProperties,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        location.reload();
                    }, 650)
                }
            );
        };
        // ==========***********==========
        // ==========***********==========


        scope.$watch('records', function (newVal) {
            if (newVal && Array.isArray(newVal) && !scope.allRecords) {
                scope.allRecords = angular.copy(newVal);
            }
        });

        scope.onCategorySearch = function (event) {
            console.log(event);
            var searchValue = (scope.searchCategory || '').trim().toLowerCase();
            if (!searchValue) {
                scope.records = angular.copy(scope.allRecords);
                return;
            }

            // if (event.which === 13) {
            scope.records = scope.allRecords.filter(function (record) {
                return record.tv_categorie_name &&
                    record.tv_categorie_name.toLowerCase().includes(searchValue);
            });
            // }

        }

        scope.hasAnyChannel = function (categories) {
            return categories.some(function (cat) {
                return cat.get_sub_category && cat.get_sub_category.length > 0;
            });
        };

    }
];

window.gridControllers = {
    TvCategoryGridController: TvCategoryGridController
};

window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};