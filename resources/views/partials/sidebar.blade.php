<div class="sidebar visible-lg-block">

  <h4>{{ trans('messages.your_groups') }}</h4>

  @forelse (Auth::user()->groups as $group)
    <a up-follow href="{{ route('groups.show', $group)}}">{{$group->name}}</a>
  @empty
    <a up-follow href="{{ route('index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
  @endforelse
</ul>


<h4>{{ trans('messages.overview') }}</h4>

<a up-follow href="{{ action('DiscussionController@index') }}">
  {{trans('messages.discussions')}}
</a>

<a up-follow href="{{ action('ActionController@index') }}">
  {{trans('messages.agenda')}}
</a>

<a up-follow href="{{ action('UserController@index') }}">
  {{trans('messages.users_list')}}
</a>

<a up-follow href="{{ action('MapController@index') }}">
  {{trans('messages.map')}}
</a>


</div>
