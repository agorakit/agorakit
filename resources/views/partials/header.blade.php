<meta content="{{ csrf_token() }}" name="csrf-token">
@if (isset($group))
    <meta content="{{ $group->id }}" name="group-id">
@endif

<title>{{ $title ?? setting('name') }}</title>

<link href="{{ route('icon', 192) }}" rel="shortcut icon">

<link href="{{ route('pwa.index') }}" rel="manifest">

{{-- font awesome --}}
<link href="{{ asset('/fonts/fontawesome/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('/fonts/fontawesome/css/v4-shims.min.css') }}" rel="stylesheet">

{{-- Nunito webfont --}}
<link href="{{ asset('/fonts/nunito/stylesheet.css') }}" rel="stylesheet">

{{-- jquery --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

{{-- full calendar --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js' defer></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/locales-all.global.min.js' defer></script>

{{-- unpoly --}}
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-migrate.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/unpoly@3.3.0/unpoly-bootstrap5.min.css" rel="stylesheet">

{{-- datatables --}}
{{--
<link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.css"
    rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" defer></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.js" defer>
</script>
--}}

{{-- Tom select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

{{-- tabler.io --}}
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet">

{{-- summernote, lite version does not rely on bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js" defer></script>

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
