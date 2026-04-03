'use strict';
var adminGroup = angular.module('adminGroup',[]);
adminGroup.factory('requestFactory',requestFactory);
var commonAPP = adminGroup;
adminGroup.controller('adminGroupController',['$window','$scope','$rootScope','requestFactory','$sce','$timeout',function(win,scope,$rootScope,requestFactory,$sce,$timeout){
 
 requestFactory.toggleLoader();

}]);
 /**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
    for(var controller in window.gridControllers){
        if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
          
            adminGroup.controller(controller,window.gridControllers[controller]);
        }
    }
}

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
	angular.bootstrap(document, ['adminGroup']);
});
