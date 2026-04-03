'use strict';

var audioUpload = ['$http', '$rootScope', '$filter', '$window', 'requestFactory', function (http, rootScope, $filter, $window, requestFactory) {
    var self = this;
    /** Audio Upload Handler Starts */
    this.hideUploadOption = function () {
        document.querySelector('.video_grid').style.display = 'block';
        document.querySelector('.add_video_container').style.display = 'none';
        document.querySelector('#video_form_fields').style.display = 'none';
    };
    this.setupAudioUploader = function (scope, $ctrlType = null) {
        this.AudioUploader = new AudioUploader(scope);
        this.AudioUploader.initiate({
            id: 'audio',
            dropAreaId: 'file_drop_area',
            afterUpload: function (response) {
                scope.singleAudioPostData.audio_details = response;
                self.saveSingleAudio(scope,$ctrlType);
            },
            beforeUpload: function (totalAudiosCount) {
                self.totalAudiosCount = totalAudiosCount;
                // Reset the values because this upload might be after failure.
                self.audioUploadCompleteCount = 0;
                self.uploadIntervalFlag = false;
                scope.audioUploadRequestCount = 0;
            },
        });
    };

    this.saveSingleAudio = function (scope,$ctrlType) {
        requestFactory.post(requestFactory.getUrl('audios/add'), scope.singleAudioPostData, function (response) {
            if (!response.error && response.hasOwnProperty('audio')) {
                scope.albumPostData.audioPostData.push({'id':response.audio.id});
                scope.albumPostData.audioPostData[scope.audioUploadRequestCount].is_active = true;
                angular.element('.form_submit #audio_id_' + scope.audioUploadRequestCount).val(response.audio.id);
                scope.isAudioUploaded = true;
                scope.audioUploadRequestCount++;
                scope.allAudioUploadStatus = true;
                //$scope.$apply();
            }
        }, function () {
            angular.element('.audio_item_' + scope.audioUploadRequestCount + ' .file-progress-wrapper .file-complete-status').text('Error occured, upload failed');
            scope.audioUploadRequestCount++;
        });
    };

    this.audioResetUploader = function (scope) {
        return this.audioReset = new AudioUploader(scope);
    };

    function AudioUploader(scope) {
        var self = this;
        this.initializeFineUploader = function () {
            window.fineUploader = new qq.FineUploaderBasic({
                autoUpload: false,
                debug: true,
                element: document.getElementById('file_drop_area'),
                request: {
                    endpoint: window.VPlay.route.apiUrl + '/api/admin/audios/handle-fine-uploader'
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
                    enableAuto: true
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
                            scope.isAudioUploaded = true;
                        }
                    },
                    onProgress: function (id, name, uploadedBytes, totalBytes) {
                        var fileNameSlug;
                        var uploadedPercentage;
                        scope.allAudioUploadStatus = false;
                        fileNameSlug = self.convertToSlug(name);
                        var parentDiv = '#audio_itemname_' + fileNameSlug;
                        uploadedPercentage = parseInt((uploadedBytes * 100) / totalBytes);
                        $(parentDiv + ' .file-progress-wrapper .progress').show();
                        $(parentDiv + ' .file-progress-wrapper .progress #progress-bar').css("width", uploadedPercentage + '%');
                        $(parentDiv + ' .file-progress-wrapper .progress #progress-bar #upload_percentage').show();
                        $(parentDiv + ' .file-progress-wrapper .progress #progress-bar #upload_percentage').text(uploadedPercentage + '%');
                        if (uploadedPercentage == 100) {
                            $(parentDiv + ' .file-progress-wrapper .progress').hide();
                            $(parentDiv + ' .file-progress-wrapper .file-complete-status').text('Uploaded');
                        }
                    },
                    onAllComplete: function () {
                        /* @todo */
                    },
                    onAutoRetry: function (id, name, attemptNumber) {
                        var fileNameSlug;
                        fileNameSlug = self.convertToSlug(name);
                        var parentDiv = '#audio_itemname_' + fileNameSlug;
                        document.getElementById("or").style.display = "none";
                        document.getElementById("upload_title").innerHTML = "No. of selected files : " + scope.uploadFiles.length;
                        angular.element(parentDiv + ' .file-progress-wrapper .file-complete-status').text('Error occured. Retrying upload, No.of attempts: ' + attemptNumber);
                        angular.element(parentDiv + ' .file-progress-wrapper .progress').hide();
                        document.getElementById("video_upload_button_wrap").style.display = "none";
                        document.getElementsByClassName("upload_file_input")[0].style.display = "none";
                        document.getElementById("video_upload_button_wrap").style.display = "none";
                    },
                    onError: function (id, name, errorReason, xhr) {
                        var fileNameSlug;
                        fileNameSlug = self.convertToSlug(name);
                        var parentDiv = '#audio_itemname_' + fileNameSlug;
                        angular.element(parentDiv + ' .file-progress-wrapper .file-complete-status').text('Error occured, upload failed');
                        angular.element(parentDiv + ' .file-progress-wrapper .progress').hide();
                        self.resetUploader();
                        self.initializeFineUploader();
                        scope.isAudioUploaded = false;
                        // Display alert message for the audios which are uploaded successfully before this error.
                        var uploadedVideosCount = self.uploadedAudiosDetails.length;
                        if (uploadedVideosCount > 0) {
                            var videoListString = '';
                            var videoText = '';
                            if (uploadedVideosCount == 1) {
                                videoText = 'audio was';
                            } else {
                                videoText = 'audio were';
                            }

                            for (var i = 0; i < uploadedVideosCount; i++) {
                                videoListString = videoListString + self.uploadedAudiosDetails[i].name;
                                if (i + 1 != uploadedVideosCount) {
                                    videoListString = videoListString + ", ";
                                }
                            }
                            document.getElementById("upload_staus_when_error").innerHTML = "But " + uploadedVideosCount + " " + videoText + " uploaded successfully(" + videoListString + ").";
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
            return (validFileTypes.indexOf(fileType) != -1) ? true : false;
        };

        this.fileDragOver = function (event) {
            // When the file is dragged over the drop area.
            this.style.boxShadow = "0px 0px 12px 0px rgba(70, 70, 70, 0.5)";
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
            document.getElementById("upload_title").innerHTML = "DRAG & DROP FILES HERE";
            document.getElementById("or").style.display = "block";
            document.getElementsByClassName("upload_file_input")[0].style.display = "inline-block";
            document.getElementById("video_upload_button_wrap").style.display = "block";
            // Add back the drop event listener to the drop area.
            var fileDropArea = document.querySelector('#' + scope.fileUploadOptions.dropAreaId);
            fileDropArea.addEventListener('drop', self.handleFileDrop);
            document.getElementById('progress-bar-wrap').style.display = 'none';
            document.getElementById('progress-bar').style.width = '0%';
            // Reset the input tag so that the same file can be selected again for upload.
            if (typeof (scope.fileElement) !== 'string') {
                scope.fileElement.value = '';
            }
        };

        this.startAudioUpload = function () {
            if(scope.uploadFiles != undefined && scope.uploadFiles.length > 0){
                var files = scope.uploadFiles;
                self.currentFileCount = 0;
                self.uploadedAudiosDetails = [];
                // Hide add audio close button, File selection container div and audio upload button
                document.getElementsByClassName("upload_file_input")[0].style.display = "none";
                document.getElementById("video_upload_button_wrap").style.display = "none";

                // Remove Drop event listener for file drop area.
                var fileDropArea = document.querySelector('#' + self.options.dropAreaId);
                fileDropArea.removeEventListener('drop', self.handleFileDrop);

                document.getElementById("upload_percentage").style.display = "block";
                self.options.beforeUpload(scope.uploadFiles.length);
                window.fineUploader.addFiles(files);
                window.fineUploader.uploadStoredFiles();
            }else{
                document.getElementById("video_error").style.display = "block";
            }
        };
        this.convertToSlug = function (text) {
            var fileName;
            fileName = text;
            fileName = fileName.substr(0, fileName.lastIndexOf('.'));
            return fileName
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-')
                ;
        }
        this.getFilesizeInMB = function (filesize) {
            return (filesize / (1024 * 1024)).toFixed(2) + 'MB';
        }
        this.prepareUpload = function (files) {
            var fileNameSlug, fileSizeInMB;
            scope.uploadFiles = [];
            scope.audioUploadLists = [];
            var validFileTypes = ['audio/mp3', 'audio/wav'];
            //document.getElementById("audio-formats-intimation").style.display = "none";
            document.getElementById("or").style.display = "none";
            document.getElementById("video_error").style.display = "none";
            document.getElementById("upload_title").style.display = "block";
            document.getElementById("upload_errors_wrap").style.display = "none";
            document.getElementById("upload_percentage").innerHTML = '0% Uploaded';
            document.getElementById("google_drive_upload_button").style.display = "none";

            for (var i = 0; i < files.length; i++) {
                if (files[i] && self.isFileValid(validFileTypes, files[i].type)) {
                    scope.allAudioUploadStatus = false;
                    fileNameSlug = self.convertToSlug(files[i].name);
                    fileSizeInMB = self.getFilesizeInMB(files[i].size)
                    scope.uploadFiles.push(files[i]);
                    scope.audioUploadLists.push({
                        'file_name': files[i].name,
                        'file_slug': fileNameSlug,
                        'file_size': fileSizeInMB,
                    });
                }
            }
            document.getElementById("upload_title").innerHTML = "No. of selected files : " + scope.uploadFiles.length;
            scope.$apply();
            var uploadButton = document.getElementById("video_upload_button_wrap");
            if (scope.uploadFiles.length == 0) {
                // There are no valid files selected.
                document.getElementById("video_error").style.display = "block";
                uploadButton.style.display = "none";
            } else {
                // Enable upload button
                uploadButton.addEventListener('click', this.startAudioUpload);
                uploadButton.style.display = "block";
            }
        };

        this.initiate = function (options) {
            this.options = options;
            scope.fileUploadOptions = options;
            this.file = document.getElementById(options.id);
            this.file.addEventListener('change', this.handleFileSelect);
            var fileDropArea = document.querySelector('#' + options.dropAreaId);
            fileDropArea.addEventListener('dragover', this.fileDragOver);
            fileDropArea.addEventListener('dragleave', this.fileDragLeave);
            fileDropArea.addEventListener('drop', this.handleFileDrop);
            var uploadButton = document.getElementById("video_upload_button_wrap");
            uploadButton.addEventListener('click', this.startAudioUpload);
            this.initializeFineUploader();
        };
    }
    /** Audio Upload Handler Ends */

    /** Audio delete start */
    this.deleteAudioRecord = function (id, status, index, type, scope) {
        if (id != '') {
            scope.deleteParams = '';
            scope.deleteRequest = requestFactory.post(requestFactory.getUrl('audios/action'), angular.extend({}, {
                selectedCheckbox: id,
                status: status
            }, scope.requestParams), function (data) {
                if (type == "add") {
                    self.audioPostDataRemove(index, scope);
                } else {
                    scope.albumPostData.audioEditPostData.splice(index, 1);
                }
                this.responseMessage = data.message;
                this.showResponseMessage = true;
            });
        } else {
            if (type == "add") {
                self.audioPostDataRemove(index, scope);
            } else {
                scope.albumPostData.audioEditPostData.splice(index, 1);
            }

        }
    };

    this.audioPostDataRemove = function (index, scope) {
        scope.albumPostData.audioPostData.splice(index, 1);
        scope.audioUploadLists.splice(index, 1);
        scope.uploadFiles.splice(index, 1);
        document.getElementById("upload_title").innerHTML = "No. of selected files : " + scope.uploadFiles.length;
        if (scope.albumPostData.audioPostData.length == 0 && scope.uploadFiles.length == 0) {
            this.resetAudioUploader = new AudioUploader(scope);
            this.resetAudioUploader.resetUploader();
            scope.allAudioUploadStatus = true;
            scope.isAudioUploaded = false;
        }
    }
    /** Audio delete end */

}];