'use strict';

var UserController = ['$scope','$rootScope','requestFactory','$window','$sce','$timeout',function ( scope, rootScope, requestFactory, $window, $sce, $timeout ) {
    var self = this;

    this.user = {};
    requestFactory.setThisArgument( this );
    requestFactory.getToaster();
    /**
     *  To get the auth id
     *
     */
    this.setQuery = function ( $authId ) {
        this.authId = $authId;
    }
    /**
     *  Function is used to add the user
     *  @param $event
     */
         $timeout( function () {
          $('#filter_startdate').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
          $('#filter_enddate').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
         }, 1000 );

    this.addUser = function ( $event ) {
        $(".sidepanel").addClass("in");
        scope.showhidepassword = 1;
        this.user.subsciptionform = 0;
        scope.errors = {};
        this.user = {};
        this.user.name = '';
        this.user.email = '';
        this.user.phone = '';
        this.user.acesstype = 'web';
        //this.user.password = '';
        this.user.exam = '';
        scope.examSelection = [];
        this.user.age = '';
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth();
        var day = today.getDate();
        var beforeTenYear = new Date(year - 10, month, day);
        $('#age').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
        //this.user.password_confirmation = '';
        this.user.is_active = true;
        baseValidator.setRules( self.ruleset );
    }
    /**
    *  Function is used to edit the user
    *
    *  @param records
    */
    this.editUser = function ( records ) {
        $(".sidepanel").addClass("in");
        scope.errors = {};       
        this.user.subsciptionform = 0;
        this.user.id = records.id;
        this.user.name = records.name;
        this.user.email = records.email;
        this.user.phone = records.phone;
        this.user.age = records.dob;
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth();
        var day = today.getDate();
        var beforeTenYear = new Date(year - 10, month, day);

        $('#age').datepicker({format:"dd-mm-yyyy",startDate:"-100 Y",viewMode: 'years',endDate: beforeTenYear,autoclose: true}).datepicker('setDate', records.dob);
        scope.examSelection = [];
        scope.showhidepassword = 0;
        var test = function ( exam ) {
            for ( var i = 0; i < exam.length; i++ ) {
                scope.examSelection.push( exam [i].id );
            }
        };
        test( records.exams );
        this.user.exam = scope.examSelection.join( ',' );
        this.user.is_active =  (records.is_active) ? true : false;
       
        this.user.acesstype = 'web';
        var rules = self.ruleset;
        baseValidator.setRules( rules );
    }
this.addSubscription = function(records){
    $(".sidepanel").addClass("in");
	scope.errors = {};
	this.user.acesstype = 'web';
	this.user.subsciptionform = 1;
	this.user.id = records.id;
	this.user.start_date =  "";
	this.user.orderid = "";
	$('#start_date').datepicker({format:"dd-mm-yyyy",startDate:"-100 Y",viewMode: 'years',autoclose: true});
	this.user.subscription_plan = String("");
	var rules = self.ruleset;
    baseValidator.setRules( rules );
}
    scope.selectexam = function ( slug ) {
        var idx = scope.examSelection.indexOf( slug );
        // Is currently selected
        if ( idx > -1 ) {
            scope.examSelection.splice( idx, 1 );
        }
        // Is newly selected
        else {
            scope.examSelection.push( slug );
        }
        self.user.exam = scope.examSelection.join( ',' );
    }

    this.fillError = function ( response ) {
        $('#loaderimg').hide();
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : requestFactory.capitalize(message[0])};
                }
            } );
        }
    };

    /**
    *  Function is used to save the user
    *
    *  @param $event,id
    */
    this.save = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            console.log(this.user);
            if(this.user.name != '' && this.user.email != '' && this.user.phone != '' ) {
                $('#loaderimg').show();
            }
            if ( id ) {
                requestFactory.put( requestFactory.getUrl( 'customers/' + id ), this.user, function ( response ) {
                    requestFactory.toggleLoader();
                    scope.getRecords( true );
                    requestFactory.setToaster('success', response.message);
                    $('#loaderimg').hide();
                    requestFactory.getToaster();
                    this.closeUserEdit();
                    $timeout( function () {
                        self.user = {};
                    }, 100 );
                }, this.fillError );

            } else {
                requestFactory.post( requestFactory.getUrl( 'customers' ), this.user, function ( response ) {
                    scope.getRecords( true );
                    requestFactory.setToaster('success', response.message);
                    $('#loaderimg').hide();
                    requestFactory.getToaster();
                    this.closeUserEdit();
                }, this.fillError );
            }
        }
    }
    this.saveSubcription = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.put( requestFactory.getUrl( 'customer-subscription/' + id ), this.user, function ( response ) {
                    scope.getRecords( true );
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closeUserEdit();
                    $timeout( function () {
                        self.user = {};
                    }, 100 );
                } );

            } else {
                requestFactory.post( requestFactory.getUrl( 'customers' ), this.user, function ( response ) {
                    scope.getRecords( true );
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    this.closeUserEdit();
                }, this.fillError );
            }
        }
    }
    var date = angular.element('#age');
    var checkValue = function (str, max) {
       if (str.charAt(0) !== '0' || str == '00') {
         var num = parseInt(str);
         if (isNaN(num) || num <= 0 || num > max) num = 1;
         str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
       };
       return str;
     };

     scope.dateKeyup =  function(e,date) {
       var input = date;
       if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
       var values = input.split('/').map(function(v) {
         return v.replace(/\D/g, '')
       });
       if (values[0]) values[0] = checkValue(values[0], 12);
       if (values[1]) values[1] = checkValue(values[1], 31);
       var output = values.map(function(v, i) {
         return v.length == 2 && i < 2 ? v + ' / ' : v;
       });
       self.user.age = output.join('').substr(0, 14);
     }


    /**
     * Function to close the sidebar which is used to edit user information.
     */
    this.closeUserEdit = function () {
        scope.gridSideFormClose();
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.allUserGroups = data.info.allUserGroups;
        requestFactory.toggleLoader();
        self.ruleset = data.info.rules;
        scope.exams = data.info.exams;
        scope.subcription_plans = data.info.subscription_plans;

    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'customer/info' ), this.defineProperties, function (response) {
            rootScope.redirectUnauthenticated(response);
        } );
    };

    this.fetchInfo();

    

    /**
     *  Listen to the records to update property
     *
     */
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.is_active ) ) {
            scope.searchRecords.is_active = 'all';
        }
        if( angular.isUndefined( scope.searchRecords.subscriber ) ) {
            scope.searchRecords.subscriber = 'all';
        }
        setTimeout(function() {
            $("#fixTable").tableHeadFixer({"head": false, "right" : 1});
        },500);
    } );
}];


window.gridControllers = {UserController : UserController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};
window.gridInitApp = angular.module('grid',[]);
$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );
