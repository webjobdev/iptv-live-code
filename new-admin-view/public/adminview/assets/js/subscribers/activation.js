var ActivationController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.addslot = {};
        scope.errors = {};
        this.channelList = [];
        this.contentList = [];
        this.subscriptionPlans = [];

        // Define subscriberIdFromUrl for use in grid filters
        const urlParams = new URL(window.location.href).searchParams;
        const subscriberIdFromUrl = urlParams.get('subscriber-id');
        scope.subscriberIdFromUrl = subscriberIdFromUrl;

        // --- Optimized API Fetching for Assigned Devices ---
        this.assignedDevicesCache = null;
        this.isFetchingAssignedDevices = false;
        this.assignedDevicesCallbacks = [];

        /**
         * Centralized function to fetch assigned devices.
         * Prevents duplicate API calls by queuing callbacks.
         */
        this.fetchOnlyAssignedDevices = function (callback) {
            if (this.assignedDevicesCache) {
                if (typeof callback === 'function') callback(this.assignedDevicesCache);
                return;
            }

            if (typeof callback === 'function') {
                this.assignedDevicesCallbacks.push(callback);
            }

            if (this.isFetchingAssignedDevices) return;
            this.isFetchingAssignedDevices = true;

            const requestData = {
                ...this.defineProperties,
                subscriber_id: subscriberIdFromUrl,
                rowsPerPage: 999
            };

            requestFactory.post(
                requestFactory.getUrl('only-assigned-device/records'),
                requestData,
                (response) => {
                    this.isFetchingAssignedDevices = false;
                    const data = response?.data?.data || [];
                    this.assignedDevicesCache = data;

                    while (this.assignedDevicesCallbacks.length > 0) {
                        const cb = this.assignedDevicesCallbacks.shift();
                        try {
                            cb(data);
                        } catch (err) {
                            console.error("Error in queued callback:", err);
                        }
                    }
                },
                (error) => {
                    this.isFetchingAssignedDevices = false;
                    console.error("❌ Failed to fetch assigned devices:", error);
                    while (this.assignedDevicesCallbacks.length > 0) {
                        const cb = this.assignedDevicesCallbacks.shift();
                        cb([]);
                    }
                }
            );
        };

        // --- Optimized API Fetching for General Settings ---
        this.generalSettingsCache = null;
        this.isFetchingGeneralSettings = false;
        this.generalSettingsCallbacks = [];

        /**
         * Centralized function to fetch general settings.
         * Prevents duplicate API calls for pricing/settings.
         */
        this.fetchGeneralSettings = function (callback) {
            if (this.generalSettingsCache) {
                if (typeof callback === 'function') callback(this.generalSettingsCache);
                return;
            }

            if (typeof callback === 'function') {
                this.generalSettingsCallbacks.push(callback);
            }

            if (this.isFetchingGeneralSettings) return;
            this.isFetchingGeneralSettings = true;

            requestFactory.post(
                requestFactory.getUrl('general/settings/get/records'),
                self.defineProperties,
                (response) => {
                    this.isFetchingGeneralSettings = false;
                    this.generalSettingsCache = response;

                    while (this.generalSettingsCallbacks.length > 0) {
                        const cb = this.generalSettingsCallbacks.shift();
                        try {
                            cb(response);
                        } catch (err) {
                            console.error("Error in queued settings callback:", err);
                        }
                    }
                },
                (error) => {
                    this.isFetchingGeneralSettings = false;
                    console.error("❌ Failed to fetch general settings:", error);
                    while (this.generalSettingsCallbacks.length > 0) {
                        const cb = this.generalSettingsCallbacks.shift();
                        cb(null);
                    }
                }
            );
        };

        // --- Optimized API Fetching for Subscriber Records ---
        this.subscriberRecordsCache = null;
        this.isFetchingSubscriberRecords = false;
        this.subscriberRecordsCallbacks = [];

        /**
         * Centralized function to fetch subscriber records.
         * Prevents duplicate API calls by queuing callbacks.
         */
        this.fetchSubscriberRecords = function (subscriberId, callback) {
            if (this.subscriberRecordsCache) {
                if (typeof callback === 'function') callback(this.subscriberRecordsCache);
                return;
            }

            if (typeof callback === 'function') {
                this.subscriberRecordsCallbacks.push(callback);
            }

            if (this.isFetchingSubscriberRecords) return;
            this.isFetchingSubscriberRecords = true;

            const requestData = {
                ...self.defineProperties,
                subscriberId: subscriberId
            };

            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                requestData,
                (response) => {
                    this.isFetchingSubscriberRecords = false;

                    let subscriberList = [];
                    if (response && response.data && response.data.data) {
                        if (Array.isArray(response.data.data.data)) {
                            subscriberList = response.data.data.data;
                        } else if (Array.isArray(response.data.data)) {
                            subscriberList = response.data.data;
                        } else {
                            subscriberList = response.data.data.data || response.data.data;
                        }
                    } else if (response && response.data && Array.isArray(response.data)) {
                        subscriberList = response.data;
                    }

                    this.subscriberRecordsCache = subscriberList;

                    while (this.subscriberRecordsCallbacks.length > 0) {
                        const cb = this.subscriberRecordsCallbacks.shift();
                        try {
                            cb(subscriberList);
                        } catch (err) {
                            console.error("Error in queued subscriber records callback:", err);
                        }
                    }
                },
                (error) => {
                    this.isFetchingSubscriberRecords = false;
                    console.error("❌ Failed to fetch subscriber records:", error);
                    while (this.subscriberRecordsCallbacks.length > 0) {
                        const cb = this.subscriberRecordsCallbacks.shift();
                        cb([]);
                    }
                }
            );
        };

        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        self.customSubscriptionPrices = {};
        self.customDayPrice = 0;


        self.calculateCustomSubscriptionPrice = function () {
            const input = self.addslot.custom_length_input;
            const dayMonthType = self.addslot.day_month_type;
            const lengthType = self.addslot.length_type;
            const prices = self.customSubscriptionPrices || {};
            const dayPrice = self.customDayPrice || 0;

            const selectedId = self.addslot.subscription;
            const plans = self.subscriptionPlans || [];
            const selectedPlan = plans.find(p => String(p.id) === String(selectedId));

            let baseDays = 0;
            let baseMonths = 0;
            let basePrice = 0;

            if (selectedPlan && selectedId !== 'free') {
                if (selectedPlan.subs_length_time_type === 'Days') {
                    baseDays = parseInt(selectedPlan.subscription_length) || 0;
                } else if (selectedPlan.subs_length_time_type === 'Months') {
                    baseMonths = parseInt(selectedPlan.subscription_length) || 0;
                }
                basePrice = parseFloat(selectedPlan.total_price || selectedPlan.price || 0);
            }

            let extraDays = 0;
            let extraMonths = 0;
            let extraPrice = 0;

            if (lengthType === 'celnder') {
                if (input) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    let selectedDate = new Date(input);
                    if (!isNaN(selectedDate.getTime())) {
                        selectedDate.setHours(0, 0, 0, 0);
                        const diffDays = Math.ceil((selectedDate - today) / (1000 * 60 * 60 * 24));
                        if (diffDays > 0) {
                            extraDays = diffDays;
                            extraPrice = extraDays * dayPrice;
                        }
                    }
                }
            } else if (lengthType === 'day-month' && dayMonthType === 'day') {
                if (input !== null && input !== undefined && input !== '') {
                    extraDays = parseInt(input) || 0;
                    extraPrice = extraDays * dayPrice;
                }
            } else if (lengthType === 'day-month' && dayMonthType === 'month') {
                if (input !== null && input !== undefined && input !== '') {
                    const extraPlan = plans.find(p => String(p.id) === String(input));
                    if (extraPlan) {
                        if (extraPlan.subs_length_time_type === 'Months') {
                            extraMonths = parseInt(extraPlan.subscription_length) || 0;
                        } else {
                            extraDays += parseInt(extraPlan.subscription_length) || 0;
                        }
                        extraPrice = parseFloat(extraPlan.total_price || extraPlan.price || 0);
                    }
                }
            }

            const totalMonths = baseMonths + extraMonths;
            const totalDays = baseDays + extraDays;

            // Accurate display: 12 months = 365 days
            let finalTotalDays = 0;
            if (totalMonths > 0) {
                // Use 30.417 as monthly factor to get 365 for 12 months (365/12 approx)
                finalTotalDays = Math.round(totalMonths * 30.417) + totalDays;
            } else {
                finalTotalDays = totalDays;
            }
            
            let result = `${finalTotalDays} day${finalTotalDays !== 1 ? 's' : ''}`;
            if (finalTotalDays <= 0) result = '0 days';

            self.dateDifferenceText = result;
            self.addslot.durationText = result;
            self.addslot.total = (basePrice + extraPrice) || 0;

            if (scope.$applyAsync) {
                scope.$applyAsync();
            }
        };

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber-subscriptions/activation/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        self.fetchInfo();

        self.fetchGeneralSettings(function (priceResponse) {
            if (!priceResponse) return;
            try {
                const settings = priceResponse.info.setting_data || [];
                const customPrices = {};
                let customDay = 0;

                settings.forEach(setting => {
                    if (setting.product_type === 'custom subscription') {
                        if (setting.month_type) {
                            customPrices[setting.month_type.trim()] = parseFloat(setting.price) || 0;
                        }
                        if (setting.days === '1') {
                            customDay = parseFloat(setting.price) || 0;
                        }
                    }
                });

                self.customSubscriptionPrices = customPrices;
                self.customDayPrice = customDay;

                if (self.addslot && self.addslot.product_type === 'custom subscription') {
                    self.calculateCustomSubscriptionPrice();
                }
            } catch (e) {
                console.error("Error parsing settings for prices", e);
            }
        });

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        if (subscriberIdFromUrl) {
            // console.log(`📦 Found subscriber ID from URL: ${subscriberIdFromUrl}`);
        } else {
            console.warn('❗ Subscriber ID not found in URL.');
        }


        this.fetchAccessory = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers-subscriptions/activation/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const responseData = response.data.data;
                        const accessoryItems = responseData.filter(item =>
                            item.product_type === 'accessories' && item.accessory
                        );

                        if (accessoryItems.length > 0) {
                            let parsedAccessories = [];
                            try {
                                const rawAccessoryList = JSON.parse(accessoryItems[0].accessory);
                                parsedAccessories = rawAccessoryList.map((acc, index) => ({
                                    id: index,
                                    accessory: acc
                                }));
                            } catch (err) {
                                console.error("Failed to parse accessory JSON:", accessoryItems[0].accessory, err);
                            }
                            this.accessory = parsedAccessories;
                            AccessoryList(this.accessory);
                        } else {
                            // console.warn("No accessories product type with valid accessory data.");
                        }

                    } else {
                        console.warn("Invalid data format from server.", response);
                    }
                }
            );
        };


        function AccessoryList(asry) {
            // console.log("AccessoryList called with data:", asry);

            const homeElement = document.getElementById("devicesoltadd");
            if (!homeElement) {
                console.warn("Element with id 'devicesoltadd' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            if (!localScope) {
                console.warn("Angular scope not found on 'devicesoltadd'.");
                return;
            }

            const targetasryId = document.getElementById("accessory-id")?.value;
            if (!targetasryId) {
                // console.warn("Target accessory ID not found in element with id 'accessory'.");
                return;
            }

            if (localScope && localScope.actCtrl) {

                const updateModel = () => {
                    localScope.actCtrl.accessory = asry;

                    const ASRY = asry.find(A => String(A.accessory) === String(targetasryId));
                    if (ASRY) {
                        localScope.actCtrl.addslot = {
                            id: ASRY.id,
                            accessory: ASRY.accessory,
                        };
                    };
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }


        self.fetchAccessory();

        // ==============================***********************************==============================
        // edit device & add slot
        // ==============================***********************************==============================

        // edit device
        this.editdevice = function () {
            // $event.preventDefault();
            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (subscriberId) {
                window.location.href = `${appUrl}admin/subscribers/devices?subscriber-id=` + subscriberId;
            } else {
                alert('Subscriber ID not found in URL');
            }
        };

        // add slot
        this.showAddSlotForm = false;
        // const urlParams = new URLSearchParams(window.location.search);
        //     const subscriberId = urlParams.get('subscriber-id');

        //     if (subscriberId) {
        //         // console.log(123);
        //         window.location.href = `${appUrl}admin/activation/subscriber/add-slot?subscriber-id=` + subscriberId;
        //     } else {
        //         alert('Subscriber ID not found in URL');
        //     }

        this.addslot = function ($event) {
            if ($event) $event.preventDefault();

            if (!this.deviceRecords) {
                this.deviceRecords = [];
            }

            const currentLimit = parseInt(this.original_device_limit) || 0;
            if (this.deviceRecords.length >= currentLimit) {
                alert("You have reached the device limit of " + currentLimit + ". You cannot add more slots.");
                return;
            }

            this.deviceRecords.push({
                subscriber_id: subscriberIdFromUrl,
                device_id: null,
                device_detaile: {
                    identifier: '',
                    mac_address: '',
                    brand_model: ''
                },
                created_at: null,
                is_slot: true
            });

            if (scope.$applyAsync) {
                scope.$applyAsync();
            }
        };

        this.removeLocalSlot = function ($event, record) {
            if ($event) {
                $event.preventDefault();
                $event.stopPropagation();
            }
            if (this.deviceRecords && record) {
                const index = this.deviceRecords.indexOf(record);
                if (index !== -1) {
                    this.deviceRecords.splice(index, 1);
                    if (scope.$applyAsync) {
                        scope.$applyAsync();
                    }
                }
            }
        };

        // ==============================***********************************==============================
        // device fetch for add slot and free subscription
        // ==============================***********************************==============================

        this.fetchDevice = function () {

            const productType = this.productType || '';

            const isDeviceRequest =
                productType === 'add devices/slots' ||
                productType === 'free subscription';

            const isAccessoryRequest = productType === 'accessories';

            const apiUrl = (productType === 'add devices/slots')
                ? 'subscriber/not-assign-device'
                : (isDeviceRequest ? 'only-assigned-device/records' : 'general/settings/records');

            const requestData = {
                ...this.defineProperties,
                subscriber_id: subscriberIdFromUrl
            };

            const renderFilteredDevices = (deviceData, source) => {

                if (!Array.isArray(deviceData)) {
                    console.error(`❌ Invalid response from ${source}. Expected array.`);
                    return;
                }

                let filteredDevices = deviceData;

                if (source !== 'subscriber/not-assign-device') {
                    filteredDevices = deviceData.filter(
                        device => String(device.subscriber_id) === String(subscriberIdFromUrl)
                    );
                }

                if (filteredDevices.length > 0) {
                    rednerdevice(filteredDevices);
                } else {
                    console.warn(`⚠️ No devices found for subscriber in ${source}`);
                    rednerdevice([]); // Call with empty array to clear previous state
                }
            };

            if (isDeviceRequest) {

                requestFactory.post(
                    requestFactory.getUrl(apiUrl),
                    requestData,
                    (response) => {

                        const deviceData = response?.data?.data || [];

                        const activeDevices = deviceData.filter(
                            device => String(device.status ?? device.is_active ?? '') === '1'
                        );

                        renderFilteredDevices(activeDevices, apiUrl);
                    }
                );
            }
            else {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/assigned-device/info'),
                    requestData,
                    (response) => {
                        const assignedDeviceData = response?.info?.assigned_device_info || [];

                        renderFilteredDevices(assignedDeviceData, 'assigned-device/info');
                    }
                );
            }

        };

        function rednerdevice(subdevice) {
            const homeElement = document.getElementById("devicesoltadd");
            if (!homeElement) {
                return;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("subscriber-id")?.value || subscriberIdFromUrl;
            let devices = subdevice;

            // If it's NOT 'add devices/slots' or 'free subscription', filter by subscriber. 
            // If it IS 'add devices/slots' or 'free subscription', we want the unassigned devices as they are.
            if (localScope && localScope.actCtrl && localScope.actCtrl.addslot &&
                localScope.actCtrl.addslot.product_type !== 'add devices/slots' &&
                localScope.actCtrl.addslot.product_type !== 'free subscription') {
                devices = subdevice.filter(o => String(o.subscriber_id) === String(targetOrgId));
            }

            if (localScope && localScope.actCtrl) {
                devices.sort((a, b) => (a.id || 0) - (b.id || 0));

                self.fetchSubscriberRecords(subscriberId, function (subscriberList) {
                    const subscriber = subscriberList.find(c => String(c.id) === String(targetOrgId));
                    let limit = null;

                    if (subscriber) {
                        if (subscriber.fetch_organization && Array.isArray(subscriber.fetch_organization.org_mon_plan)) {
                            localScope.actCtrl.subscriptionPlans = subscriber.fetch_organization.org_mon_plan.filter(plan => plan.is_active == 1);
                        }
                        limit = subscriber.device_activation_limit;
                        if (limit === null || limit === undefined) {
                            if (subscriber.fetch_organization && subscriber.fetch_organization.device_activation_limit !== null && subscriber.fetch_organization.device_activation_limit !== undefined) {
                                limit = subscriber.fetch_organization.device_activation_limit;
                            }
                        }
                    }

                    const fetchPricingAndRender = (finalLimit) => {
                        localScope.actCtrl.active_device_limit = finalLimit;
                        self.fetchGeneralSettings(function (priceResponse) {
                            if (!priceResponse) return;

                            const settings = priceResponse.info.setting_data || [];

                            const defaultPhoneSetting = settings.find(s => s.device_type === 'phone (mobile)' && s.product_type === 'add device/slots');
                            const defaultPhonePrice = defaultPhoneSetting?.price || 0;

                            devices.forEach((device, index) => {
                                const deviceType = (device.device_type || '').toLowerCase();
                                let matchedSetting = null;

                                if (index < finalLimit) {
                                    device.price = 0;
                                } else if (['tv', 'tablate', 'desktop', 'laptop'].includes(deviceType)) {
                                    matchedSetting = settings.find(s => s.device_type === deviceType && s.product_type === 'add device/slots');
                                    device.price = matchedSetting?.price || defaultPhonePrice;
                                } else {
                                    matchedSetting = settings.find(s => s.device_type === 'phone (mobile)' && s.product_type === 'add device/slots');
                                    device.price = matchedSetting?.price || defaultPhonePrice;
                                }

                                device.display_name = device.device_name || 'Unnamed Device';
                            });

                            // Store subscription set prices start
                            const subscriptionPrices = {};
                            let dayPrice = 0;

                            // Load prices from settings
                            settings.forEach(setting => {
                                if (setting.product_type === 'subscription sets') {
                                    if (setting.month_type) {
                                        const key = setting.month_type;
                                        subscriptionPrices[key] = setting.price || 0;
                                    }
                                    if (setting.days === '1') {
                                        dayPrice = setting.price || 0;
                                    }
                                }
                            });

                            localScope.actCtrl.subscriptionPrices = subscriptionPrices;

                            localScope.actCtrl.calculateSubscriptionPrice = function () {
                                const durationText = localScope.actCtrl.addslot.durationText || '';
                                const prices = localScope.actCtrl.subscriptionPrices || {};

                                if (!durationText || durationText === 'Free') {
                                    localScope.actCtrl.addslot.total = 'Free';
                                    return;
                                }

                                const monthMatch = durationText.match(/(\d+)\s*month/);
                                const months = monthMatch ? parseInt(monthMatch[1]) : 0;
                                const key = `${months} month`;
                                const basePrice = prices[key] || 0;

                                localScope.actCtrl.addslot.total = basePrice;
                            };

                            localScope.actCtrl.onSubscriptionChange = function () {
                                const selectedId = localScope.actCtrl.addslot.subscription;
                                const plans = localScope.actCtrl.subscriptionPlans || [];
                                const selectedPlan = plans.find(p => String(p.id) === String(selectedId));

                                if (selectedId === 'free' || (selectedPlan && (selectedPlan.subscription_type === 'free' || selectedPlan.subscription_price === 'free'))) {
                                    localScope.actCtrl.addslot.durationText = 'Free';
                                    localScope.actCtrl.addslot.total = 'Free';
                                    if (selectedPlan && selectedPlan.subscription_length) {
                                        scope.calculateSubscriptionDates(selectedPlan.subscription_length, selectedPlan.subs_length_time_type);
                                    } else {
                                        scope.calculateSubscriptionDates('free');
                                    }
                                } else if (selectedPlan) {
                                    scope.calculateSubscriptionDates(selectedPlan.subscription_length, selectedPlan.subs_length_time_type);
                                    localScope.actCtrl.addslot.total = selectedPlan.subscription_price === 'free' ? 'Free' : parseFloat(selectedPlan.total_price || selectedPlan.price || 0);
                                } else {
                                    scope.calculateSubscriptionDates(selectedId);
                                    localScope.actCtrl.calculateSubscriptionPrice();
                                }

                                if (localScope.actCtrl.addslot.product_type === 'custom subscription') {
                                    localScope.actCtrl.calculateCustomSubscriptionPrice();
                                }

                                if (selectedPlan && selectedPlan.subscription_devices) {
                                    localScope.actCtrl.active_device_limit = selectedPlan.subscription_devices;
                                    self.fetchDevicesAndFillSlots(selectedPlan.subscription_devices);
                                } else if (self.original_device_limit) {
                                    localScope.actCtrl.active_device_limit = self.original_device_limit;
                                    self.fetchDevicesAndFillSlots(self.original_device_limit);
                                }
                            };

                            // custom subscription code start
                            const customSubscriptionPrices = {};
                            let customDayPrice = 0;

                            settings.forEach(setting => {
                                if (setting.product_type === 'custom subscription') {
                                    if (setting.month_type) {
                                        const key = setting.month_type.trim();
                                        customSubscriptionPrices[key] = parseFloat(setting.price) || 0;
                                    }
                                    if (setting.days === '1') {
                                        customDayPrice = parseFloat(setting.price) || 0;
                                    }
                                }
                            });

                            self.customSubscriptionPrices = customSubscriptionPrices;
                            self.customDayPrice = customDayPrice;

                            // accessories start
                            const accessoriesPrice = {};
                            scope.actCtrl.accessoriesList = [];

                            settings.forEach(setting => {
                                if (setting.product_type === 'accessories' && setting.accessories_name) {
                                    scope.actCtrl.accessoriesList.push({
                                        name: setting.accessories_name,
                                        price: parseFloat(setting.price) || 0
                                    });
                                }
                            });

                            scope.$watch('actCtrl.addslot.accessory', function (newVal) {
                                if (scope.actCtrl.addslot.product_type === 'accessories') {
                                    if (newVal && Array.isArray(newVal)) {
                                        let total = 0;

                                        newVal.forEach(selectedName => {
                                            const accessory = scope.actCtrl.accessoriesList.find(item => item.name === selectedName);
                                            if (accessory) {
                                                total += accessory.price || 0;
                                            }
                                        });

                                        scope.actCtrl.addslot.total = total;
                                    } else {
                                        scope.actCtrl.addslot.total = 0;
                                    }
                                }
                            });

                            // Model update logic
                            const updateModel = () => {
                                localScope.actCtrl.addslot = localScope.actCtrl.addslot || {};
                                localScope.actCtrl.addslot.devices = devices;

                                if (devices.length > 0) {
                                    if (!devices.some(d => d.selected)) {
                                        devices[0].selected = true;
                                    }
                                    localScope.actCtrl.addslot.default_device_id = devices[0].id;
                                } else {
                                    localScope.actCtrl.addslot.default_device_id = null;
                                }

                                if (typeof localScope.updateSelectedDevices === 'function') {
                                    localScope.updateSelectedDevices();
                                }
                            };

                            if (!localScope.$$phase) {
                                localScope.$apply(updateModel);
                            } else {
                                updateModel();
                            }
                        });
                    };

                    if (limit === null || limit === undefined) {
                        requestFactory.post(
                            requestFactory.getUrl('general-settings/get-records'),
                            self.defineProperties,
                            (settingResponse) => {
                                let defaultLimit = 0;
                                if (settingResponse && Array.isArray(settingResponse)) {
                                    const setting = settingResponse.find(item => item.category === 'payment_setting' && item.key === 'device_activation_limit');
                                    if (setting) {
                                        defaultLimit = parseInt(setting.value) || 0;
                                    }
                                }
                                fetchPricingAndRender(defaultLimit);
                            }
                        );
                    } else {
                        fetchPricingAndRender(parseInt(limit) || 0);
                    }
                });
            }
        }

        scope.$watch('actCtrl.addslot.product_type', function (newVal, oldVal) {
            const productType = newVal;

            if (productType === 'add devices/slots' || productType === 'free subscription') {
                const endpoint = (productType === 'add devices/slots') ? 'subscriber/not-assign-device' : 'only-assigned-device/records';
                requestFactory.post(
                    requestFactory.getUrl(endpoint),
                    { ...scope.actCtrl.defineProperties, subscriber_id: subscriberIdFromUrl },
                    function (response) {
                        if (response && response.data && Array.isArray(response.data.data)) {
                            const deviceRecords = response.data.data;
                            const activeDevices = deviceRecords.filter(device => String(device.status ?? device.is_active ?? '') === "1");

                            // console.log("Active Devices:", activeDevices);
                            rednerdevice(activeDevices);
                        } else if (response && Array.isArray(response.data)) {
                            const deviceRecords = response.data;
                            const activeDevices = deviceRecords.filter(device => String(device.status ?? device.is_active ?? '') === "1");
                            rednerdevice(activeDevices);
                        } else {
                            console.warn("🚫 Invalid device list response");
                            rednerdevice([]);
                        }
                    }
                );
            }
        });

        scope.updateSelectedDevices = function (device) {
            const actCtrl = scope.actCtrl;
            const addslot = actCtrl?.addslot || {};
            const limit = parseInt(actCtrl.active_device_limit) || 999;

            if (Array.isArray(addslot.devices)) {
                const selectedDevicesCount = addslot.devices.filter(d => d.selected).length;

                if (selectedDevicesCount > limit && device) {
                    device.selected = false;
                    requestFactory.setToaster('error', `You can only select up to ${limit} devices for this plan.`);
                    requestFactory.getToaster();
                    return;
                }

                scope.selectedDevices = addslot.devices.filter(d => d.selected);
                addslot.device_names = scope.selectedDevices.map(d => d.device_name || d.brand_model || 'Unnamed Device');

                if (addslot.product_type === 'add devices/slots') {
                    addslot.total = scope.selectedDevices.reduce((sum, d) => sum + (parseFloat(d.price) || 0), 0);
                } else if (addslot.total !== 'Free') {
                    // For other types, total is handled by onSubscriptionChange or calculation
                }
            } else {
                scope.selectedDevices = [];
                addslot.device_names = [];
                if (addslot.total !== 'Free') addslot.total = 0;
            }
        };

        this.fetchDevice();

        // ==============================***********************************==============================
        // add slot code (insert code)
        // ==============================***********************************==============================

        this.onPaymentServiceChange = function () {
            if (this.addslot.payment_service === 'authorizenet' || this.addslot.payment_service === 'authorize_manual') {
                if (this.creditCard && this.creditCard.length > 0) {
                    if (!this.addslot.selected_credit_card_id) {
                        this.addslot.selected_credit_card_id = this.creditCard[0].id;
                    }
                } else if (!this.addslot.cc_number) {
                    $('#authorizeNetModal').modal('show');
                }
            }
        };

        this.onAdjustLengthChange = function () {
            if (this.addslot.adjust_length) {
                this.updateEndDateFromAdjustment();
                $('#flipFlop').modal('show');
            }
        };

        this.updateEndDateFromAdjustment = function () {
            if (this.addslot.adjust_length_value && this.addslot.adjust_length_type) {
                scope.calculateSubscriptionDates(this.addslot.adjust_length_value, this.addslot.adjust_length_type);
            }
        };

        this.onSelectedCardChange = function () {
            if (this.addslot.selected_credit_card_id === 'new' && !this.addslot.cc_number) {
                $('#authorizeNetModal').modal('show');
            }
        };

        this.openAuthorizeNetModal = function ($event) {
            if ($event) $event.preventDefault();
            this.addslot.selected_credit_card_id = 'new';
            $('#authorizeNetModal').modal('show');
        };

        this.getMaskedCardSource = function () {
            if (this.addslot.cc_number) {
                const num = this.addslot.cc_number;
                if (num.length >= 4) {
                    return '**** **** **** ' + num.substr(num.length - 4);
                }
                return '**** **** **** ****';
            }
            return '';
        };

        this.saveslot = function ($event) {
            $event.preventDefault();

            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (!subscriberId) {
                console.error("❌ Subscriber ID not found in URL.");
                return;
            }

            const productType = this.addslot.product_type || '';
            const selectedDevices = (this.addslot.devices || []).filter(d => d.selected);
            const totalAmount = parseFloat(this.addslot.total || 0);
            let paymentService = this.addslot.payment_service || '';
            let isAutopay = 0;

            // Check if selected plan has autopay enabled
            const selectedId = this.addslot.subscription;
            const plans = this.subscriptionPlans || [];
            const selectedPlan = plans.find(p => String(p.id) === String(selectedId));

            if (selectedPlan && (selectedPlan.autopay == 1 || selectedPlan.is_autopay == 1)) {
                isAutopay = 1;
            }

            // Only use gateway for razorpay and non-free amounts. Autopay does not trigger gateway here.
            const useGateway = paymentService === 'razorpay' && totalAmount > 0;
            const razorpayKey = window.RAZORPAY_KEY || '';
            const razorpayAmount = Math.round(totalAmount * 100);
            const paymentCurrency = this.addslot.payment_currency || '';

            // 📅 Format today’s date as DD-MM-YYYY
            const today = new Date();
            const formattedDate = [
                ('0' + today.getDate()).slice(-2),
                ('0' + (today.getMonth() + 1)).slice(-2),
                today.getFullYear()
            ].join('-');

            // 📦 Prepare payload
            let payload = {};

            if (productType === 'add devices/slots') {
                payload = {
                    subscriber_id: subscriberId,
                    assigned_devices: selectedDevices.map(device => ({
                        device_id: device.id,
                        brand_model: device.brand_model,
                        price: device.price,
                    })),
                    product_type: productType,
                    payment_service: paymentService,
                    autopay: isAutopay,
                    device: selectedDevices.map(d => d.device_name || d.brand_model || 'Unnamed Device'),
                    cash_location: this.addslot.cash_location || '',
                    payment_currency: paymentCurrency,
                    total: totalAmount,
                };
            } else {
                payload = {
                    subscriber_id: subscriberId,
                    product_type: productType,
                    activation: this.addslot.activation || '',
                    subscription: this.addslot.subscription || '',
                    adjust_length: this.addslot.adjust_length || '',
                    device: selectedDevices.map(d => d.device_name || d.brand_model || 'Unnamed Device'),
                    length_type: this.addslot.length_type || '',
                    day_month_type: this.addslot.day_month_type || '',
                    start_date: formattedDate,
                    end_date: scope.actCtrl.dateDifferenceText || scope.actCtrl.addslot.durationText || '',
                    accessory: this.addslot.accessory || '',
                    custom_charge_comment: this.addslot.custom_charge_comment || '',
                    payment_service: paymentService,
                    autopay: isAutopay,
                    cash_location: this.addslot.cash_location || '',
                    payment_currency: paymentCurrency,
                    total: totalAmount,
                    terms_of_agreement: this.addslot.terms_of_agreement || '',
                    default_device_id: this.addslot.default_device_id || null
                };
            }

            if (paymentService === 'authorizenet' || paymentService === 'authorize_manual') {
                if (this.creditCard && this.creditCard.length > 0 && this.addslot.selected_credit_card_id !== 'new') {
                    const selectedCard = this.creditCard.find(c => c.id === this.addslot.selected_credit_card_id);
                    if (selectedCard) {
                        payload.credit_card_id = selectedCard.id;
                        payload.cc_number = selectedCard.card_number || '';
                        payload.cc_exp_month = selectedCard.expiration_month || '';
                        payload.cc_exp_year = selectedCard.expiration_year || '';
                        payload.cc_cvv = selectedCard.cvv || '';
                    }
                } else {
                    payload.cc_number = this.addslot.cc_number || '';
                    payload.cc_exp_month = this.addslot.cc_exp_month || '';
                    payload.cc_exp_year = this.addslot.cc_exp_year || '';
                    payload.cc_cvv = this.addslot.cc_cvv || '';
                }
            }

            const endpoint = productType === 'add devices/slots'
                ? 'subscriber/add/only-assigned-device'
                : 'subscriber/add/device-slot';

            const saveToServer = function (dataToSend) {
                requestFactory.post(
                    requestFactory.getUrl(endpoint),
                    dataToSend,
                    function (response) {

                        if (response && response.status === 'success') {
                            requestFactory.setToaster('success', 'Subscription add successfully.');
                            requestFactory.getToaster();

                            // Refresh the grid without page reload
                            scope.$broadcast('refreshGrid');

                            // Reset the form data
                            self.addslot = {
                                subscription: '',
                                product_type: '',
                                start_date: new Date(),
                                end_date: '',
                                activation_type: 'Override',
                                devices: [],
                                accessory: [],
                                bundles: [],
                                total: 0
                            };

                            // Close modal if open
                            angular.element('#flipFlop').modal('hide');
                        } else {
                            console.warn('⚠️ Server save failed:', response);
                        }
                    },
                    function (error) {
                        console.error('❌ Error while saving data:', error);
                    },
                    {
                        headers: { 'X-CSRF-TOKEN': window.csrfToken },


                        responseType: 'json'
                    }
                );
            };

            // 💳 If using Razorpay payment gateway
            if (useGateway) {
                if (typeof Razorpay === 'undefined') {
                    alert("⚠️ Payment gateway not loaded. Please refresh and try again.");
                    return;
                }

                const options = {
                    key: razorpayKey,
                    amount: razorpayAmount,
                    currency: "INR",
                    name: "ISG",
                    description: "IPTV Payment",
                    prefill: {
                        name: "ISG",
                        phone: "1234567890"
                    },
                    theme: {
                        color: "#0F408F"
                    },
                    handler: function (res) {
                        const paymentData = {
                            ...payload,
                            razorpay_payment_id: res.razorpay_payment_id,
                            amount: razorpayAmount,
                        };
                        saveToServer(paymentData);
                    },
                    modal: {
                        ondismiss: function () {
                            console.warn("❌ Payment was cancelled.");
                            sendCancelToServer();
                        }
                    }
                };

                const rzp = new Razorpay(options);

                rzp.on('payment.failed', function (response) {
                    const cancelPayload = {
                        ...payload,
                        razorpay_payment_id: response.error.metadata?.payment_id || '',
                        amount: razorpayAmount,
                        error: response.error.description || 'Unknown error',
                        reason: response.error.reason || 'Unknown reason'
                    }

                    requestFactory.post(
                        requestFactory.getUrl('subscriber/payment/cancel'),
                        cancelPayload,
                        function (apiResponse) {
                            if (apiResponse && apiResponse.success === true) {
                                // Redirect to a custom failure page
                                window.location.href = '/402';
                            } else {
                                console.warn('Payment failure not acknowledged properly:', apiResponse);
                                // setTimeout(function () {
                                //     window.location.reload();
                                // }, 150);
                            }
                        },
                        function (error) {
                            console.error("❌ Error reporting cancellation:", error);
                            // window.location.reload();
                            setTimeout(function () {
                                window.location.reload();
                            }, 350);
                        },
                        {
                            headers: { 'X-CSRF-TOKEN': window.csrfToken },
                            responseType: 'json'
                        }
                    );
                })
                rzp.open();
            } else {
                saveToServer(payload);
            }
        };


        // ==============================***********************************==============================
        // day calculater code for tabel
        // ==============================***********************************==============================

        scope.calculateDays = function (start, end) {
            if (!start || !end) return '';

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const startDate = new Date(start);
            const endDate = new Date(end);
            startDate.setHours(0, 0, 0, 0);
            endDate.setHours(0, 0, 0, 0);

            // If start and end are the same
            // if (startDate.getTime() === endDate.getTime()) {
            //     return 'Today';
            // }

            // If today is before start d
            // ate: waiting
            if (today < startDate) {
                return 'Waiting';
            }

            // If today is after end date: expired
            if (today > endDate) {
                return 'Subscription Expired';
            }

            // Else: calculate remaining days
            const timeDiff = endDate - today;
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            return daysDiff >= 0 ? daysDiff + ' Day' + (daysDiff !== 1 ? 's' : '') + ' Left' : 'Subscription Expired';
        };

        // plan is active or not active
        self.isExpired = function (endDateStr) {
            if (!endDateStr) return true;

            const endDate = new Date(endDateStr);
            const now = new Date();
            now.setHours(0, 0, 0, 0);

            return endDate < now;
        };


        scope.$on('afterGetRecords', function (e, data) {
            if (e.targetScope.searchRecords && angular.isUndefined(e.targetScope.searchRecords.is_active)) {
                e.targetScope.searchRecords.is_active = 'all';
            }
        })

        // day counter for table
        scope.actCtrl.getDurationInYearsMonthsDays = function (startDateStr, endDateStr) {
            // console.log('📥 getDurationInYearsMonthsDays called with:');
            // console.log('Start Date:', startDateStr);
            // console.log('End Date:', endDateStr);

            if (!startDateStr || !endDateStr) {
                console.warn('⚠️ Missing date input');
                return '';
            }

            const start = new Date(startDateStr);
            const end = new Date(endDateStr);

            if (isNaN(start) || isNaN(end)) {
                console.error('❌ Invalid date format', { start, end });
                return '';
            }

            // Normalize to start of day
            start.setHours(0, 0, 0, 0);
            end.setHours(0, 0, 0, 0);

            if (end < start) {
                console.warn('⚠️ End date is before start date');
                return 'Waiting';
            }

            const diffTime = end - start;
            const totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return totalDays > 0 ? totalDays + ' Days' : 'Today';
        };

        // count total device
        scope.actCtrl.getDeviceCount = function (device) {
            if (!device) return 0;
            if (Array.isArray(device)) return device.length;

            try {
                const arr = JSON.parse(device);
                if (Array.isArray(arr)) return arr.length;
            } catch (e) {
                // Fallback for comma-separated strings or single value strings
                if (typeof device === 'string' && device.trim().length > 0) {
                    return device.split(',').filter(item => item.trim() !== '').length;
                }
            }
            return 0;
        };

        // plan is active or not active
        scope.actCtrl.isExpired = function (endDateStr) {
            if (!endDateStr) return true;

            const endDate = new Date(endDateStr);
            const now = new Date();
            now.setHours(0, 0, 0, 0);

            return endDate < now;
        };

        // if product type is add devices/slots or custom subscription added
        scope.actCtrl.addedProducts = [
            { product_type: 'add devices/slots' },
            { product_type: 'custom subscription' },
            { product_type: 'free subscription' },
            { product_type: 'subscription sets' },
            { product_type: 'add-on subscription' },
            { product_type: 'add-on device' }
        ];


        scope.actCtrl.isAddDeviceSlotAlreadyAdded = function () {
            if (!Array.isArray(scope.actCtrl.addedProducts) || scope.actCtrl.addedProducts.length === 0) {
                return false;
            }

            return scope.actCtrl.addedProducts.some(function (item) {
                return item.product_type === 'add devices/slots';
            });
        };

        // ==============================***********************************==============================
        // add slot code (for day, month, etc)
        // ==============================***********************************==============================

        // custom subscriber
        scope.actCtrl.calculateDateOrMonthDifference = function () {
            // const selectedDate = scope.actCtrl.addslot.start_date;
            const selectedDateInput = document.getElementById("set_date");
            const zipInput = scope.actCtrl.addslot.start_date;

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            // console.log("📅 Today's date:", today.toDateString());
            // console.log("📥 Received start_date:", zipInput);

            let finalOutput = '';

            // set date filed code
            if (selectedDateInput && selectedDateInput.value) {
                const selected = new Date(selectedDateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                selected.setHours(0, 0, 0, 0);

                const dayDiff = Math.ceil((selected - today) / (1000 * 60 * 60 * 24));
                // console.log("📅 Selected date:", selected.toDateString());
                // console.log("📊 Day difference:", dayDiff);

                let finalOutput = "";

                if (dayDiff === 0) {
                    finalOutput = "Today";
                } else if (dayDiff === 1) {
                    finalOutput = "1 day";
                } else if (dayDiff > 1) {
                    finalOutput = `${dayDiff} days`;
                } else {
                    finalOutput = "Invalid date";
                }

                scope.actCtrl.dateDifferenceText = finalOutput;
                // console.log("✅ Final output (date mode):", finalOutput);
                return;
            }

            // days filed script code
            if (zipInput) {
                if (!isNaN(zipInput)) {
                    const days = parseInt(zipInput, 10);
                    if (days >= 0) {
                        const futureDate = new Date(today);
                        futureDate.setDate(today.getDate() + days);

                        const dd = ('0' + futureDate.getDate()).slice(-2);
                        const mm = ('0' + (futureDate.getMonth() + 1)).slice(-2);
                        const yyyy = futureDate.getFullYear();

                        const futureText = dd + '-' + mm + '-' + yyyy;
                        finalOutput = `${days} day${days > 1 ? 's' : ''} | ${futureText}`;

                        scope.actCtrl.dateDifferenceText = finalOutput;
                        // console.log("📆 Future date:", futureText);
                        // console.log("✅ Final output (days mode):", finalOutput);
                        return;
                    } else {
                        finalOutput = "Invalid number of days";
                    }
                }

                // month filed script code
                let year, month;

                if (typeof zipInput === 'string' && /^\d{4}-\d{2}$/.test(zipInput)) {
                    [year, month] = zipInput.split('-').map(Number);
                } else if (Object.prototype.toString.call(zipInput) === '[object Date]' && !isNaN(zipInput)) {
                    year = zipInput.getFullYear();
                    month = zipInput.getMonth() + 1;
                }

                if (year && month) {
                    const endDate = new Date(year, month - 1, today.getDate());
                    endDate.setHours(0, 0, 0, 0);

                    // console.log("📅 Target month same-day date:", endDate.toDateString());

                    const totalDaysDiff = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));

                    if (totalDaysDiff < 0) {
                        finalOutput = "Invalid month";
                    } else {
                        let resultText = `${totalDaysDiff} day${totalDaysDiff !== 1 ? 's' : ''}`;
                        if (totalDaysDiff <= 0) resultText = '0 days';

                        finalOutput = resultText;
                    }

                    scope.actCtrl.dateDifferenceText = finalOutput;
                    // console.log("📊 Total day difference:", totalDaysDiff);
                    // console.log("✅ Final output (month mode):", finalOutput);
                    return;
                }
            }

            // If nothing matched
            scope.actCtrl.dateDifferenceText = finalOutput || '';
            // console.warn("⚠️ No valid input processed.");
        };

        // subscriber set
        scope.$watchGroup([
            'actCtrl.addslot.start_date',
            'actCtrl.addslot.end_date'
        ], function (newValues) {
            // console.log('🔁 $watchGroup triggered:', newValues);
            if (newValues[0] && newValues[1]) {
                scope.actCtrl.updateDurationFromDates();
            }
        });

        scope.calculateSubscriptionDates = function (months, type) {
            // console.log('📅 calculateSubscriptionDates called with:', months, type);

            if (months === 'free') {
                // console.log('🎁 Setting free subscription');
                scope.actCtrl.addslot.start_date = null;
                scope.actCtrl.addslot.end_date = null;
                scope.actCtrl.addslot.durationText = 'Free';
                return;
            }

            const start = new Date();
            const end = new Date(start);

            let duration = parseInt(months);
            let activeType = type;

            if (isNaN(duration) || duration <= 0) {
                if (scope.actCtrl.orgSettings && scope.actCtrl.orgSettings.max_activation_length) {
                    duration = parseInt(scope.actCtrl.orgSettings.max_activation_length);
                    activeType = 'Days';
                    console.log('ℹ️ Plan length not provided, using organization max_activation_length:', duration);
                }
            }

            if (activeType === 'Days') {
                end.setDate(end.getDate() + (isNaN(duration) ? 0 : duration));
            } else {
                end.setMonth(end.getMonth() + (isNaN(duration) ? 0 : duration));
            }

            scope.actCtrl.addslot.start_date = start;
            scope.actCtrl.addslot.end_date = end;

            // console.log('✅ Calculated start_date:', start);
            // console.log('✅ Calculated end_date:', end);

            // Update readable duration
            // console.log('data send to the updateDurationFromDates.');            
            scope.actCtrl.updateDurationFromDates();
        };

        scope.actCtrl.updateDurationFromDates = function () {
            const start = new Date(scope.actCtrl.addslot.start_date);
            const end = new Date(scope.actCtrl.addslot.end_date);

            // console.log('🔍 updateDurationFromDates called');
            // console.log('📌 start_date:', start);
            // console.log('📌 end_date:', end);

            if (!start || !end || isNaN(start) || isNaN(end)) {
                console.warn('⚠️ Invalid dates provided');
                scope.actCtrl.addslot.durationText = '';
                return;
            }

            // Set to start of the day
            start.setHours(0, 0, 0, 0);
            end.setHours(0, 0, 0, 0);

            const diffTime = end - start;
            if (diffTime < 0) {
                console.error('❌ End date is before start date');
                scope.actCtrl.addslot.durationText = 'Invalid date range';
                return;
            }

            const totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            let result = `${totalDays} day${totalDays !== 1 ? 's' : ''}`;
            if (totalDays <= 0) result = '0 days';

            scope.actCtrl.addslot.durationText = result;
            // console.log('🕒 Final durationText:', scope.actCtrl.addslot.durationText);
        };

        // --- Watch for subscription records to update UI state ---
        scope.$watch(function () {
            return rootScope.subscriptionRecords;
        }, function (newVal) {
            if (scope.actCtrl) {
                scope.actCtrl.hasSubscriptionRecords = (Array.isArray(newVal) && newVal.length > 0);
                // console.log("📊 hasSubscriptionRecords updated from watch:", scope.actCtrl.hasSubscriptionRecords, newVal);
            }
        }, true);

        // Also catch the event from gridView.js when records are loaded
        scope.$on('afterGetRecords', function (event, response) {
            // console.log("🔄 records loaded event caught:", event.targetScope.recordsName);
            // If this is the subscription grid, force update the flag
            if (event.targetScope && event.targetScope.recordsName === 'subscriptionRecords') {
                $timeout(function () {
                    const records = event.targetScope.subscriptionRecords;
                    scope.actCtrl.hasSubscriptionRecords = (Array.isArray(records) && records.length > 0);
                    // console.log("📊 hasSubscriptionRecords forced update:", scope.actCtrl.hasSubscriptionRecords);
                });
            }
        });

        // ==============================***********************************==============================
        //                                       assigned device code
        // ==============================***********************************==============================
        this.fetchAssignedDevice = function () {
            self.fetchOnlyAssignedDevices((data) => {
                try {
                    rootScope.handleAssignedDevices(data);
                } catch (e) {
                    console.error("Error in subscriberdevice:", e);
                }
            });
        };

        rootScope.handleAssignedDevices = function (devices) {
            try {
                const currentUrl = window.location.href;
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

        self.fetchAssignedDevice();

        // ==============================***********************************==============================
        //                                       drag and drop code
        // ==============================***********************************==============================

        this.fetchchannel = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        ChanneldefineProperties(response.data.data);
                    } else {
                        console.error("❌ Invalid data format from channel:", response);
                    }
                }
            );
        };

        function ChanneldefineProperties(channelList) {
            const homeElement = document.getElementById("devicesoltadd");
            if (!homeElement) return;

            const localScope = angular.element(homeElement).scope();
            const targetId = document.getElementById("channel-id")?.value;
            if (localScope && localScope.actCtrl) {
                const updateModel = () => {
                    // Assign full list
                    localScope.actCtrl.channelList = channelList;
                    // console.log(localScope.actCtrl.channelList);


                    // Preselect if ID exists
                    // const matched = channelList.find(c => String(c.id) === String(targetId));

                    // if (matched) {
                    //     localScope.actCtrl.addslot = {
                    //         id: matched.id,
                    //         channel_list: matched.channel_list,
                    //         start_at: new Date(matched.start_at),
                    //         end_at: new Date(matched.end_at)
                    //     };
                    // }
                    // console.log(localScope.actCtrl.addslot);
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }
        this.fetchchannel();

        // === AngularJS Scope Setup ===
        scope.actCtrl.selectedBundles = [];

        scope.handleBundleDrop = function (bundle) {
            const ctrl = scope.actCtrl;
            ctrl.selectedBundles = ctrl.selectedBundles || [];

            const exists = ctrl.selectedBundles.some(b => b.id === bundle.id);
            if (!exists) {
                ctrl.selectedBundles.push(bundle);
                console.log("📥 Bundle Dropped:", bundle);
            }
        };

        scope.actCtrl.assignSelectedBundles = function () {
            const ctrl = scope.actCtrl;

            if (!ctrl.addslot) ctrl.addslot = {};
            ctrl.addslot.bundles = angular.copy(ctrl.selectedBundles || []);
            // console.log("✅ Assigned Bundles to addslot:", ctrl.addslot.bundles);

            $('#add-bundles').modal('hide');
        };

        scope.removeBundle = function (bundle) {
            const ctrl = scope.actCtrl;
            ctrl.addslot.bundles = (ctrl.addslot.bundles || []).filter(b => b.id !== bundle.id);
            // console.log("🗑️ Removed Bundle:", bundle);

            const exists = ctrl.channelList.some(b => b.id === bundle.id);
            if (!exists) {
                ctrl.channelList.push(bundle);
                // console.log("🔁 Returned to channelList:", bundle);
            }

            scope.$applyAsync();
        };

        // === Modal Initialization ===
        $('#add-bundles').on('shown.bs.modal', function () {
            initializeBundleDragDrop();
        });

        // === Drag & Drop Logic ===
        function initializeBundleDragDrop() {
            const addedBundles = document.getElementById('addedBundles');
            const availableBundles = document.getElementById('availableBundles');
            addedBundles.innerHTML = '';

            // Drag events
            document.querySelectorAll('#availableBundles .bundle-card').forEach(card => {
                card.addEventListener('dragstart', e => {
                    e.dataTransfer.setData('text/plain', card.getAttribute('data-id'));
                    e.target.classList.add('dragging');
                });
                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                });
            });

            // Drop zone
            addedBundles.addEventListener('dragover', e => e.preventDefault());

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                // console.log("📤 Dropped Bundle ID:", draggedId);

                const card = availableBundles.querySelector(`[data-id="${draggedId}"]`);
                if (!card || addedBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn("❌ Invalid or duplicate drop:", draggedId);
                    return;
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedBundles.removeChild(clone);
                    availableBundles.appendChild(card);
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                addedBundles.appendChild(clone);
                card.remove();
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const ctrl = scope.actCtrl;
                ctrl.selectedBundles = [];
                addedBundles.querySelectorAll('.bundle-card').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.actCtrl.channelList.find(b => b.id === id);
                    if (bundle) ctrl.selectedBundles.push(bundle);
                });

                // console.log("📦 Updated Selected Bundles:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.bundle-card');
                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }

        // === Validation: Prevent checkbox if no bundle selected ===
        scope.checkBundleWarning = function (event) {
            const bundles = scope.actCtrl.addslot.bundles || [];

            if (bundles.length === 0) {
                event.preventDefault();
                const checkbox = document.getElementById('adjust-length-checkbox');

                checkbox.setAttribute('title', '⚠️ No bundles available. Please add bundles first.');
                checkbox.disabled = true;

                $(checkbox).tooltip('destroy')
                    .tooltip({ trigger: 'manual' })
                    .tooltip('show');

                setTimeout(() => {
                    $(checkbox).tooltip('hide');
                }, 1200000);

                console.warn("⚠️ Cannot proceed. No bundles selected.");
            } else {
                document.getElementById('adjust-length-checkbox').disabled = false;
            }
        };

        // === Select All Checkbox Logic ===
        scope.toggleAllBundles = function () {
            const bundles = scope.actCtrl.addslot.bundles || [];
            const selectAll = scope.actCtrl.selectAllBundles;

            bundles.forEach(bundle => {
                bundle.selected = selectAll;
            });
            // console.log("🔘 Toggle All Bundles:", selectAll);
        };

        scope.$watch('actCtrl.addslot.bundles', function (newVal) {
            if (!newVal) return;

            scope.actCtrl.addslot.bundles.forEach(bundle => {
                bundle.start_at = new Date(bundle.start_at);
                bundle.end_at = new Date(bundle.end_at);
            });

            const allSelected = newVal.length && newVal.every(bundle => bundle.selected);
            scope.actCtrl.selectAllBundles = allSelected;
            // console.log("👀 Bundles Watch Triggered:", newVal);

        }, true);

        this.resetCustomLengthData = function () {
            this.addslot.custom_length_input = '';
            this.addslot.total = 0;
            scope.actCtrl.dateDifferenceText = '';
            this.addslot.durationText = '';
            this.calculateCustomSubscriptionPrice();
        };

        this.DayMonthClick = function () {
            this.resetCustomLengthData();
        };

        this.CelnderClick = function () {
            this.resetCustomLengthData();
        };

        this.addDevice = function () {
            console.log("addDevice called");
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const subscriberIdFromUrl = urlObj.searchParams.get('subscriber-id');

            window.location.href = `${appUrl}admin/subscribers/devices?subscriber-id=${subscriberIdFromUrl}`
        }

        /**
         * Set a device as primary for the subscriber.
         * 
         * @param {Object} record
         */
        this.openPrimaryModal = function (record) {
            this.selectedDevice = record;
            this.primaryConfirmBox = true;

            $('#setPrimaryModal').modal('show');
        };

        this.cancelPrimary = function () {
            this.primaryConfirmBox = false;
            $('#setPrimaryModal').modal('hide');
        }

        this.confirmPrimary = function () {

            this.setPrimary(this.selectedDevice);

            this.primaryConfirmBox = false;
            $('#setPrimaryModal').modal('hide');
        }

        this.setPrimary = function (record) {
            // console.log("record", record);

            const payload = {
                id: record.id,
                subscriber_id: record.subscriber_id,
                device_id: record.device_id
            };

            requestFactory.post(
                requestFactory.getUrl('subscriber/set-primary-device'),
                payload,
                (response) => {
                    if (response && response.status === 'success') {
                        requestFactory.setToaster('success', 'Device set as primary successfully.');
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 350);
                        scope.$broadcast('refreshGrid');
                    }
                }
            );
        };
        // this.setPrimary();

        /**
         * Open the unlink device confirmation modal.
         * 
         * @param {Object} record
         */
        this.openUnlinkModal = function (record) {
            this.selectedDevice = record;
            this.unlinkConfirmBox = true;
            $('#unlinkSlotModal').modal('show');
        };

        /**
         * Cancel unlinking a device.
         */
        this.cancelUnlink = function () {
            this.unlinkConfirmBox = false;
            $('#unlinkSlotModal').modal('hide');
        };

        /**
         * Confirm unlinking a device.
         */
        this.confirmUnlink = function () {
            this.unlinkSlot(this.selectedDevice);
            this.unlinkConfirmBox = false;
            $('#unlinkSlotModal').modal('hide');
        };

        /**
         * Unlink a device from a slot.
         * 
         * @param {Object} record
         */
        this.unlinkSlot = function (record) {

            const payload = {
                id: record.id,
                subscriber_id: record.subscriber_id,
                device_id: record.device_id
            };

            requestFactory.post(
                requestFactory.getUrl('subscriber/delete-slot'),
                payload,
                (response) => {
                    if (response && response.status === 'success') {
                        requestFactory.setToaster('success', 'Device unlinked successfully.');
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                        scope.$broadcast('refreshGrid');
                    }
                }
            );
        };

        /**
         * Link a device to a slot.
         * 
         * @param {Object} record
         */
        this.linkSlot = function (record) {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const subscriberId = urlObj.searchParams.get('subscriber-id');
            // Redirect to devices page to select a device for this slot
            window.location.href = `${appUrl}admin/subscribers/devices?subscriber-id=${subscriberId}&link-slot=${record.id}`;
        };

        /**
         * Open the delete slot confirmation modal.
         * 
         * @param {Object} record
         */
        this.openDeleteSlotModal = function (record) {
            this.selectedDevice = record;
            this.deleteSlotConfirmBox = true;
            $('#deleteSlotModal').modal('show');
        };

        /**
         * Cancel deleting a slot.
         */
        this.cancelDeleteSlot = function () {
            this.deleteSlotConfirmBox = false;
            $('#deleteSlotModal').modal('hide');
        };

        /**
         * Confirm deleting a slot.
         */
        this.confirmDeleteSlot = function () {
            this.deleteSlot(this.selectedDevice);
            this.deleteSlotConfirmBox = false;
            $('#deleteSlotModal').modal('hide');
        };

        /**
         * Delete a slot entirely.
         * 
         * @param {Object} record
         */
        this.deleteSlot = function (record) {

            const payload = {
                id: record.id,
                subscriber_id: record.subscriber_id,
            };

            requestFactory.post(
                requestFactory.getUrl('subscriber/delete-slot'),
                payload,
                (response) => {
                    if (response && response.status === 'success') {
                        requestFactory.setToaster('success', 'Slot deleted successfully.');
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 350);
                        scope.$broadcast('refreshGrid');
                    }
                }
            );
        };


        // ==============================***********************************==============================
        // fetch default payment service
        // ==============================***********************************==============================

        this.fetchDefaultPaymentService = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && response.data.data && Array.isArray(response.data.data)) {
                        this.defaultPaymentService = response.data.data;


                        const currentUrl = window.location.href;
                        const urlObj = new URL(currentUrl);
                        const subscriberId = Number(urlObj.searchParams.get('subscriber-id'));

                        let matchedServices = [];
                        let systemDefaultServices = [];

                        this.defaultPaymentService.forEach(service => {
                            let matchedOrg = false;

                            if (Array.isArray(service.organization_default)) {
                                service.organization_default.forEach(org => {
                                    if (parseInt(org.default) === 1) {
                                        if (Array.isArray(org.subscribers)) {
                                            org.subscribers.forEach(sub => {
                                                if (Number(sub.id) === subscriberId) {
                                                    matchedOrg = true;
                                                }
                                            });
                                        }
                                    }
                                });
                            }

                            if (matchedOrg) {
                                matchedServices.push(service);
                            }

                            // Check both root 'default' and 'system_default' array for system-wide defaults
                            let isSystemDefault = (parseInt(service.default) === 1);

                            if (!isSystemDefault && Array.isArray(service.system_default) && service.system_default.length > 0) {
                                isSystemDefault = service.system_default.some(sys => parseInt(sys.payment_service_system_default) !== 0);
                            }

                            if (isSystemDefault) {
                                systemDefaultServices.push(service);
                            }
                        });

                        let servicesToShow = [];

                        // Combine matched organization services with system-wide defaults
                        const combinedList = [...matchedServices, ...systemDefaultServices];

                        // Ensure uniqueness based on service 'id'
                        servicesToShow = combinedList.filter((service, index, self) =>
                            index === self.findIndex((s) => s.id === service.id)
                        );

                        // Fallback to all services if none are identified as matches or defaults
                        if (servicesToShow.length === 0) {
                            servicesToShow = this.defaultPaymentService;
                        }

                        this.availablePaymentServices = [];
                        servicesToShow.forEach(s => {
                            let label = s.payment_provider;

                            if (label === 'External Payments' && s.provider_data) {
                                if (s.provider_data.authorize_manual && s.provider_data.authorize_manual !== 'false' && s.provider_data.authorize_manual !== '0') {
                                    this.availablePaymentServices.push({ label: 'Authorize.Net Manual', value: 'authorize_manual' });
                                }
                                if (s.provider_data.MoneyGram && s.provider_data.MoneyGram !== 'false' && s.provider_data.MoneyGram !== '0') {
                                    this.availablePaymentServices.push({ label: 'MoneyGram', value: 'moneygram' });
                                }
                                if (s.provider_data.PayPal && s.provider_data.PayPal !== 'false' && s.provider_data.PayPal !== '0') {
                                    this.availablePaymentServices.push({ label: 'PayPal Express', value: 'paypal' });
                                }
                                if (s.provider_data.Western && s.provider_data.Western !== 'false' && s.provider_data.Western !== '0') {
                                    this.availablePaymentServices.push({ label: 'Western Union', value: 'western_union' });
                                }
                            } else {
                                let value = '';
                                if (label.toLowerCase() === 'cash') value = 'cash';
                                else if (label.toLowerCase() === 'authorize.net') value = 'authorizenet';
                                else if (label.toLowerCase() === 'razorpay') value = 'razorpay';
                                else if (label.toLowerCase() === 'credit card') value = 'credit card';
                                else if (label.toLowerCase() === 'autopay') value = 'autopay';
                                else value = label.toLowerCase().replace(/[^a-z0-9]/g, '');

                                this.availablePaymentServices.push({ label: label, value: value });
                            }
                        });
                    }
                },
            );
        }
        self.fetchDefaultPaymentService();

        // credit card fetch
        this.fetchCreditCard = function () {
            requestFactory.post(
                requestFactory.getUrl('subscriber/credit-card/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && response.data.data && Array.isArray(response.data.data)) {
                        const allCards = response.data.data;
                        const urlParams = new URLSearchParams(window.location.search);
                        const subscriberId = urlParams.get('subscriber-id');

                        if (subscriberId) {
                            // Filter specifically for the subscriber's active cards
                            this.creditCard = allCards.filter(c => String(c.subscriber_id) === String(subscriberId) && Number(c.is_active) === 1);
                        } else {
                            this.creditCard = [];
                        }
                    } else {
                        this.creditCard = [];
                    }
                },
            );
        }
        self.fetchCreditCard();

        this.OrganizationDeviceRecords = function () {
            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (!subscriberId) {
                console.warn("Subscriber ID not found in URL.");
                return;
            }

            self.fetchSubscriberRecords(subscriberId, (subscriberList) => {
                const subscriber = subscriberList.find(c => String(c.id) === String(subscriberId));

                if (subscriber) {
                    if (subscriber.fetch_organization) {
                        this.orgSettings = subscriber.fetch_organization;
                        if (Array.isArray(subscriber.fetch_organization.org_mon_plan)) {
                            this.subscriptionPlans = subscriber.fetch_organization.org_mon_plan.filter(plan => plan.is_active == 1);
                        }
                    }
                    this.fetchDevicesAndFillSlots = (limit) => {
                        self.fetchOnlyAssignedDevices((data) => {
                            const assignedDevices = data.filter(d => String(d.subscriber_id) === String(subscriberId));

                            let displayList = [];
                            const totalSlots = parseInt(limit) || 0;

                            assignedDevices.forEach(d => {
                                displayList.push(d);
                            });

                            this.deviceRecords = displayList;

                            if (scope.$applyAsync) {
                                scope.$applyAsync();
                            }
                        });
                    };

                    let limit = subscriber.device_activation_limit;
                    if (limit === null || limit === undefined) {
                        if (subscriber.fetch_organization && subscriber.fetch_organization.device_activation_limit !== null && subscriber.fetch_organization.device_activation_limit !== undefined) {
                            limit = subscriber.fetch_organization.device_activation_limit;
                        }
                    }

                    if (limit === null || limit === undefined) {
                        requestFactory.post(
                            requestFactory.getUrl('general-settings/get-records'),
                            this.defineProperties,
                            (settingResponse) => {
                                let defaultLimit = 0;
                                if (settingResponse && Array.isArray(settingResponse)) {
                                    const setting = settingResponse.find(item => item.category === 'payment_setting' && item.key === 'device_activation_limit');
                                    if (setting) {
                                        defaultLimit = setting.value;
                                    }
                                }
                                this.original_device_limit = defaultLimit;
                                this.fetchDevicesAndFillSlots(defaultLimit);
                            }
                        );
                    } else {
                        this.original_device_limit = limit;
                        this.fetchDevicesAndFillSlots(limit);
                    }
                }
            });
        }
        self.OrganizationDeviceRecords();

        // --- REAL-TIME AUTO-LOAD (SSE Implementation) ---
        var eventSource = null;

        this.startSSE = function () {
            var token = localStorage.getItem('access_token');
            var subscriberId = scope.subscriberIdFromUrl;

            if (!subscriberId) {
                // console.warn("🚫 SSE: Subscriber ID not found, skipping stream.");
                return;
            }

            var streamUrl = window.VPlay.route.apiUrl + 'api/v3/stream?token=' + token + '&subscriber_id=' + subscriberId;
            // console.log("🔗 SSE: Attempting connection to", streamUrl);

            if (eventSource) eventSource.close();
            eventSource = new EventSource(streamUrl);

            // Listen for unnamed events
            eventSource.onmessage = function (event) {
                // console.log("📥 SSE: Message received", event.data);
            };

            // Listen for specific device updates
            eventSource.addEventListener('deviceUpdate', function (event) {
                // console.log("✨ SSE: deviceUpdate event received! Reloading table...");
                $timeout(function () {
                    // Clear the data cache to force a fresh API call for devices
                    self.assignedDevicesCache = null;

                    // Refresh manual table data by re-fetching
                    if (typeof self.fetchAssignedDevice === 'function') {
                        self.fetchAssignedDevice();
                    }

                    // Refresh the gridView directive using broadcasting
                    scope.$broadcast('refreshGrid');

                    // Refresh general device record state/limits
                    if (typeof self.OrganizationDeviceRecords === 'function') {
                        self.OrganizationDeviceRecords();
                    }
                }, 100);
            });

            // Heartbeat pings from server
            eventSource.addEventListener('ping', function (event) {
                // console.log("💓 SSE: Heartbeat received", event.data);
            });

            eventSource.onopen = function () {
                // console.log("✅ SSE: Connection established and open.");
            };

            eventSource.onerror = function (err) {
                console.error('❌ SSE: connection error or interrupted.', err);
                eventSource.close();
                // Attempt to retry after 5 seconds
                $timeout(function () {
                    self.startSSE();
                }, 5000);
            };
        };

        // Initialize connection
        this.startSSE();

        scope.$on('$destroy', function () {
            if (eventSource) {
                console.log("🛑 SSE: Closing connection on scope destroy.");
                eventSource.close();
            }
        });
    }
];

window.gridControllers = {
    ActivationController: ActivationController
};