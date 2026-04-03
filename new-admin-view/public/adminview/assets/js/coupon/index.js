'use strict';

var couponController = ['$scope', '$rootScope', 'requestFactory', '$window', '$sce', '$timeout', function (scope, rootScope, requestFactory, $window, $sce, $timeout) {
  this.showResponseMessage = false;
  this.responseMessage = '';
  this.expire_date = '';
  this.coupon = {};
  requestFactory.setThisArgument( this );
  requestFactory.getToaster();

  $timeout(function () {
    $('#coupon-valid-date').datepicker({ format: "dd-mm-yyyy", viewMode: 'years', autoclose: true });

    $('#expire-dates').datepicker({
      format: "dd-mm-yyyy",
      startDate: new Date(),
      autoclose: true,
    });

  }, 1000);

  this.defineProperties = function (data) {
    this.info = data.info;
    baseValidator.setRules(data.info.rules);
    requestFactory.toggleLoader();
  };

  this.fetchInfo = function () {
    requestFactory.get(requestFactory.getUrl('coupon/info'), this.defineProperties, function (response) {
      rootScope.redirectUnauthenticated(response);
    });
  };

  this.fetchInfo();
  this.fillError = function(response){
    if(response.status == 422 && response.data.hasOwnProperty('message')){
       angular.forEach(response.data.message, function(message,key) {
         if(typeof message == 'object' && message.length > 0){
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
  this.save = function ($event, id) {
    this.offerValidation();
    if (baseValidator.validateAngularForm($event.target, scope)) {
      if(this.offerValidation()){
        if (id) {
          requestFactory.put(requestFactory.getUrl(`coupon/update/${id}`), this.coupon, function (response) {
            scope.getRecords(true);
            this.showResponseMessage = true;
            this.responseMessage = response.message;
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            // $('.response-msg').html('<div class="alert alert-success" style="display:none"><button type="button" class="close" data-dismiss="alert">×</button><span id="coupon-msg">'+response.message+'</span></div>');
            $(".sidepanel").removeClass("in");
          
          }, this.fillError);
        } else {
          requestFactory.post(requestFactory.getUrl('coupon/add'), this.coupon, function (response) {
            scope.getRecords(true);
            this.showResponseMessage = true;
            this.responseMessage = response.message;
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            // $('.response-msg').html('<div class="alert alert-success" style="display:none"><button type="button" class="close" data-dismiss="alert">×</button><span id="coupon-msg">'+response.message+'</span></div>');
            $(".sidepanel").removeClass("in");
          
          }, this.fillError);
        }
      }
    }
  }
  /**
 *  Function is used to add the latest news
 *  @param $event
 */
  this.addCoupon = function ($event) {
    $(".sidepanel").addClass("in");
    scope.errors = {};
    $("#couponForm").css('display', 'block');
    $("#couponTranslationForm").css('display', "none");
    this.coupon = {};
    this.coupon.is_active = true;
    this.coupon.is_trial = false;
    this.coupon.offer_type = 'percentage';
  }

  this.editUser = function (records) {
    $(".sidepanel").addClass("in");
    scope.errors = {};
    this.coupon.id = records.id;
    this.coupon.name = records.name;
    this.coupon.code = records.code;
    this.coupon.offer = records.offer;
    this.coupon.user = records.user;
    this.coupon.offer_type = records.offer_type;
    this.coupon.valid_till = this.changeDate(records.valid_till);
    this.coupon.is_trial = (records.is_trial) ? true : false;
    this.coupon.is_active = (records.is_active) ? true : false;
  }

  this.deleteSingleCoupon = function (id) {
    requestFactory.delete(requestFactory.getUrl(`coupon/delete-coupon/${id}`), function (response) {
      scope.getRecords(true);
      this.responseMessage = response.message;
      this.showResponseMessage = true;
      // console.log(this.responseMessage, this.showResponseMessage);
      $(".sidepanel").removeClass("in");
    
    }, this.fillError);
  }

  this.changeDate = function (d) {
    d = d.split('-');
    return d[2] + '-' + d[1] + '-' + d[0];
  }

  this.autoGenCoupon = function ($event) {
    $event.preventDefault();
    var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var shuffled = str.split('').sort(function () { return 0.5 - Math.random() }).join('');
    shuffled = shuffled.substring(0, 6);
    this.coupon.code = shuffled;
    scope.errors.code = '';
  }

  this.codeVal = function ($event) {
    var code = $event.target.value.replace(/[^\w\s]/gi, '').replace(/ /g, '');
    if(code.length > 6){
      var code = code.substring(0, 6);
    }
    $('.code-val').val(code);
    // $('.code-val').val($event.target.value.replace(/[^\w\s]/gi, '').replace(/ /g, ''));
  }

  // this.offerValidation = function($event){
  //   var off = this.coupon.offer;
  //   var regexp = /^\d{1,3}(\.\d{1,2})?$/;
  //   if(parseInt(off)< 100 && this.coupon.offer_type == 'percentage'){
  //     if(!regexp.test(off)) {
  //     scope.errors["offer"] = {has : true,message : "Invalid Offer value"}
  //     return false;
  //     }
  //     return true;
  //   } else if(this.coupon.offer_type == 'flat') {
  //     if(parseInt(off)> 1000000000 ) {
  //       scope.errors["offer"] = {has : true,message : "Invalid Offer value"}
  //       return false;
  //       }
  //       return true;
  //   }  else {
  //     if(this.coupon.offer_type != "trial" && (this.coupon.offer_type == 'flat' || this.coupon.offer_type == 'percentage')){
  //       scope.errors['offer'] = {has : true,message : "Invalid Offer value"}
  //       return false;
  //     }
  //     return true;
  //   } 
  // }

  this.offerValidation = function($event){
    var off = this.coupon.offer;
    if(parseInt(off)< 100 && this.coupon.offer_type == 'percentage'){
      var regexp = /^\d{1,3}(\.\d{1,2})?$/;
      if(!regexp.test(off)) {
      scope.errors["offer"] = {has : true,message : "Invalid Offer value"}
      return false;
      }
      scope.errors.offer = '';
      return true;
    } else if(parseInt(off)< 1000000000000 && this.coupon.offer_type == 'flat') {
      var regexp = /^\d{1,9}(\.\d{1,9})?$/;
      if(!regexp.test(off)) {
        scope.errors["offer"] = {has : true,message : "Invalid Offer value"}
        return false;
        }
        scope.errors.offer = '';
        return true;
    }  else {
      if(this.coupon.offer_type != "trial" && (this.coupon.offer_type == 'flat' || this.coupon.offer_type == 'percentage')){
        scope.errors['offer'] = {has : true,message : "Invalid Offer value"}
        // scope.errors.offer = '';

        return false;
      }
      return true;
    } 
  }

  this.userValidatin = function($event){
    var user = this.coupon.user; 
      if (user) {
        scope.errors["user"] = {has : true,message : ""}
            return false;
      }
            return true;
    }

    this.closeCouponEdit = function(){
      $(".sidepanel").removeClass("in");
    }

    scope.clickMe = function(offer, type){
      if(parseInt(offer)> 99 && type == 'percentage'){
        // $window.alert("angularAlert");   
        scope.errors["offer"] = {has : true,message : "Invalid Offer value"}
        return false;
      }
      // return true
    }

  scope.$on('afterGetRecords', function (e, data) {
    if (angular.isUndefined(scope.searchRecords.is_active)) {
      scope.searchRecords.is_active = 'all';
    }
  })

}];

window.gridControllers = { couponController: couponController };
window.gridDirectives = { baseValidator: validatorDirective, intializeSidebar: intializeSidebar };

$(document).ready(function () {
  var loader = $('#preloader');
  loader.find('#status').css('display', 'none');
  loader.css('display', 'none');
});