var GeneralController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.general = {};
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
                requestFactory.getUrl('app-customization/general/info'), this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                },
            );
        }
        this.fetchInfo();

        // ==============================***********************************==============================
        // create code 
        // ==============================***********************************==============================

        this.save = function ($event, id) {
            scope.errors = {};
            const generalId = id;
            console.log("send data:", this.general);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgid = urlParams.get('id');

            this.general.organization_id = orgid;

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('app-customization/general/edit/' + generalId), this.general,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillErrors
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('app-customization/general/create'), this.general,
                    (response) => {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillErrors
                );
            }
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
                        aspectRatio: 500 / 295,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 500,
                        minCropBoxHeight: 295,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 500, height: 295 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/app-customization/general/thumbnail',
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

                                    self.general.thumbnail = data.info;
                                    self.general.thumbnail_image = data.info;
                                    self.general.selected_thumb = data.info;
                                    self.general.is_thumbnail_updated = 1;
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
            this.general = {};
            $("#GeneralForm").css('display', 'block');
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.general.id = records.id;
            this.general.live = (records.live == 1);
            this.general.epg = (records.epg == 1);
            this.general.catchup = (records.catchup == 1);
            this.general.movie = (records.movie == 1);
            this.general.sereis = (records.sereis == 1);
            this.general.event = (records.event == 1);
            this.general.thumbnail_image = records.thumbnail_image;
            $("#GeneralForm").css('display', 'block');
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.live)) {
                scope.searchRecords.live = 'all';
            }
        });

        this.orgWiseGeneral = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('app-customization/general/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const General = response.data.data;

                        const filterOrg = General.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.GeneralRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseGeneral();

    }
];

window.gridControllers = {
    GeneralController: GeneralController
};