var CpsReportController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        scope.errors = {};
        scope.cps = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('cps-reports/info'),
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
            console.log(scope.cps);

            scope.cps.generate = scope.cps.generate = 1;

            requestFactory.post(
                requestFactory.getUrl("cps-reports/create"),
                scope.cps,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 650);

                }, this.fillErrors
            );
        }

        // =============*************=============
        // =============*************=============

        this.fetchorg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const list = response.data.data;
                        this.orglist = list;
                    }
                }
            );
        }
        this.fetchorg();

        // =============*************=============
        // =============*************=============

        this.exportCsv = function (record, id) {
            const cpsurl = `${appUrl}admin/cps/generate-report/csv/${id}`;
            // console.log(cpsurl);
            window.open(cpsurl);
        }

        this.exportOds = function (record, id) {
            const odsurl = `${appUrl}admin/cps/generate-report/ods/${id}`;
            // console.log(odsurl);
            window.open(odsurl);
        }

        scope.openModel = function (record) {
            // Make sure record has an ID
            if (!record || !record.id) {
                console.error("No record ID found");
                return;
            }

            // Build request data (pass ID to backend)
            var requestData = {
                id: record.id
            };

            // Call backend API
            requestFactory.post(
                requestFactory.getUrl('cps-reports/chart-data/records'),
                requestData,
                function (response) {
                    if (response && response.data && response.data.cps && response.data.cps.chartData) {
                        var chartData = response.data.cps.chartData;

                        // Log for debugging
                        console.log("Chart data for record ID " + record.id, chartData);

                        // Update modal title
                        $('#chartModalLabel').text('Report Chart');

                        // Show the Bootstrap modal
                        $('#chartModal').modal('show');

                        // Wait for modal to be fully displayed
                        $('#chartModal').on('shown.bs.modal', function () {
                            var ctx = document.getElementById('barChart').getContext('2d');

                            // Destroy previous chart instance if exists
                            if (window.barChartInstance) {
                                window.barChartInstance.destroy();
                            }

                            // Create new chart
                            window.barChartInstance = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: chartData.labels || [],
                                    datasets: [{
                                        label: 'Subscribers',
                                        data: chartData.data || [],
                                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true,
                                                stepSize: 1
                                            }
                                        }]
                                    }
                                }
                            });

                            // Unbind modal event (so it doesn’t fire multiple times)
                            $(this).off('shown.bs.modal');
                        });
                    } else {
                        console.warn("No chart data found for record ID:", record.id);
                        alert("No chart data found for this record.");
                    }
                },
                function (error) {
                    console.error("Error fetching chart data:", error);
                    alert("Failed to load chart data.");
                }
            );
        };

        // =============*************=============
        // =============*************=============

        const today = new Date();

        // yesterday (cannot select today)
        const maxDate = new Date(today);
        maxDate.setDate(today.getDate() - 1);

        // 365 days before yesterday
        const minDate = new Date(maxDate);
        minDate.setDate(maxDate.getDate() - 365);

        // Convert to yyyy-MM-dd format (for <input type="date">)
        const formatDate = date => date.toISOString().split('T')[0];

        scope.cps = scope.cps || {};
        scope.cps.maxDate = formatDate(maxDate);
        scope.cps.minDate = formatDate(minDate);
    }
];

window.gridControllers = {
    CpsReportController: CpsReportController
};
