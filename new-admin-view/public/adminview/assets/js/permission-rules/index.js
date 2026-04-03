
var PermissionRuleController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var info = {};
        this.permissionRuleData = { modules: {} };

        this.orgModules = [
            { key: 'api-access', label: 'api_access', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'orgnaization-settings', label: 'orgnaization_settings', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'tenant-settings', label: 'tenant_settings', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'monetization-plan', label: 'monetization_plans', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'announcements', label: 'announcements', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'banner-featured-carousel', label: 'banner_featured_carousel', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'payment-services', label: 'payment_services', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'currencies', label: 'currencies', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'shopping-cart', label: 'shopping_cart', permissions: ['All', 'View', 'Edit', 'Hide'] }
        ];

        this.subsModules = [
            { key: 'search-results', label: 'search_results', permissions: ['All', 'View', 'Edit', 'Delete', 'Hide'] },
            { key: 'devices', label: 'devices', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'device-slots', label: 'devices_slots', permissions: ['All', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'activation', label: 'activation', permissions: ['All', 'View', 'Refund Payments', 'Create Payments', 'Cash Payments', 'Length Adjustments', 'Hide'] },
            { key: 'credit-cards', label: 'credit_cards', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'currencies', label: 'currencies', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'custom-streams', label: 'custom_streams', permissions: ['All', 'View', 'Edit', 'Hide'] },
            { key: 'notes', label: 'notes', permissions: ['All', 'View', 'Create', 'Edit', 'Hide'] }
        ];

        this.catgryModules = [
            { key: 'channel-categories', label: 'channel_categories', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'vod-categories', label: 'vod_categories', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'tv-show-categories', label: 'tv_show_categories', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.chanlSrvcModules = [
            { key: 'catchup-tv', label: 'catch_up_tv', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'live-rewind', label: 'live_rewind', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'epg-service', label: 'epg_service', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.settingModules = [
            { key: 'general-settings', label: 'general_settings', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'payment-services', label: 'payment_services', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'currencies', label: 'currencies', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'extensions', label: 'extensions', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'site-configurations', label: 'site_configurations', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.reportModules = [
            { key: 'subscriber-reports', label: 'subscriber_reports', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'cps-reports', label: 'cps_reports', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'activation-audit-reports', label: 'activation_audit_reports', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.drmServiceModules = [
            { key: 'drm-accounts', label: 'drm_accounts', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'drm-profiles', label: 'drm_profiles', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.indModules1 = [
            { key: 'channel', label: 'channel', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'live-event', label: 'live_event', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'video-on-demand', label: 'video_on_demand', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'tv-shows', label: 'tv_shows', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.indModules2 = [
            { key: 'info', label: 'info', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'api-access', label: 'api_access', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'system-users', label: 'system_users', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'permission-rules', label: 'permission_rules', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];

        this.indModules3 = [
            { key: 'geo-blocking', label: 'geo_blocking', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
            { key: 'stream-service', label: 'stream_service', permissions: ['All', 'View', 'Create', 'Edit', 'Delete', 'Hide'] },
        ];


        scope.searchText = [];
        scope.searchData = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        // Initialize module safely
        this.initModule = function (key, label) {
            if (!this.permissionRuleData.modules) {
                this.permissionRuleData.modules = {};
            }
            if (!this.permissionRuleData.modules[key]) {
                this.permissionRuleData.modules[key] = { permissions: {} };
            }
            this.permissionRuleData.modules[key].name = label;
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('permission-rules/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // to view add page
        this.addPermissionRule = function () {
            window.location.href = 'permission-rules/add';
        }

        // to view edit page
        this.editPermissionRule = function (id) {
            window.location.href = 'permission-rules/edit/' + id;
        }

        scope.getFormattedOrgNames = function (organizations) {
            if (!organizations || !Array.isArray(organizations)) return '';
            return organizations.map(function (org) { return org.organization_name; }).join(', ');
        };


        // call add permission rule
        this.savePermissionRule = function ($event) {
            console.log('Permission Rule Data (Add):', this.permissionRuleData);
            requestFactory.post(requestFactory.getUrl('permission-rules/add'),
                this.permissionRuleData, function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/permission-rules`;
                    }, 200);
                }, this.fillError
            );
        }

        // call update permission rule
        this.updatePermissionRule = function ($event) {
            const recordId = document.getElementById('permission-rule-id')?.value;
            console.log('Permission Rule Data (Edit):', this.permissionRuleData);
            requestFactory.post(requestFactory.getUrl('permission-rules/edit/' + recordId),
                this.permissionRuleData, function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/permission-rules`;
                    }, 200);
                }, this.fillError
            );
        }

        // get allowed organizations lists
        this.fetchOrg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/records'),
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

        // check edit page is open
        scope.isEditMode = window.location.href.includes('/edit');

        // call edit permission rule
        this.fetchRulePermissionData = function ($event, id) {
            requestFactory.post(
                requestFactory.getUrl('permission-rules/records'),
                this.permissionRuleData,
                function (response) {
                    if (response.data && response.data && Array.isArray(response.data.data)) {
                        getRulePermissionData(response.data.data);
                    } else {
                        console.warn("Invalid Data format from partner program :", response);
                    }
                }
            )
        }

        // get data for edit page
        function getRulePermissionData(data) {
            const editPgElmnt = document.getElementById('edit-form-div');
            if (!editPgElmnt) {
                console.warn("Edit page element not found");
                return;
            }

            const localScope = angular.element(editPgElmnt).scope();
            const targetRecordId = document.getElementById('permission-rule-id')?.value;
            if (!targetRecordId) {
                console.warn("Target record ID not found");
                return;
            }

            const record = data.find(item => item.id == targetRecordId);
            if (record) {
                if (localScope && localScope.permsnRuleCtrl) {
                    const updateModel = () => {
                        localScope.permsnRuleCtrl.permissionRuleData = {
                            rule_name: record.rule_name || '',
                            organization_id: (record.organization && Array.isArray(record.organization)) ? record.organization.map(item => item.id) : [],
                            organization: (record.organization && Array.isArray(record.organization)) ? record.organization.map(item => item.organization_name) : [],
                            modules: transformModules(record),
                        };
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    }
                    else {
                        updateModel();
                    }
                }

            } else {
                console.warn('No Permission Rules found with ID:', targetRecordId);
            }
        }
        this.fetchRulePermissionData();

        // format modules for edit record page
        function transformModules(rule) {
            const modules = {};

            // Helper to init module
            const init = (key, name) => {
                modules[key] = { name: name, permissions: {} };
            };

            // Initialize all known modules
            init('dashboard', 'Dashboard');
            init('organization', 'Organization');
            if (self.orgModules) self.orgModules.forEach(m => init(m.key, m.label));

            init('subscribers', 'Subscribers');
            if (self.subsModules) self.subsModules.forEach(m => init(m.key, m.label));

            init('categories', 'Categories');
            if (self.catgryModules) self.catgryModules.forEach(m => init(m.key, m.label));

            init('channel-services', 'Channel Service');
            if (self.chanlSrvcModules) self.chanlSrvcModules.forEach(m => init(m.key, m.label));

            init('settings', 'Settings');
            if (self.settingModules) self.settingModules.forEach(m => init(m.key, m.label));

            init('reports', 'Reports');
            if (self.reportModules) self.reportModules.forEach(m => init(m.key, m.label));

            init('drm-services', 'DRM Service');
            if (self.drmServiceModules) self.drmServiceModules.forEach(m => init(m.key, m.label));

            if (self.indModules1) self.indModules1.forEach(m => init(m.key, m.label));
            if (self.indModules2) self.indModules2.forEach(m => init(m.key, m.label));
            if (self.indModules3) self.indModules3.forEach(m => init(m.key, m.label));

            const keyMappings = {
                'monetization_plans': 'monetization-plan',
                'devices_slots': 'device-slots',
                'catch_up_tv': 'catchup-tv'
            };

            if (rule && rule.permissions) {
                rule.permissions.forEach(p => {
                    let rawKey = p.permission_module_name;
                    let key = rawKey.toLowerCase().replace(/_/g, '-').replace(/\s+/g, '-');

                    if (keyMappings[rawKey]) {
                        key = keyMappings[rawKey];
                    }

                    // If for some reason the key wasn't in our list (custom or new), init it now
                    if (!modules[key]) {
                        modules[key] = { name: p.permission_module_name, permissions: {} };
                    }

                    modules[key].permissions = {
                        View: p.view == 1,
                        Create: p.create == 1,
                        Edit: p.edit == 1,
                        Delete: p.delete == 1,
                        Hide: p.hide == 1,
                        CashPayments: p.cash_payment == 1,
                        RefundPayments: p.refund_payment == 1,
                        LengthAdjustments: p.length_adjustment == 1,
                        SecuritySearch: p.security_search == 1,
                        All: false
                    };
                });
            }

            return modules;
        }

        this.searchUserRecords = function () {
            const getSearchalue = document.getElementById('created-by-search').value;

            const payload = {
                name: getSearchalue,
            }

            requestFactory.post(
                requestFactory.getUrl('permission-rules/search-record'),
                payload,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // console.log("Search Records : ", response);

                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        }

        // delete Record
        this.deletePermissionRule = function ($event) {
            // console.log('This is Test!');
            const recordId = document.getElementById('permission-rule-id')?.value;
            // console.log('This is Record ID : ', recordId);
            requestFactory.post(requestFactory.getUrl('permission-rules/destroy/' + recordId),
                this.permissionRuleData, function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/permission-rules`;
                    }, 200);
                }, this.fillError
            );
        }

        // cancel Record
        this.cancelPermissionRule = function ($event) {
            window.location.href = `${appUrl}admin/permission-rules`;
        }

        // Select All Permissions
        this.selectAllPermissions = function () {
            const isChecked = this.selectAll; // bound to ng-model

            if (!this.permissionRuleData.modules) {
                this.permissionRuleData.modules = {};
            }

            const setPermissions = (key, permissionsList) => {
                if (!this.permissionRuleData.modules[key]) {
                    this.permissionRuleData.modules[key] = { permissions: {} };
                }
                if (!this.permissionRuleData.modules[key].permissions) {
                    this.permissionRuleData.modules[key].permissions = {};
                }

                permissionsList.forEach(perm => {
                    if (perm === 'Hide') {
                        // If selecting all, uncheck Hide. If unselecting all, uncheck Hide (or leave as is? Let's uncheck to clear).
                        if (isChecked) {
                            this.permissionRuleData.modules[key].permissions['Hide'] = false;
                        }
                    } else if (perm !== 'All') {
                        this.permissionRuleData.modules[key].permissions[perm] = isChecked;
                    }
                });
                // Also set 'All' itself if exists in list (it does for modules)
                if (permissionsList.includes('All')) {
                    this.permissionRuleData.modules[key].permissions['All'] = isChecked;
                }
            };

            // Dashboard (Manual)
            setPermissions('dashboard', ['View', 'Edit', 'Hide']);

            // All other groupings
            const allGroups = [
                this.orgModules,
                this.subsModules,
                this.catgryModules,
                this.chanlSrvcModules,
                this.settingModules,
                this.reportModules,
                this.drmServiceModules,
                this.indModules1,
                this.indModules2,
                this.indModules3
            ];

            allGroups.forEach(group => {
                if (Array.isArray(group)) {
                    group.forEach(mod => {
                        setPermissions(mod.key, mod.permissions);
                    });
                }
            });
        };

        // Toggle permissions for a specific module
        this.toggleModulePermissions = function (moduleKey, permissionName) {
            if (!this.permissionRuleData.modules[moduleKey]) {
                this.permissionRuleData.modules[moduleKey] = { permissions: {} };
            }
            if (!this.permissionRuleData.modules[moduleKey].permissions) {
                this.permissionRuleData.modules[moduleKey].permissions = {};
            }

            if (permissionName === 'All') {
                const isChecked = this.permissionRuleData.modules[moduleKey].permissions['All'];

                // Find module definition to get list of permissions
                let moduleDef = null;
                const allGroups = [
                    this.orgModules,
                    this.subsModules,
                    this.catgryModules,
                    this.chanlSrvcModules,
                    this.settingModules,
                    this.reportModules,
                    this.drmServiceModules,
                    this.indModules1,
                    this.indModules2,
                    this.indModules3
                ];

                for (let group of allGroups) {
                    const found = group.find(m => m.key === moduleKey);
                    if (found) {
                        moduleDef = found;
                        break;
                    }
                }

                if (moduleKey === 'dashboard') {
                    moduleDef = { permissions: ['View', 'Edit', 'Hide'] };
                }

                if (moduleKey === 'organization') {
                    moduleDef = { permissions: ['View', 'Create', 'Edit', 'Delete', 'Hide'] };
                }

                if (moduleKey === 'subscribers') {
                    moduleDef = { permissions: ['View', 'Security Search', 'Create', 'Edit', 'Delete', 'Hide'] };
                }

                if (moduleKey === 'categories') {
                    moduleDef = { permissions: ['View', 'Hide'] };
                }

                if (['channel-services', 'settings', 'reports', 'drm-services'].includes(moduleKey)) {
                    moduleDef = { permissions: ['View', 'Hide'] };
                }

                if (moduleDef) {
                    moduleDef.permissions.forEach(p => {
                        if (p !== 'All') {
                            if (p === 'Hide') {
                                this.permissionRuleData.modules[moduleKey].permissions[p] = false;
                            } else {
                                this.permissionRuleData.modules[moduleKey].permissions[p] = isChecked;
                            }
                        }
                    });
                }
            }
        };



        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.rule_name)) {
                scope.searchRecords.rule_name = '';
            }
        })
    }];


window.gridControllers = { PermissionRuleController: PermissionRuleController };


