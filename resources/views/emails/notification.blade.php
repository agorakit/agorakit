@component('mail::message')


<strong>{{trans('messages.hello')}} {{$user->name}},</strong>
<p>
{{trans('messages.here_are_the_latest_news_of')}} "<a  href="{{route('groups.show', $group)}}">{{$group->name}}</a>"
</p>


@if ($discussions->count() > 0)
<h1>{{trans('messages.latest_discussions')}}</h1>
@foreach($discussions as $discussion)
<div class="discussion" style="margin-bottom: 30px; border-bottom: 1px solid #aaa">
<h3><a  href="{{route('groups.discussions.show', [$group, $discussion])}}">{{$discussion->name}} </a></h3>
<p>
@if ($discussion->comments->count() == 0)
<a href="{{ route('groups.discussions.show', [$group, $discussion]) }}">{{$discussion->user->name}}</a> ({{$discussion->created_at->diffForHumans()}}):
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

@component('mail::button', ['url' => route('groups.discussions.show', [$group, $discussion])])
{{trans('messages.reply')}}
@endcomponent

</div>
@endforeach
@endif


@if ($actions->count() > 0)
<h1>{{trans('messages.next_actions')}}</h1>
@foreach($actions as $action)
<div style="border-bottom: 1px solid #aaa; margin-bottom: 20px; padding-bottom: 20px">
<strong><a  href="{{route('groups.actions.show', [$group, $action])}}">{{$action->name}}</a></strong>
<p>{!!filter($action->body) !!}</p>
{{$action->start->format('d/m/Y H:i')}} - {{$action->stop->format('H:i')}}
@if ($action->location) , {{$action->location}}@endif
</div>
@endforeach
<br/>
<br/>
@endif



@if ($users->count() > 0)
<h1>{{trans('messages.latest_users')}}</h1>
@foreach($users as $new_user)
<a  href="{{route('users.show', $new_user)}}">{{$new_user->name}}</a>
<br/>
@endforeach

<br/>
<br/>
@endif


@if ($files->count() > 0)
<h1>{{trans('messages.latest_files')}}</h1>
@foreach($files as $file)
<a  href="{{route('groups.files.show', [$group, $file])}}"><img src="{{route('groups.files.thumbnail', [$group, $file])}}" style="width: 24px; height:24px"/>{{$file->name}}</a>
<br/>
@endforeach
<br/>
@endif


<div style="margin-top: 20px; text-align: center">
<img src="{{route('icon', 128)}}" width="128" height="128"/>
</div>


<p style="margin-top: 20px; font-size: 0.8em">
{{trans('messages.you_receive_this_email_from_the_group')}} "{{$group->name}}", {{trans('messages.because_you_asked_for_it')}}.
<br/>
<a  href="{{action('GroupMembershipController@edit', $group)}}">
{{trans('Click here to change your notification preferences or to unsubscribe completely')}}</a>.
</p>


@endcomponent
