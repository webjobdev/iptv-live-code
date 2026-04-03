var PartnerProductController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.product = {};
        scope.errors = {};
        requestFactory.getToaster();
        scope.searchRecords = {};
        requestFactory.setThisArgument(this);

        this.defineProperties = function () {
            this.info = DataTransfer.info;
            requestFactory.getToaster();
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organizations/partner-product/info'), this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                },
            );
        }
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

        // ==============================***********************************==============================
        // create code
        // ==============================***********************************==============================

        this.save = function ($event, id) {
            scope.errors = {};
            const productId = id;
            console.log("send data:", this.product);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgid = urlParams.get('id');

            this.product.organization_id = orgid;

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('organizations/partner-product/edit/' + productId), this.product,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillErrors
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('organizations/partner-product/create'), this.product,
                    (response) => {
                        scope.getRecords(true);
                        requestFactory.getToaster();
                        requestFactory.setToaster('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillErrors
                );
            }
        }

        this.fillError = function (response) {
            $('#loaderimg').hide();
            if (response.status == 422 && response.data.hasOwnProperty('message')) {
                angular.forEach(response.data.message, function (message, key) {
                    if (typeof message == 'object' && message.length > 0) {
                        scope.errors[key] = { has: true, message: message[0] };
                        $('#loaderimg').hide();
                    }
                });
            }
        };


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

                                    self.product.thumbnail = data.info;
                                    self.product.product_image = data.info;
                                    self.product.selected_thumb = data.info;
                                    self.product.is_thumbnail_updated = 1;
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

        /**
         * End of image upload script
         * */
        // ==============================***********************************==============================
        // fetch details code
        // ==============================***********************************==============================



        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.addData = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.product = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#partner-productForm").css('display', 'block');
            // $("#partner-productFormTranslationForm").css('display', "none");
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.product.id = records.id;
            this.product.product_name = records.product_name;
            this.product.partner_program = records.partner_program;
            this.product.product_description = records.product_description;
            this.product.product_id = records.product_id;
            this.product.product_image = records.product_image;

            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);

            $("#partner-productForm").css('display', 'block');
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });


        this.orgWisePP = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organizations/partner-product/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const pp = response.data.data;

                        const filterOrg = pp.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.partnerProductRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWisePP();

    }
];

window.gridControllers = {
    PartnerProductController: PartnerProductController
};
