var ApiAccessController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.apiAccessData = {};
        scope.searchText = [];
        scope.searchData = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('api-access/info'),
                this.defineProperties,
                function (response) {
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

        // to view add page
        this.addApiAccess = function () {
            this.apiaccess = {};
            window.location.href = 'api-access/add';
        }

        // to view edit page
        this.editApiAccess = function (id) {
            window.location.href = 'api-access/edit/' + id;
        }

        // call add api
        this.saveApiUser = function ($event, id) {
            requestFactory.post(requestFactory.getUrl('api-access/add'),
                this.apiAccessData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/api-access`;
                    }, 200);
                }, this.fillError
            );
        }

        // call update api
        this.updateApiUser = function ($event, id) {
            const rcrdId = document.getElementById('api-access-id').value;
            requestFactory.post(requestFactory.getUrl('api-access/edit/' + rcrdId),
                this.apiAccessData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/api-access`;
                    }, 200);
                }, this.fillError
            );
        }

        this.apiAccessData = {
            selectedPlans: []
        };

        scope.$watch('apiAccessCtrl.apiAccessData.organization', function (newOrg) {
            if (newOrg && Array.isArray(newOrg.mon_plan)) {
                self.apiAccessData.selectedPlans = angular.copy(newOrg.mon_plan);
            } else {
                self.apiAccessData.selectedPlans = [];
            }
        });

        this.addSubscription = function () {
            if (this.apiAccessData.subscription) {
                const alreadyExists = this.apiAccessData.selectedPlans.some(p => p.id === this.apiAccessData.subscription.id);
                if (!alreadyExists) {
                    this.apiAccessData.selectedPlans.push(angular.copy(this.apiAccessData.subscription));
                    requestFactory.setToaster('success', 'Subscription added successfully');
                } else {
                    requestFactory.setToaster('warning', 'Subscription already added');
                }
                requestFactory.getToaster();
                this.apiAccessData.subscription = null;
            } else {
                requestFactory.setToaster('error', 'Please select a subscription');
                requestFactory.getToaster();
            }
        };

        this.removeSubscription = function (index) {
            this.apiAccessData.selectedPlans.splice(index, 1);
            requestFactory.setToaster('success', 'Subscription removed');
            requestFactory.getToaster();
        };

        // get data from api_access table
        // get record for edit page
        this.getApiAccessData = (data) => {
            this.fullData = data; // Store for later matching if list loads late
            const targetRcrdId = document.getElementById("api-access-id")?.value;
            if (!targetRcrdId) return;

            const record = data.find(o => String(o.id) === String(targetRcrdId));

            if (record) {
                this.apiAccessData.name = record.name;
                this.apiAccessData.login = record.login;
                this.apiAccessData.token = record.token;
                this.apiAccessData.organization_id = record.organization_id;
                this.apiAccessData.subscription_id = record.subscription_id;
                this.apiAccessData.addon = record.add_on;
                
                // Active Subscriptions
                if (record.mon_plan && Array.isArray(record.mon_plan)) {
                    this.apiAccessData.selectedPlans = angular.copy(record.mon_plan);
                } else if (record.subscription) {
                     this.apiAccessData.selectedPlans = [record.subscription];
                }

                if (this.orgList && this.orgList.length > 0) {
                    this.apiAccessData.organization = this.orgList.find(org => org.id == record.organization_id);
                }
            }
        }

        scope.$watch('apiAccessCtrl.orgList', (newList) => {
            if (newList && newList.length > 0 && this.fullData) {
                this.getApiAccessData(this.fullData);
            }
        });

        this.fetchApiAccessData = function () {
            requestFactory.post(
                requestFactory.getUrl('api-access/records'),
                {},
                (response) => {
                    // Based on provided JSON: response.data.data is the array
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.getApiAccessData(response.data.data);
                    } else if (response && Array.isArray(response.data)) {
                        this.getApiAccessData(response.data);
                    }
                }
            );
        };
        this.fetchApiAccessData();

        // get organizations lists
        this.orgPage = 1;
        this.orgList = [];
        this.hasMoreOrg = true;
        this.isOrgLoading = false;

        this.fetchOrg = function (page = 1) {
            if (this.isOrgLoading || (page > 1 && !this.hasMoreOrg)) return;
            this.isOrgLoading = true;
            this.orgPage = page;
            requestFactory.post(requestFactory.getUrl('organizations-fetch/mon-plan?page=' + page), {}, (response) => {
                this.isOrgLoading = false;
                if (response && response.data && Array.isArray(response.data.data)) {
                    if (page === 1) {
                        this.orgList = response.data.data;
                    } else {
                        this.orgList = this.orgList.concat(response.data.data);
                    }
                    this.hasMoreOrg = !!response.data.next_page_url;
                }
            }, (error) => {
                this.isOrgLoading = false;
            });
        };

        this.loadMoreOrg = function () {
            if (this.hasMoreOrg && !this.isOrgLoading) {
                this.fetchOrg(this.orgPage + 1);
            }
        };

        this.fetchOrg();

        // get subscription data
        this.fetchSubscriptionData = function () {
            requestFactory.post(
                requestFactory.getUrl('subscriptions-plans/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.subscriptionList = response.data.data;
                        // console.log('Subscription Data : ', this.subscriptionList);
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };
        this.fetchSubscriptionData();

        //  update status
        this.toggleStatus = function (record) {
            record.status = record.status == 1 ? 0 : 1;
            const payload = {
                id: record.id,
                status: record.status
            };

            requestFactory.post(
                requestFactory.getUrl('api-access/status-update'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', response.error);
                    record.status = record.status == 1 ? 0 : 1;
                },
                $timeout(function () {
                    window.location.reload();
                }, 650)
            );
        };


        //remove api access
        this.removeApiAccess = function (id) {
            if (!id) {
                console.error("Record ID not found");
                return;
            }

            requestFactory.post(
                requestFactory.getUrl('api-access/remove/' + id),
                {},
                function (response) {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = '/admin/api-access';
                    }, 200);
                }
            )
        }

        // cancel api access
        scope.cancelApiAccess = function ($event) {
            // $event.preventDefault();
            window.location.href = `${appUrl}admin/api-access`;
        }


        // searching
        self.searchUser = function ($event) {
            var searchValue = document.getElementById('searchInput').value;
            const payload = {
                name: searchValue
            };

            if (searchValue) {
                requestFactory.post(
                    requestFactory.getUrl('api-access/search-record'),
                    payload,
                    function (response) {
                        if (response && response.data) {
                            getApiAccessData(response.data);
                        } else {
                            console.warn("Invalid data format from api access:", response);
                        }
                    }
                );
            }
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (!scope.searchRecords) {
                scope.searchRecords = {};
            }
            if (angular.isUndefined(scope.searchRecords.name)) {
                scope.searchRecords.name = '';
            }
        })
    }];


window.gridControllers = { ApiAccessController: ApiAccessController };

if (!window.gridDirectives) { window.gridDirectives = {}; }

window.gridDirectives.infiniteScrollSelect = ['$timeout', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            $timeout(function () {
                $(element).on('select2:open', function () {
                    $timeout(function () {
                        var container = $('.select2-results__options');
                        container.off('scroll').on('scroll', function () {
                            if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight - 20) {
                                scope.$apply(attrs.infiniteScrollSelect);
                            }
                        });
                    }, 500);
                });
            }, 1000);
        }
    };
}];
