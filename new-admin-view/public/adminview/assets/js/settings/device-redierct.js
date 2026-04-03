var DeviceredirectController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.adddr = {};
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

        // ===========================================*******************************************======================================
        //                                                      open page code
        // ===========================================*******************************************======================================
        this.adddere = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.adddr = {};
            $("#adddrForm").css('display', 'block');
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.adddr.id = records.id;
            this.adddr.name = records.name;
            this.adddr.url = records.url;
            this.adddr.is_active = (records.is_active) ? true : false;
            $("#adddrForm").css('display', 'block');
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.adddr = {};
            });
        }

        this.closeadddrEdit = function () {
            const ClearForm = document.getElementById('adddrForm');
            ClearForm.reset();
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================

        this.save = function ($event, id) {
            console.log(this.adddr);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('setting/device-redirect/edit/' + id),
                    this.adddr,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 650);
                    }, this.fillErrors
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('setting/device-redirect/create'),
                    this.adddr,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 650);
                    }, this.fillErrors
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
    DeviceredirectController: DeviceredirectController
};