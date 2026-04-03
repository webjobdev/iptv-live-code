var SubscriptionController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope', '$http',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope, http) {

        var self = this;

        this.info = {};
        this.accessories = {};
        this.subscriptionRules = [];
        this.contentAddOnRules = [];
        this.accessoriesRules = [];

        scope.rule = {
            subsRule: [],
            contentRule: [],
            accessories: []
        };

        scope.toShowAssignedBundles = {
            assignChannelBundles: [],
            assignChannelAddOnsBundles: [],
            assignLeventBundles: [],
            assignLeventAddOnsBundles: [],
            assignTvShowBundles: [],
            assignTvShowAddOnsBundles: [],
            assignVodBundles: [],
            assignVodAddOnsBundles: [],

            assignAccessoriesBundles: [],
            assignPartnerProductBundles: [],
            assignPartnerProductAddOnsBundles: [],
        };

        scope.channlContentSet = {
            channelId: [],
        };

        scope.assignedContentSets = {
            assignedChnls: [],
            assignedChnlsAddOns: [],
            assignedLevnts: [],
            assignedLevntAddOns: [],
            assignedTvshow: [],
            assignedTvshowAddOns: [],
            assignedVod: [],
            assignedVodAddOns: [],
            assignedAccessories: [],
            assignedPproductSets: [],
            assignedPproductAddOns: [],
        }

        scope.channlContentAddOnsSet = {
            channelAddOnsId: [],
            chnlModalData: [],
        };

        scope.liveEventContentSet = {
            lEvent: [],
        };
        scope.liveEventContentAddOnsSet = {
            lEventAddOns: [],
            lEventModalData: [],
        };
        scope.vodContentSet = {
            vodSet: [],
        };
        scope.vodContentSetAddOns = {
            vodContentAddOns: [],
            vodModalData: []
        };
        scope.tvShowContentSet = {
            tvShows: [],
        };
        scope.tvShowAddOnsContentSet = {
            tvShowAddOns: [],
            tvShowModalData: [],
        };
        scope.accessoriesContentSet = {
            accessories: []
        };
        scope.pProductContentSet = {
            pProductSet: []
        };

        scope.extraPproductContentSet = {
            pProductContentAddOns: [],
            prtnrProductModalData: []
        };

        scope.deviceInputs = {
            devices: [],
        }

        this.btnNo = 0;
        scope.errors = {};
        requestFactory.getToaster();
        scope.searchRecords = {};
        requestFactory.setThisArgument(this);

        const currentUrl = window.location.href;
        const urlObj = new URL(currentUrl);
        scope.orgIdFromUrl = urlObj.searchParams.get('id');

        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('monetization-plan/subscription/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                },
            );
        }
        this.fetchInfo();

        // ==============================***********************************==============================
        // create code
        // ==============================***********************************==============================

        // auto generate identifier
        window.autogenerateIdentifier = function () {
            const autoChecked = document.getElementById('identifier-auto');
            const identifierInput = document.getElementById('identifier');
            console.log(identifierInput);

            if (autoChecked.checked && autoChecked.checked === true) {
                identifierInput.setAttribute('readonly', 'true');
                const now = new Date();
                const month = now.getMonth() + 1;
                const dateTimeStr = now.getFullYear() + month.toString().padStart(2, '0') + now.getDate() + now.getHours() + now.getMinutes() + now.getSeconds();

                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < 4; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    result += characters.charAt(randomIndex);
                }

                let identifier = 'idn_' + dateTimeStr + result
                identifierInput.value = identifier;
                scope.$apply(function () {
                    scope.subscriptionData.identifier = identifier;
                });

            } else {
                identifierInput.removeAttribute('readonly');
                identifierInput.value = '';
            }
        }

        // add device
        // this.addDeviceInputs = function () {
        //     const inpt = document.getElementById('device_inpt');
        //     const div = document.getElementById('inpt-div');
        //     if (inpt.value != '' && inpt.value > 0) {
        //         div.innerHTML = '';
        //         for (let i = 0; i < inpt.value; i++) {
        //             const inputElement = `
        //             <input type="text" class="form-control mb-2" name="device_${i + 1}" placeholder="Device ${i + 1} Price"
        //             ng-model="subscriptionData.devices[${i}]"
        //             ng-change="calculateTotalDevicePrice()"
        //             style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto; display: visible; margin-top: 5px;">`;

        //             angular.element(div).append(compile(inputElement)(scope));
        //         }
        //     } else {
        //         div.innerHTML = '';
        //     }
        // }

        // calculate total subscription price
        scope.calculateTotalDevicePrice = function () {
            const devices = scope.deviceInputs.devices || {};
            scope.subscriptionData.totalDevicesPrice = Object.values(devices)
                .map(v => parseFloat(v.inputedVal) || 0)
                .reduce((sum, val) => sum + val, 0);

            if (parseFloat(scope.subscriptionData.subs_price) || Number.isInteger(scope.subscriptionData.subs_price) == true) {
                scope.subscriptionData.totalDevicesPrice = scope.subscriptionData.totalDevicesPrice + parseInt(scope.subscriptionData.subs_price);
            }
        };

        scope.addDeviceInputs = function () {
            const inpt = document.getElementById('device_inpt');
            if (inpt.value != '' && inpt.value > 0) {
                scope.deviceInputs.devices = [];
                for (let i = 0; i < inpt.value; i++) {
                    const newId = Date.now();
                    scope.deviceInputs.devices.push({ id: newId + Math.random(), inputedVal: '' });
                }
            }
            // console.log(scope.deviceInputs.devices);
        };

        // add subscription pricing rule
        this.addSubscriptionRuleSec = function () {
            const newId = Date.now();
            scope.rule.subsRule.push({ id: newId + Math.random(), targeted_product: '', condition: '' });
        }

        // remove subscription pricing rule
        this.removeSubscriptionRuleSec = function (index) {
            scope.rule.subsRule.splice(index, 1);
        }

        // add contentAddOns pricing rule
        this.addContentAddOnsRuleSec = function () {
            const newId = Date.now();
            scope.rule.contentRule.push({ id: newId + Math.random(), targeted_product: '', condition: '' });
        }

        // remove contentAddOns pricing rule
        this.removeContentAddOnsRuleSec = function (index) {
            scope.rule.contentRule.splice(index, 1);
        }

        // add accessories pricing rule
        this.addAccessoriesRuleSec = function () {
            const newId = Date.now();
            scope.rule.accessories.push({ id: newId + Math.random(), targeted_product: '', condition: '' });
        }

        // remove accessories pricing rule
        this.removeAccessoriesRuleSec = function (index) {
            scope.rule.accessories.splice(index, 1);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const orgId = urlParams.get('id');

        // get channel set list
        this.getChannels = function () {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {

                        this.channelList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );

                    } else {
                        console.warn("Invalid response format from Channel Set Api :", response);

                    }
                }
            )
        }
        this.getChannels();

        // get VOD set list
        this.getVodSets = function () {
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.vodList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );
                    } else {
                        console.warn("Invalid response format from VOD Set Api :", response);

                    }
                }
            )
        }
        this.getVodSets();

        // get TV Show set list
        this.getTvShowSets = function () {
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.tvShowList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );
                    } else {
                        console.warn("Invalid response format from TV Show Set Api :", response);

                    }
                }
            )
        }
        this.getTvShowSets();

        // get Live Event set list
        this.getLiveEventSets = function () {
            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.liveEventList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );
                    } else {
                        console.warn("Invalid response format from Lice Event Set Api :", response);

                    }
                }
            )
        }
        this.getLiveEventSets();

        // get Accessories set list
        this.getAccessoriesSets = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetization-plan/accessories/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.accessoriesList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );
                    } else {
                        console.warn("Invalid response format from Accessories Set Api :", response);

                    }
                }
            )
        }
        this.getAccessoriesSets();

        // get partner product list
        this.getPartnerProduct = function () {
            requestFactory.post(
                requestFactory.getUrl('organizations/partner-product/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        this.partnerProductList = response.data.data.filter(item =>
                            item.organization_id == orgId
                        );
                    } else {
                        console.warn("Invalid response format from Channel Set Api :", response);

                    }
                }
            )
        }
        this.getPartnerProduct();



        // channel content set
        scope.assignChannelBundles = function () {
            scope.channlContentSet.channelId = [];

            scope.toShowAssignedBundles.assignChannelBundles.map(e =>
                scope.channlContentSet.channelId.push(e)
            );
        }

        // remove assigned channels
        scope.removeChannelBundles = function (rec) {
            scope.toShowAssignedBundles.assignChannelBundles = scope.toShowAssignedBundles.assignChannelBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.channelList.push(rec);
        }

        // channel content set add-ons
        scope.assignChannelAddOnsBundles = function () {
            scope.channlContentAddOnsSet.channelAddOnsId = [];
            scope.toShowAssignedBundles.assignChannelAddOnsBundles.map(e => {
                scope.channlContentAddOnsSet.channelAddOnsId.push(e);
            });
        }

        // remove assigned channel add-ons
        scope.removeChannelAddOnsBundles = function (rec) {
            scope.toShowAssignedBundles.assignChannelAddOnsBundles = scope.toShowAssignedBundles.assignChannelAddOnsBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.channelList.push(rec);
        }

        // Live Event Sets
        scope.assignedLeventBundles = function () {
            scope.liveEventContentSet.lEvent = [];
            scope.toShowAssignedBundles.assignLeventBundles.map(e => {
                scope.liveEventContentSet.lEvent.push(e);
            });
        }

        // remove assigned live event
        scope.removeLeventBundles = function (rec) {
            scope.toShowAssignedBundles.assignLeventBundles = scope.toShowAssignedBundles.assignLeventBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.liveEventList.push(rec);
        }

        // Live Event Add-Ons Sets
        scope.assignedLeventAddOnsBundles = function () {
            scope.liveEventContentAddOnsSet.lEventAddOns = [];
            scope.toShowAssignedBundles.assignLeventAddOnsBundles.map(e => {
                scope.liveEventContentAddOnsSet.lEventAddOns.push(e);
            });
        }

        // remove assigned live event add-ons
        scope.removeLeventAddOnsBundles = function (rec) {
            scope.toShowAssignedBundles.assignLeventAddOnsBundles = scope.toShowAssignedBundles.assignLeventAddOnsBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.liveEventList.push(rec);
        }

        // VOD Sets
        scope.assignedVodBundles = function () {
            scope.vodContentSet.vodSet = [];
            scope.toShowAssignedBundles.assignVodBundles.map(e => {
                scope.vodContentSet.vodSet.push(e);
            });
        }

        // remove assigned vod
        scope.removeVodBundles = function (rec) {
            scope.toShowAssignedBundles.assignVodBundles = scope.toShowAssignedBundles.assignVodBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.vodList.push(rec);
        }

        // VOD Add-Ons Sets
        scope.assignedVodAddOnsBundles = function () {
            scope.vodContentSetAddOns.vodContentAddOns = [];
            scope.toShowAssignedBundles.assignVodAddOnsBundles.map(e => {
                scope.vodContentSetAddOns.vodContentAddOns.push(e);
            });
        }

        // remove assigned vod add-ons
        scope.removeVodAddOnsBundles = function (rec) {
            scope.toShowAssignedBundles.assignVodAddOnsBundles = scope.toShowAssignedBundles.assignVodAddOnsBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.vodList.push(rec);
        }

        // TV Show Sets
        scope.assignedTvshowBundles = function () {
            scope.tvShowContentSet.tvShows = [];
            scope.toShowAssignedBundles.assignTvShowBundles.map(e => {
                scope.tvShowContentSet.tvShows.push(e);
            });
        }

        // remove assigned tvshow
        scope.removeTvshowBundles = function (rec) {
            scope.toShowAssignedBundles.assignTvShowBundles = scope.toShowAssignedBundles.assignTvShowBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.tvShowList.push(rec);
        }

        // TV Show Add-Ons Sets
        scope.assignedTvshowAddOnsBundles = function () {
            scope.tvShowAddOnsContentSet.tvShowAddOns = [];
            scope.toShowAssignedBundles.assignTvShowAddOnsBundles.map(e => {
                scope.tvShowAddOnsContentSet.tvShowAddOns.push(e);
            });
        }

        // remove assigned tvshow add-ons
        scope.removeTvshowAddOnsBundles = function (rec) {
            scope.toShowAssignedBundles.assignTvShowAddOnsBundles = scope.toShowAssignedBundles.assignTvShowAddOnsBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.tvShowList.push(rec);
        }



        // Accessories Sets
        scope.assignedAccessoriesBundles = function () {
            scope.accessoriesContentSet.accessories = [];
            scope.toShowAssignedBundles.assignAccessoriesBundles.map(e => {
                scope.accessoriesContentSet.accessories.push(e);
            });
        }

        // remove assigned accessories
        scope.removeAccessoriesBundles = function (rec) {
            scope.toShowAssignedBundles.assignAccessoriesBundles = scope.toShowAssignedBundles.assignAccessoriesBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.accessoriesList.push(rec);
        }


        // Partner Product Sets
        scope.assignedPartnrProductBundles = function () {
            scope.pProductContentSet.pProductSet = [];
            scope.toShowAssignedBundles.assignPartnerProductBundles.map(e => {
                scope.pProductContentSet.pProductSet.push(e);
            });
        }

        // remove assigned partner products
        scope.removePproductBundles = function (rec) {
            scope.toShowAssignedBundles.assignPartnerProductBundles = scope.toShowAssignedBundles.assignPartnerProductBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.partnerProductList.push(rec);
        }


        // Partner Product Add-Ons Sets
        scope.assignedPartnrProductAddOnsBundles = function () {
            scope.extraPproductContentSet.pProductContentAddOns = [];
            scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.map(e => {
                scope.extraPproductContentSet.pProductContentAddOns.push(e);
            });
        }

        // remove assigned accessories
        scope.removePproductAddOnsBundles = function (rec) {
            scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles = scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.filter(o => parseInt(o.id) !== parseInt(rec.id));
            self.partnerProductList.push(rec);
        }


        scope.isEditMode = window.location.href.includes('/edit');


        // --------------------------------------------- Channel Drag and Drop START --------------------------------------------//
        // Channel Content Drag and Drop
        this.channelSetContentDragDrop = function () {
            scope.toShowAssignedBundles.assignChannelBundles = []; // empty array

            // fill assigned data back to display array
            scope.channlContentSet.channelId.map(e =>
                scope.toShowAssignedBundles.assignChannelBundles.push(e)
                // scope.assignedContentSets.assignedChnls.push(e)
            );

            // remove assigend channel from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.channlContentSet.channelId.map(r =>
                    this.channelList = this.channelList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.channlContentAddOnsSet.channelAddOnsId.map(r => this.channelList = this.channelList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedChannelBundles = document.getElementById('addedChannelBundles');
            const availableChannelBundles = document.getElementById('availableChannelBundles');

            if (!addedChannelBundles || !availableChannelBundles) {
                console.warn("Drop zones not found!");
                return;
            }

            addedChannelBundles.innerHTML = `<div class="drop-zone">DROP HERE</div>`;

            // Drag events
            document.querySelectorAll('#availableChannelBundles .content-container').forEach(card => {
                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                });
            });

            // Drop zone setup
            addedChannelBundles.addEventListener('dragover', e => {
                e.preventDefault();
            });

            addedChannelBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                const card = availableChannelBundles.querySelector(`[data-id="${draggedId}"]`);
                // console.log("Dragged Card : ", card);


                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                if (addedChannelBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignChannelBundles.push(bundleData);

                const placeholder = addedChannelBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor: pointer; float:right;';
                removeBtn.onclick = () => {
                    addedChannelBundles.removeChild(clone);
                    availableChannelBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignChannelBundles = scope.toShowAssignedBundles.assignChannelBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId),
                    );

                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedChannelBundles.appendChild(clone);
                card.remove();
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedChannelBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignChannelBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                    }
                });

                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchChannelAvailable', 'availableChannelBundles');
            setupSearch('searchChannelAdded', 'addedChannelBundles');
        }

        // Channel Content Add Ons Drag and Drop
        this.channelAddOnsContentDragDrop = function () {
            scope.toShowAssignedBundles.assignChannelAddOnsBundles = []; // empty array

            // fill assigned data back to display array
            scope.channlContentAddOnsSet.channelAddOnsId.map(e =>
                scope.toShowAssignedBundles.assignChannelAddOnsBundles.push(e)
            );

            // remove assigend channel from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.channlContentAddOnsSet.channelAddOnsId.map(r =>
                    this.channelList = this.channelList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.channlContentSet.channelId.map(r => this.channelList = this.channelList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedChannelAddOnsBundles = document.getElementById('addedChannelAddOnsBundles');
            const availableChannelAddOnsBundles = document.getElementById('availableChannelAddOnsBundles');

            if (!addedChannelAddOnsBundles || !availableChannelAddOnsBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedChannelAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';

            // Drag events
            document.querySelectorAll('#availableChannelAddOnsBundles .content-container').forEach(card => {

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                });
            });

            // Drop zone setup
            addedChannelAddOnsBundles.addEventListener('dragover', e => {
                e.preventDefault();
            });

            addedChannelAddOnsBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                const card = availableChannelAddOnsBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignChannelAddOnsBundles.push(bundleData);

                if (addedChannelAddOnsBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedChannelAddOnsBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor: pointer; float:right;';
                removeBtn.onclick = () => {
                    addedChannelAddOnsBundles.removeChild(clone);
                    availableChannelAddOnsBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignChannelAddOnsBundles = scope.toShowAssignedBundles.assignChannelAddOnsBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedChannelAddOnsBundles.appendChild(clone);
                card.remove();

                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedChannelAddOnsBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignChannelAddOnsBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchChannelAddOnsAvailable', 'availableChannelAddOnsBundles');
            setupSearch('searchChannelAddOnsAdded', 'addedChannelAddOnsBundles');
        }
        // --------------------------------------------- Channel Drag and Drop END --------------------------------------------//


        // --------------------------------------------- Live Event Drag and Drop START --------------------------------------------//

        // Basic Live Event Drag and Drop
        this.liveEventContentDragDrop = function () {
            scope.toShowAssignedBundles.assignLeventBundles = []; // empty array

            // fill assigned data back to display array
            scope.liveEventContentSet.lEvent.map(e =>
                scope.toShowAssignedBundles.assignLeventBundles.push(e)
            );

            // remove assigend live events from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.liveEventContentSet.lEvent.map(r =>
                    this.liveEventList = this.liveEventList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.liveEventContentAddOnsSet.lEventAddOns.map(r => this.liveEventList = this.liveEventList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedLiveEventBundles = document.getElementById('addedLiveEventBundles');
            const availableLiveEventBundles = document.getElementById('availableLiveEventBundles');

            if (!addedLiveEventBundles || !availableLiveEventBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedLiveEventBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableLiveEventBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedLiveEventBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedLiveEventBundles drop zone...");
            });

            addedLiveEventBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableLiveEventBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                if (addedLiveEventBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignLeventBundles.push(bundleData);

                const placeholder = addedLiveEventBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedLiveEventBundles.removeChild(clone);
                    availableLiveEventBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignLeventBundles = scope.toShowAssignedBundles.assignLeventBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );
                    console.log("After Array : ", scope.toShowAssignedBundles.assignLeventBundles);


                    // ✅ show placeholder if no items left
                    // if (addedLiveEventBundles.querySelectorAll('.channel-item').length === 0) {
                    //     addedLiveEventBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    // }

                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedLiveEventBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;
                // console.log("Channel Content Set : ", scope);
                // console.log("Channel Content Set : ", ctrl);


                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedLiveEventBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignLeventBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchLiveEventAvailable', 'availableLiveEventBundles');
            setupSearch('searchLiveEventAdded', 'addedLiveEventBundles');
        }

        // Basic Live Event Add Ons Drag and Drop
        this.liveEventContentAddOnsDragDrop = function () {
            scope.toShowAssignedBundles.assignLeventAddOnsBundles = []; // empty array

            // fill assigned data back to display array
            scope.liveEventContentAddOnsSet.lEventAddOns.map(e =>
                scope.toShowAssignedBundles.assignLeventAddOnsBundles.push(e)
            );

            // remove assigend live event add-ons from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.liveEventContentSet.lEvent.map(r =>
                    this.liveEventList = this.liveEventList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.liveEventContentAddOnsSet.lEventAddOns.map(r => this.liveEventList = this.liveEventList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedLiveEventAddOnsBundles = document.getElementById('addedLiveEventAddOnsBundles');
            const availableLiveEventAddOnsBundles = document.getElementById('availableLiveEventAddOnsBundles');

            if (!addedLiveEventAddOnsBundles || !availableLiveEventAddOnsBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedLiveEventAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableLiveEventAddOnsBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedLiveEventAddOnsBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedLiveEventAddOnsBundles drop zone...");
            });

            addedLiveEventAddOnsBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                // console.log(`📤 Drop detected. Dropped Bundle ID = ${draggedId}`);

                const card = availableLiveEventAddOnsBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignLeventAddOnsBundles.push(bundleData);

                if (addedLiveEventAddOnsBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedLiveEventAddOnsBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedLiveEventAddOnsBundles.removeChild(clone);
                    availableLiveEventAddOnsBundles.appendChild(card);
                    scope.toShowAssignedBundles.assignLeventAddOnsBundles.splice(card.id, 1);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignLeventAddOnsBundles = scope.toShowAssignedBundles.assignLeventAddOnsBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedLiveEventAddOnsBundles.querySelectorAll('.channel-item').length === 0) {
                        addedLiveEventAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }

                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedLiveEventAddOnsBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedLiveEventAddOnsBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignLeventAddOnsBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchLiveEventAddOnsAvailable', 'availableLiveEventAddOnsBundles');
            setupSearch('searchLiveEventAddOnsAdded', 'addedLiveEventAddOnsBundles');
        }

        // --------------------------------------------- Live Event Drag and Drop END --------------------------------------------//

        // --------------------------------------------- VOD Drag and Drop START --------------------------------------------//

        // Basic VOD Drag and Drop
        this.vodContentDragDrop = function () {
            scope.toShowAssignedBundles.assignVodBundles = []; // empty array

            // fill assigned data back to display array
            scope.vodContentSet.vodSet.map(e =>
                scope.toShowAssignedBundles.assignVodBundles.push(e)
            );

            // remove assigend vod set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.vodContentSet.vodSet.map(r =>
                    this.vodList = this.vodList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.vodContentSetAddOns.vodContentAddOns.map(r => this.vodList = this.vodList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedVodBundles = document.getElementById('addedVodBundles');
            const availableVodBundles = document.getElementById('availableVodBundles');

            if (!addedVodBundles || !availableVodBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedVodBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableVodBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedVodBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedVodBundles drop zone...");
            });

            addedVodBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableVodBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignVodBundles.push(bundleData);

                if (addedVodBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedVodBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedVodBundles.removeChild(clone);
                    availableVodBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignVodBundles = scope.toShowAssignedBundles.assignVodBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedVodBundles.querySelectorAll('.channel-item').length === 0) {
                        addedVodBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }

                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedVodBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedVodBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignVodBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchVodAvailable', 'availableVodBundles');
            setupSearch('searchVodAdded', 'addedVodBundles');
        }

        // Basic VOD Add Ons Drag and Drop
        this.vodContentAddOnsDragDrop = function () {
            scope.toShowAssignedBundles.assignVodAddOnsBundles = []; // empty array

            // fill assigned data back to display array
            scope.vodContentSetAddOns.vodContentAddOns.map(e =>
                scope.toShowAssignedBundles.assignVodAddOnsBundles.push(e)
            );

            // remove assigend vod add-ons set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.vodContentSet.vodSet.map(r =>
                    this.vodList = this.vodList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.vodContentSetAddOns.vodContentAddOns.map(r => this.vodList = this.vodList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedVodAddOnsBundles = document.getElementById('addedVodAddOnsBundles');
            const availableVodAddOnsBundles = document.getElementById('availableVodAddOnsBundles');

            if (!addedVodAddOnsBundles || !availableVodAddOnsBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedVodAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableVodAddOnsBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedVodAddOnsBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedVodAddOnsBundles drop zone...");
            });

            addedVodAddOnsBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableVodAddOnsBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignVodAddOnsBundles.push(bundleData);

                if (addedVodAddOnsBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedVodAddOnsBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedVodAddOnsBundles.removeChild(clone);
                    availableVodAddOnsBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignVodAddOnsBundles = scope.toShowAssignedBundles.assignVodAddOnsBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedVodAddOnsBundles.querySelectorAll('.channel-item').length === 0) {
                        addedVodAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }

                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedVodAddOnsBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedVodAddOnsBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignVodAddOnsBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchVodAddOnsAvailable', 'availableVodAddOnsBundles');
            setupSearch('searchVodAddOnsAdded', 'addedVodAddOnsBundles');
        }
        // --------------------------------------------- VOD Drag and Drop END --------------------------------------------//

        // --------------------------------------------- TV Show Drag and Drop START --------------------------------------------//

        // Basic TV Show Drag and Drop
        this.tvShowContentDragDrop = function () {
            scope.toShowAssignedBundles.assignTvShowBundles = []; // empty array

            // fill assigned data back to display array
            scope.tvShowContentSet.tvShows.map(e =>
                scope.toShowAssignedBundles.assignTvShowBundles.push(e)
            );

            // remove assigend tv-show set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.tvShowContentSet.tvShows.map(r =>
                    this.tvShowList = this.tvShowList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.tvShowAddOnsContentSet.tvShowAddOns.map(r => this.tvShowList = this.tvShowList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedTvShowBundles = document.getElementById('addedTvShowBundles');
            const availableTvShowBundles = document.getElementById('availableTvShowBundles');

            if (!addedTvShowBundles || !availableTvShowBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedTvShowBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableTvShowBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedTvShowBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedTvShowBundles drop zone...");
            });

            addedTvShowBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableTvShowBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignTvShowBundles.push(bundleData);

                if (addedTvShowBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedTvShowBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedTvShowBundles.removeChild(clone);
                    availableTvShowBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignTvShowBundles = scope.toShowAssignedBundles.assignTvShowBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedTvShowBundles.querySelectorAll('.channel-item').length === 0) {
                        addedTvShowBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }

                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedTvShowBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedTvShowBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignTvShowBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchTvShowAvailable', 'availableTvShowBundles');
            setupSearch('searchTvShowAdded', 'addedTvShowBundles');
        }

        // Basic VOD Add Ons Drag and Drop
        this.tvShowContentAddOnsDragDrop = function () {
            scope.toShowAssignedBundles.assignTvShowBundles = []; // empty array

            // fill assigned data back to display array
            scope.tvShowAddOnsContentSet.tvShowAddOns.map(e =>
                scope.toShowAssignedBundles.assignTvShowBundles.push(e)
            );

            // remove assigend tv-show add-on sets from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.tvShowContentSet.tvShows.map(r =>
                    this.tvShowList = this.tvShowList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.tvShowAddOnsContentSet.tvShowAddOns.map(r => this.tvShowList = this.tvShowList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedTvShowAddOnsBundles = document.getElementById('addedTvShowAddOnsBundles');
            const availableTvShowAddOnsBundles = document.getElementById('availableTvShowAddOnsBundles');

            if (!addedTvShowAddOnsBundles || !availableTvShowAddOnsBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedTvShowAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableTvShowAddOnsBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedTvShowAddOnsBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedTvShowAddOnsBundles drop zone...");
            });

            addedTvShowAddOnsBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableTvShowAddOnsBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignTvShowAddOnsBundles.push(bundleData);

                if (addedTvShowAddOnsBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedTvShowAddOnsBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedTvShowAddOnsBundles.removeChild(clone);
                    availableTvShowAddOnsBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignTvShowAddOnsBundles = scope.toShowAssignedBundles.assignTvShowAddOnsBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedTvShowAddOnsBundles.querySelectorAll('.channel-item').length === 0) {
                        addedTvShowAddOnsBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedTvShowAddOnsBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedTvShowAddOnsBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignTvShowAddOnsBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchTvShowAddOnsAvailable', 'availableTvShowAddOnsBundles');
            setupSearch('searchTvShowAddOnsAdded', 'addedTvShowAddOnsBundles');
        }
        // --------------------------------------------- TV Show Drag and Drop END --------------------------------------------//

        // --------------------------------------------- Accessories Drag and Drop START --------------------------------------------//
        // Accessories Drag and Drop
        this.accessoriesContentDragDrop = function () {
            scope.toShowAssignedBundles.assignAccessoriesBundles = []; // empty array

            // fill assigned data back to display array
            scope.accessoriesContentSet.accessories.map(e =>
                scope.toShowAssignedBundles.assignAccessoriesBundles.push(e)
            );

            // remove assigend tv-show set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.accessoriesContentSet.accessories.map(r =>
                    this.accessoriesList = this.accessoriesList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
            }

            const addedAccessoriesBundles = document.getElementById('addedAccessoriesBundles');
            const availableAccessoriesBundles = document.getElementById('availableAccessoriesBundles');

            if (!addedAccessoriesBundles || !availableAccessoriesBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedAccessoriesBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableAccessoriesBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedAccessoriesBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedAccessoriesBundles drop zone...");
            });

            addedAccessoriesBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableAccessoriesBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignAccessoriesBundles.push(bundleData);

                if (addedAccessoriesBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedAccessoriesBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedAccessoriesBundles.removeChild(clone);
                    availableAccessoriesBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignAccessoriesBundles = scope.toShowAssignedBundles.assignAccessoriesBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedAccessoriesBundles.querySelectorAll('.channel-item').length === 0) {
                        addedAccessoriesBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedAccessoriesBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedAccessoriesBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignAccessoriesBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchAccessoriesAvailable', 'availableAccessoriesBundles');
            setupSearch('searchAccessoriesAdded', 'addedAccessoriesBundles');
        }

        // --------------------------------------------- Accessories Drag and Drop END --------------------------------------------//


        // --------------------------------------------- Partner Products Drag and Drop START --------------------------------------------//

        // Partner Products Drag and Drop
        this.partnerProductsContentDragDrop = function () {
            scope.toShowAssignedBundles.assignPartnerProductBundles = []; // empty array

            // fill assigned data back to display array
            scope.pProductContentSet.pProductSet.map(e =>
                scope.toShowAssignedBundles.assignPartnerProductBundles.push(e)
            );

            // remove assigend partner product set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.pProductContentSet.pProductSet.map(r =>
                    this.partnerProductList = this.partnerProductList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.extraPproductContentSet.pProductContentAddOns.map(r => this.partnerProductList = this.partnerProductList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedPproductBundles = document.getElementById('addedPproductBundles');
            const availablePproductBundles = document.getElementById('availablePproductBundles');

            if (!addedPproductBundles || !availablePproductBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedPproductBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availablePproductBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedPproductBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedPproductBundles drop zone...");
            });

            addedPproductBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availablePproductBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignPartnerProductBundles.push(bundleData);

                if (addedPproductBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedPproductBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedPproductBundles.removeChild(clone);
                    availablePproductBundles.appendChild(card);
                    scope.pProductContentSet.pProductSet.splice(card.id, 1);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignPartnerProductBundles = scope.toShowAssignedBundles.assignPartnerProductBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedPproductBundles.querySelectorAll('.channel-item').length === 0) {
                        addedPproductBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedPproductBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedPproductBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignPartnerProductBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchPproductAvailable', 'availablePproductBundles');
            setupSearch('searchPproductAdded', 'addedPproductBundles');
        }

        // Extra Partner Products Drag and Drop
        this.extraPartnerProductsContentDragDrop = function () {
            scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles = []; // empty array

            // fill assigned data back to display array
            scope.extraPproductContentSet.pProductContentAddOns.map(e =>
                scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.push(e)
            );

            // remove assigend partner product set from availabel channels for edit mode
            if (scope.isEditMode == true) {
                scope.pProductContentSet.pProductSet.map(r =>
                    this.partnerProductList = this.partnerProductList.filter(e => parseInt(e.id) !== parseInt(r.id))
                );
                scope.extraPproductContentSet.pProductContentAddOns.map(r => this.partnerProductList = this.partnerProductList.filter(e => parseInt(e.id) !== parseInt(r.id)));
            }

            const addedExtraPproductBundles = document.getElementById('addedExtraPproductBundles');
            const availableExtraPproductBundles = document.getElementById('availableExtraPproductBundles');

            if (!addedExtraPproductBundles || !availableExtraPproductBundles) {
                console.warn("❌ Drop zones not found!");
                return;
            }

            addedExtraPproductBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
            // console.log("🧹 Cleared previous added bundles.");

            // Drag events
            document.querySelectorAll('#availableExtraPproductBundles .content-container').forEach(card => {
                // console.log("Setting up drag events for card: ", card);

                card.addEventListener('dragstart', e => {
                    const id = card.getAttribute('data-id');
                    e.dataTransfer.setData('text/plain', id);
                    e.target.classList.add('dragging');
                    // console.log(`🚀 Drag started: Bundle ID = ${id}`);
                });

                card.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                    // console.log(`🏁 Drag ended: Bundle ID = ${card.getAttribute('data-id')}`);
                });
            });

            // Drop zone setup
            addedExtraPproductBundles.addEventListener('dragover', e => {
                e.preventDefault();
                // console.log("📥 Dragging over addedExtraPproductBundles drop zone...");
            });

            addedExtraPproductBundles.addEventListener('drop', e => {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');

                const card = availableExtraPproductBundles.querySelector(`[data-id="${draggedId}"]`);

                if (!card) {
                    console.warn(`❌ No card found for ID ${draggedId}`);
                    return;
                }

                let bundleData = JSON.parse(card.getAttribute("data-bundle"));
                scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.push(bundleData);

                if (addedExtraPproductBundles.querySelector(`[data-id="${draggedId}"]`)) {
                    console.warn(`⚠️ Duplicate drop prevented for ID ${draggedId}`);
                    return;
                }

                const placeholder = addedExtraPproductBundles.querySelector('.drop-zone');
                if (placeholder) {
                    placeholder.remove();
                }

                const clone = card.cloneNode(true);
                clone.classList.remove('dragging');
                clone.classList.add('channel-item');

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = `<i class="glyphicon glyphicon-remove-circle"></i>`;
                removeBtn.className = 'remove-btn';
                removeBtn.style.cssText = 'cursor:pointer; float:right;';
                removeBtn.onclick = () => {
                    addedExtraPproductBundles.removeChild(clone);
                    availableExtraPproductBundles.appendChild(card);

                    // remove from scope array
                    scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles = scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.filter(
                        b => parseInt(b.id) !== parseInt(draggedId)
                    );

                    if (addedExtraPproductBundles.querySelectorAll('.channel-item').length === 0) {
                        addedExtraPproductBundles.innerHTML = '<div class="drop-zone">DROP HERE</div>';
                    }
                    updateSelectedBundles();
                };

                clone.appendChild(removeBtn);
                clone.setAttribute('data-id', draggedId);
                clone.setAttribute('draggable', 'true');

                // addedExtraPproductBundles.appendChild(clone);
                card.remove();

                // console.log(`✅ Bundle assigned: ID = ${draggedId}`);
                updateSelectedBundles();
            });

            function updateSelectedBundles() {
                const scope = angular.element(document.getElementById('monetization-planForm')).scope();
                const ctrl = scope?.subscrCtrl;

                if (!ctrl) {
                    console.warn("⚠️ Angular scope or channelGridCtrl not found.");
                    return;
                }

                ctrl.selectedBundles = [];
                addedExtraPproductBundles.querySelectorAll('.content-container').forEach(card => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const bundle = scope.toShowAssignedBundles.assignPartnerProductAddOnsBundles.find(b => parseInt(b.id) === id);

                    if (bundle) {
                        ctrl.selectedBundles.push(bundle);
                        // console.log(`📦 Added to selectedBundles: ID = ${id}`);
                    }
                });

                // console.log("📊 Final selectedBundles list:", ctrl.selectedBundles);
                scope.$applyAsync();
            }

            function setupSearch(inputId, containerId) {
                const input = document.getElementById(inputId);
                const container = document.getElementById(containerId);

                if (!input || !container) {
                    console.warn(`🔍 Search setup skipped for: ${inputId}`);
                    return;
                }

                input.addEventListener('input', () => {
                    const query = input.value.toLowerCase();
                    const cards = container.querySelectorAll('.content-container');
                    // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        const match = text.includes(query);
                        card.classList.toggle('hidden', !match);
                    });
                });
            }

            setupSearch('searchExtraPproductAvailable', 'availableExtraPproductBundles');
            setupSearch('searchExtraPproductAdded', 'addedExtraPproductBundles');

        }

        // --------------------------------------------- Partner Products Drag and Drop END --------------------------------------------//

        // remove from assigned subscription
        this.removeBundle = function (bundle, key) {
            const spanEl = document.getElementsByClassName('bundle-title');
            for (let i = 0; i < spanEl.length; i++) {
                const divEl = spanEl[i].parentElement;
                const divId = divEl.getAttribute('data-id');
                if (divEl && divId == bundle.id) {
                    divEl.remove();
                    if (key == 'channel') {
                        scope.channlContentSet.channelId = scope.channlContentSet.channelId.filter(o => parseInt(o.id) !== parseInt(bundle.id));
                        this.getChannels();
                    } else if (key == 'levent') {
                        scope.liveEventContentSet.lEvent = scope.liveEventContentSet.lEvent.filter(l => parseInt(l.id) !== parseInt(bundle.id));
                        this.getLiveEventSets();
                    } else if (key == 'vod') {
                        scope.scope.vodContentSet.vodSet = scope.scope.vodContentSet.vodSet.filter(v => parseInt(v.id) !== parseInt(bundle.id));
                        this.getVodSets();
                    } else if (key == 'tvshow') {
                        scope.tvShowContentSet.tvShows = scope.tvShowContentSet.tvShows.filter(t => parseInt(t.id) !== parseInt(bundle.id));
                        this.getTvShowSets();
                    } else if (key == 'pProduct') {
                        scope.pProductContentSet.pProductSet = scope.pProductContentSet.pProductSet.filter(p => parseInt(p.id) !== parseInt(bundle.id));
                        this.getPartnerProduct();
                    }
                }
            }
        }

        // Save Subscription Data
        this.saveSubscription = function ($event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            const conditonalRules = {
                subsRule: scope.rule.subsRule,
                contentRule: scope.rule.contentRule,
                accessoriesRule: scope.rule.accessories,
            };

            const additonaDevicePrices = {
                devices: scope.deviceInputs.devices
            };

            scope.subscriptionData.org_settings ??= "";

            const payload = {
                organization_id: id,
                subscriptionData: scope.subscriptionData,
                devicePrices: additonaDevicePrices,
                conditionRules: conditonalRules,
                channelDataSet: scope.channlContentSet.channelId,
                channelAddOnsData: scope.channlContentAddOnsSet.channelAddOnsId,
                liveEventData: scope.liveEventContentSet.lEvent,
                liveEventAddOnsData: scope.liveEventContentAddOnsSet.lEventAddOns,
                vodData: scope.vodContentSet.vodSet,
                vodAddOnsData: scope.vodContentSetAddOns.vodContentAddOns,
                tvShowContentSet: scope.tvShowContentSet.tvShows,
                tvShowAddOnsContentSet: scope.tvShowAddOnsContentSet.tvShowAddOns,
                accessories: scope.accessoriesContentSet.accessories,
                partnerProduct: scope.pProductContentSet.pProductSet,
                extraPartnerProduct: scope.extraPproductContentSet.pProductContentAddOns
            };

            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/add'),
                // scope.subscriptionData,
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/monetization-plan/subscription?id=${id}`;
                    }, 650);
                }
            );
        }


        // Save Subscription Data
        this.editSubscription = function ($event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');
            const editRecId = document.getElementById('plan-edit-id')?.value;

            const conditonalRules = {
                subsRule: scope.rule.subsRule,
                contentRule: scope.rule.contentRule,
                accessoriesRule: scope.rule.accessories,
            };

            const additonaDevicePrices = {
                devices: scope.deviceInputs.devices
            };

            const payload = {
                organization_id: id,
                subscriptionData: scope.subscriptionData,
                devicePrices: additonaDevicePrices,
                conditionRules: conditonalRules,
                channelDataSet: scope.channlContentSet.channelId,
                channelAddOnsData: scope.channlContentAddOnsSet.channelAddOnsId,
                liveEventData: scope.liveEventContentSet.lEvent,
                liveEventAddOnsData: scope.liveEventContentAddOnsSet.lEventAddOns,
                vodData: scope.vodContentSet.vodSet,
                vodAddOnsData: scope.vodContentSetAddOns.vodContentAddOns,
                tvShowContentSet: scope.tvShowContentSet.tvShows,
                tvShowAddOnsContentSet: scope.tvShowAddOnsContentSet.tvShowAddOns,
                accessories: scope.accessoriesContentSet.accessories,
                partnerProduct: scope.pProductContentSet.pProductSet,
                extraPartnerProduct: scope.extraPproductContentSet.pProductContentAddOns
            };

            // console.log(payload);


            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/edit/' + editRecId),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Monitization Plan Subscription updated successfully');
                    setTimeout(() => {
                        window.location.href = `${appUrl}admin/monetization-plan/subscription?id=${id}`;
                    }, 200);
                }
            );
        }


        // ==============================***********************************==============================
        // fetch details code
        // ==============================***********************************==============================

        // get data from org monetization planss table
        this.fetchMontznPlanData = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getPlanssData(response.data.data);
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };


        // get record for edit page
        function getPlanssData(data) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            // console.log(targetRcrdId);

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));

            if (record) {
                if (localScope && localScope.subscrCtrl) {

                    const updateModel = () => {
                        scope.subscriptionData = {
                            subs_name: record.subscription_name,
                            identifier: record.subscription_identifier,
                            select_platform: record.platforms,
                            org_settings: record.use_org_settings == 1,
                            subs_length: record.subscription_length,
                            payment_method: record.subscription_type,
                            currency: record.currency,
                            subs_price: record.subscription_price,
                            is_autopay: record.autopay,
                            is_advertise: record.advertising,
                            subs_devices: record.subscription_devices,
                            subs_time_type: record.subs_length_time_type,
                            subs_unlimited_time: record.subs_length_time_type == 'unlimited',
                            organization_id: record.organization_id,
                            totalDevicesPrice: record.total_price
                        };

                        scope.channlContentSet = {
                            channelId: record.content_sets ? record.content_sets.channels : []
                        }

                        scope.channlContentAddOnsSet = {
                            channelAddOnsId: record.content_sets ? record.content_sets.channelAddOns : []
                        }

                        scope.liveEventContentSet = {
                            lEvent: record.content_sets ? record.content_sets.lEvents : []
                        }

                        scope.liveEventContentAddOnsSet = {
                            lEventAddOns: record.content_sets ? record.content_sets.lEventAddOns : []
                        }

                        scope.tvShowContentSet = {
                            tvShows: record.content_sets ? record.content_sets.tvShows : []
                        }

                        scope.tvShowAddOnsContentSet = {
                            tvShowAddOns: record.content_sets ? record.content_sets.tvShowAddOns : []
                        }

                        scope.vodContentSet = {
                            vodSet: record.content_sets ? record.content_sets.vods : []
                        }

                        scope.vodContentSetAddOns = {
                            vodContentAddOns: record.content_sets ? record.content_sets.vodAddOns : []
                        }

                        scope.accessoriesContentSet = {
                            accessories: record.accessories
                        }

                        scope.pProductContentSet = {
                            pProductSet: record.partnerProduct
                        }

                        scope.extraPproductContentSet = {
                            pProductContentAddOns: record.extraPartnerProduct
                        }

                        scope.rule = {
                            subsRule: record.conditional_subscriptions,
                            contentRule: record.conditional_content_addons,
                            accessories: record.conditional_accessories
                        }

                        try {
                            if (typeof record.additional_device_price === 'string') {
                                scope.deviceInputs.devices = JSON.parse(record.additional_device_price) ?? "";
                            } else {
                                scope.deviceInputs.devices = record.additional_device_price ?? "";
                            }
                        } catch (e) {
                            console.error("Failed to parse additional_device_price JSON:", record.additional_device_price, e);
                            scope.deviceInputs.devices = "";
                        }
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Subscrition Plan found with ID: ${targetRcrdId}`);
            }
        }

        if (scope.isEditMode == true) {
            this.fetchMontznPlanData();
        }

        // ==============================***********************************==============================
        // open side panel code
        // ==============================***********************************==============================

        this.addData = function ($event) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const openUrl = `${appUrl}admin/monitization-plan/subscription/add` + '?id=' + id;
            window.location.href = openUrl;
        }

        this.editData = function (record) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const id = urlParams.get('id');

            const openUrl = `${appUrl}admin/monitization-plan/subscription/edit/` + record.id + '?id=' + id;
            window.location.href = openUrl;
        }

        function readAsUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var $img = $('#image');
                    $img.one('load', function () {
                        $('#modal').modal('show');
                    })
                    $img.attr('src', e.target.result);
                    // document.getElementById('image').src = e.target.result;
                };
                // reader.onloadend = function (e) {
                //     $('#modal').modal('show');
                // };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // -------------------------------- Partner Product Edit Modal Image -------------------------------- //
        this.prtnrProductEditModalImg = function () {
            $(document).ready(function () {
                /*
                 * Thumb Image Upload Part
                 */
                var image = document.getElementById('image');
                // console.log(image);
                $(document).on('change', '.uploadImg', function (e) {
                    var videoItem = $(this).data('video-index');
                    console.log("Image : ", videoItem);
                    scope.errors = {};
                    var ValidImageTypes = ['image/jpeg', 'image/png'];
                    var files = e.target.files;
                    var fileType = files[0].type;
                    // console.log(ValidImageTypes, files, fileType);

                    if ($.inArray(fileType, ValidImageTypes) < 0) {
                        scope.$apply();

                        // BEGIN : To show invalid error message in the croppre box
                        $('#modal').modal('show');
                        $('.crop-body').hide();
                        $('#submit-image').hide();
                        $('.error_msg')
                            .show()
                            .text(
                                'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                            );
                        // END : To show invalid error message in the croppre box
                        return;
                    }
                    $('.crop-body').show();
                    console.log($('.crop-body').show());
                    var videoIndex = e.target.getAttribute('data-video-index');
                    $('#modal .video-index').val(videoIndex);
                    readAsUrl(this);
                });

                var cropBoxData;
                var canvasData;
                var cropper;

                $(document).on('show.bs.modal', '#modal', function () {
                    // By default hide the error and show submit button when popup opens, then based on the validation we hide/show the details in the same popup
                    $('#submit-image').show();
                    $('.error_msg').hide();

                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                        console.log(cropper);
                    }

                    setTimeout(function () {
                        cropper = new Cropper(image, {
                            autoCropArea: 1,
                            viewMode: 1,
                            aspectRatio: 355 / 200,
                            preview: '.img-preview',
                            cropBoxResizable: false,
                            minCropBoxWidth: 355,
                            minCropBoxHeight: 200,
                            autoCrop: true,
                            dragCrop: false,
                            mouseWheelZoom: false,
                            resizable: false,
                            ready: function () {
                                //Should set crop box data first here
                                var config = { left: 0, top: 0, width: 355, height: 200 };
                                cropper.setCropBoxData(config).setCanvasData(canvasData);
                            }
                        });
                        console.log(cropper);
                    }, 300);
                });
                $(document).on('hidden.bs.modal', '#modal', function () {
                    $('.uploadImg').val('');
                    $('#image').attr('src', '');
                    // document.getElementsByClassName('uploadImg')[0].value = '';
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    $('#submit-image').prop('disabled', false);
                });
                $(document).on(
                    'click',
                    '#submit-image',
                    requestFactory.access_token,
                    function () {
                        cropBoxData = cropper.getCropBoxData();
                        console.log(cropBoxData);

                        canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
                            var formData = new FormData();
                            formData.append('module', 'video');
                            formData.append('size', 'thumb');
                            formData.append('image', blob);
                            $('.crop-body').hide();
                            $('.loader-container').show();
                            $('#submit-image').prop('disabled', true);
                            $.ajax(
                                $('meta[name="base-api-url"]').attr('content') + '/organization/partner-product/thumbnail',
                                {
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function (request) {
                                        request.setRequestHeader(
                                            'Authorization',
                                            'Bearer ' + requestFactory.access_token
                                        );
                                    },
                                    success(data) {
                                        console.log("Data : ", data);
                                        var videoIndex = $('#modal').val();
                                        $('.uploaded_img').attr('src', data.info);
                                        $('.uploaded_img').show();

                                        scope.extraPproductContentSet.prtnrProductModalData.product_image = data.info;
                                        scope.$apply();
                                        $('.loader-container').hide();
                                        $('#modal').modal('hide');
                                    },
                                    error() {
                                        $('.loader-container').hide();
                                        $('.error_msg')
                                            .show()
                                            .text(
                                                'Please upload bigger image, click cancel to continue'
                                            );
                                    }
                                }
                            );
                        }, 'image/jpeg');
                    }
                );

            });
        }
        // -------------------------------- Partner Product Edit Modal Image -------------------------------- //

        // -------------------------------- Channel Edit Modal Image -------------------------------- //
        this.chnlEditModalImg = function () {
            $(document).ready(function () {
                /*
                 * Post Image Upload Part
                 */
                var posterImage = document.getElementById('cover_image');
                $(document).on('change', '.uploadPosterImg', function (e) {
                    var videoItem = $(this).data('video-index');
                    scope.errors[videoItem] = {};
                    var ValidImageTypes = ['image/jpeg', 'image/png'];
                    var files = e.target.files;
                    var fileType = files[0].type;
                    if ($.inArray(fileType, ValidImageTypes) < 0) {
                        scope.$apply();
                        // BEGIN : To show invalid error message in the croppre box
                        $('#poster_modal').modal('show');
                        $('.crop-body').hide();
                        $('#submit_cover_image').hide();
                        $('.poster_error_msg')
                            .show()
                            .text(
                                'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                            );
                        // END : To show invalid error message in the croppre box
                        return;
                    }
                    $('.crop-body').show();

                    var videoIndex = e.target.getAttribute('data-video-index');
                    $('#poster_modal .video-index').val(videoIndex);
                    readAsPosterUrl(this, videoIndex);
                });
                var cropBoxImgData;
                var canvasImgData;
                var cropperImg;

                $(document).on('show.bs.modal', '#poster_modal', function () {
                    $('#submit_cover_image').show();
                    $('.poster_error_msg').hide();
                    setTimeout(function () {
                        cropperImg = new Cropper(posterImage, {
                            autoCropArea: 1,
                            viewMode: 3,
                            aspectRatio: 1180 / 600,
                            preview: '.poster_img-preview',
                            cropBoxResizable: false,
                            minCropBoxWidth: 1180,
                            minCropBoxHeight: 600,
                            autoCrop: true,
                            dragCrop: false,
                            mouseWheelZoom: false,
                            resizable: false,
                            ready: function () {
                                //Should set crop box data first here
                                var config = { left: 0, top: 0, width: 1180, height: 600 };
                                cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
                            }
                        });
                    }, 500);
                });
                $(document).on('hidden.bs.modal', '#poster_modal', function () {
                    document.getElementsByClassName('uploadPosterImg')[0].value = '';
                    $('#submit_cover_image').prop('disabled', false);
                    cropperImg.destroy();
                });
                $(document).on(
                    'click',
                    '#submit_cover_image',
                    requestFactory.access_token,
                    function () {
                        cropBoxImgData = cropperImg.getCropBoxData();
                        canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
                            var formImgData = new FormData();
                            formImgData.append('module', 'video');
                            formImgData.append('size', 'poster');
                            formImgData.append('image', blob);
                            $('.crop-body').hide();
                            $('.poster_loader-container').show();
                            $('#submit_cover_image').prop('disabled', true);
                            $.ajax(
                                $('meta[name="base-api-url"]').attr('content') + '/channel/content-set/poster',
                                {
                                    method: 'POST',
                                    data: formImgData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function (request) {
                                        request.setRequestHeader(
                                            'Authorization',
                                            'Bearer ' + requestFactory.access_token
                                        );
                                    },
                                    success(data) {
                                        var videoIndex = $('#poster_modal').val();
                                        $('.uploaded_poster_img').attr('src', data.info);
                                        $('.uploaded_poster_img').show();
                                        scope.channlContentAddOnsSet.chnlModalData.cover_image = data.info;
                                        scope.$apply();
                                        $('.poster_loader-container').hide();
                                        $('#poster_modal').modal('hide');
                                    },
                                    error() {
                                        $('.poster_loader-container').hide();
                                        $('.poster_error_msg')
                                            .show()
                                            .text(
                                                'Please upload bigger image, click cancel to continue'
                                            );
                                    }
                                }
                            );
                        }, 'image/jpeg');
                    }
                );
            });

            function readAsPosterUrl(input, videoIndex) {
                if (input.files && input.files[0]) {
                    var readerImg = new FileReader();
                    readerImg.onload = function (e) {
                        document.getElementById('cover_image').src = e.target.result;
                    };
                    readerImg.onloadend = function (e) {
                        $('#poster_modal').modal('show');
                    };
                    readerImg.readAsDataURL(input.files[0]);
                }
            }
        }
        // -------------------------------- Channel Edit Modal Image -------------------------------- //

        // -------------------------------- Channel Edit Modal Image -------------------------------- //
        this.lEventEditModalImg = function () {
            $(document).ready(function () {
                /*
                 * Post Image Upload Part
                 */
                var posterImage = document.getElementById('cover_image');
                $(document).on('change', '.uploadPosterImg', function (e) {
                    var videoItem = $(this).data('video-index');
                    scope.errors[videoItem] = {};
                    var ValidImageTypes = ['image/jpeg', 'image/png'];
                    var files = e.target.files;
                    var fileType = files[0].type;
                    if ($.inArray(fileType, ValidImageTypes) < 0) {
                        scope.$apply();
                        // BEGIN : To show invalid error message in the croppre box
                        $('#poster_modal').modal('show');
                        $('.crop-body').hide();
                        $('#submit_cover_image').hide();
                        $('.poster_error_msg')
                            .show()
                            .text(
                                'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                            );
                        // END : To show invalid error message in the croppre box
                        return;
                    }
                    $('.crop-body').show();

                    var videoIndex = e.target.getAttribute('data-video-index');
                    $('#poster_modal .video-index').val(videoIndex);
                    readAsPosterUrl(this, videoIndex);
                });
                var cropBoxImgData;
                var canvasImgData;
                var cropperImg;

                $(document).on('show.bs.modal', '#poster_modal', function () {
                    $('#submit_cover_image').show();
                    $('.poster_error_msg').hide();
                    setTimeout(function () {
                        cropperImg = new Cropper(posterImage, {
                            autoCropArea: 1,
                            viewMode: 3,
                            aspectRatio: 1180 / 600,
                            preview: '.poster_img-preview',
                            cropBoxResizable: false,
                            minCropBoxWidth: 1180,
                            minCropBoxHeight: 600,
                            autoCrop: true,
                            dragCrop: false,
                            mouseWheelZoom: false,
                            resizable: false,
                            ready: function () {
                                //Should set crop box data first here
                                var config = { left: 0, top: 0, width: 1180, height: 600 };
                                cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
                            }
                        });
                    }, 500);
                });
                $(document).on('hidden.bs.modal', '#poster_modal', function () {
                    document.getElementsByClassName('uploadPosterImg')[0].value = '';
                    $('#submit_cover_image').prop('disabled', false);
                    cropperImg.destroy();
                });
                $(document).on(
                    'click',
                    '#submit_cover_image',
                    requestFactory.access_token,
                    function () {
                        cropBoxImgData = cropperImg.getCropBoxData();
                        canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
                            var formImgData = new FormData();
                            formImgData.append('module', 'video');
                            formImgData.append('size', 'poster');
                            formImgData.append('image', blob);
                            $('.crop-body').hide();
                            $('.poster_loader-container').show();
                            $('#submit_cover_image').prop('disabled', true);
                            $.ajax(
                                $('meta[name="base-api-url"]').attr('content') + '/live-event/content-set/poster',
                                {
                                    method: 'POST',
                                    data: formImgData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function (request) {
                                        request.setRequestHeader(
                                            'Authorization',
                                            'Bearer ' + requestFactory.access_token
                                        );
                                    },
                                    success(data) {
                                        var videoIndex = $('#poster_modal').val();
                                        $('.uploaded_poster_img').attr('src', data.info);
                                        $('.uploaded_poster_img').show();
                                        scope.liveEventContentAddOnsSet.lEventModalData.cover_image = data.info;
                                        scope.$apply();
                                        $('.poster_loader-container').hide();
                                        $('#poster_modal').modal('hide');
                                    },
                                    error() {
                                        $('.poster_loader-container').hide();
                                        $('.poster_error_msg')
                                            .show()
                                            .text(
                                                'Please upload bigger image, click cancel to continue'
                                            );
                                    }
                                }
                            );
                        }, 'image/jpeg');
                    }
                );
            });

            function readAsPosterUrl(input, videoIndex) {
                if (input.files && input.files[0]) {
                    var readerImg = new FileReader();
                    readerImg.onload = function (e) {
                        document.getElementById('cover_image').src = e.target.result;
                    };
                    readerImg.onloadend = function (e) {
                        $('#poster_modal').modal('show');
                    };
                    readerImg.readAsDataURL(input.files[0]);
                }
            }
        }
        // -------------------------------- Channel Edit Modal Image -------------------------------- //

        // partner product content set edit page data
        this.fetchPrtnrPrdctData = function (id) {
            requestFactory.post(
                requestFactory.getUrl('organizations/partner-product/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getPrtnrProdctData(response.data.data, id);
                        this.prtnrProductEditModalImg();
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get record for edit page
        function getPrtnrProdctData(data, id) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                // console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            // const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            const targetRcrdId = id;

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));
            console.log("Record : ", record);

            if (record) {
                if (localScope && localScope.subscrCtrl) {
                    console.log(localScope);
                    const updateModel = () => {
                        scope.extraPproductContentSet.prtnrProductModalData = [];
                        scope.extraPproductContentSet.prtnrProductModalData = {
                            id: record.id,
                            product_name: record.product_name,
                            product_image: record.product_image,
                            product_description: record.product_description,
                            product_id: record.product_id,
                            product_length: record.length,
                            product_price: record.price,
                            organization_id: record.organization_id
                        }
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Channel found with ID: ${targetRcrdId}`);
            }
        }

        // -------------------------------- Channel Edit Modal Data Fetch Start -------------------------------- //
        // channel add on content set edit page data
        this.fetchChnnlData = function (id) {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getChannelData(response.data.data, id);
                        this.chnlEditModalImg();
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get record for edit page
        function getChannelData(data, id) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                // console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            // const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            const targetRcrdId = id;

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));
            console.log("Record : ", record);

            if (record) {
                if (localScope && localScope.subscrCtrl) {
                    // console.log(localScope);
                    const updateModel = () => {
                        scope.channlContentAddOnsSet.chnlModalData = [];
                        scope.channlContentAddOnsSet.chnlModalData = {
                            id: record.id,
                            name: record.name,
                            description: record.description,
                            monitization_type: record.monitization_type,
                            length: record.length,
                            rent_payment_method: record.payment_method,
                            payment_method: record.payment_method,
                            currency: record.currency,
                            autopay: record.autopay,
                            price: record.price,
                            rent_price: record.rent_price,
                            period: parseInt(record.period, 10),
                            period_type: record.period_type,
                            subs_length: record.subs_length,
                            subs_length_type: record.subs_length_type,
                            subs_unlimited_time: record.subs_length_type_,
                            channels: record.channels,
                            organization_id: record.organization_id
                        }
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Channel found with ID: ${targetRcrdId}`);
            }
        }
        // -------------------------------- Channel Edit Modal Data Fetch END -------------------------------- //

        // -------------------------------- Live Event Edit Modal Data Fetch START -------------------------------- //
        // live event add on content set edit modal data
        this.fetchLiveEvents = function (id) {
            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getLiveEventData(response.data.data, id);
                        this.lEventEditModalImg();
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get live event record for edit page
        function getLiveEventData(data, id) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                // console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            // const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            const targetRcrdId = id;

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));
            console.log("Record : ", record);

            if (record) {
                if (localScope && localScope.subscrCtrl) {
                    console.log(localScope);
                    const updateModel = () => {
                        scope.liveEventContentAddOnsSet.lEventModalData = [];
                        scope.liveEventContentAddOnsSet.lEventModalData = {
                            id: record.id,
                            name: record.name,
                            cover_image: record.cover_image,
                            description: record.description,
                            monitization_type: record.monitization_type,
                            payment_method: record.payment_method,
                            price: record.price,
                            live_event_channels: record.live_event_channels
                        }
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Channel found with ID: ${targetRcrdId}`);
            }
        }
        // -------------------------------- Live Event Edit Modal Data Fetch END -------------------------------- //


        // -------------------------------- VOD Edit Modal Image -------------------------------- //
        this.vodEditModalImg = function () {
            $(document).ready(function () {
                /*
                 * Post Image Upload Part
                 */
                var posterImage = document.getElementById('cover_image');
                $(document).on('change', '.uploadPosterImg', function (e) {
                    var videoItem = $(this).data('video-index');
                    scope.errors[videoItem] = {};
                    var ValidImageTypes = ['image/jpeg', 'image/png'];
                    var files = e.target.files;
                    var fileType = files[0].type;
                    if ($.inArray(fileType, ValidImageTypes) < 0) {
                        scope.$apply();
                        // BEGIN : To show invalid error message in the croppre box
                        $('#poster_modal').modal('show');
                        $('.crop-body').hide();
                        $('#submit_cover_image').hide();
                        $('.poster_error_msg')
                            .show()
                            .text(
                                'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                            );
                        // END : To show invalid error message in the croppre box
                        return;
                    }
                    $('.crop-body').show();

                    var videoIndex = e.target.getAttribute('data-video-index');
                    $('#poster_modal .video-index').val(videoIndex);
                    readAsPosterUrl(this);
                });
                var cropBoxImgData;
                var canvasImgData;
                var cropperImg;

                $(document).on('show.bs.modal', '#poster_modal', function () {
                    $('#submit_cover_image').show();
                    $('.poster_error_msg').hide();
                    setTimeout(function () {
                        cropperImg = new Cropper(posterImage, {
                            autoCropArea: 1,
                            viewMode: 3,
                            aspectRatio: 1180 / 600,
                            preview: '.poster_img-preview',
                            cropBoxResizable: false,
                            minCropBoxWidth: 1180,
                            minCropBoxHeight: 600,
                            autoCrop: true,
                            dragCrop: false,
                            mouseWheelZoom: false,
                            resizable: false,
                            ready: function () {
                                //Should set crop box data first here
                                var config = { left: 0, top: 0, width: 1180, height: 600 };
                                cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
                            }
                        });
                    }, 500);
                });
                $(document).on('hidden.bs.modal', '#poster_modal', function () {
                    document.getElementsByClassName('uploadPosterImg')[0].value = '';
                    $('#submit_cover_image').prop('disabled', false);
                    cropperImg.destroy();
                });
                $(document).on(
                    'click',
                    '#submit_cover_image',
                    requestFactory.access_token,
                    function () {
                        cropBoxImgData = cropperImg.getCropBoxData();
                        canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
                            var formImgData = new FormData();
                            formImgData.append('module', 'video');
                            formImgData.append('size', 'poster');
                            formImgData.append('image', blob);
                            $('.crop-body').hide();
                            $('.poster_loader-container').show();
                            $('#submit_cover_image').prop('disabled', true);
                            $.ajax(
                                $('meta[name="base-api-url"]').attr('content') + '/live-event/content-set/poster',
                                {
                                    method: 'POST',
                                    data: formImgData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function (request) {
                                        request.setRequestHeader(
                                            'Authorization',
                                            'Bearer ' + requestFactory.access_token
                                        );
                                    },
                                    success(data) {
                                        var videoIndex = $('#poster_modal').val();
                                        $('.uploaded_poster_img').attr('src', data.info);
                                        $('.uploaded_poster_img').show();
                                        scope.vodContentSetAddOns.vodModalData.cover_image = data.info;
                                        scope.$apply();
                                        $('.poster_loader-container').hide();
                                        $('#poster_modal').modal('hide');
                                    },
                                    error() {
                                        $('.poster_loader-container').hide();
                                        $('.poster_error_msg')
                                            .show()
                                            .text(
                                                'Please upload bigger image, click cancel to continue'
                                            );
                                    }
                                }
                            );
                        }, 'image/jpeg');
                    }
                );
            });

            function readAsPosterUrl(input) {
                if (input.files && input.files[0]) {
                    var readerImg = new FileReader();
                    readerImg.onload = function (e) {
                        document.getElementById('cover_image').src = e.target.result;
                    };
                    readerImg.onloadend = function (e) {
                        $('#poster_modal').modal('show');
                    };
                    readerImg.readAsDataURL(input.files[0]);
                }
            }
        }
        // -------------------------------- VOD Edit Modal Image -------------------------------- //

        // -------------------------------- TV Show Edit Modal Image -------------------------------- //
        this.tvShowEditModalImg = function () {
            $(document).ready(function () {
                /*
                 * Post Image Upload Part
                 */
                var posterImage = document.getElementById('cover_image');
                $(document).on('change', '.uploadPosterImg', function (e) {
                    var videoItem = $(this).data('video-index');
                    scope.errors[videoItem] = {};
                    var ValidImageTypes = ['image/jpeg', 'image/png'];
                    var files = e.target.files;
                    var fileType = files[0].type;
                    if ($.inArray(fileType, ValidImageTypes) < 0) {
                        scope.$apply();
                        // BEGIN : To show invalid error message in the croppre box
                        $('#poster_modal').modal('show');
                        $('.crop-body').hide();
                        $('#submit_cover_image').hide();
                        $('.poster_error_msg')
                            .show()
                            .text(
                                'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
                            );
                        // END : To show invalid error message in the croppre box
                        return;
                    }
                    $('.crop-body').show();

                    var videoIndex = e.target.getAttribute('data-video-index');
                    $('#poster_modal .video-index').val(videoIndex);
                    readAsPosterUrl(this, videoIndex);
                });
                var cropBoxImgData;
                var canvasImgData;
                var cropperImg;

                $(document).on('show.bs.modal', '#poster_modal', function () {
                    $('#submit_cover_image').show();
                    $('.poster_error_msg').hide();
                    setTimeout(function () {
                        cropperImg = new Cropper(posterImage, {
                            autoCropArea: 1,
                            viewMode: 3,
                            aspectRatio: 1180 / 600,
                            preview: '.poster_img-preview',
                            cropBoxResizable: false,
                            minCropBoxWidth: 1180,
                            minCropBoxHeight: 600,
                            autoCrop: true,
                            dragCrop: false,
                            mouseWheelZoom: false,
                            resizable: false,
                            ready: function () {
                                //Should set crop box data first here
                                var config = { left: 0, top: 0, width: 1180, height: 600 };
                                cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
                            }
                        });
                    }, 500);
                });
                $(document).on('hidden.bs.modal', '#poster_modal', function () {
                    document.getElementsByClassName('uploadPosterImg')[0].value = '';
                    $('#submit_cover_image').prop('disabled', false);
                    cropperImg.destroy();
                });
                $(document).on(
                    'click',
                    '#submit_cover_image',
                    requestFactory.access_token,
                    function () {
                        cropBoxImgData = cropperImg.getCropBoxData();
                        canvasImgData = cropperImg.getCroppedCanvas().toBlob(function (blob) {
                            var formImgData = new FormData();
                            formImgData.append('module', 'video');
                            formImgData.append('size', 'poster');
                            formImgData.append('image', blob);
                            $('.crop-body').hide();
                            $('.poster_loader-container').show();
                            $('#submit_cover_image').prop('disabled', true);
                            $.ajax(
                                $('meta[name="base-api-url"]').attr('content') + '/live-event/content-set/poster',
                                {
                                    method: 'POST',
                                    data: formImgData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function (request) {
                                        request.setRequestHeader(
                                            'Authorization',
                                            'Bearer ' + requestFactory.access_token
                                        );
                                    },
                                    success(data) {
                                        var videoIndex = $('#poster_modal').val();
                                        $('.uploaded_poster_img').attr('src', data.info);
                                        $('.uploaded_poster_img').show();
                                        scope.tvShowAddOnsContentSet.tvShowModalData.cover_image = data.info;
                                        scope.$apply();
                                        $('.poster_loader-container').hide();
                                        $('#poster_modal').modal('hide');
                                    },
                                    error() {
                                        $('.poster_loader-container').hide();
                                        $('.poster_error_msg')
                                            .show()
                                            .text(
                                                'Please upload bigger image, click cancel to continue'
                                            );
                                    }
                                }
                            );
                        }, 'image/jpeg');
                    }
                );
            });

            function readAsPosterUrl(input, videoIndex) {
                if (input.files && input.files[0]) {
                    var readerImg = new FileReader();
                    readerImg.onload = function (e) {
                        document.getElementById('cover_image').src = e.target.result;
                    };
                    readerImg.onloadend = function (e) {
                        $('#poster_modal').modal('show');
                    };
                    readerImg.readAsDataURL(input.files[0]);
                }
            }
        }
        // -------------------------------- TV Show Edit Modal Image -------------------------------- //


        // -------------------------------- VOD Edit Modal Data Fetch START -------------------------------- //
        // vod add on content set edit modal data
        this.fetchVods = function (id) {
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getVodData(response.data.data, id);
                        this.vodEditModalImg();
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get vod record for edit page
        function getVodData(data, id) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                // console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            // const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            const targetRcrdId = id;

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));
            console.log("Record : ", record);

            if (record) {
                if (localScope && localScope.subscrCtrl) {
                    console.log(localScope);
                    const updateModel = () => {
                        scope.vodContentSetAddOns.vodModalData = [];
                        scope.vodContentSetAddOns.vodModalData = {
                            id: record.id,
                            organization_id: record.organization_id,
                            cover_image: record.cover_image,
                            name: record.name,
                            description: record.description,
                            monitization_type_buy: parseInt(record.monitization_type_buy, 10),
                            payment_method_buy: record.payment_method_buy,
                            buy_price: record.buy_price,
                            monitization_type_rent: parseInt(record.monitization_type_rent, 10),
                            payment_method_rent: record.payment_method_rent,
                            rent_price: record.rent_price,
                            period: parseInt(record.period, 10),
                            period_type: record.period_type,
                            currency: record.currency,
                            vods: record.vods
                        }

                        console.log(scope.vodContentSetAddOns.vodModalData);

                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Channel found with ID: ${targetRcrdId}`);
            }
        }
        // -------------------------------- VOD Edit Modal Data Fetch END -------------------------------- //

        // -------------------------------- TV Show Edit Modal Data Fetch START -------------------------------- //
        // tv show add on content set edit modal data
        this.fetchTvShow = function (id) {
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        getTvShowData(response.data.data, id);
                        this.tvShowEditModalImg();
                    } else {
                        console.warn("Invalid data format from api access:", response);
                    }
                }
            );
        };

        // get tv show record for edit page
        function getTvShowData(data, id) {
            const editPgElement = document.getElementById("edit-form-div");

            if (!editPgElement) {
                // console.error(`Element with ID ${editPgElement} not found.`);
                return;
            }

            const localScope = angular.element(editPgElement).scope();
            // const targetRcrdId = document.getElementById("plan-edit-id")?.value;
            const targetRcrdId = id;

            if (!targetRcrdId) {
                console.warn("Target Record ID not found.");
                return;
            }

            const record = data.find(o => String(o.id) === String(targetRcrdId));
            console.log("Record : ", record);

            if (record) {
                if (localScope && localScope.subscrCtrl) {
                    console.log(localScope);
                    const updateModel = () => {
                        scope.tvShowAddOnsContentSet.tvShowModalData = [];
                        scope.tvShowAddOnsContentSet.tvShowModalData = {
                            id: record.id,
                            name: record.name,
                            description: record.description,
                            monitization_type_buy: record.monitization_type_buy,
                            monitization_type_rent: record.monitization_type_rent,
                            length: record.length,
                            payment_method_buy: record.payment_method_buy,
                            payment_method_rent: record.payment_method_rent,
                            rent_price: record.rent_price,
                            buy_price: record.buy_price,
                            period: parseInt(record.period),
                            period_type: record.period_type,
                            currency: record.currency,
                            tvshows: record.tv_shows,
                            tvshow_seasons: record.tv_show_seasons,
                            tvshow_season_episodes: record.tv_show_episodes,

                        }

                        console.log("Modal Data : ", scope.tvShowAddOnsContentSet.tvShowModalData);

                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No Channel found with ID: ${targetRcrdId}`);
            }
        }
        // -------------------------------- TV Show Edit Modal Data Fetch END -------------------------------- //

        // update channel modal data
        this.updateChanlModalData = function (record) {
            requestFactory.post(
                requestFactory.getUrl('channel/content-set/update/' + record.id),
                scope.channlContentAddOnsSet.chnlModalData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        const modal = document.getElementById('channl-editon-modal');
                        if (modal) {
                            $(modal).modal('hide');
                            location.reload();
                        }
                    }, 650);
                }
            );
        }

        // update Live Event modal data
        this.updateLiveEventModalData = function (record) {
            requestFactory.post(
                requestFactory.getUrl('live-event/content-set/update/' + record.id),
                scope.liveEventContentAddOnsSet.lEventModalData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        const modal = document.getElementById('levnt-editon-modal');
                        if (modal) {
                            $(modal).modal('hide');
                            location.reload();
                        }
                    }, 650);
                }
            );
        }

        // update Vod modal data
        this.updateVodModalData = function (record) {
            requestFactory.post(
                requestFactory.getUrl('vod/content-set/update/' + record.id),
                scope.vodContentSetAddOns.vodModalData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        const modal = document.getElementById('vod-editon-modal');
                        if (modal) {
                            $(modal).modal('hide');
                            location.reload();
                        }
                    }, 650);
                }
            );
        }

        // update tv show modal data
        this.updateTvShowModalData = function (record) {
            requestFactory.post(
                requestFactory.getUrl('tv-show/content-set/update/' + record.id),
                scope.vodContentSetAddOns.vodModalData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        const modal = document.getElementById('vod-editon-modal');
                        if (modal) {
                            $(modal).modal('hide');
                            location.reload();
                        }
                    }, 650);
                }
            );
        }

        // update partner product modal data
        this.editPartnerAddONsModalData = function (record) {
            requestFactory.post(
                requestFactory.getUrl('organizations/partner-product/edit/' + record.id),
                scope.extraPproductContentSet.prtnrProductModalData,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', response.message);
                    setTimeout(() => {
                        const modal = document.getElementById('product-editon-modal');
                        if (modal) {
                            $(modal).modal('hide');
                            location.reload();
                        }
                    }, 200);
                }
            );
        }


        // search assigned data
        this.searchAssignedData = function (event) {
            // console.log("Search ");
            const input = document.getElementById('searchAddOnsAvailable');
            const price = document.getElementById('searchPriceAddOnsAvailable');
            const container = document.getElementById('availableChannelAddOnssBundles');

            if (!input || !container) {
                console.warn(`Search setup skipped for: ${'searchAddOnsAvailable'}`);
                return;
            }

            input.addEventListener('input', () => {
                const query = input.value.toLowerCase();
                const cards = container.querySelectorAll('.content-container');
                // console.log(cards);

                // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                cards.forEach(card => {
                    const text = card.innerText.toLowerCase();
                    const match = text.includes(query);
                    card.classList.toggle('hidden', !match);
                });
            });

            price.addEventListener('input', () => {
                const query = price.value.toLowerCase();
                const cards = container.querySelectorAll('.content-container .input-group');
                console.log(cards);

                // console.log(`🔎 Searching for "${query}" in #${containerId}`);

                cards.forEach(card => {
                    const text = card.innerText.toLowerCase();
                    const match = text.includes(query);
                    card.classList.toggle('hidden', !match);
                });
            });
        }


        // scope.$on('afterGetRecords', function (e, data) {
        //     if (angular.isUndefined(scope.searchRecords.is_active)) {
        //         scope.searchRecords.is_active = 'all';
        //     }
        // });

        this.orgWiseMonPlan = function () {
            const currentUrl = window.location.href;
            const urlObj = new URL(currentUrl);
            const IdFromUrl = urlObj.searchParams.get('id');

            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const MonPlan = response.data.data;

                        const filterOrg = MonPlan.filter(org =>
                            Number(org.organization_id) === Number(IdFromUrl)
                        )

                        scope.MonPlanrecords = filterOrg;
                        scope.IdFromUrl = IdFromUrl;
                    }
                }
            );
        }
        this.orgWiseMonPlan();

        this.togglePublishNow = function (record, id) {

            record.is_active = record.is_active ? 0 : 1;

            const payload = {
                is_active: record.is_active
            };

            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplanss/toggle-publish-now/' + id),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Monitization Plan Update.');
                    setTimeout(() => {
                        location.reload();
                    }, 350);
                }
            );
        }

        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        this.getCurrencySymbol = function (code) {
            if (!this.orgCurrency) return '';
            const currency = this.orgCurrency.find(function(c) { return c.short_code === code; });
            return currency ? currency.currency_symbol : '';
        }

        this.fetchOrgCurrency = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/currency/records'),
                { organization_id: scope.orgIdFromUrl },
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data) && response.data.data.length > 0) {
                        self.orgCurrency = response.data.data.map(function(c) {
                            c.short_code = c.currency_code.split(' - ')[0];
                            return c;
                        });
                        // console.log("Organization Currency loaded:", self.orgCurrency);
                    } else {
                        // Fallback to default system currency
                        requestFactory.post(
                            requestFactory.getUrl('organization/payment-service/currency/records'),
                            {}, // Empty payload to get all/system currencies
                            function (fallbackResponse) {
                                if (fallbackResponse && fallbackResponse.data && Array.isArray(fallbackResponse.data.data)) {
                                    self.orgCurrency = fallbackResponse.data.data.map(function(c) {
                                        c.short_code = c.currency_code.split(' - ')[0];
                                        return c;
                                    });
                                    // console.log("System Default Currency loaded (Fallback):", self.orgCurrency);
                                }
                            }
                        );
                    }
                }
            );
        }
        this.fetchOrgCurrency();
    }
];

window.gridControllers = {
    SubscriptionController: SubscriptionController
};
