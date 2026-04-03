var LiveEventContentSetsController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        scope.eventset = {};
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
                requestFactory.getUrl('live-event/content-set/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.addContent = function (event) {
            event.preventDefault();
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const newUrl = `${appUrl}admin/add/live-event/content-set` + '?id=' + id;
            window.location.href = newUrl;

        }

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

        this.save = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.eventset);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            scope.eventset.organization_id = id;

            const assignedChannels = scope.eventset.selectedBundles || [];
            scope.eventset.assigned_channels = assignedChannels.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/save'),
                scope.eventset,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/live-event/content-set?id=` + id;
                    }, 350);
                }, this.fillErrors
            );
        }

        this.updatedata = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.eventset);

            // const currentUrl = window.location.href;
            // const id = currentUrl.split('/').pop();

            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const orgid = params.get('org_id');

            const assignedChannels = scope.eventset.selectedBundles || [];
            scope.eventset.assigned_channels = assignedChannels.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/update/' + id),
                scope.eventset,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/live-event/content-set?id=` + orgid;
                    }, 350);
                }, this.fillErrors
            );
        }

        scope.getAssignedChannels = function (record) {
            if (!record.assigned_channels) return '';
            return record.assigned_channels.map(c => c.id + ' - ' + c.channel_name).join('<br>');
        };

        this.edit = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/live-event/content-set/edit?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        this.view = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/live-event/content-set/view?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        function getRecordIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        }

        this.fetchdata = function (callback) {
            const recordId = getRecordIdFromUrl();
            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const record = response.data.data.find(item => String(item.id) === String(recordId));
                        renderData(response.data.data);
                        // console.log("🚀 Fetched channel sets:", response.data.data);

                        if (record) {
                            // console.log("✅ Found record:", record);
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

        function renderData(chnl) {
            const homeElement = document.getElementById('channelSetForm');
            if (!homeElement) {
                console.warn("⚠️ 'channelSetForm' element not found.");
                return;
            }
            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'channelSetForm' element.");
                return;
            }

            const targetOrgId = document.getElementById("chnl_id")?.value;
            const liveevent = chnl.find(c => String(c.id) === String(targetOrgId));

            if (!liveevent) {
                console.warn(`⚠️ No liveevent found for ID = ${targetOrgId}`);
                return;
            }

            if (typeof liveevent.assigned_channels === 'string') {
                try {
                    liveevent.assigned_channels = JSON.parse(liveevent.assigned_channels);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_channels:", e);
                    liveevent.assigned_channels = [];
                }
            }

            scope.eventset = liveevent;
            scope.eventset.selectedBundles = scope.eventset.selectedBundles || [];
            scope.eventset.is_active = (liveevent.is_active == 1 || liveevent.is_active === true);

            if (Array.isArray(liveevent.assigned_channels)) {
                liveevent.assigned_channels.forEach(bundle => {
                    const exists = scope.eventset.selectedBundles.some(b => b.id === bundle.id);
                    if (!exists) {
                        scope.eventset.selectedBundles.push(bundle);
                        // console.log(`📦 Added bundle to selectedBundles: ID = ${bundle.id}`);
                    }
                });
            }
            // console.log("📊 Final selectedBundles list:", scope.eventset.selectedBundles);

            scope.$applyAsync();


            // if (channel) {

            //     scope.is_active = Number(channel.is_active) === 1;

            //     scope.livesetCtrl.selectedBundles = channel.assigned_channels || [];
            //     scope.editPage = true;
            //     scope.$applyAsync();
            // }
        }
        this.eventPage = 1;
        this.hasMoreEvents = true;
        this.isFetchingEvents = false;
        this.eventset = [];

        this.initInfiniteScroll = function () {
            const container = document.getElementById('availableBundles');
            if (!container) return;

            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 20) {
                    if (this.hasMoreEvents && !this.isFetchingEvents) {
                        this.fetchChannel(null, true);
                    }
                }
            });
        };


        // this.fetchChannel = function (assignedRecord) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const id = urlParams.get('id');
        //     requestFactory.post(
        //         requestFactory.getUrl('liveevents/records'),
        //         this.defineProperties,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 let eventList = response.data.data.filter(
        //                     item => String(item.organization) === String(id)
        //                 );

        //                 let assignedChannels = [];

        //                 if (assignedRecord && assignedRecord.assigned_channels) {
        //                     if (typeof assignedRecord.assigned_channels === "string") {
        //                         try {
        //                             assignedChannels = JSON.parse(assignedRecord.assigned_channels);
        //                             console.log("✅ Parsed assigned_channels (from string):", assignedChannels);
        //                         } catch (e) {
        //                             console.error("❌ Failed to parse assigned_channels:", e);
        //                         }
        //                     } else if (Array.isArray(assignedRecord.assigned_channels)) {
        //                         assignedChannels = assignedRecord.assigned_channels;
        //                         console.log("✅ assigned_channels is already an array:", assignedChannels);
        //                     }
        //                 }

        //                 if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
        //                     const assignedIds = assignedChannels.map(a => String(a.id));
        //                     console.log("🆔 Assigned Channel IDs to remove:", assignedIds);

        //                     eventList = eventList.filter(
        //                         le => !assignedIds.includes(String(le.id))
        //                     );

        //                     // const beforeFilterCount = eventList.length;
        //                     // eventList = eventList.filter(ch => !assignedIds.includes(String(ch.id)));
        //                     // const afterFilterCount = eventList.length;

        //                     // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
        //                 }

        //                 this.eventset = eventList;
        //                 console.log(this.eventset);

        //                 ContentDragDrop();
        //             }
        //         }
        //     );
        // }

        this.fetchChannel = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingEvents || (!this.hasMoreEvents && isLoadMore)) return;
            this.isFetchingEvents = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.eventPage = 1;
                this.hasMoreEvents = true;
                this.eventset = [];
            }

            let apiUrl = `liveevents/records?rowsPerPage=200&status=1&page=${this.eventPage}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingEvents = false;
                    const originalData = response?.data?.data || [];
                    const newData = originalData.filter(item => {
                        const isActive = item.is_active == 1;
                        const belongsToOrg = Array.isArray(item.get_all_organization) &&
                            item.get_all_organization.some(org => Number(org.id) === Number(orgId));
                        return isActive && belongsToOrg;
                    });

                    if (originalData.length < 200) {
                        this.hasMoreEvents = false;
                    }

                    if (!Array.isArray(newData)) {
                        return;
                    }

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED EVENTS
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('channelSetForm')).scope();
                    if (scope?.eventset?.selectedBundles) {
                        assignedIds = scope.eventset.selectedBundles.map(ch => String(ch.id));
                    } else if (assignedRecord?.assigned_channels) {
                        try {
                            const parsed = typeof assignedRecord.assigned_channels === 'string'
                                ? JSON.parse(assignedRecord.assigned_channels)
                                : assignedRecord.assigned_channels;
                            assignedIds = parsed.map(ch => String(ch.id));
                        } catch (e) { }
                    }

                    /* -----------------------------
                       2️⃣ FILTER AND APPEND
                    ----------------------------- */
                    const filteredNewData = newData.filter(ev => !assignedIds.includes(String(ev.id)));

                    if (isLoadMore) {
                        this.eventset = this.eventset.concat(filteredNewData);
                    } else {
                        this.eventset = filteredNewData;
                    }

                    this.eventPage++;

                    if (!isLoadMore) {
                        this.initInfiniteScroll();
                    }

                    // Delay for DOM render then re-init drag-drop hooks
                    $timeout(() => ContentDragDrop(), 100);
                }
            );
        };


        this.fetchdata((record) => {
            this.fetchChannel(record);
        });
        // this.fetchChannel();

        // $(document).ready(function () {
        //     // console.log("🚀 Initializing drag-and-drop directly...");
        //     ContentDragDrop();
        // });

        scope.removeContent = function (bundle) {
            const ctrl = scope.livesetCtrl;

            // Ensure variables exist
            scope.eventset = scope.eventset || {};
            scope.eventset.selectedBundles = scope.eventset.selectedBundles || [];
            ctrl.eventset = ctrl.eventset || []; // ✅ make sure it's defined as array

            console.log("🗑️ Removing Channel:", bundle.id, bundle.title);

            // 1️⃣ Remove from Assigned Channels
            scope.eventset.selectedBundles = scope.eventset.selectedBundles.filter(
                ch => ch.id !== bundle.id
            );
            console.log("✅ Removed from Assigned Channels:", bundle.title);

            // 2️⃣ Add back to Available Channels (if not already there)
            const existsInAvailable = Array.isArray(ctrl.eventset)
                ? ctrl.eventset.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.eventset.push(bundle);
                console.log("↩️ Returned to Available Channels:", bundle.title);
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

                const scope = angular.element(document.getElementById('channelSetForm')).scope();
                const ctrl = scope?.livesetCtrl;

                if (!scope || !ctrl) return;

                // Ensure arrays exist
                scope.eventset.selectedBundles = scope.eventset.selectedBundles || [];
                ctrl.eventset = ctrl.eventset || []; // Available events

                // Check if already assigned
                const isAlreadyAssigned = scope.eventset.selectedBundles.some(b => String(b.id) === String(draggedId));
                if (isAlreadyAssigned) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Find event object in the available list (since we dragged it from there)
                const bundle = ctrl.eventset.find(b => String(b.id) === String(draggedId));

                if (bundle) {
                    // Add to assigned
                    scope.eventset.selectedBundles = scope.eventset.selectedBundles || [];
                    scope.eventset.selectedBundles.push(bundle);

                    // Remove from available
                    ctrl.eventset = ctrl.eventset.filter(b => String(b.id) !== String(draggedId));

                    scope.$applyAsync();
                } else {
                    console.error("❌ Event not found in available list:", draggedId);
                }
            });

            // Update Angular scope


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

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }


        scope.liveEventCount = function (record) {
            if (!record || !record.assigned_channels) return '-';

            try {
                // Parse JSON if it's a string
                const data = typeof record.assigned_channels === 'string'
                    ? JSON.parse(record.assigned_channels)
                    : record.assigned_channels;

                // ✅ Return count of items if it's an array
                if (Array.isArray(data)) {
                    return data.length;
                }
            } catch (e) {
                console.error("❌ JSON parse error in channelCount:", e);
            }

            return '-';
        };


        // image code
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
                            $('meta[name="base-api-url"]').attr('content') + '/live-event/content-set/poster',
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
                                    scope.eventset.cover_image = data.info;
                                    scope.eventset.is_posterimg_updated = 1;
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

        this.orgWiseEventSet = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const EventSet = response.data.data;

                        const filterOrg = EventSet.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.liveeventrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseEventSet();

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
    LiveEventContentSetsController: LiveEventContentSetsController
};
