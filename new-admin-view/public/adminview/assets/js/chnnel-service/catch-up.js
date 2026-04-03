var CatchUpController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.catchUp = {};
        scope.errors = {};
        requestFactory.getToaster();
        scope.searchRecords = {};
        requestFactory.setThisArgument(this);

        this.defineProperties = function () {
            // this.catchUp = {};
            this.info = DataTransfer.info;
            requestFactory.getToaster();
        }

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('catch-up/info'), this.defineProperties,
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
            $event.preventDefault();
            scope.errors = {};
            const editId = id;

            console.log(this.catchUp);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('edit/catch-up/' + editId), this.catchUp,
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
                    requestFactory.getUrl('create/catch-up'), this.catchUp,
                    function (response) {
                        // scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            location.reload();
                        }, 350);
                    }, this.fillError
                );
            }
        }

        scope.changedata = function (record, id) {
            var toggleID = record.id;

            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active
            };
            // console.log(payload);

            requestFactory.post(
                requestFactory.getUrl('catch-up/toggle/edit/' + toggleID), payload,
                function (response) {
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                }
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

        // this.fetchDrm = function () {
        //     requestFactory.post(
        //         requestFactory.getUrl('drm/profile/records'), this.defineProperties,
        //         (response) => {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 const data = response.data.data
        //                 window.ddd = data
        //                 this.drmProfiles = data.filter((obj, index, self) =>
        //                     index === self.findIndex((t) => t.drm_provider === obj.drm_provider)
        //                 ).map(e => {
        //                     return {
        //                         ...e,
        //                         drmprofile: data.filter(a => a.drm_provider == e.drm_provider).map(b => b.drmprofile)
        //                     }
        //                 });
        //                 console.log('fetch data successfully.', this.drmProfiles);
        //             }
        //         }
        //     );
        // }
        // this.fetchDrm();

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



        this.fetchPlayBackToken = function () {
            requestFactory.post(
                requestFactory.getUrl('setting/play-back-token/records'),
                this.defineProperties,
                function (response) {
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
        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.addData = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.catchUp = {};
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $("#organizationForm").css('display', 'block');
            $("#organizationFormTranslationForm").css('display', "none");
        }

        this.editdata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.catchUp.id = records.id;
            this.catchUp.channel_id = records.channel_id;
            this.catchUp.description = records.description;
            this.catchUp.days = Number(records.days);
            this.catchUp.schedule_base = records.schedule_base;
            this.catchUp.streaming_provider = records.streaming_provider;
            this.catchUp.custom_streaming_url = (records.custom_streaming_url == 1);
            this.catchUp.url = records.url;
            this.catchUp.drm_type = records.drm_type;
            this.catchUp.drm_profile = records.drm_profile;
            this.catchUp.playback_token = records.playback_token;
            this.catchUp.token_generator = records.token_generator;
            this.catchUp.is_active = (records.is_active == 1);
            setTimeout(() => {
                $('.select2_custom_ddl').select2();
            }, 100);
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.catchUp = {};
            });
        }

        scope.$on('afterGetRecords', function (e, data) {
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

    }
];

window.gridControllers = {
    CatchUpController: CatchUpController
};