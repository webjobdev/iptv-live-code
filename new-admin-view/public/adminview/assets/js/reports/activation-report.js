var ActivationReportController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        scope.errors = {};
        scope.act = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('activation-reports/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fillErrors = (response) => {
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

        // =============*************=============
        // =============*************=============

        this.save = function ($event) {
            scope.errors = {};
            console.log(scope.act);

            scope.act.generate = scope.act.generate = 0;

            requestFactory.post(
                requestFactory.getUrl("activation-reports/create"),
                scope.act,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 650);

                }, this.fillErrors
            );
        }

        this.generate = function ($event) {
            scope.errors = {};
            console.log(scope.act);

            scope.act.generate = scope.act.generate = 1;

            requestFactory.post(
                requestFactory.getUrl("activation-reports/generate"),
                scope.act,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 650);

                }, this.fillErrors
            );
        }

        scope.generateButton = function (record, id) {
            // const id = id;

            record.generate = record.generate == 1 ? 0 : 1;

            const paylod = {
                id: record.id,
                generate: record.generate
            };
            console.log(paylod);

            requestFactory.post(
                requestFactory.getUrl('activation-reports/generate-report/' + id),
                paylod,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 100);
                }, this.fillErrors
            );
        }

        // =============*************=============
        // =============*************=============

        this.organization = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const list = response.data.data;
                        this.orglist = list;
                        // console.log("org data fetch:", this.orglist);
                    }
                }
            );
        }
        this.organization();

        // =============*************=============

        this.fethsubscriber = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const list = response.data.data;
                        this.userlist = list;
                        // console.log("userlist data fetch:", this.userlist);
                    }
                }
            );
        }
        this.fethsubscriber();

        // =============*************=============

        this.fetchsetting = function () {
            requestFactory.post(
                requestFactory.getUrl('general/settings/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const list = response.data.data.filter(item => item.product_type === 'custom subscription');
                        this.planlist = list;
                        // console.log("planlist data fetch:", this.planlist);
                    }
                }
            );
        }
        this.fetchsetting();

        // =============*************=============
        // =============*************=============

        this.exportCsv = function (record, id) {
            const cpsurl = `${appUrl}admin/activation-report/csv/${id}`;
            window.open(cpsurl);
        }
    }
];

window.gridControllers = {
    ActivationReportController: ActivationReportController
};
