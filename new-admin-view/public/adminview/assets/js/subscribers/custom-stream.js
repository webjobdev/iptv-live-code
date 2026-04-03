var CustomStreamController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.channel = {}
        scope.errors = {};
        this.channelList = [];
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/credit-card/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        this.editchannel = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.channel.id = records.id;
            this.channel.channel_list = records.channel_list;
            // console.log(this.channel);

            $("#subscriptionForm").css('display', 'block');
            $("#subscriptionTranslationForm").css('display', "none");
        }

        this.savechannel = function ($event, id) {
            $event.preventDefault();

            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (!subscriberId) {
                console.error("❌ Subscriber ID not found in URL.");
                return;
            }

            const today = new Date();
            const formattedDate = [
                ('0' + today.getDate()).slice(-2),
                ('0' + (today.getMonth() + 1)).slice(-2),
                today.getFullYear()
            ].join('-');

            const payload = {
                subscriber_id: subscriberId,
                start_at: formattedDate,
                end_at: this.channel.end_at || '',
                channel_list: (this.customstream?.bundles || []).map(b => b.channel_name)
            };

            // console.log("🔄 Sending Payload:", payload);

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/add/channel-list/edit/' + id), payload,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }, this.fillError
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/add/channel-list'), payload,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', response.message);
                        requestFactory.getToaster();
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }, this.fillError
                );
            }
        };

        scope.togglePublishNow = function (record, id) {
            record.is_active = record.is_active == 1 ? 0 : 1;

            const payload = {
                id: record.id,
                is_active: record.is_active,
            };

            if (id) {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/add/channel-list/edit/' + id), payload,
                    function (response) {
                        scope.getRecords(true);
                        requestFactory.setToaster('success', 'Publish Status Updated.');
                        requestFactory.getToaster();
                        setTimeout(function () {
                            window.location.reload();
                        }, 350);
                    }
                )
            } else {
                console.error('❌ ID not found. Cannot proceed with update.');
                return {
                    error: true
                };
            }
        }

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        this.fetchchannel = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/records'), this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        findchannelname(response.data.data);
                    } else {
                        console.error("Invalid data format from channel:", response);
                    }
                }
            );
        };

        function findchannelname(chnl) {
            const homeElement = document.getElementById("custom-stream");
            if (!homeElement) {
                console.error("Id not found.");
                return;
            }

            const localScope = angular.element(homeElement).scope();
            const targetOrgId = document.getElementById("availableBundles")?.value;

            if (localScope && localScope.cmsCtrl) {
                const updateModel = () => {
                    localScope.cmsCtrl.channelList = chnl;

                    const bundle = chnl.find(cnl => String(cnl.id) === String(targetOrgId));
                    if (bundle) {
                        localScope.cmsCtrl.customstream = {
                            id: bundle.id,
                            channel_name: bundle.channel_name,
                        }
                    }
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            }
        }
        this.fetchchannel();

        // AngularJS Controller Logic
        scope.cmsCtrl.selectedBundles = [];

        scope.cmsCtrl.assignSelectedBundles = function () {
            const ctrl = scope.cmsCtrl;
            if (!ctrl.customstream) ctrl.customstream = {};
            ctrl.customstream.bundles = angular.copy(ctrl.selectedBundles);
            console.log("✅ Assigned Bundles:", ctrl.customstream.bundles);
            $('#add-bundles').modal('hide');
        };

        $('#add-bundles').on('shown.bs.modal', function () {
            initializeBundleDragDrop();
        });

        function initializeBundleDragDrop() {
            const available = document.getElementById('availableBundles');
            const added = document.getElementById('addedBundles');

            available.querySelectorAll('.bundle-card').forEach(card => {
                card.setAttribute('draggable', 'true');
                card.addEventListener('dragstart', e => {
                    e.dataTransfer.setData('text/plain', card.getAttribute('data-id'));
                    e.dataTransfer.effectAllowed = 'move';
                    card.classList.add('dragging');
                });
                card.addEventListener('dragend', () => card.classList.remove('dragging'));
            });

            added.addEventListener('dragover', e => e.preventDefault());

            added.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                const card = available.querySelector(`[data-id="${draggedId}"]`);
                if (!card || added.querySelector(`[data-id="${draggedId}"]`)) return;

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');

                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = `<span class="bundle-remove"><i class="glyphicon glyphicon-remove-circle"></i></span>`;
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    added.removeChild(clone);
                    available.appendChild(card);
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                added.appendChild(clone);
                card.remove();
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const ctrl = scope.cmsCtrl;
                ctrl.selectedBundles = [];
                added.querySelectorAll('.bundle-card').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = ctrl.channelList.find(b => b.id === id);
                    if (bundle) ctrl.selectedBundles.push(bundle);
                });
                scope.$applyAsync();
            }

            setupSearch('searchAvailable', 'availableBundles');
            setupSearch('searchAdded', 'addedBundles');

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);
                input.addEventListener('input', () => {
                    const val = input.value.toLowerCase();
                    container.querySelectorAll('.bundle-card').forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }
        }


    }
];

window.gridControllers = {
    CustomStreamController: CustomStreamController
};