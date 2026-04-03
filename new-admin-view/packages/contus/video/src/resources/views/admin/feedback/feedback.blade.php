@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}" />

    <style>
        /* Container and General Layout */
        .feedback-container {
            width: 70%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            /* Optional: Add a subtle shadow if desired for "premium" look, removed based on just structure needs, but recommended */
        }

        /* Form Elements */
        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-label-custom {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }

        .form-control-custom {
            border: 2px solid rgba(128, 130, 133, 0.36);
            border-radius: 20px !important;
            padding: 10px 15px;
            height: auto;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: none;
            background-color: #fff;
        }

        .form-control-custom:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
            outline: none;
        }

        .textarea-custom {
            height: 120px !important;
            resize: vertical;
        }

        /* File Upload */
        .hidden-file-input {
            position: absolute;
            left: -9999px;
            pointer-events: none;
        }

        .custom-file-upload {
            border: 2px dashed #ccc;
            border-radius: 12px;
            height: 150px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
            font-size: medium;
            color: #555;
        }

        .custom-file-upload:hover {
            border-color: #666;
            background-color: #f0f0f0;
            transform: scale(1.01);
        }

        .upload-content p {
            margin: 5px 0;
        }

        /* Buttons */
        .btn-group-custom {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        /* Responsive Media Queries */
        @media (max-width: 991px) {
            .feedback-container {
                width: 90%;
            }
        }

        @media (max-width: 767px) {
            .feedback-container {
                width: 100%;
                padding: 10px;
            }

            .form-label-custom {
                margin-top: 0;
                margin-bottom: 8px;
            }

            /* Adjust button group mainly for very small screens if needed */
            .btn-group-custom {
                flex-direction: column;
                gap: 10px;
            }

            .btn-group-custom button {
                width: 100%;
            }
        }
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div>
        <div class="dashboard-page " id="dashboard-page" data-ng-controller="FeedbackController as fdbackCtrl">

            <div id="home" class="tab-pane fade in active"><br>
                <div class="row">
                    <!-- Improved Container with Responsive Class -->
                    <div class="feedback-container">
                        <form method="POST" enctype="multipart/form-data" data-skip-validator
                            data-ng-submit="fdbackCtrl.saveFeedback($event)">
                            {!! csrf_field() !!}
                            <input type="hidden" id="api-access-id" name="id" value="{{ request()->id }}">

                            <!-- feedback subject -->
                            <div class="form-group row form-group-custom">
                                <label for="subject" class="col-sm-2 control-label form-label-custom">
                                    <strong>Subject*:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="fdbackCtrl.feedbackData.subject"
                                        name="subject" placeholder="Enter subject" id="subject"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: auto;">
                                </div>
                            </div>

                            <!-- feedback message -->
                            <div class="form-group row form-group-custom">
                                <label for="message" class="col-sm-2 control-label form-label-custom">
                                    <strong>Message*:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="textarea" class="form-control" ng-model="fdbackCtrl.feedbackData.message"
                                        name="message" placeholder="Enter Message" id="message"
                                        style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px; height: 100px;">
                                </div>
                            </div>

                            <!--image -->
                            <div class="form-group row form-group-custom" ng-click="fdbackCtrl.triggerFileInput()">
                                <label for="image" class="col-sm-2 control-label form-label-custom">
                                    <strong>Upload File:</strong>
                                </label>
                                <div class="col-sm-10">
                                    <div class="file-upload-wrapper" id="img-div">
                                        <label for="fileInput" class="custom-file-upload">
                                            <div class="upload-content">
                                                <p><strong>Drag here to upload</strong></p>
                                                <p>or</p>
                                                <p><span class="glyphicon glyphicon-upload"></span> <strong>Click here to
                                                        browse</strong></p>
                                                <p style="font-size: 12px; color: #999;">Max size for one file: 2MB</p>
                                            </div>
                                        </label>
                                        <input type="file" id="fileInput" class="hidden-file-input"
                                            onchange="angular.element(this).scope().fdbackCtrl.onFileSelected(event)"
                                            ng-model="fdbackCtrl.feedbackData.image" />
                                    </div>

                                    <p id="file-name" class="help-block" style="margin-top: 10px; display: none"><img
                                            src="" id="preview" alt="" height="180px" width="230px"></p>
                                </div>
                            </div>

                            <!-- button group -->
                            <div class="form-group btn-group-custom">
                                <button type="submit" class="button button-blue rounded-pill rounded" id="apiaccessadd">
                                    <strong>Send</strong>
                                </button>
                                <button type="button" class="button button-gray" ng-click="fdbackCtrl.cancelApiUser()">
                                    <strong>Cancel</strong>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- <script>
        const apiUrl = `{{ env('API_URL') }}`;
    </script> -->
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/adminusers/profile.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/feedback/feedback.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection
