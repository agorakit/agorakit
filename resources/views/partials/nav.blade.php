  <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">{{ trans('messages.toggle_navigation') }}</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="{{ url('/') }}" class="navbar-brand"><i class="fa fa-child"></i> Mobilizator</a>
          </div>
          <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">

              @if ($user_logged)
              <li>
                <a href="{{ url('unread') }}">
                  {{ trans('messages.unread_discussions') }}
                  @if ($unread_discussions > 0) <span class="badge">{{$unread_discussions}}</span>@endif
                </a>
              </li>

              <li>
                <a href="{{ url('/') }}">
                  {{ trans('messages.your_groups') }}
                </a>
              </li>

              @endif
              <!--
              <li><a href="{{ url('/') }}">{{ trans('messages.home') }}</a></li>

            -->



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
