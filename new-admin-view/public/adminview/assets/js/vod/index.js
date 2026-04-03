var VodGridController = ['flowFactory', '$scope', 'requestFactory', '$rootScope', '$window', '$sce', '$timeout', '$compile', '$interval',
    function (flowFactory, scope, requestFactory, rootScope, $window, $sce, $timeout, $compile, $interval) {
        var self = this;
        this.info = {};
        this.selectedRecords = [];
        scope.errors = {};


        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info;
            this.allCollection = data.info.allCollection;
            this.allSeasons = data.info.allSeasons;
            this.allExams = data.info.allCollection;
            this.allCategories = data.info.allCategories;
            this.allSeries = data.info.allSeries;
            this.language = data.info.language;
            this.ads_info = data.info.ads_info;
            this.cast = data.info.cast;

            this.formatCategories = angular.copy(this.allCategories);

            var result = [];

            this.formatCategories.forEach(function (item, index) {
                if (item.id) {
                    if (item.child_category.length > 0) {
                        item.child_category.forEach(function (child, i) {
                            var newIndex = result.length;
                            result[newIndex] = {};
                            result[newIndex].id = child.id;
                            result[newIndex].title = child.title;
                            result[newIndex].parent = item.title;
                        });
                    }
                    else {
                        var newIndex = result.length;
                        result[newIndex] = {};
                        result[newIndex].id = '';
                        result[newIndex].title = '';
                        result[newIndex].parent = item.title;
                    }
                }
            }
            );

            this.formatCategories = result;

            if (this.language.length != 0) {
                selectedLanguage = String(this.language[0].id);
            }
            this.transcodedInfo = data.info.transcodedInfo;
            scope.livedetails = data.info.livesyncdata[0];
            this.numberOfActivePresets = data.info.numberOfActivePresets;
            baseValidator.setRules(this.info.video_edit_rules);
            angular.element('#move_collection').removeAttr('data-toggle');
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('videos/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();


        scope.encodeId = function (id) {
            return btoa(id);
        };

        // toggle button code start
        scope.statusChangeSingleRecord = function (record, id) {
            var toggleID = id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };

            // console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('video-on-demand/toggle/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.getToaster(),
                        requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 100);
                }
            );
        };
        // toggle button code end

        this.deleteSingleRecordVideos = function(id) {
            scope.deleteParams = [id];
            scope.videoConfirmationDeleteBox = true;
            scope.videotitle = title;
        };
    }
];


window.gridInitApp = angular.module('grid', ['flow', 'ngTagsInput', 'ui']);


window.gridControllers = {
    VodGridController: VodGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar,
};
