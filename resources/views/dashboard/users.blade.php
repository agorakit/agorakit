@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.users') }}</h1>
    </div>

    <div class="tab_content">
        <div class="users_list">
            @if ($users)
                @foreach( $users->chunk(3) as $users_rows)
                    <div class="row">
                        @foreach( $users_rows as $user)
                            <div class="col-xs-12 col-md-4 col">
                                <div class="user">
                                    <a href="{{action('UserController@show', $user)}}">
                                        <img src="{{$user->avatar()}}" class="img-circle" style="float:right; width:60px; height:60px"/>
                                    </a>
                                    <strong><a href="{{action('UserController@show', $user)}}">{{$user->name}}</a></strong>
                                    <div class="summary">{{summary($user->body, 200)}}</div>
                                    @if ($user->groups->count() > 0)
                                        @foreach ($user->groups as $group)
                                            <span class="label label-default"><a href="{{action('GroupController@show', $group)}}">{{$group->name}}</a></span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                {!!$users->render()!!}
            @else
                {{trans('messages.nothing_yet')}}
            @endif
        </div>
    </div>

@endsection
