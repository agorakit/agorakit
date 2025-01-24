@extends('dialog')

@section('content')
    <h1 class="d-flex align-items-center justify-center mb-4 text-xl">

        <img alt="" class="rounded" height="40" src="{{ route('icon', 40) }}" width="40" />

        <div class="ms-2">
            {{ setting('name') }} : {{ strtolower(trans('messages.register')) }}
        </div>

    </h1>




    <form action="{{ url('register') }}" method="POST" up-target=".dialog">
        @honeypot
        @csrf


        <div class="form-group">
            <label>{{ trans('messages.name') }}</label>
            <div>
                <input class="form-control" name="name" required="required" type="text" value="{{ old('name') }}">
            </div>
        </div>

        <div class="form-group">
            <label>{{ trans('messages.email') }}</label>

            <input class="form-control" name="email" required="required" type="email"
                value="@if (isset($email)) {{ $email }}@else{{ old('email') }} @endif">
        </div>


        <div class="d-flex justify-content-end mt-2">
            <button class="btn btn-primary" type="submit">
                {{ trans('messages.register') }}
            </button>

        </div>

        @include('partials.socialite')


    </form>


    <div class="alert alert-primary mt-4" role="alert">
        <i aria-hidden="true" class="fa fa-info-circle"></i>
        {{ trans('messages.if_you_already_have_account') }}, <a href="{{ url('login') }}"
            up-target=".dialog">{{ trans('messages.you_can_login_here') }}</a>
    </div>
@endsection
