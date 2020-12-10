<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name=viewport content="width=device-width, initial-scale=1">
  <title>{{setting('name')}}</title>

  <link rel="shortcut icon" href="{{{ asset('logo/favicon.png') }}}">

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

<body>


    @include('partials.errors')
  



  <div class="container  main-container main-dialog dialog  mx-auto max-w-xl bg-white sm:shadow-xl sm:rounded-lg p-4 lg:p-8">
      @yield('content')
  </div>


  <div class="credits">
    {{trans('messages.made_with')}}
    <a up-follow href="https://www.agorakit.org">Agorakit ({{config('agorakit.version')}})</a>
  </div>


  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/unpoly.min.js') }}"></script>

  @yield('js')
  @stack('js')

  <script src="{{ asset('js/compilers.js') }}"></script>

  <!-- footer -->
  @yield('footer')



</body>
</html>
