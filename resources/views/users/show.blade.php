@extends('app')

@section('content')


    @include('partials.usertab')

    <div class="tab_content">

        <h1>{{ $user->name }}</h1>
        <div>
            {{trans('messages.registered')}} :  {{ $user->created_at->diffForHumans() }}
        </div>

        <div>
            <img src="{{$user->cover()}}" class="img-rounded"/>
        </div>

        <div>
            {!! filter($user->body) !!}
        </div>


        @if ($user->groups->count() > 0)
            <div>
                <h4>{{trans('messages.groups')}}</h4>
                @foreach ($user->groups as $group)
                    <span class="label label-default"><a href="{{action('GroupController@show', $group)}}">{{$group->name}}</a></span>
                @endforeach
            </div>
        @endif

        @if ($user->discussions->count() > 0)
            <div>
                <h4>{{trans('messages.latest_discussions')}}</h4>
                @foreach ($user->discussions()->orderBy('updated_at', 'desc')->take(10)->get() as $discussion)
                    <div>
                        <a href="{{action('DiscussionController@show', [$discussion->group, $discussion])}}">{{$discussion->name}}</a>
                    </div>
                @endforeach
            </div>
        @endif


        @if ($user->files->count() > 0)
            <div>
                <h4>{{trans('messages.latest_files')}}</h4>
                @foreach ($user->files()->orderBy('updated_at', 'desc')->take(10)->get() as $file)
                    <div>
                        <a href="{{action('FileController@show', [$file->group, $file])}}">{{$file->name}}</a>
                    </div>
                @endforeach
            </div>
        @endif



    </div>
@endsection
