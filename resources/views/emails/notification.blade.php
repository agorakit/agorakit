@extends('emails.template')
@section('content')

    <strong>{{trans('messages.hello')}} {{$user->name}},</strong>
    <p>
        {{trans('messages.here_are_the_latest_news_of')}} "<a href="{{action('GroupController@show', $group->id)}}">{{$group->name}}</a>"
    </p>


    @if ($discussions->count() > 0)
        <h2>{{trans('messages.latest_discussions')}}</h2>
        @foreach($discussions as $discussion)
            <h3><a href="{{action('DiscussionController@show', [$group->id, $discussion->id])}}">{{$discussion->name}} </a></h3>
            <p>
                @if ($discussion->comments->count() > 0)
                    {{ summary($discussion->body, 1000) }}
                @else
                    {!! filter($discussion->body) !!}
                @endif
            </p>

            @foreach ($discussion->comments as $comment)
                @if ($comment->created_at > $last_notification)
                    <div style="border:1px solid #aaa; border-radius: 3px; margin-bottom: 1em; margin-left: 1em; padding: 1em">
                        <p>
                            <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}#comment_{{$comment->id}}">{{$comment->user->name}}</a> ({{$comment->created_at->diffForHumans()}}):
                            {!! filter($comment->body) !!}
                        </p>
                    </div>
                @endif
            @endforeach

            <hr/>
        @endforeach
    @endif


    @if ($actions->count() > 0)
        <h2>{{trans('messages.next_actions')}}</h2>
        @foreach($actions as $action)
            <strong><a href="{{action('ActionController@show', [$group->id, $action->id])}}">{{$action->name}}</a></strong>
            <p>{!!filter($action->body) !!}</p>
            <p>
                {{$action->start->format('d/m/Y H:i')}}
            </p>
            <p>
                {{trans('messages.location')}} : {{$action->location}}
            </p>
            <hr/>
        @endforeach
    @endif








    @if ($users->count() > 0)
        <h2>{{trans('messages.latest_users')}}</h2>
        @foreach($users as $user)
            <a href="{{action('UserController@show', $user->id)}}">{{$user->name}}</a>
            <br/>
        @endforeach
    @endif

    @if ($files->count() > 0)
        <h2>{{trans('messages.latest_files')}}</h2>
        @foreach($files as $file)
            <a href="{{action('FileController@show', [$group->id, $file->id])}}"><img src="{{action('FileController@thumbnail', [$group->id, $file->id])}}" style="width: 24px; height:24px"/>{{$file->name}}</a>
            <br/>
        @endforeach
    @endif




    <p style="margin-top: 5em; font-size: 0.8em">
        {{trans('messages.you_receive_this_email_from_the_group')}} "{{$group->name}}", {{trans('messages.because_you_asked_for_it')}}.
        <br/>
        {{trans('messages.if_you_dont_want_news_anymore')}}, <a href="{{action('MembershipController@settings', $group->id)}}">{{trans('messages.click_here')}}</a>.
    </p>


@endsection
