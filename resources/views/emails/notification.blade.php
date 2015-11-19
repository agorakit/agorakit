@extends('emails.template')

@section('content')


<p>Bonjour {{$user->name}},</p>

<p>
  Voici les dernières nouvelles du groupe "<a href="{{action('GroupController@show', $group->id)}}">{{$group->name}}</a>"
</p>


@if ($actions->count() > 0)
<h2>Prochaines actions</h2>
@foreach($actions as $action)
<strong><a href="{{action('ActionController@show', [$group->id, $action->id])}}">{{$action->name}}</a></strong>
<p>{{ $action->summary() }}</p>
<p>
{{$action->start->format('d/m/Y H:i')}}
</p>
<p>
{{trans('messages.location')}} : {{$action->location}}
</p>
<hr/>
@endforeach
@endif


<h2>Dernières discussion et mises à jour</h2>
@forelse($discussions as $discussion)
<strong><a href="{{action('DiscussionController@show', [$group->id, $discussion->id])}}">{{$discussion->name}} </a></strong>
<p>
  {{ $discussion->summary() }}
</p>
<br/>
@empty Rien de neuf depuis le dernier mail
@endforelse







<h2>Nouveaux membres</h2>
@forelse($users as $user)
<a href="{{action('UserController@show', $user->id)}}">{{$user->name}}</a>
<br/>
@empty Personne depuis le dernier mail
@endforelse


<h2>Nouveaux fichiers</h2>
@forelse($files as $file)
<a href="{{action('FileController@show', [$group->id, $file->id])}}">{{$file->name}}</a>
<br/>
@empty Rien de neuf depuis le dernier mail
@endforelse




<p style="font-size: 0.8em">
  Vous recevez cet email car lors de votre inscription au groupe "{{$group->name}}", vous avez demandé à être tenu au courant de ses activités.
  <br/>
  Si vous ne souhaitez plus recevoir de message ou changer vos options d'abonnement, <a href="{{action('MembershipController@settings', $group->id)}}">cliquez ici</a>.
</p>


@endsection
