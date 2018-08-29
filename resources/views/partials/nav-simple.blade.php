<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <a class="navbar-brand" href="{{ route('index') }}">
    @if (Storage::exists('public/logo/favicon.png'))
      <img src="{{{ asset('storage/logo/favicon.png') }}}" width="40" height="40"/>
    @else
      <i class="fa fa-child"></i>
    @endif
    <span class="ml-1 d-lg-none d-md-inline d-none d-xl-inline">{{setting('name')}}</span>
  </a>
  



  <div class="navbar-nav ml-auto">




    @if(\Config::has('app.locales'))
      <div class="dropdown nav-item">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-language"></i> {{ strtoupper(app()->getLocale()) }}
        </a>
        <div class="dropdown-menu">
          @foreach(\Config::get('app.locales') as $locale)
            @if($locale !== app()->getLocale())
              <a class="dropdown-item" href="{{Request::url()}}?force_locale={{$locale}}">
                {{ strtoupper($locale) }}
              </a>
            @endif
          @endforeach
        </div>
      </div>
    @endif



  </div>


</nav>
