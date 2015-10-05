  <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="{{ url('/') }}" class="navbar-brand">Mobilizator</a>
          </div>
          <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">
              <li><a href="{{ url('/') }}">{{ trans('messages.home') }}</a></li>
              <li><a href="{{ url('/') }}">Your groups</a></li>



            </ul>


            <ul class="nav navbar-nav navbar-right">

              @if ($user_logged)
              <li><a>{{ trans('messages.hello') }}, {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</a></li>
              <li><a href="{{ url('auth/logout') }}">{{ trans('messages.logout') }}</a></li>
              @else
              <li><a href="{{ url('auth/register') }}">{{ trans('messages.register') }}</a></li>
              <li><a href="{{ url('auth/login') }}">{{ trans('messages.login') }}</a></li>
              @endif



            </ul>

          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
