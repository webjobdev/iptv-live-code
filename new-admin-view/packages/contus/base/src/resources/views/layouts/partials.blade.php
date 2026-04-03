<!DOCTYPE html>
<html>
    <title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="base-api-url" content="">
    <meta name="base-template-url" content="">
    <meta name="access-token" content="">
    <link rel="shortcut icon" href="/ott-laravel/new-admin-view/public/adminview/assets/images/email/favicon.png">
    <!-- <link href="{{$getBaseAssetsUrl('css/bootstrap.min.css')}}" rel="stylesheet"> -->
    <link href="{{ asset('adminview/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="{{$getBaseAssetsUrl('css/admin/login.css')}}" rel="stylesheet"> -->
    <link href="{{ asset('adminview/assets/css/admin/login.css') }}" rel="stylesheet">

    @section('stylesheet')
    @show 
    <body>
        <!-- <div id="preloader">
            <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
        </div> -->
        <section>
            @yield('content')
        </section>
        @include('base::layouts.scripts')
        @section('scripts')
        @show
        
    </body>
</html>
