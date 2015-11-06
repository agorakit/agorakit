



<div class="group_header">
    <h1>{{ $group->name }}</h1>
    <p class="hidden-xs">{{ $group->body}}</p>
    <a href="{{ action('GroupController@edit', [$group->id]) }}"><i class="fa fa-pencil"></i>
      {{trans('messages.edit')}}</a>

</div>

  <ul class="nav nav-tabs">
    <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif>
      <a href="{{ action('GroupController@show', $group->id) }}">
        <i class="fa fa-home"></i> <span class="hidden-xs">{{ trans('messages.home') }}</span>
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif>
      <a href="{{ action('DiscussionController@index', $group->id) }}">
        <i class="fa fa-comments"></i> <span class="hidden-xs">{{ trans('messages.discussions') }}</span>
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif>
      <a href="{{ action('ActionController@index', $group->id) }}">
        <i class="fa fa-calendar"></i> <span class="hidden-xs">{{ trans('messages.actions') }}</span>
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif>
      <a href="{{ action('FileController@index', $group->id) }}">
        <i class="fa fa-file-o"></i> <span class="hidden-xs">{{ trans('messages.files') }}</span>
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif>
      <a href="{{ action('UserController@index', $group->id) }}">
        <i class="fa fa-users"></i> <span class="hidden-xs">{{ trans('messages.members') }}</span>
      </a>
    </li>


    <li role="presentation" @if (isset($tab) && ($tab == 'settings')) class="active" @endif>
      <a href="{{ action('MembershipController@settingsForm', $group->id) }}">
        <i class="fa fa-cog"></i> <span class="hidden-xs">{{ trans('messages.settings') }}</span>
      </a>
    </li>

  </ul>
