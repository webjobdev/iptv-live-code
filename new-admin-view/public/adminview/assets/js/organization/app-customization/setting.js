var SettingController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.sett = {};
        scope.errors = {};
        // requestFactory.getToaster();
        scope.searchRecords = {};
        // requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('app-customization/setting/info'), this.defineProperties,
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
            // const settId = id;
            // console.log("send data:", this.sett);

            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const orgid = urlParams.get('id');

            this.sett.organization_id = orgid;

            requestFactory.post(
                requestFactory.getUrl('app-customization/setting/create'), this.sett,
                (response) => {
                    requestFactory.setToaster('success', 'Setting created successfully');
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = requestFactory.getTemplateUrl(`admin/app-customization/setting?id=${orgid}`);
                    }, 350);
                }, this.fillErrors
            );
        }

        this.update = function ($event, id) {

            const url = new URL(window.location.href);
            const orgid = url.searchParams.get('org_id');

            const settId = id;

            requestFactory.post(
                requestFactory.getUrl('organization/app-customization/setting/edit/' + settId), this.sett,
                function (response) {
                    requestFactory.setToaster('success', 'Setting updated successfully');
                    requestFactory.getToaster();
                    setTimeout(() => {
                        window.location.href = requestFactory.getTemplateUrl(`admin/app-customization/setting?id=${orgid}`);
                    }, 350);
                }, this.fillErrors
            );
        }

        // ==============================***********************************==============================
        // fetch details code
        // ==============================***********************************==============================

        // fetch form data
        this.fetchsett = function () {

            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('app-customization/setting/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && response.data.data) {
                        const Assignsett = response.data.data;

                        const filterOrg = Assignsett.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.AssignSettRecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;

                        renderData(response.data.data);
                    }
                }
            );
        }

        // this.Assignsett = function (acs) {
        //     const currentUrl = window.location.href;
        //     const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
        //     const orgid = urlParams.get('id');

        //     if (!orgid) {
        //         console.warn("Organization ID not found in URL.");
        //         rootScope.records = [];
        //         return;
        //     }

        //     const filterData = acs.filter(org => Number(org.organization_id) === Number(orgid));

        //     rootScope.records = filterData;
        //     rootScope.orgid = orgid;
        // }

        function renderData(setting) {
            const homeElement = document.getElementById('OrgSettingForm');
            if (!homeElement) {
                console.warn("⚠️ 'OrgSettingForm' element not found.");
                return;
            }
            const scope = angular.element(homeElement).scope();
            if (!scope) {
                console.warn("⚠️ Angular scope not found on 'OrgSettingForm' element.");
                return;
            }

            const targetOrgId = document.getElementById("chnl_id")?.value;

            const acsett = setting.find(c => String(c.id) === String(targetOrgId));

            if (acsett) {
                // acsett.time_zone = String(acsett.time_zone);
                acsett.pin_code = acsett.pin_code ? Number(acsett.pin_code) : null;
                acsett.screen_server = acsett.screen_server ? Number(acsett.screen_server) : null;
                acsett.minutes = Number(acsett.minutes) === 1;
                acsett.channel_id = acsett.channel_id ? Number(acsett.channel_id) : null;
                acsett.system_default = Number(acsett.system_default) === 1;
                acsett.random = Number(acsett.random) === 1;
                acsett.stb_start_channel = Number(acsett.stb_start_channel) === 1;
                acsett.ss_system_default = Number(acsett.ss_system_default) === 1;

                setTimeout(() => {
                    $('.select2_custom_ddl').select2();
                }, 100);

                scope.settCtrl.sett = acsett;
                scope.$applyAsync();
            }
        }

        this.fetchsett();

        // fetch channel data
        this.fetchChannel = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.channelList = response.data.data;
                    }
                }
            );
        }
        this.fetchChannel();

        this.fetchTimeZone = function () {
            const timezones = Intl.supportedValuesOf('timeZone');
            this.timezone = timezones;
        }
        this.fetchTimeZone();

        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.openPage = function () {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const newUrl = `${appUrl}admin/app-customization/setting/add` + '?id=' + id;
            window.location.href = newUrl;
        }

        this.edit = function (record, id, orgid) {
            const newUrl = `${appUrl}admin/app-customization/setting/edit/` + id + '?org_id=' + orgid;
            window.location.href = newUrl;
        }

        this.sett = {
            pin_code: '',
            random: false
        };

        this.AutoRandom = function () {
            if (this.sett.random) {
                this.sett.pin_code = Math.floor(1000 + Math.random() * 9000);
            } else {
                this.sett.pin_code = '';
            }
        };


    }];

window.gridControllers = {
    SettingController: SettingController
};