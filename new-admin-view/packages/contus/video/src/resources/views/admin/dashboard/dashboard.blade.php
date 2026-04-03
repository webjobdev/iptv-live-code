@extends('base::layouts.default')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('adminview/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css">

    <style>
        .nav.nav-tabs {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 2px solid #ddd;
            padding-left: 0;
            margin-bottom: 1rem;
        }

        /* Tab items */
        .nav.nav-tabs li {
            margin: 0;
            list-style: none;
        }

        .nav.nav-tabs li a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            color: #000;
            border: 1px solid transparent;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
        }

        /* Active tab */
        .nav.nav-tabs li.active a,
        .nav.nav-tabs li a:hover {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-bottom: 2px solid #00ACCD;
            color: #00ACCD !important;
        }

        /* SVG icons should align with text */
        .nav.nav-tabs li a svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .nav-tabs .tab-item.active a {
            border-bottom: 3px solid #e74c3c;
            font-weight: bold;
            color: #e74c3c !important;
        }

        .nav-tabs .tab-itemmm.active a {
            border-bottom: 3px solid #e74c3c;
            font-weight: bold;
            color: #e74c3c !important;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .nav.nav-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                white-space: nowrap;
                border-bottom: none;
            }

            .nav.nav-tabs li {
                flex: 0 0 auto;
                margin-right: 6px;
            }

            .nav.nav-tabs li a {
                border-radius: 6px;
                border: 1px solid #ddd;
            }

            .nav.nav-tabs li.active a {
                border-bottom: 1px solid #00ACCD;
            }
        }

        @media (max-width: 576px) {
            .nav.nav-tabs {
                flex-direction: column;
            }

            .nav.nav-tabs li {
                width: 100%;
                margin: 4px 0;
            }

            .nav.nav-tabs li a {
                width: 100%;
                justify-content: flex-start;
                border-radius: 6px;
            }
        }

        #chart {
            display: flex;
            justify-content: center;
        }

        .text-success {
            color: green;
            font-weight: 600;
        }

        .text-danger {
            color: red;
            font-weight: 600;
        }
    </style>
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
    <div data-ng-controller="DashboardController as dashCtrl">

        <div class="dashboard-page " id="dashboard-page">

            <div class="page-heading flexbox d-flex align-items-right">
                <h4>{{ __('video::dashboard.Dashboard') }}</h4>

                @if (app('request')->has('error'))
                    <div class="alert alert-danger" style="width:100%">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span>You were not authorized to view the last page.</span>
                    </div>
                @endif

                <div class="right-side flexbox align-items-center">
                    <a href="javascript:void(0)" class="video-demo-ancher" ng-click="showDemoVideo = !showDemoVideo">
                        <div class="hotspot__positioner--01">
                            <div class="hotspot__container">
                                <div class="hotspot hotspot--01"></div>
                                <div class="hotspot hotspot--02"></div>
                                <div class="hotspot hotspot--03"></div>
                            </div>
                        </div>
                        <svg viewBox="0 0 47 48" version="1.1" x="0px" y="0px" width="55px" height="55px" class="demo-ic">
                            <g>
                                <path
                                    d="M 31.4865 29.034 C 31.0336 29.034 28.1232 26.3365 28.1232 25.9814 L 28.1232 22.4468 C 28.1232 22.0919 30.969 19.3943 31.4865 19.3943 C 32.0038 19.3943 32.0038 19.8762 32.0038 19.8762 L 32.0038 28.552 C 32.0038 28.552 31.9392 29.034 31.4865 29.034 ZM 26.8619 28.3109 L 16.7722 28.3109 C 16.3435 28.3109 15.9961 28.0664 15.9961 27.7648 L 15.9961 20.6636 C 15.9961 20.3619 16.3435 20.1172 16.7722 20.1172 L 26.8619 20.1172 C 27.2906 20.1172 27.6381 20.3619 27.6381 20.6636 L 27.6381 27.7648 C 27.6381 28.0664 27.2906 28.3109 26.8619 28.3109 ZM 24.2424 19.8419 C 22.903 19.8419 21.817 18.7628 21.817 17.4319 C 21.817 16.1009 22.903 15.0219 24.2424 15.0219 C 25.582 15.0219 26.6678 16.1009 26.6678 17.4319 C 26.6678 18.7628 25.582 19.8419 24.2424 19.8419 ZM 18.9065 19.8419 C 17.835 19.8419 16.9662 18.9786 16.9662 17.9139 C 16.9662 16.8491 17.835 15.986 18.9065 15.986 C 19.9781 15.986 20.8469 16.8491 20.8469 17.9139 C 20.8469 18.9786 19.9781 19.8419 18.9065 19.8419 Z"
                                    fill="#ffffff">
                                </path>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>

            @if (isset($_GET['permission']))
                <div class="alert alert-danger">You are not authorized to perform this action. Please contact your admin.
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">{{ implode('', $errors->all(':message')) }}</div>
            @endif

            <!-- Overview start -->
            <div class="dashbord-section dashboard-overview">
                <div class="card">
                    <div class="card-heading flexbox align-items-center">
                        <h3>Welcome To IPTV SOLUTION GROUP</h3>
                        <div class="dashboard-select">
                            <span>PID: </span>123<br>
                            <span>ISG: </span>1.02
                        </div>
                    </div>
                </div>
            </div>
            <!-- Overview end -->

            <!-- Your Subscribers metrics -->
            <div class="dashbord-section flexbox flex-wrap">
                <!-- Revenue Stacks start -->
                <div class="dashbord-section-grid flexbox width-50">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Active Subscriber Devices</h3>
                            <div class="dashboard-select">
                            </div>
                        </div>

                        <div class="card-content graph">
                            <!-- <div id="users-chart" style="height: 350px; width: 100%;"></div> -->
                            <div id="map" style="height: 350px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <!-- Revenue Stacks end -->

                <!-- Subscribed Users start -->
                <div class="dashbord-section-grid flexbox width-50" ng-if="dashCtrl.showSubscriberData">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Top 5 Countries</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path
                                            d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z"
                                            fill="#43515d" />
                                    </g>
                                </svg>
                                <select minimumResults="-1" data-jquery="select2_custom_ddl" class="select2_custom_ddl"
                                    style="width:100px" ng-model="dashCtrl.topCountrySelected"
                                    ng-change="dashCtrl.updateTopCountries()">
                                    <option value="5">Top 5</option>
                                    <option value="10">Top 10</option>
                                    <option value="20">Top 20</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-content graph">
                            <!-- <div id="users-subscriber-chart" style="height: 350px; width: 100%;"></div> -->
                            <div style="height: 350px; width: 100%;">
                                <canvas id="horizontal-bar-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Subscribed Users -->
            </div>
            <!-- Your Subscribers metrics -->

            <!-- Total Devices -->
            <div class="dashbord-section flexbox flex-wrap">
                <!-- Platform Based Video View Count -->
                <div class="dashbord-section-grid flexbox width-50" data-ng-if="dashCtrl.showSubscriberData">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Your Subscribers metrics</h3>
                            <div class="dashboard-select">
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>List</th>
                                            <th class="center">{{ __('video::dashboard.count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text"
                                                        style="font-size: 14px;color: #000;margin-top: 10px;font-weight: 700;">
                                                        Total count of subscribers
                                                    </div>
                                                </div>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        Total count of subscriber in your database
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="center">
                                                @{{ dashCtrl.metricsList.total_subscribers }}
                                            </td>
                                            <!-- <td class="center" data-ng-if="!dashCtrl.metricsList[0]">0</td> -->
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text"
                                                        style="font-size: 14px;color: #000;margin-top: 10px;font-weight: 700;">
                                                        Active subscribers
                                                    </div>
                                                </div>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        Total count of subscriber active in your database
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="center">
                                                @{{ dashCtrl.metricsList.active_subscribers }}
                                            </td>
                                            <!-- <td class="center" data-ng-if="!dashCtrl.metricsList[0]">0</td> -->
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text"
                                                        style="font-size: 14px;color: #000;margin-top: 10px;font-weight: 700;">
                                                        Expired subscribers
                                                    </div>
                                                </div>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        Total count of expired subscriber in your database
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="center">
                                                @{{ dashCtrl.metricsList.expired_subscribers }}
                                            </td>
                                            <!-- <td class="center" data-ng-if="!dashCtrl.metricsList[0]">0 -->
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text"
                                                        style="font-size: 14px;color: #000;margin-top: 10px;font-weight: 700;">
                                                        Inactive subscribers
                                                    </div>
                                                </div>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        Total count of subscriber who have not accepted TOA, existing in
                                                        your database
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="center">
                                                @{{ dashCtrl.metricsList.inactive_subscribers }}
                                            </td>
                                            <!-- <td class="center" data-ng-if="!dashCtrl.metricsList[0]">0</td> -->
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text"
                                                        style="font-size: 14px;color: #000;margin-top: 10px;font-weight: 700;">
                                                        New subscribers
                                                    </div>
                                                </div>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        Total count of new subscriber in your database over the last 7 days
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="center">
                                                @{{ dashCtrl.metricsList.new_subscribers }}
                                            </td>
                                            <!-- <td class="center" data-ng-if="!dashCtrl.metricsList[0]">0</td> -->
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ url('admin/analytics/region-wise-view') }}"
                                data-ng-if="dashCtrl.regionwise_analytics.next_page_url"
                                class="viewall">{{ __('video::dashboard.view_all') }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Platform Based Video View Count -->

                <!-- Geographic Wise View -->
                <div class="dashbord-section-grid flexbox width-50">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Total Devices</h3>
                            <!-- <div class="dashboard-select">
                                                                                            <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                                                                                <g>
                                                                                                    <path
                                                                                                        d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z"
                                                                                                        fill="#43515d" />
                                                                                                </g>
                                                                                            </svg>
                                                                                            <select minimumResults="-1" data-jquery="select2_custom_ddl" class="select2_custom_ddl"
                                                                                                style="width:100px" ng-model="dashCtrl.regionwiseDateValue"
                                                                                                ng-change="dashCtrl.applyDateFilter('regionwise',dashCtrl.regionwiseDateValue)"
                                                                                                data-ng-options="item.id as item.name for item in dashCtrl.dropdown">
                                                                                            </select>
                                                                                        </div> -->
                        </div>

                        <div class="card-content">
                            <div class="table-responsive">
                                <div id="chart" style="height: 350px; width: 100%;"></div>
                            </div>
                            <a href="{{ url('admin/analytics/region-wise-view') }}"
                                data-ng-if="dashCtrl.regionwise_analytics.next_page_url"
                                class="viewall">{{ __('video::dashboard.view_all') }}</a>
                        </div>
                    </div>
                </div>
                <!-- Geographic Wise View -->
            </div>
            <!-- Total Devices -->

            <!-- available content -->
            <div class="dashbord-section dashboard-overview">
                <div class="card">
                    <div class="card-heading flexbox align-items-center">
                        <h3>Available Content</h3>
                    </div>

                    <div class="flexbox flex-wrap overview-wrapper" style="justify-content: space-evenly;">
                        <!-- tv show -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid revenue-stacks flexbox align-items-center"
                                style="background: linear-gradient(to right, #243949 0%, #517fa4 100%);">
                                <div class="content">
                                    <h3>Tv Shows</h3>
                                    <h4>
                                        <!-- <i class="dollar">{{ getCurrency() }} </i> -->
                                        @{{ dashCtrl.contetList.total_tv_show }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- movie -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid total-watched-videos flexbox align-items-center"
                                style="background: linear-gradient(to right, #868f96 0%, #596164 100%);">
                                <div class="content">
                                    <h3>Movies</h3>
                                    <h4>
                                        @{{ dashCtrl.contetList.total_movie }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- live event -->
                        <div class="counts-list flexbox">
                            <a href="{{ url('admin/customer') }}" class="counts-list-a flexbox">
                                <div class="card counts-list-grid registered-users flexbox align-items-center"
                                    style="background:  linear-gradient(to right, #536976, #bbd2c5);">
                                    <div class="content">
                                        <h3>Live Event</h3>
                                        <h4>@{{ dashCtrl.contetList.total_live_event }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="flexbox flex-wrap overview-wrapper" style="justify-content: space-evenly;">
                        <!-- channel -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid active-subscribers flexbox align-items-center"
                                style="background: linear-gradient(to top, #48c6ef 0%, #6f86d6 100%);">

                                <div class="content">
                                    <h3>Channels</h3>
                                    <h4>@{{ dashCtrl.contetList.total_channel }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- catch-up -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid active-subscribers flexbox align-items-center"
                                style="background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);">

                                <div class="content">
                                    <h3>Catch-ups</h3>
                                    <h4>@{{ dashCtrl.contetList.total_catch_up }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- live-rewind -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid active-subscribers flexbox align-items-center"
                                style="background: linear-gradient(to right, rgba(58, 209, 189, 1) 0%, rgba(17, 155, 210, 1) 100%)">

                                <div class="content">
                                    <h3>Live Rewind</h3>
                                    <h4>@{{ dashCtrl.contetList.total_live_rewind }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- available content -->

            <!-- EPG  -->
            <div class="dashbord-section flexbox flex-wrap">
                <div class="dashbord-section-grid flexbox width-50" style="width: 100%">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>EPG</h3>
                        </div>

                        <div class="card-content">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Epg List</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-ng-if="dashCtrl.latestVideos.data.length == 0">
                                            <td colspan="7" class="no-data center">
                                                {{ trans('base::general.not_found') }}
                                            </td>
                                        </tr>
                                        <tr data-ng-if="dashCtrl.latestVideos.data.length > 0"
                                            data-ng-repeat="record in dashCtrl.EpgList">
                                            <td>
                                                <div class="table-image-text flexbox align-items-center">
                                                    <div class="text">
                                                        <a ng-if="record.is_active==0"
                                                            class="table-image-text flexbox align-items-center">
                                                            @{{ record.task_name }}
                                                        </a>
                                                        <a ng-if="record.is_active==1"
                                                            class="table-image-text flexbox align-items-center">
                                                            @{{ record.task_name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>

                                            <td ng-class="record.is_active == '1' ? 'text-success' : 'text-danger'">
                                                <i class="fa"
                                                    ng-class="record.is_active == '1' ? 'fa-check-circle' : 'fa-times-circle'"></i>
                                                @{{ record.is_active == '1' ? 'Ok' : 'Failed' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ url('admin/analytics/most-viewed') }}"
                                data-ng-if="dashCtrl.latestVideos.next_page_url"
                                class="viewall">{{ __('video::dashboard.view_all') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- EPG -->

            <!-- Stream -->
            <div class="dashbord-section dashboard-overview">
                <div class="card">
                    <div class="card-heading flexbox align-items-center">
                        <h3>Streams</h3>
                    </div>

                    <div class="flexbox flex-wrap overview-wrapper" style="justify-content: space-evenly;">
                        <!-- tv show -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid revenue-stacks flexbox align-items-center"
                                style="background: #d7d7d778">
                                <div class="content"
                                    style="display: flex; justify-content: space-between; height: 5rem; align-items: center;">
                                    <h4 style="color: black;">
                                        @{{ dashCtrl.StreamList.enabled_stream }}
                                    </h4>
                                    <h3 style="color: black;">Enabled</h3>
                                </div>
                            </div>
                        </div>

                        <!-- movie -->
                        <div class="counts-list flexbox">
                            <div class="card counts-list-grid total-watched-videos flexbox align-items-center"
                                style="background: #d7d7d778">
                                <div class="content"
                                    style="display: flex; justify-content: space-between; height: 5rem; align-items: center;">
                                    <h4 style="color: black;">
                                        @{{ dashCtrl.StreamList.restarted || '0' }}
                                    </h4>
                                    <h3 style="color: black;">Restarted</h3>
                                </div>
                            </div>
                        </div>

                        <!-- live event -->
                        <div class="counts-list flexbox">
                            <a href="{{ url('admin/customer') }}" class="counts-list-a flexbox">
                                <div class="card counts-list-grid registered-users flexbox align-items-center"
                                    style="background: #d7d7d778">
                                    <div class="content"
                                        style="display: flex; justify-content: space-between; height: 5rem; align-items: center;">
                                        <h4 style="color: black;">
                                            @{{ dashCtrl.StreamList.disabled_stream }}
                                        </h4>
                                        <h3 style="color: black;">Disabled</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stream -->

            <!-- Daily Sales Revenue  -->
            <div class="dashbord-section flexbox flex-wrap">
                <!-- based on total payments amount -->
                <div class="dashbord-section-grid flexbox width-50" ng-if="dashCtrl.showLineChart">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Daily Sales Revenue (Total Payments)</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path
                                            d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z"
                                            fill="#43515d" />
                                    </g>
                                </svg>
                                <select minimumResults="-1" data-jquery="select2_custom_ddl" class="select2_custom_ddl"
                                    style="width:100px" ng-model="dashCtrl.selectedPeriod"
                                    ng-init="dashCtrl.selectedPeriod = '7'"
                                    ng-change="dashCtrl.changeTotalRevenuePeriod(dashCtrl.selectedPeriod)">
                                    <option value="7">Last 7 Days</option>
                                    <option value="14">Last 14 Days</option>
                                    <option value="31">Last 31 Days</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-content graph">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active">
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'ALL')">All</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'INR')">INR</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'USD')">USD</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'THB')">THB</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'LAK')">LAK</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'BOB')">BOB</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="updateChart($event, 'EUR')">EUR</a>
                                </li>
                            </ul>
                            <!-- <canvas id="line-chart" style="height: 350px; width: 100%;"></canvas> -->
                            <!-- <canvas id="line-chart" width="600" height="300"></canvas> -->
                            <!-- <div style="height: 600; width: 300;"> -->
                            <canvas id="line-chart" width="600" height="300"></canvas>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <!-- based on total payments amount -->

                <!-- based on payment system types -->
                <div class="dashbord-section-grid flexbox width-50" ng-if="dashCtrl.showLineChart">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Daily Sales Revenue (payment System Types)</h3>
                            <div class="dashboard-select">
                                <svg viewBox="0 0 13 14" x="0px" y="0px" width="14px" height="14px">
                                    <g>
                                        <path
                                            d="M 11.8093 14.0901 L 1.1838 14.0901 C 0.5251 14.0901 -0.0088 13.5209 -0.0088 12.8276 L -0.0088 2.9009 C -0.0088 2.2202 0.5137 1.6666 1.1493 1.6465 L 1.1493 3.3405 C 1.1493 4.106 1.7395 4.7229 2.4669 4.7229 L 3.2981 4.7229 C 4.0254 4.7229 4.6234 4.106 4.6234 3.3405 L 4.6234 1.6406 L 8.3698 1.6406 L 8.3698 3.3405 C 8.3698 4.106 8.9677 4.7229 9.6952 4.7229 L 10.5264 4.7229 C 11.2537 4.7229 11.844 4.106 11.844 3.3405 L 11.844 1.6465 C 12.4796 1.6666 13.002 2.2202 13.002 2.9009 L 13.002 12.8276 C 13.002 13.5198 12.4672 14.0901 11.8093 14.0901 ZM 11.4578 7.0818 C 11.4578 6.782 11.2271 6.5391 10.9423 6.5391 L 2.0282 6.5391 C 1.7433 6.5391 1.5126 6.782 1.5126 7.0818 L 1.5126 12.2095 C 1.5126 12.5091 1.7435 12.7519 2.0282 12.7519 L 10.9424 12.7519 C 11.2271 12.7519 11.4578 12.5091 11.4578 12.2095 L 11.4578 7.0818 ZM 9.6577 12.0114 L 8.6036 12.0114 C 8.4369 12.0114 8.3016 11.869 8.3016 11.6936 L 8.3016 10.5848 C 8.3016 10.4091 8.4369 10.2671 8.6036 10.2671 L 9.6577 10.2671 C 9.8242 10.2671 9.9594 10.4091 9.9594 10.5848 L 9.9594 11.6936 C 9.9594 11.869 9.8242 12.0114 9.6577 12.0114 ZM 9.6576 9.2394 L 8.6036 9.2394 C 8.4369 9.2394 8.3016 9.0973 8.3016 8.9214 L 8.3016 7.8126 C 8.3016 7.6372 8.4369 7.4951 8.6036 7.4951 L 9.6576 7.4951 C 9.8242 7.4951 9.9594 7.6372 9.9594 7.8126 L 9.9594 8.9214 C 9.9594 9.0973 9.8242 9.2394 9.6576 9.2394 ZM 7.0237 12.0114 L 5.9696 12.0114 C 5.803 12.0114 5.6678 11.869 5.6678 11.6936 L 5.6678 10.5848 C 5.6678 10.4091 5.803 10.2671 5.9696 10.2671 L 7.0237 10.2671 C 7.1902 10.2671 7.3254 10.4091 7.3254 10.5848 L 7.3254 11.6936 C 7.3254 11.869 7.1902 12.0114 7.0237 12.0114 ZM 7.0237 9.2394 L 5.9696 9.2394 C 5.803 9.2394 5.6678 9.0973 5.6678 8.9214 L 5.6678 7.8126 C 5.6678 7.6372 5.803 7.4951 5.9696 7.4951 L 7.0237 7.4951 C 7.1902 7.4951 7.3254 7.6372 7.3254 7.8126 L 7.3254 8.9214 C 7.3254 9.0973 7.1902 9.2394 7.0237 9.2394 ZM 4.3895 12.0114 L 3.3356 12.0114 C 3.1689 12.0114 3.0338 11.869 3.0338 11.6936 L 3.0338 10.5848 C 3.0338 10.4091 3.1689 10.2671 3.3356 10.2671 L 4.3895 10.2671 C 4.5564 10.2671 4.6917 10.4091 4.6917 10.5848 L 4.6917 11.6936 C 4.6917 11.869 4.5564 12.0114 4.3895 12.0114 ZM 4.3895 9.2394 L 3.3356 9.2394 C 3.1689 9.2394 3.0338 9.0973 3.0338 8.9214 L 3.0338 7.8126 C 3.0338 7.6372 3.1689 7.4951 3.3356 7.4951 L 4.3895 7.4951 C 4.5564 7.4951 4.6917 7.6372 4.6917 7.8126 L 4.6917 8.9214 C 4.6917 9.0973 4.5564 9.2394 4.3895 9.2394 ZM 10.5067 3.8148 L 9.6846 3.8148 C 9.4351 3.8148 9.2328 3.6021 9.2328 3.3396 L 9.2328 0.5626 C 9.2328 0.3001 9.4351 0.0872 9.6846 0.0872 L 10.5067 0.0872 C 10.7562 0.0872 10.9584 0.3001 10.9584 0.5626 L 10.9584 3.3396 C 10.9584 3.6021 10.7562 3.8148 10.5067 3.8148 ZM 3.2861 3.8148 L 2.4639 3.8148 C 2.2142 3.8148 2.012 3.6021 2.012 3.3396 L 2.012 0.5626 C 2.012 0.3001 2.2142 0.0872 2.4639 0.0872 L 3.2861 0.0872 C 3.5355 0.0872 3.7379 0.3001 3.7379 0.5626 L 3.7379 3.3396 C 3.7379 3.6021 3.5355 3.8148 3.2861 3.8148 Z"
                                            fill="#43515d" />
                                    </g>
                                </svg>
                                <select minimumResults="-1" data-jquery="select2_custom_ddl" class="select2_custom_ddl"
                                    style="width:100px" ng-model="dashCtrl.selectpaymentPeriod"
                                    ng-init="dashCtrl.selectpaymentPeriod = '7'"
                                    ng-change="dashCtrl.changePaymentRevenuePeriod(dashCtrl.selectpaymentPeriod)">
                                    <option value="7">Last 7 Days</option>
                                    <option value="14">Last 14 Days</option>
                                    <option value="31">Last 31 Days</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-content graph">
                            <ul class="nav nav-tabs" role="tablist">

                                <li class="active">
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'ALL')">All</a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'INR')">
                                        INR
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'USD')">
                                        USD
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'THB')">
                                        THB
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'LAK')">
                                        LAK
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'BOB')">
                                        BOB
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" style="color: black;"
                                        ng-click="switchCurrency($event, 'EUR')">
                                        EUR
                                    </a>
                                </li>
                            </ul>
                            <!-- <canvas id="multipleline-chart" style="height: 350px; width: 100%;"></canvas> -->
                            <canvas id="multipleline-chart" width="600" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- based on payment system types -->
            </div>
            <!-- Daily Sales Revenue  -->

            <!-- Sales Revenue -->
            <div class="dashbord-section flexbox flex-wrap" ng-if="dashCtrl.showLineChart">
                <div class="dashbord-section-grid flexbox width-50" style="width: 100%">
                    <div class="card">
                        <div class="card-heading flexbox align-items-center">
                            <h3>Sales Revenue</h3>
                        </div>

                        <div class="card-content">
                            <div class="table-responsive">

                                <ul class="nav nav-tabs">
                                    <li ng-if="dashCtrl.showTotal" class="active"
                                        ng-click="dashCtrl.switchTab('all', $event)">
                                        <a href="javascript:void(0)" style="color: black;">All</a>
                                    </li>

                                    <li ng-click="dashCtrl.switchTab('razorpay', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Razor Pay</a>
                                    </li>

                                    <li ng-if="dashCtrl.showAuthorizeNet"
                                        ng-click="dashCtrl.switchTab('authorize', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Authorize.net</a>
                                    </li>

                                    <li ng-if="dashCtrl.showCash" ng-click="dashCtrl.switchTab('cash', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Cash</a>
                                    </li>

                                    <li ng-if="dashCtrl.showAutoPay" ng-click="dashCtrl.switchTab('autopay', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Auto Payment</a>
                                    </li>

                                    <li ng-if="dashCtrl.showCheck" ng-click="dashCtrl.switchTab('check', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Check</a>
                                    </li>

                                    <li ng-if="dashCtrl.showExternal"
                                        ng-click="dashCtrl.switchTab('external_payment', $event)">
                                        <a href="javascript:void(0)" style="color: black;">External Payments</a>
                                    </li>

                                    <li ng-if="dashCtrl.show2C2P" ng-click="dashCtrl.switchTab('2c2p', $event)">
                                        <a href="javascript:void(0)" style="color: black;">2C2P</a>
                                    </li>

                                    <li ng-if="dashCtrl.showGr4vy" ng-click="dashCtrl.switchTab('gr4vy', $event)">
                                        <a href="javascript:void(0)" style="color: black;">Gr4vy</a>
                                    </li>

                                    <li ng-if="dashCtrl.showTrueMoney" ng-click="dashCtrl.switchTab('true_mony', $event)">
                                        <a href="javascript:void(0)" style="color: black;">True Money</a>
                                    </li>

                                    <!-- <li ng-if="dashCtrl.showTotal" ng-click="dashCtrl.switchTab('total', $event)">
                                                                                                                                <a style="color: black;">Total</a>
                                                                                                                            </li> -->
                                </ul>


                                <!-- <ul class="nav nav-tabs" role="tablist">
                                                                                                                                        <li class="active" ng-click="dashCtrl.switchTab('all', $event)">
                                                                                                                                            <a style="color: black;">All</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-if="dashCtrl.showAuthorizeNet" ng-click="dashCtrl.switchTab('razorpay', $event)">
                                                                                                                                            <a style="color: black;">Razorpay</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('authorize', $event)">
                                                                                                                                            <a style="color: black;">Authorize.net</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('cash', $event)">
                                                                                                                                            <a style="color: black;">Cash</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('autopay', $event)">
                                                                                                                                            <a style="color: black;">Autopayment</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('check', $event)">
                                                                                                                                            <a style="color: black;">Check</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('external_payment', $event)">
                                                                                                                                            <a style="color: black;">External Payments</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('2c2p', $event)">
                                                                                                                                            <a style="color: black;">2C2P</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('gr4vy', $event)">
                                                                                                                                            <a style="color: black;">Gr4vy</a>
                                                                                                                                        </li>
                                                                                                                                        <li ng-click="dashCtrl.switchTab('true_mony', $event)">
                                                                                                                                            <a style="color: black;">True Money</a>
                                                                                                                                        </li>
                                                                                                                                    </ul> -->

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="center">Currency</th>
                                            <th class="center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Show "no data" message if empty -->
                                        <tr ng-if="!dashCtrl.tableData || Object.keys(dashCtrl.tableData).length === 0">
                                            <td colspan="2" class="no-data center">
                                                {{ trans('base::general.not_found') }}
                                            </td>
                                        </tr>

                                        <!-- Loop through currency data -->
                                        <tr ng-repeat="(currency, amount) in dashCtrl.tableData">
                                            <td class="center">@{{ currency }}</td>
                                            <td class="center">@{{ amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Revenue -->

            <div ng-class="showDemoVideo ? 'video-demo-popup in' : 'video-demo-popup' ">
                <div class="overlay" ng-click="showDemoVideo = !showDemoVideo"></div>
                <div class="video-demo-popup-content">
                    <span class="close" ng-click="showDemoVideo = !showDemoVideo">
                        <svg viewBox="0 0 30 31" version="1.1" x="0px" y="0px" width="30px" height="31px">
                            <g>
                                <path
                                    d="M 15.1777 0.6068 C 23.3376 0.6068 29.9525 7.2193 29.9525 15.3763 C 29.9525 23.5334 23.3376 30.146 15.1777 30.146 C 7.0178 30.146 0.403 23.5334 0.403 15.3763 C 0.403 7.2193 7.0178 0.6068 15.1777 0.6068 Z"
                                    fill="#ffffff" />
                                <path opacity="0.749"
                                    d="M 16.6619 15.5463 C 16.5675 15.452 16.5675 15.3102 16.6619 15.2158 L 20.2021 11.674 C 20.2965 11.5795 20.3438 11.4379 20.3438 11.3435 C 20.3438 11.249 20.2965 11.1073 20.2021 11.0128 L 19.5414 10.3518 C 19.4469 10.2573 19.3054 10.2101 19.2109 10.2101 C 19.0692 10.2101 18.9749 10.2573 18.8804 10.3518 L 15.3402 13.8935 C 15.2458 13.988 15.1041 13.988 15.0098 13.8935 L 11.4696 10.3518 C 11.3752 10.2573 11.2335 10.2101 11.1392 10.2101 C 11.0447 10.2101 10.9032 10.2573 10.8088 10.3518 L 10.1478 11.0128 C 10.0536 11.1073 10.0063 11.249 10.0063 11.3435 C 10.0063 11.4379 10.0536 11.5795 10.1478 11.674 L 13.6882 15.2158 C 13.7826 15.3102 13.7826 15.452 13.6882 15.5463 L 10.1478 19.0883 C 10.0536 19.1826 10.0063 19.3244 10.0063 19.4188 C 10.0063 19.5133 10.0536 19.6549 10.1478 19.7494 L 10.8088 20.4105 C 10.9032 20.505 11.0447 20.5522 11.1392 20.5522 C 11.2335 20.5522 11.3752 20.505 11.4696 20.4105 L 15.0098 16.8687 C 15.1041 16.7742 15.2458 16.7742 15.3402 16.8687 L 18.8804 20.4105 C 18.9749 20.505 19.1165 20.5522 19.2109 20.5522 C 19.3054 20.5522 19.4469 20.505 19.5414 20.4105 L 20.2021 19.7494 C 20.2965 19.6549 20.3438 19.5133 20.3438 19.4188 C 20.3438 19.3244 20.2965 19.1826 20.2021 19.0883 L 16.6619 15.5463 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>

                    <div class="video-demo-content">
                        <iframe src="https://www.youtube.com/embed/yWd4mzGqQYo"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
            var loader = $('#preloader');
            loader.find('#status').css('display', 'none');
            loader.css('display', 'none');
        });
    </script>


    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/dashboard/dashboard.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
@endsection