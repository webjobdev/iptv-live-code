'use strict';

var NotificationController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;

        this.info = {};
        scope.errors = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.announcementdefineProperties = function (data) {
            if (data && data.info && Array.isArray(data.info.announcment_info)) {
                scope.announcment_info = data.info.announcment_info;
                requestFactory.toggleLoader();
            }
        };

        // notification table code
        this.announcementfetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('announcment/notification/info'),
                this.announcementdefineProperties,
                function (response) {
                    if (response) {
                        console.log("Valid announcement data:", response);
                    } else {
                        console.warn("Invalid data format from announcmentfetchInfo:", response);
                    }
                }
            );
        };
        this.announcementfetchInfo();

        scope.toggle = function (announcement) {
            announcement.expanded = !announcement.expanded;
        };


    }

];

window.gridControllers = {
    NotificationController: NotificationController
};
