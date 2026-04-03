'use strict';

var AncActivationController = [
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

        // call save/create announcement api
        this.saveAncActivation = function ($event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            this.ancActivationData.organization_id = id;

            requestFactory.post(
                requestFactory.getUrl('announcment/activation/add'),
                this.ancActivationData,
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
                }, this.fillError, 200);
        }

        this.cancelAncAvtivation = function ($event) {
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
    AncActivationController: AncActivationController
};

// window.gridDirectives = {
//     baseValidator: validatorDirective,
//     intializeSidebar: intializeSidebar
// };
