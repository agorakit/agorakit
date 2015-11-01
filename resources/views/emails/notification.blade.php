@extends('emails.template')

@section('content')


<h2>Bonjour {{$user->name}},</h2>

<p>
  Voici les dernières nouvelles du groupe "{{$group->name}}"
</p>

<h3>Dernières discussion et mises à jour</h3>
@forelse($discussions as $discussion)
<li><a href="{{action('DiscussionController@show', [$group->id, $discussion->id])}}">{{$discussion->name}} </a></li>
@empty Rien de neuf depuis le dernier mail
@endforelse

<h3>Prochaines actions</h3>
@forelse($actions as $action)
<li><a href="{{action('ActionController@show', [$group->id, $action->id])}}">{{$action->name}}</a></li>
@empty Rien de neuf depuis le dernier mail
@endforelse


<h3>Nouveaux membres</h3>
@forelse($users as $user)
<li><a href="{{action('UserController@show', $user->id)}}">{{$user->name}}</a></li>
@empty Personne depuis le dernier mail
@endforelse


<h3>Nouveaux fichiers</h3>
@forelse($files as $file)
<li><a href="{{action('FileController@show', [$group->id, $file->id])}}">{{$file->name}}</a></li>
@empty Rien de neuf depuis le dernier mail
@endforelse




<p style="font-size: 0.8em">
  Vous recevez cet email car lors de votre inscription au groupe "{{$group->name}}", vous avez demandé à être tenu au courant de ses activités.
  <br/>
  Si vous ne souhaitez plus recevoir de message ou changer vos options d'abonnement, <a href="{{action('MembershipController@settings', $group->id)}}">cliquez ici</a>.
</p>


@endsection
