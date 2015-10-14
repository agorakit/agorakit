@extends('app')

@section('content')

@include('partials.grouptab')


<div class="discussion">
  <h2 class="name">{{ $discussion->name }} <a href="{{ action('DiscussionController@edit', [$group->id, $discussion->id]) }}" class="btn btn-primary btn-xs">Edit</a></h2>
  <div class="meta">Started by <span class="user">{{ $discussion->user->name}}</span>, in {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}} </div>
  <div class="body">
    {{ $discussion->body }}
  </div>


</div>



  <div class="comments">
    @foreach ($discussion->comments as $comment)

    @include('comments._show')

    @endforeach





  @include ('comments.form')

</div>



@endsection
