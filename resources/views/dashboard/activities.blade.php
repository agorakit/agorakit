@extends('app')

@section('content')

    <div class="page_header">
        <h1><i class="fa fa-home"></i>
            {{Config::get('agorakit.name')}}
        </h1>
    </div>


    @include('dashboard.tabs')

    <div class="tab_content">

        @isset($activities)
            <ul class="list-group">
                @foreach ($activities as $activity)
                    <li class="list-group-item">
                        <span class="avatar"><img src="{{$activity->user->avatar()}}" class="img-circle"/></span>
                        <a href="{{action('UserController@show', $activity->user)}}">{{$activity->user->name}}</a>
                         {{trans($activity->action)}}
                         <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
                         {{$activity->created_at->diffForHumans()}}
                         in <a href="{{action('GroupController@show', $activity->group)}}">{{$activity->group->name}}</a>
                    </li>
                @endforeach

            </ul>
            {{$activities->render()}}

        @endisset


    @endsection
