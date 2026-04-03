@extends('base::layouts.default')
@section('stylesheet')
@endsection
@section('header') 
@include('base::layouts.headers.dashboard') @endsection 
@section('content')

<div class="product order_list" data-ng-controller="ViewAudioDetailsController as vAudioDetailsCtrl" data-ng-init=vAudioDetailsCtrl.fetchData('{{$id}}')>

    @include('audio::admin.common.subMenu', ['template' => 'audio_detail', 'id' => $id, 'control' => 'vAudioDetailsCtrl'])
    @include('audio::admin.common.popup', ['control' => 'vAudioDetailsCtrl'])

    <div class="video-detail" data-ng-if="!vAudioDetailsCtrl.notFoundFlag">
    <!-- data-ng-if="!vAudioDetailsCtrl.notFoundFlag" -->
        <div data-ng-if="vAudioAlbumsCtrl.audioAlbums.audios == ''" style="text-align: center;width: 100%;margin-top:15px;" colspan="@{{heading.length + 2}}" class="no-data center">{{trans('base::general.not_found')}}</div>

        <ul class="flexbox custom-tabs">
            <li class="tab-link active" id="overview">
                <a href="javascript:void(0)" class="flexbox align-items-center">
                    <svg viewBox="0 0 13 16" version="1.1" x="0px" y="0px" width="13px" height="16px">
                        <g>
                            <path d="M 12.7249 15.2275 C 12.5428 15.4097 12.3215 15.5009 12.0613 15.5009 L 1.4389 15.5009 C 1.1786 15.5009 0.9572 15.4097 0.775 15.2275 C 0.5927 15.0452 0.5016 14.8238 0.5016 14.5634 L 0.5016 1.4364 C 0.5016 1.176 0.5927 0.9546 0.775 0.7723 C 0.9572 0.59 1.1786 0.4989 1.4389 0.4989 L 7.6875 0.4989 C 7.9478 0.4989 8.2343 0.564 8.5466 0.6941 C 8.8589 0.8245 9.1062 0.9808 9.2884 1.1631 L 12.3346 4.2103 C 12.5168 4.3927 12.6729 4.64 12.8031 4.9527 C 12.9332 5.2651 12.9983 5.5516 12.9983 5.8121 L 12.9983 14.5634 C 12.9983 14.8238 12.9071 15.0452 12.7249 15.2275 ZM 11.456 5.0991 L 8.3999 2.042 C 8.3219 1.964 8.1884 1.8922 7.9998 1.8271 L 7.9998 5.4996 L 11.6706 5.4996 C 11.6054 5.3107 11.5339 5.1773 11.456 5.0991 ZM 11.7487 6.7498 L 7.6873 6.7498 C 7.4268 6.7498 7.2055 6.6586 7.0233 6.4763 C 6.841 6.2941 6.75 6.0727 6.75 5.8121 L 6.75 1.7492 L 1.7513 1.7492 L 1.7513 14.2508 L 11.7487 14.2508 L 11.7487 6.7498 ZM 3.3134 7.9999 L 10.1867 7.9999 C 10.2777 7.9999 10.3526 8.0291 10.4111 8.0877 C 10.4696 8.1465 10.499 8.2215 10.499 8.3124 L 10.499 8.9375 C 10.499 9.0288 10.4696 9.1036 10.4111 9.1621 C 10.3526 9.2207 10.2777 9.2499 10.1867 9.2499 L 3.3134 9.2499 C 3.2223 9.2499 3.1474 9.2207 3.0888 9.1621 C 3.0302 9.1036 3.001 9.0288 3.001 8.9375 L 3.001 8.3124 C 3.001 8.2211 3.0304 8.1462 3.0888 8.0877 C 3.1474 8.0291 3.2223 7.9999 3.3134 7.9999 ZM 3.3134 10.5003 L 10.1867 10.5003 C 10.2777 10.5003 10.3526 10.5296 10.4111 10.5879 C 10.4696 10.6466 10.499 10.7216 10.499 10.8126 L 10.499 11.4378 C 10.499 11.5291 10.4696 11.604 10.4111 11.6625 C 10.3526 11.721 10.2777 11.7502 10.1867 11.7502 L 3.3134 11.7502 C 3.2223 11.7502 3.1474 11.721 3.0888 11.6625 C 3.0302 11.604 3.001 11.5291 3.001 11.4378 L 3.001 10.8126 C 3.001 10.7216 3.0304 10.6466 3.0888 10.5879 C 3.1474 10.5296 3.2223 10.5003 3.3134 10.5003 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.overview') }}</span>
                </a>
            </li>
            <li class="tab-link" id="metrics" style="display:none">
                <a href="javascript:void(0)" class="flexbox align-items-center">
                    <svg viewBox="0 0 15 15" version="1.1" x="0px" y="0px" width="15px" height="15px">
                        <g>
                            <path d="M 14.0449 14.9999 L 10.9299 14.9999 C 10.6778 14.9999 10.475 14.791 10.475 14.5313 L 10.5062 7.4757 C 10.5062 7.2088 10.7073 6.9943 10.9575 6.9943 L 14.0484 6.9943 C 14.2985 6.9943 14.5027 7.2088 14.4999 7.4757 L 14.4999 14.5313 C 14.4999 14.791 14.297 14.9999 14.0449 14.9999 ZM 13.4104 7.9999 L 11.4949 7.9933 L 11.4947 14.0002 L 13.4964 14.0002 L 13.4104 7.9999 ZM 9.0575 14.9999 L 5.9424 14.9999 C 5.6903 14.9999 5.4874 14.791 5.4874 14.5313 L 5.4874 1.4684 C 5.4874 1.2088 5.6903 0.9999 5.9424 0.9999 L 9.0575 0.9999 C 9.3096 0.9999 9.5153 1.2088 9.5123 1.4684 L 9.5123 14.5313 C 9.5123 14.791 9.3096 14.9999 9.0575 14.9999 ZM 8.4935 1.9995 L 6.506 1.9995 L 6.506 14.0002 L 8.4935 14.0002 L 8.4935 1.9995 ZM 4.0511 14.9999 L 0.9525 14.9999 C 0.7017 14.9999 0.5 14.785 0.5 14.5181 L 0.5 4.9873 C 0.5 4.7204 0.7017 4.5055 0.9525 4.5055 L 4.0511 4.5055 C 4.3019 4.5055 4.5066 4.7204 4.5037 4.9873 L 4.5037 14.5181 C 4.5037 14.785 4.3019 14.9999 4.0511 14.9999 ZM 3.5013 5.5058 L 1.5021 5.5058 L 1.5021 13.9997 L 3.5013 13.9997 L 3.5013 5.5058 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.metrics') }}</span>
                </a>
            </li>
            <li class="tab-link" id="metadata" style="display:none">
                <a href="javascript:void(0)" class="flexbox align-items-center">
                    <svg viewBox="0 0 511 511.99982" width="16px" height="16px">
                        <path d="m482.921875 136.523438 23.699219-23.699219c12.554687-12.554688 3.664062-34.140625-14.140625-34.140625h-58.664063v-58.664063c0-17.765625-21.558594-26.722656-34.140625-14.140625l-23.699219 23.699219c-36.675781-19.375-77.660156-29.5585938-119.488281-29.5585938-141.476562 0-255.988281 114.4921878-255.988281 255.9921878 0 141.476562 114.492188 255.988281 255.988281 255.988281 141.480469 0 255.992188-114.492188 255.992188-255.988281 0-41.828125-10.183594-82.8125-29.558594-119.488281zm-69.105469-17.839844h30.382813l-38.667969 38.664062h-50.378906v-50.382812l38.664062-38.664063v30.382813c0 11.042968 8.953125 20 20 20zm-77.332031 137.328125c0 44.109375-35.886719 79.996093-79.996094 79.996093s-79.996093-35.886718-79.996093-79.996093c0-44.113281 35.886718-80 79.996093-80 14.816407 0 28.691407 4.066406 40.605469 11.113281l-54.746094 54.742188c-7.808594 7.8125-7.808594 20.476562 0 28.285156s20.472656 7.808594 28.28125 0l54.746094-54.746094c7.050781 11.914062 11.109375 25.789062 11.109375 40.605469zm-79.996094 215.988281c-119.097656 0-215.988281-96.890625-215.988281-215.988281 0-119.097657 96.890625-215.992188 215.988281-215.992188 31.101563 0 61.644531 6.660157 89.625 19.417969l-25.101562 25.105469c-3.75 3.75-5.859375 8.835937-5.859375 14.140625v52.683594c-17.355469-9.769532-37.367188-15.351563-58.664063-15.351563-66.164062 0-119.996093 53.828125-119.996093 119.996094 0 66.164062 53.832031 119.992187 119.996093 119.992187 66.164063 0 119.996094-53.828125 119.996094-119.992187 0-21.296875-5.582031-41.308594-15.351563-58.664063h52.683594c5.304688 0 10.390625-2.109375 14.140625-5.859375l25.101563-25.101562c12.761718 27.976562 19.421875 58.519531 19.421875 89.621093 0 119.101563-96.894531 215.992188-215.992188 215.992188zm0 0" />
                    </svg>
                    <span>{{ trans('base::general.metadata') }}</span>
                </a>
            </li>
            <li class="tab-link" id="cover_thumbnail">
                <a href="javascript:void(0)" class="flexbox align-items-center">
                    <svg viewBox="0 0 16 16" version="1.1" x="0px" y="0px" width="16px" height="16px">
                        <g>
                            <path d="M 15.3542 15.5 L 1.1458 15.5 C 0.7885 15.5 0.5 15.2051 0.5 14.8409 L 0.5 1.659 C 0.5 1.2954 0.7885 1 1.1458 1 L 15.3542 1 C 15.7111 1 16 1.2954 16 1.659 L 16 14.8409 C 16 15.2051 15.7111 15.5 15.3542 15.5 ZM 2.4374 14.1818 L 8.1667 14.1818 L 5.5698 11.5313 C 5.3172 11.2738 4.9091 11.2738 4.6566 11.5313 L 2.1404 14.0987 C 2.2309 14.147 2.3284 14.1818 2.4374 14.1818 ZM 14.7083 2.9773 C 14.7083 2.6135 14.4188 2.3181 14.0624 2.3181 L 2.4374 2.3181 C 2.0811 2.3181 1.7916 2.6135 1.7916 2.9773 L 1.7916 12.5907 L 3.7433 10.5993 C 4.4989 9.8281 5.7272 9.8295 6.483 10.5993 L 6.8704 10.9945 L 10.2846 7.511 C 11.0401 6.74 12.2687 6.7406 13.0244 7.5104 L 14.7083 9.2287 L 14.7083 2.9773 ZM 14.7083 11.0923 L 12.111 8.4431 C 11.8582 8.1859 11.4508 8.1859 11.1978 8.4431 L 7.7829 11.9265 L 9.9932 14.1818 L 14.0624 14.1818 C 14.4188 14.1818 14.7083 13.8857 14.7083 13.5227 L 14.7083 11.0923 ZM 5.6666 8.909 C 4.2419 8.909 3.0833 7.7267 3.0833 6.2727 C 3.0833 4.8193 4.2419 3.6363 5.6666 3.6363 C 7.0914 3.6363 8.2499 4.8193 8.2499 6.2727 C 8.2499 7.7267 7.0914 8.909 5.6666 8.909 ZM 5.6666 4.9545 C 4.9543 4.9545 4.3749 5.545 4.3749 6.2727 C 4.3749 7.0003 4.9543 7.5909 5.6666 7.5909 C 6.379 7.5909 6.9583 7.0003 6.9583 6.2727 C 6.9583 5.545 6.379 4.9545 5.6666 4.9545 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.cover') }}</span>
                </a>
            </li>
        </ul>

        <div class="video-detail-overview tab-content active">
            <div class="player-counts flexbox">
                <div class="video-detail-content audio_detail_player">
                    <div ng-if="audio.audio_thumbnail != ''"  class="banner-image video-player" style="background-image: url(@{{audio.audio_thumbnail}})">
                        <img src="@{{audio.audio_thumbnail}}" />
                        <div class="overlay"></div>
                    </div>
                    <div ng-if="audio.audio_thumbnail == ''" class="banner-image video-player" style="background-image: url({{url('contus/base/images/no-preview.png')}})">
                        <img src="{{url('contus/base/images/no-preview.png')}}" />
                        <div class="overlay"></div>
                    </div>


                    <!-- <div class="video-player"> url({{url('contus/base/images/no-preview.png')}})
                        <audio controls preload="metadata">
                            <source src='https://vplayed-uat.s3.ap-southeast-1.amazonaws.com/audios/source/2019/01/100-audio-636266.mp3' type="audio/mp3">
                        </audio>
                    </div> -->

                    <h2 class="flexbox align-items-center video-title">
                        <span>@{{ audio.audio_title }}</span>

                        <!-- <span class="premium">Premium</span> -->
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

                            <span>@{{ audio.user.name }}</span>
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
                            <span>@{{ audio.audio_duration }}</span>
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

                            <span>@{{ audio.formatted_created_date }}</span>
                        </li>

                        <li>
                            <span class="active status-dot" ng-if="audio.is_active==1">Active</span>
                            <span class="inactive status-dot" ng-if="audio.is_active==0">In Active</span>
                        </li>
                    </ul>

                    <div class="content">
                        <div class="one-set">
                            <h4 class="heading">Overview</h4>
                            <p data-ng-bind="vAudioDetailsCtrl.editAudio.descriptionContent">Overview 
                                <a href="javascript:void" class="more" data-ng-show="vAudioDetailsCtrl.editAudio.trimFlag" data-ng-click="vAudioDetailsCtrl.showFullDescription()">More</a></p>
                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set divide-3">
                                <h4 class="heading">Artist</h4>
                                <p>@{{ audio.artist.artist_name }}</p>
                            </div>
                            <div class="one-set divide-3">
                                <h4 class="heading">Album</h4>
                                <p>@{{ audio.album.album_name }}</p>
                            </div>
                            <div class="one-set divide-3">
                                <h4 class="heading">Audio Language</h4>
                                <p>@{{ audio.album.audio_language }}</p>
                            </div>
                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set divide-3">
                                <h4 class="heading">Genre</h4>
                                <p ng-if="audio.album.genre_name != null">@{{ audio.album.genre_name }}</p>
                                <p ng-if="audio.album.genre_name == null"> - </p>
                            </div>
                            <!-- <div class="one-set divide-3">
                                <h4 class="heading">Size</h4>
                                <p>2GB</p>
                            </div> -->
                        </div>

                        <div class="division flexbox flex-wrap">
                            <div class="one-set">
                                <h4 class="heading">Direct link</h4>
                                <div>
                                  <p>
                                    <a data-ng-if="audio.job_status == 'Complete'" target="_blank" href="{{ env('WEB_SITE_AUDIO_URL') }}audio/song/@{{ audio.slug }}">{{ env('WEB_SITE_AUDIO_URL') }}audio/song/@{{ audio.slug }}</a>
                                    <a data-ng-if="audio.job_status != 'Complete'">{{ env('WEB_SITE_AUDIO_URL') }}audio/song/@{{ audio.slug }} </a>
                                    
                                    <span data-ng-if="audio.job_status != 'Complete'">Link is disabled as long as the audio is unpublished. </span> </p>
                                </div>
                            </div>
                        </div>
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
                                <h4>{{trans('audio::audio.plays')}}</h4>
                                <h5>@{{audio.play_count}}</h5>
                            </div>
                        </li>
                        <!-- <li class="flexbox align-items-center">
                            <div class="svg">
                                <svg viewBox="0 0 16 15" version="1.1" x="0px" y="0px" width="16px" height="15px">
                                    <g id="Layer%201">
                                        <path d="M 16.0004 5.8129 C 16.0004 5.5797 15.8267 5.4348 15.4785 5.3782 L 10.8024 4.6883 L 8.7064 0.3883 C 8.5886 0.1299 8.4363 0.0008 8.25 0.0008 C 8.0637 0.0008 7.9116 0.1299 7.7936 0.3883 L 5.6975 4.6883 L 1.0211 5.3782 C 0.6735 5.4348 0.4996 5.5797 0.4996 5.8129 C 0.4996 5.9452 0.5773 6.0965 0.7324 6.2665 L 4.1233 9.612 L 3.3223 14.3374 C 3.3098 14.4256 3.3037 14.4887 3.3037 14.5265 C 3.3037 14.6587 3.3362 14.7706 3.4014 14.862 C 3.4666 14.9534 3.5643 14.999 3.6947 14.999 C 3.8066 14.999 3.9308 14.9614 4.0674 14.8856 L 8.2499 12.6552 L 12.4326 14.8856 C 12.5631 14.9612 12.6873 14.999 12.8052 14.999 C 13.0599 14.999 13.1873 14.8417 13.1873 14.5266 C 13.1873 14.4447 13.1841 14.3818 13.1779 14.3374 L 12.3768 9.6123 L 15.7583 6.2668 C 15.9197 6.1028 16.0004 5.9515 16.0004 5.8129 ZM 11.0912 9.1868 L 11.7617 13.1657 L 8.2499 11.2849 L 4.7286 13.1657 L 5.4087 9.1868 L 2.5581 6.3799 L 6.4894 5.7939 L 8.2499 2.1837 L 10.0105 5.7939 L 13.9419 6.3799 L 11.0912 9.1868 Z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="content flexbox align-items-center">
                                <h4>{{trans('audio::audio.favourite')}}</h4>
                                <h5>@{{ audio.favourite_count }}</h5>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content metrics dashboard-page">
            <div class="flexbox align-items-center tab-header">
                <h2>Detailed statistics for “Sample test of Aquaman” </h2>
                <div class="bottom_button"> 
                    <button class="btn_bluegradient" title="Export PDF">Export PDF</button>
                </div>
            </div>
            <div class="dashbord-section flexbox flex-wrap">
                <div class="dashbord-section-grid flexbox width-50">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Geographic Wise View</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z" fill="#43515d"/>
                                    </g>
                                </svg>
                                <select class="ng-pristine ng-valid ng-not-empty ng-touched">
                                    <option label="All" value="number:1">All</option>
                                    <option label="Last Year" value="number:2">Last Year</option>
                                    <option label="Last Month" value="number:3">Last Month</option>
                                    <option label="Last 7 Days" value="number:4" selected="selected">Last 7 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th class="center">Total Videos</th>
                                            <th class="center">Percentage (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                       
                                        <tr data-ng-if="!dashCtrl.regionwise_analytics.data.length" class="ng-scope">
                                            <td colspan="7" class="no-data center">No Record Found</td>
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
                            <h3>Performance statistics</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z" fill="#43515d"/>
                                    </g>
                                </svg>
                                <select class="ng-pristine ng-untouched ng-valid ng-not-empty">
                                    <option label="All" value="number:1">All</option>
                                    <option label="Last Year" value="number:2">Last Year</option>
                                    <option label="Last Month" value="number:3">Last Month</option>
                                    <option label="Last 7 Days" value="number:4" selected="selected">Last 7 Days</option>
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
            <form>
                <div class="flexbox form-col">
                    <div class="form-group">
                        <label>Custom URL
                            <svg viewBox="0 0 13 13" x="0px" y="0px" width="9px" height="9px" class="custom_edit_ic">
                                <g>
                                    <path d="M 8.0836 2.1691 L 10.7281 4.8266 L 4.0342 11.5533 L 1.3912 8.8959 L 8.0836 2.1691 ZM 12.7348 1.5282 L 11.5554 0.343 C 11.0996 -0.115 10.3596 -0.115 9.9023 0.343 L 8.7726 1.4783 L 11.417 4.1357 L 12.7348 2.8116 C 13.0883 2.4563 13.0883 1.8834 12.7348 1.5282 ZM 0.0073 12.6312 C -0.0408 12.8489 0.1548 13.0439 0.3713 12.991 L 3.3182 12.273 L 0.6752 9.6154 L 0.0073 12.6312 Z" fill="#6e8c99"/>
                                </g>
                            </svg>
                        </label>
                        <div class="form-input">
                            <input type="url" name="custom_url" placeholder="Enter Custom URL" class="form-control default-val" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Meta title</label>                        
                        <div class="form-input">
                            <input type="text" name="meta_title" placeholder="Enter Meta Title (55 to 65 characters)" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="flexbox form-col">
                    <div class="form-group">
                        <label>description</label>                        
                        <div class="form-input">
                            <textarea rows="7" maxlength="65" placeholder="Enter Description (55 to 65 characters)" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keyword</label>                          
                        <div class="form-input">
                            <input type="text" name="keyword" placeholder="Enter Keyword (55 to 65 characters)" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="bottom_button text-right"> 
                    <button class="btn_gray" title="Cancel">Cancel</button>
                    <button class="btn_bluegradient" title="save">Save</button>
                </div>
            </form>
        </div>

        <div class="tab-content cover_thumbnail">            
            <div class="flexbox"> 
                <div class="video-thumb">
                    <div class="tab-header">    
                        <h2>Thumbnail image</h2>
                    </div>
                    <img data-ng-if="audio.audio_thumbnail != ''" src="@{{ audio.audio_thumbnail }}" alt="Audio Thumbnail" />
                    <img data-ng-if="audio.audio_thumbnail == ''" src="{{url('contus/base/images/no-preview.png')}}" alt="Audio Thumbnail" />
                    
                </div>
            </div>
        </div>

    </div>


    <!-- <div class="contentpanel clearfix video-detail" data-ng-if="!vAudioDetailsCtrl.notFoundFlag">
     <div class="row">
        <div class="video-view-detail">
            <div class="clearfix play-video-container">
                <div class="row">
                    <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 clearfix">
                        <div>
                            <audio controls style="width:650px;">
                                <source src="@{{audio.audio_url | trusted}}" type="audio/mpeg">
                            </audio>
                        </div>
                    </div>
                    <div class="play-video-column col-md-3 col-sm-12 col-xs-12">
                        <div class="play-video-format">
                            <ul>
                                <li>
                                    <div class="format">
                                        <i class="view-icon"></i>
                                        <span>{{trans('audio::audio.plays')}}</span>
                                        <h5> @{{audio.play_count}} </h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="format">
                                        <i class="review-fastar-icon"></i>
                                        <span>{{trans('audio::audio.favourite')}}</span>
                                        <h5>@{{ audio.favourite_count }}</h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row video-maindetails clearfix">
                    <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                        <h2></h2>
                        <div class="play-video-detail" data-ng-if="vAudioDetailsCtrl.editAudio.description != ''">
                            
                        </div>
                    </div>
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="video_detail_list">
                        <h3>{{trans('audio::audio.details')}}</h3>
                        <div class="row">
                            <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                <label>{{trans('audio::audio.artist')}} </label>
                            </div>
                            <div class="col-sm-7 col-md-7 col-lg-7 col-xs-7">
                                <span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                <label>{{trans('audio::audio.uploaded_date')}}</label>
                            </div>
                            <div class="col-sm-7 col-md-7 col-lg-7 col-xs-7">
                                <span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                <label>{{trans('audio::audio.uploaded_by')}}</label>
                            </div>
                            <div class="col-sm-7 col-md-7 col-lg-7 col-xs-7">
                                <span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                <label>{{trans('audio::audio.modified_on')}}</label>
                            </div>
                            <div class="col-sm-7 col-md-7 col-lg-7 col-xs-7">
                                <span>@{{ audio.formatted_updated_date }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        </div>
    </div> -->
</div>
<div class="error-page" data-ng-if="vAudioDetailsCtrl.notFoundFlag">
    <h4>{{ trans('base::general.404_not_found') }}</h4>
    <p>{{ trans('base::general.not_found_text') }}</p>
</div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('player/player.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angular/libs/ui-bootstrap/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/common.js')}}"></script>
<script src="{{$getAudioAssetsUrl('js/audios/viewAudioDetail.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/common/directive.js')}}"></script>


<script>
    $(document).ready(function () {
        $("#overview").click(function(){
            $(".tab-content").hide();
            $(".tab-link").removeClass("active");
            $(".video-detail-overview").show();
            $(this).addClass("active");
        });
        $("#metrics").click(function(){
            $(".tab-content").hide();
            $(".tab-link").removeClass("active");
            $(".metrics").show();
            $(this).addClass("active");
        });
        $("#metadata").click(function(){
            $(".tab-content").hide();
            $(".tab-link").removeClass("active");
            $(".metadata").show();
            $(this).addClass("active");
        });
        $("#cover_thumbnail").click(function(){
            $(".tab-content").hide();
            $(".tab-link").removeClass("active");
            $(".cover_thumbnail").show();
            $(this).addClass("active");
        });
        $('.modal-toggle').on('click', function(e) {
            e.preventDefault();
            $('.modal_popup').toggleClass('is-visible');
            $(body).addClass("popup_open");
        });
    });
    
</script>
@endsection
