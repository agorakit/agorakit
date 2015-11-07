@extends('emails.template')

@section('content')




<strong>Bonjour,</strong>

<p>
  {{$invitating_user->name}} pense que vous pourriez être intéressé(e) de rejoindre le groupe "{{$group->name}}"
</p>

<p>Voici la description de ce groupe : "{{$group->body}}"</p>


<div class="button">
<a  href="{{action('InviteController@inviteConfirm', [$group->id, $invite->token]) }}">Accepter l'invitation &rarr;</a>
</div>

<p style="font-size: 0.8em">Si vous ne souhaitez pas rejoindre ce groupe, ne faites rien. Vous ne recevrez pas d'invitations suplémentaires à participer à ce groupe.</p>


@endsection
