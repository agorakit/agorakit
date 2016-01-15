@extends('app')

@section('content')

<h1>Edit {{ $user->name }}</h1>
<p>
Inscription :  {{ $user->created_at->diffForHumans() }}
</p>

{!! Form::model($user, array('action' => ['UserController@update', $user->id], 'files' => true)) !!}

@include('users.form')

<div class="form-group">
  {!! Form::submit('Modifer mon profil', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}



@endsection
