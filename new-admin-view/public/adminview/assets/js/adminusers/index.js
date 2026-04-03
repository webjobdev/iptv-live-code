'use strict';

var UserController = [
  '$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout',
  function (scope, rootScope, requestFactory, $window, $sce, $timeout) {

    var self = this;
    this.user = {};

    requestFactory.setThisArgument(this);
    requestFactory.getToaster();

    this.setQuery = function ($authId) {
      this.authId = $authId;
    };

    this.addUser = function ($event) {
      scope.errors = {};
      // this.user = {
      //   name: '',
      //   email: '',
      //   phone: '',
      //   gender: '',
      //   is_active: true,
      //   user_group_id: "1"
      // };
    };

    this.editUser = function (records) {
      $(".sidepanel").addClass("in");
      scope.errors = {};
      this.user = {
        id: records.id,
        name: records.name,
        email: records.email,
        phone: parseInt(records.phone),
        is_active: records.is_active ? true : false,
        user_group_id: String(records.user_group_id),
        gender: String(records.gender)
      };
      $('.sidepanel .overlay, .sidepanel .save').one('click', () => {
        this.user = {};
      });
    };

    this.fillError = function (response) {
      $('#loaderimg').hide();
      if (response.status === 422 && response.data.message) {
        angular.forEach(response.data.message, function (message, key) {
          scope.errors[key] = {
            has: true,
            message: requestFactory.capitalize(message[0])
          };
        });
      }
    };

    this.save = function ($event, id) {
      if (!baseValidator.validateAngularForm($event.target, scope)) return;

      $('#loaderimg').show();

      var url = id
        ? requestFactory.getUrl('users/edit/' + id)
        : requestFactory.getUrl('users/add');

      requestFactory.post(url, this.user, function (response) {
        requestFactory.toggleLoader();
        scope.getRecords(true);
        requestFactory.setToaster('success', response.message);
        $('#loaderimg').hide();
        self.closeUserEdit();
        self.user = {};
      }, this.fillError);
    };

    this.closeUserEdit = function () {
      scope.gridSideFormClose();
    };

    this.defineProperties = function (data) {
      this.info = data.info;
      this.allUserGroups = data.info.allUserGroups;
      baseValidator.setRules(data.info.rules);
      requestFactory.toggleLoader();
    };

    this.fetchInfo = function () {
      requestFactory.get(
        requestFactory.getUrl('users/info'),
        this.defineProperties,
        function (response) {
          rootScope.redirectUnauthenticated(response);
        }
      );
    };

    this.fetchInfo();

    scope.$on('afterGetRecords', function () {
      if (angular.isUndefined(scope.searchRecords.is_active)) {
        scope.searchRecords.is_active = 'all';
      }
      setTimeout(function () {
        $("#fixTable").tableHeadFixer({ head: false, right: 1 });
        sidebarMenuEffectsInit();
      }, 500);
    });
  }
];

window.gridControllers = { UserController: UserController };
window.gridDirectives = {
  baseValidator: validatorDirective,
  intializeSidebar: intializeSidebar
};
