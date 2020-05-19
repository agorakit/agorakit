<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name=viewport content="width=device-width, initial-scale=1">
  <title>{{$title ?? setting('name')}}</title>

  <link rel="shortcut icon" href="{{{ asset('logo/favicon.png') }}}">

  <link rel="manifest" href="{{route('pwa.index')}}">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/v4-shims.css">



  <link rel="stylesheet" href="{{ mix('/css/fullcalendar.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/datatables.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/selectize.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/unpoly.css') }}">

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
  <script src="{{ asset('js/unpoly.js') }}"></script>
  <script src="{{ asset('js/fullcalendar.js') }}"></script>
  <script src="{{ asset('js/ckeditor.js') }}"></script>

  <script src="{{ asset('js/datatables.js') }}"></script>


  <script src="{{ asset('js/selectize.js') }}"></script>



@yield('js')
@stack('js')

<script src="{{ asset('js/compilers.js') }}" defer></script>

<!-- footer -->
@yield('footer')


<!-- Custom footer content added by admin -->
{!!setting('custom_footer')!!}




</body>
</html>
