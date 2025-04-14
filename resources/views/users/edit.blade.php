@extends('app')

@section('content')
    @include('users.tabs')

    <h1>{{ trans('messages.modify') }} "{{ $user->name }}"</h1>

    {!! Form::model($user, ['action' => ['UserController@update', $user], 'files' => true]) !!}

    @include('users.form')

    <div class="form-group mb-5">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    <h3 style="margin-top: 200px">Delete my account</h3>
    <div class="alert alert-warning">Please note that undoing this will be impossible after some time, and will require admin work</div>
    <a class="btn btn-danger" href="{{ route('users.delete', $user) }}">Click here to delete your account</a>
@endsection
