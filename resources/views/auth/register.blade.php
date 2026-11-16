@extends('dialog')

@section('content')
    <h1 class="d-flex align-items-center justify-center mb-4 text-xl">

        <img alt="" class="rounded" height="40" src="{{ route('icon', 40) }}" width="40" />

        <div class="ms-2">
            {{ setting('name') }} : {{ strtolower(trans('Register')) }}
        </div>

    </h1>




    <form action="{{ url('register') }}" method="POST" up-target=".dialog">
        @honeypot
        @csrf


        <div class="form-group">
            <label>{{ trans('Name') }}</label>
            <div>
                <input class="form-control" name="name" required="required" type="text" value="{{ old('name') }}">
            </div>
        </div>

        <div class="form-group">
            <label>{{ trans('Email') }}</label>

            <input class="form-control" name="email" required="required" type="email"
                value="@if (isset($email)) {{ $email }}@else{{ old('email') }} @endif">
        </div>


        <div class="d-flex justify-content-end mt-2">
            <button class="btn btn-primary" type="submit">
                {{ trans('Register') }}
            </button>

        </div>

        @include('partials.socialite')


    </form>


    <div class="alert alert-primary mt-4" role="alert">
        <i aria-hidden="true" class="fa fa-info-circle"></i>
        {{ trans('If you already have an account') }}, <a href="{{ url('login') }}"
            up-target=".dialog">{{ trans('You can login here') }}</a>
    </div>
@endsection
