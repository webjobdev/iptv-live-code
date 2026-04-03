var DashboardConfigurationController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.dashcon = {};

        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('dashboard-configuration/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fetchRecords = function () {
            requestFactory.post(requestFactory.getUrl('dashboard-configuration/records?rowsPerPage=10000000'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.ConfigurationList = response.data.data;
                        // this.settingList = this.ConfigurationList.filter(d => d.category == 'dashboard_configuration');
                        // console.log("dashboard data list:", this.settingList);
                        renderData(this.ConfigurationList);
                    }
                }
            );
        };

        function renderData(record) {
            const homeElement = document.getElementById('dashboardForm');
            if (!homeElement) {
                console.warn('dashboardForm element not found.');
                return;
            }

            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'dashboardForm' element.");
                return;
            }

            const dashboardConfigs = record.filter(
                (item) => item.category === 'dashboard_configuration'
            );

            const configData = {};
            dashboardConfigs.forEach((item) => {
                if (item.type === 'boolean') {
                    configData[item.key] = Number(item.value) === 1;
                }

                else if (item.type === 'json') {
                    try {
                        configData[item.key] = JSON.parse(item.value) || {};
                    } catch (e) {
                        console.warn(`Failed to parse JSON from item ${item.key}`, e);
                        configData[item.key] = {};
                    }
                }

                else if (item.type === 'radio') {
                    configData[item.key] = item.value;
                }

                else {
                    configData[item.key] = item.value;
                }
            });

            // console.log("✅ Filtered configData:", configData);

            if (scope && scope.dashconCtrl) {
                const updateModel = () => {
                    scope.dashconCtrl.dashcon = configData;
                    // console.log("✅ Updated Angular model:", scope.dashconCtrl.dashcon);
                };

                if (!scope.$$phase) {
                    scope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }
        this.fetchRecords();

        // save function
        this.save = function ($event) {
            // console.log(this.dashcon);
            requestFactory.post(
                requestFactory.getUrl('dashboard-configuration/edit'),
                this.dashcon,
                function (response) {
                    requestFactory.setToaster('suucess', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 650);
                }
            );
        }

        scope.toggleTransactionStatus = function () {
            // if (scope.dashconCtrl.dashcon.transactions_of_payment_service) {
            //     // If turned ON → select all
            //     scope.dashconCtrl.dashcon.based_on = 'by_type'; // default selection

            //     scope.dashconCtrl.dashcon.payment_system_type = {
            //         'Cash': true,
            //         'Authorize.net': true,
            //         'Check': true,
            //         'External Payment': true,
            //         'Gr4vy': true,
            //         '2C2P': true,
            //         'TrueMoney': true
            //     };
            // } else {
                // If turned OFF → deselect all
                scope.dashconCtrl.dashcon.based_on = null;

                scope.dashconCtrl.dashcon.payment_system_type = {
                    'Cash': false,
                    'Authorize.net': false,
                    'Check': false,
                    'External Payment': false,
                    'Gr4vy': false,
                    '2C2P': false,
                    'TrueMoney': false
                };
            // }
        };

    }
];


window.gridControllers = {
    DashboardConfigurationController: DashboardConfigurationController
};
