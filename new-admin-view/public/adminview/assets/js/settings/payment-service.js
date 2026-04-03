var PaymentServiceController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.services = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.closeDeviceEdit = function () {
            scope.gridSideFormClose();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('payment-service/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ===========================================*******************************************======================================
        //                                                      open page code
        // ===========================================*******************************************======================================
        this.openPage = function () {
            const newurl = `${appUrl}admin/setting/payment-services/add`;
            window.location.href = newurl;
        }

        this.openEditPage = function (record, id) {
            console.log(window.location.href);

            const newurl = `${appUrl}admin/setting/payment-services/edit/` + id;
            window.location.href = newurl;
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        this.save = function ($event) {
            const payload = {
                payment_provider: this.services.payment_provider,
                provider_data: {}
            };
            console.log(payload);

            if (this.services && this.services.payment_provider) {
                payload.provider_data = this.services;
            }

            requestFactory.post(
                requestFactory.getUrl('payment-service/create'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    // setTimeout(() => {
                    //     window.location = `${appUrl}admin/setting/payment-services`;
                    // }, 550);

                },
                this.fillErrors
            );
        };

        scope.togglePublishNow = function (record, id) {
            const toggleID = id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            requestFactory.post(
                requestFactory.getUrl('payment-service/toggle/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 650);
                }
            );
        }

        scope.default = function (record, id) {
            const toggleID = id;

            record.default = record.default == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                default: record.default
            };

            requestFactory.post(
                requestFactory.getUrl('payment-service/default/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 650);
                }
            );
        }

        this.updatedata = function ($event, id) {
            const path = window.location.pathname;
            const editid = path.split("/").pop();

            const payload = {
                payment_provider: this.services.payment_provider,
                provider_data: {}
            };

            if (this.services && this.services.payment_provider) {
                payload.provider_data = this.services;
            }

            requestFactory.post(
                requestFactory.getUrl('payment-service/edit/' + editid),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location = `${appUrl}admin/setting/payment-services`;
                    }, 1000);

                },
                this.fillErrors
            );
        }

        // ===========================================*******************************************======================================
        //                                                      fetch data code
        // ===========================================*******************************************======================================
        this.fetchServices = function () {
            requestFactory.post(
                requestFactory.getUrl('payment-service/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        renderData(response.data.data);
                    }
                }
            );
        }

        function renderData(service) {
            const homeElement = document.getElementById('paymentserviceForm');
            if (!homeElement) {
                console.warn("⚠️ 'paymentserviceForm' element not found.");
                return;
            }

            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'paymentserviceForm' element.");
                return;
            }

            const targetOrgId = document.getElementById("service_id")?.value;

            const src = service.find(c => String(c.id) === String(targetOrgId));
            // console.log(src);

            if (src && src.provider_data) {
                scope.pytsveCtrl.services = src.provider_data;
                setTimeout(() => {
                    $('.select2_custom_ddl').select2();
                }, 100);
                scope.$applyAsync();
            } else {
                console.error("data not found or provider_data missing");
                scope.pytsveCtrl.services = {};
                scope.$applyAsync();
            }
        }
        this.fetchServices();

        // ===========================================*******************************************======================================
        //                                                      etc data code
        // ===========================================*******************************************======================================
        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        // ===========================================*******************************************======================================
        //                                                      fetch currency code
        // ===========================================*******************************************======================================
        this.fetchCurrency = function () {
            requestFactory.post(
                requestFactory.getUrl('payment-service/currency/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.CurrencyList = response.data.data;
                    }
                }
            );
        }
        this.fetchCurrency();

    }
];


window.gridControllers = {
    PaymentServiceController: PaymentServiceController
};