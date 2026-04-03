'use strict';

var viewVideoDetail = angular.module('viewVideoDetail', ['ui.bootstrap']);

var commonAPP = viewVideoDetail;

var commonModule = viewVideoDetail;
viewVideoDetail.directive('baseValidator', validatorDirective);

viewVideoDetail
  .filter('getCount', function() {
    return function(obj) {
      if (obj) {
        return Object.keys(obj).length;
      }
    };
  })
  .filter('convertDate', function() {
    return function(obj) {
      if (obj) {
        return Date.parse(obj);
      }
    };
  })
  .filter('convertAgoTime', function() {
    return function(obj) {
      if (obj) {
        var time = new Date().getTime();
        time = parseInt(time / 1000) - parseInt(obj / 1000);
        time = time < 1 ? 1 : time;
        var tokens = [
          { id: '31536000', text: 'year' },
          { id: '2592000', text: 'month' },
          { id: '604800', text: 'week' },
          { id: '86400', text: 'day' },
          { id: '3600', text: 'hour' },
          { id: '60', text: 'minute' },
          { id: '1', text: 'second' }
        ];

        for (var unit in tokens) {
          var text = tokens[unit].text;
          unit = parseInt(tokens[unit].id);
          if (time < unit) {
            continue;
          }
          var numberOfUnits = Math.floor(time / unit);
          return (
            numberOfUnits + ' ' + text + (numberOfUnits > 1 ? 's' : '') + ' ago'
          );
        }
      }
    };
  })
  .filter('getByKey', function() {
    return function(input, id, column, data, field) {
      var column = angular.isUndefined(column) ? 'id' : column;
      var i = 0;
      if (angular.isArray(input)) {
        for (; i < input.length; i++) {
          if (input[i] && input[i][column] == id) {
            return data == 'key' ? i : field ? input[i][field] : input[i];
          }
        }
      } else {
        var result = null;
        angular.forEach(input, function(val, key) {
          if (val[column] == id) {
            result = data == 'key' ? key : val;
          }
        });
        return result;
      }
      return null;
    };
  });
viewVideoDetail.factory('requestFactory', requestFactory);

var initplayer = function($rootScope, requestFactory) {
  return {
    restrict: 'A',
    replace: true,
    scope: {
      video: '='
    },
    link: function(scope, elem, attr) {
      scope.$watch('video', function(video) {
        if (video && video.hasOwnProperty('id')) {
          videojs.Hls.xhr.beforeRequest = function(options) {
            if (video && video.is_live == 0) {
              options.headers = [];
              options.headers['X-REQUEST-TYPE'] = 'admin';
            }
          };

          var player = videojs(elem['0'].id, {
            controls: true,
            autoplay: true,
            preload: 'auto',
            fluid: true,
            playbackRates: [0.25, 0.5, 1, 1.25, 1.5, 2],
            poster: video.selected_thumb,
            sources: [
              {
                src: video.hls_playlist_url,
                type: 'application/x-mpegURL'
              }
            ],
            html5: {
              nativeAudioTracks: false,
              nativeVideoTracks: false,
              nativeTextTracks: false,
              hls: {
                overrideNative: true
              }
            },
            plugins: {
              hlsQualitySelector: {},
              keyboardShortCuts: {}
            }
          });
        }
      });
    }
  };
};

viewVideoDetail.controller('ViewVideoDetailsController', [
  '$window',
  '$scope',
  '$rootScope',
  'requestFactory',
  '$sce',
  '$timeout',
  function(win, scope, $rootScope, requestFactory, $sce, $timeout) {
    var self = this;
    scope.errors = {};
    this.editVideo = {};
    this.allCategories = {};
    this.showResponseMessage = false;
    this.gridLoadingBar = false;
    requestFactory.setThisArgument(this);
    this.notFoundFlag = false;
    var commenturl = '';
    this.loading = false;
    scope.flag = false;
    scope.final = false;
    scope.All = false;
    scope.Oldest = false;
    scope.Newest = false;
    scope.oldcomments = {};
    var jsonRevenueData_x_axis = [];
    var jsonRevenueData_y_axis = [];

    var page = 1;
    var replypage = 1;
    var activetabpage = 1;
    scope.video = {};
    scope.videoConfirmationDeleteBox = false;

    this.sourceurl = $sce.trustAsResourceUrl(
      'https://webrtc.vplayed.com:5443/WebRTCAppEE/play.html?name='
    );

    this.fetchData = function(id) {
      requestFactory.get(
        requestFactory.getUrl('videos/complete-video-details/' + id),
        function(response) {
          this.performanceStatusSelected = 4;
          this.geographicStatusSelected = 4;
          var videoDetails = response.response;
          var performanceStatistics = videoDetails.performanceStatistics;
          this.geographicStatistics = videoDetails.geographicStatistics.data;
          scope.video = response.response;

          /**
           * Active Preset Formating
           */

          if (
            scope.video.active_presets != '' &&
            typeof scope.video.active_presets == 'string'
          ) {
            var active_presets = scope.video.active_presets.split(',');

            if (active_presets.length > 0) {
              const preset_mapped = active_presets.map(function(preset) {
                if (preset >= 700) {
                  return preset + 'p' + ' HD';
                } else {
                  return preset + 'p';
                }
              });
              scope.active_presets = preset_mapped.join();
            }
          }

          if (videoDetails.subtitle != '') {
            scope.video.subtitle = this.subTitleForVideo(videoDetails.subtitle);
          }
          this.sourceurl = $sce.trustAsResourceUrl(
            'https://webrtc.vplayed.com:5443/WebRTCAppEE/play.html?name=' +
              videoDetails.stream_id
          );
          scope.video.comments = response.response.comments;
          scope.video.showreplycommentslist = false;
          scope.finalReply = false;
          scope.All = true;
          if (videoDetails.video_meta_data) {
            this.custom_url = videoDetails.video_meta_data.custom_url
              ? videoDetails.video_meta_data.custom_url
              : videoDetails.slug;
            this.title = videoDetails.video_meta_data.title;
            this.keyword = videoDetails.video_meta_data.keyword;
            this.description = videoDetails.video_meta_data.description;
          }

          angular.forEach(performanceStatistics.data, function(value, key) {
            var d = new Date(value.date);
            var months = [
              'Jan',
              'Feb',
              'Mar',
              'Apr',
              'May',
              'Jun',
              'Jul',
              'Aug',
              'Sept',
              'Oct',
              'Nov',
              'Dec'
            ];

            var monthname = months[d.getMonth()];
            var date = d.getDate();
            var originaldate = monthname + ' ' + date;
            var x = originaldate;
            var y = value.sum;
            jsonRevenueData_x_axis.push(x);
            jsonRevenueData_y_axis.push(y);
          });

          this.drawUsersChart(
            'performance-statics-chart',
            jsonRevenueData_x_axis,
            jsonRevenueData_y_axis
          );

          // this.fetchComments();
          initplayer();

          $timeout(function() {
            $('[data-toggle="popover"]').popover();
          }, 100);

          this.editVideo.videoPresets = [];
          //this.setCategoriesOfVideos(videoDetails);
          this.editVideo.thumbnail = '';

          // Form the allowed countries string.
          this.editVideo.videocountry = '';
          $('#trailerModal').on('shown.bs.modal', function() {
            document.querySelector('#trailerModal .modal-body').innerHTML =
              '<iframe width="560" height="315" src="' +
              self.editVideo.trailerEmbedUrl +
              '" frameborder="0" allowfullscreen></iframe>';
          });
          $('#trailerModal').on('hidden.bs.modal', function() {
            document.querySelector('#trailerModal .modal-body').innerHTML = '';
          });
        },
        function(response) {
          self.notFoundFlag = true;
          requestFactory.toggleLoader();
        }
      );
      $timeout(function() {
        var mainPanel = document.getElementsByClassName('mainpanel');
        if (mainPanel.length > 0) {
          var mainContent = document.getElementsByClassName('mainpanel')[0];
          mainContent.className += ' show-content';
        }
      }, 500);
    };

    /*
     * Function to delete admin video view detail page.
     */
    this.deleteSingleRecordVideos = function(id, title) {
      scope.deleteParams = [id];
      scope.videoConfirmationDeleteBox = true;
      scope.videotitle = title;
    };
    this.cancelDeleteVideos = function() {
      scope.videoConfirmationDeleteBox = false;
      scope.deleteParams = '';
    };
    this.confirmDeleteVideos = function(videoStatus) {
      if (scope.deleteParams.length > 0) {
        self.deleteRecordsVideos(scope.deleteParams, videoStatus);
        scope.videoConfirmationDeleteBox = false;
        scope.deleteParams = '';
      } else {
        scope.videoConfirmationDeleteBox = false;
        scope.deleteParams = '';
      }
    };
    this.deleteRecordsVideos = function(id, videoStatus) {
      scope.deleteParams = '';
      scope.deleteRequest = requestFactory.post(
        requestFactory.getUrl('videos/delete-action'),
        angular.extend(
          {},
          {
            selectedCheckbox: id,
            videoStatus: videoStatus
          },
          scope.requestParams
        ),
        function(data) {
          this.responseMessage = data.message;
          this.showResponseMessage = true;
          win.location = requestFactory.getTemplateUrl('admin/videos');
        }
      );
    };

    this.formatDuration = function(time) {
      var duration = time.split(':');
      if (duration.length > 2) {
        return duration[0] + 'hr ' + duration[0] + 'm ' + duration[0] + 's';
      } else {
        return duration[0] + 'm ' + duration[0] + 's';
      }
    };

    scope.updateStatus = function(record, status) {
      requestFactory.post(
        requestFactory.getUrl(
          'comments/updatestatus/' + record.id + '?status=' + status
        ),
        {},
        function(response) {
          var data = { comment: '', parent_id: '' };
          PostComment();
        }
      );
    };
    /**
     *  Function is used to fill the error
     *
     */

    this.fillError = function(response) {
      if (response.status == 422 && response.data.hasOwnProperty('message')) {
        angular.forEach(response.data.message, function(message, key) {
          if (typeof message == 'object' && message.length > 0) {
            scope.errors[key] = { has: true, message: message[0] };
          }
        });
      }
      if (response.status == 422 && response.data.hasOwnProperty('messages')) {
        angular.forEach(response.data.messages, function(message, key) {
          if (typeof message == 'object' && message.length > 0) {
            scope.errors[key] = { has: true, message: message[0] };
          }
        });
      }
    };

    /************subtitle for video***************** */

    this.subTitleForVideo = function(Subtitle) {
      var subtitleLanguages = [];
      var ParsedSubtitle = JSON.parse(Subtitle);
      angular.forEach(ParsedSubtitle, function(value, key) {
        subtitleLanguages.push(value.label);
      });
      var SubtitleString = subtitleLanguages.join();

      return SubtitleString;
    };

    /**Array filter function to remove duplicate comments in an array */
    scope.getUnique = function(arr, comp) {
      const unique = arr
        .map((e) => e[comp])

        // store the keys of the unique objects
        .map((e, i, final) => final.indexOf(e) === i && i)

        // eliminate the dead keys & store unique objects
        .filter((e) => arr[e])
        .map((e) => arr[e]);

      return unique;
    };

    /**Comment Tab Activation*/
    scope.commentActiveTab = function(comment_order, tab) {
      activetabpage = parseInt(activetabpage);

      scope.All = false;
      scope.Oldest = false;
      scope.Newest = false;
      if (tab == 'All') {
        scope.All = true;
      }
      if (tab == 'Oldest') {
        scope.Oldest = true;
      }
      if (tab == 'Newest') {
        scope.Newest = true;
      }

      requestFactory.post(
        requestFactory.getUrl('getvideoComments?page=' + activetabpage),
        { video_id: scope.video.id, order_by: comment_order },
        function(response) {
          scope.video.comments = response.data;
          //scope.final = (response.next_page_url === null) ? true : false;
        }
      );
    };

    /**Window Function to Call Load More*/
    $(window).scroll(function() {
      if (
        $(window).scrollTop() + $(window).height() >=
          $(document).height() - 100 &&
        scope.final === false &&
        scope.flag === false
      ) {
        scope.loading = true;
        scope.flag = true;
        scope.loadMore();
      }
    });
    /**Load More on Page Scroll*/
    scope.loadMore = function() {
      page = parseInt(page) + 1;
      if (scope.final === false) {
        requestFactory.post(
          requestFactory.getUrl('getvideoComments?page=' + page),
          { video_id: scope.video.id },
          function(response) {
            scope.video.comments = scope.video.comments.concat(response.data);
            scope.video.comments = scope.getUnique(scope.video.comments, '_id');
            scope.final = response.next_page_url === null ? true : false;
          }
        );

        scope.loading = false;
        scope.flag = false;
      }
    };
    /**Show Reply Comments  */
    scope.ShowreplyComments = function(comment_id) {
      if (scope.video.showreplycommentslist == false) {
        replypage = parseInt(replypage);
        requestFactory.post(
          requestFactory.getUrl('getreplyvideoComments?page=' + replypage),
          { video_id: scope.video.id, comment_id: comment_id },
          function(response) {
            scope.video.replycomments = response.data;
            scope.video.showreplycommentslist = true;
            scope.finalReply = response.next_page_url === null ? true : false;
          }
        );
      } else {
        scope.video.showreplycommentslist = false;
      }
    };
    /**Load Reply Comments  */
    scope.loadreplyComments = function(comment_id) {
      var loadreplypage = 1;
      var loadreplypage = parseInt(loadreplypage) + 1;

      requestFactory.post(
        requestFactory.getUrl('getallreplyvideoComments?page=' + loadreplypage),
        { video_id: scope.video.id, comment_id: comment_id },
        function(response) {
          scope.video.replycomments = scope.video.replycomments.concat(
            response.data
          );
          scope.video.showreplycommentslist = true;

          scope.finalReply = response.next_page_url === null ? true : false;
        }
      );
    };

    /**Metadata Custom URL focus Setting on click  */
    scope.setFocustoCustomURL = function() {
      window.document.getElementById('custom_url');
      custom_url.focus();
    };
    /**Metadata Custom URL validation */
    scope.validateCustomURL = function() {
      var str = this.vVideoDetailsCtrl.custom_url;
      this.customURLError = false;
      var pattern = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi; // fragment locater
      if (pattern.test(str)) {
        scope.errors['custom_url'] = {
          has: true,
          message: 'Entered URL is not valid'
        };
        this.customURLError = true;
        return false;
      }
    };
    /**Metadata Submission */
    scope.metaDataSubmit = function() {
      if (this.customURLError == false) {
        requestFactory.post(
          requestFactory.getUrl('videos/metadata'),
          {
            video_id: scope.video.id,
            custom_url: this.vVideoDetailsCtrl.custom_url,
            title: this.vVideoDetailsCtrl.title,
            description: this.vVideoDetailsCtrl.description,
            keyword: this.vVideoDetailsCtrl.keyword
          },
          function(response) {
            win.location = requestFactory.getTemplateUrl(
              'admin/videos/view-details-video/' + scope.video.id
            );
          },
          function(response) {
            this.fillError(response);
          }
        );
      }
    };
    /** Permformace Statistics based on Date Selected  */
    this.performanceStatus = function(item) {
      requestFactory.post(
        requestFactory.getUrl('videos/performancestatistics'),
        { video_id: scope.video.id, dataType: item },
        function(data) {
          var months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sept',
            'Oct',
            'Nov',
            'Dec'
          ];
          jsonRevenueData_x_axis = [];
          jsonRevenueData_y_axis = [];
          if (item == 2) {
            angular.forEach(data.performanceStatistics.data, function(
              value,
              key
            ) {
              var d = new Date(value.date);
              var x = months[d.getMonth()];
              var y = value.sum;
              jsonRevenueData_x_axis.push(x);
              jsonRevenueData_y_axis.push(y);
            });
          } else if (item == 3 || item == 4) {
            angular.forEach(data.performanceStatistics.data, function(
              value,
              key
            ) {
              var d = new Date(value.date);
              var monthname = months[d.getMonth()];
              var date = d.getDate();
              var originaldate = monthname + ' ' + date;
              var x = originaldate;
              var y = value.sum;
              jsonRevenueData_x_axis.push(x);
              jsonRevenueData_y_axis.push(y);
            });
          } else {
            angular.forEach(data.performanceStatistics.data, function(
              value,
              key
            ) {
              var x = value.date;
              var y = value.sum;
              jsonRevenueData_x_axis.push(x);
              jsonRevenueData_y_axis.push(y);
            });
          }

          this.drawUsersChart(
            'performance-statics-chart',
            jsonRevenueData_x_axis,
            jsonRevenueData_y_axis
          );
        }
      );
    };
    /** Geographical location Statistics  */
    this.geographicStatus = function(item) {
      requestFactory.post(
        requestFactory.getUrl('videos/geographicstatistics'),
        { video_id: scope.video.id, dataType: item },
        function(data) {
          this.geographicStatistics = data.geographicStatistics.data;
        }
      );
    };
    /** Performance Statistics Chart */
    this.drawUsersChart = function(id, x, y) {
      var chart2 = Highcharts.chart(id, {
        chart: {
          type: 'areaspline'
        },

        title: {
          text: ''
        },

        xAxis: {
          categories: x,
          plotBands: [
            {
              // visualize the weekend
              from: 4.5,
              to: 6.5
            }
          ],
          labels: {
            style: {
              color: '#b4b4b4',
              fontSize: '14px'
            }
          }
        },
        yAxis: {
          gridLineDashStyle: 'ShortDash',
          title: {
            text: ''
          },
          labels: {
            style: {
              color: '#b4b4b4',
              fontSize: '14px'
            }
          }
        },
        plotOptions: {
          series: {
            fillColor: {
              linearGradient: [0, 0, 0, 300],
              stops: [
                [0, 'rgba(255, 120, 75, 0.25)'],
                [1, 'rgba(255, 255, 255, 0.9)']
              ]
            }
          },
          areaspline: {
            fillOpacity: 0.5
          }
        },
        tooltip: {
          formatter: function() {
            return this.y;
          }
        },
        credits: {
          enabled: false
        },

        series: [
          {
            showInLegend: false,
            data: y,
            lineWidth: 2,
            lineColor: '#ff784b',
            marker: {
              lineWidth: 2,
              lineColor: 'white',
              fillColor: '#ff784b',
              symbol: 'circle',
              radius: 4,
              states: {
                hover: {
                  fillColor: 'white',
                  lineColor: '#ff784b',
                  lineWidth: 3
                }
              }
            }
          }
        ],
        navigation: {
          buttonOptions: {
            theme: {
              // Good old text links
              style: {
                //box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.15);
                opacity: -1,
                border: 0,
                background: '#014ECE',
                background:
                  '-moz-linear-gradient(left, #014ECE 0%, #014ECE 100%)',
                background:
                  '-webkit-gradient(left top, right top, color-stop(0%, #014ECE), color-stop(100%, #014ECE))',
                background:
                  '-webkit-linear-gradient(left, #014ECE 0%, #014ECE 100%)',
                background:
                  '-o-linear-gradient(left, #014ECE 0%, #014ECE 100%)',
                background:
                  '-ms-linear-gradient(left, #014ECE 0%, #014ECE 100%)',
                background:
                  'linear-gradient(to right, #014ECE 0%, #014ECE 100%)',
                // filter: 'progid:DXImageTransform.Microsoft.gradient(startColorstr='#014ECE', endColorstr='#014ECE', GradientType=1)',
                color: '#ffffff',
                padding: '0 19px'
              }
            }
          }
        },
        exporting: {
          enabled: true, // hide button
          buttons: {
            contextButton: {
              enabled: false
            },
            exportButton: {
              menuItems: null,
              text: 'Export PDF',
              onclick: function() {
                this.exportChart({
                  type: 'application/pdf'
                });
              }
            }
          }
        }
      });

      $('#export-pdf').on('click', function(event) {
        $(
          '#performance-statics-chart .highcharts-exporting-group tspan'
        ).trigger('click');
        // chart2.exportChart({type: "application/pdf"});
      });
    };

    /**Filter main Comments only */
    scope.mainCommentFilter = function(Comments) {
      if (!('parent_id' in Comments)) {
        return Comments;
      }
    };

    /*
     * Function to delete comments view detail page.
     */
    scope.deleteSingleRecordComments = function(id) {
      scope.deleteParams = id;
      scope.commentConfirmationDeleteBox = true;
    };

    scope.cancelDeleteComment = function() {
      scope.commentConfirmationDeleteBox = false;
      scope.deleteParams = '';
    };

    scope.confirmDeleteComments = function() {
      if (scope.deleteParams.length > 0) {
        scope.deleteComment(scope.deleteParams);
        scope.commentConfirmationDeleteBox = false;
        scope.deleteParams = '';
      }
    };

    /**Comments Delete  */
    scope.deleteComment = function(comment_id) {
      requestFactory.post(
        requestFactory.getUrl('comments/delete-action'),
        { comment_id: comment_id },
        function(response) {
          this.showResponseMessage = true;
          this.responseMessage = response.message;

          win.location = requestFactory.getTemplateUrl(
            'admin/videos/view-details-video/' + scope.video.id
          );
        }
      );
    };

    /**
     * Tab Change
     */
    scope.tabChange = function(tab) {
      scope.cover_thumbnail = false;
      scope.metadata = false;
      scope.metrics = false;
      scope.overview = false;

      if (tab == 'cover_thumbnail') {
        scope.cover_thumbnail = true;
        $('.tab-content').hide();
        $('.cover_thumbnail').show();
      }
      if (tab == 'metadata') {
        scope.metadata = true;

        $('.tab-content').hide();
        $('.metadata').show();
      }
      if (tab == 'metrics') {
        scope.metrics = true;
        $('.tab-content').hide();
        $('.metrics').show();
      }
      if (tab == 'overview') {
        scope.overview = true;
        $('.tab-content').hide();
        $('.video-detail-overview').show();
      }
    };

    scope.$watch('videoConfirmationDeleteBox', function() {
      if (!scope.videoConfirmationDeleteBox) {
        $('#videoDeleteModal').modal('hide');
      }
    });
    scope.tabChange('overview');
  }
]);
viewVideoDetail.directive('initPlayer', initplayer);

/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if (angular.isObject(window.gridControllers)) {
  for (var controller in window.gridControllers) {
    if (
      angular.isArray(window.gridControllers[controller]) ||
      angular.isFunction(window.gridControllers[controller])
    ) {
      window.gridControllers[controller].hideHeader = true;
      viewVideoDetail.controller(
        controller,
        window.gridControllers[controller]
      );
    }
  }
}

/**
 * Manually bootstrap the Angular module here
 */
angular.element(document).ready(function() {
  angular.bootstrap(document, ['viewVideoDetail']);
});
