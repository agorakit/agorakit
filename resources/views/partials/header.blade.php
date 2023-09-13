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

<!-- inter font -->
<link rel="preconnect" href="https://rsms.me/">
<link rel="stylesheet" href="https://rsms.me/inter/inter.css">

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<!-- additional css from modules -->

<link href="{{ asset('/css/fullcalendar.css') }}" rel="stylesheet">
<link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('/packages/summernote/summernote-lite.min.css') }}" rel="stylesheet">

<!-- unpoly -->

<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-migrate.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-bootstrap5.min.css" rel="stylesheet">

<script>
    up.link.config.followSelectors.push('a[href]')
</script>

<script src="{{ asset('js/fullcalendar.js') }}"></script>
<script src="{{ asset('packages/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/compilers.js?v=' . filemtime(public_path('js/compilers.js'))) }}"></script>


<!-- tabler.io -->

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">

<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

<!-- additional css -->

@yield('css')
@stack('css')

<!-- additional js -->
@yield('js')
@stack('js')

<!-- head -->
@yield('head')
