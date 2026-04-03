var ConverterController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.converter = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.closeDeviceEdit = function () {
            scope.gridSideFormClose();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('organization/payment-service/currency-converter/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ===========================================*******************************************======================================
        //                                                      open page code
        // ===========================================*******************************************======================================
        this.addCurr = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.converter = {};
            $("#CurrencyForm").css('display', 'block');
        }

        this.editconverterdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.converter.id = records.id;
            this.converter.token = records.token;
            this.converter.refresh_rate_mode = records.refresh_rate_mode;
            this.converter.refresh_rate = records.refresh_rate;
            this.converter.refresh_rate_unit = records.refresh_rate_unit;
            $("#CurrencyForm").css('display', 'block');
            // $("#subscriptionTranslationForm").css('display', "none");
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        scope.sysdft = function (record, id) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const queryId = urlParams.get('id');

            const converterId = id;

            const payload = {
                currency_converter_system_default: converterId
            };

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/currency-converter/sysdft/edit/' + queryId),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 650);
                }
            );
        }

        scope.togglePublishNow = function (record, id) {
            const toggleID = id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/currency-converter/toggle/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 650);
                }
            );
        }
        // ===========================================*******************************************======================================
        //                                                      fetch data code
        // ===========================================*******************************************======================================

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

    }
];


window.gridControllers = {
    ConverterController: ConverterController
};