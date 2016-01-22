@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">

  <div class="discussion">
    <h2 class="name">
      <a href="{{ action('DiscussionController@index', [$group->id]) }}">{{trans('messages.discussions')}}</a> <i class="fa fa-angle-right"></i>
      {{ $discussion->name }}
    </h2>


    <div class="meta">{{trans('messages.started_by')}} <span class="user">{{ $discussion->user->name}}</span>, {{trans('messages.in')}} {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}} </div>
    <div class="body">
      {!! filter($discussion->body) !!}

      <p>
        @can('update', $discussion)
        <a class="btn btn-default btn-xs" href="{{ action('DiscussionController@edit', [$group->id, $discussion->id]) }}">
          <i class="fa fa-pencil"></i>
          {{trans('messages.edit')}}
        </a>
        @endcan

        @if ($discussion->revisionHistory->count() > 0)
        <a class="btn btn-default btn-xs" href="{{action('DiscussionController@history', [$group->id, $discussion->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
        @endif
      </p>

    </div>
  </div>



  <div class="comments">
    @foreach ($discussion->comments as $comment)
    @include('comments._show')
    @endforeach


    @can('create-comment', $group)
    @include ('comments.create')
    @endcan


  </div>

</div>


@endsection
