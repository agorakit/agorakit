<div class="sidebar visible-lg-block">

  <h4>{{ trans('Your groups') }}</h4>

  @forelse (Auth::user()->groups as $group)
    <a  href="{{ route('groups.show', $group)}}">{{$group->name}}</a>
  @empty
    <a  href="{{ route('index')}}">{{ trans('You are not subscribed to this group yet') }}</a>
  @endforelse
</ul>


<h4>{{ trans('Overview') }}</h4>

<a  href="{{ route('discussions') }}">
  {{trans('Discussions')}}
</a>

<a  href="{{ action('CalendarEventController@index') }}">
  {{trans('Calendar')}}
</a>

<a  href="{{ action('UserController@index') }}">
  {{trans('Users')}}
</a>

<a  href="{{ action('MapController@index') }}">
  {{trans('Map')}}
</a>


</div>
