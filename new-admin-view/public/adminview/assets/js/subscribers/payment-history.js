var PaymentHistoryController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.payment = {}
        scope.errors = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/payment-history/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.opencomment = function () {
            this.payment.id = {};
            this.payment.comment = {};
            this.payment.subscription_and_payments_id = {};
            $('.sidepanel').addClass('open');
        }

        this.savecomment = function () {
            const urlParams = new URLSearchParams(window.location.search);
            const SubscriberInput = urlParams.get('subscriber-id');

            if (!SubscriberInput) {
                console.warn("Subscriber id not found.");
                return;
            }

            const payload = {
                subscriber_id: SubscriberInput,
                subscription_and_payments_id: this.payment.subscription_and_payments_id,
                comment: this.payment.comment,
            };

            requestFactory.post(
                requestFactory.getUrl('payment/comment/add'),
                payload,
                function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closeSidePanel();
                    setTimeout(() => {
                        window.location.reload();
                    }, 600);
                },
                this.fillError
            );
        };

        this.updatesubscription = function ($event) {
            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (!subscriberId) {
                console.warn("Subscriber ID not found in URL.");
                return;
            }

            const formatDateTime = (date) => {
                if (!date) return null;
                const d = new Date(date);

                // Get current time
                const now = new Date();
                d.setHours(now.getHours());
                d.setMinutes(now.getMinutes());
                d.setSeconds(now.getSeconds());

                return d.getFullYear() + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    String(d.getDate()).padStart(2, '0') + ' ' +
                    String(d.getHours()).padStart(2, '0') + ':' +
                    String(d.getMinutes()).padStart(2, '0') + ':' +
                    String(d.getSeconds()).padStart(2, '0');
            };


            const payload = {
                id: this.payment.id,
                subscriber_id: subscriberId,
                start_date: formatDateTime(this.payment.start_date),
                end_date: formatDateTime(this.payment.end_date),
                length_type: this.payment.length_type,
            };


            console.log('Final payload:', payload);

            requestFactory.post(
                requestFactory.getUrl('subscriber/add/device-slot'),
                payload,
                (response) => {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();

                    // Optionally reload page after update
                    // setTimeout(() => window.location.reload(), 600);
                },
                this.fillError
            );
        };

        // ==============================***********************************==============================
        // scope code
        // ==============================***********************************==============================

        // day calculater code for tabel
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

            // If today is outside the range
            if (today < startDate || today > endDate) {
                // return '0 Day';
                return 'Subscription Expired';
            }

            const timeDiff = endDate - today;
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            return daysDiff > 0 ? daysDiff + ' Days' : 'Subscription Expired';
        };

        this.fetchAssignedDevice = function () {
            requestFactory.post(
                requestFactory.getUrl('subscriber/payment-history/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        try {
                            rootScope.handleAssignedDevices(response.data.data);
                            // scope.pysCtrl.downloadPdf(response.data.data);
                        } catch (e) {
                            console.error("Error in subscriberdevice:", e);
                        }
                    } else {
                        console.warn("Invalid data format from DRM:", response);
                    }
                }
            );
        };

        this.adjustlength = function (payment) {
            const homeElement = document.getElementById("flipFlop");
            if (!homeElement) return;

            const localScope = angular.element(homeElement).scope();

            if (payment) {
                if (localScope && localScope.pysCtrl) {
                    const updateModel = () => {
                        localScope.pysCtrl.payment = {
                            id: payment.id,
                            start_date: new Date(payment.start_date) || 'you are not eligible',
                            subscription: payment.subscription || 'you are not eligible',
                            end_date: new Date(payment.end_date) || 'you are not eligible',
                        };
                        // console.log('abcd', localScope.pysCtrl.payment);

                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No data found for this payment`);
            }

            // console.log(payment);
        };

        rootScope.handleAssignedDevices = function (payments) {
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

                // console.log("All payment:", payment);

                const filteredpayment = payments.filter(payments =>
                    Number(payments.subscriber_id) === Number(subscriberIdFromUrl)
                );

                // console.log("Filtered payments for subscriber ID " + subscriberIdFromUrl + ":", filteredpayments);
                // console.log("Filtered payments count:", filteredpayments.length);

                rootScope.records = filteredpayment;
                rootScope.subscriberIdFromUrl = subscriberIdFromUrl;

                // console.log("rootScope.records after filter:", rootScope.records);
            } catch (error) {
                console.error("Error in handlePaymentHistory:", error);
            }

            // const homeElement = document.getElementById("flipFlop");
            // if (!homeElement) {
            //     return;
            // }

            // const localScope = angular.element(homeElement).scope();
            // const currentUrl = window.location.href;
            // console.log("Current URL:", currentUrl);
            // const urlObj = new URL(currentUrl);
            // const subscriberIdFromUrl = urlObj.searchParams.get('subscriber-id');
            // console.log("Extracted subscriber ID from URL:", subscriberIdFromUrl);

            // if (!subscriberIdFromUrl) {
            //     console.warn("Subscriber ID not found in URL.");
            //     rootScope.records = [];
            //     return;
            // }
            // console.log("All payments:", payments);
            // const filteredPayments = payments.filter(payment =>
            // Number(payment.subscriber_id) === Number(subscriberIdFromUrl)
            // );
            // console.log("Filtered payments for subscriber ID " + subscriberIdFromUrl + ":", filteredPayments);
            // console.log("Filtered payments count:", filteredPayments.length);
            // rootScope.records = filteredPayments;
            // rootScope.subscriberIdFromUrl = subscriberIdFromUrl;
            // console.log("rootScope.records after filter:", rootScope.records);

            // const length = payments.find(l => String(l.subscriber_id) === String(subscriberIdFromUrl));

            // if (length) {
            //     if (localScope && localScope.pysCtrl) {
            //         const updateModel = () => {
            //             localScope.pysCtrl.payment = {
            //                 start_date: new Date(length.start_date),
            //                 subscription: length.subscription,
            //                 end_date: new Date(length.end_date),
            //             }
            //         };

            //         if (!localScope.$$phase) {
            //             localScope.$apply(updateModel);
            //         } else {
            //             updateModel();
            //         }
            //     }
            // } else {
            //     console.warn(`No data found with this id:${subscriberIdFromUrl}`);
            // }
        };

        this.setSign = function (sign) {
            var current = this.payment.days || '';
            current = String(current).replace(/^[-+]/, '');
            this.payment.days = sign;
            this.daycount();
        };

        scope.pysCtrl.daycount = function () {
            let daysInput = scope.pysCtrl.payment.days;
            const startDate = new Date(scope.pysCtrl.payment.start_date);
            const existingEndDate = scope.pysCtrl.payment.end_date
                ? new Date(scope.pysCtrl.payment.end_date)
                : null;

            if (daysInput) {
                let sign = 1;
                if (typeof daysInput === 'string' && daysInput.startsWith('-')) {
                    sign = -1;
                    daysInput = daysInput.slice(1);
                } else if (typeof daysInput === 'string' && daysInput.startsWith('+')) {
                    daysInput = daysInput.slice(1);
                }

                if (!isNaN(daysInput)) {
                    const days = parseInt(daysInput, 10);
                    if (days >= 0) {
                        const baseDate = existingEndDate || startDate;
                        const newEndDate = new Date(baseDate);
                        newEndDate.setDate(baseDate.getDate() + (sign * days));

                        // ✅ Check if newEndDate goes before startDate
                        if (newEndDate < startDate) {
                            scope.pysCtrl.payment.formatted_end_date = "❌ Invalid: date before start date";
                            console.error("❌ Calculated end date is before start_date!");
                            return;
                        }

                        scope.pysCtrl.payment.end_date = newEndDate;

                        const dd = ('0' + newEndDate.getDate()).slice(-2);
                        const mm = ('0' + (newEndDate.getMonth() + 1)).slice(-2);
                        const yyyy = newEndDate.getFullYear();

                        scope.pysCtrl.payment.formatted_end_date = `${dd}-${mm}-${yyyy}`;

                        // console.log("📦 Base date:", baseDate);
                        // console.log("+/- Sign:", sign);
                        // console.log("🗓 User entered days:", days);
                        // console.log("✅ New end_date as Date:", newEndDate);
                        // console.log("📄 New formatted_end_date:", scope.pysCtrl.payment.formatted_end_date);
                    } else {
                        scope.pysCtrl.payment.formatted_end_date = "Invalid number of days";
                    }
                } else {
                    scope.pysCtrl.payment.formatted_end_date = "Invalid input";
                }
            } else {
                scope.pysCtrl.payment.formatted_end_date = "";
            }
        };

        scope.pysCtrl.voidpmt = function (record) {
            if (!record || !record.transaction_detail) {
                console.error("Transaction detail missing in record");
                return;
            }

            const paymentId = record.transaction_detail.payment_id;
            const amount = record.transaction_detail.amount;
            const subscriberId = record.id;

            if (!paymentId) {
                console.warn("Payment ID not found in record");
                return;
            }

            if (confirm("Are you sure you want to refund this payment?")) {
                const payload = {
                    payment_id: paymentId,
                    amount: amount * 100,
                    subscriber_id: subscriberId
                };

                requestFactory.post(
                    requestFactory.getUrl('subscriber/payment/refund'),
                    payload,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        scope.getRecords(true);
                    },
                    function (error) {
                        console.error("Refund failed:", error);
                        requestFactory.setToaster('error', "Refund failed");
                        requestFactory.getToaster();
                    }
                );
            }
        };

        this.closeSidePanel = function () {
            scope.gridSideFormClose();
        };
        // ==============================***********************************==============================
        // download pdf code
        // ==============================***********************************==============================

        scope.pysCtrl.downloadPdf = function (data) {
            // console.log('downloadpdf function is called with data:', data);

            if (!data) {
                console.error("record id is missing");
                return;
            }
            // window.location.href = '/subscriber/download-pdf/' + data;
            const pdfurl = `${appUrl}admin/subscriber/download-pdf/${data}`;
            console.log('pdf url call:', pdfurl);
            window.open(pdfurl);
        }

        this.fetchAssignedDevice();
    }
];

window.gridControllers = {
    PaymentHistoryController: PaymentHistoryController
};