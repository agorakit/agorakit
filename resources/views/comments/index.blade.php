@extends('app')

@section('content')

<h1>Comment listing</h1>

<div class="comments">
  @foreach ($comments as $comment)

  <div class="comment">

    <div class="avatar"><img src="{{$comment->user->avatar()}}"/></div>



    <div class="user">{{$comment->user->name}}</div>

    <div class="body">{{$comment->body}}</div>

    <div class="created">{{$comment->created_at->diffForHumans()}}</div>



  </div>



  @endforeach


</div>

@endsection
