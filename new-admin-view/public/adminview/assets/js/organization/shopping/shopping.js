var ShoppingController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope,) {

        var self = this;
        this.info = {};
        this.subsData = {};

        scope.plansList = [];
        scope.recordsRight = [];
        scope.droppedBundles = [];

        scope.shoppincgCart = {
            display_configrtn: 'without_subscription'
        }

        // const currentUrl = window.location.href;
        // const urlObj = new URL(currentUrl);
        // scope.orgIdFromUrl = urlObj.searchParams.get('id');
        // console.log(scope.subscriberIdFromUrl);


        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('shoppingcart/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('id');

        this.fetchMonetizationPlans = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        scope.plansList = response.data.data;
                        // scope.plansList.filter(e => {scope.recordsRight})
                        // console.log("Plan List : ", scope.plansList);
                    } else {
                        console.warn("Invalid response format from Monetization Plan Api :", response);

                    }
                }
            );
        };
        this.fetchMonetizationPlans();


        // image upload and crop
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
                        aspectRatio: 355 / 200,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 355,
                        minCropBoxHeight: 200,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 355, height: 200 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/organization/partner-product/thumbnail',
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
                                    console.log(scope);

                                    scope.shoppincgCart.cover_image = data.info;
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

        });

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

        // remove left table class to show properly
        this.classRemove = function () {
            const leftDiv = document.getElementById('left-div');
            leftDiv.classList.remove('col-sm-6');
            leftDiv.classList.add('col-sm-12');
        }

        // data drag and drop
        this.ContentDragDrop = function () {
            const leftDiv = document.getElementById('left-div');
            leftDiv.classList.remove('col-sm-12');
            leftDiv.classList.add('col-sm-6');

            const leftTable = document.getElementById("left-table");
            const rightTable = document.getElementById("right-table");
            // console.log(leftTable, rightTable);


            if (!leftTable || !rightTable) {
                console.warn("❌ Tables not found");
                return;
            }

            let draggedRow = null;

            // Init all existing rows
            leftTable.querySelectorAll("tr").forEach(makeDraggable);
            rightTable.querySelectorAll("tr").forEach(makeDraggable);

            // Attach drag events to rows
            function makeDraggable(row) {
                row.setAttribute("draggable", "true");

                row.addEventListener("dragstart", e => {
                    draggedRow = row;
                    e.dataTransfer.effectAllowed = "move";
                    row.classList.add("dragging");
                });

                row.addEventListener("dragend", () => {
                    row.classList.remove("dragging");
                    draggedRow = null;
                });
            }

            // Handle drop logic
            function setupDropZone(table) {
                table.addEventListener("dragover", e => {
                    e.preventDefault();
                });

                table.addEventListener("drop", e => {
                    e.preventDefault();
                    if (!draggedRow) return;

                    const clone = draggedRow.cloneNode(true);
                    makeDraggable(clone);

                    const noRecordTr = document.getElementById('no-record');
                    if (noRecordTr) {
                        noRecordTr.style.display = 'none';
                    }

                    // remove edit button
                    const editBtn = clone.querySelector('#edit-btn-div');
                    if (editBtn) {
                        editBtn.remove();
                    }

                    // Add remove button if dropped to right table
                    if (table.id === "right-table") {
                        const td = document.createElement("td");
                        td.innerHTML = `
                        <button class="btn btn-xs btn-danger remove-btn">
                        <span class="glyphicon glyphicon-minus"></span>
                        </button>`;
                        clone.appendChild(td);

                        // REMOVE BUTTON CLICK EVENT
                        td.querySelector(".remove-btn").addEventListener("click", () => {
                            const removBtn = clone.querySelector('tr td:last-child');
                            removBtn.remove();

                            // add edit button again
                            const actionTd = clone.querySelector('.table-action-div');
                            const divEl = document.createElement('div');
                            divEl.id = 'edit-btn-div'
                            const btn = document.createElement('button');
                            btn.class = 'btn btn-xs btn-info';
                            btn.innerHTML = `
                            <span class="glyphicon glyphicon-edit"
                            style="font-size: 15px; color: black"></span>`;
                            divEl.appendChild(btn);

                            btn.addEventListener("click", function () {
                                const scope = angular.element(document.getElementById("table-div")).scope();
                                scope.shgCtrl.editData(clone.dataset);
                                scope.$applyAsync();
                            })
                            const deleteDiv = actionTd.querySelector('#delete-btn-div');
                            actionTd.insertBefore(divEl, deleteDiv);

                            rightTable.removeChild(clone);
                            leftTable.appendChild(clone);

                            updateSelectedBundles();
                        });
                    }

                    // Prevent duplicate
                    // if (!table.querySelector(`[data-id="${draggedRow.dataset.id}"]`)) {
                    // if (table.id === 'right-table') {
                    //     const firstRow = table.querySelector("tr");
                    //     if (firstRow) {
                    //         table.insertBefore(clone, firstRow);
                    //     } else {
                    //         table.appendChild(clone);
                    //     }
                    // } else {
                    table.appendChild(clone);
                    // }
                    draggedRow.remove();
                    updateSelectedBundles();
                })
                // });
            }

            setupDropZone(leftTable);
            setupDropZone(rightTable);

            // Update Angular scope with selected bundles
            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById("table-div")).scope();
                const ctrl = scope?.shgCtrl;

                if (!ctrl) return;

                scope.droppedBundles = [];
                const rows = rightTable.querySelectorAll('tr[data-id]');
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const id = parseInt(row.dataset.id, 10);
                    if (isNaN(id)) continue;

                    const bundle = scope.plansList.find(b => b.id === id);
                    if (bundle) scope.droppedBundles.push(bundle);
                };

                scope.$applyAsync();
            }

            // 🔎 Search filter
            function setupSearch(inputId, tableId) {
                const input = document.getElementById(inputId);
                const table = document.getElementById(tableId);
                if (!input || !table) return;

                input.addEventListener("input", () => {
                    const q = input.value.toLowerCase();
                    table.querySelectorAll("tr").forEach(row => {
                        const match = row.innerText.toLowerCase().includes(q);
                        row.style.display = match ? "" : "none";
                    });
                });
            }

            setupSearch("searchAvailabel", "left-table");
            setupSearch("searchAdded", "right-table");
        }

        // create subscription
        this.createSubscription = function (event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const openUrl = `${appUrl}admin/monitization-plan/subscription/add?id=${id}`;
            window.location.href = openUrl;
        }

        // edit subscription
        this.editData = function (record) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const openUrl = `${appUrl}admin/monitization-plan/subscription/edit/` + record.id + '?org_id=' + id;
            window.location.href = openUrl;
        }

        // update status on index page to enable or disable to show in shopping cart
        scope.toggleStatus = function (record) {
            record.status = record.status == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                status: record.status,
            };

            requestFactory.post(
                requestFactory.getUrl('shopping-cart/monetization-plan/status-update'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', error.message);
                    record.status = record.status == 1 ? 0 : 1;
                }
            );
        };


        // delete plan
        scope.deleteMonetznPlan = function (id) {
            requestFactory.post(
                requestFactory.getUrl('shopping-cart/monetization-plan/destroy/' + id),
                this.defineProperties,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                }
            )
        }


        // edit subscription
        scope.editCustomPlan = function (event, record) {
            event.preventDefault();
            console.log("Event : ", event);

            const pageEl = document.getElementById('table-div');
            let localScope;
            if (pageEl) {
                localScope = angular.element(pageEl).scope();
            }

            if (localScope) {
                scope.isEditMode = true;
                const updateModel = () => {
                    scope.shoppincgCart = {
                        id: record.id,
                        plan_name: record.plan_name,
                        plan_desc: record.description,
                        cover_image: record.cover_image,
                        label: record.label == 'Enable' ? '1' : '0',
                        additional_info: record.additional_info,
                    };
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }

        // Add Custom Plan
        scope.addCustomPlan = function (event) {
            requestFactory.post(
                requestFactory.getUrl('shopping-cart/plan-add'),
                scope.shoppincgCart,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                }
            )
        }

        // Edit Custom Plan
        scope.updateCustomPlan = function (event) {
            // console.log(scope.shoppincgCart);
            requestFactory.post(
                requestFactory.getUrl('shopping-cart/plan-edit/' + scope.shoppincgCart.id),
                scope.shoppincgCart,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                }
            )
        }

        // Remove Custom Plan
        scope.removeCustomPlan = function (event, record) {
            // console.log(record);
            requestFactory.post(
                requestFactory.getUrl('shopping-cart/plan-destroy/' + record.id),
                this.defineProperties,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);
                }
            )
        }

        // get data from org monetization planss table
        this.fetchMontznPlanData = function () {
            requestFactory.post(
                requestFactory.getUrl('shopping-cart/plans/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        scope.recordsRight = response.data.data;
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };
        this.fetchMontznPlanData();


        // update table records
        scope.updateTableRecords = function (event) {
            let payload = scope.droppedBundles.map(e => ({
                plan_id: e.id,
                plan_name: e.subscription_name,
                price: e.price,
            }));

            requestFactory.post(
                requestFactory.getUrl('shopping-cart/update-records'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.reload();
                    });
                }
            )
        }

        // update status on index page to enable or disable to show in shopping cart
        scope.toggleCustomPlanStatus = function (record) {
            record.status = record.status == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                status: record.status,
            };

            requestFactory.post(
                requestFactory.getUrl('shopping-cart/update-status'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', response.error);
                    record.status = record.status == 1 ? 0 : 1;
                }
            );
        };


        scope.isActive = function (endDate) {
            if (!endDate) {
                // console.error("End Date is missing or invalid!");
                return false;
            }

            var endDateObj = new Date(endDate).toISOString();
            var currentDate = new Date().toISOString();

            // console.log("End Date in UTC: ", endDateObj);
            // console.log("Current Date in UTC: ", currentDate);

            return new Date(endDateObj).getTime() > new Date(currentDate).getTime();
        };
    }];

window.gridControllers = {
    ShoppingController: ShoppingController
};
