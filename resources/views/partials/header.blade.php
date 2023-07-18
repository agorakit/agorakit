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

<!-- additional css from modules -->
<link href="{{ asset('/css/fullcalendar.css') }}" rel="stylesheet">
<link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('/packages/summernote/summernote-lite.min.css') }}" rel="stylesheet">

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>


<!-- unpoly -->
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-migrate.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-bootstrap5.min.css" rel="stylesheet">

<script>
    up.link.config.followSelectors.push('a[href]')
</script>

<!-- main app css, candidate for removal -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

<!-- bootstrap 5.2 -->
<!--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <blade ___scripts_0___/>
    -->

<!-- tabler.io -->

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --tblr-font-sans-serif: 'Inter';
    }
</style>

<!-- additional css -->

@yield('css')
@stack('css')

<!-- head -->
@yield('head')
