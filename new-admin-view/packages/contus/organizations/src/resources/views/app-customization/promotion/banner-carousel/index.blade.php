<style>
    /* Arrow icon rotation */
    #accordian-content-set .arrow-icon {
        margin-right: 12px;
        font-size: 16px;
        /* transition: transform 0.3s ease; */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #accordian-content-set .arrow-icon {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        #accordian-content-set .arrow-icon {
            margin: 0 0 6px 0;
        }
    }

    /* Flex layout for panel heading */
    .panel-heading.d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .panel-heading.d-flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .panel-heading .heading-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    .chip {
        display: inline-block;
        padding: 6px 12px;
        margin: 4px;
        background-color: #f1f1f1;
        border-radius: 25px;
        font-size: 14px;
        color: #333;
    }

    .chip .close {
        font-size: 16px;
        margin-left: 8px;
        color: #555;
        opacity: 0.6;
    }
</style>


<style>
    #banner-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .upload-cover-thumbnail {
        /* border: 1px solid #ddd; */
        /* border-radius: 8px; */
        /* padding: 8px; */
        /* margin: 10px; */
        /* background: #fff; */
        /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
        /* max-width: 250px; */
        /* flex: 1 1 220px; */
        /* position: relative; */
    }

    .upload-cover-thumbnail img {
        width: 100%;
        border-radius: 6px;
        height: auto;
    }

    .fileuploadbox {
        /* text-align: center;
        padding: 10px;
        border: 2px dashed #aaa;
        border-radius: 6px;
        cursor: pointer; */
    }

    .fileuploadbox:hover {
        /* background: #f9f9f9; */
    }

    .add-banner {
        border: 2px dashed #aaa;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        color: #666;
        margin: 10px;
        cursor: pointer;
        flex: 1 1 220px;
        max-width: 250px;
    }

    .add-banner:hover {
        background: #f9f9f9;
    }

    /* Switch style */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
        margin-right: 6px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    .banner-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .banner-actions .status {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: #333;
    }

    .status {
        font-size: 12px;
        background: #e6f8eb;
        color: #2e7d32;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: bold;
    }

    .danger {
        font-size: 12px;
        background: #ecd4d4ff;
        color: #d11717ff;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

@extends('base::layouts.default')

@section('stylesheet')
    <link href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/cropper.css')}}" />
    <link href="{{asset('adminview/assets/css/banner-default.css')}}" rel="stylesheet">
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="CustomizationController as ctzCtrl">
        <div class="contentpanel" id="dashboard-page">
            <div class="form-page">
                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4>{{ __('organizations::index.organization') }}</h4>
                </div>

                @include('base::layouts.subnav')
                <hr>
                @include('organizations::app-customization.common.MainSubNav')
                <hr>
                @include('organizations::app-customization.common.SubNav')
                <hr>

                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4>
                        Banner Carousel
                    </h4>
                </div>

                <div class="contentpanel product order_list">
                    @include('base::partials.errors')
                    <div class="response-msg"></div>
                    <div data-grid-view data-rows-per-page="10"
                        data-template-route="admin/org/app-customization/promotion/banner_carousels"
                        data-route-name="organization/monetizationplanss" data-count="false">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- open image mode -->
    <div class="custom-modal modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="static" data-keyboard="false">
        <div class="custom-modal-dialog img-cropper" role="document">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    {{ __('video::videos.crop_image') }}
                </div>
                <div class="custom-modal-body">
                    <div class="loader-container">
                        <img src="{{asset('adminview/assets/images/loader.gif')}}">
                    </div>
                    <p class="error_msg"></p>
                    <div class="crop-body">
                        <div class="img-container">
                            <img id="image" src="" alt="Picture">
                        </div>
                        <div class="img-preview"></div>
                    </div>
                </div>
                <div class="custom-modal-footer text-right">
                    <button type="button" class="popup-button grey-color"
                        data-dismiss="modal">{{ __('video::videos.cancel') }}</button>
                    <button type="button" class="popup-button blue-color"
                        id="submit-image">{{ __('video::videos.submit') }}</button>
                </div>
            </div>
        </div>
    </div>




@endsection



<script>
    // Simple remove functionality
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("close")) {
            e.target.parentElement.remove();
        }
    });
</script>

@section('scripts')
    <script src="{{asset('adminview/assets/js/cropper.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
    <script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{asset('adminview/assets/js/fine-uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/Uploader.js')}}"></script>
    <script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
    <script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
    <script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
    <script src="{{asset('adminview/assets/js/organization/app-customization/customization.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
    <script src="{{asset('adminview/assets/js/grid.js')}}"></script>
    <script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection