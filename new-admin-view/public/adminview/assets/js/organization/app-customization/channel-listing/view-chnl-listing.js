var ChannelSettingController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        // scope.chnlset = {};
        this.addplan = {};
        scope.editPage = false;
        this.chnlset = {};

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organization/app-customization/channel-listing/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ==========***********==========
        // ==========***********==========

        scope.getAssignedChannels = function (record) {
            if (!record.assigned_channels) return '';
            return record.assigned_channels.map(c => c.id + ' - ' + c.channel_name).join('<br>');
        };

        // ==========***********==========
        // ==========***********==========

        function getRecordIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id');
        }

        this.fetchChnlListing = function (callback) {

            const recordId = getRecordIdFromUrl();
            // console.log("🔍 Record ID from URL:", recordId);

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/channel-listing/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const record = response.data.data.find(item => String(item.id) === String(recordId));
                        fetchData(response.data.data);

                        if (record) {
                            callback(record);
                        } else {
                            console.warn(`⚠️ No record found for ID = ${recordId}`);
                            callback(null);
                        }
                    } else {
                        console.warn("Invalid data format from fetchPlans:", response);
                        callback(null);
                    }
                }
            );
        }

        function fetchData(chnl) {
            const homeElement = document.getElementById('channel-listing-form');
            if (!homeElement) {
                console.warn("⚠️ 'channel-listing-form' element not found.");
                return;
            }

            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'channel-listing-form' element.");
                return;
            }

            const targetOrgId = document.getElementById("chnl_listing_id")?.value;
            if (!targetOrgId) {
                console.warn("⚠️ No chnl_listing_id element or value found.");
                return;
            }

            // ✅ Find record by ID
            const channel_listing = chnl.find(c => String(c.id) === String(targetOrgId));

            if (!channel_listing) {
                console.warn(`⚠️ No channel found for ID = ${targetOrgId}`);
                return;
            }

            // console.log("✅ Found channel listing record:", channel_listing);

            // ✅ Parse sequence_assigned_channels safely
            if (typeof channel_listing.sequence_assigned_channels === 'string') {
                try {
                    channel_listing.sequence_assigned_channels = JSON.parse(channel_listing.sequence_assigned_channels);
                } catch (e) {
                    console.error("❌ Failed to parse sequence_assigned_channels:", e);
                    channel_listing.sequence_assigned_channels = [];
                }
            }

            // ✅ Parse group_channel_list safely
            if (typeof channel_listing.group_channel_list === 'string') {
                try {
                    channel_listing.group_channel_list = JSON.parse(channel_listing.group_channel_list);
                } catch (e) {
                    console.error("❌ Failed to parse group_channel_list:", e);
                    channel_listing.group_channel_list = [];
                }
            }

            scope.chnlsetCtrl.chnlset = channel_listing;
            scope.chnlsetCtrl.chnlset.selectedBundles = scope.chnlsetCtrl.chnlset.selectedBundles || [];
            scope.chnlsetCtrl.chnlset.GroupSelectedBundles = scope.chnlsetCtrl.chnlset.GroupSelectedBundles || [];

            if (Array.isArray(channel_listing.sequence_assigned_channels)) {
                channel_listing.sequence_assigned_channels.forEach(group => {
                    const exists = scope.chnlsetCtrl.chnlset.selectedBundles.some(b => b.id === group.id);
                    if (!exists) {
                        scope.chnlsetCtrl.chnlset.selectedBundles.push(group);
                        // console.log(`📦 Added group to selectedBundles: ID = ${group.id}`);
                    }
                });
            }
            // console.log("📊 Final selectedBundles list:", scope.chnlsetCtrl.chnlset.selectedBundles);

            if (Array.isArray(channel_listing.group_channel_list)) {
                channel_listing.group_channel_list.forEach(group => {
                    const exists = scope.chnlsetCtrl.chnlset.GroupSelectedBundles.some(b => b.id === group.id);
                    if (!exists) {
                        scope.chnlsetCtrl.chnlset.GroupSelectedBundles.push(group);
                        // console.log(`📦 Added group to selectedBundles: ID = ${group.id}`);
                    }
                });
            }
            // console.log("📊 Final GroupSelectedBundles list:", scope.chnlsetCtrl.chnlset.GroupSelectedBundles);

            scope.$applyAsync();
        }

        // this.fetchChnlListing();

        // ==========***********==========
        // ==========***********==========

        this.fetchChannel = function (assignedRecord) {
            const requestData = Object.assign({}, this.defineProperties, { per_page: 100000 });

            requestFactory.post(
                requestFactory.getUrl('channel/records'),
                requestData,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        let channelList = response.data.data;

                        let assignedChannels = [];
                        let assignedGroupChannels = [];

                        // ✅ Parse and handle sequence_assigned_channels
                        if (assignedRecord && assignedRecord.sequence_assigned_channels) {
                            if (typeof assignedRecord.sequence_assigned_channels === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.sequence_assigned_channels);
                                } catch (e) {
                                    console.error("❌ Failed to parse sequence_assigned_channels:", e);
                                }
                            } else if (Array.isArray(assignedRecord.sequence_assigned_channels)) {
                                assignedChannels = assignedRecord.sequence_assigned_channels;
                            }
                        }

                        // ✅ Parse and handle group_channel_list
                        if (assignedRecord && assignedRecord.group_channel_list) {
                            if (typeof assignedRecord.group_channel_list === "string") {
                                try {
                                    assignedGroupChannels = JSON.parse(assignedRecord.group_channel_list);
                                } catch (e) {
                                    console.error("❌ Failed to parse group_channel_list:", e);
                                }
                            } else if (Array.isArray(assignedRecord.group_channel_list)) {
                                assignedGroupChannels = assignedRecord.group_channel_list;
                            }
                        }

                        // ✅ Fix typo (cahnnel_list → channel_list)
                        assignedGroupChannels.forEach(group => {
                            if (group.cahnnel_list && !group.channel_list) {
                                group.channel_list = group.cahnnel_list;
                                delete group.cahnnel_list;
                            }
                        });

                        // ✅ Extract IDs from both lists
                        const assignedIds = [
                            ...assignedChannels.map(a => String(a.id)),
                            ...assignedGroupChannels.flatMap(group =>
                                (group.channel_list || []).map(ch => String(ch.id))
                            ),
                        ];

                        // ✅ Remove assigned channels from main list
                        if (Array.isArray(channelList) && assignedIds.length > 0) {
                            const before = channelList.length;
                            channelList = channelList.filter(ch => !assignedIds.includes(String(ch.id)));
                            const after = channelList.length;

                            // console.log(`✅ Filtered out ${before - after} assigned channels`);
                        }

                        this.channlset = channelList;
                    }
                }
            );
        };


        this.fetchChnlListing((record) => {
            this.fetchChannel(record);
        });
        // this.fetchChannel();

        // ==========***********==========
        // ==========***********==========

        // Sequence List channel search code start
        $(document).ready(function () {
            // console.log("🚀 Initializing drag-and-drop directly...");
            SequenceList();
        });

        function SequenceList() {
            const addedBundles = document.getElementById('assignedChannelList');
            const availableBundles = document.getElementById('availableChannelList');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            // ✅ Event delegation for dragstart/dragend
            availableBundles.addEventListener('dragstart', e => {
                if (e.target.classList.contains('channel-item')) {
                    const id = e.target.dataset.id;
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Channel ID = ${id}`);
                }
            });

            availableBundles.addEventListener('dragend', e => {
                if (e.target.classList.contains('channel-item')) {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Channel ID = ${e.target.dataset.id}`);
                }
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedBundles drop zone...");
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                // console.log(`📤 Drop detected. Dropped Channel ID = ${draggedId}`);

                const card = availableBundles.querySelector(`[data-id="${draggedId}"]`);
                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                if (addedBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Remove placeholder if exists
                const dropZone = addedBundles.querySelector('.drop-zone');
                if (dropZone) dropZone.remove();

                // Clone & append
                const clone = card.cloneNode(true);

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedBundles.removeChild(clone);
                    availableBundles.appendChild(card);
                    // console.log(`🗑️ Removed from assigned. Returned ID = ${draggedId}`);

                    if (addedBundles.querySelectorAll('.channel-item').length === 0) {
                        addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('draggable', 'true');
                addedBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Channel assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            // Update Angular scope
            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('channelSetForm')).scope();
                const ctrl = scope?.chnlsetCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or chnlsetCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedBundles.querySelectorAll('.channel-item').forEach(card => {
                    const id = parseInt(card.dataset.id);
                    const bundle = ctrl.channlset.find(b => b.id === id);
                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            // Search setup
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.channel-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailableChannels', 'availableChannelList');
            setupSearch('searchAssignedChannels', 'assignedChannelList');
        }
        // Sequence List channel search code end

        // ==========***********==========
        // ==========***********==========

        // group list channel code start
        this.groupCounter = 0;
        
        this.addGroup = function () {
            const from = parseInt(document.getElementById('fromInput').value);
            const to = parseInt(document.getElementById('toInput').value);
            const desc = document.getElementById('descriptionInput').value;

            // Store values as public properties on the controller
            this.from = from;
            this.to = to;
            this.desc = desc;

            // Also store in chnlset for API
            // this.chnlset.from = from;
            // this.chnlset.to = to;
            // this.chnlset.description = desc;

            if (isNaN(from) || isNaN(to) || !desc || from > to) {
                alert("Please enter valid 'From', 'To', and Description values.");
                return;
            }

            const allowedDrops = to - from + 1;
            this.groupCounter++;
            const groupId = `group-${this.groupCounter}`;
            const dropZoneId = `addedBundles-${this.groupCounter}`;

            this.group = groupId;

            const groupBox = angular.element(`
                <div class="group-box" id="${groupId}" 
                    ondragover="event.preventDefault()" 
                    data-max="${allowedDrops}" data-count="0">

                    <!-- Header -->
                    <div class="group-header">
                        <span data-ng-model="chnlsetCtrl.chnlset.data">${from}–${to} ${desc}</span>
                        <div class="header-actions">
                            <button type="button" onclick="editGroup(${this.groupCounter})" class="edit-btn">✏️</button>
                            <button type="button" onclick="deleteGroup(${this.groupCounter})" class="delete-btn">🗑️</button>
                        </div>
                    </div>

                    <!-- Drop Area -->
                    <div class="scroll-box" id="${dropZoneId}" data-ng-model="chnlsetCtrl.chnlset.group_channel_list">
                        <div class="box-drop-zone">DROP HERE</div>
                    </div>
                </div>
            `);

            // append into Assigned Channels section
            const assignedSection = document.querySelector(".col-md-6:last-child");
            assignedSection.appendChild(groupBox[0]);

            this.resetForm();
            ContentDragDrop();
        };

        this.resetForm = function () {
            document.getElementById('fromInput').value = '';
            document.getElementById('toInput').value = '';
            document.getElementById('descriptionInput').value = '';
        }

        window.editGroup = function (groupId) {
            alert(`✏️ Edit group ${groupId} (implement your own edit UI)`);
        };

        window.deleteGroup = function (groupId) {
            const group = document.getElementById(`group-${groupId}`);
            const available = document.getElementById("availableChannels");

            if (!group || !available) return;

            // Find the group's drop zone
            const dropZone = group.querySelector(`#addedBundles-${groupId}`);
            if (dropZone) {
                const channels = dropZone.querySelectorAll(".channel-item");

                channels.forEach(channel => {
                    // Remove group-specific remove button if exists
                    const removeBtn = channel.querySelector(".bundle-remove");
                    if (removeBtn) removeBtn.remove();

                    // Move channel back to available
                    channel.setAttribute("draggable", "true");
                    available.appendChild(channel);
                });
            }
            group.remove();
        };

        $(document).ready(function () {
            ContentDragDrop();
            // console.log("🚀 Initializing drag-and-drop directly...");
        });

        function ContentDragDrop() {
            const availableBundles = document.getElementById('availableChannels');
            if (!availableBundles) return;

            // console.log("🚀 ContentDragDrop initialized");

            // -------- Drag Start / End --------
            availableBundles.addEventListener('dragstart', e => {
                if (e.target.classList.contains('channel-item')) {
                    const id = e.target.dataset.id;
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`✈️ Drag started: Channel ID = ${id}`);
                }
            });

            availableBundles.addEventListener('dragend', e => {
                e.target.classList.remove('dragging');
                // console.log(`🏁 Drag ended: Channel ID = ${e.target.dataset.id}`);
            });

            // -------- Handle All Group Drop Zones --------
            document.querySelectorAll('[id^="addedBundles-"]').forEach(dropZone => {
                dropZone.addEventListener('dragover', e => e.preventDefault());

                dropZone.addEventListener('drop', e => {
                    e.preventDefault();

                    const draggedId = e.dataTransfer.getData('text/plain');
                    const card = availableBundles.querySelector(`[data-id="${draggedId}"]`);
                    if (!card) {
                        console.warn(`❌ Card not found for ID ${draggedId}`);
                        return;
                    }

                    const groupId = dropZone.id.split('-')[1];
                    const group = document.getElementById(`group-${groupId}`);
                    let count = parseInt(group.dataset.count);
                    const max = parseInt(group.dataset.max);

                    // console.log(`📥 Drop detected: Channel ID = ${draggedId}, Group = ${groupId}, Count = ${count}, Max = ${max}`);

                    // Respect group limits
                    if (count >= max) {
                        alert(`You cannot drop more than ${max} channels for this group.`);
                        console.warn(`⚠️ Drop limit reached for group ${groupId}`);
                        return;
                    }

                    // Prevent duplicates
                    if (dropZone.querySelector(`[data-id="${draggedId}"]`)) {
                        console.warn(`⚠️ Duplicate prevented for Channel ID = ${draggedId} in Group ${groupId}`);
                        return;
                    }

                    // Remove placeholder if present
                    const placeholder = dropZone.querySelector('.box-drop-zone');
                    if (placeholder) placeholder.remove();

                    // Clone card and append remove button
                    const clone = card.cloneNode(true);
                    const removeBtn = document.createElement('span');
                    removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                    removeBtn.className = 'bundle-remove';
                    removeBtn.style.cssText = 'cursor:pointer; float:right;';
                    removeBtn.onclick = () => {
                        dropZone.removeChild(clone);
                        availableBundles.appendChild(card);
                        group.dataset.count = parseInt(group.dataset.count) - 1;
                        // console.log(`🗑️ Removed Channel ID = ${draggedId} from Group ${groupId}, New Count = ${group.dataset.count}`);

                        if (dropZone.querySelectorAll('.channel-item').length === 0) {
                            dropZone.innerHTML = '<div class="box-drop-zone">DROP HERE</div>';
                        }

                        // updateGroupSelectedBundles();
                    };

                    clone.appendChild(removeBtn);
                    dropZone.appendChild(clone);
                    card.remove();

                    // Increment group count
                    group.dataset.count = count + 1;
                    // console.log(`✅ Channel ID = ${draggedId} added to Group ${groupId}, New Count = ${group.dataset.count}`);

                    // Update Angular selectedBundles
                    updateGroupSelectedBundles();
                });
            });

            // -------- Update Angular Controller --------
            function updateGroupSelectedBundles() {
                const scope = angular.element(document.getElementById('channelSetForm')).scope();
                const ctrl = scope?.chnlsetCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or chnlsetCtrl not found.");
                    return;
                }

                ctrl.GroupSelectedBundles = [];
                document.querySelectorAll('[id^="addedBundles-"] .channel-item').forEach(card => {
                    const id = parseInt(card.dataset.id);
                    const bundle = ctrl.channlset.find(b => b.id === id);
                    if (bundle) {
                        ctrl.GroupSelectedBundles.push(bundle);
                        // console.log(`📦 Added to GroupSelectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final GroupSelectedBundles list:", ctrl.GroupSelectedBundles);
                scope.$applyAsync();
            }

            // -------- Search Functionality --------
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);
                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.channel-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });

                    // console.log(`🔍 Search query: "${query}" applied on container "${containerId}"`);
                });
            }

            setupSearch('searchAvailable', 'availableChannels');
            setupSearch('searchAdded', 'dropZoneId');

            // console.log("🔧 Search inputs initialized");
        }

    }
];

window.gridControllers = {
    ChannelSettingController: ChannelSettingController
};
