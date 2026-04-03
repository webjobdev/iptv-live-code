var tvsUpload = angular.module('tvsUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = tvsUpload;

tvsUpload.directive('baseValidator', validatorDirective);
tvsUpload.factory('requestFactory', requestFactory);
tvsUpload.service('commonGeofencingService', commonGeofencing);

tvsUpload.controller('SeasonEpisodeUploadController', [
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
        scope.translationError = false;
        scope.errors = {};
        this.videoSubmitted = false;
        scope.tvsneVideo = {};
        scope.editPage = false;

        this.defineProperties = function (data) {
            this.info = data.info;
            this.allCollection = data.info.allCollection;
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
            // scope.tvsneVideo.liveType = 'hls';
            // scope.tvsneVideo.aspect_ratio = '640X360';
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

        scope.tvsneVideo.timeParts = {
            hour: '00',
            minute: '00',
            second: '00'
        };

        function pad(val) {
            return ('0' + val).slice(-2);
        }

        scope.increment = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.tvsneVideo.timeParts[unit] || 0, 10);
            val = (val + 1) > max ? 0 : val + 1;
            scope.tvsneVideo.timeParts[unit] = pad(val);
            scope.updateModel();
        };

        scope.decrement = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.tvsneVideo.timeParts[unit] || 0, 10);
            val = (val - 1) < 0 ? max : val - 1;
            scope.tvsneVideo.timeParts[unit] = pad(val);
            scope.updateModel();
        };

        scope.updateModel = function () {
            scope.tvsneVideo.timeString =
                `${scope.tvsneVideo.timeParts.hour}:${scope.tvsneVideo.timeParts.minute}:${scope.tvsneVideo.timeParts.second}`;
        };

        scope.togglePublishDate = function () {
            if (scope.tvsneVideo.publish_now == 1) {
                const now = new Date();
                // Format as YYYY-MM-DD HH:MM:SS
                const formatted = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                scope.tvsneVideo.publish_date = formatted;
            }
        };

        // create vod code
        this.saveEpisode = function ($event) {
            const self = this;
            scope.errors = {};
            console.log(scope.tvsneVideo);

            const currentUrl = window.location.href;
            const encodeId = currentUrl.split("/").pop();
            const decodeId = atob(encodeId);
            const redirectId = btoa(decodeId);

            // season id fetch from url
            scope.tvsneVideo.season_id = decodeId;

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.tvsneVideo.is_active = scope.tvsneVideo.is_active ? true : false;

                if (self.videoSubmitted == false) {
                    self.videoSubmitted = true;
                    requestFactory.post(
                        requestFactory.getUrl('create/tv-show/season/episode'),
                        scope.tvsneVideo,
                        function (response) {
                            requestFactory.setToaster('success', response.message);
                            window.location.href = requestFactory.getTemplateUrl(
                                'admin/tvshow/edit-tv-show-season/season-id/' + redirectId
                            );
                        },
                        self.fillError
                    );
                }
            }
        };
        // create vod code end

        // edit vod code
        this.saveEpisodeEdit = function ($event, id) {
            scope.errors = {};
            const currentUrl = window.location.href;
            const encodeId = currentUrl.split("/").pop();

            let bundles = scope.tvsneGridCtrl.selectedVideo.bundles || [];

            scope.tvsneVideo.content_sets = bundles.map(org => {
                return {
                    organization_id: org.organization_id || org.id,
                    organization_name: org.organization_name,
                    channel_contentset: (org.bundles || []).map(b => b.id)
                };
            });

            if (baseValidator.validateAngularForm($event.target, scope)) {
                requestFactory.post(
                    requestFactory.getUrl('tv-show-season/episode/edit/' + id),
                    scope.tvsneVideo,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        window.location.href = requestFactory.getTemplateUrl(
                            'admin/tvshow/edit-tv-show-season/season-id/' + encodeId
                        );
                    },
                    // function (response) {
                    this.fillError
                    // }
                );
            }
        };
        // edit vod code end

        // go to back page
        this.backSeason = function ($event) {
            scope.errors = {};
            const currentUrl = window.location.href;
            const encode = currentUrl.split("/").pop();
            const decode = atob(encode);

            const url = `${appUrl}admin/tvshow/edit-tv-show-season/season-id/${encode}`;
            window.location.href = url;

        }
        // go to back page

        // fetch data
        this.fetchData = function (id) {
            scope.editPage = true;
            requestFactory.get(
                requestFactory.getUrl('tv-show/season/episode-to-edit/' + id),
                function (response) {
                    // console.log(response);
                    if (response && response.response && response.response.length > 0) {
                        scope.tvsneVideo = response.response[0];

                        // ✅ Parse content_sets safely
                        let bundles = [];
                        let parsedContentSets = [];

                        if (typeof scope.tvsneVideo.content_sets === "string") {
                            try {
                                parsedContentSets = JSON.parse(scope.tvsneVideo.content_sets);
                            } catch (e) {
                                console.error("❌ Invalid JSON in content_sets:", e);
                            }
                        }

                        // ✅ Populate tvsneVideo.organization for the UI Dropdown using get_all_organization if available
                        if (scope.tvsneVideo.get_all_organization && Array.isArray(scope.tvsneVideo.get_all_organization)) {
                            scope.tvsneVideo.organization = scope.tvsneVideo.get_all_organization.map(org => org.id);
                        } else {
                            scope.tvsneVideo.organization = parsedContentSets.map(org => org.organization_id);
                        }

                        // ✅ Determine which set to use for Assigned Bundle List
                        // User wants ONLY content_sets data to be shown
                        if (parsedContentSets.length > 0) {
                            bundles = parsedContentSets;
                        } else if (scope.tvsneVideo.get_all_organization && Array.isArray(scope.tvsneVideo.get_all_organization)) {
                            bundles = scope.tvsneVideo.get_all_organization.map(org => ({
                                organization_id: org.id,
                                organization_name: org.organization_name
                            }));
                        } else if (Array.isArray(scope.tvsneVideo.content_sets)) {
                            bundles = scope.tvsneVideo.content_sets;
                        }

                        // scope.tvsneVideo.organization = bundles.map(org => org.organization_id); [Handled above]

                        // if (typeof scope.tvsneVideo.timeParts === "string") {
                        //     try {
                        //         scope.tvsneVideo.timeParts = JSON.parse(scope.tvsneVideo.timeParts);
                        //     } catch (e) {
                        //         scope.tvsneVideo.timeParts = {};
                        //     }
                        // }

                        // let contentSets = [];
                        // if (typeof scope.tvsneVideo.content_sets === "string") {
                        //     try {
                        //         contentSets = JSON.parse(scope.tvsneVideo.content_sets);
                        //         // console.log("📦 Parsed content_sets (string):", contentSets);
                        //     } catch (e) {
                        //         console.error("❌ Failed to parse content_sets JSON:", e);
                        //         contentSets = [];
                        //     }
                        // } else if (Array.isArray(scope.tvsneVideo.content_sets)) {
                        //     contentSets = scope.tvsneVideo.content_sets;
                        //     // console.log("📦 content_sets is already an array:", contentSets);
                        // } else {
                        //     console.warn("⚠️ content_sets is neither string nor array:", scope.tvsneVideo.content_sets);
                        // }

                        // ✅ Step 2: Get actual bundle data from backend
                        const allBundles = scope.tvsneVideo.channel_sets || [];
                        // console.log("🗂️ All Bundles (from backend):", allBundles);

                        // ✅ Step 3: Merge both organization info + bundles
                        let mergedOrganizations = [];
                        if (Array.isArray(bundles) && bundles.length > 0) {
                            mergedOrganizations = bundles.map(org => {
                                const orgBundles = allBundles.filter(b => b.organization_id == org.organization_id);
                                // console.log(`🔍 Org ${org.organization_name} (ID: ${org.organization_id}) bundles:`, orgBundles);
                                return {
                                    organization_id: org.organization_id,
                                    organization_name: org.organization_name,
                                    bundles: orgBundles
                                };
                            });
                        } else {
                            console.warn("⚠️ No contentSets found to merge!");
                        }

                        // console.log("✅ Final mergedOrganizations:", mergedOrganizations);

                        // ✅ Step 4: Assign to Angular model
                        self.selectedVideo = self.selectedVideo || {};
                        self.selectedVideo.bundles = mergedOrganizations;
                        // console.log("🎯 Assigned to this.selectedVideo.bundles:", self.selectedVideo.bundles);

                        scope.tvsneVideo.selectedBundles = scope.tvsneVideo.selectedBundles || [];

                        mergedOrganizations.forEach(org => {
                            // Check if org already exists in selectedBundles
                            let existingOrg = scope.tvsneVideo.selectedBundles.find(
                                o => o.organization_id == org.organization_id
                            );

                            if (!existingOrg) {
                                // New organization — push directly
                                scope.tvsneVideo.selectedBundles.push({
                                    organization_id: org.organization_id,
                                    organization_name: org.organization_name,
                                    bundles: org.bundles
                                });
                            } else {
                                // Existing org — merge bundles without duplication
                                org.bundles.forEach(bundle => {
                                    const exists = existingOrg.bundles.some(
                                        b => b.id === bundle.id
                                    );
                                    if (!exists) existingOrg.bundles.push(bundle);
                                });
                            }
                        });

                        scope.tvsneVideo.episode_number = Number(scope.tvsneVideo.episode_number);
                        scope.tvsneVideo.is_active = (scope.tvsneVideo.is_active == 1);
                        scope.tvsneVideo.scheduled_publishing = (scope.tvsneVideo.scheduled_publishing == 1);
                        scope.tvsneVideo.publish_now = (scope.tvsneVideo.publish_now == 1);
                        scope.tvsneVideo.release_date = new Date(scope.tvsneVideo.release_date);
                        scope.tvsneVideo.playback_token = parseInt(scope.tvsneVideo.playback_token);
                        scope.tvsneVideo.policy = parseInt(scope.tvsneVideo.policy);

                        setTimeout(() => {
                            $('.hello').datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                            })
                        }, 1000);

                        scope.$applyAsync(() => {
                            self.fetchOrganization();
                        });
                    }
                }
            );
        }
        // fetch data

        // ==================================================**************************************************==================================================
        // organization fetch code
        // ==================================================**************************************************==================================================
        this.fetchOrganization = () => {
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
                    scope.OrganizationList = Object.values(grouped);
                    this.TvShowSetList = scope.OrganizationList;
                    // console.log("✅ Grouped Organization Data:", this.OrganizationList);

                    // if (response && response.data && Array.isArray(response.data.data)) {
                    //     this.OrganizationList = response.data.data;
                    //     // orgbundles(this.OrganizationList);
                    //     // console.log("✅ Organization data fetch successfully.", this.OrganizationList);
                    // } else {
                    //     console.error("❌ Organization data not fetch!");
                    // }
                }
            );
        }
        this.fetchOrganization();
        // this.getORGList =  () => {
        //     console.log(this.OrganizationList, "this.OrganizationList");

        //     return this.OrganizationList;
        // };

        scope.tvsneGridCtrl.assignSelectedBundles = function () {
            $('#assigned-content').modal('hide');
        };

        scope.removeBundle = function (org) {
            const ctrl = scope.tvsneGridCtrl;

            // Remove from selected (scope model)
            if (scope.tvsneVideo && scope.tvsneVideo.selectedBundles) {
                scope.tvsneVideo.selectedBundles = scope.tvsneVideo.selectedBundles.filter(
                    b => b.organization_id != org.organization_id
                );
            }

            // Remove from controller model (if separate)
            if (ctrl.selectedVideo && ctrl.selectedVideo.bundles) {
                ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(
                    b => b.organization_id != org.organization_id
                );
            }

            // Return to available list
            if (!ctrl.TvShowSetList) ctrl.TvShowSetList = [];

            // Check if already in available list (shouldn't be, but good to be safe)
            const exists = ctrl.TvShowSetList.some(o => o.organization_id == org.organization_id);
            if (!exists) {
                ctrl.TvShowSetList.push(org);
            }

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
                // console.warn("❌ Drop zones not found!");
                return;
            }

            // prevent multiple initializations
            if (availableBundles.getAttribute('data-dnd-init')) return;
            availableBundles.setAttribute('data-dnd-init', 'true');

            // --- AVAILABLE (Source) ---
            availableBundles.addEventListener('dragstart', e => {
                const card = e.target.closest('.content-container');
                if (card) {
                    const id = card.getAttribute('data-id');
                    if (id) {
                        e.dataTransfer.setData('text/plain', id);
                        e.dataTransfer.effectAllowed = "move";
                        card.classList.add('dragging');
                        // console.log(`🚀 Drag started: ID = ${id}`);
                    }
                }
            });

            availableBundles.addEventListener('dragend', e => {
                const card = e.target.closest('.content-container');
                if (card) {
                    card.classList.remove('dragging');
                }
            });

            // --- ADDED (Target) ---
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault(); // Necessary for drop to work
                e.dataTransfer.dropEffect = "move";
                addedBundles.classList.add('drag-over');
            });

            addedBundles.addEventListener('dragleave', e => {
                addedBundles.classList.remove('drag-over');
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                addedBundles.classList.remove('drag-over');

                const draggedId = e.dataTransfer.getData('text/plain');
                if (!draggedId) return;

                const formEl = document.getElementById('videoEditForm');
                const scope = angular.element(formEl).scope();

                if (!scope || !scope.tvsneGridCtrl) {
                    console.error("❌ Angular scope or controller not found on videoEditForm!");
                    return;
                }

                const ctrl = scope.tvsneGridCtrl;
                const orgId = parseInt(draggedId);

                // Find in available list
                const availableList = ctrl.TvShowSetList || [];
                const orgIndex = availableList.findIndex(o => o.organization_id == orgId);

                if (orgIndex === -1) {
                    // console.warn(`⚠️ Org ID ${orgId} not found in available list. Maybe already assigned?`);
                    return;
                }

                const orgData = availableList[orgIndex];

                // --- Update Model ---
                // Add to selected bundles
                const newSelection = angular.copy(orgData);

                if (!ctrl.selectedVideo) ctrl.selectedVideo = {};
                if (!ctrl.selectedVideo.bundles) ctrl.selectedVideo.bundles = [];
                ctrl.selectedVideo.bundles.push(newSelection);

                if (!scope.tvsneVideo.selectedBundles) scope.tvsneVideo.selectedBundles = [];
                scope.tvsneVideo.selectedBundles.push(newSelection);

                // Remove from available list
                ctrl.TvShowSetList.splice(orgIndex, 1);

                // Apply changes
                scope.$applyAsync();
            });

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);
                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
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

        this.fetchDrm = function () {
            requestFactory.post(
                requestFactory.getUrl('drm/profile/records'), this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.DrmList = response.data.data;
                        // console.log("✅ DRM profiles fetched successfully.", this.drmProfiles);
                    } else {
                        console.error("❌ DRM profiles not fetched!");
                    }
                }
            );
        };
        this.fetchDrm();
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
                        aspectRatio: 540 / 800,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 540,
                        minCropBoxHeight: 800,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 540, height: 800 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/tv-show/season/episode/thumbnail',
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
                                    scope.tvsneVideo.thumbnail = data.info;
                                    scope.tvsneVideo.thumbnail_image = data.info;
                                    scope.tvsneVideo.selected_thumb = data.info;
                                    scope.tvsneVideo.is_thumbnail_updated = 1;
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
                        aspectRatio: 1280 / 720,
                        preview: '.poster_img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 1280,
                        minCropBoxHeight: 720,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 1280, height: 720 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/tv-show/season/episode/poster',
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
                                    scope.tvsneVideo.poster_image = data.info;
                                    scope.tvsneVideo.is_posterimg_updated = 1;
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
            tvsUpload.controller(controller, window.gridControllers[controller]);
        }
    }
}

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
    angular.bootstrap(document, ['tvsUpload']);
});
