<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>

    <!-- Bootstrap -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" />-->

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />


    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <link rel="stylesheet" href="/css/discussions.css">
    <link rel="stylesheet" href="/css/actions.css">


    <!-- additional css -->

    @yield('css')
    @stack('css')



    <!-- head -->
    @yield('head')
</head>

<body>

    @include('partials.nav')


    <div class="container main-container">
        <div class="main">
            @include('partials.errors')
            @yield('content')

        </div>
    </div>

    <div class="credits">{{trans('messages.made_with')}} <a href="https://www.agorakit.org">Agorakit ({{config('agorakit.version')}})</a></div>



    <!-- js -->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


    @yield('js')
    @stack('js')


    <!-- footer -->
    @yield('footer')



</body>
</html>
