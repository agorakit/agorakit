@extends('emails.template')

@section('content')




<strong>{{trans('messages.hello')}}, {{$to_user->name}} </strong>

<p>
  {{$from_user->name}} {{trans('messages.sent_you_a_message')}} :
</p>

<p>
{{ $body }}
</p>





@endsection
