var AnnouncmentController = [
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
                requestFactory.getUrl('announcment/info'),
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

        /**----------------------------------------------------- Add Announcement START -----------------------------------------------------  */
        // this code for add drm name
        this.addAnnouncement = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.announcementData = {};
            // $("#organizationForm").css('display', 'block');
            // $("#organizationTranslationForm").css('display', "none");
        }

        // get subscribers list
        this.fetchSubscribers = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.subsList = response.data.data;
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };
        this.fetchSubscribers();

        // call save/create announcement api
        this.saveAnnouncement = function (event) {
            if (event) event.preventDefault();

            if (this.isSubmitting) {
                return;
            }

            this.isSubmitting = true;
            const addBtn = document.getElementById("addAnnouncemntBtn");
            if (addBtn) addBtn.disabled = true;

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            this.announcementData.organization_id = id;
            // console.log("Announcement Data : ", this.announcementData);

            requestFactory.post(
                requestFactory.getUrl('announcment/add'),
                this.announcementData,
                function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    setTimeout(function () {
                        location.reload();
                    }, 200);
                }.bind(this), (error) => {
                    this.fillError(error);
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    // if (addBtn) addBtn.disabled = false;
                }
            )
        }

        /**----------------------------------------------------- Add Announcement END -----------------------------------------------------  */

        this.openForm = function (userId) {
            this.announcment.id = userId;
            this.announcment.announcement = '';
            $('.sidepanel').addClass('open');
        };

        // Save form
        this.save = function ($event) {
            $event.preventDefault();
            const payload = {
                id: this.announcment.id,
                announcement: this.announcment.announcement
            };

            requestFactory.post(
                requestFactory.getUrl('announcment/add'),
                payload,
                function (response) {
                    $('.sidepanel').removeClass('open');
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                },
                this.fillError
            );
        };


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

        this.orgWiseAnc = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('announcment/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const Anc = response.data.data;

                        const filterOrg = Anc.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.AncRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseAnc();
    }

];

window.gridControllers = {
    AnnouncmentController: AnnouncmentController
};

// window.gridDirectives = {
//     baseValidator: validatorDirective,
//     intializeSidebar: intializeSidebar
// };
