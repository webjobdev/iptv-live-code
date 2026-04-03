this.fetchVod = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingVods || (!this.hasMoreVods && isLoadMore)) return;
            this.isFetchingVods = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.vodPage = 1;
                this.hasMoreVods = true;
                this.vodset = [];
            }

            let apiUrl = `video-on-demand/records?rowsPerPage=200&page=${this.vodPage}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingVods = false;
                    const newData = response?.data?.data || [];

                    if (newData.length < 200) {
                        this.hasMoreVods = false;
                    }

                    if (!Array.isArray(newData)) return;

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED VODs
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('vodSetForm')).scope();
                    if (scope?.vodset?.selectedBundles) {
                        assignedIds = scope.vodset.selectedBundles.map(ch => String(ch.id));
                    } else if (assignedRecord?.assigned_vod) {
                        try {
                            const parsed = typeof assignedRecord.assigned_vod === "string"
                                ? JSON.parse(assignedRecord.assigned_vod)
                                : assignedRecord.assigned_vod;
                            assignedIds = parsed.map(ch => String(ch.id));
                        } catch (e) { }
                    }

                    /* -----------------------------
                       2️⃣ FILTER AND APPEND
                    ----------------------------- */
                    const filteredNewData = newData.filter(vod => !assignedIds.includes(String(vod.id)));

                    if (isLoadMore) {
                        this.vodset = this.vodset.concat(filteredNewData);
                    } else {
                        this.vodset = filteredNewData;
                        this.initInfiniteScroll();
                    }

                    this.vodPage++;
                    $timeout(() => ContentDragDrop(), 100);
                }
            );
        };