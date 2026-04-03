<div class="leftpanel mCustomScrollbar" data-mcs-theme="dark">
    <div class="sidebar-menu">
        <ul>
            <!-- dashboard -->
            <li class="treeview" data-ng-if="checkAccess('dashboard.view')">
                <a href="{{ url('admin/dashboard') }}" {{ Request::is('admin/dashboard') ? 'class=active' : '' }}>

                    <svg viewBox="0 0 12 18" version="1.1" x="0px" y="0px">
                        <g>
                            <path
                                d="M 4.3903 17.9858 C 4.4687 18.0169 4.5471 18.0324 4.6254 18.0324 C 4.8291 18.0324 5.033 17.9394 5.1582 17.7533 L 11.8812 8.1363 C 12.0222 7.9501 12.0379 7.6865 11.9283 7.4846 C 11.8185 7.2831 11.5992 7.1436 11.3641 7.1436 L 7.1954 7.1124 L 8.4022 0.7837 C 8.4648 0.5046 8.3082 0.21 8.0418 0.0858 C 7.7754 -0.0383 7.462 0.0393 7.2738 0.272 L 0.1435 9.1599 C -0.0132 9.346 -0.0445 9.6096 0.0651 9.827 C 0.1748 10.0442 0.3942 10.1837 0.6449 10.1837 L 5.2522 10.2147 L 4.0142 17.2878 C 3.9516 17.5825 4.1083 17.8772 4.3903 17.9858 Z" />
                        </g>
                    </svg>

                    <span>{{ __('base::general.dashboard') }}</span>
                </a>
            </li>

            <!-- organizations -->
            <li class="treeview" data-ng-if="checkAccess('organization.view')">
                <a href="{{ url('admin/organizations') }}" {{ Request::is('admin/organizations') || Request::is('admin/general/details') || Request::is('admin/contentset', 'admin/live-event/content-set', 'admin/vod/content-set', 'admin/tv-show/content-set') || Request::is('admin/live-event/content-set') || Request::is('admin/add/live-event/content-set') || Request::is('admin/live-event/content-set/edit') || Request::is('admin/vod/content-set') || Request::is('admin/add/vod/content-set') || Request::is('admin/vod/content-set/edit') || Request::is('admin/tv-show/content-set') || Request::is('admin/add/tv-show/content-set') || Request::is('admin/tv-show/content-set/edit') || Request::is('admin/monetization-paln/subscription') || Request::is('admin/announcment') || Request::is('admin/customization', 'admin/app-customization/banner_carousels/add') || Request::is('admin/shoppingcart') || Request::is('admin/add-subscribers') || Request::is('admin/devices') || Request::is('admin/devices') || Request::is('admin/payment-service') || Request::is('admin/partner-product') || Request::is('admin/monetization-plan/accessories') || Request::is('admin/monitization-plan/subscription/add', 'admin/app-customization/promotion/banner_carousels', 'admin/app-customization/banner_carousels_subscription', 'admin/app-customization/banner_carousels_subscription/add', 'admin/app-customization/promotion/features-row', 'admin/app-customization/promotion/row-order', 'admin/app-customization/promotion/row-order/add', 'admin/app-customization/channel-listing', 'admin/app-customization/channel-listing/add', 'admin/app-customization/general', 'admin/app-customization/setting', 'admin/app-customization/setting/add', 'admin/app-customization/setting/edit/*', 'admin/app-customization/banner_carousels_subscription/edit/*', 'admin/organizations/payment-service', 'admin/organizations/payment-service/currency', 'admin/organizations/payment-service/currency-converter', 'admin/view-subscribers', 'admin/reminders', 'admin/activation/add', 'admin/disabled-accounts/add', 'admin/push-notifications', 'admin/monetization-plan/subscription', 'admin/monitization-plan/subscription/edit/*', 'admin/channel/content-set/view/*', 'admin/channel/content-set/edit', 'admin/add/channel/content-set', 'admin/live-event/content-set/view/*', 'admin/vod/content-set/view/*', 'admin/tv-show/content-set/view/*', 'admin/app-customization/channel-listing/add/*', 'admin/app-customization/channel-listing/view', 'admin/app-customization/promotion/row-order/view', '', '', ) ? 'class=active' : '' }}>
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 36 36" version="1.1"
                        preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>organization-solid</title>
                        <polygon
                            points="9.8 18.8 26.2 18.8 26.2 21.88 27.8 21.88 27.8 17.2 18.8 17.2 18.8 14 17.2 14 17.2 17.2 8.2 17.2 8.2 21.88 9.8 21.88 9.8 18.8"
                            class="clr-i-solid clr-i-solid-path-1">
                        </polygon>
                        <rect x="2" y="23" width="14" height="10" rx="2" ry="2" class="clr-i-solid clr-i-solid-path-2">
                        </rect>
                        <rect x="20" y="23" width="14" height="10" rx="2" ry="2" class="clr-i-solid clr-i-solid-path-3">
                        </rect>
                        <rect x="11" y="3" width="14" height="10" rx="2" ry="2" class="clr-i-solid clr-i-solid-path-4">
                        </rect>
                        <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                    </svg>
                    <span>
                        {{ trans('base::general.organizations') }}
                    </span>
                </a>
            </li>

            <!-- drm -->
            <li class="treeview" data-ng-if="checkAccess('drm_profiles.view')">
                <a href="{{ url('admin/drm') }}"
                    class="{{ Request::is('admin/drm') || Request::is('admin/drm/detail/add*') || Request::is('admin/drm/profile/add*') ? 'active' : '' }}">
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 35 35" data-name="Layer 2"
                        id="Layer_2" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.5,34.44A3.07,3.07,0,0,1,15.89,34L9.82,30.45A14.79,14.79,0,0,1,2.25,17.7V8A3.2,3.2,0,0,1,4.34,5L16.4.57a3.2,3.2,0,0,1,2.2,0L30.66,5a3.2,3.2,0,0,1,2.09,3V17.7a14.79,14.79,0,0,1-7.57,12.75L19.11,34A3.07,3.07,0,0,1,17.5,34.44Zm0-31.56a.67.67,0,0,0-.24,0L5.2,7.33A.69.69,0,0,0,4.75,8V17.7a12.3,12.3,0,0,0,6.33,10.59l6.07,3.56a.73.73,0,0,0,.7,0l6.07-3.56h0A12.3,12.3,0,0,0,30.25,17.7V8a.69.69,0,0,0-.45-.65L17.74,2.92A.67.67,0,0,0,17.5,2.88Z" />
                        <path
                            d="M16.4,22.35a1.3,1.3,0,0,1-.81-.29l-4.27-3.6a1.25,1.25,0,0,1,1.61-1.92l3.35,2.82L22,13.06a1.25,1.25,0,0,1,1.86,1.68l-6.48,7.2A1.27,1.27,0,0,1,16.4,22.35Z" />
                    </svg>
                    <span>{{ trans('base::general.drm') }}</span>
                </a>
            </li>

            <!-- subscribers -->
            <li class="treeview" data-ng-if="checkAccess('subscribers.view')">
                <a href="{{ url('admin/subscribers') }}" {{ Request::is('admin/subscribers') || Request::is('admin/subscribers/detail/add*') || Request::is('admin/subscribers/devices*') || Request::is('admin/subscriber/activation*') || Request::is('admin/subscriber/activation*') || Request::is('admin/subscribers/credit-card*') || Request::is('admin/subscribers/payment-history*') || Request::is('admin/subscribers/patner-product*') || Request::is('admin/activation/subscriber/add-slot*') || Request::is('admin/subscriber/custom-stream*') || Request::is('admin/subscriber/notes*') ? 'class=active' : '' }}>
                    <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16.719 19.7519L16.0785 14.6279C15.8908 13.1266 14.6146 12 13.1017 12H12H10.8983C9.38538 12 8.10917 13.1266 7.92151 14.6279L7.28101 19.7519C7.1318 20.9456 8.06257 22 9.26556 22H12H14.7344C15.9374 22 16.8682 20.9456 16.719 19.7519Z"
                            stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="5" r="3" stroke="#000000" stroke-width="2" />
                        <circle cx="4" cy="9" r="2" stroke="#000000" stroke-width="2" />
                        <circle cx="20" cy="9" r="2" stroke="#000000" stroke-width="2" />
                        <path
                            d="M4 14H3.69425C2.71658 14 1.8822 14.7068 1.72147 15.6712L1.38813 17.6712C1.18496 18.8903 2.12504 20 3.36092 20H7"
                            stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M20 14H20.3057C21.2834 14 22.1178 14.7068 22.2785 15.6712L22.6119 17.6712C22.815 18.8903 21.8751 20 20.6392 20C19.4775 20 18.0952 20 17 20"
                            stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>
                        {{ trans('base::general.subscribers') }}
                    </span>
                </a>
            </li>

            <!-- channels -->
            <li class="treeview" data-ng-if="checkAccess('channel.view')">
                <a href="{{ url('admin/channel') }}"
                    class="{{ Request::is('admin/channel') || Request::is('admin/channel/add') || Request::is('admin/channel/channel-details-edit/*') ? 'active' : '' }}">
                    <svg width="800px" height="800px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.284 14.546A2.975 2.975 0 0117 14c1.654 0 3 1.346 3 3s-1.346 3-3 3-3-1.346-3-3c.004-.279.047-.555.129-.822l-1.575-1.125A3.964 3.964 0 0110 16a3.964 3.964 0 01-2.554-.947l-1.575 1.125c.076.262.129.535.129.822 0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3c.615 0 1.214.191 1.716.546l1.56-1.114A3.97 3.97 0 016 12c0-1.858 1.28-3.411 3-3.858V5.815A2.993 2.993 0 017 3c0-1.654 1.346-3 3-3s3 1.346 3 3a2.996 2.996 0 01-2 2.816v2.326c1.72.447 3 2 3 3.858-.003.49-.096.976-.276 1.432l1.56 1.114zm1.037 3.146A1 1 0 0017 18a1 1 0 000-2 1 1 0 00-.679 1.692zm-14 0A1 1 0 003 18a1 1 0 000-2 1 1 0 00-.679 1.692zM11 3c0-.551-.449-1-1-1-.551 0-1 .449-1 1 0 .551.449 1 1 1 .551 0 1-.449 1-1z"
                            fill="#5C5F62" />
                    </svg>
                    <span>Channels</span>
                </a>
            </li>

            <!-- live event -->
            <li class="treeview" data-ng-if="checkAccess('live_event.view')">
                <a href="{{ url('admin/liveevents') }}" {{ Request::is('admin/liveevents') || Request::is('admin/liveevents/details-liveevents-edit/*') || Request::is('admin/liveevents/view-liveevents-video/*') || Request::is('admin/liveevents/add') ? 'class=active' : '' }}>
                    <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" width="800px" height="800px"
                        viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                        <g>
                            <path
                                d="M46.5,20h-41C4.7,20,4,20.7,4,21.5V46c0,2.2,1.8,4,4,4h36c2.2,0,4-1.8,4-4V21.5C48,20.7,47.3,20,46.5,20z M19,42c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V42z M19,32c0,0.6-0.4,1-1,1h-4 c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z M29,42c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4 c0.6,0,1,0.4,1,1V42z M29,32c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z M39,42 c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V42z M39,32c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4 c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z" />
                            <path
                                d="M44,7h-4h-1V5c0-1.6-1.3-3-3-3l0,0c-1.6,0-3,1.3-3,3v2H19V5c0-1.6-1.3-3-3-3l0,0c-1.6,0-3,1.3-3,3v2h-1H8 c-2.2,0-4,1.8-4,4v2.5C4,14.3,4.7,15,5.5,15h41c0.8,0,1.5-0.7,1.5-1.5V11C48,8.8,46.2,7,44,7z" />
                        </g>
                    </svg>
                    <span>Live Events</span>
                </a>
            </li>

            <!-- vod -->
            <li class="treeview" data-ng-if="checkAccess('video_on_demand.view')">
                <a href="{{ url('admin/vod') }}" {{ Request::is('admin/vod') || Request::is('admin/vod/add') || Request::is('admin/vod/vod-details-edit/*') ? 'class=active' : '' }}>
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21.53,7.15a1,1,0,0,0-1,0L17,8.89A3,3,0,0,0,14,6H5A3,3,0,0,0,2,9v6a3,3,0,0,0,3,3h9a3,3,0,0,0,3-2.89l3.56,1.78A1,1,0,0,0,21,17a1,1,0,0,0,.53-.15A1,1,0,0,0,22,16V8A1,1,0,0,0,21.53,7.15ZM15,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V9A1,1,0,0,1,5,8h9a1,1,0,0,1,1,1Zm5-.62-3-1.5V11.12l3-1.5Z" />
                    </svg>
                    <span>Video On Demand (VOD)</span>
                </a>
            </li>

            <!-- tv show -->
            <li class="treeview" data-ng-if="checkAccess('tv_shows.view')">
                <a href="{{ url('admin/tvshow') }}" {{ Request::is(['admin/tvshow', 'admin/tvshow/add', 'admin/tvshow/edit-tv-show/*', 'admin/tvshow/season/episode', 'admin/tvshow/add/season/*', 'admin/tvshow/edit-tv-show-season/season-id/*', 'admin/tvshow/season/episode-edit/episode-id/*', 'admin/tvshow/season/episode/*']) ? 'class=active' : '' }}>
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="495.861px" height="495.861px"
                        viewBox="0 0 495.861 495.861" style="enable-background:new 0 0 495.861 495.861;"
                        xml:space="preserve">
                        <g>
                            <g>
                                <path
                                    d="M212.443,40.199l-18.645-24.266l-87.577,67.298L18.645,15.934L0,40.199l61.534,47.283l1.989,1.53H9.321v353.431h35.701 v1.529v35.955h61.2v-35.955v-1.529h296.819v1.529v35.955h61.201v-35.955v-1.529h31.619V89.012H148.918l1.989-1.53L212.443,40.199z M432.619,237.168c15.775,0,28.559,12.788,28.559,28.559c0,15.771-12.787,28.56-28.559,28.56s-28.559-12.785-28.559-28.56 S416.848,237.168,432.619,237.168z M404.061,170.867c0-15.774,12.787-28.559,28.559-28.559s28.561,12.788,28.561,28.559 c0,15.771-12.789,28.559-28.561,28.559S404.061,186.642,404.061,170.867z M356.119,398.098H56.24V133.361h299.879V398.098z" />
                            </g>
                        </g>
                    </svg>
                    <span>Tv Show</span>
                </a>
            </li>

            <!-- Geo Blocking -->
            <li class="treeview" data-ng-if="checkAccess('geo_blocking.view')">
                <a href="javascript:void(0);"
                    class="{{ Request::is(['admin/geo-blocking/ip-restrictions', 'admin/geo-blocking/ip-restrictions/add', 'admin/geo-blocking/ip-restrictions/edit/*', 'admin/geo-blocking/geo-restrictions', 'admin/geo-blocking/geo-restrictions/add', 'admin/geo-blocking/geo-restrictions/edit/*']) ? 'active' : '' }}">
                    <svg width="16px" height="16px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" class="bi bi-geo-fill">
                        <path fill-rule="evenodd"
                            d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z" />
                    </svg>
                    <span>Geo Blocking</span>
                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                </a>

                <ul class="sub-menu treeview-menu">
                    <li>
                        <a href="{{ url('admin/geo-blocking/geo-restrictions') }}" {{ Request::is(['admin/geo-blocking/geo-restrictions', 'admin/geo-blocking/geo-restrictions', 'admin/geo-blocking/geo-restrictions/add', 'admin/geo-blocking/geo-restrictions/edit/*']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Geo Restrictions
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/geo-blocking/ip-restrictions') }}" {{ Request::is(['admin/geo-blocking/ip-restrictions', 'admin/geo-blocking/ip-restrictions', 'admin/geo-blocking/ip-restrictions/add', 'admin/geo-blocking/ip-restrictions/edit/*']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            IP Restrictions
                        </a>
                    </li>
                </ul>
            </li>

            <!-- manage categories -->
            <li class="treeview"
                data-ng-if="checkAccess('channel_categories.view') || checkAccess('currencies.view') || checkAccess('extensions.view')">
                <a href="javascript:void(0);"
                    class="{{ Request::is('admin/categories', 'admin/categories/*', 'admin/livecategory', 'admin/livecategory/*', 'admin/tvcategory', 'admin/tvcategory/*', 'admin/vodcategory', 'admin/vodcategory/*', 'admin/series-category', 'admin/series-category/*') ? 'active' : '' }}">
                    <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M27 22.141V18a2 2 0 0 0-2-2h-8v-4h2a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2v4H7a2 2 0 0 0-2 2v4.142a4 4 0 1 0 2 0V18h8v4.142a4 4 0 1 0 2 0V18h8v4.141a4 4 0 1 0 2 0M13 4h6l.001 6H13ZM8 26a2 2 0 1 1-2-2 2 2 0 0 1 2 2m10 0a2 2 0 1 1-2-2 2.003 2.003 0 0 1 2 2m8 2a2 2 0 1 1 2-2 2 2 0 0 1-2 2" />
                        <path data-name="&lt;Transparent Rectangle&gt;" style="fill:none" d="M0 0h32v32H0z" />
                    </svg>
                    <span>Manage Category</span>

                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                </a>

                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('channel_categories.view')">
                        <a href="{{ url('admin/tvcategory') }}" {{ Request::is('admin/tvcategory', 'admin/tvcategory/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            TV Categories
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('vod_categories.view')">
                        <a href="{{ url('admin/vodcategory') }}" {{ Request::is('admin/vodcategory', 'admin/vodcategory/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            VOD Categories
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('tv_show_categories.view')">
                        <a href="{{ url('admin/series-category') }}" {{ Request::is('admin/series-category', 'admin/series-category/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Series Categories
                        </a>
                    </li>
                </ul>
            </li>

            <!-- channel Services -->
            <li class="treeview"
                data-ng-if="checkAccess('catch_up_tv.view') || checkAccess('live_rewind.view') || checkAccess('epg_service.view')">
                <a href="javascript:void(0);"
                    class="{{ Request::is('admin/channel-services/catch-up-tv', 'admin/channel-services/live-rewind', 'admin/channel-services/epg-service') ? 'active' : '' }}">
                    <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000"
                            d="M864 409.6a192 192 0 01-37.888 349.44A256.064 256.064 0 01576 960h-96a32 32 0 110-64h96a192.064 192.064 0 00181.12-128H736a32 32 0 01-32-32V416a32 32 0 0132-32h32c10.368 0 20.544.832 30.528 2.432a288 288 0 00-573.056 0A193.235 193.235 0 01256 384h32a32 32 0 0132 32v320a32 32 0 01-32 32h-32a192 192 0 01-96-358.4 352 352 0 01704 0zM256 448a128 128 0 100 256V448zm640 128a128 128 0 00-128-128v256a128 128 0 00128-128z" />
                    </svg>

                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                    <span>Channels Services</span>
                </a>

                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('catch_up_tv.view')">
                        <a href="{{ url('admin/channel-services/catch-up-tv') }}" {{ Request::is('admin/channel-services/catch-up-tv') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Catch-up TV
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('live_rewind.view')">
                        <a href="{{ url('admin/channel-services/live-rewind') }}" {{ Request::is('admin/channel-services/live-rewind') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Live Rewind
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('epg_service.view')">
                        <a href="{{ url('admin/channel-services/epg-service') }}" {{ Request::is('admin/channel-services/epg-service') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            EPG Service
                        </a>
                    </li>
                </ul>
            </li>

            <!-- settings -->
            <li class="treeview"
                data-ng-if="checkAccess('general_settings.view') || checkAccess('payment_services.view') || checkAccess('currencies.view') || checkAccess('extensions.view')">
                <a href="javascript:void(0);"
                    class="{{ Request::is(['admin/settings/email-settings', 'admin/general/settings', 'admin/setting/payment-services/add', 'admin/settings/email-settings/add', 'admin/settings/email-settings/edit/*', 'admin/settings/payment-settings', 'admin/settings/payment-settings/add', 'admin/settings/payment-settings/edit/*', 'admin/setting/payment-services', 'admin/setting/payment-services/currency', 'admin/setting/payment-services/currency-converter', 'admin/setting/payment-services/edit/*', 'admin/setting/play-back-token', 'admin/setting/device-redirect', 'admin/general/email-settings', 'admin/dashboard-configuration']) ? 'active' : '' }}">
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                        <path
                            d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z" />
                    </svg>
                    <span>Settings</span>
                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                </a>
                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('general_settings.view')">
                        <a href="{{ url('admin/general/email-settings') }}" {{ Request::is(['admin/general/email-settings', 'admin/general/email-settings/add', 'admin/general/email-settings/edit/*']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            General Settings
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('general_settings.view')">
                        <a href="{{ url('admin/general/settings') }}" {{ Request::is('admin/general/settings') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            <span>
                                Subscriber Setting
                            </span>
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('payment_services.view')">
                        <a href="{{ url('admin/setting/payment-services') }}" {{ Request::is(['admin/setting/payment-services', 'admin/setting/payment-services/add', 'admin/setting/payment-services/currency-converter', 'admin/setting/payment-services/currency', 'admin/setting/payment-services/edit/*']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Payment Services
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('extensions.view')">
                        <a href="{{ url('admin/setting/play-back-token') }}" {{ Request::is(['admin/setting/play-back-token', 'admin/setting/device-redirect']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Extensions
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('general_settings.view')">
                        <a href="{{ url('admin/dashboard-configuration') }}" {{ Request::is(['admin/dashboard-configuration']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Dashboard Configuration
                        </a>
                    </li>

                    <!-- <li data-ng-if="checkAccess('general_settings.view')">
                        <a href="{{ url('admin/m3u-channel') }}" {{ Request::is(['admin/m3u-channel']) ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            M3U Channel
                        </a>
                    </li> -->
                </ul>
            </li>

            <!-- reports -->
            <li class="treeview"
                data-ng-if="checkAccess('subscriber_reports') || checkAccess('cps_reports') || checkAccess('activation_audit_reports')">
                <a href="javascript:void(0);"
                    class="{{ Request::is('admin/subscriber-reports', 'admin/actvation/generate-report', 'admin/subscriber/save-templates', 'admin/subscriber/generate', 'admin/cps-reports', 'admin/cps/save-templates', 'admin/activation-reports', 'admin/activation/save-templates', 'admin/actvation') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="800px" height="800px"
                        viewBox="0 0 36 36">
                        <rect x="6.48" y="18" width="5.76" height="11.52" rx="1" ry="1" />
                        <rect x="15.12" y="6.48" width="5.76" height="23.04" rx="1" ry="1" />
                        <rect x="23.76" y="14.16" width="5.76" height="15.36" rx="1" ry="1" />
                    </svg>

                    <span>
                        Reports
                    </span>

                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                </a>

                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('subscriber_reports')">
                        <a href="{{ url('admin/subscriber-reports') }}" {{ Request::is('admin/subscriber-reports') || Request::is('admin/subscriber/save-templates') || Request::is('admin/subscriber/generate') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Subscriber Reports
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('cps_reports')">
                        <a href="{{ url('admin/cps-reports') }}" {{ Request::is('admin/cps-reports') || Request::is('admin/cps/save-templates') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Cps Reports
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('activation_audit_reports')">
                        <a href="{{ url('admin/activation-reports') }}" {{ Request::is('admin/activation-reports', 'admin/actvation/generate-report', 'admin/activation/save-templates') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Activation Reports
                        </a>
                    </li>
                </ul>
            </li>

            <!-- api access -->
            <li class="treeview" data-ng-if="checkAccess('api_access.view')">
                <a href="{{ url('admin/api-access') }}" {{ Request::is('admin/api-access') || Request::is('admin/api-access/add') || Request::is('admin/api-access/edit/*') ? 'class=active' : '' }}>
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                        style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <path style="fill:#5A6B96;"
                            d="M409.52,270.851v17.538c0,19.221-15.578,34.8-34.789,34.8H45.052 c-19.211,0-34.789-15.578-34.789-34.8v-17.538H409.52z" />
                        <path style="fill:#EFEFEF;"
                            d="M409.52,72.428v198.423H10.262V72.428c0-19.211,15.578-34.789,34.789-34.789H374.73C393.942,37.638,409.52,53.217,409.52,72.428z" />
                        <path style="fill:#264087;"
                            d="M272.698,403.935c-41.87,0-83.74,0-125.61,0c9.795-26.75,14.199-54,14.45-80.749c32.236,0,64.472,0,96.708,0C258.5,349.936,262.902,377.185,272.698,403.935z" />
                        <g>
                            <path style="fill:#FBC640;"
                                d="M432.677,405.3h-44.7c-38.141,0-69.061,30.919-69.061,69.061l0,0h182.821l0,0C501.738,436.221,470.818,405.3,432.677,405.3z" />
                            <path style="fill:#FBC640;"
                                d="M410.327,414.376L410.327,414.376c-33.007,0-59.765-26.757-59.765-59.764v-31.2c0-33.007,26.757-59.764,59.764-59.764l0,0c33.007,0,59.764,26.757,59.764,59.764v31.2C470.092,387.619,443.333,414.376,410.327,414.376z" />
                        </g>
                        <g>
                            <path style="fill:#231F20;"
                                d="M368.475,68.426H246.688c-5.667,0-10.262,4.594-10.262,10.262c0,5.668,4.595,10.262,10.262,10.262h121.787c5.667,0,10.262-4.594,10.262-10.262C378.737,73.021,374.141,68.426,368.475,68.426z" />
                            <path style="fill:#231F20;"
                                d="M185.236,88.951h4.105c5.667,0,10.262-4.594,10.262-10.262c0-5.668-4.595-10.262-10.262-10.262h-4.105c-5.667,0-10.262,4.594-10.262,10.262C174.973,84.357,179.569,88.951,185.236,88.951z" />
                            <path style="fill:#231F20;"
                                d="M141.621,78.689c0-5.668-4.595-10.262-10.262-10.262H51.312c-5.667,0-10.262,4.594-10.262,10.262c0,5.668,4.595,10.262,10.262,10.262h80.046C137.025,88.951,141.621,84.356,141.621,78.689z" />
                            <path style="fill:#231F20;"
                                d="M324.56,177.482H178.046c-4.768-23.393-25.499-41.049-50.279-41.049c-28.293,0-51.312,23.018-51.312,51.312s23.019,51.312,51.312,51.312c24.78,0,45.512-17.656,50.279-41.049h97.768v30.787c0,5.668,4.595,10.262,10.262,10.262c5.667,0,10.262-4.594,10.262-10.262v-30.787h17.959v17.104c0,5.668,4.595,10.262,10.262,10.262c5.667,0,10.262-4.594,10.262-10.262v-27.367C334.822,182.076,330.227,177.482,324.56,177.482zM127.767,218.531c-16.976,0-30.787-13.811-30.787-30.787s13.811-30.787,30.787-30.787s30.787,13.811,30.787,30.787S144.743,218.531,127.767,218.531z" />
                            <path style="fill:#231F20;"
                                d="M462.736,400.983c10.949-12.361,17.616-28.598,17.616-46.371v-31.2c0-35.405-26.416-64.737-60.571-69.371V72.428c0-24.842-20.21-45.052-45.051-45.052H45.052C20.21,27.376,0,47.586,0,72.428v215.961c0,24.847,20.21,45.062,45.052,45.062h105.906c-1.08,20.814-4.832,40.999-11.177,60.222h-26.338c-5.667,0-10.262,4.594-10.262,10.262c0,5.668,4.595,10.262,10.262,10.262h192.902c5.667,0,10.262-4.594,10.262-10.262c0-5.668-4.595-10.262-10.262-10.262h-26.337c-6.345-19.224-10.098-39.41-11.177-60.222h71.47v21.161c0,17.772,6.667,34.009,17.616,46.371c-28.872,11.869-49.263,40.278-49.263,73.379c0,5.668,4.595,10.262,10.262,10.262h182.822c5.667,0,10.262-4.594,10.262-10.262C512,441.26,491.61,412.852,462.736,400.983z M459.828,323.411v31.2c0,27.296-22.207,49.502-49.502,49.502c-27.295,0-49.502-22.206-49.502-49.501v-31.2c0-27.296,22.207-49.502,49.502-49.502C437.621,273.911,459.828,296.117,459.828,323.411z M20.525,72.428c0-13.524,11.003-24.527,24.527-24.527H374.73c13.524,0,24.526,11.002,24.526,24.527v181.841c-7.007,1.118-13.667,3.281-19.824,6.32H20.525V72.428z M20.525,288.389v-7.276h334.041c-6.936,9.122-11.695,19.985-13.479,31.813H45.052C31.528,312.926,20.525,301.919,20.525,288.389z M258.476,393.673h-97.167c5.793-19.342,9.21-39.513,10.201-60.222h76.766C249.266,354.16,252.682,374.328,258.476,393.673z M330.075,464.099c4.271-24.169,23.359-43.287,47.51-47.613c9.778,5.195,20.92,8.151,32.742,8.151s22.964-2.957,32.742-8.151c24.15,4.326,43.237,23.444,47.51,47.613H330.075z" />
                        </g>
                    </svg>
                    <span>
                        {{ trans('base::general.api_access') }}
                    </span>
                </a>
            </li>

            <!-- stream services -->
            <li class="treeview" data-ng-if="checkAccess('stream_service.view')">
                <a href="javascript:void(0);"
                    class="{{ Request::is('admin/stream-services') || Request::is('admin/stream-services/stream-settings') || Request::is('admin/stream-services/streaming-url-policy') || Request::is('admin/stream-services/streaming-url-policy/add') || Request::is('admin/stream-services/streaming-url-policy/edit/*') || Request::is('admin/stream-services/stream-settings/add') || Request::is('admin/stream-services/stream-settings/edit/*') ? 'active' : '' }}">
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17 10.5V7C17 6.73478 16.8946 6.48043 16.7071 6.29289C16.5196 6.10536 16.2652 6 16 6H4C3.73478 6 3.48043 6.10536 3.29289 6.29289C3.10536 6.48043 3 6.73478 3 7V17C3 17.2652 3.10536 17.5196 3.29289 17.7071C3.48043 17.8946 3.73478 18 4 18H16C16.2652 18 16.5196 17.8946 16.7071 17.7071C16.8946 17.5196 17 17.2652 17 17V13.5L21 17.5V6.5L17 10.5ZM8 15V9L12.5 12L8 15Z" />
                    </svg>
                    <span>{{ trans('base::general.stream_services') }}</span>
                    <svg class="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 6" width="5"
                        height="6">
                        <g>
                            <path d="M4.9302 2.9155L0.2527 0.0455C0.2032 0.0154 0.1389 0.0122 0.0861 0.0368
                     C0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637V5.9035
                     C-0.0002 5.9567 0.033 6.0058 0.0861 6.0304
                     C0.1096 6.0415 0.1354 6.0468 0.1611 6.0468
                     C0.1932 6.0468 0.2252 6.0383 0.2527 6.0215L4.9302 3.1517
                     C4.9739 3.1248 4.9998 3.0809 4.9998 3.0334
                     C4.9998 2.9865 4.9736 2.9422 4.9302 2.9155Z" />
                        </g>
                    </svg>
                </a>

                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('stream_service.view')">
                        <a href="{{ url('admin/stream-services/stream-settings') }}" {{ Request::is('admin/stream-services/stream-settings') || Request::is('admin/stream-services/stream-settings/add') || Request::is('admin/stream-services/stream-settings/edit/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ trans('base::general.stream_setting') }}
                        </a>
                    </li>

                    <li data-ng-if="checkAccess('stream_service.view')">
                        <a href="{{ url('admin/stream-services/streaming-url-policy') }}" {{ Request::is('admin/stream-services/streaming-url-policy') || Request::is('admin/stream-services/streaming-url-policy/add') || Request::is('admin/stream-services/streaming-url-policy/edit/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ trans('base::general.streaming_url_policy') }}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- partner program -->
            <li class="treeview">
                <a href="{{ url('admin/partner-programs') }}" {{ Request::is('admin/partner-programs') || Request::is('admin/partner-programs/add') || Request::is('admin/partner-programs/edit/*') ? 'class=active' : '' }}>
                    <svg width="32px" height="32px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" id="Layer_3"
                        version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g>
                            <path
                                d="M27.251,11.303C28.305,10.582,29,9.371,29,8V5c0-0.552-0.448-1-1-1h-6c-0.552,0-1,0.448-1,1v3   c0,1.371,0.695,2.582,1.749,3.303C21.144,11.93,20,13.484,20,15.308v0.774c-0.708-0.411-1.481-0.719-2.305-0.898   C19.052,14.544,20,13.173,20,11.576v-3c0-0.552-0.448-1-1-1h-6c-0.552,0-1,0.448-1,1v3c0,1.597,0.948,2.969,2.305,3.609   c-0.823,0.178-1.597,0.487-2.305,0.898v-0.774c0-1.824-1.144-3.378-2.749-4.005C10.305,10.582,11,9.371,11,8V5c0-0.552-0.448-1-1-1   H4C3.448,4,3,4.448,3,5v3c0,1.371,0.695,2.582,1.749,3.303C3.144,11.93,2,13.484,2,15.308V20c0,0.552,0.448,1,1,1h5.263   C8.097,21.641,8,22.308,8,23v4c0,0.552,0.448,1,1,1h14c0.552,0,1-0.448,1-1v-4c0-0.692-0.097-1.359-0.263-2H29c0.552,0,1-0.448,1-1   v-4.692C30,13.484,28.856,11.93,27.251,11.303z M23,6h4v2c0,1.103-0.897,2-2,2s-2-0.897-2-2V6z M14,9.576h4v2c0,1.103-0.897,2-2,2   s-2-0.897-2-2V9.576z M5,6h4v2c0,1.103-0.897,2-2,2S5,9.103,5,8V6z M9,19H4v-3.692c0-1.167,0.874-2.125,2-2.277V16   c0,0.552,0.448,1,1,1s1-0.448,1-1v-2.969c1.126,0.152,2,1.11,2,2.277v2.418c-0.35,0.397-0.66,0.828-0.927,1.289   C9.048,19.013,9.026,19,9,19z M22,26H10v-3c0-2.967,2.167-5.431,5-5.91V20c0,0.552,0.448,1,1,1s1-0.448,1-1v-2.91   c2.833,0.478,5,2.942,5,5.91V26z M28,19h-5c-0.026,0-0.048,0.013-0.073,0.015C22.66,18.554,22.35,18.123,22,17.726v-2.418   c0-1.167,0.874-2.125,2-2.277V16c0,0.552,0.448,1,1,1s1-0.448,1-1v-2.969c1.126,0.152,2,1.11,2,2.277V19z" />
                        </g>
                    </svg>
                    <span>
                        {{ trans('base::general.partner_programs') }}
                    </span>
                </a>
            </li>

            <!-- Devices -->
            <li class="treeview">
                <a href="{{ url('admin/divice') }}" {{ Request::is('admin/divice') || Request::is('admin/divice/add') || Request::is('admin/divice/edit/*') ? 'class=active' : '' }}>
                    <svg fill="#000000" width="12px" height="12px" viewBox="0 0 36 36" version="1.1"
                        preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>devices-solid</title>
                        <path class="clr-i-solid clr-i-solid-path-1"
                            d="M32,13H24a2,2,0,0,0-2,2V30a2,2,0,0,0,2,2h8a2,2,0,0,0,2-2V15A2,2,0,0,0,32,13Zm0,2V28H24V15Z">
                        </path>
                        <path class="clr-i-solid clr-i-solid-path-2"
                            d="M28,4H4A2,2,0,0,0,2,6V22a2,2,0,0,0,2,2h8v2H9.32A1.2,1.2,0,0,0,8,27a1.2,1.2,0,0,0,1.32,1H19.92v-.37H20V22H4V6H28v5h2V6A2,2,0,0,0,28,4Z">
                        </path>
                        <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                    </svg>
                    <span>
                        {{ trans('base::general.device') }}
                    </span>
                </a>
            </li>

            <!-- manage user -->
            <li class="treeview" data-ng-if="checkAccess('system_users.view') || checkAccess('permission_rules.view') ">
                <a href="#" {{ Request::is('admin/users', 'admin/permission-rules') || Request::is('admin/customer') || Request::is('admin/system-user') || Request::is('admin/groups') || Request::is('admin/groups/add') || Request::is('admin/groups/edit/*') ? 'class=active' : '' }}>

                    <svg viewBox="0 0 16 17" version="1.1" x="0px" y="0px">
                        <g>
                            <path
                                d="M 14.6428 7.6448 L 14.6428 9.5095 L 15.8857 11.2088 C 16.0246 11.3984 16.0383 11.6524 15.9208 11.856 L 14.7966 13.803 C 14.6791 14.0063 14.4524 14.1215 14.2188 14.0961 L 12.1296 13.8685 L 10.5144 14.8015 L 9.6668 16.7257 C 9.572 16.9409 9.359 17.0796 9.1239 17.0796 L 6.8758 17.0796 C 6.6407 17.0796 6.4279 16.9409 6.3331 16.7257 L 5.4853 14.8015 L 3.8703 13.8685 L 1.781 14.0961 C 1.5475 14.1215 1.3208 14.0063 1.2033 13.8027 L 0.0791 11.8557 C -0.0385 11.6524 -0.0248 11.3984 0.1141 11.2088 L 1.357 9.5095 L 1.357 7.6448 L 0.1139 5.9454 C -0.0248 5.7559 -0.0385 5.502 0.0791 5.2986 L 1.2033 3.3515 C 1.3206 3.1478 1.5473 3.033 1.781 3.0585 L 3.8702 3.2857 L 5.4853 2.353 L 6.3331 0.4287 C 6.4279 0.2135 6.6408 0.0746 6.8758 0.0746 L 9.1239 0.0746 C 9.359 0.0746 9.572 0.2135 9.6667 0.4287 L 10.5144 2.353 L 12.1294 3.2857 L 14.2187 3.0583 C 14.4523 3.033 14.6788 3.1478 14.7965 3.3515 L 15.9208 5.2986 C 16.0383 5.502 16.0246 5.7559 15.8857 5.9454 L 14.6428 7.6448 ZM 13.1893 7.1963 L 14.2443 5.7527 L 13.5553 4.5587 L 11.782 4.7515 C 11.665 4.7644 11.5468 4.7397 11.4448 4.6804 L 9.6218 3.6268 C 9.5198 3.5678 9.4392 3.478 9.3917 3.37 L 8.6722 1.7355 L 7.2942 1.7355 L 6.5748 3.37 C 6.5273 3.478 6.4467 3.5678 6.3448 3.6268 L 4.5217 4.6804 C 4.4196 4.7397 4.3016 4.7644 4.1846 4.7515 L 2.4113 4.5587 L 1.7221 5.7527 L 2.7772 7.1963 C 2.8468 7.2914 2.8842 7.4062 2.8842 7.5242 L 2.8842 9.6303 C 2.8842 9.748 2.8468 9.863 2.7772 9.9581 L 1.7221 11.4015 L 2.4112 12.5959 L 4.1846 12.4027 C 4.3016 12.3901 4.4196 12.4148 4.5217 12.4736 L 6.3448 13.5277 C 6.4467 13.5865 6.5273 13.6765 6.5748 13.7842 L 7.2942 15.4188 L 8.6722 15.4188 L 9.3917 13.7842 C 9.4392 13.6765 9.5198 13.5865 9.6218 13.5277 L 11.4448 12.4736 C 11.5468 12.4148 11.665 12.3899 11.782 12.4027 L 13.5553 12.5959 L 14.2443 11.4015 L 13.1893 9.9581 C 13.1198 9.863 13.0823 9.748 13.0823 9.6303 L 13.0823 7.5242 C 13.0823 7.4062 13.1198 7.2914 13.1893 7.1963 ZM 9.819 11.6664 L 6.1143 11.6664 C 5.8162 11.6664 5.5748 11.425 5.5748 11.1273 L 5.5748 9.2796 C 5.5748 8.5744 6.1489 8.0006 6.8546 8.0006 L 9.0785 8.0006 C 9.7843 8.0006 10.3584 8.5744 10.3584 9.2796 L 10.3584 11.1273 C 10.3584 11.425 10.1168 11.6664 9.819 11.6664 ZM 7.9846 7.9648 C 7.0723 7.9648 6.3301 7.2231 6.3301 6.3115 C 6.3301 5.3999 7.0723 4.6582 7.9846 4.6582 C 8.8968 4.6582 9.6391 5.3999 9.6391 6.3115 C 9.6391 7.2231 8.8968 7.9648 7.9846 7.9648 Z" />
                        </g>
                    </svg>

                    <span>{{ __('base::general.manage_users') }}</span>
                    <svg class="sidebar-toggle-icon" viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px"
                        height="6px">
                        <g>
                            <path
                                d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                        </g>
                    </svg>
                </a>
                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('system_users.view')">
                        <a href="{{ url('admin/system-user') }}" {{ Request::is('admin/system-user') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            System Users
                        </a>
                    </li>

                    <!-- permission rules -->
                    <li data-ng-if="checkAccess('permission_rules.view')">
                        <a href="{{ url('admin/permission-rules') }}" {{ Request::is('admin/permission-rules') || Request::is('admin/permission-rules/add') || Request::is('admin/permission-rules/edit/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ trans('base::general.permission_rules') }}
                        </a>
                    </li>

                    <!-- <li data-ng-if="checkAccess('admin_user_all')">
                        <a href="{{ url('admin/users') }}" {{ Request::is('admin/users') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ __('base::general.admins') }}
                        </a>
                    </li> -->
                    <!-- <li data-ng-if="checkAccess('usergroup_all')">
                        <a href="{{ url('admin/groups') }}" {{ Request::is('admin/groups') || Request::is('admin/groups/add') || Request::is('admin/groups/edit/*') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ __('base::general.admin_groups') }}
                        </a>
                    </li> -->

                </ul>
            </li>

            <!-- M3U Import -->
            <li class="treeview">
                <a href="{{ url('admin/m3u-channel') }}" {{ Request::is('admin/m3u-channel', 'admin/m3u-vod', 'admin/m3u-tvshow') ? 'class=active' : '' }}>
                    <svg width="800px" height="800px" viewBox="0 0 23 23" id="meteor-icon-kit__regular-bulk-edit"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 18H8C6.34315 18 5 16.6569 5 15V13H3C1.34315 13 0 11.6569 0 10V3C0 1.34315 1.34315 0 3 0H10C11.6569 0 13 1.34315 13 3V5H15C16.6569 5 18 6.34315 18 8V10H20C21.6569 10 23 11.3431 23 13V20C23 21.6569 21.6569 23 20 23H13C11.3431 23 10 21.6569 10 20V18ZM10 16V13C10 11.3431 11.3431 10 13 10H16V8C16 7.44772 15.5523 7 15 7H8C7.44772 7 7 7.44772 7 8V15C7 15.5523 7.44772 16 8 16H10ZM5 11V8C5 6.34315 6.34315 5 8 5H11V3C11 2.44772 10.5523 2 10 2H3C2.44772 2 2 2.44772 2 3V10C2 10.5523 2.44772 11 3 11H5ZM13 12C12.4477 12 12 12.4477 12 13V20C12 20.5523 12.4477 21 13 21H20C20.5523 21 21 20.5523 21 20V13C21 12.4477 20.5523 12 20 12H13Z"
                            fill="#758CA3" />
                    </svg>
                    <span>M3U Import</span>
                    <svg class="sidebar-toggle-icon" viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px"
                        height="6px">
                        <g>
                            <path
                                d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                        </g>
                    </svg>
                </a>
                <ul class="sub-menu treeview-menu">
                    <li>
                        <a href="{{ url('admin/m3u-channel') }}" {{ Request::is('admin/m3u-channel') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Channel Upload
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/m3u-vod') }}" {{ Request::is('admin/m3u-vod') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Vod Upload
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/m3u-tvshow') }}" {{ Request::is('admin/m3u-tvshow') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            Tv Show Upload
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Site Configuration -->
            <li class="treeview" data-ng-if="checkAccess('site_configurations')">
                <a href="{{ url('admin/banner') }}" {{ Request::is('admin/settings') || (Request::is('admin/emails*') || Request::is('admin/static-content*') || Request::is('admin/banner') || Request::is('admin/kidsbanner') || Request::is('admin/landingbanner') || Request::is('admin/home-footer-banner') || Request::is('admin/livebanner')) ? 'class=active' : '' }}>
                    <svg viewBox="0 0 16 16" version="1.1" x="0px" y="0px">
                        <g>
                            <path
                                d="M 6.1018 0.1552 L 5.7013 2.1744 C 5.0333 2.4227 4.4158 2.7698 3.8735 3.2076 L 1.9009 2.5386 L 0.0031 5.774 L 1.5815 7.1228 C 1.5255 7.4603 1.4892 7.8042 1.4892 8.1574 C 1.4892 8.5107 1.5255 8.8542 1.5815 9.1921 L 0.0031 10.541 L 1.9009 13.7764 L 3.8735 13.1073 C 4.4158 13.5451 5.0333 13.8918 5.7013 14.1404 L 6.1018 16.1597 L 9.8976 16.1597 L 10.2981 14.1404 C 10.966 13.8918 11.5837 13.5451 12.126 13.1073 L 14.0986 13.7764 L 15.9964 10.541 L 14.418 9.1921 C 14.4739 8.8542 14.5102 8.5107 14.5102 8.1574 C 14.5102 7.8042 14.4739 7.4603 14.418 7.1228 L 15.9964 5.774 L 14.0986 2.5386 L 12.126 3.2076 C 11.5837 2.7698 10.966 2.4227 10.2981 2.1744 L 9.8976 0.1552 L 6.1018 0.1552 ZM 7.9997 4.1406 C 10.2123 4.1406 12.0061 5.9392 12.0061 8.1574 C 12.0061 10.3757 10.2123 12.1743 7.9997 12.1743 C 5.7872 12.1743 3.9932 10.3757 3.9932 8.1574 C 3.9932 5.9392 5.7872 4.1406 7.9997 4.1406 Z" />
                        </g>
                    </svg>
                    <span>{{ trans('base::general.site_configuration') }}</span>
                    <svg class="sidebar-toggle-icon" viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px"
                        height="6px">
                        <g>
                            <path
                                d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                        </g>
                    </svg>
                </a>
                <ul class="sub-menu treeview-menu">
                    <li data-ng-if="checkAccess('site_configurations')">
                        <a href="{{ url('admin/settings') }}" {{ Request::is('admin/settings') ? 'class=active' : '' }}>
                            <svg viewBox="0 0 5 6" version="1.1" x="0px" y="0px" width="5px" height="6px">
                                <g>
                                    <path
                                        d="M 4.9302 2.9155 L 0.2527 0.0455 C 0.2032 0.0154 0.1389 0.0122 0.0861 0.0368 C 0.033 0.0615 -0.0002 0.1105 -0.0002 0.1637 L -0.0002 5.9035 C -0.0002 5.9567 0.033 6.0058 0.0861 6.0304 C 0.1096 6.0415 0.1354 6.0468 0.1611 6.0468 C 0.1932 6.0468 0.2252 6.0383 0.2527 6.0215 L 4.9302 3.1517 C 4.9739 3.1248 4.9998 3.0809 4.9998 3.0334 C 4.9998 2.9865 4.9736 2.9422 4.9302 2.9155 Z" />
                                </g>
                            </svg>
                            {{ trans('base::general.site_settings') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>