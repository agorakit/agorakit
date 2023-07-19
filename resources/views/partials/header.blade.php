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

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

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

<!-- main app css, candidate for removal -->
<!--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->

<!-- bootstrap 5.2 -->



<!-- tabler.io -->

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js"></script>
<!--<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet">-->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<style>
    :root
    {
        /*--tblr-font-sans-serif: 'Inter';*/
        --tblr-nav-tabs-link-active-bg: white;
        --tblr-border-radius: 5px;
        --tblr-body-bg: #ccc;

    }

    .nav-tabs .nav-link.active
    {
        background-color: white;
    }
</style>

<!-- additional css -->

@yield('css')
@stack('css')

<!-- additional js -->
@yield('js')
@stack('js')

<!-- head -->
@yield('head')
