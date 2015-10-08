@extends('app')

@section('content')

@include('partials.grouptab')

    <h2>{{ $discussion->name }}</h2>

<p>
            {{ $discussion->body }}
</p>


@foreach ($discussion->comments as $comment)

  @include('comments._show')

@endforeach
</table>

<a class="btn btn-primary" href="{{ action('CommentController@create', ['discussion', $discussion->id] ) }}">Reply</a>

<!--
<div class="alert alert-success">
This discussion is part of a group called
<strong>{{ $discussion->group()->first()->name}}</strong>
</div>

<div class="alert alert-success">

This discussion has been started by <strong>{{ $author->name}}</strong>
</div>
-->


@endsection
