



<div class="group_header">
    <h1>{{ $group->name }}</h1>
    <p class="hidden-xs">{{ $group->body}}</p>
</div>

  <ul class="nav nav-pills nav-justified">
    <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif>
      <a href="{{ action('GroupController@show', $group->id) }}">
        <i class="fa fa-home"></i> {{ trans('messages.home') }}
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif>
      <a href="{{ action('DiscussionController@index', $group->id) }}">
        <i class="fa fa-comments"></i> {{ trans('messages.discussions') }}
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif>
      <a href="{{ action('ActionController@index', $group->id) }}">
        <i class="fa fa-calendar"></i> {{ trans('messages.actions') }}
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif>
      <a href="{{ action('FileController@index', $group->id) }}">
        <i class="fa fa-file-o"></i> {{ trans('messages.files') }}
      </a>
    </li>

    <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif>
      <a href="{{ action('UserController@index', $group->id) }}">
        <i class="fa fa-users"></i> {{ trans('messages.members') }}
      </a>
    </li>

  </ul>
