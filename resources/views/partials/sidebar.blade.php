<div class="sidebar visible-lg-block">

  <h4>{{ trans('messages.your_groups') }}</h4>

  @forelse (Auth::user()->groups as $group)
    <a  href="{{ route('groups.show', $group)}}">{{$group->name}}</a>
  @empty
    <a  href="{{ route('index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
  @endforelse
</ul>


<h4>{{ trans('messages.overview') }}</h4>

<a  href="{{ route('discussions') }}">
  {{trans('messages.discussions')}}
</a>

<a  href="{{ action('ActionController@index') }}">
  {{trans('messages.agenda')}}
</a>

<a  href="{{ action('UserController@index') }}">
  {{trans('messages.users_list')}}
</a>

<a  href="{{ action('MapController@index') }}">
  {{trans('messages.map')}}
</a>


</div>
