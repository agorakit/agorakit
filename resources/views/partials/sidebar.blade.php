<div class="sidebar visible-lg-block">

  <h4>{{ trans('messages.your_groups') }}</h4>

  @forelse (Auth::user()->groups as $group)
    <a href="{{ route('groups.show', $group->id)}}">{{$group->name}}</a>
  @empty
    <a href="{{ route('index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
  @endforelse
</ul>


<h4>{{ trans('messages.overview') }}</h4>

<a href="{{ action('DashboardController@discussions') }}">
  {{trans('messages.discussions')}}
</a>

<a href="{{ action('DashboardController@agenda') }}">
  {{trans('messages.agenda')}}
</a>

<a href="{{ action('DashboardController@users') }}">
  {{trans('messages.users_list')}}
</a>

<a href="{{ action('DashboardController@map') }}">
  {{trans('messages.map')}}
</a>


</div>
