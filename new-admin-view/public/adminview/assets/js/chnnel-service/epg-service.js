var EpgServiceController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        // this.epg = {};
        scope.epg = {};
        scope.errors = {};
        requestFactory.getToaster();
        scope.searchRecords = {};
        requestFactory.setThisArgument(this);

        this.defineProperties = function () {
            this.info = DataTransfer.info;
            requestFactory.getToaster();
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('epg-service/info'), this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                },
            );
        }
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
        // ==============================***********************************==============================
        // ==============================***********************************==============================

        scope.epg.start_time = {
            hour: '00',
            minute: '00',
        };

        function pad(val) {
            return ('0' + val).slice(-2);
        }

        scope.increment = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.epg.start_time[unit] || 0, 10);
            val = (val + 1) > max ? 0 : val + 1;
            scope.epg.start_time[unit] = pad(val);
            scope.updateModel();
        };

        scope.decrement = function (unit) {
            let max = unit === 'hour' ? 23 : 59;
            let val = parseInt(scope.epg.start_time[unit] || 0, 10);
            val = (val - 1) < 0 ? max : val - 1;
            scope.epg.start_time[unit] = pad(val);
            scope.updateModel();
        };

        scope.updateModel = function () {
            scope.epg.expire_scheduled_time =
                `${scope.epg.start_time.hour}:${scope.epg.start_time.minute}`;
        };

        this.save = function ($event, id) {
            scope.errors = {};
            const liveid = id;
            // console.log('sending data to api:', scope.epg);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('edit/epg-service/' + liveid), scope.epg,
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('create/epg-service'), scope.epg,
                    (response) => {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 650);
                    }, this.fillError
                );
            }
        }

        scope.changedata = function (record) {
            const toggleId = record.id;

            record.is_active = record.is_active == 1 ? 0 : 1

            const payload = {
                id: record.id,
                is_active: record.is_active,
            }
            // console.log("hello", payload);

            requestFactory.post(
                requestFactory.getUrl('epg-service/toggle/edit/' + toggleId), payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                }, this.fillErrors
            );
        }

        this.rundata = function (record) {
            requestFactory.post(
                requestFactory.getUrl('epg-service/run/' + record.id), {},
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    scope.getRecords(true);
                    setTimeout(() => {
                        location.reload();
                    }, 650);
                }, this.fillError
            );
        }

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        this.addData = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            scope.epg = {
                start_time: {
                    hour: '00',
                    minute: '00'
                }
            };
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            // $("#organizationForm").css('display', 'block');
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            scope.epg.id = records.id;
            scope.epg.task_name = records.task_name;
            scope.epg.schedule_base = records.schedule_base;
            scope.epg.start_time = (typeof records.start_time === 'string') ? JSON.parse(records.start_time) : (records.start_time || { hour: '00', minute: '00' });
            scope.epg.time_zone = records.time_zone;
            scope.epg.shift_postfix = records.shift_postfix;
            scope.epg.source_url = records.source_url;
            scope.epg.is_active = (records.is_active == 1);
            // this.epg.token_generator = records.channel_id;
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                scope.epg = {};
            });
            // $("#organizationForm").css('display', 'block');
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        // this.fetchPlayBackToken = function () {
        //     requestFactory.post(
        //         requestFactory.getUrl('setting/play-back-token/records'),
        //         this.defineProperties,
        //         function (response) {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 const pbt = response.data.data;
        //                 const filterpbt = pbt.filter(playback => playback.is_active == 1);
        //                 this.playbackTokenList = filterpbt;
        //             }
        //         }
        //     );
        // }
        // this.fetchPlayBackToken();

        // this.fetchGeneratedToken = function () {
        //     requestFactory.post(
        //         requestFactory.getUrl('stream-services/streaming-url-policy/records'),
        //         this.defineProperties,
        //         function (response) {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 const data = response.data.data;
        //                 const filter = data.filter(policy => policy.status == 1);
        //                 this.PolicyList = filter;
        //             }
        //         }
        //     )
        // }
        // this.fetchGeneratedToken();

    }
];

window.gridControllers = {
    EpgServiceController: EpgServiceController
};