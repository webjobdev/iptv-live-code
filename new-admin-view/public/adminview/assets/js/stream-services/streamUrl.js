var StreamingUrlPolicyController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var info = {};
        this.streamingUrlData = {};
        this.subRuleCount = 0;

        this.ruleCount = 1;
        this.rules = [
            { id: 1, where: '', condition: '', operator: '', logical_operator: '', subRules: [] }
        ];
        this.viewData = {};

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

        // to view add page
        this.addStreamingUrlPolicy = function () {
            window.location.href = `${appUrl}admin/stream-services/streaming-url-policy/add`;
        }

        // to view edit page
        this.editStreamingUrlPolicy = function (id) {
            window.location.href = `${appUrl}admin/stream-services/streaming-url-policy/edit/` + id;
        }

        // view page
        this.viewStreamingUrlPolicy = function (id) {
            window.location.href = `${appUrl}admin/stream-services/streaming-url-policy/view/` + id;
        }

        // Add Rule Logic
        this.addRuleSec = function () {
            this.rules.push({ where: '', condition: '', operator: '', logical_operator: '', subRules: [] });
        }

        // Add Sub Rule Logic
        this.addSubRuleSec = function (rule) {
            rule.subRules.push({ criteria: '', sub_logical_operator: '' });
        }

        // Remove Sub Rule Logic
        this.removeSubRuleSec = function (index, rule) {
            if (Array.isArray(rule.subRules) && rule.subRules.length > 0) {
                rule.subRules.splice(index, 1);
            }
        };


        // store policy data
        this.saveStreamUrlPolicy = function ($event) {
            const payload = {
                policy_name: this.streamingUrlData.policy_name,
                rules: this.rules,
            }
            console.log('Payload : ', payload);
            requestFactory.post(requestFactory.getUrl('stream-services/streaming-url-policy/add'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/stream-services/streaming-url-policy`;
                    }, 200);
                }, this.fillError
            )
        }

        // update stream url policy record
        this.updateStreamingUrlPolicy = function ($event, id) {
            const recordId = document.getElementById('stream-url-id').value;

            const payload = {
                policy_name: this.streamingUrlData.policy_name,
                rules: this.rules,
            }

            requestFactory.post(requestFactory.getUrl('stream-services/streaming-url-policy/edit/' + recordId),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/stream-services/streaming-url-policy`;
                    }, 200);
                }, this.fillError
            )
        }

        // update status on index page
        this.toggleStatus = function (record) {
            record.status = record.status == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                status: record.status,
            };

            requestFactory.post(
                requestFactory.getUrl('stream-services/streaming-url-policy/status-update'),
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

        // View data
        this.viewStreamingUrlPolicy = function (id) {
            window.location.href = `${appUrl}admin/stream-services/streaming-url-policy/view/` + id;
            requestFactory.get(requestFactory.getUrl('stream-services/streaming-url-policy/view/' + id),
                function (response) {
                    console.log(response);
                    // this.viewData = response
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                }
            );
        }

        // check view page is open
        scope.isViewMode = window.location.href.includes('/view');


        // get data from stream_url_policy table
        this.fetchStreamingUrlPolicyData = function () {
            requestFactory.post(
                requestFactory.getUrl('stream-services/streaming-url-policy/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getStreamingUrlPolicyData(response.data.data);
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get record for edit page
        function getStreamingUrlPolicyData(data) {
            const editPgElmnt = document.getElementById('edit-form-div');

            if (!editPgElmnt) {
                console.error(`Element with ID ${editPgElmnt} not found.`);
                return;
            }

            const localScope = angular.element(editPgElmnt).scope();
            const targetRecordId = document.getElementById('stream-url-id')?.value;

            if (!targetRecordId) {
                console.warn("Target Record Id not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRecordId));
            if (record) {
                if (localScope && localScope.strmUrlCtrl) {
                    const updateModel = () => {
                        localScope.strmUrlCtrl.streamingUrlData = {
                            policy_name: record.policy_name
                        };

                        // const ruleData = JSON.parse(record.rules);
                        if (typeof record.rules === 'string') {
                            try {
                                ruleData = JSON.parse(record.rules);
                            } catch (e) {
                                console.error("Invalid JSON in record.rules:", e, record.rules);
                            }
                        } else {
                            ruleData = record.rules; // already an array/object
                        }

                        localScope.strmUrlCtrl.rules = ruleData.map(({ where, condition, operator, logical_operator, subRules }) => ({
                            // id: element.id,
                            where,
                            condition,
                            operator,
                            logical_operator,
                            subRules: (subRules || []).map(({ criteria, sub_logical_operator }) => ({
                                // id: sub.id,
                                criteria,
                                sub_logical_operator
                            }))
                        }));
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
                else {
                    console.warn(`No Stream Url Policy found with ID: ${targetRecordId}`);
                }
            }
        }
        this.fetchStreamingUrlPolicyData();

        scope.deleteUrlPolicy = function (id) {
            requestFactory.post(
                requestFactory.getUrl('stream-services/streaming-url-policy/delete-record/' + id),
                this.defineProperties,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/stream-services/streaming-url-policy`;
                    }, 200);
                }
            )
        }

        scope.cancelStreamUrlPolicy = function () {
            window.location.href = `${appUrl}admin/stream-services/streaming-url-policy`;
        }

        // search record name
        // self.searchRecord = function ($event) {
        //     var searchValue = document.getElementById('searchInput').value;
        //     const payload = {
        //         name: searchValue
        //     };

        //     if (searchValue) {
        //         requestFactory.post(
        //             requestFactory.getUrl('stream-services/streaming-url-policy/search-record'),
        //             payload,
        //             function (response) {
        //                 console.log('Rresponse : ', response);

        //                 if (response && response.data) {
        //                     console.log(response.data);

        //                     getStreamingUrlPolicyData(response.data);
        //                 } else {
        //                     console.warn("Invalid data format from stream url policy : ", response);
        //                 }
        //             }
        //         );
        //     }
        // }


        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.policy_name)) {
                scope.searchRecords.policy_name = '';
            }
        })
    }];


window.gridControllers = { StreamingUrlPolicyController: StreamingUrlPolicyController };
