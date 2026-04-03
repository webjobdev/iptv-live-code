'use strict';

var AncPushNotificationController = [
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
                requestFactory.getUrl('announcment/push-notifications/info'),
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

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('id');

        /**----------------------------------------------------- Add Push Notification START -----------------------------------------------------  */
        // this code for add drm name
        this.addAncNotifications = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.pushNotificationData = {};
            // $("#ancPushNotificationForm").css('display', 'block');
            // $("#organizationTranslationForm").css('display', "none");
        }

        // get subscription list
        this.fetchSubscriptions = function ($event) {
            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplan/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.orgSubscriptnList = response.data.data;

                    } else {
                        console.warn("Invalid data format from Subscription Plans :", response);
                    }
                }
            )
        }
        this.fetchSubscriptions();

        // call save/create announcement api
        this.savePushNotification = function ($event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            this.pushNotificationData.organization_id = id;
            requestFactory.post(
                requestFactory.getUrl('announcment/push-notifications/add'),
                this.pushNotificationData,
                function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }, this.fillError
            )
        }

        /**----------------------------------------------------- Add Push Notification END -----------------------------------------------------  */


        /**----------------------------------------------------- Other Push Notification START --------------------------------------------------- */

        // open sidepanel
        this.viewPushNotification = function (records) {
            $(".sidepanel").addClass("in");
            this.pushNotificationData = { id: records.id };
            this.pushNotificationData.name = records.name;
            this.pushNotificationData.title = records.title;
            this.pushNotificationData.description = records.description;
            this.pushNotificationData.org_subscription_id = records.org_subscription_id;
            this.pushNotificationData.subscriber_status = records.subscriber_status;
            this.pushNotificationData.platform = records.platform;
            this.pushNotificationData.resource_type = records.resource_type;
            this.pushNotificationData.publish = records.publish;
            this.pushNotificationData.created_by = records.created_by;
            this.pushNotificationData.status = records.status;
        }

        // copy record
        this.copyPushNotification = function (records) {
            this.pushNotificationData = { id: records.id };
            this.pushNotificationData.name = records.name;
            this.pushNotificationData.title = records.title;
            this.pushNotificationData.email = records.user[0].email;
            this.pushNotificationData.status = records.status == 0 ? 'Send Out' : records.status == 1 ? 'Pending' : records.status == 2 ? 'Deleted' : records.status == 3 ? 'Failed' : '';

            const { name, email, title, status } = this.pushNotificationData;
            console.log("Data : ", this.pushNotificationData);
            const copyText = `Name: ${name}, Email: ${email}, Title: ${title}, Status: ${status}`;
            console.log("Text Copied : ", copyText);
            console.log("Text Copied : ", navigator.clipboard);
            if (navigator.clipboard) {
                navigator.clipboard.writeText(copyText).then(() => {
                    const toolTip = document.getElementById('copy-btn');
                    setTimeout(function () {
                        toolTip.textContent = 'Copied !';
                        console.log('Copied');
                    }, 200)
                }).catch(err => {
                    console.error("Failed to copy: ", err);
                });
            } else {
                // Fallback for older browsers or HTTP contexts
                const textArea = document.createElement("textarea");
                textArea.value = copyText;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    document.execCommand('copy');
                    const toolTip = document.getElementById('copy-btn');
                    toolTip.textContent = 'Copied !';
                    console.log("Text copied using fallback method");
                } catch (err) {
                    console.error("Unable to copy to clipboard: ", err);
                } finally {
                    document.body.removeChild(textArea);
                }
            }
        }

        // delete record
        this.deletePushNotification = function (id) {
            requestFactory.post(
                requestFactory.getUrl('announcment/push-notifications/delete/' + id),
                this.defineProperties,
                function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }
            )
        }

        this.closeSubscriptionEdit = function () {
            scope.gridSideFormClose();
        };

        /**----------------------------------------------------- Edit Push Notification END ----------------------------------------------------- */
        scope.$on('afterGetRecords', function (e, data) {
            // if (angular.isUndefined(scope.searchRecords.is_active)) {
            //     scope.searchRecords.is_active = 'all';
            // }
            setTimeout(function () {
                $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
            }, 500);
        });

        this.orgWisePushNot = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('push-notifications/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const PushNot = response.data.data;

                        const filterOrg = PushNot.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.PushNotRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWisePushNot();
    }

];

window.gridControllers = {
    AncPushNotificationController: AncPushNotificationController,
};

// window.gridDirectives = {
//     baseValidator: validatorDirective,
//     intializeSidebar: intializeSidebar
// };
