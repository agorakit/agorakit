<div class="container">



<h1><a href="{{ action('GroupController@index') }}">Groups</a> / {{ $group->name }}</h1>





<ul class="nav nav-tabs">
  <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif><a href="{{ action('GroupController@show', $group->id) }}">Home</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif><a href="{{ action('DiscussionController@index', $group->id) }}">Discussions</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif><a href="{{ action('ActionController@index', $group->id) }}">Actions</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif><a href="{{ action('FileController@index', $group->id) }}">Files</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif><a href="{{ action('UserController@index', $group->id) }}">Users</a></li>
</ul>



</div>
