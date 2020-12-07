@extends('dialog')

@section('content')


<h1 class="flex items-center justify-center mb-4 text-xl">
    @if (Storage::exists('public/logo/favicon.png'))
    <img src="{{{ asset('storage/logo/favicon.png') }}}" width="40" height="40" class="rounded" />
    @else
    <img src="/images/logo.svg" width="40" height="40" class="rounded" />
    @endif

    <div class="ml-2">
        {{setting('name')}} : {{ strtolower(trans('messages.login')) }}
    </div>
</h1>


<div class="alert alert-secondary" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    <a up-target=".dialog" href="{{ route('register') }}">@lang('Click here to register a new account')</a>
</div>



<form method="POST" action="{{ url('/login') }}" class="mb-5">

    @csrf
    @honeypot

    <div class="form-group">
        <label>{{ __('Username or Email') }}</label>
        <div>
            <input type="text" class="form-control" name="login" required="required"
                value="{{ old('username') ?: old('email') }}">
        </div>

        @error('login')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label>{{ trans('messages.password') }}</label>
        <div class="small-help">
            @lang('If you lost your password, leave it blank, we will send you a login link by email')
        </div>
        <div>
            <input type="password" class="form-control" name="password">
        </div>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" checked value="1" id="remember_me">
        <label class="form-check-label" for="remember_me">
            {{ trans('messages.remember_me') }}
        </label>
    </div>



    <div class="flex justify-between items-center mt-8">
        
        <button type="submit" class="btn btn-primary btn">{{ trans('messages.login') }}</button>
		<a class="mr-4" href="{{route('password.request')}}">{{ trans('messages.password_reset') }}</a>
    </div>

</form>







@endsection