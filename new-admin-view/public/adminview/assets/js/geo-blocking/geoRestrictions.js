var GeoRestrictionController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.geoRestrictionData = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('geo-blocking/geo-restrictions/info'),
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

        // --------------------------------------------------  Geo Restrciton Section START --------------------------------------------------//

        // to view add page
        this.addGeoRestrictions = function () {
            window.location.href = 'geo-restrictions/add';
        }

        // to view edit page
        this.editGeoRestrictions = function (id) {
            if (scope.checkAccess('geo_blocking') == false) {
                alert('Contact Support team to change the record!');
            } else {
                window.location.href = 'geo-restrictions/edit/' + id;
            };


        }


        //get timezone list
        fetch(`${appUrl}countries.json`)
            .then(response => response.json())
            .then(data => {
                this.countryList = data;
                // console.log("Countries ", this.countryList);
            })

        // call add Geo Restrictions api
        this.saveGeoRestrictions = function ($event) {
            requestFactory.post(requestFactory.getUrl('geo-blocking/geo-restrictions/create'),
                this.geoRestrictionData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/geo-blocking/geo-restrictions`;
                    }, 200);
                }, this.fillError
            );
        }


        // call update api
        this.updateGeoRestrictions = function ($event) {
            const rcrdId = document.getElementById('geo-restriction-id').value;
            requestFactory.post(requestFactory.getUrl('geo-blocking/geo-restrictions/edit/' + rcrdId),
                this.geoRestrictionData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/geo-blocking/geo-restrictions`;
                    }, 200);
                }, this.fillError
            );
        }

        // fetch api call
        this.fetchGeoRestrictionData = function () {
            requestFactory.post(
                requestFactory.getUrl('geo-blocking/geo-restrictions/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getGeoRestrictionData(response.data.data);
                    } else {
                        console.warn("Invalid data format from drm:", response);
                    }
                }
            );
        };

        function getGeoRestrictionData(data) {
            const homeElement = document.getElementById("home");
            if (!homeElement) {
                console.error("Element with ID 'home' not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetId = document.getElementById("geo-restriction-id")?.value;

            if (!targetId) {
                console.warn("Target organization ID not found.");
                return;
            }
            console.log(localScope);


            const geoRestriction = data.find(o => String(o.id) === String(targetId));
            if (geoRestriction) {
                if (localScope && localScope.geoRestrctnsCtrl) {
                    const updateModel = () => {
                        localScope.geoRestrctnsCtrl.geoRestrictionData = {
                            geo_restrictions: geoRestriction.geo_ip_status,
                            name: geoRestriction.name,
                            type: geoRestriction.type,
                            ip_restriction: geoRestriction.geo_protection_status,
                            mode: geoRestriction.mode,
                            countries: geoRestriction.countries,
                            overide: geoRestriction.override_geo_restrictions,
                        }
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel());
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Geo Restriction found with this ID: ${targetId}`);
            }
        }
        this.fetchGeoRestrictionData();

        // edit page is open or not
        scope.isEditMode = location.href.includes('edit');

        scope.cancelGeoRestrictions = function () {
            window.location.href = `${appUrl}admin/geo-blocking/geo-restrictions`;
        }


        // --------------------------------------------------  Geo Restrciton Section END --------------------------------------------------//

        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.program_name)) {
                scope.searchRecords.program_name = '';
            }
        })
    }];


window.gridControllers = { GeoRestrictionController: GeoRestrictionController };
