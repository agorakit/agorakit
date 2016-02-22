@extends('emails.template')

@section('content')




<strong>Bonjour,</strong>

<p>
  {{$invitating_user->name}} pense que vous pourriez être intéressé(e) de rejoindre le groupe "<a href="{{action('GroupController@show',  [$group->id] )}}">{{$group->name}}</a>" au sein de <a href="{{action('DashboardController@index')}}">{{env('APP_NAME')}}</a>
</p>
<p>
Cela vous permettra d'être informé(e) des actions de ce groupe et de prendre part aux discussions.
</p>

<p>Voici la description de ce groupe :
<p>{!! filter($group->body) !!}</p>


@include('emails.button', ['url' => action('InviteController@inviteConfirm', [$group->id, $invite->token]), 'label' => 'Accepter l\'invitation'])
<p>(Cette action est réversible)</p>

<p style="font-size: 0.8em">Si vous ne souhaitez pas rejoindre ce groupe, ne faites rien. Vous ne recevrez pas d'invitations suplémentaires à participer à ce groupe.</p>


@endsection
