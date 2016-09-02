<div class="sidebar visible-lg-block">

  <strong>{{ trans('messages.your_groups') }}</strong>


    @forelse (Auth::user()->groups as $group)
      <a href="{{ action('GroupController@show', $group->id)}}">{{$group->name}}</a>
    @empty
      <a href="{{ action('DashboardController@index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
    @endforelse
  </ul>
</div>
