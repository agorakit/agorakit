<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{ $title ?? setting('name') }}</title>

    <link rel="shortcut icon" href="{{ route('icon', 192) }}">

    <link rel="manifest" href="{{ route('pwa.index') }}">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/v4-shims.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">



    <link rel="stylesheet" href="{{ asset('/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/selectize.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/unpoly.css') }}">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">


    <!-- additional css -->

    @yield('css')
    @stack('css')

    <!-- head -->
    @yield('head')
</head>

<body class="bg-gray-300">

    @unless(request()->get('embed'))
        @include('partials.nav')
    @endunless



    <div class="spinner bg-gray-200 fixed px-4 py-2 m-6 rounded-full hidden">
        <i class="far fa-save"></i>
        Please wait!
    </div>

    <div class="network-error bg-gray-200 fixed px-4 py-2 m-6 rounded-full hidden">
        <i class="far fa-save"></i>
        No network!
    </div>

    <div class="container main-container">
        <div class="main">
            @include('partials.errors')
            @yield('content')
        </div>
    </div>

    <footer class="text-xs text-gray-500 sm:rounded-lg mx-auto">
        {{ trans('messages.made_with') }}
        <a up-follow href="https://www.agorakit.org">Agorakit ({{ config('agorakit.version') }})</a>
        - <a up-follow href="{{ request()->fullUrlWithQuery(['embed' => 1]) }}">{{ trans('messages.embed') }}</a>
    </footer>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/unpoly.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>

    <script src="{{ asset('js/datatables.min.js') }}"></script>


    <script src="{{ asset('js/selectize.js') }}"></script>



    @yield('js')
    @stack('js')

    <script src="{{ asset('js/compilers.js') }}"></script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }

    </script>



    <!-- footer -->
    @yield('footer')


    <!-- Custom footer content added by admin -->
    {!! setting('custom_footer') !!}




</body>

</html>
