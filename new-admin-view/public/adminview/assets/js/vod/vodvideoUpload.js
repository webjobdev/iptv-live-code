var vodUpload = angular.module('vodUpload', ['flow', 'ngTagsInput', 'ui']);
var commonAPP = vodUpload;

vodUpload.directive('baseValidator', validatorDirective);
vodUpload.factory('requestFactory', requestFactory);
vodUpload.service('commonGeofencingService', commonGeofencing);
vodUpload.controller('VodUploadController', [
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
        scope.vodSelectedVideo = {};
        scope.editPage = false;

        this.categoryFilter = function (item) {
            if (!scope.vodSelectedVideo.organization ||
                (Array.isArray(scope.vodSelectedVideo.organization) && scope.vodSelectedVideo.organization.length === 0)) {
                return true;
            }

            var selectedOrgs = scope.vodSelectedVideo.organization;
            if (!Array.isArray(selectedOrgs)) {
                selectedOrgs = [selectedOrgs];
            }

            // Check against get_organization array (Many-to-Many relationship)
            if (item.get_organization && Array.isArray(item.get_organization) && item.get_organization.length > 0) {
                var hasMatch = item.get_organization.some(function (catOrg) {
                    return selectedOrgs.some(function (selOrgId) {
                        return catOrg.id == selOrgId;
                    });
                });
                if (hasMatch) return true;
            }

            // Fallback: Check against direct organization properties (One-to-Many or legacy)
            // Ensure comparison works regardless of type (string/number)
            return selectedOrgs.some(function (orgId) {
                // Check if item.organization is the ID directly
                if (item.organization && item.organization == orgId) return true;

                // Check if item.organization_id is the ID (common Laravel pattern)
                if (item.organization_id && item.organization_id == orgId) return true;

                // Check if item.organization is an object with an id property
                if (item.organization && typeof item.organization === 'object' && item.organization.id == orgId) return true;

                return false;
            });
        };

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
            // scope.vodSelectedVideo.liveType = 'hls';
            // scope.vodSelectedVideo.aspect_ratio = '640X360';
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

        // scope.vodSelectedVideo.timeParts = {
        //     hour: '00',
        //     minute: '00',
        //     second: '00'
        // };

        // function pad(val) {
        //     return ('0' + val).slice(-2);
        // }

        // scope.increment = function (unit) {
        //     let max = unit === 'hour' ? 23 : 59;
        //     let val = parseInt(scope.vodSelectedVideo.timeParts[unit] || 0, 10);
        //     val = (val + 1) > max ? 0 : val + 1;
        //     scope.vodSelectedVideo.timeParts[unit] = pad(val);
        //     scope.updateModel();
        // };

        // scope.decrement = function (unit) {
        //     let max = unit === 'hour' ? 23 : 59;
        //     let val = parseInt(scope.vodSelectedVideo.timeParts[unit] || 0, 10);
        //     val = (val - 1) < 0 ? max : val - 1;
        //     scope.vodSelectedVideo.timeParts[unit] = pad(val);
        //     scope.updateModel();
        // };

        // scope.updateModel = function () {
        //     scope.vodSelectedVideo.timeParts =
        //         `${scope.vodSelectedVideo.timeParts.hour}:${scope.vodSelectedVideo.timeParts.minute}:${scope.vodSelectedVideo.timeParts.second}`;
        // };

        scope.vodSelectedVideo.timeParts = {
            hour: '00',
            minute: '00',
            second: '00'
        };

        function pad(val) {
            return ('0' + val).slice(-2);
        }

        scope.increment = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.vodSelectedVideo.timeParts[unit] || 0, 10);
            val = (val + 1) > max ? 0 : val + 1;
            scope.vodSelectedVideo.timeParts[unit] = pad(val);
            scope.updateModel();
        };

        scope.decrement = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.vodSelectedVideo.timeParts[unit] || 0, 10);
            val = (val - 1) < 0 ? max : val - 1;
            scope.vodSelectedVideo.timeParts[unit] = pad(val);
            scope.updateModel();
        };

        scope.updateModel = function () {
            // Keep timeParts as an object, store formatted string separately
            scope.vodSelectedVideo.timeString =
                `${scope.vodSelectedVideo.timeParts.hour}:${scope.vodSelectedVideo.timeParts.minute}:${scope.vodSelectedVideo.timeParts.second}`;
        };


        scope.togglePublishDate = function () {
            if (scope.vodSelectedVideo.publish_now == 1) {
                const now = new Date();
                // Format as YYYY-MM-DD HH:MM:SS
                const formatted = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                scope.vodSelectedVideo.publish_date = formatted;
            }
        };

        scope.scheduledDate = function () {
            // You can also reset scheduled times if unchecked:
            if (!scope.vodSelectedVideo.scheduled_publishing) {
                scope.selectedVideo.scheduled_time = '';
                scope.selectedVideo.expire_scheduled_time = '';
            }
        };
        // date format code end

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
        this.saveVod = function ($event) {
            scope.errors = {};
            console.log(scope.vodSelectedVideo);

            if (baseValidator.validateAngularForm($event.target, scope)) {
                scope.vodSelectedVideo.is_active = scope.vodSelectedVideo.is_active ? true : false;

                if (this.videoSubmitted == false) {
                    this.videoSubmitted = true;
                    requestFactory.post(
                        requestFactory.getUrl('create/video-on-demand'),
                        scope.vodSelectedVideo,
                        function (response) {
                            requestFactory.setToaster('success', response.message);
                            window.location.href = requestFactory.getTemplateUrl(
                                'admin/vod'
                            );
                        },
                        this.fillError
                    );
                }
            }
        };
        // create vod code end

        // edit vod code
        this.saveVodEdit = function ($event, id) {
            scope.error = {};
            var vodId = id;
            console.log("updated data:", scope.vodSelectedVideo);


            if (baseValidator.validateAngularForm($event.target, scope)) {

                // ---- Sync bundles into content_sets ----
                // let bundles = scope.vodGridCtrl.selectedVideo.bundles || [];

                // scope.vodSelectedVideo.content_sets = bundles.map(bundle => {
                //     return {
                //         organization_id: bundle.organization_id || bundle.id,
                //         organization_name: bundle.organization_name
                //     };
                // });

                let bundles = scope.vodGridCtrl.selectedVideo.bundles || [];

                scope.vodSelectedVideo.content_sets = bundles.map(org => {
                    return {
                        organization_id: org.organization_id || org.id,
                        organization_name: org.organization_name,
                        vod_contentset: (org.bundles || []).map(b => b.id)
                    };
                });

                // Debug check
                // console.log("Final payload =>", JSON.stringify(scope.vod, null, 2));

                // ---- Send request ----
                requestFactory.post(
                    requestFactory.getUrl('video-on-demand/edit/' + vodId),
                    scope.vodSelectedVideo,
                    (response) => {
                        if (response.data) {
                            scope.vodSelectedVideo = response.data;
                        }

                        scope.vodSelectedVideo.is_active = !!scope.vodSelectedVideo.is_active;

                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        // setTimeout(() => {
                        //     window.location.href = `${appUrl}admin/vod`;
                        // }, 200  );
                        window.location.href = requestFactory.getTemplateUrl(
                            'admin/vod'
                        );

                        $('#' + vodId).removeClass('not-saved');
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

        this.fetchData = function (id) {
            scope.editPage = true;
            requestFactory.get(
                requestFactory.getUrl('video-on-demand/vod-to-edit/' + id),
                function (response) {

                    if (response && response.response && response.response.length > 0) {
                        scope.vodSelectedVideo = response.response[0];

                        console.log("Fetch data:", scope.vodSelectedVideo);

                        // ✅ Parse content_sets safely
                        let bundles = [];
                        let parsedContentSets = [];

                        if (typeof scope.vodSelectedVideo.content_sets === "string") {
                            try {
                                parsedContentSets = JSON.parse(scope.vodSelectedVideo.content_sets);
                            } catch (e) {
                                console.error("❌ Invalid JSON in content_sets:", e);
                            }
                        }

                        // ✅ Populate vodSelectedVideo.organization for the UI Dropdown using get_all_organization if available
                        if (scope.vodSelectedVideo.get_all_organization && Array.isArray(scope.vodSelectedVideo.get_all_organization)) {
                            scope.vodSelectedVideo.organization = scope.vodSelectedVideo.get_all_organization.map(org => org.id);
                        } else {
                            scope.vodSelectedVideo.organization = parsedContentSets.map(org => org.organization_id);
                        }

                        // ✅ Determine which set to use for Assigned Bundle List
                        // User wants ONLY content_sets data to be shown
                        if (parsedContentSets.length > 0) {
                            bundles = parsedContentSets;
                        } else if (scope.vodSelectedVideo.get_all_organization && Array.isArray(scope.vodSelectedVideo.get_all_organization)) {
                            bundles = scope.vodSelectedVideo.get_all_organization.map(org => ({
                                organization_id: org.id,
                                organization_name: org.organization_name
                            }));
                        } else if (Array.isArray(scope.vodSelectedVideo.content_sets)) {
                            bundles = scope.vodSelectedVideo.content_sets;
                        }

                        // ✅ Populate channel.organization for the UI Dropdown [Handled above]
                        // scope.vodSelectedVideo.organization = bundles.map(org => org.organization_id);

                        // ✅ channel_sets = actual bundle data from backend
                        const allBundles = scope.vodSelectedVideo.channel_sets || [];

                        // ✅ Merge both: organization info + their bundles
                        let mergedOrganizations = bundles.map(org => {
                            const orgBundles = allBundles.filter(b => b.organization_id === org.organization_id);
                            return {
                                organization_id: org.organization_id,
                                organization_name: org.organization_name,
                                bundles: orgBundles
                            };
                        });

                        self.selectedVideo = self.selectedVideo || {};
                        self.selectedVideo.bundles = mergedOrganizations;


                        scope.vodSelectedVideo.selectedBundles = mergedOrganizations;

                        if (typeof scope.vodSelectedVideo.category === "string") {
                            try {
                                scope.vodSelectedVideo.category = JSON.parse(scope.vodSelectedVideo.category);
                            } catch (e) {
                                scope.vodSelectedVideo.category = [];
                            }
                        }

                        if (typeof scope.vodSelectedVideo.geo_block_country_list === "string") {
                            try {
                                scope.vodSelectedVideo.geo_block_country_list = JSON.parse(scope.vodSelectedVideo.geo_block_country_list);
                            } catch (e) {
                                scope.vodSelectedVideo.geo_block_country_list = [];
                            }
                        }

                        if (typeof scope.vodSelectedVideo.timeParts === "string") {
                            try {
                                scope.vodSelectedVideo.timeParts = JSON.parse(scope.vodSelectedVideo.timeParts);
                            } catch (e) {
                                scope.vodSelectedVideo.timeParts = {};
                            }
                        }

                        scope.vodSelectedVideo.scheduled_time = toDateTime(scope.vodSelectedVideo.scheduled_time);

                        setTimeout(() => {
                            $('.hello').datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                                showClear: true,
                                useCurrent: false,
                            });
                        }, 1000);

                        scope.vodSelectedVideo.playback_token = parseInt(scope.vodSelectedVideo.playback_token);
                        scope.vodSelectedVideo.policy = parseInt(scope.vodSelectedVideo.policy);
                        scope.vodSelectedVideo.is_active = (scope.vodSelectedVideo.is_active == 1);
                        scope.vodSelectedVideo.is_parental = (scope.vodSelectedVideo.is_parental == 1);
                        scope.vodSelectedVideo.geo_policy = (scope.vodSelectedVideo.geo_policy == 1);
                        scope.vodSelectedVideo.scheduled_publishing = (scope.vodSelectedVideo.scheduled_publishing == 1);
                        scope.vodSelectedVideo.publish_now = (scope.vodSelectedVideo.publish_now == 1);

                        scope.$applyAsync();

                        scope.$applyAsync(() => {
                            self.fetchVodContent();
                        });
                    }
                },
            );
        };
        // fetch data code end here

        // ==================================================**************************************************==================================================
        // organization fetch code
        // ==================================================**************************************************==================================================

        this.fetchVodContent = () => {
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                scope.defineProperties,
                (response) => {
                    const data = response?.data?.data;
                    if (!Array.isArray(data)) {
                        console.error("❌ Invalid content-set data.");
                        return;
                    }

                    // Step 1: Group by organization
                    const orgGroup = {};
                    data.forEach(item => {
                        const orgId = item.organization_id;
                        if (!orgGroup[orgId]) {
                            orgGroup[orgId] = {
                                organization_id: orgId,
                                organization_name: item.getorg?.organization_name || "Unknown",
                                bundles: []
                            };
                        }
                        orgGroup[orgId].bundles.push({
                            id: item.id,
                            name: item.name
                        });
                    });

                    let availableList = Object.values(orgGroup);

                    // 🛠️ Hydrate selectedBundles if bundles are missing (Fix for empty "Assigned" view)
                    if (scope.vodSelectedVideo && scope.vodSelectedVideo.selectedBundles) {
                        scope.vodSelectedVideo.selectedBundles.forEach(assignedOrg => {
                            const orgData = orgGroup[assignedOrg.organization_id];
                            if (orgData && (!assignedOrg.bundles || assignedOrg.bundles.length === 0)) {
                                // If no specific bundles assigned, assume ALL are relevant (or at least show them)
                                // Cloning to avoid reference issues
                                assignedOrg.bundles = orgData.bundles.map(b => ({ ...b }));
                            }
                        });
                    }

                    // 🔍 Filter availableList to ONLY include organizations present in the current vod's allowed list
                    if (scope.vodSelectedVideo && Array.isArray(scope.vodSelectedVideo.organization) && scope.vodSelectedVideo.organization.length > 0) {
                        const allowedOrgIds = scope.vodSelectedVideo.organization.map(id => parseInt(id));
                        availableList = availableList.filter(org => allowedOrgIds.includes(parseInt(org.organization_id)));
                    }

                    // Step 2: Filter out already assigned bundles
                    if (scope.vodSelectedVideo?.selectedBundles?.length) {
                        availableList = availableList.map(org => {
                            const assignedOrg = scope.vodSelectedVideo.selectedBundles.find(
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

                    scope.VodContentList = availableList;

                    // console.log("✅ Filtered Available Content Sets:", scope.VodContentList);
                }
            );
        };

        scope.vodGridCtrl.assignSelectedBundles = function () {
            const ctrl = scope.vodGridCtrl;

            if (!ctrl.selectedVideo) {
                ctrl.selectedVideo = {};
            }

            if (Array.isArray(ctrl.selectedBundles) && ctrl.selectedBundles.length > 0) {
                ctrl.selectedVideo.bundles = ctrl.selectedBundles.map(org => ({
                    organization_id: org.organization_id,
                    organization_name: org.organization_name,
                    bundles: org.bundles.map(b => ({
                        id: b.id,
                        name: b.name
                    }))
                }));

                // console.log("✅ Assigned grouped bundles to selectedVideo:", ctrl.selectedVideo.bundles);
            } else {
                ctrl.selectedVideo.bundles = [];
                console.warn("⚠️ No bundles selected.");
            }

            let existingOrgs = Array.isArray(ctrl.selectedVideo.bundles) ? ctrl.selectedVideo.bundles : [];

            // ✅ Merge logic (don’t remove old ones)
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

            $('#add-bundles').modal('hide');
        };



        scope.removeBundle = function (org) {
            const ctrl = scope.vodGridCtrl;

            scope.VodContentList = scope.VodContentList || [];
            scope.vodSelectedVideo.selectedBundles = scope.vodSelectedVideo.selectedBundles || [];

            // 🗑️ 1. Remove from selectedVideo.bundles
            if (ctrl.selectedVideo?.bundles?.length) {
                ctrl.selectedVideo.bundles = ctrl.selectedVideo.bundles.filter(
                    b => b.organization_id !== org.organization_id
                );
                console.log("🗑️ Removed from selectedVideo.bundles:", org);
            }

            // 🗑️ 2. Remove from channel.selectedBundles
            scope.vodSelectedVideo.selectedBundles = scope.vodSelectedVideo.selectedBundles.filter(
                o => o.organization_id !== org.organization_id
            );
            console.log("🧹 Removed organization from selectedBundles:", org);

            // 🔁 3. Return this org (and its bundles) to ChannelContentList
            const existingOrg = scope.VodContentList.find(
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
                console.log("↩️ Merged bundles back to existing organization in VodContentList:", org);
            } else {
                // Add as new org entry
                scope.VodContentList.push({
                    organization_id: org.organization_id,
                    organization_name: org.organization_name,
                    bundles: org.bundles.map(b => ({ id: b.id, name: b.name }))
                });
                console.log("🆕 Returned full organization to VodContentList:", org);
            }

            // ctrl.selectedVideo.bundles = (ctrl.selectedVideo.bundles || []).filter(b => b.id !== bundle.id);
            // // console.log("🗑️ Removed Bundle:", bundle);

            // const exists = scope.VodContentList.some(b => b.id === bundle.id);
            // if (!exists) {
            //     scope.VodContentList.push(bundle);
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
                const ctrl = scope?.vodGridCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or vodGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedBundles.querySelectorAll('.content-container').forEach(card => {
                    const orgId = parseInt(card.getAttribute('data-id'));
                    const orgData = scope.VodContentList.find(o => o.organization_id === orgId);

                    if (orgData) {
                        // Prepare bundles array from HTML (to stay consistent with structure)
                        const bundles = [];
                        card.querySelectorAll('.item-box').forEach(bundleElem => {
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

        this.fetchOrganization = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'), this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.OrganizationList = response.data.data;
                        // console.log(this.OrganizationList);

                    }
                }
            )
        }
        this.fetchOrganization();

        // ============***************=============
        this.fetchCategories = function () {
            requestFactory.get(
                requestFactory.getUrl('vod-category/get/records'),
                (response) => {
                    if (response && response.data && Array.isArray(response.data)) {
                        this.vodCategoryList = response.data;
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
                                    scope.vodSelectedVideo.thumbnail = data.info;
                                    scope.vodSelectedVideo.thumbnail_image = data.info;
                                    scope.vodSelectedVideo.selected_thumb = data.info;
                                    scope.vodSelectedVideo.is_thumbnail_updated = 1;
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
                                    scope.vodSelectedVideo.poster_image = data.info;
                                    scope.vodSelectedVideo.is_posterimg_updated = 1;
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
