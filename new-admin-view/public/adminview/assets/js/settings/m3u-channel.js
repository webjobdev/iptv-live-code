
var M3UChannelController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.setting = {};
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
            requestFactory.get(requestFactory.getUrl('setting/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ===========================================*******************************************======================================
        //                                                      subscriber setting code
        // ===========================================*******************************************======================================

        this.addSett = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.setting = {};
            $("#subscriptionForm").css('display', 'block');
            $("#subscriptionTranslationForm").css('display', "none");
        }

        this.editsettingdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.setting.id = records.id;
            this.setting.m3u_url = records.m3u_url;
            // this.setting.is_active = (records.is_active) ? true : false;
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.setting = {};
            });
        }

        this.save = function ($event, id) {
            $event.preventDefault();

            const payload = {
                m3u_url: this.setting.m3u_url || '',
            }
            console.log(payload);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('m3u-channel/edit/' + id),
                    payload, function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        this.closeDeviceEdit();
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('m3u-channel/create'),
                    payload, function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        this.closeDeviceEdit();
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    }, this.fillError
                );
            }
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        })
    }];


window.gridControllers = { M3UChannelController: M3UChannelController };