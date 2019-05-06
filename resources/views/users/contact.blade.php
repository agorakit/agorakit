@extends('app')

@section('content')

@include('users.tabs')

<div class="tab_content">

  <h1>{{_('Contact')}} {{ $user->name }}</h1>

  <div class="help">
    {{_('Use the form below to send an email directly to this user.')}}
  </div>

  {!! Form::open(['action' => ['UserController@contact', $user]]) !!}



  <div class="form-group">
    {!! Form::label('body', _('Your message :')) !!}
    {!! Form::textarea('body', null, ['class' => 'form-control', 'required']) !!}
  </div>

  <div class="form-check">
   <input type="checkbox" class="form-check-input" name="reveal_email" id="reveal_email" checked="checked">
   <label class="form-check-label" for="reveal_email">{{_('Reveal my email to this user so we can communicate by email')}}</label>
 </div>


  <div class="form-group mt-4">
    {!! Form::submit(trans('messages.send'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}

</div>

@endsection
