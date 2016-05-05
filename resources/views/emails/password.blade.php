@extends('emails.template')

@section('content')


<p>{{trans('messages.you_asked_for_assword_change')}}}}</p>


@include('emails.button', ['url' => url('password/reset/'.$token), 'label' => trans('messages.click_here_to_change_your_password')])


<p style="font-size: 0.8em">{{trans('messages.if_you_dont_want_to_change_password_do_nothing')}}</p>


@endsection
