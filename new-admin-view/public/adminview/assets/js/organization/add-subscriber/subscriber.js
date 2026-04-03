'use strict';

var SubscriberController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;

        this.info = {};
        scope.errors = {};
        this.org_sub = { pin_code: '4321' };
        // this.organizations = [];
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

        this.editSubscriber = function (record, id) {
            if (!id) {
                console.warn("Subscriber ID is missing!");
                return;
            }
            const url = `subscribers/detail/add?subscriber-id=${id}`;
            window.location.href = url;
        };

        // get phone code json
        // fetch(`${appUrl}phonecode.json`)
        //     .then(response => response.json())
        //     .then(data => {
        //         this.phnCodeList = data;
        //     })
        // ==================================================***************************************************==========================================

        // strat fetch organization name
        this.fetchOrg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        OrgdefineProperties(response.data.data);
                        // console.log("Valid org data:", response);
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };
        function OrgdefineProperties(Org) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                // console.error("Element with ID 'home' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("org-id")?.value;

            if (!targetOrgId) {
                // console.warn("Target organization ID not found.");
                return;
            }

            const org = Org.find(o => String(o.id) === String(targetOrgId));
            // console.log("Fetched organization:", org);
            if (org) {
                if (localScope && localScope.subCtrl) {
                    const updateModel = () => {
                        localScope.subCtrl.org_sub.organization_id = org.organization_id;
                        localScope.subCtrl.org_sub.organization_name = org.organization_name;
                        localScope.subCtrl.org_sub.provider_id = org.provider_id;
                        // console.log(localScope.subCtrl.org_sub);
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
        this.fetchOrg();
        // end fetch organization name


        // save subscriber code strat
        this.savesubscriber = function ($event) {
            $event.preventDefault();
            const orgIdInput = document.getElementById('org-id')?.value;
            if (!orgIdInput) {
                // console.error("Required input elements not found.");
                return;
            }

            const payload = {
                organization_id: orgIdInput,
                organization_name: this.org_sub.organization_name || '',
                account_number: this.org_sub.account_number || '',
                pin_code: this.org_sub.pin_code || '',
                user_name: this.org_sub.user_name || '',
                password: this.org_sub.password || '',
                first_name: this.org_sub.first_name || '',
                last_name: this.org_sub.last_name || '',
                email: this.org_sub.email || '',
                phone_number_code: this.org_sub.phone_number_code || '',
                phone_number: this.org_sub.phone_number || '',
                address: this.org_sub.address || '',
                city: this.org_sub.city || '',
                zip_code: this.org_sub.zip_code || '',
                country: this.org_sub.country || '',
                state: this.org_sub.state || '',
                language: this.org_sub.language || '',
                date_of_birth: this.org_sub.date_of_birth || '',
                timezone: this.org_sub.timezone || '',
            };

            requestFactory.post(requestFactory.getUrl('subscriber/add'),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    $(".sidepanel").removeClass("in");
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/view-subscribers?id=${orgIdInput}`;
                    }, 600);
                }, this.fillErrors
            );
        }
        // save subscriber code end

        // ==================================================***************************************************==========================================

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

        // account number strat
        window.generateAccountNumber = function () {
            const numbers = "1234567890";
            const length = 14;
            const token = Array.from({ length }, () =>
                numbers.charAt(Math.floor(Math.random() * numbers.length))
            ).join('');

            const input = document.getElementById('account_number');
            if (input) {
                input.value = token;
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.subCtrl) {
                        scope.$apply(() => {
                            scope.subCtrl.org_sub.account_number = token;
                        });
                    }
                }
            } else {
                console.warn("Account number input not found in DOM.");
            }
        };
        // account number end

        // pin code stat
        window.generatePinCode = function () {
            const numbers = "1234567890";
            const length = 4;
            const token = Array.from({ length }, () =>
                numbers.charAt(Math.floor(Math.random() * numbers.length))
            ).join('');

            const input = document.getElementById('pin_code');
            if (input) {
                input.value = token;
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.subCtrl) {
                        scope.$apply(() => {
                            scope.subCtrl.org_sub.pin_code = token;
                        });
                    }
                }
            } else {
                console.warn("Pin code input not found in DOM.");
            }
        };
        // pin code end

        // user name strat
        window.generateUsername = function () {
            const prefix = "user";
            const numbers = "1234567890";
            const length = 4;

            const randomDigits = Array.from({ length }, () =>
                numbers.charAt(Math.floor(Math.random() * numbers.length))
            ).join('');

            const token = prefix + randomDigits;

            const input = document.getElementById('user_name');
            if (input) {
                input.value = token;

                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.subCtrl) {
                        scope.$apply(() => {
                            scope.subCtrl.org_sub.user_name = token;
                        });
                    }
                }
            } else {
                console.warn("Username input not found in DOM.");
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
    }
];

window.gridControllers = {
    SubscriberController: SubscriberController
};
