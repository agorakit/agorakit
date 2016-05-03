@extends('emails.template')

@section('content')

<h1>{{trans('messages.thank_you_for_joining')}} "{{env('APP_NAME')}}"</h1>

<p>
{{trans('messages.we_need_to_confirm')}}

</p>

@include('emails.button', ['url' => action('Auth\AuthController@confirmEmail', [$user->token]), 'label' => trans('messages.click_to_confirm')])

<p style="font-size: 0.8em">{{trans('messages.if_you_didnt_ask')}} "{{env('APP_NAME')}}", {{trans('messages.do_nothing')}}.</p>


@endsection
