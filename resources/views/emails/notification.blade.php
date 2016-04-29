@extends('emails.template')

@section('content')


<strong>Bonjour {{$user->name}},</strong>

<p>
  Voici les dernières nouvelles du groupe "<a href="{{action('GroupController@show', $group->id)}}">{{$group->name}}</a>"
</p>


@if ($actions->count() > 0)
<h2>Prochaines actions</h2>
@foreach($actions as $action)
<strong><a href="{{action('ActionController@show', [$group->id, $action->id])}}">{{$action->name}}</a></strong>
<p>{{ summary($action->body) }}</p>
<p>
  {{$action->start->format('d/m/Y H:i')}}
</p>
<p>
  {{trans('messages.location')}} : {{$action->location}}
</p>
<hr/>
@endforeach
@endif


@if ($discussions->count() > 0)
<h2>Dernières discussion et mises à jour</h2>
@foreach($discussions as $discussion)
<h3><a href="{{action('DiscussionController@show', [$group->id, $discussion->id])}}">{{$discussion->name}} </a></h3>
<p>
  {!! summary($discussion->body, 500) !!} ...
</p>

@foreach ($discussion->comments as $comment)

@if ($comment->created_at > $last_notification)
  <div style="border:1px solid #aaa; border-radius: 3px; margin-bottom: 1em; margin-left: 1em; padding: 1em">
<p style="font-size: 0.8em;">
  <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}#comment_{{$comment->id}}">{{$comment->user->name}}</a> ({{$comment->created_at->diffForHumans()}}):
  {!! summary($comment->body, 3000) !!}
</p>
</div>
@endif

@endforeach

<hr/>
@endforeach
@endif





@if ($users->count() > 0)
<h2>Nouveaux participants</h2>
@foreach($users as $user)
<a href="{{action('UserController@show', $user->id)}}">{{$user->name}}</a>
<br/>
@endforeach
@endif

@if ($files->count() > 0)
<h2>Nouveaux fichiers</h2>
@foreach($files as $file)
<a href="{{action('FileController@show', [$group->id, $file->id])}}"><img src="{{action('FileController@thumbnail', [$group->id, $file->id])}}" style="width: 24px; height:24px"/>{{$file->name}}</a>
<br/>
@endforeach
@endif




<p style="margin-top: 5em; font-size: 0.8em">
  Vous recevez cet email car lors de votre inscription au groupe "{{$group->name}}", vous avez demandé à être tenu au courant de ses activités.
  <br/>
  Si vous ne souhaitez plus recevoir de message ou changer vos options d'abonnement, <a href="{{action('MembershipController@settings', $group->id)}}">cliquez ici</a>.
</p>


@endsection
