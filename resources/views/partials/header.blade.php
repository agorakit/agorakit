<meta content="{{ csrf_token() }}" name="csrf-token">
@if (isset($group))
    <meta content="{{ $group->id }}" name="group-id">
@endif

<title>{{ $title ?? setting('name') }}</title>

<link href="{{ route('icon', 192) }}" rel="icon">

<link href="{{ route('pwa.index') }}" rel="manifest">


{{-- font awesome --}}
<link href="{{ asset('/fonts/fontawesome/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('/fonts/fontawesome/css/v4-shims.min.css') }}" rel="stylesheet">

{{-- Nunito webfont --}}

<link href="{{ asset('/fonts/nunito/stylesheet.css') }}" rel="stylesheet">


{{-- unpoly --}}
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.8.0/unpoly.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.8.0/unpoly-migrate.min.js" defer></script>


<link href="https://cdn.jsdelivr.net/npm/unpoly@3.8.0/unpoly.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/unpoly@3.8.0/unpoly-bootstrap5.min.css" rel="stylesheet">


{{-- tabler.io --}}
{{--
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet">
--}}


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

{{-- unpoly compilers --}}
<script src="{{ asset('js/compilers.js?v=' . filemtime(public_path('js/compilers.js'))) }}" defer></script>


<link href="{{ asset('/css/custom.css?v=' . filemtime(public_path('css/custom.css'))) }}" rel="stylesheet">
<link href="{{ asset('/css/agorakit.css?v=' . filemtime(public_path('css/agorakit.css'))) }}" rel="stylesheet">

{{-- additional css --}}
@yield('css')
@stack('css')

{{-- additional js --}}
@yield('js')
@stack('js')

{{-- head --}}
@yield('head')
