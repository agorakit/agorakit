@extends('app')

@section('content')

@include('partials.grouptab')

<h1>Create a discussion</h1>


{!! Form::open(array('action' => ['DiscussionController@store', $group->id])) !!}

@include('discussions.form')

<div class="form-group">
{!! Form::submit('Create a discussion', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')



@endsection
