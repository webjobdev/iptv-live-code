
var SettingController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
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
            this.setting.product_type = records.product_type;
            this.setting.days = records.days;
            this.setting.accessories_name = records.accessories_name;
            this.setting.device_type = records.device_type;
            this.setting.month_type = records.month_type;
            this.setting.price = records.price;
            this.setting.is_active = (records.is_active) ? true : false;
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.setting = {};
            });
        }

        this.save = function ($event, id) {
            $event.preventDefault();

            const payload = {
                product_type: this.setting.product_type || '',
                days: this.setting.days || '',
                accessories_name: this.setting.accessories_name || '',
                device_type: this.setting.device_type || '',
                month_type: this.setting.month_type || '',
                price: this.setting.price || '',
                is_active: this.setting.is_active || '',
            }
            console.log(payload);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('subacriber/setting/edit/' + id),
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
                    requestFactory.getUrl('subacriber/setting/add'),
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


window.gridControllers = { SettingController: SettingController };