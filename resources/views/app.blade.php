<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{setting('name')}}</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/unpoly@0.53.0/dist/unpoly.min.css">

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">


    <!-- additional css -->

    @yield('css')
    @stack('css')

    <!-- head -->
    @yield('head')
</head>

<body>

    @unless (request()->get('embed'))
        @if (Auth::check())
            @include('partials.nav')
        @else
            @include('partials.nav-guest')
        @endif
    @endunless

    <div class="container main-container">
        <div class="main">
            @include('partials.errors')
            @yield('content')
        </div>
    </div>

    <div class="credits">
        {{trans('messages.made_with')}}
        <a href="https://www.agorakit.org">Agorakit ({{config('agorakit.version')}})</a>
        - <a href="{{request()->fullUrlWithQuery(['embed'=>1])}}">{{trans('messages.embed')}}</a>
    </div>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/unpoly@0.53.0/dist/unpoly.min.js"></script>


    @yield('js')
    @stack('js')


    <!-- footer -->
    @yield('footer')



</body>
</html>
