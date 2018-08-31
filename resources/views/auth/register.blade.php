@extends('dialog')

@section('content')

    <h1>{{ trans('messages.register') }}</h1>


    @if (isset($invite_and_register))
        <form method="POST" action="{{ action('InviteController@inviteRegister', [$group, $token]) }}">
        @else
            <form method="POST" action="{{ url('register') }}">
                <div class="help" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    {{trans('messages.if_you_already_have_account')}}, <a href="{{url('login')}}">{{trans('messages.you_can_login_here')}}</a>
                </div>
            @endif

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label>{{ trans('messages.name') }}</label>
                <div>
                    <input type="text" class="form-control" required="required"  name="name" value="{{ old('name') }}">
                </div>
            </div>

            <div class="form-group">
                <label >{{ trans('messages.email') }}</label>

                <input type="email" class="form-control" required="required" name="email" value="@if (isset($email)) {{$email}}@else{{ old('email')}}@endif" >
                </div>


                <div class="form-group">
                    <label >{{ trans('messages.password') }}</label>
                    <div>
                        <input type="password" class="form-control" required="required" name="password">
                    </div>
                </div>

                <div class="form-group">
                    <label >{{ trans('messages.confirm_password') }}</label>
                    <div >
                        <input type="password" class="form-control" required="required" name="password_confirmation">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">

                    <button type="submit" class="btn btn-primary">
                        {{ trans('messages.register') }}
                    </button>

                </div>

                @include('partials.socialite')

            </div>
        </form>



    @endsection
