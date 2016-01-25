@extends('app')

@section('content')

@include('partials.usertab')

<div class="tab_content">

  <h1>{{trans('messages.contact')}} {{ $user->name }}</h1>

  {!! Form::open(['action' => ['UserController@mail', $user->id]]) !!}



  <div class="form-group">
    {!! Form::label('body', trans('messages.message')) !!}
    {!! Form::textarea('body', null, ['class' => 'form-control', 'required']) !!}
  </div>


  <div class="form-group">
    {!! Form::submit(trans('messages.send'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}

</div>

@endsection
