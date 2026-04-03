var SystemUserController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.systemUserData = {
            password: '',
            confirm_password: ''
        };
        scope.searchText = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);
        this.showPassword = false;


        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('system-user/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // this code for add drm name
        this.addSysUser = function ($event) {
            scope.isEditMode = false;
            $(".sidepanel").addClass("in");
            scope.errors = {};
            // this.systemUserData = {};
        }

        this.systemUserData.passwordMistMatch = false;

        scope.checkPasswordsMatch = function () {
            scope.passwordMistMatch = this.systemUserData.password !== this.systemUserData.confirm_password;
        };

        // call add system user
        this.saveSystemUser = function ($event) {
            requestFactory.post(requestFactory.getUrl('system-user/add'),
                this.systemUserData,
                function (response) {
                    // scope.getRecords(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/system-user`;
                    }, 200);
                }, this.fillError
            );
        }

        // call update system user
        this.updateSystemUser = function (record) {
            requestFactory.post(requestFactory.getUrl('system-user/edit/' + record.id),
                record,
                function (response) {
                    // scope.getRecords(true);
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/system-user`;
                    }, 200);
                }, this.fillError
            );
        }

        this.editSysUser = function (record) {
            scope.isEditMode = true;
            $(".sidepanel").addClass("in");
            scope.isEditMode = true;
            this.systemUserData = { id: record.id };
            this.systemUserData.first_name = record.first_name;
            this.systemUserData.last_name = record.last_name;
            this.systemUserData.password = record.password;
            this.systemUserData.permission_rule = record.rules?.id;
            this.systemUserData.email = record.email;
            this.systemUserData.phone_number = record.phone_number;
            this.systemUserData.company = record.company;
            this.systemUserData.location = record.location;
            this.systemUserData.max_failed_logins = record.max_failed_logins;
            this.systemUserData.status = record.status;
            this.systemUserData.is_super_admin = record.is_super_admin;
            this.systemUserData.change_password = record.can_change_password_for_next_login;
            $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
                this.systemUserData = {};
            });
        }

        // get organizations lists
        this.fetchPemrissionRule = function () {
            requestFactory.post(
                requestFactory.getUrl('permission-rules/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.rulesList = response.data.data;
                    } else {
                        console.warn("Invalid data format from Permission Rules:", response);
                    }
                }
            );
        };
        this.fetchPemrissionRule();

        //  update status
        this.toggleStatus = function (record) {
            record.status = record.status == 1 ? 0 : 1;
            const payload = {
                id: record.id,
                status: record.status
            };

            requestFactory.post(
                requestFactory.getUrl('api-access/status-update'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                },
                function (error) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('error', response.error);
                    record.status = record.status == 1 ? 0 : 1;
                },
                $timeout(function () {
                    window.location.reload();
                }, 650)
            );
        };


        // cancel api access
        this.cancelSysUser = function ($event) {
            $(".sidepanel").addClass("out");
        }

        scope.$on('afterGetRecords', function (e, data) {

            // if (angular.isUndefined(scope.searchRecords.program_name)) {
            //     scope.searchRecords.program_name = '';
            // }
        })

        //Download The User Log Info
        // this.downloadUserLog = function (userId) {

        //     const url = requestFactory.getUrl('/system-user/download-user-log/' + userId);

        //     console.log(url); // check ID
        //     // $window.open(url, '_blank');

        // };

        this.downloadUserLog = function (userId) {

            const baseUrl = window.location.origin; // OR hardcode if needed

            const url = `${appUrl}admin/system-user/download-user-log/${userId}`;

            window.open(url, '_blank');
        };

    }];


window.gridControllers = { SystemUserController: SystemUserController };
