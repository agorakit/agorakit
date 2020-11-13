<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{ $title ?? setting('name') }}</title>

    <link rel="shortcut icon" href="{{ route('icon', 192) }}">

    <link rel="manifest" href="{{ route('pwa.index') }}">

    <!-- font awesome -->
    <link rel="stylesheet" href="{{ asset('/fonts/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/fonts/fontawesome/css/v4-shims.min.css')}}">

    <link rel="stylesheet" href="{{ asset('/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/unpoly.css') }}">

    <link rel="stylesheet" href="{{ asset('/css/app.css?v='.filemtime(public_path('css/app.css'))) }}">


    <!-- additional css -->

    @yield('css')
    @stack('css')

    <!-- head -->
    @yield('head')
</head>

<body class="bg-gray-300 text-gray-900">

    @unless(request()->get('embed'))
        @include('partials.nav')
    @endunless


    <div class="js-spinner fixed hidden bg-green-700 top-0 z-50 w-full">
        <div class="inline-block  text-green-200 m-4">
            <i class="far fa-save mr-2"></i>
            {{__('Loading')}}
        </div>
    </div>

    <div class="js-network-error fixed hidden bg-red-700 top-0 z-50 mt-12 w-full">
        <div class="inline-block  text-red-200 m-4">
            <i class="fa fa-plug mr-2"></i>
            {{__('Network error')}}
        </div>
    </div>


    <div class="mx-auto p-4 bg-white sm:shadow-xl xl:p-8 xl:rounded-lg xl:my-4" style="max-width: 1240px">
        @include('partials.errors')
        @yield('content')
    </div>


    <footer class="h-40  p-10 text-xs text-gray-600 sm:rounded-lg text-center">
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


    <script src="{{ asset('js/select2.min.js') }}"></script>



    @yield('js')
    @stack('js')

    <script src="{{ asset('js/compilers.js?v='.filemtime(public_path('js/compilers.js'))) }}"></script>

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
