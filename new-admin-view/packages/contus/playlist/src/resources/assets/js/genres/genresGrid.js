'use strict';

var GenresGridController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.genre = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.showgenre = true;
    scope.errors = {};
    this.selectedRecords = [];

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

    this.closeGenreEdit = function () {
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
    };

    this.defineProperties = function (data) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('genres/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();


    /**
     *  Function is used to add the genre
     *  
     *  @param  $event
     */

    this.addGenre = function (event) {
        this.genre = {};
        this.genre.id = '';
        this.genre.genre_name = '';
        this.genre.order = '';
        this.genre.is_active = String(1);
        scope.errors = {};
    }

    /**
     *  Function is used to edit the genre
     *  
     *  @param array records
     */

    this.editGenre = function (records) {
        scope.errors = {};
        this.genre.id = records.id;
        this.genre.genre_name = records.genre_name;
        this.genre.order = records.order;
        this.genre.is_active = String(records.is_active);
    }


    /**
     *  Function is used to save the genre
     *  
     *  @param  $event, id
     */

    this.genreSave = function ($event, id) {
        if (id) {
            requestFactory.post(requestFactory.getUrl('genres/edit/' + id), this.genre, function (response) {
                this.responseMessage = response.message;
                this.showResponseMessage = true;
                scope.getRecords(true);
                this.closeGenreEdit();

            }, this.fillError);
        } else {
            requestFactory.post(requestFactory.getUrl('genres/add'), this.genre, function (response) {
                this.responseMessage = response.message;
                this.showResponseMessage = true;
                scope.getRecords(true);
                this.closeGenreEdit();
            }, this.fillError);
        }


    }

    /**
     * Function to update status of a genre
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'genres';
        scope.updateStatus(record);
    };

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
    });
    /**
     * Function to select and unselect all checkboxes.
     */
    this.selectAllRecords = function () {
        self.selectedRecords = requestFactory.selectBulkRecords();
    };
}];

window.gridControllers = {
    GenresGridController: GenresGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};