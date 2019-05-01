@extends('app')

@section('content')

<div class="tab_content">

    <h1>
        {{setting('name')}}
    </h1>

    <div>
        {!! setting('homepage_presentation', trans('documentation.intro')) !!}
    </div>


        <div class="btn-group d-flex mt-5 justify-content-center w10">
            <a class="btn btn-outline-primary w-10" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
            <a class="btn btn-outline-secondary w-10" href="{{ url('register') }}">{{ trans('messages.register') }}</a>
        </div>




</div>

@endsection
