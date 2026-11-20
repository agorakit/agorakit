@extends('emails.template')

@section('content')


<p>{{t('You asked for a password change')}}}}</p>


@include('emails.button', ['url' => url('password/reset/'.$token), 'label' => t('Click here to change your password')])


<p style="font-size: 0.8em">{{t('If you don\'t want to change your password, do nothing')}}</p>


@endsection
