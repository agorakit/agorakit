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
<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/css/tabler.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/js/tabler.min.js"></script>

{{-- unpoly compilers --}}
<script src="{{ asset('js/compilers.js?v=' . filemtime(public_path('js/compilers.js'))) }}" defer></script>

<link href="{{ asset('/css/custom.css?v=' . filemtime(public_path('css/custom.css'))) }}" rel="stylesheet">

{{-- additional css --}}
@yield('css')
@stack('css')

{{-- additional js --}}
@yield('js')
@stack('js')

{{-- head --}}
@yield('head')
