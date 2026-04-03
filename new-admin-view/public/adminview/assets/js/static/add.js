'use strict';
var staticAddPage = angular.module( 'staticAddPage', [] );
var commonAPP = staticAddPage;
staticAddPage.directive( 'baseValidator', validatorDirective );
staticAddPage.factory( 'requestFactory', requestFactory );
staticAddPage.controller( 'StaticContentAddController', ['$scope','$rootScope','requestFactory',function ( $scope, $rootScope, requestFactory ) {
    var self = this;
    this.info = {};
    $scope.errors = {};
    requestFactory.setThisArgument(this);
    this.staticData = {};

    this.defineProperties = function ( data ) {
        this.info = data.info;
        requestFactory.toggleLoader();
        this.staticData.is_footer_menu = true;
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'static-content/info' ), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
        } );
        

    };

    this.fetchInfo();

    /**
     *  Function is used to save the static content
     *  
     */
    this.submitform = function ($event) {
        if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
            //this.staticData.is_footer_menu = (this.staticData.is_footer_menu == true)?1:0;
                requestFactory.post( requestFactory.getUrl( 'static-content/add' ), this.staticData, function ( response ) {
                    requestFactory.setToaster('success', response.message);
                    this.staticData.is_footer_menu = (this.staticData.is_footer_menu == 1)?true:false;
                    location.href = requestFactory.getTemplateUrl( 'admin/static-content' ) ;
                }, this.fillError );
        }
    }


    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    $scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    /**
 * Manually merging this controller with Common Controller for fetching header data
 */

}] );


/**
* Manually merging this controller with Common Controller for fetching header data
*/

if(angular.isObject(window.gridControllers)){
    for(var controller in window.gridControllers){
        if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
          
            staticAddPage.controller(controller,window.gridControllers[controller]);
        }
    }
}

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
    angular.bootstrap(document, ['staticAddPage']);
});