
var profile = angular.module('profile', []);
var commonAPP = profile;
if (typeof (validatorDirective) != 'undefined') {
    profile.directive('baseValidator', validatorDirective);
}

profile.factory('requestFactory', requestFactory);

profile.controller('ProfileController', ['$window', '$scope', '$rootScope', 'requestFactory', function (win, scope, rootScope, requestFactory) {
    var self = this;
    this.user = {};
    scope.errors = {};
    this.showResponseMessage = false;
    this.profileImageError = false;
    requestFactory.setThisArgument(this);
    requestFactory.getToaster();
    requestFactory.toggleLoader();

    /**
     *  To get the profile rules
     *
     */
    this.getProfileRules = function () {
        requestFactory.get(requestFactory.getUrl('users/info'), function (response) {
            baseValidator.setRules({
                phone: "required|numeric|min:10",
                email: "required",
                name: "required",
            });
        });
    }

    this.getProfileRules(); // To get the profile rules


    this.fetchData = function () {
        requestFactory.get(requestFactory.getUrl('users/edit'), function (response) {
            this.user = response.response;
            this.user.is_active = String(response.response.is_active);
            this.showResponseMessage = false;
            self.user.is_profile_image_updated = 0;
            // requestFactory.toggleLoader();
        }, function (response) {
            rootScope.redirectUnauthenticated(response);
        });
    }
    /**
     *  Functtion is used to fill the error
     *
     */
    this.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            requestFactory.toggleLoader();
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    scope.errors[key] = { has: true, message: message[0] };
                }
            });
        }
    };

    /**
     *  Functtion is used to save the new password
     *
     */
    this.save = function ($event) {
        if (baseValidator.validateAngularForm($event.target, scope)) {

            requestFactory.toggleLoader();
            requestFactory.post(requestFactory.getUrl('profile/users/edit/' + this.user.id), this.user, function (response) {
                requestFactory.setToaster('success', response.message);
                win.location = requestFactory.getTemplateUrl('admin/users/profile');
            }, this.fillError);
        }
    };

    this.removeProfileImageProperty = function () {
        self.user.profile = '';
        self.user.profile_image = '';
    };

    this.deleteProfileImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('users/delete-profile-image/' + this.user.id), this.user, function () {
            win.location = requestFactory.getTemplateUrl('admin/users/profile');
        }, function () { });
    };

    /**
      * Image Upload Script
      *
      * */
    function readAsUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('image').src = e.target.result;
            };
            reader.onloadend = function (e) {
                $('#modal').modal('show');
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    $(document).ready(function () {
        var image = document.getElementById('image');
        var access_token = requestFactory.access_token;
        $(document).on('change', '.uploadImg', function (e) {
            var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
            var files = e.target.files;
            var fileType = files[0].type;
            if ($.inArray(fileType, ValidImageTypes) < 0) {
                //  scope.errors.profile_image = { has: true, message: 'Invalid file format. Upload only jpeg and png file formats.' };
                scope.$apply();

                // BEGIN : To show invalid error message in the cropper box
                $('#modal').modal('show');
                $('.crop-body').hide();
                $('#submit-image').hide();
                $('.error_msg').show().text("Invalid file format. Upload only jpeg and png file formats, click cancel to continue");
                // END : To show invalid error message in the cropper box

                return;
            }
            $('.crop-body').show();

            readAsUrl(this);
        });

        var cropBoxData;
        var canvasData;
        var cropper;


        $(document).on('show.bs.modal', '#modal', function () {
            // By default hide the error and show submit button when popup opens, then based on the validation we hide/show the details in the same popup
            $('#submit-image').show();
            $('.error_msg').hide();
            setTimeout(function () {
                cropper = new Cropper(image, {
                    autoCropArea: 1,
                    viewMode: 3,
                    aspectRatio: 12 / 13,
                    preview: '.img-preview',
                    cropBoxResizable: true,
                    minCropBoxWidth: 200,
                    minCropBoxHeight: 245,
                    autoCrop: true,
                    dragCrop: false,
                    mouseWheelZoom: false,
                    resizable: true,
                    ready: function () {
                        //Should set crop box data first here
                        cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                    }
                });
            }, 500);
        });
        $(document).on('hidden.bs.modal', '#modal', function () {
            document.getElementsByClassName("uploadImg")[0].value = "";
            $('#submit-image').prop('disabled', false);
            cropper.destroy();
        });
        $(document).on('click', '#submit-image', access_token, function () {
            cropBoxData = cropper.getCropBoxData();
            canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
                var formData = new FormData();
                formData.append('module', 'video');
                formData.append('size', 'thumb');
                formData.append('image', blob);
                $('.crop-body').hide();
                $('.loader-container').show();
                $('#submit-image').prop('disabled', true);
                $.ajax($('meta[name="base-api-url"]').attr('content') + '/users/profile-image', {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function (request) { request.setRequestHeader('Authorization', 'Bearer ' + access_token) },
                    success(data) {
                        //var videoIndex = $('#modal').val();
                        $('.uploaded_img').attr('src', data.info);
                        $('.uploaded_img').show();
                        self.user.is_profile_image_updated = 1;
                        self.user.profile_image = data.info

                        scope.$apply();
                        $('.loader-container').hide();
                        $('#modal').modal('hide');
                    },
                    error() {
                        $('.loader-container').hide();
                        $('.error_msg').show().text("Please upload bigger image");
                    },
                })
            }, 'image/jpeg');
        });

    });

    /**
     * End of image upload script
    *
    * */

    /**
     * Get Feedback Page
    *
    * */
    this.getFeedback = function () {
        window.location.href = 'feedback';
    };

}]);

/**
 * Manually merging this controller with Common Controller for fetching header data
 */
if (angular.isObject(window.gridControllers)) {
    for (var controller in window.gridControllers) {
        if (angular.isArray(window.gridControllers[controller]) || angular.isFunction(window.gridControllers[controller])) {
            window.gridControllers[controller].hideHeader = true;
            profile.controller(controller, window.gridControllers[controller]);
        }
    }
}

/**
* Manually bootstrap the Angular module here
*/

// /ott-laravel/new-admin-view/public

// Normalize URL path so it works locally AND on live server
(function () {

    // Split path into segments & remove empty values
    const parts = window.location.pathname.split('/').filter(Boolean);

    // Always match last 3 segments: admin/users/profile
    const normalizedPath = '/' + parts.slice(-3).join('/');

    if (normalizedPath === '/admin/users/profile') {
        angular.element(document).ready(function () {
            angular.bootstrap(document, ['profile']);
        });
    }

})();

// if (document.location.pathname == '/admin/users/profile') {
//     angular.element(document).ready(function () {
//         angular.bootstrap(document, ['profile']);
//     });
// }
