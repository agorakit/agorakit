@extends('app')

@section('content')

    <div class="tab_content">

        <h1>{{trans('messages.your_search_for')}}<strong>{{$query}}</strong></h1>


        @if ($groups->count() > 0)
            <h2>{{trans('messages.groups')}}</h2>
        @endif
        @foreach ($groups as $group)
            <div class="result">
                <h4><a href="{{$group->link()}}">{{$group->name}}</a></h4>
                {{summary($group->body)}}
            </div>
        @endforeach

        @if ($discussions->count() > 0)
            <h2>{{trans('messages.discussions')}}</h2>
        @endif
        @foreach ($discussions as $discussion)
            <div class="result">
                <h4><a href="{{$discussion->link()}}">{{$discussion->name}}</a></h4>
                {{summary($discussion->body)}}
            </div>
        @endforeach


        @if ($actions->count() > 0)
            <h2>{{trans('messages.actions')}}</h2>
        @endif
        @foreach ($actions as $action)
            <div class="result">
                <h4><a href="{{$action->link()}}">{{$action->name}}</a></h4>
                {{summary($action->body)}}
            </div>
        @endforeach


        @if ($users->count() > 0)
            <h2>{{trans('messages.users')}}</h2>
        @endif
        @foreach ($users as $user)
            <div class="result">
                <h4><a href="{{$user->link()}}">{{$user->name}}</a></h4>
                {{summary($user->body)}}
            </div>
        @endforeach

        @if ($comments->count() > 0)
            <h2>{{trans('messages.comments')}}</h2>
        @endif
        @foreach ($comments as $comment)
            <div class="result">
                <h4><a href="{{$comment->link()}}">{{$comment->discussion->name}}</a></h4>
                {{summary($comment->body)}}
            </div>
        @endforeach


        @if ($files->count() > 0)
            <h2>{{trans('messages.files')}}</h2>
        @endif
        @foreach ($files as $file)
            <div class="result">
                <h4><a href="{{$file->link()}}">{{$file->name}}</a></h4>
            </div>
        @endforeach


    </div>

@endsection
