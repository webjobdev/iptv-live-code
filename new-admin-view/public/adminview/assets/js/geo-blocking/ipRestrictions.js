var IpRestrictionController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.ipRestrictionData = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('geo-blocking/ip-restrictions/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fillError = (response) => {
            console.log("Response : ", response);
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


        // --------------------------------------------------  IP Restrciton Section START --------------------------------------------------//

        // to view add page
        this.addIpRestrictions = function () {
            window.location.href = 'ip-restrictions/add';
        }

        // to view edit page
        this.editIpRestrictions = function (id) {
            if (scope.checkAccess('geo_blocking') == false) {
                alert('Contact Support team to change the record!');
            } else {
                window.location.href = 'ip-restrictions/edit/' + id;
            }
        }

        //  edit page is open or not
        scope.isEditMode = location.href.includes('edit');

        // call add IP Restrictions api
        this.saveIpRestrictions = function ($event, id) {
            requestFactory.post(requestFactory.getUrl('geo-blocking/ip-restrictions/create'),
                this.ipRestrictionData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/geo-blocking/ip-restrictions`;
                    }, 200);
                }, this.fillError
            );
        }

        // call update api
        this.updateIpRestrictions = function ($event) {
            const rcrdId = document.getElementById('ip-restriction-id').value;
            requestFactory.post(requestFactory.getUrl('geo-blocking/ip-restrictions/edit/' + rcrdId),
                this.ipRestrictionData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/geo-blocking/ip-restrictions`;
                    }, 200);
                }, this.fillError
            );
        }

        // fetch api call
        this.fetchIpRestrictionData = function () {
            requestFactory.post(
                requestFactory.getUrl('geo-blocking/ip-restrictions/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getIpRestrictionData(response.data.data);
                    } else {
                        console.warn("Invalid data format from drm:", response);
                    }
                }
            );
        };

        function getIpRestrictionData(data) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                console.error("Element with ID 'home' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetId = document.getElementById("ip-restriction-id")?.value;

            if (!targetId) {
                console.warn("Target organization ID not found.");
                return;
            }

            const ipRestriction = data.find(o => String(o.id) === String(targetId));
            if (ipRestriction) {
                if (localScope && localScope.ipRestrctnsCtrl) {
                    const updateModel = () => {
                        localScope.ipRestrctnsCtrl.ipRestrictionData = {
                            ip_restrictions: ipRestriction.geo_ip_status,
                            mode: ipRestriction.mode,
                            ip_address: ipRestriction.ip_address,
                        }
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel());
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No IP Restriction found with this ID: ${targetId}`);
            }
        }
        this.fetchIpRestrictionData();
        scope.isEditMode = location.href.includes('edit');

        scope.cancelIpRestrictions = function () {
            window.location.href = `${appUrl}admin/geo-blocking/ip-restrictions`;
        }

        // --------------------------------------------------  IP Restrciton Section END --------------------------------------------------//

        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.program_name)) {
                scope.searchRecords.program_name = '';
            }
        })
    }];


window.gridControllers = { IpRestrictionController: IpRestrictionController };
