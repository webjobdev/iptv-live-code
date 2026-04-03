var RowOrderController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        this.rows = {};
        this.btnNo = 0;
        this.allBundles = [];

        scope.rows = {};

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

        this.opnPage = function (event) {
            event.preventDefault();
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const newUrl = `${appUrl}admin/app-customization/promotion/row-order/add` + '?id=' + id;
            window.location.href = newUrl;
        }

        this.view = function (record, id) {
            const newUrl = `${appUrl}admin/app-customization/promotion/row-order/view` + '?id=' + id;
            window.location.href = newUrl;
        }

        scope.uniqueTypes = [{ type: 'All' }];

        scope.$watch(function () {
            return self.allBundles.length;
        }, function () {
            const seen = new Set();
            const types = (self.allBundles || []).filter(item => {
                if (seen.has(item.type)) return false;
                seen.add(item.type);
                return true;
            });
            scope.uniqueTypes = [{ type: 'All' }].concat(types);
        });

        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            console.log(scope.rows);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const selectedBundles = scope.ROCtrl.selectedBundles || [];

            // Group by type
            const grouped = selectedBundles.reduce((acc, channel) => {
                let existing = acc.find(item => item["row_type"] === channel.type);
                const channelObj = {
                    id: channel.id,
                    row_name: channel.channel_name || channel.title || channel.name
                };

                if (existing) {
                    existing.row_data.push(channelObj);
                } else {
                    acc.push({
                        "row_type": channel.type,
                        "row_data": [channelObj]
                    });
                }
                return acc;
            }, []);

            scope.rows.assigne_row = grouped;
            scope.rows.organization_id = id;

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/promotion/row-order/create'),
                scope.rows,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = requestFactory.getTemplateUrl(`admin/app-customization/promotion/row-order?id=${id}`);
                    }, 650);
                }, this.fillError
            );
        };

        // ==========***********==========
        // ==========***********==========

        // drag and drop code

        const currentUrl = window.location.href;
        const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        const organization_id = urlParams.get('id');

        this.fetchMonPlan = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.monPlans = response.data.data.filter(item => item.organization_id == organization_id);
                        this.allBundles = [];
                        const uniqueMap = new Map();

                        const addItems = (items, type) => {
                            let list = items;
                            if (typeof items === 'string') {
                                try {
                                    list = JSON.parse(items);
                                } catch (e) {
                                    list = [];
                                }
                            }
                            if (Array.isArray(list)) {
                                list.forEach(item => {
                                    if (item && item.id && !uniqueMap.has(type + item.id)) {
                                        uniqueMap.set(type + item.id, { ...item, type: type });
                                    }
                                });
                            }
                        };

                        if (Array.isArray(this.monPlans)) {
                            this.monPlans.forEach(plan => {
                                if (plan.content_sets) {
                                    const cs = plan.content_sets;

                                    // Channels (from Channel Sets)
                                    if (Array.isArray(cs.channels)) {
                                        cs.channels.forEach(set => addItems(set.channels, 'channel'));
                                    }
                                    if (Array.isArray(cs.channelAddOns)) {
                                        cs.channelAddOns.forEach(set => addItems(set.channels, 'channel'));
                                    }

                                    // VOD (from VOD Sets)
                                    if (Array.isArray(cs.vods)) {
                                        cs.vods.forEach(set => addItems(set.vods, 'vod'));
                                    }
                                    if (Array.isArray(cs.vodAddOns)) {
                                        cs.vodAddOns.forEach(set => addItems(set.vods, 'vod'));
                                    }

                                    // Live Events (from Live Event Sets)
                                    const leSets = (cs.lEvents || cs.live_event || []);
                                    const leAddOns = (cs.lEventAddOns || cs.live_event_add_ons || []);
                                    [...leSets, ...leAddOns].forEach(set => {
                                        addItems(set.assigned_channels || set.live_events || set.assigned_live_event || set.live_event || set.channels || set.lEvents, 'liveevent');
                                    });

                                    // TV Shows (from TV Show Sets)
                                    const tvSets = (cs.tvShows || cs.tv_show || []);
                                    const tvAddOns = (cs.tvShowAddOns || cs.tv_show_add_ons || []);
                                    [...tvSets, ...tvAddOns].forEach(set => {
                                        addItems(set.assigned_tv_show || set.tv_shows || set.tv_show || set.tvShows, 'tvshow');
                                    });
                                }
                            });
                        }

                        this.allBundles = Array.from(uniqueMap.values());
                        // console.log("Plans Fetched:", this.monPlans);
                        // console.log("Flattened Bundles (Available Rows):", this.allBundles);
                    }
                }
            );
        }
        this.fetchMonPlan();
        // fetch logic is now centralized in fetchMonPlan to handle nested sets properly
        this.fetchChannel = function () { };
        this.fetchLiveEvent = function () { };
        this.fetchVod = function () { };
        this.fetchTvShow = function () { };

        // let orgId = window.location.href.split('?')[1].split('=')[1];

        // // fetch channel
        // this.fetchChannel = function () {
        //     const payload = {
        //         definedProperties: this.defineProperties,
        //         organization_id: orgId,
        //     };
        //     requestFactory.post(
        //         requestFactory.getUrl('channel/assigned-content/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 // const channelList = response.data.data;
        //                 this.channlset = response.data.data.map(c => ({ ...c, type: 'channel' }));
        //                 this.allBundles = this.allBundles.concat(this.channlset);
        //                 // console.log("Channel List:", channelList);
        //             }
        //         }
        //     );
        // }
        // this.fetchChannel();

        // // fetch live event
        // this.fetchLiveEvent = function () {
        //     const payload = {
        //         definedProperties: this.defineProperties,
        //         organization_id: orgId
        //     };

        //     requestFactory.post(
        //         requestFactory.getUrl('live-event/assigned-content/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 // const LiveEventList = response.data.data;
        //                 this.liveevent = response.data.data.map(e => ({ ...e, type: 'liveevent' }));
        //                 this.allBundles = this.allBundles.concat(this.liveevent);
        //                 // console.log("Live Event List:", LiveEventList);
        //             }
        //         }
        //     );
        // }
        // this.fetchLiveEvent();

        // // fetch vod
        // this.fetchVod = function () {
        //     const payload = {
        //         definedProperties: this.defineProperties,
        //         organization_id: orgId
        //     };

        //     requestFactory.post(
        //         requestFactory.getUrl('vod/assigned-content/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 // const VodList = response.data.data;
        //                 this.vod = response.data.data.map(v => ({ ...v, type: 'vod' }));
        //                 // console.log(this.vod);

        //                 this.allBundles = this.allBundles.concat(this.vod);
        //                 // console.log("Video On Demand List:", VodList);
        //             }
        //         }
        //     );
        // }
        // this.fetchVod();

        // // fetch tv show
        // this.fetchTvShow = function () {
        //     const payload = {
        //         definedProperties: this.defineProperties,
        //         organization_id: orgId
        //     };
        //     requestFactory.post(
        //         requestFactory.getUrl('tvshow/assigned-content/records'),
        //         payload,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 // const TvShowList = response.data.data;
        //                 this.tvshow = response.data.data.map(t => ({ ...t, type: 'tvshow' }));
        //                 this.allBundles = this.allBundles.concat(this.tvshow);
        //                 // console.log("Tv Show List:", TvShowList);
        //             }
        //         }
        //     );
        // }
        // this.fetchTvShow();

        // fetch radio
        // this.fetchRadio = function () {
        //     requestFactory.post(
        //         requestFactory.getUrl('radio/records'),
        //         this.defineProperties,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 // const RadioList = response.data.data;
        //                 this.radio = response.data.data.map(r => ({ ...r, type: 'radio' }));
        //                 this.allBundles = this.allBundles.concat(this.radio);
        //                 // console.log("Radio List:", RadioList);
        //             }
        //         }
        //     );
        // }
        // this.fetchRadio();


        $(document).ready(function () {
            // console.log("🚀 Initializing drag-and-drop directly...");
            ContentDragDrop();
        });

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
                    const type = e.target.dataset.type; // channel, vod, radio, etc.
                    e.dataTransfer.setData('text/plain', JSON.stringify({ id, type }));
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: ID = ${id}, TYPE = ${type}`);
                }
            });

            availableBundles.addEventListener('dragend', e => {
                if (e.target.classList.contains('channel-item')) {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: ID = ${e.target.dataset.id}, TYPE = ${e.target.dataset.type}`);
                }
            });

            // Drop zone setup
            addedBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedBundles drop zone...");
            });

            addedBundles.addEventListener('drop', e => {
                e.preventDefault();
                const droppedData = JSON.parse(e.dataTransfer.getData('text/plain'));
                const draggedId = droppedData.id;
                const draggedType = droppedData.type;

                // console.log(`📤 Drop detected. ID = ${draggedId}, TYPE = ${draggedType}`);

                const card = availableBundles.querySelector(`[data-id="${draggedId}"][data-type="${draggedType}"]`);
                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}, TYPE = ${draggedType}`);
                    return;
                }

                if (addedBundles.querySelector(`[data-id="${draggedId}"][data-type="${draggedType}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}, TYPE = ${draggedType}`);
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
                    console.log(`🗑️ Removed from assigned. Returned ID = ${draggedId}, TYPE = ${draggedType}`);

                    if (addedBundles.querySelectorAll('.channel-item').length === 0) {
                        addedBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('draggable', 'true');
                addedBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Assigned: ID = ${draggedId}, TYPE = ${draggedType}`);
                updateSelectedBundles();
            });

            // Update Angular scope
            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('channelSetForm')).scope();
                const ctrl = scope?.ROCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or ROCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedBundles.querySelectorAll('.channel-item').forEach(card => {
                    // const id = parseInt(card.dataset.id);
                    const id = card.dataset.id; // Keep as string for comparison or convert carefully
                    const type = card.dataset.type;

                    // this.type = type;

                    // // Find in the correct dataset
                    // let bundle;
                    // if (type === 'channel') bundle = ctrl.channlset.find(b => b.id === id);
                    // if (type === 'vod') bundle = ctrl.vod.find(b => b.id === id);
                    // if (type === 'liveevent') bundle = ctrl.liveevent.find(b => b.id === id);
                    // if (type === 'tvshow') bundle = ctrl.tvshow.find(b => b.id === id);
                    // if (type === 'radio') bundle = ctrl.radio.find(b => b.id === id);

                    // All data is now centralized in allBundles
                    const bundle = (ctrl.allBundles || []).find(b => b.id == id && b.type === type);

                    if (bundle) {
                        // ctrl.selectedBundles.push({ ...bundle, type });
                        ctrl.selectedBundles.push({ ...bundle });
                        // console.log(`📦 Added to selectedBundles: ID = ${id}, TYPE = ${type}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            // Search setup

            function setupSearch(inputId, containerId, selectId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);
                const select = document.getElementById(selectId);

                if (!container) return;

                function applyFilter() {
                    const query = input ? input.value.toLowerCase() : "";

                    let rawVal = select ? $(select).val() : "";

                    if (rawVal && typeof rawVal === 'string' && rawVal.indexOf('string:') === 0) {
                        rawVal = rawVal.replace('string:', '');
                    }
                    let selectedType = rawVal ? rawVal.toLowerCase() : "";
                    if (selectedType === 'all') selectedType = "";

                    const cards = container.querySelectorAll('.channel-item');
                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const type = (card.dataset.type || "").toLowerCase();

                        const matchesText = text.includes(query);
                        const matchesType = !selectedType || type === selectedType;

                        card.classList.toggle('hidden', !(matchesText && matchesType));
                    });
                }

                if (input) {
                    input.addEventListener('input', applyFilter);
                }
                if (select) {
                    select.addEventListener('change', applyFilter);
                    $(select).on('change', applyFilter);
                }

                // Initial filter
                applyFilter();
            }

            // 🔗 Pass dropdown ID also
            setupSearch('searchAvailable', 'availableBundles', 'AvailableType');
            setupSearch('searchAdded', 'addedBundles', 'AssignedType');
        }

        // ==========***********==========
        // ==========***********==========

        /**
        * Start of image upload script
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
                        aspectRatio: 338 / 170,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 338,
                        minCropBoxHeight: 170,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 338, height: 170 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/organization/app-customization/promotion/row-order/thumbnail',
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
                                    scope.rows.vertical_image = data.info;
                                    scope.rows.vertical_image = data.info;
                                    scope.rows.selected_thumb = data.info;
                                    scope.rows.is_thumbnail_updated = 1;
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
                            $('meta[name="base-api-url"]').attr('content') + '/organization/app-customization/promotion/row-order/poster',
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
                                    scope.rows.horizontal_image = data.info;
                                    scope.rows.is_posterimg_updated = 1;
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
        /**
        * End of image upload script
        * */
        // ==========***********==========
        // ==========***********==========

        this.orgWiseRowsorder = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/promotion/row-order/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const Rowsorder = response.data.data;

                        const filterOrg = Rowsorder.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        ).sort((a, b) => a.row_order - b.row_order);

                        scope.RowsorderRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseRowsorder();

        // ==========***********==========
        // ==========***********==========
        setTimeout(() => {
            function initDragDrop() {
                const sortableList = document.querySelector(".list-wrapper");

                if (!sortableList) {
                    $timeout(initDragDrop, 200);
                    return;
                }

                sortableList.addEventListener("dragstart", (e) => {
                    if (e.target.classList.contains("list-item")) {
                        setTimeout(() => e.target.classList.add("dragging"), 0);
                    }
                });

                sortableList.addEventListener("dragend", (e) => {
                    if (e.target.classList.contains("list-item")) {
                        e.target.classList.remove("dragging");
                        updateOrderToServer();
                    }
                });

                sortableList.addEventListener("dragover", (e) => {
                    e.preventDefault();

                    const draggingItem = document.querySelector(".dragging");
                    if (!draggingItem) return;

                    const siblings = [
                        ...sortableList.querySelectorAll(".list-item:not(.dragging)")
                    ];

                    const nextSibling = siblings.find(sibling => {
                        return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
                    });

                    sortableList.insertBefore(draggingItem, nextSibling);
                });
            }
            initDragDrop();
        }, 250);

        // ==========***********==========
        // ==========***********==========
        function updateOrderToServer() {
            const rows = document.querySelectorAll(".list-item");

            let newOrder = [];
            rows.forEach((row, index) => {
                newOrder.push({
                    id: row.getAttribute("data-id"),
                    // title: row.getAttribute("data-title"),
                    order: index + 1
                });
            });

            console.log("Sending new order:", newOrder);

            requestFactory.post(
                requestFactory.getUrl('organization/app-customiztion/promotion/row-order/save-order'),
                { rows: newOrder },
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 550);
                }
            );

            // $http.post('/api/rows/save-order', { rows: newOrder })
            //     .then(function (response) {
            //         console.log("Order saved successfully");
            //     })
            //     .catch(function (err) {
            //         console.error("Order save failed", err);
            //     });
        }
    }];

window.gridControllers = {
    RowOrderController: RowOrderController
};