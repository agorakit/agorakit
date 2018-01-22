@extends('app')

@section('content')


    @include('users.tabs')

    <div class="tab_content">

        <h1>{{ $user->name }}</h1>

        @if (Auth::user())
            <div>
                {{trans('messages.registered')}} :  {{ $user->created_at->diffForHumans() }}
            </div>


            <div class="row">
                <div class="col-md-8">
                    {!! filter($user->body) !!}
                </div>

                <div class="col-md-4">
                    <img src="{{$user->cover()}}" class="rounded-circle img-fluid"/>
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

        @endif

    </div>
@endsection
