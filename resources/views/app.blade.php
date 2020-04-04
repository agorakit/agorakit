<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name=viewport content="width=device-width, initial-scale=1">
  <title>{{$title ?? setting('name')}}</title>

  <link rel="shortcut icon" href="{{{ asset('logo/favicon.png') }}}">


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/v4-shims.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/css/jquery.atwho.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>
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
    <a href="https://www.agorakit.org">Agorakit ({{config('agorakit.version')}})</a>
    - <a href="{{request()->fullUrlWithQuery(['embed'=>1])}}">{{trans('messages.embed')}}</a>
  </div>


  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/unpoly.min.js') }}"></script>
  <script src="{{ asset('js/trumbowyg.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Caret.js/0.3.1/jquery.caret.min.js"></script>
  <script src="{{ asset('js/atwho.js') }}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/lang-all.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

  @yield('js')
  @stack('js')

  <script src="{{ asset('js/compilers.js') }}"></script>

  <!-- footer -->
  @yield('footer')



</body>
</html>
