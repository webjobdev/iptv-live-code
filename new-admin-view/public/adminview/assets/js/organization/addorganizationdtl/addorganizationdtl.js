'use strict';

var AddOrganizationDetailController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        this.organizationdetail = {};
        this.organizationsetting = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organization/detail/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };

        this.fetchInfo();


        scope.$on('afterGetRecords', function () {
            if (angular.isUndefined(scope.searchRecords?.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
            setTimeout(() => {
                $("#fixTable").tableHeadFixer({ head: false, right: 1 });
            }, 500);
        });

        this.addDetail = function () {
            scope.error = {};
            this.organizationdetail = {};
        };

        this.save = function ($event) {
            $event.preventDefault();

            // const logoInput = document.getElementById('organization_logo');
            const orgIdInput = document.getElementById('org-id');
            const prefixInput = document.getElementById('prefix');

            if (!orgIdInput) {
                console.error("Required input elements not found.");
                return;
            }

            this.organizationdetail.login_token = document.getElementById('loginToken')?.value || '';
            this.organizationdetail.api_token = document.getElementById('apiToken')?.value || '';

            const formData = new FormData();
            // const logoFile = logoInput.files[0];

            // if (logoFile) {
            //     formData.append('organization_logo', logoFile);
            // }

            formData.append('organization_name', this.organizationdetail.organization_name || '');
            formData.append('organization_logo', this.organizationdetail.organization_logo || '');
            formData.append('organization_id', orgIdInput.value);
            formData.append('prefix', prefixInput.value);
            formData.append('api_access', this.organizationdetail.api_access ? 1 : 0);
            formData.append('login_token', this.organizationdetail.login_token || '');
            formData.append('api_token', this.organizationdetail.api_token || '');

            const selectedPlatforms = Array.from(
                document.querySelectorAll('input[name="select_platform[]"]:checked')
            ).map(el => el.value);

            formData.append('select_platform', JSON.stringify(selectedPlatforms));

            fetch(`${apiUrl}/organization/general/setting/add`, {
                method: "POST",
                body: formData,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                }
            })
                .then(res => res.json())
                .then(response => {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    console.warn("API Response:", response.message);
                    setTimeout(function () {
                        location.reload();
                    }, 650);
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                });

        };

        this.settingsave = function ($event) {
            $event?.preventDefault();

            const getValue = (id) => document.getElementById(id)?.value || '';
            const getChecked = (name) => document.querySelector(`input[name="${name}"]:checked`)?.value || 0;

            const formData = new FormData();
            const setting = this.organizationsetting || {};

            // Basic Inputs
            formData.append('organization_id', getValue('id'));
            formData.append('max_activation_length', getValue('max_activation_length'));
            formData.append('device_activation_limit', getValue('device_activation_limit'));
            formData.append('void_payment_in', getValue('void_payment_in'));
            formData.append('expired_voucher_removal', getValue('expired_voucher_removal'));
            formData.append('voucher_slots', getValue('voucher_slots'));
            formData.append('link_code_expiration', getValue('link_code_expiration'));

            // Radio Inputs
            const radios = [
                ['custom_charges', 'custom_charges_system_default'],
                ['custom_subscription', 'custom_subscription_system_default'],
                ['device_slots', 'device_slots_system_default'],
                ['device_linking', 'device_linking_system_default'],
                ['active_toa', 'active_toa_system_default'],
                ['subscription_activation', 'subscription_activation_system_default'],
                ['subscription_prorating', 'subscription_prorating_system_default'],
                ['content_add_on_prorating', 'content_add_on_prorating_system_default']
            ];

            radios.forEach(([key, sysDefault]) => {
                formData.append(key, getChecked(key));
                formData.append(sysDefault, getChecked(sysDefault));
            });

            formData.append('voucher_subscribers', setting.voucher_subscribers ? 1 : 0);
            formData.append('unlimited', getChecked('unlimited'));
            formData.append('max_activation_length_system_default', getChecked('max_activation_length_system_default'));
            formData.append('device_activation_limit_system_default', getChecked('device_activation_limit_system_default'));
            formData.append('void_payment_in_system_default', getChecked('void_payment_in_system_default'));
            formData.append('disallow_void', getChecked('disallow_void'));
            formData.append('custom_charges_system_default', getChecked('custom_charges_system_default'));
            formData.append('custom_subscription_system_default', getChecked('custom_subscription_system_default'));
            formData.append('device_slots_system_default', getChecked('device_slots_system_default'));
            formData.append('device_linking_system_default', getChecked('device_linking_system_default'));
            formData.append('link_code_expiration_system_default', getChecked('link_code_expiration_system_default'));
            formData.append('active_toa_system_default', getChecked('active_toa_system_default'));
            formData.append('subscription_activation_system_default', getChecked('subscription_activation_system_default'));
            formData.append('subscription_prorating_system_default', getChecked('subscription_prorating_system_default'));
            formData.append('content_add_on_prorating_system_default', getChecked('content_add_on_prorating_system_default'));
            formData.append('voucher_subscribers_system_default', getChecked('voucher_subscribers_system_default'));
            formData.append('expired_voucher_removal_system_default', getChecked('expired_voucher_removal_system_default'));
            formData.append('voucher_slots_system_default', getChecked('voucher_slots_system_default'));

            fetch(`${apiUrl}/organization/setting/add`, {
                method: "POST",
                body: formData,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                }
            })
                .then(res => res.json())
                .then(response => {
                    // if (response.success) {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        location.reload();
                    }, 650);
                })
                .catch(err => console.error("Fetch error:", err));
        };


        this.fetchPlans = function () {
            const urlParams = new URLSearchParams(window.location.search);
            const urlId = urlParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records'),
                { organization_id: urlId },
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        renderOrganization(response.data.data);
                        renderOrganizationSetting(response.data.data);
                    } else {
                        console.warn("Invalid data format from fetchPlans:", response);
                    }
                }
            );
        };

        function renderOrganization(organizations) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                console.error("Element with ID 'home' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();

            const targetOrgId = document.getElementById("org-id")?.value;

            if (!targetOrgId) {
                console.warn("Target organization ID not found.");
                return;
            }

            const org = organizations.find(o => String(o.organization_id) === String(targetOrgId));

            if (org) {
                // console.log("Fetched organization:", org);

                if (localScope && localScope.adoCtrl) {
                    const updateModel = () => {
                        localScope.adoCtrl.organizationdetail = {
                            organization_logo: org.organization_logo,
                            organization_name: org.organization_name,
                            provider_id: org.provider_id,
                            organization_id: org.organization_id,
                            prefix: org.prefix,
                            select_platform: org.select_platform || [],
                            api_access: org.api_access == 1 || org.api_access === true,
                            login_token: org.login_token,
                            api_token: org.api_token
                        };
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No organization found with ID: ${targetOrgId}`);
            }
        }

        function renderOrganizationSetting(organizations) {
            const homeElement = document.getElementById("menu1");
            if (!homeElement) {
                console.error("Element with ID 'menu1' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();

            const targetOrgId = document.getElementById("org-id")?.value;

            if (!targetOrgId) {
                console.warn("Target organization ID not found.");
                return;
            }

            const org = organizations.find(o => String(o.organization_id) === String(targetOrgId));

            if (org) {
                // console.log("Fetched organization:", org);

                if (localScope && localScope.adoCtrl) {
                    const updateModel = () => {
                        localScope.adoCtrl.organizationsetting = {
                            max_activation_length: org.max_activation_length,
                            device_activation_limit: org.device_activation_limit,
                            custom_charges: org.custom_charges,
                            custom_subscription: org.custom_subscription,
                            device_slots: org.device_slots,
                            device_linking: org.device_linking,
                            link_code_expiration: org.link_code_expiration,
                            active_toa: org.active_toa,
                            subscription_prorating: org.subscription_prorating,
                            content_add_on_prorating: org.content_add_on_prorating,
                            voucher_subscribers: org.voucher_subscribers,
                            expired_voucher_removal: org.expired_voucher_removal,
                            voucher_slots: org.voucher_slots,
                            subscription_activation: org.subscription_activation,
                            void_payment_in: org.void_payment_in,
                            unlimited: org.unlimited,
                            max_activation_length_system_default: org.max_activation_length_system_default,
                            device_activation_limit_system_default: org.device_activation_limit_system_default,
                            void_payment_in_system_default: org.void_payment_in_system_default,
                            disallow_void: org.disallow_void,
                            custom_charges_system_default: org.custom_charges_system_default,
                            custom_subscription_system_default: org.custom_subscription_system_default,
                            device_slots_system_default: org.device_slots_system_default,
                            device_linking_system_default: org.device_linking_system_default,
                            link_code_expiration_system_default: org.link_code_expiration_system_default,
                            active_toa_system_default: org.active_toa_system_default,
                            subscription_activation_system_default: org.subscription_activation_system_default,
                            subscription_prorating_system_default: org.subscription_prorating_system_default,
                            content_add_on_prorating_system_default: org.content_add_on_prorating_system_default,
                            voucher_subscribers_system_default: org.voucher_subscribers_system_default,
                            expired_voucher_removal_system_default: org.expired_voucher_removal_system_default,
                            voucher_slots_system_default: org.voucher_slots_system_default
                        };
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No organization found with ID: ${targetOrgId}`);
            }
        }

        this.fetchPlans();


        this.togglePlatform = function (platform) {
            if (!this.organizationdetail.select_platform) {
                this.organizationdetail.select_platform = [];
            }

            const index = this.organizationdetail.select_platform.indexOf(platform);
            if (index === -1) {
                this.organizationdetail.select_platform.push(platform);
            } else {
                this.organizationdetail.select_platform.splice(index, 1);
            }
        };

        //login token code
        document.addEventListener("DOMContentLoaded", function () {
            const targetOrgId = document.getElementById("org-id")?.value;

            // console.log("DOMContentLoaded event fired.");

            if (targetOrgId) {
                // console.log("Organization ID found: ", targetOrgId);
                window.fetchLoginToken(targetOrgId);
            } else {
                console.warn("Organization ID not found.");
            }
        });

        window.fetchLoginToken = function (orgId) {
            // if (!orgId) {
            //     console.error("Organization ID is missing.");
            //     return;
            // }

            // console.log("Fetching login token for Organization ID: ", orgId);

            const payload = { organization_id: orgId };

            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                payload,
                function (response) {
                    // console.log("Received response from fetchLoginToken:", response);

                    if (response && response.data && response.data.data.login_token) {
                        // console.log("Login token found in response:", response.data.data.login_token);
                        document.getElementById('loginToken').value = response.data.data.login_token;
                    } else {
                        // console.log("Login token not found. Generating a new token.");
                        window.generateToken();
                    }
                }
            );
        };

        window.generateToken = function () {
            // if (event) event.preventDefault();

            // console.log("Generating a new login token...");

            const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
            const token = Array.from({ length: 22 }, () => characters[Math.floor(Math.random() * characters.length)]).join('');

            // console.log("Generated token: ", token);
            document.getElementById('loginToken').value = token;
        };

        window.copyToken = function () {
            const tokenField = document.getElementById('loginToken');
            tokenField.select();
            document.execCommand("copy");

            console.log("Token copied to clipboard: ", tokenField.value);
        };

        // api token code
        document.addEventListener("DOMContentLoaded", function () {
            const targetOrgId = document.getElementById("org-id")?.value;
            // console.log("DOM loaded");

            if (targetOrgId) {
                window.fetchApiToken(targetOrgId);
            } else {
                console.warn("Organization ID not found. Generating token manually.");
                generateApiToken();
            }
        });

        window.fetchApiToken = function (orgId) {
            if (!orgId) {
                console.error("Organization ID is missing.");
                return;
            }

            const payload = { organization_id: orgId };

            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                payload,
                function (response) {
                    if (response && response.data && response.data.data.token) {
                        document.getElementById('apiToken').value = response.data.data.token;
                    } else {
                        generateApiToken();
                    }
                }
            )
        };

        window.generateApiToken = function () {
            const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
            const token = Array.from({ length: 50 }, () =>
                characters[Math.floor(Math.random() * characters.length)]
            ).join('');

            document.getElementById('apiToken').value = token;
        };

        window.copyApiToken = function () {
            const tokenField = document.getElementById('apiToken');
            tokenField.select();
            document.execCommand("copy");
        };

        // api access button checked code 
        window.toggleApiAccess = function () {
            const isChecked = document.getElementById("api_access").checked;

            document.getElementById("loginToken").disabled = !isChecked;
            document.getElementById("apiToken").disabled = !isChecked;
            document.getElementById("generateTokenBtn").disabled = !isChecked;

            if (isChecked) {
                const orgId = document.getElementById("org-id")?.value;

                if (orgId) {
                    fetchLoginToken(orgId);
                    fetchApiToken(orgId);
                } else {
                    window.generateToken();
                    window.generateApiToken();
                }
            } else {
                document.getElementById("loginToken").value = "";
                document.getElementById("apiToken").value = "";
            }
        };

        // ==================================================**************************************************==================================================
        // image upload code
        // ==================================================**************************************************==================================================

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
                        aspectRatio: 626 / 626,
                        preview: '.img-preview',
                        cropBoxResizable: false,
                        minCropBoxWidth: 626,
                        minCropBoxHeight: 626,
                        autoCrop: true,
                        dragCrop: false,
                        mouseWheelZoom: false,
                        resizable: false,
                        ready: function () {
                            //Should set crop box data first here
                            var config = { left: 0, top: 0, width: 626, height: 626 };
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
                            $('meta[name="base-api-url"]').attr('content') + '/organizations/logo/upload',
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
                                    self.organizationdetail.thumbnail = data.info;
                                    self.organizationdetail.organization_logo = data.info;
                                    self.organizationdetail.selected_thumb = data.info;
                                    self.organizationdetail.is_thumbnail_updated = 1;
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


        // ==========********===========
        // ==========********===========

        this.FetchSettingPaymentService = function () {
            var self = this;
            requestFactory.post(
                requestFactory.getUrl('general-settings/get-records'),
                this.defineProperties,
                function (response) {
                    if (response && Array.isArray(response)) {
                        self.paymentSettingList = response.filter(function (item) {
                            return item.category === 'payment_setting';
                        });

                        self.defaultSettings = {};
                        self.paymentSettingList.forEach(function (item) {
                            self.defaultSettings[item.key] = item.value;
                        });
                    }
                }
            );
        }
        this.FetchSettingPaymentService();

    }

];

window.gridControllers = {
    AddOrganizationDetailController: AddOrganizationDetailController
};
