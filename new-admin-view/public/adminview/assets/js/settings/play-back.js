var PlayBackController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.pbt = {};
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
        this.addPBT = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.pbt = {};
            $("#pbtForm").css('display', 'block');
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.pbt.id = records.id;
            this.pbt.name = records.name;
            this.pbt.type = records.type;
            this.pbt.secret_key = records.secret_key;
            this.pbt.token_time = records.token_time;
            this.pbt.secret_generation_number = records.secret_generation_number;
            this.pbt.ignore_device_ip_verification = records.ignore_device_ip_verification;
            this.pbt.rsa_private_key = records.rsa_private_key;
            this.pbt.url_format = records.url_format;
            this.pbt.is_active = (records.is_active) ? true : false;
            $("#pbtForm").css('display', 'block');
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.pbt = {};
            });
        }

        this.closeadddrEdit = function () {
            const ClearForm = document.getElementById('pbtForm');
            ClearForm.reset();
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        this.save = function ($event, id) {
            console.log(this.pbt);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('setting/play-back-token/edit/' + id),
                    this.pbt,
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
                    requestFactory.getUrl('setting/play-back-token/create'),
                    this.pbt,
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

        scope.togglePublishNow = function (record, id) {
            const toggleID = id;
            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            requestFactory.post(
                requestFactory.getUrl('setting/play-back-token/toggle/edit/' + toggleID),
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
    PlayBackController: PlayBackController
};