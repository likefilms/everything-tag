<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">

    <meta property="og:site_name" content="{{ $websiteTitle }}">
    <meta property="og:title" content="@yield('ogTitle')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:image" content="@yield('image')">

    <!--<link href="{{ app()->isLocal() ? asset('css/public.css') : asset(elixir('css/public.css')) }}" rel="stylesheet">-->

    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/nouislider.css')}}" rel="stylesheet">
    <link href="{{asset('css/resize.css')}}" rel="stylesheet">
    <link href="{{asset('css/tinyscrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <link href="{{asset('css/test.css')}}" rel="stylesheet">

    @include('core::public._feed-links')

    @yield('css')

    @if(app()->environment('local') and config('typicms.google_analytics_code'))

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', '{{ config('typicms.google_analytics_code') }}', 'auto');
        ga('send', 'pageview');
    </script>

    @endif

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php
    $view = "main";
?>

<body class="login">

    <div class="container login" id="main" role="login">

        @include('core::public._alert')

        @yield('main')

    </div>


    <script src="@if(app()->environment('production')){{ asset(elixir('public/js/public/components.min.js')) }}@else{{ asset('public/js/public/components.min.js') }}@endif"></script>
    <script src="@if(app()->environment('production')){{ asset(elixir('public/js/public/master.js')) }}@else{{ asset('public/js/public/master.js') }}@endif"></script>
    @if (Request::input('preview'))
    <script src="{{ asset('public/js/public/previewmode.js') }}"></script>
    @endif

    @yield('js')

</body>

</html>
