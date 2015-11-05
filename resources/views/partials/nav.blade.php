<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
        <span class="sr-only">{{ trans('messages.toggle_navigation') }}</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="{{ url('/') }}" class="navbar-brand"><i class="fa fa-child"></i> {{Config::get('mobilizator.name')}}</a>
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

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{ trans('messages.your_groups') }} <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">

            @forelse ($user_groups as $user_group)
            <li><a href="{{ action('GroupController@show', $user_group->id)}}">{{$user_group->name}}</a></li>
            @empty
            <li><a href="{{ action('GroupController@index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a></li>
            @endforelse

            <li role="separator" class="divider"></li>
            <li><a href="{{ action('GroupController@index')}}">{{ trans('messages.groups_list') }}</a></li>
            <li><a href="{{ action('GroupController@create') }}">
              <i class="fa fa-bolt"></i>
              {{ trans('group.create_a_group_button') }}</a> </li>


            </ul>
          </li>


          @endif
        </ul>


        <ul class="nav navbar-nav navbar-right">

          @if ($user_logged)
          <li><a href="{{action('UserController@show', $user->id)}}">{{ trans('messages.hello') }}, {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</a></li>
          <li><a href="{{ url('auth/logout') }}">{{ trans('messages.logout') }}</a></li>
          @else
          <li><a href="{{ url('auth/register') }}">{{ trans('messages.register') }}</a></li>
          <li><a href="{{ url('auth/login') }}">{{ trans('messages.login') }}</a></li>
          @endif



        </ul>

      </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
  </nav>


  @if (isset($user->verified) && ($user->verified == 0))
  <div class="container">
    <div class="alert alert-info alert-dismissible fade in" id="message">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <i class="fa fa-info-circle"></i>
      Vous n'avez pas vérifié votre email. Merci de le faire depuis votre boite mail.
    </div>
  </div>
  @endif
