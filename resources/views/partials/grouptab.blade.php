<div class="page_header">
  <h1>
    <a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    @if (isset($tab) && ($tab <> 'home'))
      <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a>
    @else
      {{ $group->name }}
    @endif

    <span class="small">
      @if ($group->isPublic())
        <i class="fa fa-globe" title="{{trans('Public group')}}"></i>
      @else
        <i class="fa fa-lock" title="{{trans('Closed group')}}"></i>
      @endif
    </span>
  </h1>
</div>

<ul class="nav nav-tabs">
  <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif>
    <a href="{{ action('GroupController@show', $group->id) }}">
      <i class="fa fa-home"></i> <span class="hidden-xs hidden-sm">{{ trans('Home') }}</span>
    </a>
  </li>


  @can ('viewDiscussions', $group)
    <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif>
      <a href="{{ action('DiscussionController@index', $group->id) }}">
        <i class="fa fa-comments"></i> <span class="hidden-xs hidden-sm">{{ trans('Discussions') }}</span>
      </a>
    </li>
  @endcan

  @can ('viewCalendarEvents', $group)
    <li role="presentation" @if (isset($tab) && ($tab == 'calendarevent')) class="active" @endif>
      <a href="{{ action('CalendarEventController@index', $group->id) }}">
        <i class="fa fa-calendar"></i> <span class="hidden-xs hidden-sm">{{ trans('Calendar') }}</span>
      </a>
    </li>
  @endcan

  @can ('viewFiles', $group)
    <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif>
      <a href="{{ action('FileController@index', $group->id) }}">
        <i class="fa fa-file-o"></i> <span class="hidden-xs hidden-sm">{{ trans('Files') }}</span>
      </a>
    </li>
  @endcan

  @can ('viewMembers', $group)
    <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif>
      <a href="{{ action('UserController@index', $group->id) }}">
        <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm">{{ trans('Members') }}</span>
      </a>
    </li>
  @endcan

  @if ($group->isMember())
    <li role="presentation" @if (isset($tab) && ($tab == 'settings')) class="active" @endif>
      <a href="{{ action('MembershipController@settingsForm', $group->id) }}">
        <i class="fa fa-cog"></i> <span class="hidden-xs hidden-sm">{{ trans('Settings') }}</span>
      </a>
    </li>
  @else
    @can ('join', $group)
      <li role="presentation" @if (isset($tab) && ($tab == 'settings')) class="active" @endif>
        <a href="{{ action('MembershipController@joinForm', $group->id) }}">
          <i class="fa fa-cog"></i> <span class="hidden-xs hidden-sm">{{ trans('Join') }}</span>
        </a>
      </li>
    @else
      <li role="presentation" @if (isset($tab) && ($tab == 'settings')) class="active" @endif>
        <a href="{{ action('MembershipController@howToJoin', $group->id) }}">
          <i class="fa fa-lock"></i> <span class="hidden-xs hidden-sm">{{ trans('Join') }}</span>
        </a>
      </li>
    @endcan
  @endif

</ul>
