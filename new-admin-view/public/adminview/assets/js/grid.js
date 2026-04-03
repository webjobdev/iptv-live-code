'use strict';

var grid = angular.isObject(window.gridInitApp)?window.gridInitApp:angular.module('grid', []);
grid.factory('requestFactory',requestFactory);
grid.directive("datepicker", function () {
	  return {
	    restrict: "A",
	    require: "ngModel",
	    link: function (scope, elem, attrs, ngModelCtrl) {
	      var updateModel = function (dateText) {
	        scope.$apply(function () {
	          ngModelCtrl.$setViewValue(dateText);
	        });
	      };
	      var options = {
	        dateFormat: "yy-mm-dd",
	        onSelect: function (dateText) {
	          updateModel(dateText);
	        }
	      };      
	      elem.datepicker(options);
	    }
	  }
});

grid.directive('bootTooltip',function(){
	return {
		restrict: 'A',
		link    : function(scope, element, attrs){
			 try {
				$(element).tooltip(); 
			 } catch(error){}
		}
	}
});



grid.directive('onFinishRender',['$timeout',function($timeout){
	return {
		restrict: 'A',
		link    : function(scope, element, attrs){
			if (scope.$last === true) {
				$timeout(function(){$( document ).trigger( "enhance.tablesaw" );},1000);				
			}
		}
	}
}]);
grid.directive('onFinishRenderedRecords',['$timeout',function($timeout){
	return {
		restrict: 'A',
		link    : function(scope, element, attrs){
			if (scope.$last === true) {
				$('.tablesaw-advance').remove();
				$('.tablesaw-toolbar').remove();
				$timeout(function(){$( document ).trigger( "enhance.tablesaw" );},1000);				
			}
		}
	}
}]);
grid.directive('onError', function() {  
	return {
		restrict:'A',
		link: function(scope, element, attr) {
		element.on('error', function() {
			element.attr('src', attr.onError);
		})
		}
	}
});
grid.directive('bgImage', function () {
    return {
        link: function(scope, element, attr) {
            attr.$observe('bgImage', function() {           
              if (!attr.bgImage) {
                 // No attribute specified, so use default
                 element.css("background-image","url("+attr.onErrorSrc+")");
              } else {
                 var image = new Image();  
                 image.src = attr.bgImage;
                 image.onload = function() { 
                    //Image loaded- set the background image to it
                    element.css("background-image","url("+attr.bgImage+")");
                 };
                 image.onerror = function() {
                    //Image failed to load- use default
                    element.css("background-image","url("+attr.onErrorSrc+")");
                 };
             }
         });
      }
    };
});
grid.filter('trusted', ['$sce', function ($sce) {
	return $sce.trustAsResourceUrl;
}]);

/**
* Define all grid directives
*/
if(angular.isObject(window.gridDirectives)){
	for(var directive in window.gridDirectives){
		if(angular.isArray(window.gridDirectives[directive]) || angular.isFunction(window.gridDirectives[directive])){
			grid.directive(directive,window.gridDirectives[directive]);
		}
	}
}
/**
* Define all grid filters
*/
if(angular.isObject(window.gridFilters)){
    for(var filter in window.gridFilters){
        if(angular.isArray(window.gridFilters[filter]) || angular.isFunction(window.gridFilters[filter])){
            grid.filter(filter,window.gridFilters[filter]);
        }
    }
}
/**
* Define all grid controllers
*/
if(angular.isObject(window.gridControllers)){
	for(var controller in window.gridControllers){
		if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
			grid.controller(controller,window.gridControllers[controller]);
		}
	}
}

/**
* Define all grid directives
*/
grid.directive('gridView',window.gridView);
/**
* Manually bootstrap the Angular module here
*/
 angular.element(document).ready(function() {
    angular.bootstrap(document, ['grid']);
 });