<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <a class="navbar-brand" href="{{ route('index') }}">
    @if (Storage::exists('public/logo/favicon.png'))
      <img src="{{{ asset('storage/logo/favicon.png') }}}" width="40" height="40"/>
    @else
      <img src="/images/logo.svg" width="40" height="40"/>
    @endif
    <span class="ml-1 d-lg-none d-md-inline d-none d-xl-inline">{{setting('name')}}</span>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#agorakit_navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>



  <div class="collapse navbar-collapse" id="agorakit_navbar">

    <div class="navbar-nav mr-auto">


      <div class="nav-item">
        <a class="nav-link" href="{{ action('GroupController@index') }}">
          <i class="fa fa-cubes"></i> {{trans('messages.groups')}}
        </a>
      </div>

      <div class="nav-item">
        <a class="nav-link" href="{{ action('DiscussionController@index') }}">
          <i class="fa fa-comments-o"></i> {{trans('messages.discussions')}}
        </a>
      </div>

      <div class="nav-item">
        <a class="nav-link" href="{{ action('ActionController@index') }}">
          <i class="fa fa-calendar"></i> {{trans('messages.agenda')}}
        </a>
      </div>

      <div class="nav-item">
        <a class="nav-link" href="{{ action('MapController@index') }}">
          <i class="fa fa-map-marker"></i> {{trans('messages.map')}}
        </a>
      </div>

      <div class="nav-item">
        <a class="nav-link" href="{{ action('PageController@help') }}">
          <i class="fa fa-info-circle"></i>
          {{trans('messages.help')}}
        </a>
      </div>




    </div>



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


      <div class="nav-item">
        <div class="btn-group">
          <a class="btn btn-outline-primary" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
          <a class="btn btn-outline-secondary" href="{{ url('register') }}">{{ trans('messages.register') }}</a>
        </div>
      </div>

    </div>


  </nav>
