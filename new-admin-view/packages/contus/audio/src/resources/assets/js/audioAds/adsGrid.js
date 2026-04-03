'use strict';

var AdsGridController = ['$scope', '$rootScope' ,'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, rootScope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.ads = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.showad = true;
    this.ads.is_image_updated = 0;
    scope.ads = {};
    scope.ads.audioName = '';
    scope.errors = {};

    requestFactory.setThisArgument(this);
  


    function audioUploader(scope) {
        var self = this;


        this.initializeFineUploader = function () {
            window.fineUploader = new qq.FineUploaderBasic({
                element: document.getElementById('file_drop_area'),
                request: {
                    endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader',
                    params: {
                        type: "audioAds",                       
                    }
                },
                deleteFile: {
                    enabled: true,
                    endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader'
                },
                chunking: {
                    enabled: true,
                    concurrent: {
                        enabled: true
                    },
                    success: {
                        endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader?done'
                    }
                },
                resume: {
                    enabled: true
                },
                retry: {
                    enableAuto: false
                },
                button: document.getElementById('select-files-button'),
                callbacks: {
                    onComplete: function (id, name, response, xhr) {
                        if (response.success == true) {
                            var uploadResponse = {};
                            uploadResponse.name = name;
                            uploadResponse.uuid = response.uuid;
                            self.uploadedAudiosDetails.push(uploadResponse);
                            self.options.afterUpload(uploadResponse);

                            $('.edit_video_upload .upload-status-wrapper').show();
                            $('.edit_video_upload .upload-success').html(name);
                            document.getElementById("progress-bar-wrap").style.display = 'none';
                        }
                    },
                    onProgress: function (id, name, uploadedBytes, totalBytes) {
                        document.getElementById("upload_percentage").style.display = 'block';
                        document.getElementById("video_frame").style.display = 'none';
                        var uploadedPercentage = parseInt((uploadedBytes * 100) / totalBytes);
                        document.getElementById('progress-bar-wrap').style.display = 'block';
                        var progressBar = document.getElementById('progress-bar');
                        progressBar.style.width = uploadedPercentage + '%';
                        document.getElementById("upload_percentage").innerHTML = uploadedPercentage + '% Uploaded';
                        document.getElementById("upload_title").innerHTML = name;
                        if (uploadedPercentage == 100) {
                            document.getElementById("upload_percentage").innerHTML = 'Done...';
                        }
                    },
                    onError: function (id, name, errorReason, xhr) {
                        document.getElementById("upload_errors_wrap").style.display = "block";
                        document.getElementById("upload_title").style.display = "none";
                        self.resetUploader();
                        self.initializeFineUploader();
                        // Display alert message for the audios which are uploaded successfully before this error.
                        var uploadedAudiosCount = self.uploadedAudiosDetails.length;
                        if (uploadedAudiosCount > 0) {
                            var audioListString = '';
                            var audioText = '';
                            if (uploadedAudiosCount == 1) {
                                audioText = 'audio was';
                            } else {
                                audioText = 'audios were';
                            }

                            for (var i = 0; i < uploadedAudiosCount; i++) {
                                audioListString = audioListString + self.uploadedAudiosDetails[i].name;
                                if (i + 1 != uploadedAudiosCount) {
                                    audioListString = audioListString + ", ";
                                }
                            }
                            document.getElementById("upload_staus_when_error").innerHTML = "But " + uploadedAudiosCount + " " + audioText + " uploaded successfully(" + audioListString + ").";
                        } else {
                            document.getElementById("upload_staus_when_error").innerHTML = '';
                        }
                    },
                    onUpload: function (id, name) {
                        self.currentFileCount++;
                    },
                },
            });
        };

        this.isFileValid = function (validFileTypes, fileType) {
            if (validFileTypes.indexOf(fileType) != -1) {
                return true;
            } else {
                return false;
            }
        };

        this.isFileValidSize = function (fileSize) {
            var validFileSize = fileSize / 1024 / 1024;
            console.log(validFileSize);
            if (validFileSize <= 5) {
                return true;
            } else {
                return false;
            }
        };

        this.fileDragOver = function (event) {
            // When the file is dragged over the drop area.
            this.style.boxShadow = "0px 0px 50px 10px rgba(0,0,0,0.75)";
            event.preventDefault();
            event.stopPropagation();
        };

        this.fileDragLeave = function (event) {
            // When the file is dragged out of the drop area.
            this.style.boxShadow = "none";
            event.preventDefault();
            event.stopPropagation();
        };

        this.handleFileDrop = function (event) {
            // When the file is dropped in the drop area.
            this.style.boxShadow = "none";
            var files = event.dataTransfer.files;
            self.prepareUpload(files);
            event.preventDefault();
            event.stopPropagation();
        };

        this.handleFileSelect = function () {
            // When the file is selected using file select.
            var files = this.files;
            self.prepareUpload(files);
        };
        this.resetUploader = function () {
            document.getElementById("video_error").style.display = "none";
            document.getElementById("size_error").style.display = "none";
            document.querySelector('.add_video_container .fa-times').style.display = "inline";
            document.getElementsByClassName("upload_file_input")[0].style.display = "block";
            document.getElementById("upload_percentage").style.display = "none";
            document.getElementById("google_drive_upload_button").style.display = "inline";

            // Add back the drop event listener to the drop area.
            var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
            fileDropArea.addEventListener('drop', self.handleFileDrop);
            document.getElementById('progress-bar-wrap').style.display = 'none';
            document.getElementById('progress-bar').style.width = '0%';
            // Reset the input tag so that the same file can be selected again for upload.
            this.file.value = '';
        };

        this.startAudioUpload = function () {
            var files = self.uploadFiles;
            self.currentFileCount = 0;
            self.uploadedAudiosDetails = [];

            // Hide add audio close button, File selection container div and audio upload button
            document.querySelector('.add_video_container .fa-times').style.display = "none";
            document.getElementsByClassName("upload_file_input")[0].style.display = "none";
            document.getElementById("video_upload_button_wrap").style.display = "none";

            // Remove Drop event listener for file drop area.
            var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
            fileDropArea.removeEventListener('drop', self.handleFileDrop);

            document.getElementById("upload_percentage").style.display = "block";
            self.options.beforeUpload(self.uploadFiles.length);
            window.fineUploader.addFiles(files);
            window.fineUploader.uploadStoredFiles();
        };

        this.prepareUpload = function (files) {
            self.uploadFiles = [];
            var validFileTypes = ['audio/mp3', 'audio/wav'];
            document.getElementById("upload_title").innerHTML = "No. of selected files : " + files.length;
            document.getElementById("video_error").style.display = "none";
            document.getElementById("size_error").style.display = "none";
            document.getElementById("upload_title").style.display = "block";
            document.getElementById("upload_errors_wrap").style.display = "none";
            document.getElementById("upload_percentage").innerHTML = '0% Uploaded';
            document.getElementById("google_drive_upload_button").style.display = "none";
            for (var i = 0; i < files.length; i++) {
                if (files[i] && self.isFileValid(validFileTypes, files[i].type) && self.isFileValidSize(files[i].size)) {
                  //  if (files[i] && self.isFileValid(validFileTypes, files[i].type)) {
                    self.fileSize = files[i].size;
                    self.uploadFiles.push(files[i]);
                }else if (!self.isFileValid(validFileTypes, files[i].type)){
                    document.getElementById("video_error").style.display = "block";
                } else if (!self.isFileValidSize(files[i].size)){
                    document.getElementById("size_error").style.display = "block";
                }
            }
            var uploadButton = document.getElementById("video_upload_button_wrap");
            if (self.uploadFiles.length == 0) {
                uploadButton.style.display = "none";
            } else {
                // Enable upload button
                this.startAudioUpload();
                // uploadButton.addEventListener('click', this.startAudioUpload);
                uploadButton.style.display = "block";
            }
        };

        this.initiate = function (options) {
            this.options = options;
            this.file = document.getElementById(options.id);
            this.file.addEventListener('change', this.handleFileSelect);
            var fileDropArea = document.querySelector('#' + options.dropAreaId);
            fileDropArea.addEventListener('dragover', this.fileDragOver);
            fileDropArea.addEventListener('dragleave', this.fileDragLeave);
            fileDropArea.addEventListener('drop', this.handleFileDrop);
            this.initializeFineUploader();
        };
    }

    this.setupAudioUploader = function () {        
        this.audioUploader = new audioUploader(scope);
        this.audioUploader.initiate({
            id: 'ad_audio',
            dropAreaId: 'file_drop_area',
            afterUpload: function (response) {
                self.ads.newAudioName = response.name;
                self.ads.newAudioUUID = response.uuid;
                self.ads.ad_audio = response.name;
            },
            beforeUpload: function (totalAudiosCount) {
                self.totalAudiosCount = totalAudiosCount;
                // Reset the values because this upload might be after failure.
                self.audioUploadCompleteCount = 0;
                self.uploadIntervalFlag = false;
                self.audioUploadRequestCount = 0;
            },
        });

    };

    this.resetAudio = function () {
        this.ads.newAudioName = '';
        this.ads.newAudioUUID = '';
        this.ads.ad_audio = '';
        $('.edit_video_upload .upload-status-wrapper').hide();
    }

    /** Audio Upload Handler Ends */

    this.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    scope.errors[key] = {
                        has: true,
                        message: message[0]
                    };
                }
            });
        }
    };    

    this.closeAdsEdit = function () {                
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
    };

    this.closeAdsEditAdd = function () {
        $(".sidepanel").removeClass("in");                                                      
    };

    this.deleteAdsImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('audios/ads/delete-ad-image/' + this.ads.id), this.ads, function (response) {
            requestFactory.toggleLoader();
            scope.responseMessage = response.message;
            scope.showResponseMessage = true;
            scope.getRecords(true);
            self.closeAdsEdit();
            self.resetAdImageUpload();
        }, function () { });
    };

    this.resetAdImageUpload = function () {
        if (typeof window.AdImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            this.ads.image = '';
            this.ads.ad_thumbnail = '';
        }
    };

    this.defineProperties = function (data) {
        this.setupAudioUploader();
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('audios/ads/info'), this.defineProperties, function (response) { 
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchInfo();
    $timeout(function () {
        window.AdImageUploadHandler = new uploadHandler;
        window.AdImageUploadHandler.initate({
            file: 'ad-image',
            previewer: 'ad-image-preview',
            deleteIcon: 'ad-image-delete',
            progress: 'ad-image-progress',
            beforeUpload: function () {
                self.ads.ad_thumbnail = undefined;
                if (!scope.$$phase) {
                    scope.$apply();
                }
            },
            afterUpload: function (response) {
                self.ads.image = response.info;
                self.ads.ad_thumbnail = response.info;
                self.ads.module = 'ad-image';
                self.ads.is_image_updated = 1;
                if (scope.errors.hasOwnProperty('image'))
                    delete scope.errors['image'];
            }
        });
    }, 1000);

    /**
     *  Function is used to add the artist
     *  
     *  @param  $event
     */


    this.addAd = function (event) {
        self.resetAudio();
        self.resetAdImageUpload();
        this.ads = {};
        scope.errors = {};
        this.ads.id = '';
        this.ads.ad_name = '';
        this.ads.ad_url = '';
        this.ads.is_active = String(1);
        this.ads.ad_thumbnail = '';
        this.ads.is_image_updated = 0;
        this.ads.image = '';
        scope.isUpdated = false;
        $('#ad-image-progress').html('');
        $("#adsForm").css('display', 'block');
    }

    /**
     *  Function is used to edit the artists
     *  
     *  @param array records
     */

    this.editAd = function (records) {     
        // self.resetAdImageUpload();
        this.ads.id = records.id;
        this.ads.ad_name = records.ad_name;
        this.ads.is_active = String(records.is_active);
        this.ads.ad_thumbnail = records.ad_image;
        this.ads.ad_url = records.ad_url;
        this.ads.is_image_updated = 0;
        this.ads.ad_audio = records.audio_ad_fine_uploader_name;
        this.ads.image = this.ads.ad_thumbnail;        
        if (records.ad_thumbnail != '') {
            $('.preview-image').removeClass('hide');
            $('.clsProgressbar').addClass('hide');            
        }
        if(this.ads.ad_audio != ''){
            $('.edit_video_upload .upload-status-wrapper').show();
            $('.edit_video_upload .upload-success').html(this.ads.ad_audio);
            document.getElementById("progress-bar-wrap").style.display = 'none';
        }
        scope.errors = {};
    }

    this.removeThumbnailProperty = function () {
        $('#ad-image-progress').html('');
        self.ads.image = '';
        self.ads.ad_image = '';
        self.ads.is_image_updated = 0;
    }

    /**
     *  Function is used to save the artist
     *  
     *  @param  $event, id
     */

    this.adsSave = function ($event, id) {
            if (id) {
                requestFactory.post(requestFactory.getUrl('audios/ads/edit/' + id), this.ads, function (response) {
                    scope.responseMessage = response.message;
                    scope.showResponseMessage = true;
                    scope.getRecords(true);
                    self.resetAdImageUpload();
                    self.closeAdsEditAdd();
                }, this.fillError);
            } else {
                requestFactory.post(requestFactory.getUrl('audios/ads/add'), this.ads, function (response) {
                    scope.responseMessage = response.message;
                    scope.showResponseMessage = true;
                    scope.getRecords(true);
                    self.closeAdsEditAdd();
                    self.resetAdImageUpload();
                }, this.fillError);
            }
    }
    /**
     * Function to update status of a ads
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'ads';
        scope.updateStatus(record);
    };

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
    });
}];

window.gridControllers = {
    AdsGridController: AdsGridController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};