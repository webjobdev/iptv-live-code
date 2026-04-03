var tvsUpload = angular.module('tvsUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = tvsUpload;

tvsUpload.directive('baseValidator', validatorDirective);
tvsUpload.factory('requestFactory', requestFactory);
tvsUpload.service('commonGeofencingService', commonGeofencing);

tvsUpload.controller('TvShowSeasonUploadController', [
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
        this.tvShowId = null;
        this.videoSubmitted = false;
        scope.tvsnSelectedVideo = {};
        scope.editPage = false;

        this.defineProperties = function (data) {
            this.info = data.info;
            // this.allCollection = data.info.allCollection;
            // this.allSeasons = data.info.allSeasons;
            // this.allExams = data.info.allCollection;
            // this.allCategories = data.info.allCategories;
            // this.radioCategories = data.info.allRdioCategories;
            // this.liveCategories = data.info.allLiveCategories;
            // this.formatCategories = angular.copy(this.allCategories);
            var result = [];
            // this.formatCategories.forEach(function (item, index) {
            //     if (item.id) {
            //         if (item.child_category.length > 0) {
            //             item.child_category.forEach(function (child, i) {
            //                 var newIndex = result.length;
            //                 result[newIndex] = {};
            //                 result[newIndex].id = child.id;
            //                 result[newIndex].title = child.title;
            //                 result[newIndex].parent = item.title;
            //             });
            //         } else {
            //             var newIndex = result.length;
            //             result[newIndex] = {};
            //             result[newIndex].id = '';
            //             result[newIndex].title = '';
            //             result[newIndex].parent = item.title;
            //         }
            //     }
            // });
            this.formatCategories = result;
            // this.allSeries = data.info.allSeries;
            // this.language = data.info.language;
            // this.ads_info = data.info.ads_info;
            // if (this.language.length != 0) {
            //     scope.selectedLanguage = this.language[0].id;
            //     scope.defaultLanguage = this.language[0].id;
            // }
            // this.transcodedInfo = data.info.transcodedInfo;
            // scope.livedetails = data.info.livesyncdata[0];
            // this.numberOfActivePresets = data.info.numberOfActivePresets;
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

        this.init = function () {
            scope.livePage = true;
            // scope.tvsnSelectedVideo.liveType = 'hls';
            // scope.tvsnSelectedVideo.aspect_ratio = '640X360';
        };

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

        scope.togglePublishDate = function () {
            if (scope.tvsnSelectedVideo.publish_now == 1) {
                const now = new Date();
                // Format as YYYY-MM-DD HH:MM:SS
                const formatted = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                scope.tvsnSelectedVideo.publish_date = formatted;
            }
        };
        // date format code end

        // create vod code
        this.saveSeason = function ($event) {
            scope.errors = {};
            console.log(scope.tvsnSelectedVideo);

            const currentUrl = window.location.href;
            const encodeId = currentUrl.split("/").pop();
            const decodeId = atob(encodeId);

            // set hidden input value
            scope.tvsnSelectedVideo.tv_show_id = decodeId;

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.tvsnSelectedVideo.is_active = scope.tvsnSelectedVideo.is_active ? true : false;

                if (this.videoSubmitted == false) {
                    this.videoSubmitted = true;
                    requestFactory.post(
                        requestFactory.getUrl('create/tv-show/season'),
                        scope.tvsnSelectedVideo,
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
        this.updateSeason = function ($event) {
            scope.errors = {};
            const currentUrl = window.location.pathname;
            // console.log("📌 Current URL:", currentUrl);

            const encodedId = currentUrl.split("/").pop();
            // console.log("📌 Encoded ID from URL:", encodedId);

            const decodedId = atob(encodedId);

            let bundles = scope.tvsnGridCtrl.selectedVideo.bundles || [];

            scope.tvsnSelectedVideo.content_sets = bundles.map(org => {
                return {
                    organization_id: org.organization_id || org.id,
                    organization_name: org.organization_name,
                    channel_contentset: (org.bundles || []).map(b => b.id)
                };
            });

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.tvsnSelectedVideo.geoType = scope.geoType;
                scope.tvsnSelectedVideo.allowedData = Object.assign(
                    {},
                    scope.allowedData[decodedId]
                );
                requestFactory.post(
                    requestFactory.getUrl('tv-show-season/edit/' + decodedId),
                    scope.tvsnSelectedVideo,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        window.location.reload();
                    }, this.fillError
                );
            }
        };
        // edit vod code end

        // fetch season list code
        this.fetchSeason = function () {
            const self = this;
            let currentUrl = window.location.pathname;
            let encodedId = currentUrl.split("/").pop();
            let decodedId = atob(encodedId);

            if (currentUrl.includes("/add/season/")) {

                requestFactory.post(
                    requestFactory.getUrl("tv-show/season/records?tv_show_id=" + decodedId),
                    this.defineProperties,
                    function (response) {
                        if (response && Array.isArray(response.data)) {
                            let allSeasons = response.data;
                            self.seasons = allSeasons;

                            if (self.seasons.length > 0) {
                                self.tvShowId = self.seasons[0].tv_show_id;
                            }

                        } else {
                            console.error("❌ Invalid response format:", response);
                        }
                    }
                );

            } else if (currentUrl.includes("/edit-tv-show-season/season-id/")) {
                self.seasonId = decodedId;
                requestFactory.post(
                    requestFactory.getUrl('tv-show/season/records'),
                    this.defineProperties,
                    function (response) {
                        if (response && response.data && Array.isArray(response.data.data)) {
                            let seasonData = response.data.data.find(season => season.id == self.seasonId);

                            if (seasonData) {
                                self.tvShowId = seasonData.tv_show_id;
                            }
                        }
                    }
                );
            } else {
                console.error("❌ URL does not match add/season or edit-tv-show-season route.");
            }
        };
        this.fetchSeason();
        // fetch season list code

        // remove season code
        this.removeSeason = function () {
            const currentUrl = window.location.pathname;
            const encodedId = currentUrl.split("/").pop();
            const decodedId = atob(encodedId);

            requestFactory.post(
                requestFactory.getUrl('remove/season/' + decodedId), this.defineProperties,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = requestFactory.getTemplateUrl('admin/tvshow');
                    }, 650);
                }, this.fillError
            );
        }
        // remove season code

        // open episode page
        this.addSeasonEpisode = function ($event) {
            const currentUrl = window.location.href;
            const encodeId = currentUrl.split("/").pop();
            const url = `${appUrl}admin/tvshow/season/episode/${encodeId}`;
            window.location.href = url;
        }
        // open episode page

        // fetch episode data code
        this.fetchEpisode = function () {
            const currentUrl = window.location.href;
            const encoedId = currentUrl.split("/").pop();
            const decode = atob(encoedId);

            requestFactory.get(
                requestFactory.getUrl('tv-show/fetch/season/episode/records?season_id=' + decode),
                function (response) {
                    if (response && response.data) {

                        const list = response.data;
                        self.episodeList = list;
                        // console.log("✅ Final Episode List:", self.episodeList);
                    } else {
                        console.error("❌ Episode data missing or not an array");
                    }
                }
            );
        };

        this.fetchEpisode();
        // fetch episode data code

        // episode toggle button
        scope.episodeToggleButton = function (episode, id) {
            var toggleID = id;
            episode.is_active = episode.is_active == 1 ? 0 : 1;

            const payload = {
                id: episode.id,
                is_active: episode.is_active
            };
            // console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('episode/toggle/edit/' + toggleID),
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
        // episode toggle button

        // delete episode
        scope.deleteSingleRecord = function (episode, id) {
            var toggleID = id;

            requestFactory.post(
                requestFactory.getUrl('episode/delete/' + toggleID),
                function (response) {
                    requestFactory.getToaster(),
                        requestFactory.setToaster('success', 'Episode Delete.');
                    $timeout(function () {
                        window.location.reload();
                    }, 650);
                }
            );
        };
        // delete episode

        // id encode id code
        scope.encodeId = function (id) {
            return btoa(id);
        };
        // id encode id code

        // back to season code
        this.BackToSeason = function (id) {
            if (!id) {
                console.error("tvShowId not available yet!");
                return;
            }

            const encodeId = btoa(id);
            window.location.href = appUrl + 'admin/tvshow/add/season/' + encodeId;
        };
        // back to season code

        // back to tv show code
        this.BackToTvShow = function (id) {

            let currentUrl = window.location.pathname;
            let encodedId = currentUrl.split("/").pop();
            let decodedId = atob(encodedId);

            if (!id) {
                console.error("tvShowId not available yet!");
                return;
            }

            const encodeId = btoa(decodedId);
            const url = appUrl + 'admin/tvshow/edit-tv-show/' + encodeId;
            window.location.href = url;
        };
        // back to tv show code



        // tv show season fetch data code
        this.fetchData = function (id) {
            scope.editPage = true;
            requestFactory.get(
                requestFactory.getUrl('tv-show/season-to-edit/' + id),
                function (response) {
                    // console.log("fetch data:", response);
                    if (response && response.response && response.response.length > 0) {
                        scope.tvsnSelectedVideo = response.response[0];

                        // ✅ Parse content_sets safely
                        let bundles = [];
                        let parsedContentSets = [];

                        if (typeof scope.tvsnSelectedVideo.content_sets === "string") {
                            try {
                                parsedContentSets = JSON.parse(scope.tvsnSelectedVideo.content_sets);
                            } catch (e) {
                                console.error("❌ Invalid JSON in content_sets:", e);
                            }
                        }

                        // ✅ Populate tvsnSelectedVideo.organization for the UI Dropdown using get_all_organization if available
                        if (scope.tvsnSelectedVideo.get_all_organization && Array.isArray(scope.tvsnSelectedVideo.get_all_organization)) {
                            scope.tvsnSelectedVideo.organization = scope.tvsnSelectedVideo.get_all_organization.map(org => org.id);
                        } else {
                            scope.tvsnSelectedVideo.organization = parsedContentSets.map(org => org.organization_id);
                        }

                        // ✅ Determine which set to use for Assigned Bundle List
                        // User wants ONLY content_sets data to be shown
                        if (parsedContentSets.length > 0) {
                            bundles = parsedContentSets;
                        } else if (scope.tvsnSelectedVideo.get_all_organization && Array.isArray(scope.tvsnSelectedVideo.get_all_organization)) {
                            bundles = scope.tvsnSelectedVideo.get_all_organization.map(org => ({
                                organization_id: org.id,
                                organization_name: org.organization_name
                            }));
                        } else if (Array.isArray(scope.tvsnSelectedVideo.content_sets)) {
                            bundles = scope.tvsnSelectedVideo.content_sets;
                        }

                        // if (typeof scope.tvsnSelectedVideo.content_sets === "string") {
                        //     try {
                        //         contentSets = JSON.parse(scope.tvsnSelectedVideo.content_sets);
                        //         // console.log("📦 Parsed content_sets (string):", contentSets);
                        //     } catch (e) {
                        //         console.error("❌ Failed to parse content_sets JSON:", e);
                        //         contentSets = [];
                        //     }
                        // } else if (Array.isArray(scope.tvsnSelectedVideo.content_sets)) {
                        //     contentSets = scope.tvsnSelectedVideo.content_sets;
                        //     // console.log("📦 content_sets is already an array:", contentSets);
                        // } else {
                        //     // console.warn("⚠️ content_sets is neither string nor array:", scope.tvsnSelectedVideo.content_sets);
                        // }

                        // ✅ Step 2: Get actual bundle data from backend
                        const allBundles = scope.tvsnSelectedVideo.channel_sets || [];
                        // console.log("🗂️ All Bundles (from backend):", allBundles);

                        // ✅ Step 3: Merge both organization info + bundles
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
                            // console.warn("⚠️ No contentSets found to merge!");
                        }

                        // console.log("✅ Final mergedOrganizations:", mergedOrganizations);

                        // ✅ Step 4: Assign to Angular model
                        self.selectedVideo = self.selectedVideo || {};
                        self.selectedVideo.bundles = mergedOrganizations;
                        // console.log("🎯 Assigned to this.selectedVideo.bundles:", self.selectedVideo.bundles);

                        scope.tvsnSelectedVideo.selectedBundles = scope.tvsnSelectedVideo.selectedBundles || [];

                        mergedOrganizations.forEach(org => {
                            // Check if org already exists in selectedBundles
                            let existingOrg = scope.tvsnSelectedVideo.selectedBundles.find(
                                o => o.organization_id === org.organization_id
                            );

                            if (!existingOrg) {
                                // New organization — push directly
                                scope.tvsnSelectedVideo.selectedBundles.push({
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


                        scope.tvsnSelectedVideo.is_active = (scope.tvsnSelectedVideo.is_active == 1);
                        scope.tvsnSelectedVideo.scheduled_publishing = (scope.tvsnSelectedVideo.scheduled_publishing == 1);
                        scope.tvsnSelectedVideo.expire_time_unlimited = (scope.tvsnSelectedVideo.expire_time_unlimited == 1);
                        scope.tvsnSelectedVideo.publish_now = (scope.tvsnSelectedVideo.publish_now == 1);
                        scope.tvsnSelectedVideo.release_date = new Date(scope.tvsnSelectedVideo.release_date);

                        setTimeout(() => {
                            $('.hello').datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                            })
                        }, 1000);

                        scope.$applyAsync(() => {
                            self.fetchOrganization();
                        });
                    }
                },
            );
        };
        // tv show season fetch data code

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

                    scope.OrganizationList = Object.values(grouped);
                    this.TvShowSetList = scope.OrganizationList;
                }
            );
        }

        scope.tvsnGridCtrl.assignSelectedBundles = function () {
            $('#assigned-content').modal('hide');
        };

        scope.removeBundle = function (org) {
            const ctrl = scope.tvsnGridCtrl;

            // Remove from selected
            if (ctrl.selectedVideo && ctrl.selectedVideo.bundles) {
                ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(b => b.organization_id !== org.organization_id);
            }
            if (scope.tvsnSelectedVideo && scope.tvsnSelectedVideo.selectedBundles) {
                scope.tvsnSelectedVideo.selectedBundles = scope.tvsnSelectedVideo.selectedBundles.filter(
                    o => o.organization_id !== org.organization_id
                );
            }

            // Add back to available
            if (!ctrl.TvShowSetList) ctrl.TvShowSetList = [];

            const existingOrg = ctrl.TvShowSetList.find(o => o.organization_id === org.organization_id);
            if (existingOrg) {
                // Merge bundles back
                const currentBundleIds = new Set(existingOrg.bundles.map(b => b.id));
                org.bundles.forEach(b => {
                    if (!currentBundleIds.has(b.id)) {
                        existingOrg.bundles.push(b);
                    }
                });
            } else {
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

                if (!scope || !scope.tvsnGridCtrl) {
                    console.error("❌ Angular scope or controller not found on videoEditForm!");
                    return;
                }

                const ctrl = scope.tvsnGridCtrl;
                const orgId = parseInt(draggedId);

                // Find in available list
                const availableList = ctrl.TvShowSetList || [];
                const orgIndex = availableList.findIndex(o => o.organization_id === orgId);

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

                if (!scope.tvsnSelectedVideo.selectedBundles) scope.tvsnSelectedVideo.selectedBundles = [];
                scope.tvsnSelectedVideo.selectedBundles.push(newSelection);

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

        /**
         * method to get from categories
        **/
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
                                    scope.tvsnSelectedVideo.thumbnail = data.info;
                                    scope.tvsnSelectedVideo.thumbnail_image = data.info;
                                    scope.tvsnSelectedVideo.selected_thumb = data.info;
                                    scope.tvsnSelectedVideo.is_thumbnail_updated = 1;
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
                                    scope.tvsnSelectedVideo.poster_image = data.info;
                                    scope.tvsnSelectedVideo.is_posterimg_updated = 1;
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
