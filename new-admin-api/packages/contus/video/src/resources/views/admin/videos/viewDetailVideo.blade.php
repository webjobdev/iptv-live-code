@extends('base::layouts.default') @section('stylesheet')
<link href="//vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
{{-- <link href="{{$getBaseAssetsUrl('player/player.css')}}" rel="stylesheet"> --}}
<link href="{{$getBaseAssetsUrl('player/play-js.css')}}" rel="stylesheet">

 <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-datetimepicker.min.css')}}"/>
 <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/select2.min.css')}}"/>


 @endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div class="product order_list" data-ng-controller="ViewVideoDetailsController as vVideoDetailsCtrl" data-ng-init=vVideoDetailsCtrl.fetchData('{{$id}}') id="video-detail-start">
<span ng-if="!video.is_live">
    @include('video::admin.common.subMenu', ['template' => 'video_detail', 'id' => $id, 'control' =>
    'vVideoDetailsCtrl'])
</span>
<span ng-if="video.is_live">
    @include('video::admin.common.subMenu', ['template' => 'live_video_detail', 'id' => $id, 'control' =>
    'vVideoDetailsCtrl'])
</span>
    @include('video::admin.common.popup', ['control' => 'vVideoDetailsCtrl'])
    @include('base::partials.errors')

    <div  class="loader-ring" >
            <div class="loader-ring-light"></div>
            <div class="loader-ring-track"></div>
    </div>
    <div class="video-detail main-container" data-ng-if="!vVideoDetailsCtrl.notFoundFlag">
        <ul class="flexbox custom-tabs">
            <li class="tab-link " ng-class="{active: overview }" ng-click="tabChange('overview')">
                <a href="" class="flexbox align-items-center">
                    <svg viewBox="0 0 13 16" version="1.1" x="0px" y="0px" width="13px" height="16px">
                        <g>
                            <path d="M 12.7249 15.2275 C 12.5428 15.4097 12.3215 15.5009 12.0613 15.5009 L 1.4389 15.5009 C 1.1786 15.5009 0.9572 15.4097 0.775 15.2275 C 0.5927 15.0452 0.5016 14.8238 0.5016 14.5634 L 0.5016 1.4364 C 0.5016 1.176 0.5927 0.9546 0.775 0.7723 C 0.9572 0.59 1.1786 0.4989 1.4389 0.4989 L 7.6875 0.4989 C 7.9478 0.4989 8.2343 0.564 8.5466 0.6941 C 8.8589 0.8245 9.1062 0.9808 9.2884 1.1631 L 12.3346 4.2103 C 12.5168 4.3927 12.6729 4.64 12.8031 4.9527 C 12.9332 5.2651 12.9983 5.5516 12.9983 5.8121 L 12.9983 14.5634 C 12.9983 14.8238 12.9071 15.0452 12.7249 15.2275 ZM 11.456 5.0991 L 8.3999 2.042 C 8.3219 1.964 8.1884 1.8922 7.9998 1.8271 L 7.9998 5.4996 L 11.6706 5.4996 C 11.6054 5.3107 11.5339 5.1773 11.456 5.0991 ZM 11.7487 6.7498 L 7.6873 6.7498 C 7.4268 6.7498 7.2055 6.6586 7.0233 6.4763 C 6.841 6.2941 6.75 6.0727 6.75 5.8121 L 6.75 1.7492 L 1.7513 1.7492 L 1.7513 14.2508 L 11.7487 14.2508 L 11.7487 6.7498 ZM 3.3134 7.9999 L 10.1867 7.9999 C 10.2777 7.9999 10.3526 8.0291 10.4111 8.0877 C 10.4696 8.1465 10.499 8.2215 10.499 8.3124 L 10.499 8.9375 C 10.499 9.0288 10.4696 9.1036 10.4111 9.1621 C 10.3526 9.2207 10.2777 9.2499 10.1867 9.2499 L 3.3134 9.2499 C 3.2223 9.2499 3.1474 9.2207 3.0888 9.1621 C 3.0302 9.1036 3.001 9.0288 3.001 8.9375 L 3.001 8.3124 C 3.001 8.2211 3.0304 8.1462 3.0888 8.0877 C 3.1474 8.0291 3.2223 7.9999 3.3134 7.9999 ZM 3.3134 10.5003 L 10.1867 10.5003 C 10.2777 10.5003 10.3526 10.5296 10.4111 10.5879 C 10.4696 10.6466 10.499 10.7216 10.499 10.8126 L 10.499 11.4378 C 10.499 11.5291 10.4696 11.604 10.4111 11.6625 C 10.3526 11.721 10.2777 11.7502 10.1867 11.7502 L 3.3134 11.7502 C 3.2223 11.7502 3.1474 11.721 3.0888 11.6625 C 3.0302 11.604 3.001 11.5291 3.001 11.4378 L 3.001 10.8126 C 3.001 10.7216 3.0304 10.6466 3.0888 10.5879 C 3.1474 10.5296 3.2223 10.5003 3.3134 10.5003 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.overview') }}</span>
                </a>
            </li>
            <li class="tab-link"  ng-class="{active: metrics }" id="metrics" ng-click="tabChange('metrics')">
                <a href="" class="flexbox align-items-center">
                    <svg viewBox="0 0 15 15" version="1.1" x="0px" y="0px" width="15px" height="15px">
                        <g>
                            <path d="M 14.0449 14.9999 L 10.9299 14.9999 C 10.6778 14.9999 10.475 14.791 10.475 14.5313 L 10.5062 7.4757 C 10.5062 7.2088 10.7073 6.9943 10.9575 6.9943 L 14.0484 6.9943 C 14.2985 6.9943 14.5027 7.2088 14.4999 7.4757 L 14.4999 14.5313 C 14.4999 14.791 14.297 14.9999 14.0449 14.9999 ZM 13.4104 7.9999 L 11.4949 7.9933 L 11.4947 14.0002 L 13.4964 14.0002 L 13.4104 7.9999 ZM 9.0575 14.9999 L 5.9424 14.9999 C 5.6903 14.9999 5.4874 14.791 5.4874 14.5313 L 5.4874 1.4684 C 5.4874 1.2088 5.6903 0.9999 5.9424 0.9999 L 9.0575 0.9999 C 9.3096 0.9999 9.5153 1.2088 9.5123 1.4684 L 9.5123 14.5313 C 9.5123 14.791 9.3096 14.9999 9.0575 14.9999 ZM 8.4935 1.9995 L 6.506 1.9995 L 6.506 14.0002 L 8.4935 14.0002 L 8.4935 1.9995 ZM 4.0511 14.9999 L 0.9525 14.9999 C 0.7017 14.9999 0.5 14.785 0.5 14.5181 L 0.5 4.9873 C 0.5 4.7204 0.7017 4.5055 0.9525 4.5055 L 4.0511 4.5055 C 4.3019 4.5055 4.5066 4.7204 4.5037 4.9873 L 4.5037 14.5181 C 4.5037 14.785 4.3019 14.9999 4.0511 14.9999 ZM 3.5013 5.5058 L 1.5021 5.5058 L 1.5021 13.9997 L 3.5013 13.9997 L 3.5013 5.5058 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.metrics') }}</span>
                </a>
            </li>
            <li class="tab-link" id="metadata" ng-class="{active: metadata }" ng-click="tabChange('metadata')">
                <a href="" class="flexbox align-items-center">
                    <svg viewBox="0 0 511 511.99982" width="16px" height="16px">
                        <path d="m482.921875 136.523438 23.699219-23.699219c12.554687-12.554688 3.664062-34.140625-14.140625-34.140625h-58.664063v-58.664063c0-17.765625-21.558594-26.722656-34.140625-14.140625l-23.699219 23.699219c-36.675781-19.375-77.660156-29.5585938-119.488281-29.5585938-141.476562 0-255.988281 114.4921878-255.988281 255.9921878 0 141.476562 114.492188 255.988281 255.988281 255.988281 141.480469 0 255.992188-114.492188 255.992188-255.988281 0-41.828125-10.183594-82.8125-29.558594-119.488281zm-69.105469-17.839844h30.382813l-38.667969 38.664062h-50.378906v-50.382812l38.664062-38.664063v30.382813c0 11.042968 8.953125 20 20 20zm-77.332031 137.328125c0 44.109375-35.886719 79.996093-79.996094 79.996093s-79.996093-35.886718-79.996093-79.996093c0-44.113281 35.886718-80 79.996093-80 14.816407 0 28.691407 4.066406 40.605469 11.113281l-54.746094 54.742188c-7.808594 7.8125-7.808594 20.476562 0 28.285156s20.472656 7.808594 28.28125 0l54.746094-54.746094c7.050781 11.914062 11.109375 25.789062 11.109375 40.605469zm-79.996094 215.988281c-119.097656 0-215.988281-96.890625-215.988281-215.988281 0-119.097657 96.890625-215.992188 215.988281-215.992188 31.101563 0 61.644531 6.660157 89.625 19.417969l-25.101562 25.105469c-3.75 3.75-5.859375 8.835937-5.859375 14.140625v52.683594c-17.355469-9.769532-37.367188-15.351563-58.664063-15.351563-66.164062 0-119.996093 53.828125-119.996093 119.996094 0 66.164062 53.832031 119.992187 119.996093 119.992187 66.164063 0 119.996094-53.828125 119.996094-119.992187 0-21.296875-5.582031-41.308594-15.351563-58.664063h52.683594c5.304688 0 10.390625-2.109375 14.140625-5.859375l25.101563-25.101562c12.761718 27.976562 19.421875 58.519531 19.421875 89.621093 0 119.101563-96.894531 215.992188-215.992188 215.992188zm0 0" />
                    </svg>
                    <span>{{ trans('base::general.metadata') }}</span>
                </a>
            </li>
            <li class="tab-link" id="cover_thumbnail" ng-class="{active: cover_thumbnail }" ng-click="tabChange('cover_thumbnail')">
                <a href="" class="flexbox align-items-center">
                    <svg viewBox="0 0 16 16" version="1.1" x="0px" y="0px" width="16px" height="16px">
                        <g>
                            <path d="M 15.3542 15.5 L 1.1458 15.5 C 0.7885 15.5 0.5 15.2051 0.5 14.8409 L 0.5 1.659 C 0.5 1.2954 0.7885 1 1.1458 1 L 15.3542 1 C 15.7111 1 16 1.2954 16 1.659 L 16 14.8409 C 16 15.2051 15.7111 15.5 15.3542 15.5 ZM 2.4374 14.1818 L 8.1667 14.1818 L 5.5698 11.5313 C 5.3172 11.2738 4.9091 11.2738 4.6566 11.5313 L 2.1404 14.0987 C 2.2309 14.147 2.3284 14.1818 2.4374 14.1818 ZM 14.7083 2.9773 C 14.7083 2.6135 14.4188 2.3181 14.0624 2.3181 L 2.4374 2.3181 C 2.0811 2.3181 1.7916 2.6135 1.7916 2.9773 L 1.7916 12.5907 L 3.7433 10.5993 C 4.4989 9.8281 5.7272 9.8295 6.483 10.5993 L 6.8704 10.9945 L 10.2846 7.511 C 11.0401 6.74 12.2687 6.7406 13.0244 7.5104 L 14.7083 9.2287 L 14.7083 2.9773 ZM 14.7083 11.0923 L 12.111 8.4431 C 11.8582 8.1859 11.4508 8.1859 11.1978 8.4431 L 7.7829 11.9265 L 9.9932 14.1818 L 14.0624 14.1818 C 14.4188 14.1818 14.7083 13.8857 14.7083 13.5227 L 14.7083 11.0923 ZM 5.6666 8.909 C 4.2419 8.909 3.0833 7.7267 3.0833 6.2727 C 3.0833 4.8193 4.2419 3.6363 5.6666 3.6363 C 7.0914 3.6363 8.2499 4.8193 8.2499 6.2727 C 8.2499 7.7267 7.0914 8.909 5.6666 8.909 ZM 5.6666 4.9545 C 4.9543 4.9545 4.3749 5.545 4.3749 6.2727 C 4.3749 7.0003 4.9543 7.5909 5.6666 7.5909 C 6.379 7.5909 6.9583 7.0003 6.9583 6.2727 C 6.9583 5.545 6.379 4.9545 5.6666 4.9545 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.cover_thumbnail') }}</span>
                </a>
            </li>
        </ul>

        <div class="video-detail-overview tab-content active">
            <div class="player-counts flexbox">
                <div class="video-detail-content">
                    <iframe width="560" height="315" ng-if="video.is_live" ng-src="@{{ vVideoDetailsCtrl.sourceurl }}" frameborder="0" allowfullscreen></iframe>
                    <div class="video-player"  ng-if="!video.is_live">
                        <video id="example-video" init-player video="video" />
                    </div>
                    <h2 class="flexbox align-items-start video-title">
                        <span class="video-title">@{{ video.title }}</span>

                        <span ng-if="video.is_premium==1" class="premium">{{ __('video::videos.premium') }}</span>
                    </h2>

                    <ul class="video-info flexbox flex-wrap">
                        <li class="flexbox flex-wrap align-items-center">
                            <svg version="1.1" x="0px" y="0px" width="13px" height="13px" viewBox="0 0 408 408">
                                <g>
                                    <g>
                                        <path d="M204,204c56.1,0,102-45.9,102-102S260.1,0,204,0c-56.1,0-102,45.9-102,102S147.9,204,204,204z M204,255
                                            C135.15,255,0,288.15,0,357v51h408v-51C408,288.15,272.85,255,204,255z" />
                                    </g>
                                </g>
                            </svg>

                            <span>@{{ video.user.name ? video.user.name: '-' }}</span>
                        </li>

                        <li class="flexbox flex-wrap align-items-center">
                            <svg version="1.1" x="0px" y="0px" width="13px" height="13px" viewBox="0 0 299.995 299.995">
                                <g>
                                    <g>
                                        <path d="M149.995,0C67.156,0,0,67.158,0,149.995s67.156,150,149.995,150s150-67.163,150-150S232.834,0,149.995,0z
                                            M214.842,178.524H151.25c-0.215,0-0.415-0.052-0.628-0.06c-0.213,0.01-0.412,0.06-0.628,0.06
                                            c-5.729,0-10.374-4.645-10.374-10.374V62.249c0-5.729,4.645-10.374,10.374-10.374s10.374,4.645,10.374,10.374v95.527h54.47
                                            c5.729,0,10.374,4.645,10.374,10.374C225.212,173.879,220.571,178.524,214.842,178.524z" />
                                    </g>
                                </g>
                            </svg>
                            <span>@{{  video.video_duration ? video.video_duration:"-"  }}</span>
                        </li>

                        <li class="flexbox flex-wrap align-items-center">
                            <svg version="1.1" x="0px" y="0px" width="13px" height="13px" viewBox="0 0 488.152 488.152">
                                <g>
                                    <g>
                                        <path d="M177.854,269.311c0-6.115-4.96-11.069-11.08-11.069h-38.665c-6.113,0-11.074,4.954-11.074,11.069v38.66
                                            c0,6.123,4.961,11.079,11.074,11.079h38.665c6.12,0,11.08-4.956,11.08-11.079V269.311L177.854,269.311z" />
                                        <path d="M274.483,269.311c0-6.115-4.961-11.069-11.069-11.069h-38.67c-6.113,0-11.074,4.954-11.074,11.069v38.66
                                            c0,6.123,4.961,11.079,11.074,11.079h38.67c6.108,0,11.069-4.956,11.069-11.079V269.311z" />
                                        <path d="M371.117,269.311c0-6.115-4.961-11.069-11.074-11.069h-38.665c-6.12,0-11.08,4.954-11.08,11.069v38.66
                                            c0,6.123,4.96,11.079,11.08,11.079h38.665c6.113,0,11.074-4.956,11.074-11.079V269.311z" />
                                        <path d="M177.854,365.95c0-6.125-4.96-11.075-11.08-11.075h-38.665c-6.113,0-11.074,4.95-11.074,11.075v38.653
                                            c0,6.119,4.961,11.074,11.074,11.074h38.665c6.12,0,11.08-4.956,11.08-11.074V365.95L177.854,365.95z" />
                                        <path d="M274.483,365.95c0-6.125-4.961-11.075-11.069-11.075h-38.67c-6.113,0-11.074,4.95-11.074,11.075v38.653
                                            c0,6.119,4.961,11.074,11.074,11.074h38.67c6.108,0,11.069-4.956,11.069-11.074V365.95z" />
                                        <path d="M371.117,365.95c0-6.125-4.961-11.075-11.069-11.075h-38.67c-6.12,0-11.08,4.95-11.08,11.075v38.653
                                            c0,6.119,4.96,11.074,11.08,11.074h38.67c6.108,0,11.069-4.956,11.069-11.074V365.95L371.117,365.95z" />
                                        <path d="M440.254,54.354v59.05c0,26.69-21.652,48.198-48.338,48.198h-30.493c-26.688,0-48.627-21.508-48.627-48.198V54.142
                                            h-137.44v59.262c0,26.69-21.938,48.198-48.622,48.198H96.235c-26.685,0-48.336-21.508-48.336-48.198v-59.05
                                            C24.576,55.057,5.411,74.356,5.411,98.077v346.061c0,24.167,19.588,44.015,43.755,44.015h389.82
                                            c24.131,0,43.755-19.889,43.755-44.015V98.077C482.741,74.356,463.577,55.057,440.254,54.354z M426.091,422.588
                                            c0,10.444-8.468,18.917-18.916,18.917H80.144c-10.448,0-18.916-8.473-18.916-18.917V243.835c0-10.448,8.467-18.921,18.916-18.921
                                            h327.03c10.448,0,18.916,8.473,18.916,18.921L426.091,422.588L426.091,422.588z" />
                                        <path d="M96.128,129.945h30.162c9.155,0,16.578-7.412,16.578-16.567V16.573C142.868,7.417,135.445,0,126.29,0H96.128
                                            C86.972,0,79.55,7.417,79.55,16.573v96.805C79.55,122.533,86.972,129.945,96.128,129.945z" />
                                        <path d="M361.035,129.945h30.162c9.149,0,16.572-7.412,16.572-16.567V16.573C407.77,7.417,400.347,0,391.197,0h-30.162
                                            c-9.154,0-16.577,7.417-16.577,16.573v96.805C344.458,122.533,351.881,129.945,361.035,129.945z" />
                                    </g>
                                </g>
                            </svg>

                            <span>@{{ video.formatted_published_date ? video.formatted_published_date : '-' }}</span>
                        </li>

                        <li class="flexbox flex-wrap align-items-center">
                            <span class="active status-dot" ng-if="video.is_active==1">{{ __('video::videos.message.active') }}</span>
                            <span class="inactive status-dot" ng-if="video.is_active==0">{{ __('video::videos.message.inactive') }}</span>
                        </li>
                    </ul>

                    <div class="content">
                        <div class="one-set">
                            <h4 class="heading">{{ __('video::videos.description') }}</h4>
                            <p ng-init="limit = 150; moreShown = false">
                                    @{{  video.description| limitTo: limit}}@{{ video.description.length > limit ? '...' : ''}}
                                    <a class="read-more-trigger" ng-show="video.description.length > limit"
                                      href ng-click="limit=video.description.length; moreShown = true">  {{ __('video::videos.more') }}
                                    </a>
                                    <a class="read-more-trigger" ng-show="moreShown" href ng-click="limit=200; moreShown =    false"> {{ __('video::videos.less') }} </a>
                                </p>

                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.category') }}</h4>
                                <p>@{{ video.categories[0].title ? video.categories[0].title: '-' }}</p>
                            </div>
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.genre') }}</h4>
                                <p>@{{ video.collections[0].name ? video.collections[0].name:'-' }}</p>
                            </div>
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.starring') }}</h4>
                                <p>@{{ video.presenter ? video.presenter: '-' }}</p>
                            </div>
                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.audio_language') }}</h4>
                                <p>@{{ video.audio_language ? video.audio_language: '-' }}</p>
                            </div>
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.subtitle') }}</h4>
                                <p>@{{ video.subtitle ? video.subtitle: '-'}}</p>
                            </div>

                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.size') }}</h4>
                                 <p>@{{video.video_size ? video.video_size: '-'}}</p>
                            </div>
                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set divide-3">
                                <h4 class="heading">{{ __('video::videos.presets') }}</h4>
                                {{-- <p>1080p HD, 720p, 360p</p> --}}
                               <p> @{{ active_presets ? active_presets: '-'}}</p>
                            </div>
                            <div class="one-set divide-2">
                                <h4 class="heading">{{ __('video::videos.direct_link') }}</h4>
                                <p>

                                    <a  data-ng-show="video.job_status=='Complete'&& video.is_active==1 "  target="_blank" href="{{ env('WEB_SITE_URL') }}@{{ 'video/'+video.slug }}">{{ env('WEB_SITE_URL') }}@{{ 'video/'+video.slug }}</a>
                                     <a data-ng-if="video.job_status!='Complete'">{{ env('WEB_SITE_URL') }}@{{ 'video/'+video.slug }}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="comments">

                        <div class="response flexbox flex-wrap" data-ng-if="video.comments.length > '0'">
                            <h5>@{{ video.comments.length }} {{ __('video::videos.response') }}</h5>

                            <div class="comments-tabs">
                                <span ng-class="{active: All }" ng-click="commentActiveTab('desc','All')">{{ __('video::videos.all') }}</span> /
                                <span ng-class="{active: Oldest}" ng-click="commentActiveTab('asc','Oldest')">{{ __('video::videos.oldest') }}</span> /
                                <span ng-class="{active: Newest}" ng-click="commentActiveTab('desc','Newest')">{{ __('video::videos.newest') }}</span>
                            </div>
                        </div>
                        <div class="response flexbox flex-wrap" data-ng-if="video.comments.length == '0'">
                                <h5> {{ __('video::videos.comment') }}</h5>




                        </div>
                        <p  data-ng-if="video.comments.length == '0'" class="center" style="font-size:12px">{{ __('video::videos.no_record_found') }}</p>

                        {{-- <ul class="comments-sections" infinite-scroll='loadMore()' infinite-scroll-distance='0'> --}}
                            <ul class="comments-sections">
                            <li data-ng-repeat="comments in video.comments | filter: mainCommentFilter ">
                                <div class="flexbox" >
                                    <div class="image" style="background-image: url(@{{ comments.customer_details.profile_picture }})">
                                    </div>

                                    <div class="comment-content">
                                        <div class="name-date flexbox align-items-center flex-wrap">
                                            <span class="name">@{{ comments.customer_details.name }}</span>
                                            <span class="date">@{{ comments.created_at }}</span>
                                        </div>

                                        <div class="comment-text">
                                          @{{ comments.comment }}
                                        </div>

                                        <div class="replied-delete flexbox align-items-center flex-wrap">
                                            <h3 ng-if="comments.reply_count >= 1" class="show-replies flexbox align-items-center flex-wrap" data-ng-class="{'close-reply-icon': video.showreplycommentslist ==true}">
                                                <span  ng-click="ShowreplyComments(comments._id)">{{ __('video::videos.view') }}@{{ comments.reply_count }}{{ __('video::videos.replies') }}
                                                <svg viewBox="0 0 8 5" version="1.1" xml:space="preserve" x="0px" y="0px" width="8px" height="6px">
                                                    <g id="Layer%201">
                                                        <path id="Forma%201%20copy%205" d="M 4.13 4.9406 L 7.9991 0.2616 C 8.0293 0.2121 8.0325 0.148 8.0079 0.0949 C 7.9832 0.0417 7.9344 0.0086 7.881 0.0086 L 0.1429 0.0086 C 0.0897 0.0086 0.0406 0.0417 0.016 0.0949 C 0.005 0.1184 -0.0004 0.1443 -0.0004 0.17 C -0.0004 0.2021 0.0082 0.234 0.0249 0.2616 L 3.8939 4.9406 C 3.9208 4.984 3.9648 5.0099 4.0121 5.0099 C 4.0592 5.0099 4.1033 4.9839 4.13 4.9406 Z" fill="#000000"></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            </h3>

                                            <a data-ng-if="checkAccess('videos_all_write') || checkAccess('live_videos_all_write')" href="javascript:void(0)" class="delete-action flexbox align-items-center flex-wrap">
                                                <svg viewBox="0 0 11 12" version="1.1" x="0px" y="0px" width="11px"
                                                    height="12px">
                                                    <g>
                                                        <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" />
                                                    </g>
                                                </svg>

                                                <span data-toggle="modal"
                                                data-target="#commentsdeletemodal" data-ng-click="deleteSingleRecordComments(comments._id)">{{ __('video::videos.delete') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <ul  data-ng-show="video.showreplycommentslist">
                                    <li data-ng-repeat="replycomments in video.replycomments" data-ng-if="replycomments.parent_id==comments._id">
                                        <div class="flexbox">
                                            <div class="image" style="background-image: url(@{{ replycomments.customer_details.profile_picture }})">
                                            </div>

                                            <div class="comment-content">
                                                <div class="name-date flexbox align-items-center flex-wrap">
                                                    <span class="name">@{{ replycomments.customer_details.name }}</span>
                                                    <span class="date">@{{ replycomments.created_at }}</span>
                                                </div>

                                                <div class="comment-text">
                                                    @{{ replycomments.comment  }}
                                                </div>

                                                <div class="replied-delete flexbox align-items-center flex-wrap">
                                                    <a data-ng-if="checkAccess('videos_all_write') || checkAccess('live_videos_all_write')" href="javascript:void(0)" class="delete-action flexbox align-items-center flex-wrap">
                                                        <svg viewBox="0 0 11 12" version="1.1" x="0px" y="0px" width="11px"
                                                            height="12px">
                                                            <g>
                                                                <path d="M 10.4998 3.513 L 9.6099 3.513 L 8.9153 11.6068 C 8.8962 11.8292 8.7144 11.9998 8.4966 11.9998 L 2.4885 11.9998 C 2.2708 11.9998 2.0889 11.8293 2.0701 11.6069 L 1.3752 3.513 L 0.4995 3.513 L 0.4995 2.6513 L 3.4268 2.6513 L 3.4268 1.7179 C 3.4268 1.322 3.741 0.9999 4.1272 0.9999 L 6.8721 0.9999 C 7.2583 0.9999 7.5725 1.322 7.5725 1.7179 L 7.5725 2.6513 L 10.4998 2.6513 L 10.4998 3.513 ZM 6.7322 1.8615 L 4.2668 1.8615 L 4.2668 2.6513 L 6.7322 2.6513 L 6.7322 1.8615 ZM 2.2188 3.513 L 2.873 11.1383 L 8.1121 11.1383 L 8.7661 3.513 L 2.2188 3.513 ZM 6.5222 9.8588 L 6.7043 4.7609 L 7.5442 4.7924 L 7.3621 9.8902 L 6.5222 9.8588 ZM 5.0796 4.7767 L 5.9199 4.7767 L 5.9199 9.8746 L 5.0796 9.8746 L 5.0796 4.7767 ZM 3.4551 4.7923 L 4.2949 4.7608 L 4.4771 9.8586 L 3.6372 9.8902 L 3.4551 4.7923 Z" />
                                                            </g>
                                                        </svg>
                                                        <span data-toggle="modal"
                                                        data-target="#commentsdeletemodal" data-ng-click="deleteSingleRecordComments(replycomments._id)">{{ __('video::videos.delete') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <span data-ng-show="finalReply==false" class="read-more-trigger" data-ng-if="$last" ng-click="loadreplyComments(comments._id)">{{ __('video::videos.load_more') }}</span>
                                    </li>

                                </ul>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="counts">
                    <ul>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg x="0px" y="0px" viewBox="0 0 511.999 511.999" width="20px" height="20px">
                                    <g>
                                        <g>
                                            <path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
                                                c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
                                                C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
                                                c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
                                                C447.361,287.923,358.746,385.406,255.997,385.406z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
                                                s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
                                                s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" />
                                        </g>
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.views')}}</h4>

                                <h5>@{{video.view_count}}</h5>
                            </div>
                        </li>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 17 16" version="1.1" x="0px" y="0px" width="17px" height="16px">
                                    <g>
                                        <path d="M 16.127 5.8019 C 15.6896 5.4253 15.137 5.2177 14.5747 5.2177 L 14.0509 5.2177 L 12.1672 5.2177 L 11.2734 5.2177 L 11.2734 2.8758 C 11.2734 1.7554 10.9419 0.9395 10.2834 0.4518 C 9.2455 -0.3111 7.8375 0.1186 7.775 0.138 C 7.5348 0.2152 7.3713 0.4374 7.3713 0.6884 L 7.3713 3.5131 C 7.3713 4.3725 6.9629 5.1017 6.1508 5.6859 C 5.5356 6.1303 4.9061 6.333 4.8245 6.3572 L 4.7668 6.3716 C 4.5505 6.1254 4.2334 5.9708 3.8778 5.9708 L 1.6864 5.9708 C 1.033 5.9708 0.4995 6.5069 0.4995 7.1636 L 0.4995 14.3871 C 0.4995 15.0438 1.033 15.5798 1.6864 15.5798 L 3.8873 15.5798 C 4.1757 15.5798 4.4448 15.4735 4.6466 15.2997 C 5.0887 15.7343 5.6895 15.9998 6.3383 15.9998 L 8.5247 15.9998 L 8.7505 15.9998 L 13.2245 15.9998 C 13.9164 15.9998 14.5267 15.8309 14.9928 15.517 C 15.5887 15.1115 15.9587 14.4693 16.0645 13.6484 L 16.9535 8.0954 C 17.0928 7.236 16.7756 6.3572 16.127 5.8019 ZM 3.921 14.3871 C 3.921 14.4065 3.9066 14.4209 3.8873 14.4209 L 1.6864 14.4209 C 1.6672 14.4209 1.6528 14.4065 1.6528 14.3871 L 1.6528 7.1636 C 1.6528 7.1442 1.6672 7.1298 1.6864 7.1298 L 3.8873 7.1298 C 3.9066 7.1298 3.921 7.1442 3.921 7.1636 L 3.921 14.3871 ZM 15.8146 7.9168 L 14.9255 13.4793 C 14.9255 13.4842 14.9255 13.4938 14.9208 13.5035 C 14.8823 13.8367 14.7573 14.8458 13.2245 14.8458 L 8.7505 14.8458 L 8.5247 14.8458 L 6.3383 14.8458 C 5.723 14.8458 5.1849 14.3823 5.0887 13.7691 C 5.084 13.745 5.0791 13.7208 5.0743 13.7015 L 5.0743 7.4919 L 5.1079 7.4823 C 5.1176 7.4823 5.1223 7.4774 5.132 7.4774 C 5.1656 7.4677 5.9825 7.236 6.7947 6.6517 C 7.924 5.8454 8.5247 4.7589 8.5247 3.5131 L 8.5247 1.1664 C 8.8418 1.1328 9.2791 1.1471 9.6011 1.3837 C 9.947 1.6397 10.1201 2.1418 10.1201 2.8709 L 10.1201 5.7922 C 10.1201 6.111 10.3795 6.3716 10.6967 6.3716 L 12.1672 6.3716 L 14.0509 6.3716 L 14.5747 6.3716 C 14.8632 6.3716 15.1466 6.4827 15.3773 6.6759 C 15.7233 6.9752 15.8866 7.4484 15.8146 7.9168 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.like')}}</h4>

                                <h5>@{{video.like_count}}</h5>
                            </div>
                        </li>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 17 17" version="1.1" x="0px" y="0px" width="17px" height="17px">
                                    <g>
                                        <path d="M 16.1417 10.6978 C 15.704 11.0744 15.1509 11.2821 14.5881 11.2821 L 14.0638 11.2821 L 12.1783 11.2821 L 11.2836 11.2821 L 11.2836 13.624 C 11.2836 14.7442 10.9517 15.5602 10.2927 16.048 C 9.2538 16.8109 7.8445 16.3811 7.782 16.3618 C 7.5414 16.2845 7.3778 16.0624 7.3778 15.8113 L 7.3778 12.9866 C 7.3778 12.1271 6.969 11.398 6.1561 10.8137 C 5.5404 10.3695 4.9104 10.1667 4.8285 10.1426 L 4.7708 10.1281 C 4.5543 10.3743 4.2369 10.5288 3.881 10.5288 L 1.6876 10.5288 C 1.0334 10.5288 0.4995 9.9928 0.4995 9.3362 L 0.4995 2.1127 C 0.4995 1.4559 1.0334 0.92 1.6876 0.92 L 3.8905 0.92 C 4.1792 0.92 4.4485 1.0261 4.6506 1.2 C 5.0931 0.7655 5.6943 0.4998 6.3438 0.4998 L 8.5323 0.4998 L 8.7583 0.4998 L 13.2366 0.4998 C 13.9292 0.4998 14.5399 0.6688 15.0066 0.9827 C 15.603 1.3884 15.9734 2.0305 16.0792 2.8514 L 16.969 8.4043 C 17.1085 9.2638 16.7911 10.1425 16.1417 10.6978 ZM 3.9243 2.1127 C 3.9243 2.0933 3.9099 2.0789 3.8905 2.0789 L 1.6876 2.0789 C 1.6683 2.0789 1.6539 2.0933 1.6539 2.1127 L 1.6539 9.3362 C 1.6539 9.3555 1.6683 9.3701 1.6876 9.3701 L 3.8905 9.3701 C 3.9099 9.3701 3.9243 9.3555 3.9243 9.3362 L 3.9243 2.1127 ZM 15.8291 8.5829 L 14.9392 3.0204 C 14.9392 3.0155 14.9392 3.0058 14.9344 2.9962 C 14.8959 2.663 14.7709 1.6539 13.2366 1.6539 L 8.7583 1.6539 L 8.5323 1.6539 L 6.3438 1.6539 C 5.728 1.6539 5.1893 2.1174 5.0931 2.7307 C 5.0884 2.7549 5.0835 2.7789 5.0786 2.7982 L 5.0786 9.0078 L 5.1123 9.0175 C 5.1219 9.0175 5.1267 9.0224 5.1364 9.0224 C 5.17 9.032 5.9878 9.2638 6.8007 9.848 C 7.931 10.6544 8.5323 11.7409 8.5323 12.9866 L 8.5323 15.3333 C 8.8497 15.3671 9.2875 15.3526 9.6097 15.116 C 9.9561 14.8602 10.1292 14.358 10.1292 13.6287 L 10.1292 10.7075 C 10.1292 10.3889 10.3889 10.1281 10.7064 10.1281 L 12.1783 10.1281 L 14.0638 10.1281 L 14.5881 10.1281 C 14.8767 10.1281 15.1605 10.0171 15.3914 9.8239 C 15.7378 9.5244 15.9012 9.0513 15.8291 8.5829 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.dislike')}}</h4>

                                <h5>@{{video.dislike_count}}</h5>
                            </div>
                        </li>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 18 17" version="1.1" x="0px" y="0px" width="18px" height="17px">
                                    <g>
                                        <path d="M 14.5195 13.0268 L 7.4882 13.0268 L 4.1223 16.3286 C 4.0082 16.4405 3.8558 16.4999 3.7007 16.4999 C 3.6239 16.4999 3.5464 16.4853 3.4727 16.4554 C 3.25 16.3649 3.1047 16.1516 3.1047 15.9152 L 3.1047 13.0036 C 1.6378 12.8219 0.4999 11.5903 0.4999 10.103 L 0.4999 3.4236 C 0.4999 1.8115 1.837 0.4999 3.4805 0.4999 L 14.5195 0.4999 C 16.163 0.4999 17.5001 1.8115 17.5001 3.4236 L 17.5001 10.103 C 17.5001 11.7152 16.163 13.0268 14.5195 13.0268 ZM 16.3079 3.4237 C 16.3079 2.4563 15.5056 1.6694 14.5195 1.6694 L 3.4805 1.6694 C 2.4944 1.6694 1.6921 2.4563 1.6921 3.4237 L 1.6921 10.103 C 1.6921 11.0704 2.4944 11.8573 3.4805 11.8573 L 3.7008 11.8573 C 4.0299 11.8573 4.2969 12.1191 4.2969 12.442 L 4.2969 14.5034 L 6.8197 12.0286 C 6.9315 11.9189 7.0831 11.8573 7.2413 11.8573 L 14.5195 11.8573 C 15.5056 11.8573 16.3079 11.0704 16.3079 10.103 L 16.3079 3.4237 ZM 13.3873 5.8101 L 4.6127 5.8101 C 4.2834 5.8101 4.0165 5.5483 4.0165 5.2252 C 4.0165 4.9023 4.2834 4.6405 4.6127 4.6405 L 13.3873 4.6405 C 13.7164 4.6405 13.9834 4.9023 13.9834 5.2252 C 13.9834 5.5483 13.7164 5.8101 13.3873 5.8101 ZM 4.6127 7.9152 L 10.5221 7.9152 C 10.8513 7.9152 11.1182 8.1769 11.1182 8.5 C 11.1182 8.8228 10.8513 9.0847 10.5221 9.0847 L 4.6127 9.0847 C 4.2834 9.0847 4.0165 8.8228 4.0165 8.5 C 4.0165 8.1769 4.2834 7.9152 4.6127 7.9152 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.comment')}}</h4>

                                <h5>@{{video.comments.length}}</h5>
                            </div>
                        </li>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 16 15" version="1.1" x="0px" y="0px" width="16px" height="15px">
                                    <g id="Layer%201">
                                        <path d="M 16.0004 5.8129 C 16.0004 5.5797 15.8267 5.4348 15.4785 5.3782 L 10.8024 4.6883 L 8.7064 0.3883 C 8.5886 0.1299 8.4363 0.0008 8.25 0.0008 C 8.0637 0.0008 7.9116 0.1299 7.7936 0.3883 L 5.6975 4.6883 L 1.0211 5.3782 C 0.6735 5.4348 0.4996 5.5797 0.4996 5.8129 C 0.4996 5.9452 0.5773 6.0965 0.7324 6.2665 L 4.1233 9.612 L 3.3223 14.3374 C 3.3098 14.4256 3.3037 14.4887 3.3037 14.5265 C 3.3037 14.6587 3.3362 14.7706 3.4014 14.862 C 3.4666 14.9534 3.5643 14.999 3.6947 14.999 C 3.8066 14.999 3.9308 14.9614 4.0674 14.8856 L 8.2499 12.6552 L 12.4326 14.8856 C 12.5631 14.9612 12.6873 14.999 12.8052 14.999 C 13.0599 14.999 13.1873 14.8417 13.1873 14.5266 C 13.1873 14.4447 13.1841 14.3818 13.1779 14.3374 L 12.3768 9.6123 L 15.7583 6.2668 C 15.9197 6.1028 16.0004 5.9515 16.0004 5.8129 ZM 11.0912 9.1868 L 11.7617 13.1657 L 8.2499 11.2849 L 4.7286 13.1657 L 5.4087 9.1868 L 2.5581 6.3799 L 6.4894 5.7939 L 8.2499 2.1837 L 10.0105 5.7939 L 13.9419 6.3799 L 11.0912 9.1868 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.favourite')}}</h4>

                                <h5>@{{video.favourite_count}}</h5>
                            </div>
                        </li>
                        <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 15 15" version="1.1" x="0px" y="0px" width="15px" height="15px">
                                    <g>
                                        <path d="M 11.5935 8.4528 L 11.5935 1.7079 L 1.2422 1.7079 L 1.2422 14.2918 L 6.0038 14.2918 C 6.3453 14.2918 6.6249 14.5636 6.6249 14.8958 C 6.6249 15.2281 6.3453 15.4999 6.0038 15.4999 L 0.6211 15.4999 C 0.2794 15.4999 0 15.2281 0 14.8958 L 0 1.104 C 0 0.7716 0.2795 0.4998 0.6211 0.4998 L 12.2146 0.4998 C 12.5563 0.4998 12.8357 0.7716 12.8357 1.104 L 12.8357 8.4528 C 12.8357 8.7852 12.5563 9.057 12.2146 9.057 C 11.8729 9.057 11.5935 8.7852 11.5935 8.4528 ZM 14.7921 9.9227 C 14.5333 9.7012 14.14 9.7213 13.9124 9.973 L 10.5067 13.6577 L 8.7986 12.0568 C 8.5502 11.8254 8.1569 11.8354 7.9188 12.0771 C 7.6808 12.3187 7.691 12.7012 7.9395 12.9327 L 10.1133 14.9663 C 10.2272 15.077 10.3824 15.1374 10.548 15.1374 C 10.5583 15.1374 10.5583 15.1374 10.5688 15.1374 C 10.7344 15.1275 10.9 15.0569 11.0035 14.936 L 14.8336 10.7885 C 15.0717 10.5267 15.0509 10.1442 14.7921 9.9227 ZM 9.3162 4.0233 L 3.7264 4.0233 C 3.3849 4.0233 3.1055 4.2951 3.1055 4.6274 C 3.1055 4.9596 3.3849 5.2314 3.7264 5.2314 L 9.3162 5.2314 C 9.6578 5.2314 9.9373 4.9596 9.9373 4.6274 C 9.9373 4.2951 9.6578 4.0233 9.3162 4.0233 ZM 9.9374 7.9496 C 9.9374 7.6173 9.6578 7.3455 9.3163 7.3455 L 3.7264 7.3455 C 3.3849 7.3455 3.1055 7.6173 3.1055 7.9496 C 3.1055 8.2818 3.3849 8.5535 3.7264 8.5535 L 9.3162 8.5535 C 9.6578 8.5535 9.9374 8.2818 9.9374 7.9496 ZM 3.7264 10.567 C 3.3849 10.567 3.1055 10.8388 3.1055 11.171 C 3.1055 11.5032 3.3849 11.775 3.7264 11.775 L 5.3827 11.775 C 5.7242 11.775 6.0038 11.5032 6.0038 11.171 C 6.0038 10.8388 5.7242 10.567 5.3827 10.567 L 3.7264 10.567 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('video::videos.format')}}</h4>

                                <h5>HLS</h5>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content metrics dashboard-page">
            <div class="flexbox align-items-center tab-header">
                <h2>{{ __('video::videos.detailed_statistics_for') }} “@{{ video.title }}” </h2>

            </div>
            <div class="dashbord-section flexbox flex-wrap">
                <div class="dashbord-section-grid flexbox width-50">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>{{ __('video::videos.geographic_wise_view') }}</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z" fill="#43515d"/>
                                    </g>
                                </svg>

                                <select data-jquery="select2_custom_ddl" minimumResults="-1" class="select2_custom_ddl"  ng-model="vVideoDetailsCtrl.geographicStatusSelected" ng-change="vVideoDetailsCtrl.geographicStatus(vVideoDetailsCtrl.geographicStatusSelected)"  data-ng-options="item.id as item.name for item in video.chart_date_filter">

                                </select>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('video::videos.location') }}</th>
                                            <th>{{ __('video::videos.view') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-ng-if="!vVideoDetailsCtrl.geographicStatistics.length" class="ng-scope">
                                            <td colspan="3" class="no-data center">{{ __('video::videos.no_record_found') }}</td>
                                        </tr>
                                        <tr data-ng-if="vVideoDetailsCtrl.geographicStatistics.length > 0" data-ng-repeat="record in vVideoDetailsCtrl.geographicStatistics">
                                            <td>
                                                <p class="strong">@{{record.country}}</p>
                                            </td>
                                            <td >@{{record.sum}}</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashbord-section-grid flexbox width-50">

                    <div class="card">

                        <div class="card-heading flexbox align-items-center">
                            <h3>{{ __('video::videos.performance_statistics') }}</h3>
                            <div class="bottom_button">
                                    <button class="btn_bluegradient" id="export-pdf" title="Export PDF">{{ __('video::videos.export_pdf') }}</button>

                            </div>
                            <div class="dashboard-select">

                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z" fill="#43515d"/>
                                    </g>
                                </svg>

                                <select  minimumResults="-1"  data-jquery="select2_custom_ddl" class="select2_custom_ddl"  class="form-control" ng-model="vVideoDetailsCtrl.performanceStatusSelected" ng-change="vVideoDetailsCtrl.performanceStatus(vVideoDetailsCtrl.performanceStatusSelected)" data-ng-options="item.id as item.name for item in video.chart_date_filter">

                                </select>
                            </div>
                        </div>

                        <div class="card-content graph">
                            <div id="performance-statics-chart" style="height: 350px; width: 100%;">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content metadata">
            <form ng-submit="metaDataSubmit()">
                <div class="flexbox form-col">

                    {{-- <div class="form-group ">
                            <label>{{ __('video::videos.custom_url') }}
                                <svg ng-click="setFocustoCustomURL()" viewBox="0 0 13 13" x="0px" y="0px" width="9px" height="9px" class="custom_edit_ic">
                                    <g>
                                        <path d="M 8.0836 2.1691 L 10.7281 4.8266 L 4.0342 11.5533 L 1.3912 8.8959 L 8.0836 2.1691 ZM 12.7348 1.5282 L 11.5554 0.343 C 11.0996 -0.115 10.3596 -0.115 9.9023 0.343 L 8.7726 1.4783 L 11.417 4.1357 L 12.7348 2.8116 C 13.0883 2.4563 13.0883 1.8834 12.7348 1.5282 ZM 0.0073 12.6312 C -0.0408 12.8489 0.1548 13.0439 0.3713 12.991 L 3.3182 12.273 L 0.6752 9.6154 L 0.0073 12.6312 Z" fill="#6e8c99"/>
                                    </g>
                                </svg>
                            </label>
                            <div class="form-input">
                            <div class="flexbox custom-url">
                                <span ng-click="setFocustoCustomURL()" class="">{{ env('WEB_SITE_URL') }}</span>
                                <input class=" form-control" id="custom_url" type="text" name="custom_url" data-ng-model="vVideoDetailsCtrl.custom_url"  ng-blur="validateCustomURL()" placeholder="{{ __('video::videos.enter_custom_url') }}">
                            </div>
                            <p class="error-msg" data-ng-show="errors.custom_url.has">@{{ errors.custom_url.message }}</p>
                          </div>
                    </div> --}}
                    <div class="form-group">
                            <label>{{ __('video::videos.meta_title') }}</label>
                            <div class="form-input">
                                <input type="text" name="meta_title" data-ng-model="vVideoDetailsCtrl.title" placeholder="{{ __('video::videos.enter_meta_title') }}" class="form-control" />
                            </div>
                    </div>
                    <div class="form-group">
                            <label>{{ __('video::videos.keyword') }}</label>
                            <div class="form-input">
                                <input type="text" name="keyword" data-ng-model="vVideoDetailsCtrl.keyword" placeholder="{{ __('video::videos.enter_meta_keyword') }}" class="form-control" />
                            </div>
                        </div>


                </div>
                <div class="flexbox form-col">
                    <div class="form-group">
                            <label>{{ __('video::videos.description') }}</label>
                            <div class="form-input">
                                <textarea rows="7" maxlength="65" data-ng-model="vVideoDetailsCtrl.description" placeholder="{{ __('video::videos.enter_meta_description') }}" class="form-control"></textarea>
                            </div>
                        </div>


                </div>

                <div class="bottom_button text-right">

                    <a href="{{ url('admin/videos/view-details-video',$id) }}" class="btn_gray" title="Cancel">{{ __('video::videos.cancel') }}</a>
                    <button type="submit" class="btn_bluegradient" title="save"  value="Submit">{{ __('video::videos.save') }}</button>
                </div>
            </form>
        </div>

        <div class="tab-content cover_thumbnail">
            <div class="flexbox">
                <div class="video-cover">
                    <div class="tab-header">
                        <h2>{{ __('video::videos.cover_image') }}</h2>
                    </div>
                   <img ng-if="video.poster_image!='' && video.poster_image!=null" src="@{{ video.poster_image  }}" alt="Video Cover" />
                   <img ng-if="video.poster_image=='' || video.poster_image==null"src="{{$getBaseAssetsUrl('images/cover.png')}}" alt="Video Cover" />
                </div>
                <div class="video-thumb">
                    <div class="tab-header">
                        <h2>{{ __('video::videos.thumbnail_image') }}</h2>
                    </div>
                    <img  ng-if="video.thumbnail_image!=''" src="@{{ video.thumbnail_image }}" alt="Video Thumbnail" />
                    <img  ng-if="video.thumbnail_image==''" src="{{$getBaseAssetsUrl('images/no-preview.png')}}" alt="Video Thumbnail" />
                </div>
            </div>
        </div>
    </div>

    <div class="overlay"></div>
        <div class="alert-popup modal fade" id="commentsdeletemodal" data-role="dialog">
            <!-- Modal content-->
            <div class="alert-popup-content">
                <div class="popup_head">
                    <h3>{{__('base::gridlist.delete_comment')}}</h3>
                </div>
                <div class="popup_content">
                        <svg x="0px" y="0px" viewBox="0 0 512 512" width="26px" height="26px">
                            <g>
                                <g>
                                    <g>
                                        <polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804" fill="#7f7f7f"></polygon>
                                        <rect x="235.948" y="175.791" width="40.104" height="237.285" fill="#7f7f7f"></rect>
                                        <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804" fill="#7f7f7f"></polygon>
                                        <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474     L380.665,471.896z" fill="#7f7f7f"></path>
                                    </g>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z" fill="#7f7f7f"></path>
                                </g>
                            </g>
                        </svg>
                    <div data-ng-show="commentConfirmationDeleteBox">
                        <span class="conformation_txt">{{__('base::gridlist.delete_comment_confirm')}}</span>
                    </div>
                    <div class="popup_btns text-center">
                        <a data-ng-click="cancelDeleteComment()" class="pop_cancel_btn" data-dismiss="modal">{{__('base::gridlist.cancel')}}</a>
                        <a data-ng-click="confirmDeleteComments()" class="pop_confirm_btn"
                            data-dismiss="modal">{{__('base::gridlist.confirm')}}</a>
                    </div>
                </div>
            </div>

    </div>
</div>
{{-- <div class="error-page" data-ng-if="vVideoDetailsCtrl.notFoundFlag">
    <h4>{{ trans('base::general.404_not_found') }}</h4>
    <p>{{ trans('base::general.not_found_text') }}</p>
</div> --}}
<div data-ng-if="vVideoDetailsCtrl.editVideo.trailerId != ''" id="trailerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('video::videos.trailer')}}</h5>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>


</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('player/play.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/libs/ui-bootstrap/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>

<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/viewVideoDetail.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


@endsection
