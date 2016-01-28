@extends('emails.template')

@section('content')


<p>Vous avez demandé de pouvoir changer votre mot de passe</p>


@include('emails.button', ['url' => url('password/reset/'.$token), 'label' => 'Cliquez ici pour changer votre mot de passe'])


<p style="font-size: 0.8em">Si vous ne souhaitez pas changer de mot de passe, ne faites rien.</p>


@endsection
