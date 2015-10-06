@extends('app')

@section('content')
    <h2>{{ $discussion->name }}</h2>

<p>
            {{ $discussion->body }}
</p>


<div class="alert alert-success">
This discussion is part of a group called
<strong>{{ $discussion->group()->first()->name}}</strong>
</div>

<div class="alert alert-success">

This discussion has been started by <strong>{{ $author->name}}</strong>
</div>



@endsection
