'use strict';

var SubscriberOrganizationController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {

        var self = this;

        this.info = {};
        scope.errors = {};

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();
        // ==================================================***************************************************==========================================
        // ==================================================***************************************************==========================================
        this.orgWiseSubscriber = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const Subscriber = response.data.data;

                        const filterOrg = Subscriber.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        self.allOrgRecords = filterOrg; // Store for filtering
                        scope.SubscriberRecords = filterOrg;
                        scope.showRecords = filterOrg.length > 0;
                        scope.noRecords = filterOrg.length === 0;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseSubscriber();

        this.filterSubscribers = function () {
            const search = scope.searchRecords || {};
            let filtered = self.allOrgRecords || [];

            if (search.user_name) {
                const term = search.user_name.trim().toLowerCase();
                filtered = filtered.filter(r => r.user_name && String(r.user_name).toLowerCase().includes(term));
            }
            if (search.first_name) {
                const term = search.first_name.trim().toLowerCase();
                filtered = filtered.filter(r => {
                    const fullName = (r.first_name || '') + ' ' + (r.last_name || '');
                    return fullName.toLowerCase().includes(term);
                });
            }
            if (search.email) {
                const term = search.email.trim().toLowerCase();
                filtered = filtered.filter(r => r.email && String(r.email).toLowerCase().includes(term));
            }
            if (search.phone_number) {
                const term = search.phone_number.trim().toLowerCase();
                filtered = filtered.filter(r => r.phone_number && String(r.phone_number).toLowerCase().includes(term));
            }
            if (search.address) {
                const term = search.address.trim().toLowerCase();
                filtered = filtered.filter(r => r.address && String(r.address).toLowerCase().includes(term));
            }

            scope.SubscriberRecords = filtered;
            scope.showRecords = filtered.length > 0;
            scope.noRecords = filtered.length === 0;
        };

        this.editSubscriber = function (record, id) {
            if (!id) {
                console.warn("Subscriber ID is missing!");
                return;
            }

            const url = `${appUrl}admin/subscribers/detail/add?subscriber-id=${id}`;
            window.location.href = url;
            // console.log(url);
        }


    }
];

window.gridControllers = {
    SubscriberOrganizationController: SubscriberOrganizationController
};
