var StreamSettingController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var info = {};
        this.streamingUrlData = {};
        this.subRuleCount = 1;
        this.subRules = [
            { id: 1, criteria: '' }
        ];

        this.ruleCount = 1;
        this.rules = [
            { id: 1, where: '', condition: '', operator: '', logical_operator: '' }
        ];

        scope.searchText = [];
        scope.searchData = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('stream-services/streaming-url-policy/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // to view add page
        this.addStreamSetting = function () {
            window.location.href = 'stream-settings/add';
        }

        // to view add page
        this.editStreamSetting = function (id) {
            window.location.href = 'stream-settings/edit/' + id;
        }

        scope.cancelStreamSetting = function () {
            window.location.href = `${appUrl}admin/stream-services/stream-settings`;
        }

        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.name)) {
                scope.searchRecords.name = '';
            }
        })
    }];


window.gridControllers = { StreamSettingController: StreamSettingController };
