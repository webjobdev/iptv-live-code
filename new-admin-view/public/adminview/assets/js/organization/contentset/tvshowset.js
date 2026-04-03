var TvShowContentSetsController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        scope.tvsset = {};
        this.addplan = {};
        this.contentset = {};
        scope.editPage = false;

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('org_id') || urlObj.searchParams.get('id');

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('tv-show/content-set/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fillErrors = (response) => {
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

        this.addContent = function (event) {
            event.preventDefault();
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const newUrl = `${appUrl}admin/add/tv-show/content-set` + '?id=' + id;
            window.location.href = newUrl;

        }

        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.tvsset);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            scope.tvsset.organization_id = id;

            const assignedvods = scope.tvsset.selectedBundles || [];
            scope.tvsset.assigned_tv_show = assignedvods.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            const assignedSeasons = scope.tvsset.selectedSeasonBundles || [];
            scope.tvsset.assigned_tv_show_season = assignedSeasons.map(show => {
                return {
                    id: show.id,
                    title: show.title,
                    get_season_data: (show.get_season_data || show.get_seasons || []).map(season => {
                        return {
                            id: Number(season.id),
                            title: season.title || '',
                            season_number: Number(season.season_number) || 0
                        };
                    })
                };
            });


            const assignedEpisodes = scope.tvsset.selectedEpisodeBundles || [];
            scope.tvsset.assigned_tv_show_episode = assignedEpisodes.map(show => {
                return {
                    id: show.id,
                    title: show.title,
                    get_seasons: (show.get_seasons || []).map(season => {
                        return {
                            title: season.title || '',
                            season_number: Number(season.season_number) || 0,
                            get_episodes: (season.get_episodes || []).map(episode => {
                                return {
                                    id: episode.id,
                                    episode_name: episode.episode_name
                                };
                            })
                        };
                    })
                };
            });

            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/save'),
                scope.tvsset,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/tv-show/content-set?id=` + id;
                    }, 350);
                }, this.fillErrors
            );
        }

        this.updatedata = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.tvsset);

            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const orgid = params.get('org_id');

            const assignedvods = scope.tvsset.selectedBundles || [];
            scope.tvsset.assigned_tv_show = assignedvods.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            const assignedSeasons = scope.tvsset.selectedSeasonBundles || [];
            scope.tvsset.assigned_tv_show_season = assignedSeasons.map(show => {
                return {
                    id: show.id,
                    title: show.title,
                    get_season_data: (show.get_season_data || show.get_seasons || []).map(season => {
                        return {
                            id: Number(season.id),
                            title: season.title || '',
                            season_number: Number(season.season_number) || 0
                        };
                    })
                };
            });


            const assignedEpisodes = scope.tvsset.selectedEpisodeBundles || [];
            scope.tvsset.assigned_tv_show_episode = assignedEpisodes.map(show => {
                return {
                    id: show.id,
                    title: show.title,
                    get_seasons: (show.get_seasons || []).map(season => {
                        return {
                            title: season.title || '',
                            season_number: Number(season.season_number) || 0,
                            get_episodes: (season.get_episodes || []).map(episode => {
                                return {
                                    id: episode.id,
                                    episode_name: episode.episode_name
                                };
                            })
                        };
                    })
                };
            });

            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/update/' + id),
                scope.tvsset,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/tv-show/content-set?id=` + orgid;
                    }, 350);

                }, this.fillErrors
            );
        }

        // scope.getAssignedvods = function (record) {
        //     if (!record.assigned_tvs) return '';
        //     return record.assigned_tvs.map(c => c.id + ' - ' + c.vod_name).join('<br>');
        // };

        this.edit = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/tv-show/content-set/edit?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        this.view = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/tv-show/content-set/view?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        // ==========***********==========
        // ==========***********==========

        function getRecordIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('id')) {
                return urlParams.get('id');
            }
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        }

        this.fetchdata = function (callback) {
            const recordId = getRecordIdFromUrl();
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        renderData(response.data.data);
                        const record = response.data.data.find(item => String(item.id) === String(recordId));

                        if (record) {
                            // console.log("✅ Found record:", record);
                            callback(record); // Pass data to fetchChannel()
                        } else {
                            console.warn(`⚠️ No record found for ID = ${recordId}`);
                            callback(null);
                        }

                        // console.log("🚀 Fetched vod sets:", response.data.data);

                    } else {
                        console.warn("Invalid data format from fetchPlans:", response);
                        callback(null);
                    }
                }
            );
        }

        function renderData(chnl) {
            const homeElement = document.getElementById('tvssetForm');
            if (!homeElement) {
                console.warn("⚠️ 'tvssetForm' element not found.");
                return;
            }
            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'tvssetForm' element.");
                return;
            }

            const targetOrgId = document.getElementById("chnl_id")?.value;
            const tvs = chnl.find(c => String(c.id) === String(targetOrgId));

            if (!tvs) {
                console.warn(`⚠️ No channel found for ID = ${targetOrgId}`);
                return;
            }

            if (typeof tvs.assigned_tv_show === 'string') {
                try {
                    tvs.assigned_tv_show = JSON.parse(tvs.assigned_tv_show);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_tv_show:", e);
                    tvs.assigned_tv_show = [];
                }
            }

            if (typeof tvs.assigned_tv_show_season === 'string') {
                try {
                    tvs.assigned_tv_show_season = JSON.parse(tvs.assigned_tv_show_season);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_tv_show_season:", e);
                    tvs.assigned_tv_show_season = [];
                }
            }

            if (typeof tvs.assigned_tv_show_episode === 'string') {
                try {
                    tvs.assigned_tv_show_episode = JSON.parse(tvs.assigned_tv_show_episode);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_tv_show_episode:", e);
                    tvs.assigned_tv_show_episode = [];
                }
            }

            scope.tvsset = tvs;
            scope.tvsset.selectedBundles = scope.tvsset.selectedBundles || [];
            scope.tvsset.selectedSeasonBundles = scope.tvsset.selectedSeasonBundles || [];
            scope.tvsset.selectedEpisodeBundles = scope.tvsset.selectedEpisodeBundles || [];

            scope.tvsset.period = Number(tvs.period);
            scope.tvsset.is_active = (tvs.is_active == 1 || tvs.is_active === true);


            if (Array.isArray(tvs.assigned_tv_show)) {
                tvs.assigned_tv_show.forEach(bundle => {
                    const exists = scope.tvsset.selectedBundles.some(b => b.id === bundle.id);
                    if (!exists) {
                        scope.tvsset.selectedBundles.push(bundle);
                        // console.log(`📦 Added bundle to selectedBundles: ID = ${bundle.id}`);
                    }
                });
            }
            // console.log("📊 Final selectedBundles list:", scope.tvsset.selectedBundles);

            if (Array.isArray(tvs.assigned_tv_show_season)) {
                tvs.assigned_tv_show_season.forEach(bundle => {
                    const exists = scope.tvsset.selectedSeasonBundles.some(b => String(b.id) === String(bundle.id));
                    if (!exists) {
                        if (typeof bundle.get_season_data === 'string') {
                            try { bundle.get_season_data = JSON.parse(bundle.get_season_data); } catch (e) { }
                        }
                        if (typeof bundle.get_seasons === 'string') {
                            try { bundle.get_seasons = JSON.parse(bundle.get_seasons); } catch (e) { }
                        }
                        scope.tvsset.selectedSeasonBundles.push(bundle);
                    }
                });
            }
            // console.log("📊 Final selectedSeasonBundles list:", scope.tvsset.selectedSeasonBundles);

            if (Array.isArray(tvs.assigned_tv_show_episode)) {
                tvs.assigned_tv_show_episode.forEach(bundle => {
                    const exists = scope.tvsset.selectedEpisodeBundles.some(b => b.id === bundle.id);
                    if (!exists) {
                        scope.tvsset.selectedEpisodeBundles.push(bundle);
                        // console.log(`📦 Added bundle to selectedEpisodeBundles: ID = ${bundle.id}`);
                    }
                });
            }
            // console.log("📊 Final selectedEpisodeBundles list:", scope.tvsset.selectedEpisodeBundles);

            scope.$applyAsync();

            // if (tvs) {
            //     scope.tvsset = tvs;
            //     scope.tvsetCtrl.selectedBundles = tvs.assigned_tvs || [];
            //     scope.editPage = true;
            //     scope.$applyAsync();
            // }
        }
        // this.fetchdata();

        // State tracking for Infinite Scroll
        this.tvsPage = 1;
        this.hasMoreTvs = true;
        this.isFetchingTvs = false;
        this.tvsset = [];

        this.seasonPage = 1;
        this.hasMoreSeasons = true;
        this.isFetchingSeasons = false;
        this.seasons = [];

        this.episodePage = 1;
        this.hasMoreEpisodes = true;
        this.isFetchingEpisodes = false;
        this.episodes = [];

        // Initializers for each scrolling container
        this.initTvsInfiniteScroll = function () {
            const container = document.getElementById('availableBundles');
            if (!container) return;
            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 30) {
                    if (this.hasMoreTvs && !this.isFetchingTvs) this.fetchtvShow(null, true);
                }
            });
        };

        this.initSeasonsInfiniteScroll = function () {
            const container = document.getElementById('availableSeasonBundles');
            if (!container) return;
            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 30) {
                    if (this.hasMoreSeasons && !this.isFetchingSeasons) this.fetchSeasons(null, true);
                }
            });
        };

        this.initEpisodesInfiniteScroll = function () {
            const container = document.getElementById('availableEpisodeBundles');
            if (!container) return;
            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 30) {
                    if (this.hasMoreEpisodes && !this.isFetchingEpisodes) this.fetchEpisodes(null, true);
                }
            });
        };
        // ==========***********==========
        this.fetchtvShow = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingTvs || (!this.hasMoreTvs && isLoadMore)) return;
            this.isFetchingTvs = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.tvsPage = 1;
                this.hasMoreTvs = true;
                this.tvsset = [];
            }

            let apiUrl = `tv-show/fetch/records?rowsPerPage=200&status=1&page=${this.tvsPage}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingTvs = false;
                    const originalData = response?.data?.data || [];
                    const newData = originalData.filter(item => item.is_active == 1);

                    if (originalData.length < 200) {
                        this.hasMoreTvs = false;
                    }

                    if (!Array.isArray(newData)) return;

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED TV SHOWS
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('tvssetForm')).scope();
                    if (scope?.tvsset?.selectedBundles) {
                        assignedIds = scope.tvsset.selectedBundles.map(ch => String(ch.id));
                    } else if (assignedRecord?.assigned_tv_show) {
                        try {
                            const parsed = typeof assignedRecord.assigned_tv_show === "string"
                                ? JSON.parse(assignedRecord.assigned_tv_show)
                                : assignedRecord.assigned_tv_show;
                            assignedIds = parsed.map(ch => String(ch.id));
                        } catch (e) { }
                    }

                    /* -----------------------------
                       2️⃣ FILTER AND APPEND
                    ----------------------------- */
                    const filteredNewData = newData.filter(show => !assignedIds.includes(String(show.id)));

                    if (isLoadMore) {
                        this.tvsset = this.tvsset.concat(filteredNewData);
                    } else {
                        this.tvsset = filteredNewData;
                        this.initTvsInfiniteScroll();
                    }

                    this.tvsPage++;
                    $timeout(() => ContentDragDrop(), 100);
                }
            );
        };

        // this.fetchtvShow = function (assignedRecord) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const id = urlParams.get('id');

        //     const payload = {
        //         organization: id,
        //         ...this.defineProperties
        //     };
        //     requestFactory.post(
        //         requestFactory.getUrl('tv-show/fetch/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 let eventList = response.data.data.filter(
        //                     item => String(item.organization) === String(id)
        //                 );

        //                 let assignedChannels = [];

        //                 if (assignedRecord && assignedRecord.assigned_tv_show) {
        //                     if (typeof assignedRecord.assigned_tv_show === "string") {
        //                         try {
        //                             assignedChannels = JSON.parse(assignedRecord.assigned_tv_show);
        //                             // console.log("✅ Parsed assigned_tv_show (from string):", assignedChannels);
        //                         } catch (e) {
        //                             console.error("❌ Failed to parse assigned_tv_show:", e);
        //                         }
        //                     } else if (Array.isArray(assignedRecord.assigned_tv_show)) {
        //                         assignedChannels = assignedRecord.assigned_tv_show;
        //                         // console.log("✅ assigned_tv_show is already an array:", assignedChannels);
        //                     }
        //                 }


        //                 if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
        //                     const assignedIds = assignedChannels.map(a => String(a.id));
        //                     // console.log("🆔 Assigned Channel IDs to remove:", assignedIds);

        //                     const beforeFilterCount = eventList.length;
        //                     eventList = eventList.filter(ch => !assignedIds.includes(String(ch.id)));
        //                     const afterFilterCount = eventList.length;

        //                     // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
        //                 }

        //                 this.tvsset = eventList;
        //                 // this.tvsset = eventList.filter(item => item.organization == id);
        //                 // console.log(this.tvsset);
        //                 ContentDragDrop();
        //             }
        //         }
        //     );
        // }
        // this.fetchtvShow();
        // this.fetchdata((record) => {

        // });

        scope.removeContent = function (bundle) {
            const ctrl = scope.tvsetCtrl;

            // Ensure variables exist
            scope.tvsset = scope.tvsset || {};
            scope.tvsset.selectedBundles = scope.tvsset.selectedBundles || [];
            ctrl.tvsset = ctrl.tvsset || []; // ✅ make sure it's defined as array

            console.log("🗑️ Removing Channel:", bundle.id, bundle.tilte);

            // 1️⃣ Remove from Assigned Channels
            scope.tvsset.selectedBundles = scope.tvsset.selectedBundles.filter(
                ch => ch.id !== bundle.id
            );
            console.log("✅ Removed from Assigned Channels:", bundle.tilte);

            // 2️⃣ Add back to Available Channels (if not already there)
            const existsInAvailable = Array.isArray(ctrl.tvsset)
                ? ctrl.tvsset.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.tvsset.push(bundle);
                console.log("↩️ Returned to Available Channels:", bundle.tilte);
            }

            // Update UI
            scope.$applyAsync();
        };

        function ContentDragDrop() {
            const addedBundles = document.getElementById('addedBundles');
            const availableBundles = document.getElementById('availableBundles');


            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            // addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            // ✅ Event delegation for dragstart/dragend
            availableBundles.addEventListener('dragstart', e => {
                if (e.target.classList.contains('tvs-item')) {
                    const id = e.target.dataset.id;
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                }
            });

            availableBundles.addEventListener('dragend', e => {
                if (e.target.classList.contains('tvs-item')) {
                    e.target.classList.remove('dragging');
                }
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const scope = angular.element(document.getElementById('tvssetForm')).scope();
                const ctrl = scope?.tvsetCtrl;

                if (!scope || !ctrl) return;

                // Ensure arrays exist
                scope.tvsset.selectedBundles = scope.tvsset.selectedBundles || [];
                ctrl.tvsset = ctrl.tvsset || []; // Available

                // Check if already assigned
                const isAlreadyAssigned = scope.tvsset.selectedBundles.some(b => String(b.id) === String(draggedId));
                if (isAlreadyAssigned) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Find object in the available list (since we dragged it from there)
                const bundle = ctrl.tvsset.find(b => String(b.id) === String(draggedId));

                if (bundle) {
                    // Add to assigned
                    scope.tvsset.selectedBundles = scope.tvsset.selectedBundles || [];
                    scope.tvsset.selectedBundles.push(bundle);

                    // Remove from available
                    ctrl.tvsset = ctrl.tvsset.filter(b => String(b.id) !== String(draggedId));

                    scope.$applyAsync();
                } else {
                    console.error("❌ TV Show not found in available list:", draggedId);
                }
            });

            // Search setup
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.tvs-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }
        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // ==========***********==========

        this.fetchSeasons = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingSeasons || (!this.hasMoreSeasons && isLoadMore)) return;
            this.isFetchingSeasons = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.seasonPage = 1;
                this.hasMoreSeasons = true;
                this.seasons = [];
            }

            let apiUrl = `tv-show/fetch/season/records?rowsPerPage=200&status=1&page=${this.seasonPage}&organization_id=${orgId || ''}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingSeasons = false;
                    let fullData = [];
                    if (response && response.data && Array.isArray(response.data.data)) {
                        fullData = response.data.data;
                    } else if (response && Array.isArray(response.data)) {
                        fullData = response.data;
                    }

                    const seasonData = fullData.filter(item => item.is_active == 1);

                    if (fullData.length < 200) {
                        this.hasMoreSeasons = false;
                    }

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED SEASONS
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('tvssetForm')).scope();
                    if (scope?.tvsset?.selectedSeasonBundles) {
                        assignedIds = scope.tvsset.selectedSeasonBundles.map(ch => String(ch.id));
                    } else if (assignedRecord?.assigned_tv_show_season) {
                        try {
                            const parsed = typeof assignedRecord.assigned_tv_show_season === "string"
                                ? JSON.parse(assignedRecord.assigned_tv_show_season)
                                : assignedRecord.assigned_tv_show_season;
                            assignedIds = parsed.map(ch => String(ch.id));
                        } catch (e) { }
                    }

                    /* -----------------------------
                       2️⃣ FILTER AND APPEND
                    ----------------------------- */
                    const filteredNewData = seasonData.filter(season => !assignedIds.includes(String(season.id)));

                    if (isLoadMore) {
                        this.seasons = this.seasons.concat(filteredNewData);
                    } else {
                        this.seasons = filteredNewData;
                        this.initSeasonsInfiniteScroll();
                    }

                    this.seasonPage++;
                    $timeout(() => SeasonDragDrop(), 100);
                }
            );
        };


        // this.fetchSeasons = function (assignedRecord) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const id = urlParams.get('id');

        //     const payload = {
        //         organization: id,
        //         ...this.defineProperties
        //     };
        //     requestFactory.post(
        //         requestFactory.getUrl('tv-show/fetch/season/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 let eventList = response.data.data.filter(
        //                     item => String(item.organization) === String(id)
        //                 );

        //                 let assignedChannels = [];

        //                 if (assignedRecord && assignedRecord.assigned_tv_show_season) {
        //                     if (typeof assignedRecord.assigned_tv_show_season === "string") {
        //                         try {
        //                             assignedChannels = JSON.parse(assignedRecord.assigned_tv_show_season);
        //                             // console.log("✅ Parsed assigned_tv_show_season (from string):", assignedChannels);
        //                         } catch (e) {
        //                             console.error("❌ Failed to parse assigned_tv_show_season:", e);
        //                         }
        //                     } else if (Array.isArray(assignedRecord.assigned_tv_show_season)) {
        //                         assignedChannels = assignedRecord.assigned_tv_show_season;
        //                         // console.log("✅ assigned_tv_show_season is already an array:", assignedChannels);
        //                     }
        //                 }

        //                 if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
        //                     const assignedIds = assignedChannels.map(a => String(a.id));
        //                     // console.log("🆔 Assigned Channel IDs to remove:", assignedIds);

        //                     const beforeFilterCount = eventList.length;
        //                     eventList = eventList.filter(ch => !assignedIds.includes(String(ch.id)));
        //                     const afterFilterCount = eventList.length;

        //                     // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
        //                 }

        //                 this.seasons = eventList;
        //                 // this.seasons = eventList.filter(item => item.organization == id);
        //                 // console.log('Filtered Seasons:', this.seasons);
        //                 SeasonDragDrop();
        //             }
        //         }
        //     );
        // }
        // this.fetchdata((record) => {

        // });
        // this.fetchSeasons();

        scope.removeSeason = function (bundle) {
            const ctrl = scope.tvsetCtrl;

            // Ensure data structures exist
            scope.tvsset = scope.tvsset || {};
            scope.tvsset.selectedSeasonBundles = scope.tvsset.selectedSeasonBundles || [];
            ctrl.seasons = ctrl.seasons || []; // ✅ match the list used in fetchSeasons

            // 1️⃣ Remove from assigned (selected) list
            scope.tvsset.selectedSeasonBundles = scope.tvsset.selectedSeasonBundles.filter(
                ch => ch.id !== bundle.id
            );

            // 2️⃣ Add back to available seasons (if not already present)
            const existsInAvailable = Array.isArray(ctrl.seasons)
                ? ctrl.seasons.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.seasons.push(bundle);
                // console.log("↩️ Returned to Available Seasons:", bundle.title);
            }

            // 3️⃣ Update UI asynchronously
            scope.$applyAsync();
        };


        function SeasonDragDrop() {
            const addedBundles = document.getElementById('addedSeasonBundles');
            const availableBundles = document.getElementById('availableSeasonBundles');


            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            // addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            // ✅ Event delegation for dragstart/dragend
            availableBundles.addEventListener('dragstart', e => {
                const card = e.target.closest('.content-container');
                if (card) {
                    e.dataTransfer.setData('text/plain', card.dataset.id);
                    card.classList.add('dragging');
                }
            });

            availableBundles.addEventListener('dragend', e => {
                const card = e.target.closest('.content-container');
                if (card) {
                    card.classList.remove('dragging');
                }
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const scope = angular.element(document.getElementById('tvssetForm')).scope();
                const ctrl = scope?.tvsetCtrl;

                if (!scope || !ctrl) return;

                // Ensure arrays exist
                scope.tvsset.selectedSeasonBundles = scope.tvsset.selectedSeasonBundles || [];
                ctrl.seasons = ctrl.seasons || []; // Available seasons

                // Check if already assigned
                const isAlreadyAssigned = scope.tvsset.selectedSeasonBundles.some(b => String(b.id) === String(draggedId));
                if (isAlreadyAssigned) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Find object in available seasons
                const bundle = ctrl.seasons.find(b => String(b.id) === String(draggedId));

                if (bundle) {
                    // Add to assigned
                    scope.tvsset.selectedSeasonBundles = scope.tvsset.selectedSeasonBundles || [];
                    scope.tvsset.selectedSeasonBundles.push(bundle);

                    // Remove from available
                    ctrl.seasons = ctrl.seasons.filter(b => String(b.id) !== String(draggedId));

                    scope.$applyAsync();
                } else {
                    console.error("❌ Season not found in available list:", draggedId);
                }
            });

            // Search setup
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.tvs-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailableSeason', 'availableSeasonBundles');
            setupSearch('searchAddedSeason', 'addedSeasonBundles');
        }

        // ==========***********==========
        // ==========***********==========

        // ==========***********==========
        // ==========***********==========
        this.fetchEpisodes = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingEpisodes || (!this.hasMoreEpisodes && isLoadMore)) return;
            this.isFetchingEpisodes = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.episodePage = 1;
                this.hasMoreEpisodes = true;
                this.episodes = [];
            }

            let apiUrl = `fetch/season/episode/records?rowsPerPage=200&status=1&page=${this.episodePage}&organization_id=${orgId || ''}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingEpisodes = false;
                    const originalData = (response?.data && Array.isArray(response.data)) ? response.data : [];
                    const episodeData = originalData.filter(item => {
                        const isActive = item.is_active == 1;
                        const belongsToOrg = Array.isArray(item.get_all_organization) &&
                            item.get_all_organization.some(org => Number(org.id) === Number(orgId));
                        return isActive && belongsToOrg;
                    });

                    if (originalData.length < 200) {
                        this.hasMoreEpisodes = false;
                    }

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED EPISODES
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('tvssetForm')).scope();
                    if (scope?.tvsset?.selectedEpisodeBundles) {
                        assignedIds = scope.tvsset.selectedEpisodeBundles.map(ch => String(ch.id));
                    } else if (assignedRecord?.assigned_tv_show_episode) {
                        try {
                            const parsed = typeof assignedRecord.assigned_tv_show_episode === "string"
                                ? JSON.parse(assignedRecord.assigned_tv_show_episode)
                                : assignedRecord.assigned_tv_show_episode;
                            assignedIds = parsed.map(ch => String(ch.id));
                        } catch (e) { }
                    }

                    /* -----------------------------
                       2️⃣ FILTER AND APPEND
                    ----------------------------- */
                    const filteredNewData = episodeData.filter(ep => !assignedIds.includes(String(ep.id)));

                    if (isLoadMore) {
                        this.episodes = this.episodes.concat(filteredNewData);
                    } else {
                        this.episodes = filteredNewData;
                        this.initEpisodesInfiniteScroll();
                    }

                    this.episodePage++;
                    $timeout(() => EpisodeDragDrop(), 100);
                }
            );
        };

        // this.episodes = function (assignedRecord) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const id = urlParams.get('id');

        //     const payload = {
        //         organization: id,
        //         ...this.defineProperties
        //     };
        //     requestFactory.get(
        //         requestFactory.getUrl('tv-show/fetch/season/episode/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 let eventList = response.data.data.filter(
        //                     item => String(item.organization) === String(id)
        //                 );

        //                 let assignedChannels = [];

        //                 if (assignedRecord && assignedRecord.assigned_tv_show_episode) {
        //                     if (typeof assignedRecord.assigned_tv_show_episode === "string") {
        //                         try {
        //                             assignedChannels = JSON.parse(assignedRecord.assigned_tv_show_episode);
        //                             // console.log("✅ Parsed assigned_tv_show_episode (from string):", assignedChannels);
        //                         } catch (e) {
        //                             console.error("❌ Failed to parse assigned_tv_show_episode:", e);
        //                         }
        //                     } else if (Array.isArray(assignedRecord.assigned_tv_show_episode)) {
        //                         assignedChannels = assignedRecord.assigned_tv_show_episode;
        //                         // console.log("✅ assigned_tv_show_episode is already an array:", assignedChannels);
        //                     }
        //                 }

        //                 if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
        //                     const assignedIds = assignedChannels.map(a => String(a.id));
        //                     // console.log("🆔 Assigned Channel IDs to remove:", assignedIds);

        //                     const beforeFilterCount = eventList.length;
        //                     eventList = eventList.filter(ch => !assignedIds.includes(String(ch.id)));
        //                     const afterFilterCount = eventList.length;

        //                     // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
        //                 }

        //                 this.episodes = eventList;
        //                 // this.episodes = eventList.filter(item => item.organization == id);;
        //                 // console.log(this.episodes);
        //                 EpisodeDragDrop();
        //             }
        //         }
        //     );
        // }
        this.fetchdata((record) => {
            this.fetchtvShow(record);
            this.fetchSeasons(record);
            this.fetchEpisodes(record);
        });
        // this.episodes();


        scope.removeSeasonEpisode = function (bundle) {
            const ctrl = scope.tvsetCtrl;

            // Ensure data structures exist
            scope.tvsset = scope.tvsset || {};
            scope.tvsset.selectedEpisodeBundles = scope.tvsset.selectedEpisodeBundles || [];
            ctrl.episodes = ctrl.episodes || []; // ✅ match the list used in fetchSeasons

            // 1️⃣ Remove from assigned (selected) list
            scope.tvsset.selectedEpisodeBundles = scope.tvsset.selectedEpisodeBundles.filter(
                ch => ch.id !== bundle.id
            );

            // 2️⃣ Add back to available episodes (if not already present)
            const existsInAvailable = Array.isArray(ctrl.episodes)
                ? ctrl.episodes.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.episodes.push(bundle);
                // console.log("↩️ Returned to Available episodes:", bundle.title);
            }

            // 3️⃣ Update UI asynchronously
            scope.$applyAsync();
        };


        function EpisodeDragDrop() {
            const addedBundles = document.getElementById('addedEpisodeBundles');
            const availableBundles = document.getElementById('availableEpisodeBundles');


            if (!addedBundles || !availableBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            // Reset assigned section
            // addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            // ✅ Event delegation for dragstart/dragend
            availableBundles.addEventListener('dragstart', e => {
                const card = e.target.closest('.content-container-episode');
                if (card) {
                    e.dataTransfer.setData('text/plain', card.dataset.id);
                    card.classList.add('dragging');
                }
            });

            availableBundles.addEventListener('dragend', e => {
                const card = e.target.closest('.content-container-episode');
                if (card) {
                    card.classList.remove('dragging');
                }
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const scope = angular.element(document.getElementById('tvssetForm')).scope();
                const ctrl = scope?.tvsetCtrl;

                if (!scope || !ctrl) return;

                // Ensure arrays exist
                scope.tvsset.selectedEpisodeBundles = scope.tvsset.selectedEpisodeBundles || [];
                ctrl.episodes = ctrl.episodes || []; // Available episodes

                // Check if already assigned
                const isAlreadyAssigned = scope.tvsset.selectedEpisodeBundles.some(b => String(b.id) === String(draggedId));
                if (isAlreadyAssigned) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Find object in available episodes
                const bundle = ctrl.episodes.find(b => String(b.id) === String(draggedId));

                if (bundle) {
                    // Add to assigned
                    scope.tvsset.selectedEpisodeBundles = scope.tvsset.selectedEpisodeBundles || [];
                    scope.tvsset.selectedEpisodeBundles.push(bundle);

                    // Remove from available
                    ctrl.episodes = ctrl.episodes.filter(b => String(b.id) !== String(draggedId));

                    scope.$applyAsync();
                } else {
                    console.error("❌ Episode not found in available list:", draggedId);
                }
            });

            // Search setup
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.tvs-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailableEpisode', 'availableEpisodeBundles');
            setupSearch('searchAddedEpisode', 'addedEpisodeBundles');
        }
        // ==========***********==========
        // ==========***********==========

        scope.getTvShowCount = function (record) {
            if (!record || !record.assigned_tv_show) return '-';

            try {
                // Parse JSON if it's a string
                const data = typeof record.assigned_tv_show === 'string'
                    ? JSON.parse(record.assigned_tv_show)
                    : record.assigned_tv_show;

                // Return number of TV shows if data is an array
                if (Array.isArray(data)) {
                    return data.length;
                }
            } catch (e) {
                console.error("❌ JSON parse error in getTvShowCount:", e);
            }

            return '-';
        };


        scope.getSeasonCount = function (record) {
            if (!record || !record.assigned_tv_show_season) return '-';

            try {
                // If it's a string, parse it
                const data = typeof record.assigned_tv_show_season === 'string'
                    ? JSON.parse(record.assigned_tv_show_season)
                    : record.assigned_tv_show_season;

                // Safely access nested array
                if (Array.isArray(data) && data.length > 0) {
                    const show = data[0]; // first TV show object
                    if (show.get_season_data && Array.isArray(show.get_season_data)) {
                        return show.get_season_data.length;
                    }
                }
            } catch (e) {
                console.error("❌ JSON parse error in getSeasonCount:", e);
            }

            return '-';
        };

        scope.getEpisodeCount = function (record) {
            if (!record || !record.assigned_tv_show_episode) return '-';

            try {
                // Parse JSON if it's a string
                const data = typeof record.assigned_tv_show_episode === 'string'
                    ? JSON.parse(record.assigned_tv_show_episode)
                    : record.assigned_tv_show_episode;

                // Ensure we have at least one TV show
                if (Array.isArray(data) && data.length > 0) {
                    const show = data[0]; // first TV show object

                    // Check for get_seasons array
                    if (show.get_seasons && Array.isArray(show.get_seasons)) {
                        let totalEpisodes = 0;

                        // Loop through all seasons and count their episodes
                        show.get_seasons.forEach(season => {
                            if (season.get_episodes && Array.isArray(season.get_episodes)) {
                                totalEpisodes += season.get_episodes.length;
                            }
                        });

                        return totalEpisodes;
                    }
                }
            } catch (e) {
                console.error("❌ JSON parse error in getEpisodeCount:", e);
            }

            return '-';
        };

        // ==========***********==========
        // ==========***********==========

        $(document).ready(function () {
            /*
             * Post Image Upload Part
             */
            var posterImage = document.getElementById('cover_image');
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
                    $('#submit_cover_image').hide();
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
                $('#submit_cover_image').show();
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
                $('#submit_cover_image').prop('disabled', false);
                cropperImg.destroy();
            });
            $(document).on(
                'click',
                '#submit_cover_image',
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
                        $('#submit_cover_image').prop('disabled', true);
                        $.ajax(
                            $('meta[name="base-api-url"]').attr('content') + '/vod/content-set/poster',
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
                                    scope.tvsset.cover_image = data.info;
                                    scope.tvsset.is_posterimg_updated = 1;
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
                    document.getElementById('cover_image').src = e.target.result;
                };
                readerImg.onloadend = function (e) {
                    $('#poster_modal').modal('show');
                };
                readerImg.readAsDataURL(input.files[0]);
            }
        }

        // ==========***********==========
        // ==========***********==========

        scope.$watch('tvsset.item_type', function (newVal) {
            if (newVal === 'tv_show') {
                setTimeout(() => {
                    // console.log("🎬 Tv Show selected → initializing drag and drop...");
                    ContentDragDrop();
                }, 200);
            }
        });


        this.orgWiseTvShowSet = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const TvShowSet = response.data.data;

                        const filterOrg = TvShowSet.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.TvShowSetrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseTvShowSet();

        this.fetchOrgCurrency = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/currency/records'),
                { organization_id: scope.orgIdFromUrl },
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data) && response.data.data.length > 0) {
                        self.orgCurrency = response.data.data.map(function (c) {
                            c.short_code = c.currency_code.split(' - ')[0];
                            return c;
                        });
                        console.log("Organization Currency loaded:", self.orgCurrency);
                        scope.$applyAsync();
                    } else {
                        // Fallback to default system currency
                        requestFactory.post(
                            requestFactory.getUrl('organization/payment-service/currency/records'),
                            {}, // Empty payload to get all/system currencies
                            function (fallbackResponse) {
                                if (fallbackResponse && fallbackResponse.data && Array.isArray(fallbackResponse.data.data)) {
                                    self.orgCurrency = fallbackResponse.data.data.map(function (c) {
                                        c.short_code = c.currency_code.split(' - ')[0];
                                        return c;
                                    });
                                    console.log("System Default Currency loaded (Fallback):", self.orgCurrency);
                                    scope.$applyAsync();
                                }
                            }
                        );
                    }
                }
            );
        }
        this.fetchOrgCurrency();
    }
];

window.gridControllers = {
    TvShowContentSetsController: TvShowContentSetsController
};
