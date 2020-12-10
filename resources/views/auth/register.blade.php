@extends('dialog')

@section('content')

    <h1 class="flex items-center justify-center mb-4 text-xl">
        @if (Storage::exists('public/logo/favicon.png'))
            <img src="{{{ asset('storage/logo/favicon.png') }}}" width="40" height="40" class="rounded"/>
        @else
            <img src="/images/logo.svg" width="40" height="40" class="rounded"/>
        @endif

        <div class="ml-2">
        {{setting('name')}} : {{ strtolower(trans('messages.register')) }}
        </div>
    </h1>



    <form method="POST" action="{{ url('register') }}" up-target=".dialog">
        @honeypot
        @csrf


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


            <div class="d-flex justify-content-end mt-8">

                <button type="submit" class="btn btn-primary">
                    {{ trans('messages.register') }}
                </button>

            </div>

            @include('partials.socialite')

     
    </form>


 <div class="alert alert-primary mt-8" role="alert">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        {{trans('messages.if_you_already_have_account')}}, <a up-target=".dialog" href="{{url('login')}}">{{trans('messages.you_can_login_here')}}</a>
</div>


@endsection
