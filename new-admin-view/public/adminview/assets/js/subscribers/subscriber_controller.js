'use strict';

var SubscriberController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;
        this.info = {};
        scope.errors = {};
        this.subscriber = { pin_code: '4321' };
        this.countryCodeList = [];
        this.countryList = [];
        this.languages = [];
        this.timezoneList = [];

        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

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

        // Initialize based on mode
        this.init = function() {
            const mode = document.getElementById('form-mode')?.value;
            const targetId = document.getElementById('target-id')?.value;

            if (mode === 'add') {
                this.fetchOrg(targetId);
            } else if (mode === 'edit') {
                this.fetchSubscriber(targetId);
            }
        };

        this.fetchOrg = function (orgId) {
            if (!orgId) return;
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                {},
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const org = response.data.data.find(o => String(o.id) === String(orgId));
                        if (org) {
                            self.subscriber.organization_id = org.organization_id;
                            self.subscriber.organization_name = org.organization_name;
                            self.subscriber.provider_id = org.provider_id;
                            self.subscriber.organization_prefix = org.prefix || '';
                            scope.$applyAsync();
                        }
                    }
                }
            );
        };

        this.fetchSubscriber = function (subId) {
            if (!subId) return;
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                {},
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const sub = response.data.data.find(o => String(o.id) === String(subId));
                        if (sub) {
                            self.subscriber = {
                                id: sub.id,
                                organization_name: sub.organization_name,
                                provider_id: sub.fetch_organization?.provider_id,
                                organization_prefix: sub.fetch_organization?.prefix || '',
                                account_number: sub.account_number || '',
                                pin_code: sub.pin_code || '4321',
                                user_name: sub.user_name || '',
                                first_name: sub.first_name || '',
                                last_name: sub.last_name || '',
                                email: sub.email || '',
                                phone_number_code: sub.phone_number_code || '',
                                phone_number: sub.phone_number || '',
                                address: sub.address || '',
                                city: sub.city || '',
                                zip_code: sub.zip_code || '',
                                country: sub.country || '',
                                state: sub.state || '',
                                language: sub.language || '',
                                date_of_birth: sub.date_of_birth ? new Date(sub.date_of_birth) : '',
                                timezone: sub.timezone || '',
                            };
                            scope.$applyAsync();
                        }
                    }
                }
            );
        };

        this.save = function ($event) {
            $event.preventDefault();
            const mode = document.getElementById('form-mode')?.value;
            const targetId = document.getElementById('target-id')?.value;

            const payload = angular.copy(this.subscriber);
            if (mode === 'add') {
                payload.organization_id = targetId;
            } else {
                payload.id = targetId;
            }
            
            // Format date if present
            if (payload.date_of_birth instanceof Date) {
                payload.date_of_birth = payload.date_of_birth.toISOString().split('T')[0];
            }

            const url = mode === 'add' ? 'subscriber/add' : 'subscribers/add';

            requestFactory.post(requestFactory.getUrl(url),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        if (mode === 'add') {
                            window.location.href = `${appUrl}admin/view-subscribers?id=${targetId}`;
                        } else {
                            location.reload();
                        }
                    }, 600);
                }, this.fillErrors
            );
        };

        this.cancel = function() {
            window.location.href = `${appUrl}admin/subscribers`;
        };

        // Shared helper functions
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
        };

        window.generateAccountNumber = function () {
            const checkbox = document.getElementById('account_number_auto');
            if (checkbox && checkbox.checked) {
                const prefix = self.subscriber.organization_prefix || '';
                const part1 = Math.floor(100 + Math.random() * 900);
                const part2 = Math.floor(100 + Math.random() * 900);
                const token = prefix ? `${prefix}-${part1}-${part2}` : `${part1}-${part2}`;
                scope.$apply(() => { self.subscriber.account_number = token; });
            } else {
                scope.$apply(() => { self.subscriber.account_number = ''; });
            }
        };

        window.generatePinCode = function () {
            const checkbox = document.getElementById('pin_code_auto');
            if (checkbox && checkbox.checked) {
                scope.$apply(() => { self.subscriber.pin_code = '4321'; });
            } else {
                scope.$apply(() => { self.subscriber.pin_code = ''; });
            }
        };

        window.generateUsername = function () {
            const checkbox = document.getElementById('username_auto');
            if (checkbox && checkbox.checked) {
                const token = Array.from({ length: 4 }, () => Math.floor(Math.random() * 10)).join('');
                scope.$apply(() => { self.subscriber.user_name = token; });
            } else {
                scope.$apply(() => { self.subscriber.user_name = ''; });
            }
        };

        // Fetch JSON data
        const fetchJson = (url, target) => {
            fetch(`${appUrl}${url}`)
                .then(r => r.json())
                .then(data => { self[target] = data; scope.$applyAsync(); });
        };

        fetchJson('country_code.json', 'countryCodeList');
        fetchJson('subscriber_countries.json', 'countryList');
        fetchJson('language.json', 'languages');
        fetchJson('subscriber_timezone.json', 'timezoneList');

        this.init();
    }
];

window.gridControllers = window.gridControllers || {};
window.gridControllers.SubscriberController = SubscriberController;
