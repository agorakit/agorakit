@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">

<div class="discussion">
  <h2 class="name">
    <a href="{{ action('DiscussionController@index', [$group->id]) }}">{{trans('messages.discussions')}}</a> /
    {{ $discussion->name }} <a href="{{ action('DiscussionController@edit', [$group->id, $discussion->id]) }}" class="btn btn-primary btn-xs">{{trans('messages.edit')}}</a>
  </h2>


  <div class="meta">{{trans('messages.started_by')}} <span class="user">{{ $discussion->user->name}}</span>, {{trans('messages.in')}} {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}} </div>
  <div class="body">
    {{ $discussion->body }}
  </div>
</div>



<div class="comments">
  @foreach ($discussion->comments as $comment)

  @include('comments._show')

  @endforeach



@if ($group->isMember())
  @include ('comments.form')
@endif

</div>

</div>


@endsection
