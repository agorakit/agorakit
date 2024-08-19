@extends('dialog')

@section('content')
    <h1 class="d-flex mb-4">
        <img class="rounded" height="40" src="{{ route('icon', 40) }}" width="40" />
        <div class="ms-2">
            {{ setting('name') }} : {{ strtolower(trans('messages.login')) }}
        </div>

    </h1>

    <form action="{{ url('/login') }}" class="mb-5" method="POST">

        @csrf
        @honeypot

        <div class="form-group mb-3">
            <label class="form-label">{{ __('Username or Email') }}</label>
            <input class="form-control" name="login" required="required" type="text"
                value="{{ old('username') ?: old('email') }}">
        </div>

        <div class="form-group mb-3">
            <label class="form-label">{{ trans('messages.password') }}</label>
            <input class="form-control" name="password" type="password">
            <div class="form-text">
                @lang('If you lost your password, leave it blank, we will send you a login link by email')
            </div>
        </div>

        <div class="form-check mb-3">
            <input checked class="form-check-input" id="remember_me" name="remember" type="checkbox" value="1">
            <label class="form-check-label" for="remember_me">
                {{ trans('messages.remember_me') }}
            </label>
        </div>

        <div class="flex justify-content-between align-items-center my-4">

            <button class="btn btn-primary" type="submit">{{ trans('messages.login') }}</button>
            <a class="ms-4" href="{{ route('password.request') }}">{{ trans('messages.password_reset') }}</a>
        </div>

    </form>

    <div class="alert alert-primary" role="alert">
        <i aria-hidden="true" class="fa fa-info-circle"></i>
        <a href="{{ route('register') }}">@lang('Click here to register a new account')</a>
    </div>
@endsection
