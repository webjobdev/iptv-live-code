var PartnerProductController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.payment = {}
        scope.errors = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/assigned-device/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ==============================***********************************==============================
        //                                            scope code
        // ==============================***********************************==============================

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        scope.ppCtrl.getStatus = function (record) {
            if (!record.subscription_and_payments_detaile) return 'unknown';
            var start = new Date(record.subscription_and_payments_detaile.start_date);
            var end = new Date(record.subscription_and_payments_detaile.end_date);
            var now = new Date();

            if (start > now) return 'upcoming';
            if (start <= now && end >= now) return 'active';
            return 'expired';
        };

    }
];

window.gridControllers = {
    PartnerProductController: PartnerProductController
};