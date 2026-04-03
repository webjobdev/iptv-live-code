

var FeedbackController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        // this.info = {};
        this.feedbackData = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('feedback/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        this.cancelApiUser = function () {
            window.location.href = `${appUrl}admin/users/profile`;
        }


        // upload image
        this.onFileSelected = function (event) {
            var input = event.target;
            var image = document.getElementById('preview');
            var previewImg = document.getElementById('file-name');
            console.log(input.files);
            // for (let i = 0; i < input.files.length; i++) {
            //     console.log("Image : ", input.files[i]);
            // }

            if (input.files && input.files[0] && input.files.length > 0) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // console.log('result', e);

                    image.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                previewImg.style.display = "";
            }
        }

        // add feedback
        this.saveFeedback = function ($event) {
            $event.preventDefault();

            const inpt = document.getElementById('fileInput');

            const formData = new FormData();
            const file = inpt.files[0];
            if (file) {
                formData.append('image', file || '');
            }

            formData.append('subject', this.feedbackData.subject || '');
            formData.append('message', this.feedbackData.message || '');

            // hostUrl = document.location.host;
            // fetch(hostUrl + "/api/admin/feedback/add", {
            fetch(`${apiUrl}/feedback/add`, {
                method: "POST",
                body: formData,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                }
            })
                .then(res => res.json())
                .then(response => {
                    scope.getRecords?.(true);
                    requestFactory.setToaster('success', response.message);
                    requestFactory.getToaster();
                    setTimeout(function () {
                        window.location.href = `${appUrl}admin/users/profile`;
                    }, 200);
                })
                .catch(err => {
                    console.error("Fetch Error : ", err);
                })
        }

    }];

window.gridControllers = { FeedbackController: FeedbackController };

