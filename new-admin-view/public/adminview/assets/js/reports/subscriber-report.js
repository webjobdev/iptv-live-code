var SubscriberReportController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        scope.errors = {};
        scope.subre = {};
        scope.getRecords = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber-reports/info'),
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

        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            scope.errors = {};
            console.log(scope.subre);

            scope.subre.generate = scope.subre.generate = 0;

            requestFactory.post(
                requestFactory.getUrl("subscriber-reports/create"),
                scope.subre,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 650);

                }, this.fillError
            );
        }

        this.reportGenret = function ($event) {
            scope.errors = {};
            console.log(scope.subre);

            scope.subre.generate = scope.subre.generate = 1;

            requestFactory.post(
                requestFactory.getUrl("subscriber-reports/generate"),
                scope.subre,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);

                    setTimeout(() => {
                        window.location.reload();
                    }, 650);

                }, this.fillError
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
                requestFactory.getUrl('subscriber-reports/generate-report/' + id),
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

        this.sendpdf = function (record, id) {
            // const org = record.organization.id;
            // console.log(org);

            // requestFactory.post(
            //     requestFactory.getUrl('subscriber-reports/generate-report/pdf/' + id),
            //     {},
            //     function (response) {
            //         requestFactory.getToaster();
            //         requestFactory.setToaster('success', response.message);
            //         // $timeout(function () {
            //         //     location.reload();
            //         // }, 100);
            //     }, this.fillErrors
            // );
            if (!id) {
                console.error("Record is undefined or null");
                return;
            }

            const pdfurl = `${appUrl}admin/subscriber-reports/generate-report/pdf/${id}`;
            window.open(pdfurl);
        }

        this.exportcsv = function (record, id) {
            if (!id) {
                console.error("Record is undefined or null");
                return
            }
            const csvUrl = `${appUrl}admin/subscriber-reports/generate-report/csv/${id}`;
            // console.log(csvUrl);
            window.open(csvUrl);
        }

        this.exporttable = function (record, id) {
            if (!id) {
                console.error("Record is undefined or null");
                return
            }
            const csvUrl = `${appUrl}admin/subscriber-reports/generate-report/table/${id}`;
            // console.log(csvUrl);
            window.open(csvUrl);
        }

        // ==========***********==========
        // ==========***********==========

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

    }
];

window.gridControllers = {
    SubscriberReportController: SubscriberReportController
};
