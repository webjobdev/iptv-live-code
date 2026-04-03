'use strict';

var AncDisabledAccController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;
        // var baseValidator = window.gridDirectives.baseValidator;

        this.info = {};
        scope.errors = {};
        this.announcment = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('announcment/reminders/info'),
                this.defineProperties.bind(this),
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

        // call save disabled account api
        this.saveAncDisabledAcc = function ($event) {
            // console.log(this.ancDisableAccData);
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            this.ancDisableAccData.organization_id = id;

            console.log('Disable Data : ', this.ancDisableAccData);

            requestFactory.post(
                requestFactory.getUrl('announcment/disabled-account/add'),
                this.ancDisableAccData,
                function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();

                    const inputs = document.querySelectorAll('input, textarea');
                    inputs.forEach(input => {
                        if (input.type === 'radio') {
                            input.checked = false;
                        } else {
                            input.value = '';
                        }
                    });
                }, this.fillError, 200
            );
        }

        this.cancelAncDisabledAcc = function ($event) {
            // reset all inputs
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
        }


        scope.$on('afterGetRecords', function (e, data) {
            // if (angular.isUndefined(scope.searchRecords.is_active)) {
            //     scope.searchRecords.is_active = 'all';
            // }
            setTimeout(function () {
                $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
            }, 500);
        });
    }

];

window.gridControllers = {
    AncDisabledAccController: AncDisabledAccController
};

// window.gridDirectives = {
//     baseValidator: validatorDirective,
//     intializeSidebar: intializeSidebar
// };
