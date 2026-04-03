var channelUpload = angular.module('channelUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = channelUpload;

channelUpload.directive('baseValidator', validatorDirective);
channelUpload.factory('requestFactory', requestFactory);
channelUpload.service('commonGeofencingService', commonGeofencing);

channelUpload.controller('channelUploadController', [
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
        // this.content_sets = [];
        this.selectedRecords = [];
        this.OrganizationList = [];
        scope.translationError = false;
        scope.errors = {};
        this.videoSubmitted = false;
        scope.channel = {};
        scope.editPage = false;
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

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

        this.init = function () {
            scope.livePage = true;
            // scope.channel.liveType = 'hls';
            // scope.channel.aspect_ratio = '640X360';
        };

        this.generateEpgId = function () {
            if (!scope.channel.channel_name) return;
            var name = scope.channel.channel_name.trim();
            var parts = name.split(/\s+/);
            var prefix = "";

            if (parts.length >= 2) {
                // First 2 chars of first 2 words
                var first = parts[0].substring(0, 2);
                var second = parts[1].substring(0, 2);
                prefix = (first + second).toLowerCase();
            } else if (parts.length === 1) {
                // First 4 chars of the single word
                prefix = parts[0].substring(0, 4).toLowerCase();
            }

            if (prefix) {
                // Generate random 4 digit number
                var randomNum = Math.floor(1000 + Math.random() * 9000);
                scope.channel.epg_id = prefix + randomNum;
            }
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

        // create vod code
        this.saveChannel = function ($event) {
            scope.errors = {};
            console.log(scope.channel);

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.channel.is_active = scope.channel.is_active ? true : false;

                // if (this.videoSubmitted == false) {
                // this.videoSubmitted = true;
                requestFactory.post(
                    requestFactory.getUrl('create/channel'),
                    scope.channel,
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        window.location.href = requestFactory.getTemplateUrl(
                            'admin/channel'
                        );
                    },
                    this.fillError
                );
                // }
            }
        };
        // create vod code end

        // edit vod code
        this.saveChannelEdit = function ($event, id) {
            scope.error = {};
            var channelId = id;

            if (baseValidator.validateAngularForm($event.target, scope)) {

                let bundles = scope.channelGridCtrl.selectedVideo.bundles || [];

                scope.channel.content_sets = bundles.map(org => {
                    return {
                        organization_id: org.organization_id || org.id,
                        organization_name: org.organization_name,
                        channel_contentset: (org.bundles || []).map(b => b.id)
                    };
                });

                // ---- Send request ----
                requestFactory.post(
                    requestFactory.getUrl('channel/edit/' + channelId),
                    scope.channel,
                    (response) => {
                        if (response.data) {
                            scope.channel = response.data;
                        }

                        scope.channel.is_active = !!scope.channel.is_active;

                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        window.location.href = requestFactory.getTemplateUrl(
                            'admin/channel'
                        );

                        $('#' + channelId).removeClass('not-saved');
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
        this.fetchData = function (id, callback) {
            scope.editPage = true;

            requestFactory.get(
                requestFactory.getUrl('channel/channel-to-edit/' + id),
                function (response) {
                    if (response && response.response && response.response.length > 0) {
                        scope.channel = response.response[0];

                        // Convert numeric values
                        scope.channel.sorting_number = Number(scope.channel.sorting_number);

                        // ✅ Parse content_sets safely
                        let contentSets = [];
                        let parsedContentSets = [];

                        if (typeof scope.channel.content_sets === "string") {
                            try {
                                parsedContentSets = JSON.parse(scope.channel.content_sets);
                            } catch (e) {
                                console.error("❌ Invalid JSON in content_sets:", e);
                            }
                        }

                        // ✅ Populate channel.organization for the UI Dropdown using get_all_organization if available
                        if (scope.channel.get_all_organization && Array.isArray(scope.channel.get_all_organization)) {
                            scope.channel.organization = scope.channel.get_all_organization.map(org => org.id);
                        } else {
                            scope.channel.organization = parsedContentSets.map(org => org.organization_id);
                        }

                        // ✅ Determine which set to use for Assigned Bundle List
                        // User wants ONLY content_sets data to be shown
                        if (parsedContentSets.length > 0) {
                            contentSets = parsedContentSets;
                        } else if (scope.channel.get_all_organization && Array.isArray(scope.channel.get_all_organization)) {
                            contentSets = scope.channel.get_all_organization.map(org => ({
                                organization_id: org.id,
                                organization_name: org.organization_name
                            }));
                        } else if (Array.isArray(scope.channel.content_sets)) {
                            contentSets = scope.channel.content_sets;
                        }

                        // ✅ Populate channel.organization for the UI Dropdown [Handled above]
                        // scope.channel.organization = contentSets.map(org => org.organization_id);

                        const allBundles = scope.channel.channel_sets || [];

                        // ✅ Merge both: organization info + their bundles
                        const mergedOrganizations = contentSets.map(org => {
                            const orgBundles = allBundles.filter(
                                b => b.organization_id == org.organization_id
                            );

                            return {
                                organization_id: org.organization_id,
                                organization_name: org.organization_name,
                                bundles: orgBundles.map(b => ({
                                    id: b.id,
                                    name: b.name
                                }))
                            };
                        });

                        // ✅ Assign merged data to Angular model
                        this.selectedVideo = this.selectedVideo || {};
                        this.selectedVideo.bundles = mergedOrganizations;
                        scope.channel.selectedBundles = mergedOrganizations;

                        if (typeof scope.channel.geo_block_country_list === "string") {
                            try {
                                scope.channel.geo_block_country_list = JSON.parse(scope.channel.geo_block_country_list);
                            } catch (e) {
                                scope.channel.geo_block_country_list = [];
                            }
                        }

                        // ✅ Convert numeric flags to booleans
                        scope.channel.is_active = (scope.channel.is_active == 1);
                        scope.channel.pin_locked = (scope.channel.pin_locked == 1);
                        scope.channel.geo_blocking = (scope.channel.geo_blocking == 1);
                        scope.channel.group_chat = (scope.channel.group_chat == 1);
                        scope.channel.playback_token = parseInt(scope.channel.playback_token);
                        scope.channel.policy = parseInt(scope.channel.policy);

                        // ✅ Trigger Angular digest to update UI
                        scope.$applyAsync();

                        scope.$applyAsync(() => {
                            this.fetchChannelSet();
                        });
                    }
                }
            );
        };
        // fetch data code end here

        // ==================================================**************************************************==================================================
        // organization fetch code
        // ==================================================**************************************************==================================================
        // fetch channel set code
        this.fetchChannelSet = () => {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records?rowsPerPage=1000'),
                scope.defineProperties,
                function (response) {
                    const data = response?.data?.data;
                    if (!Array.isArray(data)) {
                        console.error("❌ Invalid organization data!");
                        return;
                    }

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

                    // 🛠️ Hydrate selectedBundles if bundles are missing (Fix for empty "Assigned" view)
                    if (scope.channel && scope.channel.selectedBundles) {
                        scope.channel.selectedBundles.forEach(assignedOrg => {
                            const orgData = grouped[assignedOrg.organization_id];
                            if (orgData && (!assignedOrg.bundles || assignedOrg.bundles.length === 0)) {
                                assignedOrg.bundles = orgData.bundles.map(b => ({ ...b }));
                            }
                        });
                    }

                    let availableList = Object.values(grouped);

                    // 🔍 Filter availableList to ONLY include organizations present in the current channel's allowed list
                    if (scope.channel && Array.isArray(scope.channel.organization) && scope.channel.organization.length > 0) {
                        const allowedOrgIds = scope.channel.organization.map(id => parseInt(id));
                        availableList = availableList.filter(org => allowedOrgIds.includes(parseInt(org.organization_id)));
                    } else if (scope.editPage) {
                    }

                    if (scope.channel?.selectedBundles?.length) {
                        availableList = availableList.map(org => {
                            const assignedOrg = scope.channel.selectedBundles.find(
                                o => o.organization_id == org.organization_id
                            );
                            if (assignedOrg) {
                                org.bundles = org.bundles.filter(
                                    bundle => !assignedOrg.bundles.some(b => b.id == bundle.id)
                                );
                            }
                            return org;
                        }).filter(org => org.bundles.length > 0);
                    }

                    scope.ChannelContentList = availableList;

                }
            );
        };
        // fetch channel set code end here

        // assign selected bundles code
        scope.channelGridCtrl.assignSelectedBundles = function () {
            const ctrl = scope.channelGridCtrl;

            if (!ctrl.selectedVideo) ctrl.selectedVideo = {};

            const newOrgs = Array.isArray(ctrl.selectedBundles) ? ctrl.selectedBundles.map(org => ({
                organization_id: org.organization_id,
                organization_name: org.organization_name,
                bundles: org.bundles.map(b => ({
                    id: b.id,
                    name: b.name
                }))
            })) : [];

            // ✅ Keep existing assigned bundles
            let existingOrgs = Array.isArray(ctrl.selectedVideo.bundles) ? ctrl.selectedVideo.bundles : [];

            // ✅ Merge logic (don’t remove old ones)
            newOrgs.forEach(newOrg => {
                const existing = existingOrgs.find(o => o.organization_id == newOrg.organization_id);
                if (existing) {
                    // merge bundles without duplicates
                    const mergedBundles = [
                        ...existing.bundles,
                        ...newOrg.bundles.filter(nb => !existing.bundles.some(eb => eb.id == nb.id))
                    ];
                    existing.bundles = mergedBundles;
                } else {
                    existingOrgs.push(newOrg);
                }
            });

            ctrl.selectedVideo.bundles = existingOrgs;

            console.log("✅ Final merged assigned bundles:", ctrl.selectedVideo.bundles);

            $('#add-bundles').modal('hide');
        };
        // assign selected bundles code end here

        // remove bundle code
        scope.removeBundle = function (org) {
            const ctrl = scope.channelGridCtrl;

            scope.ChannelContentList = scope.ChannelContentList || [];
            scope.channel.selectedBundles = scope.channel.selectedBundles || [];

            // 🗑️ 1. Remove from selectedVideo.bundles
            if (ctrl.selectedVideo?.bundles?.length) {
                ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(
                    b => b.organization_id !== org.organization_id
                );
                console.log("🗑️ Removed from selectedVideo.bundles:", org);
            }

            // 🗑️ 2. Remove from channel.selectedBundles
            scope.channel.selectedBundles = scope.channel.selectedBundles.filter(
                o => o.organization_id !== org.organization_id
            );
            console.log("🧹 Removed organization from selectedBundles:", org);

            // 🔁 3. Return this org (and its bundles) to ChannelContentList
            const existingOrg = scope.ChannelContentList.find(
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
                console.log("↩️ Merged bundles back to existing organization in ChannelContentList:", org);
            } else {
                // Add as new org entry
                scope.ChannelContentList.push({
                    organization_id: org.organization_id,
                    organization_name: org.organization_name,
                    bundles: org.bundles.map(b => ({ id: b.id, name: b.name }))
                });
                console.log("🆕 Returned full organization to ChannelContentList:", org);
            }

            scope.$applyAsync();
        };
        // remove bundle code end here

        // assigned content modal open code
        $('#assigned-content').on('shown.bs.modal', function () {
            console.log("✅ Modal opened — initializing drag-and-drop...");
            ContentDragDrop();
        });
        // assigned content modal open code end here

        // content drag and drop code
        function ContentDragDrop() {
            const addedBundles = document.getElementById('addedBundles');
            const availableBundles = document.getElementById('availableBundles');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedBundles.innerHTML = '';
            console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableBundles .content-container').forEach(card => {
                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
                console.log("📥 Dragging over addedBundles drop zone...");
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                console.log(`📤 Drop detected. Dropped Bundle ID = ${draggedId}`);

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
                    console.log(`🗑️ Removed from assigned. Returned ID = ${draggedId}`);
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                addedBundles.appendChild(clone);
                card.remove();

                console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('channelEditForm')).scope();
                const ctrl = scope?.channelGridCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];

                // Loop through each organization container in the addedBundles section
                addedBundles.querySelectorAll('.content-container').forEach(orgCard => {
                    const orgId = parseInt(orgCard.getAttribute('data-id'));
                    const orgData = scope.ChannelContentList.find(o => o.organization_id === orgId);

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

                        console.log(`📦 Added Organization: ${orgData.organization_name} with ${bundles.length} bundles`);
                    }
                });

                console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
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
        // content drag and drop code end here

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
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.OrganizationList = response.data.data;
                        // console.log(this.Onlyorganization);

                    }
                }
            )
        }
        this.fetchOrganization();

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


        // ==================================================**************************************************==================================================

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
                        aspectRatio: 1500 / 1500,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 1500,
                        minCropBoxHeight: 1500,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 1500, height: 1200 };
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
                                    scope.channel.thumbnail = data.info;
                                    scope.channel.thumbnail_image = data.info;
                                    scope.channel.selected_thumb = data.info;
                                    scope.channel.is_thumbnail_updated = 1;
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
                        aspectRatio: 1180 / 665,
                        preview: '.poster_img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 10,
                        minCropBoxHeight: 10,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 1180, height: 665 };
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
                    canvasImgData = cropperImg.getCroppedCanvas({
                        width: 1180,
                        height: 665
                    }).toBlob(function (blob) {
                        var formImgData = new FormData();
                        formImgData.append('module', 'video');
                        formImgData.append('size', 'poster');
                        formImgData.append('image', blob);
                        $('.crop-body').hide();
                        $('.poster_loader-container').show();
                        $('#submit_poster_image').prop('disabled', true);
                        $.ajax(
                            $('meta[name="base-api-url"]').attr('content') + '/channel/poster',
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
                                    scope.channel.poster_image = data.info;
                                    scope.channel.is_posterimg_updated = 1;
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
            channelUpload.controller(controller, window.gridControllers[controller]);
        }
    }
}

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function () {
    angular.bootstrap(document, ['channelUpload']);
});
