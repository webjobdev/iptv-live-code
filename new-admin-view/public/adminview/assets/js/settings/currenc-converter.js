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
            requestFactory.get(requestFactory.getUrl('payment-service/info'),
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

        // ===========================================*******************************************======================================
        //                                                      open page code
        // ===========================================*******************************************======================================
        this.addCurr = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.converter = {};
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
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.converter = {};
            });
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================

        this.save = function ($event, id) {
            console.log(this.converter);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('payment-service/currency-converter/edit/' + id),
                    this.converter,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 650);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('payment-service/currency-converter/create'),
                    this.converter,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 650);
                    }, this.fillError
                );
            }
        };

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