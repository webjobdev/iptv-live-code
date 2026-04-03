var MonetizationPlanController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, $rootScope) {

        var self = this;
        this.info = {};
        this.addplan = {};
        this.contentset = {};

        // Load initial info
        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info || {};
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('organization/monetizationplan/info'),
                this.defineProperties,
                function (response) {
                    $rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.fetchcontentset = function () {
            requestFactory.post(
                requestFactory.getUrl('organization/monetizationplan/records'),
                this.defineProperties,
                function (response) {
                    // console.log("API response:", response);
                    if (response && response.data && Array.isArray(response.data.data)) {
                        // renderOrganization(response.data.data);
                    } else {
                        console.warn("Invalid data format from fetchcontentset:", response);
                    }
                }
            );
        };

        const razorpayKey = window.RAZORPAY_KEY;
        const username = window.USER_NAME;
        const phone_number = window.USER_PHONE;
        window.startPayment = function (planId, amount, organizationId) {
            if (typeof Razorpay === 'undefined') {
                alert("Payment gateway failed to load. Please try again.");
                return;
            }

            const options = {
                key: razorpayKey,
                amount: amount,
                name: username || "Guest",
                description: "Ip tv solution group payment gateway",
                prefill: {
                    name: username || "Guest",
                    phone: phone_number || "1234567890"
                },
                theme: { color: "#0F408F" },

                handler: function (res) {
                    requestFactory.post(
                        requestFactory.getUrl('organization/payment/create'),
                        {
                            plan_id: planId,
                            amount: amount,
                            organization_id: organizationId,
                            razorpay_payment_id: res.razorpay_payment_id
                        },
                        function (response) {
                            if (response && response.success) {
                                window.location.reload();
                            } else {
                                console.warn('Payment creation failed:', response);
                            }
                        },
                        function (error) {
                            console.error('Error in payment request:', error);
                        },
                        {
                            headers: {
                                'X-CSRF-TOKEN': window.csrfToken
                            },
                            responseType: 'json'
                        }
                    );
                },
                modal: {
                    ondismiss: function () {
                        window.location.reload();
                    }
                }
            };

            // const generateDeviceInfo = async () => {
            //     const deviceInfo = {
            //         userAgent: navigator.userAgent || "Unknown",
            //         identifier: localStorage.getItem('device_id') || crypto.randomUUID(),
            //         ipAddress: "Unknown",
            //         location: {
            //             latitude: null,
            //             longitude: null
            //         }
            //     };

            //     // Save device ID in localStorage if not already present
            //     if (!localStorage.getItem('device_id')) {
            //         localStorage.setItem('device_id', deviceInfo.identifier);
            //     }

            //     // Get Geolocation
            //     if (navigator.geolocation) {
            //         try {
            //             const position = await new Promise((resolve, reject) =>
            //                 navigator.geolocation.getCurrentPosition(resolve, reject)
            //             );
            //             deviceInfo.location.latitude = position.coords.latitude;
            //             deviceInfo.location.longitude = position.coords.longitude;
            //         } catch (e) {
            //             console.warn("Location access denied or failed.");
            //         }
            //     }

            //     // Get IP address
            //     try {
            //         const res = await fetch('https://api.ipify.org?format=json');
            //         const json = await res.json();
            //         deviceInfo.ipAddress = json.ip;
            //     } catch (err) {
            //         console.warn("Unable to fetch IP address.");
            //     }

            //     return deviceInfo;
            // };

            // // Razorpay payment config
            // const options = {
            //     key: razorpayKey,
            //     amount: amount,
            //     name: username || "Guest",
            //     description: "Ip tv solution group payment gateway",
            //     prefill: {
            //         name: username || "Guest",
            //         phone: phone_number || "1234567890"
            //     },
            //     theme: { color: "#0F408F" },

            //     handler: async function (res) {
            //         const deviceInfo = await generateDeviceInfo();

            //         requestFactory.post(
            //             requestFactory.getUrl('organization/payment/create'),
            //             {
            //                 plan_id: planId,
            //                 amount: amount,
            //                 organization_id: organizationId,
            //                 razorpay_payment_id: res.razorpay_payment_id,
            //                 device_info: deviceInfo // send device, ip, location, etc.
            //             },
            //             function (response) {
            //                 if (response && response.success) {
            //                     window.location.reload();
            //                 } else {
            //                     console.warn('Payment creation failed:', response);
            //                 }
            //             },
            //             function (error) {
            //                 console.error('Error in payment request:', error);
            //             },
            //             {
            //                 headers: {
            //                     'X-CSRF-TOKEN': window.csrfToken
            //                 },
            //                 responseType: 'json'
            //             }
            //         );
            //     },

            //     modal: {
            //         ondismiss: function () {
            //             window.location.reload();
            //         }
            //     }
            // };

            const rzp = new Razorpay(options);

            rzp.on('payment.failed', function (response) {
                const failureData = {
                    plan_id: planId,
                    amount: amount,
                    organization_id: organizationId,
                    razorpay_payment_id: response.error.metadata?.payment_id || '',
                    error: response.error.description || 'Unknown error',
                    reason: response.error.reason || 'Unknown reason'
                };

                requestFactory.post(
                    requestFactory.getUrl('organization/payment/failure'),
                    failureData,
                    function (apiResponse) {
                        if (apiResponse && apiResponse.success === true) {
                            // Redirect to a custom failure page
                            window.location.href = '/402';
                        } else {
                            console.warn('Payment failure not acknowledged properly:', apiResponse);
                            window.location.reload();
                        }
                    },
                    function (error) {
                        console.error('Error in payment failure request:', error);
                        window.location.reload();
                    },
                    {
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        responseType: 'json'
                    }
                );
            });

            rzp.open();
        };

        // function renderOrganization(monetizationplan) {
        //     const container = document.getElementById("pricing-container");
        //     container.innerHTML = "";

        //     const targetOrgId = document.getElementById("org-id")?.value;

        //     if (!targetOrgId) {
        //         console.error("Organization ID not found.");
        //         return;
        //     }

        //     // Filter the monetization plans based on the organization ID
        //     const filteredPlans = monetizationplan.filter(monetization => String(monetization.organization_id) === String(targetOrgId));

        //     if (filteredPlans.length === 0) {
        //         console.warn(`No plans found for Organization with ID ${targetOrgId}.`);
        //         return;
        //     }

        //     // Loop through the filtered plans and render them
        //     filteredPlans.forEach((monetization) => {
        //         const contentSet = monetization.content_sets && monetization.content_sets[0];

        //         const card = document.createElement("div");
        //         card.className = "card";

        //         const isGold = monetization.name.toLowerCase() === "gold";
        //         const badgeHTML = isGold
        //             ? `<span class="badge gradient-custom text-white popular-badge px-4 py-2">Popular</span>`
        //             : "";

        //         if (isGold) {
        //             card.classList.add('gold-plan');
        //         }

        //         card.innerHTML = `
        //             ${badgeHTML}
        //             <h3>${monetization.name}</h3>
        //             <div class="price">₹ ${monetization.amount}/mo</div>
        //             <ul class="features">
        //                 <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Description:</small> ${monetization.description}</li><br>
        //                 <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Duration:</small> ${monetization.duration} days</li><br>
        //                 ${contentSet ? `
        //                     <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Video and sound quality:</small> ${contentSet.video_and_sound_quality}</li><br>
        //                     <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Resolution:</small> ${JSON.parse(contentSet.resolution).join(', ')}</li><br>
        //                     <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Supported Devices:</small> ${JSON.parse(contentSet.supported_devices).join(', ')}</li><br>
        //                     <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Download Devices:</small> ${contentSet.download_devices}</li><br>
        //                     <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Ads:</small> ${contentSet.no_ad}</li>
        //                 ` : ''}
        //             </ul>
        //             <input type="hidden" name="id" value="${monetization.id}">
        //             <input type="hidden" name="amount" value="${monetization.amount * 100}">
        //             <input type="hidden" name="organization_id" value="${targetOrgId}">
        //             <button type="button"
        //                 class="plnbtn btn-lg pay-btn"
        //                 onclick="startPayment(${monetization.id}, ${monetization.amount * 100}, ${targetOrgId})">
        //                 Choose Plan
        //             </button>
        //         `;

        //         container.appendChild(card);
        //     });
        // }

        // function renderOrganization(monetizationplan) {
        //     const container = document.getElementById("pricing-container");
        //     container.innerHTML = "";

        //     const targetOrgId = document.getElementById("org-id")?.value;

        //     if (!targetOrgId) {
        //         console.error("Organization ID not found.");
        //         return;
        //     }

        //     // Filter only active plans for the target organization
        //     const filteredPlans = monetizationplan
        //         .filter(plan =>
        //             String(plan.organization_id) === String(targetOrgId) &&
        //             plan.is_active == 1
        //         )
        //         .sort((a, b) => {
        //             const order = { silver: 1, gold: 2, platinum: 3 };
        //             const aRank = order[a.name.toLowerCase()] || 999;
        //             const bRank = order[b.name.toLowerCase()] || 999;
        //             return aRank - bRank;
        //         });

        //     if (filteredPlans.length === 0) {
        //         console.warn(`No active plans found for Organization with ID ${targetOrgId}.`);
        //         return;
        //     }

        //     // Loop through the filtered plans and render them
        //     filteredPlans.forEach((monetization) => {
        //         const contentSet = monetization.content_sets && monetization.content_sets[0];

        //         const card = document.createElement("div");
        //         card.className = "card";

        //         const isGold = monetization.name.toLowerCase() === "gold";
        //         const badgeHTML = isGold
        //             ? `<span class="badge gradient-custom text-white popular-badge px-4 py-2">Popular</span>`
        //             : "";

        //         if (isGold) {
        //             card.classList.add('gold-plan');
        //         }

        //         card.innerHTML = `
        //     ${badgeHTML}
        //     <h3>${monetization.name}</h3>
        //     <div class="price">₹ ${monetization.amount}/mo</div>
        //     <ul class="features">
        //         <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Description:</small> ${monetization.description}</li><br>
        //         <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Duration:</small> ${monetization.duration} days</li><br>
        //         ${contentSet ? `
        //             <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Video and sound quality:</small> ${contentSet.video_and_sound_quality}</li><br>
        //             <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Resolution:</small> ${JSON.parse(contentSet.resolution).join(', ')}</li><br>
        //             <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Supported Devices:</small> ${JSON.parse(contentSet.supported_devices).join(', ')}</li><br>
        //             <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Download Devices:</small> ${contentSet.download_devices}</li><br>
        //             <li><i class="bi bi-check2 text-primary me-2"></i><small class="text-dark" style="font-weight: bold;">Ads:</small> ${contentSet.no_ad}</li>
        //         ` : ''}
        //     </ul>
        //     <input type="hidden" name="id" value="${monetization.id}">
        //     <input type="hidden" name="amount" value="${monetization.amount * 100}">
        //     <input type="hidden" name="organization_id" value="${targetOrgId}">
        //     <button type="button"
        //         class="plnbtn btn-lg pay-btn"
        //         onclick="startPayment(${monetization.id}, ${monetization.amount * 100}, ${targetOrgId})">
        //         Choose Plan
        //     </button>
        // `;

        //         container.appendChild(card);
        //     });
        // }


        document.addEventListener("DOMContentLoaded", function () {
            renderOrganization(monetizationPlans);
        });

        this.fetchcontentset();


    }];

window.gridControllers = {
    MonetizationPlanController: MonetizationPlanController
};