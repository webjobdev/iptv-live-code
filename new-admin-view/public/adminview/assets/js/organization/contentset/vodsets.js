var VodContentSetsController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        scope.vodset = {};
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
                requestFactory.getUrl('vod/content-set/info'),
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

            const newUrl = `${appUrl}admin/add/vod/content-set` + '?id=' + id;
            window.location.href = newUrl;

        }

        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.vodset);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            scope.vodset.organization_id = id;

            const assignedvods = scope.vodset.selectedBundles || [];
            scope.vodset.assigned_vod = assignedvods.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            requestFactory.post(
                requestFactory.getUrl('vod/content-set/save'),
                scope.vodset,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/vod/content-set?id=` + id;
                    }, 350);
                }, this.fillErrors
            );
        }

        this.updatedata = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.vodset);

            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const orgid = params.get('org_id');

            const assignedvods = scope.vodset.selectedBundles || [];
            scope.vodset.assigned_vod = assignedvods.map(event => {
                return {
                    id: event.id,
                    title: event.title
                };
            });

            requestFactory.post(
                requestFactory.getUrl('vod/content-set/update/' + id),
                scope.vodset,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/vod/content-set?id=` + orgid;
                    }, 350);
                }, this.fillErrors
            );
        }

        // scope.getAssignedvods = function (record) {
        //     if (!record.assigned_vod) return '';
        //     return record.assigned_vod.map(c => c.id + ' - ' + c.vod_name).join('<br>');
        // };

        this.edit = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/vod/content-set/edit?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        this.view = function (record, id, organization_id) {
            const newUrl = `${appUrl}admin/vod/content-set/view?id=` + id + '&org_id=' + organization_id;
            window.location.href = newUrl;
        }

        // ==========***********==========
        // ==========***********==========

        function getRecordIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            return pathParts[pathParts.length - 1];
        }

        this.fetchdata = function (callback) {
            const recordId = getRecordIdFromUrl();
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const record = response.data.data.find(item => String(item.id) === String(recordId));
                        renderData(response.data.data);
                        // console.log("🚀 Fetched vod sets:", response.data.data);
                        if (record) {
                            // console.log("✅ Found record:", record);
                            callback(record); // Pass data to fetchvod()
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
            const homeElement = document.getElementById('vodSetForm');
            if (!homeElement) {
                console.warn("⚠️ 'vodSetForm' element not found.");
                return;
            }
            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'vodSetForm' element.");
                return;
            }

            const targetOrgId = document.getElementById("chnl_id")?.value;
            const vod = chnl.find(c => String(c.id) === String(targetOrgId));

            if (!vod) {
                console.warn(`⚠️ No vod found for ID = ${targetOrgId}`);
                return;
            }

            if (typeof vod.assigned_vod === 'string') {
                try {
                    vod.assigned_vod = JSON.parse(vod.assigned_vod);
                } catch (e) {
                    console.error("❌ Failed to parse assigned_vod:", e);
                    vod.assigned_vod = [];
                }
            }

            scope.vodset = vod;
            scope.vodset.selectedBundles = scope.vodset.selectedBundles || [];
            scope.vodset.period = Number(vod.period);
            scope.vodset.is_active = (vod.is_active == 1 || vod.is_active === true);

            // ✅ Merge assigned_vod into selectedBundles (avoid duplicates)
            if (Array.isArray(vod.assigned_vod)) {
                vod.assigned_vod.forEach(bundle => {
                    const exists = scope.vodset.selectedBundles.some(b => b.id === bundle.id);
                    if (!exists) {
                        scope.vodset.selectedBundles.push(bundle);
                        // console.log(`📦 Added bundle to selectedBundles: ID = ${bundle.id}`);
                    }
                });
            }

            // console.log("📊 Final selectedBundles list:", scope.vodset.selectedBundles);

            scope.$applyAsync();

            // if (vod) {
            //     scope.vodset = vod;
            //     // scope.vodset = Number(vod.period)
            //     scope.vodsetCtrl.selectedBundles = vod.assigned_vod || [];
            //     scope.editPage = true;
            //     scope.$applyAsync();
            // }
        }
        // this.fetchdata();

        // State Tracking for Infinite Scroll
        this.vodPage = 1;
        this.hasMoreVods = true;
        this.isFetchingVods = false;
        this.vodset = [];

        this.initInfiniteScroll = function () {
            const container = document.getElementById('availableBundles');
            if (!container) return;

            container.addEventListener('scroll', () => {
                const { scrollTop, scrollHeight, clientHeight } = container;
                if (scrollTop + clientHeight >= scrollHeight - 30) {
                    if (this.hasMoreVods && !this.isFetchingVods) {
                        this.fetchVod(null, true);
                    }
                }
            });
        };

        // ==========***********==========

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
                    const filteredNewData = newData.filter(item => {
                        const isNotAssigned = !assignedIds.includes(String(item.id));

                        const currentOrgId = orgId || scope.orgIdFromUrl || (new URLSearchParams(window.location.search)).get('id');

                        // Check if the VOD is active
                        const isActive = item.is_active == 1 || item.is_active === true || item.is_active == "1";

                        const belongsToOrg = (item.organization && Number(item.organization) === Number(currentOrgId)) ||
                            (Array.isArray(item.get_all_organization) && item.get_all_organization.some(org =>
                                Number(org.id) === Number(currentOrgId) ||
                                Number(org.organization_id) === Number(currentOrgId) ||
                                (org.pivot && Number(org.pivot.organization_id) === Number(currentOrgId))
                            ));

                        return isNotAssigned && isActive && belongsToOrg;
                    });

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

        // this.fetchVod = function (assignedRecord) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const id = urlParams.get('id');
        //     requestFactory.post(
        //         requestFactory.getUrl('video-on-demand/records'),
        //         this.defineProperties,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 let eventList = response.data.data.filter(
        //                     item => String(item.organization) === String(id)
        //                 );

        //                 let assignedvods = [];

        //                 if (assignedRecord && assignedRecord.assigned_vod) {
        //                     if (typeof assignedRecord.assigned_vod === "string") {
        //                         try {
        //                             assignedvods = JSON.parse(assignedRecord.assigned_vod);
        //                             // console.log("✅ Parsed assigned_vod (from string):", assignedvods);
        //                         } catch (e) {
        //                             console.error("❌ Failed to parse assigned_vod:", e);
        //                         }
        //                     } else if (Array.isArray(assignedRecord.assigned_vod)) {
        //                         assignedvods = assignedRecord.assigned_vod;
        //                         // console.log("✅ assigned_vod is already an array:", assignedvods);
        //                     }
        //                 }

        //                 if (Array.isArray(assignedvods) && assignedvods.length > 0) {
        //                     const assignedIds = assignedvods.map(a => String(a.id));
        //                     // console.log("🆔 Assigned vod IDs to remove:", assignedIds);

        //                     const beforeFilterCount = eventList.length;
        //                     eventList = eventList.filter(ch => !assignedIds.includes(String(ch.id)));
        //                     const afterFilterCount = eventList.length;

        //                     // console.log(`🧹 Filtered vod list: ${afterFilterCount}/${beforeFilterCount} vods remain`);
        //                 }

        //                 this.vodset = eventList;

        //                 ContentDragDrop();
        //             }
        //         }
        //     );
        // }

        // this.fetchVod();

        this.fetchdata((record) => {
            this.fetchVod(record);
        });

        // $(document).ready(function () {
        //     // console.log("🚀 Initializing drag-and-drop directly...");
        //     ContentDragDrop();
        // });

        scope.removeContent = function (bundle) {
            const ctrl = scope.vodsetCtrl;

            // Ensure variables exist
            scope.vodset = scope.vodset || {};
            scope.vodset.selectedBundles = scope.vodset.selectedBundles || [];
            ctrl.vodset = ctrl.vodset || []; // ✅ make sure it's defined as array

            console.log("🗑️ Removing Channel:", bundle.id, bundle.title);

            // 1️⃣ Remove from Assigned Channels
            scope.vodset.selectedBundles = scope.vodset.selectedBundles.filter(
                ch => ch.id !== bundle.id
            );
            console.log("✅ Removed from Assigned Channels:", bundle.title);

            // 2️⃣ Add back to Available Channels (if not already there)
            const existsInAvailable = Array.isArray(ctrl.vodset)
                ? ctrl.vodset.some(ch => ch.id === bundle.id)
                : false;

            if (!existsInAvailable) {
                ctrl.vodset.push(bundle);
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
                if (e.target.classList.contains('vod-item')) {
                    const id = e.target.dataset.id;
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                }
            });

            availableBundles.addEventListener('dragend', e => {
                if (e.target.classList.contains('vod-item')) {
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

                const scope = angular.element(document.getElementById('vodSetForm')).scope();
                const ctrl = scope?.vodsetCtrl;

                if (!scope || !ctrl) return;

                // Ensure arrays exist
                scope.vodset.selectedBundles = scope.vodset.selectedBundles || [];
                ctrl.vodset = ctrl.vodset || []; // Available

                // Check if already assigned
                const isAlreadyAssigned = scope.vodset.selectedBundles.some(b => String(b.id) === String(draggedId));
                if (isAlreadyAssigned) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                // Find object in the available list (since we dragged it from there)
                const bundle = ctrl.vodset.find(b => String(b.id) === String(draggedId));

                if (bundle) {
                    // Add to assigned
                    scope.vodset.selectedBundles = scope.vodset.selectedBundles || [];
                    scope.vodset.selectedBundles.push(bundle);

                    // Remove from available
                    ctrl.vodset = ctrl.vodset.filter(b => String(b.id) !== String(draggedId));

                    scope.$applyAsync();
                } else {
                    console.error("❌ VOD not found in available list:", draggedId);
                }
            });

            // Search setup
            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) return;

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.vod-item');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.classList.toggle('hidden', !text.includes(query));
                    });
                });
            }

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');
        }

        scope.vodCount = function (record) {
            if (!record || !record.assigned_vod) return '-';

            try {
                // Parse JSON if it's a string
                const data = typeof record.assigned_vod === 'string'
                    ? JSON.parse(record.assigned_vod)
                    : record.assigned_vod;

                // ✅ Return count of items if it's an array
                if (Array.isArray(data)) {
                    return data.length;
                }
            } catch (e) {
                console.error("❌ JSON parse error in vodCount:", e);
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
                                    scope.vodset.cover_image = data.info;
                                    scope.vodset.is_posterimg_updated = 1;
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

        this.orgWiseVodSet = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const VodSet = response.data.data;

                        const filterOrg = VodSet.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.VodSetrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseVodSet();

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
                        // console.log("Organization Currency loaded:", self.orgCurrency);
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
                                    // console.log("System Default Currency loaded (Fallback):", self.orgCurrency);
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
    VodContentSetsController: VodContentSetsController
};