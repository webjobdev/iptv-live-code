<head>
    <title>{{config()->get('settings.general-settings.site-settings.page_title')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description"
        content="{{config()->get('settings.general-settings.site-settings.page_description')}}">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-api-url" content="">
    <meta name="base-template-url" content="">
    <meta name="public-access-token" content="8YZKroRBFPV0aX0Hz9YTydI6gZq5pu">
    <meta name="access-token" content="">
    <link rel="shortcut icon"
        href="{{ asset('adminview/assets/images/email') . '/' . config()->get('settings.general-settings.site-settings.favicon') }}">
    <!--[if lt IE 9]>
	script src="{{$getBaseAssetsUrl('js/html5shiv.js')}}"></script>-->
    <!-- <![endif]-->

    <!-- style-->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

    <link href="{{asset('adminview/assets/css/bootstrap.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/jquery.mCustomScrollbar.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{asset('adminview/assets/css/admin/style.css?v=')}}{{env('ASSERT_VERSION', time())}}" type="text/css"
        rel="stylesheet">
    <link href="{{asset('adminview/assets/css/admin/responsive.css?v=')}}{{env('ASSERT_VERSION', time())}}"
        type="text/css" rel="stylesheet">
    <link href="{{ asset('adminview/assets/css/new_custom.css') }}" rel="stylesheet" type="text/css">

    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700' rel='stylesheet'
        type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('adminview/assets/css/select2.min.css')}}" type="text/css" rel="stylesheet" />
    @section('stylesheet')

    @show
</head>