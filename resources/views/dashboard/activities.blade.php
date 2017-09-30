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
            @foreach ($activities as $activity)
                    {{$activity->user->name}} {{$activity->action}} {{$activity->model->name}} {{$activity->created_at->diffForHumans()}} in {{$activity->group->name}}
                <br/>
            @endforeach

            {{$activities->render()}}

        @endisset


@endsection
