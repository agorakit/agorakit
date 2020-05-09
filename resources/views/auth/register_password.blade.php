@extends('dialog')

@section('content')

    <h1>{{config('agorakit.name')}} : @lang('Set your password')</h1>

    <p>@lang('Use a strong password with at least 8 characters')</p>

    <form method="POST" action="{{ url('register/password') }}">
        @honeypot
        @csrf

        <div class="form-group">
            <label >{{ trans('messages.password') }}</label>
            <div>
                <input type="password" class="form-control" required="required" name="password"  minlength="8">
            </div>
        </div>

        <div class="form-group">
            <label >{{ trans('messages.confirm_password') }}</label>
            <div >
                <input type="password" class="form-control" required="required" name="password_confirmation" minlength="8">
            </div>
        </div>


        <div class="d-flex justify-content-end mt-4">

            <button type="submit" class="btn btn-primary">
                {{ trans('messages.register') }}
            </button>

        </div>

    </div>
</form>



@endsection
