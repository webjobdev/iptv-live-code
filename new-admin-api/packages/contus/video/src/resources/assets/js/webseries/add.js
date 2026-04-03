'use strict';

var WebseriesAddApp = angular.module('WebseriesAddApp', ['ui']);
var commonAPP = WebseriesAddApp;
WebseriesAddApp.directive('baseValidator', validatorDirective);
WebseriesAddApp.factory('requestFactory', requestFactory);
WebseriesAddApp.controller('webseriesAddController', [
  '$scope',
  '$rootScope',
  'requestFactory',
  function(scope, $rootScope, requestFactory) {
    var self = this;
    this.info = {};
    scope.errors = {};
    requestFactory.setThisArgument(this);
    this.showResponseMessage = false;
    scope.webseriesPostData = {};
    scope.webseriesPostData.is_active = true;
    scope.fileUploadOptions = {};
    scope.fileElement = '';
    scope.deleteType = '';

    this.fetchInfo = function() {
      requestFactory.get(
        requestFactory.getUrl('webseries/info'),
        this.defineProperties,
        function() {}
      );
    };

    this.defineProperties = function(data) {
      requestFactory.toggleLoader();
      this.info = data.info;
      scope.webseries_categories = data.info.webseries_categories;
      scope.video_genres = data.info.video_genres;
      baseValidator.setRules(this.info.rules);
    };
    this.fetchInfo();

    this.fillError = function(response) {
      if (response.status == 422 && response.data.hasOwnProperty('message')) {
        const addWebseriesBtn = document.getElementById('webseries-submit-btn');
        if (addWebseriesBtn) {
          addWebseriesBtn.disabled = false;
          addWebseriesBtn.innerText = 'Submit';
          addWebseriesBtn.style.opacity = '1';
          addWebseriesBtn.style.cursor = 'pointer';
        }

        angular.forEach(response.data.message, function(message, key) {
          if (typeof message == 'object' && message.length > 0) {
            scope.errors[key] = {
              has: true,
              message: message[0]
            };
          }
        });
      }
    };

    this.webseriesSave = function($event) {
      baseValidator.setRules(this.info.rules);
      if (baseValidator.validateAngularForm($event.target, scope)) {
        scope.webseriesPostData.thumbnail_image = angular
          .element('.uploaded_img')
          .attr('src');
        scope.webseriesPostData.is_thumbnail_updated = 1;
        scope.webseriesPostData.category = angular
          .element('#webseries-category')
          .attr('value');

        const addWebseriesBtn = document.getElementById('webseries-submit-btn');
        if (addWebseriesBtn) {
          addWebseriesBtn.disabled = true;
          addWebseriesBtn.innerText = 'Processing...';
          addWebseriesBtn.style.opacity = '0.5';
          addWebseriesBtn.style.cursor = 'progress';
        }

        requestFactory.post(
          requestFactory.getUrl('webseries/add'),
          scope.webseriesPostData,
          function(response) {
            requestFactory.setToaster('success', response.message);
            window.location.href = requestFactory.getTemplateUrl(
              'admin/webseries'
            );
          },
          this.fillError
        );
      }
    };

    function readAsUrl(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('image').src = e.target.result;
        };
        reader.onloadend = function(e) {
          $('#modal').modal('show');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    $(document).ready(function() {
      /*
       * Thumb Image Upload Part
       */
      var image = document.getElementById('image');
      var access_token = requestFactory.access_token;

      $(document).on('change', '.uploadImg', function(e) {
        var videoItem = $(this).data('video-index');
        scope.errors = {};
        var ValidImageTypes = ['image/jpeg', 'image/png'];
        var files = e.target.files;
        var fileType = files[0].type;
        if ($.inArray(fileType, ValidImageTypes) < 0) {
          scope.$apply();

          // BEGIN : To show invalid error message in the croppre box
          $('#modal').modal('show');
          $('.crop-body').hide();
          $('#submit-image').hide();
          $('.error_msg')
            .show()
            .text(
              'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
            );
          // END : To show invalid error message in the croppre box
          return;
        }
        $('.crop-body').show();
        var videoIndex = e.target.getAttribute('data-video-index');
        $('#modal .video-index').val(videoIndex);
        readAsUrl(this);
      });

      var cropBoxData;
      var canvasData;
      var cropper;

      $(document).on('show.bs.modal', '#modal', function() {
        // By default hide the error and show submit button when popup opens, then based on the validation we hide/show the details in the same popup
        $('#submit-image').show();
        $('.error_msg').hide();
        setTimeout(function() {
          cropper = new Cropper(image, {
            autoCropArea: 1,
            viewMode: 1,
            aspectRatio: 182 / 268,
            preview: '.img-preview',
            cropBoxResizable: false,
            minCropBoxWidth: 182,
            minCropBoxHeight: 268,
            autoCrop: true,
            dragCrop: false,
            mouseWheelZoom: false,
            resizable: false,
            ready: function() {
              //Should set crop box data first here
              var config = { left: 0, top: 0, width: 182, height: 268 };
              cropper.setCropBoxData(config).setCanvasData(canvasData);
            }
          });
        }, 500);
      });
      $(document).on('hidden.bs.modal', '#modal', function() {
        document.getElementsByClassName('uploadImg')[0].value = '';
        $('#submit-image').prop('disabled', false);
        cropper.destroy();
      });
      $(document).on('click', '#submit-image', function() {
        cropBoxData = cropper.getCropBoxData();
        canvasData = cropper.getCroppedCanvas().toBlob(function(blob) {
          var formData = new FormData();
          formData.append('module', 'video');
          formData.append('size', 'thumb');
          formData.append('image', blob);
          $('.crop-body').hide();
          $('.loader-container').show();
          $('#submit-image').prop('disabled', true);
          $.ajax(
            $('meta[name="base-api-url"]').attr('content') +
              '/webseries/thumbnail',
            {
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: function(request) {
                request.setRequestHeader(
                  'Authorization',
                  'Bearer ' + access_token
                );
              },
              success(data) {
                var videoIndex = $('#modal').val();
                $('.uploaded_img').attr('src', data.info);
                $('.uploaded_img').show();
                scope.webseriesPostData.thumbnail = data.info;
                scope.webseriesPostData.thumbnail_image = data.info;
                scope.webseriesPostData.selected_thumb = data.info;
                scope.webseriesPostData.is_thumbnail_updated = 1;
                scope.$apply();
                $('.loader-container').hide();
                $('#modal').modal('hide');
              },
              error() {
                $('.loader-container').hide();
                $('.error_msg')
                  .show()
                  .text('Please upload bigger image, click cancel to continue');
              }
            }
          );
        }, 'image/jpeg');
      });

      /*
       * Post Image Upload Part
       */
      var posterImage = document.getElementById('poster_image');
      $(document).on('change', '.uploadPosterImg', function(e) {
        var videoItem = $(this).data('video-index');
        scope.errors[videoItem] = {};
        var ValidImageTypes = ['image/jpeg', 'image/png'];
        var files = e.target.files;
        var fileType = files[0].type;
        if ($.inArray(fileType, ValidImageTypes) < 0) {
          scope.$apply();
          // BEGIN : To show invalid error message in the croppre box
          $('#poster_modal').modal('show');
          $('.crop-body').hide();
          $('#submit_poster_image').hide();
          $('.poster_error_msg')
            .show()
            .text(
              'Invalid file format. Upload only jpeg and png file formats, click cancel to continue'
            );
          // END : To show invalid error message in the croppre box
          return;
        }
        $('.crop-body').show();

        var videoIndex = e.target.getAttribute('data-video-index');
        $('#poster_modal .video-index').val(videoIndex);
        readAsPosterUrl(this, videoIndex);
      });
      var cropBoxImgData;
      var canvasImgData;
      var cropperImg;

      $(document).on('show.bs.modal', '#poster_modal', function() {
        $('#submit_poster_image').show();
        $('.poster_error_msg').hide();
        setTimeout(function() {
          cropperImg = new Cropper(posterImage, {
            autoCropArea: 1,
            viewMode: 3,
            aspectRatio: 940 / 500,
            preview: '.poster_img-preview',
            cropBoxResizable: false,
            minCropBoxWidth: 400,
            minCropBoxHeight: 300,
            autoCrop: true,
            dragCrop: false,
            mouseWheelZoom: false,
            zoomable: false,
            resizable: false,
            ready: function() {
              //Should set crop box data first here
              var config = { left: 0, top: 0, width: 600, height: 300 };
              cropperImg.setCropBoxData(config).setCanvasData(canvasImgData);
            }
          });
        }, 500);
      });
      $(document).on('hidden.bs.modal', '#poster_modal', function() {
        document.getElementsByClassName('uploadPosterImg')[0].value = '';
        $('#submit_poster_image').prop('disabled', false);
        cropperImg.destroy();
      });

      $(document).on('click', '#submit_poster_image', function() {
        cropBoxImgData = cropperImg.getCropBoxData();
        canvasImgData = cropperImg.getCroppedCanvas().toBlob(function(blob) {
          var formImgData = new FormData();
          formImgData.append('module', 'video');
          formImgData.append('size', 'poster');
          formImgData.append('image', blob);
          $('.crop-body').hide();
          $('.poster_loader-container').show();
          $('#submit_poster_image').prop('disabled', true);
          $.ajax(
            $('meta[name="base-api-url"]').attr('content') +
              '/webseries/poster',
            {
              method: 'POST',
              data: formImgData,
              processData: false,
              contentType: false,
              beforeSend: function(request) {
                request.setRequestHeader(
                  'Authorization',
                  'Bearer ' + access_token
                );
              },
              success(data) {
                var videoIndex = $('#poster_modal').val();
                $('.uploaded_poster_img').attr('src', data.info);
                $('.uploaded_poster_img').show();
                scope.webseriesPostData.poster_image = data.info;
                scope.webseriesPostData.is_posterimg_updated = 1;
                scope.$apply();
                $('.poster_loader-container').hide();
                $('#poster_modal').modal('hide');
              },
              error() {
                $('.poster_loader-container').hide();
                $('.poster_error_msg')
                  .show()
                  .text('Please upload bigger image, click cancel to continue');
              }
            }
          );
        }, 'image/jpeg');
      });
    });
    /**
     * End of image upload script
     *
     * */

    function readAsPosterUrl(input, videoIndex) {
      if (input.files && input.files[0]) {
        var readerImg = new FileReader();
        readerImg.onload = function(e) {
          document.getElementById('poster_image').src = e.target.result;
        };
        readerImg.onloadend = function(e) {
          $('#poster_modal').modal('show');
        };
        readerImg.readAsDataURL(input.files[0]);
      }
    }
  }
]);

/**
 * Manually merging this controller with Common Controller for fetching header data
 */

if (angular.isObject(window.gridControllers)) {
  for (var controller in window.gridControllers) {
    if (
      angular.isArray(window.gridControllers[controller]) ||
      angular.isFunction(window.gridControllers[controller])
    ) {
      WebseriesAddApp.controller(
        controller,
        window.gridControllers[controller]
      );
    }
  }
}

angular.element(document).ready(function() {
  angular.bootstrap(document, ['WebseriesAddApp']);
});
