
<ul class="nav nav-tabs">
  <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif><a href="{{ action('GroupController@show', $group->id) }}">Home</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif><a href="{{ action('DiscussionController@index', $group->id) }}">Discussions</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif><a href="{{ action('FileController@index', $group->id) }}">Files</a></li>
</ul>
