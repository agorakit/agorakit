<div class="sidebar visible-lg-block">

  <h4>{{ t('Your groups') }}</h4>

  @forelse (Auth::user()->groups as $group)
    <a  href="{{ route('groups.show', $group)}}">{{$group->name}}</a>
  @empty
    <a  href="{{ route('index')}}">{{ t('You are not subscribed to this group yet') }}</a>
  @endforelse
</ul>


<h4>{{ t('Overview') }}</h4>

<a  href="{{ route('discussions') }}">
  {{t('Discussions')}}
</a>

<a  href="{{ action('CalendarEventController@index') }}">
  {{t('Calendar')}}
</a>

<a  href="{{ action('UserController@index') }}">
  {{t('Users')}}
</a>

<a  href="{{ action('MapController@index') }}">
  {{t('Map')}}
</a>


</div>
