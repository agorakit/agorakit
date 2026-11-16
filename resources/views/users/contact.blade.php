@extends('app')

@section('content')
    <h1>{{ trans('Contact') }} {{ $user->name }}</h1>

    <div class="help">
        {{ trans('Use the form below to send an email directly to this user.') }}
    </div>

    {!! Form::open(['action' => ['UserController@contact', $user]]) !!}

    <div class="form-group">
        {!! Form::label('body', trans('Your message :')) !!}
        {!! Form::textarea('body', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-check">
        <input checked="checked" class="form-check-input" id="reveal_email" name="reveal_email" type="checkbox">
        <label class="form-check-label"
            for="reveal_email">{{ trans('Reveal my email to this user so we can communicate by email') }}</label>
    </div>

    <div class="form-group mt-4">
        {!! Form::submit(trans('send'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
