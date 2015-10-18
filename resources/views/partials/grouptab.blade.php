<div class="container">



<h1><a href="{{ action('GroupController@index') }}">{{ trans('messages.group') }}</a> / {{ $group->name }}</h1>





<ul class="nav nav-tabs">
  <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif><a href="{{ action('GroupController@show', $group->id) }}">{{ trans('messages.home') }}</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif><a href="{{ action('DiscussionController@index', $group->id) }}">{{ trans('messages.discussions') }}</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif><a href="{{ action('ActionController@index', $group->id) }}">{{ trans('messages.actions') }}</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif><a href="{{ action('FileController@index', $group->id) }}">{{ trans('messages.files') }}</a></li>
  <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif><a href="{{ action('UserController@index', $group->id) }}">{{ trans('messages.users') }}</a></li>
</ul>



</div>
