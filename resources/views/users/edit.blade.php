@extends('app')

@section('content')

@include('users.tabs')

<div class="tab_content">

<h1>Modifier {{ $user->name }}</h1>
<p>
Inscription :  {{ $user->created_at->diffForHumans() }}
</p>

{!! Form::model($user, array('action' => ['UserController@update', $user->id], 'files' => true)) !!}

@include('users.form')

<div class="form-group">
  {!! Form::submit(trans('messages.edit_my_profile'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

</div>

@endsection
