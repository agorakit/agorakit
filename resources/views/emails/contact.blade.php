@extends('emails.template')

@section('content')




<strong>Bonjour, {{$to_user->name}} </strong>

<p>
  {{$from_user->name}} vous a envoyé un message :
</p>

<p>
{{ $body }}
</p>





@endsection
