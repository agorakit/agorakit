@extends('dialog')

@section('content')
    <div class="container">
        <h1>{{ __('Reset Password') }}</h1>


        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @honeypot

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }}</label>


                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>


                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="form-group">

                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>

                    </div>
                </form>
            </div>

        @endsection
