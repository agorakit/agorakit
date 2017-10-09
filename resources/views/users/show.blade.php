@extends('app')

@section('content')


    @include('users.tabs')

    <div class="tab_content">

        <h1>{{ $user->name }}</h1>
        <div>
            {{trans('messages.registered')}} :  {{ $user->created_at->diffForHumans() }}
        </div>


        <div class="row">
            <div class="col-md-8">
                {!! filter($user->body) !!}
            </div>

            <div class="col-md-4">
                <img src="{{$user->cover()}}" class="img-circle img-responsive"/>
            </div>
        </div>


        @if ($user->groups->count() > 0)
            <div>
                <h4>{{trans('messages.groups')}}</h4>
                @foreach ($user->groups as $group)
                    <span class="label label-default"><a href="{{route('groups.show', $group)}}">{{$group->name}}</a></span>
                @endforeach
            </div>
        @endif


        <h4>{{trans('messages.recent_activity')}}</h4>
        @each('partials.activity-small', $activities, 'activity')



        <div class="row">
            <div class="col-md-4">
                @if ($user->discussions->count() > 0)
                    <div>
                        <h4>{{trans('messages.latest_discussions')}}</h4>
                        @foreach ($user->discussions()->orderBy('updated_at', 'desc')->take(10)->get() as $discussion)
                            <div>
                                <a href="{{route('groups.discussions.show', [$discussion->group, $discussion])}}">{{$discussion->name}}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                @if ($user->comments->count() > 0)
                    <div>
                        <h4>{{trans('messages.latest_comments')}}</h4>
                        @foreach ($user->comments()->orderBy('updated_at', 'desc')->take(10)->get() as $comment)
                            <div>
                                <a href="{{route('groups.discussions.show', [$comment->discussion->group, $comment->discussion])}}#comment_{{$comment->id}}">{{$comment->discussion->name}}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                @if ($user->files->count() > 0)
                    <div>
                        <h4>{{trans('messages.latest_files')}}</h4>
                        @foreach ($user->files()->orderBy('updated_at', 'desc')->take(10)->get() as $file)
                            <div>
                                <a href="{{route('groups.files.download', [$file->group, $file])}}">{{$file->name}}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
