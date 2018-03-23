@extends('app')

@section('content')


    @include('users.tabs')

    <div class="tab_content">

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
                        @unless ($group->isSecret())
                            <a href="{{ route('groups.show', [$group]) }}">
                                <span class="badge badge-secondary badge-group">
                                    @if ($group->isOpen())
                                        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                    @elseif ($group->isClosed())
                                        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                    @endif
                                    {{ $group->name }}
                                </span>
                            </a>
                        @endunless
                    @endforeach
                </div>
            @endif


            <h4>{{trans('messages.recent_activity')}}</h4>
            @each('partials.activity-small', $activities, 'activity')

        @endif

    </div>
@endsection
