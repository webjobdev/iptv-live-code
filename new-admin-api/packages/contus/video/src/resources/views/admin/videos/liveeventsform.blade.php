<div class="card main-container">
    <div class="card-left">
        <button ng-if="!livePage && !editPage" class="button button-blue" ng-click="vgridCtrl.addMoreVideos()">+
            {{ __('video::videos.upload_more_videos') }}</button>
        <ul>
            <li class="active" ng-if="livePage && !editPage">
                <div class="status-upload not-saved live-video flexbox align-items-center" id="@{{ video.id }}">
                    <svg _ngcontent-c1="" xml:space="preserve" xmlns:graph="http://ns.adobe.com/Graphs/1.0/"
                        xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/"
                        xmlns:x="http://ns.adobe.com/Extensibility/1.0/" xmlns:xlink="http://www.w3.org/1999/xlink"
                        enable-background="new 0 0 30 30" height="30px" id="Layer_1" version="1.1" viewBox="0 0 30 30"
                        width="30px" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
                        <path _ngcontent-c1=""
                            d="M26.387,23.657l-1.261-1.263c4.122-4.123,4.121-10.83-0.002-14.953l1.263-1.261  C31.205,10.998,31.205,18.84,26.387,23.657z M22.942,20.212l1.261,1.266c3.614-3.617,3.614-9.5,0-13.115l-1.261,1.261  C25.86,12.542,25.86,17.295,22.942,20.212z M21.952,19.224c2.374-2.373,2.374-6.238-0.001-8.611l-1.261,1.262  c1.68,1.68,1.68,4.411,0,6.086L21.952,19.224z M5.353,7.441L4.09,6.18c-4.818,4.817-4.818,12.66,0,17.477l1.261-1.263  C1.229,18.273,1.23,11.565,5.353,7.441z M6.271,21.478l1.262-1.266c-2.918-2.917-2.918-7.667,0-10.588L6.271,8.361  C2.656,11.976,2.656,17.86,6.271,21.478z M8.522,19.224l1.263-1.263c-1.678-1.679-1.678-4.409,0.001-6.086l-1.263-1.262  C6.149,12.989,6.149,16.852,8.522,19.224z"
                            fill="#5cb85c"></path>
                        <path _ngcontent-c1="" clip-rule="evenodd"
                            d="M15.366,12.178c1.646,0,2.98,1.335,2.98,2.981  s-1.334,2.98-2.98,2.98s-2.981-1.334-2.981-2.98S13.72,12.178,15.366,12.178z"
                            fill="#5cb85c" fill-rule="evenodd"></path>
                    </svg>
                    <h3>Live Event In Progress</h3>
                </div>
            </li>
            <li class="" data-ng-repeat="video in videoArray" 
                ng-class="{'active': ((video.id > 0 && video.id == selectedVideo.id)) }">
                <div ng-if="video.job_status=='Complete'" class="status-upload not-saved" id="@{{ video.id }}"
                    data-ng-click="videoData(video)">
                    <span class="completed status"></span>
                    <h3>@{{ video.title}}</h3>
                    <span class="status-text">{{ __('video::videos.video_upload_status.complete') }}</span>
                </div>
                <div ng-if="video.job_status=='Uploaded' || video.job_status=='Video Uploaded' || video.job_status=='Convert to MP4' || video.job_status=='Progressing' || video.job_status=='Uploading'"
                    class="status-upload not-saved" id="@{{ video.id }}" id="progressStatus" data-ng-click="videoData(video)">
                    <span class="inprogress status"></span>
                    <h3>@{{ video.title}}</h3>
                    <span class="status-text completed" data-ng-show="video.uploading_percentage == 100 && video.transcodingPercentage == 100">
                    <span class="completed status"></span>
                        {{ __('video::videos.video_upload_status.complete') }}
                    </span>
                    <span class="status-text failed" style="display: none;">
                        Failed
                    </span>
                    <div class="upload-progress-status flexbox" data-ng-hide="video.uploading_percentage == 100 && video.transcodingPercentage == 100" >
                        <div class="flexbox status-box"  ng-class="video.uploading_percentage == 100 ? 'success' : 'inprogress'">
                            <svg viewBox="0 0 34.016 34.016" width="15" height="15">
                                <g clip-path="url(#_clipPath_cXNGA6YPiEbllVOo79UDHhzVTEdH0OK4)"><g><g><g><g><path d=" M 17.008 0 C 7.615 0 0 7.615 0 17.008 C 0 26.401 7.615 34.016 17.008 34.016 C 26.401 34.016 34.016 26.401 34.016 17.008 C 34.016 7.615 26.401 0 17.008 0 Z  M 17.008 31.274 C 9.129 31.274 2.741 24.887 2.741 17.008 C 2.741 9.129 9.129 2.741 17.008 2.741 C 24.887 2.741 31.274 9.129 31.274 17.008 C 31.274 20.792 29.771 24.42 27.096 27.096 C 24.42 29.771 20.792 31.274 17.008 31.274 L 17.008 31.274 Z " fill="#8d8d8d"/><path d=" M 23.686 13.706 L 18.083 8.404 C 17.825 8.13 17.459 7.984 17.083 8.004 L 17.083 8.004 C 16.71 8.002 16.351 8.145 16.082 8.404 L 16.082 8.404 L 10.38 13.706 C 9.879 14.279 9.879 15.134 10.38 15.707 C 10.639 15.981 11.004 16.127 11.38 16.107 C 11.714 16.067 12.028 15.928 12.281 15.707 L 15.682 12.606 L 15.982 24.811 C 15.973 25.186 16.118 25.547 16.383 25.812 C 16.647 26.077 17.009 26.221 17.383 26.212 C 17.757 26.221 18.119 26.077 18.384 25.812 C 18.648 25.547 18.793 25.186 18.784 24.811 L 18.484 12.706 L 21.685 15.607 C 21.944 15.881 22.309 16.027 22.686 16.007 C 23.058 16.009 23.417 15.866 23.686 15.607 C 24.189 15.073 24.189 14.24 23.686 13.706 Z " fill="#8d8d8d"/></g></g></g></g></g>
                            </svg>
                                    
                            <span ng-if="!editPage" class="status-percentage">@{{ video.uploading_percentage }}%</span>

                            <span ng-if="video.uploading_percentage == 100 && editPage" class="status-percentage">100%</span>


                        </div>
                        <span class="seperator"></span>
                        <div class="flexbox status-box " ng-class=" ((video.transcodingPercentage > 0 && video.transcodingPercentage < 100) ||  (video.uploading_percentage == 100)) ? 'inprogress' : (video.transcodingPercentage == 100) ? 'success' : ''">
                            <svg viewBox="0 0 34.016 34.016" width="15" height="15">
                                <g clip-path="url(#_clipPath_Et4h7nQuM1eCkqrMTLBnGHjw91wzKYJj)"><g><g><g><g><g/></g></g></g><g><g>
                                <path d=" M 17.008 0 C 7.615 0 0 7.615 0 17.008 C 0 26.401 7.615 34.016 17.008 34.016 C 26.401 34.016 34.016 26.401 34.016 17.008 C 34.005 7.619 26.397 0.01 17.008 0 L 17.008 0 Z  M 17.008 31.274 C 9.129 31.274 2.741 24.887 2.741 17.008 C 2.741 9.129 9.129 2.741 17.008 2.741 C 24.887 2.741 31.274 9.129 31.274 17.008 C 31.266 24.883 24.883 31.266 17.008 31.274 L 17.008 31.274 Z " fill="#8d8d8d"/>
                                <path d=" M 21.758 19.585 C 21.515 19.34 21.185 19.202 20.84 19.202 C 20.495 19.202 20.164 19.34 19.921 19.585 C 19.685 19.836 19.541 20.16 19.513 20.503 C 19.514 20.853 19.662 21.187 19.921 21.422 L 20.942 22.442 L 18.595 22.442 C 18.084 22.442 18.084 22.442 17.88 22.034 C 17.756 21.766 17.583 21.524 17.37 21.32 L 14.921 17.952 C 15.06 17.724 15.232 17.518 15.431 17.34 C 15.531 17.24 15.531 17.246 15.631 17.14 L 17.468 14.691 C 17.668 14.385 17.876 14.181 17.978 13.976 C 18.178 13.67 18.178 13.568 18.693 13.568 L 21.04 13.568 L 20.019 14.589 C 19.774 14.832 19.637 15.162 19.637 15.507 C 19.637 15.852 19.774 16.183 20.019 16.426 C 20.262 16.67 20.593 16.808 20.938 16.808 C 21.283 16.808 21.613 16.67 21.856 16.426 L 25.122 13.16 C 25.366 12.938 25.505 12.623 25.505 12.293 C 25.505 11.963 25.366 11.648 25.122 11.425 L 21.754 8.16 C 21.511 7.915 21.181 7.777 20.836 7.777 C 20.491 7.777 20.16 7.915 19.917 8.16 C 19.672 8.403 19.535 8.733 19.535 9.078 C 19.535 9.423 19.672 9.754 19.917 9.997 L 20.938 11.017 L 18.591 11.017 C 17.455 10.96 16.386 11.553 15.835 12.548 C 15.735 12.748 15.529 12.854 15.325 13.16 L 13.794 15.303 L 13.182 16.324 C 12.218 15.668 11.082 15.313 9.917 15.303 L 7.059 15.303 C 6.387 15.312 5.843 15.855 5.835 16.528 C 5.879 17.185 6.402 17.708 7.059 17.752 L 9.917 17.752 C 10.904 17.771 11.836 18.217 12.468 18.977 L 12.533 19.121 L 12.568 19.177 L 15.323 22.954 C 15.523 23.154 15.629 23.362 15.731 23.464 C 16.282 24.458 17.351 25.052 18.487 24.995 L 20.834 24.995 L 19.813 26.015 C 19.559 26.254 19.412 26.585 19.405 26.933 C 19.386 27.287 19.538 27.629 19.813 27.852 C 20.037 28.127 20.378 28.279 20.732 28.26 C 21.081 28.259 21.415 28.111 21.65 27.852 L 24.916 24.586 C 25.152 24.335 25.296 24.012 25.324 23.668 C 25.316 23.338 25.209 23.018 25.018 22.75 L 21.758 19.585 Z " fill="#8d8d8d"/></g></g></g></g>
                            </svg>
            
                            <span ng-if="video.job_status=='Progressing' && !editPage" class="status-percentage">@{{ video.transcodingPercentage }}%</span>

                            <span ng-if="video.job_status=='Progressing' && editPage" class="status-percentage">@{{ video.upload_percentage }}%</span>

                            <span ng-if="video.job_status!='Progressing' && (!editPage || editPage)" class="status-percentage">0%</span>
                            
                        </div>
                    </div>
                </div>
                <div ng-if="video.job_status=='Error'" class="status-upload not-saved" id="@{{ video.id }}"
                    data-ng-click="videoData(video)">
                    <span class="failed status"></span>
                    <h3>@{{ video.title}}</h3>
                    <span class="status-text">{{ __('video::videos.video_upload_status.error') }} </span>
                </div>
            </li>
        </ul>
    </div>
    <div class="card-right">
        <div class="card-content">
            <div class="header-section flexbox align-items-center flex-wrap">
                <h3>@{{ selectedVideo.title}}</h3>
                <div class="right-side">
                    <select data-jquery="select2_custom_ddl" minimumResults="-1" class="lang-select select2_custom_ddl"
                        style="width:100px" ng-change="languageChange()" data-ng-model="selectedLanguage"
                        data-ng-options="lang.id as lang.title  for lang in vgridCtrl.language ">

                    </select>
                </div>
                <div id="progress-bar-wrap" class="upload_progress_container" ng-show="selectedVideo.showProgress"> 
                    <div class="flexbox align-items-center">
                        <div class="upload_progress_bar" style="display: none;" >
                            <div class="progress active">
                                <div id="progress-bar" class="progress-bar progress-bar-success"
                                    style="width:@{{ selectedVideo.uploading_percentage }}%">
                                    <p id="upload_percentage" style="display: block;">
                                        @{{ selectedVideo.uploading_text }}</p>
                                </div>
                            </div>                            
                        </div>
                        <!-- Progress bar -->
                        <div class="upload-progress-bar" >
                            <ul>
                                <li class="@{{ selectedVideo.uploading_class }} ">
                                    <div class="icon-wrapper">
                                    <svg viewBox="0 0 34.016 34.016" width="38" height="38">
                                    <g clip-path="url(#_clipPath_cXNGA6YPiEbllVOo79UDHhzVTEdH0OK4)"><g><g><g><g><path d=" M 17.008 0 C 7.615 0 0 7.615 0 17.008 C 0 26.401 7.615 34.016 17.008 34.016 C 26.401 34.016 34.016 26.401 34.016 17.008 C 34.016 7.615 26.401 0 17.008 0 Z  M 17.008 31.274 C 9.129 31.274 2.741 24.887 2.741 17.008 C 2.741 9.129 9.129 2.741 17.008 2.741 C 24.887 2.741 31.274 9.129 31.274 17.008 C 31.274 20.792 29.771 24.42 27.096 27.096 C 24.42 29.771 20.792 31.274 17.008 31.274 L 17.008 31.274 Z " fill="#8d8d8d"/><path d=" M 23.686 13.706 L 18.083 8.404 C 17.825 8.13 17.459 7.984 17.083 8.004 L 17.083 8.004 C 16.71 8.002 16.351 8.145 16.082 8.404 L 16.082 8.404 L 10.38 13.706 C 9.879 14.279 9.879 15.134 10.38 15.707 C 10.639 15.981 11.004 16.127 11.38 16.107 C 11.714 16.067 12.028 15.928 12.281 15.707 L 15.682 12.606 L 15.982 24.811 C 15.973 25.186 16.118 25.547 16.383 25.812 C 16.647 26.077 17.009 26.221 17.383 26.212 C 17.757 26.221 18.119 26.077 18.384 25.812 C 18.648 25.547 18.793 25.186 18.784 24.811 L 18.484 12.706 L 21.685 15.607 C 21.944 15.881 22.309 16.027 22.686 16.007 C 23.058 16.009 23.417 15.866 23.686 15.607 C 24.189 15.073 24.189 14.24 23.686 13.706 Z " fill="#8d8d8d"/></g></g></g></g></g>
                                    </svg>

                                        <span class="step-text"> @{{ selectedVideo.uploading_text }}</span>

                                        

                                    </div>
                                    <span class="step-outer-circle">
                                        <i class="step-inner-circle"></i>
                                    </span>
                                    <div class="progress">
                                        <div id="progress-bar" class="progress-bar" style="width:@{{ selectedVideo.uploading_percentage }}%"></div>
                                    </div>
                                </li>
                                <li class="@{{ selectedVideo.transcoding_class }}">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 34.016 34.016" width="38" height="38">
                                        <g clip-path="url(#_clipPath_Et4h7nQuM1eCkqrMTLBnGHjw91wzKYJj)"><g><g><g><g><g/></g></g></g><g><g>
                                        <path d=" M 17.008 0 C 7.615 0 0 7.615 0 17.008 C 0 26.401 7.615 34.016 17.008 34.016 C 26.401 34.016 34.016 26.401 34.016 17.008 C 34.005 7.619 26.397 0.01 17.008 0 L 17.008 0 Z  M 17.008 31.274 C 9.129 31.274 2.741 24.887 2.741 17.008 C 2.741 9.129 9.129 2.741 17.008 2.741 C 24.887 2.741 31.274 9.129 31.274 17.008 C 31.266 24.883 24.883 31.266 17.008 31.274 L 17.008 31.274 Z " fill="#8d8d8d"/>
                                        <path d=" M 21.758 19.585 C 21.515 19.34 21.185 19.202 20.84 19.202 C 20.495 19.202 20.164 19.34 19.921 19.585 C 19.685 19.836 19.541 20.16 19.513 20.503 C 19.514 20.853 19.662 21.187 19.921 21.422 L 20.942 22.442 L 18.595 22.442 C 18.084 22.442 18.084 22.442 17.88 22.034 C 17.756 21.766 17.583 21.524 17.37 21.32 L 14.921 17.952 C 15.06 17.724 15.232 17.518 15.431 17.34 C 15.531 17.24 15.531 17.246 15.631 17.14 L 17.468 14.691 C 17.668 14.385 17.876 14.181 17.978 13.976 C 18.178 13.67 18.178 13.568 18.693 13.568 L 21.04 13.568 L 20.019 14.589 C 19.774 14.832 19.637 15.162 19.637 15.507 C 19.637 15.852 19.774 16.183 20.019 16.426 C 20.262 16.67 20.593 16.808 20.938 16.808 C 21.283 16.808 21.613 16.67 21.856 16.426 L 25.122 13.16 C 25.366 12.938 25.505 12.623 25.505 12.293 C 25.505 11.963 25.366 11.648 25.122 11.425 L 21.754 8.16 C 21.511 7.915 21.181 7.777 20.836 7.777 C 20.491 7.777 20.16 7.915 19.917 8.16 C 19.672 8.403 19.535 8.733 19.535 9.078 C 19.535 9.423 19.672 9.754 19.917 9.997 L 20.938 11.017 L 18.591 11.017 C 17.455 10.96 16.386 11.553 15.835 12.548 C 15.735 12.748 15.529 12.854 15.325 13.16 L 13.794 15.303 L 13.182 16.324 C 12.218 15.668 11.082 15.313 9.917 15.303 L 7.059 15.303 C 6.387 15.312 5.843 15.855 5.835 16.528 C 5.879 17.185 6.402 17.708 7.059 17.752 L 9.917 17.752 C 10.904 17.771 11.836 18.217 12.468 18.977 L 12.533 19.121 L 12.568 19.177 L 15.323 22.954 C 15.523 23.154 15.629 23.362 15.731 23.464 C 16.282 24.458 17.351 25.052 18.487 24.995 L 20.834 24.995 L 19.813 26.015 C 19.559 26.254 19.412 26.585 19.405 26.933 C 19.386 27.287 19.538 27.629 19.813 27.852 C 20.037 28.127 20.378 28.279 20.732 28.26 C 21.081 28.259 21.415 28.111 21.65 27.852 L 24.916 24.586 C 25.152 24.335 25.296 24.012 25.324 23.668 C 25.316 23.338 25.209 23.018 25.018 22.75 L 21.758 19.585 Z " fill="#8d8d8d"/></g></g></g></g>
                                        </svg>
                                        <span class="step-text"> @{{ selectedVideo.transcoding_text }}</span>
                                    </div>
                                    <span class="step-outer-circle">
                                        <i class="step-inner-circle">
                                        </i>
                                    </span>
                                    <div class="progress">
                                        <div id="progress-bar" class="progress-bar" style="width:@{{ selectedVideo.transcodingPercentage }}%"></div>
                                    </div>
                                </li>
                                <li class="process-completed @{{ selectedVideo.completed_class }}">
                                    <div class="icon-wrapper">
                                    <svg viewBox="0 0 34.016 34.016" width="38" height="38"><g clip-path="url(#_clipPath_redM47JRj1P3Sp3YAA16uKaDA4o0ODA2)"><g><g><g><path d=" M 17.008 0 C 7.615 0 0 7.615 0 17.008 C 0 26.401 7.615 34.016 17.008 34.016 C 26.401 34.016 34.016 26.401 34.016 17.008 C 34.005 7.619 26.397 0.01 17.008 0 L 17.008 0 Z  M 17.008 31.274 C 9.129 31.274 2.741 24.887 2.741 17.008 C 2.741 9.129 9.129 2.741 17.008 2.741 C 24.887 2.741 31.274 9.129 31.274 17.008 C 31.266 24.883 24.883 31.266 17.008 31.274 L 17.008 31.274 Z " fill="#8d8d8d"/><path d=" M 22.559 11.104 L 13.475 20.188 L 10.152 16.865 C 9.539 16.253 8.546 16.253 7.934 16.865 C 7.322 17.478 7.322 18.471 7.935 19.083 L 12.368 23.516 C 12.662 23.81 13.06 23.975 13.476 23.975 C 13.892 23.975 14.291 23.81 14.585 23.516 L 24.777 13.322 C 25.368 12.706 25.358 11.731 24.754 11.127 C 24.151 10.524 23.176 10.513 22.559 11.104 Z " fill="#8d8d8d"/></g></g></g></g></svg>
                                        <span class="step-text">Completed</span>
                                    </div>
                                    <span class="step-outer-circle">
                                        <i class="step-inner-circle"></i>
                                    </span>
                                </li>
                            </ul>
                            <div class="flexbox upload-active-status" >
                                <!-- <span class="upload_wait">{{ __('video::videos.transcoding_progress') }}</span> -->
                                <span class="upload_wait">  {{ __('video::videos.status_text') }}</span>
                                <span class="upload_file_status"><span id="completedVideobytes">@{{ selectedVideo.completedVideobytes }}</span>
                                {{ __('video::videos.uploaded_of') }} <span
                                    id="totalVideobytes">@{{ selectedVideo.totalVideobytes }}</span></span> 
                            </div>
                        </div>
                        <!-- Progress End -->
                        <div class="cancel_upload_progress flexbox align-items-center" data-toggle="modal"
                            data-target="#videoReplaceModal" class="cancel__upload"
                            data-ng-click="vgridCtrl.showReplaceVideo(selectedVideo.title)">
                            <svg x="0px" y="0px" width="8px" height="8px" viewBox="0 0 612 612" class="upload_cancel">
                                <g>
                                    <g id="cross">
                                        <g>
                                            <polygon
                                                points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397      306,341.411 576.521,611.397 612,575.997 341.459,306.011    "
                                                fill="#252629">
                                            </polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <span data-toggle="modal" data-target="#videoReplaceModal"
                                class="cancel__upload">{{ __('video::videos.cancel') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="browse_replace" ng-show="selectedVideo.showReplace">
                    <div class="edit_video_upload">
                        <div class="edit_video_file">
                            <div class="upload_file_input flexbox align-items-center justify-center flex-wrap">
                                <svg width="22px" height="22px" viewBox="0 0 45.544 45.544">
                                    <path id="upload"
                                        d="M1615.5,471.464a7.839,7.839,0,0,1-7.829-7.829V433.75a7.839,7.839,0,0,1,7.829-7.83h29.884a7.839,7.839,0,0,1,7.83,7.83v29.885a7.839,7.839,0,0,1-7.83,7.829Zm-3.591-37.715v29.885a3.6,3.6,0,0,0,3.591,3.591h29.884a3.6,3.6,0,0,0,3.593-3.591V433.75a3.6,3.6,0,0,0-3.593-3.592H1615.5A3.6,3.6,0,0,0,1611.907,433.75Zm16.376,24.624V447.166l-4.572,4.572-3-3,6.734-6.734h0l3-3h0l3,3h0l6.735,6.734-3,3-4.661-4.66v11.3Z"
                                        transform="translate(-1607.669 -425.92)" fill="#4c4c4c" opacity="0.8" />
                                </svg>
                                <input type="file" data-ng-click="vgridCtrl.browseReplaceVideo()"
                                    accept="video/*,.mkv,.mov,.avi,.mp4"
                                    class="filestyle upload_video" id="video" name="video"
                                    data-buttonName="btn-primary">
                                <span
                                    class="browse">{{ __('video::videos.browse') }}</span><span>&amp;{{ __('video::videos.replace_your_video') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="video-accepted-formats">( {{ __('video::videos.accepted_video_formats') }} )</p>
                </div>
                <div style="display: none" class="upload_error_alert">
                    <span>{{ __('video::videos.select_valid_file') }}</span>
                    <svg x="0px" y="0px" width="8px" height="8px" viewBox="0 0 612 612"
                        data-ng-click="vgridCtrl.hideUploadOption()">
                        <g>
                            <g id="cross">
                                <g>
                                    <polygon
                                        points="612,36.004 576.521,0.603 306,270.608 35.478,0.603 0,36.004 270.522,306.011 0,575.997 35.478,611.397 306,341.411 576.521,611.397 612,575.997 341.459,306.011"
                                        fill="#6b7d8b"></polygon>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>
            <form id="videoEditForm" name="videoEditForm" method="POST" data-base-validator
                data-ng-submit="vgridCtrl.saveVideoEdit($event, selectedVideo.id)" enctype="multipart/form-data">
                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <div class="cover-image">
                        <h4>{{ __('video::videos.poster') }}</h4>
                        <div class="image-content">
                            <img ng-show="selectedVideo.poster_image.length > 0"
                                ng-class="{'active':selectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ selectedVideo.id }}" alt=""
                                ng-src="@{{selectedVideo.poster_image  }}" />

                            <img ng-show="selectedVideo.poster_image.length == 0"
                                ng-class="{'active':selectedVideo.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ selectedVideo.id }}" alt=""
                                ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': selectedVideo.poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="selectedVideo.poster_image.length"
                                                    class="flexbox align-items-center">
                                                    <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px" width="27px"
                                                        height="27px">
                                                        <g>
                                                            <path opacity="0.702"
                                                                d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.upload_cover_picture') }}</span>
                                                </div>
                                                <div ng-hide="!selectedVideo.poster_image.length"
                                                    class="flexbox align-items-center ng-hide">
                                                    <svg x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899">
                                                        <g>
                                                            <path
                                                                d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981   c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611   C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069   L27.473,390.597L0.3,512.69z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.change_cover_picture') }}</span>
                                                </div>
                                                <input type="file" class="uploadPosterImg" name="image"
                                                    data-video-index="@{{ selectedVideo.id }}">
                                            </div>
                                            <p>{{ __('video::videos.poster_file_hint') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.poster_image.has">@{{errors.poster_image.message}}</p>
                    </div>
                    <div class="thumbnail-image">
                        <h4>{{ __('video::videos.thumbnail')}}</h4>
                        <div class="image-content">
                            <img ng-show="selectedVideo.thumbnail_image.length > 0"
                                ng-class="{'active': selectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ selectedVideo.id }}" alt=""
                                ng-src="@{{ selectedVideo.thumbnail_image }}" />

                            <img ng-show="selectedVideo.thumbnail_image.length == 0"
                                ng-class="{'active': selectedVideo.thumbnail_image}"
                                class="uploaded_img uploaded_img_@{{ selectedVideo.id }}" alt="" ng-src="" />
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': selectedVideo.thumbnail_image.length > 0}">
                                            <svg class="upload_img_ic" viewBox="0 0 27 27" version="1.1" x="0px" y="0px"
                                                width="27px" height="27px">
                                                <g>
                                                    <path opacity="0.702"
                                                        d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                        fill="#ffffff"></path>
                                                </g>
                                            </svg>
                                            <div class="input">
                                                <div ng-hide="selectedVideo.thumbnail_image.length">
                                                    <span>{{ __('video::videos.upload_thumbnail_image') }}</span>
                                                </div>
                                                <div ng-hide="!selectedVideo.thumbnail_image.length"
                                                    class="ng-hide flexbox align-items-center">
                                                    <svg class="change_img_ic" x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899"">
                                                            <g>
                                                                <path d="
                                                        M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z
                                                        M518.113,63.177l-47.981-47.981
                                                        c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611
                                                        C532.495,100.753,532.495,77.559,518.113,63.177z
                                                        M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069
                                                        L27.473,390.597L0.3,512.69z" fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.change_thumbnail_image') }}</span>
                                                </div>
                                                <input type="file" class="uploadImg" name="image"
                                                    data-video-index="@{{ selectedVideo.id }}">
                                            </div>
                                            <p ng-if="!livePage">{{ __('video::videos.thumb_file_hint') }}</p>
                                            <p ng-if="livePage">( Only jpeg, png files allowed with a minimum dimension of 338x170 )</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.thumbnail_image.has">
                            @{{errors.thumbnail_image.message}}</p>
                    </div>
                </div>

                <!-- mobile postet start -->
                <div ng-if="!livePage" class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.mobile_poster_image.has}">
                    <div class="thumbnail-image">
                        <h4>MObile poster image</h4>
                        <div class="image-content">
                            <img ng-show="selectedVideo.mobile_poster_image.length > 0"
                                 ng-class="{'active':selectedVideo.mobile_poster_image}"
                                 class="uploaded_mobile_poster_img uploaded_mobile_poster_img_@{{ selectedVideo.id }}" alt=""
                                 ng-src="@{{selectedVideo.mobile_poster_image  }}" />

                            <img ng-show="selectedVideo.mobile_poster_image.length == 0"
                                 ng-class="{'active':selectedVideo.mobile_poster_image}"
                                 class="uploaded_mobile_poster_img uploaded_mobile_poster_img_@{{ selectedVideo.id }}" alt=""
                                 ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                             data-ng-class="{'change-image': selectedVideo.mobile_poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="selectedVideo.mobile_poster_image.length"
                                                     class="flexbox align-items-center">
                                                    <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px" width="27px"
                                                         height="27px">
                                                        <g>
                                                            <path opacity="0.702"
                                                                  d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                  fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>upload cover picture</span>
                                                </div>
                                                <div ng-hide="!selectedVideo.mobile_poster_image.length"
                                                     class="flexbox align-items-center ng-hide">
                                                    <svg x="0px" y="0px" width="13" height="13"
                                                         viewBox="0 0 528.899 528.899">
                                                        <g>
                                                            <path
                                                                    d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981   c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611   C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069   L27.473,390.597L0.3,512.69z"
                                                                    fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.change_cover_picture') }}</span>
                                                </div>
                                                <input type="file" class="uploadMobilePosterImg" name="image"
                                                       data-video-index="@{{ selectedVideo.id }}">
                                            </div>
                                            <p>( Only jpeg, png files allowed with a minimum dimension of 380x500 )</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.mobile_poster_image.has">@{{errors.mobile_poster_image.message}}</p>
                    </div>
                </div>
                <!-- mobile poster end -->

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                            <label ng-if="!livePage">Title one
                                <span class="required">*</span>
                            </label>
                            <label ng-if="livePage">Title
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.title.has}">
                                <input type="text" name="title" class="form-control"
                                    placeholder="Enter Title"
                                    data-ng-model="selectedVideo.title">
                            </div>
                            <p class="error-msg" data-ng-show="errors.title.has">@{{errors.title.message}}</p>
                        </div>

                        <div class="form-group" ng-if="!livePage">
                            <label>Title Two
                            </label>
                            <div class="form-input">
                                <input type="text" name="title_two" class="form-control"
                                    placeholder="Enter Title Two"
                                    data-ng-model="selectedVideo.title_two">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label>{{ __('video::videos.tags') }}</label>
                            <div class="form-input">
                                <tags-input class="custom_ng_tags" placeholder="{{ __('video::videos.tag_message') }}"
                                    ng-model="selectedVideo.search_tag"></tags-input>
                            </div>
                        </div> -->
                       
                        <!-- <div class="form-group">
                            <label>{{ __('video::videos.presenter') }}</label>
                            <div class="form-input">
                                <input type="text" name="presenter" class="form-control"
                                    placeholder="{{ __('video::videos.principal_performer') }}"
                                    data-ng-model="selectedVideo.presenter">
                            </div>
                            <p class="error-msg" data-ng-show="errors.presenter.has">@{{errors.presenter.message}}</p>
                        </div> -->
                        <div class="form-group" ng-if="!livePage">
                            <label>{{__('video::videos.subtitle_language') }}</label>
                            <div ng-show="selectedVideo.showsubTitleList" class="form-input tags-subtitles flexbox">
                                <div class="tags">
                                    <ul>
                                        <li ng-repeat="subtitles in selectedVideo.subTitleList">
                                            <span>@{{ subtitles.language }}</span>
                                            <a class="remove-button"
                                                ng-click="vgridCtrl.subTitleDelete($index,selectedVideo.id)"></a>
                                        </li>
                                    </ul>
                                </div>
                                <i data-ng-click="vgridCtrl.addSubTitle()" class="upload-subtitle" data-toggle="modal"
                                    data-target="#subtitle">
                                    <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                        <g>
                                            <path
                                                d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                                fill="#ffffff" />
                                        </g>
                                    </svg>
                                </i>
                            </div>
                            <button type="button" ng-show="selectedVideo.showMainSubtitle" data-toggle="modal"
                                data-target="#subtitle" class="subtitle-add submitbutton subtitle_btn"
                                data-ng-click="vgridCtrl.addSubTitle()">
                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                    <g>
                                        <path
                                            d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                            fill="#ffffff"></path>
                                    </g>
                                </svg>
                                <span>{{ __('video::videos.upload_subtitle') }}</span>

                            </button>
                        </div>
                        <!-- Audio Multiple Uploads -->
                        <div class="form-group" ng-if="!livePage
                        && (selectedVideo.job_status=='Uploaded' 
                        || selectedVideo.job_status=='Video Uploaded' 
                        || selectedVideo.job_status=='Convert to MP4' 
                        || selectedVideo.job_status=='Progressing'
                        || selectedVideo.job_status=='Complete')"
                        >
                            <label>{{__('video::videos.add_audios') }}</label>
                            <div ng-show="!selectedVideo.showIfNoAudios" class="form-input tags-subtitles flexbox">
                                <div class="tags">
                                    <ul>
                                        <li ng-repeat="audioTrack in selectedVideo.audioTrackList">
                                            <span>@{{ audioTrack.audio_title }}</span>
                                            <a class="remove-button"
                                                ng-click="vgridCtrl.audioTrackDelete($index,selectedVideo.id, audioTrack.id)"></a>
                                        </li>
                                    </ul>
                                </div>
                                <i data-ng-click="vgridCtrl.prepareAudioUpload()" class="upload-subtitle" data-toggle="modal"
                                    data-target="#audios">
                                    <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                        <g>
                                            <path
                                                d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                                fill="#ffffff" />
                                        </g>
                                    </svg>
                                </i>
                            </div>
                            <button type="button" ng-show="selectedVideo.showIfNoAudios" data-toggle="modal"
                                data-target="#audios" class="subtitle-add submitbutton subtitle_btn"
                                data-ng-click="vgridCtrl.prepareAudioUpload()">
                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                    <g>
                                        <path
                                            d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                            fill="#ffffff"></path>
                                    </g>
                                </svg>
                                <span>{{ __('video::videos.upload_audio') }}</span>

                            </button>
                        </div>
                        <!-- <div class="form-group" data-ng-class="{'has-error': errors.price.has}" ng-if="!livePage">
                            <label>
                                {{ __('video::videos.price') }}
                                
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.price.has}">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('video::videos.enter_price') }}"
                                    data-ng-model="selectedVideo.price">
                            </div>
                            <p class="error-msg" data-ng-show="errors.price.has">@{{errors.price.message}}</p>
                        </div> -->

                        <div class="form-group" ng-if="livePage || ( editPage && selectedVideo.is_live ) ">
                            <label>
                                {{__('video::videos.stream_by')}}
                            </label>
                            <div class="form-input">
                                <select myPlaceholder="{{ __('video::videos.stream_by') }}" ng-if="editPage"
                                    makeDisable="1" allowClear="1" data-jquery="select2_custom_ddl"
                                    myValue="selectedVideo.liveType" class="form-control"
                                    data-ng-model="selectedVideo.liveType">
                                    <option value="aspect_ratio">{{__('video::videos.aspect_ratio')}}</option>
                                    <option value="hls">{{__('video::videos.hls_url')}}</option>
                                </select>

                                <select myPlaceholder="{{ __('video::videos.stream_by') }}" ng-if="!editPage"
                                    allowClear="1" data-jquery="select2_custom_ddl" myValue="selectedVideo.liveType"
                                    class="form-control" data-ng-model="selectedVideo.liveType">
                                    <option value="aspect_ratio">{{__('video::videos.aspect_ratio')}}</option>
                                    <option value="hls">{{__('video::videos.hls_url')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" data-ng-class="{'has-error': errors.post_creator.has}"
                            ng-if="selectedVideo.liveType == 'aspect_ratio'">
                            <label>{{__('video::videos.stream_video_ratio')}}

                            </label>
                            <div class="form-input">
                                <select myPlaceholder="{{ __('video::videos.stream_video_ratio') }}" ng-if="editPage"
                                    makeDisable="1" minimumResults="5" allowClear="1" data-jquery="select2_custom_ddl"
                                    myValue="selectedVideo.aspect_ratio" class="form-control" name="aspect_ratio"
                                    data-ng-model="selectedVideo.aspect_ratio">
                                    <option value="640X360">640X360</option>
                                    <option value="1280X720">1280X720</option>
                                    <option value="1920X1080">1920X1080</option>
                                </select>

                                <select myPlaceholder="{{ __('video::videos.stream_video_ratio') }}" ng-if="!editPage"
                                    minimumResults="5" allowClear="1" data-jquery="select2_custom_ddl"
                                    myValue="selectedVideo.aspect_ratio" class="form-control" name="aspect_ratio"
                                    data-ng-model="selectedVideo.aspect_ratio">
                                    <option value="640X360">640X360</option>
                                    <option value="1280X720">1280X720</option>
                                    <option value="1920X1080">1920X1080</option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.aspect_ratio.has">@{{
                                    errors.aspect_ratio.message }}</p>
                        </div>

                        <div class="form-group" data-ng-class="{'has-error': errors.hls.has}"
                            ng-if="selectedVideo.liveType == 'hls'">
                            <label>
                                {{__('video::videos.hls_url')}}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <input ng-if="editPage"  type="text" name="hls"
                                    data-ng-model="selectedVideo.hls" class="form-control"
                                    placeholder="{{__('video::videos.enter_hls_url')}}" value="{{old('hls')}}" />

                                <input ng-if="!editPage" type="text" name="hls" data-ng-model="selectedVideo.hls"
                                    class="form-control" placeholder="{{__('video::videos.enter_hls_url')}}"
                                    value="{{old('hls')}}" />


                            </div>
                            <p class="error-msg" data-ng-show="errors.hls.has">@{{ errors.hls.message }}</p>
                        </div>

                        <div class="form-group" data-ng-class="{'has-error': errors.scheduled_time.has}"
                            ng-if="livePage || selectedVideo.is_live">
                            <label>
                                {{__('video::videos.scheduled_time')}}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input ng-if="!editPage" futureDate="true" myValue="selectedVideo.scheduled_time"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="scheduled_time" id="scheduled_time"
                                    data-ng-model="selectedVideo.scheduled_time" size="30"
                                    placeholder="{{__('video::videos.scheduled_time')}}"
                                    data-validation-name="scheduled_time" value="{{date ( "Y-m-d H:i:s")}}"
                                    class="form-control" ng-blur="dateBlur($event,selectedVideo.scheduled_time)"
                                    ng-keyup="dateKeyup($event,selectedVideo.scheduled_time)" />

                                <input ng-if="editPage" futureDate="true" myvalue="selectedVideo.scheduled_time"myValue="selectedVideo.scheduled_time" autocomplete="off"
                                    data-jquery="date_time_picker" datetime-picker type="text" name="scheduled_time"
                                    id="scheduled_time" data-ng-model="selectedVideo.scheduled_time" size="30"
                                    placeholder="{{__('video::videos.scheduled_time')}}"
                                    data-validation-name="scheduled_time" value="{{date ( "Y-m-d H:i:s")}}"
                                    class="form-control" ng-blur="dateBlur($event,selectedVideo.scheduled_time)"
                                    ng-keyup="dateKeyup($event,selectedVideo.scheduled_time)" />


                            </div>
                            <p class="error-msg" data-ng-show="errors.scheduled_time.has">
                                @{{ errors.scheduled_time.message }}</p>
                        </div>
                        <div  class="form-group" data-ng-class="{'has-error': errors.expire_scheduled_time.has}"
                            ng-if="livePage || selectedVideo.is_live">
                            <label>
                                Expire Scheduled Time
                                <span class="required">*</span>
                            </label>
                            <div class="form-input calender-left">
                                <i class="calender-icon"></i>
                                <input ng-if="!editPage" futureDate="true" myValue="selectedVideo.expire_scheduled_time"
                                    autocomplete="off" data-jquery="date_time_picker" datetime-picker type="text"
                                    name="expire_scheduled_time" id="expire_scheduled_time"
                                    data-ng-model="selectedVideo.expire_scheduled_time" size="30"
                                    placeholder="Expire Scheduled Time"
                                    data-validation-name="expire_scheduled_time" value="{{date ( "Y-m-d H:i:s")}}"
                                    class="form-control" ng-blur="dateBlur($event,selectedVideo.expire_scheduled_time)"
                                    ng-keyup="dateKeyup($event,selectedVideo.expire_scheduled_time)" />

                                <input ng-if="editPage" futureDate="true" myvalue="selectedVideo.expire_scheduled_time"myValue="selectedVideo.expire_scheduled_time" autocomplete="off"
                                    data-jquery="date_time_picker" datetime-picker type="text" name="expire_scheduled_time"
                                    id="expire_scheduled_time" data-ng-model="selectedVideo.expire_scheduled_time" size="30"
                                    placeholder="Expire Scheduled Time"
                                    data-validation-name="expire_scheduled_time" value="{{date ( "Y-m-d H:i:s")}}"
                                    class="form-control" ng-blur="dateBlur($event,selectedVideo.expire_scheduled_time)"
                                    ng-keyup="dateKeyup($event,selectedVideo.expire_scheduled_time)" />


                            </div>
                            <p class="error-msg" data-ng-show="errors.expire_scheduled_time.has">
                                @{{ errors.expire_scheduled_time.message }}</p>
                        </div>

                        <!-- <div class="form-group">
                            <label>{{ __('video::videos.ads') }}</label>
                            <div class="form-input">
                                <select allowClear="1" data-jquery="select2_custom_ddl" myValue="selectedVideo.ads"
                                    myPlaceholder="{{ __('video::videos.select_ads') }}" class=" select2_custom_ddl"
                                    style="width:100px" data-ng-model="selectedVideo.ads"
                                    data-ng-options="a.id as a.title  for a in vgridCtrl.ads_info">
                                    <option value=""> SELECT </option>
                                </select> 
                            </div>
                        </div> -->

                        <div class="form-group" ng-if="!livePage">
                            <label>Browse & Replace the trailer Video</label>
                            <!-- <div>
                                <div class="video-player" ng-if="selectedVideo.trailer_url">
                                     <video autoplay="autoplay" preload="auto">
                                        <source src="@{{selectedVideo.trailer_url}}" type="video/mp4" />
                                    </video>
                                </div>
                            </div> -->
                            <a class="intimation" href="@{{selectedVideo.trailer_url}}" target="_blank">@{{selectedVideo.trailer_url}}</a>
                            <div class="trailer-button">
                                <button type="button" class="subtitle-add submitbutton subtitle_btn">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <input type="file" id="trailer_file" name="trailer_file"
                                            data-ng-model="trailer.trailer_file" accept="video/mp4"
                                            ng-files="validateTrailer($files,selectedVideo.id)">
                                    </div>
                                    <span>Select Video</span>
                                </button>
                                <a ng-if="selectedVideo.trailer_url" class="remove-trailer" data-target="#removeTrailerModel" data-toggle="modal" data-ng-click="vgridCtrl.removeTrailer(selectedVideo.id, selectedVideo.title)">
                                    <span>Remove</span>
                                </a>
                            </div>
                            <p class="error-msg trailer-show-error" data-ng-show="trailer.trailer_file == 'error'">
                                File not supported
                            </p>
                            <p class="error-msg trailer-show-error" data-ng-show="trailer.trailer_file == 'size-error'">
                                File size should be less than 15MB
                            </p>
                            <p class="info-msg">{{ trans('video::videos.accepted_banner_video_formats') }}. File size
                                should be lesser than 15MB.</p>
                            <p class="info-msg success-msg" id="trailer-show-success">
                            <p class="info-msg success-msg" id="trailer-uploading-button" style="display: none;"> Uploading...</p>
                            
                               
                            </p>
                            <p id="trailer-show-name" class="info-msg trailer-name"></p>
                            <button id="trailer-show-button" style="display: none;" type="button"
                                class="subtitle-add submitbutton subtitle_btn trailer-upload"
                                ng-click="addTrailer(selectedVideo.id)">
                                <svg viewBox="0 0 13 15" version="1.1" x="0px" y="0px" width="13px" height="15px">
                                    <g>
                                        <path
                                            d="M 0.5228 6.4618 C 0.5693 6.5747 0.6778 6.6484 0.7981 6.6484 L 4.0627 6.6484 L 4.0627 14.1979 C 4.0627 14.3646 4.1962 14.4999 4.3607 14.4999 L 9.1274 14.4999 C 9.2918 14.4999 9.4253 14.3646 9.4253 14.1979 L 9.4253 6.6484 L 12.7023 6.6484 C 12.8227 6.6484 12.9312 6.5746 12.9777 6.4623 C 13.0236 6.3494 12.9985 6.2195 12.9133 6.1332 L 6.9698 0.0886 C 6.9138 0.032 6.8382 -0.0002 6.7589 -0.0002 C 6.6796 -0.0002 6.604 0.032 6.548 0.0881 L 0.5872 6.1326 C 0.502 6.2189 0.4763 6.3488 0.5228 6.4618 Z"
                                            fill="#ffffff"></path>
                                    </g>
                                </svg>
                                <span>Upload Trailer</span>
                            </button>
                        </div>
                    </div>
                    <div class="one-set width-50">
                        <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                            <label>
                                {{ __('video::videos.description') }}
                                <!-- <span class="required">*</span> -->
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.description.has}">
                                <textarea rows="7" name="description"  maxlength="50000"  class="form-control"
                                    data-ng-model="selectedVideo.description"
                                    placeholder="  {{ __('video::videos.description_message') }}"></textarea>
                            </div>
                            <p class="error-msg" data-ng-show="errors.description.has">@{{errors.description.message}}
                            </p>
                        </div>
                        <!-- kids start -->

                        <div class="form-group" data-ng-if="!livePage || ( editPage && !selectedVideo.is_live )">
                            <div class="webseries switch-concept flexbox align-items-center">
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Is the video is part of the Kids</span>
                                    <div class="right-side">
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="selectedVideo.is_kids" data-ng-change="vgridCtrl.changeKids()" >
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>                           
                        
                        <!-- kids end -->
                            <div class="form-group" data-ng-if="!livePage || ( editPage && !selectedVideo.is_live )">
                                <div class="webseries switch-concept flexbox align-items-center">
                                    <svg viewBox="-24 0 384 384.00007">
                                        <path
                                            d="m7.96875 381.839844c4.941406 2.863281 11.015625 2.878906 15.96875.046875l104.0625-59.453125 104.0625 59.460937c2.457031 1.402344 5.195312 2.105469 7.9375 2.105469 2.777344 0 5.542969-.71875 8.03125-2.160156 4.929688-2.863282 7.96875-8.136719 7.96875-13.839844v-132.433594l32.0625 18.320313c2.457031 1.417969 5.203125 2.113281 7.9375 2.113281 3.105469 0 6.191406-.902344 8.871094-2.6875 5.039062-3.359375 7.753906-9.289062 7-15.296875l-7.167969-57.296875 27.785156-34.71875c3.542969-4.433594 4.480469-10.414062 2.457031-15.710938-2.035156-5.304687-6.722656-9.128906-12.3125-10.066406l-41.167968-6.863281-25.464844-38.207031v-59.152344c0-8.832031-7.167969-16-16-16h-128c-8.832031 0-16 7.167969-16 16s7.167969 16 16 16h112v43.152344l-25.472656 38.199218-41.160156 6.863282c-5.597657.9375-10.289063 4.761718-12.320313 10.066406-2.023437 5.304688-1.09375 11.277344 2.457031 15.710938l27.777344 34.71875-7.160156 57.296874c-.753906 6.007813 1.957031 11.9375 7 15.296876 5.039062 3.367187 11.566406 3.589843 16.816406.574218l32.0625-18.3125v104.859375l-88.0625-50.320312c-4.914062-2.816407-10.960938-2.816407-15.875 0l-88.0625 50.328125v-308.433594h16c8.832031 0 16-7.167969 16-16s-7.167969-16-16-16h-32c-8.832031 0-16 7.167969-16 16v352c0 5.703125 3.03125 10.976562 7.96875 13.839844zm196.527344-215.839844-14.960938-18.703125 21.105469-3.519531c4.359375-.730469 8.222656-3.226563 10.679687-6.90625l18.679688-28.03125 18.6875 28.03125c2.457031 3.679687 6.320312 6.175781 10.679688 6.90625l21.105468 3.519531-14.960937 18.703125c-2.703125 3.375-3.917969 7.695312-3.382813 11.984375l4.03125 32.246094-28.214844-16.117188c-.183593-.105469-.386718-.136719-.578124-.234375-.847657-.445312-1.734376-.808594-2.671876-1.09375-.320312-.097656-.632812-.21875-.960937-.296875-1.207031-.296875-2.445313-.488281-3.734375-.488281s-2.527344.191406-3.734375.480469c-.328125.078125-.640625.199219-.960937.296875-.929688.285156-1.816407.648437-2.671876 1.09375-.183593.097656-.394531.128906-.578124.234375l-28.214844 16.117187 4.03125-32.246094c.546875-4.28125-.679688-8.601562-3.375-11.976562zm0 0"
                                            fill="#3d3d3d" />
                                    </svg>
                                    <div class="swich-content flexbox align-items-center flex-wrap">
                                        <span>{{ __('video::videos.webseries') }}</span>
                                        <div class="right-side">
                                            <label class="switch">
                                                <input type="checkbox" name="status" ng-model="selectedVideo.is_webseries" data-ng-change="vgridCtrl.webseriesChange()">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <!-- <div class="form-group" data-ng-class="{'has-error': errors.category.has}">
                            <label>
                                <span ng-if="selectedVideo.is_webseries">
                                    {{ __('video::videos.web_series') }}
                                </span>
                                <span ng-if="!selectedVideo.is_webseries">
                                    {{ __('video::videos.category') }}
                                </span>
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <select  ng-if="selectedVideo.is_webseries" data-jquery="select2_custom_ddl" myValue="selectedVideo.category"
                                    myPlaceholder="{{ __('video::videos.select_web_series') }}" 
                                    ng-options="item.id as item.title group by item.parent for item in vgridCtrl.formatCategories"
                                    ng-init="vgridCtrl.editVideo.category" name="category"
                                    class="admin_category_sub form-control select2_custom_ddl" style="width:100px"
                                    data-ng-model="selectedVideo.category" data-ng-change="vgridCtrl.changeCategory()">

                                    <option value=""></option>
                                </select>
                                <select multiple ng-if="!livePage && (!selectedVideo.is_webseries && !selectedVideo.is_live)" data-jquery="select2_custom_ddl" myValue="selectedVideo.category"
                                    myPlaceholder="{{ __('video::videos.select_category') }}" 
                                    ng-options="item.id as item.title group by item.parent for item in vgridCtrl.formatCategories"
                                    ng-init="vgridCtrl.editVideo.category" name="category"
                                    class="admin_category_sub form-control select2_custom_ddl" style="width:100px"
                                    data-ng-model="selectedVideo.category" data-ng-change="vgridCtrl.changeCategory()">

                                    <option value=""></option>
                                </select>

                                <select multiple ng-if="livePage || (!selectedVideo.is_webseries && selectedVideo.is_live)" data-jquery="select2_custom_ddl" myValue="selectedVideo.category"
                                    myPlaceholder="{{ __('video::videos.select_category') }}" 
                                    ng-options="item.id as item.title group by item.parent for item in vgridCtrl.liveCategories"
                                    ng-init="vgridCtrl.editVideo.category" name="category"
                                    class="admin_category_sub form-control select2_custom_ddl" style="width:100px"
                                    data-ng-model="selectedVideo.category" data-ng-change="vgridCtrl.changeCategory()">

                                    <option value=""></option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.category.has">@{{errors.category.message}}</p>
                        </div> -->
                        <br/><br/>
                        <!-- <div class="form-group" ng-show="showSeasons">
                            <label>{{ __('video::videos.season') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <select allowClear="1" data-jquery="select2_custom_ddl" myValue="selectedVideo.season"
                                    myPlaceholder="{{ __('video::videos.select_seasons') }}"
                                    class="admin_category_sub form-control select2_custom_ddl"
                                    data-ng-options="id as item for (id,item) in vgridCtrl.allSeasons"
                                    data-ng-model="selectedVideo.season">
                                    <option value=""></option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.season.has">@{{errors.season.message}}</p>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.episode_order.has}" ng-show="showSeasons">
                            <label>
                                {{ __('video::videos.episode_order') }}
                            </label>

                            <div class="form-input" data-ng-class="{'has-error': errors.episode_order.has}">
                                <input type="number" class="form-control" min="1"
                                placeholder="{{ __('video::videos.enter_episode_order') }}"
                                data-ng-model="selectedVideo.episode_order">
                            </div>
                            <p class="error-msg" data-ng-show="errors.episode_order.has">@{{errors.episode_order.message}}</p>
                        </div>
                        <div ng-if="!livePage && !selectedVideo.is_webseries" class="form-group">
                            <label>{{ __('video::videos.genre') }}</label>
                            <div class="form-input">
                                <select multiple data-jquery="select2_custom_ddl" myValue="selectedVideo.group"
                                    myPlaceholder="{{__('video::videos.select_genre')}}"
                                    class="admin_category_sub form-control select2_custom_ddl"
                                    data-ng-model="selectedVideo.group"
                                    data-ng-options="id as item for (id,item) in vgridCtrl.allCollection ">
                                    <option value=""></option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.group.has">@{{errors.group.message}}</p>
                        </div>
                       
                        <div ng-if="!livePage" class="form-group" >
                            <label>
                                {{ __('video::videos.release_year') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" >
                                <input type="number" onkeypress="return this.value.length < 4;" oninput="if(this.value.length>=4) { this.value = this.value.slice(0,4); }" class="form-control" name="release_year"
                                    placeholder="{{ __('video::videos.enter_release_year') }}"
                                    data-ng-model="selectedVideo.release_year" >
                            </div>
                            <p class="error-msg release_year" data-ng-show="errors.release_year.has">@{{errors.release_year.message}}</p>
                        </div> -->

                               
                        <!-- parental lock pin Starts-->
                        <!-- <div ng-if="!livePage && !selectedVideo.is_kids" class="form-group">
                            <label>Parental Lock Restricted Video</label>
                            <div class="form-input">
                                <select data-jquery="select2_custom_ddl" name="status"
                                    class="admin_category_sub form-control select2_custom_ddl" myValue="selectedVideo.is_parental"
                                    myPlaceholder="Select parental Lock"                                     data-ng-model="selectedVideo.is_parental"
                                    ng-init="selectedVideo.is_parental">
                                    <option value=""></option>
                                    <option ng-selected="selectedVideo.is_parental == 1" value="1" >Yes</option>
                                    <option ng-selected="selectedVideo.is_parental == 0" value="0">No</option>
                                    
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.group.has">@{{errors.group.message}}</p>
                        </div> -->

                        <div class="form-group">
                        <div ng-if="selectedVideo.is_parental == 1 && !selectedVideo.is_kids">
                            <label>                             
                            Age limit  
                            <span class="required">*</span>                       
                            </label>
                            <div class="form-input">
                                    <select  allowClear="1" data-jquery="select2_custom_ddl" name="age_limit"
                                    class="admin_category_sub form-control select2_custom_ddl" myValue="selectedVideo.age_limit"
                                    myPlaceholder="Select age limit"                                     data-ng-model="selectedVideo.age_limit"
                                    ng-init="selectedVideo.age_limit">
                                    <option value="">--- Select ---</option>
                                    <option ng-selected="selectedVideo.age_limit == 'G'" value="G" >G</option>
                                    <option ng-selected="selectedVideo.age_limit == 'PG'" value="PG">PG</option>
                                    <option ng-selected="selectedVideo.age_limit == '13+'" value="13+">13+</option>
                                    <option ng-selected="selectedVideo.age_limit == '16+'" value="16+">16+</option>
                                    <option ng-selected="selectedVideo.age_limit == 'R'" value="R">R</option>
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.age_limit.has">Age limit required</p>
                        </div>
                    </div>
                        <!-- parental lock pin End-->

                        <div class="form-group" ng-if="!livePage">
                        <div>
                            <label>                             
                            Video Quality  
                            </label>
                            <div class="form-input">
                                    <select  allowClear="1" data-jquery="select2_custom_ddl" name="video_quality"
                                    class="admin_category_sub form-control select2_custom_ddl" myValue="selectedVideo.video_quality"
                                    myPlaceholder="Select video quality" data-ng-model="selectedVideo.video_quality"
                                    ng-init="selectedVideo.video_quality">
                                    <option value="">--- Select ---</option>
                                    <option ng-selected="selectedVideo.video_quality == 'SD'" value="SD" >SD</option>
                                    <option ng-selected="selectedVideo.video_quality == 'HD'" value="HD">HD</option>
                                    <option ng-selected="selectedVideo.video_quality == 'FHD'" value="FHD">FHD</option>
                                    <option ng-selected="selectedVideo.video_quality == 'UHD'" value="UHD">UHD</option>
                                    <option ng-selected="selectedVideo.video_quality == '4K'" value="4K">4K</option>
                                </select>
                            </div>
                        </div>
                    </div>

                        <!-- <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="-24 0 384 384.00007">
                                    <path
                                        d="m7.96875 381.839844c4.941406 2.863281 11.015625 2.878906 15.96875.046875l104.0625-59.453125 104.0625 59.460937c2.457031 1.402344 5.195312 2.105469 7.9375 2.105469 2.777344 0 5.542969-.71875 8.03125-2.160156 4.929688-2.863282 7.96875-8.136719 7.96875-13.839844v-132.433594l32.0625 18.320313c2.457031 1.417969 5.203125 2.113281 7.9375 2.113281 3.105469 0 6.191406-.902344 8.871094-2.6875 5.039062-3.359375 7.753906-9.289062 7-15.296875l-7.167969-57.296875 27.785156-34.71875c3.542969-4.433594 4.480469-10.414062 2.457031-15.710938-2.035156-5.304687-6.722656-9.128906-12.3125-10.066406l-41.167968-6.863281-25.464844-38.207031v-59.152344c0-8.832031-7.167969-16-16-16h-128c-8.832031 0-16 7.167969-16 16s7.167969 16 16 16h112v43.152344l-25.472656 38.199218-41.160156 6.863282c-5.597657.9375-10.289063 4.761718-12.320313 10.066406-2.023437 5.304688-1.09375 11.277344 2.457031 15.710938l27.777344 34.71875-7.160156 57.296874c-.753906 6.007813 1.957031 11.9375 7 15.296876 5.039062 3.367187 11.566406 3.589843 16.816406.574218l32.0625-18.3125v104.859375l-88.0625-50.320312c-4.914062-2.816407-10.960938-2.816407-15.875 0l-88.0625 50.328125v-308.433594h16c8.832031 0 16-7.167969 16-16s-7.167969-16-16-16h-32c-8.832031 0-16 7.167969-16 16v352c0 5.703125 3.03125 10.976562 7.96875 13.839844zm196.527344-215.839844-14.960938-18.703125 21.105469-3.519531c4.359375-.730469 8.222656-3.226563 10.679687-6.90625l18.679688-28.03125 18.6875 28.03125c2.457031 3.679687 6.320312 6.175781 10.679688 6.90625l21.105468 3.519531-14.960937 18.703125c-2.703125 3.375-3.917969 7.695312-3.382813 11.984375l4.03125 32.246094-28.214844-16.117188c-.183593-.105469-.386718-.136719-.578124-.234375-.847657-.445312-1.734376-.808594-2.671876-1.09375-.320312-.097656-.632812-.21875-.960937-.296875-1.207031-.296875-2.445313-.488281-3.734375-.488281s-2.527344.191406-3.734375.480469c-.328125.078125-.640625.199219-.960937.296875-.929688.285156-1.816407.648437-2.671876 1.09375-.183593.097656-.394531.128906-.578124.234375l-28.214844 16.117187 4.03125-32.246094c.546875-4.28125-.679688-8.601562-3.375-11.976562zm0 0"
                                        fill="#3d3d3d" />
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>{{ __('video::videos.premium') }}</span>
                                    <div class="right-side">
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="selectedVideo.is_premium">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                    <g>
                                        <path
                                            d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>{{ __('video::videos.status') }}</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="selectedVideo.is_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 14 18" version="1.1" x="0px" y="0px" width="14px" height="18px">
                                    <g>
                                        <path id="Forma%201"
                                            d="M 13.9663 13.1473 C 13.899 12.7867 13.5536 12.5493 13.1948 12.6168 C 12.8361 12.6844 12.5996 13.0314 12.6669 13.3918 C 12.7167 13.6593 12.5997 13.8499 12.5255 13.9397 C 12.4515 14.0292 12.287 14.1794 12.0172 14.1794 L 1.9827 14.1794 C 1.7128 14.1794 1.5484 14.0293 1.4744 13.9397 C 1.4003 13.8499 1.2832 13.6593 1.3331 13.3918 C 1.5206 12.3867 1.8969 11.7256 2.2607 11.0863 C 2.7026 10.3098 3.1595 9.507 3.1595 8.2694 L 3.1595 7.2733 C 3.1595 5.1792 4.8539 3.4525 6.9374 3.4221 L 7.0624 3.4221 C 9.1389 3.4524 10.8276 5.1791 10.8276 7.2733 L 10.8276 8.2694 C 10.8276 9.3226 11.1706 10.0475 11.4921 10.6442 C 11.6118 10.8663 11.8392 10.9923 12.074 10.9923 C 12.1803 10.9923 12.2881 10.9664 12.3881 10.912 C 12.7092 10.7375 12.8286 10.3345 12.6548 10.012 C 12.3349 9.4183 12.1497 8.9427 12.1497 8.2694 L 12.1497 7.2733 C 12.1497 5.9066 11.6228 4.6158 10.6659 3.6387 C 9.8459 2.8013 8.7929 2.2787 7.6546 2.1344 L 7.6546 1.1639 C 7.6546 0.7972 7.3586 0.4999 6.9936 0.4999 C 6.6285 0.4999 6.3325 0.7972 6.3325 1.1639 L 6.3325 2.1364 C 3.8054 2.4658 1.8374 4.6561 1.8374 7.2733 L 1.8374 8.2694 C 1.8374 9.1542 1.5177 9.7159 1.1129 10.4271 C 0.7142 11.1279 0.2622 11.9221 0.0336 13.1473 C -0.0754 13.7322 0.0789 14.3302 0.4571 14.788 C 0.835 15.2454 1.3912 15.5077 1.9827 15.5077 L 5.0232 15.5077 C 5.0232 16.6062 5.9128 17.4998 7.0063 17.4998 C 8.0999 17.4998 8.9895 16.6062 8.9895 15.5077 L 12.0172 15.5077 C 12.6088 15.5077 13.1649 15.2454 13.5428 14.788 C 13.921 14.3302 14.0754 13.7322 13.9663 13.1473 ZM 7.0063 16.1716 C 6.6418 16.1716 6.3453 15.8738 6.3453 15.5077 L 7.6674 15.5077 C 7.6674 15.8738 7.3709 16.1716 7.0063 16.1716 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>{{ __('video::videos.notify_customer')}}</span>
                                    <div class="right-side">
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="selectedVideo.is_notify">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <p>{{ __('video::videos.notify_customer_message')}}</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                @include('geofencing::admin.videos.allowed_countries')
            </form>

            <!-- Language Form -->
            <form id="languageForm" style="display: none" name="languageForm" method="POST" data-base-validator
                data-ng-submit="vgridCtrl.saveLanguage($event, selectedVideo.id)">
                <div class="division flexbox">
                    <div class="one-set width-50">
                        <div class="form-group">
                            <label>
                                {{ __('video::videos.title') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <input type="text" disabled="disabled" name="title" class="form-control"
                                    placeholder=" {{ __('video::videos.enter_title') }}"
                                    data-ng-model="selectedVideo.title">
                            </div>
                        </div>
                        <div class="form-group" ng-if="!livePage">
                            <label>
                                Title Two
                            </label>
                            <div class="form-input">
                                <input type="text" disabled="disabled" name="title_two" class="form-control"
                                    placeholder="Enter Title Two"
                                    data-ng-model="selectedVideo.title_two">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('video::videos.description') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <textarea rows="7" disabled="disabled" name="description" class="form-control"
                                    data-ng-model="selectedVideo.description"
                                    placeholder="{{ __('video::videos.description_message') }}"
                                    value="{{old('content')}}"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('video::videos.presenter') }}</label>
                            <div class="form-input">
                                <input data-validation-name="Cast" disabled="disabled" type="text" name="presenter"
                                    class="form-control" placeholder="{{ __('video::videos.principal_performer') }}"
                                    data-ng-model="selectedVideo.presenter">
                            </div>
                            <p class="error-msg" data-ng-show="errors.presenter.has">@{{errors.presenter.message}}</p>
                        </div>
                    </div>
                    <div class="one-set width-50 new-arrow">
                        <div class="form-group">
                            <label>
                                {{ __('video::videos.title') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <input type="text" name="title" class="form-control"
                                    placeholder=" {{ __('video::videos.enter_title') }}"
                                    data-ng-model="selectedLanguageVideo.title">
                            </div>
                            <p class="error-msg" data-ng-show="errors.title.has">@{{errors.title.message
                                }}</p>
                        </div>
                        <div class="form-group" ng-if="!livePage">
                            <label>
                                Title Two
                            </label>
                            <div class="form-input">
                                <input type="text" name="title_two" class="form-control"
                                    placeholder="Enter Title Two"
                                    data-ng-model="selectedLanguageVideo.title_two">
                            </div>
                            <p class="error-msg" data-ng-show="errors.title_two.has">@{{errors.title.message
                                }}</p>
                        </div>
                        <div class="form-group">
                            <label>
                                {{ __('video::videos.description') }}
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <textarea rows="7" name="description" class="form-control"
                                    data-ng-model="selectedLanguageVideo.description"
                                    placeholder=" {{ __('video::videos.description_message') }}"
                                    value="{{old('content')}}"></textarea>
                            </div>
                            <p class="error-msg" data-ng-show="errors.description.has">@{{errors.description.message
                                }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('video::videos.presenter') }}</label>
                            <div class="form-input">
                                <input data-validation-name="Cast" type="text" name="presenter" class="form-control"
                                    placeholder="{{ __('video::videos.principal_performer') }}"
                                    data-ng-model="selectedLanguageVideo.presenter">
                            </div>
                            <p class="error-msg" data-ng-show="errors.presenter.has">@{{errors.presenter.message}}</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="bottom-button text-right ">
            <a ng-if="selectedVideo.id" class="delete-button" href="javascript:void(0)" data-toggle="modal"
                data-target="#videoDeleteModal"
                data-ng-click="vgridCtrl.deleteSingleRecordVideos(selectedVideo.id, selectedVideo.title)">
                <svg viewBox="0 0 14 17" version="1.1" x="0px" y="0px" width="14px" height="17px">
                    <g>
                        <path
                            d="M 12.9751 3.1697 L 1.0323 3.1697 C 0.7284 3.1697 0.4821 2.9233 0.4821 2.6193 L 0.4821 1.574 C 0.4821 1.2699 0.7284 1.0238 1.0323 1.0238 L 4.8785 1.0238 C 4.9061 0.7457 5.1406 0.019 5.426 0.019 L 8.5814 0.019 C 8.8668 0.019 9.1013 0.7457 9.1289 1.0238 L 12.9751 1.0238 C 13.2791 1.0238 13.5254 1.2699 13.5255 1.5741 L 13.5255 2.6193 C 13.5255 2.9231 13.2791 3.1697 12.9751 3.1697 ZM 12.3715 15.5684 C 12.3715 15.8722 12.1252 16.1185 11.8212 16.1185 L 2.1863 16.1185 C 1.8822 16.1185 1.6359 15.8722 1.6359 15.5684 L 1.6359 4.2266 L 12.3715 4.2266 L 12.3715 15.5684 ZM 5.6652 6.7257 C 5.6652 6.3907 5.3936 6.1191 5.0585 6.1191 C 4.7233 6.1191 4.4518 6.3907 4.4518 6.7257 L 4.4518 12.7393 C 4.4518 13.0741 4.7233 13.3459 5.0585 13.3459 C 5.3936 13.3459 5.6652 13.0741 5.6652 12.7393 L 5.6652 6.7257 ZM 9.5558 6.7257 C 9.5558 6.3907 9.2839 6.1191 8.949 6.1191 C 8.6139 6.1191 8.3422 6.3907 8.3422 6.7257 L 8.3422 12.7393 C 8.3422 13.0741 8.6138 13.3459 8.949 13.3459 C 9.2841 13.3459 9.5558 13.0741 9.5558 12.7393 L 9.5558 6.7257 Z"
                            fill="#fc4e4e" />
                    </g>
                </svg>
            </a>
            <a class="save"  ng-if="!livePage && !selectedVideo.is_live " href="{{ url('admin/videos') }}">
                {{ __('video::videos.back') }}
            </a>
            <a class="save" ng-if="livePage || selectedVideo.is_live" href="{{ url('admin/liveevents') }}">
                {{ __('video::videos.back') }}
            </a>
            <button ng-if="!livePage" id="videoEditFormSubmit"
                data-ng-click="vgridCtrl.saveVideoEdit($event, selectedVideo.id)" class="publish-now">
                {{ __('video::videos.save') }}
            </button>
            <button ng-if="livePage && editPage" id="videoEditFormSubmit"
            data-ng-click="vgridCtrl.saveVideoEdit($event, selectedVideo.id)" class="publish-now">
            {{ __('video::videos.publish_now') }}
           </button>
            <button ng-if="livePage && !editPage" id="videoEditFormSubmit"
                data-ng-click="vgridCtrl.saveLiveVideo($event, selectedVideo.id)" class="publish-now">
                {{ __('video::videos.publish_now') }}
            </button>

            <button id="videoLanguageEditFormSubmit" style="display: none"
                data-ng-click="vgridCtrl.saveLanguage($event, selectedVideo.id)" class="publish-now">
                {{ __('video::videos.save') }}
            </button>
        </div>
    </div>

</div>
</div>

<div class="custom-modal modal fade" id="subtitle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="custom-modal-dialog" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">

                {{ __('video::videos.upload_subtitle_files') }}
            </div>
            <div class="custom-modal-body">
                <div class="form-group" data-ng-class="{'has-error': errors.language.has}">
                    <label>

                        {{ __('video::videos.subtitle_name') }}
                    </label>
                    <div class="form-input">
                        <input type="text" class="form-control" name="subtitle_name"
                            placeholder="{{ __('video::videos.subtitle_name_placeholder') }}"
                            data-ng-model="subTitles.language">
                    </div>
                    <p class="error-msg" data-ng-show="errors.language.has">{{
                            __('video::videos.subtitle_name_required') }}</p>
                </div>

                <div class="subtitle-browse" data-ng-class="{'has-error': errors.url.has}">
                    <svg viewBox="0 0 24 24" version="1.1" x="0px" data-ng-model="subTitles.name" y="0px" width="24px"
                        height="24px">
                        <g>
                            <path opacity="0.8"
                                d="M 19.6916 23.7321 L 4.7493 23.7321 C 2.5909 23.7321 0.8347 21.9758 0.8347 19.8177 L 0.8347 4.875 C 0.8347 2.7162 2.5909 0.9601 4.7493 0.9601 L 19.6916 0.9601 C 21.8503 0.9601 23.6066 2.7162 23.6066 4.875 L 23.6066 19.8177 C 23.6066 21.9758 21.8503 23.7321 19.6916 23.7321 ZM 21.4876 4.875 C 21.4876 3.8849 20.6821 3.0789 19.6916 3.0789 L 4.7493 3.0789 C 3.7592 3.0789 2.9537 3.8849 2.9537 4.875 L 2.9537 19.8173 C 2.9537 20.8076 3.7592 21.6128 4.7493 21.6128 L 19.6916 21.6128 C 20.6821 21.6128 21.4876 20.8076 21.4876 19.8173 L 21.4876 4.875 ZM 15.5904 13.8693 L 13.2602 11.5391 L 13.2602 17.1868 L 11.1417 17.1868 L 11.1417 11.5832 L 8.8557 13.8693 L 7.3577 12.3707 L 10.7247 9.0038 L 10.724 9.0031 L 12.2221 7.5049 L 12.2228 7.5054 L 12.2238 7.5049 L 13.7219 9.0031 L 13.7212 9.0038 L 17.0884 12.3707 L 15.5904 13.8693 Z"
                                fill="#4b4b4b" />
                        </g>
                    </svg>
                    <h3>{{ __('video::videos.subtitle_drag_message') }}</h3>
                    <span>{{ __('video::videos.subtitle_support_format') }}</span>

                    <input type="file" id="subtitle_file" name="subtitle_file" data-ng-model="subTitles.url"
                        ng-files="getTheFiles($files,selectedVideo.id)">
                    <p class="error-msg" data-ng-show="errors.url.has"> {{ __('video::videos.file_required') }}</p>
                    <p class="error-msg" data-ng-show="subTitles.url == 'error' && !errors.url.has">
                        {{ __('video::videos.file_not_supported') }}
                    </p>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <button type="button" class="popup-button grey-color" data-dismiss="modal">{{
                        __('video::videos.cancel') }}</button>
                <button type="button" class="popup-button blue-color" id="submit_subtitle"
                    ng-click="subTitleSubmit(selectedVideo.id)">{{
                        __('video::videos.add') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- video Audio upload Modal -->
<div class="custom-modal modal fade" id="audios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="custom-modal-dialog" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">

                {{ __('video::videos.upload_audio_file') }}
            </div>
            <div class="custom-modal-body">
                <div class="form-group" data-ng-class="{'has-error': errors.audio_name.has}">
                    <label>

                        {{ __('video::videos.audio_name') }}
                    </label>
                    <div class="form-input">
                        <input type="text" class="form-control" name="audio_name"
                            placeholder="{{ __('video::videos.audio_name_placeholder') }}"
                            data-ng-model="audios.audio_name">
                    </div>
                    <p class="error-msg" data-ng-show="errors.audio_name.has">{{
                            __('video::videos.audio_name_required') }}</p>
                </div>
                <div class="upload-info" data-ng-if="isAudioFileUploaded">
                    <h3 class="audio-upload_status error-msg" data-ng-if="audioUploadStatus.error">{{ __('video::videos.video_upload_status.error') }}: @{{audioUploadErrMsg}}</h3>
                    <h3 class="audio-upload_status" data-ng-if="audioUploadStatus.isAudioAdded">{{ __('video::videos.video_upload_status.added') }}...</h3>
                    <h3 class="audio-upload_status" data-ng-if="audioUploadStatus.isAudioUploading">{{ __('video::videos.video_upload_status.uploading') }}...</h3>
                    <h3 class="audio-upload_status" data-ng-if="audioUploadStatus.isAudioUploaded">{{ __('video::videos.video_upload_status.uploaded') }}</h3>
                    <span id="uploaded-audio-file-name">@{{audioFilename}}</span>
                </div>
                <div class="subtitle-browse" data-ng-class="{'has-error': errors.audio_file.has}" data-ng-if="!isAudioFileUploaded">
                    <svg viewBox="0 0 24 24" version="1.1" x="0px" data-ng-model="subTitles.name" y="0px" width="24px"
                        height="24px">
                        <g>
                            <path opacity="0.8"
                                d="M 19.6916 23.7321 L 4.7493 23.7321 C 2.5909 23.7321 0.8347 21.9758 0.8347 19.8177 L 0.8347 4.875 C 0.8347 2.7162 2.5909 0.9601 4.7493 0.9601 L 19.6916 0.9601 C 21.8503 0.9601 23.6066 2.7162 23.6066 4.875 L 23.6066 19.8177 C 23.6066 21.9758 21.8503 23.7321 19.6916 23.7321 ZM 21.4876 4.875 C 21.4876 3.8849 20.6821 3.0789 19.6916 3.0789 L 4.7493 3.0789 C 3.7592 3.0789 2.9537 3.8849 2.9537 4.875 L 2.9537 19.8173 C 2.9537 20.8076 3.7592 21.6128 4.7493 21.6128 L 19.6916 21.6128 C 20.6821 21.6128 21.4876 20.8076 21.4876 19.8173 L 21.4876 4.875 ZM 15.5904 13.8693 L 13.2602 11.5391 L 13.2602 17.1868 L 11.1417 17.1868 L 11.1417 11.5832 L 8.8557 13.8693 L 7.3577 12.3707 L 10.7247 9.0038 L 10.724 9.0031 L 12.2221 7.5049 L 12.2228 7.5054 L 12.2238 7.5049 L 13.7219 9.0031 L 13.7212 9.0038 L 17.0884 12.3707 L 15.5904 13.8693 Z"
                                fill="#4b4b4b" />
                        </g>
                    </svg>
                    <h3>{{ __('video::videos.audio_drag_message') }}</h3>
                    <span>{{ __('video::videos.audio_support_format') }}</span>
                    <input type="file"  id="audio_file" name="audio_file" data-ng-model="audios.audio_file"
                        ng-files="validateAudioFile($files,selectedVideo.id)">
                    <p class="error-msg" data-ng-show="errors.audio_file.has"> {{ __('video::videos.file_required') }}</p>
                    <p class="error-msg" data-ng-show="audios.audio_file == 'error' && !errors.audio_file.has">
                        {{ __('video::videos.file_not_supported') }}
                    </p>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <button type="button" class="popup-button grey-color" data-dismiss="modal">{{
                        __('video::videos.cancel') }}</button>
                <button data-ng-if="audioUploadStatus.isAudioUploading || audioUploadStatus.isAudioUploaded" type="button" class="popup-button blue-color" id="submit_subtitle"
                    >{{
                        __('video::videos.add') }}</button>
                <button data-ng-if="!audioUploadStatus.isAudioUploading && !audioUploadStatus.isAudioUploaded" type="button" class="popup-button blue-color" id="submit_subtitle"
                    ng-click="addAudio(selectedVideo.id)">{{
                        __('video::videos.add') }}</button>
            </div>
        </div>
    </div>
</div>