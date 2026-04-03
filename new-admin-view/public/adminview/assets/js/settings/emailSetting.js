var GenSettingController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var records = {};
        this.emailSettingData = {};
        this.multipleDeviceData = {};
        this.tenantData = {};

        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('general/settings/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fetchRecords = function () {
            requestFactory.post(requestFactory.getUrl('general-settings/get-records'),
                this.defineProperties,
                function (response) {
                    this.settingsList = response;
                    this.emailSettingList = this.settingsList.filter((w) => w.category == 'email_setting');
                    this.paymentSettingList = this.settingsList.filter((w) => w.category == 'payment_setting');
                    this.tenantSettingList = this.settingsList.filter((w) => w.category == 'multi_tenant_setting');
                }
            );
        };
        this.fetchRecords();

        fetch(`${appUrl}timezone.json`)
            .then(response => response.json())
            .then(data => {
                this.tzList = data;                
            })

        this.copyToClipboard = function (text) {
            const paswdInpt = document.getElementById('paswd');
            const toolTip = document.getElementById('tooltip');
            navigator.clipboard.writeText(paswdInpt.value)
                .then(() => {
                    toolTip.innerText = 'Copied';

                    setTimeout(() => {
                        toolTip.innerText = 'Copy';
                    }, 200);
                })
                .catch(err => console.error('Failed to copy : ', err));
        };

        // email setting save api
        this.saveEmailSetting = function (record) {
            requestFactory.post(
                requestFactory.getUrl('general-settings/save-setting'),
                record,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }
            )
        }

        // payment setting save api
        this.savePaymentSetting = function (record) {
            requestFactory.post(
                requestFactory.getUrl('general-settings/save-setting'),
                record,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }
            )
        }

        // tenant setting save api
        this.saveTenantSetting = function (record) {
            const tenantMode = document.getElementById("tenant-mode").checked;
            const guestMode = document.getElementById("guest-mode").checked;
            const inAppRegistration = document.getElementById("in-app-registration").checked;

            const payload = {
                ...record,
                multi_tenant_mode: tenantMode,
                guest_mode: guestMode,
                in_app_registration: inAppRegistration,
            };

            requestFactory.post(
                requestFactory.getUrl('general-settings/save-tenant-settings'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }
            )
        }

        // get data for tenant setting get data
        this.fetchTenantSettings = function (record) {
            requestFactory.post(
                requestFactory.getUrl('general-settings/get-records'),
                this.tenantData,
                function (response) {
                    if (response) {
                        getSettingsData(response);
                    } else {
                        console.warn("Invalid Data format from device list :", response);
                    }
                }
            )
        }

        function getSettingsData(data) {
            const editPgElmnt = document.getElementById('tenant');
            if (!editPgElmnt) {
                console.warn("Edit page element not found");
                return;
            }
            const localScope = angular.element(editPgElmnt).scope();
            const record = data.filter(item => item.category == 'multi_tenant_setting');
            if (record) {
                if (localScope && localScope.genSetngCtrl) {
                    const updateModel = () => {
                        let tenantData = {}; // temporary object

                        record.forEach(x => {
                            switch (x.key) {
                                case 'multi_tenant_mode':
                                    tenantData.multi_tenant_mode = x.value || '';
                                    break;
                                case 'guest_mode':
                                    tenantData.guest_mode = x.value || '';
                                    break;
                                case 'guest_organization':
                                    tenantData.guest_organization = parseInt(x.value) || '';
                                    break;
                                case 'guest_subscription':
                                    tenantData.guest_subscription = parseInt(x.value) || '';
                                    break;
                                case 'in_app_registration':
                                    tenantData.in_app_registration = x.value || '';
                                    break;
                                case 'default_organization':
                                    tenantData.default_organization = parseInt(x.value) || '';
                                    break;
                                case 'default_subscription':
                                    tenantData.default_subscription = parseInt(x.value) || '';
                                    break;
                                case 'code_expiration_time':
                                    tenantData.code_expiration_time = parseInt(x.value) || '';
                                    break;
                                case 'code_expiration_time_type':
                                    tenantData.code_expiration_time_type = x.value || '';
                                    break;
                            }
                        });

                        // finally assign
                        localScope.genSetngCtrl.tenantData = tenantData;
                        // console.log("Localscope : ", localScope.genSetngCtrl.tenantData);

                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    }
                    else {
                        updateModel();
                    }
                }
            }
        }
        this.fetchTenantSettings();

        // get organization detail
        this.fetchOrg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.orgList = response.data.data;
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };
        this.fetchOrg();

        // get subscription list
        this.fetchSubscriptions = function () {
            requestFactory.post(
                requestFactory.getUrl('subscriptions-plans/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.subsList = response.data.data;
                        // console.log('Subscribers List : ', this.subsList);

                    } else {
                        console.warn("Invalid data format from Subscription :", response);
                    }
                }
            );
        };
        this.fetchSubscriptions();

        // cancel settings
        this.cancelTenantSetting = function (event) {
            window.location.href = `${appUrl}admin/general/email-settings`;
        }


        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.serial_no)) {
                scope.searchRecords.serial_no = '';
            }
        })
    }];


window.gridControllers = { GenSettingController: GenSettingController };
