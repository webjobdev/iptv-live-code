
var CustomizationController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        this.banner = {}
        this.appcustomization = {};
        scope.bnrcrs = {};

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organization/customization/info'),
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

        // ======================================*****************************************======================================
        // ======================================*****************************************======================================

        // update record
        this.UpdateRecord = function ($event, id) {
            const EditId = id;

            const payload = {
                auto_scrolling: this.banner.auto_scrolling,
                second: this.banner.second,
                subscription_name: this.banner.subscription_name,
                banners: this.banners.map(b => ({
                    id: b.id,
                    banner_image: b.banner_image,
                    banner_is_active: b.banner_is_active ? 1 : 0
                }))
            };
            console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('org/app-customiztion/banner_carousel/edit/' + EditId),
                payload,
                function (response) {
                    requestFactory.setToaster('success', 'Banner Carousel Data Updated.');
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.reload();
                    }, 350);
                }, this.fillError
            );
        }

        scope.Updatetoggledata = function (record, id) {
            const toggleID = id;

            record.banner_carousel_is_active = record.banner_carousel_is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                banner_carousel_is_active: record.banner_carousel_is_active
            };

            requestFactory.post(
                requestFactory.getUrl('organization/app-customiztion/banner_carousel/toggle/edit/' + toggleID),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 350);
                }
            );
        }

        this.addBannerCarouselSubscription = function (banner, record) {

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const bannerId = banner && banner.id ? banner.id : '';
            const palnId = record && record.id ? record.id : '';

            const newUrl = `${appUrl}admin/app-customization/banner_carousels/add` + '?id=' + id + '&banner_id=' + bannerId + '&plan_id=' + palnId;
            window.location.href = newUrl;
        }

        this.save = function ($event) {
            $event.preventDefault();

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            const bannerId = urlParams.get('banner_id');
            const planId = urlParams.get('plan_id');

            scope.bnrcrs = scope.bnrcrs || {};

            scope.bnrcrs.organization_id = id;
            scope.bnrcrs.banner_id = bannerId;
            scope.bnrcrs.plan_id = planId;

            const selectedPlatforms = Array.from(
                document.querySelectorAll('input[name="select_platform[]"]:checked')
            ).map(el => el.value);

            scope.bnrcrs.select_platform = selectedPlatforms;

            if (scope.isEditMode && scope.bnrcrsEditId) {
                requestFactory.post(
                    requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/edit/' + scope.bnrcrsEditId),
                    scope.bnrcrs,
                    function (response) {
                        requestFactory.setToaster('success', response.message || 'Updated successfully.');
                        requestFactory.getToaster();
                        setTimeout(() => { window.history.back(); }, 350);
                    }, this.fillErrors
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/create'),
                    scope.bnrcrs,
                    function (response) {
                        requestFactory.setToaster('success', response.message || 'Saved successfully.');
                        requestFactory.getToaster();
                        setTimeout(() => { window.history.back(); }, 350);
                    }, this.fillErrors
                );
            }
        };

        this.loadBannerSubscriptionData = function () {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const bannerId = urlParams.get('banner_id');

            if (!bannerId) return;

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/banner_carousels_subscription/records'),
                {},
                function (response) {
                    var records = (response && response.data && Array.isArray(response.data.data))
                        ? response.data.data
                        : (response && Array.isArray(response.data) ? response.data : []);

                    const existing = records.find(function (r) {
                        return String(r.banner_id) === String(bannerId);
                    });

                    if (existing) {
                        scope.bnrcrs = existing;
                        scope.bnrcrs.is_active = existing.is_active == 1;
                        scope.isEditMode = true;
                        scope.bnrcrsEditId = existing.id;
                        scope.pageTitle = 'Edit Banner Carousel Subscription';
                    } else {
                        scope.isEditMode = false;
                        scope.bnrcrsEditId = null;
                        scope.pageTitle = 'Add Banner Carousel Subscription';
                    }

                    scope.$applyAsync();
                },
                function (err) {
                    console.warn('Could not load subscription records:', err);
                }
            );
        };
        this.loadBannerSubscriptionData();

        // ======================================*****************************************======================================
        // ======================================*****************************************======================================

        // fetch detaile using api
        this.fetchData = function (plan) {
            // console.log("plan:", plan);

            const collapseId = "collapse-" + plan.id;
            const homeElement = document.getElementById(collapseId);

            if (!homeElement) {
                console.warn("⚠️ Element with id", collapseId, "not found");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            if (!localScope) {
                console.warn("⚠️ Angular scope not found for element", collapseId);
                return;
            }

            localScope.ctzCtrl.banner = plan;
            localScope.ctzCtrl.banners = plan.banners || [];

            localScope.$applyAsync();
        }
        // ======================================*****************************************======================================
        // ======================================*****************************************======================================

        this.maxBanners = 10;
        this.banners = [];

        this.addBanner = function () {
            if (this.banners.length >= this.maxBanners) {
                alert("You can only add up to " + this.maxBanners + " banners.");
                return;
            }

            this.banners.push({
                id: Date.now() + Math.floor(Math.random() * 1000),
                file: null,
                preview: "",
                active: true
            });
        };

        this.removeBanner = function (banner) {
            var bannerId = banner.id;

            // If banner_image is null
            if (!banner.banner_image) {
                requestFactory.post(
                    requestFactory.getUrl('org/app-customiztion/banner_carousel/banner/delete/' + bannerId),
                    { id: bannerId },
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 350);
                    }
                );
            }
            // If banner_image is not null
            else {
                const payload = {
                    id: banner.id,
                    banner_image: banner.banner_image,
                    banner_is_active: banner.banner_is_active,
                };

                requestFactory.post(
                    requestFactory.getUrl('org/app-customiztion/banner_carousel/banner/delete/' + bannerId),
                    payload,
                    function (response) {
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 350);
                    }
                );
            }
        };

        this.previewImage = function (event, index) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    scope.$apply(function () {
                        this.banners[index].preview = e.target.result;
                    });
                };
                reader.readAsDataURL(file);
            }
        };

        // ======================================*****************************************======================================
        // ======================================*****************************************======================================

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
                var bannerId = $(this).data('banner-id');
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
                $('#modal').attr('data-banner-id', bannerId);
                // var videoIndex = e.target.getAttribute('data-video-index');
                // $('#modal .video-index').val(videoIndex);
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
                        aspectRatio: 500 / 320,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 500,
                        minCropBoxHeight: 320,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 500, height: 320 };
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

            $(document).on('click', '#submit-image', requestFactory.access_token, function () {
                cropBoxData = cropper.getCropBoxData();
                canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
                    var formData = new FormData();

                    formData.append('module', 'video');
                    formData.append('size', 'thumb');
                    formData.append('image', blob);

                    $('.crop-body').hide();
                    $('.loader-container').show();
                    $('#submit-image').prop('disabled', true);

                    var bannerId = $('#modal').attr('data-banner-id');

                    $.ajax(
                        $('meta[name="base-api-url"]').attr('content') + '/organization/app-customiztion/banner_carousels/thumbnail',
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
                                $('.uploaded_img_' + bannerId).attr('src', data.info).show();

                                // Update the data inside AngularJS scope
                                var scope = angular.element($('#banner-wrapper')).scope();

                                var foundBanner = scope.ctzCtrl.banners.find(b => b.id == bannerId);

                                if (foundBanner) {
                                    foundBanner.banner_image = data.info;
                                    scope.$apply();
                                }

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

        // ======================================*****************************************======================================
        // ======================================*****************************************======================================

        this.orgWiseBannerCarousel = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const BannerCarousel = response.data.data;

                        const filterOrg = BannerCarousel.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.BannerCarouselrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseBannerCarousel();
    }
];

window.gridControllers = {
    CustomizationController: CustomizationController
};