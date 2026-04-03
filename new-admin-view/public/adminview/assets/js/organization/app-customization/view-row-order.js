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

        // ==========***********==========
        // ==========***********==========

        this.view = function (record, id) {
            const newUrl = `${appUrl}admin/app-customization/promotion/row-order/view` + '?id=' + id;
            window.location.href = newUrl;
        }

        scope.getUniqueTypes = function () {
            const seen = new Set();
            return scope.ROCtrl.allBundles.filter(item => {
                if (seen.has(item.type)) return false;
                seen.add(item.type);
                return true;
            });
        };

        // ==========***********==========
        // ==========***********==========

        function getRecordIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id');
        }

        this.fetchData = function (callback) {

            const recordId = getRecordIdFromUrl();
            // console.log("🔍 Record ID from URL:", recordId);

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/promotion/row-order/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const record = response.data.data.find(item => String(item.id) === String(recordId));
                        renderData(response.data.data);
                        // console.log("data fetch:", response.data.data);
                        if (record) {
                            // console.log("✅ Found record:", record);
                            callback(record); // Pass data to fetchChannel()
                        } else {
                            console.warn(`⚠️ No record found for ID = ${recordId}`);
                            callback(null);
                        }
                    }
                }
            )
        }

        function renderData(rowdata) {
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

            const targetOrgId = document.getElementById("row_id")?.value;
            const row = rowdata.find(c => String(c.id) === String(targetOrgId));

            if (!row) {
                console.warn(`⚠️ No channel found for ID = ${targetOrgId}`);
                return;
            }

            scope.rows = row;
            scope.rows.selectedBundles = scope.rows.selectedBundles || [];

            row.assigne_row.forEach(group => {
                if (!Array.isArray(group.row_data)) return;
                group.row_data.forEach(bundle => {
                    const flatItem = {
                        id: bundle.id,
                        row_name: bundle.row_name,
                        row_type: group.row_type
                    };

                    const existsInBundles = scope.rows.selectedBundles.some(b => b.id === flatItem.id && b.row_type === flatItem.row_type);
                    if (!existsInBundles) scope.rows.selectedBundles.push(flatItem);

                    // console.log(`📦 Added: [${flatItem.row_type}] ${flatItem.row_name} (ID=${flatItem.id})`);
                });
            });

            // console.log("📊 Final selectedBundles list:", scope.rows.selectedBundles);
            scope.$applyAsync();
        }

        // this.fetchData();
        // ==========***********==========
        // ==========***********==========

        // drag and drop code

        // fetch channel
        this.fetchChannel = function (assignedRecord) {
            // console.log("🚀 fetchChannel() called...");
            requestFactory.post(
                requestFactory.getUrl('channel/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // const channelList = response.data.data;
                        this.channlset = response.data.data.map(c => ({ ...c, type: 'channel' }));

                        let assignedChannels = [];

                        if (assignedRecord && assignedRecord.assigne_row) {
                            if (typeof assignedRecord.assigne_row === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.assigne_row);
                                    // console.log("✅ Parsed assigne_row (from string):", assignedChannels);
                                } catch (e) {
                                    console.error("❌ Failed to parse assigne_row:", e);
                                }
                            } else if (Array.isArray(assignedRecord.assigne_row)) {
                                assignedChannels = assignedRecord.assigne_row;
                                // console.log("✅ assigne_row is already an array:", assignedChannels);
                            }
                        }

                        if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                            // Extract all IDs from all row_data arrays (any type)
                            const assignedIds = assignedChannels.flatMap(a =>
                                Array.isArray(a.row_data) ? a.row_data.map(r => String(r.id)) : []
                            );

                            // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                            if (assignedIds.length > 0) {
                                const beforeFilterCount = this.channlset.length;
                                this.channlset = this.channlset.filter(ch => !assignedIds.includes(String(ch.id)));
                                const afterFilterCount = this.channlset.length;

                                // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                            }
                        }

                        // if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                        //     const assignedIds = assignedChannels.map(a => String(a.id));
                        //     // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                        //     const beforeFilterCount = this.channlset.length;
                        //     this.channlset = this.channlset.filter(ch => !assignedIds.includes(String(ch.id)));
                        //     const afterFilterCount = this.channlset.length;

                        //     // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                        // }


                        this.allBundles = this.allBundles.concat(this.channlset);
                        // console.log("Channel List:", this.allBundles);
                    }
                }
            );
        }
        // this.fetchChannel();


        // fetch live event
        this.fetchLiveEvent = function (assignedRecord) {
            requestFactory.post(
                requestFactory.getUrl('liveevents/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // const LiveEventList = response.data.data;
                        this.liveevent = response.data.data.map(e => ({ ...e, type: 'liveevent' }));

                        let assignedChannels = [];

                        if (assignedRecord && assignedRecord.assigne_row) {
                            if (typeof assignedRecord.assigne_row === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.assigne_row);
                                    // console.log("✅ Parsed assigne_row (from string):", assignedChannels);
                                } catch (e) {
                                    console.error("❌ Failed to parse assigne_row:", e);
                                }
                            } else if (Array.isArray(assignedRecord.assigne_row)) {
                                assignedChannels = assignedRecord.assigne_row;
                                // console.log("✅ assigne_row is already an array:", assignedChannels);
                            }
                        }

                        if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                            // Extract all IDs from all row_data arrays (any type)
                            const assignedIds = assignedChannels.flatMap(a =>
                                Array.isArray(a.row_data) ? a.row_data.map(r => String(r.id)) : []
                            );

                            // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                            if (assignedIds.length > 0) {
                                const beforeFilterCount = this.liveevent.length;
                                this.liveevent = this.liveevent.filter(ch => !assignedIds.includes(String(ch.id)));
                                const afterFilterCount = this.liveevent.length;

                                // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                            }
                        }

                        this.allBundles = this.allBundles.concat(this.liveevent);
                        // console.log("Live Event List:", LiveEventList);
                    }
                }
            );
        }
        // this.fetchLiveEvent();

        // fetch vod
        this.fetchVod = function (assignedRecord) {
            requestFactory.post(
                requestFactory.getUrl('video-on-demand/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // const VodList = response.data.data;
                        this.vod = response.data.data.map(v => ({ ...v, type: 'vod' }));

                        let assignedChannels = [];

                        if (assignedRecord && assignedRecord.assigne_row) {
                            if (typeof assignedRecord.assigne_row === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.assigne_row);
                                    // console.log("✅ Parsed assigne_row (from string):", assignedChannels);
                                } catch (e) {
                                    console.error("❌ Failed to parse assigne_row:", e);
                                }
                            } else if (Array.isArray(assignedRecord.assigne_row)) {
                                assignedChannels = assignedRecord.assigne_row;
                                // console.log("✅ assigne_row is already an array:", assignedChannels);
                            }
                        }

                        if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                            // Extract all IDs from all row_data arrays (any type)
                            const assignedIds = assignedChannels.flatMap(a =>
                                Array.isArray(a.row_data) ? a.row_data.map(r => String(r.id)) : []
                            );

                            // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                            if (assignedIds.length > 0) {
                                const beforeFilterCount = this.vod.length;
                                this.vod = this.vod.filter(ch => !assignedIds.includes(String(ch.id)));
                                const afterFilterCount = this.vod.length;

                                // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                            }
                        }

                        this.allBundles = this.allBundles.concat(this.vod);
                        // console.log("Video On Demand List:", VodList);
                    }
                }
            );
        }
        // this.fetchVod();

        // fetch tv show
        this.fetchTvShow = function (assignedRecord) {
            requestFactory.post(
                requestFactory.getUrl('tv-show/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // const TvShowList = response.data.data;
                        this.tvshow = response.data.data.map(t => ({ ...t, type: 'tvshow' }));

                        let assignedChannels = [];

                        if (assignedRecord && assignedRecord.assigne_row) {
                            if (typeof assignedRecord.assigne_row === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.assigne_row);
                                    // console.log("✅ Parsed assigne_row (from string):", assignedChannels);
                                } catch (e) {
                                    console.error("❌ Failed to parse assigne_row:", e);
                                }
                            } else if (Array.isArray(assignedRecord.assigne_row)) {
                                assignedChannels = assignedRecord.assigne_row;
                                // console.log("✅ assigne_row is already an array:", assignedChannels);
                            }
                        }

                        if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                            // Extract all IDs from all row_data arrays (any type)
                            const assignedIds = assignedChannels.flatMap(a =>
                                Array.isArray(a.row_data) ? a.row_data.map(r => String(r.id)) : []
                            );

                            // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                            if (assignedIds.length > 0) {
                                const beforeFilterCount = this.tvshow.length;
                                this.tvshow = this.tvshow.filter(ch => !assignedIds.includes(String(ch.id)));
                                const afterFilterCount = this.tvshow.length;

                                // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                            }
                        }

                        this.allBundles = this.allBundles.concat(this.tvshow);
                        // console.log("Tv Show List:", TvShowList);
                    }
                }
            );
        }
        // this.fetchTvShow();

        // fetch radio
        this.fetchRadio = function (assignedRecord) {
            requestFactory.post(
                requestFactory.getUrl('radio/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // const RadioList = response.data.data;
                        this.radio = response.data.data.map(r => ({ ...r, type: 'radio' }));

                        let assignedChannels = [];

                        if (assignedRecord && assignedRecord.assigne_row) {
                            if (typeof assignedRecord.assigne_row === "string") {
                                try {
                                    assignedChannels = JSON.parse(assignedRecord.assigne_row);
                                    // console.log("✅ Parsed assigne_row (from string):", assignedChannels);
                                } catch (e) {
                                    console.error("❌ Failed to parse assigne_row:", e);
                                }
                            } else if (Array.isArray(assignedRecord.assigne_row)) {
                                assignedChannels = assignedRecord.assigne_row;
                                // console.log("✅ assigne_row is already an array:", assignedChannels);
                            }
                        }

                        if (Array.isArray(assignedChannels) && assignedChannels.length > 0) {
                            // Extract all IDs from all row_data arrays (any type)
                            const assignedIds = assignedChannels.flatMap(a =>
                                Array.isArray(a.row_data) ? a.row_data.map(r => String(r.id)) : []
                            );

                            // console.logconsole.log("🆔 Assigned Channel IDs to remove:", assignedIds);

                            if (assignedIds.length > 0) {
                                const beforeFilterCount = this.radio.length;
                                this.radio = this.radio.filter(ch => !assignedIds.includes(String(ch.id)));
                                const afterFilterCount = this.radio.length;

                                // console.log(`🧹 Filtered channel list: ${afterFilterCount}/${beforeFilterCount} channels remain`);
                            }
                        }

                        this.allBundles = this.allBundles.concat(this.radio);
                        // console.log("Radio List:", RadioList);
                    }
                }
            );
        }
        // this.fetchRadio();

        this.fetchData((record) => {
            this.fetchChannel(record);
            this.fetchLiveEvent(record);
            this.fetchVod(record);
            this.fetchTvShow(record);
            this.fetchRadio(record);
        });


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
                    const id = parseInt(card.dataset.id);
                    const type = card.dataset.type;

                    this.type = type;

                    // Find in the correct dataset
                    let bundle;
                    if (type === 'channel') bundle = ctrl.channlset.find(b => b.id === id);
                    if (type === 'vod') bundle = ctrl.vod.find(b => b.id === id);
                    if (type === 'liveevent') bundle = ctrl.liveevent.find(b => b.id === id);
                    if (type === 'tvshow') bundle = ctrl.tvshow.find(b => b.id === id);
                    if (type === 'radio') bundle = ctrl.radio.find(b => b.id === id);

                    if (bundle) {
                        ctrl.selectedBundles.push({ ...bundle, type });
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
                    const selectedType = select ? select.value.toLowerCase() : "";

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
                }

                // Initial filter
                applyFilter();
            }

            // 🔗 Pass dropdown ID also
            setupSearch('searchAvailable', 'availableBundles', 'AvailableType');
            setupSearch('searchAdded', 'addedBundles', 'AvailableType');
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


    }
];

window.gridControllers = {
    RowOrderController: RowOrderController
};