@extends('emails.template')

@section('content')

<h1>Merci d'avoir rejoint "{{env('APP_NAME')}}"</h1>

<p>
  Nous avons juste besoin de confirmer votre adresse mail.
</p>

@include('emails.button', ['url' => action('Auth\AuthController@confirmEmail', [$user->token]), 'label' => 'Cliquez ici pour confirmer votre adresse email'])

<p style="font-size: 0.8em">Si vous n'avez pas demandé à rejoindre "{{env('APP_NAME')}}", ne faites rien.</p>


@endsection
