var CreditCardController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.credit = {}
        scope.errors = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/credit-card/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.addCreditcard = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.credit = {}
            $("#subscriptionForm").css('display', 'block');
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#subscriptionTranslationForm").css('display', "none");
        }

        // ==============================***********************************==============================

        this.editcredit = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.credit.id = records.id;
            this.credit.profile_name = records.profile_name;
            this.credit.security_type = records.security_type;
            this.credit.card_type = records.card_type;
            this.credit.card_number = records.card_number;
            this.credit.expiration_month = records.expiration_month;
            this.credit.expiration_year = records.expiration_year;
            this.credit.cvv = records.cvv;
            this.credit.billing_address = records.billing_address;
            this.credit.first_name = records.first_name;
            this.credit.last_name = records.last_name;
            this.credit.email = records.email;
            this.credit.phone_number = records.phone_number;
            this.credit.address = records.address;
            this.credit.city = records.city;
            this.credit.zip_code = records.zip_code;
            this.credit.country = records.country;
            this.credit.state = records.state;
            this.credit.is_active = records.is_active ? true : false;
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.credit = {};
            });
        }

        this.save = function ($event, id) {
            $event.preventDefault();

            // const orgIdInput = document.getElementById('subscriber-id');
            const urlParams = new URLSearchParams(window.location.search);
            const SubscriberInput = urlParams.get('subscriber-id');

            if (!SubscriberInput) {
                console.error("Required input elements not found.");
                return;
            }

            const payload = {
                subscriber_id: SubscriberInput,
                profile_name: this.credit.profile_name || '',
                security_type: this.credit.security_type || '',
                card_type: this.credit.card_type || '',
                card_number: this.credit.card_number || '',
                expiration_month: this.credit.expiration_month || '',
                expiration_year: this.credit.expiration_year || '',
                cvv: this.credit.cvv || '',
                billing_address: this.credit.billing_address || '',
                first_name: this.credit.first_name || '',
                last_name: this.credit.last_name || '',
                email: this.credit.email || '',
                phone_number: this.credit.phone_number || '',
                address: this.credit.address || '',
                city: this.credit.city || '',
                zip_code: this.credit.zip_code || '',
                country: this.credit.country || '',
                state: this.credit.state || '',
                is_active: this.credit.is_active || '',
            }

            // console.log(payload);

            if (id) {
                requestFactory.post(requestFactory.getUrl('subscriber/credit-card/edit/' + id), payload, function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closecreditEdit();
                    $timeout(function () {
                        self.credit = {};
                    }, 100);
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('subscriber/credit-card/add'), payload, function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closecreditEdit();
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);
                }, this.fillError);
            }
        }

        scope.creditcardList = [];

        // Step 2: Triggered when user clicks radio button
        scope.onBillingTypeChange = function () {
            if (scope.credCtrl.credit.billing_address === '0') {
                const urlParams = new URLSearchParams(window.location.search);
                const targetSubId = urlParams.get('subscriber-id');

                if (targetSubId && Array.isArray(self.subscriberRecord)) {
                    const subscriber = self.subscriberRecord.find(s => String(s.id) === String(targetSubId));
                    if (subscriber) {
                        scope.credCtrl.credit.first_name = subscriber.first_name || '';
                        scope.credCtrl.credit.last_name = subscriber.last_name || '';
                        scope.credCtrl.credit.email = subscriber.email || '';
                        scope.credCtrl.credit.phone_number = subscriber.phone_number || subscriber.phone || '';
                        scope.credCtrl.credit.address = subscriber.address || '';
                        scope.credCtrl.credit.city = subscriber.city || '';
                        scope.credCtrl.credit.zip_code = subscriber.zip_code || subscriber.postal_code || '';
                        scope.credCtrl.credit.country = subscriber.country || '';
                        scope.credCtrl.credit.state = subscriber.state || '';
                    } else {
                        console.warn("No matching subscriber found for ID: " + targetSubId);
                    }
                }
            } else {
                scope.credCtrl.credit.first_name = '';
                scope.credCtrl.credit.last_name = '';
                scope.credCtrl.credit.email = '';
                scope.credCtrl.credit.phone_number = '';
                scope.credCtrl.credit.address = '';
                scope.credCtrl.credit.city = '';
                scope.credCtrl.credit.zip_code = '';
                scope.credCtrl.credit.country = '';
                scope.credCtrl.credit.state = '';
            }
        };

        // ==============================***********************************==============================

        scope.togglePublishNow = function (record, id) {
            // if (!id) {
            //     console.warn('[TOGGLE] Still no valid ID. Aborting.');
            //     return;
            // }
            // console.log('[TOGGLE] Current is_active before toggle:', record.is_active);

            // Toggle the is_active
            record.is_active = record.is_active == 1 ? 0 : 1;

            // console.log('[TOGGLE] New is_active after toggle:', record.is_active);

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            // console.log('[TOGGLE] Payload to be sent:', payload);

            if (id) {
                const url = requestFactory.getUrl('subscriber/credit-card/edit/' + id);
                // console.log('[TOGGLE] API URL:', url);

                requestFactory.post(
                    url,
                    payload,
                    function (response) {
                        // console.log('[TOGGLE] Success response from API:', response);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', 'Publish is_active updated');
                        $timeout(function () {
                            location.reload();
                        }, 100);
                    },
                    function (error) {
                        // console.error('[TOGGLE] Error response from API:', error);
                        requestFactory.getToaster();
                        requestFactory.setToaster('error', 'Failed to update publish is_active');

                        // Revert is_active on error
                        record.is_active = record.is_active == 1 ? 0 : 1;
                        // console.log('[TOGGLE] is_active reverted due to error:', record.is_active);
                    }
                );
            } else {
                // console.warn('[TOGGLE] No ID provided, request not sent.');
            }
        };

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.security_type)) {
                scope.searchRecords.security_type = 'all';
            }
        });

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.billing_address)) {
                scope.searchRecords.billing_address = 'all';
            }
        });

        scope.credCtrl.updateCardPattern = function () {
            var type = scope.credCtrl.credit.card_type;

            if (type === 'american express') {
                // 15-digit number
                scope.credCtrl.cardNumberPattern = /^\d{15}$/;
            } else if (['visa', 'mastercard', 'jcb'].includes(type)) {
                // 16-digit number
                scope.credCtrl.cardNumberPattern = /^\d{16}$/;
            } else {
                // Default to allow any format (or disallow if you prefer)
                scope.credCtrl.cardNumberPattern = /.*/;
            }
        };

        scope.credCtrl.updateCardPattern();

        this.closecreditEdit = function () {
            scope.gridSideFormClose();
        };


        this.fetchAssignedDevice = function () {
            requestFactory.post(
                requestFactory.getUrl('subscriber/credit-card/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        try {
                            rootScope.handleAssignedDevices(response.data.data);
                        } catch (e) {
                            console.error("Error in subscriberdevice:", e);
                        }
                    } else {
                        console.warn("Invalid data format from DRM:", response);
                    }
                }
            );
        };

        rootScope.handleAssignedDevices = function (devices) {
            try {
                const currentUrl = window.location.href;
                // console.log("Current URL:", currentUrl);

                const urlObj = new URL(currentUrl);
                const subscriberIdFromUrl = urlObj.searchParams.get('subscriber-id');
                // console.log("Extracted subscriber ID from URL:", subscriberIdFromUrl);

                if (!subscriberIdFromUrl) {
                    console.warn("Subscriber ID not found in URL.");
                    rootScope.records = [];
                    return;
                }

                // console.log("All devices:", devices);

                const filtereddevices = devices.filter(payment =>
                    Number(payment.subscriber_id) === Number(subscriberIdFromUrl)
                );

                // console.log("Filtered devices for subscriber ID " + subscriberIdFromUrl + ":", filtereddevices);
                // console.log("Filtered devices count:", filtereddevices.length);

                rootScope.records = filtereddevices;
                rootScope.subscriberIdFromUrl = subscriberIdFromUrl;

                // console.log("rootScope.records after filter:", rootScope.records);
            } catch (error) {
                console.error("Error in handlePaymentHistory:", error);
            }
        };

        this.fetchAssignedDevice();

        // fetch country
        fetch(`${appUrl}subscriber_countries.json`)
            .then(response => response.json())
            .then(data => {
                this.countryList = data;
            });

        // fetch subscriber record 
        this.fetchSubscriberRecord = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data.data && Array.isArray(response.data.data)) {
                        this.subscriberRecord = response.data.data;
                    }
                },
            );
        }
        this.fetchSubscriberRecord();
    }
];

window.gridControllers = {
    CreditCardController: CreditCardController
};

// 358 993 163 739 887 5 = 16 (jcb)
// 374 655 016 525 189 = 15 (ax)
// 540 814 324 904 217 2 = 16 (mc)
// 491 657 546 609 719 9 = 16 (visa)