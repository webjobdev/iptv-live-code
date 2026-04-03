var SubscriberController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        scope.errors = {};
        this.org_sub = {};
        this.sub = { pin_code: '4321' };
        this.orgList = [];
        this.orgsub_page = {};
        this.searchRecords = {
            account_number: '',
            user_name: '',
            first_name: '',
            email: '',
            phone_number: ''
        };
        let searchTimeout = null;
        self.records = [];
        self.showRecords = false;
        self.noRecords = false;
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.addDrm = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.drm = {};
            $("#organizationForm").css('display', 'block');
            $("#organizationTranslationForm").css('display', "none");
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/info'),
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

        // ==================================================***************************************************==========================================
        // start fetch organization name

        this.fetchOrg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.orgList = response.data.data;
                        OrgdefineProperties(response.data.data);
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };

        function OrgdefineProperties(Org) {
            const homeElement = document.getElementById("home");
            if (!homeElement) return;

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("org-id")?.value;

            if (localScope && localScope.subCtrl) {
                const updateModel = () => {
                    // Populate dropdown list
                    localScope.subCtrl.orgList = Org;

                    // Preselect if ID present
                    const org = Org.find(o => String(o.id) === String(targetOrgId));

                    if (org) {
                        localScope.subCtrl.org_sub = {
                            id: org.id,
                            organization_name: org.organization_name,
                            provider_id: org.provider_id,
                            organization_prefix: org.prefix || '',
                        };
                    }
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }
        this.fetchOrg();

        // end fetch organization name

        // add side panel code start
        this.editSubscriber = function (id) {
            if (!id) {
                console.warn("Subscriber ID is missing!");
                return;
            }
            const url = `subscribers/detail/add?subscriber-id=${id}`;
            window.location.href = url;
        };

        this.creditcard = function (id) {
            if (!id) {
                console.warn("Subscriber ID is missing!");
                return;
            }
            const url = `subscriber/activation?subscriber-id=${id}`;
            window.location.href = url;
        }
        // add side panel code end

        this.cancel = function () {
            const url = `${appUrl}admin/subscribers`;
            window.location.href = url;
        }

        // subscriber panel code
        this.setOrgName = function (orgId) {
            const selectedOrg = this.orgList.find(function (org) {
                return org.id === orgId;
            });
            this.org_sub.organization_name = selectedOrg ? selectedOrg.organization_name : '';
            this.org_sub.organization_prefix = selectedOrg ? selectedOrg.prefix : '';
        };

        this.sidepanelsave = function ($event) {

            const userInput = document.getElementById('user_name');
            const accInput = document.getElementById('account_number');

            const payload = {
                user_name: userInput.value,
                first_name: this.org_sub.first_name || '',
                last_name: this.org_sub.last_name || '',
                organization_id: this.org_sub.id || '',
                organization_name: this.org_sub.organization_name || '',
                account_number: accInput.value,
                email: this.org_sub.email || '',
            }
            // console.log(this.org_sub);

            requestFactory.post(requestFactory.getUrl('subscribers/add'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    $(".sidepanel").removeClass("in");
                    setTimeout(function () {
                        location.reload();
                    }, 600);
                }, this.fillError
            );
        }

        this.fetchSub = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const orgData = response.data.data;
                        // console.log(response.data.data);
                        // OrgdefineProperties(orgData);
                        rendersubscriber(orgData);
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };

        function rendersubscriber(Sub) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("sub-id")?.value;

            if (!targetOrgId) {
                return;
            }

            const org = Sub.find(o => String(o.id) === String(targetOrgId));
            if (org) {
                // console.log("Fetch data.", org);
                if (localScope && localScope.subCtrl) {
                    const updateModel = () => {
                        localScope.subCtrl.sub = {
                            id: org.id,
                            organization_name: org.organization_name,
                            provider_id: org.fetch_organization?.provider_id,
                            organization_prefix: org.fetch_organization?.prefix || '',
                            account_number: org.account_number || '',
                            pin_code: org.pin_code || '4321',
                            user_name: org.user_name || '',
                            first_name: org.first_name || '',
                            last_name: org.last_name || '',
                            email: org.email || '',
                            phone_number_code: org.phone_number_code || '',
                            phone_number: org.phone_number || '',
                            address: org.address || '',
                            city: org.city || '',
                            zip_code: org.zip_code || '',
                            country: org.country || '',
                            state: org.state || '',
                            language: org.language || '',
                            date_of_birth: org.date_of_birth ? new Date(org.date_of_birth) : '',
                            timezone: org.timezone || '',
                        };
                    };
                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No organization found with ID: ${targetOrgId}`);
            }
        }
        this.fetchSub();

        this.editsave = function ($event) {
            $event.preventDefault();
            const orgIdInput = document.getElementById('sub-id');
            const userInput = document.getElementById('user_name');
            const accInput = document.getElementById('account_number');
            if (!orgIdInput) {
                // console.error("Required input elements not found.");
                return;
            }

            const payload = {
                id: orgIdInput.value,
                organization_name: this.sub.organization_name || '',
                account_number: accInput.value,
                pin_code: this.sub.pin_code || '',
                user_name: userInput.value,
                password: this.sub.password || '',
                first_name: this.sub.first_name || '',
                last_name: this.sub.last_name || '',
                email: this.sub.email || '',
                phone_number_code: this.sub.phone_number_code || '',
                phone_number: this.sub.phone_number || '',
                address: this.sub.address || '',
                city: this.sub.city || '',
                zip_code: this.sub.zip_code || '',
                country: this.sub.country || '',
                state: this.sub.state || '',
                language: this.sub.language || '',
                date_of_birth: this.sub.date_of_birth || '',
                timezone: this.sub.timezone || '',
            };

            requestFactory.post(requestFactory.getUrl('subscribers/add'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    $(".sidepanel").removeClass("in");
                    setTimeout(function () {
                        location.reload();
                    }, 600);
                }, this.fillError
            );
        }
        // subscriber panel code end

        // ==================================================***************************************************==========================================

        scope.$on('afterGetRecords', function (e, data) {
            if (scope.searchRecords && (typeof scope.searchRecords === 'object') && angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        // password hide and show start
        window.toggleAccessKey = function () {
            const accessinput = document.getElementById("accessKeyInput");
            const icon = document.getElementById("toggleaccessIcon");

            if (accessinput.type === "password") {
                accessinput.type = "text";
                icon.classList.remove("glyphicon-eye-open");
                icon.classList.add("glyphicon-eye-close");
            } else {
                accessinput.type = "password";
                icon.classList.remove("glyphicon-eye-close");
                icon.classList.add("glyphicon-eye-open");
            }
        }
        // password hide and show end

        // account number start
        window.generateAccountNumber = function () {
            const checkbox = document.getElementById('account_auto') || document.getElementById('account_number_auto');
            const homeElement = document.getElementById('home');
            const scope = homeElement ? angular.element(homeElement).scope() : null;
            const subCtrl = scope ? scope.subCtrl : null;

            if (checkbox && checkbox.checked && subCtrl) {
                const prefix = subCtrl.org_sub.organization_prefix || subCtrl.sub?.organization_prefix || '';
                const part1 = Math.floor(100 + Math.random() * 900);
                const part2 = Math.floor(100 + Math.random() * 900);
                const token = prefix ? `${prefix}-${part1}-${part2}` : `${part1}-${part2}`;

                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.account_number = token;
                    if (subCtrl.sub) subCtrl.sub.account_number = token;
                });
            } else if (subCtrl) {
                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.account_number = '';
                    if (subCtrl.sub) subCtrl.sub.account_number = '';
                });
            }
        };
        // account number end

        // pin code stat
        window.generatePinCode = function () {
            const checkbox = document.getElementById('pin_code_auto');
            const homeElement = document.getElementById('home');
            const scope = homeElement ? angular.element(homeElement).scope() : null;
            const subCtrl = scope ? scope.subCtrl : null;

            if (checkbox && checkbox.checked && subCtrl) {
                const token = '4321';
                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.pin_code = token;
                    if (subCtrl.sub) subCtrl.sub.pin_code = token;
                });
            } else if (subCtrl) {
                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.pin_code = '';
                    if (subCtrl.sub) subCtrl.sub.pin_code = '';
                });
            }
        };
        // pin code end

        // user name start
        window.generateUsername = function () {
            const checkbox = document.getElementById('username_auto');
            const homeElement = document.getElementById('home');
            const scope = homeElement ? angular.element(homeElement).scope() : null;
            const subCtrl = scope ? scope.subCtrl : null;

            if (checkbox && checkbox.checked && subCtrl) {
                const token = Array.from({ length: 4 }, () => Math.floor(Math.random() * 10)).join('');
                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.user_name = token;
                    if (subCtrl.sub) subCtrl.sub.user_name = token;
                });
            } else if (subCtrl) {
                scope.$applyAsync(() => {
                    if (subCtrl.org_sub) subCtrl.org_sub.user_name = '';
                    if (subCtrl.sub) subCtrl.sub.user_name = '';
                });
            }
        };
        // user name end

        // document.getElementById('date_of_birth').addEventListener('change', function () {
        //     const selectedDate = new Date(this.value);
        //     const maxDate = new Date("2005-12-31");

        //     if (selectedDate > maxDate) {
        //         alert("Date of year must be on or before 2005.");
        //         this.value = "";
        //     }
        // });


        scope.subCtrl.getDeviceCount = function (deviceJson) {
            try {
                if (!deviceJson || deviceJson === 'null') return 0;
                
                let arr = JSON.parse(deviceJson);
                
                // Handle double-serialization: if the result is still a string, parse it again
                if (typeof arr === 'string') {
                    arr = JSON.parse(arr);
                }
                
                return Array.isArray(arr) ? arr.length : 0;
            } catch (e) {
                // console.error("Error parsing device count:", e);
                return 0;
            }
        };

        scope.calculateDays = function (start, end) {
            if (!start || !end) return '';

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const startDate = new Date(start);
            const endDate = new Date(end);
            startDate.setHours(0, 0, 0, 0);
            endDate.setHours(0, 0, 0, 0);

            // If start and end are the same
            if (startDate.getTime() === endDate.getTime()) {
                return 'Today';
            }

            if (today < startDate) {
                return 'Waiting';
            }

            if (today > endDate) {
                return 'Subscription Expired';
            }

            // // If today is outside the range
            // if (today < startDate || today > endDate) {
            //     // return '0 Day';
            //     return 'Subscription Expired';
            // }

            const timeDiff = endDate - today;
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            return daysDiff > 0 ? daysDiff + ' Days' : 'Subscription Expired';
        };


        // plan is active or not active
        scope.subCtrl.isExpired = function (endDateStr) {
            if (!endDateStr) return true;

            const endDate = new Date(endDateStr);
            const now = new Date();
            now.setHours(0, 0, 0, 0);

            return endDate < now;
        };


        scope.productTypeFilter = function (record) {
            return record.product_type !== 'custom subscription' && record.product_type !== 'subscription sets';
        };

        // ===========================================*******************************************======================================
        //                                                      fetch json data code
        // ===========================================*******************************************======================================

        // fetch currency code
        fetch(`${appUrl}country_code.json`)
            .then(response => response.json())
            .then(data => {
                this.countryCodeList = data;
            });

        // fetch country
        fetch(`${appUrl}subscriber_countries.json`)
            .then(response => response.json())
            .then(data => {
                this.countryList = data;
            });

        // fetch country
        fetch(`${appUrl}language.json`)
            .then(response => response.json())
            .then(data => {
                this.languages = data;
            });

        // fetch timezone
        fetch(`${appUrl}subscriber_timezone.json`)
            .then(response => response.json())
            .then(data => {
                this.timezoneList = data;
            });

        // fetch subscriber data
        this.filterSubscribers = function () {
            const search = scope.searchRecords || {};
            const payload = angular.copy(search);

            // Prepare query parameters for the URL
            const queryParams = {};
            for (const key in search) {
                if (search.hasOwnProperty(key) && search[key] && search[key] !== 'all') {
                    queryParams[key] = search[key];
                }
            }
            // Add timestamp
            queryParams['_t'] = new Date().getTime();

            requestFactory.post(
                requestFactory.getUrl('subscribers/get-all/records', queryParams),
                payload,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const records = response.data.data;
                        const filtered = records.filter(r => {
                            let match = true;
                            if (search.account_number) {
                                match = match && r.account_number && String(r.account_number).toLowerCase().includes(search.account_number.trim().toLowerCase());
                            }
                            if (search.user_name) {
                                const term = search.user_name.trim().toLowerCase();
                                const val = r.user_name ? String(r.user_name).toLowerCase() : '';
                                match = match && val.includes(term);
                            }
                            if (search.first_name) {
                                const fullName = (r.first_name || '') + ' ' + (r.last_name || '');
                                match = match && fullName.toLowerCase().includes(search.first_name.trim().toLowerCase());
                            }
                            if (search.email) {
                                match = match && r.email && String(r.email).toLowerCase().includes(search.email.trim().toLowerCase());
                            }
                            if (search.phone_number) {
                                match = match && r.phone_number && String(r.phone_number).toLowerCase().includes(search.phone_number.trim().toLowerCase());
                            }
                            if (search.is_active && search.is_active !== 'all') {
                                match = match && String(r.is_active) === String(search.is_active);
                            }
                            return match;
                        });

                        scope.records = filtered;
                        scope.showRecords = filtered.length > 0;
                        scope.noRecords = filtered.length === 0;

                        if (!scope.$$phase) {
                            scope.$apply();
                        }

                    } else {
                        scope.records = [];
                        scope.showRecords = false;
                        scope.noRecords = true;
                    }
                }
            );
        };

        // Initial load
        this.filterSubscribers();
    }

];

window.gridControllers = {
    SubscriberController: SubscriberController
};

