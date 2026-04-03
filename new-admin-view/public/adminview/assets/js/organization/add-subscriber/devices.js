var DeviceController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;

        const urlParams = new URLSearchParams(window.location.search);
        const subscriberIdFromUrl = urlParams.get('subscriber-id');
        rootScope.subscriberIdFromUrl = subscriberIdFromUrl;

        this.info = {};
        scope.errors = {};
        this.device = {};
        requestFactory.getToaster();
        scope.searchRecords = { is_active: 'all' };
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader(1);
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/device/info'),
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

        // this.fetchInfo = function () {
        //     const currentUrl = window.location.href;
        //     const match = currentUrl.match(/devices\/(\d+)/);

        //     if (match && match[1]) {
        //         const subscriberId = match[1];

        //         requestFactory.get(
        //             requestFactory.getUrl('subscriber/device/info/' + subscriberId),
        //             this.defineProperties,
        //             function (response) {
        //                 rootScope.redirectUnauthenticated(response);
        //             }
        //         );
        //     } else {
        //         alert('Subscriber ID not found in URL');
        //     }
        // };

        // this.fetchInfo();

        this.addDevice = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.device = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#subscriptionForm").css('display', 'block');
        }

        this.editdevice = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.device.id = records.id;
            this.device.device_type = records.device_type;
            this.device.brand_model = records.brand_model;
            this.device.mac_address = records.mac_address;
            this.device.serial_number = records.serial_number;
            this.device.identifier = records.identifier;
            this.device.ip_address = records.ip_address;
            this.device.city = records.city;
            this.device.country = records.country;
            this.device.latitude = records.latitude;
            this.device.longitude = records.longitude;
            this.device.is_active = (records.is_active) ? true : false;
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.device = {};
            });
        };

        this.save = function ($event, id) {
            // $event.preventDefault();

            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');
            // console.log("Subscriber ID from URL:", subscriberId);

            if (!subscriberId) {
                console.error("Subscriber ID not found in URL.");
                return;
            }

            const payload = {
                subscriber_id: subscriberId,
                device_type: this.device.device_type || '',
                brand_model: this.device.brand_model || '',
                mac_address: this.device.mac_address || '',
                serial_number: this.device.serial_number || '',
                identifier: this.device.identifier || '',
                ip_address: this.device.ip_address || '',
                city: this.device.city || '',
                country: this.device.country || '',
                latitude: this.device.latitude || '',
                longitude: this.device.longitude || '',
                is_active: this.device.is_active || '',
            };
            console.log(payload);

            if (id) {
                requestFactory.post(requestFactory.getUrl('subscribers-device/edit/' + id), payload, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closeDeviceEdit();
                    setTimeout(() => {
                        window.location.reload();
                    }, 350);
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('subscribers/device/add'), payload, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closeDeviceEdit();
                    setTimeout(() => {
                        window.location.reload();
                    }, 350);
                }, this.fillError);
            }
        };


        // this.devicedefineProperties = function (data) {
        //     if (
        //         data &&
        //         data.device_info &&
        //         data.device_info.original &&
        //         Array.isArray(data.device_info.original.data)
        //     ) {
        //         scope.device_info = data.device_info.original.data;
        //         requestFactory.toggleLoader();
        //     }
        // };


        // notification table code
        // this.devicefetchInfo = function () {
        //     const currentUrl = window.location.href;
        //     const match = currentUrl.match(/devices\/(\d+)/);
        //     if (match && match[1]) {
        //         const subscriberId = match[1];
        //         requestFactory.get(
        //             requestFactory.getUrl('subscriber/device/info/' + subscriberId),
        //             this.devicedefineProperties,
        //             function (response) {
        //                 if (response) {
        //                     console.log("Valid device data:", response);
        //                 } else {
        //                     console.warn("Invalid data format from announcmentfetchInfo:", response);
        //                 }
        //             }
        //         );
        //     } else {
        //         alert('Subscriber ID not found in URL');
        //     }
        // };
        // this.devicefetchInfo();

        // =======================================*******************************************======================================

        scope.togglePublishNow = function (record, id) {
            // if (!id) {
            //     console.warn('[TOGGLE] Still no valid ID. Aborting.');
            //     return;
            // }
            // console.log('[TOGGLE] Current status before toggle:', record.status);

            // Toggle the status
            record.is_active = record.is_active == 1 ? 0 : 1;

            // console.log('[TOGGLE] New status after toggle:', record.status);

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            // console.log('[TOGGLE] Payload to be sent:', payload);

            if (id) {
                const url = requestFactory.getUrl('subscribers-device/toggle/' + id);
                // console.log('[TOGGLE] API URL:', url);

                requestFactory.post(
                    url,
                    payload,
                    function (response) {
                        // console.log('[TOGGLE] Success response from API:', response);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', 'Publish status updated');
                        $timeout(function () {
                            location.reload();
                        }, 100);
                    },
                    function (error) {
                        // console.error('[TOGGLE] Error response from API:', error);
                        requestFactory.getToaster();
                        requestFactory.setToaster('error', 'Failed to update publish status');

                        // Revert status on error
                        record.status = record.status == 1 ? 0 : 1;
                        // console.log('[TOGGLE] Status reverted due to error:', record.status);
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
            rootScope.records = scope.records;
        });

        this.closeDeviceEdit = function () {
            scope.gridSideFormClose();
        };

    }
];

window.gridControllers = {
    DeviceController: DeviceController
};
