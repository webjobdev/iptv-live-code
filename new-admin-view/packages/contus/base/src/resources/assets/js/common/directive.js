if(!angular.isDefined(commonAPP)) {
  var commonAPP = grid;
}
commonAPP.directive('jquery', function($timeout) {
    return {
        restrict: 'A',
        scope: {
          myvalue: '=',
        },
        link: function (scope, element, attrs) {
          scope.$watch('myvalue', function(value) {
            switch(attrs.jquery) {
                case 'select2_custom_ddl':
                    setTimeout(function() {
                      var myplaceholder   = (attrs.myplaceholder) ? attrs.myplaceholder : '';
                      var minimumResults  = (attrs.minimumresults) ? attrs.minimumresults : 3;
                      var allowClear      = (attrs.allowclear) ? attrs.allowclear : 0;
                      var makedisable      = (attrs.makedisable) ? attrs.makedisable : 0;
                      $(element).select2({
                        minimumResultsForSearch:minimumResults,
                        allowClear:allowClear,
                        readonly:true,
                        placeholder: {
                          id: '', // the value of the option
                          text: myplaceholder
                        },
                      });
                      if(makedisable) {
                        $(element).select2('enable', false);
                      }

                    }, 100);

                break;

                case 'date_time_picker':
                      var futureDate   = (attrs.futuredate) ? attrs.futuredate : false;

                      const today = new Date()
                      const israelDate = today.toLocaleString('he-IL', {timeZone: "Asia/Jerusalem"});
                      var d = israelDate,
                      dArr = d.split('.'),
                      israelTime = new Date(dArr[1] + "." + dArr[0] + "." + dArr[2]).getTime();

                      if(futureDate) {
                        $(element).datetimepicker({
                            format: "YYYY-MM-DD HH:mm:ss",
                            showClear: true,
                            useCurrent: false,
                            minDate:israelTime
                              
                        }).on('dp.change',function(){
                            scope.myvalue = $(element).val();
                            scope.$apply();
                        });
                      }
                      else {
                        // Only future date
                        $(element).datetimepicker({
                            format: "YYYY-MM-DD HH:mm:ss",
                            showClear: true,
                            useCurrent: false,
                            // minDate:new Date()
                              
                        }).on('dp.change',function(){
                            scope.myvalue = $(element).val();
                            scope.$apply();
                        });
                      }
                break;
            }
          });
       }
    };
})