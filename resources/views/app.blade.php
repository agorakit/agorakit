<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($group))
        <meta name="group-id" content="{{ $group->id }}">
    @endif

    <title>{{ $title ?? setting('name') }}</title>

    <link href="{{ route('icon', 192) }}" rel="shortcut icon">

    <link href="{{ route('pwa.index') }}" rel="manifest">

    <!-- font awesome -->
    <link href="{{ asset('/fonts/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/fonts/fontawesome/css/v4-shims.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/unpoly.css') }}" rel="stylesheet">
    <link href="{{ asset('/packages/summernote/summernote-lite.min.css') }}" rel="stylesheet">

    <!-- bootstrap 5.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <!-- tabler.io -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    -->

    <!-- additional css -->

    @yield('css')
    @stack('css')

    <!-- head -->
    @yield('head')
</head>

<body>

    @unless (request()->get('embed'))
        @include('partials.nav')
    @endunless

    @include('partials.errors')

    <div class="container">

        @yield('content')
    </div>

    <footer class="h-40  p-10 text-xs text-gray-600 sm:rounded-lg text-center">
        {{ trans('messages.made_with') }}
        <a href="https://www.agorakit.org" up-follow>Agorakit ({{ config('agorakit.version') }})</a>
        - <a href="{{ request()->fullUrlWithQuery(['embed' => 1]) }}" up-follow>{{ trans('messages.embed') }}</a>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/unpoly.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script src="{{ asset('packages/summernote/summernote-lite.min.js') }}"></script>

    <script src="{{ asset('js/datatables.min.js') }}"></script>

    <script src="{{ asset('js/select2.min.js') }}"></script>

    @yield('js')
    @stack('js')

    <script src="{{ asset('js/compilers.js?v=' . filemtime(public_path('js/compilers.js'))) }}"></script>

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
