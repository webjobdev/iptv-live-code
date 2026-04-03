// 'use strict';

var DashboardController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval','$rootScope',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval, rootScope){
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
	var defaultData=3;


	var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
	scope.language ={};
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


    this.defineProperties = function(data) {
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
		this.dateWiseViewCount=data.info.total_view_count;
		scope.languages = data.info.language;
		angular.forEach(data.info.user_subscribers, function(value, key) {
			var d = new Date(value.month);
			var monthname =months[d.getMonth()]
			var date=d.getDate()
			var originaldate =monthname+" "+date;
			var x = originaldate;
			var y = value.count;
			jsonData_x_axis.push(x)
			jsonData_y_axis.push(y);
		});

		angular.forEach(data.info.revenue_staus, function(value, key) {
			var d = new Date(value.month);
			var monthname =months[d.getMonth()]
			var date=d.getDate()
			var originaldate =monthname+" "+date;
			var x = originaldate;
			var y = value.count;
			jsonRevenueData_x_axis.push(x)
			jsonRevenueData_y_axis.push(y);
		});

		this.subcribedUserChart('users-subscriber-chart', jsonData_x_axis,jsonData_y_axis);
		this.revenuedataChart('users-chart',jsonRevenueData_x_axis,jsonRevenueData_y_axis);

		this.getVideoStatsBlocks();
		this.defaultDataSelection(defaultData);

	};
	this.defaultDataSelection = function(defaultData){
		this.overStatusSelected = defaultData;
		this.dropdownSelected = defaultData;
		this.subscribersDropdownSelected = defaultData;
		this.revenueSelected = defaultData;
		this.revenueStatusSelected = defaultData;
		this.regionwiseDateValue = defaultData;
		this.platformwiseDateValue = defaultData;
		this.activeSubscriberSelected = defaultData;
		}

	this.getVideoStatsBlocks = function(){
		requestFactory.get(requestFactory.getUrl('dashboard/getdashboardvideostats?type=3'),this.defineVideoStats,function(){});
	}
	this.defineVideoStats = function(statsData){

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
	this.subcribedUserChart = function(id, x,y)
	{
		Highcharts.chart(id, {
			chart: {
				type: 'areaspline'
			},

			title: {
			  text: ''
			},

			xAxis: {
			  categories: x,
			  plotBands: [{ // visualize the weekend
				from: 4.5,
				to: 6.5,

			}],
			labels: {
				style: {
						color: '#b4b4b4',
						fontSize:'14px'
				}
		}
			},
			yAxis: {
				gridLineDashStyle: 'ShortDash',
				title: {
					text: ''
				},
				labels: {
				style: {
					color: '#b4b4b4',
					fontSize:'14px'
				},

		}
		},
			plotOptions: {
				series: {
					fillColor: {
						linearGradient: [0, 0, 0, 300],
						stops: [
							[0, 'rgba(255, 120, 75, 0.25)'],
							[1, 'rgba(255, 255, 255, 0.9)']
						]
				}
			},
				areaspline: {
					fillOpacity: 0.5
				}
			},
			tooltip: {
        formatter: function () {
            return this.y;
        }
   		 },
			credits: {
				enabled: false
			},

			series: [{

				showInLegend: false,
					data:y,
					lineWidth: 2,
					lineColor: '#ff784b',
					marker: {
						lineWidth: 2,
						lineColor: 'white',
						fillColor: '#ff784b',
						symbol: 'circle',
						radius: 4,
						states: {
										hover: {
												fillColor: 'white',
												lineColor: '#ff784b',
												lineWidth: 3
										}
								}
					},
			}]
		  });
	}
	this.revenuedataChart = function(id, x,y)
	{
		Highcharts.chart(id, {
			chart: {
				type: 'areaspline'
			},
			title: {
			  text: ''
			},

			xAxis: {
			  categories: x,
			  plotBands: [{ // visualize the weekend
				from: 4.5,
				to: 6.5,

			}],
			labels: {
				style: {
						color: '#b4b4b4',
						fontSize:'14px'
				}
			}
			},
			yAxis: {
				gridLineDashStyle: 'ShortDash',
				title: {
					text: ''
				},
				labels: {
				style: {
					color: '#b4b4b4',
					fontSize:'14px'
				},

		}
		},
			plotOptions: {
				series: {
					fillColor: {
						linearGradient: [0, 0, 0, 250],
						stops: [
							[0, 'rgba(0, 185, 126, 0.25)'],
							[1, 'rgba(255, 255, 255, 0.9)']
						]
				}
			},
				areaspline: {
					fillOpacity: 0.5
				}
			},
			tooltip: {
        formatter: function () {
            return '$<b>'+this.y+'</b>';
        }
   		 },
			credits: {
				enabled: false
			},

			series: [{

				showInLegend: false,
					data:y,

					lineWidth: 2,
					lineColor: '#00b97e',
					marker: {
						lineWidth: 2,
						lineColor: 'white',
						fillColor: '#00b97e',
						symbol: 'circle',
						radius: 4,
						states: {
										hover: {
												fillColor: 'white',
												lineColor: '#00b97e',
												lineWidth: 3
										}
								}
					},

			}],
			lang: {
        noData: "No data to display"
    },
		  });
	}
    this.fetchInfo = function() {
	  requestFactory.get(requestFactory.getUrl('dashboard/info?type=3'),this.defineProperties,
	  function(response){
		rootScope.redirectUnauthenticated(response);
	  });

	};

	var commentfail = function ( response ) {
		ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

	}
	this.changeVideoViewCount = function(item){
		requestFactory.get(requestFactory.getUrl('dashboard/overviewcount/' + item ),function(data) {
			this.dateWiseViewCount=String(data.info.total_view_count);
		},commentfail);
	}
	this.changeSignedCustomers = function(item) {
		requestFactory.get(requestFactory.getUrl('dashboard/signed-customer/' + item ),function(data) {
			this.registerUser = data.info.register_user;
		},commentfail);
	};

	this.changeActiveSubscriber = function(item) {
		requestFactory.get(requestFactory.getUrl('dashboard/active-subscriber/' + item ),function(data) {
			this.subcribedUser = data.info.subcribed_user;
		},commentfail);
	};


	this.changeSubscribedCustomers = function(item) {
		requestFactory.get(requestFactory.getUrl('dashboard/subscriber-user/' + item ),function(data) {
			jsonData_x_axis = [];
			jsonData_y_axis = [];
			if(item==2)
			{
				angular.forEach(data.info.user_subscribers, function(value, key) {
					var d = new Date(value.month);
					var x = months[d.getMonth()];
					var y = value.count;
					jsonData_x_axis.push(x)
					jsonData_y_axis.push(y);
				});
			}
			else if(item==3 ||item==4 ){
				angular.forEach(data.info.user_subscribers, function(value, key) {
					var d = new Date(value.month);
					var monthname =months[d.getMonth()]
					var date=d.getDate()
					var originaldate =monthname+" "+date;
					var x = originaldate;
					var y = value.count;
					jsonData_x_axis.push(x)
					jsonData_y_axis.push(y);
				});
			}

			else{
				angular.forEach(data.info.user_subscribers, function(value, key) {
					var x = value.month;
					var y = value.count;
					jsonData_x_axis.push(x)
					jsonData_y_axis.push(y);
				});
			}
			this.subcribedUserChart('users-subscriber-chart', jsonData_x_axis,jsonData_y_axis);
		},commentfail);
	};

	this.changeRevenueCount = function(item) {
		requestFactory.get(requestFactory.getUrl('dashboard/revenue/' + item ),function(data) {
			this.totalRevenue = data.info.total_revenue;
		},commentfail);
	};

	this.changeRevenueStatus = function(item) {
		requestFactory.get(requestFactory.getUrl('dashboard/revenue_status/' + item ),function(data) {
			jsonRevenueData_x_axis = [];
			jsonRevenueData_y_axis = [];
			if(item==2)
			{
				angular.forEach(data.info.revenue_staus, function(value, key) {
					var d = new Date(value.month);
					var x = months[d.getMonth()];
					var y = value.count;
					jsonRevenueData_x_axis.push(x);
				  jsonRevenueData_y_axis.push(y);
				});
			}
			else if(item==3||item==4){
				angular.forEach(data.info.revenue_staus, function(value, key) {
					var d = new Date(value.month);
					var monthname =months[d.getMonth()]
					var date=d.getDate()
					var originaldate =monthname+" "+date;
					var x = originaldate;
					var y = value.count;
					jsonRevenueData_x_axis.push(x);
				  jsonRevenueData_y_axis.push(y);
				});
			}
			else{
				angular.forEach(data.info.revenue_staus, function(value, key) {
					var x = value.month;
					var y = value.count;
					jsonRevenueData_x_axis.push(x);
					jsonRevenueData_y_axis.push(y);
				});
			}

			this.revenuedataChart('users-chart', jsonRevenueData_x_axis,jsonRevenueData_y_axis);
		},commentfail);
	};
	this.applyDateFilter = function(type,item){
		if(type == 'regionwise'){
			this.applyDateFilterOnRegionWise(item);
		}
		if(type=='platformwise'){
			this.applyDateFilterOnPlatformWise(item);
		}
	}
	this.applyDateFilterOnRegionWise = function(item){
		requestFactory.get(requestFactory.getUrl('dashboard/regionwisevideocount_datefilter/' + item ),function(data) {
			this.regionwise_analytics = data.info.regionwise_analytics;
		});
	};
	this.applyDateFilterOnPlatformWise = function(item){
		requestFactory.get(requestFactory.getUrl('dashboard/platformwisevideocount_datefilter/' + item ),function(data) {
			this.platformwise_analytics_web = data.info.platformwise_analytics_web;
			this.platformwise_analytics_ios = data.info.platformwise_analytics_ios;
			this.platformwise_analytics_android = data.info.platformwise_analytics_android;
		});
	};

	/**
	 * Overview Data Change Part
	 */
	this.changeOverviewData=function(item){
		requestFactory.get(requestFactory.getUrl('dashboard/overviewcount/' + item ),function(data) {

			this.totalRevenue = data.info.total_revenue['revenue'];
			this.totalRevenueSince = data.info.total_revenue['revenueSince'];
			this.totalNumberOfActiveVideos = data.info.total_number_of_active_videos;
			this.subcribedUser = data.info.subcribed_user;
			this.registerUser = data.info.register_user;
			this.dateWiseViewCount=data.info.total_view_count;

		},commentfail);

	}
	window.addEventListener('load', function () {
		this.hideLoader=true;
		}, false);
	this.languageChange = function() {

		var language = this.language

		requestFactory.post(requestFactory.getUrl('site/language'),{language}, function(response) {

				$window.location = requestFactory.getTemplateUrl('admin/dashboard');

		  });
	};

	this.fetchInfo();

}];
window.gridControllers = {DashboardController : DashboardController};


