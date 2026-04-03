$(document).ready(function () {
    //baseValidator.initateThroughJquery($('form[name="emailtemplateForm"]'), 'emailtemplateForm').setLocale(window.Mara.locale);
});
var emailPage = angular.module('emailPage', ["ui"]);
emailPage.directive('baseValidator', validatorDirective);
emailPage.factory('requestFactory', requestFactory);
var commonAPP = emailPage;

emailPage.controller('EmailController', ['$scope', '$rootScope', 'requestFactory', function ($scope, $rootScope, requestFactory) {

    $scope.languages = [];
    $scope.languageEmailTemplate = {};
    $scope.editEmailTemp = {};
    $scope.translationError = false,
        requestFactory.get(requestFactory.getUrl('emails/email-data/' + angular.element('span#inititate').html()), function (response) {
            $scope.emailData = {
                    name: response.response.name,
                    content: response.response.content,
                    subject: response.response.subject
                },
                $scope.languages = response.language,
                $scope.email_templates_translation = response.response.email_templates_translation,
                $scope.languages = response.language,
                $scope.editEmailTemp.language = parseInt(response.language[0].id);

            requestFactory.toggleLoader();
        }, $scope.fillError);
    $scope.errors = {};
    baseValidator.setRules(JSON.parse(angular.element('span#rules').html()));

    $scope.submitform = function ($event) {
        if (baseValidator.validateAngularForm($event.target, $scope)) {
            if (angular.element('span#inititate').html()) {
                requestFactory.post(requestFactory.getUrl('emails/edit/' + angular.element('span#inititate').html()), $scope.emailData, function (response) {
                    requestFactory.setToaster('success', response.message);    
                    location.href = requestFactory.getTemplateUrl('admin/emails');
                    },
                    function (resp) {
                        $scope.fillError(resp);
                    });
            }
        }
    }

    $scope.submitTranslationform = function (event) {
        $scope.languageEmailTemplate.languageCode = $scope.editEmailTemp.language;
        requestFactory.post(requestFactory.getUrl('emails/addLanguage/' + angular.element('span#inititate').html()), $scope.languageEmailTemplate, function (response) {
            requestFactory.setToaster('success', response.message);
            location.href = requestFactory.getTemplateUrl('admin/emails');
        }, function (e) {
            $scope.translationError = true;
            $scope.fillError(e);
        });

    }

    $scope.languageChange = function () {
        $scope.errors = [];
        if ($scope.editEmailTemp.language == $scope.languages[0].id) {
            $('#emailtemplateForm').css('display', 'block');
            $('#emailtemplateTranslationForm').css('display', 'none');

        } else {
            $scope.languageEmailTemplate = {};
            angular.forEach($scope.email_templates_translation, function (value) {
                if (value.language_id == $scope.editEmailTemp.language) {
                    $scope.languageEmailTemplate.languageCode = value.language_id;
                    $scope.languageEmailTemplate.name = value.name;
                    $scope.languageEmailTemplate.subject = value.subject;
                    $scope.languageEmailTemplate.content = value.content;
                }
            });
            $('#emailtemplateForm').css('display', 'none');
            $('#emailtemplateTranslationForm').css('display', 'block');
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
}]);

 /**
 * Manually merging this controller with Common Controller for fetching header data
 */
if(angular.isObject(window.gridControllers)){
    for(var controller in window.gridControllers){
        if(angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])){
         
            emailPage.controller(controller,window.gridControllers[controller]);
        }
    }
  }

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
    angular.bootstrap(document, ['emailPage']);
});