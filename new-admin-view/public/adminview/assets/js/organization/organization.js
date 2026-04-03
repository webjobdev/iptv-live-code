'use strict';

var DashboardController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
    var self = this;
    this.organization_list = {};
    this.srh = {};
    requestFactory.setThisArgument(this);
    requestFactory.getToaster();

    this.defineProperties = function (data) {
        requestFactory.toggleLoader();
        this.info = data.info;
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('organizations/info'), this.defineProperties,
            function (response) {
                rootScope.redirectUnauthenticated(response);
            });
    };
    this.fetchInfo();

    this.addOrganization = function ($event) {
        $(".sidepanel").addClass("in");
        scope.errors = {};
        this.organization = {};
        $("#organizationForm").css('display', 'block');
        $("#organizationTranslationForm").css('display', "none");
    }

    this.fetchOrganization = function (organizations) {
        $(".sidepanel").addClass("in");
        scope.errors = {};

        this.organization_list = {};
        this.organization_list.id = organizations.id;
        this.organization_list.name = organizations.name;

        $("#organizationForm").css('display', 'block');
        $("#subscriptionTranslationForm").css('display', 'none');
    };

    this.save = function ($event, id) {
        requestFactory.post(requestFactory.getUrl('organizations/add'),
            this.organization,
            function (response) {   
                requestFactory.setToaster('success', response.message);
                requestFactory.getToaster();
                $(".sidepanel").removeClass("in");
                setTimeout(function () {
                    location.reload();
                }, 350);
            }, this.fillError);
    }

    scope.$on('afterGetRecords', function (e, data) {
        if (scope.searchRecords && (typeof scope.searchRecords === 'object') && angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
        setTimeout(function () {
            $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
        }, 500);
    });


    // this.fetchPlans = function () {
    //     requestFactory.post(requestFactory.getUrl('organizations/records'), this.defineProperties, function (response) {
    //         rootScope.redirectUnauthenticated(response);
    //         if (response && response.data && response.data.data) {
    //             const organizations = response.data.data;

    //             requestFactory.post(requestFactory.getUrl('organizations/general/settingrecords/records'), {}, function (settingsResponse) {
    //                 if (settingsResponse && settingsResponse.data && Array.isArray(settingsResponse.data.data)) {
    //                     const settings = settingsResponse.data.data;

    //                     const mergedData = organizations.map(org => {
    //                         const match = settings.find(s => s.organization_id === org.id);
    //                         return {
    //                             ...org,
    //                             organization_logo: match && match.organization_logo
    //                                 ? fixLogoUrl(match.organization_logo)
    //                                 : null
    //                         };
    //                     });
    //                     renderOrganization(mergedData);
    //                 } else {
    //                     console.warn("Invalid settings data format:", settingsResponse);
    //                 }
    //             });
    //         }
    //     });
    // };


    this.fetchPlans = function () {
        const fetchAllOrganizations = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/records'),
                this.defineProperties, function (response) {
                    rootScope.redirectUnauthenticated(response);
                    if (response && response.data && response.data.data) {
                        let organizations = response.data.data;

                        const userPermissionsString = localStorage.getItem('user_permissions');
                        let allowedOrgIds = [];
                        let isSuperAdmin = false;
                        if (userPermissionsString === 'all' || userPermissionsString === '"all"') {
                            isSuperAdmin = true;
                        } else if (userPermissionsString) {
                            try {
                                const parsedPerms = JSON.parse(userPermissionsString);
                                if (parsedPerms === 'all' || parsedPerms.all === true || parsedPerms.all === 'true' || parsedPerms.all == 1) {
                                    isSuperAdmin = true;
                                }
                                if (parsedPerms.organizations && Array.isArray(parsedPerms.organizations)) {
                                    allowedOrgIds = parsedPerms.organizations.map(org => org.id);
                                }
                            } catch (e) {
                                console.error("Error parsing user_permissions", e);
                            }
                        }

                        if (!isSuperAdmin && allowedOrgIds.length > 0) {
                            organizations = organizations.filter(org => allowedOrgIds.includes(org.id));
                        }

                        requestFactory.post(
                            requestFactory.getUrl('organizations/general/settingrecords/records'),
                            {}, function (settingsResponse) {
                                if (settingsResponse && settingsResponse.data && Array.isArray(settingsResponse.data.data)) {
                                    const settings = settingsResponse.data.data;
                                    const mergedData = organizations.map(org => {
                                        const match = settings.find(s => s.organization_id === org.id);
                                        return {
                                            ...org,
                                            organization_logo: match && match.organization_logo
                                                ? fixLogoUrl(match.organization_logo)
                                                : null
                                        };
                                    });
                                    // console.log("Merged Data : ", mergedData);

                                    renderOrganization(mergedData);
                                } else {
                                    console.warn("Invalid settings data format:", settingsResponse);
                                }
                            }
                        );

                        // requestFactory.post(
                        //     requestFactory.getUrl('shoppingcart/records'),
                        //     {},
                        //     function (cartResponse) {
                        //         if (cartResponse && cartResponse.data && Array.isArray(cartResponse.data.data)) {
                        //             const subscribers = cartResponse.data.data;
                        //             const now = new Date();

                        //             // Loop through each organization panel
                        //             document.querySelectorAll('.org-panel').forEach(orgPanel => {
                        //                 const orgId = orgPanel.getAttribute('data-org-id');
                        //                 const orgSubscribers = subscribers.filter(sub => sub.organization_id == orgId);

                        //                 const inactiveSubscribers = orgSubscribers.filter(sub => {
                        //                     return new Date(sub.end_at) < now;
                        //                 }).length;

                        //                 const activeSubscribers = orgSubscribers.length - inactiveSubscribers;

                        //                 const totalSubEl = orgPanel.querySelector('.total_sub');
                        //                 const inactiveSubEl = orgPanel.querySelector('.inactive_sub');

                        //                 if (totalSubEl) {
                        //                     totalSubEl.innerHTML = `<strong>Active Subscribers:</strong> ${activeSubscribers}`;
                        //                 }
                        //                 if (inactiveSubEl) {
                        //                     inactiveSubEl.innerHTML = `<strong>Inactive Subscribers:</strong> ${inactiveSubscribers}`;
                        //                 }
                        //             });
                        //         } else {
                        //             console.warn("Invalid subscriber data format:", cartResponse);
                        //         }
                        //     }
                        // );

                    }
                });
        };

        $('#searchInput').on('input', function () {
            const searchQuery = $(this).val();
            // console.log('Search Query:', searchQuery);

            if (!searchQuery) {
                fetchAllOrganizations.call(this);
            } else {
                requestFactory.post(requestFactory.getUrl('organizations/records'), this.defineProperties, function (response) {
                    // console.log('Organizations response:', response);

                    rootScope.redirectUnauthenticated(response);

                    if (response && response.data && response.data.data) {
                        let organizations = response.data.data;

                        const userPermissionsString = localStorage.getItem('user_permissions');
                        let allowedOrgIds = [];
                        let isSuperAdmin = false;
                        if (userPermissionsString === 'all' || userPermissionsString === '"all"') {
                            isSuperAdmin = true;
                        } else if (userPermissionsString) {
                            try {
                                const parsedPerms = JSON.parse(userPermissionsString);
                                if (parsedPerms === 'all' || parsedPerms.all === true || parsedPerms.all === 'true' || parsedPerms.all == 1) {
                                    isSuperAdmin = true;
                                }
                                if (parsedPerms.organizations && Array.isArray(parsedPerms.organizations)) {
                                    allowedOrgIds = parsedPerms.organizations.map(org => org.id);
                                }
                            } catch (e) {
                                console.error("Error parsing user_permissions", e);
                            }
                        }

                        if (!isSuperAdmin && allowedOrgIds.length > 0) {
                            organizations = organizations.filter(org => allowedOrgIds.includes(org.id));
                        }

                        // console.log('Fetched Organizations:', organizations);
                        const filteredOrganizations = organizations.filter(org =>
                            org.organization_name && org.organization_name.toLowerCase().includes(searchQuery.toLowerCase())
                        );
                        // console.log('Filtered Organizations:', filteredOrganizations);
                        requestFactory.post(requestFactory.getUrl('organizations/general/settingrecords/records'), {}, function (settingsResponse) {
                            // console.log('Settings response:', settingsResponse);

                            if (settingsResponse && settingsResponse.data && Array.isArray(settingsResponse.data.data)) {
                                const settings = settingsResponse.data.data;
                                // console.log('Fetched Settings:', settings);
                                const mergedData = filteredOrganizations.map(org => {
                                    const match = settings.find(s => s.organization_id === org.id);
                                    return {
                                        ...org,
                                        organization_logo: match && match.organization_logo
                                            ? fixLogoUrl(match.organization_logo)
                                            : null
                                    };
                                });
                                renderOrganization(mergedData);
                            } else {
                                console.warn("Invalid settings data format:", settingsResponse);
                            }
                        });
                    } else {
                        console.warn("Invalid organizations response format:", response);
                    }
                });
            }
        });
        fetchAllOrganizations.call(this);
    };

    function fixLogoUrl(path) {
        const baseUrl = window.VPlay.route.apiUrl + 'public/';
        if (path.startsWith('http')) {
            return path.replace('new-admin-view.test', 'new-admin-api.test');
        }
        return baseUrl + path;
    }

    let currentPage = 1;
    const organizationsPerPage = 10;
    function renderOrganization(organizations, page = 1) {
        const card = document.getElementById("accordion-864287");
        card.innerHTML = "";

        const totalOrganizations = organizations.length;
        const totalPages = Math.ceil(totalOrganizations / organizationsPerPage);

        // Calculate start and end index for slicing
        const startIndex = (page - 1) * organizationsPerPage;
        const endIndex = Math.min(startIndex + organizationsPerPage, totalOrganizations);

        const organizationsToShow = organizations.slice(startIndex, endIndex);
        const now = new Date();

        organizationsToShow.forEach((organization, index) => {
            let activeSubscriberCount = 0;
            let expiredSubscriberCount = 0;
            let activeDeviceCount = 0;
            let expiredDeviceCount = 0;
            let activeChannelCount = 0;
            let expiredChannelCount = 0;
            let activeVodCount = 0;
            let expiredVodCount = 0;

            organization.organization.forEach(o => {
                o.subscribers.forEach(subscriber => {
                    const details = subscriber.subscription_and_payments_details;
                    details.forEach(subsDetail => {
                        if (subsDetail && subsDetail.end_date) {
                            const endDate = new Date(subsDetail.end_date);
                            if (endDate > now) {
                                activeSubscriberCount += 1;
                            } else {
                                expiredSubscriberCount += 1;
                            }
                        }
                    });

                    subscriber.devices.forEach(device => {
                        if (parseInt(device.status) === 1) {
                            activeDeviceCount += 1;
                        } else if (parseInt(device.status) === 0) {
                            expiredDeviceCount += 1;
                        }
                    });
                });

                o.channels.forEach(chnls => {
                    chnls.channels.forEach(c => {
                        if (c.is_active == 1) {
                            activeChannelCount += 1;
                        }
                        else if (c.is_active == 0) {
                            expiredChannelCount += 1;
                        }
                    })
                });

                o.vods.forEach(vods => {
                    vods.vods.forEach(c => {
                        if (c.is_active == 1) {
                            activeVodCount += 1;
                        }
                        else if (c.is_active == 0) {
                            expiredVodCount += 1;
                        }
                    })
                })
            });

            const uniqueId = startIndex + index;

            const panel = document.createElement("div");
            panel.className = "panel panel-default";
            panel.style.marginBottom = "20px";
            panel.style.borderRadius = "5px";


            panel.innerHTML = `
            <div class="panel panel-default" id="cards-container" style="margin-bottom: 20px; border-radius: 5px;" >
                <div class="panel-heading" role="tab" id="heading-${uniqueId}">
                    <a role="button" data-toggle="collapse" data-parent="#accordion-864287" href="#collapse-${uniqueId}" aria-expanded="false" aria-controls="collapse-${uniqueId}" class="collapsed" 
                        style="display: flex; align-items: center; text-decoration: none; color: #333; font-size: 16px; font-weight: 700;">
                        ${organization.organization_logo
                    ? '<img src="' + organization.organization_logo + '" class="img-circle" style="width: 3rem; margin-right: 10px;">'
                    : '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 489.581 489.581" style="enable-background:new 0 0 489.581 489.581; width: 3rem; margin-right: 10px;" xml:space="preserve"><g><g><path d="M390.133,275.665V244.79c0-4.225-3.425-7.65-7.65-7.65H252.439v-23.224c55.496-3.936,99.446-50.34,99.446-106.821 C351.885,48.043,303.842,0,244.789,0c-59.053,0-107.095,48.043-107.095,107.096c0,56.481,43.95,102.885,99.446,106.821v23.224 H107.095c-4.225,0-7.649,3.425-7.649,7.65v30.875C43.95,279.602,0,326.005,0,382.485c0,59.053,48.043,107.096,107.095,107.096 c59.053,0,107.096-48.043,107.096-107.096c0-56.481-43.95-102.884-99.446-106.819V252.44h260.09v23.225 c-55.496,3.936-99.446,50.339-99.446,106.819c0,59.053,48.043,107.096,107.096,107.096c59.052,0,107.095-48.043,107.095-107.096 C489.579,326.004,445.629,279.601,390.133,275.665z M160.643,456.998c-15.082,10.869-33.579,17.282-53.548,17.282 s-38.466-6.413-53.548-17.282v-20.966c0-19.29,10.404-37.099,27.181-46.612c7.467,5.261,16.559,8.364,26.366,8.364 c9.808,0,18.9-3.103,26.367-8.364c16.777,9.512,27.182,27.323,27.182,46.612V456.998z M76.496,351.886 c0-16.872,13.727-30.599,30.599-30.599s30.599,13.727,30.599,30.599s-13.727,30.599-30.599,30.599S76.496,368.758,76.496,351.886z M198.891,382.485c0,23.226-8.675,44.461-22.949,60.646v-7.098c0-23.391-11.895-45.093-31.341-57.74 c5.278-7.475,8.392-16.581,8.392-26.407c0-25.308-20.59-45.899-45.898-45.899s-45.898,20.591-45.898,45.899 c0,9.825,3.114,18.932,8.392,26.407c-19.446,12.648-31.34,34.349-31.34,57.74v7.098c-14.275-16.186-22.95-37.421-22.95-60.646 c0-50.616,41.18-91.796,91.796-91.796S198.891,331.868,198.891,382.485z M175.942,167.743 c-14.274-16.186-22.95-37.421-22.95-60.647c0-50.616,41.18-91.796,91.797-91.796c50.617,0,91.797,41.179,91.797,91.796 c0,23.227-8.675,44.462-22.95,60.647v-7.099c0-23.39-11.895-45.092-31.341-57.74c5.278-7.474,8.392-16.581,8.392-26.407 c0-25.308-20.59-45.898-45.898-45.898s-45.898,20.59-45.898,45.898c0,9.826,3.114,18.932,8.392,26.407 c-19.446,12.649-31.341,34.35-31.341,57.74V167.743z M275.388,76.497c0,16.872-13.726,30.599-30.599,30.599 c-16.872,0-30.599-13.726-30.599-30.599c0-16.871,13.726-30.598,30.599-30.598C261.661,45.899,275.388,59.626,275.388,76.497z M191.241,181.61v-20.967c0-19.289,10.404-37.099,27.181-46.612c7.467,5.261,16.559,8.364,26.367,8.364 c9.808,0,18.9-3.103,26.367-8.364c16.777,9.512,27.181,27.324,27.181,46.612v20.967c-15.082,10.869-33.579,17.282-53.548,17.282 C224.821,198.892,206.323,192.479,191.241,181.61z M436.032,456.998c-15.082,10.869-33.579,17.282-53.548,17.282 c-19.969,0-38.466-6.413-53.549-17.282v-20.966c0-19.29,10.404-37.099,27.182-46.612c7.467,5.261,16.559,8.364,26.367,8.364 c9.808,0,18.9-3.103,26.367-8.364c16.777,9.512,27.181,27.323,27.181,46.612V456.998z M351.885,351.886 c0-16.872,13.726-30.599,30.599-30.599s30.599,13.727,30.599,30.599c-0.001,16.872-13.727,30.599-30.599,30.599 S351.885,368.758,351.885,351.886z M451.33,443.13v-7.098c0-23.391-11.895-45.093-31.341-57.74 c5.278-7.474,8.392-16.581,8.392-26.407c0-25.308-20.59-45.899-45.898-45.899c-25.308,0-45.898,20.591-45.898,45.899 c0,9.825,3.114,18.932,8.392,26.406c-19.446,12.648-31.341,34.349-31.341,57.741v7.098c-14.274-16.185-22.949-37.42-22.949-60.646 c0-50.616,41.18-91.796,91.797-91.796c50.616,0,91.796,41.179,91.796,91.796C474.28,405.71,465.605,426.945,451.33,443.13z"/></g></g></svg>'
                }
                        <p style="flex-grow: 1;"> ${organization.organization_name} </p>
                        <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;"></i>
                    </a>
                </div>

                <div id="collapse-${uniqueId}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="row" style="margin-bottom: 15px;">

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Search Subscribers">
                            </div>

                            <div class="col-md-6 text-right">
                                <!-- Button Toolbar -->
                                <div class="d-flex gap-4" role="toolbar">
                                    <!-- Search Button -->
                                    <form action="" method="GET" class="btn-group">
                                        <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Search">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </form>

                                    <!-- Add Button -->
                                    <form action="add-subscribers" method="GET" class="btn-group">
                                        <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Add">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </form>

                                    <!-- Subscribers Button -->
                                    <form action="view-subscribers" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Subscribers">
                                            <span class="glyphicon glyphicon-user"></span>
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <form action="general/details" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Edit">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </form>

                                    <!-- Content Button -->
                                    <form action="contentset" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Content">
                                            <span class="glyphicon glyphicon-film"></span>
                                        </button>
                                    </form>

                                    <!-- Monetization Button -->
                                    <form action="monetization-plan/subscription" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Monetization">
                                            <span class="glyphicon glyphicon-briefcase"></span>
                                        </button>
                                    </form>

                                    <!-- Announcements Button -->
                                    <form action="announcment" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Announcements">
                                            <span class="glyphicon glyphicon-bullhorn"></span>
                                        </button>
                                    </form>

                                    <!-- Customization Button -->
                                    <form action="app-customization/promotion/banner_carousels" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Customization">
                                            <span class="glyphicon glyphicon-phone"></span>
                                        </button>
                                    </form>

                                    <!-- Cart Button -->
                                    <form action="shoppingcart" method="GET" class="btn-group">
                                    <input type="hidden" name="id" value="${organization.id}">
                                        <button class="btn btn-default" title="Cart">
                                            <span class="glyphicon glyphicon-shopping-cart"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Grid buttons -->
                        <div class="row">
                            <div class="col-md-6 dashboard-container">
                                <div class="dashboard-grid text-center">
                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="add-subscribers" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-user"></span>
                                                <p>ADD SUBSCRIBER</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="view-subscribers" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-list"></span>
                                                <p>VIEW SUBSCRIBERS</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="contentset" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-film"></span>
                                                <p>CONTENT SETS</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="monetization-plan/subscription" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-briefcase"></span>
                                                <p>MONETIZATION PLANS</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="announcment" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-bullhorn"></span>
                                                <p>ANNOUNCEMENTS</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="app-customization/promotion/banner_carousels" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-phone"></span>
                                                <p>APP CUSTOMIZATION</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="organizations/payment-service" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-credit-card"></span>
                                                <p>PAYMENT SERVICES</p>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-xs-6 col-sm-3 dashboard-item">
                                        <form action="shoppingcart" method="GET">
                                            <input type="hidden" name="id" value="${organization.id}">
                                            <button class="dashboard-btn">
                                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                                <p>SHOPPING CART</p>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 stats-section">
                                <div class="row">
                                    <div class="col-md-6 stat-column">
                                    <div class="stat-item"><strong>Active Subscribers:</strong> ${activeSubscriberCount}</div>
                                    <div class="stat-item"><strong>Expired Subscribers:</strong> ${expiredSubscriberCount}</div>
                                    <div class="stat-item"><strong>Active Devices:</strong> ${activeDeviceCount}</div>
                                    <div class="stat-item"><strong>Inactive Devices:</strong> ${expiredDeviceCount}</div>
                                    </div>

                                    <div class="col-md-6 stat-column">
                                    <div class="stat-item"><strong>Channels:</strong> UP: ${activeChannelCount}, DOWN: ${expiredChannelCount}</div>
                                    <div class="stat-item"><strong>Video on Demand:</strong> UP: ${activeVodCount}, DOWN: ${expiredVodCount}</div>
                                    <div class="stat-item"><strong>Catch-up Channels:</strong> Recording: -</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            `;
            card.appendChild(panel);
        });
        renderPaginationControls(totalPages, page, organizations);
    }

    function renderPaginationControls(totalPages, currentPage, organizations) {
        const paginationContainer = document.getElementById("pagination-controls");
        paginationContainer.innerHTML = "";

        const pagination = document.createElement("ul");
        pagination.className = "pagination";

        // Previous button
        const prevItem = document.createElement("li");
        prevItem.className = currentPage === 1 ? "disabled" : "";
        prevItem.innerHTML = `<a href="#">«</a>`;
        prevItem.onclick = () => {
            if (currentPage > 1) {
                renderOrganization(organizations, currentPage - 1);
            }
        };
        pagination.appendChild(prevItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement("li");
            pageItem.className = i === currentPage ? "active" : "";
            pageItem.innerHTML = `<a href="#">${i}</a>`;
            pageItem.onclick = () => renderOrganization(organizations, i);
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextItem = document.createElement("li");
        nextItem.className = currentPage === totalPages ? "disabled" : "";
        nextItem.innerHTML = `<a href="#">»</a>`;
        nextItem.onclick = () => {
            if (currentPage < totalPages) {
                renderOrganization(organizations, currentPage + 1);
            }
        };
        pagination.appendChild(nextItem);

        paginationContainer.appendChild(pagination);
    }

    this.fetchPlans();


    this.fetchCounts = function () {
        requestFactory.get(
            requestFactory.getUrl(''),
            {},
            function (response) {
                if (response && response.data && response.data.data) {
                    scope.orgDataCounts = response.data.data;
                    console.log("Count Data : ", scope.orgDataCounts);
                } else {
                    console.warn('Error occurred in fetching data : ', response.errors);
                }
            }
        )
    }


}];


window.gridControllers = { DashboardController: DashboardController };

