'use strict';

var LanguagesGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.language = {};
    this.showartist = true;
    scope.errors = {};
    requestFactory.setThisArgument(this);
  

    this.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    scope.errors[key] = {
                        has: true,
                        message: message[0]
                    };
                }
            });
        }
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('languages/info'), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
         });
    };

    this.fetchInfo();
    /**
     *  Function is used to add the artist
     *  
     *  @param  $event
     */
    this.addLanguage = function (event) {
        $(".sidepanel").addClass("in"); 
        this.language = {};
        this.language.id = '';
        this.language.language_name = '';
        this.language.order = '';
        this.language.is_active = String(1);
        scope.errors = {};
    }

    /**
     *  Function is used to edit the artists
     *  
     *  @param array records
     */
    this.editLanguage = function (records) {
        $(".sidepanel").addClass("in"); 
        scope.errors = {};
        this.language.id = records.id;
        this.language.language_name = records.language_name;
        this.language.order = records.order;
        this.language.is_active = String(records.is_active);

    }
    /**
     *  Function is used to save the artist
     *  
     *  @param  $event, id
     */
    this.languageSave = function ($event, id) {
        if (id) {
            requestFactory.post(requestFactory.getUrl('languages/edit/' + id), this.language, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                scope.closesidePanelForm();

            }, this.fillError);
        } else {
            requestFactory.post(requestFactory.getUrl('languages/add'), this.language, function (response) {
                scope.responseMessage = response.message;
                scope.showResponseMessage = true;
                scope.getRecords(true);
                scope.closesidePanelForm();
            }, this.fillError);
        }
    }
    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
    });
}];

window.gridControllers = {
    LanguagesGridController: LanguagesGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};