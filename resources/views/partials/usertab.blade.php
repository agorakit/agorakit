<div class="page_header">
  <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>

    {{ $user->name }}</h1>
  </div>

  <ul class="nav nav-tabs">
    <li role="presentation" @if (isset($tab) && ($tab == 'profile')) class="active" @endif>
      <a href="{{ action('UserController@show', $user->id) }}">
        <i class="fa fa-user"></i> {{ trans('messages.user_profile') }}
      </a>
    </li>



    <li role="presentation" @if (isset($tab) && ($tab == 'contact')) class="active" @endif>
      <a href="{{action('UserController@contact', $user->id)}}"><i class="fa fa-envelope-o"></i>
        {{trans('messages.contact_this_user')}}</a>
      </a>
    </li>

    @can('update', $user)
    <li role="presentation" @if (isset($tab) && ($tab == 'edit')) class="active" @endif>
      <a href="{{ action('UserController@edit', $user->id) }}">
        <i class="fa fa-pencil"></i>
        {{trans('messages.edit')}}
      </a>
    </li>
    @endcan


  </ul>
