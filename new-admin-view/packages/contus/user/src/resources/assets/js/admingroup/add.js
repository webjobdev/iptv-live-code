'use strict';
var adminGroup = angular.module('adminGroup',[]);
adminGroup.factory('requestFactory',requestFactory);
adminGroup.directive('baseValidator',validatorDirective);
var commonAPP = adminGroup;
adminGroup.controller('adminGroupController',['$window','$scope','$rootScope','requestFactory','$sce','$timeout',function(win,scope,$rootScope,requestFactory,$sce,$timeout){
    var self = this;
    this.accessModules = {};
    this.adminGroupData = {};
    this.adminGroupData.permissions = [];
    scope.errors = {};
    requestFactory.toggleLoader();
    this.defineProperties = function (data) {
        baseValidator.setRules(data.info.rules);
        self.accessModules = data.access_modules;
        var id = angular.element('#formid').html();
        self.fetchData(id);
    };
    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('admingroup/info'), this.defineProperties, function () { });
    };
    this.fetchInfo();
    this.fetchData = function (id) {
        requestFactory.get(requestFactory.getUrl('groups/edit_info/' + id), function (response) {
            self.adminGroupData.name = response.data.group_info.name;
            let grpPermissions = response.data.key_info;
            let grpPermissionsArr = Object.keys( grpPermissions);
            angular.forEach(self.accessModules, function(item, key){  
                if(grpPermissionsArr.indexOf(item.access_name) !== -1){
                    item.Selected = true;
                }
                self.toggleChildItem(item, grpPermissionsArr);
            });
          });
    };
    this.toggleChildItem = function(item, grpPermissionsArr) {
        if(item.hasOwnProperty('children')){
            angular.forEach(item.children, function(childItem, key){
                if(grpPermissionsArr.indexOf(childItem.access_name) !== -1){
                    childItem.Selected = true;
                }
                if(typeof childItem === "object"){
                    self.toggleChildItem(childItem,grpPermissionsArr);
                }
            });
        }
    };
    this.toggleAllCheckboxes = function($event, $childrenItems = null, parentItem = null, grandparentItem = null){
        var selected;
        selected = $event.target.checked;
        if(parentItem !== null){
            this.toggleParentItem(selected, parentItem);
        }
        if(grandparentItem !== null){
            this.toggleParentItem(selected, grandparentItem);
        }
        angular.forEach($childrenItems, function(item, key){
            item.Selected = selected;
            if(item.hasOwnProperty('children') && item.children.length !== 0) {
                self.toggleAllCheckboxes($event, item.children);
            }
        })
    }
    this.toggleParentItem = function(selected, item){
        var result;
        if(item.hasOwnProperty('children') && item.children.length > 0){
            angular.forEach(item.children, function(childItem, key){
                if(childItem.Selected === true){
                    result = true;
                }
            });
        }
        item.Selected = result;
    }
    this.extractAccess = function(data){
        angular.forEach(data, function(val,key){
            if(val.Selected === true){ 
                if(self.adminGroupData.permissions.indexOf(val.access_name) === -1){
                    self.adminGroupData.permissions.push(val.access_name);
                }
                if(val.hasOwnProperty('children') && val.children.length > 0){
                    self.extractAccess(val.children);
                }
            } 
            else if(!val.Selected || val.Selected === false){
                var index = self.adminGroupData.permissions.indexOf(val.access_name);
                if (index !== -1) {
                    self.adminGroupData.permissions.splice(index,1);
                 }
                if(val.hasOwnProperty('children') && val.children.length > 0){
                    self.extractAccess(val.children);
                }
            }
        });
    }   
    this.submit = function($event, id = null){
        this.extractAccess(this.accessModules);
           if(id){
            requestFactory.post(requestFactory.getUrl('groups/edit/'+id), this.adminGroupData, function (response) {
                requestFactory.setToaster('success', response.message);
                window.location = window.VPlay.route.viewURL+'admin/groups';              
              }, this.fillError);
           } else {
            requestFactory.post(requestFactory.getUrl('groups/add'), this.adminGroupData, function (response) {
                requestFactory.setToaster('success', response.message);
                window.location = window.VPlay.route.viewURL+'admin/groups';              
            }, this.fillError);
        }
    };
    this.fillError = function (response) {
        if (response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                    if (scope.translationError == true) {
                        scope.errors['trans_' + key] = {
                            has: true,
                            message: message[0]
                        };
                    } else {
                        scope.errors[key] = {
                            has: true,
                            message: message[0]
                        };
                    }
            });
        }
    };

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




