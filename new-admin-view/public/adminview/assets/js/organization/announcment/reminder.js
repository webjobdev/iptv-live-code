'use strict';

var AncReminderController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;
        // var baseValidator = window.gridDirectives.baseValidator;

        this.info = {};
        scope.errors = {};
        this.announcment = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);
        this.isSubmitting = false;

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
        scope.isSubmitting = true;

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('id');

        /**----------------------------------------------------- Add Announcement Reminder START -----------------------------------------------------  */
        // this code for add announcement reminder
        this.addAncReminder = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.ancReminderData = {};
            // $("#organizationForm").css('display', 'block');
            // $("#organizationTranslationForm").css('display', "none");
        }


        // call save/create announcement api
        this.saveAncReminder = function (event) {

            if (this.isSubmitting) {
                return;
            }

            this.isSubmitting = true;
            const addBtn = document.getElementById("addReminderBtn");
            if (addBtn) addBtn.disabled = true;

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgId = urlParams.get('id');
            this.ancReminderData.organization_id = orgId;

            requestFactory.post(
                requestFactory.getUrl('announcment/reminders/add'),
                this.ancReminderData,
                function (response) {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    self.ancReminderData = {};
                    self.closeSubscriptionEdit();
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/reminders?id=${orgId}`;
                    }, 100);
                },
                function (error) {
                    scope.isSubmitting = false;

                    if (typeof self.fillError === 'function') {
                        self.fillError(error);
                    }
                }.bind(this), (error) => {
                    this.fillError(error);
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    // if (addBtn) addBtn.disabled = false;
                }
            )
        }

        /**----------------------------------------------------- Add Announcement Reminder END -----------------------------------------------------  */

        /**----------------------------------------------------- Edit Announcement Reminder START --------------------------------------------------- */

        // open sidepanel
        this.editAncReminder = function (records) {
            $(".sidepanel").addClass("in");
            this.ancReminderData = { id: records.id };
            this.ancReminderData.subject = records.subject;
            this.ancReminderData.message = records.message;
            this.ancReminderData.day_before = records.day_before;
            this.ancReminderData.reminder_to = records.reminder_to;
        }

        //  update status
        this.toggleStatus = function (record) {
            console.log("Toggle Record : ", record);

            record.status = record.status == 1 ? 0 : 1;
            const payload = {
                id: record.id,
                status: record.status
            };

            requestFactory.post(
                requestFactory.getUrl('announcement/reminders/status-update'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                }, this.fillError,
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', response.error);
                    record.status = record.status == 1 ? 0 : 1;
                }
            );
        };

        /**----------------------------------------------------- Edit Announcement Reminder END ----------------------------------------------------- */

        this.deleteAncReminder = function (id) {
            requestFactory.post(
                requestFactory.getUrl('announcment/reminders/destroy/' + id),
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

        scope.$on('afterGetRecords', function (e, data) {
            // if (angular.isUndefined(scope.searchRecords.is_active)) {
            //     scope.searchRecords.is_active = 'all';
            // }
            setTimeout(function () {
                $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
            }, 500);
        });

        this.orgWiseReminder = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('reminders/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const Reminder = response.data.data;

                        const filterOrg = Reminder.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.ReminderRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseReminder();
    }

];

window.gridControllers = {
    AncReminderController: AncReminderController
};

// window.gridDirectives = {
//     baseValidator: validatorDirective,
//     intializeSidebar: intializeSidebar
// };
