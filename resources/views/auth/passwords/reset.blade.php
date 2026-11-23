@extends('dialog')

@section('content')
    <div class="container">
        <h1>{{ trans('Reset Password') }}</h1>


        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @honeypot

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">{{ trans('E-Mail Address') }}</label>


                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="password">{{ trans('Password') }}</label>


                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="password-confirm">{{ trans('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="form-group mt-4">

                        <button type="submit" class="btn btn-primary">
                            {{ trans('Reset Password') }}
                        </button>

                    </div>
                </form>
            </div>

        @endsection
