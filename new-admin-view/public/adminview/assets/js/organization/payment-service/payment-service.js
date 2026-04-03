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
            requestFactory.get(requestFactory.getUrl('organization/payment-service/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ===========================================*******************************************======================================
        //                                                      sopen page code
        // ===========================================*******************************************======================================
        // this.openPage = function () {
        //     const newurl = `/admin/setting/payment-services/add`;
        //     window.location.href = newurl;
        // }

        this.openEditPage = function (record, id) {
            const newurl = `${appUrl}admin/setting/payment-services/edit/` + id;
            window.location.href = newurl;
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        // this.save = function ($event) {
        //     const payload = {
        //         payment_provider: this.services.payment_provider,
        //         provider_data: {}
        //     };

        //     if (this.services && this.services.payment_provider) {
        //         payload.provider_data = this.services;
        //     }

        //     requestFactory.post(
        //         requestFactory.getUrl('payment-service/create'),
        //         payload,
        //         function (response) {
        //             requestFactory.setToaster('success', response.message);
        //             requestFactory.getToaster();
        //             setTimeout(() => {
        //                 window.location = '/admin/setting/payment-services';
        //             }, 1000);

        //         },
        //         this.fillErrors
        //     );
        // };

        scope.togglePublishNow = function (record, id) {
            const toggleID = id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/toggle/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.setToaster('success', 'Publish status updated');
                    requestFactory.getToaster();
                    $timeout(function () {
                        location.reload();
                    }, 650);
                }
            );
        }

        scope.default = function (record, id, status) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const queryId = urlParams.get('id');

            const defaultId = status == 1 ? 1 : 0;
            console.log(status);


            const payload = {
                organization_id: queryId,
                payment_service_id: id,
                default: defaultId
            };

            console.log("payload", payload);

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/default'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', 'Publish status updated');
                    requestFactory.getToaster();
                    $timeout(function () {
                        location.reload();
                    }, 100);
                }
            );
        }

        scope.sysdft = function (record, id, status) {
            const converterId = status == 1 ? 1 : 0;

            const payload = {
                id: id,
                default: converterId
            };

            console.log("payload", payload);

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/sysdft'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', 'Record Updated Successfully.');
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                }
            );
        }
        // ===========================================*******************************************======================================
        //                                                      fetch data code
        // ===========================================*******************************************======================================



        // ===========================================*******************************************======================================
        //                                                      etc data code
        // ===========================================*******************************************======================================
        scope.$on('afterGetRecords', function (e, data) {
            if (!scope.searchRecords) {
                scope.searchRecords = {};
            }
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

    }
];


window.gridControllers = {
    PaymentServiceController: PaymentServiceController
};