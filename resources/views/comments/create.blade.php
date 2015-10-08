@extends('app')

@section('content')

@include('partials.grouptab')

<h1>Replying to discussion "{{$discussion->name}}"</h1>


{!! Form::open(array('action' => ['CommentController@store', 'discussion', $discussion->id])) !!}

@include('comments.form')

<div class="form-group">
{!! Form::submit('Reply', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')



@endsection
