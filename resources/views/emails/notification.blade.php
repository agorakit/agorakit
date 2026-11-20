@component('mail::message')


<strong>{{t('Hello')}} {{$user->name}},</strong>
<p>
{{t('Here is the latest news from')}} "<a  href="{{route('groups.show', $group)}}">{{$group->name}}</a>"
</p>


@if ($discussions->count() > 0)
<h1>{{t('Latest discussions')}}</h1>
@foreach($discussions as $discussion)
<div class="discussion" style="margin-bottom: 30px; border-bottom: 1px solid #aaa">
<h3><a  href="{{route('groups.discussions.show', [$group, $discussion])}}#unread">{{$discussion->name}} </a></h3>
<p>
@if ($discussion->comments->count() == 0)
<a href="{{ route('groups.discussions.show', [$group, $discussion]) }}#unread">{{$discussion->user->name}}</a> ({{$discussion->created_at->diffForHumans()}}):
{!! filter($discussion->body) !!}
@endif
</p>

@foreach ($discussion->comments as $comment)
@if ($comment->created_at > $last_notification)
<div style="border:1px solid #aaa; border-radius: 3px; margin-bottom: 1em; margin-left: 1em; padding: 1em">
<p>
<a  href="{{ route('groups.discussions.show', [$group, $discussion]) }}#comment_{{$comment->id}}">{{$comment->user->name}}</a> ({{$comment->created_at->diffForHumans()}}):
{!! filter($comment->body) !!}
</p>
</div>
@endif
@endforeach

@component('mail::button', ['url' => route('groups.discussions.show', [$group, $discussion]) . "#unread"])
{{t('Reply')}}
@endcomponent

</div>
@endforeach
@endif


@if ($events->count() > 0)
<h1>{{t('Next events')}}</h1>
@foreach($events as $event)
<div style="border-bottom: 1px solid #aaa; margin-bottom: 20px; padding-bottom: 20px">
<strong><a  href="{{route('groups.calendarevents.show', [$group, $event])}}">{{$event->name}}</a></strong>
<p>{!!filter($event->body) !!}</p>
{{$event->start->format('d/m/Y H:i')}} - {{$event->stop->format('H:i')}}
@if ($event->hasLocation()) , {{$event->locationDisplay('long')}}@endif
</div>
@endforeach
<br/>
<br/>
@endif



@if ($users->count() > 0)
<h1>{{t('Latest users')}}</h1>
@foreach($users as $new_user)
<a  href="{{route('users.show', $new_user)}}">{{$new_user->name}}</a>
<br/>
@endforeach

<br/>
<br/>
@endif


@if ($files->count() > 0)
<h1>{{t('Latest files')}}</h1>
@foreach($files as $file)
<a  href="{{route('groups.files.show', [$group, $file])}}"><img alt="" src="{{route('groups.files.thumbnail', [$group, $file])}}" style="width: 24px; height:24px"/>{{$file->name}}</a>
<br/>
@endforeach
<br/>
@endif


<div style="margin-top: 20px; text-align: center">
<img alt="" src="{{route('icon', 128)}}" width="128" height="128"/>
</div>


<p style="margin-top: 20px; font-size: 0.8em">
{{t('You receive this email from the group')}} "{{$group->name}}", {{t('Because you asked for it')}}.
<br/>
<a  href="{{action('GroupMembershipController@edit', $group)}}">
{{t('Click here to change your notification preferences or to unsubscribe completely')}}</a>.
</p>


@endcomponent
