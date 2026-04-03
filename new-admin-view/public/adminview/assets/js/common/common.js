"use strict";
var myApp = angular.module('myApp', []);
var CommonController = [
    "$scope",
    "requestFactory",
    "$rootScope",
    "$window",
    "$sce",
    "$timeout",
    "$compile",
    "$interval",
    "$filter",
    function (
        scope,
        requestFactory,
        rootScope,
        $window,
        $sce,
        $timeout,
        $compile,
        $interval,
        $filter
    ) {
        var self = this;
        this.info = {};
        this.selectedRecords = [];
        this.responseMessage = false;
        this.showResponseMessage = false;
        this.dropdownSelected = 4;
        this.subscribersDropdownSelected = 4;
        this.revenueSelected = 4;
        this.revenueStatusSelected = 4;
        this.regionwiseDateValue = 4;
        this.platformwiseDateValue = 4;
        scope.videoCount = 0;
        this.activeSubscriberSelected = 4;
        scope.languages = {};
        scope.language = "";
        scope.hideHeader = false;
        scope.showHeaderProgress = false;
        this.headerCtrl = {};
        scope.errors = {};
        requestFactory.setThisArgument(this);
        rootScope.detailVideo = [];
        scope.node_url = "";
        rootScope.userPermissions = {};
        this.apiUrl = "https://new-admin-api.test/api/";


        // notification code
        scope.announcements = [];



        this.defineProperties = function (data) {
            this.info = data.info;
            scope.current_username = this.info.current_user.name;
            scope.current_profileImg = this.info.current_user.profileImg;
            rootScope.authId = this.info.current_user.authID;
            scope.languages = data.info.language;
            scope.language = String(data.info.session_language);
            scope.node_url = data.info.node_url;
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('dashboard/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };

        rootScope.checkAccess = function (permission) {
            // Super Admin bypass
            if (requestFactory.hasPermission('all')) {
                return true;
            }

            // Case 1: Simple Permission (no dot) - implies 'View' access
            // Delegate to original factory method to ensure legacy behavior (Sidebar visibility)
            if (permission.indexOf('.') === -1) {
                return requestFactory.hasPermission(permission);
            }

            // Case 2: Complex Permission (module.action)
            var parts = permission.split('.');
            var module = parts[0];
            var action = parts[1];

            // Ensure list is loaded by calling hasPermission on the module. 
            // This populates requestFactory.userpermissionList as a side effect.
            requestFactory.hasPermission(module);
            var userList = requestFactory.userpermissionList;

            // Debugging
            // console.log('CheckAccess Complex:', permission, module, action, userList);

            if (userList === 'all') {
                return true;
            }

            if (userList) {
                if (Array.isArray(userList)) {
                    for (var i = 0; i < userList.length; i++) {
                        if (userList[i].permission_module_name === module) {
                            if (userList[i].hide == 1 || userList[i].hide == '1') {
                                return false;
                            }
                            var val = userList[i][action];
                            if (val === undefined) {
                                val = userList[i][action.charAt(0).toUpperCase() + action.slice(1)];
                            }
                            return (val == 1 || val == '1' || val === true);
                        }
                    }
                } else if (typeof userList === 'object' && userList.hasOwnProperty(module)) {
                    var p = userList[module];
                    if (p && (p.hide == 1 || p.hide == '1' || p.Hide == 1 || p.Hide === true)) {
                        return false;
                    }
                    var val = p[action];
                    if (val === undefined) {
                        val = p[action.charAt(0).toUpperCase() + action.slice(1)];
                    }
                    return (val == 1 || val == '1' || val === true);
                }
            }

            return false;
        }

        rootScope.redirectUnauthenticated = function (response) {
            if (response.data.statusCode == 401) {
                $window.location = window.VPlay.route.viewURL + 'admin/auth/login';
            }
        }
        var commentfail = function (response) {
            ngToast.create({
                className: "danger",
                content: "<strong>" + response.message + "</strong>"
            });
        };

        /**
         * Interval Called Frequently Every One Minute to Fetch Transcoding Progress of Video
         */
        // $interval(function () {
        //     self.fetchProgress();
        // }, 2000);
        //   this.fetchProgress = function () {
        //     scope.apiParams = {};
        //     scope.apiParams.location = window.location.href;
        //     scope.apiParams.video_ids = rootScope.detailVideo;

        //     requestFactory.post(requestFactory.getUrl('videos/headerprogress'), scope.apiParams, function (response) {

        //         if(response.response.video_detail.length > 0) {
        //             rootScope.transcodeVideoDetails  = response.response.video_detail;
        //             rootScope.transcodeVideoCount    = response.response.video_detail.length;
        //         }
        //         if (response.response.video_info.length > 0) {
        //             scope.videoDetails  = response.response.video_info;
        //             scope.videoCount    = response.response.video_info.length;
        //             document.getElementById("header-progress").style.display = "block";
        //         }
        //         else {
        //             document.getElementById("header-progress").style.display = "none";
        //         }
        //     }, function () { });
        //   }

        scope.progress_index = 0;

        scope.next = function () {
            if (scope.progress_index >= scope.videoDetails.length - 1) {
                scope.progress_index = scope.videoDetails.length - 1;
            } else {
                scope.progress_index++;
            }
        };
        scope.previous = function () {
            if (scope.progress_index <= scope.videoDetails.length - 1) {
                if (scope.progress_index > 0) {
                    scope.progress_index--;
                } else {
                    scope.progress_index = 0;
                }
            } else {
                scope.progress_index = scope.videoDetails.length + 1;
            }
        };

        this.languageChange = function () {
            var selectedlanguage = this.languageSelected;

            requestFactory.post(
                requestFactory.getUrl("site/language"),
                { selectedlanguage },
                function (response) {
                    $window.location = $window.location.href;
                }
            );
        };

        this.fetchInfo();
        this.logout = function ($event) {
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_permissions');
            requestFactory.get(requestFactory.getUrl('auth/logout'),
                function (response) {
                    $window.location = window.VPlay.route.viewURL + 'admin/auth/login';
                },
                function () { }
            );
        }

        scope.hideHeader = window.gridControllers.CommonController.hideHeader;


        // notification code
        // angular.module('myApp')
        //   .controller('CommonController', function ($scope, $filter) {
        //     $scope.fromNow = function (dateString) {
        //       return moment(dateString).fromNow();
        //     };
        //   });


        this.announcementdefineProperties = function (data) {
            if (data && data.info && Array.isArray(data.info.announcements)) {
                scope.todayCount = data.info.todayAnnouncementCount;
                scope.announcements = data.info.announcements;
            }
        };

        this.announcementfetchInfo = function () {
            // console.log("Fetching announcement info...");
            requestFactory.get(
                requestFactory.getUrl('announcment/notification/info'),
                this.announcementdefineProperties,
                function (response) {
                    if (response) {
                        // console.log("Valid announcement data:", response);
                    } else {
                        console.warn("Invalid data format from announcmentfetchInfo:", response);
                    }
                }
            );
        };
        this.announcementfetchInfo();

        // this.rulePermissionDefinrProperties = function (data) {
        //     if (data) {
        //         console.log("Permission rule data ! ", data);
        //     }
        // }

        // get permission rule api
        rootScope.getPermissions = function () {
            requestFactory.get(
                requestFactory.getUrl('assign-permission'),
                function (response) {
                    if (response) {
                        const formattedData = transformModules(response);
                        scope.permsnList = formattedData;
                        // console.log(formattedData);
                        localStorage.setItem('user_rule_permissions', formattedData);
                    } else {
                        console.warn("Permission NOT Response ! : ", response);
                    }
                }
            )
        }
        rootScope.getPermissions();

        // format permission modules for edit record page
        function transformModules(data) {
            const modules = {};
            data.data.permissions.forEach(p => {
                const key = p.permission_module_name.toLowerCase().replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, '-');

                modules[key] = {
                    name: p.permission_module_name,
                    // permissions: {
                    View: p.view == 1,
                    Create: p.create == 1,
                    Edit: p.edit == 1,
                    Delete: p.delete == 1,
                    Hide: p.hide == 1,
                    CashPayments: p.cash_payment == 1,
                    RefundPayments: p.refund_payment == 1,
                    LengthAdjustments: p.length_adjustment == 1,
                    SecuritySearch: p.security_search == 1
                    // }
                };
            });

            return modules;
        }
    }
];


if (angular.isObject(window.gridControllers)) {
    window.gridControllers.CommonController = CommonController;
} else {
    window.gridControllers = {};
    window.gridControllers.CommonController = CommonController;
}


