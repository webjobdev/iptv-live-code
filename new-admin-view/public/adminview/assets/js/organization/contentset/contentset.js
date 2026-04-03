var ContentSetsController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {

        var self = this;
        this.info = {};
        scope.channlset = {};
        this.addplan = {};
        this.contentset = {};
        scope.editPage = false;
        this.selectedBundles = [];

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };
        // ==========***********==========
        // ==========***********==========

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('org_id') || urlObj.searchParams.get('id');

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
        // ==========***********==========
        // ==========***********==========

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

        this.addchannelContent = function (event) {
            event.preventDefault();
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const newUrl = `${appUrl}admin/add/channel/content-set` + '?id=' + id;
            window.location.href = newUrl;

        }
        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            $event.preventDefault();
            // console.log("🚀 data submitted:", scope.channlset);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            scope.channlset.organization_id = id;

            const assignedChannels = scope.setCtrl.selectedBundles || [];
            scope.channlset.assigned_channels = assignedChannels.map(channel => {
                return {
                    id: channel.id,
                    channel_name: channel.channel_name
                };
            });

            requestFactory.post(
                requestFactory.getUrl('channel/content-set/save'),
                scope.channlset,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/contentset?id=` + id;
                    }, 350);
                }, this.fillErrors
            );
        }
        // ==========***********==========
        // ==========***********==========

        this.updatedata = function ($event) {
            $event.preventDefault();
            // console.log("🚀 data submitted:", scope.channlset);

            // const currentUrl = window.location.href;
            // const urlParams = new URLSearchParams(window.location.href);
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const orgid = params.get('org_id');

            // console.log(id);

            const assignedChannels = scope.setCtrl.selectedBundles || [];
            scope.channlset.assigned_channels = assignedChannels.map(channel => {
                return {
                    id: channel.id,
                    channel_name: channel.channel_name
                };
            });

            requestFactory.post(
                requestFactory.getUrl('channel/content-set/update/' + id),
                scope.channlset,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/contentset?id=` + orgid;
                    }, 350);
                }, this.fillErrors
            );
        }
        // ==========***********==========
        // ==========***********==========

        scope.getAssignedChannels = function (record) {
            if (!record.assigned_channels) return '';
            return record.assigned_channels.map(c => c.id + ' - ' + c.channel_name).join('<br>');
        };
        // ==========***********==========
        // ==========***********==========

        this.edit = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/channel/content-set/edit?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }
        // ==========***********==========
        // ==========***********==========

        this.view = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/channel/content-set/view?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }
        // ==========***********==========
        // ==========***********==========

        function getRecordIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        }
        // ==========***********==========
        // ==========***********==========

        this.fetchdata = function (callback) {
            const recordId = getRecordIdFromUrl();
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const record = response.data.data.find(item => String(item.id) === String(recordId));
                        renderData(response.data.data);

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
        // ==========***********==========
        // ==========***********==========

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

            const channel = chnl.find(c => String(c.id) === String(targetOrgId));

            if (!channel) {
                console.warn(`⚠️ No channel found for ID = ${targetOrgId}`);
                return;
            }

            // Parse assigned_channels safely
            if (typeof channel.assigned_channels === 'string') {
                try {
                    channel.assigned_channels = JSON.parse(channel.assigned_channels);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_channels:", e);
                    channel.assigned_channels = [];
                }
            }

            // Initialize channlset and selectedBundles if not exists
            scope.channlset = channel;
            scope.channlset.selectedBundles = scope.channlset.selectedBundles || [];
            scope.channlset.is_active = (channel.is_active == 1 || channel.is_active === true);

            // ✅ Merge assigned_channels into selectedBundles (avoid duplicates)
            if (Array.isArray(channel.assigned_channels)) {
                channel.assigned_channels.forEach(bundle => {
                    const exists = scope.channlset.selectedBundles.some(b => b.id === bundle.id);
                    if (!exists) {
                        scope.channlset.selectedBundles.push(bundle);
                        // console.log(`📦 Added bundle to selectedBundles: ID = ${bundle.id}`);
                    }
                });
            }

            // console.log("📊 Final selectedBundles list:", scope.channlset.selectedBundles);

            scope.$applyAsync();
        }
        this.channelPage = 1;
        this.hasMoreChannels = true;
        this.isFetchingChannels = false;
        this.channlset = [];

        this.initInfiniteScroll = function () {
            const container = document.getElementById('availableBundles');
            if (!container) return;

            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 20) {
                    if (this.hasMoreChannels && !this.isFetchingChannels) {
                        this.fetchChannel(null, true);
                    }
                }
            });
        };

        this.fetchChannel = function (assignedRecord, isLoadMore = false) {
            if (this.isFetchingChannels || (!this.hasMoreChannels && isLoadMore)) return;
            this.isFetchingChannels = true;

            const urlParams = new URLSearchParams(window.location.search);
            let orgId = urlParams.get('id');
            if (window.location.href.includes('edit') && urlParams.has('org_id')) {
                orgId = urlParams.get('org_id');
            }

            if (!isLoadMore) {
                this.channelPage = 1;
                this.hasMoreChannels = true;
                this.channlset = [];
            }

            let apiUrl = `channel/records?rowsPerPage=200&status=1&page=${this.channelPage}`;

            requestFactory.post(
                requestFactory.getUrl(apiUrl),
                this.defineProperties,
                (response) => {
                    this.isFetchingChannels = false;
                    const originalData = response?.data?.data || [];
                    const newData = originalData.filter(item => {
                        const isActive = item.is_active == 1;
                        const belongsToOrg = Number(item.organization) === Number(orgId) ||
                            (Array.isArray(item.get_all_organization) &&
                                item.get_all_organization.some(org => Number(org.id) === Number(orgId)));
                        return isActive && belongsToOrg;
                    });

                    if (originalData.length < 200) {
                        this.hasMoreChannels = false;
                    }

                    if (!Array.isArray(newData)) {
                        return;
                    }

                    /* -----------------------------
                       1️⃣ PARSE ASSIGNED CHANNELS
                    ----------------------------- */
                    let assignedIds = [];
                    const scope = angular.element(document.getElementById('channelSetForm')).scope();
                    if (scope?.channlset?.selectedBundles) {
                        assignedIds = scope.channlset.selectedBundles.map(ch => String(ch.id));
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
                    const filteredNewData = newData.filter(ch => !assignedIds.includes(String(ch.id)));

                    if (isLoadMore) {
                        this.channlset = this.channlset.concat(filteredNewData);
                    } else {
                        this.channlset = filteredNewData;
                    }

                    this.channelPage++;

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
        // ==========***********==========
        // ==========***********==========


        // $(document).ready(function () {
        //     // console.log("🚀 Initializing drag-and-drop directly...");
        //     ContentDragDrop();
        // });


        scope.removeContent = function (bundle) {
            const ctrl = scope.setCtrl;

            // Ensure variables exist
            scope.channlset = scope.channlset || {};
            scope.channlset.selectedBundles = scope.channlset.selectedBundles || [];
            ctrl.channlset = ctrl.channlset || []; // ✅ make sure it's defined as array

            console.log("🗑️ Removing Channel:", bundle.id, bundle.channel_name);

            // 1️⃣ Remove from Assigned Channels
            scope.channlset.selectedBundles = scope.channlset.selectedBundles.filter(
                ch => ch.id !== bundle.id
            );
            console.log("✅ Removed from Assigned Channels:", bundle.channel_name);

            // 2️⃣ Add back to Available Channels (if not already there)
            const existsInAvailable = Array.isArray(ctrl.channlset)
                ? ctrl.channlset.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.channlset.push(bundle);
                console.log("↩️ Returned to Available Channels:", bundle.channel_name);
            }

            // Update UI
            scope.$applyAsync();
        };
        // ==========***********==========
        // ==========***********==========

        function ContentDragDrop() {
            const addedBundles = document.getElementById('addedBundles');
            const availableBundles = document.getElementById('availableBundles');

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
            function updateSelectedBundles(jay = []) {
                const scope = angular.element(document.getElementById('channelSetForm')).scope();
                const ctrl = scope?.setCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or setCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = jay;
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

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }
        // ==========***********==========
        // ==========***********==========

        scope.channelCount = function (record) {
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
                            $('meta[name="base-api-url"]').attr('content') + '/channel/content-set/poster',
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
                                    scope.channlset.cover_image = data.info;
                                    scope.channlset.is_posterimg_updated = 1;
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

        this.orgwisechnlset = function () {

            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        try {
                            const chnlset = response.data.data;

                            const filterOrg = chnlset.filter(org =>
                                Number(org.organization_id) === Number(IdFromUrl)
                            );

                            scope.channelrecords = filterOrg;
                            scope.IdFromUrl = IdFromUrl;

                        } catch (e) {
                            console.error("Error in subscriberdevice:", e);
                        }
                    }
                }
            );
        }

        this.orgwisechnlset();

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
    ContentSetsController: ContentSetsController
};
