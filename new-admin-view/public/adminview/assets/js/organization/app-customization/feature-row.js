var FeatureRowController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        this.btnNo = 0;
        scope.fturow = {};
        this.fturow = {};

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('channel/content-set/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
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

        // ==========***********==========
        // ==========***********==========

        scope.fturow.platforms = scope.fturow.platforms || [];

        scope.fturCtrl.togglePlatform = function (platform, fturow) {
            const target = fturow || scope.fturow;
            target.platforms = target.platforms || [];

            if (Array.isArray(target.platforms)) {
                const index = target.platforms.indexOf(platform);
                if (index > -1) {
                    target.platforms.splice(index, 1);
                } else {
                    target.platforms.push(platform);
                }
            }
        };

        $('#assigned-content').on('hidden.bs.modal', function () {
            $('.add-card').focus();
        });

        // ==========***********==========
        // ==========***********==========

        this.UpdateRecord = function ($event, id) {
            const EditId = id;

            // Resolve the correct data object (local scope vs controller scope)
            let currentFturow = this.fturow;
            if ($event && $event.target) {
                const elScope = angular.element($event.target).scope();
                if (elScope && elScope.fturow) {
                    currentFturow = elScope.fturow;
                }
            }

            // Build platforms object from data model
            const platforms = {};
            if (Array.isArray(currentFturow.platforms)) {
                currentFturow.platforms.forEach(p => {
                    platforms[p] = true;
                });
            } else {
                // Fallback to DOM if array missing
                const selectedPlatforms = Array.from(
                    document.querySelectorAll('input[name="platforms[]"]:checked')
                ).map(el => el.value);
                selectedPlatforms.forEach(p => platforms[p] = true);
            }

            // Assign relations
            const assignedChannels = scope.fturCtrl.selectedBundles || [];
            scope.fturow.channelDataSet = assignedChannels.map(channel => ({
                id: channel.id,
            }));
            const assignedTvShows = scope.fturCtrl.selectedTvShow || [];
            scope.fturow.tvShowContentSet = assignedTvShows.map(show => ({
                id: show.id,
            }));
            const assignedMovies = scope.fturCtrl.selectedmovie || [];
            scope.fturow.vodData = assignedMovies.map(movie => ({
                id: movie.id,
            }));

            const payload = {
                featured_row_status: currentFturow.featured_row_status,
                show_in_live: currentFturow.show_in_live,
                channelDataSet: scope.fturow.channelDataSet,
                vodData: scope.fturow.vodData,
                tvShowContentSet: scope.fturow.tvShowContentSet,
                platforms,
            };
            // console.log("payload:", payload);

            requestFactory.post(
                requestFactory.getUrl('org/app-customiztion/featured-rows/edit/' + EditId),
                payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            );
        }

        // ==========***********==========
        // ==========***********==========

        this.fetchData = function (plan) {
            // console.log("plan:", plan);

            const collapseId = "collapse-" + plan.id;
            const homeElement = document.getElementById(collapseId);

            if (!homeElement) {
                console.warn("⚠️ Element with id", collapseId, "not found");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            if (!localScope) {
                console.warn("⚠️ Angular scope not found for element", collapseId);
                return;
            }

            localScope.fturow = plan;
            localScope.fturow.platforms = plan.platforms
                ? Object.keys(plan.platforms).filter(key => plan.platforms[key])
                : [];

            localScope.$applyAsync();
        }

        // ==========***********==========
        // ==========***********==========

        // In fturCtrl
        scope.featureRows = [];

        this.opnPage = function ($event) {
            scope.featureRows.push({
                id: Date.now(),
                title: 'Silver Plan',
                subscription: ''
            });
        };

        // ==========***********==========
        // ==========***********==========

        // channel drag and drop code start
        this.fetchChannel = () => {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'), this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.MasterChannelList = response.data.data;
                        this.ChannelList = angular.copy(this.MasterChannelList);
                    } else {
                        console.error("❌ Organization data not fetch!");
                    }
                }
            );
        }
        this.fetchChannel();

        this.openChannelModal = function (record) {
            scope.currentEditingRecord = record;
            scope.fturCtrl.SelectedChannel = [];
            const assignedIds = new Set();
            if (record && record.content_sets && record.content_sets.channels) {
                scope.fturCtrl.SelectedChannel = angular.copy(record.content_sets.channels);
                record.content_sets.channels.forEach(c => assignedIds.add(Number(c.id)));
            }
            if (this.MasterChannelList) {
                this.ChannelList = this.MasterChannelList.filter(c => !assignedIds.has(Number(c.id)));
            }
        };

        scope.fturCtrl.assignSelectedBundles = function () {
            const ctrl = scope.fturCtrl;
            if (scope.currentEditingRecord) {
                scope.currentEditingRecord.content_sets.channels = angular.copy(ctrl.selectedBundles || []);
            }
            ctrl.SelectedChannel = [];
            $('#assigned-content').modal('hide'); // Fix modal ID: assigned-content vs add-bundles
        };

        scope.removeBundle = function (bundle) {
            const ctrl = scope.fturCtrl;
            ctrl.SelectedChannel.bundles = (ctrl.SelectedChannel.bundles || []).filter(b => b.id !== bundle.id);
            // console.log("🗑️ Removed Bundle:", bundle);

            const exists = ctrl.ChannelList.some(b => b.id === bundle.id);
            if (!exists) {
                ctrl.ChannelList.push(bundle);
                // console.log("🔁 Returned to ChannelList:", bundle);
            }

            scope.$applyAsync();
        };

        $(document).on('shown.bs.modal', '#assigned-content', function () {
            // console.log("🔥 Channel Modal opened (delegated) — drag init...");
            ChannelDragDrop();
        });

        function ChannelDragDrop() {
            // scope.channlContentSet.channelId = [];
            // this.fetchChannel();

            const addedBundles = document.getElementById('AddChannel');
            const availableBundles = document.getElementById('availableChannelList');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            const ctrl = angular.element(document.getElementById('Featured_Rows')).scope().fturCtrl;
            const existing = ctrl.SelectedChannel || [];

            if (existing.length > 0) {
                addedBundles.innerHTML = '';
                existing.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'channel-item';
                    div.setAttribute('draggable', 'true');
                    div.dataset.id = item.id;
                    div.innerHTML = `
                        <div class="channel-info">
                            <i class="glyphicon glyphicon-blackboard"></i>
                            ${item.name}
                        </div>
                        <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                    `;
                    const removeBtn = document.createElement('span');
                    removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                    removeBtn.className = 'remove-btn';
                    removeBtn.style.cssText = 'cursor:pointer; float:right;';

                    removeBtn.onclick = () => {
                        div.remove();
                        if (addedBundles.querySelectorAll('.channel-item').length === 0) {
                            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                        }
                        ctrl.ChannelList.push(item);
                        updateSelectedBundles();
                    };

                    div.appendChild(removeBtn);
                    addedBundles.appendChild(div);
                });
                updateSelectedBundles();
            }

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
                const scope = angular.element(document.getElementById('Featured_Rows')).scope();
                const ctrl = scope?.fturCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or fturCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedBundles.querySelectorAll('.channel-item').forEach(card => {
                    const id = parseInt(card.dataset.id);
                    const bundle = ctrl.MasterChannelList.find(b => b.id === id);
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

            setupSearch('searchAvailable', 'availableChannelList');
            setupSearch('searchAdded', 'AddChannel');
        }
        //  channel drag and drop code end

        // ==========***********==========
        // ==========***********==========

        // tv show drag and drop code start
        this.fetchTvshow = function () {
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.MasterTvShowList = response.data.data;
                        this.TvShowList = angular.copy(this.MasterTvShowList);
                    }
                }
            );
        }
        this.fetchTvshow();

        this.openTvShowModal = function (record) {
            scope.currentEditingRecord = record;
            scope.fturCtrl.SelectedShow = [];
            const assignedIds = new Set();
            if (record && record.content_sets && record.content_sets.tvShows) {
                scope.fturCtrl.SelectedShow = angular.copy(record.content_sets.tvShows);
                record.content_sets.tvShows.forEach(c => assignedIds.add(Number(c.id)));
            }
            if (this.MasterTvShowList) {
                this.TvShowList = this.MasterTvShowList.filter(c => !assignedIds.has(Number(c.id)));
            }
        };

        scope.fturCtrl.assignSelecteShow = function () {
            const ctrl = scope.fturCtrl;
            if (scope.currentEditingRecord) {
                scope.currentEditingRecord.content_sets.tvShows = angular.copy(ctrl.selectedTvShow || []);
            }
            ctrl.SelectedShow = [];
            $('#assigned-show').modal('hide');
        };

        scope.removeBundle = function (bundle) {
            const ctrl = scope.fturCtrl;
            ctrl.selectedVideo.bundles = (ctrl.selectedVideo.bundles || []).filter(b => b.id !== bundle.id);
            // console.log("🗑️ Removed Bundle:", bundle);

            const exists = ctrl.TvShowList.some(b => b.id === bundle.id);
            if (!exists) {
                ctrl.TvShowList.push(bundle);
                // console.log("🔁 Returned to ChannelList:", bundle);
            }

            scope.$applyAsync();
        };

        $(document).on('shown.bs.modal', '#assigned-show', function () {
            console.log("🔥 Tv Show Modal opened (delegated) — drag init...");
            ShowDragDrop();
        });

        function ShowDragDrop() {
            // scope.channlContentSet.channelId = [];
            // this.fetchChannel();

            const addedBundles = document.getElementById('AddShow');
            const availableBundles = document.getElementById('availableShowList');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            const ctrl = angular.element(document.getElementById('Featured_Rows')).scope().fturCtrl;
            const existing = ctrl.SelectedShow || [];

            if (existing.length > 0) {
                addedBundles.innerHTML = '';
                existing.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'channel-item';
                    div.setAttribute('draggable', 'true');
                    div.dataset.id = item.id;
                    div.innerHTML = `
                        <div class="channel-info">
                            <i class="glyphicon glyphicon-blackboard"></i>
                            ${item.name}
                        </div>
                        <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                    `;
                    const removeBtn = document.createElement('span');
                    removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                    removeBtn.className = 'remove-btn';
                    removeBtn.style.cssText = 'cursor:pointer; float:right;';

                    removeBtn.onclick = () => {
                        div.remove();
                        if (addedBundles.querySelectorAll('.channel-item').length === 0) {
                            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                        }
                        ctrl.TvShowList.push(item);
                        updateselectedTvShow();
                    };

                    div.appendChild(removeBtn);
                    addedBundles.appendChild(div);
                });
                updateselectedTvShow();
            }

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
                    updateselectedTvShow();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('draggable', 'true');
                addedBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Channel assigned: ID = ${draggedId}`);
                updateselectedTvShow();
            });

            // Update Angular scope
            function updateselectedTvShow() {
                const scope = angular.element(document.getElementById('Featured_Rows')).scope();
                const ctrl = scope?.fturCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or fturCtrl not found.");
                    return;
                }

                ctrl.selectedTvShow = [];
                addedBundles.querySelectorAll('.channel-item').forEach(card => {
                    const id = parseInt(card.dataset.id);
                    const bundle = ctrl.MasterTvShowList.find(b => b.id === id);
                    if (bundle) {
                        ctrl.selectedTvShow.push(bundle);
                        console.log(`📦 Added to selectedTvShow: ID = ${id}`);
                    }
                });

                console.log("📊 Final selectedTvShow list:", ctrl.selectedTvShow);
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

            setupSearch('searchAvailable', 'availableShowList');
            setupSearch('searchAdded', 'AddShow');
        }
        // tv show drag and drop code end

        // ==========***********==========
        // ==========***********==========

        // movie drag and drop code start
        this.fetchMovie = function () {
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.MasterMovieList = response.data.data;
                        this.MovieList = angular.copy(this.MasterMovieList);
                    }
                }
            );
        }
        this.fetchMovie();

        this.openMovieModal = function (record) {
            scope.currentEditingRecord = record;
            scope.fturCtrl.SelectedMovie = [];
            const assignedIds = new Set();
            if (record && record.content_sets && record.content_sets.vods) {
                scope.fturCtrl.SelectedMovie = angular.copy(record.content_sets.vods);
                record.content_sets.vods.forEach(c => assignedIds.add(Number(c.id)));
            }
            if (this.MasterMovieList) {
                this.MovieList = this.MasterMovieList.filter(c => !assignedIds.has(Number(c.id)));
            }
        };

        scope.fturCtrl.assignSelecteMovie = function () {
            const ctrl = scope.fturCtrl;
            if (scope.currentEditingRecord) {
                scope.currentEditingRecord.content_sets.vods = angular.copy(ctrl.selectedmovie || []);
            }
            ctrl.SelectedMovie = [];
            $('#assigned-movie').modal('hide');
        };

        scope.removeBundle = function (bundle) {
            const ctrl = scope.fturCtrl;
            ctrl.selectedVideo.bundles = (ctrl.selectedVideo.bundles || []).filter(b => b.id !== bundle.id);
            // console.log("🗑️ Removed Bundle:", bundle);

            const exists = ctrl.MovieList.some(b => b.id === bundle.id);
            if (!exists) {
                ctrl.MovieList.push(bundle);
                // console.log("🔁 Returned to ChannelList:", bundle);
            }

            scope.$applyAsync();
        };

        $(document).on('shown.bs.modal', '#assigned-movie', function () {
            // console.log("🔥 Movie Modal opened (delegated) — drag init...");
            MovieDragDrop();
        });

        function MovieDragDrop() {
            // scope.channlContentSet.channelId = [];
            // this.fetchChannel();

            const addedBundles = document.getElementById('AddMovie');
            const availableBundles = document.getElementById('availableMovieList');

            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            const ctrl = angular.element(document.getElementById('Featured_Rows')).scope().fturCtrl;
            const existing = ctrl.SelectedMovie || [];

            if (existing.length > 0) {
                addedBundles.innerHTML = '';
                existing.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'channel-item';
                    div.setAttribute('draggable', 'true');
                    div.dataset.id = item.id;
                    div.innerHTML = `
                        <div class="channel-info">
                            <i class="glyphicon glyphicon-blackboard"></i>
                            ${item.name}
                        </div>
                        <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                    `;
                    const removeBtn = document.createElement('span');
                    removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                    removeBtn.className = 'remove-btn';
                    removeBtn.style.cssText = 'cursor:pointer; float:right;';

                    removeBtn.onclick = () => {
                        div.remove();
                        if (addedBundles.querySelectorAll('.channel-item').length === 0) {
                            addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                        }
                        ctrl.MovieList.push(item);
                        updateselectedmovie();
                    };

                    div.appendChild(removeBtn);
                    addedBundles.appendChild(div);
                });
                updateselectedmovie();
            }

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
                    updateselectedmovie();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('draggable', 'true');
                addedBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Channel assigned: ID = ${draggedId}`);
                updateselectedmovie();
            });

            // Update Angular scope
            function updateselectedmovie() {
                const scope = angular.element(document.getElementById('Featured_Rows')).scope();
                const ctrl = scope?.fturCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or fturCtrl not found.");
                    return;
                }

                ctrl.selectedmovie = [];
                addedBundles.querySelectorAll('.channel-item').forEach(card => {
                    const id = parseInt(card.dataset.id);
                    const bundle = ctrl.MasterMovieList.find(b => b.id === id);
                    if (bundle) {
                        ctrl.selectedmovie.push(bundle);
                        // console.log(`📦 Added to selectedmovie: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedmovie list:", ctrl.selectedmovie);
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

            setupSearch('searchAvailable', 'availableMovieList');
            setupSearch('searchAdded', 'AddMovie');
        }
        // movie drag and drop code end

        // delte channel bundel
        this.removeChannelBundle = function (bundle) {
            var channelId = bundle.id;
            console.log(channelId);

            const payload = {
                id: bundle.id,
            }
            console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('org/app-customiztion/featured-rows/channel-delele/' + channelId), payload,
                function (response) {
                    requestFactory.setToaster('message', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            );
        }

        // delte channel bundel
        this.removeTvShowBundle = function (bundle) {
            var channelId = bundle.id;
            console.log(channelId);

            const payload = {
                id: bundle.id,
            }
            console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('org/app-customiztion/featured-rows/tvshow-delete/' + channelId), payload,
                function (response) {
                    requestFactory.setToaster('message', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            );
        }
        // ==========***********==========
        // ==========***********==========

        // delte channel bundel
        this.removeMovieBundle = function (bundle) {
            var channelId = bundle.id;
            console.log(channelId);

            const payload = {
                id: bundle.id,
            }
            console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('org/app-customiztion/featured-rows/movie-delete/' + channelId), payload,
                function (response) {
                    requestFactory.setToaster('message', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            );
        }
        // ==========***********==========
        // ==========***********==========

        this.orgWiseFeaturedRows = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const FeaturedRows = response.data.data;

                        const filterOrg = FeaturedRows.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.FeaturedRowsrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseFeaturedRows();
    }
];

window.gridControllers = {
    FeatureRowController: FeatureRowController
};