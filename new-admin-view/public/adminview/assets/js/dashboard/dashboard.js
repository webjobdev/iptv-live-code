'use strict';

var DashboardController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;

        this.info = {};
        this.selectedRecords = [];
        this.responseMessage = false;
        this.showResponseMessage = false;
        /**
         * This function is used to select the default data selection for dashboard and variable value of $defaultData can be changed by
         * following values
         * 	 All =1
         *   Last Year  = 2;
         *   Last Month = 3;
         * 	 Last Week  = 4;
         *
         */
        var defaultData = 3;


        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
        scope.language = {};
        scope.errors = {};
        scope.showDemoVideo = false;
        requestFactory.setThisArgument(this);
        // requestFactory.toggleLoader();
        var jsonData = [];
        var jsonRevenueData = [];
        var jsonData_x_axis = [];
        var jsonData_y_axis = [];
        var jsonRevenueData_x_axis = [];
        var jsonRevenueData_y_axis = [];
        this.platformwise_analytics_web = '';
        this.platformwise_analytics_ios = '';
        this.platformwise_analytics_android = '';


        this.defineProperties = function (data) {
            requestFactory.toggleLoader();
            this.info = data.info;

            this.totalNumberOfVideos = data.info.total_number_of_videos;
            this.totalRevenue = data.info.total_revenue['revenue'];
            this.totalRevenueSince = data.info.total_revenue['revenueSince'];
            this.revenueStaus = data.info.revenue_staus;

            this.totalNumberOfActiveVideos = data.info.total_number_of_active_videos;
            this.subcribedUser = data.info.subcribed_user;
            this.registerUser = data.info.register_user;
            this.totalNumberOfActiveCustomer = data.info.total_number_of_active_customer;
            this.dropdown = data.info.chart_date_filter;
            this.dateWiseViewCount = data.info.total_view_count;
            scope.languages = data.info.language;

            // angular.forEach(data.info.user_subscribers, function (value, key) {
            //     var d = new Date(value.month);
            //     var monthname = months[d.getMonth()]
            //     var date = d.getDate()
            //     var originaldate = monthname + " " + date;
            //     var x = originaldate;
            //     var y = value.count;
            //     jsonData_x_axis.push(x)
            //     jsonData_y_axis.push(y);
            // });

            // angular.forEach(data.info.revenue_staus, function (value, key) {
            //     var d = new Date(value.month);
            //     var monthname = months[d.getMonth()]
            //     var date = d.getDate()
            //     var originaldate = monthname + " " + date;
            //     var x = originaldate;
            //     var y = value.count;
            //     jsonRevenueData_x_axis.push(x)
            //     jsonRevenueData_y_axis.push(y);
            // });

            // this.subcribedUserChart('users-subscriber-chart', jsonData_x_axis, jsonData_y_axis);
            // this.revenuedataChart('users-chart', jsonRevenueData_x_axis, jsonRevenueData_y_axis);

            this.getVideoStatsBlocks();
            this.defaultDataSelection(defaultData);

        };

        this.defaultDataSelection = function (defaultData) {
            this.overStatusSelected = defaultData;
            this.dropdownSelected = defaultData;
            this.subscribersDropdownSelected = defaultData;
            this.revenueSelected = defaultData;
            this.revenueStatusSelected = defaultData;
            this.regionwiseDateValue = defaultData;
            this.platformwiseDateValue = defaultData;
            this.activeSubscriberSelected = defaultData;
        }

        this.getVideoStatsBlocks = function () {
            requestFactory.get(requestFactory.getUrl('dashboard/getdashboardvideostats?type=3'), this.defineVideoStats, function () { });
        }
        this.defineVideoStats = function (statsData) {

            this.webPlatformCount = this.iosPlatformCount = this.androidPlatformCount = '';
            this.topCategories = statsData.info.top_categories;
            this.latestVideos = statsData.info.latest_videos;
            this.favouriteVideos = statsData.info.favourite_videos;
            this.mostCommentedVideos = statsData.info.most_commented_videos;
            this.regionwise_analytics = statsData.info.regionwise_analytics;
            this.platformwise_analytics_web = statsData.info.platformwise_analytics_web;
            this.platformwise_analytics_ios = statsData.info.platformwise_analytics_ios;
            this.platformwise_analytics_android = statsData.info.platformwise_analytics_android;
        }

        // this.subcribedUserChart = function (id, x, y) {
        //     Highcharts.chart(id, {
        //         chart: {
        //             type: 'areaspline'
        //         },

        //         title: {
        //             text: ''
        //         },

        //         xAxis: {
        //             categories: x,
        //             plotBands: [{ // visualize the weekend
        //                 from: 4.5,
        //                 to: 6.5,

        //             }],
        //             labels: {
        //                 style: {
        //                     color: '#b4b4b4',
        //                     fontSize: '14px'
        //                 }
        //             }
        //         },
        //         yAxis: {
        //             gridLineDashStyle: 'ShortDash',
        //             title: {
        //                 text: ''
        //             },
        //             labels: {
        //                 style: {
        //                     color: '#b4b4b4',
        //                     fontSize: '14px'
        //                 },

        //             }
        //         },
        //         plotOptions: {
        //             series: {
        //                 fillColor: {
        //                     linearGradient: [0, 0, 0, 300],
        //                     stops: [
        //                         [0, 'rgba(255, 120, 75, 0.25)'],
        //                         [1, 'rgba(255, 255, 255, 0.9)']
        //                     ]
        //                 }
        //             },
        //             areaspline: {
        //                 fillOpacity: 0.5
        //             }
        //         },
        //         tooltip: {
        //             formatter: function () {
        //                 return this.y;
        //             }
        //         },
        //         credits: {
        //             enabled: false
        //         },

        //         series: [{

        //             showInLegend: false,
        //             data: y,
        //             lineWidth: 2,
        //             lineColor: '#ff784b',
        //             marker: {
        //                 lineWidth: 2,
        //                 lineColor: 'white',
        //                 fillColor: '#ff784b',
        //                 symbol: 'circle',
        //                 radius: 4,
        //                 states: {
        //                     hover: {
        //                         fillColor: 'white',
        //                         lineColor: '#ff784b',
        //                         lineWidth: 3
        //                     }
        //                 }
        //             },
        //         }]
        //     });
        // }

        // this.revenuedataChart = function (id, x, y) {
        //     Highcharts.chart(id, {
        //         chart: {
        //             type: 'areaspline'
        //         },
        //         title: {
        //             text: ''
        //         },

        //         xAxis: {
        //             categories: x,
        //             plotBands: [{ // visualize the weekend
        //                 from: 4.5,
        //                 to: 6.5,

        //             }],
        //             labels: {
        //                 style: {
        //                     color: '#b4b4b4',
        //                     fontSize: '14px'
        //                 }
        //             }
        //         },
        //         yAxis: {
        //             gridLineDashStyle: 'ShortDash',
        //             title: {
        //                 text: ''
        //             },
        //             labels: {
        //                 style: {
        //                     color: '#b4b4b4',
        //                     fontSize: '14px'
        //                 },

        //             }
        //         },
        //         plotOptions: {
        //             series: {
        //                 fillColor: {
        //                     linearGradient: [0, 0, 0, 250],
        //                     stops: [
        //                         [0, 'rgba(0, 185, 126, 0.25)'],
        //                         [1, 'rgba(255, 255, 255, 0.9)']
        //                     ]
        //                 }
        //             },
        //             areaspline: {
        //                 fillOpacity: 0.5
        //             }
        //         },
        //         tooltip: {
        //             formatter: function () {
        //                 return '$<b>' + this.y + '</b>';
        //             }
        //         },
        //         credits: {
        //             enabled: false
        //         },

        //         series: [{

        //             showInLegend: false,
        //             data: y,

        //             lineWidth: 2,
        //             lineColor: '#00b97e',
        //             marker: {
        //                 lineWidth: 2,
        //                 lineColor: 'white',
        //                 fillColor: '#00b97e',
        //                 symbol: 'circle',
        //                 radius: 4,
        //                 states: {
        //                     hover: {
        //                         fillColor: 'white',
        //                         lineColor: '#00b97e',
        //                         lineWidth: 3
        //                     }
        //                 }
        //             },

        //         }],
        //         lang: {
        //             noData: "No data to display"
        //         },
        //     });
        // }

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('dashboard/info?type=3'), this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                });
        };

        var commentfail = function (response) {
            ngToast.create({ className: 'danger', content: '<strong>' + response.message + '</strong>' });

        }
        this.changeVideoViewCount = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/overviewcount/' + item), function (data) {
                this.dateWiseViewCount = String(data.info.total_view_count);
            }, commentfail);
        }
        this.changeSignedCustomers = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/signed-customer/' + item), function (data) {
                this.registerUser = data.info.register_user;
            }, commentfail);
        };

        this.changeActiveSubscriber = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/active-subscriber/' + item), function (data) {
                this.subcribedUser = data.info.subcribed_user;
            }, commentfail);
        };


        // this.changeSubscribedCustomers = function (item) {
        //     requestFactory.get(requestFactory.getUrl('dashboard/subscriber-user/' + item), function (data) {
        //         jsonData_x_axis = [];
        //         jsonData_y_axis = [];
        //         if (item == 2) {
        //             angular.forEach(data.info.user_subscribers, function (value, key) {
        //                 var d = new Date(value.month);
        //                 var x = months[d.getMonth()];
        //                 var y = value.count;
        //                 jsonData_x_axis.push(x)
        //                 jsonData_y_axis.push(y);
        //             });
        //         }
        //         else if (item == 3 || item == 4) {
        //             angular.forEach(data.info.user_subscribers, function (value, key) {
        //                 var d = new Date(value.month);
        //                 var monthname = months[d.getMonth()]
        //                 var date = d.getDate()
        //                 var originaldate = monthname + " " + date;
        //                 var x = originaldate;
        //                 var y = value.count;
        //                 jsonData_x_axis.push(x)
        //                 jsonData_y_axis.push(y);
        //             });
        //         }

        //         else {
        //             angular.forEach(data.info.user_subscribers, function (value, key) {
        //                 var x = value.month;
        //                 var y = value.count;
        //                 jsonData_x_axis.push(x)
        //                 jsonData_y_axis.push(y);
        //             });
        //         }
        //         this.subcribedUserChart('users-subscriber-chart', jsonData_x_axis, jsonData_y_axis);
        //     }, commentfail);
        // };

        this.changeRevenueCount = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/revenue/' + item), function (data) {
                this.totalRevenue = data.info.total_revenue;
            }, commentfail);
        };

        // this.changeRevenueStatus = function (item) {
        //     requestFactory.get(requestFactory.getUrl('dashboard/revenue_status/' + item), function (data) {
        //         jsonRevenueData_x_axis = [];
        //         jsonRevenueData_y_axis = [];
        //         if (item == 2) {
        //             angular.forEach(data.info.revenue_staus, function (value, key) {
        //                 var d = new Date(value.month);
        //                 var x = months[d.getMonth()];
        //                 var y = value.count;
        //                 jsonRevenueData_x_axis.push(x);
        //                 jsonRevenueData_y_axis.push(y);
        //             });
        //         }
        //         else if (item == 3 || item == 4) {
        //             angular.forEach(data.info.revenue_staus, function (value, key) {
        //                 var d = new Date(value.month);
        //                 var monthname = months[d.getMonth()]
        //                 var date = d.getDate()
        //                 var originaldate = monthname + " " + date;
        //                 var x = originaldate;
        //                 var y = value.count;
        //                 jsonRevenueData_x_axis.push(x);
        //                 jsonRevenueData_y_axis.push(y);
        //             });
        //         }
        //         else {
        //             angular.forEach(data.info.revenue_staus, function (value, key) {
        //                 var x = value.month;
        //                 var y = value.count;
        //                 jsonRevenueData_x_axis.push(x);
        //                 jsonRevenueData_y_axis.push(y);
        //             });
        //         }

        //         this.revenuedataChart('users-chart', jsonRevenueData_x_axis, jsonRevenueData_y_axis);
        //     }, commentfail);
        // };

        this.applyDateFilter = function (type, item) {
            if (type == 'regionwise') {
                this.applyDateFilterOnRegionWise(item);
            }
            if (type == 'platformwise') {
                this.applyDateFilterOnPlatformWise(item);
            }
        }
        this.applyDateFilterOnRegionWise = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/regionwisevideocount_datefilter/' + item), function (data) {
                this.regionwise_analytics = data.info.regionwise_analytics;
            });
        };
        this.applyDateFilterOnPlatformWise = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/platformwisevideocount_datefilter/' + item), function (data) {
                this.platformwise_analytics_web = data.info.platformwise_analytics_web;
                this.platformwise_analytics_ios = data.info.platformwise_analytics_ios;
                this.platformwise_analytics_android = data.info.platformwise_analytics_android;
            });
        };

        /**
         * Overview Data Change Part
         */
        this.changeOverviewData = function (item) {
            requestFactory.get(requestFactory.getUrl('dashboard/overviewcount/' + item), function (data) {

                this.totalRevenue = data.info.total_revenue['revenue'];
                this.totalRevenueSince = data.info.total_revenue['revenueSince'];
                this.totalNumberOfActiveVideos = data.info.total_number_of_active_videos;
                this.subcribedUser = data.info.subcribed_user;
                this.registerUser = data.info.register_user;
                this.dateWiseViewCount = data.info.total_view_count;

            }, commentfail);

        }
        window.addEventListener('load', function () {
            this.hideLoader = true;
        }, false);
        this.languageChange = function () {

            var language = this.language

            requestFactory.post(requestFactory.getUrl('site/language'), { language }, function (response) {

                $window.location = requestFactory.getTemplateUrl('admin/dashboard');

            });
        };
        this.fetchInfo();
        // ========================================*******************************========================================
        // @author jay@picode.in
        // ========================================*******************************========================================

        window.onload = function () {
            setTimeout(() => {
                this.worldMapOnLoad = function () {
                    requestFactory.post(
                        requestFactory.getUrl('dashboard/subscriber-device-count/records'), this.defineProperties,
                        function (response) {
                            if (response && response.data && Array.isArray(response.data.subscriber_device_data)) {
                                const markers = [];

                                const countryCoords = {
                                    "Afghanistan": [33.93911, 67.709953],
                                    "Albania": [41.153332, 20.168331],
                                    "Algeria": [28.033886, 1.659626],
                                    "American Samoa": [-14.270972, -170.132217],
                                    "Andorra": [42.546245, 1.601554],
                                    "Angola": [-11.202692, 17.873887],
                                    "Antigua and Barbuda": [17.060816, -61.796428],
                                    "Argentina": [-38.416097, -63.616672],
                                    "Armenia": [40.069099, 45.038189],
                                    "Australia": [-25.274398, 133.775136],
                                    "Austria": [47.516231, 14.550072],
                                    "Azerbaijan": [40.143105, 47.576927],
                                    "Bahamas": [25.03428, -77.39628],
                                    "Bahrain": [25.930414, 50.637772],
                                    "Bangladesh": [23.684994, 90.356331],
                                    "Barbados": [13.193887, -59.543198],
                                    "Belarus": [53.709807, 27.953389],
                                    "Belgium": [50.503887, 4.469936],
                                    "Belize": [17.189877, -88.49765],
                                    "Benin": [9.30769, 2.315834],
                                    "Bhutan": [27.514162, 90.433601],
                                    "Bolivia": [-16.290154, -63.588653],
                                    "Bosnia and Herzegovina": [43.915886, 17.679076],
                                    "Botswana": [-22.328474, 24.684866],
                                    "Brazil": [-14.235004, -51.92528],
                                    "Brunei": [4.535277, 114.727669],
                                    "Bulgaria": [42.733883, 25.48583],
                                    "Burkina Faso": [12.238333, -1.561593],
                                    "Burundi": [-3.373056, 29.918886],
                                    "Cambodia": [12.565679, 104.990963],
                                    "Cameroon": [7.369722, 12.354722],
                                    "Canada": [56.130366, -106.346771],
                                    "Cape Verde": [16.002082, -24.013197],
                                    "Central African Republic": [6.611111, 20.939444],
                                    "Chad": [15.454166, 18.732207],
                                    "Chile": [-35.675147, -71.542969],
                                    "China": [35.86166, 104.195397],
                                    "Colombia": [4.570868, -74.297333],
                                    "Comoros": [-11.875001, 43.872219],
                                    "Democratic Republic of the Congo": [-4.038333, 21.758664],
                                    "Republic of the Congo": [-0.228021, 15.827659],
                                    "Costa Rica": [9.748917, -83.753428],
                                    "Ivory Coast": [7.539989, -5.54708],
                                    "Croatia": [45.1, 15.2],
                                    "Cuba": [21.521757, -77.781167],
                                    "Cyprus": [35.126413, 33.429859],
                                    "Czech Republic": [49.817492, 15.472962],
                                    "Denmark": [56.26392, 9.501785],
                                    "Djibouti": [11.825138, 42.590275],
                                    "Dominica": [15.414999, -61.370976],
                                    "Dominican Republic": [18.735693, -70.162651],
                                    "Ecuador": [-1.831239, -78.183406],
                                    "Egypt": [26.820553, 30.802498],
                                    "El Salvador": [13.794185, -88.89653],
                                    "Equatorial Guinea": [1.650801, 10.267895],
                                    "Eritrea": [15.179384, 39.782334],
                                    "Estonia": [58.595272, 25.013607],
                                    "Ethiopia": [9.145, 40.489673],
                                    "Fiji": [-16.578193, 179.414413],
                                    "Finland": [61.92411, 25.748151],
                                    "France": [46.227638, 2.213749],
                                    "Gabon": [-0.803689, 11.609444],
                                    "Gambia": [13.443182, -15.310139],
                                    "Georgia": [42.315407, 43.356892],
                                    "Germany": [51.165691, 10.451526],
                                    "Ghana": [7.946527, -1.023194],
                                    "Greece": [39.074208, 21.824312],
                                    "Grenada": [12.262776, -61.604171],
                                    "Guatemala": [15.783471, -90.230759],
                                    "Guinea": [9.945587, -9.696645],
                                    "Guinea-Bissau": [11.803749, -15.180413],
                                    "Guyana": [4.860416, -58.93018],
                                    "Haiti": [18.971187, -72.285215],
                                    "Honduras": [15.199999, -86.241905],
                                    "Hungary": [47.162494, 19.503304],
                                    "Iceland": [64.963051, -19.020835],
                                    "India": [20.593684, 78.96288],
                                    "Indonesia": [-0.789275, 113.921327],
                                    "Iran": [32.427908, 53.688046],
                                    "Iraq": [33.223191, 43.679291],
                                    "Ireland": [53.41291, -8.24389],
                                    "Israel": [31.046051, 34.851612],
                                    "Italy": [41.87194, 12.56738],
                                    "Jamaica": [18.109581, -77.297508],
                                    "Japan": [36.204824, 138.252924],
                                    "Jordan": [30.585164, 36.238414],
                                    "Kazakhstan": [48.019573, 66.923684],
                                    "Kenya": [-0.023559, 37.906193],
                                    "South Korea": [35.907757, 127.766922],
                                    "Kuwait": [29.337498, 47.658779],
                                    "Kyrgyzstan": [41.20438, 74.766098],
                                    "Laos": [19.85627, 102.495496],
                                    "Latvia": [56.879635, 24.603189],
                                    "Lebanon": [33.854721, 35.862285],
                                    "Libya": [26.3351, 17.228331],
                                    "Lithuania": [55.169438, 23.881275],
                                    "Luxembourg": [49.815273, 6.129583],
                                    "Malaysia": [4.210484, 101.975766],
                                    "Maldives": [3.202778, 73.22068],
                                    "Mexico": [23.634501, -102.552784],
                                    "Nepal": [28.394857, 84.124008],
                                    "Netherlands": [52.132633, 5.291266],
                                    "New Zealand": [-40.900557, 174.885971],
                                    "Nigeria": [9.081999, 8.675277],
                                    "Norway": [60.472024, 8.468946],
                                    "Oman": [21.512583, 55.923255],
                                    "Pakistan": [30.375321, 69.345116],
                                    "Philippines": [12.879721, 121.774017],
                                    "Poland": [51.919438, 19.145136],
                                    "Portugal": [39.399872, -8.224454],
                                    "Qatar": [25.354826, 51.183884],
                                    "Romania": [45.943161, 24.96676],
                                    "Russia": [61.52401, 105.318756],
                                    "Saudi Arabia": [23.885942, 45.079162],
                                    "Singapore": [1.352083, 103.819836],
                                    "South Africa": [-30.559482, 22.937506],
                                    "Spain": [40.463667, -3.74922],
                                    "Sri Lanka": [7.873054, 80.771797],
                                    "Sweden": [60.128161, 18.643501],
                                    "Switzerland": [46.818188, 8.227512],
                                    "Syria": [34.802075, 38.996815],
                                    "Taiwan": [23.69781, 120.960515],
                                    "Thailand": [15.870032, 100.992541],
                                    "Turkey": [38.963745, 35.243322],
                                    "Ukraine": [48.379433, 31.16558],
                                    "United Arab Emirates": [23.424076, 53.847818],
                                    "United Kingdom": [55.378051, -3.435973],
                                    "United States": [37.09024, -95.712891],
                                    "Vietnam": [14.058324, 108.277199],
                                    "Yemen": [15.552727, 48.516388],
                                    "Zambia": [-13.133897, 27.849332],
                                    "Zimbabwe": [-19.015438, 29.154857]
                                };

                                const countryDeviceTotals = {};

                                // 1️⃣ Group + Sum devices by country
                                response.data.subscriber_device_data.forEach(device => {
                                    if (!device.country) return;

                                    if (!countryDeviceTotals[device.country]) {
                                        countryDeviceTotals[device.country] = 0;
                                    }

                                    countryDeviceTotals[device.country] += Number(device.devices_count || 0);
                                });

                                // 2️⃣ Create markers
                                Object.keys(countryDeviceTotals).forEach(country => {
                                    const coords = countryCoords[country];

                                    if (coords) {
                                        markers.push({
                                            coords: coords,
                                            name: `${countryDeviceTotals[country]} Devices`
                                        });
                                    }
                                });

                                // this.info = response.data.subscriber_device_data.forEach(device => {
                                //     let coords = null;

                                //     coords = countryCoords[device.country];
                                //     if (!coords) coords == countryCoords[device.country] || null;

                                //     if (coords) {
                                //         markers.push({
                                //             coords: coords,
                                //             name: `${device.devices_count} Devices`
                                //         });
                                //     }
                                // });

                                setTimeout(() => {
                                    const map = new jsVectorMap({
                                        selector: "#map",
                                        map: "world",
                                        markers: markers,
                                        selectedMarkers: [],
                                        markerLabelStyle: {
                                            fontSize: '14px'
                                        }
                                    });
                                }, 200);

                            }
                        }
                    );
                }
                this.worldMapOnLoad();
            }, 2000);

        };
        // ========================================*******************************========================================

        // scope.this = this;

        this.topCountrySelected = '5'; // default top 5

        this.updateTopCountries = function () {
            this.barChartOnLoad();
        };

        this.barChartOnLoad = function () {
            const postData = Object.assign({}, this.defineProperties, {
                limit: this.topCountrySelected || 5
            });

            requestFactory.post(
                requestFactory.getUrl('dashboard/subscriber-active-count/records'),
                postData,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.subscriber_active_data)) {
                        const chartCanvas = document.getElementById("horizontal-bar-chart");

                        const country = {
                            "AF": "Afghanistan", "AL": "Albania", "DZ": "Algeria", "AS": "American Samoa",
                            "AD": "Andorra", "AO": "Angola", "AI": "Anguilla", "AQ": "Antarctica",
                            "AG": "Antigua and Barbuda", "AR": "Argentina", "AM": "Armenia", "AW": "Aruba",
                            "AU": "Australia", "AT": "Austria", "AZ": "Azerbaijan", "BS": "Bahamas",
                            "BH": "Bahrain", "BD": "Bangladesh", "BB": "Barbados", "BY": "Belarus",
                            "BE": "Belgium", "BZ": "Belize", "BJ": "Benin", "BM": "Bermuda", "BT": "Bhutan",
                            "BO": "Bolivia", "BA": "Bosnia and Herzegovina", "BW": "Botswana", "BR": "Brazil",
                            "IO": "British Indian Ocean Territory", "BN": "Brunei", "BG": "Bulgaria",
                            "BF": "Burkina Faso", "BI": "Burundi", "KH": "Cambodia", "CM": "Cameroon",
                            "CA": "Canada", "CL": "Chile", "CN": "China", "CO": "Colombia", "CR": "Costa Rica",
                            "HR": "Croatia", "CU": "Cuba", "CY": "Cyprus", "CZ": "Czech Republic",
                            "DK": "Denmark", "DJ": "Djibouti", "DM": "Dominica", "DO": "Dominican Republic",
                            "EC": "Ecuador", "EG": "Egypt", "SV": "El Salvador", "EE": "Estonia", "ET": "Ethiopia",
                            "FJ": "Fiji", "FI": "Finland", "FR": "France", "DE": "Germany", "GH": "Ghana",
                            "GR": "Greece", "HK": "Hong Kong", "HU": "Hungary", "IS": "Iceland", "IN": "India",
                            "ID": "Indonesia", "IR": "Iran", "IQ": "Iraq", "IE": "Ireland", "IL": "Israel",
                            "IT": "Italy", "JP": "Japan", "JO": "Jordan", "KE": "Kenya", "KW": "Kuwait",
                            "KG": "Kyrgyzstan", "LA": "Laos", "LB": "Lebanon", "LY": "Libya", "LT": "Lithuania",
                            "LU": "Luxembourg", "MY": "Malaysia", "MV": "Maldives", "MX": "Mexico",
                            "NP": "Nepal", "NL": "Netherlands", "NZ": "New Zealand", "NG": "Nigeria",
                            "NO": "Norway", "OM": "Oman", "PK": "Pakistan", "PE": "Peru", "PH": "Philippines",
                            "PL": "Poland", "PT": "Portugal", "QA": "Qatar", "RO": "Romania", "RU": "Russia",
                            "SA": "Saudi Arabia", "SG": "Singapore", "ZA": "South Africa", "KR": "South Korea",
                            "ES": "Spain", "LK": "Sri Lanka", "SE": "Sweden", "CH": "Switzerland",
                            "SY": "Syria", "TW": "Taiwan", "TH": "Thailand", "TR": "Turkey", "UA": "Ukraine",
                            "AE": "United Arab Emirates", "GB": "United Kingdom", "US": "United States",
                            "VN": "Vietnam", "YE": "Yemen", "ZM": "Zambia", "ZW": "Zimbabwe"
                        };

                        if (response.data.subscriber_active_data.length === 0) {
                            chartCanvas.parentNode.innerHTML = `
                            <div style="text-align:center; color:#666; padding:120px 0; font-size:16px;">
                                <strong>No Data Found</strong>
                            </div>`;
                            return;
                        }

                        // Restore canvas if replaced before
                        if (!document.getElementById("horizontal-bar-chart")) {
                            const newCanvas = document.createElement("canvas");
                            newCanvas.id = "horizontal-bar-chart";
                            newCanvas.style.height = "350px";
                            newCanvas.style.width = "100%";
                            const cardContent = document.querySelector(".card-content.graph");
                            cardContent.innerHTML = "";
                            cardContent.appendChild(newCanvas);
                        }

                        const labels = [];
                        const dataValues = [];

                        response.data.subscriber_active_data.forEach(item => {
                            const countryName = country[item.country] || item.country;
                            labels.push(countryName);
                            dataValues.push(item.active_count);
                        });

                        const ctx = document.getElementById("horizontal-bar-chart").getContext("2d");

                        if (window.subscriberBarChart) {
                            window.subscriberBarChart.destroy();
                        }

                        window.subscriberBarChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Active Subscribers",
                                    backgroundColor: "#00ACCD",
                                    data: dataValues
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: "#333",
                                        titleFont: { size: 14 },
                                        bodyFont: { size: 13 },
                                        padding: 10
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: { color: "#555" },
                                        grid: { color: "#eee" }
                                    },
                                    y: {
                                        ticks: { color: "#333" },
                                        grid: { display: false }
                                    }
                                },
                                animation: {
                                    duration: 800,
                                    easing: 'easeOutQuart'
                                }
                            }
                        });
                    }
                }
            );
        };

        // On select change
        // this.updateTopCountries = function () {
        //     this.barChartOnLoad();
        // };
        // ========================================*******************************========================================

        this.pieChartOnLoad = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard/device-count/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && response.data.device_count) {

                        const deviceCount = response.data.device_count;

                        // Prepare labels with counts and series dynamically
                        const labels = [];
                        const series = [];

                        Object.keys(deviceCount).forEach(key => {
                            const count = deviceCount[key];
                            const labelName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            labels.push(`${labelName} ${count}`); // label with count
                            series.push(count); // series value
                        });

                        // Handle no data gracefully (all zero)
                        const hasData = series.some(val => val > 0);

                        const options = {
                            series: hasData ? series : [1],
                            chart: {
                                type: 'pie',
                                height: 350,
                                toolbar: { show: false },
                            },
                            labels: hasData ? labels : ['No Data'],
                            colors: [
                                '#EF4444', // red
                                '#F97316', // orange
                                '#F59E0B', // amber
                                '#84CC16', // lime
                                '#22C55E', // green
                                '#14B8A6', // teal
                                '#0EA5E9', // sky
                                '#3B82F6', // blue
                                '#6366F1', // indigo
                                '#8B5CF6', // violet
                                '#A855F7', // purple
                                '#EC4899'  // pink
                            ],
                            dataLabels: {
                                enabled: true,
                                dropShadow: { enabled: true },
                                style: { fontSize: '14px', colors: ['#fff'] },
                            },
                            stroke: {
                                width: 2,
                                colors: ['#fff'],
                            },
                            legend: {
                                position: 'bottom',
                                fontSize: '14px',
                                labels: { colors: '#374151' },
                                markers: { radius: 12 },
                            },
                            tooltip: {
                                fillSeriesColor: false,
                                theme: 'light',
                                y: {
                                    formatter: val => val, // show actual count
                                },
                            },
                            responsive: [
                                {
                                    breakpoint: 768,
                                    options: { chart: { height: 300 }, legend: { position: 'bottom' } },
                                },
                                {
                                    breakpoint: 480,
                                    options: { chart: { height: 250 }, legend: { position: 'bottom', fontSize: '12px' } },
                                },
                            ],
                        };

                        // Render chart
                        const chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                    }
                }
            );
        }
        this.pieChartOnLoad();
        // ========================================*******************************========================================

        this.selectedPeriod = 7;

        this.changeTotalRevenuePeriod = function (period) {
            this.lineChartOnLoad(period);
        };

        this.lineChartOnLoad = function (period = 7) {
            const params = { period: period };

            requestFactory.post(
                requestFactory.getUrl('dashboard/total-sales-revenue/records'),
                params,
                function (response) {
                    if (response && response.data && response.data.total_revenue) {
                        const revenueData = response.data.total_revenue.total_revenue; // nested key
                        const periodText = response.data.total_revenue.period;

                        let labels = [];
                        let currencyData = {};

                        revenueData.forEach(currencyGroup => {
                            const currency = currencyGroup.currency;
                            const records = currencyGroup.data;

                            currencyData[currency] = [];

                            records.forEach(record => {
                                const date = new Date(record.date);
                                const formattedDate = date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'short'
                                });

                                if (!labels.includes(formattedDate)) {
                                    labels.push(formattedDate);
                                }

                                currencyData[currency].push(record.total_amount);
                            });
                        });

                        // const defaultCurrency = Object.keys(currencyData)[0] || 'INR';
                        const defaultCurrency = Object.keys(currencyData)[0] || 'INR';
                        const defaultData = currencyData[defaultCurrency] || [];

                        const ctx = document.getElementById("line-chart").getContext("2d");

                        if (window.totalRevenueChart) {
                            window.totalRevenueChart.destroy();
                        }

                        window.totalRevenueChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "ALL",
                                    data: defaultData,
                                    borderColor: "#ff0000",
                                    backgroundColor: "rgba(255, 0, 0, 0.1)",
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: "#ff0000",
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: `Total Revenue (${periodText}) - ${defaultCurrency}`,
                                        font: { size: 18 }
                                    },
                                    legend: {
                                        display: true,
                                        position: 'bottom'
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: `Amount (${defaultCurrency})`
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Date'
                                        }
                                    }
                                }
                            }
                        });

                        // Currency change function
                        scope.updateChart = function (event, currency) {
                            const ul = event.target.closest('ul.nav-tabs');
                            if (ul) ul.querySelectorAll('li').forEach(tab => tab.classList.remove('active'));
                            const li = event.target.closest('li');
                            if (li) li.classList.add('active');

                            const newData = currencyData[currency] || [];
                            window.totalRevenueChart.data.datasets[0].data = newData;
                            window.totalRevenueChart.data.datasets[0].label = currency;
                            window.totalRevenueChart.options.plugins.title.text = `Currency Chart - ${currency}`;
                            window.totalRevenueChart.options.scales.y.title.text = `Amount (${currency})`;
                            window.totalRevenueChart.update();
                        };
                    }
                }
            );
        };
        // ========================================*******************************========================================

        this.selectPaymentPeriod = 7;

        this.changePaymentRevenuePeriod = function (period) {
            this.selectPaymentPeriod = period;
            this.multipleLineOnLoad(period);
        }

        this.multipleLineOnLoad = function (period = 7) {
            const params = { period: period };

            requestFactory.post(
                requestFactory.getUrl('dashboard/total-payment-type-sales-revenue/records'),
                params,
                function (response) {
                    if (response && response.data && response.data.total_revenue) {
                        this.multiLineInfo = response.data.total_revenue;

                        // Calculate start date based on selected period
                        const endDate = new Date();
                        const startDate = new Date();
                        startDate.setDate(endDate.getDate() - (period - 1));

                        // Filter data by selected period
                        this.multiLineInfo.forEach(item => {
                            item.data = item.data.filter(d => {
                                const dateObj = new Date(d.date);
                                return dateObj >= startDate && dateObj <= endDate;
                            });

                            // Ensure all dates in period are present
                            const allDates = [];
                            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                                const formatted = d.toISOString().split('T')[0];
                                allDates.push(formatted);
                                if (!item.data.find(x => x.date === formatted)) {
                                    item.data.push({ date: formatted, total_amount: 0 });
                                }
                            }

                            // Sort by date
                            item.data.sort((a, b) => new Date(a.date) - new Date(b.date));
                        });

                        // Extract all unique dates for labels
                        const allDatesLabels = [];
                        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                            allDatesLabels.push(new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                        }

                        // Prepare datasets by currency
                        const datasetsByCurrency = {};
                        const colors = [
                            "#6366F1", "#10B981", "#F59E0B", "#EF4444",
                            "#3B82F6", "#8B5CF6", "#EC4899", "#14B8A6",
                            "#22D3EE", "#84CC16"
                        ];
                        let colorIndex = 0;

                        this.multiLineInfo.forEach(item => {
                            const { payment_gateway, currency, data } = item;

                            if (!datasetsByCurrency[currency]) datasetsByCurrency[currency] = [];

                            const dateMap = Object.fromEntries(data.map(d => [d.date, d.total_amount]));
                            const dataPoints = [];
                            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                                const formatted = d.toISOString().split('T')[0];
                                dataPoints.push(dateMap[formatted] || 0);
                            }

                            datasetsByCurrency[currency].push({
                                label: payment_gateway.toUpperCase(),
                                data: dataPoints,
                                borderColor: colors[colorIndex % colors.length],
                                backgroundColor: colors[colorIndex % colors.length] + "33",
                                fill: true,
                                tension: 0.3
                            });

                            colorIndex++;
                        });

                        // Render chart
                        const firstCurrency = Object.keys(datasetsByCurrency)[0];
                        const ctx = document.getElementById("multipleline-chart").getContext("2d");

                        if (window.charttt) window.charttt.destroy(); // Destroy previous chart if exists

                        window.charttt = new Chart(ctx, {
                            type: "line",
                            data: {
                                labels: allDatesLabels,
                                datasets: datasetsByCurrency[firstCurrency]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: true, position: "bottom" }
                                },
                                scales: {
                                    y: { beginAtZero: true, title: { display: true, text: `Revenue (${firstCurrency})` } },
                                    x: { title: { display: true, text: "Date" } }
                                }
                            }
                        });

                        // Switch currency dynamically
                        scope.switchCurrency = function (event, currency) {
                            const ul = event.target.closest('ul.nav-tabs');
                            if (ul) ul.querySelectorAll('li').forEach(tab => tab.classList.remove('active'));
                            const li = event.target.closest('li');
                            if (li) li.classList.add('active');

                            window.charttt.data.datasets = datasetsByCurrency[currency] || [];
                            window.charttt.options.scales.y.title.text = `Revenue (${currency})`;
                            window.charttt.update();
                        }
                    }
                }.bind(this)
            );
        }
        // ========================================*******************************========================================

        this.fetchDashPermission = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard-configuration/records?rowsPerPage=1000000'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const config = response.data.data;
                        this.permission = config.filter(p => p.category === 'dashboard_configuration');

                        // Find specific permission
                        const activeSubPermission = this.permission.find(p => p.key === 'number_of_active_subscriber');
                        this.showActiveSubscriber = activeSubPermission && activeSubPermission.value == "1";
                        // console.log(this.showActiveSubscriber);

                    }
                }
            );
        };
        this.fetchDashPermission();
        // ========================================*******************************========================================

        this.fetchavAilableContent = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard/available-content/fetchrecords'),
                this.defineProperties,
                function (response) {
                    if (response && response.data) {
                        this.contetList = response.data;
                        // console.log(this.contetList);
                    }
                }
            );
        }
        this.fetchavAilableContent();
        // ========================================*******************************========================================

        this.fetchSubscriberMatrix = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard/subscriber-count/fetchrecords'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data)) {
                        this.metricsList = response.data[0];
                        // console.log(this.metricsList);
                    }
                }
            );
        };
        this.fetchSubscriberMatrix();
        // ========================================*******************************========================================

        this.fetchStream = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard/streams/fetchrecords'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data)) {
                        this.StreamList = response.data[0];
                        // console.log(this.metricsList);
                    }
                }
            );
        };
        this.fetchStream();
        // ========================================*******************************========================================

        this.fetchepg = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard/epg/fetchrecords'),
                this.defineProperties,
                function (response) {
                    if (response && response.data && Array.isArray(response.data.epg_data)) {
                        this.EpgList = response.data.epg_data;
                        // console.log(this.EpgList);
                    }
                }
            );
        };
        this.fetchepg();
        // ========================================*******************************========================================
        this.tableData = []; // Default empty table data
        this.allInfo = {};   // Store All tab data
        this.gatewayInfo = {}; // Store payment gateway data

        // 🔹 Fetch data from API
        this.fetchCurrencyData = function () {
            var self = this; // Preserve context

            requestFactory.post(
                requestFactory.getUrl('dashboard/sales-revenue-currency/records'),
                this.defineProperties,
                function (response) {
                    if (response && response.data) {
                        // Store data
                        self.allInfo = response.data.all || {};
                        self.gatewayInfo = response.data.gateway_type || {};

                        // Default tab = All
                        self.tableData = self.allInfo;
                    }
                }
            );
        };

        // 🔹 Call fetch function on load
        this.fetchCurrencyData();



        // 🔹 Switch tab function
        this.switchTab = function (tab, event) {
            const clickedTab = event?.currentTarget || event?.target?.closest('li');
            if (clickedTab) {
                const ul = clickedTab.closest('ul.nav-tabs');
                if (ul) ul.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                clickedTab.classList.add('active');
            }

            // Map between tab names and API keys
            const keyMap = {
                authorize: 'authorize.net',
                autopay: 'autopayment',
                external_payment: 'exteranl',
                true_mony: 'true_money'
            };

            // Determine the correct key for lookup
            const key = keyMap[tab] || tab;

            // Update table data based on selected tab
            if (tab === 'all') {
                this.tableData = this.allInfo;
            } else {
                this.tableData = this.gatewayInfo[key] || {};
            }

            // console.log('Switched Tab:', key, this.tableData);
        };

        this.fetchPermission = function () {
            requestFactory.post(
                requestFactory.getUrl('dashboard-configuration/records?rowsPerPage=1000000'),
                this.defineProperties,
                (response) => { // arrow function keeps 'this' context
                    if (response && response.data && response.data.data) {
                        // Filter for dashboard configuration
                        this.permission = response.data.data.filter(p => p.category === 'dashboard_configuration');

                        // console.log("Dashboard Permissions:", this.permission);

                        const activeRevenuePermission = this.permission.find(p => p.key === 'transactions_of_payment_service');
                        const activeSubscriberPermission = this.permission.find(sub => sub.key === 'number_of_active_subscriber');

                        this.showLineChart = activeRevenuePermission && activeRevenuePermission.value == "1";
                        this.showSubscriberData = activeSubscriberPermission && activeSubscriberPermission.value == "1";

                        const getPermissionValue = key => {
                            const perm = this.permission.find(p => p.key === key);
                            return perm && perm.value === "1";
                        };

                        // Set visibility flags based on permissions
                        this.showAuthorizeNet = getPermissionValue('amount_of_authorize_net_payment');
                        this.showCash = getPermissionValue('amount_of_cash_payment');
                        this.showAutoPay = getPermissionValue('autopayment_amount');
                        this.showCheck = getPermissionValue('amount_of_check_payment');
                        this.showExternal = getPermissionValue('amount_of_external_payment');
                        this.show2C2P = getPermissionValue('amount_of_2c2p_payment');
                        this.showGr4vy = getPermissionValue('amount_of_gr4avy_payment');
                        this.showTrueMoney = getPermissionValue('amount_of_true_money_payment');
                        this.showTotal = getPermissionValue('amount_of_total_payment');


                        if (this.showLineChart) {
                            // 🔹 Auto-load default chart on page load
                            this.lineChartOnLoad(7);
                            this.multipleLineOnLoad(7);
                        }

                        if (this.showSubscriberData) {
                            this.barChartOnLoad();
                        }
                    }
                }
            );
        };

        // Call the function
        this.fetchPermission();
    }
];
window.gridControllers = { DashboardController: DashboardController };


