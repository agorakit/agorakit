@extends('emails.template')

@section('content')

<h1>Merci d'avoir rejoint "{{env('APP_NAME')}}"</h1>

<p>
  Nous avons juste besoin de confirmer votre adresse mail.

  <div class="button">
    <a href='{{ url("register/confirm/{$user->token}") }}'>
      Cliquez ici pour confirmer votre adresse email
    </a>
  </div>

</p>

<p style="font-size: 0.8em">Si vous n'avez pas demandé à rejoindre "{{env('APP_NAME')}}", ne faites rien.</p>


@endsection
