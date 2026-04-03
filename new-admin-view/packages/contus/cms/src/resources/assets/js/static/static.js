$(document).ready(function () {
    baseValidator.initateThroughJquery($('form[name="staticContentForm"]'), 'staticContentForm');
});
'use strict';
var staticPage = angular.module('staticPage', ["ui"]);
staticPage.directive('baseValidator', validatorDirective);
staticPage.factory('requestFactory', requestFactory);
var commonAPP = staticPage;
var commonModule = staticPage;
staticPage.controller('StaticController', ['$scope', '$rootScope', 'requestFactory', function ($scope, $rootScope, requestFactory) {
    $scope.is_translation_form = {},
        $scope.languages = {},
        $scope.staticContent = {},
        $scope.staticTranslationData = {},
        $scope.static_pages_translation = {},
        $scope.translationError = false,
        requestFactory.get(requestFactory.getUrl('static-content/static-data/' + angular.element('span#inititate').html()), function (response) {
            $scope.staticData = {
                    title: response.response.title,
                    content: response.response.content
                },
                $scope.staticData.content = response.response.content;
                $scope.languages = response.language,
                $scope.staticContent.language = parseInt(response.language[0].id),
                $scope.static_pages_translation = response.response.static_pages_translation,
                $scope.staticData.is_footer_menu = (response.response.is_footer_menu == 0) ? false : true;
            baseValidator.setRules(response.rules);
            requestFactory.toggleLoader();
        }, $scope.fillError);
    $scope.errors = {};
    baseValidator.setRules(JSON.parse(angular.element('span#rules').html()));

    $scope.submitform = function ($event) {
        if (baseValidator.validateAngularForm($event.target, $scope)) {
            if (angular.element('span#inititate').html()) {
                //$scope.staticData.is_footer_menu = ($scope.staticData.is_footer_menu == true) ? 1 : 0;
                requestFactory.post(requestFactory.getUrl('static-content/edit/' + angular.element('span#inititate').html()), $scope.staticData, function (response) {
                    requestFactory.setToaster('success', response.message);
                    location.href = requestFactory.getTemplateUrl('admin/static-content');
                }, function (resp) {
                    $scope.fillError(resp);
                });
            }
        }
    }
    /**
     *  Functtion is used to fill the error
     *  
     */

    $scope.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    if ($scope.translationError == true) {
                        console.log('here');
                        $scope.errors['trans_' + key] = {
                            has: true,
                            message: message[0]
                        };
                    } else {
                        $scope.errors[key] = {
                            has: true,
                            message: message[0]
                        };
                    }

                }
            });
        }
    };

    $scope.languageChange = function () {
        $scope.translationError = false;
        $scope.errors = [];
        if ($scope.staticContent.language == $scope.languages[0].id) {
            $("#staticContentForm").css('display', 'block');
            $("#staticContentTranslationForm").css('display', "none");
        } else {

            $scope.staticTranslationData.title = '';
            $scope.staticTranslationData.content = '';
            angular.forEach(this.static_pages_translation, function (value) {
                if (value.language_id == $scope.staticContent.language) {
                    $scope.staticTranslationData.title = value.title;
                    $scope.staticTranslationData.content = value.content;
                }
            });

            $("#staticContentForm").css('display', 'none');
            $("#staticContentTranslationForm").css('display', 'block');
        }
    };



    $scope.submitTranslationform = function (event) {
       
        $scope.staticTranslationData.languageCode = $scope.staticContent.language;
        requestFactory.post(requestFactory.getUrl('static-content/addLanguage/' + angular.element('span#inititate').html()), $scope.staticTranslationData, function (response) {
            requestFactory.setToaster('success', response.message);
            location.href = requestFactory.getTemplateUrl('admin/static-content');

        }, function (e) {
            $scope.translationError = true;
            $scope.fillError(e);
        });
    
    };
}]);


/**
* Manually merging this controller with Common Controller for fetching header data
*/
if(angular.isObject(window.gridControllers)){
    for(var controller in window.gridControllers){
        if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
            window.gridControllers[controller].hideHeader=true;
            staticPage.controller(controller,window.gridControllers[controller]);
        }
    }
}

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
    angular.bootstrap(document, ['staticPage']);
});