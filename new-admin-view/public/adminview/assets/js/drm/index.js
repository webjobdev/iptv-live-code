var DrmController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // this.info = {};
        this.accdrm = {};
        this.prodrm = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('drm/info'),
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

        // ===========================================*******************************************======================================

        // this code for add drm name
        this.addDrm = function ($event) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.drm = {};
            $("#organizationForm").css('display', 'block');
            $("#organizationTranslationForm").css('display', "none");
        }

        this.save = function ($event, id) {
            requestFactory.post(requestFactory.getUrl('drm/add'),
                this.drm, function (response) {
                    scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    $(".sidepanel").removeClass("in");
                    setTimeout(function () {
                        location.reload();
                    }, 650);
                }, this.fillError
            );
        }

        // this.closeDrmEdit = function () {
        //     scope.gridSideFormClose();
        // };

        // publish button code
        scope.isActive = function (publish_now) {
            return publish_now == 1;
        };

        // active button code (in tbl)
        scope.is_Active = function (is_active) {
            return is_active == 1;
        };

        // publish toggle button code (in tbl)
        scope.togglePublishNow = function (record) {
            record.publish_now = record.publish_now == 1 ? 0 : 1;

            const payload = {
                drm_id: record.id,
                publish_now: record.publish_now
            };

            requestFactory.post(
                requestFactory.getUrl('drm/detail/add'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    setTimeout(function () {
                        location.reload();
                    }, 300);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', 'Failed to update publish status');
                    // Revert the value on failure
                    record.publish_now = record.publish_now == 1 ? 0 : 1;
                }
            );
        };

        // toggale code for table (in tbl)
        scope.toggle = function (recod) {
            recod.expanded = !recod.expanded;
        };

        // this.editdrm = function (records) {
        //     $(".sidepanel").addClass("in");
        //     scope.errors = {};

        //     // Initialize drm object if not already
        //     this.drm = this.drm || {};

        //     this.drm.id = records.id;
        //     this.drm.drm_name = records.drm_name;
        //     this.drm.publish_now = records.publish_now;

        //     $("#subscriptionForm").show();
        //     $("#subscriptionTranslationForm").hide();
        // };

        this.editdrm = function (id) {
            window.location.href = 'drm/detail/add/' + id;
        }

        // scope.deleteConfirmed = function () {
        //     console.log("clicnk on delete button");
        // }

        scope.selectedDeleteId = null;

        scope.setDeleteId = function (id) {
            scope.selectedDeleteId = id;
            console.log("Selected ID to delete:", id);
        };


        scope.deleteConfirmed = function () {
            if (scope.selectedDeleteId) {
                console.log("Deleting ID:", scope.selectedDeleteId);

                requestFactory.delete(
                    requestFactory.getUrl('drm/delete') + '/' + scope.selectedDeleteId,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        $(".sidepanel").removeClass("in");
                    },
                    this.fillError
                );
            } else {
                console.warn("No ID selected for deletion.");
            }
        };



        // =======================================*******************************************======================================

        // this code for add drm account (add/update/delete)
        this.fetchDrm = function () {
            requestFactory.post(
                requestFactory.getUrl('drm/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        DrmdefineProperties(response.data.data);
                        // console.log("Valid drm data:", response);
                    } else {
                        console.warn("Invalid data format from drm:", response);
                    }
                }
            );
        };

        function DrmdefineProperties(drm) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                // console.error("Element with ID 'home' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("drm-id")?.value;

            if (!targetOrgId) {
                // console.warn("Target organization ID not found.");
                return;
            }

            const org = drm.find(o => String(o.drm_id) === String(targetOrgId));
            if (org) {
                // console.log("Fetched organization:", org);
                if (localScope && localScope.drmCtrl) {
                    const updateModel = () => {
                        localScope.drmCtrl.accdrm = {
                            drm_name: org.drm_name,
                            drm_provider: org.drm_provider,
                            account_id: org.account_id,
                            access_key: org.access_key,
                            site_key: org.site_key,
                            px_value: org.px_value,
                            publish_now: org.publish_now == 1,
                        };
                        // console.log(localScope.drmCtrl.accdrm);
                        localScope.drmCtrl.prodrm = {
                            drm_type: org.drmprofile.drm_type,
                            license_persistent: org.drmprofile.license_persistent,
                            license_limitation: org.drmprofile.license_limitation,
                            license_duration: org.drmprofile.license_duration,
                            hdcp_type: org.drmprofile.hdcp_type,
                            robustness: org.drmprofile.robustness,
                            fps_certificate: org.drmprofile.fps_certificate,
                            output_protection_level: org.drmprofile.output_protection_level,
                            integration_type: org.drmprofile.integration_type,
                            playready_security_level: org.drmprofile.playready_security_level,
                            hardware_drm_required: org.drmprofile.hardware_drm_required,
                            rooted_devices_allowed: org.drmprofile.rooted_devices_allowed,
                            is_active: org.drmprofile.is_active == 1,
                            authorization_url: org.drmprofile.authorization_url,
                        };
                        // console.log(localScope.drmCtrl.prodrm);
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
        this.fetchDrm();

        // save data code
        this.saveaccountdetail = function ($event) {
            const drmIdInput = document.getElementById('drm-id');
            const accessKeyInput = document.getElementById('accessKeyInput');
            const siteKeyInput = document.getElementById('siteKeyInput');
            const px_value = document.getElementById('px_value');
            const account_id = document.getElementById('account_id');

            if (!drmIdInput) {
                console.error("Required input element 'drm-id' not found.");
                return;
            }

            // Update accdrm keys from input
            this.accdrm = this.accdrm || {};
            this.accdrm.access_key = accessKeyInput?.value || '';
            this.accdrm.site_key = siteKeyInput?.value || '';
            this.accdrm.px_value = px_value?.value || '';
            this.accdrm.account_id = account_id?.value || '';

            const payload = {
                drm_id: drmIdInput.value,
                drm_name: this.accdrm.drm_name || '',
                drm_provider: this.accdrm.drm_provider || '',
                px_value: this.accdrm.px_value || '',
                account_id: this.accdrm.account_id || '',
                publish_now: this.accdrm.publish_now ? 1 : 0,
                access_key: this.accdrm.access_key,
                site_key: this.accdrm.site_key,
            };

            const url = requestFactory.getUrl('drm/detail/add');

            requestFactory.post(
                url,
                payload,
                response => {
                    if (typeof scope.getRecords === 'function') {
                        scope.getRecords(true);
                    }
                    requestFactory.getToaster?.();
                    requestFactory.setToaster?.('success', response.message);

                    setTimeout(() => {
                        location.reload();
                    }, 300);
                },
                this.fillError || (error => {
                    console.error('Error submitting DRM details:', error);
                })
            );
        };


        // ========================================*******************************************======================================

        // this code for add drm profile

        this.saveprofiledetail = function ($event) {
            $event.preventDefault();

            const drmIdInput = document.getElementById('drm-id');
            const fpsInput = document.getElementById('fps_certificate');

            if (!drmIdInput) {
                console.error("Required input element 'drm-id' not found.");
                return;
            }

            const formData = new FormData();

            if (fpsInput && fpsInput.files.length > 0) {
                const fpsFile = fpsInput.files[0];
                formData.append('fps_certificate', fpsFile);
            }

            formData.append('drm_id', drmIdInput.value);
            formData.append('drm_name', this.accdrm?.drm_name || '');
            formData.append('drm_provider', this.accdrm?.drm_provider || '');
            formData.append('drm_type', this.prodrm?.drm_type || '');
            formData.append('license_persistent', this.prodrm?.license_persistent || '');
            formData.append('license_limitation', this.prodrm?.license_limitation || '');
            formData.append('license_duration', this.prodrm?.license_duration || '');
            formData.append('hdcp_type', this.prodrm?.hdcp_type || '');
            formData.append('robustness', this.prodrm?.robustness || '');
            formData.append('output_protection_level', this.prodrm?.output_protection_level || '');
            formData.append('integration_type', this.prodrm?.integration_type || '');
            formData.append('playready_security_level', this.prodrm?.playready_security_level || '');
            formData.append('hardware_drm_required', this.prodrm?.hardware_drm_required || '');
            formData.append('rooted_devices_allowed', this.prodrm?.rooted_devices_allowed || '');
            formData.append('is_active', this.prodrm?.is_active ? 1 : 0);

            fetch(`${apiUrl}/drm/profile/detail/add`, {
                method: "POST",
                body: formData,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                }
            })
                .then(res => res.json())
                .then(response => {
                    scope.getRecords?.(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    console.warn("API Response:", response.message);
                    window.location.href = requestFactory.getTemplateUrl(
                        'admin/drm'
                    );
                    // setTimeout(function () {
                    //     location.reload();
                    // }, 300);
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                });
        };

        // profile active button code (in tbl)
        scope.toggleProfilePublishNow = function (record) {
            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                drm_id: record.drm_details_id,
                is_active: record.is_active
            };

            console.log("Sending payload:", payload);

            requestFactory.post(
                requestFactory.getUrl('drm/profile/detail/add'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    setTimeout(function () {
                        location.reload();
                    }, 300);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', 'Failed to update publish status');
                    record.is_active = record.is_active == 1 ? 0 : 1;
                }
            );
        };

        // =========================================*******************************************======================================


        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.publish_now)) {
                scope.searchRecords.publish_now = 'all';
            }
        })
    }];


window.gridControllers = { DrmController: DrmController };
