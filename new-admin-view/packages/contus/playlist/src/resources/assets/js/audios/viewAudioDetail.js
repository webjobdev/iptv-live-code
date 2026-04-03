'use strict';

var viewAudioDetail = angular.module('viewAudioDetail', ["ui.bootstrap"]);
var commonAPP = viewAudioDetail;
viewAudioDetail.directive('baseValidator', validatorDirective);

viewAudioDetail.filter('getCount', function () {
    return function (obj) {
        if (obj) {
            return Object.keys(obj).length;
        }
    };
}).filter('convertDate', function () {
    return function (obj) {
        if (obj) {
            return Date.parse(obj);
        }
    };
}).filter('convertAgoTime', function () {
    return function (obj) {
        if (obj) {
            var time = new Date().getTime();
            time = parseInt(time / 1000) - parseInt(obj / 1000);
            time = (time < 1) ? 1 : time;
            var tokens = [{
                'id': '31536000',
                'text': 'year'
            },
            {
                'id': '2592000',
                'text': 'month'
            },
            {
                'id': '604800',
                'text': 'week'
            },
            {
                'id': '86400',
                'text': 'day'
            },
            {
                'id': '3600',
                'text': 'hour'
            },
            {
                'id': '60',
                'text': 'minute'
            },
            {
                'id': '1',
                'text': 'second'
            }
            ];

            for (var unit in tokens) {
                var text = tokens[unit].text;
                unit = parseInt(tokens[unit].id);
                if (time < unit) {
                    continue;
                }
                var numberOfUnits = Math.floor(time / unit);
                return numberOfUnits + ' ' + text + ((numberOfUnits > 1) ? 's' : '') + ' ago';
            }
        }
    };
}).filter('getByKey', function () {
    return function (input, id, column, data, field) {
        var column = angular.isUndefined(column) ? 'id' : column;
        var i = 0;
        if (angular.isArray(input)) {
            for (; i < input.length; i++) {
                if (input[i] && input[i][column] == id) {
                    return (data == 'key') ? i : (field) ? input[i][field] : input[i];
                }
            }
        } else {
            var result = null;
            angular.forEach(input, function (val, key) {
                if (val[column] == id) {
                    result = (data == 'key') ? key : val;
                }
            });
            return result;
        }
        return null;
    };
}).filter('trusted', ['$sce', function ($sce) {
    return $sce.trustAsResourceUrl;
 }]);
viewAudioDetail.factory('requestFactory', requestFactory);

var initplayer = function ($rootScope, requestFactory) {
    return {
        restrict: 'A',
        replace: true,
        scope: {
            video: '='
        },
        link: function (scope, elem, attr) {
            scope.$watch('video', function (video) {
                if (video) {
                    if (video && video.is_live == 0) {
                        var player = videojs(elem['0'].id, {
                            "controls": true,
                            "autoplay": true,
                            "preload": "auto",
                            "fluid": true,
                            "playbackRates": [0.25, 0.5, 1, 1.25, 1.5, 2],
                            "poster": video.selected_thumb,
                            "sources": [{
                                "src": video.hls_playlist_url,
                                "type": 'application/x-mpegURL'
                            }],
                            "html5": {
                                "nativeAudioTracks": false,
                                "nativeVideoTracks": false,
                                "nativeTextTracks": false,
                                "hls": {
                                    "overrideNative": true,
                                }
                            },
                            "plugins": {
                                "hlsQualitySelector": {},
                                "keyboardShortCuts": {}
                            }
                        });
                    } else if (video.username) {
                        var player = videojs(elem['0'].id, {
                            "controls": true,
                            "autoplay": true,
                            "live": true,
                            "preload": "auto",
                            "fluid": true,
                            "playbackRates": [0.25, 0.5, 1, 1.25, 1.5, 2],
                            "poster": video.selected_thumb,
                            "sources": [{
                                "src": video.hls_playlist_url,
                                "type": 'application/x-mpegURL'
                            }],
                            "html5": {
                                "nativeAudioTracks": false,
                                "nativeVideoTracks": false,
                                "nativeTextTracks": false,
                                "hls": {
                                    "overrideNative": true,
                                }
                            },
                            "plugins": {
                                "hlsQualitySelector": {},
                                "keyboardShortCuts": {}
                            }
                        });
                    }
                }
            });
        }
    }
};
viewAudioDetail.controller('ViewAudioDetailsController', ['$scope', '$window', '$rootScope', 'requestFactory', '$sce', '$timeout', function (scope, win, rootScope, requestFactory, $sce, $timeout) {
    var self = this;
    scope.errors = {};
    this.editAudio = {};
    this.allCategories = {};
    this.showResponseMessage = false;
    this.gridLoadingBar = false;
    requestFactory.setThisArgument(this);
    this.notFoundFlag = false;
    var commenturl = '';
    scope.ConfirmationDeleteBox = false;
    this.fetchData = function (id) {
        requestFactory.get(requestFactory.getUrl('audios/complete-audio-details/' + id), function (response) {
            requestFactory.toggleLoader();
            var audioDetails = response.response.audios_data;
            scope.audio = response.response.audios_data;
            scope.audio.favourite_count  = response.response.favourites_count;
            initplayer();
            this.editAudio.id = audioDetails.id;
            this.editAudio.title = audioDetails.title;
            if(audioDetails.audio_description != null){
                this.editAudio.description = audioDetails.audio_description;
                this.editAudio.trimmed_description = this.getTrimmedString(audioDetails.audio_description, 300);
                this.setDescriptionData(300);
            }
            this.editAudio.is_active = String(audioDetails.is_active);
            this.editAudio.thumbnail_image = audioDetails.thumbnail_image;
            $timeout(function () {
                $('[data-toggle="popover"]').popover();
            }, 100);

        }, function (response) {
            self.notFoundFlag = true;
            requestFactory.toggleLoader();
        });
    }


    /*
     * Function to delete admin audio view detail page.
     */
    this.deleteSingleRecord = function (id) {
        scope.deleteParams = [id];
        scope.ConfirmationDeleteBox = true;
    };
    this.cancelDelete = function () {
        scope.ConfirmationDeleteBox = false;
        scope.deleteParams = '';
    };
    this.confirmDelete = function (status) {
        if (scope.deleteParams.length > 0) {
            self.deleteRecords(scope.deleteParams, status);
            scope.ConfirmationDeleteBox = false;
            scope.deleteParams = '';
        } else {
            scope.ConfirmationDeleteBox = false;
            scope.deleteParams = '';
        }
    };
    this.deleteRecords = function (id, status) {
        scope.deleteParams = '';
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/delete-action'), angular.extend({}, {
            selectedCheckbox: id,
            status: status
        }, scope.requestParams), function (data) {
            $('.accordion_wrapper_' + id).remove();
            this.responseMessage = data.message;
            this.showResponseMessage = true;
            if ($('.not-saved').length <= 0) {
                window.location = requestFactory.getTemplateUrl('admin/audios/audios');
            }
        });
    };


    this.formatDuration = function (time) {
        var duration = time.split(':');
        if (duration.length > 2) {
            return duration[0] + 'hr ' + duration[0] + 'm ' + duration[0] + 's';
        } else {
            return duration[0] + 'm ' + duration[0] + 's';
        }
    }

    this.setDescriptionData = function (length) {
        if (self.editAudio.description.length > length) {
            self.editAudio.trimFlag = true;
            self.editAudio.descriptionContent = self.editAudio.trimmed_description;
        } else {
            self.editAudio.trimFlag = false;
            self.editAudio.descriptionContent = self.editAudio.description;
        }
    };
    this.getTrimmedString = function (string, length) {
        return string.length > length ? string.substring(0, length - 3) + "..." : string;
    };

    this.showFullDescription = function () {
        self.editAudio.trimFlag = false;
        self.editAudio.descriptionContent = self.editAudio.description;
    };
}]);
/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
    for(var controller in window.gridControllers){
        if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
            window.gridControllers[controller].hideHeader=true;
            viewAudioDetail.controller(controller,window.gridControllers[controller]);
        }
    }
}
viewAudioDetail.directive('initPlayer', initplayer);
/**
 * Manually bootstrap the Angular module here
 */

angular.element(document).ready(function () {
    angular.bootstrap(document, ['viewAudioDetail']);
});