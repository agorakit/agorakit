@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">

<div class="discussion">
  <h2 class="name">
    <a href="{{ action('DiscussionController@index', [$group->id]) }}">{{trans('messages.discussions')}}</a> /
    {{ $discussion->name }}
  </h2>


  <div class="meta">{{trans('messages.started_by')}} <span class="user">{{ $discussion->user->name}}</span>, {{trans('messages.in')}} {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}} </div>
  <div class="body">
    {!! $discussion->body !!}

    <p>
      <a href="{{ action('DiscussionController@edit', [$group->id, $discussion->id]) }}">
        <i class="fa fa-pencil"></i>
        {{trans('messages.edit')}}
      </a>
        @if ($discussion->revisionHistory->count() > 0)
        |  <a href="{{action('DiscussionController@history', [$group->id, $discussion->id])}}">{{trans('messages.show_history')}}</a>
        @endif
    </p>

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
