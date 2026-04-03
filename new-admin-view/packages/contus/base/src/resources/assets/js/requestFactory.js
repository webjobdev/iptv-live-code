'use strict';

var requestFactory = ['$http', '$rootScope', '$filter', '$window', function (http, rootScope, $filter, $window) {
  var self = this;
  /**
    * object property to get the date format
    * 
    * @var object
    */
  rootScope.getFormattedDate = function (date, type) {

    var type = angular.isDefined(type) ? type : 'date';
    return $filter('date')(new Date(date.replace(/ /g, 'T')), (type == 'datetime') ? requestHandler.getDateTimeFormat() : requestHandler.getDateFormat());
  };
  rootScope.toasterAlerts = {};
  var requestHandler = {
    /**
      * object property to object this reference
      * 
      * @var object
      */
    self: null,
    /**
      * object property to hold base api url
      * 
      * @var string
      */
    baseApiUrl: null,
    /**
      * object property to hold base Template url
      * 
      * @var string
      */
    baseTemplateUrl: null,
    /**
      * object property to hold default date format
      * 
      * @var string
      */
    baseDateFormat: 'dd-MM-yyyy',
    /**
      * object property to hold default datetime format
      * 
      * @var string
      */
    baseDateTimeFormat: 'dd-MM-yyyy HH:mm:ss',
    /**
      * object property to various request headers
      * 
      * @var object
      */
    headers: {},
    /**
      * object property to this argument
      * 
      * @var object
      */
    thisArgument: self,

    access_token: null,
    userpermissionList: {},
    /**
      * object property method to set the headers and base url from meta tags
      * 
      * @return object
      */
    boot: function () {
      this.getAccessToken();
      this.baseApiUrl = window.VPlay.route.apiUrl + 'api/admin';
      for (var i = 0, meta; meta = document.getElementsByTagName('meta')[i]; i++) {
        if (meta.name == 'csrf-token') {
          this.headers['X-CSRF-TOKEN'] = meta.content;
        }
        if (meta.name == 'base-api-url') {
          meta.content = this.baseApiUrl;
        }
        if (meta.name == 'base-template-url') {
          this.baseTemplateUrl = window.VPlay.route.viewURL;
          meta.content = this.baseTemplateUrl;
        }

        if (meta.name == 'public-access-token') {
          this.headers['X-PUBLIC-ACCESS-TOKEN'] = meta.content;
        }

        if (meta.name == 'user-id') {
          this.headers['X-USER-ID'] = meta.content;
        }

        this.headers['Authorization'] = 'Bearer ' + this.access_token;
        if (meta.name == 'access-token') {
          meta.content = 'Bearer ' + this.access_token;
        }
        if (meta.name == 's3bucketurl') {
          this.s3bucketurl = meta.content;
        }
        rootScope.currentUrl = this.baseTemplateUrl;
      }

      this.headers['X-WEB-SERVICE'] = true;
      this.headers['Accept'] = 'application/json';

      return this;

    },
    /**
     * object property method to get available headers
     * 
     * @return headers
     */
    setHeaders: function (key, value) {
      this.headers[key] = value;
    },
    /**
      * object property method to get available headers
      * 
      * @return headers
      */
    getHeaders: function () {
      return this.headers;
    },
    /**
      * object property method to get base api url
      * 
      * @return string
      */
    getBaseApiUrl: function () {
      return this.baseApiUrl;
    },
    /**
      * object property method to get base template url
      * 
      * @return string
      */
    getBaseTemplateUrl: function () {
      return this.baseTemplateUrl;
    },
    s3bucketurl: function () {
      return this.s3bucketurl;
    },
    /**
     * object property method to get auth userid
     * 
     * @return string
     */
    getAuthUserId: function () {
      return this.headers['X-USER-ID'];
    },
    /**
     * object property method to logged in status
     * 
     * @return string
     */
    isLoggedIn: function () {
      return (this.headers['X-USER-ID'] && this.headers['X-ACCESS-TOKEN']) ? true : false;
    },
    /**
     * object property method to get auth userid
     * 
     * @return string
     */
    getDateFormat: function () {
      return this.baseDateFormat;
    },
    /**
     * object property method to get datetime format
     * 
     * @return string
     */
    getDateTimeFormat: function () {
      return this.baseDateTimeFormat;
    },
    /**
     * object property method for get request
     * 
     * @param url
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    get: function (url, successCallback, errorCallback) {
      this.request({
        method: 'GET',
        url: url,
        headers: this.getHeaders(),
      }, successCallback, errorCallback);
    },
    /**
     * object property method for getTemplate request
     * 
     * @param url
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    getTemplate: function (url, successCallback, errorCallback) {
      this.request({
        method: 'GET',
        url: url,
      }, successCallback, errorCallback);
    },
    /**
     * object property method for post request
     * 
     * @param url
     * @param data
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    post: function (url, data, successCallback, errorCallback) {
      this.request({
        method: 'POST',
        url: url,
        headers: this.getHeaders(),
        data: data
      }, successCallback, errorCallback);
    },
    /**
     * object property method for put request
     * 
     * @param url
     * @param data
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    put: function (url, data, successCallback, errorCallback) {
      this.request({
        method: 'PUT',
        url: url,
        headers: this.getHeaders(),
        data: data
      }, successCallback, errorCallback);
    },
    /**
     * object property method for delete request
     * 
     * @param url
     * @param data
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    delete: function (url, data, successCallback, errorCallback) {
      this.request({
        method: 'DELETE',
        url: url,
        headers: this.getHeaders(),
        data: data
      }, successCallback, errorCallback);
    },
    /**
     * object property method for request
     * 
     * @param config
     * @param successCallback
     * @param errorCallback
     * @return string
     */
    request: function (config, successCallback, errorCallback) {
      rootScope.httpLoaderLocalElement = rootScope.httpLoaderLocalElement + 1
      http(config).then(this.successCallback(successCallback), this.errorCallback(errorCallback));
    },
    /**
     * object property method for set this argument
     * 
     * @param thisArgument
     * @return string
     */
    setThisArgument: function (thisArgument) {
      this.thisArgument = thisArgument;

      return this;
    },
    /**
     * object property method for callback the sucess method
     * 
     * @param callback
     * @return string
     */
    successCallback: function (callback) {
      return function (response) {
        if (typeof callback == 'function') {
          rootScope.httpLoaderLocalElement = rootScope.httpLoaderLocalElement - 1
          callback.call(requestHandler.thisArgument, response.data);
        }
      }
    },
    /**
     * object property method for callback the error method
     * 
     * @param callback
     * @return string
     */
    errorCallback: function (callback) {
      return function (response) {
        if (typeof callback == 'function') {
          rootScope.httpLoaderLocalElement = rootScope.httpLoaderLocalElement - 1
          callback.call(requestHandler.thisArgument, response);
        }
      }
    },
    /**
     * object property method for build the query
     * 
     * @param queryParams
     * @return string
     */
    buildQueryParams: function (queryParams) {
      var params = false;
      var queryLength = Object.keys(queryParams).length;
      var i = 1;


      for (var iter in queryParams) {
        if (typeof queryParams[iter] != 'undefined') {
          if (!params) {
            params = '?';
          }

          params += iter + '=' + queryParams[iter];

          if (i < queryLength) {
            params += '&';
          }

          i++;
        }
      }

      return params;
    },
    /**
     * object property method for get the url
     * 
     * @param path
     * @param queryParams
     * @return string
     */
    getUrl: function (path, queryParams) {
      var url = this.getBaseApiUrl() + '/' + path;
      return (queryParams) ? url + this.buildQueryParams(queryParams) : url;
    },
    /**
     * object property method for get the template url
     * 
     * @param path
     * @param queryParams
     * @return string
     */
    getTemplateUrl: function (path, queryParams) {
      var url;
      url = window.VPlay.route.viewURL + path;
      return (queryParams) ? url + this.buildQueryParams(queryParams) : url;
    },
    /**
        }
       // var url = this.getBaseTemplateUrl()+'/'+path;

        return (queryParams) ? url+this.buildQueryParams(queryParams) : url;
    },
    /**
     * object property method for get the active session
     * 
     * @return string
     */
    isActiveSession: function () {
      var userId = this.getAuthUserId();

      return angular.isDefined(userId) && userId != null;
    },
    /**
     * object property method for get the info url
     * 
     * @return string
     */
    getInfoUrl: function () {
      var path = 'info';
      var model = this.getModelId();

      if (model) {
        path = path + '/' + this.getModelId();
      }

      return this.getUrl(path, { query: this.getQueryParam() });
    },
    /**
     * object property method to find the property is mobile or not
     * 
     * @return string
     */
    isMobile: function () {
      return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 800);
    },
    /**
     * Function to show and hide the loader image.
     */
    toggleLoader: function (grid) {
      var gridList = grid || 0;
      var mainPanel = document.getElementsByClassName('mainpanel');
      if (mainPanel.length > 0) {
        var mainContent = document.getElementsByClassName('mainpanel')[0];
        if (mainContent.className.indexOf('show-content') == -1 || gridList) {
          mainContent.classList.add("show-content");
        }
        else {
          mainContent.classList.remove("show-content");
        }
      }
    },
    getLength: function (obj) {
      if (obj) {
        return Object.keys(obj).length;
      }
    },
    /**
     * Function is used to show pagination link
     * 
     * @param object
     *            scope
     * @param int
     *            totalLinks
     * @return void
     */
    paginate: function (scope, totalLinks) {
      scope.links = [];
      var counterLimit;
      if (scope.currentPage > totalLinks) {
        return false;
      }
      var counter = Math.floor(scope.currentPage / 5);
      if (counter == 0) {
        counter = 1;
      }
      else {
        counter = counter * 5;
      }
      if ((totalLinks - counter) >= 5) {
        counterLimit = counter + 5;
      }
      else {
        counterLimit = totalLinks;
      }
      var initialCounter = counter + 5;
      if ((scope.currentPage > 1) && (totalLinks > 1)) {
        scope.links.push({ value: 'Previous', pageNumber: scope.currentPage - 1, current: false });
      }
      /*
           * if((counter >= 5 ) && (totalLinks > 1) ) {
           * scope.links.push({value:'First',pageNumber:1, current:false }); }
           */
      if ((counter >= 4) && (totalLinks > 1)) {
        scope.links.push({ value: 'First', pageNumber: 1, current: false });
      }
      for (counter; counter <= counterLimit; counter++) {

        if (scope.currentPage == counter) {
          scope.links.push({ value: counter, pageNumber: counter, current: true });
        }
        else {
          scope.links.push({ value: counter, pageNumber: counter, current: false });
        }
      }

      if ((initialCounter < totalLinks - 1) && totalLinks > 1) {
        scope.links.push({ value: '...', pageNumber: null, current: false });
        scope.links.push({ value: totalLinks - 1, pageNumber: totalLinks - 1, current: false });
        scope.links.push({ value: totalLinks, pageNumber: totalLinks, current: false });
        scope.links.push({ value: 'Next', pageNumber: scope.currentPage + 1, current: false });
      }
      /* latest */
      else if ((initialCounter == totalLinks - 1) && totalLinks > 1) {
        scope.links.push({ value: totalLinks, pageNumber: totalLinks, current: false });
        scope.links.push({ value: 'Next', pageNumber: scope.currentPage + 1, current: false });
      }
      else if (scope.currentPage != totalLinks && totalLinks > 1) {
        scope.links.push({ value: 'Next', pageNumber: scope.currentPage + 1, current: false });
      }
      else {
        //
      }
    },
    setAccessToken: function (data) {
      localStorage.setItem("access_token", data);
    },
    getAccessToken: function () {
      this.access_token = localStorage.getItem('access_token');
    },
    capitalize: function (string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    setToaster: function (type, msg) {
      sessionStorage.toasterAlertClass = type;
      sessionStorage.toasterAlertMsg = msg;
    },
    getToaster: function () {
      $('.response-msg').html('<div class="alert alert-' + sessionStorage.toasterAlertClass + '" style="display:none"><span id="coupon-msg">' + sessionStorage.toasterAlertMsg + '</span></div>');
      angular.element('.alert-success').fadeIn(1000).delay(2000).fadeOut(1000);
      delete sessionStorage.toasterAlertClass;
      delete sessionStorage.toasterAlertMsg;
    },

    // setUserPermission: function (data){
    //   localStorage.setItem("user_permissions", data);
    // },
    // hasPermission: function(permission) {
    //   let userPermissions = localStorage.getItem('user_permissions');
    //   if (userPermissions) {
    //     userPermissions = JSON.parse(userPermissions)
    //   }
    //   this.userpermissionList = userPermissions;
    //   if(permission != undefined ){
    //     if(this.userpermissionList != undefined && this.userpermissionList.hasOwnProperty(permission)){
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   }
    // }

    setUserPermission: function (data) {
      // Make sure data is a string
      localStorage.setItem("user_permissions", JSON.stringify(data));
    },
    
    
    hasPermission: function (permission) {
      let userPermissions = localStorage.getItem('user_permissions');

      if (userPermissions) {
        try {
          userPermissions = JSON.parse(userPermissions);
        } catch (e) {
          console.error("Invalid JSON in user_permissions");
          return false;
        }
      } else {
        return false;
      }

      this.userpermissionList = userPermissions;

      // Super Admin bypass
      if (userPermissions && userPermissions.all === true) {
        return true;
      }

      if (permission !== undefined && userPermissions) {
        if (Array.isArray(userPermissions)) {
          // Handle array of objects with permissions property
          const allPermissions = userPermissions.flatMap(p => p.permissions || []);
          if (allPermissions.length > 0) {
              return allPermissions.includes(permission);
          }
          // Handle array of strings
          return userPermissions.includes(permission);
        } else if (typeof userPermissions === 'object') {
          // Handle existing object-based permissions if any
          return !!userPermissions[permission];
        }
      }

      return false;
    }
  };
  return requestHandler.boot();
}];