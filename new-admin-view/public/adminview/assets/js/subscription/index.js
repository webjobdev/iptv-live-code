'use strict';

var subscriptionPlanController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', function (scope, rootScope, requestFactory, $window, $sce, $timeout) {
  var self = this;
  this.subscriptions_plans = {};
  this.languages = {};
  this.subscribeTranslation = {};
  requestFactory.setThisArgument(this);
  requestFactory.getToaster();

  /**
   *  To get the auth id
   *  
   */
  this.setQuery = function ($authId) {
    this.authId = $authId;
  }

  /**
   *  Function is used to add the latest news
   *  @param $event
   */
  this.addSubscriptionsPlans = function ($event) {
    $(".sidepanel").addClass("in"); 
    scope.errors = {};
    this.subscriptions_plans = {};
    this.subscriptions_plans.is_active = true;
    $("#subscriptionForm").css('display', 'block');
    $("#subscriptionTranslationForm").css('display', "none");
  }

  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */
  this.editSubscriptionsPlans = function (records) {
    $(".sidepanel").addClass("in");
    scope.errors = {};
    this.subscriptions_plans.id = records.id;
    this.subscriptions_plans.name = records.name;
    this.subscriptions_plans.type = records.type;
    this.subscriptions_plans.description = records.description;
    this.subscriptions_plans.amount = records.amount;
    this.subscriptions_plans.amount_israel = records.amount_israel;
    this.subscriptions_plans.no_of_device = records.no_of_device;
    this.subscriptions_plans.duration = records.duration;
    //this.subscriptions_plans.trial = (records.trial) ? true : false;
    if (records.amount === "0" || records.amount === 0) {
      this.subscriptions_plans.trial = 1;
    } else {
      this.subscriptions_plans.trial = 0;
    }
    this.subscriptions_plans.is_active = (records.is_active) ? true : false;
    this.subscribe_translation = records.subscription_translation;
    this.subscribeTranslation.language = parseInt(this.languages[0].id);
    $("#subscriptionForm").css('display', 'block');
    $("#subscriptionTranslationForm").css('display', "none");
  }

  this.fillError = function (response) {
    if (response.status == 422 && response.data.hasOwnProperty('message')) {
      angular.forEach(response.data.message, function (message, key) {
        if (typeof message == 'object' && message.length > 0) {
          // scope.errors[key] = {has : true , message : message[0]};
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
        }
      });
    }
  };

  this.languageChange = function () {
    scope.errors = [];
    if (this.subscribeTranslation.language == this.languages[0].id) {
      $("#subscriptionForm").css('display', 'block');
      $("#subscriptionTranslationForm").css('display', "none");
    } else {
      // self.subscribeTranslation = {};
      // this.subscribeTranslation.language = String(this.languages[0].id);
      self.subscribeTranslation.name = '';
      self.subscribeTranslation.type = '';
      self.subscribeTranslation.description = '';
      angular.forEach(this.subscribe_translation, function (value) {
        if (value.language_id == self.subscribeTranslation.language) {
          self.subscribeTranslation.languageCode = value.language_id;
          self.subscribeTranslation.name = value.name;
          self.subscribeTranslation.type = value.type;
          self.subscribeTranslation.description = value.description;
        }
      });
      $("#subscriptionForm").css('display', 'none');
      $("#subscriptionTranslationForm").css('display', 'block');
    }
  }

  /**
   *  Function is used to save the latestnews
   *  
   *  @param $event,id
   */
  this.save = function ($event, id) {
    if (baseValidator.validateAngularForm($event.target, scope)) {
      if (id) {
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/edit/' + id), this.subscriptions_plans, function (response) {
          scope.getRecords(true);
          requestFactory.setToaster('success', response.message);
          requestFactory.getToaster();
          this.closeSubscriptionEdit();
          $timeout(function () {
            self.subscriptions_plans = {};
          }, 100);

        }, this.fillError);

      } else {
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/add'), this.subscriptions_plans, function (response) {
          scope.getRecords(true);
          requestFactory.setToaster('success', response.message);
          requestFactory.getToaster();
          this.closeSubscriptionEdit();
        }, this.fillError);
      }
    }
  }

  this.saveTranslation = function (event, id) {
    this.subscribeTranslation.languageCode = this.subscribeTranslation.language;
    requestFactory.post(requestFactory.getUrl('subscriptions-plans/addLanguage/' + id), this.subscribeTranslation, function (response) {
      requestFactory.setToaster('success', response.message);
      requestFactory.getToaster();

      var myIndex = scope.records.map(function (obj) {
        return obj.id;
      }).indexOf(id);
      var langIndex = scope.records[myIndex].subscription_translation.map(function (obj) {
        return obj.language_id;
      }).indexOf(parseInt(this.subscribeTranslation.language));
      langIndex = (langIndex >= 0) ? langIndex : 0;

      scope.records[myIndex].subscription_translation[langIndex] = {
        language_id: this.subscribeTranslation.language,
        name: this.subscribeTranslation.name,
        type: this.subscribeTranslation.type
      };
      this.closeSubscriptionEdit();
    }, function (e) {
      scope.translationError = true;
      this.fillError(e);
    });


  };



  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeSubscriptionEdit = function () {
    scope.gridSideFormClose();
    /*var container = document.getElementById( 'st-container' )
    classie.remove( container, 'st-menu-open' );*/
  };

  this.defineProperties = function (data) {
    this.info = data.info;
    this.languages = data.info.language;
    baseValidator.setRules(data.info.rules);
    requestFactory.toggleLoader();
  };

  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('subscriptions-plans/info'), this.defineProperties, function () {
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.fetchInfo();


  /**
   *  Listen to the records to update property
   *  
   */
  scope.$on('afterGetRecords', function (e, data) {
    if (angular.isUndefined(scope.searchRecords.is_active)) {
      scope.searchRecords.is_active = 'all';
    }
    setTimeout(function () {
      $("#fixTable").tableHeadFixer({ "head": false, "right": 1 });
    }, 500);
  });

}];

window.gridControllers = { subscriptionPlanController: subscriptionPlanController };
window.gridDirectives = {
  baseValidator: validatorDirective,
  intializeSidebar: intializeSidebar
};

$(document).ready(function () {
  var loader = $('#preloader');
  loader.find('#status').css('display', 'none');
  loader.css('display', 'none');
});