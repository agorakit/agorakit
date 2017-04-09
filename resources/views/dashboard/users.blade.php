@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.users') }}</h1>
    </div>

    @include('dashboard.tabs')

    <div class="tab_content">
        <div class="users_list">
            @if ($users)
                <table class="table table-hover special">
                    <tr>
                        <th style="width: 50px"></th>
                        <th style="width:80%;">{{ trans('messages.name') }}</th>
                        <th style="min-width:150px;">{{ trans('messages.registration_time') }}</th>
                    </tr>

                    @foreach( $users as $user )
                        <tr>


                            <td>
                                <a href="{{ action('UserController@show', $user->id) }}"> <span class="avatar"><img src="{{$user->avatar()}}" class="img-circle"/></span></a>

                            </td>

                            <td class="content">
                                <a href="{{ action('UserController@show', $user->id) }}">
                                    <span class="name"> {{ $user->name }}</span>
                                    <span class="summary">{{summary($user->body, 200)}}</span>
                                </a>
                                <br/>
                                <span class="summary">
                                    @if ($user->groups->count() > 0)
                                        @foreach ($user->groups as $group)
                                            <span class="label label-default"><a href="{{action('GroupController@show', $group)}}">{{$group->name}}</a></span>
                                        @endforeach
                                    @endif
                                </span>
                            </td>



                            <td style="font-size: 10px;">
                                <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                            </td>



                        </tr>
                    @endforeach
                </table>

                {!! $users->render() !!}

            @else
                {{trans('messages.nothing_yet')}}
            @endif
        </div>
    </div>

@endsection
