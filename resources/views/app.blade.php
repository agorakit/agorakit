<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name=viewport content="width=device-width, initial-scale=1">
  <title>{{$title ?? setting('name')}}</title>

  <link rel="shortcut icon" href="{{{ asset('logo/favicon.png') }}}">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/v4-shims.css">

  <!-- full calendar -->
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css">
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.css">
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.css">
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/list@4.4.0/main.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css">

  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">


  <!-- additional css -->

  @yield('css')
  @stack('css')

  <!-- head -->
  @yield('head')
</head>

<body>

  @unless (request()->get('embed'))
    @if (Auth::check())
      @include('partials.nav')
    @else
      @include('partials.nav-guest')
    @endif
  @endunless



  <div class="spinner shadow">
    <i class="far fa-save"></i>
    Please wait!
  </div>

  <div class="container main-container @if (isset($dialog)) main-dialog @endif">
    <div class="main">
      @include('partials.errors')
      @yield('content')
    </div>
  </div>

  <div class="credits">
    {{trans('messages.made_with')}}
    <a up-follow href="https://www.agorakit.org">Agorakit ({{config('agorakit.version')}})</a>
    - <a up-follow href="{{request()->fullUrlWithQuery(['embed'=>1])}}">{{trans('messages.embed')}}</a>
  </div>


  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/unpoly.min.js') }}"></script>
  <script src="{{ asset('js/ckeditor.js') }}" defer></script>

  <!-- Full calendar-->
  <script src='https://unpkg.com/@fullcalendar/core@4.4.0/locales/{{App::getLocale()}}.js'></script>
  <script src="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js" defer></script>

  <script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js" defer></script>
  <script src="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js" defer></script>
  <script src="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js" defer></script>
  <script src="https://unpkg.com/@fullcalendar/list@4.4.0/main.min.js" defer></script>

  <!-- Selectize-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" defer></script>

  <!-- Datatables-->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" defer></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" defer></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" defer></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" defer></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" defer></script>
  @yield('js')
  @stack('js')

  <script src="{{ asset('js/compilers.js') }}" defer></script>

  <!-- footer -->
  @yield('footer')


  <!-- Custom footer content added by admin -->
  {!!setting('custom_footer')!!}




</body>
</html>
