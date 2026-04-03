var DeviceController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // var info = {};
        this.deviceData = {};
        this.multipleDeviceData = {};

        this.btnNo = 0;
        scope.deviceData = {
            serial: [],
            mac: [],
        };
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('divice/info'),
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

        /**----------------------------------------------------- Add Device START -----------------------------------------------------  */
        // to view add page
        this.addDevices = function () {
            window.location.href = 'divice/add';
        }

        //get timezone list
        fetch(`${appUrl}timezone.json`)
            .then(response => response.json())
            .then(data => {
                this.tzList = data;
            })

        // get organizations lists
        this.fetchOrg = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/general/settingrecords/records?rowsPerPage=500'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.orgList = response.data.data;
                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };
        this.fetchOrg();

        // auto generate identifier
        window.autoGenerateIdentifier = function () {
            const input = document.getElementById('identifier_inpt');
            const autoInput = document.getElementById('ident_auto');

            if (autoInput.checked == true) {
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.deviceCtrl) {
                        scope.$apply(() => {
                            const uidentifr = generateUUID();
                            scope.deviceCtrl.deviceData.identifier = uidentifr;
                            input.value = uidentifr;
                        });
                    }
                }
            } else {
                input.value = '';
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.deviceCtrl) {
                        scope.$apply(() => {
                            scope.deviceCtrl.deviceData.identifier = '';
                        });
                    }
                }
            }
        };

        // generate random UUID
        function generateUUID() {
            var d = new Date().getTime();
            var d2 = (performance && performance.now && (performance.now() * 1000)) || 0;

            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16;
                if (d > 0) {
                    r = (d + r) % 16 | 0;
                    d = Math.floor(d / 16);
                } else {
                    r = (d2 + r) % 16 | 0;
                    d2 = Math.floor(d2 / 16);
                }

                return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
            });
        }

        // get timezone from ip_address
        window.getTimezoneOnIp = function () {
            const tz = document.getElementById('timezone_select');
            const boiCheck = document.getElementById('based_on_ip_check');
            const homeElement = document.getElementById('home');
            const scope = homeElement ? angular.element(homeElement).scope() : null;

            if (boiCheck.checked) {
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => {
                        const userTimezone = data.timezone;
                        tz.value = userTimezone;

                        if (scope && scope.deviceCtrl) {
                            scope.$apply(() => {
                                scope.deviceCtrl.deviceData.timezone = userTimezone;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user timezone:', error);
                    });
            } else {
                tz.value = '';
                if (scope && scope.deviceCtrl) {
                    scope.$apply(() => {
                        scope.deviceCtrl.deviceData.timezone = '';
                    });
                }
            }
        };


        // generate security code
        window.generateSecurityCode = function () {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < 6; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }

            const codeInput = document.getElementById('security_code_input');
            const codeCheckbox = document.getElementById('security_auto_check');
            if (codeCheckbox && codeCheckbox.checked) {
                codeInput.value = result;
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.deviceCtrl) {
                        scope.$apply(() => {
                            scope.deviceCtrl.deviceData.security_code = result;
                        });
                    }
                }
            }
            else {
                codeInput.value = '';
                const homeElement = document.getElementById('home');
                if (homeElement) {
                    const scope = angular.element(homeElement).scope();
                    if (scope && scope.deviceCtrl) {
                        scope.$apply(() => {
                            scope.deviceCtrl.deviceData.security_code = '';
                        });
                    }
                }
            }

        };

        // upload multiple device list file and fill data in inputs
        // scope.addDeviceListData = function (event) {
        //     const listFile = event.target.files[0];

        //     if (listFile) {
        //         const fileName = listFile.name;
        //         const input = document.getElementById('list_file_inpt');
        //         const deviceDataList = document.querySelectorAll('#device_data');
        //         // console.log(deviceDataList);

        //         input.value = fileName;

        //         let reader = new FileReader();

        //         reader.onload = function (e) {
        //             const text = e.target.result.trim();
        //             const lines = text.split(/\r?\n/).slice(1);

        //             scope.$apply(() => {
        //                 scope.deviceCtrl.deviceData.serial = [];
        //                 scope.deviceCtrl.deviceData.mac = [];
        //                 lines.forEach(line => {
        //                     const [serial, mac] = line.split(',');
        //                     scope.deviceCtrl.deviceData.serial.push(serial);
        //                     scope.deviceCtrl.deviceData.mac.push(mac);
        //                 });
        //                 deviceDataList.forEach(e => { e.style.display = '' });
        //             });
        //         }
        //         reader.readAsText(listFile);
        //     }
        // }

        // click on parse button and fill data from the file to inputs
        this.parseFile = function (event) {
            let selectedFile = document.getElementById('browse_list');
            let listFile = selectedFile.files[0];
            // console.log(listFile.type == 'text/csv');
            // const listFile = event.target.files[0];

            if (listFile && listFile.type == 'text/csv') {
                const fileName = listFile.name;
                // const input = document.getElementById('list_file_inpt');
                const deviceDataList = document.querySelectorAll('#device_data');
                // console.log(deviceDataList);

                // input.value = fileName;

                let reader = new FileReader();

                reader.onload = function (e) {
                    const text = e.target.result.trim();
                    const lines = text.split(/\r?\n/).slice(1);

                    scope.$apply(() => {
                        scope.deviceCtrl.deviceData.serial = [];
                        scope.deviceCtrl.deviceData.mac = [];
                        lines.forEach(line => {
                            const [serial, mac] = line.split(',');
                            scope.deviceCtrl.deviceData.serial.push(serial);
                            scope.deviceCtrl.deviceData.mac.push(mac);
                        });
                        deviceDataList.forEach(e => { e.style.display = '' });
                    });
                }
                reader.readAsText(listFile);
            } else {
                alert("Please make sure you have uploaded the file with .csv extension.")
            }
        }


        // get subscribers list
        this.fetchSubscribers = function () {
            requestFactory.post(
                requestFactory.getUrl('subscribers/records'),
                this.defineProperties,
                (response) => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.subsList = response.data.data;
                        // console.log('Subscribers List : ', this.subsList);

                    } else {
                        console.warn("Invalid data format from Org:", response);
                    }
                }
            );
        };
        this.fetchSubscribers();

        // add partner program
        this.saveDevice = function ($event) {
            requestFactory.post(
                requestFactory.getUrl('divice/add'),
                this.deviceData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}/admin/divice`;
                    }, 200);
                },
                this.fillError
            )
        }



        // update device data
        this.updateDevice = function ($event) {
            const recordId = document.getElementById('device-id')?.value;
            requestFactory.post(
                requestFactory.getUrl('divice/edit/' + recordId),
                this.deviceData, function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/divice`;
                    }, 200);
                },
                this.fillError
            )
        }

        /**----------------------------------------------------- Add Device END -----------------------------------------------------  */

        /**----------------------------------------------------- Add Multiple Devices START ----------------------------------------------------- */

        // add partner program
        this.saveMultipleDevices = function ($event) {
            $event.preventDefault();

            disableButtonWithLoader('deviceadd');

            // const orgIds = [];
            // this.multipleDeviceData.organization.forEach(element => {
            //     orgIds.push(element.organization_id);
            // });
            // const formData = new FormData();

            // formData.append('mac_add', JSON.stringify(this.deviceData.mac) || []);
            // formData.append('serial_no', JSON.stringify(this.deviceData.serial) || []);
            // formData.append('device_redirect', this.deviceData.device_redirect || '');
            // formData.append('identifier', JSON.stringify(this.deviceData.identifier) || '');
            // formData.append('timezone', this.deviceData.timezon || '');
            // formData.append('organization', JSON.stringify(this.deviceData.organization || []));
            // formData.append('security_code_required', this.deviceData.security_code_req || []);
            // formData.append('security_code', this.deviceData.security_code || []);
            // formData.append('subscribers', JSON.stringify(this.deviceData.subscribers) || '');
            // // formData.append('organization_id', orgIds || []);
            // formData.append('device_model', JSON.stringify(this.deviceData.device_model) || '');
            // formData.append('firmware_version', JSON.stringify(this.deviceData.firmware_version) || '');
            // formData.append('ip_address', JSON.stringify(this.deviceData.ip_address) || '');
            // formData.append('isp', this.deviceData.isp || '');
            // formData.append('location', this.deviceData.location || '');
            // formData.append('status', this.deviceData.status || '');

            // formData.append('first_value', this.deviceData.firstValue || '');
            // formData.append('create_subscribers', this.deviceData.create_subscribers || '');
            // formData.append('seperator', this.deviceData.seperator || '');

            const payload = {
                deviceData: this.deviceData,
            };

            const listFileInput = document.getElementById('browse_list');

            if (listFileInput) {
                const listFile = listFileInput.files[0];
                if (listFile) {
                    payload.list = listFile ?? "";
                    // formData.append('list', listFile || '');
                }
                else {
                    payload.list = this.multipleDeviceData.list ?? "";
                    // formData.append('list', this.multipleDeviceData.list || '');
                }
            }

            const parseFileInput = document.getElementById('parse-file-inpt');
            if (parseFileInput) {
                const parseFile = parseFileInput.files[0];
                if (parseFile) {
                    payload.parse_file = parseFile ?? "";
                    // formData.append('parse_file', parseFile || '');
                }
            }

            requestFactory.post(
                requestFactory.getUrl(`divice/add`),
                payload,
                function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster("success", response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/divice`
                    }, 100);
                }, this.fillError
            )

            // fetch(`${apiUrl}/divice/add`, {
            //     method: "POST",
            //     body: formData,
            //     headers: {
            //         "Authorization": "Bearer " + localStorage.getItem("access_token")
            //     }
            // })
            //     .then(res => res.json())
            //     .then(response => {
            //         scope.getRecords?.(true);
            //         requestFactory.setToaster('success', response.message);
            //         requestFactory.getToaster();
            //         setTimeout(function () {
            //             window.location.href = `${appUrl}admin/divice`;
            //         }, 200);
            //     })
            //     .catch(err => {
            //         console.error("Fetch Error : ", err);
            //     })
            //     .finally(() => {
            //         enableButtonRemoveLoader('deviceadd');
            //     });
        }


        function disableButtonWithLoader(buttonId) {
            const btn = document.getElementById(buttonId);
            if (!btn) return;

            btn.disabled = true;
            btn.classList.add('disabled');

            btn.querySelector('.btn-text')?.classList.add('d-none');
            btn.querySelector('.btn-loader')?.classList.remove('d-none');
        }

        function enableButtonRemoveLoader(buttonId) {
            const btn = document.getElementById(buttonId);
            if (!btn) return;

            btn.disabled = false;
            btn.classList.remove('disabled');

            btn.querySelector('.btn-text')?.classList.remove('d-none');
            btn.querySelector('.btn-loader')?.classList.add('d-none');
        }

        /**----------------------------------------------------- Add Multiple Devices END -----------------------------------------------------  */

        /**----------------------------------------------------- Edit Device START -----------------------------------------------------  */

        // check edit page is open
        scope.isEditMode = window.location.href.includes('/edit');

        // get data for edit page
        this.fetchDeviceData = function () {
            requestFactory.post(
                requestFactory.getUrl('divice/records'),
                this.programData,
                function (response) {
                    if (response.data && response.data && Array.isArray(response.data.data)) {
                        getDeviceData(response.data.data);
                    } else {
                        console.warn("Invalid Data format from device list :", response);
                    }
                }
            )
        }

        function getDeviceData(data) {
            const editPgElmnt = document.getElementById('home');
            if (!editPgElmnt) {
                console.warn("Edit page element not found");
                return;
            }
            const localScope = angular.element(editPgElmnt).scope();
            const targetRecordId = document.getElementById('device-id')?.value;
            if (!targetRecordId) {
                console.warn("Target record ID not found");
                return;
            }

            const record = data.find(item => item.id == targetRecordId);
            if (record) {
                if (localScope && localScope.deviceCtrl) {
                    const updateModel = () => {

                        const organizations = record.get_all_organization || record.organizations || [];
                        const selectedOrgs = [];
                        if (organizations && Array.isArray(organizations)) {
                            organizations.forEach(element => {
                                selectedOrgs.push(element.organization_name);
                            });
                        }

                        console.log("Record Data : ", record);

                        localScope.deviceCtrl.deviceData = {
                            mac_add: record.mac_address || '',
                            serial_no: record.serial_number || '',
                            device_redirect: record.device_redirect ?? '0',
                            identifier: record.identifier || '',
                            timezone: record.timezone || '',
                            organization: organizations,
                            organization_id: record.organization_id || '',
                            security_code_req: record.security_code_required == "1",
                            security_code: record.security_code || '',
                            subscribers_id: record.subscriber_id ? Number(record.subscriber_id) : '',
                            device_model: record.brand_model || '',
                            firmware_version: record.firmware_version || '',
                            ip_address: record.ip_address || '',
                            isp: record.isp || '',
                            location: record.location || '',
                            status: record.is_active == "1",
                            slectedOrgName: selectedOrgs,
                            first_value: record.first_value || '',
                            serial_mac_seperator: record.serial_mac_seperator || '',
                            create_subscribers: record.create_subscriber == "1",
                        };
                    }

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    }
                    else {
                        updateModel();
                    }
                } else {
                    console.warn('No Device found with ID:', targetRecordId);
                }
            }
        }
        this.fetchDeviceData();

        // to view add page
        this.editDevices = function (id) {
            window.location.href = 'divice/edit/' + id;
        }

        // delete on click of remove button on edit page
        this.removeDevice = function (event) {
            const recordId = document.getElementById('device-id')?.value;

            requestFactory.post(
                requestFactory.getUrl('divice/destroy/' + recordId),
                this.deviceData,
                function (response) {
                    // scope.getRecords(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/divice`;
                    }, 200);
                }
            )

        }

        // cancel Record
        scope.cancelDevice = function () {
            window.location.href = `${appUrl}admin/divice`;
        }


        /**----------------------------------------------------- Edit Device END -----------------------------------------------------  */
        // serch user records
        // this.searchDeviceRecords = function () {
        //     // const getSearchValue = document.getElementById('ind-search-inpt').value;

        //     const payload = {
        //         searchVal: this.deviceData.search_value,
        //     }
        //     console.log(payload);


        //     requestFactory.post(
        //         requestFactory.getUrl('divice/search-record'),
        //         payload,
        //         function (response) {
        //             if (response && response.data && Array.isArray(response.data.data)) {
        //                 console.log("Search Records : ", response);
        //             } else {
        //                 console.warn("Invalid data format from api access:", response);
        //             }
        //         }
        //     );
        // }

        scope.$on('afterGetRecords', function (e, data) {

            if (angular.isUndefined(scope.searchRecords.serial_no)) {
                scope.searchRecords.serial_no = '';
            }
        })
    }];


window.gridControllers = { DeviceController: DeviceController };

