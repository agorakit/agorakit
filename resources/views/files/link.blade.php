@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h3>
            @if (isset($file))
                <a href="{{ route('groups.files.index', $group->id) }}"><i class="fa fa-home" aria-hidden="true"></i></a>
                {{$file->name}}
            @endif
        </h3>


        <div>
            <ul>
                <li>{{trans('messages.author')}} : {{$file->user->name}}</li>
                <li>{{trans('messages.created')}} : {{$file->created_at}}</li>
            </ul>
        </div>

        <div>
            <a class="btn btn-primary" href="{{$file->path}}">
                {{trans('messages.visit_link')}} "{{$file->name}}"
            </a>
        </div>


    </div>

@endsection
