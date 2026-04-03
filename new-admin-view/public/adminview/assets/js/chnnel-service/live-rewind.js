var LiveRewindController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.livewind = {};
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
                requestFactory.getUrl('live-rewind/info'), this.defineProperties,
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
        // create code 
        // ==============================***********************************==============================

        this.save = function ($event, id) {
            // $event = preventDefault();
            scope.errors = {};
            const liveid = id;

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('edit/live-rewind/' + liveid), this.livewind,
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('create/live-rewind'), this.livewind,
                    (response) => {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
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
                requestFactory.getUrl('live-rewind/toggle/edit/' + toggleId), payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                }, this.fillErrors
            );
        }

        // ==============================***********************************==============================
        // fetch details code
        // ==============================***********************************==============================

        this.fetchChannel = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.channelList = response.data.data;
                    }
                }
            );
        }
        this.fetchChannel();

        this.fetchDrm = function () {
            requestFactory.post(
                requestFactory.getUrl('drm/profile/records'),
                this.defineProperties,
                (response) => {
                    // console.log("📥 Raw API response:", response);

                    if (response && response.data && Array.isArray(response.data.data)) {
                        let profiles = response.data.data;

                        // 🔹 Keep all profiles
                        this.drmProfiles = profiles;

                        // 🔹 Extract unique providers for first dropdown
                        this.drmProviders = profiles
                            .map(p => p.drm_provider)
                            .filter((value, index, self) => self.indexOf(value) === index);

                        // console.log("✅ DRM providers:", this.drmProviders);
                        // console.log("✅ DRM profiles:", this.drmProfiles);
                    } else {
                        console.error("❌ DRM profiles not fetched or invalid format!", response);
                    }
                },
                (error) => {
                    console.error("❌ API request failed:", error);
                }
            );
        };

        this.fetchDrm();

        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.addData = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.livewind = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#organizationForm").css('display', 'block');
            $("#organizationFormTranslationForm").css('display', "none");
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.livewind.id = records.id;
            this.livewind.channel_id = records.channel_id;
            this.livewind.drm_type = records.drm_type;
            this.livewind.drm_profile = records.drm_profile;
            this.livewind.streaming_provider = records.streaming_provider;
            this.livewind.live_rewind_node = records.live_rewind_node;
            this.livewind.custome_streaming_url = (records.custome_streaming_url == 1);
            this.livewind.url = records.url;
            this.livewind.playback_token = parseInt(records.playback_token);
            this.livewind.token_generator = records.token_generator;
            this.livewind.is_active = (records.is_active == 1);
            // this.livewind.token_generator = records.channel_id;
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#organizationForm").css('display', 'block');
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.livewind = {};
            });
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        this.fetchPlayBackToken = function () {
            requestFactory.post(
                requestFactory.getUrl('setting/play-back-token/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const pbt = response.data.data;
                        const filterpbt = pbt.filter(playback => playback.is_active == 1);
                        this.playbackTokenList = filterpbt;
                    }
                }
            );
        }
        this.fetchPlayBackToken();

        this.fetchGeneratedToken = function () {
            requestFactory.post(
                requestFactory.getUrl('stream-services/streaming-url-policy/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const data = response.data.data;
                        const filter = data.filter(policy => policy.status == 1);
                        this.PolicyList = filter;
                    }
                }
            )
        }
        this.fetchGeneratedToken();

    }
];

window.gridControllers = {
    LiveRewindController: LiveRewindController
};