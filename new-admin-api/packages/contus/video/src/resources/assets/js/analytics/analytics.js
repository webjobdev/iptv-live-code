'use strict';

var AnalyticsController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,rootScope,requestFactory,$window,$sce,$timeout,$compile,$interval){
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
		this.topBrowser = defaultData;
		this.total_visitor = defaultData;
		}

	this.getVideoStatsBlocks = function(){
		requestFactory.get(requestFactory.getUrl('analytics/getdashboardvideostats'),this.defineVideoStats,function(){});
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
		this.top_browsers = statsData.info.fetch_top_browsers;
		this.total_visitors = statsData.info.total_visitors;

		var visitor = 0;
		var pageView = 0;
		angular.forEach(statsData.info.total_visitors, function(value, key) {	
			visitor += value.visitors;
			pageView += value.pageViews;
		});
		this.visitors_count = visitor;
		this.page_views = pageView;
		this.user_types = statsData.info.user_types;
		this.userTypes('analytics-user-types', this.user_types[0].sessions,this.user_types[1].sessions);
	}

	this.userTypes = function(id, x,y)
	{
		// Make monochrome colors
		var pieColors = (function () {
			var colors = [],
				base = Highcharts.getOptions().colors[0],
				i; 

			for (i = 0; i < 10; i += 1) {
				// Start out with a darkened base color (negative brighten), and end
				// up with a much brighter color
				colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
			}
			return colors;
		}());

		// Build the chart
		Highcharts.chart(id, {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: ''
			},
			credits: {
        enabled: false
    	},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					colors: pieColors,
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
						distance: -50,
						filter: {
							property: 'percentage',
							operator: '>',
							value: 4
						}
					}
				}
			},
			series: [{
			name: 'sessions',
			data: [
				{ name: 'New Visitor', y: x },
				{ name: 'Returning Visitor', y: y }
			]
		}]
		});
	}



    this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('dashboard/info'),this.defineProperties,function(response){
		rootScope.redirectUnauthenticated(response);
	  });

	};


	

	this.applyDateFilter = function(type,item){
		if(type == 'regionwise'){
			this.applyDateFilterOnRegionWise(item);
		}
		if(type=='platformwise'){
			this.applyDateFilterOnPlatformWise(item);
		}
		if(type == 'topBrowser') {
			this.applyDateFilterOnTopBrowser(item);
		}
	}

	this.applyDateFilterOnTopBrowser = function(item) {
		requestFactory.get(requestFactory.getUrl('analytics/get-top-browsers/' + item ),function(data) {
			this.top_browsers = data.top_browsers;
		});
	}
	this.applyDateFilterOnRegionWise = function(item){
		requestFactory.get(requestFactory.getUrl('analytics/get-user-types/' + item ),function(data) {
			this.user_types = data.user_types;
			console.log(this.user_types);
			this.userTypes('analytics-user-types', this.user_types[0].sessions,this.user_types[1].sessions);
		});
	};
	

	this.changeTotalVisitor = function (item){
		requestFactory.get(requestFactory.getUrl('analytics/get-total-visitors/' + item ),function(data) {
			this.total_visitors = data.total_visitors;
			var visitor = 0;
			var pageView = 0;
			angular.forEach(data.total_visitors, function(value, key) {	
				visitor += value.visitors;
				pageView += value.pageViews;
			});
			this.visitors_count = visitor;
			this.page_views = pageView;
			
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
window.gridControllers = {AnalyticsController : AnalyticsController};


