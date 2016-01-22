@extends('app')

@section('content')

@if ($user_logged)
Dashboard here
@else
<a class="btn btn-primary" href="{{ url('register') }}" role="button"><span class="glyphicon glyphicon-user"></span> {{ trans('messages.register') }}</a>

{{ trans('messages.or') }}

<a class="btn btn-primary" href="{{ url('login') }}" role="button"><span class="glyphicon glyphicon-off"></span> {{ trans('messages.login') }}</a>
@endif

@endsection
