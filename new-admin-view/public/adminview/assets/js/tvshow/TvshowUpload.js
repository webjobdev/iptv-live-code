var vodUpload = angular.module('vodUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = vodUpload;

vodUpload.directive('baseValidator', validatorDirective);
vodUpload.factory('requestFactory', requestFactory);
vodUpload.service('commonGeofencingService', commonGeofencing);
vodUpload.controller('TvShowUploadController', [
    'flowFactory',
    '$scope',
    'requestFactory',
    '$rootScope',
    '$window',
    '$sce',
    '$timeout',
    '$compile',
    '$interval',
    'commonGeofencingService',
    '$location',
    function (
        flowFactory,
        scope,
        requestFactory,
        rootScope,
        $window,
        $sce,
        $timeout,
        $compile,
        $interval,
        commonGeofencingService,
        $location
    ) {
        var self = this;
        this.info = {};
        this.selectedRecords = [];
        this.OrganizationList = [];
        scope.translationError = false;
        scope.errors = {};
        this.videoSubmitted = false;
        scope.tvsSelectedVideo = {};
        scope.editPage = false;

        this.defineProperties = function (data) {
            this.info = data.info;
            // this.allCollection = data.info.allCollection;
            this.allSeasons = data.info.allSeasons;
            this.allExams = data.info.allCollection;
            this.allCategories = data.info.allCategories;
            this.radioCategories = data.info.allRdioCategories;
            this.liveCategories = data.info.allLiveCategories;
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
                    } else {
                        var newIndex = result.length;
                        result[newIndex] = {};
                        result[newIndex].id = '';
                        result[newIndex].title = '';
                        result[newIndex].parent = item.title;
                    }
                }
            });
            this.formatCategories = result;
            this.allSeries = data.info.allSeries;
            this.language = data.info.language;
            this.ads_info = data.info.ads_info;
            if (this.language.length != 0) {
                scope.selectedLanguage = this.language[0].id;
                scope.defaultLanguage = this.language[0].id;
            }
            this.transcodedInfo = data.info.transcodedInfo;
            scope.livedetails = data.info.livesyncdata[0];
            this.numberOfActivePresets = data.info.numberOfActivePresets;
            baseValidator.setRules(this.info.video_edit_rules);
            angular.element('#move_collection').removeAttr('data-toggle');

            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('videos/info'),
                this.defineProperties,
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

        this.init = function () {
            scope.livePage = true;
            // scope.tvsSelectedVideo.liveType = 'hls';
            // scope.tvsSelectedVideo.aspect_ratio = '640X360';
        };

        // date format code start 
        this.handleDateFormat = function (scheduled_date, type) {
            var result;
            var splitDate = scheduled_date.split('-');
            return splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];
        };

        this.handleTimeFormat = function (scheduled_time) {
            var splitDate = scheduled_time.split(':');
            return splitDate[0] + '-' + splitDate[1] + '-' + splitDate[2];
        };

        this.formatDate = function (date) {
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            return (
                ('0' + date.getDate()).slice(-2) +
                '-' +
                month +
                '-' +
                date.getFullYear()
            );
        };

        // scope.tvsSelectedVideo.timeParts = {
        //     hour: '00',
        //     minute: '00',
        //     second: '00'
        // };

        // function pad(val) {
        //     return ('0' + val).slice(-2);
        // }

        // scope.increment = function (unit) {
        //     let max = unit === 'hour' ? 23 : 59;
        //     let val = parseInt(scope.tvsSelectedVideo.timeParts[unit] || 0, 10);
        //     val = (val + 1) > max ? 0 : val + 1;
        //     scope.tvsSelectedVideo.timeParts[unit] = pad(val);
        //     scope.updateModel();
        // };

        // scope.decrement = function (unit) {
        //     let max = unit === 'hour' ? 23 : 59;
        //     let val = parseInt(scope.tvsSelectedVideo.timeParts[unit] || 0, 10);
        //     val = (val - 1) < 0 ? max : val - 1;
        //     scope.tvsSelectedVideo.timeParts[unit] = pad(val);
        //     scope.updateModel();
        // };

        // scope.updateModel = function () {
        //     scope.tvsSelectedVideo.timeParts =
        //         `${scope.tvsSelectedVideo.timeParts.hour}:${scope.tvsSelectedVideo.timeParts.minute}:${scope.tvsSelectedVideo.timeParts.second}`;
        // };

        scope.togglePublishDate = function () {
            if (scope.tvsSelectedVideo.publish_now == 1) {
                const now = new Date();
                // Format as YYYY-MM-DD HH:MM:SS
                const formatted = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                scope.tvsSelectedVideo.publish_date = formatted;
            }
        };

        // create vod code
        this.saveTvShow = function ($event) {
            scope.errors = {};

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.tvsSelectedVideo.is_active = scope.tvsSelectedVideo.is_active ? true : false;
                if (this.videoSubmitted == false) {
                    this.videoSubmitted = true;
                    requestFactory.post(
                        requestFactory.getUrl('create/tv-show'),
                        scope.tvsSelectedVideo,
                        function (response) {
                            requestFactory.setToaster('success', response.message);
                            window.location.href = requestFactory.getTemplateUrl(
                                'admin/tvshow'
                            );
                        },
                        this.fillError
                    );
                }
            }
        };
        // create vod code end

        // edit vod code
        this.saveTvshowEdit = function ($event, id) {
            scope.error = {};
            var vodId = id;
            console.log("Final payload =>", scope.tvsSelectedVideo);

            if (baseValidator.validateAngularForm($event.target, scope)) {

                // ---- Sync bundles into content_sets ----
                let bundles = scope.tvsGridCtrl.selectedVideo.bundles || [];

                scope.tvsSelectedVideo.content_sets = bundles.map(bundle => {
                    return {
                        organization_id: bundle.organization_id || bundle.id,
                        organization_name: bundle.organization_name,
                        tv_show_contentset: (bundle.bundles || []).map(b => b.id)
                    };
                });

                // Debug check
                // 

                // ---- Send request ----
                requestFactory.post(
                    requestFactory.getUrl('tv-show/edit/' + vodId),
                    scope.tvsSelectedVideo,
                    (response) => {
                        if (response.data) {
                            scope.tvsSelectedVideo = response.data;
                        }

                        scope.tvsSelectedVideo.is_active = !!scope.tvsSelectedVideo.is_active;

                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        window.location.href = requestFactory.getTemplateUrl(
                            'admin/tvshow'
                        );

                        $('#' + vodId).removeClass('not-saved');

                        window.location.href = requestFactory.getTemplateUrl('admin/tvshow');
                    },
                    this.fillError
                );
            } else {
                scope.errors = {};
                angular.forEach(scope.errors, function (eachmessage, key) {
                    if (
                        typeof eachmessage == 'object' &&
                        eachmessage.hasOwnProperty('message')
                    ) {
                        scope.errors[key] = {
                            has: true,
                            message: eachmessage.message
                        };
                    }
                });
            }
        };
        // edit vod code end


        // fetch data code start
        function toDateTime(dateString) {
            if (!dateString) return null;
            return new Date(dateString.replace(" ", "T"));
        }



        this.addSeason = function ($event) {
            const currentUrl = window.location.href;
            // console.log("Current URL:", currentUrl);
            const encodeId = currentUrl.split("/").pop();
            // console.log("encode id is:", encodeId);
            // const decodeId = atob(encodeId);
            // console.log("decode id is:", decodeId);
            const url = `${appUrl}admin/tvshow/add/season/${encodeId}`;
            // console.log("url is:", url);
            window.location.href = url;

        }
        // fetch data code end here

        this.changeKids = function () {
            scope.tvsSelectedVideo.is_parental = 0;
            scope.tvsSelectedVideo.age_limit = '';
        };

        /**
         * method to get from categories
        **/

        this.setFormCategoriesData = function (allCategories) {
            this.formatCategories = [];
            this.formatCategories = angular.copy(allCategories);
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
                    } else {
                        var newIndex = result.length;
                        result[newIndex] = {};
                        result[newIndex].id = '';
                        result[newIndex].title = '';
                        result[newIndex].parent = item.title;
                    }
                }
            });
            this.formatCategories = result;
        };

        this.resetFormData = function (event) {
            this.collection = {};
            scope.errors = {};
            requestFactory.get(
                requestFactory.getUrl('videos/collection-update'),
                function (response) {
                    this.allCollection = response.info.allCollection;
                    baseValidator.setRules(this.info.video_edit_rules);
                }
            );
            this.showcreateCollection = true;
            this.collection.id = String(0);
        };

        this.deleteBulkRecord = function () {
            scope.deleteParams = this.selectedRecords;
            this.isDeactivateBulkRecord = false;
            this.isActivateBulkRecord = false;
            this.isDeleteBulkRecord = true;
        };

        this.activateOrDeactivateBulkRecord = function ($isActivateOrDeactivate) {
            scope.activateParams = this.selectedRecords;
            if ($isActivateOrDeactivate == 'activate') {
                this.isDeleteBulkRecord = false;
                this.isDeactivateBulkRecord = false;
                this.isActivateBulkRecord = true;
            } else if ($isActivateOrDeactivate == 'deactivate') {
                this.isDeleteBulkRecord = false;
                this.isActivateBulkRecord = false;
                this.isDeactivateBulkRecord = true;
            }
        };

        this.cancelDeleteVideos = function () {
            scope.videoConfirmationDeleteBox = false;
            scope.deleteParams = '';
        };

        this.cancelResetForm = function () {
            var myIndex = scope.searchVideo(
                scope.videoArray,
                scope.tvsSelectedVideo.title
            );
            scope.videoArray[myIndex] = {
                ...scope.videoArray[myIndex],
                ...scope.resetVideo
            };
            scope.tvsSelectedVideo = {};
            scope.tvsSelectedVideo = { ...scope.tvsSelectedVideo, ...scope.resetVideo };
        };

        scope.searchVideo = function (inputArray, title) {
            return inputArray.findIndex(function (person) {
                return person.key == scope.randomKeys[title];
            });
        };

        this.cancelReplaceVideos = function () {
            scope.videoConfirmationReplaceBox = false;
        };

        this.confirmReplaceVideos = function () {
            scope.videoConfirmationReplaceBox = false;
            scope.tvsSelectedVideo.showProgress = false;
            scope.tvsSelectedVideo.showReplace = true;
        };

        this.deleteSingleRecordVideos = function (id, title) {
            scope.deleteParams = [id];
            scope.videoConfirmationDeleteBox = true;
            scope.videotitle = title;
        };

        this.cancelDeleteVideos = function () {
            scope.videoConfirmationDeleteBox = false;
            scope.deleteParams = '';
            $('#videoDeleteModal').modal('hide');
        };

        this.deleteRecordsVideos = function (id, videoStatus) {
            scope.deleteParams = '';
            requestFactory.post(
                requestFactory.getUrl('tv-show/fetch/action'),
                angular.extend({}, { selectedCheckbox: id, action: 'delete' }, scope.requestParams),
                function (data) {
                    requestFactory.setToaster('success', (data && data.message ? data.message : "Record deleted successfully"));
                    window.location.href = requestFactory.getTemplateUrl('admin/tvshow');
                }, function (response) {
                    requestFactory.setToaster('error', 'Error deleting record');
                }
            );
        };

        this.confirmDeleteVideos = function (videoStatus) {
            if (scope.deleteParams.length > 0) {
                self.deleteRecordsVideos(scope.deleteParams, videoStatus);
                scope.videoConfirmationDeleteBox = false;
                if (videoStatus == 'bulk-video') {
                    this.selectedRecords = [];
                }
                scope.deleteParams = '';
            } else {
                scope.videoConfirmationDeleteBox = false;
                scope.deleteParams = '';
            }
        };

        this.confirmActivateOrDeactivateVideos = function (is_status) {
            if (is_status == 1) {
                this.isActivateBulkRecord = false;
            } else if (is_status == 0) {
                this.isDeactivateBulkRecord = false;
            }
            self.activateOrDeactivateRecordsVideos(scope.activateParams, is_status);
        };

        this.activateOrDeactivateRecordsVideos = function (id, is_status) {
            scope.activateParams = '';
            scope.showRecords = false;
            scope.gridLoadingBar = true;
            var activateIdLength = id.length;
            var isStatus = is_status == 1 ? 'activate' : 'deactivate';

            requestFactory.toggleLoader();
            scope.deleteRequest = requestFactory.post(
                requestFactory.getUrl('videos/bulk-update-status'),
                angular.extend(
                    {},
                    {
                        selectedCheckbox: id,
                        isStatus: isStatus
                    },
                    scope.requestParams
                ),
                function (data) {
                    requestFactory.toggleLoader();
                    requestFactory.setToaster('success', data.message);
                    this.selectedRecords = [];
                    angular.element('#selectall').removeAttr('checked');
                    if (scope.records.length - activateIdLength > 0) {
                        scope.getRecords(true);
                    } else {
                        scope.currentPage =
                            scope.currentPage - 1 == 0 ? 1 : scope.currentPage - 1;
                        scope.getRecords(true);
                    }
                }
            );
            angular.element('#move_collection').removeAttr('data-toggle');
        };

        if ($('#thumb-image').length) {
            window.VideoThumbnailUploadHandler = new uploadHandler();
            window.VideoThumbnailUploadHandler.initate({
                file: 'thumb-image',
                previewer: 'thumb-preview',
                progress: 'thumb-progress',
                deleteIcon: 'thumb-delete',
                beforeUpload: function () {
                    scope.errors = {};
                    if (!scope.$$phase) {
                        scope.$apply();
                    }
                },
                afterUpload: function (response) {
                    self.editVideo.thumbnail = response.info;
                    self.editVideo.selected_thumb = response.info;
                }
            });
        }

        this.addFullScreenEventListener = function () {
            var myPlayer = videojs('video_player');
            myPlayer.on('fullscreenchange', function () {
                if (myPlayer.isFullscreen()) {
                    // Change transition property to none to avoid layout shake while exit.
                    document.getElementById('menu-7').style.transitionProperty = 'none';
                    document.querySelector('.st-pusher').style.transitionProperty =
                        'none';
                } else {
                    // Remove back the transition value none so that the video edit sidebar closes and opens smoothly.
                    document.getElementById('menu-7').style.removeProperty('transition');
                    document
                        .querySelector('.st-pusher')
                        .style.removeProperty('transition');
                }
            });
        };
        // this.addFullScreenEventListener();

        /**
         *  Function is used to select the move collection Button
         *
         *  @param $event, id
         *
         */
        this.selectRecord = function ($event, id) {
            var isCheckboxSelected = false;
            var eventCheckbox = $event.target || $event.srcElement;

            if (angular.isObject(eventCheckbox)) {
                if (angular.element(eventCheckbox).is(':checked')) {
                    angular.element('#move_collection').attr('data-toggle', 'modal');

                    if (this.selectedRecords.indexOf(id) == -1) {
                        this.selectedRecords.push(id);
                    }
                } else if (this.selectedRecords.indexOf(id) > -1) {
                    this.selectedRecords.splice(this.selectedRecords.indexOf(id), 1);
                }
            }

            if (this.selectedRecords.length == 0) {
                angular.element('#move_collection').removeAttr('data-toggle');
            }
            this.checkMasterCheckbox();
        };
        /**
         * Function to check and uncheck master checkbox when all the checkboxes are checked or not.
         */
        this.checkMasterCheckbox = function () {
            var mainCheckbox = true;
            angular.element('.checkbox').each(function () {
                if (angular.element(this).prop('checked') == false) {
                    mainCheckbox = false;
                }
            });

            if (mainCheckbox == false) {
                // Uncheck the main checkbox
                angular.element('#selectall').prop('checked', false);
            } else {
                // Check the main checkbox
                angular.element('#selectall').prop('checked', true);
            }
        };
        /**
         * Function to select and unselect all checkboxes.
         */
        this.selectAllRecords = function () {
            if (angular.element('#selectall').prop('checked')) {
                self.selectedRecords = [];
                angular.element('.checkbox').each(function () {
                    angular.element(this).prop('checked', true);
                    var id = Number(angular.element(this).val());
                    self.selectedRecords.push(id);
                });
                angular.element('#move_collection').attr('data-toggle', 'modal');
            } else {
                angular.element('.checkbox').each(function () {
                    angular.element(this).prop('checked', false);
                    var id = Number(angular.element(this).val());
                    self.selectedRecords.splice(self.selectedRecords.indexOf(id), 1);
                });
            }
            if (this.selectedRecords.length == 0) {
                angular.element('#move_collection').removeAttr('data-toggle');
            }
        };

        /**
         *  Function is used to select the create collection
         *  @param string collection
         *  @return void
         */

        this.createCollection = function (collection) {
            if (parseInt(collection) === 0) {
                this.showcreateCollection = true;
            } else {
                scope.errors = {};
                this.showcreateCollection = false;
            }
        };

        /**
         *  Function is used to save the collection
         *  @param $event
         *
         */

        this.save = function ($event) {
            if (baseValidator.validateAngularForm($event.target, scope)) {
                this.collection.tvsSelectedVideos = this.selectedRecords;
                requestFactory.post(
                    requestFactory.getUrl('collections/add'),
                    this.collection,
                    function (response) {
                        this.fetchInfo();
                        angular.element('.close').click();
                        requestFactory.toggleLoader();
                        angular.element('.checkbox').attr('checked', false);
                        angular.element('#selectall').prop('checked', false);
                        this.selectedRecords = [];
                        requestFactory.setToaster('success', response.message);
                    },
                    this.fillError
                );
            }
        };

        /**
         * Function to update status of a preset,collection,category and video
         *
         * @param object record
         * @return void
         */

        this.updateStatus = function (record) {
            scope.routeName = 'videos';
            scope.updateStatus(record);
        };

        /**
        *  Listen to the records to update property
        **/

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
                scope.searchRecords.type = 'all';
            }
            scope.selectId = [];
            for (var i = 0, len = data.data.data.length; i < len; i++) {
                if (data.data.data[i].liveStatus === 'starting') {
                    scope.selectId.push(data.data.data[i]);
                }
            }
            angular.element('#move_collection').removeAttr('data-toggle');
            self.selectedRecords = [];
            angular.element('.checkbox').attr('checked', false);
            scope.getStatusLive();

            setTimeout(function () {
                $('#fixTable').tableHeadFixer({ head: false, right: 1 });
            }, 500);

            scope.progressArray = [];
            scope.indexArray = [];
            data.data.data.filter(function (item, key) {
                if (item.job_status != 'Complete' && item.job_status != 'Error') {
                    scope.progressArray.push(item.id);
                }

                scope.indexArray[item.id] = key;
            });
            if (scope.progressArray.length > 0) {
                $interval(function () {
                    self.fetchProgress();
                }, 2000);
            }
        });


        /**
         * Image Upload Script
         * */
        function readAsUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('image').src = e.target.result;
                };
                reader.onloadend = function (e) {
                    $('#modal').modal('show');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function () {
            /*
             * Thumb Image Upload Part
             */
            var image = document.getElementById('image');
            $(document).on('change', '.uploadImg', function (e) {
                var videoItem = $(this).data('video-index');
                scope.errors = {};
                var ValidImageTypes = ['image/jpeg', 'image/png'];
                var files = e.target.files;
                var fileType = files[0].type;
                if ($.inArray(fileType, ValidImageTypes) < 0) {
                    scope.$apply();

                    // BEGIN : To show invalid error message in the croppre box
                    $('#modal').modal('show');
                    $('.crop-body').hide();
                    $('#submit-image').hide();
                    $('.error_msg')
                        .show()
                        .text(
                            'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                        );
                    // END : To show invalid error message in the croppre box
                    return;
                }
                $('.crop-body').show();
                var videoIndex = e.target.getAttribute('data-video-index');
                $('#modal .video-index').val(videoIndex);
                readAsUrl(this);
            });

            var cropBoxData;
            var canvasData;
            var cropper;

            $(document).on('show.bs.modal', '#modal', function () {
                // By default hide the error and show submit button when popup opens, then based on the validation we hide/show the details in the same popup
                $('#submit-image').show();
                $('.error_msg').hide();
                setTimeout(function () {
                    cropper = new Cropper(image, {
                        autoCropArea: 1,
                        viewMode: 1,
                        aspectRatio: 200 / 338,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 200,
                        minCropBoxHeight: 338,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 200, height: 338 };
                            cropper.setCropBoxData(config).setCanvasData(canvasData);
                        }
                    });
                }, 500);
            });
            $(document).on('hidden.bs.modal', '#modal', function () {
                document.getElementsByClassName('uploadImg')[0].value = '';
                $('#submit-image').prop('disabled', false);
                cropper.destroy();
            });
            $(document).on(
                'click',
                '#submit-image',
                requestFactory.access_token,
                function () {
                    cropBoxData = cropper.getCropBoxData();
                    canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
                        var formData = new FormData();
                        formData.append('module', 'video');
                        formData.append('size', 'thumb');
                        formData.append('image', blob);
                        $('.crop-body').hide();
                        $('.loader-container').show();
                        $('#submit-image').prop('disabled', true);
                        $.ajax(
                            $('meta[name="base-api-url"]').attr('content') + '/vod/thumbnail',
                            {
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                beforeSend: function (request) {
                                    request.setRequestHeader(
                                        'Authorization',
                                        'Bearer ' + requestFactory.access_token
                                    );
                                },
                                success(data) {
                                    var videoIndex = $('#modal').val();
                                    $('.uploaded_img').attr('src', data.info);
                                    $('.uploaded_img').show();
                                    scope.tvsSelectedVideo.thumbnail = data.info;
                                    scope.tvsSelectedVideo.thumbnail_image = data.info;
                                    scope.tvsSelectedVideo.selected_thumb = data.info;
                                    scope.tvsSelectedVideo.is_thumbnail_updated = 1;
                                    scope.$apply();
                                    $('.loader-container').hide();
                                    $('#modal').modal('hide');
                                },
                                error() {
                                    $('.loader-container').hide();
                                    $('.error_msg')
                                        .show()
                                        .text(
                                            'Please upload bigger image, click cancel to continue'
                                        );
                                }
                            }
                        );
                    }, 'image/jpeg');
                }
            );

            /*
             * Post Image Upload Part
             */

            var posterImage = document.getElementById('poster_image');
            $(document).on('change', '.uploadPosterImg', function (e) {
                var videoItem = $(this).data('video-index');
                scope.errors[videoItem] = {};
                var ValidImageTypes = ['image/jpeg', 'image/png'];
                var files = e.target.files;
                var fileType = files[0].type;
                if ($.inArray(fileType, ValidImageTypes) < 0) {
                    scope.$apply();
                    // BEGIN : To show invalid error message in the croppre box
                    $('#poster_modal').modal('show');
                    $('.crop-body').hide();
                    $('#submit_poster_image').hide();
                    $('.poster_error_msg')
                        .show()
                        .text(
                            'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                        );
                    // END : To show invalid error message in the croppre box
                    return;
                }
                $('.crop-body').show();

                var videoIndex = e.target.getAttribute('data-video-index');
                $('#poster_modal .video-index').val(videoIndex);
                readAsPosterUrl(this, videoIndex);
            });
            var cropBoxImgData;
            var canvasImgData;
            var cropperImg;

            $(document).on('show.bs.modal', '#poster_modal', function () {
                $('#submit_poster_image').show();
                $('.poster_error_msg').hide();
                setTimeout(function () {
                    cropperImg = new Cropper(posterImage, {
                        autoCropArea: 1,
                        viewMode: 3,
                        aspectRatio: 1180 / 600,
                        preview: '.poster_img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 1180,
                        minCropBoxHeight: 600,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 1180, height: 600 };
                            cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
                        }
                    });
                }, 500);
            });
            $(document).on('hidden.bs.modal', '#poster_modal', function () {
                document.getElementsByClassName('uploadPosterImg')[0].value = '';
                $('#submit_poster_image').prop('disabled', false);
                cropperImg.destroy();
            });
            $(document).on(
                'click',
                '#submit_poster_image',
                requestFactory.access_token,
                function () {
                    cropBoxImgData = cropperImg.getCropBoxData();
                    canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
                        var formImgData = new FormData();
                        formImgData.append('module', 'video');
                        formImgData.append('size', 'poster');
                        formImgData.append('image', blob);
                        $('.crop-body').hide();
                        $('.poster_loader-container').show();
                        $('#submit_poster_image').prop('disabled', true);
                        $.ajax(
                            $('meta[name="base-api-url"]').attr('content') + '/vod/poster',
                            {
                                method: 'POST',
                                data: formImgData,
                                processData: false,
                                contentType: false,
                                beforeSend: function (request) {
                                    request.setRequestHeader(
                                        'Authorization',
                                        'Bearer ' + requestFactory.access_token
                                    );
                                },
                                success(data) {
                                    var videoIndex = $('#poster_modal').val();
                                    $('.uploaded_poster_img').attr('src', data.info);
                                    $('.uploaded_poster_img').show();
                                    scope.tvsSelectedVideo.poster_image = data.info;
                                    scope.tvsSelectedVideo.is_posterimg_updated = 1;
                                    scope.$apply();
                                    $('.poster_loader-container').hide();
                                    $('#poster_modal').modal('hide');
                                },
                                error() {
                                    $('.poster_loader-container').hide();
                                    $('.poster_error_msg')
                                        .show()
                                        .text(
                                            'Please upload bigger image, click cancel to continue'
                                        );
                                }
                            }
                        );
                    }, 'image/jpeg');
                }
            );
        });

        /**
         * End of image upload script
         * */

        function readAsPosterUrl(input, videoIndex) {
            if (input.files && input.files[0]) {
                var readerImg = new FileReader();
                readerImg.onload = function (e) {
                    document.getElementById('poster_image').src = e.target.result;
                };
                readerImg.onloadend = function (e) {
                    $('#poster_modal').modal('show');
                };
                readerImg.readAsDataURL(input.files[0]);
            }
        }

        // ==================================================**************************************************==================================================

        this.fetchData = function (id) {
            scope.editPage = true;
            requestFactory.get(
                requestFactory.getUrl('tv-show/tvshow-to-edit/' + id),
                function (response) {

                    if (response && response.response && response.response.length > 0) {
                        scope.tvsSelectedVideo = response.response[0];
                        console.log("fetch data:", scope.tvsSelectedVideo);

                        // ✅ Parse content_sets safely
                        let bundles = [];
                        let parsedContentSets = [];

                        if (typeof scope.tvsSelectedVideo.content_sets === "string") {
                            try {
                                parsedContentSets = JSON.parse(scope.tvsSelectedVideo.content_sets);
                            } catch (e) {
                                console.error("❌ Invalid JSON in content_sets:", e);
                            }
                        }

                        // ✅ Populate tvsSelectedVideo.organization for the UI Dropdown using get_all_organization if available
                        if (scope.tvsSelectedVideo.get_all_organization && Array.isArray(scope.tvsSelectedVideo.get_all_organization)) {
                            scope.tvsSelectedVideo.organization = scope.tvsSelectedVideo.get_all_organization.map(org => org.id);
                        } else {
                            scope.tvsSelectedVideo.organization = parsedContentSets.map(org => org.organization_id);
                        }

                        // ✅ Determine which set to use for Assigned Bundle List
                        // User wants ONLY content_sets data to be shown
                        if (parsedContentSets.length > 0) {
                            bundles = parsedContentSets;
                        } else if (scope.tvsSelectedVideo.get_all_organization && Array.isArray(scope.tvsSelectedVideo.get_all_organization)) {
                            bundles = scope.tvsSelectedVideo.get_all_organization.map(org => ({
                                organization_id: org.id,
                                organization_name: org.organization_name
                            }));
                        } else if (Array.isArray(scope.tvsSelectedVideo.content_sets)) {
                            bundles = scope.tvsSelectedVideo.content_sets;
                        }

                        // scope.tvsSelectedVideo.organization = bundles.map(org => org.organization_id); [Handled above]
                        const allBundles = scope.tvsSelectedVideo.channel_sets || [];

                        let mergedOrganizations = [];
                        if (bundles.length > 0) {
                            mergedOrganizations = bundles.map(org => {
                                const orgBundles = allBundles.filter(b => b.organization_id === org.organization_id);
                                // console.log(`🔍 Org ${org.organization_name} (ID: ${org.organization_id}) bundles:`, orgBundles);
                                return {
                                    organization_id: org.organization_id,
                                    organization_name: org.organization_name,
                                    bundles: orgBundles
                                };
                            });
                        } else {
                            console.warn("⚠️ No bundles found to merge!");
                        }

                        self.selectedVideo = self.selectedVideo || {};
                        self.selectedVideo.bundles = mergedOrganizations;

                        scope.tvsSelectedVideo.selectedBundles = mergedOrganizations;

                        if (typeof scope.tvsSelectedVideo.category === "string") {
                            try {
                                scope.tvsSelectedVideo.category = JSON.parse(scope.tvsSelectedVideo.category);
                            } catch (e) {
                                scope.tvsSelectedVideo.category = [];
                            }
                        }

                        if (typeof scope.tvsSelectedVideo.geo_block_country_list === "string") {
                            try {
                                scope.tvsSelectedVideo.geo_block_country_list = JSON.parse(scope.tvsSelectedVideo.geo_block_country_list);
                            } catch (e) {
                                scope.tvsSelectedVideo.geo_block_country_list = [];
                            }
                        }

                        if (typeof scope.tvsSelectedVideo.timeParts === "string") {
                            try {
                                scope.tvsSelectedVideo.timeParts = JSON.parse(scope.tvsSelectedVideo.timeParts);
                            } catch (e) {
                                scope.tvsSelectedVideo.timeParts = {};
                            }
                        }

                        scope.tvsSelectedVideo.scheduled_time = toDateTime(scope.tvsSelectedVideo.scheduled_time);

                        scope.tvsSelectedVideo.is_active = (scope.tvsSelectedVideo.is_active == 1);
                        scope.tvsSelectedVideo.is_parental = (scope.tvsSelectedVideo.is_parental == 1);
                        scope.tvsSelectedVideo.geo_policy = (scope.tvsSelectedVideo.geo_policy == 1);
                        scope.tvsSelectedVideo.scheduled_publishing = (scope.tvsSelectedVideo.scheduled_publishing == 1);
                        scope.tvsSelectedVideo.publish_now = (scope.tvsSelectedVideo.publish_now == 1);

                        scope.tvsSelectedVideo.playback_token = parseInt(scope.tvsSelectedVideo.playback_token);
                        scope.tvsSelectedVideo.policy = parseInt(scope.tvsSelectedVideo.policy);

                        setTimeout(() => {
                            $('.hello').datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                            })
                        }, 1000);

                        scope.$applyAsync();

                        scope.$applyAsync(() => {
                            self.fetchTvShowSet();
                        });
                    }
                },
            );
        };

        // ==================================================**************************************************==================================================
        // organization fetch code
        // ==================================================**************************************************==================================================
        this.fetchTvShowSet = () => {
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'), scope.defineProperties,
                (response) => {

                    const data = response?.data?.data;
                    if (!Array.isArray(data)) {
                        console.error("❌ Invalid organization data!");
                        return;
                    }

                    // Group by organization_id
                    const grouped = {};

                    data.forEach(item => {
                        const orgId = item.organization_id;
                        if (!grouped[orgId]) {
                            grouped[orgId] = {
                                organization_id: orgId,
                                organization_name: item.getorg?.organization_name || "Unknown",
                                bundles: []
                            };
                        }
                        grouped[orgId].bundles.push({
                            id: item.id,
                            name: item.name
                        });
                    });

                    // Convert object to array
                    let availableList = Object.values(grouped);

                    // 🛠️ Hydrate selectedBundles if bundles are missing (Fix for empty "Assigned" view)
                    if (scope.tvsSelectedVideo && scope.tvsSelectedVideo.selectedBundles) {
                        scope.tvsSelectedVideo.selectedBundles.forEach(assignedOrg => {
                            const orgData = grouped[assignedOrg.organization_id];
                            if (orgData && (!assignedOrg.bundles || assignedOrg.bundles.length === 0)) {
                                // If no specific bundles assigned, assume ALL are relevant (or at least show them)
                                // Cloning to avoid reference issues
                                assignedOrg.bundles = orgData.bundles.map(b => ({ ...b }));
                            }
                        });
                    }

                    // 🔍 Filter availableList to ONLY include organizations present in the current vod's allowed list
                    if (scope.tvsSelectedVideo && Array.isArray(scope.tvsSelectedVideo.organization) && scope.tvsSelectedVideo.organization.length > 0) {
                        const allowedOrgIds = scope.tvsSelectedVideo.organization.map(id => parseInt(id));
                        availableList = availableList.filter(org => allowedOrgIds.includes(parseInt(org.organization_id)));
                    }

                    if (scope.tvsSelectedVideo?.selectedBundles?.length) {
                        availableList = availableList.map(org => {
                            const assignedOrg = scope.tvsSelectedVideo.selectedBundles.find(
                                o => o.organization_id === org.organization_id
                            );
                            if (assignedOrg) {
                                // Remove bundles that are already assigned
                                org.bundles = org.bundles.filter(
                                    bundle => !assignedOrg.bundles.some(b => b.id === bundle.id)
                                );
                            }
                            return org;
                        }).filter(org => org.bundles.length > 0); // Remove orgs with no available bundles
                    }


                    scope.TvShowSetList = availableList;
                    // console.log("✅ Grouped Organization Data:", scope.TvShowSetList);
                }
            );
        }

        scope.tvsGridCtrl.assignSelectedBundles = function () {
            const ctrl = scope.tvsGridCtrl;

            if (!ctrl.selectedVideo) {
                ctrl.selectedVideo = {};
            }

            const newOrgs = Array.isArray(ctrl.selectedBundles) ? ctrl.selectedBundles.map(org => ({
                organization_id: org.organization_id,
                organization_name: org.organization_name,
                bundles: org.bundles.map(b => ({
                    id: b.id,
                    name: b.name
                }))
            })) : [];

            let existingOrgs = Array.isArray(ctrl.selectedVideo.bundles) ? ctrl.selectedVideo.bundles : [];

            newOrgs.forEach(newOrg => {
                const existing = existingOrgs.find(o => o.organization_id === newOrg.organization_id);
                if (existing) {
                    // merge bundles without duplicates
                    const mergedBundles = [
                        ...existing.bundles,
                        ...newOrg.bundles.filter(nb => !existing.bundles.some(eb => eb.id === nb.id))
                    ];
                    existing.bundles = mergedBundles;
                } else {
                    existingOrgs.push(newOrg);
                }
            });

            ctrl.selectedVideo.bundles = existingOrgs;


            // if (Array.isArray(ctrl.selectedBundles) && ctrl.selectedBundles.length > 0) {
            //     ctrl.selectedVideo.bundles = ctrl.selectedBundles.map(org => ({
            //         organization_id: org.organization_id,
            //         organization_name: org.organization_name,
            //         bundles: org.bundles.map(b => ({
            //             id: b.id,
            //             name: b.name
            //         }))
            //     }));

            //     // console.log("✅ Assigned grouped bundles to selectedVideo:", ctrl.selectedVideo.bundles);
            // } else {
            //     ctrl.selectedVideo.bundles = [];
            //     console.warn("⚠️ No bundles selected.");
            // }

            $('#add-bundles').modal('hide');
        };

        scope.removeBundle = function (org) {
            const ctrl = scope.tvsGridCtrl;

            scope.TvShowSetList = scope.TvShowSetList || [];
            scope.tvsSelectedVideo.selectedBundles = scope.tvsSelectedVideo.selectedBundles || [];

            if (ctrl.selectedVideo?.bundles?.length) {
                ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(
                    b => b.organization_id !== org.organization_id
                );
                // console.log("🗑️ Removed from selectedVideo.bundles:", org);
            }

            scope.tvsSelectedVideo.selectedBundles = scope.tvsSelectedVideo.selectedBundles.filter(
                o => o.organization_id !== org.organization_id
            );

            const existingOrg = scope.TvShowSetList.find(
                o => o.organization_id === org.organization_id
            );

            if (existingOrg) {
                // Merge bundles without duplication
                org.bundles.forEach(bundle => {
                    const alreadyExists = existingOrg.bundles.some(b => b.id === bundle.id);
                    if (!alreadyExists) {
                        existingOrg.bundles.push({
                            id: bundle.id,
                            name: bundle.name
                        });
                    }
                });
                // console.log("↩️ Merged bundles back to existing organization in TvShowSetList:", org);
            } else {
                // Add as new org entry
                scope.TvShowSetList.push({
                    organization_id: org.organization_id,
                    organization_name: org.organization_name,
                    bundles: org.bundles.map(b => ({ id: b.id, name: b.name }))
                });
                // console.log("🆕 Returned full organization to TvShowSetList:", org);
            }

            // const ctrl = scope.tvsGridCtrl;
            // ctrl.selectedVideo.bundles = (ctrl.selectedVideo.bundles || []).filter(b => b.id !== bundle.id);
            // // console.log("🗑️ Removed Bundle:", bundle);

            // const exists = ctrl.OrganizationList.some(b => b.id === bundle.id);
            // if (!exists) {
            //     ctrl.OrganizationList.push(bundle);
            //     // console.log("🔁 Returned to OrganizationList:", bundle);
            // }

            scope.$applyAsync();
        };

        $('#assigned-content').on('shown.bs.modal', function () {
            // console.log("✅ Modal opened — initializing drag-and-drop...");
            ContentDragDrop();
        });

        function ContentDragDrop() {
            const addedBundles = document.getElementById('addedBundles');
            const availableBundles = document.getElementById('availableBundles');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedBundles.innerHTML = '';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableBundles .content-container').forEach(card => {
                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedBundles drop zone...");
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                // console.log(`📤 Drop detected. Dropped Bundle ID = ${draggedId}`);

                const card = availableBundles.querySelector(`[data-id="${draggedId}"]`);
                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                if (addedBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedBundles.removeChild(clone);
                    availableBundles.appendChild(card);
                    // console.log(`🗑️ Removed from assigned. Returned ID = ${draggedId}`);
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                addedBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('videoEditForm')).scope();
                const ctrl = scope?.tvsGridCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or tvsGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];

                addedBundles.querySelectorAll('.content-container').forEach(orgCard => {
                    const orgId = parseInt(orgCard.getAttribute('data-id'));
                    const orgData = scope.TvShowSetList.find(o => o.organization_id === orgId);

                    if (orgData) {
                        // Prepare bundles array from HTML (to stay consistent with structure)
                        const bundles = [];
                        orgCard.querySelectorAll('.item-box').forEach(bundleElem => {
                            const bundleName = bundleElem.textContent.trim();
                            const matchedBundle = orgData.bundles.find(b => b.name === bundleName);
                            if (matchedBundle) {
                                bundles.push(matchedBundle);
                            }
                        });

                        // Push complete organization data (with bundles)
                        ctrl.selectedBundles.push({
                            organization_id: orgData.organization_id,
                            organization_name: orgData.organization_name,
                            bundles: bundles
                        });

                        // console.log(`📦 Added Organization: ${orgData.organization_name} with ${bundles.length} bundles`);
                    }
                });

                // addedBundles.querySelectorAll('.content-container').forEach(card => {
                //     const id = parseInt(card.getAttribute('data-id'));
                //     const bundle = ctrl.OrganizationList.find(b => b.id === id);
                //     if (bundle) {
                //         ctrl.selectedBundles.push(bundle);
                //         // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                //     }
                // });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }


        // ============***************=============
        this.fetchDrm = function () {
            requestFactory.post(
                requestFactory.getUrl('drm/profile/records'), this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.drmProfiles = response.data.data;
                        // console.log("✅ DRM profiles fetched successfully.", this.drmProfiles);
                    } else {
                        console.error("❌ DRM profiles not fetched!");
                    }
                }
            );
        };
        this.fetchDrm();

        // ============***************=============
        this.fetchOrganization = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.OrganizationList = response.data.data;
                        // console.log(this.Onlyorganization);

                    }
                }
            )
        }
        this.fetchOrganization();

        // ============***************=============
        this.fetchCategories = function () {
            requestFactory.get(
                requestFactory.getUrl('series-category/get/records'),
                (response) => {
                    if (response && response.data && Array.isArray(response.data)) {
                        this.SeriesCategoryList = response.data.filter(function (item) {
                            return item.series_categorie_name && item.series_categorie_name.trim() !== '';
                        });
                    }
                }
            );
        }
        this.fetchCategories();

        // ============***************=============
        this.fetchPolicy = function () {
            requestFactory.post(
                requestFactory.getUrl('stream-services/streaming-url-policy/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const data = response.data.data;
                        const filter = data.filter(policy => policy.status == 1);
                        this.PolicyList = filter;
                    }
                    // console.log(response);
                }

            );
        }
        this.fetchPolicy();

        // ============***************=============
        this.fetchGeoBlocing = function () {
            requestFactory.post(
                requestFactory.getUrl('geo-blocking/geo-restrictions/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const filterdata = response.data.data;
                        this.geoBlockList = filterdata.filter(groblocing => groblocing.geo_ip_status == 1);
                        // console.log(this.geoBlockList);

                    }
                }
            );
        }
        this.fetchGeoBlocing();

        // ============***************=============
        this.fetchPBT = function () {
            requestFactory.post(
                requestFactory.getUrl('setting/play-back-token/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const pbt = response.data.data;
                        const filterpbt = pbt.filter(playback => playback.is_active == 1);
                        this.playbackTokenList = filterpbt;
                    }
                }
            )
        }
        this.fetchPBT();

        // ============***************=============

    }
]);

/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if (angular.isObject(window.gridControllers)) {
    for (var controller in window.gridControllers) {
        if (
            angular.isArray(window.gridControllers[controller]) ||
            angular.isFunction(window.gridControllers[controller])
        ) {
            vodUpload.controller(controller, window.gridControllers[controller]);
        }
    }
}

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
    angular.bootstrap(document, ['vodUpload']);
});
