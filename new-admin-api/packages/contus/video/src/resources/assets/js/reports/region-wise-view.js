'use strict';
var RegionWiseViewController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    requestFactory.toggleLoader();
    this.info = {};
    scope.errors = {};
    requestFactory.setThisArgument(this);
}];
window.gridControllers = {RegionWiseViewController : RegionWiseViewController};