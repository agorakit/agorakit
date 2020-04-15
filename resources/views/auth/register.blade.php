@extends('dialog')

@section('content')

    <h1>{{config('agorakit.name')}} : {{ trans('messages.register') }}</h1>



            <form method="POST" action="{{ url('register') }}">
                @honeypot
                @csrf

                <div class="help" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    {{trans('messages.if_you_already_have_account')}}, <a href="{{url('login')}}">{{trans('messages.you_can_login_here')}}</a>
                </div>



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


                <div class="d-flex justify-content-end mt-4">

                    <button type="submit" class="btn btn-primary">
                        {{ trans('messages.register') }}
                    </button>

                </div>

                @include('partials.socialite')

            </div>
        </form>



    @endsection
