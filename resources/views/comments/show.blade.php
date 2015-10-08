@extends('app')

@section('content')

@include('partials.grouptab')

    <h2>{{ $comment->user }}</h2>

<p>
            {{ $comment->body }}
</p>


<a class="btn btn-primary" href="{{ action('CommentController@create', [$group->id, $discussion->id] ) }}">Reply</a>


<div class="alert alert-success">
This discussion is part of a group called
<strong>{{ $discussion->group()->first()->name}}</strong>
</div>

<div class="alert alert-success">

This discussion has been started by <strong>{{ $author->name}}</strong>
</div>



@endsection
