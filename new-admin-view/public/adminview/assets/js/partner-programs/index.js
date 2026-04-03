
var PartnerProgramController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var info = {};
        this.programData = {};

        scope.searchText = [];
        scope.searchData = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);
        this.isSubmitting = false;

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('partner-programs/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
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

        // to view add page
        this.addPartnerProgram = function () {
            window.location.href = 'partner-programs/add';
        }

        // to view edit page
        this.editPartnerProgram = function (id) {
            window.location.href = 'partner-programs/edit/' + id;
        }


        /**
         * Image Upload Script
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

                                    self.programData.partner_app_logo = data.info;
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

        // add partner program
        this.savePartnerProgram = function ($event) {
            $event.preventDefault();

            if (this.isSubmitting) {
                return;
            }

            this.isSubmitting = true;
            const addBtn = document.getElementById("partnerprogramadd");
            addBtn.disabled = true;

            if (window.location.href.includes('/edit')) {
                var recordId = document.getElementById('edit-program-id').value;
                if (!recordId) {
                    console.error("Record ID not found");
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    return;
                }
            }

            // const inpt = document.getElementById('fileInput');
            // const formData = new FormData();
            // const file = inpt.files[0];

            // if (file) {
            //     formData.append('image', file || '');
            // } else {
            //     formData.append('image', this.programData.partner_app_logo || '');
            // }
            const apiEndpoint = !recordId ? "partner-programs/add" : "partner-programs/edit/" + recordId;

            requestFactory.post(
                requestFactory.getUrl(apiEndpoint),
                this.programData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/partner-programs`;
                    }, 200);
                }.bind(this), (error) => {
                    this.fillError(error);
                    this.isSubmitting = false;
                    addBtn.disabled = false;
                }
            )
        }

        // check edit page is open
        scope.isEditMode = window.location.href.includes('/edit');

        // get data for edit page
        this.fetchPartnerProgramData = function () {
            requestFactory.post(
                requestFactory.getUrl('partner-programs/records'),
                this.programData,
                function (response) {
                    if (response.data && response.data && Array.isArray(response.data.data)) {
                        getPartnerProgramData(response.data.data);
                    } else {
                        console.warn("Invalid Data format from partner program :", response);
                    }
                }
            )
        }

        function getPartnerProgramData(data) {
            const editPgElmnt = document.getElementById('edit-form-div');
            if (!editPgElmnt) {
                console.warn("Edit page element not found");
                return;
            }
            const localScope = angular.element(editPgElmnt).scope();
            const targetRecordId = document.getElementById('edit-program-id')?.value;
            if (!targetRecordId) {
                console.warn("Target record ID not found");
                return;
            }

            const record = data.find(item => item.id == targetRecordId);
            if (record) {
                if (localScope && localScope.programCtrl) {
                    const updateModel = () => {
                        // const prevwImg = document.getElementById('file-name');
                        // const imgSrc = document.getElementById('preview');
                        // if (prevwImg) {
                        //     prevwImg.style.display = "block";
                        //     imgSrc.src = record.partner_app_logo_url || '';
                        // }

                        localScope.programCtrl.programData = {
                            program_name: record.program_name || '',
                            partner_provider: record.partner_provider || '',
                            partner_code: record.partner_code || '',
                            description: record.description || '',
                            api_link: record.partner_api_link || '',
                            api_key: record.api_key || '',
                            partner_app_logo: record.partner_app_logo || '',
                        };
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    }
                    else {
                        updateModel();
                    }
                } else {
                    console.warn('No Partner Program found with ID:', targetRecordId);
                }
            }
        }
        this.fetchPartnerProgramData();


        // serch user records
        this.searchUserRecords = function () {
            const getSearchalue = document.getElementById('created-by-search').value;

            const payload = {
                name: getSearchalue,
            }

            requestFactory.post(
                requestFactory.getUrl('partner-programs/search-record'),
                payload,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        console.log("Search Records : ", response);

                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        }

        // remoe partner program
        this.removePartnerProgram = function (id) {
            if (!id) {
                console.error("Record ID not found");
                return;
            }

            requestFactory.post(
                requestFactory.getUrl('partner-programs/remove/' + id),
                {},
                function (response) {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = '/admin/partner-programs';
                    }, 200);
                }
            )
        }

        scope.cancelPartnerProgram = function ($event) {
            // $event.preventDefault();
            window.location.href = `${appUrl}admin/partner-programs`;
        }


        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.program_name)) {
                scope.searchRecords.program_name = '';
            }
        })
    }];


window.gridControllers = { PartnerProgramController: PartnerProgramController };

