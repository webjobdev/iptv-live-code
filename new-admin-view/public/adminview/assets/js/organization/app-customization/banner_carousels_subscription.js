var BannerCarouselsSubscriptionController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        scope.bnrcrs = {};
        this.addplan = {};

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

            const newUrl = `${appUrl}admin/app-customization/banner_carousels_subscription/add` + '?id=' + id;
            window.location.href = newUrl;
        }

        // ==========***********==========
        // ==========***********==========

        this.togglePlatform = function (platform) {
            if (!scope.bnrcrs.select_platform) {
                scope.bnrcrs.select_platform = [];
            }

            const index = scope.bnrcrs.select_platform.indexOf(platform);
            if (index === -1) {
                scope.bnrcrs.select_platform.push(platform);
            } else {
                scope.bnrcrs.select_platform.splice(index, 1);
            }
        };

        // ==========***********==========
        // ==========***********==========

        this.save = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.bnrcrs);

            // Get organization ID from URL
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            // Assign organization ID
            scope.bnrcrs.organization_id = id;

            // Collect all checked platforms into an array
            const selectedPlatforms = Array.from(
                document.querySelectorAll('input[name="select_platform[]"]:checked')
            ).map(el => el.value);

            // Store as array (not string) in scope
            scope.bnrcrs.select_platform = selectedPlatforms;

            // Send data to API
            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/create'),
                scope.bnrcrs,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.history.back();
                    }, 650);
                }, this.fillErrors
            );
        };

        // ==========***********==========
        // ==========***********==========

        this.updatedata = function ($event) {
            $event.preventDefault();
            console.log("🚀 data submitted:", scope.bnrcrs);

            // const currentUrl = window.location.href;
            // const id = currentUrl.split('/').pop();

            const path = window.location.pathname;
            const bcsIdPart = path.split("/").pop();
            const bcsId = bcsIdPart.split("&")[0].split("=")[1];

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/edit/' + bcsId),
                scope.bnrcrs,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.history.back();
                    }, 650);
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

        /*************  ✨ Windsurf Command 🌟  *************/
        this.edit = function (record, id) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const queryId = urlParams.get('id'); // renamed to queryId

            const newUrl = `${appUrl}admin/app-customization/banner_carousels_subscription/edit/bcs_id=${id}` + '&org_id=' + queryId;

            window.location.href = newUrl;
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        // ==========***********==========
        // ==========***********==========

        this.fetchdata = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        renderData(response.data.data);
                    } else {
                        console.warn("Invalid data format from fetchPlans:", response);
                    }
                }
            );
        }

        function renderData(chnl) {
            const homeElement = document.getElementById('BCSForm');
            if (!homeElement) {
                console.warn("⚠️ 'BCSForm' element not found.");
                return;
            }
            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'BCSForm' element.");
                return;
            }

            const path = window.location.pathname;
            const bcsIdPart = path.split("/").pop();
            const bcsId = bcsIdPart.split("&")[0].split("=")[1];

            const targetBCS = chnl.find(c => String(c.id) === String(bcsId));
            // console.log("Fetched organization:", targetBCS);

            if (targetBCS) {
                scope.bnrcrs = targetBCS;
                scope.is_active = targetBCS.is_active ? true : false;
                scope.$applyAsync();
            }
        }
        this.fetchdata();

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
                            $('meta[name="base-api-url"]').attr('content') + '/organization/ac/banner_carousels_subscription/thumbnail',
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
                                    scope.bnrcrs.thumbnail = data.info;
                                    scope.bnrcrs.thumbnail_image = data.info;
                                    scope.bnrcrs.selected_thumb = data.info;
                                    scope.bnrcrs.is_thumbnail_updated = 1;
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
                            $('meta[name="base-api-url"]').attr('content') + '/organization/ac/banner_carousels_subscription/poster',
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
                                    scope.bnrcrs.poster_image = data.info;
                                    scope.bnrcrs.is_posterimg_updated = 1;
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

        this.orgWiseBannerCarouselSub = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const BannerCarouselSub = response.data.data;

                        const filterOrg = BannerCarouselSub.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.BannerCarouselSubrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseBannerCarouselSub();
    }];

window.gridControllers = {
    BannerCarouselsSubscriptionController: BannerCarouselsSubscriptionController
};