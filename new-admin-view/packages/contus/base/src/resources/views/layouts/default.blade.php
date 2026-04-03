<!DOCTYPE html>
<html lang="en">
@include('base::layouts.head')

<body>
    <div id="preloader">
        <div id="status"><i></i></div>
    </div>
    <section>
        <div id="st-container" class="st-container">
            <!-- content push wrapper -->
            <div class="st-pusher" data-ng-controller="CommonController as headerCtrl">
                @yield('header')
                <div class="mainpanel">
                    @include('base::layouts.sidebar')

                    <div class="loader-ring">
                        <div class="loader-ring-light"></div>
                        <div class="loader-ring-track"></div>
                    </div>

                    <div class="main-container">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('base::layouts.scripts')
    @section('scripts')
    @show


</body>

</html>