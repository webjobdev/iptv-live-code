var AccessoriesController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.accessories = {};
        scope.errors = {};
        requestFactory.getToaster();
        scope.searchRecords = {};
        requestFactory.setThisArgument(this);

        this.defineProperties = function () {
            this.info = DataTransfer.info;
            requestFactory.getToaster();
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organization/monetization-plan/accessories/info'), this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                },
            );
        }
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

        // ==============================***********************************==============================
        // create code 
        // ==============================***********************************==============================

        this.save = function ($event, id) {
            scope.errors = {};
            const accessoriesId = id;
            console.log("send data:", this.accessories);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgid = urlParams.get('id');

            this.accessories.organization_id = orgid;

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('organization/monitization-plan/accessories/edit/' + accessoriesId), this.accessories,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('organization/monetization-plan/accessories/create'), this.accessories,
                    (response) => {
                        scope.getRecords(true);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            }
        }

        scope.togglePublishNow = function (record, id) {
            const toggleId = id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };
            // console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('monitization-plan/accessories/toggle-edit/' + toggleId),
                payload,
                (response) => {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }, this.fillError
            );
        }

        // ==============================***********************************==============================
        // fetch details code
        // ==============================***********************************==============================

        this.fetchaccessories = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetization-plan/accessories/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && response.data.data) {
                        this.setAssignAccessories(response.data.data);
                    }
                }
            );
        }

        this.setAssignAccessories = function (acs) {
            // try {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgid = urlParams.get('id');

            if (!orgid) {
                console.warn("Organization ID not found in URL.");
                rootScope.records = [];
                return;
            }

            // console.log("All devices:", acs);

            const filterData = acs.filter(org => Number(org.organization_id) === Number(orgid));

            // console.log("Filtered devices for subscriber ID " + orgid + ":", filterData);
            // console.log("Filtered devices count:", filterData.length);

            rootScope.records = filterData;
            rootScope.orgid = orgid;
            // console.log("rootScope.records after filter:", rootScope.records);
            // } catch (error) {
            //     console.error("Error in handlePaymentHistory:", error);
            // }
        }

        this.fetchaccessories();

        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.addData = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.accessories = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#monetization-planForm").css('display', 'block');
            // $("#monetization-planFormTranslationForm").css('display', "none");
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.accessories.id = records.id;
            this.accessories.accessories = records.accessories;
            this.accessories.accessories_type = records.accessories_type;
            this.accessories.identifier = records.identifier;
            this.accessories.identifier_auto = (records.identifier_auto) ? true : false;
            this.accessories.currency = records.currency;
            this.accessories.price = records.price;
            this.accessories.description = records.description;
            this.accessories.is_active = (records.is_active) ? true : false;

            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);

            $("#monetization-planForm").css('display', 'block');
        }

        this.accessories = {
            identifier: '',
            identifier_auto: false
        };

        this.toggleAutoIdentifier = function () {
            if (this.accessories.identifier_auto) {
                var randomStr = Math.random().toString(36).substring(2, 10) +
                    Math.floor(Math.random() * 10000);
                this.accessories.identifier = randomStr;
            } else {
                this.accessories.identifier = '';
            }
        };

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        this.orgWiseAccessories = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/monetization-plan/accessories/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const Accessories = response.data.data;

                        const filterOrg = Accessories.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.Accessoriesrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseAccessories();

    }
];

window.gridControllers = {
    AccessoriesController: AccessoriesController
};