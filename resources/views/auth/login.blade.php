@extends('dialog')

@section('content')
    <h1 class="d-flex mb-4">

        @if (Storage::exists('public/logo/favicon.png'))
            <img class="rounded" src="{{{ asset('storage/logo/favicon.png') }}}" width="40" height="40" />
        @else
            <img class="rounded" src="/images/logo.svg" width="40" height="40" />
        @endif

        <div class="ms-2">
            {{ setting('name') }} : {{ strtolower(trans('messages.login')) }}
        </div>

    </h1>

    <form class="mb-5" method="POST" action="{{ url('/login') }}">

        @csrf
        @honeypot

        <div class="form-group mb-3">
            <label class="form-label">{{ __('Username or Email') }}</label>
            <input class="form-control" name="login" type="text" value="{{ old('username') ?: old('email') }}"
                required="required">

            @error('login')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="form-label">{{ trans('messages.password') }}</label>
            <input class="form-control" name="password" type="password">
            <div class="form-text">
                @lang('If you lost your password, leave it blank, we will send you a login link by email')
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" id="remember_me" name="remember" type="checkbox" value="1" checked>
            <label class="form-check-label" for="remember_me">
                {{ trans('messages.remember_me') }}
            </label>
        </div>

        <div class="flex justify-between items-center my-8">

            <button class="btn btn-primary btn" type="submit">{{ trans('messages.login') }}</button>
            <a class="mr-4" href="{{ route('password.request') }}">{{ trans('messages.password_reset') }}</a>
        </div>

    </form>

    <div class="alert alert-primary" role="alert">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        <a href="{{ route('register') }}" up-target=".dialog">@lang('Click here to register a new account')</a>
    </div>
@endsection
