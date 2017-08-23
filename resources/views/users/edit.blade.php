@extends('app')

@section('content')

@include('users.tabs')

<div class="tab_content">

<h1>{{trans('messages.modify')}} "{{ $user->name }}"</h1>

{!! Form::model($user, array('action' => ['UserController@update', $user->id], 'files' => true)) !!}

@include('users.form')

<div class="form-group">
  {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

</div>

@endsection
